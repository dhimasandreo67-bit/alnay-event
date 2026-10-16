<?php

namespace App\Http\Controllers;

use App\Models\PembayaranRegistrasi;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Midtrans\Config;

class RegistrasiController extends Controller
{
    /**
     * GET /peserta/{peserta}/registrasi
     * Halaman checkout registrasi
     */
    public function show(Request $request, Peserta $peserta)
    {
        if ((int) $peserta->user_id !== (int) $request->user()->id) {
            abort(403, 'Bukan pendaftaran milik kamu.');
        }

        if ($peserta->status !== 'diterima') {
            return redirect()->route('profil-user')
                ->with('error', 'Pendaftaran belum diterima admin.');
        }

        // Cek sudah lunas?
        $sudahLunas = PembayaranRegistrasi::where('peserta_id', $peserta->id)
            ->where('status_midtrans', 'settlement')
            ->where('tipe', 'lunas')
            ->exists();

        if ($sudahLunas) {
            return redirect()->route('profil-user')
                ->with('status', 'Registrasi ini sudah lunas.');
        }

        $event = $peserta->event;
        $biayaRegis = (int) ($event->harga_registrasi ?? 0);
        $dpRegis = (int) ($event->harga_registrasi_dp ?? 0);

        if ($biayaRegis <= 0) {
            return redirect()->route('profil-user')
                ->with('error', 'Biaya registrasi belum diatur admin.');
        }

        // Cek pembayaran pending
        $pembayaran = PembayaranRegistrasi::where('peserta_id', $peserta->id)
            ->where('status_midtrans', 'pending')
            ->latest('id')
            ->first();

        return view('registrasi.checkout', [
            'peserta'      => $peserta,
            'event'        => $event,
            'pembayaran'   => $pembayaran,
            'biayaRegis'   => $biayaRegis,
            'dpRegis'      => $dpRegis > 0 ? $dpRegis : (int) round($biayaRegis / 2),
        ]);
    }

