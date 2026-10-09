<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <title>{{ $event->nama }} - Akan Datang | ALNAY.ID</title>

    <link rel="icon" type="image/png" href="{{ asset('images/alnaylogo.png') }}?v=2">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/alnaylogo.png') }}?v=2">

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .font-display {
            font-family: 'Playfair Display', serif;
        }

        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body class="bg-[#faf9f6] text-[#15233d]">

    @php
        // =====================================================
        // DATA DARI CONTROLLER
        // $event, $jumlahPeserta, $sisaHariPendaftaran
        // =====================================================

        // Pecah syarat & ketentuan jadi array
        $syaratList = array_values(
            array_filter(array_map('trim', preg_split('/\R/', (string) $event->syarat_ketentuan))),
        );

        // Status pendaftaran — berdasarkan waktu
        // Status pendaftaran — gabungan flag admin (pendaftaran_dibuka) + waktu
        $sekarang = now();
        $bukaDaftar = $event->pembukaan_pendaftaran_tanggal;
        $batasDaftar = $event->batas_pendaftaran_tanggal;

        if ($bukaDaftar && $sekarang->lt($bukaDaftar)) {
            $statusPendaftaran = 'Belum Dibuka';
            $statusClass = 'bg-gray-500';
        } elseif ($batasDaftar && $sekarang->gt($batasDaftar)) {
            $statusPendaftaran = 'Pendaftaran Ditutup';
            $statusClass = 'bg-rose-500';
        } else {
            $statusPendaftaran = 'Pendaftaran Dibuka';
            $statusClass = 'bg-[#c99a32]';
        }

        // Dipakai juga oleh tombol "Daftar Sebagai Kandidat" di bawah
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

        // ---- Jadwal Event (otomatis, termasuk Verifikasi Data kalau ada selisih hari) ----
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
    @endphp

    {{-- HEADER --}}
    @include('partials.header')

    <main class="max-w-5xl mx-auto px-5 pt-0 pb-6">

        {{-- EVENT BANNER --}}
        <section class="relative overflow-hidden rounded-xl mx-0 md:-mx-10 mt-4">
            @if ($event->banner)
                <div class="relative w-full h-[220px] sm:h-[300px] md:h-[380px]">
                    {{-- Gambar Utama dengan handler onerror --}}
                    <img src="{{ asset('storage/' . $event->banner) }}" 
                        alt="{{ $event->nama }}"
                        class="w-full h-full object-cover object-center"
                        onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">

                    {{-- Tampilan Fallback jika Gambar Gagal Dimuat (404/Rusak) --}}
                    <div class="hidden w-full h-full bg-gradient-to-br from-[#0a1526] to-[#1a2942] flex flex-col items-center justify-center text-center p-4">
                        <svg class="w-12 h-12 text-[#c99a32] opacity-50 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="font-display text-xl sm:text-2xl font-bold text-[#c99a32] tracking-wider mb-1">
                            {{ $event->nama }}
                        </p>
                        <p class="text-[10px] sm:text-xs text-gray-400 uppercase tracking-widest">Alnay Event Center</p>
                    </div>
                </div>
            @else
                {{-- Tampilan Fallback jika data banner memang kosong/null dari database --}}
                <div class="w-full h-[220px] sm:h-[300px] md:h-[380px] bg-gradient-to-br from-[#0a1526] to-[#1a2942] flex flex-col items-center justify-center text-center p-4">
                    <p class="font-display text-xl sm:text-3xl font-bold text-[#c99a32] tracking-wider mb-1">
                        {{ $event->nama }}
                    </p>
                    <p class="text-[10px] sm:text-xs text-gray-400 uppercase tracking-widest">Banner belum diunggah</p>
                </div>
            @endif
        </section>

        {{-- EVENT INFORMATION --}}
        <section class="relative z-20 -mt-6 sm:-mt-10 mx-4 lg:mx-8">
    <!-- Main Card Container -->
    <div class="rounded-2xl border border-[#ece5d7] bg-white/95 backdrop-blur-md p-5 sm:p-7 shadow-xl shadow-[#0a1526]/5 transition-all duration-300 hover:shadow-2xl hover:shadow-[#0a1526]/10">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-stretch">

            <!-- LEFT COLUMN: Informasi Utama Event (lg:col-span-7 atau 8) -->
            <div class="lg:col-span-7 xl:col-span-8 flex flex-col justify-between">
                <div x-data="{ openModal: false }" class="relative">
                    <!-- Title -->
                    <h1 class="display-font text-2xl sm:text-3xl font-extrabold text-[#0a1526] tracking-tight leading-snug">
                        {{ $event->nama }}
                    </h1>

                    <!-- Description (Dipotong maksimal 3 baris) -->
                    <p class="text-[#5c5c5c] text-xs sm:text-sm leading-relaxed mt-2.5 max-w-3xl line-clamp-3">
                        {{ $event->deskripsi ?: 'Deskripsi belum diisi.' }}
                    </p>

                    <!-- Link Lihat Selengkapnya -->
                    @if(!empty($event->deskripsi) && mb_strlen(strip_tags($event->deskripsi)) > 150)
                        <button 
                            @click="openModal = true" 
                            type="button"
                            class="mt-1 text-xs sm:text-sm font-semibold text-[#c99a32] hover:text-[#a37b2a] hover:underline focus:outline-none transition-colors cursor-pointer">
                            Lihat selengkapnya
                        </button>
                    @endif

                    <!-- Teleport Modal ke <body> supaya blur meliputi seluruh skrin -->
                    <template x-teleport="body">
                        <div 
                            x-show="openModal" 
                            x-cloak
                            @keydown.escape.window="openModal = false"
                            class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
                            role="dialog"
                            aria-modal="true"
                        >
                            <!-- 1. Backdrop Overlay (Gelap & Blur Seluruh Skrin Viewport) -->
                            <div 
                                x-show="openModal"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                @click="openModal = false"
                                class="fixed inset-0 bg-black/60 backdrop-blur-md z-40 cursor-pointer">
                            </div>

                            <!-- 2. Card / Box Modal -->
                            <div 
                                x-show="openModal"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                                class="relative z-50 bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[85vh] flex flex-col overflow-hidden my-auto"
                                @click.stop
                            >
                                <!-- Modal Header -->
                                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-white shrink-0">
                                    <h3 class="text-base sm:text-lg font-bold text-[#0a1526]">
                                        Deskripsi Lengkap Event
                                    </h3>
                                    
                                    <!-- Tombol X Close -->
                                    <button 
                                        @click="openModal = false" 
                                        type="button" 
                                        class="p-2 text-gray-400 hover:text-[#c99a32] hover:bg-gray-100 rounded-full transition-all focus:outline-none cursor-pointer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Modal Content (Scrollable) -->
                                <div class="p-6 overflow-y-auto text-sm text-[#5c5c5c] leading-relaxed whitespace-pre-line">
                                    {{ $event->deskripsi }}
                                </div>

                                <!-- Modal Footer -->
                                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 text-right shrink-0">
                                    <button 
                                        @click="openModal = false" 
                                        type="button"
                                        class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:border-gray-400 transition-all focus:outline-none focus:ring-2 focus:ring-gray-200 cursor-pointer">
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Interactive Info Badges -->
                <div class="flex flex-wrap items-center gap-2.5 mt-5">
                    <!-- Badge 1: Tanggal Voting -->
                    <div class="group inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-[#fcfaf6] border border-[#eee5d2] text-[11px] font-medium text-[#4B463F] transition-all duration-200 hover:border-[#c99a32] hover:bg-white hover:shadow-sm">
                        <svg class="w-3.5 h-3.5 text-[#c99a32] transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Voting mulai: <strong class="text-[#0a1526] font-semibold">{{ $event->tanggal_mulai?->translatedFormat('d M Y') ?? '-' }}</strong></span>
                    </div>
                    <!-- Badge 2: Lokasi (Opsional) -->
                    @if ($event->lokasi)
                        <div class="group inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-[#fcfaf6] border border-[#eee5d2] text-[11px] font-medium text-[#4B463F] transition-all duration-200 hover:border-[#c99a32] hover:bg-white hover:shadow-sm">
                            <svg class="w-3.5 h-3.5 text-[#c99a32] transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="text-[#0a1526] font-semibold">{{ $event->lokasi }}</span>
                        </div>
                    @endif

                    <!-- Badge 3: Harga Vote -->
                    <div class="group inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-[#fcfaf6] border border-[#eee5d2] text-[11px] font-medium text-[#4B463F] transition-all duration-200 hover:border-[#c99a32] hover:bg-white hover:shadow-sm">
                        <svg class="w-3.5 h-3.5 text-[#c99a32] transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span><strong class="text-[#c99a32] font-bold">Rp {{ number_format($event->harga_per_vote ?? 0, 0, ',', '.') }}</strong> / vote</span>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Box Countdown & Status Pendaftaran (lg:col-span-5 atau 4) -->
            <div class="lg:col-span-5 xl:col-span-4 flex flex-col justify-between rounded-xl bg-gradient-to-br from-[#0a1526] via-[#11223f] to-[#0a1526] p-4 sm:p-5 text-white border border-[#1e3252] shadow-md relative overflow-hidden group">
                <!-- Background Glow Effect Decorative -->
                <div class="absolute -right-10 -top-10 h-32 w-32 rounded-full bg-[#c99a32]/10 blur-2xl transition-all duration-500 group-hover:bg-[#c99a32]/20"></div>

                <div>
                    <!-- Header Label Status -->
                    <div class="flex items-center justify-between border-b border-white/10 pb-3">
                        <span class="text-[10px] uppercase tracking-wider font-semibold text-[#c5b388] flex items-center gap-1.5">
                            @if ($event->pendaftaran_dibuka && $sisaHari !== null)
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
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

                        <span class="text-[10px] font-medium text-gray-400">Reg. Timer</span>
                    </div>

                    <!-- Countdown Display / Closed Status -->
                    @if (!$event->pendaftaran_dibuka)
                        <div class="py-5 text-center">
                            <span class="inline-flex items-center gap-2 rounded-full bg-rose-500/10 border border-rose-500/20 px-3 py-1 text-xs font-semibold text-rose-400">
                                Pendaftaran Ditutup
                            </span>
                        </div>
                    @elseif ($sisaHari !== null)
                        <div class="grid grid-cols-3 gap-2 my-4 text-center">
                            <!-- Hari -->
                            <div class="rounded-lg bg-white/5 border border-white/10 p-2 backdrop-blur-sm transition-transform duration-200 hover:scale-105 hover:bg-white/10">
                                <p class="display-font text-xl sm:text-2xl font-bold text-[#f5d78e] tracking-tight">
                                    {{ sprintf('%02d', $sisaHari) }}
                                </p>
                                <p class="text-[9px] font-medium text-gray-400 tracking-wider mt-0.5">HARI</p>
                            </div>
                            <!-- Jam -->
                            <div class="rounded-lg bg-white/5 border border-white/10 p-2 backdrop-blur-sm transition-transform duration-200 hover:scale-105 hover:bg-white/10">
                                <p class="display-font text-xl sm:text-2xl font-bold text-[#f5d78e] tracking-tight">
                                    {{ sprintf('%02d', $sisaJam) }}
                                </p>
                                <p class="text-[9px] font-medium text-gray-400 tracking-wider mt-0.5">JAM</p>
                            </div>
                            <!-- Menit -->
                            <div class="rounded-lg bg-white/5 border border-white/10 p-2 backdrop-blur-sm transition-transform duration-200 hover:scale-105 hover:bg-white/10">
                                <p class="display-font text-xl sm:text-2xl font-bold text-[#f5d78e] tracking-tight">
                                    {{ sprintf('%02d', $sisaMenit) }}
                                </p>
                                <p class="text-[9px] font-medium text-gray-400 tracking-wider mt-0.5">MENIT</p>
                            </div>
                        </div>
                    @else
                        <div class="py-5 text-center">
                            <span class="inline-flex items-center gap-2 rounded-full bg-amber-500/10 border border-amber-500/20 px-3 py-1 text-xs font-semibold text-amber-300">
                                {{ $statusPendaftaran }}
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Bottom Stats Grid (Biaya & Kuota) -->
                <div class="grid grid-cols-2 gap-2 pt-3 border-t border-white/10 text-center">
                    <div class="rounded-lg bg-white/5 p-2">
                        <p class="text-xs sm:text-sm font-bold text-white">
                            Rp {{ number_format($event->biaya_pendaftaran ?? 0, 0, ',', '.') }}
                        </p>
                        <p class="text-[9px] text-[#c5b388] mt-0.5 font-medium">Biaya Daftar</p>
                    </div>
                    <div class="rounded-lg bg-white/5 p-2">
                        <p class="text-xs sm:text-sm font-bold text-white">
                            {{ $event->kuota_peserta ? number_format($event->kuota_peserta, 0, ',', '.') . ' Orang' : 'Tanpa Batas' }}
                        </p>
                        <p class="text-[9px] text-[#c5b388] mt-0.5 font-medium">Kuota Peserta</p>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>

        {{-- SYARAT & KETENTUAN --}}
        <section class="mt-10 sm:mt-12">
    {{-- Section Header --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-6">
        <div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#c99a32]/10 border border-[#c99a32]/30 text-[10px] font-bold uppercase tracking-widest text-[#b88625]">
                Panduan Peserta
            </span>
            <h2 class="display-font mt-3 text-[22px] md:text-[26px] font-bold text-[#0a1526]">
                Syarat &amp; Ketentuan
            </h2>
            <p class="mt-1 text-[13px] text-[#6b6b6b]">
                Daftar kandidat akan tampil setelah voting dimulai.
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[1fr_340px] gap-5">

        {{-- KIRI: SYARAT LIST --}}
        <div class="group relative bg-white rounded-2xl border border-[#ece5d7] shadow-sm p-6 sm:p-7 transition-all duration-300 hover:border-[#c99a32]/40 hover:shadow-md overflow-hidden">
            {{-- Watermark dekoratif --}}
            <span class="absolute -right-4 -top-6 text-[120px] font-black text-[#c99a32]/5 pointer-events-none select-none leading-none">
                S&amp;K
            </span>

            {{-- Top accent line --}}
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#c99a32] via-[#e0b254] to-[#c99a32] transform scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-500"></div>

            <div class="relative z-10">
                {{-- Icon + Judul kecil --}}
                <div class="flex items-center gap-3 mb-5">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#f4ede0] text-[#c99a32] transition-all duration-300 group-hover:bg-[#c99a32] group-hover:text-white group-hover:scale-110">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-[15px] font-bold text-[#0a1526]">Ketentuan Peserta</h3>
                        <p class="text-[11px] text-[#8a8178]">Baca dengan teliti sebelum mendaftar</p>
                    </div>
                </div>

                {{-- List Syarat --}}
                <ul class="space-y-3 text-[13px] text-[#4B463F]">
                    @forelse ($syaratList as $i => $syarat)
                        <li class="group/item flex items-start gap-3 p-3 rounded-xl bg-[#fdfcf9] border border-[#f4f1e9] hover:border-[#c99a32]/40 hover:bg-[#fdf8ec] transition-all duration-200">
                            {{-- Nomor --}}
                            <span class="shrink-0 w-6 h-6 rounded-lg bg-[#f4ede0] text-[#c99a32] text-[11px] font-bold flex items-center justify-center group-hover/item:bg-[#c99a32] group-hover/item:text-white transition-all duration-200">
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
            <div class="relative bg-gradient-to-br from-[#0a1526] to-[#15243b] rounded-2xl border border-[#1a2942] shadow-lg p-5 sm:p-6 overflow-hidden">
                {{-- Glow effect --}}
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-[#c99a32]/20 blur-3xl rounded-full pointer-events-none"></div>

                <div class="relative z-10">
                    {{-- Header --}}
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#c99a32]/20 border border-[#c99a32]/30 text-[#e0b254]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="display-font text-[15px] font-bold text-white">Pendaftaran Kandidat</h3>
                            <p class="text-[10px] text-[#c5b388] uppercase tracking-wider font-semibold">Info Pendaftaran</p>
                        </div>
                    </div>

                    {{-- Info Boxes --}}
                    <div class="space-y-2.5 text-[12px]">
                        <div class="flex justify-between items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/10">
                            <span class="text-[#c5b388] flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
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

                    @php
                        $bukaPendaftaran =
                            $event->pendaftaran_dibuka && $statusPendaftaran === 'Pendaftaran Dibuka';
                    @endphp

                    @if ($bukaPendaftaran)
                        <a href="{{ route('public.registration', $event) }}"
                            class="group/btn mt-4 flex items-center justify-center gap-2 w-full rounded-xl bg-gradient-to-r from-[#c99a32] to-[#b88625] py-3 text-white text-[12px] font-bold shadow-md transition-all duration-300 hover:from-[#b88625] hover:to-[#a3741d] hover:shadow-lg active:scale-[0.98]">
                            <span>Daftar Sebagai Kandidat</span>
                            <svg class="w-4 h-4 transition-transform duration-300 group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                            </svg>
                        </a>
                        <p class="mt-2.5 text-[10px] text-[#c5b388] text-center">
                            Biaya pendaftaran
                            <strong class="text-[#e0b254]">
                                Rp {{ number_format($event->biaya_pendaftaran ?? 0, 0, ',', '.') }}
                            </strong>
                        </p>
                    @else
                        <div class="mt-4 flex items-center justify-center gap-2 w-full rounded-xl bg-white/5 border border-white/10 py-3 text-[12px] font-semibold text-[#c5b388]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Pendaftaran Ditutup
                        </div>
                    @endif
                </div>
            </div>

            {{-- CARD: JADWAL EVENT --}}
            <div class="bg-white rounded-2xl border border-[#ece5d7] shadow-sm p-5 sm:p-6 transition-all duration-300 hover:border-[#c99a32]/40 hover:shadow-md">
                {{-- Header --}}
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#f4ede0] text-[#c99a32]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="display-font text-[15px] font-bold text-[#0a1526]">Jadwal Event</h3>
                        <p class="text-[10px] text-[#8a8178] uppercase tracking-wider font-semibold">Timeline kegiatan</p>
                    </div>
                </div>

                {{-- Timeline --}}
                <div class="relative space-y-5 text-[12px]">
                    {{-- Vertical connector line --}}
                    <div class="absolute left-[7px] top-3 bottom-3 w-[2px] bg-gradient-to-b from-[#c99a32]/40 via-[#c99a32]/20 to-transparent"></div>

                    {{-- Step 1 --}}
                    <div class="relative flex items-start gap-3 pl-0">
                        <span class="shrink-0 relative z-10 w-4 h-4 rounded-full bg-[#c99a32] ring-4 ring-[#fdf8ec] mt-0.5"></span>
                        <div class="flex-1">
                            <p class="font-bold text-[#0a1526]">Pendaftaran Kandidat</p>
                            <p class="text-[11px] text-[#6b6b6b] mt-0.5">
                                {{ $bukaDaftar ? $bukaDaftar->translatedFormat('d M Y') : '-' }}
                                <span class="text-[#c99a32] mx-1">→</span>
                                {{ $batasDaftar ? $batasDaftar->translatedFormat('d M Y') : '-' }}
                            </p>
                        </div>
                    </div>

                    {{-- Step 2 --}}
                    <div class="relative flex items-start gap-3 pl-0">
                        <span class="shrink-0 relative z-10 w-4 h-4 rounded-full bg-[#c99a32] ring-4 ring-[#fdf8ec] mt-0.5"></span>
                        <div class="flex-1">
                            <p class="font-bold text-[#0a1526]">Voting Publik</p>
                            <p class="text-[11px] text-[#6b6b6b] mt-0.5">
                                {{ $event->tanggal_mulai?->translatedFormat('d M') ?? '-' }}
                                <span class="text-[#c99a32] mx-1">→</span>
                                {{ $event->tanggal_selesai?->translatedFormat('d M Y') ?? '-' }}
                            </p>
                        </div>
                    </div>

                    {{-- Step 3 --}}
                    <div class="relative flex items-start gap-3 pl-0">
                        <span class="shrink-0 relative z-10 w-4 h-4 rounded-full bg-[#c99a32] ring-4 ring-[#fdf8ec] mt-0.5">
                            <span class="absolute inset-0 rounded-full bg-[#c99a32] opacity-40"></span>
                        </span>
                        <div class="flex-1">
                            <p class="font-bold text-[#0a1526]">Pengumuman Pemenang</p>
                            <p class="text-[11px] text-[#6b6b6b] mt-0.5" id="alur-pendaftaran">
                                Setelah voting berakhir
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </aside>
    </div>
</section>

        {{-- ALUR PENDAFTARAN --}}
        <section class="mt-10">
    <!-- Section Header dengan Tag Highlight -->
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

    <!-- Grid Steps dengan Interaksi Hover & Animasi -->
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
            <div class="group relative bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-xl hover:shadow-[#0a1526]/5 hover:border-[#c99a32]/40 transition-all duration-300 transform hover:-translate-y-2 overflow-hidden flex flex-col justify-between">
                
                <!-- 1. Top Accent Line Animation (Garis warna bergerak saat hover) -->
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#c99a32] to-[#e5c158] opacity-0 group-hover:opacity-100 transition-all duration-300 transform scale-x-0 group-hover:scale-x-100 origin-left"></div>

                <!-- 2. Watermark Angka Besar Transparan di Background -->
                <span class="absolute -right-2 -bottom-4 text-7xl font-black text-gray-100/80 group-hover:text-[#c99a32]/10 group-hover:scale-110 transition-all duration-500 pointer-events-none select-none">
                    0{{ $nomor }}
                </span>

                <div class="relative z-10">
                    <!-- Badge Nomor & Arrow Connector -->
                    <div class="flex items-center justify-between">
                        <!-- Badge Nomor Interaktif -->
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#f4ede0] text-[15px] font-bold text-[#c99a32] group-hover:bg-[#c99a32] group-hover:text-white group-hover:scale-110 group-hover:shadow-lg group-hover:shadow-[#c99a32]/30 transition-all duration-300">
                            {{ $nomor }}
                        </div>

                        <!-- Panah Petunjuk Alur (Khusus tampilan Desktop lg, kecuali item terakhir) -->
                        @if(!$loop->last)
                            <div class="hidden lg:block text-gray-300 group-hover:text-[#c99a32] group-hover:translate-x-1.5 transition-all duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Judul & Deskripsi -->
                    <h3 class="mt-5 text-[16px] font-bold text-[#15233d] group-hover:text-[#0a1526] transition-colors duration-200">
                        {{ $judul }}
                    </h3>
                    <p class="mt-2 text-[13px] leading-relaxed text-gray-500 group-hover:text-gray-600 transition-colors duration-200">
                        {{ $deskripsi }}
                    </p>
                </div>
                <!-- 3. Indikator Langkah di Bawah (Muncul saat Hover) -->
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

</body>

</html>