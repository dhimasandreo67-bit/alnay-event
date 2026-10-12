<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Event & Kandidat - {{ $event->nama }} | ALNAY.ID</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="{{ asset('images/alnaylogo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        * {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #faf9f6;
        }

        .font-display {
            font-family: 'Playfair Display', serif;
        }

        /* ============================================ */
        /* BANNER                                        */
        /* ============================================ */
        .event-banner {
            position: relative;
        }

        .event-banner::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg,
                    rgba(10, 21, 38, 0.02) 25%,
                    rgba(10, 21, 38, 0.42) 100%);
            pointer-events: none;
        }

        /* ============================================ */
        /* GOLD LINE — SHIMMER                           */
        /* ============================================ */
        .gold-line {
            width: 32px;
            height: 2px;
            background: linear-gradient(90deg, #c99a32, #f0c76a, #c99a32);
            background-size: 200% 100%;
            animation: goldLineShimmer 3s ease-in-out infinite;
        }

        @keyframes goldLineShimmer {

            0%,
            100% {
                background-position: 0% 0;
            }

            50% {
                background-position: 100% 0;
            }
        }

        /* ============================================ */
        /* GOLD SHIMMER TEXT — JUDUL BANNER              */
        /* ============================================ */
        /* ============================================ */
        /* GOLD SHIMMER TEXT — JUDUL BANNER              */
        /* ============================================ */
        .gold-shimmer-text {
            background: linear-gradient(110deg,
                    #8f681c 0%,
                    #c99a32 20%,
                    #f8df8b 40%,
                    #fff1b3 50%,
                    #e0b254 60%,
                    #b88625 80%,
                    #8f681c 100%);
            background-size: 250% 100%;
            background-position: 100% 0;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: goldShimmer 5s ease-in-out infinite;
        }

        @keyframes goldShimmer {

            0%,
            100% {
                background-position: 100% 0;
            }

            50% {
                background-position: 0% 0;
            }
        }

        /* ============================================ */
        /* KANDIDAT CARD — HOVER PREMIUM                 */
        /* ============================================ */
        .kandidat-card {
            transition: transform .35s cubic-bezier(.22, 1, .36, 1),
                box-shadow .35s cubic-bezier(.22, 1, .36, 1),
                border-color .35s ease;
        }

        .kandidat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 22px 45px -18px rgba(198, 146, 32, .35),
                0 8px 18px -10px rgba(21, 35, 61, .15);
            border-color: #e6d3a7;
        }

        .kandidat-card img {
            transition: transform .6s cubic-bezier(.22, 1, .36, 1);
        }

        .kandidat-card:hover img {
            transform: scale(1.06);
        }

        /* ============================================ */
        /* DETAIL BUTTON — SHIMMER SWEEP                 */
        /* ============================================ */
        .detail-button {
            position: relative;
            overflow: hidden;
            transition: all .3s cubic-bezier(.22, 1, .36, 1);
        }

        .detail-button::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg,
                    transparent,
                    rgba(255, 255, 255, .25),
                    transparent);
            transition: left .6s ease;
        }

        .detail-button:hover::before {
            left: 100%;
        }

        .detail-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px -8px rgba(198, 146, 32, .55);
        }

        /* ============================================ */
        /* STAT BOX                                      */
        /* ============================================ */
        .stat-box {
            transition: background .25s ease, transform .25s ease;
        }

        .stat-box:hover {
            background: #f6f1e6;
            transform: translateY(-2px) scale(1.02);
        }

        /* ============================================ */
        /* COUNTDOWN ITEM                                */
        /* ============================================ */
        .countdown-item {
            transition: transform .25s ease, box-shadow .25s ease;
        }

        .countdown-item:hover {
            transform: translateY(-3px) scale(1.04);
            box-shadow: 0 8px 18px -8px rgba(201, 154, 50, .4);
        }

        /* ============================================ */
        /* PULSE SOFT — BADGE VOTING AKTIF               */
        /* ============================================ */
        .pulse-soft {
            animation: pulseSoft 2.4s ease-in-out infinite;
        }

        @keyframes pulseSoft {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: .65;
                transform: scale(1.15);
            }
        }

        /* ============================================ */
        /* REVEAL UP — ENTRANCE ANIMATION                */
        /* ============================================ */
        .reveal-up {
            animation: revealUp .6s cubic-bezier(.22, 1, .36, 1) both;
        }

        @keyframes revealUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .reveal-up.delay-1 {
            animation-delay: .05s;
        }

        .reveal-up.delay-2 {
            animation-delay: .12s;
        }

        .reveal-up.delay-3 {
            animation-delay: .19s;
        }

        .reveal-up.delay-4 {
            animation-delay: .26s;
        }

        /* ============================================ */
        /* SECTION REVEAL                                */
        /* ============================================ */
        .section-reveal {
            animation: sectionReveal .8s cubic-bezier(.22, 1, .36, 1) both;
        }

        @keyframes sectionReveal {
            from {
                opacity: 0;
                transform: translateY(24px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ============================================ */
        /* MODAL                                         */
        /* ============================================ */
        .modal-panel {
            animation: modalUp .25s cubic-bezier(.22, 1, .36, 1);
        }

        @keyframes modalUp {
            from {
                opacity: 0;
                transform: translateY(16px) scale(.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        #modalSyarat.flex {
            animation: backdropFade .3s ease-out;
        }

        @keyframes backdropFade {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* ============================================ */
        /* SEARCH INPUT                                  */
        /* ============================================ */
        .search-input {
            transition: border-color .25s ease, box-shadow .25s ease, transform .25s ease;
        }

        .search-input:focus {
            border-color: #c99a32;
            box-shadow: 0 0 0 4px rgba(201, 154, 50, .12);
            transform: translateY(-1px);
        }

        /* ============================================ */
        /* REDUCED MOTION                                */
        /* ============================================ */
        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation-duration: .01ms !important;
                transition-duration: .01ms !important;
            }
        }

        .event-banner {
            animation: bannerFadeIn 1s ease-out both;
        }

        @keyframes bannerFadeIn {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="text-[#15233d]">

    @php
        // =====================================================
        // DATA DARI CONTROLLER
        // =====================================================
        $formatAngka = function ($angka) {
            if ($angka >= 1000000) {
                return round($angka / 1000000, 1) . 'M';
            }
            if ($angka >= 1000) {
                return round($angka / 1000, 1) . 'K';
            }
            return number_format($angka, 0, ',', '.');
        };

        $rp = fn($n) => $n ? 'Rp ' . number_format($n, 0, ',', '.') : 'Gratis';

        $inisial = fn($nama) => strtoupper(
            implode(
                '',
                array_map(fn($kata) => mb_substr($kata, 0, 1), array_slice(preg_split('/\s+/', trim($nama)), 0, 2)),
            ),
        );

        $pecahBaris = fn($teks) => array_values(array_filter(array_map('trim', preg_split('/\R/', (string) $teks))));

        $ketentuanVoting = $pecahBaris($event->syarat_ketentuan ?? '');

        // =====================================================
        // LOGIKA TURUNAN
        // =====================================================
        $tz = 'Asia/Jakarta';
        $now = \Carbon\Carbon::now($tz);

        $mulai = $event->tanggal_mulai ? \Carbon\Carbon::parse($event->tanggal_mulai, $tz)->locale('id') : null;

        $selesai = $event->tanggal_selesai ? \Carbon\Carbon::parse($event->tanggal_selesai, $tz)->locale('id') : null;

        $rentang = function ($a, $b = null) {
            if (!$a) {
                return '-';
            }

            if (!$b) {
                return $a->translatedFormat('j F Y');
            }

            if ($a->isSameMonth($b)) {
                return $a->format('j') . ' – ' . $b->translatedFormat('j F Y');
            }

            return $a->translatedFormat('j M') . ' – ' . $b->translatedFormat('j M Y');
        };

        $periode = $selesai ? $rentang($mulai, $selesai) : ($mulai ? 'Mulai ' . $rentang($mulai) : '-');

        // ---- Jadwal Event ----
        $bukaDaftar = $event->pembukaan_pendaftaran_tanggal
            ? \Carbon\Carbon::parse($event->pembukaan_pendaftaran_tanggal, $tz)->locale('id')
            : null;

        $batasDaftar = $event->batas_pendaftaran_tanggal
            ? \Carbon\Carbon::parse($event->batas_pendaftaran_tanggal, $tz)->locale('id')
            : null;

        $jadwal = [];

        if ($batasDaftar) {
            $jadwal[] = [
                'nama' => 'Pendaftaran Kandidat',
                'tanggal' => $bukaDaftar
                    ? $rentang($bukaDaftar, $batasDaftar)
                    : 'Sampai ' . $batasDaftar->translatedFormat('j F Y'),
            ];

            if ($mulai) {
                $verifAwal = $batasDaftar->copy()->addDay()->startOfDay();
                $verifAkhir = $mulai->copy()->subDay()->startOfDay();

                if ($verifAwal->lte($verifAkhir)) {
                    $jadwal[] = [
                        'nama' => 'Verifikasi Data',
                        'tanggal' => $rentang($verifAwal, $verifAkhir),
                    ];
                }
            }
        } elseif ($mulai) {
            $jadwal[] = [
                'nama' => 'Pendaftaran Kandidat',
                'tanggal' => 'Sudah ditutup',
            ];
        }

        $jadwal[] = [
            'nama' => 'Voting Publik',
            'tanggal' => $periode,
        ];

        $jadwal[] = [
            'nama' => 'Pengumuman Pemenang',
            'tanggal' => 'Setelah voting berakhir',
        ];

        // Hitung mundur
        $cdLabel = $selesai ? 'Sisa Waktu Voting' : 'Voting Sedang Berjalan';
        $detik = $selesai ? max(0, $selesai->timestamp - $now->timestamp) : 0;

        $cdHari = intdiv($detik, 86400);
        $cdJam = intdiv($detik % 86400, 3600);
        $cdMenit = intdiv($detik % 3600, 60);

        // Kandidat
        $kandidat = $pesertas
            ->map(function ($p, $i) use ($totalSuara) {
                $nama = $p->namaTampil();

                return [
                    'id' => $p->id,
                    'nomor' => str_pad($i + 1, 2, '0', STR_PAD_LEFT),
                    'nama' => $nama,
                    'asal' => '-',
                    'foto' => $p->user?->foto_custom ? asset('storage/' . $p->user->foto_custom) : $p->user?->foto_url,
                    'inisial' => strtoupper(
                        implode(
                            '',
                            array_map(
                                fn($kata) => mb_substr($kata, 0, 1),
                                array_slice(preg_split('/\s+/', trim($nama)), 0, 2),
                            ),
                        ),
                    ),
                    'vote' => number_format($p->total_vote, 0, ',', '.'),
                ];
            })
            ->toArray();
    @endphp

    {{-- HEADER --}}
    @include('partials.header')

    <main class="mx-auto max-w-5xl px-5 pb-7">

        {{-- EVENT BANNER --}}
        <section
            class="event-banner group relative mx-0 mt-4 overflow-hidden rounded-[20px] shadow-[0_18px_40px_-12px_rgba(21,35,61,.25)]">
            @if ($event->banner)
                {{-- Gambar utama --}}
                <img src="{{ asset('storage/' . $event->banner) }}" alt="{{ $event->nama }}"
                    class="block h-[220px] w-full object-cover object-center transition-transform duration-700 group-hover:scale-[1.02] sm:h-[285px] md:h-[350px]"
                    onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden'); this.nextElementSibling.classList.add('flex');">

                {{-- Fallback kalau gambar gagal load --}}
                <div
                    class="hidden h-[220px] w-full bg-gradient-to-br from-[#0a1526] to-[#1a2942] items-center justify-center sm:h-[285px] md:h-[350px]">
                    <div class="text-center">
                        <p class="font-display text-2xl font-bold text-[#c99a32] tracking-wider sm:text-3xl">
                            {{ $event->nama }}</p>
                        <p class="mt-2 text-[10px] tracking-[0.3em] text-[#c5b388] uppercase font-semibold">Alnay Event
                            Center</p>
                    </div>
                </div>
            @else
                {{-- Fallback: tampil kalau memang tidak ada banner di database --}}
                <div
                    class="flex h-[220px] w-full items-center justify-center bg-gradient-to-br from-[#0a1526] via-[#11223f] to-[#1a2942] sm:h-[285px] md:h-[350px]">
                    <div class="text-center px-4">
                        <p class="font-display text-2xl font-bold text-[#c99a32] tracking-wide md:text-3xl">
                            {{ $event->nama }}
                        </p>
                        <p class="mt-2 text-[10px] tracking-[0.3em] text-[#c5b388] uppercase font-semibold">
                            Alnay Event Center
                        </p>
                    </div>
                </div>
            @endif

            {{-- Overlay gradient di bawah --}}
            <div
                class="pointer-events-none absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-[#0a1526]/40 to-transparent">
            </div>

            {{-- Badge status — floating glassmorphism --}}
            <span
                class="absolute top-4 right-4 z-10 inline-flex items-center gap-2 rounded-full border backdrop-blur-md px-4 py-1.5 text-[11px] font-semibold shadow-lg sm:text-[12px]
                {{ $event->voting_aktif
                    ? 'border-emerald-400/30 bg-emerald-950/70 text-emerald-300'
                    : 'border-orange-400/30 bg-orange-950/70 text-orange-300' }}">
                @if ($event->voting_aktif)
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    Sedang Berlangsung
                @else
                    <span class="h-2 w-2 rounded-full bg-orange-400"></span>
                    Telah Berakhir
                @endif
            </span>
        </section>

        {{-- EVENT INFORMATION --}}
        <section
            class="section-reveal relative -mt-5 mx-2 overflow-hidden rounded-[20px] border border-[#ebe7df] bg-white shadow-[0_18px_45px_-15px_rgba(21,35,61,.12)]">
            {{-- Garis emas atas --}}
            <div class="h-[3px] w-full bg-gradient-to-r from-[#c99a32] via-[#e0b254] to-[#c99a32]"></div>

            <div class="p-5 md:p-6">
                <div class="grid grid-cols-1 gap-5 lg:grid-cols-[1fr_220px] self-">

                    <div>
                        @if (!$event->voting_aktif)
                            <span
                                class="inline-flex items-center gap-1.5 rounded-full bg-[#15233d] px-2.5 py-1 text-[9px] font-semibold uppercase tracking-wider text-white">
                                <span class="h-1.5 w-1.5 rounded-full bg-[#c99a32]"></span>
                                Voting Ditutup
                            </span>
                        @else
                            <span
                                class="inline-flex items-center gap-1.5 rounded-full bg-[#f8f2e5] px-2.5 py-1 text-[9px] font-semibold uppercase tracking-wider text-[#9b762d]">
                                <span class="pulse-soft h-1.5 w-1.5 rounded-full bg-[#c99a32]"></span>
                                Voting Aktif
                            </span>
                        @endif

                        <h1 class="mt-2 font-display text-xl font-bold leading-tight text-[#111827] md:text-2xl">
                            {{ $event->nama }}
                        </h1>

                        <p class="mt-2 max-w-3xl text-xs leading-relaxed text-gray-500 md:text-sm">
                            {{ $event->deskripsi ?: 'Deskripsi belum diisi.' }}
                        </p>

                        <div class="mt-3 flex flex-wrap gap-1.5">
                            <span
                                class="inline-flex items-center gap-1.5 rounded-full border border-[#eee8da] bg-[#faf8f2] px-2.5 py-1 text-[10px] text-gray-600">
                                <svg class="h-3 w-3 text-[#c99a32]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" stroke-width="2">
                                    <rect x="3" y="4.5" width="18" height="17" rx="2" />
                                    <path d="M16 2.5v4M8 2.5v4M3 9h18" />
                                </svg>
                                {{ $periode }}
                            </span>

                            <span
                                class="inline-flex items-center rounded-full border border-[#eee8da] bg-[#faf8f2] px-2.5 py-1 text-[10px] text-gray-600">
                                {{ $rp($event->harga_per_vote) }} / vote
                            </span>

                            @if ($event->lokasi)
                                <span
                                    class="inline-flex items-center rounded-full border border-[#eee8da] bg-[#faf8f2] px-2.5 py-1 text-[10px] text-gray-600">
                                    {{ $event->lokasi }}
                                </span>
                            @endif
                        </div>

                        <button type="button" data-buka-syarat
                            class="mt-3 inline-flex items-center gap-1 text-[11px] font-semibold text-[#a37b2a] transition hover:text-[#c99a32]">
                            Lihat Syarat &amp; Ketentuan
                            <span class="transition-transform group-hover:translate-x-0.5">→</span>
                        </button>
                    </div>

                    {{-- COUNTDOWN --}}
                    <div class="rounded-xl border border-white/10 bg-gradient-to-br from-[#0a1526] to-[#15243b] p-4 relative overflow-hidden self-start mt-8">

                        {{-- Glow gold di pojok --}}
                        <div
                            class="absolute -top-10 -right-10 w-32 h-32 bg-[#c99a32]/20 blur-3xl rounded-full pointer-events-none">
                        </div>

                        <div class="relative z-10">
                            {{-- Header Label Status --}}
                            <div class="flex items-center justify-between border-b border-white/10 pb-3">
                                <span
                                    class="text-[10px] uppercase tracking-wider font-semibold text-[#c5b388] flex items-center gap-1.5">
                                    <span class="h-2 w-2 rounded-full bg-[#c99a32]"></span>
                                    {{ $cdLabel }}
                                </span>
                                <span class="text-[10px] font-medium text-gray-400">Timer</span>
                            </div>

                            {{-- Countdown Display --}}
                            @if ($selesai)
                                <div class="mt-4 flex items-center justify-center gap-1.5">
                                    <div
                                        class="countdown-item min-w-[44px] rounded-lg bg-white/5 border border-white/10 backdrop-blur-sm px-2 py-2.5 text-center transition-transform duration-200 hover:scale-105 hover:bg-white/10">
                                        <span
                                            class="display-font block text-lg font-bold leading-none text-[#f5d78e] tracking-tight">
                                            {{ sprintf('%02d', $cdHari) }}
                                        </span>
                                        <span
                                            class="mt-1 block text-[8px] font-semibold uppercase text-gray-400 tracking-wider">
                                            Hari
                                        </span>
                                    </div>

                                    <span class="display-font text-base font-bold text-[#c99a32]">:</span>

                                    <div
                                        class="countdown-item min-w-[44px] rounded-lg bg-white/5 border border-white/10 backdrop-blur-sm px-2 py-2.5 text-center transition-transform duration-200 hover:scale-105 hover:bg-white/10">
                                        <span
                                            class="display-font block text-lg font-bold leading-none text-[#f5d78e] tracking-tight">
                                            {{ sprintf('%02d', $cdJam) }}
                                        </span>
                                        <span
                                            class="mt-1 block text-[8px] font-semibold uppercase text-gray-400 tracking-wider">
                                            Jam
                                        </span>
                                    </div>

                                    <span class="display-font text-base font-bold text-[#c99a32]">:</span>

                                    <div
                                        class="countdown-item min-w-[44px] rounded-lg bg-white/5 border border-white/10 backdrop-blur-sm px-2 py-2.5 text-center transition-transform duration-200 hover:scale-105 hover:bg-white/10">
                                        <span
                                            class="display-font block text-lg font-bold leading-none text-[#f5d78e] tracking-tight">
                                            {{ sprintf('%02d', $cdMenit) }}
                                        </span>
                                        <span
                                            class="mt-1 block text-[8px] font-semibold uppercase text-gray-400 tracking-wider">
                                            Menit
                                        </span>
                                    </div>
                                </div>
                            @endif

                            {{-- Stat Boxes --}}
                            <div class="mt-4 grid grid-cols-2 gap-2 border-t border-white/10 pt-3">
                                <div
                                    class="rounded-lg bg-white/5 border border-white/10 backdrop-blur-sm px-2 py-2.5 text-center transition-transform duration-200 hover:scale-105 hover:bg-white/10">
                                    <p class="display-font text-base font-bold text-[#f5d78e]">
                                        {{ $jumlahPeserta }}
                                    </p>
                                    <p class="mt-0.5 text-[9px] font-medium text-gray-400 uppercase tracking-wider">
                                        Kandidat
                                    </p>
                                </div>

                                <div
                                    class="rounded-lg bg-white/5 border border-white/10 backdrop-blur-sm px-2 py-2.5 text-center transition-transform duration-200 hover:scale-105 hover:bg-white/10">
                                    @if ($blackoutAktif ?? false)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-5 w-5 text-[#f5d78e]"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                        </svg>
                                        <p class="mt-0.5 text-[9px] font-medium text-gray-400 uppercase tracking-wider">
                                            Total Vote
                                        </p>
                                    @else
                                        <p class="text-lg font-bold">{{ $formatAngka($totalSuara) }}</p>
                                        <p class="text-xs text-gray-500 mt-1" id="peringkat">Total Vote</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ============================================ --}}
        {{-- PERINGKAT SEMENTARA --}}
        {{-- ============================================ --}}
        @php
            $top3 = $pesertas->take(3);
            $totalUntukPersen = $totalSuara > 0 ? $totalSuara : 1;
        @endphp

        @if ($blackoutAktif ?? false)
            <section class="section-reveal mt-7">
                <div
                    class="overflow-hidden rounded-[18px] border border-[#e8dcc0] bg-gradient-to-br from-[#fdfbf6] via-white to-[#faf6ed] shadow-sm">
                    <div class="relative px-6 pt-10 pb-8 text-center md:px-12 md:pt-12 md:pb-10">
                        <div class="mb-5 flex items-center justify-center gap-2">
                            <span class="h-px w-12 bg-gradient-to-r from-transparent to-[#c99a32]/40"></span>
                            <svg class="h-4 w-4 text-[#c99a32]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z"/>
                            </svg>
                            <span class="h-px w-12 bg-gradient-to-l from-transparent to-[#c99a32]/40"></span>
                        </div>

                        <h2 class="font-display text-xl font-bold tracking-tight text-[#0a1526] md:text-2xl">
                            Peringkat Sementara
                        </h2>
                        <h2 class="mb-5 font-display text-2xl font-bold leading-tight text-[#0a1526] md:text-3xl">
                            Siapa yang akan membawa pulang mahkota?
                        </h2>
                        <p class="mx-auto max-w-xl text-[13px] leading-relaxed text-[#5a5750] md:text-[14px]">
                            Untuk menjaga ketegangan menjelang malam puncak, kami merahasiakan peringkat sementara saat
                            ini.
                        </p>
                    </div>
                </div>
            </section>
        @else
            <section class="section-reveal mt-7">
                <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <div class="mb-2 flex items-center gap-3">
                            <span
                                class="inline-block h-px w-8 bg-gradient-to-r from-transparent via-[#c99a32]/60 to-[#c99a32]"></span>
                            <span class="inline-flex items-center gap-1.5">
                                <span class="inline-block h-1 w-1 rotate-45 bg-[#c99a32]"></span>
                                <span class="text-[10px] font-bold uppercase tracking-[.24em] text-[#b28a38]">
                                    Top 3
                                </span>
                                <span class="inline-block h-1 w-1 rotate-45 bg-[#c99a32]"></span>
                            </span>
                        </div>
                        <h2 class="font-display text-lg font-bold text-[#15233d] md:text-xl">
                            Peringkat Sementara
                        </h2>
                        <p class="mt-1.5 text-[13px] leading-relaxed text-[#8a8178] md:text-sm">
                            Peserta dengan perolehan suara tertinggi saat ini.
                        </p>
                    </div>
                </div>

                @if ($top3->count() > 0)
                    <div class="grid grid-cols-1 items-end gap-3.5 sm:grid-cols-{{ min($top3->count(), 3) }}">
                        @foreach ($top3 as $i => $p)
                            @php
                                $namaP = $p->namaTampil();
                                $fotoP = $p->user?->foto_custom
                                    ? asset('storage/' . $p->user->foto_custom)
                                    : $p->user?->foto_url;
                                $inisialP = strtoupper(
                                    implode(
                                        '',
                                        array_map(
                                            fn($kata) => mb_substr($kata, 0, 1),
                                            array_slice(preg_split('/\s+/', trim($namaP)), 0, 2),
                                        ),
                                    ),
                                );
                                $persenP =
                                    $totalUntukPersen > 0 ? round(($p->total_vote / $totalUntukPersen) * 100, 2) : 0;
                                $juara = $i + 1;
                                $order = $juara == 1 ? 'sm:order-2' : ($juara == 2 ? 'sm:order-1' : 'sm:order-3');
                                $border =
                                    $juara == 1
                                        ? 'border-2 border-[#c99a32] shadow-md'
                                        : 'border border-[#ece5d7] shadow-sm hover:border-[#c99a32]/40';
                                $height = $juara == 1 ? 'h-[230px] sm:h-[270px]' : 'h-[190px] sm:h-[210px]';
                            @endphp

                            <div
                                class="group bg-white rounded-2xl overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-xl {{ $border }} {{ $order }}">

                                <div class="relative overflow-hidden {{ $height }}">
                                    @if ($fotoP)
                                        <img src="{{ $fotoP }}" alt="{{ $namaP }}"
                                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                    @else
                                        <div
                                            class="w-full h-full bg-gradient-to-br from-[#fbf6e9] to-[#f4ede0] flex items-center justify-center">
                                            <span
                                                class="font-display text-4xl font-bold text-[#c99a32]">{{ $inisialP }}</span>
                                        </div>
                                    @endif

                                    {{-- Nomor --}}
                                    <span
                                        class="absolute top-3 left-3 bg-white/95 backdrop-blur px-2.5 py-1 rounded-lg text-[11px] font-bold text-[#0a1526] shadow-sm">
                                        {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                                    </span>

                                    {{-- Badge juara --}}
                                    <span
                                        class="absolute top-3 right-3 inline-flex items-center gap-1 bg-gradient-to-r from-[#c99a32] to-[#b88625] text-white text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase tracking-wider shadow-md">
                                        @if ($juara == 1)
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z" />
                                            </svg>
                                        @endif
                                        Juara {{ $juara }}
                                    </span>
                                </div>

                                <div class="p-4">
                                    <div class="flex items-center justify-between gap-2 mb-2">
                                        <h3 class="font-bold text-[14px] text-[#0a1526] truncate">{{ $namaP }}
                                        </h3>
                                        <span
                                            class="text-[10px] font-semibold text-[#c99a32] bg-[#fbf6e9] px-2 py-1 rounded-md whitespace-nowrap">
                                            {{ $p->asal ?? '-' }}
                                        </span>
                                    </div>

                                    <div class="flex items-baseline gap-1.5">
                                        @php
                                        $formatHasil = $event->format_hasil_voting ?? 'keduanya';
                                        $voteP = number_format($p->total_vote, 0, ',', '.');
                                    @endphp

                                    @if ($formatHasil === 'angka')
                                        <p class="text-lg font-bold text-[#0a1526]">{{ $voteP }}</p>
                                        <span class="text-[11px] text-[#8a8178]">Suara</span>
                                    @elseif ($formatHasil === 'persentase')
                                        <p class="text-lg font-bold text-[#0a1526]">{{ $persenP }}%</p>
                                        <span class="text-[11px] text-[#8a8178]">Vote</span>
                                    @else
                                        <p class="text-lg font-bold text-[#0a1526]">{{ $voteP }} <span class="text-sm font-normal text-[#8a8178]">({{ $persenP }}%)</span></p>
                                    @endif
                                        <span class="text-[11px] text-[#8a8178]">Vote</span>
                                    </div>

                                    <div class="w-full h-1.5 bg-[#f0ece2] rounded-full mt-2.5 overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-[#c99a32] to-[#e0b254] rounded-full transition-all duration-700"
                                            style="width: {{ $persenP }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>
        @endif

        {{-- CANDIDATE SECTION --}}
        <section class="section-reveal mt-7">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <div class="mb-1.5 flex items-center gap-3">
                        <span class="inline-block h-px w-8 bg-gradient-to-r from-transparent to-[#c99a32]"></span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="inline-block h-1 w-1 rotate-45 bg-[#c99a32]"></span>
                            <span class="text-[10px] font-bold uppercase tracking-[.24em] text-[#b28a38]">
                                Peserta
                            </span>
                            <span class="inline-block h-1 w-1 rotate-45 bg-[#c99a32]"></span>
                        </span>
                    </div>
                    <h2 class="mt-1.5 font-display text-xl font-bold tracking-tight text-[#0a1526] md:text-2xl">
                        Daftar Kandidat
                    </h2>
                </div>

                <div class="relative w-full lg:w-60 shrink-0">
                    <input type="text" id="searchKandidat" placeholder="Cari kandidat..."
                        class="search-input h-10 w-full rounded-full border border-[#ddd9d0] bg-white pl-9 pr-4 text-xs outline-none focus:border-[#c99a32] focus:ring-0">

                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-3 h-4 w-4 text-gray-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="m21 21-4.35-4.35m1.35-5.4a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z" />
                    </svg>
                </div>
            </div>

            @if (count($kandidat) > 0)
                <div id="gridKandidat" class="mt-3.5 grid grid-cols-1 gap-3.5 sm:grid-cols-2 lg:grid-cols-4">

                    @foreach ($kandidat as $index => $item)
                        @php
                            $delayClass = 'delay-' . (($index % 4) + 1);
                            $isHidden = $index >= 8;
                        @endphp
                        <div class="kandidat-card {{ $isHidden ? 'hidden' : '' }} reveal-up {{ $delayClass }} group overflow-hidden rounded-xl border border-[#ebe8e1] bg-white"
                            data-nama="{{ strtolower($item['nama']) }}" data-index="{{ $index }}">

                            <div class="relative h-[175px] overflow-hidden bg-[#fbf6e9]">
                                <img src="{{ $item['foto'] ?? '' }}" alt="{{ $item['nama'] }}"
                                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                    onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden'); this.nextElementSibling.classList.add('flex');">
                                <div
                                    class="hidden h-full w-full items-center justify-center bg-gradient-to-br from-[#faf5e9] to-[#f2e7cb]">
                                    <span class="font-display text-4xl font-bold text-[#c99a32]">
                                        {{ $item['inisial'] }}
                                    </span>
                                </div>

                                <div
                                    class="absolute inset-x-0 bottom-0 h-14 bg-gradient-to-t from-black/25 to-transparent">
                                </div>

                                @if ($blackoutAktif ?? false)
                                    <span
                                        class="absolute left-2.5 top-2.5 flex h-7 w-7 items-center justify-center rounded-full bg-white/95 font-display text-[13px] font-bold text-gray-400 shadow-md backdrop-blur ring-1 ring-white/60"
                                        title="Nomor urut dirahasiakan">
                                        ?
                                    </span>
                                @else
                                    <span
                                        class="absolute left-2.5 top-2.5 flex h-7 w-7 items-center justify-center rounded-full bg-white/95 font-display text-[11px] font-bold text-[#c99a32] shadow-md backdrop-blur ring-1 ring-white/60">
                                        {{ $item['nomor'] }}
                                    </span>
                                @endif
                            </div>

                            <div class="p-3.5">
                                <div class="flex items-start justify-between gap-2">
                                    <h3 class="min-w-0 truncate text-[13px] font-semibold text-[#15233d]">
                                        {{ $item['nama'] }}
                                    </h3>

                                    <span
                                        class="shrink-0 rounded-full bg-[#fbf6e9] px-2 py-1 text-[9px] font-semibold text-[#b28a38]">
                                        {{ $item['asal'] }}
                                    </span>
                                </div>

                                @if ($blackoutAktif ?? false)
                                    <div class="mt-2 flex items-center gap-1.5 text-[10px] text-amber-600 italic">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                        </svg>
                                        <span>Jumlah suara dirahasiakan</span>
                                    </div>
                                @else
                                    <div class="mt-2 flex items-center gap-1.5 text-[10px] text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 0115 0" />
                                        </svg>
                                        @php
                                    $formatCard = $event->format_hasil_voting ?? 'keduanya';
                                    $persenCard = $totalSuara > 0 ? round(($p->total_vote / $totalSuara) * 100, 1) : 0;
                                @endphp

                                @if ($formatCard === 'angka')
                                    <span>{{ $item['vote'] }} suara</span>
                                @elseif ($formatCard === 'persentase')
                                    <span>{{ $persenCard }}%</span>
                                @else
                                    <span>{{ $item['vote'] }} ({{ $persenCard }}%)</span>
                                @endif
                                    </div>
                                @endif

                                <a href="/peserta/{{ $item['id'] }}"
                                    class="detail-button mt-3 block w-full rounded-lg bg-gradient-to-r from-[#c69220] to-[#b58219] py-2.5 text-center text-[11px] font-semibold tracking-wide text-white shadow-sm">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if (count($kandidat) > 8)
                    <div class="mt-5 text-center" id="wrapBtnLihatSemua">
                        <button type="button" id="btnLihatSemua"
                            class="inline-flex items-center gap-2 rounded-full border border-[#c99a32]/40 bg-white px-6 py-3 text-xs font-semibold text-[#a37b2a] shadow-sm transition-all duration-300 hover:border-[#c99a32] hover:bg-[#faf8f2] hover:shadow-md">
                            <span id="btnLihatSemuaText">
                                Lihat Semua {{ count($kandidat) }} Kandidat
                            </span>
                            <svg id="btnLihatSemuaIcon" class="h-3.5 w-3.5 transition-transform duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </div>
                @endif

                <div id="emptySearch"
                    class="hidden mt-3.5 rounded-xl border border-dashed border-[#ddd8cc] bg-white p-8 text-center">
                    <div
                        class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-[#faf5e9] text-[#c99a32]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="m21 21-4.35-4.35m1.35-5.4a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z" />
                        </svg>
                    </div>
                    <p class="mt-2 text-sm font-semibold text-[#15233d]">
                        Kandidat tidak ditemukan
                    </p>
                    <p class="mt-1 text-xs text-gray-400">
                        Coba kata kunci lain.
                    </p>
                </div>
            @else
                <div class="mt-3.5 rounded-xl border border-dashed border-[#ddd8cc] bg-white p-9 text-center">
                    <div
                        class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-[#faf5e9] text-[#c99a32]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 4.5a7.5 7.5 0 100 15 7.5 7.5 0 000-15zM9.75 10.5h.01M14.25 10.5h.01M9.5 14.25a3.5 3.5 0 005 0" />
                        </svg>
                    </div>

                    <p class="mt-2 text-sm font-semibold text-[#15233d]">
                        Belum ada kandidat pada event ini
                    </p>

                    <p class="mt-1 text-xs text-gray-400">
                        Kandidat akan muncul di sini begitu admin menambahkannya.
                    </p>
                </div>
            @endif
        </section>
    </main>

    {{-- FOOTER --}}
    @include('partials.footer')

    {{-- POPUP SYARAT & KETENTUAN --}}
    <div id="modalSyarat"
        class="hidden fixed inset-0 z-[60] items-end justify-center bg-[#07101d]/55 backdrop-blur-sm sm:items-center sm:px-5">

        <div
            class="modal-panel flex max-h-[88vh] w-full max-w-xl flex-col overflow-hidden rounded-t-2xl bg-white shadow-2xl sm:rounded-2xl">

            <div class="flex items-center justify-between border-b border-gray-100 px-5 py-3.5">
                <div>
                    <div class="gold-line mb-1.5"></div>
                    <h2 class="font-display text-lg font-bold text-[#15233d]">
                        Syarat &amp; Ketentuan
                    </h2>
                </div>

                <button type="button" data-tutup-syarat aria-label="Tutup"
                    class="flex h-8 w-8 items-center justify-center rounded-full text-xl text-gray-400 transition hover:bg-gray-100 hover:text-[#15233d]">
                    &times;
                </button>
            </div>

            <div class="overflow-y-auto px-5 py-4 space-y-5">

                {{-- Cara Voting --}}
                <div>
                    <h3 class="text-sm font-semibold text-[#15233d]">
                        Cara Voting
                    </h3>

                    <ol class="mt-2.5 grid gap-2">
                        <li class="flex items-center gap-3 rounded-lg bg-[#faf8f2] p-2.5 text-xs text-[#15233d]">
                            <span
                                class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-white font-display text-sm font-bold text-[#c99a32] shadow-sm">
                                1
                            </span>
                            Pilih kandidat favorit Anda.
                        </li>

                        <li class="flex items-center gap-3 rounded-lg bg-[#faf8f2] p-2.5 text-xs text-[#15233d]">
                            <span
                                class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-white font-display text-sm font-bold text-[#c99a32] shadow-sm">
                                2
                            </span>
                            Tekan tombol Vote ({{ $rp($event->harga_per_vote) }} per vote) dan selesaikan pembayaran.
                        </li>

                        <li class="flex items-center gap-3 rounded-lg bg-[#faf8f2] p-2.5 text-xs text-[#15233d]">
                            <span
                                class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-white font-display text-sm font-bold text-[#c99a32] shadow-sm">
                                3
                            </span>
                            Suara tercatat dan langsung masuk ke perolehan kandidat.
                        </li>
                    </ol>
                </div>

                @if (count($ketentuanVoting) > 0)
                    <div>
                        <h3 class="text-sm font-semibold text-[#15233d]">
                            Syarat &amp; Ketentuan Event
                        </h3>

                        <ul
                            class="mt-2.5 space-y-1.5 pl-5 text-xs leading-relaxed text-gray-600 marker:text-[#c99a32] list-disc">
                            @foreach ($ketentuanVoting as $ketentuan)
                                <li>{{ $ketentuan }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Jadwal Event --}}
                <div>
                    <h3 class="text-sm font-semibold text-[#15233d]">
                        Jadwal Event
                    </h3>

                    <ol class="relative mt-3 space-y-4 border-l border-[#e8dcc0] pl-5">
                        @foreach ($jadwal as $tahap)
                            <li class="relative">
                                <span
                                    class="absolute -left-[25px] top-1 h-2.5 w-2.5 rounded-full border-2 border-white bg-[#c99a32] shadow-sm"></span>

                                <p class="text-xs font-semibold text-[#15233d]">
                                    {{ $tahap['nama'] }}
                                </p>

                                <p class="mt-0.5 text-[11px] text-gray-500">
                                    {{ $tahap['tanggal'] }}
                                </p>
                            </li>
                        @endforeach
                    </ol>
                </div>

                {{-- Informasi Event --}}
                <div>
                    <h3 class="text-sm font-semibold text-[#15233d]">
                        Informasi Event
                    </h3>

                    <div class="mt-1 divide-y divide-gray-100">
                        <div class="flex justify-between gap-4 py-2 text-xs">
                            <span class="text-gray-500">Periode Voting</span>
                            <span class="text-right font-medium text-[#15233d]">{{ $periode }}</span>
                        </div>

                        @if ($event->lokasi)
                            <div class="flex justify-between gap-4 py-2 text-xs">
                                <span class="text-gray-500">Tempat</span>
                                <span class="text-right font-medium text-[#15233d]">{{ $event->lokasi }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between gap-4 py-2 text-xs">
                            <span class="text-gray-500">Harga / Vote</span>
                            <span class="text-right font-medium text-[#15233d]">
                                {{ $rp($event->harga_per_vote) }} per vote
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-100 px-5 py-3 text-right">
                <button type="button" data-tutup-syarat
                    class="rounded-lg bg-[#15233d] px-5 py-2.5 text-[11px] font-semibold text-white transition hover:-translate-y-0.5 hover:bg-[#1f3357]">
                    Mengerti
                </button>
            </div>
        </div>
    </div>

    <script>
        // Buka / tutup popup Syarat & Ketentuan
        (function() {
            var modal = document.getElementById('modalSyarat');

            function buka() {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }

            function tutup() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }

            document.querySelectorAll('[data-buka-syarat]').forEach(function(el) {
                el.addEventListener('click', buka);
            });

            document.querySelectorAll('[data-tutup-syarat]').forEach(function(el) {
                el.addEventListener('click', tutup);
            });

            modal.addEventListener('click', function(e) {
                if (e.target === modal) tutup();
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') tutup();
            });
        })();

        // ============================================
        // SEARCH + LIHAT SEMUA — TOGGLE
        // ============================================
        (function() {
            var input = document.getElementById('searchKandidat');
            var cards = document.querySelectorAll('.kandidat-card');
            var empty = document.getElementById('emptySearch');
            var btn = document.getElementById('btnLihatSemua');
            var btnText = document.getElementById('btnLihatSemuaText');
            var btnIcon = document.getElementById('btnLihatSemuaIcon');
            var wrapBtn = document.getElementById('wrapBtnLihatSemua');

            if (!input) return;

            var isExpanded = false;
            var batasAwal = 8;
            var totalKandidat = cards.length;

            // === Toggle Lihat Semua ===
            if (btn) {
                btn.addEventListener('click', function() {
                    isExpanded = !isExpanded;
                    var q = input.value.toLowerCase().trim();

                    cards.forEach(function(card) {
                        var idx = parseInt(card.dataset.index);

                        if (q.length > 0) return;

                        if (isExpanded) {
                            card.classList.remove('hidden');
                        } else {
                            if (idx >= batasAwal) {
                                card.classList.add('hidden');
                            }
                        }
                    });

                    if (isExpanded) {
                        btnText.textContent = 'Tampilkan Lebih Sedikit';
                        btnIcon.style.transform = 'rotate(180deg)';
                    } else {
                        btnText.textContent = 'Lihat Semua ' + totalKandidat + ' Kandidat';
                        btnIcon.style.transform = 'rotate(0)';
                    }
                });
            }

            // === Search ===
            input.addEventListener('input', function() {
                var q = this.value.toLowerCase().trim();
                var visible = 0;
                var isSearching = q.length > 0;

                cards.forEach(function(card) {
                    var idx = parseInt(card.dataset.index);
                    var match = card.dataset.nama.includes(q);

                    if (isSearching) {
                        if (match) {
                            card.classList.remove('hidden');
                            visible++;
                        } else {
                            card.classList.add('hidden');
                        }
                    } else {
                        if (idx >= batasAwal && !isExpanded) {
                            card.classList.add('hidden');
                        } else {
                            card.classList.remove('hidden');
                        }
                        visible++;
                    }
                });

                if (wrapBtn) {
                    wrapBtn.style.display = isSearching ? 'none' : '';
                }

                if (empty) {
                    empty.classList.toggle('hidden', visible > 0);
                }
            });
        })();
    </script>
</body>

</html>