    /**
     * POST /peserta/{peserta}/registrasi/bayar
     * Pilih metode (lunas / cicil) → charge Midtrans
     */
    public function bayar(Request $request, Peserta $peserta)
    {
        if ((int) $peserta->user_id !== (int) $request->user()->id) {
            return response()->json(['message' => 'Bukan pendaftaran milik kamu.'], 403);
        }

        if ($peserta->status !== 'diterima') {
            return response()->json(['message' => 'Pendaftaran belum diterima admin.'], 403);
        }

        $request->validate([
            'metode'         => ['required', 'in:lunas,cicil,pelunasan'],
            'payment_method' => ['nullable', 'in:qris,bri_va'],
        ]);

        $event = $peserta->event;
        $biayaRegis = (int) ($event->harga_registrasi ?? 0);
        $dpRegis = (int) ($event->harga_registrasi_dp ?? 0);

        if ($dpRegis <= 0) {
            $dpRegis = (int) round($biayaRegis / 2);
        }

        // Tentukan tipe & jumlah
        $totalDibayar = PembayaranRegistrasi::where('peserta_id', $peserta->id)
            ->where('status_midtrans', 'settlement')
            ->sum('jumlah');

        $sisa = max($biayaRegis - $totalDibayar, 0);

        if ($sisa <= 0) {
            return response()->json(['message' => 'Registrasi sudah lunas.'], 400);
        }

        if ($request->metode === 'lunas') {
            $tipe = 'lunas';
            $jumlah = $sisa;
        } elseif ($request->metode === 'cicil') {
            // Cicilan pertama
            if ($totalDibayar > 0) {
                return response()->json(['message' => 'DP sudah dibayar. Gunakan pelunasan.'], 400);
            }
            $tipe = 'dp1';
            $jumlah = min($dpRegis, $sisa);
        } else {
            // pelunasan
            $tipe = 'dp2';
            $jumlah = $sisa;
        }

        // Cek pending yang sama
        $pembayaran = PembayaranRegistrasi::where('peserta_id', $peserta->id)
            ->where('status_midtrans', 'pending')
            ->latest('id')
            ->first();

        $orderId = 'REGISTRASI-' . $peserta->id . '-' . strtoupper($tipe) . '-' . Str::random(6);

        // Setup Midtrans
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized  = config('services.midtrans.is_sanitized');
        Config::$is3ds        = config('services.midtrans.is_3ds');

        $paymentMethod = $request->input('payment_method', 'bri_va');

        // Simpan / update record DULU
        if ($pembayaran) {
            $pembayaran->update([
                'tipe'              => $tipe,
                'jumlah'            => $jumlah,
                'tarif_dasar'       => $biayaRegis,
                'midtrans_order_id' => $orderId,
                'status_midtrans'   => 'pending',
                'payment_method'    => $paymentMethod,
                'qr_string'         => null,   // reset
                'qr_url'            => null,
                'va_number'         => null,
            ]);
        } else {
            $pembayaran = PembayaranRegistrasi::create([
                'peserta_id'        => $peserta->id,
                'tipe'              => $tipe,
                'jumlah'            => $jumlah,
                'tarif_dasar'       => $biayaRegis,
                'midtrans_order_id' => $orderId,
                'status_midtrans'   => 'pending',
                'payment_method'    => $paymentMethod,
            ]);
        }

        // LANGSUNG charge via prosesCharge — sesuai pilihan user
        try {
            $this->prosesCharge($peserta, $pembayaran, $paymentMethod);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal charge: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'order_id'     => $orderId,
            'redirect_url' => route('peserta.registrasi.instruksi', $peserta->id),
        ]);
    }
    /**
     * POST /peserta/{peserta}/registrasi/charge
     * Charge QRIS / VA
     */
    public function charge(Request $request, Peserta $peserta)
    {
        if ((int) $peserta->user_id !== (int) $request->user()->id) {
            return response()->json(['message' => 'Bukan pendaftaran milik kamu.'], 403);
        }

        $request->validate([
            'payment_method' => ['required', 'in:qris,bri_va'],
        ]);

        $pembayaran = PembayaranRegistrasi::where('peserta_id', $peserta->id)
            ->where('status_midtrans', 'pending')
            ->latest('id')
            ->first();

        if (!$pembayaran) {
            return response()->json(['message' => 'Belum ada transaksi pending.'], 400);
        }

        // Kalau sudah ada QR/VA dengan metode sama
        // ✅ Cek existing — per metode
        $metodeSama = $pembayaran->payment_method === $request->payment_method;
        $fieldAda = ($request->payment_method === 'qris' && $pembayaran->qr_string)
                || ($request->payment_method === 'bri_va' && $pembayaran->va_number);

        if ($metodeSama && $fieldAda) {
            return response()->json([
                'redirect_url' => route('peserta.registrasi.instruksi', $peserta->id),
            ]);
        }

        // ✅ Kalau metode BEDA — reset field lama
        if ($pembayaran->payment_method !== $request->payment_method) {
            $pembayaran->update([
                'qr_string' => null,
                'qr_url'    => null,
                'va_number' => null,
            ]);
        }

        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized  = config('services.midtrans.is_sanitized');
        Config::$is3ds        = config('services.midtrans.is_3ds');

        try {
            $payload = [
                'payment_type' => $request->payment_method === 'qris' ? 'qris' : 'bank_transfer',
                'transaction_details' => [
                    'order_id'     => $pembayaran->midtrans_order_id,
                    'gross_amount' => (int) $pembayaran->jumlah,
                ],
                'customer_details' => [
                    'first_name' => $request->user()->nama ?? $request->user()->name,
                    'email'      => $request->user()->email,
                ],
            ];

            if ($request->payment_method === 'bri_va') {
                $payload['bank_transfer'] = ['bank' => 'bri'];
            }

            $response = \Illuminate\Support\Facades\Http::withBasicAuth(
                config('services.midtrans.server_key'),
                ''
            )->post(
                config('services.midtrans.is_production')
                    ? 'https://api.midtrans.com/v2/charge'
                    : 'https://api.sandbox.midtrans.com/v2/charge',
                $payload
            );

            $data = $response->json();

            if (!$response->successful() || ($data['status_code'] ?? '') !== '201') {
                return response()->json([
                    'message' => 'Gagal request Midtrans: ' . ($data['status_message'] ?? 'Unknown'),
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }

        $qrString = null;
        $qrUrl    = null;
        $vaNumber = null;

        if ($request->payment_method === 'qris') {
            $qrString = $data['qr_string'] ?? null;
            $qrUrl = collect($data['actions'] ?? [])->firstWhere('name', 'generate-qr-code')['url'] ?? null;
        } else {
            $vaNumber = $data['va_numbers'][0]['va_number'] ?? null;
        }

        $pembayaran->update([
            'midtrans_transaction_id' => $data['transaction_id'] ?? null,
            'payment_method'          => $request->payment_method,
            'qr_string'               => $qrString,
            'qr_url'                  => $qrUrl,
            'va_number'               => $vaNumber,
        ]);

        return response()->json([
            'redirect_url' => route('peserta.registrasi.instruksi', $peserta->id),
        ]);
    }

    /**
     * GET /peserta/{peserta}/registrasi/instruksi
     */
    public function instruksi(Request $request, Peserta $peserta)
        {
            if ((int) $peserta->user_id !== (int) $request->user()->id) {
                abort(403);
            }

            $pembayaran = PembayaranRegistrasi::where('peserta_id', $peserta->id)
                ->where('status_midtrans', 'pending')
                ->latest('id')
                ->first();

            if (!$pembayaran) {
                return redirect()->route('profil-user')
                    ->with('error', 'Belum ada pembayaran pending.');
            }

        // ✅ Cek per metode — bukan cek 2 field sekaligus
            $perluCharge = false;

            if ($pembayaran->payment_method === 'qris' && !$pembayaran->qr_url) {
                $perluCharge = true;
            } elseif ($pembayaran->payment_method === 'bri_va' && !$pembayaran->va_number) {
                $perluCharge = true;
            } elseif (!$pembayaran->payment_method) {
                $perluCharge = true;
            }

            if ($perluCharge) {
                if (!$pembayaran->payment_method) {
                    return redirect()->route('peserta.registrasi.show', $peserta->id)
                        ->with('info', 'Silakan pilih metode pembayaran dulu.');
                }

                try {
                    $this->prosesCharge($peserta, $pembayaran, $pembayaran->payment_method);
                    $pembayaran->refresh();
                } catch (\Exception $e) {
                    return redirect()->route('profil-user')
                        ->with('error', 'Gagal charge: ' . $e->getMessage());
                }
            }

            return view('registrasi.instruksi', [
                'peserta'    => $peserta,
                'event'      => $peserta->event,
                'pembayaran' => $pembayaran,
            ]);
        }

/**
 * Helper: charge QRIS ke Midtrans
 */
/**
 * Helper: charge ke Midtrans — support QRIS & VA
 */
        private function prosesCharge(Peserta $peserta, PembayaranRegistrasi $pembayaran, string $paymentMethod = 'qris')
        {
            \Midtrans\Config::$serverKey    = config('services.midtrans.server_key');
            \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
            \Midtrans\Config::$is3ds        = config('services.midtrans.is_3ds');

            $payload = [
                'payment_type' => $paymentMethod === 'qris' ? 'qris' : 'bank_transfer',
                'transaction_details' => [
                    'order_id'     => $pembayaran->midtrans_order_id,
                    'gross_amount' => (int) $pembayaran->jumlah,
                ],
            ];

            if ($paymentMethod === 'bri_va') {
                $payload['bank_transfer'] = ['bank' => 'bri'];
            }

            $response = \Illuminate\Support\Facades\Http::withBasicAuth(
                config('services.midtrans.server_key'),
                ''
            )->post(
                config('services.midtrans.is_production')
                    ? 'https://api.midtrans.com/v2/charge'
                    : 'https://api.sandbox.midtrans.com/v2/charge',
                $payload
            );

            $data = $response->json();

            if (!$response->successful() || ($data['status_code'] ?? '') !== '201') {
                throw new \Exception('Gagal charge Midtrans: ' . ($data['status_message'] ?? 'Unknown'));
            }

            $qrString = null;
            $qrUrl    = null;
            $vaNumber = null;

            if ($paymentMethod === 'qris') {
                $qrString = $data['qr_string'] ?? null;
                $qrUrl = collect($data['actions'] ?? [])->firstWhere('name', 'generate-qr-code')['url'] ?? null;
            } else {
                $vaNumber = $data['va_numbers'][0]['va_number'] ?? null;
            }

            $pembayaran->update([
                'midtrans_transaction_id' => $data['transaction_id'] ?? null,
                'payment_method'          => $paymentMethod,
                'qr_string'               => $qrString,
                'qr_url'                  => $qrUrl,
                'va_number'               => $vaNumber,
            ]);
        }   

    /**
     * GET /peserta/{peserta}/registrasi/status
     */
    public function status(Request $request, Peserta $peserta)
    {
        if ((int) $peserta->user_id !== (int) $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $pembayaran = PembayaranRegistrasi::where('peserta_id', $peserta->id)
            ->latest('id')
            ->first();

        $status = $pembayaran?->status_midtrans ?? 'none';

        return response()->json([
            'status'   => $status,
            'lunas'    => $status === 'settlement' && $pembayaran->tipe === 'lunas',
            'pending'  => in_array($status, ['pending', 'capture']),
            'gagal'    => in_array($status, ['deny', 'cancel', 'failure', 'expire']),
            'order_id' => $pembayaran?->midtrans_order_id,
            'tipe'     => $pembayaran?->tipe,
        ]);
    }

    /**
     * POST /peserta/midtrans/webhook-registrasi
     */
    public function webhook(Request $request)
    {
        \Log::info('Midtrans webhook REGISTRASI received', $request->all());

        if (!$request->filled('order_id') || !$request->filled('signature_key')) {
            return response()->json(['message' => 'OK — test notification'], 200);
        }

        // Verifikasi signature
        $signature = hash(
            'sha512',
            (string) $request->input('order_id')
                . (string) $request->input('status_code')
                . (string) $request->input('gross_amount')
                . config('services.midtrans.server_key')
        );

        if (!hash_equals($signature, (string) $request->input('signature_key'))) {
            \Log::warning('Webhook REGISTRASI: signature tidak valid');
            return response()->json(['message' => 'Signature tidak valid'], 403);
        }

        $pembayaran = PembayaranRegistrasi::where('midtrans_order_id', $request->order_id)
            ->first();

        if (!$pembayaran) {
            \Log::warning('Webhook REGISTRASI: order tidak ditemukan', [
                'order_id' => $request->order_id,
            ]);
            return response()->json(['message' => 'Order tidak ditemukan'], 200);
        }

        $pembayaran->update([
            'midtrans_transaction_id' => $request->transaction_id,
            'status_midtrans'         => $request->transaction_status,
            'metode'                  => $request->payment_type,
            'tanggal_bayar'           => $request->transaction_status === 'settlement'
                ? now()
                : $pembayaran->tanggal_bayar,
        ]);

        // Update status_registrasi peserta
        if ($request->transaction_status === 'settlement') {
            $peserta = $pembayaran->peserta;
            $event = $peserta->event;
            $biayaRegis = (int) ($event->harga_registrasi ?? 0);

            $totalDibayar = PembayaranRegistrasi::where('peserta_id', $peserta->id)
                ->where('status_midtrans', 'settlement')
                ->sum('jumlah');

            $statusReg = $totalDibayar <= 0
                ? 'belum'
                : ($biayaRegis > 0 && $totalDibayar >= $biayaRegis ? 'lunas' : 'dp');

            $peserta->update(['status_registrasi' => $statusReg]);
        }

        return response()->json(['message' => 'OK']);
    }
}