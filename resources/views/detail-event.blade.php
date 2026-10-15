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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        html, body {
            overflow-x: hidden;
            max-width: 100vw;
        }

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
            animation: bannerFadeIn 1s ease-out both;
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
            0%, 100% { background-position: 0% 0; }
            50% { background-position: 100% 0; }
        }

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
            0%, 100% { background-position: 100% 0; }
            50% { background-position: 0% 0; }
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
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: .65; transform: scale(1.15); }
        }

        /* ============================================ */
        /* REVEAL UP — ENTRANCE ANIMATION                */
        /* "backwards" (bukan "both") supaya transform   */
        /* hover tidak ditimpa nilai akhir animasi       */
        /* ============================================ */
        .reveal-up {
            animation: revealUp .6s cubic-bezier(.22, 1, .36, 1) backwards;
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

        .reveal-up.delay-1 { animation-delay: .05s; }
        .reveal-up.delay-2 { animation-delay: .12s; }
        .reveal-up.delay-3 { animation-delay: .19s; }
        .reveal-up.delay-4 { animation-delay: .26s; }

        /* ============================================ */
        /* SECTION REVEAL                                */
        /* ============================================ */
        .section-reveal {
            animation: sectionReveal .8s cubic-bezier(.22, 1, .36, 1) backwards;
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
            from { opacity: 0; }
            to { opacity: 1; }
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
            *, *::before, *::after {
                animation-duration: .01ms !important;
                transition-duration: .01ms !important;
            }
        }

        /* ============================================ */
        /* PAGINASI DAFTAR KANDIDAT                      */
        /* ============================================ */
        .pg-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            height: 34px;
            padding: 0 10px;
            border-radius: 999px;
            border: 1px solid #e8dfc8;
            background: #fff;
            color: #8a6a1f;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: transform .25s ease, background .25s ease, border-color .25s ease, box-shadow .25s ease;
        }

        .pg-btn:hover:not(:disabled):not(.is-active) {
            border-color: #c99a32;
            background: #faf8f2;
            transform: translateY(-1px);
        }

        .pg-btn.is-active {
            background: linear-gradient(90deg, #c69220, #b58219);
            border-color: transparent;
            color: #fff;
            box-shadow: 0 6px 14px -6px rgba(198, 146, 32, .6);
            cursor: default;
        }

        .pg-btn:disabled {
            opacity: .4;
            cursor: not-allowed;
        }

        .pg-dots {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 20px;
            color: #b5ad9c;
            font-size: 12px;
            letter-spacing: .1em;
        }

        @media (max-width: 400px) {
            .pg-btn {
                min-width: 32px;
                height: 32px;
                padding: 0 8px;
            }
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        /* ============================================ */
        /* TOP 3 SWIPER — COVERFLOW (Mobile) / SLIDE     */
        /* ============================================ */
        .top3Swiper {
            overflow: hidden !important;
            padding: 20px 0 50px 0;
            margin: 0 -12px;
            width: calc(100% + 24px);
        }

        .top3Swiper .swiper-wrapper {
            align-items: center;
        }

        /* ---- MOBILE: coverflow blur ---- */
        @media (max-width: 767px) {
            .top3Swiper .swiper-slide {
                width: 80%;
                max-width: none;
                transition: all .45s cubic-bezier(.22, 1, .36, 1);
                opacity: 0.35;
                filter: blur(3px);
                transform: scale(.85);
            }

            .top3Swiper .swiper-slide-active {
                opacity: 1;
                filter: blur(0);
                transform: scale(1);
                z-index: 10;
            }

            .top3Swiper .swiper-slide-next,
            .top3Swiper .swiper-slide-prev {
                opacity: 0.55;
                filter: blur(1.5px);
                transform: scale(.9);
            }

            .top3Swiper .top3Pagination {
                position: absolute;
                bottom: 10px;
                left: 0;
                right: 0;
                display: flex;
                justify-content: center;
                gap: 6px;
            }

            .top3Swiper .top3Pagination .swiper-pagination-bullet {
                width: 6px;
                height: 6px;
                background: #d4c4a8;
                opacity: 1;
                transition: all .3s ease;
                margin: 0 !important;
            }

            .top3Swiper .top3Pagination .swiper-pagination-bullet-active {
                width: 20px;
                border-radius: 99px;
                background: #c99a32;
            }
        }

        /* ---- DESKTOP/TABLET: 3 kolom biasa, tanpa blur ---- */
        @media (min-width: 768px) {
            .top3Swiper {
                padding: 20px 0 30px 0;
            }

            /* Desktop: tidak digeser, 3 kartu statis di tengah */
            .top3Swiper .swiper-wrapper {
                display: flex;
                justify-content: center;
                gap: 20px;
                transform: none !important;
            }

            .top3Swiper .swiper-slide {
                width: auto;
                flex: 1 1 0;
                min-width: 0;
                max-width: 300px;
                margin-right: 0 !important;
                opacity: 1;
                filter: none;
                transform: none;
            }

            .top3Swiper .swiper-slide-active,
            .top3Swiper .swiper-slide-next,
            .top3Swiper .swiper-slide-prev {
                opacity: 1;
                filter: none;
                transform: none;
            }

            .top3Swiper .top3Pagination {
                display: none !important;
            }
        }

        @media (max-width: 640px) {
            .top3Swiper .swiper-slide {
                width: 82%;
                max-width: none;
            }

            #gridKandidat .kandidat-card .relative.h-\[175px\] {
                height: 140px !important;
            }

            #gridKandidat .kandidat-card h3 {
                font-size: 12px !important;
            }

            #gridKandidat .kandidat-card .p-3\.5 {
                padding: 0.6rem !important;
            }

            #gridKandidat .kandidat-card a.detail-button {
                padding-top: 0.5rem !important;
                padding-bottom: 0.5rem !important;
                font-size: 10px !important;
            }
        }
    </style>
