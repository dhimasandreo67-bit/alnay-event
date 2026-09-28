<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Peserta;
use App\Models\Voting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\CoreApi;
use Midtrans\Snap;
use Midtrans\Notification;


class VotingController extends Controller
{
    /**
     * Memproses checkout vote & generate Midtrans Snap Token
     * POST /peserta/{peserta}/vote
     */
    /**
     * Memproses checkout vote & redirect ke halaman instruksi
     * POST /peserta/{peserta}/vote
     */
    /**
     * Memproses checkout vote & redirect ke halaman instruksi
     * POST /peserta/{peserta}/vote
     */
    public function vote(Request $request, Peserta $peserta)
    {
        $event = $peserta->event;

        if (! $event->voting_aktif) {
            return response()->json(['message' => 'Voting untuk event ini belum/tidak aktif.'], 403);
        }

        if ($peserta->status !== 'diterima') {
            return response()->json(['message' => 'Peserta ini belum berstatus diterima.'], 403);
        }

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'jumlah_vote' => ['required', 'integer', 'min:1'],
            'pesan_dukungan' => ['nullable', 'string', 'max:1000'],
            'metode_pembayaran' => ['required', 'string', 'in:qris,bri_va'],
        ]);

        // Batas waktu bayar (menit). 60 = 1 jam, 180 = 3 jam.
        $durasiMenit = 60;

        $user = $request->user();
        $namaTampilan = $data['nama'];
        $hargaPerVote = (float) $event->harga_per_vote;
        $totalBayar = (int) round($hargaPerVote * (int) $data['jumlah_vote']);
        $orderId = 'VOTING-' . $peserta->id . '-' . time() . '-' . Str::random(4);

        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized', true);
        Config::$is3ds = config('services.midtrans.is_3ds', true);

        $sekarang = Carbon::now();
        $expiredAt = $sekarang->copy()->addMinutes($durasiMenit);

        $customExpiry = [
            'order_time' => $sekarang->format('Y-m-d H:i:s O'),
            'expiry_duration' => $durasiMenit,
            'unit' => 'minute',
        ];

        $vaNumber = null;
        $qrUrl = null;

        try {
            if ($data['metode_pembayaran'] === 'qris') {
                $chargeResponse = CoreApi::charge([
                    'payment_type' => 'qris',
                    'transaction_details' => [
                        'order_id' => $orderId,
                        'gross_amount' => $totalBayar,
                    ],
                    'qris' => [
                        'acquirer' => 'gopay',
                    ],
                    'custom_expiry' => $customExpiry,
                ]);

                foreach ($chargeResponse->actions as $action) {
                    if ($action->name === 'generate-qr-code') {
                        $qrUrl = $action->url;
                    }
                }
            } else {
                $chargeResponse = CoreApi::charge([
                    'payment_type' => 'bank_transfer',
                    'transaction_details' => [
                        'order_id' => $orderId,
                        'gross_amount' => $totalBayar,
                    ],
                    'bank_transfer' => [
                        'bank' => 'bri',
                    ],
                    'custom_expiry' => $customExpiry,
                ]);

                if (isset($chargeResponse->va_numbers) && count($chargeResponse->va_numbers) > 0) {
                    $vaNumber = $chargeResponse->va_numbers[0]->va_number;
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal membuat transaksi via Midtrans: ' . $e->getMessage()
            ], 500);
        }

        try {
            $voting = DB::transaction(function () use (
                $event,
                $peserta,
                $user,
                $namaTampilan,
                $data,
                $hargaPerVote,
                $totalBayar,
                $orderId,
                $vaNumber,
                $qrUrl,
                $expiredAt,
                $sekarang
            ) {
                return Voting::create([
                    'event_id'          => $event->id,
                    'peserta_id'        => $peserta->id,
                    'user_id'           => $user?->id,
                    'nama_pemilih'      => $user?->nama ?? $data['nama'],
                    'email_pemilih'     => $data['email'],
                    'nama_tampilan'     => $namaTampilan,
                    'jumlah_vote'       => $data['jumlah_vote'],
                    'harga_per_vote'    => $hargaPerVote,
                    'total_bayar'       => $totalBayar,
                    'metode_pembayaran' => $data['metode_pembayaran'],
                    'va_number'         => $vaNumber,
                    'qr_url'            => $qrUrl,
                    'expired_at'        => $expiredAt,
                    'midtrans_order_id' => $orderId,
                    'status_midtrans'   => 'pending',
                    'pesan_dukungan'    => $data['pesan_dukungan'] ?? null,
                    'tanggal_vote'      => $sekarang,
                ]);
            });

            return response()->json([
                'status'       => 'success',
                'order_id'     => $orderId,
                'redirect_url' => "/voting/{$orderId}/instruksi"
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal membuat transaksi: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Menampilkan Halaman Instruksi Pembayaran (Custom Invoice Page)
     * GET /voting/{orderId}/instruksi
     */
    public function instruksiPembayaran($orderId)
    {
        $voting = Voting::with(['peserta.user', 'peserta.event'])
            ->where('midtrans_order_id', $orderId)
            ->firstOrFail();

        return view('voting.instruksi', [
            'voting'  => $voting,
            'peserta' => $voting->peserta,
            'event'   => $voting->peserta?->event ?? $voting->event
        ]);
    }

    /**
     * Cek status transaksi terkini (dipanggil tombol "Saya Sudah Bayar")
     * GET /voting/{orderId}/status
     */
    public function cekStatus($orderId)
    {
        $voting = Voting::where('midtrans_order_id', $orderId)->firstOrFail();

        return response()->json([
            'status_midtrans' => $voting->status_midtrans,
            'redirect_url' => $voting->status_midtrans === 'settlement'
                ? "/peserta/{$voting->peserta_id}"
                : null,
        ]);
    }

    /**
     * Webhook HTTP Callback dari Midtrans
     * POST /voting/midtrans/webhook
     */
    public function webhook(Request $request)
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');

        try {
            $notif = new Notification();

            $transactionStatus = $notif->transaction_status;
            $fraudStatus = $notif->fraud_status ?? null;
            $orderId = $notif->order_id;

            $voting = Voting::where('midtrans_order_id', $orderId)->first();

            if (! $voting) {
                return response()->json(['message' => 'Order ID tidak ditemukan.'], 404);
            }

            if ($voting->status_midtrans === 'settlement') {
                return response()->json(['message' => 'Transaksi sudah diselesaikan sebelumnya.'], 200);
            }

            $statusPembayaranBaru = 'pending';

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $statusPembayaranBaru = 'challenge';
                } else if ($fraudStatus == 'accept') {
                    $statusPembayaranBaru = 'settlement';
                }
            } else if ($transactionStatus == 'settlement') {
                $statusPembayaranBaru = 'settlement';
            } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                $statusPembayaranBaru = 'failed';
            } else if ($transactionStatus == 'pending') {
                $statusPembayaranBaru = 'pending';
            }

            DB::transaction(function () use ($voting, $statusPembayaranBaru) {
                $voting->update([
                    'status_midtrans' => $statusPembayaranBaru,
                ]);

                if ($statusPembayaranBaru === 'settlement') {
                    $peserta = $voting->peserta;
                    if ($peserta) {
                        $peserta->increment('total_vote', $voting->jumlah_vote);
                    }
                }
            });

            return response()->json(['message' => 'Status transaksi berhasil diperbarui.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Webhook error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Menampilkan Rekap Hasil Voting Per Event
     * GET /event/{event}/hasil-voting
     */
    public function hasilVoting(Event $event)
    {
        $adminLogin = Auth::guard('admin')->check();

        if (! $event->tampilkan_hasil_voting && ! $adminLogin) {
            return response()->json(['message' => 'Hasil voting untuk event ini belum ditampilkan ke publik.'], 403);
        }

        $pesertaList = Peserta::with('user')
            ->where('event_id', $event->id)
            ->where('status', 'diterima')
            ->orderBy('total_vote', 'desc')
            ->get();

        return response()->json([
            'event' => $event->only(['id', 'nama', 'tanggal_mulai', 'tanggal_selesai', 'voting_aktif', 'tampilkan_hasil_voting']),
            'peserta' => $pesertaList,
            'hasil_voting' => $pesertaList
        ]);
    }

    /**
     * Menampilkan Detail Peserta Tunggal (JSON API untuk Checkout)
     * GET /api/peserta/{peserta}
     */
    public function showPeserta(Peserta $peserta)
    {
        $peserta->load(['user', 'event']);

        return response()->json([
            'id' => $peserta->id,
            'nama' => $peserta->user->nama ?? $peserta->nama_panggung ?? 'Peserta',
            'foto' => $peserta->foto ? asset('storage/' . $peserta->foto) : ($peserta->user->foto_url ?? null),
            'kategori' => $peserta->kategori ?? $peserta->event->nama ?? '-',
            'harga_per_vote' => (float) ($peserta->event->harga_per_vote ?? 0),
            'total_vote' => (int) $peserta->total_vote,
            'event' => $peserta->event ? $peserta->event->only(['id', 'nama', 'harga_per_vote', 'tampilkan_hasil_voting']) : null,
        ]);
    }

    /**
     * Menampilkan Dukungan/Ulasan Terkini Per Peserta
     * GET /event/{event}/peserta/{peserta}/dukungan-terkini
     */
    public function dukunganTerkini(Event $event, Peserta $peserta)
    {
        $adminLogin = Auth::guard('admin')->check();

        if (! $event->tampilkan_hasil_voting && ! $adminLogin) {
            return response()->json(['message' => 'Dukungan terkini untuk event ini belum ditampilkan ke publik.'], 403);
        }

        if ($peserta->event_id !== $event->id) {
            return response()->json(['message' => 'Peserta ini bukan bagian dari event tersebut.'], 404);
        }

        $dukungan = Voting::where('event_id', $event->id)
            ->where('peserta_id', $peserta->id)
            ->where('status_midtrans', 'settlement')
            ->orderBy('tanggal_vote', 'desc')
            ->take(10)
            ->get(['id', 'nama_tampilan', 'jumlah_vote', 'pesan_dukungan', 'tanggal_vote']);

        return response()->json($dukungan);
    }

    /**
     * Khusus Admin: Melihat Daftar Pemilih (Voters) Per Peserta
     * GET /peserta/{peserta}/voter
     */
    public function voters(Peserta $peserta)
    {
        $voters = Voting::with('voter:id,nama,email')
            ->where('peserta_id', $peserta->id)
            ->where('status_midtrans', 'settlement')
            ->orderBy('tanggal_vote', 'desc')
            ->paginate(15);

        return response()->json($voters);
    }
}
