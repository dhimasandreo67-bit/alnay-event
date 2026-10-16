<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->nama }} - Hasil Akhir | ALNAY.ID</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        html, body {
            overflow-x: hidden;
            max-width: 100vw;
        }
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
        .search-input { transition: border-color .25s ease, box-shadow .25s ease, transform .25s ease; }
        .search-input:focus { border-color: #c99a32; box-shadow: 0 0 0 4px rgba(201, 154, 50, .12); }

        /* "backwards" (bukan "both") supaya transform hover tidak ditimpa nilai akhir animasi */
        .reveal-up { animation: revealUp .7s cubic-bezier(.22, 1, .36, 1) backwards; }
        @keyframes revealUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .reveal-up.delay-1 { animation-delay: .08s; }
        .reveal-up.delay-2 { animation-delay: .16s; }
        .reveal-up.delay-3 { animation-delay: .24s; }
        .reveal-up.delay-4 { animation-delay: .32s; }
        .reveal-up.delay-5 { animation-delay: .40s; }
        .banner-reveal { animation: bannerReveal 1s cubic-bezier(.22, 1, .36, 1) both; }
        @keyframes bannerReveal {
            from { opacity: 0; transform: scale(.98) translateY(12px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        .progress-fill { animation: progressFill 1.2s cubic-bezier(.22, 1, .36, 1) both; transform-origin: left; }
        @keyframes progressFill {
            from { transform: scaleX(0); }
            to { transform: scaleX(1); }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: .01ms !important; transition-duration: .01ms !important; }
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
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

        /* ============================================ */
        /* TOP 3 SWIPER — COVERFLOW (Mobile) / STATIS    */
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
                z-index: 100;
                pointer-events: auto;
            }

            .top3Swiper .top3Pagination .swiper-pagination-bullet {
                width: 6px;
                height: 6px;
                background: #d4c4a8;
                opacity: 1;
                transition: all .3s ease;
                margin: 0 !important;
                z-index: 101;
                pointer-events: auto;
                cursor: pointer;
            }

            .top3Swiper .top3Pagination .swiper-pagination-bullet-active {
                width: 20px;
                border-radius: 99px;
                background: #c99a32;
            }
        }

        /* ---- DESKTOP/TABLET: 3 kartu statis di tengah, tanpa geser ---- */
        @media (min-width: 768px) {
            .top3Swiper {
                padding: 20px 0 30px 0;
            }

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
    </style>
</head>

<body class="bg-[#faf9f6] text-[#15233d]">
    @include('partials.back-to-top')

    @php
        $blackoutAktif = false;
        $formatAngka = function ($angka) {
            if ($angka >= 1000000) return round($angka / 1000000, 1) . 'M';
            if ($angka >= 1000) return round($angka / 1000, 1) . 'K';
            return number_format($angka, 0, ',', '.');
        };

        $inisial = fn($nama) => strtoupper(implode('', array_map(
            fn($kata) => mb_substr($kata, 0, 1),
            array_slice(preg_split('/\s+/', trim($nama)), 0, 2)
        )));

        $kandidat = $pesertas->map(function ($p, $i) use ($totalSuara) {
            return [
                'id' => $p->id,
                'nomor' => str_pad($i + 1, 2, '0', STR_PAD_LEFT),
                'nama' => $p->namaTampil(),
                'foto' => $p->fotoUtama(),
                'persen' => $totalSuara > 0 ? round(($p->total_vote / $totalSuara) * 100, 2) : 0,
                'vote' => $p->total_vote,
            ];
        })->toArray();

        $pemenangArr = [];
        foreach (array_slice($kandidat, 0, 3) as $i => $k) {
            $k['juara'] = $i + 1;
            $pemenangArr[] = $k;
        }

        // Peringkat 4 – 6 (daftar ringkas di bawah Top 3)
        $peringkat46 = array_slice($kandidat, 3, 3);

        $jumlahKandidat = count($kandidat);
    @endphp

    @include('partials.header')

    <main class="max-w-5xl mx-auto px-5 pt-0 pb-6">

        {{-- EVENT BANNER --}}
        <section class="banner-reveal relative overflow-hidden rounded-2xl mx-0 md:-mx-10 mt-4 border border-[#ece5d7] shadow-sm group">
            @if ($event->banner)
                <img src="{{ asset('storage/' . $event->banner) }}" alt="{{ $event->nama }}"
                    class="w-full h-[220px] sm:h-[300px] md:h-[380px] object-cover object-center transition-transform duration-700 group-hover:scale-[1.02]"
                    onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden'); this.nextElementSibling.classList.add('flex');">
                <div class="hidden w-full h-[220px] sm:h-[300px] md:h-[380px] bg-gradient-to-br from-[#0a1526] to-[#1a2942] items-center justify-center">
                    <div class="text-center">
                        <p class="font-display text-2xl sm:text-3xl font-bold text-[#c99a32] tracking-wider">{{ $event->nama }}</p>
                        <p class="mt-2 text-[10px] tracking-[0.3em] text-[#c5b388] uppercase font-semibold">Alnay Event Center</p>
                    </div>
                </div>
            @else
                <div class="w-full h-[220px] sm:h-[300px] md:h-[380px] bg-gradient-to-br from-[#0a1526] via-[#11223f] to-[#1a2942] flex items-center justify-center">
                    <div class="text-center px-4">
                        <p class="font-display text-2xl sm:text-3xl md:text-4xl font-bold text-[#c99a32] tracking-wider drop-shadow-md">{{ $event->nama }}</p>
                        <p class="mt-2 text-[10px] sm:text-[11px] tracking-[0.3em] text-[#c5b388] uppercase font-semibold">Alnay Event Center</p>
                    </div>
                </div>
            @endif

            <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-[#0a1526]/40 to-transparent pointer-events-none"></div>

            <span class="absolute top-4 right-4 z-10 inline-flex items-center gap-2 rounded-full border border-orange-400/30 bg-orange-950/70 backdrop-blur-md px-4 py-1.5 text-[11px] sm:text-[12px] font-semibold text-orange-300 shadow-lg">
                <span class="h-2 w-2 rounded-full bg-orange-400"></span>
                Telah Berakhir
            </span>
        </section>

        {{-- EVENT INFORMATION --}}
        <section class="reveal-up delay-1 relative -mt-6 mx-4 bg-white rounded-2xl shadow-sm border border-[#ece5d7] hover:border-[#c99a32]/40 transition-all duration-300">
            <div class="p-5 md:p-6">
                <div class="grid grid-cols-1 lg:grid-cols-[1fr_220px] gap-5 items-center">
                    <div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-orange-100 border border-orange-200 text-orange-700 text-[10px] font-bold uppercase tracking-wider">
                            <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                            Telah Berakhir
                        </span>
                        <h1 class="font-display text-xl md:text-2xl font-bold text-[#0a1526] mt-3">{{ $event->nama }}</h1>

                        <div class="relative mt-2.5 max-w-3xl">
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
                    </div>
                    <div class="relative overflow-hidden bg-gradient-to-br from-[#faf8f2] to-[#fdfcf9] border border-[#eee5d2] rounded-2xl p-4">
                        <span class="absolute -right-3 -top-3 pointer-events-none select-none" style="width: 80px; height: 80px;">
                            <svg class="w-full h-full text-[#c99a32]/5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z"/>
                            </svg>
                        </span>
                        <div class="relative grid grid-cols-2 gap-4 w-full text-center">
                            <div>
                                <p class="text-xl font-bold text-[#0a1526]">{{ $jumlahKandidat }}</p>
                                <p class="text-[10px] uppercase tracking-wider text-[#8a8178] font-bold mt-1">Kandidat</p>
                            </div>
                            <div>
                                <p class="text-xl font-bold text-[#c99a32]">{{ $formatAngka($totalSuara) }}</p>
                                <p class="text-[10px] uppercase tracking-wider text-[#8a8178] font-bold mt-1">Total Vote</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- PERINGKAT --}}
        @if ($blackoutAktif ?? false)
            <section class="reveal-up delay-2 mt-10">
                <div class="overflow-hidden rounded-2xl border border-[#e8dcc0] bg-gradient-to-br from-[#fdfbf6] via-white to-[#faf6ed] shadow-sm">
                    <div class="relative px-6 pt-12 pb-10 md:px-12 md:pt-16 md:pb-12 text-center">
                        <div class="flex items-center justify-center gap-2 mb-6">
                            <span class="h-px w-12 bg-gradient-to-r from-transparent to-[#c99a32]/40"></span>
                            <svg class="h-4 w-4 text-[#c99a32]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z"/>
                            </svg>
                            <span class="h-px w-12 bg-gradient-to-l from-transparent to-[#c99a32]/40"></span>
                        </div>
                        <p class="text-[10px] uppercase tracking-[0.35em] text-[#c99a32] font-bold mb-4">Peringkat Sementara</p>
                        <h2 class="font-display text-[26px] md:text-[34px] font-bold text-[#0a1526] leading-tight mb-5">
                            Siapa yang akan<br class="hidden sm:block"> membawa pulang mahkota?
                        </h2>
                        <p class="max-w-xl mx-auto text-[13px] md:text-[15px] leading-[1.8] text-[#5a5750]">
                            Untuk menjaga ketegangan menjelang malam puncak, kami merahasiakan peringkat sementara saat ini. Ikuti terus perjalanan para finalis.
                        </p>
                        @if ($event->voting_selesai)
                            <div class="mt-8 inline-flex flex-col items-center gap-2">
                                <p class="text-[10px] uppercase tracking-[0.25em] text-[#8a8178] font-bold">Malam Puncak</p>
                                <p class="font-display text-lg md:text-xl font-bold text-[#0a1526]">
                                    {{ $event->voting_selesai->translatedFormat('d F Y') }}
                                </p>
                                <p class="text-[12px] text-[#8a8178]">Pukul {{ $event->voting_selesai->format('H:i') }} WIB</p>
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
        @else
            <section class="mt-10 overflow-hidden">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-5 px-5">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#c99a32]/10 border border-[#c99a32]/30 text-[10px] font-bold uppercase tracking-widest text-[#b88625]">Hasil Akhir</span>
                    <h2 class="font-display mt-2.5 text-[22px] md:text-[26px] font-bold text-[#0a1526]">Peringkat Sekarang</h2>
                </div>
                <p class="text-[12px] text-[#8a8178] whitespace-nowrap">
                    Total <strong class="text-[#0a1526]">{{ number_format($totalSuara, 0, ',', '.') }}</strong> suara dari
                    <strong class="text-[#0a1526]">{{ $jumlahKandidat }}</strong> kandidat
                </p>
            </div>

            @if (count($pemenangArr) > 0)
                    @php
                        // Reorder: 2 - 1 - 3
                        $pemenangRaw = collect($pemenangArr);
                        $pemenangSort = collect();
                        if ($pemenangRaw->count() >= 2) $pemenangSort->push($pemenangRaw[1]);
                        if ($pemenangRaw->count() >= 1) $pemenangSort->push($pemenangRaw[0]);
                        if ($pemenangRaw->count() >= 3) $pemenangSort->push($pemenangRaw[2]);
                    @endphp

                    <div class="swiper top3Swiper mt-4">
                        <div class="swiper-wrapper">

                        @foreach ($pemenangSort as $p)
                            @php
                                $juara = $p['juara'];
                                $border = $juara == 1 ? 'border-2 border-[#c99a32] shadow-md' : 'border border-[#ece5d7] shadow-sm hover:border-[#c99a32]/40';
                                $height = $juara == 1 ? 'h-[230px] sm:h-[270px]' : 'h-[210px] sm:h-[210px]';
                            @endphp

                            <div class="swiper-slide">
                                <div class="group bg-white rounded-2xl overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-xl {{ $border }}">
                                <div class="relative overflow-hidden {{ $height }}">
                                    @if ($p['foto'])
                                        <img src="{{ $p['foto'] }}" alt="{{ $p['nama'] }}"
                                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                            onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden'); this.nextElementSibling.classList.add('flex');">
                                        <div class="hidden w-full h-full bg-gradient-to-br from-[#fbf6e9] to-[#f4ede0] items-center justify-center">
                                            <span class="font-display text-4xl font-bold text-[#c99a32]">{{ $inisial($p['nama']) }}</span>
                                        </div>
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-[#fbf6e9] to-[#f4ede0] flex items-center justify-center">
                                            <span class="font-display text-4xl font-bold text-[#c99a32]">{{ $inisial($p['nama']) }}</span>
                                        </div>
                                    @endif

                                    <span class="absolute top-3 right-3 inline-flex items-center gap-1 bg-gradient-to-r from-[#c99a32] to-[#b88625] text-white text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase tracking-wider shadow-md">
                                        @if ($juara == 1)
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z" />
                                            </svg>
                                        @endif
                                        Juara {{ $juara }}
                                    </span>
                                </div>

                                <div class="p-4">
                                    <div class="mb-2">
                                        <h3 class="font-bold text-[14px] text-[#0a1526] truncate">{{ $p['nama'] }}</h3>
                                    </div>

                                    @if ($blackoutAktif ?? false)
                                        <div class="mt-2 flex items-center gap-1.5 text-[10px] text-amber-600 italic">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                            </svg>
                                            <span>Jumlah suara dirahasiakan</span>
                                        </div>
                                    @else
                                        <div class="flex items-baseline gap-1.5">
                                            <p class="text-lg font-bold text-[#0a1526]">{{ $p['persen'] }}%</p>
                                            <span class="text-[11px] text-[#8a8178]">Vote</span>
                                        </div>
                                        <div class="w-full h-1.5 bg-[#f0ece2] rounded-full mt-2.5 overflow-hidden">
                                            <div class="progress-fill h-full bg-gradient-to-r from-[#c99a32] to-[#e0b254] rounded-full" style="width: {{ $p['persen'] }}%"></div>
                                        </div>
                                    @endif
                                </div>
                                                        </div>{{-- /card --}}
                            </div>{{-- /swiper-slide --}}
                        @endforeach

                        </div>{{-- /swiper-wrapper --}}

                        {{-- Pagination --}}
                        @if ($pemenangSort->count() > 1)
                            <div class="top3Pagination"></div>
                        @endif
                    </div>{{-- /swiper --}}

                    {{-- ============================================ --}}
                    {{-- PERINGKAT 4 – 6 (daftar ringkas)              --}}
                    {{-- ============================================ --}}
                    @if (count($peringkat46) > 0)
                        <div class="mt-1 overflow-hidden rounded-2xl border border-[#ece5d7] bg-white shadow-sm">
                            @foreach ($peringkat46 as $i => $ps)
                                <div
                                    class="flex items-center gap-3 px-4 py-3 sm:gap-4 sm:px-5 {{ $loop->first ? '' : 'border-t border-[#f3efe4]' }}">

                                    {{-- Nomor peringkat --}}
                                    <span class="w-6 shrink-0 font-display text-lg text-[#b5ad9c] sm:w-8 sm:text-xl">
                                        {{ str_pad($i + 4, 2, '0', STR_PAD_LEFT) }}
                                    </span>

                                    {{-- Foto / inisial --}}
                                    <div class="h-10 w-10 shrink-0 overflow-hidden rounded-lg border border-[#ece5d7] bg-[#fbf6e9]">
                                        @if ($ps['foto'])
                                            <img src="{{ $ps['foto'] }}" alt="{{ $ps['nama'] }}" loading="lazy"
                                                class="h-full w-full object-cover">
                                        @else
                                            <div class="flex h-full w-full items-center justify-center text-[11px] font-bold text-[#c99a32]">
                                                {{ $inisial($ps['nama']) }}
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Nama + bar --}}
                                    <div class="min-w-0 flex-1 sm:flex sm:items-center sm:gap-4">
                                        <div class="min-w-0 sm:w-56 sm:shrink-0">
                                            <h3 class="truncate text-[13px] font-semibold text-[#0a1526]">{{ $ps['nama'] }}</h3>
                                        </div>

                                        <div class="mt-1.5 h-1.5 w-full overflow-hidden rounded-full bg-[#f0ece2] sm:mt-0 sm:flex-1">
                                            <div class="h-full rounded-full bg-gradient-to-r from-[#c99a32] to-[#e0b254]"
                                                style="width: {{ $ps['persen'] }}%"></div>
                                        </div>
                                    </div>

                                    {{-- Nilai --}}
                                    <div class="shrink-0 text-right sm:w-24">
                                        <span class="text-sm font-bold text-[#0a1526]">{{ $ps['persen'] }}%</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="bg-white rounded-2xl border border-dashed border-[#e5e1db] p-12 text-center mt-4">
                        <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-[#fdf8ec] flex items-center justify-center">
                            <svg class="w-6 h-6 text-[#c99a32]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-[#0a1526]">Belum ada peserta</p>
                        <p class="mt-1 text-xs text-[#8a8178]">Event ini belum memiliki peserta yang diterima.</p>
                    </div>
                @endif
            </section>
        @endif

        {{-- DAFTAR KANDIDAT --}}
        <section id="daftarKandidat" class="reveal-up delay-5 mt-10 scroll-mt-20">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-5">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#0a1526]/5 border border-[#0a1526]/10 text-[10px] font-bold uppercase tracking-widest text-[#0a1526]">Semua Peserta</span>
                    <h2 class="font-display mt-2.5 text-[22px] md:text-[26px] font-bold text-[#0a1526]">Daftar Kandidat</h2>
                    <p class="text-[12px] text-[#8a8178] mt-1"><strong class="text-[#0a1526]">{{ $jumlahKandidat }}</strong> kandidat terdaftar</p>
                </div>

                <div class="relative w-full sm:w-64 shrink-0">
                    <input type="text" id="searchKandidat" placeholder="Cari kandidat..."
                        class="search-input h-10 w-full rounded-full border border-[#ddd9d0] bg-white pl-9 pr-4 text-xs outline-none focus:border-[#c99a32] focus:ring-0 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-3 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m21 21-4.35-4.35m1.35-5.4a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z" />
                    </svg>
                </div>
            </div>

            @if (count($kandidat) > 0)
                <div id="gridKandidat" class="grid grid-cols-2 md:grid-cols-4 gap-3.5 sm:gap-4">
                    @foreach ($kandidat as $index => $k)
                        @php
                            $delayClass = 'delay-' . (($index % 4) + 1);
                            $isHidden = $index >= 8;
                        @endphp
                        <div class="kandidat-card {{ $isHidden ? 'hidden' : '' }} reveal-up {{ $delayClass }} group bg-white rounded-2xl overflow-hidden border border-[#ece5d7] shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-[#c99a32]/40"
                            data-nama="{{ mb_strtolower($k['nama']) }}" data-index="{{ $index }}">
                            <div class="relative h-[180px] md:h-[200px] overflow-hidden bg-gradient-to-br from-[#fbf6e9] to-[#f4ede0]">
                                @if ($k['foto'])
                                    <img src="{{ $k['foto'] }}" alt="{{ $k['nama'] }}" loading="lazy"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                        onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden'); this.nextElementSibling.classList.add('flex');">
                                    <div class="hidden w-full h-full items-center justify-center">
                                        <span class="font-display text-4xl font-bold text-[#c99a32]">{{ $inisial($k['nama']) }}</span>
                                    </div>
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="font-display text-4xl font-bold text-[#c99a32]">{{ $inisial($k['nama']) }}</span>
                                    </div>
                                @endif
                                <span class="absolute top-2.5 left-2.5 bg-white/95 backdrop-blur px-2 py-0.5 rounded-md text-[10px] font-bold text-[#0a1526] shadow-sm">{{ $k['nomor'] }}</span>
                            </div>

                            <div class="p-3.5">
                                <h3 class="text-[13px] font-bold text-[#0a1526] truncate">{{ $k['nama'] }}</h3>

                                @if ($blackoutAktif ?? false)
                                    <div class="mt-2 flex items-center gap-1.5 text-[10px] text-amber-600 italic">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                        </svg>
                                        <span>Jumlah suara dirahasiakan</span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-1.5 mt-2">
                                        <span class="text-[12px] font-bold text-[#c99a32]">{{ $k['persen'] }}%</span>
                                        <span class="text-[10px] text-[#8a8178]">Vote</span>
                                    </div>
                                @endif
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

                <div id="emptySearch" class="hidden mt-4 rounded-2xl border border-dashed border-[#ddd8cc] bg-white p-10 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#faf5e9] text-[#c99a32]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m21 21-4.35-4.35m1.35-5.4a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z" />
                        </svg>
                    </div>
                    <p class="mt-3 text-sm font-semibold text-[#0a1526]">Kandidat tidak ditemukan</p>
                    <p class="mt-1 text-xs text-[#8a8178]">Coba kata kunci lain.</p>
                </div>
            @else
                <div class="bg-white rounded-2xl border border-dashed border-[#e5e1db] p-12 text-center">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-[#fdf8ec] flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#c99a32]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-[#0a1526]">Belum ada kandidat</p>
                </div>
            @endif
        </section>

    </main>

    @include('partials.footer')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
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
        var mqDesktop = window.matchMedia('(min-width: 768px)');

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