</head>

<body class="text-[#15233d]">

    @php
        // =====================================================
        // HELPER
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
        $blackout = (bool) ($blackoutAktif ?? false);

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

        // ---- Jadwal Event (alur baru: Pendaftaran → Audisi → Registrasi → Voting → Pengumuman) ----
        $bukaDaftar = $event->pembukaan_pendaftaran_tanggal
            ? \Carbon\Carbon::parse($event->pembukaan_pendaftaran_tanggal, $tz)->locale('id')
            : null;

        $batasDaftar = $event->batas_pendaftaran_tanggal
            ? \Carbon\Carbon::parse($event->batas_pendaftaran_tanggal, $tz)->locale('id')
            : null;

        $audisiTgl = !empty($event->audisi_tanggal)
            ? \Carbon\Carbon::parse($event->audisi_tanggal, $tz)->locale('id')
            : null;

        $jadwal = [];

        if ($batasDaftar) {
            $jadwal[] = [
                'nama' => 'Pendaftaran Kandidat',
                'tanggal' => $bukaDaftar
                    ? $rentang($bukaDaftar, $batasDaftar)
                    : 'Sampai ' . $batasDaftar->translatedFormat('j F Y'),
            ];
        } elseif ($mulai) {
            $jadwal[] = [
                'nama' => 'Pendaftaran Kandidat',
                'tanggal' => 'Sudah ditutup',
            ];
        }

        // Audisi hanya tampil kalau event ini memang memakai audisi
        if ($event->ada_audisi ?? true) {
            $jadwal[] = [
                'nama' => 'Audisi',
                'tanggal' => $audisiTgl ? $audisiTgl->translatedFormat('j F Y') : 'Jadwal diumumkan admin',
            ];
        }

        $jadwal[] = [
            'nama' => 'Registrasi Peserta',
            'tanggal' => $selesai
                ? 'Sebelum voting berakhir (' . $selesai->translatedFormat('j F Y') . ')'
                : 'Sebelum voting berakhir',
        ];

        $jadwal[] = [
            'nama' => 'Voting Publik',
            'tanggal' => $periode,
        ];

        $jadwal[] = [
            'nama' => 'Pengumuman Pemenang',
            'tanggal' => 'Setelah voting berakhir',
        ];

        // ---- Hitung mundur ----
        $cdLabel = $event->voting_aktif ? ($selesai ? 'Sisa Waktu Voting' : 'Voting Sedang Berjalan') : 'Voting Selesai';
        $detik = $selesai ? max(0, $selesai->timestamp - $now->timestamp) : 0;
        $tampilCountdown = $event->voting_aktif && $selesai && $detik > 0;

        $cdHari = intdiv($detik, 86400);
        $cdJam = intdiv($detik % 86400, 3600);
        $cdMenit = intdiv($detik % 3600, 60);
        $cdDetik = $detik % 60;

        // ---- Kandidat ----
        // Nomor di pojok kartu = peringkat (urutan suara).
        // Saat blackout, kartu diurutkan berdasarkan id dan nomor diganti "?"
        // supaya tidak membocorkan peringkat.
        $kandidat = $pesertas
            ->map(function ($p) use ($inisial) {
                $nama = $p->namaTampil();

                return [
                    'id' => $p->id,
                    'nama' => $nama,
                    'foto' => $p->user?->foto_custom ? asset('storage/' . $p->user->foto_custom) : $p->user?->foto_url,
                    'inisial' => $inisial($nama),
                    'vote_raw' => (int) $p->total_vote,
                    'vote' => number_format($p->total_vote, 0, ',', '.'),
                ];
            })
            ->values();

        // Saat blackout, urutan kartu mengikuti id (bukan suara)
        if ($blackout) {
            $kandidat = $kandidat->sortBy('id')->values();
        }

        $kandidat = $kandidat->all();
    @endphp

    {{-- HEADER --}}
    @include('partials.header')

    <main class="mx-auto max-w-5xl px-5 pb-7">

        {{-- EVENT BANNER --}}
        <section
            class="event-banner group relative mx-0 mt-4 overflow-hidden rounded-[20px] shadow-[0_18px_40px_-12px_rgba(21,35,61,.25)]">
            @if ($event->banner)
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
                <div class="grid grid-cols-1 gap-5 lg:grid-cols-[1fr_220px]">

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

                        <div class="relative mt-2 max-w-3xl">
                            <p id="deskripsiEvent"
                                style="transition: max-height .45s cubic-bezier(.22, 1, .36, 1)"
                                class="max-h-[78px] overflow-hidden text-xs leading-relaxed text-gray-500 md:max-h-none md:overflow-visible md:text-sm">
                                {{ $event->deskripsi ?: 'Deskripsi belum diisi.' }}
                            </p>

                            {{-- Efek pudar di bawah teks yang terpotong (mobile) --}}
                            <div id="deskripsiFade"
                                class="pointer-events-none absolute inset-x-0 bottom-0 h-9 bg-gradient-to-t from-white via-white/80 to-transparent opacity-0 transition-opacity duration-300 md:hidden">
                            </div>
                        </div>

                        {{-- Hanya mobile, dan hanya muncul kalau teks terpotong (diatur JS) --}}
                        <button type="button" id="btnSelengkapnya" aria-expanded="false"
                            class="mt-1.5 hidden items-center gap-1 text-[11px] font-semibold text-[#a37b2a] transition hover:text-[#c99a32] md:hidden">
                            <span id="btnSelengkapnyaText">Lihat selengkapnya</span>
                            <svg id="btnSelengkapnyaIcon" class="h-3 w-3 transition-transform duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div class="mt-3 flex flex-col items-start gap-1.5 sm:flex-row sm:flex-wrap sm:items-center">
                            {{-- Baris 1: tanggal --}}
                            <span
                                class="inline-flex items-center gap-1.5 rounded-full border border-[#eee8da] bg-[#faf8f2] px-2.5 py-1 text-[10px] text-gray-600">
                                <svg class="h-3 w-3 text-[#c99a32]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" stroke-width="2">
                                    <rect x="3" y="4.5" width="18" height="17" rx="2" />
                                    <path d="M16 2.5v4M8 2.5v4M3 9h18" />
                                </svg>
                                {{ $periode }}
                            </span>

                            {{-- Baris 2: biaya vote + tempat --}}
                            <div class="flex flex-wrap items-center gap-1.5">
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
                        </div>

                        <button type="button" data-buka-syarat
                            class="group mt-3 inline-flex items-center gap-1 text-[11px] font-semibold text-[#a37b2a] transition hover:text-[#c99a32]">
                            Lihat Syarat &amp; Ketentuan
                            <span class="transition-transform group-hover:translate-x-0.5">→</span>
                        </button>
                    </div>

                    {{-- COUNTDOWN --}}
                    <div class="relative mt-8 self-start overflow-hidden rounded-xl border border-white/10 bg-gradient-to-br from-[#0a1526] to-[#15243b] p-4">
                        {{-- Glow gold di pojok --}}
                        <div
                            class="pointer-events-none absolute -top-10 -right-10 h-32 w-32 rounded-full bg-[#c99a32]/20 blur-3xl">
                        </div>

                        <div class="relative z-10">
                            {{-- Header Label Status --}}
                            <div class="flex items-center justify-between border-b border-white/10 pb-3">
                                <span
                                    class="flex items-center gap-1.5 text-[10px] font-semibold uppercase tracking-wider text-[#c5b388]">
                                    <span class="h-2 w-2 rounded-full bg-[#c99a32]"></span>
                                    {{ $cdLabel }}
                                </span>
                                <span class="text-[10px] font-medium text-gray-400">Timer</span>
                            </div>

                            {{-- Countdown Display (berjalan real-time) --}}
                            @if ($tampilCountdown)
                                <div id="countdown" data-target="{{ $selesai->timestamp * 1000 }}"
                                    class="mt-4 grid grid-cols-4 gap-1.5">
                                    @foreach ([['cdHari', 'Hari', $cdHari], ['cdJam', 'Jam', $cdJam], ['cdMenit', 'Menit', $cdMenit], ['cdDetik', 'Detik', $cdDetik]] as [$cdId, $cdNama, $cdNilai])
                                        <div
                                            class="countdown-item rounded-lg border border-white/10 bg-white/5 px-1 py-2.5 text-center backdrop-blur-sm hover:bg-white/10">
                                            <span id="{{ $cdId }}"
                                                class="font-display block text-lg font-bold leading-none tracking-tight text-[#f5d78e] tabular-nums">{{ sprintf('%02d', $cdNilai) }}</span>
                                            <span
                                                class="mt-1 block text-[8px] font-semibold uppercase tracking-wider text-gray-400">{{ $cdNama }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @elseif (!$event->voting_aktif)
                                <p class="mt-4 rounded-lg border border-white/10 bg-white/5 px-3 py-3 text-center text-[11px] leading-relaxed text-[#c5b388]">
                                    Voting telah berakhir. Terima kasih atas partisipasinya.
                                </p>
                            @endif

                            {{-- Stat Boxes --}}
                            <div class="mt-4 grid grid-cols-2 gap-2 border-t border-white/10 pt-3">
                                <div
                                    class="rounded-lg border border-white/10 bg-white/5 px-2 py-2.5 text-center backdrop-blur-sm transition-transform duration-200 hover:scale-105 hover:bg-white/10">
                                    <p class="font-display text-base font-bold text-[#f5d78e]">
                                        {{ $jumlahPeserta }}
                                    </p>
                                    <p class="mt-0.5 text-[9px] font-medium uppercase tracking-wider text-gray-400">
                                        Kandidat
                                    </p>
                                </div>

                                <div
                                    class="rounded-lg border border-white/10 bg-white/5 px-2 py-2.5 text-center backdrop-blur-sm transition-transform duration-200 hover:scale-105 hover:bg-white/10">
                                    @if ($blackout)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-5 w-5 text-[#f5d78e]"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                        </svg>
                                        <p class="mt-0.5 text-[9px] font-medium uppercase tracking-wider text-gray-400">
                                            Total Vote
                                        </p>
                                    @else
                                        <p class="font-display text-base font-bold text-[#f5d78e]">{{ $formatAngka($totalSuara) }}</p>
                                        <p class="mt-0.5 text-[9px] font-medium uppercase tracking-wider text-gray-400">Total Vote</p>
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
            $top3Raw = $pesertas->take(3);

            // Tag juara ASLI sebelum reorder
            $top3Raw->each(function ($p, $i) {
                $p->juara_asli = $i + 1;
            });

            $totalUntukPersen = $totalSuara > 0 ? $totalSuara : 1;

            // Reorder: juara 2 - juara 1 - juara 3
            $top3 = collect();
            if ($top3Raw->count() >= 2) {
                $top3->push($top3Raw[1]);
            }
            if ($top3Raw->count() >= 1) {
                $top3->push($top3Raw[0]);
            }
            if ($top3Raw->count() >= 3) {
                $top3->push($top3Raw[2]);
            }

            // Peringkat 4 – 6 (daftar ringkas di bawah Top 3)
            $peringkat46 = $pesertas->slice(3, 3)->values();
        @endphp

        @if ($blackoutAktif ?? false)
            <section class="section-reveal mt-7">
                <div
                    class="overflow-hidden rounded-[18px] border border-[#e8dcc0] bg-gradient-to-br from-[#fdfbf6] via-white to-[#faf6ed] shadow-sm">
                    <div class="relative px-6 pt-10 pb-8 text-center md:px-12 md:pt-12 md:pb-10">
                        <div class="mb-5 flex items-center justify-center gap-2">
                            <span class="h-px w-12 bg-gradient-to-r from-transparent to-[#c99a32]/40"></span>
                            <svg class="h-4 w-4 text-[#c99a32]" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z" />
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
                <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between px-5">
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
                    <div class="swiper top3Swiper">
                        <div class="swiper-wrapper">

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
                                        $totalUntukPersen > 0
                                            ? round(($p->total_vote / $totalUntukPersen) * 100, 2)
                                            : 0;
                                    $juara = $p->id === $top3Raw[0]->id ? 1 : ($p->id === $top3Raw[1]->id ? 2 : 3);
                                    $border =
                                        $juara == 1
                                            ? 'border-2 border-[#c99a32] shadow-md'
                                            : 'border border-[#ece5d7] shadow-sm hover:border-[#c99a32]/40';
                                    $height = $juara == 1 ? 'h-[230px] sm:h-[270px]' : 'h-[210px] sm:h-[210px]';
                                @endphp

                                <div class="swiper-slide">
                                    <div
                                        class="group bg-white rounded-2xl overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-xl {{ $border }}">

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
                                            <div class="mb-2">
                                                <h3 class="font-bold text-[14px] text-[#0a1526] truncate">
                                                    {{ $namaP }}</h3>
                                            </div>

                                            <div class="flex items-baseline gap-1.5">
                                                @php
                                                    $formatHasil = $event->format_hasil_voting ?? 'keduanya';
                                                    $voteP = number_format($p->total_vote, 0, ',', '.');
                                                @endphp
                                                @if ($formatHasil === 'angka')
                                                    <p class="text-lg font-bold text-[#0a1526]">{{ $voteP }}
                                                    </p>
                                                    <span class="text-[11px] text-[#8a8178]">Suara</span>
                                                @elseif ($formatHasil === 'persentase')
                                                    <p class="text-lg font-bold text-[#0a1526]">{{ $persenP }}%
                                                    </p>
                                                    <span class="text-[11px] text-[#8a8178]">Vote</span>
                                                @else
                                                    <p class="text-lg font-bold text-[#0a1526]">{{ $voteP }}
                                                        <span
                                                            class="text-sm font-normal text-[#8a8178]">({{ $persenP }}%)</span>
                                                    </p>
                                                @endif
                                            </div>

                                            <div class="w-full h-1.5 bg-[#f0ece2] rounded-full mt-2.5 overflow-hidden">
                                                <div class="h-full bg-gradient-to-r from-[#c99a32] to-[#e0b254] rounded-full transition-all duration-700"
                                                    style="width: {{ $persenP }}%"></div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endforeach

                        </div>

                        {{-- Pagination dots --}}
                        @if ($top3->count() > 1)
                            <div class="top3Pagination"></div>
                        @endif
                    </div>
                @endif

                {{-- ============================================ --}}
                {{-- PERINGKAT 4 – 6 (daftar ringkas)              --}}
                {{-- Ikut tersembunyi saat blackout karena berada  --}}
                {{-- di cabang @else yang sama dengan Top 3.       --}}
                {{-- ============================================ --}}
                @if ($peringkat46->count() > 0)
                    <div class="mt-1 overflow-hidden rounded-2xl border border-[#ece5d7] bg-white shadow-sm">
                        @foreach ($peringkat46 as $i => $ps)
                            @php
                                $rank = $i + 4;
                                $namaR = $ps->namaTampil();
                                $fotoR = $ps->user?->foto_custom
                                    ? asset('storage/' . $ps->user->foto_custom)
                                    : $ps->user?->foto_url;
                                $persenR = $totalUntukPersen > 0 ? round(($ps->total_vote / $totalUntukPersen) * 100, 1) : 0;
                                $voteR = number_format($ps->total_vote, 0, ',', '.');
                                $formatR = $event->format_hasil_voting ?? 'keduanya';
                            @endphp

                            <div
                                class="flex items-center gap-3 px-4 py-3 sm:gap-4 sm:px-5 {{ $loop->first ? '' : 'border-t border-[#f3efe4]' }}">

                                {{-- Nomor peringkat --}}
                                <span class="w-6 shrink-0 font-display text-lg text-[#b5ad9c] sm:w-8 sm:text-xl">
                                    {{ str_pad($rank, 2, '0', STR_PAD_LEFT) }}
                                </span>

                                {{-- Foto / inisial --}}
                                <div
                                    class="h-10 w-10 shrink-0 overflow-hidden rounded-lg border border-[#ece5d7] bg-[#fbf6e9]">
                                    @if ($fotoR)
                                        <img src="{{ $fotoR }}" alt="{{ $namaR }}" loading="lazy"
                                            class="h-full w-full object-cover">
                                    @else
                                        <div
                                            class="flex h-full w-full items-center justify-center text-[11px] font-bold text-[#c99a32]">
                                            {{ $inisial($namaR) }}
                                        </div>
                                    @endif
                                </div>

                                {{-- Nama + bar --}}
                                <div class="min-w-0 flex-1 sm:flex sm:items-center sm:gap-4">
                                    <div class="min-w-0 sm:w-56 sm:shrink-0">
                                        <h3 class="truncate text-[13px] font-semibold text-[#0a1526]">{{ $namaR }}</h3>
                                    </div>

                                    <div
                                        class="mt-1.5 h-1.5 w-full overflow-hidden rounded-full bg-[#f0ece2] sm:mt-0 sm:flex-1">
                                        <div class="h-full rounded-full bg-gradient-to-r from-[#c99a32] to-[#e0b254]"
                                            style="width: {{ $persenR }}%"></div>
                                    </div>
                                </div>

                                {{-- Nilai --}}
                                <div class="shrink-0 text-right sm:w-24">
                                    @if ($formatR === 'angka')
                                        <span class="text-sm font-bold text-[#0a1526]">{{ $voteR }}</span>
                                        <span class="text-[11px] text-[#8a8178]">suara</span>
                                    @elseif ($formatR === 'persentase')
                                        <span class="text-sm font-bold text-[#0a1526]">{{ $persenR }}%</span>
                                    @else
                                        <span class="text-sm font-bold text-[#0a1526]">{{ $persenR }}%</span>
                                        <span class="block text-[10px] text-[#8a8178]">{{ $voteR }} suara</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>
        @endif

        {{-- CANDIDATE SECTION --}}
        <section id="daftarKandidat" class="section-reveal mt-7 scroll-mt-20">
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

                <div class="relative w-full shrink-0 lg:w-60">
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
                <div id="gridKandidat" class="mt-3.5 grid grid-cols-2 gap-3 sm:grid-cols-2 sm:gap-3.5 lg:grid-cols-4">

                    @foreach ($kandidat as $index => $item)
                        @php
                            $delayClass = 'delay-' . (($index % 4) + 1);
                            $isHidden = $index >= 8;
                            $formatCard = $event->format_hasil_voting ?? 'keduanya';
                            $persenCard = $totalSuara > 0 ? round(($item['vote_raw'] / $totalSuara) * 100, 1) : 0;
                        @endphp
                        <div class="kandidat-card {{ $isHidden ? 'hidden' : '' }} reveal-up {{ $delayClass }} group overflow-hidden rounded-xl border border-[#ebe8e1] bg-white"
                            data-nama="{{ mb_strtolower($item['nama']) }}" data-index="{{ $index }}">

                            <div class="relative h-[175px] overflow-hidden bg-[#fbf6e9]">
                                @if ($item['foto'])
                                    <img src="{{ $item['foto'] }}" alt="{{ $item['nama'] }}" loading="lazy"
                                        class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                        onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden'); this.nextElementSibling.classList.add('flex');">
                                    <div
                                        class="hidden h-full w-full items-center justify-center bg-gradient-to-br from-[#faf5e9] to-[#f2e7cb]">
                                        <span class="font-display text-4xl font-bold text-[#c99a32]">{{ $item['inisial'] }}</span>
                                    </div>
                                @else
                                    <div
                                        class="flex h-full w-full items-center justify-center bg-gradient-to-br from-[#faf5e9] to-[#f2e7cb]">
                                        <span class="font-display text-4xl font-bold text-[#c99a32]">{{ $item['inisial'] }}</span>
                                    </div>
                                @endif

                                <div
                                    class="pointer-events-none absolute inset-x-0 bottom-0 h-14 bg-gradient-to-t from-black/25 to-transparent">
                                </div>

                                @if ($blackout)
                                    <span
                                        class="absolute left-2.5 top-2.5 flex h-7 w-7 items-center justify-center rounded-full bg-white/95 font-display text-[13px] font-bold text-gray-400 shadow-md ring-1 ring-white/60 backdrop-blur"
                                        title="Nomor urut dirahasiakan">
                                        ?
                                    </span>
                                @else
                                    <span
                                        class="absolute left-2.5 top-2.5 flex h-7 w-7 items-center justify-center rounded-full bg-white/95 font-display text-[11px] font-bold text-[#c99a32] shadow-md ring-1 ring-white/60 backdrop-blur">
                                        {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                @endif
                            </div>

                            <div class="p-3.5">
                                <h3 class="truncate text-[13px] font-semibold text-[#15233d]">
                                    {{ $item['nama'] }}
                                </h3>

                                @if ($blackout)
                                    <div class="mt-2 flex items-center gap-1.5 text-[10px] italic text-amber-600">
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

                                        @if ($formatCard === 'angka')
                                            <span>{{ $item['vote'] }} suara</span>
                                        @elseif ($formatCard === 'persentase')
                                            <span>{{ $persenCard }}%</span>
                                        @else
                                            <span>{{ $item['vote'] }} ({{ $persenCard }}%)</span>
                                        @endif
                                    </div>
                                @endif

                                <a href="{{ url('/peserta/' . $item['id']) }}"
                                    class="detail-button mt-3 block w-full rounded-lg bg-gradient-to-r from-[#c69220] to-[#b58219] py-2.5 text-center text-[11px] font-semibold tracking-wide text-white shadow-sm">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- PAGINASI — dibangun oleh JS (disembunyikan otomatis kalau cuma 1 halaman) --}}
                <div class="mt-5">
                    <nav id="paginasiKandidat" aria-label="Halaman kandidat"
                        class="hidden flex-wrap items-center justify-center gap-1.5"></nav>
                    <p id="infoHalaman" class="mt-2.5 hidden text-center text-[11px] text-[#8a8178]"></p>
                </div>

                <div id="emptySearch"
                    class="mt-3.5 hidden rounded-xl border border-dashed border-[#ddd8cc] bg-white p-8 text-center">
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
        class="fixed inset-0 z-[60] hidden items-end justify-center bg-[#07101d]/55 backdrop-blur-sm sm:items-center sm:px-5">

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

            <div class="space-y-5 overflow-y-auto px-5 py-4">

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
                            class="mt-2.5 list-disc space-y-1.5 pl-5 text-xs leading-relaxed text-gray-600 marker:text-[#c99a32]">
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

    <!-- Swiper JS — HARUS DI ATAS init -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
    // ============================================
    // MODAL SYARAT & KETENTUAN (buka / tutup eksplisit)
    // ============================================
    (function() {
        var modal = document.getElementById('modalSyarat');
        if (!modal) return;

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

        document.querySelectorAll('[data-buka-syarat]').forEach(function(b) {
            b.addEventListener('click', buka);
        });
        document.querySelectorAll('[data-tutup-syarat]').forEach(function(b) {
            b.addEventListener('click', tutup);
        });
        modal.addEventListener('click', function(e) {
            if (e.target === modal) tutup();
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') tutup();
        });
    })();

    // ============================================
    // DESKRIPSI EVENT — LIHAT SELENGKAPNYA (mobile)
    // Terpotong 4 baris + efek pudar, buka/tutup dengan animasi
    // ============================================
    (function() {
        var teks = document.getElementById('deskripsiEvent');
        var fade = document.getElementById('deskripsiFade');
        var btn = document.getElementById('btnSelengkapnya');
        if (!teks || !fade || !btn) return;

        var label = document.getElementById('btnSelengkapnyaText');
        var ikon = document.getElementById('btnSelengkapnyaIcon');
        var BARIS = 4;
        var mq = window.matchMedia('(max-width: 767px)');
        var terbuka = false;
        var timer;

        function tinggiTutup() {
            var gaya = getComputedStyle(teks);
            var lh = parseFloat(gaya.lineHeight);
            if (isNaN(lh)) lh = parseFloat(gaya.fontSize) * 1.6;
            return Math.round(lh * BARIS);
        }

        function tampilTombol(tampil) {
            btn.classList.toggle('hidden', !tampil);
            btn.classList.toggle('inline-flex', tampil);
        }

        // Hitung ulang kondisi (load, resize, ganti orientasi)
        function atur() {
            // Desktop/tablet: teks tampil penuh
            if (!mq.matches) {
                teks.style.maxHeight = '';
                fade.style.opacity = '0';
                tampilTombol(false);
                return;
            }

            var tutup = tinggiTutup();
            var terpotong = teks.scrollHeight > tutup + 2;

            if (!terpotong) {
                teks.style.maxHeight = '';
                fade.style.opacity = '0';
                tampilTombol(false);
                terbuka = false;
                return;
            }

            tampilTombol(true);
            if (terbuka) {
                teks.style.maxHeight = 'none';
                fade.style.opacity = '0';
            } else {
                teks.style.maxHeight = tutup + 'px';
                fade.style.opacity = '1';
            }
        }

        btn.addEventListener('click', function() {
            clearTimeout(timer);

            if (!terbuka) {
                // Buka: animasi sampai tinggi penuh, lalu lepas batas tinggi
                terbuka = true;
                teks.style.maxHeight = teks.scrollHeight + 'px';
                fade.style.opacity = '0';
                timer = setTimeout(function() {
                    if (terbuka) teks.style.maxHeight = 'none';
                }, 480);
            } else {
                // Tutup: mulai dari tinggi penuh supaya animasi tetap mulus
                terbuka = false;
                teks.style.maxHeight = teks.scrollHeight + 'px';
                void teks.offsetHeight; // paksa reflow
                teks.style.maxHeight = tinggiTutup() + 'px';
                fade.style.opacity = '1';
            }

            label.textContent = terbuka ? 'Lihat lebih sedikit' : 'Lihat selengkapnya';
            ikon.style.transform = terbuka ? 'rotate(180deg)' : 'rotate(0)';
            btn.setAttribute('aria-expanded', terbuka);
        });

        atur();
        window.addEventListener('load', atur);
        window.addEventListener('resize', atur);
        if (document.fonts && document.fonts.ready) document.fonts.ready.then(atur);
        if (mq.addEventListener) mq.addEventListener('change', atur);
        else mq.addListener(atur);
    })();

    // ============================================
    // COUNTDOWN REAL-TIME
    // ============================================
    (function() {
        var box = document.getElementById('countdown');
        if (!box) return;

        var target = parseInt(box.dataset.target, 10);
        var el = {
            hari: document.getElementById('cdHari'),
            jam: document.getElementById('cdJam'),
            menit: document.getElementById('cdMenit'),
            detik: document.getElementById('cdDetik'),
        };
        var pad = function(n) { return String(n).padStart(2, '0'); };
        var timer;

        function tick() {
            var sisa = Math.max(0, Math.floor((target - Date.now()) / 1000));
            el.hari.textContent = pad(Math.floor(sisa / 86400));
            el.jam.textContent = pad(Math.floor((sisa % 86400) / 3600));
            el.menit.textContent = pad(Math.floor((sisa % 3600) / 60));
            el.detik.textContent = pad(sisa % 60);
            if (sisa <= 0) clearInterval(timer);
        }

        tick();
        timer = setInterval(tick, 1000);
    })();

    // ============================================
    // DAFTAR KANDIDAT — PENCARIAN + PAGINASI
    // Mobile / tablet (2 kolom) : 6 kartu per halaman (3 baris)
    // Desktop (4 kolom)         : 8 kartu per halaman (2 baris)
    // ============================================
    (function() {
        var input = document.getElementById('searchKandidat');
        var grid = document.getElementById('gridKandidat');
        if (!input || !grid) return;

        var cards = Array.prototype.slice.call(grid.querySelectorAll('.kandidat-card'));
        var empty = document.getElementById('emptySearch');
        var nav = document.getElementById('paginasiKandidat');
        var info = document.getElementById('infoHalaman');
        var seksi = document.getElementById('daftarKandidat');
        var mqDesktop = window.matchMedia('(min-width: 1024px)');

        var page = 1;
        var filtered = cards.slice();

        function perPage() {
            return mqDesktop.matches ? 8 : 6;
        }

        function totalPages() {
            return Math.max(1, Math.ceil(filtered.length / perPage()));
        }

        // Daftar nomor halaman yang ditampilkan: 1 … (n-1) n (n+1) … last
        function daftarHalaman(tp, cur) {
            if (tp <= 5) {
                return Array.from({ length: tp }, function(_, i) { return i + 1; });
            }
            var set = [1, tp, cur - 1, cur, cur + 1].filter(function(n) { return n >= 1 && n <= tp; });
            set = Array.from(new Set(set)).sort(function(a, b) { return a - b; });
            var out = [];
            set.forEach(function(n, i) {
                if (i > 0 && n - set[i - 1] > 1) out.push('...');
                out.push(n);
            });
            return out;
        }

        function tombol(isi, tujuan, opsi) {
            opsi = opsi || {};
            var b = document.createElement('button');
            b.type = 'button';
            b.className = 'pg-btn' + (opsi.aktif ? ' is-active' : '');
            b.innerHTML = isi;
            if (opsi.label) b.setAttribute('aria-label', opsi.label);
            if (opsi.aktif) b.setAttribute('aria-current', 'page');
            if (opsi.nonaktif) b.disabled = true;
            if (!opsi.aktif && !opsi.nonaktif) {
                b.addEventListener('click', function() {
                    page = tujuan;
                    render(true);
                });
            }
            return b;
        }

        var IKON_PREV = '<svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>';
        var IKON_NEXT = '<svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>';

        function bangunNav(tp) {
            nav.innerHTML = '';

            if (tp <= 1) {
                nav.classList.add('hidden');
                nav.classList.remove('flex');
                info.classList.add('hidden');
                return;
            }

            nav.classList.remove('hidden');
            nav.classList.add('flex');

            nav.appendChild(tombol(IKON_PREV, page - 1, { label: 'Halaman sebelumnya', nonaktif: page === 1 }));

            daftarHalaman(tp, page).forEach(function(n) {
                if (n === '...') {
                    var d = document.createElement('span');
                    d.className = 'pg-dots';
                    d.textContent = '…';
                    nav.appendChild(d);
                } else {
                    nav.appendChild(tombol(String(n), n, { aktif: n === page, label: 'Halaman ' + n }));
                }
            });

            nav.appendChild(tombol(IKON_NEXT, page + 1, { label: 'Halaman berikutnya', nonaktif: page === tp }));

            var pp = perPage();
            var dari = (page - 1) * pp + 1;
            var sampai = Math.min(page * pp, filtered.length);
            info.textContent = 'Menampilkan ' + dari + '–' + sampai + ' dari ' + filtered.length + ' kandidat';
            info.classList.remove('hidden');
        }

        function render(gulir) {
            var tp = totalPages();
            if (page > tp) page = tp;
            if (page < 1) page = 1;

            var pp = perPage();
            var awal = (page - 1) * pp;
            var akhir = awal + pp;

            // Sembunyikan semua, lalu tampilkan yang ada di halaman aktif
            var posisi = new Map();
            filtered.forEach(function(c, i) { posisi.set(c, i); });

            cards.forEach(function(card) {
                var i = posisi.has(card) ? posisi.get(card) : -1;
                card.classList.toggle('hidden', !(i >= awal && i < akhir));
            });

            if (empty) empty.classList.toggle('hidden', filtered.length > 0);
            bangunNav(tp);

            if (gulir && seksi) {
                seksi.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        input.addEventListener('input', function() {
            var q = this.value.toLowerCase().trim();
            filtered = cards.filter(function(card) {
                return q === '' || card.dataset.nama.includes(q);
            });
            page = 1;
            render(false);
        });

        // Jumlah kartu per halaman berubah saat ukuran layar berubah
        if (mqDesktop.addEventListener) mqDesktop.addEventListener('change', function() { render(false); });
        else mqDesktop.addListener(function() { render(false); });

        render(false);
    })();

    // ============================================
    // TOP 3 — SWIPER (hanya mobile; desktop statis 3 kolom)
    // ============================================
    (function() {
        if (typeof Swiper === 'undefined') return;

        var el = document.querySelector('.top3Swiper');
        if (!el) return;

        var mq = window.matchMedia('(max-width: 767px)');
        var swiper = null;
        var totalSlides = el.querySelectorAll('.swiper-slide').length;

        function aktifkan() {
            if (swiper) return;
            swiper = new Swiper(el, {
                grabCursor: true,
                centeredSlides: true,
                slidesPerView: 'auto',
                loop: false,
                initialSlide: totalSlides >= 2 ? 1 : 0,

                effect: 'coverflow',
                coverflowEffect: {
                    rotate: 0,
                    stretch: 0,
                    depth: 100,
                    modifier: 1,
                    slideShadows: false,
                },

                pagination: {
                    el: '.top3Pagination',
                    clickable: true,
                },
                keyboard: { enabled: true },
            });
        }

        function matikan() {
            if (!swiper) return;
            swiper.destroy(true, true); // hapus instance + style inline
            swiper = null;
        }

        function sinkron() {
            if (mq.matches) aktifkan();
            else matikan();
        }

        sinkron();
        if (mq.addEventListener) mq.addEventListener('change', sinkron);
        else mq.addListener(sinkron);
    })();
</script>
</body>

</html>