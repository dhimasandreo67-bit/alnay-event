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
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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

        .event-banner {
            position: relative;
        }

        .event-banner::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(
                180deg,
                rgba(10, 21, 38, 0.02) 25%,
                rgba(10, 21, 38, 0.38) 100%
            );
            pointer-events: none;
        }

        .kandidat-card {
            transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
        }

        .kandidat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 30px rgba(21, 35, 61, .09);
            border-color: #e6d3a7;
        }

        .kandidat-card img {
            transition: transform .45s ease;
        }

        .kandidat-card:hover img {
            transform: scale(1.045);
        }

        .detail-button {
            transition: all .2s ease;
        }

        .detail-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 12px rgba(198, 146, 32, .2);
        }

        .gold-line {
            width: 32px;
            height: 2px;
            background: #c99a32;
        }

        .stat-box {
            transition: background .2s ease, transform .2s ease;
        }

        .stat-box:hover {
            background: #f6f1e6;
            transform: translateY(-1px);
        }

        .countdown-item {
            transition: transform .2s ease;
        }

        .countdown-item:hover {
            transform: translateY(-2px);
        }

        .modal-panel {
            animation: modalUp .22s ease-out;
        }

        @keyframes modalUp {
            from {
                opacity: 0;
                transform: translateY(12px) scale(.985);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .fade-up {
            animation: fadeUp .45s ease both;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .search-input {
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        .search-input:focus {
            box-shadow: 0 0 0 3px rgba(201, 154, 50, .10);
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
                array_map(
                    fn($kata) => mb_substr($kata, 0, 1),
                    array_slice(preg_split('/\s+/', trim($nama)), 0, 2)
                )
            )
        );

        $pecahBaris = fn($teks) => array_values(
            array_filter(
                array_map('trim', preg_split('/\R/', (string) $teks))
            )
        );

        $ketentuanVoting = $pecahBaris($event->syarat_ketentuan ?? '');

        // =====================================================
        // LOGIKA TURUNAN
        // =====================================================
        $tz = 'Asia/Jakarta';
        $now = \Carbon\Carbon::now($tz);

        $mulai = $event->tanggal_mulai
            ? \Carbon\Carbon::parse($event->tanggal_mulai, $tz)->locale('id')
            : null;

        $selesai = $event->tanggal_selesai
            ? \Carbon\Carbon::parse($event->tanggal_selesai, $tz)->locale('id')
            : null;

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

        $periode = $selesai
            ? $rentang($mulai, $selesai)
            : ($mulai ? 'Mulai ' . $rentang($mulai) : '-');

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
            'tanggal' => $periode
        ];

        $jadwal[] = [
            'nama' => 'Pengumuman Pemenang',
            'tanggal' => 'Setelah voting berakhir'
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
                    'foto' => $p->user?->foto_custom
                        ? asset('storage/' . $p->user->foto_custom)
                        : $p->user?->foto_url,
                    'inisial' => strtoupper(
                        implode(
                            '',
                            array_map(
                                fn($kata) => mb_substr($kata, 0, 1),
                                array_slice(preg_split('/\s+/', trim($nama)), 0, 2)
                            )
                        )
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
        <section class="event-banner relative mx-0 mt-4 overflow-hidden rounded-[18px] shadow-sm">
            @if ($event->banner)
                <img src="{{ asset('storage/' . $event->banner) }}"
                    alt="{{ $event->nama }}"
                    class="h-[220px] w-full object-cover object-center sm:h-[285px] md:h-[350px]">
            @else
                <div class="flex h-[220px] w-full items-center justify-center bg-gradient-to-br from-[#091527] via-[#13223a] to-[#243754] sm:h-[285px] md:h-[350px]">
                    <div class="relative z-10 text-center px-5">
                        <div class="gold-line mx-auto mb-3"></div>
                        <p class="font-display text-2xl font-bold tracking-wide text-[#d3a343] md:text-3xl">
                            {{ $event->nama }}
                        </p>
                    </div>
                </div>
            @endif
        </section>

        @if (!$event->voting_aktif)
            <div class="mx-2 mt-3 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3">
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-amber-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    stroke-width="2">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 9v2m0 4h.01M5 19h14a2 2 0 001.84-2.75L13.74 4a2 2 0 00-3.5 0L3.16 16.25A2 2 0 005 19z" />
                </svg>

                <div>
                    <p class="m-0 text-[13px] font-bold text-amber-800">
                        Voting Ditutup
                    </p>
                    <p class="mt-0.5 m-0 text-[12px] leading-relaxed text-amber-700">
                        Admin telah menutup voting untuk event ini. Kamu masih bisa melihat hasil & daftar kandidat.
                    </p>
                </div>
            </div>
        @endif

        {{-- EVENT INFORMATION --}}
        <section class="relative -mt-5 mx-2 overflow-hidden rounded-[18px] border border-[#ebe7df] bg-white shadow-[0_12px_35px_rgba(21,35,61,.07)]">
            <div class="p-4 md:p-5">
                <div class="grid grid-cols-1 gap-5 lg:grid-cols-[1fr_220px]">

                    <div>
                        @if (!$event->voting_aktif)
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-[#15233d] px-2.5 py-1 text-[9px] font-semibold uppercase tracking-wider text-white">
                                <span class="h-1.5 w-1.5 rounded-full bg-[#c99a32]"></span>
                                Voting Ditutup
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-[#f8f2e5] px-2.5 py-1 text-[9px] font-semibold uppercase tracking-wider text-[#9b762d]">
                                <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-[#c99a32]"></span>
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
                            <span class="inline-flex items-center rounded-full border border-[#eee8da] bg-[#faf8f2] px-2.5 py-1 text-[10px] text-gray-600">
                                {{ $periode }}
                            </span>

                            <span class="inline-flex items-center rounded-full border border-[#eee8da] bg-[#faf8f2] px-2.5 py-1 text-[10px] text-gray-600">
                                {{ $rp($event->harga_per_vote) }} / vote
                            </span>

                            @if ($event->lokasi)
                                <span class="inline-flex items-center rounded-full border border-[#eee8da] bg-[#faf8f2] px-2.5 py-1 text-[10px] text-gray-600">
                                    {{ $event->lokasi }}
                                </span>
                            @endif
                        </div>

                        <button type="button"
                            data-buka-syarat
                            class="mt-3 inline-flex items-center gap-1 text-[11px] font-semibold text-[#a37b2a] transition hover:text-[#c99a32]">
                            Lihat Syarat &amp; Ketentuan
                            <span class="transition-transform group-hover:translate-x-0.5">→</span>
                        </button>
                    </div>

                    {{-- COUNTDOWN --}}
                    <div class="rounded-xl border border-[#eee5d2] bg-[#faf8f2] p-3.5">
                        <p class="text-center text-[9px] font-bold uppercase tracking-[.18em] text-gray-500">
                            {{ $cdLabel }}
                        </p>

                        @if ($selesai)
                            <div class="mt-2.5 flex items-center justify-center gap-1.5">
                                <div class="countdown-item min-w-[39px] rounded-lg bg-white px-1.5 py-2 text-center shadow-sm">
                                    <span class="block text-lg font-bold leading-none text-[#c99a32]">
                                        {{ sprintf('%02d', $cdHari) }}
                                    </span>
                                    <span class="mt-1 block text-[7px] font-semibold uppercase text-gray-400">
                                        Hari
                                    </span>
                                </div>

                                <span class="text-sm font-bold text-[#c99a32]">:</span>

                                <div class="countdown-item min-w-[39px] rounded-lg bg-white px-1.5 py-2 text-center shadow-sm">
                                    <span class="block text-lg font-bold leading-none text-[#c99a32]">
                                        {{ sprintf('%02d', $cdJam) }}
                                    </span>
                                    <span class="mt-1 block text-[7px] font-semibold uppercase text-gray-400">
                                        Jam
                                    </span>
                                </div>

                                <span class="text-sm font-bold text-[#c99a32]">:</span>

                                <div class="countdown-item min-w-[39px] rounded-lg bg-white px-1.5 py-2 text-center shadow-sm">
                                    <span class="block text-lg font-bold leading-none text-[#c99a32]">
                                        {{ sprintf('%02d', $cdMenit) }}
                                    </span>
                                    <span class="mt-1 block text-[7px] font-semibold uppercase text-gray-400">
                                        Menit
                                    </span>
                                </div>
                            </div>
                        @endif

                        <div class="mt-3 grid grid-cols-2 gap-2 border-t border-[#e7dfd0] pt-3">
                            <div class="stat-box rounded-lg px-2 py-1.5 text-center">
                                <p class="text-base font-bold text-[#15233d]">
                                    {{ $jumlahPeserta }}
                                </p>
                                <p class="mt-0.5 text-[9px] text-gray-500">
                                    Kandidat
                                </p>
                            </div>

                            <div class="stat-box rounded-lg px-2 py-1.5 text-center">
                                <p class="text-base font-bold text-[#15233d]">
                                    {{ $formatAngka($totalSuara) }}
                                </p>
                                <p class="mt-0.5 text-[9px] text-gray-500">
                                    Total Vote
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- CANDIDATE SECTION --}}
        <section class="mt-7">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <div class="gold-line"></div>
                        <span class="text-[9px] font-semibold uppercase tracking-[.18em] text-[#b28a38]">
                            Peserta
                        </span>
                    </div>
                    <h2 class="mt-1 font-display text-lg font-bold text-[#15233d] md:text-xl">
                        Daftar Kandidat
                    </h2>
                </div>

                <div class="relative">
                    <input type="text"
                        id="searchKandidat"
                        placeholder="Cari kandidat..."
                        class="search-input h-10 w-full rounded-full border border-[#ddd9d0] bg-white pl-9 pr-4 text-xs outline-none focus:border-[#c99a32] focus:ring-0 lg:w-60">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="absolute left-3 top-3 h-4 w-4 text-gray-400"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="m21 21-4.35-4.35m1.35-5.4a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z" />
                    </svg>
                </div>
            </div>

            @if (count($kandidat) > 0)
                <div id="gridKandidat"
                    class="mt-3.5 grid grid-cols-1 gap-3.5 sm:grid-cols-2 lg:grid-cols-4">

                    @foreach ($kandidat as $item)
                        <div class="kandidat-card fade-up overflow-hidden rounded-xl border border-[#ebe8e1] bg-white"
                            data-nama="{{ strtolower($item['nama']) }}">

                            <div class="relative h-[175px] overflow-hidden bg-[#fbf6e9]">
                                @if ($item['foto'])
                                    <img src="{{ $item['foto'] }}"
                                        alt="{{ $item['nama'] }}"
                                        class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-[#faf5e9] to-[#f2e7cb]">
                                        <span class="font-display text-4xl font-bold text-[#c99a32]">
                                            {{ $item['inisial'] }}
                                        </span>
                                    </div>
                                @endif

                                <div class="absolute inset-x-0 bottom-0 h-14 bg-gradient-to-t from-black/25 to-transparent"></div>

                                <span class="absolute left-2.5 top-2.5 rounded-full bg-white/95 px-2.5 py-1.5 text-[10px] font-bold text-[#15233d] shadow-sm backdrop-blur">
                                    {{ $item['nomor'] }}
                                </span>
                            </div>

                            <div class="p-3.5">
                                <div class="flex items-start justify-between gap-2">
                                    <h3 class="min-w-0 truncate text-[13px] font-semibold text-[#15233d]">
                                        {{ $item['nama'] }}
                                    </h3>

                                    <span class="shrink-0 rounded-full bg-[#fbf6e9] px-2 py-1 text-[9px] font-semibold text-[#b28a38]">
                                        {{ $item['asal'] }}
                                    </span>
                                </div>

                                <div class="mt-2 flex items-center gap-1.5 text-[10px] text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-3.5 w-3.5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.5"
                                            d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 0115 0" />
                                    </svg>
                                    <span>{{ $item['vote'] }} suara</span>
                                </div>

                                @php
                                    $voteUrl = '/peserta/' . $item['id'];
                                @endphp

                                <a href="/peserta/{{ $item['id'] }}"
                                    class="detail-button mt-2.5 block w-full rounded-lg bg-[#c69220] py-2 text-center text-[11px] font-semibold text-white">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div id="emptySearch"
                    class="hidden mt-3.5 rounded-xl border border-dashed border-[#ddd8cc] bg-white p-8 text-center">
                    <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-[#faf5e9] text-[#c99a32]">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
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
                    <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-[#faf5e9] text-[#c99a32]">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
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

        <div class="modal-panel flex max-h-[88vh] w-full max-w-xl flex-col overflow-hidden rounded-t-2xl bg-white shadow-2xl sm:rounded-2xl">

            <div class="flex items-center justify-between border-b border-gray-100 px-5 py-3.5">
                <div>
                    <div class="gold-line mb-1.5"></div>
                    <h2 class="font-display text-lg font-bold text-[#15233d]">
                        Syarat &amp; Ketentuan
                    </h2>
                </div>

                <button type="button"
                    data-tutup-syarat
                    aria-label="Tutup"
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
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-white font-display text-sm font-bold text-[#c99a32] shadow-sm">
                                1
                            </span>
                            Pilih kandidat favorit Anda.
                        </li>

                        <li class="flex items-center gap-3 rounded-lg bg-[#faf8f2] p-2.5 text-xs text-[#15233d]">
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-white font-display text-sm font-bold text-[#c99a32] shadow-sm">
                                2
                            </span>
                            Tekan tombol Vote ({{ $rp($event->harga_per_vote) }} per vote) dan selesaikan pembayaran.
                        </li>

                        <li class="flex items-center gap-3 rounded-lg bg-[#faf8f2] p-2.5 text-xs text-[#15233d]">
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-white font-display text-sm font-bold text-[#c99a32] shadow-sm">
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

                        <ul class="mt-2.5 space-y-1.5 pl-5 text-xs leading-relaxed text-gray-600 marker:text-[#c99a32] list-disc">
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
                                <span class="absolute -left-[25px] top-1 h-2.5 w-2.5 rounded-full border-2 border-white bg-[#c99a32] shadow-sm"></span>

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
                <button type="button"
                    data-tutup-syarat
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