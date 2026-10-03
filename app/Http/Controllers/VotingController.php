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
use Illuminate\Support\Facades\Log;

class VotingController extends Controller
{

    /**
     * GET /admin/voters/{event?}
     * Halaman Daftar Voter
     */
    public function voters(?Event $event = null): View
    {
        // 1. Tentukan Event
        $event ??= Event::query()->latest('id')->first();

        if (!$event) {
            return view('admin.voters.index', ['event' => null]);
        }

        // 2. Ambil semua voting untuk event ini (yang sudah settlement)
        $votings = \App\Models\Voting::where('event_id', $event->id)
            ->where('status_midtrans', 'settlement')
            ->with(['peserta.user', 'user'])
            ->latest('id')
            ->get();

        // 3. Statistik dasar
        $totalSuara = $votings->sum('jumlah_vote');
        $voterUnik = $votings->whereNotNull('user_id')->pluck('user_id')->unique()->count();

        // 4. ✅ HITUNG PESERTA TERPOPULER
        $terpopuler = null;
        
        if ($votings->isNotEmpty()) {
            $groupedVotes = $votings->groupBy('peserta_id');
            
            $topPesertaId = null;
            $topTotal = 0;
            
            foreach ($groupedVotes as $pesertaId => $votes) {
                $total = $votes->sum('jumlah_vote');
                if ($total > $topTotal) {
                    $topTotal = $total;
                    $topPesertaId = $pesertaId;
                }
            }
            
            if ($topPesertaId) {
                $peserta = \App\Models\Peserta::with('user')->find($topPesertaId);
                $terpopuler = (object) [
                    'total' => $topTotal,
                    'peserta' => $peserta,
                ];
            }
        }

        // 5. Suara terakhir
        $suaraTerakhir = $votings->first();

        // 6. List peserta untuk dropdown filter
        $pesertaList = \App\Models\Peserta::where('event_id', $event->id)
            ->where('status', 'diterima')
            ->with('user')
            ->get();

        // 7. Kirim ke view
        return view('admin.voters.index', [
            'event'         => $event,
            'votings'       => $votings,
            'totalSuara'    => $totalSuara,
            'voterUnik'     => $voterUnik,
            'terpopuler'    => $terpopuler,
            'suaraTerakhir' => $suaraTerakhir,
            'pesertaList'   => $pesertaList,
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
        // Logika yang sama: URL > Session > Terbaru
        if (!$event) {
            $eventId = session('active_event_id');
            $event = $eventId ? Event::find($eventId) : Event::query()->latest('id')->first();
        } else {
            session(['active_event_id' => $event->id]);
        }

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

    public function leaderboard($event = null)
    {
        // 1. Ambil data Event berdasarkan ID (Aman dari error string)
        if ($event) {
            $eventData = \App\Models\Event::findOrFail($event);
        } else {
            $eventData = \App\Models\Event::latest()->first();
        }

        // Jika tidak ada event sama sekali, kembalikan view kosong
        if (!$eventData) {
            return view('admin.leaderboard.index', [
                'event'            => null,
                'pesertas'         => collect(),
                'totalSuara'       => 0,
                'pesertaCount'     => 0,
                'peringkat1'       => null,
                'peringkat2'       => null,
                'peringkat3'       => null,
                'persenPeringkat1' => 0,
            ]);
        }

        // 2. Query peserta aktif (status diterima & pembayaran settlement)
        $rawPeserta = \App\Models\Peserta::where('event_id', $eventData->id)
            ->where('status', 'diterima')
            ->whereHas('pembayaran', function ($query) {
                $query->where('status_midtrans', 'settlement');
            })
            ->with(['user'])
            ->get();

        // 3. Hitung total suara langsung dari tabel voting untuk setiap peserta
        $rawPeserta = $rawPeserta->map(function ($peserta) use ($eventData) {
            $totalVote = \App\Models\Voting::where('peserta_id', $peserta->id)
                ->where('event_id', $eventData->id)
                ->where('status_midtrans', 'settlement')
                ->sum('jumlah_vote');
            
            $peserta->total_vote = $totalVote;
            return $peserta;
        });

        // Sortir berdasarkan total vote (descending) dan reset index
        $rawPeserta = $rawPeserta->sortByDesc('total_vote')->values();

        // 4. Ringkasan Total Suara & Jumlah Peserta
        $totalSuara = \App\Models\Voting::where('event_id', $eventData->id)
            ->where('status_midtrans', 'settlement')
            ->sum('jumlah_vote');
            
        $pesertaCount = $rawPeserta->count();

        // Variasi style avatar
        $warnaList = [
            'bg-[#edf3fb] text-[#42658c] border-[#d8e5f4]',
            'bg-[#f0e6cf] text-[#8a6d1f] border-[#d9bd79]',
            'bg-[#fff1e8] text-[#a96335] border-[#f1d5c4]',
            'bg-[#f0fdf4] text-[#166534] border-[#bbf7d0]',
        ];

        // 5. Format data array untuk view
        $pesertas = $rawPeserta->map(function ($p, $index) use ($totalSuara, $warnaList) {
            $nama = $p->user->nama ?? $p->nama ?? 'Tanpa Nama';
            $totalVote = $p->total_vote ?? 0;
            
            $persentase = $totalSuara > 0 ? round(($totalVote / $totalSuara) * 100, 1) : 0;

            // Buat Inisial Nama
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

        // 6. Ambil Top 3 untuk Podium
        $peringkat1 = $pesertas->get(0);
        $peringkat2 = $pesertas->get(1);
        $peringkat3 = $pesertas->get(2);

        $persenPeringkat1 = $peringkat1['persentase'] ?? 0;

        // 7. Return ke View
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

    public function manualVote(Request $request)
    {
        $request->validate([
            'peserta_id'  => 'required|exists:peserta_event,id',
            'jumlah_vote' => 'required|integer|min:1',
            'nama_voter'  => 'nullable|string|max:255',
            'email_voter' => 'nullable|email|max:255',
        ]);

        try {
            $peserta = \App\Models\Peserta::findOrFail($request->peserta_id);
            
            // ✅ PERBAIKAN: Trim dulu, baru cek kosong
            $namaVoter = trim($request->input('nama_voter', ''));
            $emailVoter = trim($request->input('email_voter', ''));
            
            // Jika kosong setelah trim, gunakan default
            $namaVoter = $namaVoter !== '' ? $namaVoter : 'Seseorang';
            $emailVoter = $emailVoter !== '' ? $emailVoter : null;

            \App\Models\Voting::create([
                'event_id'          => $peserta->event_id,
                'peserta_id'        => $peserta->id,
                'jumlah_vote'       => $request->jumlah_vote,
                'tanggal_vote'      => now(),
                'nama_voter'        => $namaVoter,
                'email_voter'       => $emailVoter,
                'user_id'           => null,
                'nama_tampilan'     => $namaVoter,
                'harga_per_vote'    => $peserta->event?->harga_per_vote ?? 0,
                'total_bayar'       => ($peserta->event?->harga_per_vote ?? 0) * $request->jumlah_vote,
                'midtrans_order_id' => 'MANUAL-' . date('YmdHis') . '-' . $peserta->id,
                'status_midtrans'   => 'settlement',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Vote manual berhasil ditambahkan untuk ' . $namaVoter . '.'
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Manual Vote Failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $voting = Voting::findOrFail($id);
            
            // Hapus data voting
            $voting->delete();

            return response()->json([
                'success' => true,
                'message' => 'Vote berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            // Catat error ke log jika ada masalah
            Log::error('Delete Vote Failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus vote: ' . $e->getMessage()
            ], 500);
        }
    }
}