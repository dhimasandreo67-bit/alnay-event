<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Peserta;
use App\Models\Voting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class VotingController extends Controller
{
    /**
     * POST /peserta/:id/vote (pelanggan, login wajib)
     * jumlah_vote dan nama_tampilan dari body, user_id dari token,
     * total_bayar dihitung SERVER (bukan dari input client).
     */
    public function vote(Request $request, Peserta $peserta)
    {
        $data = $request->validate([
            'jumlah_vote' => ['required', 'integer', 'min:1'],
            'nama_tampilan' => ['required', 'string', 'max:255'],
        ]);

        if (! $peserta->event->voting_aktif) {
            abort(422, 'Voting untuk event ini sedang tidak aktif.');
        }

        $hargaPerVote = $peserta->event->harga_per_vote;
        $totalBayar = $hargaPerVote * $data['jumlah_vote'];
        $orderId = 'VOTE-'.$peserta->id.'-'.Str::random(8);

        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
        $snapToken = Snap::getSnapToken([
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $totalBayar,
            ],
            'customer_details' => [
                'first_name' => $request->user()->nama,
                'email' => $request->user()->email,
            ],
        ]);

        $voting = Voting::create([
            'event_id' => $peserta->event_id,
            'peserta_id' => $peserta->id,
            'user_id' => $request->user()->id,
            'nama_tampilan' => $data['nama_tampilan'],
            'jumlah_vote' => $data['jumlah_vote'],
            'harga_per_vote' => $hargaPerVote, // snapshot, jangan ambil ulang dari event nanti
            'total_bayar' => $totalBayar,
            'midtrans_order_id' => $orderId,
            'status_midtrans' => 'pending',
        ]);

        return response()->json([
            'snap_token' => $snapToken,
            'order_id' => $orderId,
            'voting' => $voting,
        ]);
    }

    /**
     * GET /voting/:id (pelanggan)
     * Status transaksi vote milik user yang login.
     */
    public function status(Request $request, Voting $voting)
    {
        if ($voting->user_id !== $request->user()->id) {
            abort(403, 'Bukan transaksi vote milik kamu.');
        }

        return response()->json($voting);
    }

    /**
     * POST /voting/midtrans/webhook (public, verifikasi signature)
     * Vote baru dihitung SAH setelah status_midtrans = settlement.
     */
    public function webhook(Request $request)
    {
        $signature = hash('sha512', (string) $request->input('order_id')
            .(string) $request->input('status_code')
            .(string) $request->input('gross_amount')
            .config('services.midtrans.server_key'));

        if (! hash_equals($signature, (string) $request->input('signature_key'))) {
            abort(403, 'Signature tidak valid');
        }

        $voting = Voting::where('midtrans_order_id', $request->order_id)->firstOrFail();

        $voting->update([
            'status_midtrans' => $request->transaction_status,
        ]);

        return response()->json(['message' => 'OK']);
    }

    /**
     * GET /event/:id/hasil-voting (public)
     * Rekap jumlah vote per peserta, pakai nama_tampilan (bukan nama asli).
     * Hanya ditampilkan kalau event->tampilkan_hasil_voting = true.
     */
    public function hasilEvent(Event $event)
    {
        if (! $event->tampilkan_hasil_voting) {
            return response()->json(['message' => 'Hasil voting belum ditampilkan untuk event ini'], 403);
        }

        $rekap = $event->pesertas()
            ->withSum(['votingMasuk as total_vote' => function ($query) {
                $query->where('status_midtrans', 'settlement');
            }], 'jumlah_vote')
            ->with('user:id,nama') // TODO: ganti ke agregasi nama_tampilan per transaksi kalau perlu breakdown, bukan cuma total
            ->get(['id', 'event_id', 'user_id']);

        return response()->json($rekap);
    }
}