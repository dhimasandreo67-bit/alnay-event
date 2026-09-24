<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Peserta;
use App\Models\Voting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class VotingController extends Controller
{
    // POST /peserta/{peserta}/vote
    public function vote(Request $request, Peserta $peserta)
    {
        $event = $peserta->event;

        // Syarat 1: voting event harus aktif
        if (! $event->voting_aktif) {
            abort(403, 'Voting untuk event ini belum/tidak aktif.');
        }

        // Syarat 2: peserta harus sudah berstatus diterima
        if ($peserta->status !== 'diterima') {
            abort(403, 'Peserta ini belum berstatus diterima, tidak bisa divote.');
        }

        // Syarat 3: hari ini harus dalam rentang tanggal event
        $hariIni = Carbon::now()->startOfDay();
        if ($hariIni->lt(Carbon::parse($event->tanggal_mulai)) || $hariIni->gt(Carbon::parse($event->tanggal_selesai))) {
            abort(403, 'Voting hanya bisa dilakukan selama periode event berlangsung.');
        }

        $data = $request->validate([
            'jumlah_vote' => ['required', 'integer', 'min:1'],
            'tipe_nama' => ['required', 'in:asli,samaran,anonim'],
            'nama_samaran' => ['required_if:tipe_nama,samaran', 'nullable', 'string', 'max:255'],
        ]);

        // Tentukan nama_tampilan sesuai pilihan user
        $namaTampilan = match ($data['tipe_nama']) {
            'asli' => $request->user()->nama,
            'samaran' => $data['nama_samaran'],
            'anonim' => 'Anonim',
        };

        // Snapshot harga per vote dari event SEKARANG, tidak ambil ulang nanti
        $hargaPerVote = (float) $event->harga_per_vote;
        $totalBayar = $hargaPerVote * (int) $data['jumlah_vote'];

        $orderId = 'VOTING-' . $peserta->id . '-' . Str::random(8);

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
            'event_id' => $event->id,
            'peserta_id' => $peserta->id,
            'user_id' => $request->user()->id,
            'nama_tampilan' => $namaTampilan,
            'jumlah_vote' => $data['jumlah_vote'],
            'harga_per_vote' => $hargaPerVote,
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

    // POST /voting/midtrans/webhook
    public function webhook(Request $request)
    {
        $signature = hash('sha512', (string) $request->input('order_id')
            . (string) $request->input('status_code')
            . (string) $request->input('gross_amount')
            . config('services.midtrans.server_key'));

        if (! hash_equals($signature, (string) $request->input('signature_key'))) {
            abort(403, 'Signature tidak valid');
        }

        $voting = Voting::where('midtrans_order_id', $request->order_id)->firstOrFail();

        $voting->update([
            'status_midtrans' => $request->transaction_status,
        ]);

        return response()->json(['message' => 'OK']);
    }

    // GET /event/{event}/hasil-voting
    public function hasilVoting(Request $request, Event $event)
    {
        $adminLogin = Auth::guard('admin')->check();

        if (! $event->tampilkan_hasil_voting && ! $adminLogin) {
            abort(403, 'Hasil voting untuk event ini belum ditampilkan ke publik.');
        }

        $hasil = Voting::where('event_id', $event->id)
            ->where('status_midtrans', 'settlement')
            ->selectRaw('peserta_id, SUM(jumlah_vote) as total_vote')
            ->groupBy('peserta_id')
            ->with(['peserta:id,user_id', 'peserta.user:id,nama']) // sesuaikan kolom nama di tabel peserta_event kalau beda
            ->orderByDesc('total_vote')
            ->get();

        return response()->json(['hasil_voting' => $hasil]);
    }

    // GET /peserta/{peserta}/voter  (admin only)
    public function voters(Peserta $peserta)
    {
        $voters = Voting::where('peserta_id', $peserta->id)
            ->where('status_midtrans', 'settlement')
            ->with('voter:id,nama,email')
            ->orderByDesc('tanggal_vote')
            ->get();

        return response()->json(['voters' => $voters]);
    }
}
