<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Peserta;
use App\Models\Voting;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;
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

    public function edit(?Event $event = null): View
    {
        $event ??= Event::query()->latest('id')->first();
 
        return view('admin.voting.settings', [
            'event' => $event,
            'events' => Event::query()->latest('id')->get(['id', 'nama']),
        ]);
    }
 
    /**
     * POST /admin/voting/settings/:event
     */
    public function update(Request $request, Event $event): RedirectResponse
    {
        $data = $request->validate([
            'voting_aktif' => ['nullable', 'boolean'],
            'format_hasil_voting' => ['required', 'in:angka,persentase,keduanya'],
            'voting_mulai' => ['nullable', 'date'],
            'voting_selesai' => ['nullable', 'date', 'after_or_equal:voting_mulai'],
            'auto_freeze_voting' => ['nullable', 'boolean'],
            'blackout_h2_voting' => ['nullable', 'boolean'],
            'metode_pembayaran' => ['nullable', 'array'],
            'rekening_bank' => ['nullable', 'string', 'max:100'],
            'rekening_nomor' => ['nullable', 'string', 'max:50'],
            'rekening_atas_nama' => ['nullable', 'string', 'max:255'],
        ]);
 
        $data['voting_aktif'] = $request->boolean('voting_aktif');
        $data['auto_freeze_voting'] = $request->boolean('auto_freeze_voting');
        $data['blackout_h2_voting'] = $request->boolean('blackout_h2_voting');
 
        $event->update($data);
 
        return redirect()
            ->route('admin.voting.settings', $event)
            ->with('success', 'Pengaturan event & voting berhasil disimpan.');
    }

    public function updateSettings(Request $request, Event $event)
    {
        $validated = $request->validate([
            'voting_aktif' => 'boolean',
            'voting_mulai' => 'nullable|date',
            'voting_selesai' => 'nullable|date|after_or_equal:voting_mulai',
            'auto_freeze_voting' => 'boolean',
            'blackout_h2_voting' => 'boolean',
            'format_hasil_voting' => 'required|in:angka,persentase,keduanya',
        ]);

        // Lakukan update data
        $event->update($validated);

        return redirect()->back()->with('success', 'Pengaturan voting berhasil diperbarui!');
    }

    /**
     * GET /admin/voters/{event?}
     * Halaman Daftar Voter - menampilkan semua data voting
     */
    public function voters(?Event $event = null): View
    {
        // Ambil event terbaru jika tidak ada parameter
        $event ??= Event::query()->latest('id')->first();

        // Base query untuk voting yang sudah lunas (settlement)
        $votingQuery = \App\Models\Voting::where('event_id', $event?->id ?? 0)
            ->where('status_midtrans', 'settlement')
            ->with(['peserta.user', 'user']);

        // Statistik
        $totalSuaraMasuk = (clone $votingQuery)->sum('jumlah_vote');
        $voterUnik = (clone $votingQuery)->distinct('user_id')->count('user_id');

        // Peserta terpopuler - gunakan withSum dengan relasi votingMasuk
        $pesertaTerpopuler = \App\Models\Peserta::where('event_id', $event?->id ?? 0)
            ->withSum(['voting as total_vote' => function ($q) {
                $q->where('status_midtrans', 'settlement');
            }], 'jumlah_vote')
            ->with('user:id,nama')
            ->orderByDesc('total_vote')
            ->first();

        // Suara terakhir masuk - gunakan id DESC karena tidak ada created_at
        $suaraTerakhir = (clone $votingQuery)->latest('id')->first();

        // List semua voting - gunakan id DESC
        $votings = $votingQuery
            ->latest('id')
            ->get()
            ->map(function ($voting) {
                // Format waktu dari id atau gunakan updated_at jika ada
                $waktu = '-';
                if (property_exists($voting, 'updated_at') && $voting->updated_at) {
                    $waktu = $voting->updated_at->format('H.i.s');
                } elseif (property_exists($voting, 'created_at') && $voting->created_at) {
                    $waktu = $voting->created_at->format('H.i.s');
                } else {
                    // Fallback: gunakan id sebagai indikator urutan
                    $waktu = '#' . $voting->id;
                }

                return [
                    'id' => $voting->id,
                    'waktu' => $waktu,
                    'nama_voter' => $voting->nama_tampilan ?? $voting->user?->nama ?? 'Anonim',
                    'no_hp' => $voting->user?->no_hp ?? $voting->user?->email ?? '-',
                    'peserta_id' => $voting->peserta_id,
                    'peserta_nama' => $voting->peserta?->user?->nama ?? 'Peserta dihapus',
                    'peserta_inisial' => strtoupper(substr($voting->peserta?->user?->nama ?? 'P', 0, 2)),
                    'jumlah_vote' => $voting->jumlah_vote,
                ];
            });

        // List peserta untuk filter dropdown
        $pesertaList = \App\Models\Peserta::where('event_id', $event?->id ?? 0)
            ->with('user:id,nama')
            ->orderBy('user_id')
            ->get(['id', 'user_id']);

        return view('admin.voters.index', [
            'event' => $event,
            'events' => Event::query()->latest('id')->get(['id', 'nama']),
            'totalSuaraMasuk' => $totalSuaraMasuk,
            'voterUnik' => $voterUnik,
            'pesertaTerpopuler' => $pesertaTerpopuler,
            'suaraTerakhir' => $suaraTerakhir ? ($suaraTerakhir->updated_at?->format('H.i.s') ?? '#' . $suaraTerakhir->id) : '-',
            'votings' => $votings,
            'pesertaList' => $pesertaList,
        ]);
    }

    public function leaderboard($event = null)
    {
        // 1. Ambil data Event berdasarkan ID
        if ($event) {
            $eventData = Event::findOrFail($event);
        } else {
            $eventData = Event::latest()->firstOrFail();
        }

        // 2. Query peserta aktif (status diterima & pembayaran settlement) + eager load user
        $rawPeserta = Peserta::where('event_id', $eventData->id)
            ->where('status', 'diterima')
            ->whereHas('pembayaran', function ($query) {
                $query->where('status_midtrans', 'settlement');
            })
            ->with(['user'])
            ->withCount('votingMasuk')
            ->orderByDesc('voting_masuk_count')
            ->get();

        // 3. Ringkasan Total Suara & Jumlah Peserta
        $totalSuara = $rawPeserta->sum('voting_masuk_count');
        $pesertaCount = $rawPeserta->count();

        // Variasi style avatar
        $warnaList = [
            'bg-[#edf3fb] text-[#42658c] border-[#d8e5f4]',
            'bg-[#f0e6cf] text-[#8a6d1f] border-[#d9bd79]',
            'bg-[#fff1e8] text-[#a96335] border-[#f1d5c4]',
            'bg-[#f0fdf4] text-[#166534] border-[#bbf7d0]',
        ];

        // 4. Format data array (Ambil nama dari $p->user->nama)
        $pesertas = $rawPeserta->map(function ($p, $index) use ($totalSuara, $warnaList) {
            // Ambil nama dari relasi User
            $nama = $p->user->nama ?? $p->nama ?? 'Tanpa Nama';
            $totalVote = $p->voting_masuk_count ?? 0;
            
            // Persentase Suara
            $persentase = $totalSuara > 0 ? round(($totalVote / $totalSuara) * 100, 1) : 0;

            // Buat Inisial Nama (contoh: "Tono Subagyo" -> "TS")
            $words = explode(' ', trim($nama));
            $inisial = strtoupper(substr($words[0], 0, 1));
            if (count($words) > 1) {
                $inisial .= strtoupper(substr(end($words), 0, 1));
            }

            return [
                'id'          => $p->id,
                'nama'        => $nama,
                'total_vote'  => $totalVote,
                'persentase'  => $persentase,
                'inisial'     => $inisial,
                'warna'       => $warnaList[$index % count($warnaList)],
            ];
        });

        // 5. Ambil Top 3 untuk Podium
        $peringkat1 = $pesertas->get(0);
        $peringkat2 = $pesertas->get(1);
        $peringkat3 = $pesertas->get(2);

        $persenPeringkat1 = $peringkat1['persentase'] ?? 0;

        // 6. Return ke View
        return view('admin.leaderboard.index', [
            'event'            => $eventData,
            'pesertas'         => $pesertas,
            'totalSuara'       => $totalSuara,
            'pesertaCount'     => $pesertaCount,
            'peringkat1'       => $peringkat1,
            'peringkat2'       => $peringkat2,
            'peringkat3'       => $peringkat3,
            'persenPeringkat1' => $persenPeringkat1,
        ]);
    }

    /**
     * Helper untuk warna berdasarkan jumlah vote
     */
    private function getColorForRank(int $votes): string
    {
        $colors = [
            'bg-gradient-to-br from-amber-100 to-amber-50 text-amber-700 border-amber-200', // Gold
            'bg-gradient-to-br from-blue-100 to-blue-50 text-blue-700 border-blue-200',    // Silver
            'bg-gradient-to-br from-orange-100 to-orange-50 text-orange-700 border-orange-200', // Bronze
            'bg-gradient-to-br from-purple-100 to-purple-50 text-purple-700 border-purple-200',
            'bg-gradient-to-br from-pink-100 to-pink-50 text-pink-700 border-pink-200',
            'bg-gradient-to-br from-indigo-100 to-indigo-50 text-indigo-700 border-indigo-200',
            'bg-gradient-to-br from-teal-100 to-teal-50 text-teal-700 border-teal-200',
        ];
        
        static $index = 0;
        $color = $colors[$index % count($colors)];
        $index++;
        return $color;
    }
}