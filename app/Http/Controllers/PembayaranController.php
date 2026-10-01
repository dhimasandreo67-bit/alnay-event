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

        // 2. Cek status peserta — harus 'diterima' admin dulu
        if ($peserta->status !== 'diterima') {
            return response()->json([
                'message' => 'Pendaftaran belum diterima admin.',
            ], 403);
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

        // 4. Ambil nominal
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
        // Cek pembayaran pending yang sudah ada — pakai itu
        $pembayaran = PembayaranEvent::where('peserta_id', $peserta->id)
            ->where('status_midtrans', 'pending')
            ->latest('id')
            ->first();

        if (! $pembayaran) {
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
            // Update order_id baru ke record lama
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
        // 1. Verifikasi signature Midtrans
        $signature = hash(
            'sha512',
            (string) $request->input('order_id')
                . (string) $request->input('status_code')
                . (string) $request->input('gross_amount')
                . config('services.midtrans.server_key')
        );

        if (! hash_equals($signature, (string) $request->input('signature_key'))) {
            abort(403, 'Signature tidak valid');
        }

        // 2. Cari pembayaran
        $pembayaran = PembayaranEvent::where('midtrans_order_id', $request->order_id)
            ->firstOrFail();

        // 3. Update data pembayaran
        $pembayaran->update([
            'midtrans_transaction_id' => $request->transaction_id,
            'status_midtrans'         => $request->transaction_status,
            'metode'                  => $request->payment_type,
            'tanggal_bayar'           => $request->transaction_status === 'settlement'
                ? now()
                : $pembayaran->tanggal_bayar,
        ]);

        // 4. TIDAK mengubah status peserta.
        //    Status peserta tetap 'diterima' (dari admin).
        //    Untuk cek "peserta aktif", gunakan:
        //    peserta.status = 'diterima' AND pembayaran.status_midtrans = 'settlement'

        return response()->json(['message' => 'OK']);
    }

    /**
     * GET /peserta/{peserta}/pembayaran
     * Halaman pembayaran pendaftaran.
     */
    public function show(Request $request, Peserta $peserta)
    {
        // Cek peserta milik user yang login
        if ((int) $peserta->user_id !== (int) $request->user()->id) {
            abort(403, 'Bukan pendaftaran milik kamu.');
        }

        // Cek status peserta
        if ($peserta->status !== 'diterima') {
            return redirect()
                ->route('profil-user')
                ->with('error', 'Pendaftaran belum diterima admin.');
        }

        // Ambil pembayaran terbaik — prioritas settlement, lalu pending terbaru
        $semuaPembayaran = $peserta->pembayaran instanceof \Illuminate\Support\Collection
            ? $peserta->pembayaran
            : collect([$peserta->pembayaran])->filter();

        $pembayaran = $semuaPembayaran->where('status_midtrans', 'settlement')->sortByDesc('id')->first()
            ?? $semuaPembayaran->sortByDesc('id')->first();

        // Kalau sudah lunas → redirect ke profil
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

    /**
     * POST /peserta/{peserta}/charge
     * Request charge ke Midtrans Core API — QRIS atau VA BRI.
     */
    public function charge(Request $request, Peserta $peserta)
    {
        // Cek peserta milik user
        if ((int) $peserta->user_id !== (int) $request->user()->id) {
            return response()->json(['message' => 'Bukan pendaftaran milik kamu.'], 403);
        }

        // Cek status
        if ($peserta->status !== 'diterima') {
            return response()->json(['message' => 'Pendaftaran belum diterima admin.'], 403);
        }

        // Validasi metode
        $request->validate([
            'payment_method' => ['required', 'in:qris,bri_va'],
        ]);

        // Cek sudah bayar
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

        // Cek kalau sudah ada pending — pakai yang lama
        $pembayaran = PembayaranEvent::where('peserta_id', $peserta->id)
            ->where('status_midtrans', 'pending')
            ->latest('id')
            ->first();

        if ($pembayaran && $pembayaran->qr_string && $pembayaran->payment_method === $request->payment_method) {
            // Sudah ada pending dengan QR/VA — langsung redirect ke instruksi
            return response()->json([
                'redirect_url' => route('peserta.pembayaran.instruksi', $peserta->id),
            ]);
        }

        // Generate order ID
        $orderId = 'PENDAFTARAN-' . $peserta->id . '-' . Str::random(8);

        // Setup Midtrans
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized  = config('services.midtrans.is_sanitized');
        Config::$is3ds        = config('services.midtrans.is_3ds');

        try {
            // Request ke Midtrans Core API — via HTTP
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

            if (! $response->successful() || ($data['status_code'] ?? '') !== '201') {
                return response()->json([
                    'message' => 'Gagal request ke Midtrans: ' . ($data['status_message'] ?? 'Unknown'),
                    'debug' => $data,
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }

        // Extract QR / VA dari response
        $qrString = null;
        $qrUrl = null;
        $vaNumber = null;

        if ($request->payment_method === 'qris') {
            $qrString = $data['actions'][0]['url'] ?? null;  // QR image URL
            // Ambil dari 'qr_string' juga kalau ada
            if (isset($data['qr_string'])) {
                $qrString = $data['qr_string'];
            }
            // QR URL untuk ditampilkan
            $qrUrl = collect($data['actions'] ?? [])->firstWhere('name', 'generate-qr-code')['url'] ?? null;
        } else {
            $vaNumber = $data['va_numbers'][0]['va_number'] ?? null;
        }

        // Simpan / update pembayaran
        if ($pembayaran) {
            $pembayaran->update([
                'midtrans_order_id' => $orderId,
                'midtrans_transaction_id' => $data['transaction_id'] ?? null,
                'payment_method' => $request->payment_method,
                'qr_string' => $qrString,
                'qr_url' => $qrUrl,
                'va_number' => $vaNumber,
            ]);
        } else {
            $pembayaran = PembayaranEvent::create([
                'peserta_id' => $peserta->id,
                'jumlah' => $jumlah,
                'tarif_dasar' => $jumlah,
                'midtrans_order_id' => $orderId,
                'midtrans_transaction_id' => $data['transaction_id'] ?? null,
                'status_midtrans' => 'pending',
                'payment_method' => $request->payment_method,
                'qr_string' => $qrString,
                'qr_url' => $qrUrl,
                'va_number' => $vaNumber,
            ]);
        }

        return response()->json([
            'redirect_url' => route('peserta.pembayaran.instruksi', $peserta->id),
        ]);
    }

    /**
     * GET /peserta/{peserta}/pembayaran/instruksi
     * Halaman instruksi pembayaran — QRIS atau VA BRI.
     */
    public function instruksi(Request $request, Peserta $peserta)
    {
        // Cek milik user
        if ((int) $peserta->user_id !== (int) $request->user()->id) {
            abort(403);
        }

        // Ambil pembayaran pending
        $pembayaran = PembayaranEvent::where('peserta_id', $peserta->id)
            ->where('status_midtrans', 'pending')
            ->latest('id')
            ->first();

        if (! $pembayaran) {
            return redirect()->route('peserta.pembayaran', $peserta->id)
                ->with('error', 'Belum ada pembayaran pending.');
        }

        return view('pembayaran.instruksi', [
            'peserta' => $peserta,
            'event' => $peserta->event,
            'pembayaran' => $pembayaran,
        ]);
    }

    /**
     * GET /peserta/{peserta}/pembayaran/status
     * Cek status pembayaran (untuk polling).
     */
    public function statusPembayaran(Request $request, Peserta $peserta)
    {
        if ((int) $peserta->user_id !== (int) $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $pembayaran = PembayaranEvent::where('peserta_id', $peserta->id)
            ->latest('id')
            ->first();

        return response()->json([
            'status' => $pembayaran?->status_midtrans ?? 'none',
            'lunas' => $pembayaran?->status_midtrans === 'settlement',
        ]);
    }
}
