<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event & Vote Photo - ALNAY.ID</title>

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
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            background: #fff;
        }

        .display-font {
            font-family: "Playfair Display", Georgia, serif;
        }

        .card-shadow {
            box-shadow: 0 12px 28px rgba(0, 0, 0, .16);
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
    @endphp

    {{-- HEADER --}}
    <header class="sticky top-0 z-50 border-b border-[#e9e9e9] bg-white/95 backdrop-blur">
        <div class="mx-auto flex h-[56px] max-w-[1200px] items-center justify-between px-5 md:px-8">
            <a href="{{ route('dashboard-user') }}"
                class="display-font text-[16px] font-bold tracking-tight text-[#13223a]">
                EVENTALNAY.ID
            </a>

            @auth('pelanggan')
                @php
                    $u = auth('pelanggan')->user();
                    $fotoHeader = $u->foto_custom ? asset('storage/' . $u->foto_custom) : $u->foto_url;
                    $inisialHeader = strtoupper(substr(trim($u->nama_custom ?: $u->nama), 0, 1));
                @endphp
                <a href="{{ route('profil-user') }}" aria-label="Profil pengguna"
                    class="flex h-9 w-9 items-center justify-center rounded-full border border-[#d9d9d9] bg-white text-[#333] transition hover:bg-[#f7f7f7] overflow-hidden">
                    @if ($fotoHeader)
                        <img src="{{ $fotoHeader }}" alt="{{ $u->nama_custom ?: $u->nama }}"
                            class="w-full h-full object-cover">
                    @else
                        <span class="text-[11px] font-bold">{{ $inisialHeader }}</span>
                    @endif
                </a>
            @else
                <a href="{{ route('auth.google') }}"
                    class="rounded-full bg-[#cf9b32] px-4 py-2 text-[11px] font-semibold text-white transition hover:bg-[#b88625]">
                    Login
                </a>
            @endauth
        </div>
    </header>

    {{-- HERO --}}
    <section class="px-5 pb-8 pt-6 md:px-8 md:pt-8">
        <div class="mx-auto flex max-w-[1200px] flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <h1 class="display-font font-bold text-[32px] leading-tight text-[#111827] md:text-[36px]">
                    Event &amp; Vote Photo
                </h1>
                <p class="mt-3 max-w-[560px] text-[14px] leading-6 text-[#6b6b6b] md:text-[15px]">
                    Dukung perwakilan terbaik dan ikuti berbagai perayaan budaya yang menginspirasi.
                </p>
            </div>

            <div class="flex shrink-0 gap-2">
                <a href="{{ route('dashboard-user', ['tab' => 'terkini']) }}"
                    class="rounded-full px-4 py-2 text-[12px] font-semibold transition {{ $tab === 'terkini' ? 'bg-black text-[#d4a63d]' : 'border border-[#dedede] bg-white text-[#333] hover:bg-[#f7f7f7]' }}">
                    Event Terkini
                </a>
                <a href="{{ route('dashboard-user', ['tab' => 'sebelumnya']) }}"
                    class="rounded-full px-4 py-2 text-[12px] font-semibold transition {{ $tab === 'sebelumnya' ? 'bg-black text-[#d4a63d]' : 'border border-[#dedede] bg-white text-[#333] hover:bg-[#f7f7f7]' }}">
                    Event Sebelumnya
                </a>
            </div>
        </div>
    </section>

    {{-- KONTEN TAB --}}
    <main class="mx-auto max-w-[1200px] px-5 pb-6 md:px-8">

        @if ($tab === 'terkini')

            {{-- ============================================ --}}
            {{-- CARD BESAR #1: Event Berlangsung (prioritas) ATAU Event Akan Datang --}}
            {{-- ============================================ --}}
            @php
                $eventHero = $eventBerlangsung ?? $eventAkanDatangUtama;
                $heroIsBerlangsung = $eventHero && $eventHero->status === 'sedang_berlangsung';
            @endphp

            @if ($eventHero)
                <article class="card-shadow overflow-hidden rounded-[10px] border border-[#ededed] bg-white">
                    <div
                        class="relative h-[260px] sm:h-[340px] md:h-[420px] max-h-[420px] overflow-hidden bg-[#efe6d3]">
                        @if ($eventHero->banner)
                            <img src="{{ asset('storage/' . $eventHero->banner) }}" alt="{{ $eventHero->nama }}"
                                class="h-full w-full object-cover">
                        @else
                            <div class="h-full w-full flex items-center justify-center text-[#a99260] text-sm">
                                Belum ada banner diunggah
                            </div>
                        @endif

                        @if ($heroIsBerlangsung)
                            <span
                                class="absolute right-4 top-4 rounded-full bg-[#c99a3a] px-3 py-1.5 text-[10px] font-semibold text-white shadow-sm">
                                ● Sedang Berlangsung
                            </span>
                        @else
                            <span
                                class="absolute right-4 top-4 rounded-full bg-blue-500 px-3 py-1.5 text-[10px] font-semibold text-white shadow-sm">
                                ● Akan Datang
                            </span>
                        @endif
                    </div>

                    <div class="flex flex-col gap-6 p-6 sm:p-7 md:flex-row md:items-center md:justify-between">
                        <div class="max-w-[560px]">
                            <h2 class="text-[19px] font-bold leading-6 text-[#13223a] md:text-[20px]">
                                {{ $eventHero->nama }}
                            </h2>

                            <p class="mt-3 text-[13px] leading-[1.65] text-[#666]">
                                {{ $eventHero->deskripsi ?: 'Deskripsi belum diisi.' }}
                            </p>

                            <div class="mt-4 flex items-center gap-2 text-[12px] text-[#666]">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#C9962E"
                                    stroke-width="1.7">
                                    <rect x="3" y="4.5" width="18" height="17" rx="2"></rect>
                                    <path d="M16 2.5v4M8 2.5v4M3 9h18"></path>
                                </svg>
                                <span>
                                    {{ $eventHero->tanggal_mulai?->format('d M Y') }}
                                    -
                                    {{ $eventHero->tanggal_selesai?->format('d M Y') }}
                                </span>
                            </div>
                        </div>

                        <div class="w-full shrink-0 md:w-[360px]">
                            @if ($heroIsBerlangsung)
                                {{-- Statistik event berlangsung --}}
                                <div
                                    class="grid grid-cols-3 overflow-hidden rounded-[10px] border border-[#ece5d7] bg-[#fcfaf6] text-center">
                                    <div class="px-2 py-3">
                                        <div class="text-[9px] text-[#888]">Sisa Waktu</div>
                                        <div class="mt-1 text-[13px] font-bold text-[#27303d]">{{ $sisaWaktu ?? '-' }}
                                            Hari</div>
                                    </div>
                                    <div class="border-x border-[#e8e1d5] px-2 py-3">
                                        <div class="text-[9px] text-[#888]">Peserta</div>
                                        <div class="mt-1 text-[13px] font-bold text-[#27303d]">{{ $jumlahPeserta }}
                                        </div>
                                    </div>
                                    <div class="px-2 py-3">
                                        <div class="text-[9px] text-[#888]">Total Suara</div>
                                        <div class="mt-1 text-[13px] font-bold text-[#c87918]">
                                            {{ number_format($totalSuara, 0, ',', '.') }}</div>
                                    </div>
                                </div>

                                <a href="{{ route('public.registration', $eventHero) }}"
                                    class="mt-4 block w-full rounded-[4px] bg-[#cf9b32] py-3 text-center text-[12px] font-semibold text-white transition hover:bg-[#b88625]">
                                    Dukung Peserta Favoritmu <span class="ml-1">→</span>
                                </a>
                            @else
                                {{-- Info event akan datang — karena gak ada event berlangsung --}}
                                <div
                                    class="rounded-[10px] border border-[#ece5d7] bg-[#fcfaf6] p-4 text-[12px] text-[#4B463F]">
                                    <p class="font-semibold text-[#13223a] mb-1">Pendaftaran Dibuka</p>
                                    <p>Daftar sebelum
                                        <span class="font-bold text-[#13223a]">
                                            {{ $eventHero->batas_pendaftaran_tanggal?->format('d M Y') ?? 'segera' }}
                                        </span>
                                    </p>
                                </div>

                                <a href="{{ route('public.registration', $eventHero) }}"
                                    class="mt-4 block w-full rounded-[4px] bg-[#cf9b32] py-3 text-center text-[12px] font-semibold text-white transition hover:bg-[#b88625]">
                                    Daftar Sebagai Peserta <span class="ml-1">→</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </article>
            @endif

            {{-- ============================================ --}}
            {{-- CARD KECIL: Event Akan Datang Lainnya --}}
            {{-- (kalau ada berlangsung → semua akan datang; kalau gak ada → #2, #3) --}}
            {{-- ============================================ --}}
            @if ($eventAkanDatangLainnya->count() > 0)
                <section class="mt-10">
                    <h2 class="display-font text-xl font-bold text-[#111827] md:text-[22px]">
                        Event Akan Datang
                    </h2>

                    <div class="mt-4 space-y-3">
                        @foreach ($eventAkanDatangLainnya as $event)
                            <div
                                class="card-shadow flex items-center gap-4 rounded-[10px] border border-[#ededed] bg-white p-3">
                                <div class="relative w-24 h-24 shrink-0 overflow-hidden rounded-lg bg-[#efe6d3]">
                                    @if ($event->banner)
                                        <img src="{{ asset('storage/' . $event->banner) }}" alt="{{ $event->nama }}"
                                            class="h-full w-full object-cover">
                                    @else
                                        <div
                                            class="h-full w-full flex items-center justify-center text-[#a99260] text-[10px]">
                                            No banner
                                        </div>
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h3 class="text-[14px] font-bold text-[#13223a] truncate">{{ $event->nama }}</h3>
                                    <p class="mt-1 text-[11px] text-[#777] line-clamp-2">
                                        {{ \Illuminate\Support\Str::limit($event->deskripsi ?: 'Deskripsi belum diisi.', 100) }}
                                    </p>
                                    <p class="mt-1 text-[10px] text-[#999]">
                                        {{ $event->tanggal_mulai?->format('d M Y') }}
                                        -
                                        {{ $event->tanggal_selesai?->format('d M Y') }}
                                    </p>
                                </div>

                                <span
                                    class="shrink-0 rounded-full bg-blue-100 px-3 py-1 text-[10px] font-semibold text-blue-700">
                                    Akan Datang
                                </span>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- ALUR EVENT --}}
            <section class="mt-14">
                <h2 class="display-font text-2xl font-bold text-[#111827] md:text-[26px]">Alur Event</h2>
                <p class="mt-1 text-[13px] text-[#6b6b6b] md:text-[14px]">Cara ikut serta dan mendukung peserta</p>

                <div class="mt-5 grid grid-cols-1 gap-3.5 sm:grid-cols-3">
                    @foreach ($alurEvent as $langkah)
                        <div class="card-shadow rounded-[10px] border border-[#ededed] bg-white p-6">
                            <div
                                class="flex h-9 w-9 items-center justify-center rounded-full bg-[#f4ede0] text-[14px] font-bold text-[#c99a32]">
                                {{ $langkah['nomor'] }}
                            </div>
                            <h3 class="mt-4 text-[16px] font-bold text-[#13223a]">{{ $langkah['judul'] }}</h3>
                            <p class="mt-2 text-[13px] leading-[1.6] text-[#666]">{{ $langkah['deskripsi'] }}</p>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- PERINGKAT: Prioritas event berlangsung, fallback ke event baru selesai --}}
            @php
                $tampilPeringkat = false;
                $judulPeringkat = 'Peringkat Sementara';
                $subjudulPeringkat = 'Peserta dengan suara terbanyak';
                $namaEventPeringkat = null;
                $badgePeringkat = null;
                $peringkatData = collect();
                $totalSuaraPeringkat = 0;
                $routeAllPeserta = null;

                // Prioritas 1: event berlangsung
                if ($eventBerlangsung && $peringkatTerkini->count() > 0) {
                    $tampilPeringkat = true;
                    $namaEventPeringkat = $eventBerlangsung->nama;
                    $peringkatData = $peringkatTerkini;
                    $totalSuaraPeringkat = $totalSuara;
                    $routeAllPeserta = route('public.registration', $eventBerlangsung);
                }
                // Prioritas 2: event baru selesai (≤ 3 hari)
                elseif (!$eventBerlangsung && $eventPeringkatAkhir && $peringkatAkhir->count() > 0) {
                    $tampilPeringkat = true;
                    $judulPeringkat = 'Peringkat Akhir';
                    $subjudulPeringkat = 'Hasil akhir suara peserta';
                    $namaEventPeringkat = $eventPeringkatAkhir->nama;
                    $badgePeringkat = 'Telah Berakhir';
                    $peringkatData = $peringkatAkhir;
                    $totalSuaraPeringkat = $totalSuaraAkhir;
                    $routeAllPeserta = route('public.registration', $eventPeringkatAkhir);
                }
            @endphp

            @if ($tampilPeringkat)
                <section class="mt-14">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <h2 class="display-font text-2xl font-bold text-[#111827] md:text-[26px]">
                                    {{ $judulPeringkat }}
                                </h2>
                                @if ($badgePeringkat)
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full bg-black px-2.5 py-1 text-[10px] font-semibold text-white">
                                        ● {{ $badgePeringkat }}
                                    </span>
                                @endif
                            </div>

                            {{-- Info nama event --}}
                            <p class="text-[13px] text-[#c99a32] font-semibold mb-1">
                                {{ $namaEventPeringkat }}
                            </p>

                            <p class="text-[12px] text-[#6b6b6b] md:text-[13px]">
                                {{ $subjudulPeringkat }}
                            </p>
                        </div>

                        @if ($routeAllPeserta)
                            <a href="{{ $routeAllPeserta }}"
                                class="whitespace-nowrap text-[13px] font-semibold text-[#c99a32] hover:underline">
                                Lihat semua peserta →
                            </a>
                        @endif
                    </div>

                    <div
                        class="card-shadow mt-5 divide-y divide-gray-100 rounded-[10px] border border-gray-100 bg-white">
                        @foreach ($peringkatData as $i => $p)
                            @php
                                $namaP = $p->user->nama ?? 'Peserta';
                                $kata = explode(' ', trim($namaP));
                                $inisial = strtoupper(substr($kata[0] ?? 'P', 0, 1));
                                if (count($kata) > 1) {
                                    $inisial .= strtoupper(substr(end($kata), 0, 1));
                                }
                                $persen =
                                    $totalSuaraPeringkat > 0
                                        ? round(($p->total_vote / $totalSuaraPeringkat) * 100, 1)
                                        : 0;
                            @endphp
                            <div class="flex items-center gap-4 px-5 py-4">
                                <span class="display-font w-6 text-[16px] font-bold text-[#c99a32]">
                                    {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                                </span>
                                <div
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#fbf6e9] text-[11px] font-bold text-[#c99a32]">
                                    {{ $inisial }}
                                </div>
                                <p class="w-44 truncate text-[14px] font-semibold text-[#15233d]">{{ $namaP }}
                                </p>
                                <div class="mx-2 h-1.5 flex-1 rounded-full bg-gray-100">
                                    <div class="h-1.5 rounded-full bg-[#c99a32]" style="width: {{ $persen }}%">
                                    </div>
                                </div>
                                <span class="whitespace-nowrap text-[14px] font-semibold text-[#15233d]">
                                    {{ $persen }}%
                                </span>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- PERTANYAAN UMUM --}}
            <section class="mb-6 mt-14">
                <h2 class="display-font text-2xl font-bold text-[#111827] md:text-[26px]">Pertanyaan Umum</h2>
                <p class="mt-1 text-[13px] text-[#6b6b6b] md:text-[14px]">Hal yang sering ditanyakan seputar event</p>

                <div class="mt-5 space-y-3">
                    @foreach ($faq as $item)
                        <details class="group card-shadow rounded-[10px] border border-gray-100 bg-white px-5 py-4">
                            <summary
                                class="flex cursor-pointer list-none items-center justify-between gap-3 text-[14px] font-semibold text-[#15233d]">
                                {{ $item['pertanyaan'] }}
                                <span
                                    class="shrink-0 text-[16px] text-[#c99a32] transition group-open:rotate-45">+</span>
                            </summary>
                            <p class="mt-3 text-[13px] leading-[1.6] text-[#666]">{{ $item['jawaban'] }}</p>
                        </details>
                    @endforeach
                </div>
            </section>
        @else
            {{-- EVENT SEBELUMNYA --}}
            <section class="pt-2">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <h2 class="display-font text-2xl font-bold text-[#111827] md:text-[26px]">Event Sebelumnya</h2>
                    <p class="text-[13px] text-[#9a9a9a] md:text-[14px]">Arsip event dan pemenang</p>
                </div>

                @if ($eventSebelumnya->count() > 0)
                    <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($eventSebelumnya as $item)
                            <article
                                class="card-shadow overflow-hidden rounded-[10px] border border-[#ededed] bg-white">
                                <div class="relative h-[220px] bg-[#efe6d3]">
                                    @if ($item->banner)
                                        <img src="{{ asset('storage/' . $item->banner) }}" alt="{{ $item->nama }}"
                                            class="h-full w-full object-cover">
                                    @else
                                        <div
                                            class="h-full w-full flex items-center justify-center text-[#a99260] text-sm">
                                            Belum ada banner
                                        </div>
                                    @endif

                                    <span
                                        class="absolute left-4 top-4 rounded-full bg-black px-3 py-1.5 text-[10px] font-semibold text-white">
                                        ● Telah Berakhir
                                    </span>
                                </div>

                                <div class="p-5">
                                    <h3 class="text-[17px] font-bold leading-6 text-[#13223a]">{{ $item->nama }}
                                    </h3>

                                    <div class="mt-4 space-y-2 text-[12px] text-[#777]">
                                        <div class="flex items-center gap-2">
                                            <span>▣</span>
                                            <span>{{ $item->tanggal_selesai?->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div
                        class="card-shadow mt-5 rounded-[10px] border border-dashed border-gray-200 bg-white p-10 text-center">
                        <p class="text-sm font-semibold text-[#15233d]">Belum ada arsip event</p>
                        <p class="mt-1 text-xs text-gray-400">Event yang sudah selesai akan muncul di sini.</p>
                    </div>
                @endif
            </section>

        @endif

    </main>

    {{-- FOOTER --}}
    <footer class="bg-[#101010] text-white mt-16">
        <div class="mx-auto max-w-[1200px] px-5 py-12 md:px-8">
            <div class="grid grid-cols-1 gap-10 border-b border-[#2a2a2a] pb-10 md:grid-cols-[1.5fr_1fr_1fr_1.15fr]">
                <div>
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-8 w-8 items-center justify-center rounded bg-white text-[11px] font-bold text-black">
                            A</div>
                        <div>
                            <div class="font-bold tracking-[.12em]">ALNAY.ID</div>
                            <div class="text-[8px] tracking-[.15em] text-[#c9962e]">JAS &amp; GOWN BERBUDAYA</div>
                        </div>
                    </div>
                    <p class="mt-4 max-w-[330px] text-[10px] leading-5 text-[#858585]">
                        Platform penjualan dan penyewaan fashion etnik ALNAY.ID yang menghadirkan
                        busana adat Lampung dengan tampilan modern dan elegan.
                    </p>
                </div>

                <div>
                    <h3 class="text-[10px] font-semibold tracking-[.14em]">KOLEKSI ADAT</h3>
                    <ul class="mt-4 space-y-2 text-[10px] text-[#858585]">
                        <li>Jas Berbudaya</li>
                        <li>Gown Berbudaya</li>
                        <li>Set Baju Pasangan</li>
                        <li>Aksesoris Adat</li>
                        <li>Busana Lampung</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-[10px] font-semibold tracking-[.14em]">BUTUH BANTUAN?</h3>
                    <div class="mt-4 space-y-2 text-[10px] text-[#858585]">
                        <p>Senin - Jumat: 08:00 - 20:00 WIB</p>
                        <p>Sabtu - Minggu: 09:00 - 18:00 WIB</p>
                        <p class="pt-1 leading-4">Lampung, Indonesia</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-[10px] font-semibold tracking-[.14em]">LAYANAN &amp; INFORMASI</h3>
                    <ul class="mt-4 space-y-2 text-[10px] text-[#858585]">
                        <li>Cara &amp; Syarat Sewa Busana</li>
                        <li>Aturan Deposit</li>
                        <li>Jadwal Fitting Studio</li>
                        <li>Panduan Ukur Badan Online</li>
                        <li>Pemesanan Busana</li>
                    </ul>
                </div>
            </div>

            <div
                class="flex flex-col gap-3 pt-5 text-[9px] text-[#626262] md:flex-row md:items-center md:justify-between">
                <div>© 2026 ALNAY.ID. All Rights Reserved.</div>
                <div class="flex flex-wrap gap-5">
                    <span>Kebijakan Privasi</span>
                    <span>Syarat &amp; Ketentuan</span>
                    <span>Informasi ALNAY.ID</span>
                </div>
            </div>
        </div>
    </footer>

</body>

</html>
