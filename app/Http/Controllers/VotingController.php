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

        // 1. Cek voting aktif
        if (! $event->voting_aktif) {
            return response()->json(['message' => 'Voting untuk event ini belum/tidak aktif.'], 403);
        }

        // 2. Cek periode voting — belum dibuka?
        if ($event->voting_mulai && now()->lt($event->voting_mulai)) {
            return response()->json([
                'message' => 'Voting belum dibuka. Jadwal: ' . $event->voting_mulai->format('d M Y, H:i') . ' WIB',
            ], 403);
        }


        //    Kunci HANYA kalau auto_freeze_voting aktif
        // Cek periode voting — sudah lewat?
if ($event->voting_selesai && now()->gt($event->voting_selesai)) {
    return response()->json([
        'message' => 'Voting sudah ditutup pada ' . $event->voting_selesai->format('d M Y, H:i') . ' WIB',
    ], 403);
}

        // 4. Cek status peserta
        if (!$peserta->isVotable()) {
            return response()->json(['message' => 'Peserta ini belum bisa divote.'], 403);
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

        $event = $voting->peserta?->event ?? $voting->event;

        $blackoutAktif = false;
        if ($event && $event->blackout_h2_voting && $event->voting_selesai) {
            $selisihJam = now()->diffInHours($event->voting_selesai, false);
            if ($selisihJam <= 48) {
                $blackoutAktif = true;
            }
        }

        return view('voting.instruksi', [
            'voting'        => $voting,
            'peserta'       => $voting->peserta,
            'event'         => $event,
            'blackoutAktif' => $blackoutAktif,
        ]);
    }

    /**
     * GET /voting/{orderId}/status
     * Cek status pembayaran voting via AJAX (untuk tombol "Saya Sudah Bayar")
     */
    public function cekStatus($orderId)
    {
        $voting = Voting::where('midtrans_order_id', $orderId)->first();

        if (! $voting) {
            return response()->json([
                'status_midtrans' => 'failed',
                'message' => 'Transaksi tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'status_midtrans' => $voting->status_midtrans,
            'redirect_url'    => $voting->status_midtrans === 'settlement' 
                ? '/peserta/' . $voting->peserta_id 
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
                    $event = $voting->event;

                    if (! $peserta) {
                        return;
                    }

                    // ✅ Cek apakah order dari ADMIN (manual vote)
                    $isAdminOrder = str_starts_with($voting->midtrans_order_id, 'MANUAL-');

                    // ✅ Cek auto freeze — HANYA untuk order non-admin
                    $freezeAktif = ! $isAdminOrder
                    && $event->voting_selesai
                    && now()->gt($event->voting_selesai);

                    if ($freezeAktif) {
                        \Log::warning('Vote settlement setelah freeze — suara TIDAK dihitung', [
                            'voting_id'      => $voting->id,
                            'peserta_id'     => $peserta->id,
                            'order_id'       => $voting->midtrans_order_id,
                            'jumlah_vote'    => $voting->jumlah_vote,
                            'voting_selesai' => $event->voting_selesai,
                            'waktu_sekarang' => now(),
                        ]);

                        return; // Skip increment
                    }

                                        // ✅ GUARD BARU: Jangan hitung suara kalau voting_aktif dimatikan
                    //    (Kecuali ini order MANUAL dari admin)
                    if (! $isAdminOrder && ! $event->voting_aktif) {
                        \Log::warning('Vote settlement ditolak — voting_aktif = false', [
                            'voting_id'   => $voting->id,
                            'peserta_id'  => $peserta->id,
                            'order_id'    => $voting->midtrans_order_id,
                            'jumlah_vote' => $voting->jumlah_vote,
                        ]);
                        return; // Skip increment
                    }

                    // ✅ Increment — normal atau admin
                    $peserta->increment('total_vote', $voting->jumlah_vote);

                    // ✅ Log — kalau order admin
                    if ($isAdminOrder) {
                        \Log::info('Admin menambah suara manual (via webhook)', [
                            'voting_id'   => $voting->id,
                            'peserta_id'  => $peserta->id,
                            'jumlah_vote' => $voting->jumlah_vote,
                            'order_id'    => $voting->midtrans_order_id,
                        ]);
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

        // ✅ Kalau hasil belum dipublikasikan & bukan admin → kirim JSON kosong (bukan 403)
        //    supaya frontend tetap bisa render tanpa crash
        if (! $event->tampilkan_hasil_voting && ! $adminLogin) {
            return response()->json([
                'event' => $event->only(['id', 'nama', 'voting_aktif', 'tampilkan_hasil_voting']),
                'peserta' => [],
                'hasil_voting' => [],
                'blackout' => false,
                'waktu_grand_final' => null,
                'message' => 'Hasil voting belum dipublikasikan.',
            ], 200);
        }

        $pesertaList = Peserta::with('user')
            ->where('event_id', $event->id)
            ->where('status', 'diterima')
            ->orderBy('total_vote', 'desc')
            ->get();

        // ============================================
        // Cek blackout H-2
        // ============================================
        $blackoutAktif = false;
        $waktuGrandFinal = null;
        $message = null;

        if ($event->blackout_h2_voting) {
            $blackoutAktif = true;
            $waktuGrandFinal = $event->voting_selesai?->translatedFormat('d F Y, H:i');
            $message = 'Untuk menjaga ketegangan menjelang malam puncak, kami merahasiakan peringkat sementara saat ini.';
        }

        // ============================================
        // Kalau blackout — sembunyikan total_vote
        // ============================================
        if ($blackoutAktif && ! $adminLogin) {
            $pesertaList->each(function ($p) {
                $p->total_vote = null; // sembunyikan angka
            });
        }

        return response()->json([
            'event' => $event->only([
                'id', 'nama', 'tanggal_mulai', 'tanggal_selesai',
                'voting_aktif', 'tampilkan_hasil_voting',
            ]),
            'peserta' => $pesertaList,
            'hasil_voting' => $pesertaList,

            // ===== BLACKOUT INFO =====
            'blackout' => $blackoutAktif,
            'waktu_grand_final' => $waktuGrandFinal,
            'message' => $message,
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
            ?? $peserta->nama_pendaftaran
            ?? $peserta->user->nama
            ?? 'Peserta';

        $bio = $cariJawaban('bio', '/bio|profil|tentang|deskripsi/i');
        $lokasi = $cariJawaban('lokasi', '/lokasi|domisili|kota|alamat/i');

        // ✅ PRIORITAS 1: Foto dari Form Builder (Upload Gambar)
        $fotoDariForm = null;
        $jawabanFoto = $peserta->jawabans->first(function ($j) {
            $tipe = strtolower(trim($j->pertanyaan->tipe_input ?? ''));
            return in_array($tipe, ['file', 'upload_gambar', 'image', 'gambar']) && $j->jawaban;
        });

        if ($jawabanFoto) {
            $fotoDariForm = str_starts_with($jawabanFoto->jawaban, 'http') 
                ? $jawabanFoto->jawaban 
                : asset('storage/' . $jawabanFoto->jawaban);
        }

        // ✅ PRIORITAS 2: Foto Custom (yang sudah di-upload admin)
        $fotoCustom = $peserta->user?->foto_custom 
            ? asset('storage/' . $peserta->user->foto_custom) 
            : null;

        // ✅ PRIORITAS 3: Foto Google (fallback terakhir)
        $fotoGoogle = $peserta->user?->foto_url;

        // Urutan prioritas
        $foto = $fotoDariForm ?? $fotoCustom ?? $fotoGoogle;

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
                'format_hasil_voting',
                'voting_aktif',
                'voting_mulai',
                'voting_selesai',
                'auto_freeze_voting',
                'status',               
            ]) : null,
        ]);
    }

    /**
     * GET /event/{event}/peserta/{peserta}/dukungan-terkini
     */
        public function dukunganTerkini(Event $event, Peserta $peserta)
    {
        // ✅ HAPUS pengecekan tampilkan_hasil_voting di sini agar aktivitas recent selalu transparan
        // $adminLogin = Auth::guard('admin')->check();
        // if (! $event->tampilkan_hasil_voting && ! $adminLogin) { ... }

        if ($peserta->event_id !== $event->id) {
            return response()->json(['message' => 'Peserta ini bukan bagian dari event tersebut.'], 404);
        }

        $dukungan = Voting::where('event_id', $event->id)
            ->where('peserta_id', $peserta->id)
            ->where('status_midtrans', 'settlement')
            ->where('midtrans_order_id', 'NOT LIKE', 'MANUAL-%')
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
            ->where('midtrans_order_id', 'NOT LIKE', 'MANUAL-%')
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
        // Logika active event: URL > Session > Terbaru
        if (! $event) {
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
     * POST /admin/voting/settings/{event}
     */
    public function update(Request $request, Event $event): RedirectResponse
    {
        $data = $request->validate([
            'voting_aktif' => ['nullable', 'boolean'],
            'tampilkan_hasil_voting' => ['nullable', 'boolean'],
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
            'tampilkan_hasil_voting' => 'boolean',
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
        
        // Query voting yang sudah settlement
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
            ->get();

        $pesertaList = Peserta::where('event_id', $event?->id ?? 0)
            ->where('status', 'diterima')
            ->whereHas('pembayaran', function ($q) {
                $q->where('status_midtrans', 'settlement');
            })
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
        // 1. Ambil data Event berdasarkan ID
        if ($event) {
            $eventData = \App\Models\Event::findOrFail($event);
        } else {
            $eventData = \App\Models\Event::latest('id')->first();
        }

        // Kalau gak ada event — return view kosong
        if (! $eventData) {
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

        // 2. Query peserta aktif (diterima + pembayaran settlement)
        $rawPeserta = \App\Models\Peserta::where('event_id', $eventData->id)
            ->where('status', 'diterima')
            ->whereHas('pembayaran', function ($query) {
                $query->where('status_midtrans', 'settlement');
            })
            ->with(['user'])
            ->get();

        // 3. Hitung total suara per peserta — dari tabel voting
        $rawPeserta = $rawPeserta->map(function ($peserta) use ($eventData) {
            $peserta->total_vote = \App\Models\Voting::where('peserta_id', $peserta->id)
                ->where('event_id', $eventData->id)
                ->where('status_midtrans', 'settlement')
                ->sum('jumlah_vote');

            return $peserta;
        });

        // Sortir berdasarkan total vote descending
        $rawPeserta = $rawPeserta->sortByDesc('total_vote')->values();

        // 4. Ringkasan Total Suara & Jumlah Peserta
        $totalSuara = \App\Models\Voting::where('event_id', $eventData->id)
            ->where('status_midtrans', 'settlement')
            ->sum('jumlah_vote');

        $pesertaCount = $rawPeserta->count();

        $warnaList = [
            'bg-[#edf3fb] text-[#42658c] border-[#d8e5f4]',
            'bg-[#f0e6cf] text-[#8a6d1f] border-[#d9bd79]',
            'bg-[#fff1e8] text-[#a96335] border-[#f1d5c4]',
            'bg-[#f0fdf4] text-[#166534] border-[#bbf7d0]',
        ];

        // 5. Format data untuk view
        $pesertas = $rawPeserta->map(function ($p, $index) use ($totalSuara, $warnaList) {
            $nama = $p->namaTampil();
            $totalVote = $p->total_vote ?? 0;
            $persentase = $totalSuara > 0 ? round(($totalVote / $totalSuara) * 100, 1) : 0;

            // Inisial nama
            $words = explode(' ', trim($nama));
            $inisial = strtoupper(substr($words[0], 0, 1));
            if (count($words) > 1) {
                $inisial .= strtoupper(substr(end($words), 0, 1));
            }

            return [
                'id'         => $p->id,
                'nama'       => $nama,
                'total_vote' => $totalVote,
                'persentase' => $persentase,
                'inisial'    => $inisial,
                'warna'      => $warnaList[$index % count($warnaList)],
            ];
        });

        // 6. Top 3 podium
        $peringkat1 = $pesertas->get(0);
        $peringkat2 = $pesertas->get(1);
        $peringkat3 = $pesertas->get(2);

        $persenPeringkat1 = $peringkat1['persentase'] ?? 0;

        // 7. Return view
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

    /**
     * DELETE /admin/voters/{id}
     * Hapus record voting.
     */
    public function destroy($id)
    {
        try {
            $voting = Voting::findOrFail($id);
            
            // Kurangi total_vote peserta
            if ($voting->peserta) {
                $voting->peserta->decrement('total_vote', $voting->jumlah_vote);
            }
            
            $voting->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Vote berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            Log::error('Delete Vote Failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus vote: ' . $e->getMessage()
            ], 500);
        }
    }
}
