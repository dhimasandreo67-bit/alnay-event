<?php

namespace App\Http\Controllers;

use App\Models\PembayaranEvent;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class PembayaranController extends Controller
{
    // POST /peserta/{peserta}/bayar
    public function bayar(Request $request, Peserta $peserta)
    {
        // 1. Cek peserta milik user yang login
        if ((int) $peserta->user_id !== (int) $request->user()->id) {
            abort(403, 'Bukan pendaftaran milik kamu.');
        }

        // Cek sudah lunas?
        $sudahLunas = \App\Models\PembayaranEvent::where('peserta_id', $peserta->id)
            ->where('status_midtrans', 'settlement')
            ->exists();

        if ($sudahLunas) {
            return response()->json([
                'message' => 'Pendaftaran ini sudah dibayar.',
            ], 400);
        }

        // 3. Cek sudah pernah bayar lunas?
        $sudahBayar = PembayaranEvent::where('peserta_id', $peserta->id)
            ->where('status_midtrans', 'settlement')
            ->exists();

        if ($sudahBayar) {
            return response()->json([
                'message' => 'Pendaftaran ini sudah dibayar.',
            ], 400);
        }

        // 4. Ambil nominal (TANPA kode unik)
        $jumlah = (int) $peserta->event->biaya_pendaftaran;
        if ($jumlah <= 0) {
            return response()->json([
                'message' => 'Biaya pendaftaran belum diatur admin.',
            ], 400);
        }

        $orderId = 'PENDAFTARAN-' . $peserta->id . '-' . Str::random(8);

        // 5. Setup Midtrans
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized  = config('services.midtrans.is_sanitized');
        Config::$is3ds        = config('services.midtrans.is_3ds');

        try {
            $snapToken = Snap::getSnapToken([
                'transaction_details' => [
                    'order_id'     => $orderId,
                    'gross_amount' => $jumlah,
                ],
                'customer_details' => [
                    'first_name' => $request->user()->nama,
                    'email'      => $request->user()->email,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal membuat transaksi: ' . $e->getMessage(),
            ], 500);
        }

        // 6. Simpan record pembayaran (pending)
        $pembayaran = PembayaranEvent::where('peserta_id', $peserta->id)
            ->where('status_midtrans', 'pending')
            ->latest('id')
            ->first();

        if (!$pembayaran) {
            $pembayaran = PembayaranEvent::create([
                'peserta_id'        => $peserta->id,
                'jumlah'            => $jumlah,
                'tarif_dasar'       => $jumlah,
                'midtrans_order_id' => $orderId,
                'status_midtrans'   => 'pending',
                'metode'            => null,
                'tanggal_bayar'     => null,
            ]);
        } else {
            $pembayaran->update(['midtrans_order_id' => $orderId]);
        }

        return response()->json([
            'snap_token' => $snapToken,
            'order_id'   => $orderId,
            'pembayaran' => $pembayaran,
        ]);
    }

    // POST /peserta/midtrans/webhook
    public function webhook(Request $request)
    {
        \Log::info('Midtrans webhook received', $request->all());

        if (!$request->filled('order_id') || !$request->filled('signature_key')) {
            return response()->json(['message' => 'OK — test notification'], 200);
        }

        // 1. Verifikasi signature Midtrans
        $signature = hash(
        'sha512',
        $request->order_id .
        $request->status_code .
        $request->gross_amount .
        config('services.midtrans.server_key')
    );

    if (!hash_equals($signature, (string) $request->input('signature_key'))) {
        \Log::warning('Midtrans webhook: signature tidak valid', [
            'calculated' => $signature,
            'received'   => $request->signature_key,
            'order_id'   => $request->order_id,
        ]);
        return response()->json(['message' => 'Signature tidak valid'], 403);
    }

    // 2. Cari pembayaran
    $pembayaran = PembayaranEvent::where('midtrans_order_id', $request->order_id)
        ->first();

    if (!$pembayaran) {
        \Log::warning('Midtrans webhook: order_id tidak ditemukan', [
            'order_id' => $request->order_id,
        ]);
        return response()->json(['message' => 'Order tidak ditemukan'], 200);
    }


    $pembayaran->update([
        'midtrans_transaction_id' => $request->transaction_id,
        'status_midtrans'         => $request->transaction_status,
        'metode'                  => $request->payment_type,
        'tanggal_bayar'           => in_array($request->transaction_status, ['settlement', 'capture'])
            ? now()
            : $pembayaran->tanggal_bayar,
    ]);

    // 4. ✅ TRIGGER: hanya kalau settlement / capture
    if (in_array($request->transaction_status, ['settlement', 'capture'])) {
        $peserta = $pembayaran->peserta;
        if ($peserta) {
            app(\App\Services\PesertaAlurService::class)->setelahBayar($peserta);
        }
    }

    return response()->json(['message' => 'OK']);
}

    // GET /peserta/{peserta}/pembayaran
    public function show(Request $request, Peserta $peserta)
    {
        if ((int) $peserta->user_id !== (int) $request->user()->id) {
            abort(403, 'Bukan pendaftaran milik kamu.');
        }

        // Cek status — boleh bayar kalau belum lunas
        $sudahLunas = \App\Models\PembayaranEvent::where('peserta_id', $peserta->id)
            ->where('status_midtrans', 'settlement')
            ->exists();

        if ($sudahLunas) {
            return redirect()
                ->route('profil-user')
                ->with('status', 'Pendaftaran ini sudah dibayar.');
        }

        $semuaPembayaran = $peserta->pembayaran instanceof \Illuminate\Support\Collection
            ? $peserta->pembayaran
            : collect([$peserta->pembayaran])->filter();

        $pembayaran = $semuaPembayaran->where('status_midtrans', 'settlement')->sortByDesc('id')->first()
            ?? $semuaPembayaran->sortByDesc('id')->first();

        if ($pembayaran && $pembayaran->status_midtrans === 'settlement') {
            return redirect()
                ->route('profil-user')
                ->with('status', 'Pendaftaran ini sudah dibayar.');
        }

        return view('pembayaran.checkout', [
            'peserta'    => $peserta,
            'event'      => $peserta->event,
            'pembayaran' => $pembayaran,
            'batasWaktu' => now()->addHours(2),
            'jumlah'     => (int) ($peserta->event->biaya_pendaftaran ?? 0),
        ]);
    }

    // POST /peserta/{peserta}/charge
    public function charge(Request $request, Peserta $peserta)
    {
        if ((int) $peserta->user_id !== (int) $request->user()->id) {
            return response()->json(['message' => 'Bukan pendaftaran milik kamu.'], 403);
        }

        // Cek sudah lunas? — kalau sudah, nggak boleh charge lagi
        $sudahLunas = \App\Models\PembayaranEvent::where('peserta_id', $peserta->id)
            ->where('status_midtrans', 'settlement')
            ->exists();

        if ($sudahLunas) {
            return response()->json(['message' => 'Pendaftaran ini sudah dibayar.'], 400);
        }

        $request->validate([
            'payment_method' => ['required', 'in:qris,bri_va'],
        ]);

        $sudahBayar = PembayaranEvent::where('peserta_id', $peserta->id)
            ->where('status_midtrans', 'settlement')
            ->exists();

        if ($sudahBayar) {
            return response()->json(['message' => 'Pendaftaran ini sudah dibayar.'], 400);
        }

        $jumlah = (int) $peserta->event->biaya_pendaftaran;
        if ($jumlah <= 0) {
            return response()->json(['message' => 'Biaya pendaftaran belum diatur admin.'], 400);
        }

        $pembayaran = PembayaranEvent::where('peserta_id', $peserta->id)
            ->where('status_midtrans', 'pending')
            ->latest('id')
            ->first();

        if ($pembayaran
                && $pembayaran->payment_method === $request->payment_method
                && (
                    ($request->payment_method === 'qris' && $pembayaran->qr_string)
                    || ($request->payment_method === 'bri_va' && $pembayaran->va_number)
                )
            ) {
                return response()->json([
                    'redirect_url' => route('peserta.registrasi.instruksi', $peserta->id),
                ]);
            }

        $orderId = 'PENDAFTARAN-' . $peserta->id . '-' . Str::random(8);

        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized  = config('services.midtrans.is_sanitized');
        Config::$is3ds        = config('services.midtrans.is_3ds');

        try {
            $payload = [
                'payment_type' => $request->payment_method === 'qris' ? 'qris' : 'bank_transfer',
                'transaction_details' => [
                    'order_id'     => $orderId,
                    'gross_amount' => $jumlah,
                ],
                'customer_details' => [
                    'first_name' => $request->user()->nama,
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
                    'message' => 'Gagal request ke Midtrans: ' . ($data['status_message'] ?? 'Unknown'),
                    'debug'   => $data,
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }

        $qrString = null;
        $qrUrl    = null;
        $vaNumber = null;

        if ($request->payment_method === 'qris') {
            $qrString = $data['actions'][0]['url'] ?? null;
            if (isset($data['qr_string'])) {
                $qrString = $data['qr_string'];
            }
            $qrUrl = collect($data['actions'] ?? [])->firstWhere('name', 'generate-qr-code')['url'] ?? null;
        } else {
            $vaNumber = $data['va_numbers'][0]['va_number'] ?? null;
        }

        if ($pembayaran) {
            $pembayaran->update([
                'midtrans_order_id'       => $orderId,
                'midtrans_transaction_id' => $data['transaction_id'] ?? null,
                'payment_method'          => $request->payment_method,
                'qr_string'               => $qrString,
                'qr_url'                  => $qrUrl,
                'va_number'               => $vaNumber,
            ]);
        } else {
            $pembayaran = PembayaranEvent::create([
                'peserta_id'              => $peserta->id,
                'jumlah'                  => $jumlah,
                'tarif_dasar'             => $jumlah,
                'midtrans_order_id'       => $orderId,
                'midtrans_transaction_id' => $data['transaction_id'] ?? null,
                'status_midtrans'         => 'pending',
                'payment_method'          => $request->payment_method,
                'qr_string'               => $qrString,
                'qr_url'                  => $qrUrl,
                'va_number'               => $vaNumber,
            ]);
        }

        return response()->json([
            'redirect_url' => route('peserta.pembayaran.instruksi', $peserta->id),
        ]);
    }

    // GET /peserta/{peserta}/pembayaran/instruksi
    public function instruksi(Request $request, Peserta $peserta)
    {
        if ((int) $peserta->user_id !== (int) $request->user()->id) {
            abort(403);
        }

        $pembayaran = PembayaranEvent::where('peserta_id', $peserta->id)
            ->where('status_midtrans', 'pending')
            ->latest('id')
            ->first();

        if (!$pembayaran) {
            return redirect()->route('peserta.pembayaran', $peserta->id)
                ->with('error', 'Belum ada pembayaran pending.');
        }

        return view('pembayaran.instruksi', [
            'peserta'    => $peserta,
            'event'      => $peserta->event,
            'pembayaran' => $pembayaran,
        ]);
    }

    // GET /peserta/{peserta}/pembayaran/status
    public function statusPembayaran(Request $request, Peserta $peserta)
    {
        if ((int) $peserta->user_id !== (int) $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $pembayaran = PembayaranEvent::where('peserta_id', $peserta->id)
            ->latest('id')
            ->first();

        $status = $pembayaran?->status_midtrans ?? 'none';

        return response()->json([
            'status'   => $status,
            'lunas'    => $status === 'settlement',
            'pending'  => in_array($status, ['pending', 'capture']),
            'gagal'    => in_array($status, ['deny', 'cancel', 'failure', 'expire']),
            'expired'  => $status === 'expire',
            'order_id' => $pembayaran?->midtrans_order_id,
        ]);
    }
}
