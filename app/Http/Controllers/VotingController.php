<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Peserta;
use App\Models\Voting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Midtrans\Config;
use Midtrans\CoreApi;
use Midtrans\Notification;

class VotingController extends Controller
{
    // ============================================================
    // METHOD DARI VERSI KITA (vote pakai CoreApi — BRI VA & QRIS)
    // ============================================================

    /**
     * POST /peserta/{peserta}/vote
     * Proses checkout vote & generate Midtrans (QRIS / BRI VA)
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
            'jumlah_vote' => ['required', 'integer', 'min:1', 'max:1000'],
            'pesan_dukungan' => ['nullable', 'string', 'max:1000'],
            'metode_pembayaran' => ['required', 'string', 'in:qris,bri_va'],
        ]);


        $durasiMenit = 60;
        $user = $request->user();
        // ===== PAGAR 2: Rate limit — max 1 transaksi per 5 detik =====
        if ($user) {
            $lastVote = Voting::where('event_id', $event->id)
                ->where('user_id', $user?->id)
                ->latest('tanggal_vote')
                ->first();
        }

        if ($lastVote && $lastVote->tanggal_vote) {
            $detikSejak = $lastVote->tanggal_vote->diffInSeconds(now());
            if ($detikSejak < 5) {
                $sisa = 5 - $detikSejak;
                return response()->json([
                    'message' => "Tunggu {$sisa} detik sebelum vote lagi."
                ], 429);
            }
        }
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
                    'qris' => ['acquirer' => 'gopay'],
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
                    'bank_transfer' => ['bank' => 'bri'],
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
            DB::transaction(function () use (
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
                $voting->update(['status_midtrans' => $statusPembayaranBaru]);

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
     * GET /api/peserta/{peserta}
     */
    public function showPeserta(Peserta $peserta)
    {
        $peserta->load(['user', 'event', 'jawabans.pertanyaan']);

        // Helper: ambil jawaban berdasarkan tag_tampilan, fallback ke regex label
        $cariJawaban = function (string $tag, string $pattern) use ($peserta) {
            $j = $peserta->jawabans->firstWhere('pertanyaan.tag_tampilan', $tag);
            if ($j && $j->jawaban) {
                return $j->jawaban;
            }
            $j = $peserta->jawabans->first(function ($x) use ($pattern) {
                return $x->pertanyaan
                    && preg_match($pattern, $x->pertanyaan->label ?? '')
                    && $x->jawaban;
            });
            return $j?->jawaban;
        };

        $nama = $cariJawaban('nama', '/nama/i')
            ?? $peserta->user->nama
            ?? 'Peserta';

        $bio = $cariJawaban('bio', '/bio|profil|tentang|deskripsi/i');
        $lokasi = $cariJawaban('lokasi', '/lokasi|domisili|kota|alamat/i');

        $fotoRaw = $cariJawaban('foto', '/foto|gambar|upload/i');
        $foto = $fotoRaw
            ? (str_starts_with($fotoRaw, 'http') ? $fotoRaw : asset('storage/' . $fotoRaw))
            : ($peserta->user->foto_url ?? null);

        return response()->json([
            'id'              => $peserta->id,
            'nama'            => $nama,
            'foto'            => $foto,
            'bio'             => $bio,
            'lokasi'          => $lokasi,
            'kategori'        => $peserta->event->nama ?? '-',
            'harga_per_vote'  => (float) ($peserta->event->harga_per_vote ?? 0),
            'total_vote'      => (int) $peserta->total_vote,
            'galeri_foto'     => $peserta->galeri_foto ?? [],
            'jawabans'        => $peserta->jawabans->map(fn($j) => [
                'label'   => $j->pertanyaan->label ?? '-',
                'tag'     => $j->pertanyaan->tag_tampilan ?? null,
                'jawaban' => $j->jawaban,
            ]),
            'event'           => $peserta->event ? $peserta->event->only([
                'id',
                'nama',
                'harga_per_vote',
                'tampilkan_hasil_voting',
                'format_hasil_voting'
            ]) : null,
        ]);
    }

    /**
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
     * GET /peserta/{peserta}/voter (JSON API)
     * RENAME dari voters() → votersByPeserta() agar tidak konflik dengan method admin
     */
    public function votersByPeserta(Peserta $peserta)
    {
        $voters = Voting::with('voter:id,nama,email')
            ->where('peserta_id', $peserta->id)
            ->where('status_midtrans', 'settlement')
            ->orderBy('tanggal_vote', 'desc')
            ->paginate(15);

        return response()->json($voters);
    }

    // ============================================================
    // METHOD DARI VERSI HAN (admin voting, voter, leaderboard)
    // ============================================================

    /**
     * GET /admin/voting/settings/{event?}
     */
    public function edit(?Event $event = null): View
    {
        $event ??= Event::query()->latest('id')->first();

        return view('admin.voting.settings', [
            'event' => $event,
            'events' => Event::query()->latest('id')->get(['id', 'nama']),
        ]);
    }

