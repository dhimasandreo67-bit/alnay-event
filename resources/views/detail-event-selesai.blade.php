<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $event->nama }} - Hasil Akhir | ALNAY.ID</title>

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

        .search-input {
            transition: border-color .25s ease, box-shadow .25s ease, transform .25s ease;
        }

        .search-input:focus {
            border-color: #c99a32;
            box-shadow: 0 0 0 4px rgba(201, 154, 50, .12);
        }

        /* ============================================ */
        /* ANIMASI ENTRANCE — SMOOTH                     */
        /* ============================================ */

        /* Reveal up — fade + slide up */
        .reveal-up {
            animation: revealUp .7s cubic-bezier(.22, 1, .36, 1) both;
        }

        @keyframes revealUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Stagger delay — card masuk berurutan */
        .reveal-up.delay-1 {
            animation-delay: .08s;
        }

        .reveal-up.delay-2 {
            animation-delay: .16s;
        }

        .reveal-up.delay-3 {
            animation-delay: .24s;
        }

        .reveal-up.delay-4 {
            animation-delay: .32s;
        }

        .reveal-up.delay-5 {
            animation-delay: .40s;
        }

        .reveal-up.delay-6 {
            animation-delay: .48s;
        }

        /* Banner — fade in smooth */
        .banner-reveal {
            animation: bannerReveal 1s cubic-bezier(.22, 1, .36, 1) both;
        }

        @keyframes bannerReveal {
            from {
                opacity: 0;
                transform: scale(.98) translateY(12px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        /* Card hover — lift halus */
        .card-lift {
            transition: transform .35s cubic-bezier(.22, 1, .36, 1),
                box-shadow .35s cubic-bezier(.22, 1, .36, 1),
                border-color .35s ease;
        }

        .card-lift:hover {
            transform: translateY(-6px);
            box-shadow: 0 22px 45px -18px rgba(198, 146, 32, .28),
                0 8px 18px -10px rgba(21, 35, 61, .12);
        }

        /* Foto — zoom halus saat hover */
        .card-lift:hover img {
            transform: scale(1.06);
        }

        /* Progress bar — animasi fill */
        .progress-fill {
            animation: progressFill 1.2s cubic-bezier(.22, 1, .36, 1) both;
            transform-origin: left;
        }

        @keyframes progressFill {
            from {
                transform: scaleX(0);
            }

            to {
                transform: scaleX(1);
            }
        }

        /* Badge — soft pulse untuk status */
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
                opacity: .7;
                transform: scale(1.1);
            }
        }

        /* Search input — focus glow */
        .search-input {
            transition: border-color .25s ease, box-shadow .25s ease, transform .25s ease;
        }

        .search-input:focus {
            border-color: #c99a32;
            box-shadow: 0 0 0 4px rgba(201, 154, 50, .12);
            transform: translateY(-1px);
        }

        /* Reduced motion */
        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body class="bg-[#faf9f6] text-[#15233d]">

    @php
        $formatAngka = function ($angka) {
            if ($angka >= 1000000) {
                return round($angka / 1000000, 1) . 'M';
            }
            if ($angka >= 1000) {
                return round($angka / 1000, 1) . 'K';
            }
            return number_format($angka, 0, ',', '.');
        };

        $inisial = fn($nama) => strtoupper(
            implode(
                '',
                array_map(fn($kata) => mb_substr($kata, 0, 1), array_slice(preg_split('/\s+/', trim($nama)), 0, 2)),
            ),
        );

        $kandidat = $pesertas
            ->map(function ($p, $i) use ($totalSuara) {
                return [
                    'id' => $p->id,
                    'nomor' => str_pad($i + 1, 2, '0', STR_PAD_LEFT),
                    'nama' => $p->namaTampil(),
                    'asal' => '-',
                    'foto' => $p->user?->foto_custom ? asset('storage/' . $p->user->foto_custom) : $p->user?->foto_url,
                    'persen' => $totalSuara > 0 ? round(($p->total_vote / $totalSuara) * 100, 2) : 0,
                    'vote' => $p->total_vote,
                ];
            })
            ->toArray();

        $pemenangArr = [];
        foreach (array_slice($kandidat, 0, 3) as $i => $k) {
            $k['juara'] = $i + 1;
            $pemenangArr[] = $k;
        }

        $peringkatLainArr = [];
        foreach (array_slice($kandidat, 3, 3) as $i => $k) {
            $k['peringkat'] = sprintf('%02d', $i + 4);
            $k['inisial'] = $inisial($k['nama']);
            $peringkatLainArr[] = $k;
        }

        $jumlahKandidat = count($kandidat);
    @endphp

    {{-- HEADER --}}
    @include('partials.header')

    {{-- MAIN CONTENT --}}

    <main class="max-w-5xl mx-auto px-5 pt-0 pb-6">

        {{-- EVENT BANNER --}}
        <section
            class="banner-reveal relative overflow-hidden rounded-2xl mx-0 md:-mx-10 mt-4 border border-[#ece5d7] shadow-sm group">
            @if ($event->banner)
                <img src="{{ asset('storage/' . $event->banner) }}" alt="{{ $event->nama }}"
                    class="w-full h-[220px] sm:h-[300px] md:h-[380px] object-cover object-center transition-transform duration-700 group-hover:scale-[1.02]"
                    onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden'); this.nextElementSibling.classList.add('flex');">

                {{-- Fallback kalau gambar gagal load --}}
                <div
                    class="hidden w-full h-[220px] sm:h-[300px] md:h-[380px] bg-gradient-to-br from-[#0a1526] to-[#1a2942] items-center justify-center">
                    <div class="text-center">
                        <p class="font-display text-2xl sm:text-3xl font-bold text-[#c99a32] tracking-wider">
                            {{ $event->nama }}</p>
                        <p class="mt-2 text-[10px] tracking-[0.3em] text-[#c5b388] uppercase font-semibold">Alnay Event
                            Center</p>
                    </div>
                </div>
            @else
                <div
                    class="w-full h-[220px] sm:h-[300px] md:h-[380px] bg-gradient-to-br from-[#0a1526] via-[#11223f] to-[#1a2942] flex items-center justify-center">
                    <div class="text-center px-4">
                        <p
                            class="font-display text-2xl sm:text-3xl md:text-4xl font-bold text-[#c99a32] tracking-wider drop-shadow-md">
                            {{ $event->nama }}</p>
                        <p
                            class="mt-2 text-[10px] sm:text-[11px] tracking-[0.3em] text-[#c5b388] uppercase font-semibold">
                            Alnay Event Center</p>
                    </div>
                </div>
            @endif

            {{-- Overlay gradient tipis di bawah biar transisi ke info card smooth --}}
            <div
                class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-[#0a1526]/40 to-transparent pointer-events-none">
            </div>

            {{-- Badge "Telah Berakhir" di atas banner --}}
            <span
                class="absolute top-4 right-4 z-10 inline-flex items-center gap-2 rounded-full border border-orange-400/30 bg-orange-950/70 backdrop-blur-md px-4 py-1.5 text-[11px] sm:text-[12px] font-semibold text-orange-300 shadow-lg">
                <span class="h-2 w-2 rounded-full bg-orange-400"></span>
                Telah Berakhir
            </span>
        </section>

        {{-- EVENT INFORMATION --}}
        <section
            class="reveal-up delay-1 relative -mt-6 mx-4 bg-white rounded-2xl shadow-sm border border-[#ece5d7] hover:border-[#c99a32]/40 transition-all duration-300">
            <div class="p-5 md:p-6">
                <div class="grid grid-cols-1 lg:grid-cols-[1fr_220px] gap-5 items-center">

                    <div>
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-orange-100 border border-orange-200 text-orange-700 text-[10px] font-bold uppercase tracking-wider">
                            <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                            Telah Berakhir
                        </span>

                        <h1 class="font-display text-xl md:text-2xl font-bold text-[#0a1526] mt-3">
                            {{ $event->nama }}
                        </h1>

                        <p class="text-gray-500 text-xs md:text-sm leading-relaxed mt-2.5 max-w-3xl">
                            {{ $event->deskripsi ?: 'Deskripsi belum diisi.' }}
                        </p>
                    </div>

                    <div
                        class="relative overflow-hidden bg-gradient-to-br from-[#faf8f2] to-[#fdfcf9] border border-[#eee5d2] rounded-2xl p-4">
                        <span
                            class="absolute -right-3 -top-3 text-6xl font-black text-[#c99a32]/5 pointer-events-none select-none">★</span>
                        <div class="relative grid grid-cols-2 gap-4 w-full text-center">
                            <div>
                                <p class="text-xl font-bold text-[#0a1526]">{{ $jumlahKandidat }}</p>
                                <p class="text-[10px] uppercase tracking-wider text-[#8a8178] font-bold mt-1">Kandidat
                                </p>
                            </div>
                            <div>
                                <p class="text-xl font-bold text-[#c99a32]">{{ $formatAngka($totalSuara) }}</p>
                                <p class="text-[10px] uppercase tracking-wider text-[#8a8178] font-bold mt-1">Total Vote
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        {{-- PERINGKAT --}}
        @if ($blackoutAktif ?? false)
            {{-- Blackout notice --}}
            <section class="reveal-up delay-2 mt-10">
                {{-- Section Header --}}
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-5">
                    <div>
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#c99a32]/10 border border-[#c99a32]/30 text-[10px] font-bold uppercase tracking-widest text-[#b88625]">
                            Hasil Akhir
                        </span>
                    </div>

                    <p class="text-[10px] uppercase tracking-[0.35em] text-[#c99a32] font-bold mb-4">
                        Peringkat Sementara
                    </p>
                    <h2 class="font-display text-[26px] md:text-[34px] font-bold text-[#0a1526] leading-tight mb-5">
                        Siapa yang akan<br class="hidden sm:block"> membawa pulang mahkota?
                    </h2>
                    <p class="max-w-xl mx-auto text-[13px] md:text-[15px] leading-[1.8] text-[#5a5750]">
                        Untuk menjaga ketegangan menjelang malam puncak, kami merahasiakan
                        peringkat sementara saat ini. Ikuti terus perjalanan para finalis.
                    </p>
                    @if ($event->voting_selesai)
                        <div class="mt-8 inline-flex flex-col items-center gap-2">
                            <p class="text-[10px] uppercase tracking-[0.25em] text-[#8a8178] font-bold">Malam Puncak
                            </p>
                            <p class="font-display text-lg md:text-xl font-bold text-[#0a1526]">
                                {{ $event->voting_selesai->translatedFormat('d F Y') }}
                            </p>
                            <p class="text-[12px] text-[#8a8178]">Pukul {{ $event->voting_selesai->format('H:i') }}
                                WIB</p>
                        </div>
                    @endif
                </div>

                <div class="border-t border-[#e8dcc0]/60 bg-white/40 px-6 py-4 md:px-12">
                    <div class="flex items-center justify-center gap-2 text-[11px] text-[#8a8178]">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="italic">Peringkat akan diumumkan secara resmi pada malam puncak.</span>
                    </div>
                </div>
                </div>
            </section>
        @else
            <section class="mt-10">
                {{-- Section Header --}}
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-5">
                    <div>
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#c99a32]/10 border border-[#c99a32]/30 text-[10px] font-bold uppercase tracking-widest text-[#b88625]">
                            Hasil Akhir
                        </span>
                        <h2 class="font-display mt-2.5 text-[22px] md:text-[26px] font-bold text-[#0a1526]">
                            Peringkat Sekarang
                        </h2>
                        <p class="text-gray-500 text-xs md:text-sm mt-1">
                            Peringkat peserta berdasarkan perolehan suara.
                        </p>
                    </div>
                    <p class="text-[12px] text-[#8a8178] whitespace-nowrap">
                        Total <strong class="text-[#0a1526]">{{ number_format($totalSuara, 0, ',', '.') }}</strong>
                        suara dari
                        <strong class="text-[#0a1526]">{{ $jumlahKandidat }}</strong> kandidat
                    </p>
                </div>

                @if (count($pemenangArr) > 0)
                    {{-- KARTU TIGA BESAR --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4 items-end">
                        @foreach ($pemenangArr as $p)
                            <div
                                class="reveal-up delay-{{ $p['juara'] + 2 }} group bg-white rounded-2xl overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-xl
                            {{ $p['juara'] == 1 ? 'border-2 border-[#c99a32] shadow-md sm:order-2' : 'border border-[#ece5d7] shadow-sm hover:border-[#c99a32]/40 ' . ($p['juara'] == 2 ? 'sm:order-1' : 'sm:order-3') }}">

                                <div
                                    class="relative overflow-hidden {{ $p['juara'] == 1 ? 'h-[230px] sm:h-[270px]' : 'h-[190px] sm:h-[210px]' }}">
                                    @if ($p['foto'])
                                        <img src="{{ $p['foto'] }}" alt="{{ $p['nama'] }}"
                                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                            onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden'); this.nextElementSibling.classList.add('flex');">
                                        <div
                                            class="hidden w-full h-full bg-gradient-to-br from-[#fbf6e9] to-[#f4ede0] items-center justify-center">
                                            <span
                                                class="font-display text-4xl font-bold text-[#c99a32]">{{ $inisial($p['nama']) }}</span>
                                        </div>
                                    @else
                                        <div
                                            class="w-full h-full bg-gradient-to-br from-[#fbf6e9] to-[#f4ede0] flex items-center justify-center">
                                            <span
                                                class="font-display text-4xl font-bold text-[#c99a32]">{{ $inisial($p['nama']) }}</span>
                                        </div>
                                    @endif

                                    {{-- Badge nomor --}}
                                    <span
                                        class="absolute top-3 left-3 bg-white/95 backdrop-blur px-2.5 py-1 rounded-lg text-[11px] font-bold text-[#0a1526] shadow-sm">
                                        {{ $p['nomor'] }}
                                    </span>

                                    {{-- Badge juara --}}
                                    <span
                                        class="absolute top-3 right-3 inline-flex items-center gap-1 bg-gradient-to-r from-[#c99a32] to-[#b88625] text-white text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase tracking-wider shadow-md">
                                        @if ($p['juara'] == 1)
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z" />
                                            </svg>
                                        @endif
                                        Juara {{ $p['juara'] }}
                                    </span>
                                </div>

                                <div class="p-4">
                                    <div class="flex items-center justify-between gap-2 mb-2">
                                        <h3 class="font-bold text-[14px] text-[#0a1526] truncate">{{ $p['nama'] }}
                                        </h3>
                                        <span
                                            class="text-[10px] font-semibold text-[#c99a32] bg-[#fbf6e9] px-2 py-1 rounded-md whitespace-nowrap">
                                            {{ $p['asal'] }}
                                        </span>
                                    </div>

                                    <div class="flex items-baseline gap-1.5">
                                        <p class="text-lg font-bold text-[#0a1526]">{{ $p['persen'] }}%</p>
                                        <span class="text-[11px] text-[#8a8178]">Vote</span>
                                    </div>

                                                                        <div class="w-full h-1.5 bg-[#f0ece2] rounded-full mt-2.5 overflow-hidden">
                                        <div class="progress-fill h-full bg-gradient-to-r from-[#c99a32] to-[#e0b254] rounded-full"
                                            style="width: {{ $p['persen'] }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if (count($peringkatLainArr) > 0)
                        {{-- PERINGKAT 4-6 --}}
                        <div
                            class="reveal-up delay-4 mt-5 bg-white rounded-2xl border border-[#ece5d7] shadow-sm overflow-hidden divide-y divide-[#f4f1e9]">
                            <p class="text-[10px] uppercase tracking-widest text-[#8a8178] font-bold">Peringkat
                                Lanjutan</p>
                        </div>

                        @foreach ($peringkatLainArr as $r)
                            <div
                                class="group flex items-center gap-3 px-5 py-3.5 hover:bg-[#fdfcf9] transition-colors duration-200">
                                <span class="font-display text-[15px] font-bold text-[#c99a32] w-7 shrink-0">
                                    {{ $r['peringkat'] }}
                                </span>

                                <div
                                    class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#fbf6e9] to-[#f4ede0] text-[#c99a32] text-[12px] font-bold flex items-center justify-center shrink-0 border border-[#eee5d2]">
                                    {{ $r['inisial'] }}
                                </div>

                                <p class="text-[13px] font-bold text-[#0a1526] w-40 truncate">{{ $r['nama'] }}
                                </p>

                                <span
                                    class="text-[10px] font-semibold text-[#c99a32] bg-[#fbf6e9] px-2 py-1 rounded-md whitespace-nowrap">
                                    {{ $r['asal'] }}
                                </span>

                                <div class="flex-1 h-1.5 bg-[#f0ece2] rounded-full mx-2 overflow-hidden">
                                    <div class="progress-fill h-full bg-gradient-to-r from-[#c99a32] to-[#e0b254] rounded-full"
                                        style="width: {{ $r['persen'] }}%"></div>
                                </div>

                                <span
                                    class="font-bold text-[13px] text-[#0a1526] whitespace-nowrap tabular-nums">{{ $r['persen'] }}%</span>
                            </div>
                        @endforeach
                        </div>
                    @endif
                @else
                    <div class="bg-white rounded-2xl border border-dashed border-[#e5e1db] p-12 text-center mt-4">
                        <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-[#fdf8ec] flex items-center justify-center">
                            <svg class="w-6 h-6 text-[#c99a32]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-[#0a1526]">Belum ada peserta</p>
                        <p class="mt-1 text-xs text-[#8a8178]">Event ini belum memiliki peserta yang diterima.</p>
                    </div>
                @endif
            </section>
        @endif

        {{-- DAFTAR KANDIDAT --}}
        <section class="reveal-up delay-5 mt-10">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-5">
                <div>
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#0a1526]/5 border border-[#0a1526]/10 text-[10px] font-bold uppercase tracking-widest text-[#0a1526]">
                        Semua Peserta
                    </span>
                    <h2 class="font-display mt-2.5 text-[22px] md:text-[26px] font-bold text-[#0a1526]">
                        Daftar Kandidat
                    </h2>
                    <p class="text-[12px] text-[#8a8178] mt-1">
                        <strong class="text-[#0a1526]">{{ $jumlahKandidat }}</strong> kandidat terdaftar
                    </p>
                </div>

                {{-- SEARCH KANDIDAT --}}
                <div class="relative w-full sm:w-64 shrink-0">
                    <input type="text" id="searchKandidat" placeholder="Cari kandidat..."
                        class="search-input h-10 w-full rounded-full border border-[#ddd9d0] bg-white pl-9 pr-4 text-xs outline-none focus:border-[#c99a32] focus:ring-0 transition">

                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-3 h-4 w-4 text-gray-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="m21 21-4.35-4.35m1.35-5.4a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z" />
                    </svg>
                </div>
            </div>

            @if (count($kandidat) > 0)
                <div id="gridKandidat" class="grid grid-cols-2 md:grid-cols-4 gap-3.5 sm:gap-4">
                    @foreach ($kandidat as $k)
                        <div class="kandidat-card reveal-up delay-{{ (($loop->iteration - 1) % 4) + 1 }} group bg-white rounded-2xl overflow-hidden border border-[#ece5d7] shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-[#c99a32]/40"
                            data-nama="{{ strtolower($k['nama']) }}">
                            <div
                                class="relative h-[180px] md:h-[200px] overflow-hidden bg-gradient-to-br from-[#fbf6e9] to-[#f4ede0]">
                                @if ($k['foto'])
                                    <img src="{{ $k['foto'] }}" alt="{{ $k['nama'] }}"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                        onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden'); this.nextElementSibling.classList.add('flex');">
                                    <div class="hidden w-full h-full items-center justify-center">
                                        <span
                                            class="font-display text-4xl font-bold text-[#c99a32]">{{ $inisial($k['nama']) }}</span>
                                    </div>
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span
                                            class="font-display text-4xl font-bold text-[#c99a32]">{{ $inisial($k['nama']) }}</span>
                                    </div>
                                @endif

                                <span
                                    class="absolute top-2.5 left-2.5 bg-white/95 backdrop-blur px-2 py-0.5 rounded-md text-[10px] font-bold text-[#0a1526] shadow-sm">
                                    {{ $k['nomor'] }}
                                </span>
                            </div>

                            <div class="p-3.5">
                                <h3 class="text-[13px] font-bold text-[#0a1526] truncate">{{ $k['nama'] }}</h3>
                                <p class="text-[10px] text-[#8a8178] mt-0.5">{{ $k['asal'] }}</p>

                                <div class="flex items-center gap-1.5 mt-2">
                                    <span class="text-[12px] font-bold text-[#c99a32]">{{ $k['persen'] }}%</span>
                                    <span class="text-[10px] text-[#8a8178]">Vote</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{-- Empty state kalau search gak ketemu --}}
                <div id="emptySearch"
                    class="hidden mt-4 rounded-2xl border border-dashed border-[#ddd8cc] bg-white p-10 text-center">
                    <div
                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#faf5e9] text-[#c99a32]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="m21 21-4.35-4.35m1.35-5.4a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z" />
                        </svg>
                    </div>
                    <p class="mt-3 text-sm font-semibold text-[#0a1526]">
                        Kandidat tidak ditemukan
                    </p>
                    <p class="mt-1 text-xs text-[#8a8178]">
                        Coba kata kunci lain.
                    </p>
                </div>
            @else
                <div class="bg-white rounded-2xl border border-dashed border-[#e5e1db] p-12 text-center">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-[#fdf8ec] flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#c99a32]" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-[#0a1526]">Belum ada kandidat</p>
                </div>
            @endif
        </section>

    </main>

    @include('partials.footer')

    <script>
        // Search kandidat
        (function() {
            var input = document.getElementById('searchKandidat');
            var cards = document.querySelectorAll('.kandidat-card');
            var empty = document.getElementById('emptySearch');

            if (!input) return;

            input.addEventListener('input', function() {
                var q = this.value.toLowerCase().trim();
                var visible = 0;

                cards.forEach(function(card) {
                    var match = card.dataset.nama.includes(q);
                    card.style.display = match ? '' : 'none';
                    if (match) visible++;
                });

                if (empty) {
                    empty.classList.toggle('hidden', visible > 0);
                }
            });
        })();
    </script>
</body>

</html>
