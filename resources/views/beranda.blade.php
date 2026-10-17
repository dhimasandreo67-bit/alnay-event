<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event & Vote Photo - ALNAY.ID</title>
    <link rel="icon" type="image/png" href="{{ asset('images/alnaylogo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        display: ['Georgia', 'Times New Roman', 'serif']
                    },
                    colors: {
                        alnay: '#C9962E'
                    },
                    boxShadow: {
                        card: '0 12px 28px rgba(0,0,0,.16)'
                    }
                }
            }
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        html, body {
        overflow-x: hidden;
        max-width: 100vw;
}
        html { scroll-behavior: smooth; }
        body { background: #fff; font-family: "Inter", ui-sans-serif, system-ui, sans-serif; }
        .display-font { font-family: "Playfair Display", Georgia, serif; }
        .brand-font { font-family: "Cinzel", "Playfair Display", Georgia, serif; letter-spacing: 0.02em; }
        .card-shadow { box-shadow: 0 12px 28px rgba(0, 0, 0, .16); }

        /* ============================================
        MODE LIST — Event Akan Datang
        ============================================ */
        #event-akan-datang-container.view-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        #event-akan-datang-container.view-list .event-card {
            flex-direction: row !important;
            height: auto;
        }
        #event-akan-datang-container.view-list .event-banner-wrapper {
            width: 45% !important;
            height: 250px !important;
            flex-shrink: 0;
        }
        #event-akan-datang-container.view-list .event-card > div:last-child {
            width: 55% !important;
        }

        @media (max-width: 640px) {
            #event-akan-datang-container.view-list .event-card {
                flex-direction: row !important;
            }
            #event-akan-datang-container.view-list .event-banner-wrapper {
                width: 40% !important;
                height: 260px !important;
            }
            #event-akan-datang-container.view-list .event-card > div:last-child {
                width: 60% !important;
            }
            #event-akan-datang-container.view-list .event-card > div:last-child .p-5 {
                padding: 0.75rem !important;
            }
            #event-akan-datang-container.view-list .event-card h3 {
                font-size: 13px !important;
            }
            #event-akan-datang-container.view-list .event-card p {
                font-size: 11px !important;
            }
            #event-akan-datang-container.view-list .flex.gap-2.mt-5 {
                margin-top: 0.5rem !important;
            }
            #event-akan-datang-container.view-list .flex.gap-2.mt-5 a {
                padding: 0.4rem 0.5rem !important;
                font-size: 10px !important;
            }
        }

        #event-akan-datang-container:not(.view-list) {
            display: grid;
        }

        /* ============================================
        MODE LIST — Event Sebelumnya
        ============================================ */
        #event-sebelumnya-container.view-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        #event-sebelumnya-container.view-list .event-card {
            flex-direction: row !important;
            height: auto;
        }
        #event-sebelumnya-container.view-list .event-banner-wrapper {
            width: 45% !important;
            height: 250px !important;
            flex-shrink: 0;
        }
        #event-sebelumnya-container.view-list .event-card > div:last-child {
            width: 55% !important;
        }

        @media (max-width: 640px) {
            #event-sebelumnya-container.view-list .event-card {
                flex-direction: row !important;
            }
            #event-sebelumnya-container.view-list .event-banner-wrapper {
                width: 40% !important;
                height: 260px !important;
            }
            #event-sebelumnya-container.view-list .event-card > div:last-child {
                width: 60% !important;
            }
            #event-sebelumnya-container.view-list .event-card > div:last-child .p-5 {
                padding: 0.75rem !important;
            }
            #event-sebelumnya-container.view-list .event-card h3 {
                font-size: 13px !important;
            }
            #event-sebelumnya-container.view-list .event-card p {
                font-size: 11px !important;
            }
        }

        #event-sebelumnya-container:not(.view-list) {
            display: grid;
        }

        @media (max-width: 640px) {
            #event-sebelumnya-container:not(.view-list) .event-card h3 {
                font-size: 14px !important;
            }

            #event-sebelumnya-container:not(.view-list) .event-card p {
                font-size: 12px !important;
            }

            #event-sebelumnya-container:not(.view-list) .event-banner-wrapper {
                height: 180px !important;
            }
        }

        /* ============================================
        GOLD SHIMMER — Judul Hero
        ============================================ */
        .gold-shimmer {
            display: inline-block;
            background: linear-gradient(110deg,
                #8f681c 0%, #c99a32 20%, #f8df8b 40%, #fff1b3 50%,
                #e0b254 60%, #b88625 80%, #8f681c 100%);
            background-size: 250% 100%;
            background-position: 100% 0;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: goldShimmer 4s ease-in-out infinite;
            filter: drop-shadow(0 2px 6px rgba(201, 154, 50, 0.18));
        }
        @keyframes goldShimmer {
            0% { background-position: 100% 0; }
            50% { background-position: 0% 0; }
            100% { background-position: 100% 0; }
        }

        /* ============================================
        HERO GOLD BORDER — animasi emas keliling
        ============================================ */
        @property --hero-angle {
            syntax: '<angle>';
            initial-value: 0deg;
            inherits: false;
        }
        .hero-gold-border {
            position: absolute;
            inset: -2px;
            border-radius: 18px;
            padding: 2px;
            z-index: 0;
            background: conic-gradient(
                from var(--hero-angle),
                transparent 0deg, transparent 25deg,
                #8b641c 35deg, #c99a32 45deg, #f3cf6b 55deg, #fff4c7 62deg,
                #c99a32 70deg, #8b641c 80deg,
                transparent 95deg, transparent 180deg,
                #8b641c 195deg, #c99a32 205deg, #f3cf6b 215deg, #fff4c7 222deg,
                #c99a32 230deg, #8b641c 240deg,
                transparent 255deg, transparent 360deg
            );
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask-composite: exclude;
            animation: heroGoldFlow 7s linear infinite;
            filter:
                drop-shadow(0 0 3px rgba(201, 154, 50, 0.75))
                drop-shadow(0 0 9px rgba(201, 154, 50, 0.35));
            pointer-events: none;
        }
        @keyframes heroGoldFlow {
            to { --hero-angle: 360deg; }
        }
        .hero-event-card:hover .hero-gold-border {
            animation-duration: 3.5s;
            filter:
                drop-shadow(0 0 4px rgba(201, 154, 50, 0.95))
                drop-shadow(0 0 14px rgba(201, 154, 50, 0.45));
        }
        @media (prefers-reduced-motion: reduce) {
            .reveal-left,
            .reveal-right {
                opacity: 1 !important;
                transform: none !important;
                transition: none !important;
            }
        }

        /* ============================================
        LINE CLAMP — untuk deskripsi + toggle
        ============================================ */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .line-clamp-none {
            display: block;
            -webkit-line-clamp: unset;
            overflow: visible;
        }

        /* ==================== ANIMASI SCROLL ==================== */
        .reveal {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity 0.6s cubic-bezier(0.22, 1, 0.36, 1),
                        transform 0.6s cubic-bezier(0.22, 1, 0.36, 1);
            will-change: opacity, transform;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-left {
            opacity: 0;
            transform: translateX(-60px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), 
                        transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .reveal-left.visible {
            opacity: 1;
            transform: translateX(0);
        }

        .reveal-left,
        .reveal-right {
            opacity: 0;
            transition:
                opacity 0.55s cubic-bezier(0.22, 1, 0.36, 1),
                transform 0.55s cubic-bezier(0.22, 1, 0.36, 1);
            will-change: opacity, transform;
        }

        .reveal-left  { transform: translateX(-28px); }
        .reveal-right { transform: translateX(28px); }

        .reveal-left.is-visible,
        .reveal-right.visible {
            opacity: 1;
            transform: translateX(0);
        }

        .reveal-zoom {
            opacity: 0;
            transform: scale(0.85);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), 
                        transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .reveal-zoom.visible {
            opacity: 1;
            transform: scale(1);
        }

        /* Stagger delay untuk elemen berurutan */
        .stagger-1 { transition-delay: 0.05s; }
        .stagger-2 { transition-delay: 0.1s; }
        .stagger-3 { transition-delay: 0.15s; }
        .stagger-4 { transition-delay: 0.2s; }
        .stagger-5 { transition-delay: 0.25s; }
        .stagger-6 { transition-delay: 0.3s; }
        .stagger-7 { transition-delay: 0.35s; }
        .stagger-8 { transition-delay: 0.4s; }

        .vote-step-card {
            transition: box-shadow 0.3s ease, border-color 0.3s ease, transform 0.3s ease;
        }

        /* Smooth scroll untuk seluruh halaman */
        html {
            scroll-behavior: smooth;
        }

        /* Parallax ringan untuk hero */
        .hero-parallax {
            transition: transform 0.1s linear;
        }
    </style>
</head>

<body class="text-[#17233b] antialiased">
    @include('partials.back-to-top')

    @php
        $tab = request()->query('tab', 'terkini');

        $faq = [
            ['pertanyaan' => 'Apa itu Event & Vote Photo?', 'jawaban' => 'Event & Vote Photo adalah rangkaian ajang budaya ALNAY.ID di mana pengunjung dapat mendukung kandidat favorit mereka melalui sistem vote.'],
            ['pertanyaan' => 'Siapa yang boleh mendaftar?', 'jawaban' => 'Pendaftaran terbuka sesuai syarat dan ketentuan tiap event, umumnya mengacu pada rentang usia dan kategori peserta yang tertera di halaman pendaftaran.'],
            ['pertanyaan' => 'Bagaimana cara memberi vote?', 'jawaban' => 'Buka halaman detail event, pilih kandidat favoritmu, lalu ikuti langkah pembayaran vote yang tersedia di halaman tersebut.'],
            ['pertanyaan' => 'Kapan pemenang diumumkan?', 'jawaban' => 'Pemenang diumumkan setelah masa voting resmi ditutup sesuai jadwal yang tertera pada masing-masing event.'],
        ];
        $caraVote = [
            ['no' => '01', 'judul' => 'Pilih Event',    'isi' => 'Buka event yang sedang berlangsung dari beranda.',           'label' => 'Mulai',   'aksi' => 'Pilih Event'],
            ['no' => '02', 'judul' => 'Pilih Kandidat', 'isi' => 'Lihat daftar kandidat dan buka profil favoritmu.',           'label' => 'Jelajah', 'aksi' => 'Lihat Kandidat'],
            ['no' => '03', 'judul' => 'Vote & Bayar',   'isi' => 'Masuk dengan akun Google, lalu selesaikan pembayaran vote.', 'label' => 'Utama',   'aksi' => 'Berikan Suara', 'utama' => true],
            ['no' => '04', 'judul' => 'Suara Tercatat', 'isi' => 'Suaramu langsung masuk ke perolehan kandidat pilihanmu.',    'label' => 'Selesai', 'aksi' => 'Pantau Peringkat'],
        ];

        $routeDetail = function ($event) {
            if (!$event) return '#';
            return match ($event->status) {
                'akan_datang' => route('detail-event.akan-datang', $event->id),
                'sedang_berlangsung' => route('detail-event.berlangsung', $event->id),
                'telah_berakhir' => route('detail-event.selesai', $event->id),
                default => route('detail-event.berlangsung', $event->id),
            };
        };
    @endphp

    {{-- HEADER --}}
    @include('partials.header')

    {{-- HERO --}}
    <section class="hero-parallax relative overflow-hidden px-5 pt-8 pb-10 md:px-8 md:pt-12 md:pb-6 bg-gradient-to-b from-[#fdfbf7] via-white to-transparent max-w-[1200px] mx-auto">
        <div class="pointer-events-none absolute -top-20 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-[#c99a32]/10 blur-[120px] rounded-full"></div>

        <div class="relative mx-auto flex max-w-[1200px] flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <div class="max-w-[680px]">
                <div class="inline-flex items-center gap-2 rounded-full border border-[#f4ede0] bg-[#fdf8ec] px-3.5 py-1 text-[11px] font-bold uppercase tracking-wider text-[#c99a32] shadow-sm mb-4">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#c99a32] opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-[#c99a32]"></span>
                    </span>
                    Platform Event &amp; Voting Resmi ALNAY.ID
                </div>

                <h1 class="reveal display-font font-bold text-[34px] leading-[1.18] text-[#111827] sm:text-[42px] md:text-[48px] tracking-tight">
                    Selamat Datang di <br class="hidden sm:inline" />
                    <span class="gold-shimmer">Event &amp; Vote Kandidat</span>
                </h1>

                <p class=" reveal mt-4 max-w-[580px] text-[14px] leading-relaxed text-[#555] sm:text-[15px] md:text-[16px]">
                    Temukan event yang sedang berlangsung, dukung peserta favoritmu, dan ikuti hasil suaranya secara langsung.
                </p>
            </div>

            <div class="flex shrink-0 items-center gap-1.5 rounded-2xl bg-[#f5f3ef] p-1.5 border border-[#ebe7df] shadow-inner self-start md:self-end">
                <a href="{{ route('dashboard-user', ['tab' => 'terkini']) }}"
                    class="relative flex items-center gap-2 rounded-xl px-5 py-2.5 text-[13px] font-bold transition-all duration-300 {{ $tab === 'terkini' ? 'bg-[#111827] text-[#d4a63d] shadow-md scale-[1.02]' : 'text-[#6b6b6b] hover:text-[#111827] hover:bg-white/60' }}">
                    @if ($tab === 'terkini')
                        <span class="w-2 h-2 rounded-full bg-[#d4a63d] animate-pulse"></span>
                    @endif
                    Event Terkini
                </a>
                <a href="{{ route('dashboard-user', ['tab' => 'sebelumnya']) }}"
                    class="relative flex items-center gap-2 rounded-xl px-5 py-2.5 text-[13px] font-bold transition-all duration-300 {{ $tab === 'sebelumnya' ? 'bg-[#111827] text-[#d4a63d] shadow-md scale-[1.02]' : 'text-[#6b6b6b] hover:text-[#111827] hover:bg-white/60' }}">
                    @if ($tab === 'sebelumnya')
                        <span class="w-2 h-2 rounded-full bg-[#d4a63d]"></span>
                    @endif
                    Event Sebelumnya
                </a>
            </div>
        </div>
    </section>

    {{-- KONTEN TAB --}}
    <main class="mx-auto max-w-[1200px] px-5 pb-6 md:px-8">

        @if ($tab === 'terkini')

            @php
                $heroEvents = ($eventBerlangsungList ?? collect())->count() > 0
                    ? $eventBerlangsungList
                    : collect([$eventAkanDatangUtama])->filter();

                $heroIsCarousel = $heroEvents->count() > 1;

                $formatAngka = function ($angka) {
                    if ($angka >= 1000000) return round($angka / 1000000, 1) . 'M';
                    if ($angka >= 1000)    return round($angka / 1000, 1) . 'K';
                    return number_format($angka, 0, ',', '.');
                };
            @endphp

            {{-- ==================== HERO CAROUSEL ==================== --}}
            @if ($heroEvents->count() > 0)
                <div class="hero-carousel-wrapper reveal-zoom relative" id="heroCarousel">

                    <div class="hero-carousel-viewport" style="clip-path: inset(0px 0px 0px 0px round 16px);">
                        <div class="hero-carousel-track flex items-stretch transition-transform duration-500 ease-out" style="will-change: transform;">

                            @foreach ($heroEvents as $idx => $eventHero)
                                @php
                                    $heroIsBerlangsung = $eventHero->status === 'sedang_berlangsung';
                                    $sisaWaktu     = $eventHero->stat_sisa_waktu     ?? 0;
                                    $jumlahPeserta = $eventHero->stat_jumlah_peserta ?? 0;
                                    $totalSuara    = $eventHero->stat_total_suara    ?? 0;
                                @endphp

                                <div class="hero-slide flex shrink-0" style="width: 100%; flex: 0 0 100%;" data-slide="{{ $idx }}">

                                    <article class="hero-event-card group relative rounded-[16px] bg-white transition-all duration-300 shadow-md hover:shadow-xl w-full h-full flex flex-col">
                                        <div class="hero-gold-border absolute -inset-[2px] rounded-[18px] z-0"></div>

                                        <div class="relative overflow-hidden rounded-[16px] z-10 flex-1 flex flex-col">

                                            {{-- BANNER IMAGE --}}
                                            <a href="{{ $routeDetail($eventHero) }}"
                                                class="relative block h-[320px] sm:h-[400px] md:h-[480px] overflow-hidden bg-[#0a1526]">
                                                @if ($eventHero->banner)
                                                    <div class="relative h-full w-full overflow-hidden group">
                                                        <img src="{{ asset('storage/' . $eventHero->banner) }}"
                                                            alt="{{ $eventHero->nama }}"
                                                            class="h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-105"
                                                            onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden'); this.nextElementSibling.classList.add('flex');">
                                                        <div class="hidden h-full w-full items-center justify-center text-[#a99260] text-sm"
                                                            style="background: linear-gradient(to bottom right, #efe6d3, #d4c4a8) !important; position: relative; z-index: 10;">
                                                            <div class="text-center p-4">
                                                                <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                                </svg>
                                                                <span class="text-xs">Alnay Event Center</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-[#0a1526] via-[#11223f] to-[#1a2942]">
                                                        <div class="text-center px-4">
                                                            <p class="display-font text-[30px] sm:text-[42px] font-extrabold tracking-wider text-[#c99a32] drop-shadow-md">
                                                                COMING SOON
                                                            </p>
                                                            <p class="mt-2 text-[11px] sm:text-[13px] tracking-[0.3em] font-medium text-[#c5b388] uppercase">
                                                                Alnay Event Center
                                                            </p>
                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="absolute inset-0 bg-gradient-to-t from-[#0a1526]/90 via-[#0a1526]/20 to-transparent"></div>

                                                <div class="absolute top-4 right-4 z-10 sm:top-6 sm:right-6">
                                                    @if ($eventHero->status === 'sedang_berlangsung')
                                                        <span class="inline-flex items-center gap-2 rounded-full border border-emerald-400/30 bg-emerald-950/70 backdrop-blur-md px-4 py-1.5 text-[11px] sm:text-[12px] font-semibold text-emerald-300 shadow-lg">
                                                            <span class="relative flex h-2 w-2">
                                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                                            </span>
                                                            Sedang Berlangsung
                                                        </span>
                                                    @elseif ($eventHero->status === 'akan_datang')
                                                        <span class="inline-flex items-center gap-2 rounded-full border border-blue-400/30 bg-blue-950/70 backdrop-blur-md px-4 py-1.5 text-[11px] sm:text-[12px] font-semibold text-blue-300 shadow-lg">
                                                            <span class="h-2 w-2 rounded-full bg-blue-400 animate-pulse"></span>
                                                            Akan Datang
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-2 rounded-full border border-orange-400/30 bg-orange-950/70 backdrop-blur-md px-4 py-1.5 text-[11px] sm:text-[12px] font-semibold text-orange-300 shadow-lg">
                                                            <span class="h-2 w-2 rounded-full bg-orange-400"></span>
                                                            Telah Berakhir
                                                        </span>
                                                    @endif
                                                </div>
                                            </a>

                                            {{-- INFO SECTION --}}
                                            <div class="flex flex-col gap-6 p-6 sm:p-8 md:flex-row md:items-stretch md:justify-between bg-white relative z-10 flex-1">
                                                <div class="max-w-[580px] flex-1 flex flex-col">
                                                    <a href="{{ $routeDetail($eventHero) }}" class="group/title inline-block">
                                                        <h2 class="display-font text-[22px] sm:text-[26px] md:text-[28px] font-bold leading-tight text-[#0a1526] transition-colors duration-200 group-hover/title:text-[#c99a32]">
                                                            {{ $eventHero->nama }}
                                                        </h2>
                                                    </a>

                                                    @php
                                                        $deskripsi = $eventHero->deskripsi ?: 'Ikuti event bergengsi ini dan berikan dukungan terbaik untuk kandidat favoritmu!';
                                                        $deskripsiId = 'desk-' . $eventHero->id;
                                                    @endphp

                                                    <p id="{{ $deskripsiId }}"
                                                        class="mt-3 text-[13px] sm:text-[14px] leading-relaxed text-[#5c5c5c] line-clamp-2 transition-all duration-300">
                                                        {{ $deskripsi }}
                                                    </p>

                                                    @if (strlen($deskripsi) > 100)
                                                        <button type="button"
                                                            onclick="toggleDeskripsi('{{ $deskripsiId }}', this)"
                                                            class="mt-2 self-start text-[11px] font-semibold text-[#c99a32] hover:text-[#b88625] hover:underline transition">
                                                            Selengkapnya →
                                                        </button>
                                                    @endif

                                                    <div class="mt-auto pt-4 flex flex-wrap items-center gap-4 text-[12px] text-[#554f47]">
                                                        <div class="flex items-center gap-2 rounded-lg bg-[#fcfaf6] border border-[#f0e8d8] px-3 py-1.5">
                                                            <svg class="w-4 h-4 text-[#c99a32]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke-width="2"></rect>
                                                                <line x1="16" y1="2" x2="16" y2="6" stroke-width="2"></line>
                                                                <line x1="8" y1="2" x2="8" y2="6" stroke-width="2"></line>
                                                                <line x1="3" y1="10" x2="21" y2="10" stroke-width="2"></line>
                                                            </svg>
                                                            <span class="font-medium">
                                                                {{ $eventHero->tanggal_mulai?->format('d M Y') }} &ndash; {{ $eventHero->tanggal_selesai?->format('d M Y') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="w-full shrink-0 md:w-[360px] flex flex-col justify-center">
                                                    @if ($heroIsBerlangsung)
                                                        <div class="grid grid-cols-3 gap-2.5">
                                                            <div class="rounded-xl border border-[#ece5d7] bg-[#fcfaf6] p-3 text-center transition-transform hover:-translate-y-0.5 duration-200">
                                                                <p class="text-[10px] uppercase tracking-wider text-[#8a8178] font-semibold">Sisa Waktu</p>
                                                                <p class="mt-1 text-[17px] font-bold text-[#0a1526]">
                                                                    {{ $sisaWaktu }} <span class="text-[10px] font-normal text-[#8a8178]">Hari</span>
                                                                </p>
                                                            </div>
                                                            <div class="rounded-xl border border-[#ece5d7] bg-[#fcfaf6] p-3 text-center transition-transform hover:-translate-y-0.5 duration-200">
                                                                <p class="text-[10px] uppercase tracking-wider text-[#8a8178] font-semibold">Peserta</p>
                                                                <p class="mt-1 text-[17px] font-bold text-[#0a1526]">{{ $jumlahPeserta }}</p>
                                                            </div>
                                                            <div class="rounded-xl border border-[#ece5d7] bg-[#fcfaf6] p-3 text-center transition-transform hover:-translate-y-0.5 duration-200">
                                                                <p class="text-[10px] uppercase tracking-wider text-[#8a8178] font-semibold">Total Suara</p>
                                                                <p class="mt-1 flex items-center justify-center text-[17px] font-bold text-[#c99a32]">
                                                                    @if ($blackoutAktif ?? false)
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                                                        </svg>
                                                                    @else
                                                                        {{ $formatAngka($totalSuara) }}
                                                                    @endif
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <a href="{{ $routeDetail($eventHero) }}"
                                                            class="mt-4 group/btn relative flex items-center justify-center gap-2.5 w-full rounded-xl bg-gradient-to-r from-[#c99a32] to-[#b88625] py-3.5 text-center text-[13px] font-bold text-white shadow-md hover:shadow-lg transition-all duration-300 hover:from-[#b88625] hover:to-[#a3741d] active:scale-[0.99]">
                                                            <span>Lihat Event & Vote Peserta</span>
                                                            <svg class="w-4 h-4 transition-transform duration-300 group-hover/btn:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                                            </svg>
                                                        </a>
                                                    @else
                                                        <div class="rounded-xl border border-[#ece5d7] bg-[#fcfaf6] p-4 text-[12px] text-[#4B463F]">
                                                            <div class="flex items-center gap-2 text-[#0a1526] font-semibold mb-1">
                                                                <svg class="w-4 h-4 text-[#c99a32]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <circle cx="12" cy="12" r="10" stroke-width="2"/>
                                                                    <polyline points="12 6 12 12 16 14" stroke-width="2"/>
                                                                </svg>
                                                                Pendaftaran Dibuka
                                                            </div>
                                                            <p class="text-[#6b6b6b]">Daftar sebelum <span class="font-bold text-[#0a1526]">{{ $eventHero->batas_pendaftaran_tanggal?->format('d M Y') ?? 'segera' }}</span></p>
                                                        </div>

                                                        <a href="{{ $routeDetail($eventHero) }}"
                                                            class="mt-4 group/btn flex items-center justify-center gap-2.5 w-full rounded-xl bg-[#0a1526] py-3.5 text-center text-[13px] font-bold text-white shadow-md hover:bg-[#15243b] transition-all duration-300 active:scale-[0.99]">
                                                            <span>Lihat Detail Event</span>
                                                            <svg class="w-4 h-4 transition-transform duration-300 group-hover/btn:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                                            </svg>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>

                                        </div>
                                    </article>

                                </div>{{-- /hero-slide --}}
                            @endforeach

                        </div>{{-- /hero-carousel-track --}}
                    </div>{{-- /hero-carousel-viewport --}}

                    @if ($heroIsCarousel)
                        <button type="button" id="heroPrev" aria-label="Sebelumnya"
                            class="group/nav absolute top-[160px] sm:top-[200px] md:top-[240px] -translate-y-1/2 left-3 z-20 flex items-center justify-center w-9 h-9 rounded-full bg-white/70 backdrop-blur-sm border border-white/50 shadow-sm hover:bg-white hover:border-[#c99a32]/40 transition-all duration-300">
                            <svg class="w-4 h-4 text-[#0a1526] transition-transform duration-300 group-hover/nav:-translate-x-0.5"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>

                        <button type="button" id="heroNext" aria-label="Selanjutnya"
                            class="group/nav absolute top-[160px] sm:top-[200px] md:top-[240px] -translate-y-1/2 right-3 z-20 flex items-center justify-center w-9 h-9 rounded-full bg-white/70 backdrop-blur-sm border border-white/50 shadow-sm hover:bg-white hover:border-[#c99a32]/40 transition-all duration-300">
                            <svg class="w-4 h-4 text-[#0a1526] transition-transform duration-300 group-hover/nav:translate-x-0.5"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>

                        <div class="hero-dots flex justify-center items-center gap-2 mt-5">
                            @foreach ($heroEvents as $idx => $ev)
                                <button type="button" data-dot="{{ $idx }}" aria-label="Slide {{ $idx + 1 }}"
                                    class="hero-dot relative w-2 h-2 rounded-full transition-all duration-300
                                        {{ $idx === 0
                                            ? 'bg-[#c99a32] ring-4 ring-[#c99a32]/20'
                                            : 'bg-[#d4c4a8] hover:bg-[#c99a32]/60' }}">
                                </button>
                            @endforeach
                        </div>
                    @endif

                </div>{{-- /hero-carousel-wrapper --}}
            @endif

            {{-- ==================== EVENT AKAN DATANG ==================== --}}
            @if ($eventAkanDatangLainnya->count() > 0)
                <section class="mt-12">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-5 reveal-right">
                        <div>
                            <h2 class="reveal-left display-font text-2xl font-bold text-[#111827] md:text-[26px]">Event Akan Datang</h2>
                            <p class="reveal-left stagger-2 mt-0.5 text-[13px] text-[#6b6b6b]">Pilihan event menarik yang siap dibuka mendatang</p>
                        </div>
                        <div class="inline-flex items-center gap-1 self-start sm:self-auto rounded-xl bg-[#f5f3ef] p-1 border border-[#ebe7df]">
                            <button type="button" id="btn-view-grid" title="Tampilan Grid"
                                class="flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-[12px] font-semibold transition-all duration-200 bg-white text-[#0a1526] shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                </svg>
                                <span>Grid</span>
                            </button>
                            <button type="button" id="btn-view-list" title="Tampilan List"
                                class="flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-[12px] font-semibold transition-all duration-200 text-[#777] hover:text-[#0a1526]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                                <span>List</span>
                            </button>
                        </div>
                    </div>

                    {{-- PERBAIKAN: Mengubah grid-cols-2 menjadi grid-cols-1 untuk mode mobile agar responsif & tidak berantakan --}}
                    <div id="event-akan-datang-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 transition-all duration-300">
                        @foreach ($eventAkanDatangLainnya as $event)
                            @php $bukaPendaftaran = (bool) ($event->pendaftaran_aktif ?? false); @endphp

                            <div class="event-card card-shadow rounded-[16px] border border-[#ededed] bg-white overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:border-[#c99a32]/40 hover:shadow-lg flex flex-col reveal stagger-{{ min($idx + 1, 8) }}">
                                <a href="{{ $routeDetail($event) }}" class="event-banner-wrapper relative block h-[200px] w-full shrink-0 bg-[#efe6d3] overflow-hidden group">
                                    @if ($event->banner)
                                        <img src="{{ asset('storage/' . $event->banner) }}" alt="{{ $event->nama }}"
                                            class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="hidden h-full w-full items-center justify-center text-[#a99260] text-sm bg-gradient-to-br from-[#efe6d3] to-[#d4c4a8]">
                                            <div class="text-center p-4">
                                                <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <span class="text-xs">Alnay Event Center</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="h-full w-full flex items-center justify-center text-[#a99260] text-sm bg-gradient-to-br from-[#efe6d3] to-[#d4c4a8]">
                                            <div class="text-center p-4">
                                                <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <span class="text-xs">Belum ada banner</span>
                                            </div>
                                        </div>
                                    @endif
                                    <span class="absolute top-3 left-3 rounded-full bg-blue-500/90 backdrop-blur-md px-3 py-1 text-[10px] font-semibold text-white shadow-md">
                                        ● Akan Datang
                                    </span>
                                </a>

                                <div class="p-5 flex flex-col justify-between flex-1">
                                    <div>
                                        <a href="{{ $routeDetail($event) }}">
                                            <h3 class="text-[16px] font-bold text-[#13223a] hover:text-[#c99a32] transition line-clamp-1">
                                                {{ $event->nama }}
                                            </h3>
                                        </a>
                                        <p class="mt-1.5 text-[12px] text-[#777] line-clamp-2">
                                            {{ \Illuminate\Support\Str::limit($event->deskripsi ?: 'Deskripsi belum diisi.', 100) }}
                                        </p>

                                        <div class="mt-4 space-y-2 text-[11px] text-[#666] bg-[#fdfcf9] p-3 rounded-xl border border-[#f4f1e9]">
                                            @if ($event->batas_pendaftaran_tanggal)
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-3.5 h-3.5 text-[#c99a32] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    <span>Batas Daftar: <strong class="text-[#0a1526]">{{ $event->batas_pendaftaran_tanggal->format('d M Y') }}</strong></span>
                                                </div>
                                            @endif
                                            @if ($event->kuota_peserta)
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-3.5 h-3.5 text-[#c99a32] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    </svg>
                                                    <span>Kuota: <strong class="text-[#0a1526]">{{ $event->kuota_peserta }} Peserta</strong></span>
                                                </div>
                                            @endif
                                            <div class="flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 text-[#c99a32] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span>Pelaksanaan: <strong class="text-[#0a1526]">{{ $event->tanggal_mulai?->format('d M Y') }}</strong></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex gap-2 mt-5">
                                        @if ($bukaPendaftaran)
                                            <a href="{{ route('public.registration', $event) }}"
                                                class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl bg-[#0a1526] hover:bg-[#1a2942] px-4 py-2.5 text-[12px] font-bold text-white transition shadow-sm">
                                                Daftar Peserta
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                                </svg>
                                            </a>
                                        @endif
                                        <a href="{{ $routeDetail($event) }}"
                                            class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl border border-[#e5e1db] bg-white hover:bg-[#faf8f2] hover:border-[#c99a32] px-4 py-2.5 text-[12px] font-semibold text-[#4b463f] transition">
                                            Lihat Syarat &amp; Ketentuan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- ==================== CARA VOTE ==================== --}}
            <section class="mt-12 sm:mt-16">
                <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-12">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#c99a32]/10 border border-[#c99a32]/30 text-[11px] font-bold uppercase tracking-widest text-[#b88625]">
                        Cara Vote
                    </span>
                    <h3 class="reveal display-font mt-3 text-[24px] sm:text-[30px] font-bold text-[#0a1526] leading-tight">
                        Cara Memberikan Vote
                    </h3>
                    <p class="reveal stagger-2 mt-2 text-[13px] sm:text-[14px] text-[#6b6b6b]">
                        Empat langkah mudah untuk mendukung kandidat favoritmu.
                    </p>
                </div>

                <div class="relative grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6">
                    <div class="hidden lg:block absolute top-[52px] left-[10%] right-[10%] h-[2px] bg-gradient-to-r from-amber-200 via-[#c99a32] to-amber-200 z-0 opacity-40"></div>

                    @foreach ($caraVote as $idx => $langkah)
                        @php $utama = $langkah['utama'] ?? false; @endphp

                        <div class="vote-step-card group relative rounded-2xl p-6 hover:-translate-y-2 hover:shadow-xl z-10 overflow-hidden {{ $utama ? 'border border-[#c99a32]/40 bg-gradient-to-b from-white to-[#fcfaf6] shadow-md hover:border-[#c99a32]' : 'border border-[#ece5d7] bg-white shadow-sm hover:border-[#c99a32]/50' }} reveal-{{ $idx % 2 === 0 ? 'left' : 'right' }} stagger-{{ min($idx + 1, 8) }}">
                            <span class="absolute -right-2 -bottom-4 text-7xl font-black text-gray-100/80 group-hover:text-[#c99a32]/10 group-hover:scale-110 transition-all duration-500 pointer-events-none select-none z-0">{{ $langkah['no'] }}</span>

                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-5">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-xl font-bold text-[18px] shadow-md transition-transform duration-300 group-hover:scale-110 {{ $utama ? 'bg-[#c99a32] text-white' : 'bg-[#0a1526] text-[#c99a32] group-hover:bg-[#c99a32] group-hover:text-white' }}">{{ $langkah['no'] }}</div>

                                    @if ($utama)
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold tracking-wider text-amber-700 uppercase bg-amber-100/80 px-2.5 py-1 rounded-md">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-600 animate-pulse"></span>
                                            {{ $langkah['label'] }}
                                        </span>
                                    @else
                                        <span class="text-[10px] font-semibold tracking-wider text-[#a09585] uppercase bg-[#fcfaf6] px-2.5 py-1 rounded-md border border-[#eee8dc]">{{ $langkah['label'] }}</span>
                                    @endif
                                </div>

                                <h4 class="text-[16px] font-bold text-[#0a1526] group-hover:text-[#c99a32] transition-colors">{{ $langkah['judul'] }}</h4>
                                <p class="mt-2 text-[12px] sm:text-[13px] text-[#6b6b6b] leading-relaxed">{{ $langkah['isi'] }}</p>

                                <div class="mt-4 pt-4 border-t border-[#f4f0e8] flex items-center text-[11px] font-semibold text-[#c99a32] {{ $utama ? 'group-hover:text-[#b88625]' : 'opacity-80 group-hover:opacity-100 transition-opacity' }}">
                                    <span>{{ $langkah['aksi'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <p class="mt-8 text-center text-[12px] sm:text-[13px] text-[#8a8178]">
                    Ingin jadi peserta? Cek event berlabel <span class="font-semibold text-[#b88625]">Daftar Peserta</span> di bagian atas.
                </p>
            </section>

            {{-- ==================== PERINGKAT ==================== --}}
            @php
                $tampilPeringkat = false;
                $blackoutMode = $blackoutAktif ?? false;
                $judulPeringkat = 'Peringkat Sementara';
                $subjudulPeringkat = 'Peserta dengan suara terbanyak';
                $namaEventPeringkat = null;
                $badgePeringkat = null;
                $peringkatData = collect();
                $totalSuaraPeringkat = 0;
                $routeAllPeserta = null;

                if (! $blackoutMode && $eventBerlangsung && $peringkatTerkini->count() > 0) {
                    $tampilPeringkat = true;
                    $namaEventPeringkat = $eventBerlangsung->nama;
                    $peringkatData = $peringkatTerkini;
                    $totalSuaraPeringkat = $totalSuara;
                    $routeAllPeserta = $routeDetail($eventBerlangsung);
                } elseif (! $blackoutMode && ! $eventBerlangsung && $eventPeringkatAkhir && $peringkatAkhir->count() > 0) {
                    $tampilPeringkat = true;
                    $judulPeringkat = 'Peringkat Sekarang';
                    $subjudulPeringkat = 'Hasil suara peserta sementara';
                    $namaEventPeringkat = $eventPeringkatAkhir->nama;
                    $badgePeringkat = 'Telah Berakhir';
                    $peringkatData = $peringkatAkhir;
                    $totalSuaraPeringkat = $totalSuaraAkhir;
                    $routeAllPeserta = $routeDetail($eventPeringkatAkhir);
                }
            @endphp

            @if ($blackoutMode)
                <section class="mt-14">
                    <div class="card-shadow overflow-hidden rounded-[16px] border border-[#e8dcc0] bg-gradient-to-br from-[#fdfbf6] via-white to-[#faf6ed]">
                        <div class="relative px-6 pt-10 pb-8 md:px-12 md:pt-14 md:pb-10 text-center">
                            <div class="flex items-center justify-center gap-2 mb-6">
                                <span class="h-px w-12 bg-gradient-to-r from-transparent to-[#c99a32]/40"></span>
                                <svg class="w-3.5 h-3.5 text-[#c99a32]" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z"/>
                                </svg>
                                <span class="h-px w-12 bg-gradient-to-l from-transparent to-[#c99a32]/40"></span>
                            </div>
                            <p class="text-[10px] uppercase tracking-[0.35em] text-[#c99a32] font-bold mb-4">Peringkat Sementara</p>
                            <h2 class="reveal-left isplay-font text-[26px] md:text-[34px] font-bold text-[#0a1526] leading-tight mb-5">
                                Siapa yang akan<br class="hidden sm:block"> membawa pulang mahkota?
                            </h2>
                            <p class="max-w-xl mx-auto text-[13px] md:text-[15px] leading-[1.8] text-[#5a5750]">
                                Untuk menjaga ketegangan menjelang malam puncak, kami merahasiakan peringkat sementara saat ini. Ikuti terus perjalanan para finalis, dan saksikan siapa yang akan tampil sebagai yang terbaik.
                            </p>
                            <div class="mt-8 mb-6 flex items-center justify-center gap-3">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#c99a32]/40"></span>
                                <span class="w-1 h-1 rounded-full bg-[#c99a32]/30"></span>
                                <span class="w-1 h-1 rounded-full bg-[#c99a32]/20"></span>
                            </div>
                            @if (($blackoutEvent ?? null)?->voting_selesai)
                                <div class="inline-flex flex-col items-center gap-2">
                                    <p class="text-[10px] uppercase tracking-[0.25em] text-[#8a8178] font-bold">Malam Puncak</p>
                                    <p class="display-font text-lg md:text-xl font-bold text-[#0a1526]">
                                        {{ $blackoutEvent->voting_selesai->translatedFormat('d F Y') }}
                                    </p>
                                    <p class="text-[12px] text-[#8a8178]">Pukul {{ $blackoutEvent->voting_selesai->format('H:i') }} WIB</p>
                                </div>
                            @endif
                        </div>
                        <div class="border-t border-[#e8dcc0]/60 bg-white/40 px-6 py-4 md:px-12">
                            <div class="flex items-center justify-center gap-2 text-[11px] text-[#8a8178]">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="italic">Peringkat akan diumumkan secara resmi pada malam puncak.</span>
                            </div>
                        </div>
                    </div>
                </section>
            @elseif ($tampilPeringkat)
                <section class="mt-14">
                    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <div class="flex items-center gap-2 min-w-0">
                                    <h2 class="display-font text-2xl font-bold text-[#111827] md:text-[26px]">
                                        {{ $judulPeringkat }} Event Terkini
                                    </h2>
                                    @if ($badgePeringkat)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-black px-2.5 py-1 text-[10px] font-semibold text-white shrink-0"> ● {{ $badgePeringkat }}</span>
                                    @endif
                                </div>
                                @if ($routeAllPeserta)
                                    <a href="{{ $routeAllPeserta }}" class="whitespace-nowrap text-[13px] font-semibold text-[#c99a32] hover:underline shrink-0">
                                        Lihat semua peserta →
                                    </a>
                                @endif
                            </div>
                            <p class="text-[13px] text-[#c99a32] font-semibold mb-1">{{ $namaEventPeringkat }}</p>
                            <p class="text-[12px] text-[#6b6b6b] md:text-[13px] mb-6">{{ $subjudulPeringkat }}</p>
                        </div>

                        <div class="space-y-4">
                            @foreach ($peringkatData as $i => $p)
                                @php
                                    $namaP = $p->namaTampil();
                                    $kata = explode(' ', trim($namaP));
                                    $inisial = strtoupper(substr($kata[0] ?? 'P', 0, 1));
                                    if (count($kata) > 1) $inisial .= strtoupper(substr(end($kata), 0, 1));

                                    $persen = $totalSuaraPeringkat > 0 ? round(($p->total_vote / $totalSuaraPeringkat) * 100, 2) : 0;

                                    $warnaRank = match ($i) {
                                        0 => 'bg-[#fdf3d9] text-[#a37b2a]',
                                        1 => 'bg-[#eef2f7] text-[#5b6b82]',
                                        2 => 'bg-[#fdeee5] text-[#a96335]',
                                        default => 'bg-[#f4f4f2] text-[#8b8e90]',
                                    };
                                @endphp

                                <div class="flex items-center gap-4 p-4 rounded-lg {{ $warnaRank }} reveal stagger-{{ min($idx + 1, 8) }}">
                                    <div class="text-2xl font-bold w-8 text-center shrink-0">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</div>
                                    <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center font-bold text-lg shadow-sm shrink-0">{{ $inisial }}</div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-bold text-[#15233d] truncate">{{ $namaP }}</div>
                                        <div class="hidden md:block mt-2 h-2 rounded-full bg-white/70 overflow-hidden">
                                            <div class="h-full rounded-full bg-gradient-to-r from-[#c99a32] to-[#e0b254]" style="width: {{ $persen }}%"></div>
                                        </div>
                                    </div>
                                    <div class="text-xl font-bold shrink-0 text-[#15233d]">
                                        @if ($blackoutAktif ?? false)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#c99a32] mx-auto" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                            </svg>
                                        @else
                                            @php
                                                $formatHasil = $eventBerlangsung->format_hasil_voting ?? ($eventPeringkatAkhir->format_hasil_voting ?? 'keduanya');
                                                $voteAngka = number_format($p->total_vote, 0, ',', '.');
                                            @endphp
                                            @if ($formatHasil === 'angka')
                                                {{ $voteAngka }}
                                            @elseif ($formatHasil === 'persentase')
                                                {{ number_format($persen, 2, '.', '') }}%
                                            @else
                                                {{ $voteAngka }} <span class="text-sm font-normal text-[#8a8178]">({{ number_format($persen, 2, '.', '') }}%)</span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif

            {{-- ==================== FAQ ==================== --}}
            <section class="mb-6 mt-14">
                <h2 class="reveal-right display-font text-2xl font-bold text-[#111827] md:text-[26px]">Pertanyaan Umum</h2>
                <p class="mt-1 text-[13px] text-[#6b6b6b] md:text-[14px]">Hal yang sering ditanyakan seputar event</p>

                <div class="reveal stagger-{{ $idx + 1 }} mt-5 grid grid-cols-1 sm:grid-cols-2 gap-3.5 items-start">
                    <div class="space-y-3.5">
                        @foreach ($faq as $index => $item)
                            @if ($index % 2 === 0)
                                <div class="faq-item card-shadow rounded-[12px] border border-[#ededed] bg-white overflow-hidden">
                                    <button type="button"
                                        class="faq-trigger w-full flex items-center justify-between gap-3 px-5 py-4 text-left text-[14px] font-semibold text-[#15233d] hover:bg-[#fdfcf9] transition">
                                        <span>{{ $item['pertanyaan'] }}</span>
                                        <span class="faq-icon shrink-0 relative flex items-center justify-center w-6 h-6 rounded-full bg-[#fdf8ec] text-[#c99a32] transition-colors duration-300">
                                            <svg class="icon-plus w-4 h-4 absolute transition-all duration-300 opacity-100 scale-100 rotate-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            <svg class="icon-minus w-4 h-4 absolute transition-all duration-300 opacity-0 scale-50 -rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"></path>
                                            </svg>
                                        </span>
                                    </button>
                                    <div class="faq-content grid grid-rows-[0fr] transition-[grid-template-rows] duration-300 ease-out">
                                        <div class="overflow-hidden">
                                            <div class="px-5 pb-4 pt-3 text-[13px] leading-[1.6] text-[#666] border-t border-[#f4f1e9]">
                                                {{ $item['jawaban'] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="space-y-3.5">
                        @foreach ($faq as $index => $item)
                            @if ($index % 2 !== 0)
                                <div class="faq-item card-shadow rounded-[12px] border border-[#ededed] bg-white overflow-hidden">
                                    <button type="button"
                                        class="faq-trigger w-full flex items-center justify-between gap-3 px-5 py-4 text-left text-[14px] font-semibold text-[#15233d] hover:bg-[#fdfcf9] transition">
                                        <span>{{ $item['pertanyaan'] }}</span>
                                        <span class="faq-icon shrink-0 relative flex items-center justify-center w-6 h-6 rounded-full bg-[#fdf8ec] text-[#c99a32] transition-colors duration-300">
                                            <svg class="icon-plus w-4 h-4 absolute transition-all duration-300 opacity-100 scale-100 rotate-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            <svg class="icon-minus w-4 h-4 absolute transition-all duration-300 opacity-0 scale-50 -rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"></path>
                                            </svg>
                                        </span>
                                    </button>
                                    <div class="faq-content grid grid-rows-[0fr] transition-[grid-template-rows] duration-300 ease-out">
                                        <div class="overflow-hidden">
                                            <div class="px-5 pb-4 pt-3 text-[13px] leading-[1.6] text-[#666] border-t border-[#f4f1e9]">
                                                {{ $item['jawaban'] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </section>

        @else
            {{-- ==================== EVENT SEBELUMNYA ==================== --}}
            <section class="pt-2">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-5">
                    <div>
                        <h2 class="display-font text-2xl font-bold text-[#111827] md:text-[26px]">Event Sebelumnya</h2>
                        <p class="mt-0.5 text-[13px] text-[#9a9a9a] md:text-[14px]">Arsip event dan pemenang</p>
                    </div>

                    @if ($eventSebelumnya->count() > 0)
                        <div class="inline-flex items-center gap-1 self-start sm:self-auto rounded-xl bg-[#f5f3ef] p-1 border border-[#ebe7df]">
                            <button type="button" id="btn-view-grid-prev" title="Tampilan Grid"
                                class="flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-[12px] font-semibold transition-all duration-200 bg-white text-[#0a1526] shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                </svg>
                                <span>Grid</span>
                            </button>
                            <button type="button" id="btn-view-list-prev" title="Tampilan List"
                                class="flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-[12px] font-semibold transition-all duration-200 text-[#777] hover:text-[#0a1526]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                                <span>List</span>
                            </button>
                        </div>
                    @endif
                </div>

                @if ($eventSebelumnya->count() > 0)
                    {{-- PERBAIKAN: Mengubah grid-cols-2 menjadi grid-cols-1 untuk mode mobile pada event sebelumnya --}}
                    <div id="event-sebelumnya-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 transition-all duration-300">
                        @foreach ($eventSebelumnya as $item)
                            <a href="{{ $routeDetail($item) }}"
                                class="event-card card-shadow overflow-hidden rounded-[16px] border border-[#ededed] bg-white transition-all duration-300 hover:-translate-y-1 hover:border-[#c99a32]/40 hover:shadow-lg flex flex-col">

                                <div class="event-banner-wrapper relative h-[220px] w-full shrink-0 bg-[#efe6d3] overflow-hidden">
                                    @if ($item->banner)
                                        <img src="{{ asset('storage/' . $item->banner) }}" alt="{{ $item->nama }}"
                                            class="h-full w-full object-cover transition duration-500 hover:scale-105"
                                            onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden'); this.nextElementSibling.classList.add('flex');">
                                        <div class="hidden h-full w-full items-center justify-center text-[#a99260] text-sm bg-gradient-to-br from-[#efe6d3] to-[#d4c4a8]">
                                            <div class="text-center p-4">
                                                <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <span class="text-xs">Alnay Event Center</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="h-full w-full flex items-center justify-center text-[#a99260] text-sm bg-gradient-to-br from-[#efe6d3] to-[#d4c4a8]">
                                            <div class="text-center p-4">
                                                <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <span class="text-xs">Belum ada banner</span>
                                            </div>
                                        </div>
                                    @endif
                                    <span class="absolute left-4 top-4 rounded-full bg-black/85 backdrop-blur-md px-3 py-1.5 text-[10px] font-semibold text-white shadow-md">
                                        ● Telah Berakhir
                                    </span>
                                </div>

                                <div class="p-5 flex flex-col justify-between flex-1">
                                    <div>
                                        <h3 class="text-[16px] font-bold leading-6 text-[#13223a] line-clamp-1">{{ $item->nama }}</h3>
                                        <p class="mt-1.5 text-[12px] text-[#777] line-clamp-2">
                                            {{ \Illuminate\Support\Str::limit($item->deskripsi ?: 'Deskripsi belum diisi.', 100) }}
                                        </p>

                                        <div class="mt-4 space-y-2 text-[11px] text-[#666] bg-[#fdfcf9] p-3 rounded-xl border border-[#f4f1e9]">
                                            @if ($item->tanggal_selesai)
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-3.5 h-3.5 text-[#c99a32] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    <span>Selesai: <strong class="text-[#0a1526]">{{ $item->tanggal_selesai->format('d M Y') }}</strong></span>
                                                </div>
                                            @endif
                                            @if ($item->lokasi)
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-3.5 h-3.5 text-[#c99a32] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    </svg>
                                                    <span class="line-clamp-1">{{ $item->lokasi }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mt-5">
                                        <span class="inline-flex items-center justify-center gap-2 w-full rounded-xl border border-[#e5e1db] bg-white hover:bg-[#faf8f2] hover:border-[#c99a32] px-4 py-2.5 text-[12px] font-semibold text-[#4b463f] transition">
                                            Lihat Arsip Event
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="card-shadow mt-5 rounded-[10px] border border-dashed border-gray-200 bg-white p-10 text-center">
                        <p class="text-sm font-semibold text-[#15233d]">Belum ada arsip event</p>
                        <p class="mt-1 text-xs text-gray-400">Event yang sudah selesai akan muncul di sini.</p>
                    </div>
                @endif
            </section>
        @endif

    </main>

    {{-- FOOTER --}}
    @include('partials.footer')

    {{-- ==================== SCRIPTS ==================== --}}
    <script>
        /* ============ FAQ ACCORDION ============ */
        document.addEventListener('DOMContentLoaded', function () {
            const faqItems = document.querySelectorAll('.faq-item');

            faqItems.forEach(item => {
                const trigger = item.querySelector('.faq-trigger');
                const content = item.querySelector('.faq-content');
                const iconWrapper = item.querySelector('.faq-icon');
                const iconPlus = item.querySelector('.icon-plus');
                const iconMinus = item.querySelector('.icon-minus');

                trigger.addEventListener('click', () => {
                    const isOpen = content.classList.contains('grid-rows-[1fr]');

                    faqItems.forEach(otherItem => {
                        const otherContent = otherItem.querySelector('.faq-content');
                        const otherIconWrapper = otherItem.querySelector('.faq-icon');
                        const otherPlus = otherItem.querySelector('.icon-plus');
                        const otherMinus = otherItem.querySelector('.icon-minus');

                        otherContent.classList.remove('grid-rows-[1fr]');
                        otherContent.classList.add('grid-rows-[0fr]');

                        otherIconWrapper.classList.remove('bg-[#c99a32]', 'text-white');
                        otherIconWrapper.classList.add('bg-[#fdf8ec]', 'text-[#c99a32]');

                        if (otherPlus) {
                            otherPlus.classList.remove('opacity-0', 'scale-50', 'rotate-90');
                            otherPlus.classList.add('opacity-100', 'scale-100', 'rotate-0');
                        }
                        if (otherMinus) {
                            otherMinus.classList.remove('opacity-100', 'scale-100', 'rotate-0');
                            otherMinus.classList.add('opacity-0', 'scale-50', '-rotate-90');
                        }
                    });

                    if (!isOpen) {
                        content.classList.remove('grid-rows-[0fr]');
                        content.classList.add('grid-rows-[1fr]');

                        iconWrapper.classList.remove('bg-[#fdf8ec]', 'text-[#c99a32]');
                        iconWrapper.classList.add('bg-[#c99a32]', 'text-white');

                        if (iconPlus) {
                            iconPlus.classList.remove('opacity-100', 'scale-100', 'rotate-0');
                            iconPlus.classList.add('opacity-0', 'scale-50', 'rotate-90');
                        }
                        if (iconMinus) {
                            iconMinus.classList.remove('opacity-0', 'scale-50', '-rotate-90');
                            iconMinus.classList.add('opacity-100', 'scale-100', 'rotate-0');
                        }
                    }
                });
            });
        });

        /* ============ VIEW MODE — EVENT AKAN DATANG ============ */
        document.addEventListener('DOMContentLoaded', function() {
            const btnGrid = document.getElementById('btn-view-grid');
            const btnList = document.getElementById('btn-view-list');
            const container = document.getElementById('event-akan-datang-container');

            if (!btnGrid || !btnList || !container) return;

            function applyViewMode(mode) {
                if (mode === 'list') {
                    container.classList.add('view-list');
                    container.classList.remove('grid-cols-1', 'sm:grid-cols-2', 'lg:grid-cols-3');

                    btnList.classList.add('bg-white', 'text-[#0a1526]', 'shadow-sm');
                    btnList.classList.remove('text-[#777]');
                    btnGrid.classList.remove('bg-white', 'text-[#0a1526]', 'shadow-sm');
                    btnGrid.classList.add('text-[#777]');
                } else {
                    container.classList.remove('view-list');
                    container.classList.add('grid-cols-1', 'sm:grid-cols-2', 'lg:grid-cols-3');

                    btnGrid.classList.add('bg-white', 'text-[#0a1526]', 'shadow-sm');
                    btnGrid.classList.remove('text-[#777]');
                    btnList.classList.remove('bg-white', 'text-[#0a1526]', 'shadow-sm');
                    btnList.classList.add('text-[#777]');
                }
                localStorage.setItem('eventViewMode', mode);
            }

            applyViewMode(localStorage.getItem('eventViewMode') || 'grid');

            btnGrid.addEventListener('click', (e) => { e.preventDefault(); applyViewMode('grid'); });
            btnList.addEventListener('click', (e) => { e.preventDefault(); applyViewMode('list'); });
        });

        /* ============ VIEW MODE — EVENT SEBELUMNYA ============ */
        document.addEventListener('DOMContentLoaded', function() {
            const btnGridPrev = document.getElementById('btn-view-grid-prev');
            const btnListPrev = document.getElementById('btn-view-list-prev');
            const containerPrev = document.getElementById('event-sebelumnya-container');

            if (!btnGridPrev || !btnListPrev || !containerPrev) return;

            function applyViewModePrev(mode) {
                if (mode === 'list') {
                    containerPrev.classList.add('view-list');
                    containerPrev.classList.remove('grid-cols-1', 'sm:grid-cols-2', 'lg:grid-cols-3');

                    btnListPrev.classList.add('bg-white', 'text-[#0a1526]', 'shadow-sm');
                    btnListPrev.classList.remove('text-[#777]');
                    btnGridPrev.classList.remove('bg-white', 'text-[#0a1526]', 'shadow-sm');
                    btnGridPrev.classList.add('text-[#777]');
                } else {
                    containerPrev.classList.remove('view-list');
                    containerPrev.classList.add('grid-cols-1', 'sm:grid-cols-2', 'lg:grid-cols-3');

                    btnGridPrev.classList.add('bg-white', 'text-[#0a1526]', 'shadow-sm');
                    btnGridPrev.classList.remove('text-[#777]');
                    btnListPrev.classList.remove('bg-white', 'text-[#0a1526]', 'shadow-sm');
                    btnListPrev.classList.add('text-[#777]');
                }
                localStorage.setItem('eventViewModePrev', mode);
            }

            applyViewModePrev(localStorage.getItem('eventViewModePrev') || 'grid');

            btnGridPrev.addEventListener('click', (e) => { e.preventDefault(); applyViewModePrev('grid'); });
            btnListPrev.addEventListener('click', (e) => { e.preventDefault(); applyViewModePrev('list'); });
        });

        /* ============ HERO CAROUSEL — LOOP MULUS PAKAI CLONE ============ */
        document.addEventListener('DOMContentLoaded', function () {
            const track = document.querySelector('.hero-carousel-track');
            if (!track) return;

            const slides = track.querySelectorAll('.hero-slide');
            const total = slides.length;
            if (total <= 1) return;

            // ✅ Clone slide pertama — taruh di akhir — biar loop mulus
            const firstClone = slides[0].cloneNode(true);
            firstClone.classList.add('hero-slide-clone');
            track.appendChild(firstClone);

            // ✅ Clone slide terakhir — taruh di awal — biar prev mulus
            const lastClone = slides[total - 1].cloneNode(true);
            lastClone.classList.add('hero-slide-clone');
            track.insertBefore(lastClone, slides[0]);

            // Total sekarang = total + 2 (clone di awal & akhir)
            const allSlides = track.querySelectorAll('.hero-slide');
            const totalWithClones = allSlides.length;

            let current = 1;   // ← mulai dari slide 1 asli (index 1 — karena clone di index 0)
            let timer = null;
            const INTERVAL = 5000;
            const realTotal = total;

            const dots    = document.querySelectorAll('.hero-dot');
            const btnPrev = document.getElementById('heroPrev');
            const btnNext = document.getElementById('heroNext');

            // Set posisi awal — ke slide asli pertama (index 1)
            track.style.transform = `translateX(-${current * 100}%)`;

            function updateDots(idx) {
                dots.forEach((d, i) => {
                    if (i === idx) {
                        d.classList.add('bg-[#c99a32]', 'ring-4', 'ring-[#c99a32]/20');
                        d.classList.remove('bg-[#d4c4a8]', 'hover:bg-[#c99a32]/60');
                    } else {
                        d.classList.remove('bg-[#c99a32]', 'ring-4', 'ring-[#c99a32]/20');
                        d.classList.add('bg-[#d4c4a8]', 'hover:bg-[#c99a32]/60');
                    }
                });
            }

            function render(animate = true) {
                if (animate) {
                    track.style.transition = 'transform 500ms ease-out';
                } else {
                    track.style.transition = 'none';
                }
                track.style.transform = `translateX(-${current * 100}%)`;

                // Update dots — pakai index asli
                const realIndex = (current - 1 + realTotal) % realTotal;
                updateDots(realIndex);
            }

            function next() {
                if (current >= totalWithClones - 1) return;   // safety
                current++;
                render(true);
            }

            function prev() {
                if (current <= 0) return;   // safety
                current--;
                render(true);
            }

            // ✅ SETELAH ANIMASI SELESAI — cek apakah di clone — pindah instan ke slide asli
            track.addEventListener('transitionend', function (e) {
                if (e.propertyName !== 'transform') return;

                // Kalau di clone terakhir (index totalWithClones - 1 = clone-1) — pindah instan ke slide asli [1] (index 1)
                if (current === totalWithClones - 1) {
                    track.style.transition = 'none';
                    current = 1;
                    track.style.transform = `translateX(-${current * 100}%)`;
                    void track.offsetHeight;   // paksa reflow
                    track.style.transition = '';
                }
                // Kalau di clone awal (index 0 = clone-3) — pindah instan ke slide asli terakhir (index realTotal)
                else if (current === 0) {
                    track.style.transition = 'none';
                    current = realTotal;
                    track.style.transform = `translateX(-${current * 100}%)`;
                    void track.offsetHeight;
                    track.style.transition = '';
                }
            });

            function start() {
                stop();
                timer = setInterval(next, INTERVAL);
            }
            function stop() {
                if (timer) { clearInterval(timer); timer = null; }
            }

            btnNext?.addEventListener('click', () => { next(); start(); });
            btnPrev?.addEventListener('click', () => { prev(); start(); });

            dots.forEach(d => {
                d.addEventListener('click', () => {
                    const idx = parseInt(d.dataset.dot, 10);
                    current = idx + 1;   // ← +1 karena clone di awal
                    render(true);
                    start();
                });
            });

            const wrapper = document.getElementById('heroCarousel');
            wrapper?.addEventListener('mouseenter', stop);
            wrapper?.addEventListener('mouseleave', start);

            let startX = 0;
            track.addEventListener('touchstart', e => {
                startX = e.touches[0].clientX;
            }, { passive: true });

            track.addEventListener('touchend', e => {
                const diff = startX - e.changedTouches[0].clientX;
                if (Math.abs(diff) > 50) {
                    diff > 0 ? next() : prev();
                    start();
                }
            }, { passive: true });

            document.addEventListener('keydown', e => {
                if (e.key === 'ArrowLeft')  { prev(); start(); }
                if (e.key === 'ArrowRight') { next(); start(); }
            });

            render(false);   // ← init tanpa animasi
            start();
        });

        /* ============ TOGGLE DESKRIPSI ============ */
        function toggleDeskripsi(id, btn) {
            const el = document.getElementById(id);
            if (!el) return;

            const isExpanded = el.classList.contains('line-clamp-none');

            if (isExpanded) {
                el.classList.remove('line-clamp-none');
                el.classList.add('line-clamp-2');
                btn.textContent = 'Selengkapnya →';
            } else {
                el.classList.remove('line-clamp-2');
                el.classList.add('line-clamp-none');
                btn.textContent = 'Sembunyikan ↑';
            }
        }

        // ==================== ANIMASI SCROLL (Intersection Observer) ====================
        document.addEventListener('DOMContentLoaded', function() {
            const revealElements = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-zoom');
            
            const revealOptions = {
                threshold: 0.15,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const revealObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    } else {
                        entry.target.classList.remove('visible');
                    }
                });
            }, revealOptions);
            
            revealElements.forEach(el => revealObserver.observe(el));
            
            const heroSection = document.querySelector('.hero-parallax');
            if (heroSection) {
                window.addEventListener('scroll', function() {
                    const scrolled = window.scrollY;
                    if (scrolled < 800) {
                        heroSection.style.transform = `translateY(${scrolled * 0.3}px)`;
                    }
                });
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const revealElements = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-zoom');

            if (!('IntersectionObserver' in window)) {
                revealElements.forEach(el => el.classList.add('visible'));
                return;
            }

            const revealObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(entry => {
                    if (!entry.isIntersecting) return;

                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                });
            }, {
                threshold: 0.12,
                rootMargin: '0px 0px -40px 0px'
            });

            revealElements.forEach(el => revealObserver.observe(el));
        });
    </script>
</body>

</html>