    /**
     * POST /admin/voting/settings/{event}
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

    /**
     * Varian update settings (dari han)
     */
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

        $event->update($validated);

        return redirect()->back()->with('success', 'Pengaturan voting berhasil diperbarui!');
    }

    /**
     * GET /admin/voters/{event?}
     * Halaman Daftar Voter (admin)
     */
    public function voters(?Event $event = null): View
    {
        $event ??= Event::query()->latest('id')->first();

        $votingQuery = Voting::where('event_id', $event?->id ?? 0)
            ->where('status_midtrans', 'settlement')
            ->with(['peserta.user', 'voter']);

        $totalSuaraMasuk = (clone $votingQuery)->sum('jumlah_vote');
        $voterUnik = (clone $votingQuery)->distinct('user_id')->count('user_id');

        $pesertaTerpopuler = Peserta::where('event_id', $event?->id ?? 0)
            ->withSum(['voting as total_vote' => function ($q) {
                $q->where('status_midtrans', 'settlement');
            }], 'jumlah_vote')
            ->with('user:id,nama')
            ->orderByDesc('total_vote')
            ->first();

        $suaraTerakhir = (clone $votingQuery)->latest('id')->first();

        $votings = $votingQuery
            ->latest('id')
            ->get()
            ->map(function ($voting) {
                $waktu = '-';
                if (property_exists($voting, 'updated_at') && $voting->updated_at) {
                    $waktu = $voting->updated_at->format('H.i.s');
                } elseif (property_exists($voting, 'created_at') && $voting->created_at) {
                    $waktu = $voting->created_at->format('H.i.s');
                } else {
                    $waktu = '#' . $voting->id;
                }

                return [
                    'id' => $voting->id,
                    'waktu' => $waktu,
                    'nama_voter' => $voting->nama_tampilan ?? $voting->voter?->nama ?? 'Anonim',
                    'no_hp' => $voting->voter?->no_hp ?? $voting->voter?->email ?? '-',
                    'peserta_id' => $voting->peserta_id,
                    'peserta_nama' => $voting->peserta?->user?->nama ?? 'Peserta dihapus',
                    'peserta_inisial' => strtoupper(substr($voting->peserta?->user?->nama ?? 'P', 0, 2)),
                    'jumlah_vote' => $voting->jumlah_vote,
                ];
            });

        $pesertaList = Peserta::where('event_id', $event?->id ?? 0)
            ->with('user:id,nama')
            ->orderBy('user_id')
            ->get(['id', 'user_id']);

        return view('admin.voters.index', [
            'event' => $event,
            'events' => Event::query()->latest('id')->get(['id', 'nama']),
            'totalSuara' => $totalSuaraMasuk,
            'voterUnik' => $voterUnik,
            'terpopuler' => $pesertaTerpopuler,
            'suaraTerakhir' => $suaraTerakhir,
            'votings' => $votings,
            'pesertaList' => $pesertaList,
        ]);
    }

    /**
     * GET /admin/leaderboard/{event?}
     */
    public function leaderboard($event = null)
    {
        if ($event) {
            $eventData = Event::findOrFail($event);
        } else {
            $eventData = Event::latest('id')->firstOrFail();
        }

        $rawPeserta = Peserta::where('event_id', $eventData->id)
            ->where('status', 'diterima')
            ->whereHas('pembayaran', function ($query) {
                $query->where('status_midtrans', 'settlement');
            })
            ->with(['user'])
            ->withCount('votingMasuk')
            ->orderByDesc('voting_masuk_count')
            ->get();

        $totalSuara = $rawPeserta->sum('voting_masuk_count');
        $pesertaCount = $rawPeserta->count();

        $warnaList = [
            'bg-[#edf3fb] text-[#42658c] border-[#d8e5f4]',
            'bg-[#f0e6cf] text-[#8a6d1f] border-[#d9bd79]',
            'bg-[#fff1e8] text-[#a96335] border-[#f1d5c4]',
            'bg-[#f0fdf4] text-[#166534] border-[#bbf7d0]',
        ];

        $pesertas = $rawPeserta->map(function ($p, $index) use ($totalSuara, $warnaList) {
            $nama = $p->user->nama ?? $p->nama ?? 'Tanpa Nama';
            $totalVote = $p->voting_masuk_count ?? 0;
            $persentase = $totalSuara > 0 ? round(($totalVote / $totalSuara) * 100, 1) : 0;

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

        $peringkat1 = $pesertas->get(0);
        $peringkat2 = $pesertas->get(1);
        $peringkat3 = $pesertas->get(2);

        $persenPeringkat1 = $peringkat1['persentase'] ?? 0;

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
     * Helper untuk warna berdasarkan rank
     */
    private function getColorForRank(int $votes): string
    {
        $colors = [
            'bg-gradient-to-br from-amber-100 to-amber-50 text-amber-700 border-amber-200',
            'bg-gradient-to-br from-blue-100 to-blue-50 text-blue-700 border-blue-200',
            'bg-gradient-to-br from-orange-100 to-orange-50 text-orange-700 border-orange-200',
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
