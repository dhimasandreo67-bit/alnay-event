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
    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            background: #fff;
            font-family: "Inter", ui-sans-serif, system-ui, sans-serif;
        }

        .display-font {
            font-family: "Playfair Display", Georgia, serif;
        }

        .brand-font {
            font-family: "Cinzel", "Playfair Display", Georgia, serif;
            letter-spacing: 0.02em;
        }

        .card-shadow {
            box-shadow: 0 12px 28px rgba(0, 0, 0, .16);
        }

        /* ============================================
        MODE LIST — Desktop & Mobile
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

        /* Banner landscape di mode list */
        #event-akan-datang-container.view-list .event-banner-wrapper {
            width: 45% !important;
            height: 250px !important;
            flex-shrink: 0;
        }

        #event-akan-datang-container.view-list .event-card > div:last-child {
            width: 55% !important;
        }

    /* ============================================
       MODE LIST — Mobile (Tetap Horizontal)
    ============================================ */
    @media (max-width: 640px) {
        #event-akan-datang-container.view-list .event-card {
            flex-direction: row !important; /* JANGAN kembali ke column */
        }

        #event-akan-datang-container.view-list .event-banner-wrapper {
            width: 40% !important;
            height: 260px !important; /* Lebih pendek di mobile */
        }

        #event-akan-datang-container.view-list .event-card > div:last-child {
            width: 10% !important;
        }

        /* Compact info di mobile */
        #event-akan-datang-container.view-list .event-card > div:last-child .p-5 {
            padding: 0.75rem !important;
        }

        #event-akan-datang-container.view-list .event-card h3 {
            font-size: 13px !important;
        }

        #event-akan-datang-container.view-list .event-card p {
            font-size: 11px !important;
        }

        #event-akan-datang-container.view-list .event-card .text-\[11px\] {
            font-size: 10px !important;
        }

        /* Tombol lebih kecil di mobile */
        #event-akan-datang-container.view-list .flex.gap-2.mt-5 {
            margin-top: 0.5rem !important;
        }

        #event-akan-datang-container.view-list .flex.gap-2.mt-5 a {
            padding: 0.4rem 0.5rem !important;
            font-size: 10px !important;
        }
    }

    /* ============================================
       MODE GRID — Default (sudah ada di Tailwind)
    ============================================ */
    #event-akan-datang-container:not(.view-list) {
        display: grid;
    }

    /* ============================================
   MODE LIST — Event Sebelumnya (Desktop & Mobile)
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

    /* Banner landscape di mode list */
    #event-sebelumnya-container.view-list .event-banner-wrapper {
        width: 45% !important;
        height: 250px !important;
        flex-shrink: 0;
    }

    #event-sebelumnya-container.view-list .event-card > div:last-child {
        width: 55% !important;
    }

    /* Mode list — Mobile */
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

    /* Mode grid — Default */
    #event-sebelumnya-container:not(.view-list) {
        display: grid;
    }

    .gold-shimmer {
        display: inline-block;

        background: linear-gradient(
            110deg,
            #8f681c 0%,
            #c99a32 20%,
            #f8df8b 40%,
            #fff1b3 50%,
            #e0b254 60%,
            #b88625 80%,
            #8f681c 100%
        );

        background-size: 250% 100%;
        background-position: 100% 0;

        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;

        animation: goldShimmer 4s ease-in-out infinite;

        filter: drop-shadow(0 2px 6px rgba(201, 154, 50, 0.18));
    }

    @keyframes goldShimmer {
        0% {
            background-position: 100% 0;
        }

        50% {
            background-position: 0% 0;
        }

        100% {
            background-position: 100% 0;
        }
    }

    /* =========================================================
    PROPERTY UNTUK ANIMASI SUDUT CONIC-GRADIENT
    ========================================================= */
    @property --hero-angle {
        syntax: '<angle>';
        initial-value: 0deg;
        inherits: false;
    }

    /* =========================================================
    HERO GOLD BORDER
    Cahaya emas mengalir di sepanjang border
    ========================================================= */
    .hero-gold-border {
        position: absolute;
        inset: -2px;
        border-radius: 18px;
        padding: 2px;
        z-index: 0;

        /* Conic gradient yang sudutnya di-animate */
        background: conic-gradient(
            from var(--hero-angle),

            transparent 0deg,
            transparent 25deg,

            #8b641c 35deg,
            #c99a32 45deg,
            #f3cf6b 55deg,
            #fff4c7 62deg,
            #c99a32 70deg,
            #8b641c 80deg,

            transparent 95deg,
            transparent 180deg,

            #8b641c 195deg,
            #c99a32 205deg,
            #f3cf6b 215deg,
            #fff4c7 222deg,
            #c99a32 230deg,
            #8b641c 240deg,

            transparent 255deg,
            transparent 360deg
        );

        /* Mask: tampilkan hanya ring 2px */
        -webkit-mask:
            linear-gradient(#fff 0 0) content-box,
            linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask:
            linear-gradient(#fff 0 0) content-box,
            linear-gradient(#fff 0 0);
        mask-composite: exclude;

        /* Animasi sudut conic-gradient, BUKAN rotate */
        animation: heroGoldFlow 7s linear infinite;

        filter:
            drop-shadow(0 0 3px rgba(201, 154, 50, 0.75))
            drop-shadow(0 0 9px rgba(201, 154, 50, 0.35));

        pointer-events: none;
    }

    /* =========================================================
    ANIMASI — animasikan custom property, bukan transform
    ========================================================= */
    @keyframes heroGoldFlow {
        to {
            --hero-angle: 360deg;
        }
    }

    /* Hover: animasi lebih cepat + glow lebih kuat */
    .hero-event-card:hover .hero-gold-border {
        animation-duration: 3.5s;
        filter:
            drop-shadow(0 0 4px rgba(201, 154, 50, 0.95))
            drop-shadow(0 0 14px rgba(201, 154, 50, 0.45));
    }

    /* Reduced motion */
    @media (prefers-reduced-motion: reduce) {
        .hero-gold-border {
            animation: none;
        }
    }
    </style>
</head>

<body class="text-[#17233b] antialiased">

    @php
        $tab = request()->query('tab', 'terkini');

        $alurEvent = [
            [
                'nomor' => 1,
                'judul' => 'Pendaftaran',
                'deskripsi' => 'Daftarkan diri sebagai peserta atau finalis sebelum batas registrasi ditutup.',
            ],
            [
                'nomor' => 2,
                'judul' => 'Vote Berlangsung',
                'deskripsi' => 'Dukung peserta favoritmu lewat halaman event selama masa voting berlangsung.',
            ],
            [
                'nomor' => 3,
                'judul' => 'Pengumuman Pemenang',
                'deskripsi' => 'Peserta dengan suara tertinggi diumumkan sebagai pemenang event.',
            ],
        ];

        $faq = [
            [
                'pertanyaan' => 'Apa itu Event & Vote Photo?',
                'jawaban' =>
                    'Event & Vote Photo adalah rangkaian ajang budaya ALNAY.ID di mana pengunjung dapat mendukung kandidat favorit mereka melalui sistem vote.',
            ],
            [
                'pertanyaan' => 'Siapa yang boleh mendaftar?',
                'jawaban' =>
                    'Pendaftaran terbuka sesuai syarat dan ketentuan tiap event, umumnya mengacu pada rentang usia dan kategori peserta yang tertera di halaman pendaftaran.',
            ],
            [
                'pertanyaan' => 'Bagaimana cara memberi vote?',
                'jawaban' =>
                    'Buka halaman detail event, pilih kandidat favoritmu, lalu ikuti langkah pembayaran vote yang tersedia di halaman tersebut.',
            ],
            [
                'pertanyaan' => 'Kapan pemenang diumumkan?',
                'jawaban' =>
                    'Pemenang diumumkan setelah masa voting resmi ditutup sesuai jadwal yang tertera pada masing-masing event.',
            ],
        ];

        $routeDetail = function ($event) {
            if (!$event) {
                return '#';
            }
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
    <section class="relative overflow-hidden px-5 pt-8 pb-10 md:px-8 md:pt-12 md:pb-6 bg-gradient-to-b from-[#fdfbf7] via-white to-transparent max-w-[1200px] mx-auto">
        
        {{-- Ambient Ambient Glow Effect (Efek Cahaya Latar) --}}
        <div class="pointer-events-none absolute -top-20 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-[#c99a32]/10 blur-[120px] rounded-full"></div>

        <div class="relative mx-auto flex max-w-[1200px] flex-col gap-6 md:flex-row md:items-end md:justify-between">
            
            {{-- Sisi Kiri: Judul, Tagline, & Trust Badges --}}
            <div class="max-w-[680px]">
                
                {{-- Badge Top Tagline dengan Pulse Animasi --}}
                <div class="inline-flex items-center gap-2 rounded-full border border-[#f4ede0] bg-[#fdf8ec] px-3.5 py-1 text-[11px] font-bold uppercase tracking-wider text-[#c99a32] shadow-sm mb-4">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#c99a32] opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-[#c99a32]"></span>
                    </span>
                    Platform Event &amp; Voting Resmi ALNAY.ID
                </div>

                {{-- Judul Utama dengan Gradasi Warna --}}
                <h1 class="display-font font-bold text-[34px] leading-[1.18] text-[#111827] sm:text-[42px] md:text-[48px] tracking-tight">
                    Ajang Perayaan Budaya &amp; <br class="hidden sm:inline" />

                    <span class="gold-shimmer">
                        Vote Perserta Favorit
                    </span>
                </h1>

                
                {{-- Deskripsi --}}
                <p class="mt-4 max-w-[580px] text-[14px] leading-relaxed text-[#555] sm:text-[15px] md:text-[16px]">
                    Dukung perwakilan terbaik, berikan suaramu secara transparan, dan ikuti setiap momen bersejarah dalam perayaan budaya yang menginspirasi.
                </p>

                {{-- Feature / Trust Badges Interaktif --}}
                <div class="mt-6 flex flex-wrap items-center gap-3 text-[12px] text-[#4b463f] font-medium">
                    <div class="flex items-center gap-2 bg-white/80 backdrop-blur border border-[#ededed] px-3.5 py-1.5 rounded-xl shadow-xs transition hover:border-[#c99a32]/40">

                        <span>Sistem Vote Transparan</span>
                    </div>

                    <div class="flex items-center gap-2 bg-white/80 backdrop-blur border border-[#ededed] px-3.5 py-1.5 rounded-xl shadow-xs transition hover:border-[#c99a32]/40">
                        <span>Hasil Suara Real-time</span>
                    </div>
                </div>
            </div>

            {{-- Sisi Kanan: Tab Navigation Switcher --}}
            <div class="flex shrink-0 items-center gap-1.5 rounded-2xl bg-[#f5f3ef] p-1.5 border border-[#ebe7df] shadow-inner self-start md:self-end">
                <a href="{{ route('dashboard-user', ['tab' => 'terkini']) }}"
                    class="relative flex items-center gap-2 rounded-xl px-5 py-2.5 text-[13px] text-italic font-bold transition-all duration-300 {{ $tab === 'terkini' ? 'bg-[#111827] text-[#d4a63d] shadow-md scale-[1.02]' : 'text-[#6b6b6b] hover:text-[#111827] hover:bg-white/60' }}">
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
                $eventHero = $eventBerlangsung ?? $eventAkanDatangUtama;
                $heroIsBerlangsung = $eventHero && $eventHero->status === 'sedang_berlangsung';

                $formatAngka = function ($angka) {
                    if ($angka >= 1000000) {
                        return round($angka / 1000000, 1) . 'M';
                    }
                    if ($angka >= 1000) {
                        return round($angka / 1000, 1) . 'K';
                    }
                    return number_format($angka, 0, ',', '.');
                };
            @endphp

    @if ($eventHero)
    <!-- ==================== HERO BANNER ==================== -->
    <article class="hero-event-card group relative rounded-[16px] bg-white transition-all duration-300 shadow-md hover:shadow-xl">
    {{-- Rotating Gold Border Layer --}}
    <div class="hero-gold-border absolute -inset-[2px] rounded-[18px] z-0"></div>

    <div class="relative overflow-hidden rounded-[16px] z-10">

        <!-- BANNER IMAGE / COVER -->
        <a href="{{ $routeDetail($eventHero) }}"
            class="relative block h-[320px] sm:h-[400px] md:h-[480px] overflow-hidden bg-[#0a1526]">
            
            @if ($eventHero->banner)
                <div class="relative h-full w-full overflow-hidden group">
                    <img src="{{ asset('storage/' . $eventHero->banner) }}" 
                        alt="{{ $eventHero->nama }}"
                        class="h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-105"
                        onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden'); this.nextElementSibling.classList.add('flex');">
                    
                    {{-- Fallback placeholder jika gambar gagal dimuat --}}
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
                {{-- Tampilan jika memang tidak ada banner di database --}}
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

            <!-- Dark Overlay Gradients -->
            <div class="absolute inset-0 bg-gradient-to-t from-[#0a1526]/90 via-[#0a1526]/20 to-transparent"></div>

            <!-- Floating Status Badges (Glassmorphism Effect) -->
            <div class="absolute top-4 right-4 z-10 sm:top-6 sm:right-6">
    @if ($eventHero->status === 'sedang_berlangsung')
        {{-- Sedang Berlangsung - Hijau dengan animasi pulse --}}
        <span class="inline-flex items-center gap-2 rounded-full border border-emerald-400/30 bg-emerald-950/70 backdrop-blur-md px-4 py-1.5 text-[11px] sm:text-[12px] font-semibold text-emerald-300 shadow-lg">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>
            Sedang Berlangsung
        </span>
    @elseif ($eventHero->status === 'akan_datang')
        {{-- Akan Datang - Biru --}}
        <span class="inline-flex items-center gap-2 rounded-full border border-blue-400/30 bg-blue-950/70 backdrop-blur-md px-4 py-1.5 text-[11px] sm:text-[12px] font-semibold text-blue-300 shadow-lg">
            <span class="h-2 w-2 rounded-full bg-blue-400 animate-pulse"></span>
            Akan Datang
        </span>
    @else
        {{-- Telah Berakhir - Oranye --}}
        <span class="inline-flex items-center gap-2 rounded-full border border-orange-400/30 bg-orange-950/70 backdrop-blur-md px-4 py-1.5 text-[11px] sm:text-[12px] font-semibold text-orange-300 shadow-lg">
            <span class="h-2 w-2 rounded-full bg-orange-400"></span>
            Telah Berakhir
        </span>
    @endif
</div>
        </a>

        <!-- HERO INFO SECTION -->
        <div class="flex flex-col gap-6 p-6 sm:p-8 md:flex-row md:items-center md:justify-between bg-white relative z-10">
            <!-- Left Info -->
            <div class="max-w-[580px] flex-1">
                <a href="{{ $routeDetail($eventHero) }}" class="group/title inline-block">
                    <h2 class="display-font text-[22px] sm:text-[26px] md:text-[28px] font-bold leading-tight text-[#0a1526] transition-colors duration-200 group-hover/title:text-[#c99a32]">
                        {{ $eventHero->nama }}
                    </h2>
                </a>

                <p class="mt-3 text-[13px] sm:text-[14px] leading-relaxed text-[#5c5c5c] line-clamp-2">
                    {{ $eventHero->deskripsi ?: 'Ikuti event bergengsi ini dan berikan dukungan terbaik untuk kandidat favoritmu!' }}
                </p>

                <div class="mt-4 flex flex-wrap items-center gap-4 text-[12px] text-[#554f47]">
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

            <!-- Right Action / Stats Card -->
            <div class="w-full shrink-0 md:w-[360px]">
                @if ($heroIsBerlangsung)
                    <div class="grid grid-cols-3 gap-2.5">
                        <div class="rounded-xl border border-[#ece5d7] bg-[#fcfaf6] p-3 text-center transition-transform hover:-translate-y-0.5 duration-200">
                            <p class="text-[10px] uppercase tracking-wider text-[#8a8178] font-semibold">Sisa Waktu</p>
                            <p class="mt-1 text-[17px] font-bold text-[#0a1526]">
                                {{ $sisaWaktu ?? 0 }} <span class="text-[10px] font-normal text-[#8a8178]">Hari</span>
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
                        class="mt-4 group/btn relative flex items-center justify-center gap-2.5 w-full rounded-xl bg-gradient-to-r from-[#c99a32] to-[#b88625] py-3.5 text-center text-[13px] font-bold text-white shadow-md hover:shadow-lg transition-all duration-300 hover:from-[#b88625] hover:to-[#a3741d] active:scale-[0.99]" id="btn-lihat-event">
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
    </article>
@endif

            {{-- CARD EVENT AKAN DATANG --}}
@if ($eventAkanDatangLainnya->count() > 0)
    <section class="mt-12">
        {{-- Header & Filter Switcher --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-5">
            <div>
                <h2 class="display-font text-2xl font-bold text-[#111827] md:text-[26px]">Event Akan Datang</h2>
                <p class="mt-0.5 text-[13px] text-[#6b6b6b]">Pilihan event menarik yang siap dibuka mendatang</p>
            </div>

            {{-- Filter Toggle List / Grid --}}
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

        {{-- Container Event Akan Datang --}}
        <div id="event-akan-datang-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 transition-all duration-300">
            @foreach ($eventAkanDatangLainnya as $event)
                @php
                    $bukaPendaftaran =
                        $event->batas_pendaftaran_tanggal &&
                        now()->lte($event->batas_pendaftaran_tanggal) &&
                        (!$event->pembukaan_pendaftaran_tanggal ||
                            now()->gte($event->pembukaan_pendaftaran_tanggal));
                @endphp

                <div class="event-card card-shadow rounded-[16px] border border-[#ededed] bg-white overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:border-[#c99a32]/40 hover:shadow-lg flex flex-col">

                    {{-- Banner Event --}}
                    <a href="{{ $routeDetail($event) }}" class="event-banner-wrapper relative block h-[200px] w-full shrink-0 bg-[#efe6d3] overflow-hidden group">
                        @if ($event->banner)
                            <img src="{{ asset('storage/' . $event->banner) }}" alt="{{ $event->nama }}"
                                class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            {{-- Fallback LOKAL (gak manggil layanan eksternal sama sekali), tampil
                                 otomatis kalau file banner gagal dimuat (404, storage:link belum
                                 jalan, dsb). Default-nya 'hidden', di-reveal via JS onerror di atas. --}}
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

                    {{-- Detail Info --}}
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

                        {{-- BUTTONS - SIDE BY SIDE (KIRI KANAN) --}}
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
                                class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl border border-[#e5e1db] bg-white hover:bg-[#faf8f2] hover:border-[#c99a32] px-4 py-2.5 text-[12px] font-semibold text-[#4b463f] transition" id="label-alur-event">
                                Lihat Syarat &amp; Ketentuan
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endif

            <!-- ==================== BAGIAN ALUR EVENT INTERAKTIF ==================== -->
    <section class="mt-12 sm:mt-16">
    <!-- Section Header -->
    <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-12">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#c99a32]/10 border border-[#c99a32]/30 text-[11px] font-bold uppercase tracking-widest text-[#b88625]">
            Alur Event
        </span>
        <h3 class="display-font mt-3 text-[24px] sm:text-[30px] font-bold text-[#0a1526] leading-tight">
            Alur & Langkah Mengikuti Event
        </h3>
        <p class="mt-2 text-[13px] sm:text-[14px] text-[#6b6b6b]">
            Empat langkah mudah untuk berpartisipasi, mendukung kandidat, dan merayakan pemenang.
        </p>
    </div>

    <!-- Interactive Flow Cards Grid -->
    <div class="relative grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6">

        <!-- Connecting Background Line (Desktop Only) -->
        <div class="hidden lg:block absolute top-[52px] left-[10%] right-[10%] h-[2px] bg-gradient-to-r from-amber-200 via-[#c99a32] to-amber-200 z-0 opacity-40"></div>

        <!-- STEP 1 -->
        <div class="group relative rounded-2xl border border-[#ece5d7] bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl hover:border-[#c99a32]/50 z-10 overflow-hidden">
            <!-- WATERMARK ANGKA -->
            <span class="absolute -right-2 -bottom-4 text-7xl font-black text-gray-100/80 group-hover:text-[#c99a32]/10 group-hover:scale-110 transition-all duration-500 pointer-events-none select-none z-0">
                01
            </span>

            <div class="relative z-10">
                <div class="flex items-center justify-between mb-5">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#0a1526] text-[#c99a32] font-bold text-[18px] shadow-md transition-transform duration-300 group-hover:scale-110 group-hover:bg-[#c99a32] group-hover:text-white">
                        01
                    </div>
                    <span class="text-[10px] font-semibold tracking-wider text-[#a09585] uppercase bg-[#fcfaf6] px-2.5 py-1 rounded-md border border-[#eee8dc]">
                        Tahap awal
                    </span>
                </div>
                <h4 class="text-[16px] font-bold text-[#0a1526] group-hover:text-[#c99a32] transition-colors">
                    Pendaftaran & Profil
                </h4>
                <p class="mt-2 text-[12px] sm:text-[13px] text-[#6b6b6b] leading-relaxed">
                    Peserta mendaftarkan diri, mengunggah karya atau profil terbaik sesuai ketentuan event.
                </p>
                <div class="mt-4 pt-4 border-t border-[#f4f0e8] flex items-center text-[11px] font-semibold text-[#c99a32] opacity-80 group-hover:opacity-100 transition-opacity">
                    <span>Lengkapi Data</span>
                </div>
            </div>
        </div>

        <!-- STEP 2 -->
        <div class="group relative rounded-2xl border border-[#ece5d7] bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl hover:border-[#c99a32]/50 z-10 overflow-hidden">
            <!-- WATERMARK ANGKA -->
            <span class="absolute -right-2 -bottom-4 text-7xl font-black text-gray-100/80 group-hover:text-[#c99a32]/10 group-hover:scale-110 transition-all duration-500 pointer-events-none select-none z-0">
                02
            </span>

            <div class="relative z-10">
                <div class="flex items-center justify-between mb-5">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#0a1526] text-[#c99a32] font-bold text-[18px] shadow-md transition-transform duration-300 group-hover:scale-110 group-hover:bg-[#c99a32] group-hover:text-white">
                        02
                    </div>
                    <span class="text-[10px] font-semibold tracking-wider text-[#a09585] uppercase bg-[#fcfaf6] px-2.5 py-1 rounded-md border border-[#eee8dc]">
                        Kurasi
                    </span>
                </div>
                <h4 class="text-[16px] font-bold text-[#0a1526] group-hover:text-[#c99a32] transition-colors">
                    Verifikasi Panitia
                </h4>
                <p class="mt-2 text-[12px] sm:text-[13px] text-[#6b6b6b] leading-relaxed">
                    Panitia melalukan validasi kualifikasi peserta sebelum ditampilkan secara publik di daftar voting.
                </p>
                <div class="mt-4 pt-4 border-t border-[#f4f0e8] flex items-center text-[11px] font-semibold text-[#c99a32] opacity-80 group-hover:opacity-100 transition-opacity">
                    <span>Proses Validasi</span>
                </div>
            </div>
        </div>

        <!-- STEP 3 -->
        <div class="group relative rounded-2xl border border-[#c99a32]/40 bg-gradient-to-b from-white to-[#fcfaf6] p-6 shadow-md transition-all duration-300 hover:-translate-y-2 hover:shadow-xl hover:border-[#c99a32] z-10 overflow-hidden">
            <!-- WATERMARK ANGKA -->
            <span class="absolute -right-2 -bottom-4 text-7xl font-black text-gray-100/80 group-hover:text-[#c99a32]/10 group-hover:scale-110 transition-all duration-500 pointer-events-none select-none z-0">
                03
            </span>

            <div class="relative z-10">
                <div class="flex items-center justify-between mb-5">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#c99a32] text-white font-bold text-[18px] shadow-md transition-transform duration-300 group-hover:scale-110">
                        03
                    </div>
                    <span class="inline-flex items-center gap-1 text-[10px] font-bold tracking-wider text-amber-700 uppercase bg-amber-100/80 px-2.5 py-1 rounded-md">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-600 animate-pulse"></span>
                        Utama
                    </span>
                </div>
                <h4 class="text-[16px] font-bold text-[#0a1526] group-hover:text-[#c99a32] transition-colors">
                    Voting & Dukungan
                </h4>
                <p class="mt-2 text-[12px] sm:text-[13px] text-[#6b6b6b] leading-relaxed">
                    Pendukung dapat memberikan voting secara real-time untuk mendongkrak peringkat kandidat pilihan.
                </p>
                <div class="mt-4 pt-4 border-t border-[#f4f0e8] flex items-center text-[11px] font-semibold text-[#c99a32] group-hover:text-[#b88625]">
                    <span>Kumpulkan Suara</span>
                </div>
            </div>
        </div>

        <!-- STEP 4 -->
        <div class="group relative rounded-2xl border border-[#ece5d7] bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl hover:border-[#c99a32]/50 z-10 overflow-hidden">
            <!-- WATERMARK ANGKA -->
            <span class="absolute -right-2 -bottom-4 text-7xl font-black text-gray-100/80 group-hover:text-[#c99a32]/10 group-hover:scale-110 transition-all duration-500 pointer-events-none select-none z-0">
                04
            </span>

            <div class="relative z-10">
                <div class="flex items-center justify-between mb-5">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#0a1526] text-[#c99a32] font-bold text-[18px] shadow-md transition-transform duration-300 group-hover:scale-110 group-hover:bg-[#c99a32] group-hover:text-white">
                        04
                    </div>
                    <span class="text-[10px] font-semibold tracking-wider text-[#a09585] uppercase bg-[#fcfaf6] px-2.5 py-1 rounded-md border border-[#eee8dc]">
                        Final
                    </span>
                </div>
                <h4 class="text-[16px] font-bold text-[#0a1526] group-hover:text-[#c99a32] transition-colors">
                    Pengumuman Pemenang
                </h4>
                <p class="mt-2 text-[12px] sm:text-[13px] text-[#6b6b6b] leading-relaxed">
                    Pemenang ditentukan berdasarkan akumulasi voting akhir dan kriteria penilaian juri resmi.
                </p>
                <div class="mt-4 pt-4 border-t border-[#f4f0e8] flex items-center text-[11px] font-semibold text-[#c99a32] opacity-80 group-hover:opacity-100 transition-opacity">
                    <span>Pemenang Event</span>
                </div>
            </div>
        </div>

    </div>
</section>

                        {{-- PERINGKAT --}}
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

                                   {{-- ✅ Kalau blackout — notice elegan --}}
            @if ($blackoutMode)
                <section class="mt-14">
                    <div class="card-shadow overflow-hidden rounded-[16px] border border-[#e8dcc0] bg-gradient-to-br from-[#fdfbf6] via-white to-[#faf6ed]">

                        <div class="relative px-6 pt-10 pb-8 md:px-12 md:pt-14 md:pb-10 text-center">

                            {{-- Ornamen atas --}}
                            <div class="flex items-center justify-center gap-2 mb-6">
                                <span class="h-px w-12 bg-gradient-to-r from-transparent to-[#c99a32]/40"></span>
                                <svg class="w-3.5 h-3.5 text-[#c99a32]" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l2.4 7.4H22l-6.2 4.5 2.4 7.4L12 16.8l-6.2 4.5 2.4-7.4L2 9.4h7.6z"/>
                                </svg>
                                <span class="h-px w-12 bg-gradient-to-l from-transparent to-[#c99a32]/40"></span>
                            </div>

                            {{-- Eyebrow --}}
                            <p class="text-[10px] uppercase tracking-[0.35em] text-[#c99a32] font-bold mb-4">
                                Peringkat Sementara
                            </p>

                            {{-- Judul utama — teaser elegan --}}
                            <h2 class="display-font text-[26px] md:text-[34px] font-bold text-[#0a1526] leading-tight mb-5">
                                Siapa yang akan<br class="hidden sm:block"> membawa pulang mahkota?
                            </h2>

                            {{-- Deskripsi puitis --}}
                            <p class="max-w-xl mx-auto text-[13px] md:text-[15px] leading-[1.8] text-[#5a5750]">
                                Untuk menjaga ketegangan menjelang malam puncak, kami merahasiakan
                                peringkat sementara saat ini. Ikuti terus perjalanan para finalis,
                                dan saksikan siapa yang akan tampil sebagai yang terbaik.
                            </p>

                            {{-- Divider --}}
                            <div class="mt-8 mb-6 flex items-center justify-center gap-3">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#c99a32]/40"></span>
                                <span class="w-1 h-1 rounded-full bg-[#c99a32]/30"></span>
                                <span class="w-1 h-1 rounded-full bg-[#c99a32]/20"></span>
                            </div>

                            {{-- Timeline grand final --}}
                            @if (($blackoutEvent ?? null)?->voting_selesai)
                                <div class="inline-flex flex-col items-center gap-2">
                                    <p class="text-[10px] uppercase tracking-[0.25em] text-[#8a8178] font-bold">
                                        Malam Puncak
                                    </p>
                                    <p class="display-font text-lg md:text-xl font-bold text-[#0a1526]">
                                        {{ $blackoutEvent->voting_selesai->translatedFormat('d F Y') }}
                                    </p>
                                    <p class="text-[12px] text-[#8a8178]">
                                        Pukul {{ $blackoutEvent->voting_selesai->format('H:i') }} WIB
                                    </p>
                                </div>
                            @endif
                        </div>

                        {{-- Footer info --}}
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
                                        <span class="inline-flex items-center gap-1 rounded-full bg-black px-2.5 py-1 text-[10px] font-semibold text-white shrink-0"> ● {{ $badgePeringkat }}
                                        </span>
                                    @endif
                                </div>

                                @if ($routeAllPeserta)
                                    <a
                                        href="{{ $routeAllPeserta }}"
                                        class="whitespace-nowrap text-[13px] font-semibold text-[#c99a32] hover:underline shrink-0">
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
                                    $namaP = $p->user->nama ?? 'Peserta';

                                    $kata = explode(' ', trim($namaP));
                                    $inisial = strtoupper(substr($kata[0] ?? 'P', 0, 1));

                                    if (count($kata) > 1) {
                                        $inisial .= strtoupper(substr(end($kata), 0, 1));
                                    }

                                    $persen = $totalSuaraPeringkat > 0
                                        ? round(($p->total_vote / $totalSuaraPeringkat) * 100, 2)
                                        : 0;

                                    $warnaRank = match ($i) {
                                        0 => 'bg-[#fdf3d9] text-[#a37b2a]',
                                        1 => 'bg-[#eef2f7] text-[#5b6b82]',
                                        2 => 'bg-[#fdeee5] text-[#a96335]',
                                        default => 'bg-[#f4f4f2] text-[#8b8e90]',
                                    };
                                @endphp

                                <div class="flex items-center gap-4 p-4 rounded-lg {{ $warnaRank }}">

                                    {{-- Ranking --}}
                                    <div class="text-2xl font-bold w-8 text-center shrink-0">
                                        {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                                    </div>

                                    {{-- Inisial --}}
                                    <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center font-bold text-lg shadow-sm shrink-0">
                                        {{ $inisial }}
                                    </div>

                                    {{-- Nama + Progress Bar --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="font-bold text-[#15233d] truncate">
                                            {{ $namaP }}
                                        </div>

                                        {{-- Progress bar tetap menggunakan data/fungsi kode pertama --}}
                                        <div class="hidden md:block mt-2 h-2 rounded-full bg-white/70 overflow-hidden">
                                            <div
                                                class="h-full rounded-full bg-gradient-to-r from-[#c99a32] to-[#e0b254]"
                                                style="width: {{ $persen }}%"
                                            ></div>
                                        </div>
                                    </div>

                                    {{-- Persentase --}}
                                    <div class="text-xl font-bold shrink-0" id="faq">
                                        {{ number_format($persen, 2, '.', '') }}%
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif

            {{-- FAQ ACCORDION --}}
            <section class="mb-6 mt-14">
                <h2 class="display-font text-2xl font-bold text-[#111827] md:text-[26px]">Pertanyaan Umum</h2>
                <p class="mt-1 text-[13px] text-[#6b6b6b] md:text-[14px]">Hal yang sering ditanyakan seputar event</p>

                {{-- Kontainer 2 Kolom Terpisah (Kiri & Kanan Independen) --}}
                <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-3.5 items-start">
                    
                    {{-- KOLOM KIRI (FAQ Genap: 1, 3, dst) --}}
                    <div class="space-y-3.5">
                        @foreach ($faq as $index => $item)
                            @if ($index % 2 === 0)
                                <div class="faq-item card-shadow rounded-[12px] border border-[#ededed] bg-white overflow-hidden">
                                    <button type="button"
                                        class="faq-trigger w-full flex items-center justify-between gap-3 px-5 py-4 text-left text-[14px] font-semibold text-[#15233d] hover:bg-[#fdfcf9] transition">
                                        <span>{{ $item['pertanyaan'] }}</span>
                                        
                                        <!-- Icon Wrapper -->
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

                    {{-- KOLOM KANAN (FAQ Ganjil: 2, 4, dst) --}}
                    <div class="space-y-3.5">
                        @foreach ($faq as $index => $item)
                            @if ($index % 2 !== 0)
                                <div class="faq-item card-shadow rounded-[12px] border border-[#ededed] bg-white overflow-hidden">
                                    <button type="button"
                                        class="faq-trigger w-full flex items-center justify-between gap-3 px-5 py-4 text-left text-[14px] font-semibold text-[#15233d] hover:bg-[#fdfcf9] transition">
                                        <span>{{ $item['pertanyaan'] }}</span>
                                        
                                        <!-- Icon Wrapper -->
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
            {{-- EVENT SEBELUMNYA --}}
        <section class="pt-2">
            {{-- Header & Filter Switcher --}}
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-5">
                <div>
                    <h2 class="display-font text-2xl font-bold text-[#111827] md:text-[26px]">Event Sebelumnya</h2>
                    <p class="mt-0.5 text-[13px] text-[#9a9a9a] md:text-[14px]">Arsip event dan pemenang</p>
                </div>

                @if ($eventSebelumnya->count() > 0)
                    {{-- Filter Toggle List / Grid --}}
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
                <div id="event-sebelumnya-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 transition-all duration-300">
                    @foreach ($eventSebelumnya as $item)
                        <a href="{{ $routeDetail($item) }}"
                            class="event-card card-shadow overflow-hidden rounded-[16px] border border-[#ededed] bg-white transition-all duration-300 hover:-translate-y-1 hover:border-[#c99a32]/40 hover:shadow-lg flex flex-col">
                            
                            {{-- Banner --}}
                            <div class="event-banner-wrapper relative h-[220px] w-full shrink-0 bg-[#efe6d3] overflow-hidden">
                                @if ($item->banner)
                                    <img src="{{ asset('storage/' . $item->banner) }}" alt="{{ $item->nama }}" 
                                        class="h-full w-full object-cover transition duration-500 hover:scale-105"
                                        onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden'); this.nextElementSibling.classList.add('flex');">

                                    {{-- Fallback: tampil kalau gambar gagal dimuat (404, storage:link belum jalan, dsb) --}}
                                    <div class="hidden h-full w-full items-center justify-center text-[#a99260] text-sm bg-gradient-to-br from-[#efe6d3] to-[#d4c4a8]">
                                        <div class="text-center p-4">
                                            <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span class="text-xs">Alnay Event Center</span>
                                        </div>
                                    </div>
                                @else
                                    {{-- Fallback: tampil kalau memang tidak ada banner di database --}}
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

                            {{-- Detail Info --}}
                            <div class="p-5 flex flex-col justify-between flex-1">
                                <div>
                                    <h3 class="text-[16px] font-bold leading-6 text-[#13223a] line-clamp-1">
                                        {{ $item->nama }}
                                    </h3>
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

                                {{-- Tombol Lihat Arsip --}}
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

    {{-- SCRIPT FAQ ACCORDION --}}
    <script>
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

                    // Tutup item FAQ lainnya (perilaku accordion tunggal)
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

                    // Buka item jika sebelumnya tertutup
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

    document.addEventListener('DOMContentLoaded', function() {
        const btnGrid = document.getElementById('btn-view-grid');
        const btnList = document.getElementById('btn-view-list');
        const container = document.getElementById('event-akan-datang-container');

        if (!btnGrid || !btnList || !container) return;

        // Simpan preferensi di localStorage
        const savedView = localStorage.getItem('eventViewMode') || 'grid';
        applyViewMode(savedView);

        btnGrid.addEventListener('click', function() {
            applyViewMode('grid');
            localStorage.setItem('eventViewMode', 'grid');
        });

        btnList.addEventListener('click', function() {
            applyViewMode('list');
            localStorage.setItem('eventViewMode', 'list');
        });

        function applyViewMode(mode) {
            if (mode === 'list') {
                container.classList.add('view-list');
                container.classList.remove('grid-cols-1', 'sm:grid-cols-2', 'lg:grid-cols-3');

                // Update tombol aktif
                btnList.classList.add('bg-white', 'text-[#0a1526]', 'shadow-sm');
                btnList.classList.remove('text-[#777]');
                btnGrid.classList.remove('bg-white', 'text-[#0a1526]', 'shadow-sm');
                btnGrid.classList.add('text-[#777]');
            } else {
                container.classList.remove('view-list');
                container.classList.add('grid-cols-1', 'sm:grid-cols-2', 'lg:grid-cols-3');

                // Update tombol aktif
                btnGrid.classList.add('bg-white', 'text-[#0a1526]', 'shadow-sm');
                btnGrid.classList.remove('text-[#777]');
                btnList.classList.remove('bg-white', 'text-[#0a1526]', 'shadow-sm');
                btnList.classList.add('text-[#777]');
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const btnGrid = document.getElementById('btn-view-grid');
        const btnList = document.getElementById('btn-view-list');
        const container = document.getElementById('event-akan-datang-container');

        if (!btnGrid || !btnList || !container) return;

        // Fungsi untuk menerapkan mode
        function applyViewMode(mode) {
            if (mode === 'list') {
                container.classList.add('view-list');
                container.classList.remove('grid-cols-1', 'sm:grid-cols-2', 'lg:grid-cols-3');
                
                // Update tombol aktif
                btnList.classList.add('bg-white', 'text-[#0a1526]', 'shadow-sm');
                btnList.classList.remove('text-[#777]');
                btnGrid.classList.remove('bg-white', 'text-[#0a1526]', 'shadow-sm');
                btnGrid.classList.add('text-[#777]');
            } else {
                container.classList.remove('view-list');
                container.classList.add('grid-cols-1', 'sm:grid-cols-2', 'lg:grid-cols-3');
                
                // Update tombol aktif
                btnGrid.classList.add('bg-white', 'text-[#0a1526]', 'shadow-sm');
                btnGrid.classList.remove('text-[#777]');
                btnList.classList.remove('bg-white', 'text-[#0a1526]', 'shadow-sm');
                btnList.classList.add('text-[#777]');
            }
            
            // Simpan preferensi
            localStorage.setItem('eventViewMode', mode);
        }

        // Load preferensi tersimpan
        const savedView = localStorage.getItem('eventViewMode') || 'grid';
        applyViewMode(savedView);

        // Event listener untuk tombol
        btnGrid.addEventListener('click', function(e) {
            e.preventDefault();
            applyViewMode('grid');
        });

        btnList.addEventListener('click', function(e) {
            e.preventDefault();
            applyViewMode('list');
        });
    });

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

        const savedViewPrev = localStorage.getItem('eventViewModePrev') || 'grid';
        applyViewModePrev(savedViewPrev);

        btnGridPrev.addEventListener('click', function(e) {
            e.preventDefault();
            applyViewModePrev('grid');
        });

        btnListPrev.addEventListener('click', function(e) {
            e.preventDefault();
            applyViewModePrev('list');
        });
    });
    </script>

</body>

</html>