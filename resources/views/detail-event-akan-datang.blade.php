<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <title>{{ $event->nama }} - Akan Datang | ALNAY.ID</title>

    <link rel="icon" type="image/png" href="{{ asset('images/alnaylogo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

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
        /* TIMELINE ITEM                                 */
        /* ============================================ */
        .timeline-item {
            transition: transform .25s ease;
        }

        .timeline-item:hover {
            transform: translateX(4px);
        }

        /* ============================================ */
        /* STEP CARD                                     */
        /* ============================================ */
        .step-card {
            transition: transform .35s cubic-bezier(.22, 1, .36, 1),
                box-shadow .35s cubic-bezier(.22, 1, .36, 1),
                border-color .35s ease;
        }

        .step-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 22px 45px -18px rgba(198, 146, 32, .35),
                0 8px 18px -10px rgba(21, 35, 61, .15);
            border-color: #e6d3a7;
        }

        /* ============================================ */
        /* BADGE HOVER                                   */
        /* ============================================ */
        .badge-hover {
            transition: all .25s ease;
        }

        .badge-hover:hover {
            border-color: #c99a32;
            background: #fff;
            transform: translateY(-1px);
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: .01ms !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body class="text-[#15233d]">

    @php
        // =====================================================
        // DATA DARI CONTROLLER
        // =====================================================
        $syaratList = array_values(
            array_filter(array_map('trim', preg_split('/\R/', (string) $event->syarat_ketentuan))),
        );

        // Status pendaftaran — PRIORITAS: Flag admin > Tanggal
        $sekarang = now();
        $bukaDaftar = $event->pembukaan_pendaftaran_tanggal;
        $batasDaftar = $event->batas_pendaftaran_tanggal;

        // 1. Cek manual toggle dari admin terlebih dahulu
        if (isset($event->pendaftaran_dibuka) && !$event->pendaftaran_dibuka) {
            $statusPendaftaran = 'Pendaftaran Ditutup';
            $statusClass = 'bg-rose-500';
        } 
        // 2. Jika tidak di-toggle manual, cek berdasarkan tanggal
        elseif ($bukaDaftar && $sekarang->lt($bukaDaftar)) {
            $statusPendaftaran = 'Belum Dibuka';
            $statusClass = 'bg-gray-500';
        } elseif ($batasDaftar && $sekarang->gt($batasDaftar)) {
            $statusPendaftaran = 'Pendaftaran Ditutup';
            $statusClass = 'bg-rose-500';
        } else {
            $statusPendaftaran = 'Pendaftaran Dibuka';
            $statusClass = 'bg-[#c99a32]';
        }

        $bukaPendaftaran = $statusPendaftaran === 'Pendaftaran Dibuka';

        // Countdown pendaftaran
        if ($batasDaftar && $sekarang->lt($batasDaftar)) {
            $selisih = $sekarang->diff($batasDaftar);
            $sisaHari = $selisih->days;
            $sisaJam = $selisih->h;
            $sisaMenit = $selisih->i;
        } else {
            $sisaHari = $sisaJam = $sisaMenit = null;
        }

        // Jadwal Event
        $jadwal = [];

        if ($batasDaftar) {
            $jadwal[] = [
                'nama'    => 'Pendaftaran Kandidat',
                'tanggal' => $bukaDaftar
                    ? $bukaDaftar->translatedFormat('d M Y') . ' – ' . $batasDaftar->translatedFormat('d M Y')
                    : 'Sampai ' . $batasDaftar->translatedFormat('d M Y'),
            ];

            if ($event->tanggal_mulai) {
                $mulaiVoting = \Carbon\Carbon::parse($event->tanggal_mulai)->locale('id');
                $verifAwal   = $batasDaftar->copy()->addDay()->startOfDay();
                $verifAkhir  = $mulaiVoting->copy()->subDay()->startOfDay();

                if ($verifAwal->lte($verifAkhir)) {
                    $jadwal[] = [
                        'nama'    => 'Verifikasi Data',
                        'tanggal' => $verifAwal->translatedFormat('d M Y') . ' – ' . $verifAkhir->translatedFormat('d M Y'),
                    ];
                }
            }
        } else {
            $jadwal[] = ['nama' => 'Pendaftaran Kandidat', 'tanggal' => '-'];
        }

        $jadwal[] = [
            'nama'    => 'Voting Publik',
            'tanggal' => ($event->tanggal_mulai?->translatedFormat('d M') ?? '-') . ' – ' . ($event->tanggal_selesai?->translatedFormat('d M Y') ?? '-'),
        ];

        $jadwal[] = ['nama' => 'Pengumuman Pemenang', 'tanggal' => 'Setelah voting berakhir'];

        $rp = fn($n) => $n ? 'Rp ' . number_format($n, 0, ',', '.') : 'Gratis';
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

            <div
                class="pointer-events-none absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-[#0a1526]/40 to-transparent">
            </div>

            {{-- Badge status — floating glassmorphism --}}
            <span
                class="absolute top-4 right-4 z-10 inline-flex items-center gap-2 rounded-full border backdrop-blur-md px-4 py-1.5 text-[11px] font-semibold shadow-lg sm:text-[12px]
                {{ $statusPendaftaran === 'Pendaftaran Dibuka'
                    ? 'border-emerald-400/30 bg-emerald-950/70 text-emerald-300'
                    : 'border-rose-400/30 bg-rose-950/70 text-rose-300' }}">
                @if ($statusPendaftaran === 'Pendaftaran Dibuka')
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    Pendaftaran Dibuka
                @else
                    <span class="h-2 w-2 rounded-full bg-rose-400"></span>
                    {{ $statusPendaftaran }}
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
                        <span
                            class="inline-flex items-center gap-1.5 rounded-full bg-[#f8f2e5] px-2.5 py-1 text-[9px] font-semibold uppercase tracking-wider text-[#9b762d]">
                            <span class="h-1.5 w-1.5 rounded-full bg-[#c99a32]"></span>
                            Akan Datang
                        </span>

                        <h1 class="mt-2 font-display text-xl font-bold leading-tight text-[#111827] md:text-2xl">
                            {{ $event->nama }}
                        </h1>

                        <div class="relative mt-2 max-w-3xl" x-data="{ openModal: false }">
                            <p id="deskripsiEvent"
                                style="transition: max-height .45s cubic-bezier(.22, 1, .36, 1)"
                                class="max-h-[78px] overflow-hidden text-xs leading-relaxed text-gray-500 md:max-h-none md:overflow-visible md:text-sm">
                                {{ $event->deskripsi ?: 'Deskripsi belum diisi.' }}
                            </p>

                            <div id="deskripsiFade"
                                class="pointer-events-none absolute inset-x-0 bottom-0 h-9 bg-gradient-to-t from-white via-white/80 to-transparent opacity-0 transition-opacity duration-300 md:hidden">
                            </div>

                            <button type="button" id="btnSelengkapnya" aria-expanded="false"
                                @click="openModal = true"
                                class="mt-1.5 inline-flex items-center gap-1 text-[11px] font-semibold text-[#a37b2a] transition hover:text-[#c99a32] md:hidden">
                                <span id="btnSelengkapnyaText">Lihat selengkapnya</span>
                                <svg id="btnSelengkapnyaIcon" class="h-3 w-3 transition-transform duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            {{-- Modal Deskripsi Lengkap --}}
                            <template x-teleport="body">
                                <div x-show="openModal" x-cloak @keydown.escape.window="openModal = false"
                                    class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
                                    role="dialog" aria-modal="true">
                                    <div x-show="openModal" x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                        @click="openModal = false"
                                        class="fixed inset-0 bg-black/60 backdrop-blur-md z-40 cursor-pointer">
                                    </div>

                                    <div x-show="openModal" x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                                        class="relative z-50 bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[85vh] flex flex-col overflow-hidden my-auto">
                                        
                                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-white shrink-0">
                                            <h3 class="text-base sm:text-lg font-bold text-[#0a1526]">
                                                Deskripsi Lengkap Event
                                            </h3>
                                            <button @click="openModal = false" type="button"
                                                class="p-2 text-gray-400 hover:text-[#c99a32] hover:bg-gray-100 rounded-full transition-all focus:outline-none cursor-pointer">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>

                                        <div class="p-6 overflow-y-auto text-sm text-[#5c5c5c] leading-relaxed whitespace-pre-line">
                                            {{ $event->deskripsi }}
                                        </div>

                                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 text-right shrink-0">
                                            <button @click="openModal = false" type="button"
                                                class="rounded-lg bg-[#15233d] px-5 py-2.5 text-[11px] font-semibold text-white transition hover:-translate-y-0.5 hover:bg-[#1f3357]">
                                                Tutup
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="mt-3 flex flex-col items-start gap-1.5 sm:flex-row sm:flex-wrap sm:items-center">
                            <span
                                class="inline-flex items-center gap-1.5 rounded-full border border-[#eee8da] bg-[#faf8f2] px-2.5 py-1 text-[10px] text-gray-600">
                                <svg class="h-3 w-3 text-[#c99a32]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" stroke-width="2">
                                    <rect x="3" y="4.5" width="18" height="17" rx="2" />
                                    <path d="M16 2.5v4M8 2.5v4M3 9h18" />
                                </svg>
                                Voting mulai: {{ $event->tanggal_mulai?->translatedFormat('d M Y') ?? '-' }}
                            </span>

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
                    </div>

                    {{-- COUNTDOWN / STATUS PENDAFTARAN --}}
                    <div class="relative mt-8 self-start overflow-hidden rounded-xl border border-white/10 bg-gradient-to-br from-[#0a1526] to-[#15243b] p-4">
                        <div
                            class="pointer-events-none absolute -top-10 -right-10 h-32 w-32 rounded-full bg-[#c99a32]/20 blur-3xl">
                        </div>

                        <div class="relative z-10">
                            <div class="flex items-center justify-between border-b border-white/10 pb-3">
                                <span
                                    class="flex items-center gap-1.5 text-[10px] font-semibold uppercase tracking-wider text-[#c5b388]">
                                    @if ($event->pendaftaran_dibuka && $sisaHari !== null)
                                        <span class="relative flex h-2 w-2">
                                            <span
                                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                        </span>
                                    @else
                                        <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                                    @endif
                                    @if (!$event->pendaftaran_dibuka)
                                        Pendaftaran Ditutup
                                    @elseif ($sisaHari !== null)
                                        Ditutup Dalam
                                    @else
                                        Status Pendaftaran
                                    @endif
                                </span>
                                <span class="text-[10px] font-medium text-gray-400">Timer</span>
                            </div>

                            @if (!$event->pendaftaran_dibuka)
                                <div class="py-5 text-center">
                                    <span
                                        class="inline-flex items-center gap-2 rounded-full bg-rose-500/10 border border-rose-500/20 px-3 py-1 text-xs font-semibold text-rose-400">
                                        Pendaftaran Ditutup
                                    </span>
                                </div>
                            @elseif ($sisaHari !== null)
                                <div id="countdown" class="mt-4 grid grid-cols-3 gap-1.5">
                                    <div
                                        class="countdown-item rounded-lg border border-white/10 bg-white/5 px-1 py-2.5 text-center backdrop-blur-sm hover:bg-white/10">
                                        <span class="font-display block text-lg font-bold leading-none tracking-tight text-[#f5d78e] tabular-nums">
                                            {{ sprintf('%02d', $sisaHari) }}
                                        </span>
                                        <span class="mt-1 block text-[8px] font-semibold uppercase tracking-wider text-gray-400">Hari</span>
                                    </div>
                                    <div
                                        class="countdown-item rounded-lg border border-white/10 bg-white/5 px-1 py-2.5 text-center backdrop-blur-sm hover:bg-white/10">
                                        <span class="font-display block text-lg font-bold leading-none tracking-tight text-[#f5d78e] tabular-nums">
                                            {{ sprintf('%02d', $sisaJam) }}
                                        </span>
                                        <span class="mt-1 block text-[8px] font-semibold uppercase tracking-wider text-gray-400">Jam</span>
                                    </div>
                                    <div
                                        class="countdown-item rounded-lg border border-white/10 bg-white/5 px-1 py-2.5 text-center backdrop-blur-sm hover:bg-white/10">
                                        <span class="font-display block text-lg font-bold leading-none tracking-tight text-[#f5d78e] tabular-nums">
                                            {{ sprintf('%02d', $sisaMenit) }}
                                        </span>
                                        <span class="mt-1 block text-[8px] font-semibold uppercase tracking-wider text-gray-400">Menit</span>
                                    </div>
                                </div>
                            @else
                                <div class="py-5 text-center">
                                    <span
                                        class="inline-flex items-center gap-2 rounded-full bg-amber-500/10 border border-amber-500/20 px-3 py-1 text-xs font-semibold text-amber-300">
                                        {{ $statusPendaftaran }}
                                    </span>
                                </div>
                            @endif

                            <div class="mt-4 grid grid-cols-2 gap-2 border-t border-white/10 pt-3">
                                <div
                                    class="stat-box rounded-lg border border-white/10 bg-white/5 px-2 py-2.5 text-center backdrop-blur-sm">
                                    <p class="font-display text-base font-bold text-[#f5d78e]">
                                        {{ $rp($event->biaya_pendaftaran ?? 0) }}
                                    </p>
                                    <p class="mt-0.5 text-[9px] font-medium uppercase tracking-wider text-gray-400">
                                        Biaya Daftar
                                    </p>
                                </div>

                                <div
                                    class="stat-box rounded-lg border border-white/10 bg-white/5 px-2 py-2.5 text-center backdrop-blur-sm">
                                    <p class="font-display text-base font-bold text-[#f5d78e]">
                                        {{ $event->kuota_peserta ? number_format($event->kuota_peserta, 0, ',', '.') . ' Orang' : 'Tanpa Batas' }}
                                    </p>
                                    <p class="mt-0.5 text-[9px] font-medium uppercase tracking-wider text-gray-400">
                                        Kuota Peserta
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- SYARAT & KETENTUAN --}}
        <section class="section-reveal mt-7">
            <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <div class="mb-2 flex items-center gap-3">
                        <span
                            class="inline-block h-px w-8 bg-gradient-to-r from-transparent via-[#c99a32]/60 to-[#c99a32]"></span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="inline-block h-1 w-1 rotate-45 bg-[#c99a32]"></span>
                            <span class="text-[10px] font-bold uppercase tracking-[.24em] text-[#b28a38]">
                                Panduan Peserta
                            </span>
                            <span class="inline-block h-1 w-1 rotate-45 bg-[#c99a32]"></span>
                        </span>
                    </div>
                    <h2 class="font-display text-lg font-bold text-[#15233d] md:text-xl">
                        Syarat &amp; Ketentuan
                    </h2>
                    <p class="mt-1.5 text-[13px] leading-relaxed text-[#8a8178] md:text-sm">
                        Daftar kandidat akan tampil setelah voting dimulai.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-[1fr_340px] gap-5">
                {{-- KIRI: SYARAT LIST --}}
                <div
                    class="group relative bg-white rounded-2xl border border-[#ece5d7] shadow-sm p-6 sm:p-7 transition-all duration-300 hover:border-[#c99a32]/40 hover:shadow-md overflow-hidden">
                    <span
                        class="absolute -right-4 -top-6 text-[120px] font-black text-[#c99a32]/5 pointer-events-none select-none leading-none">
                        S&amp;K
                    </span>

                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#c99a32] via-[#e0b254] to-[#c99a32] transform scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-500"></div>

                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-5">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#f4ede0] text-[#c99a32] transition-all duration-300 group-hover:bg-[#c99a32] group-hover:text-white group-hover:scale-110">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-[15px] font-bold text-[#0a1526]">Ketentuan Peserta</h3>
                                <p class="text-[11px] text-[#8a8178]">Baca dengan teliti sebelum mendaftar</p>
                            </div>
                        </div>

                        <ul class="space-y-3 text-[13px] text-[#4B463F]">
                            @forelse ($syaratList as $i => $syarat)
                                <li class="group/item flex items-start gap-3 p-3 rounded-xl bg-[#fdfcf9] border border-[#f4f1e9] hover:border-[#c99a32]/40 hover:bg-[#fdf8ec] transition-all duration-200">
                                    <span
                                        class="shrink-0 w-6 h-6 rounded-lg bg-[#f4ede0] text-[#c99a32] text-[11px] font-bold flex items-center justify-center group-hover/item:bg-[#c99a32] group-hover/item:text-white transition-all duration-200">
                                        {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                    <span class="pt-0.5 leading-relaxed">{{ $syarat }}</span>
                                </li>
                            @empty
                                <li class="text-[12px] text-[#8a8178] italic text-center py-6 bg-[#fdfcf9] rounded-xl border border-dashed border-[#e5e1db]">
                                    Syarat &amp; ketentuan belum diisi admin.
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                {{-- KANAN: ASIDE --}}
                <aside class="space-y-4">
                    {{-- CARD: PENDAFTARAN KANDIDAT --}}
                    <div
                        class="relative bg-gradient-to-br from-[#0a1526] to-[#15243b] rounded-2xl border border-[#1a2942] shadow-lg p-5 sm:p-6 overflow-hidden">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-[#c99a32]/20 blur-3xl rounded-full pointer-events-none"></div>

                        <div class="relative z-10">
                            <div class="flex items-center gap-3 mb-4">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#c99a32]/20 border border-[#c99a32]/30 text-[#e0b254]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-display text-[15px] font-bold text-white">Pendaftaran Kandidat</h3>
                                    <p class="text-[10px] text-[#c5b388] uppercase tracking-wider font-semibold">Info Pendaftaran</p>
                                </div>
                            </div>

                            <div class="space-y-2.5 text-[12px]">
                                <div class="flex justify-between items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/10">
                                    <span class="text-[#c5b388] flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Batas Daftar
                                    </span>
                                    <span class="font-semibold text-white text-right">
                                        {{ $batasDaftar?->translatedFormat('d M Y') ?? '-' }}
                                    </span>
                                </div>

                                <div class="flex justify-between items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/10">
                                    <span class="text-[#c5b388] flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Pukul
                                    </span>
                                    <span class="font-semibold text-white">
                                        @php
                                            $jamBatas = $event->batas_pendaftaran_waktu;
                                            if ($jamBatas && strlen($jamBatas) > 5) {
                                                $jamBatas = \Carbon\Carbon::parse($jamBatas)->format('H:i');
                                            }
                                        @endphp
                                        {{ $jamBatas ?? '23:59' }} WIB
                                    </span>
                                </div>
                            </div>

                            @if ($bukaPendaftaran)
                                <a href="{{ route('public.registration', $event) }}"
                                    class="group/btn mt-4 flex items-center justify-center gap-2 w-full rounded-xl bg-gradient-to-r from-[#c99a32] to-[#b88625] py-3 text-white text-[12px] font-bold shadow-md transition-all duration-300 hover:from-[#b88625] hover:to-[#a3741d] hover:shadow-lg active:scale-[0.98]">
                                    <span>Daftar Sebagai Kandidat</span>
                                    <svg class="w-4 h-4 transition-transform duration-300 group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                    </svg>
                                </a>
                                <p class="mt-2.5 text-[10px] text-[#c5b388] text-center">
                                    Biaya pendaftaran
                                    <strong class="text-[#e0b254]">
                                        {{ $rp($event->biaya_pendaftaran ?? 0) }}
                                    </strong>
                                </p>
                            @else
                                <div
                                    class="mt-4 flex items-center justify-center gap-2 w-full rounded-xl bg-white/5 border border-white/10 py-3 text-[12px] font-semibold text-[#c5b388]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    Pendaftaran Ditutup
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- CARD: JADWAL EVENT --}}
                    <div
                        class="bg-white rounded-2xl border border-[#ece5d7] shadow-sm p-5 sm:p-6 transition-all duration-300 hover:border-[#c99a32]/40 hover:shadow-md">
                        <div class="flex items-center gap-3 mb-4">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#f4ede0] text-[#c99a32]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-display text-[15px] font-bold text-[#0a1526]">Jadwal Event</h3>
                                <p class="text-[10px] text-[#8a8178] uppercase tracking-wider font-semibold">Timeline kegiatan</p>
                            </div>
                        </div>

                        <div class="relative space-y-5 text-[12px]">
                            <div class="absolute left-[7px] top-3 bottom-3 w-[2px] bg-gradient-to-b from-[#c99a32]/40 via-[#c99a32]/20 to-transparent"></div>

                            @foreach ($jadwal as $tahap)
                                @php $tgl = explode(' – ', $tahap['tanggal']); @endphp

                                <div class="timeline-item relative flex items-start gap-3 pl-0">
                                    <span
                                        class="shrink-0 relative z-10 w-4 h-4 rounded-full bg-[#c99a32] ring-4 ring-[#fdf8ec] mt-0.5">
                                        @if ($loop->last)
                                            <span class="absolute inset-0 rounded-full bg-[#c99a32] opacity-40"></span>
                                        @endif
                                    </span>
                                    <div class="flex-1">
                                        <p class="font-bold text-[#0a1526]">{{ $tahap['nama'] }}</p>
                                        <p class="text-[11px] text-[#6b6b6b] mt-0.5">
                                            @if (count($tgl) === 2)
                                                {{ $tgl[0] }}
                                                <span class="text-[#c99a32] mx-1">→</span>
                                                {{ $tgl[1] }}
                                            @else
                                                {{ $tahap['tanggal'] }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </aside>
            </div>
        </section>

        {{-- ALUR PENDAFTARAN --}}
        <section class="section-reveal mt-7">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-2 mb-8">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#f4ede0] border border-[#e8d2ab] text-[#c99a32] text-xs font-semibold mb-2.5">
                        <span class="w-2 h-2 rounded-full bg-[#c99a32] animate-pulse"></span>
                        Panduan Kandidat
                    </div>
                    <h2 class="font-display text-xl md:text-2xl font-bold text-[#15233d]">Alur Pendaftaran</h2>
                    <p class="text-gray-500 text-xs md:text-sm mt-1">4 langkah mudah untuk bergabung sebagai kandidat event</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                @php
                    $alurPendaftaran = [
                        ['1', 'Masuk dengan Google', 'Login pakai akun Google — cuma butuh 1 detik.'],
                        ['2', 'Isi Formulir', 'Lengkapi data diri & unggah foto terbaikmu.'],
                        ['3', 'Verifikasi Admin', 'Admin akan memverifikasi pendaftaranmu.'],
                        ['4', 'Tampil di Event', 'Kandidat tampil & siap menerima vote publik.'],
                    ];
                @endphp

                @foreach ($alurPendaftaran as [$nomor, $judul, $deskripsi])
                    <div class="step-card group relative bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-xl hover:shadow-[#0a1526]/5 hover:border-[#c99a32]/40 transition-all duration-300 transform hover:-translate-y-2 overflow-hidden flex flex-col justify-between">
                        
                        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#c99a32] to-[#e5c158] opacity-0 group-hover:opacity-100 transition-all duration-300 transform scale-x-0 group-hover:scale-x-100 origin-left"></div>

                        <span class="absolute -right-2 -bottom-4 text-7xl font-black text-gray-100/80 group-hover:text-[#c99a32]/10 group-hover:scale-110 transition-all duration-500 pointer-events-none select-none">
                            0{{ $nomor }}
                        </span>

                        <div class="relative z-10">
                            <div class="flex items-center justify-between">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#f4ede0] text-[15px] font-bold text-[#c99a32] group-hover:bg-[#c99a32] group-hover:text-white group-hover:scale-110 group-hover:shadow-lg group-hover:shadow-[#c99a32]/30 transition-all duration-300">
                                    {{ $nomor }}
                                </div>

                                @if(!$loop->last)
                                    <div class="hidden lg:block text-gray-300 group-hover:text-[#c99a32] group-hover:translate-x-1.5 transition-all duration-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <h3 class="mt-5 text-[16px] font-bold text-[#15233d] group-hover:text-[#0a1526] transition-colors duration-200">
                                {{ $judul }}
                            </h3>
                            <p class="mt-2 text-[13px] leading-relaxed text-gray-500 group-hover:text-gray-600 transition-colors duration-200">
                                {{ $deskripsi }}
                            </p>
                        </div>
                        
                        <div class="mt-5 pt-3 border-t border-gray-100 flex items-center gap-1.5 text-[11px] font-semibold text-[#c99a32] opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 relative z-10">
                            <span>Langkah {{ $nomor }}</span>
                            <svg class="w-3.5 h-3.5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </main>

    @include('partials.footer')

    <script>
        // ============================================
        // DESKRIPSI EVENT — LIHAT SELENGKAPNYA (mobile)
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

            function atur() {
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
                    terbuka = true;
                    teks.style.maxHeight = teks.scrollHeight + 'px';
                    fade.style.opacity = '0';
                    timer = setTimeout(function() {
                        if (terbuka) teks.style.maxHeight = 'none';
                    }, 480);
                } else {
                    terbuka = false;
                    teks.style.maxHeight = teks.scrollHeight + 'px';
                    void teks.offsetHeight;
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

            var el = {
                hari: box.querySelectorAll('.countdown-item')[0].querySelector('span:first-child'),
                jam: box.querySelectorAll('.countdown-item')[1].querySelector('span:first-child'),
                menit: box.querySelectorAll('.countdown-item')[2].querySelector('span:first-child'),
            };
            
            var pad = function(n) { return String(n).padStart(2, '0'); };
            var timer;

            function tick() {
                var now = new Date();
                var batas = new Date('{{ $batasDaftar }}');
                var sisa = Math.max(0, Math.floor((batas - now) / 1000));
                
                el.hari.textContent = pad(Math.floor(sisa / 86400));
                el.jam.textContent = pad(Math.floor((sisa % 86400) / 3600));
                el.menit.textContent = pad(Math.floor((sisa % 3600) / 60));
                
                if (sisa <= 0) clearInterval(timer);
            }

            tick();
            timer = setInterval(tick, 1000);
        })();
    </script>
</body>

</html>