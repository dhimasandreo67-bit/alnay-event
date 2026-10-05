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
            }

            .display-font {
                font-family: "Playfair Display", Georgia, serif;
            }

            .brand-font {
                font-family: "Cinzel", "Playfair Display", Georgia, serif;
                letter-spacing: 0.02em;
            }

            body {
                font-family: "Inter", ui-sans-serif, system-ui, sans-serif;
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

            // Helper — route detail event sesuai status
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
        <header class="sticky top-0 z-50 border-b border-[#e9e9e9] bg-white/95 backdrop-blur">
            <div class="mx-auto flex h-[56px] max-w-[1200px] items-center justify-between px-5 md:px-8">
                <a href="{{ route('dashboard-user') }}" class="flex items-center gap-2.5">
                    <img src="{{ asset('images/alnaylogo.png') }}" alt="ALNAY.ID" class="h-9 w-9 object-contain">
                    <span class="brand-font text-[16px] font-bold tracking-tight text-[#13223a]">EVENT ALNAY.ID</span>
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
                    <h1 class="display-font font-bold text-[32px] leading-tight text-[#111827] md:text-[36px]">Event
                        &amp;
                        Vote Photo</h1>
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
                    <article class="card-shadow overflow-hidden rounded-[12px] border border-[#ededed] bg-white">

                        {{-- BANNER --}}
                        <a href="{{ $routeDetail($eventHero) }}"
                            class="block relative h-[300px] sm:h-[380px] md:h-[460px] max-h-[460px] overflow-hidden bg-[#0a1526] group">
                            @if ($eventHero->banner)
                                <img src="{{ asset('storage/' . $eventHero->banner) }}" alt="{{ $eventHero->nama }}"
                                    class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                            @else
                                <div
                                    class="h-full w-full flex items-center justify-center bg-gradient-to-br from-[#0a1526] to-[#1a2942]">
                                    <div class="text-center">
                                        <p
                                            class="display-font text-[28px] md:text-[36px] font-bold text-[#c99a32] tracking-wider">
                                            COMING SOON</p>
                                        <p class="text-[12px] text-[#8a7a52] mt-1 tracking-[.3em]">ALNAY OF THE YEAR</p>
                                    </div>
                                </div>
                            @endif

                            <div
                                class="absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-black/60 to-transparent pointer-events-none">
                            </div>

                            @if ($heroIsBerlangsung)
                                @php
                                    $statusBadge = match ($eventHero->status) {
                                        'akan_datang' => [
                                            'label' => 'Akan Datang',
                                            'bg' => 'bg-blue-500',
                                        ],
                                        'sedang_berlangsung' => [
                                            'label' => 'Sedang Berlangsung',
                                            'bg' => 'bg-emerald-500',
                                        ],
                                        'telah_berakhir' => [
                                            'label' => 'Telah Berakhir',
                                            'bg' => 'bg-orange-500',
                                        ],
                                        default => [
                                            'label' => 'Status',
                                            'bg' => 'bg-gray-500',
                                        ],
                                    };
                                @endphp

                                <span
                                    class="absolute right-4 top-4 inline-flex items-center gap-1.5 rounded-full {{ $statusBadge['bg'] }} px-3.5 py-1.5 text-[11px] font-semibold text-white shadow-md">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                                    {{ $statusBadge['label'] }}
                                </span>
                            @else
                                <span
                                    class="absolute right-4 top-4 inline-flex items-center gap-1.5 rounded-full bg-blue-500 px-3.5 py-1.5 text-[11px] font-semibold text-white shadow-md">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                                    Akan Datang
                                </span>
                            @endif
                        </a>

                        {{-- INFO BAWAH --}}
                        <div
                            class="bg-white flex flex-col gap-6 p-6 sm:p-7 md:flex-row md:items-start md:justify-between">

                            <div class="max-w-[560px] flex-1">
                                <a href="{{ $routeDetail($eventHero) }}">
                                    <h2
                                        class="display-font text-[22px] md:text-[26px] font-bold leading-tight text-[#0a1526] hover:text-[#c99a32] transition">
                                        {{ $eventHero->nama }}
                                    </h2>
                                </a>

                                <p class="mt-3 text-[13px] md:text-[14px] leading-[1.7] text-[#6b6b6b]">
                                    {{ $eventHero->deskripsi ?: 'Deskripsi belum diisi.' }}
                                </p>

                                <div class="mt-5 flex items-center gap-2 text-[12px] text-[#4B463F]">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                        stroke="#C9962E" stroke-width="1.8">
                                        <rect x="3" y="4.5" width="18" height="17" rx="2"></rect>
                                        <path d="M16 2.5v4M8 2.5v4M3 9h18"></path>
                                    </svg>
                                    <span class="font-medium">
                                        {{ $eventHero->tanggal_mulai?->format('d M Y') }} &ndash;
                                        {{ $eventHero->tanggal_selesai?->format('d M Y') }}
                                    </span>
                                </div>
                            </div>

                            <div class="w-full shrink-0 md:w-[380px]">

                                @if ($heroIsBerlangsung)
                                    <div class="grid grid-cols-3 gap-2">
                                        <div
                                            class="rounded-[10px] border border-[#ece5d7] bg-[#fcfaf6] px-3 py-4 text-center">
                                            <p class="text-[10px] uppercase tracking-wide text-[#8a8178] font-semibold">
                                                Sisa
                                                Waktu</p>
                                            <p class="mt-1.5 text-[18px] font-bold text-[#27303d]">
                                                {{ $sisaWaktu ?? 0 }}
                                                <span class="text-[11px] font-normal text-[#8a8178]">Hari</span>
                                            </p>
                                        </div>
                                        <div
                                            class="rounded-[10px] border border-[#ece5d7] bg-[#fcfaf6] px-3 py-4 text-center">
                                            <p class="text-[10px] uppercase tracking-wide text-[#8a8178] font-semibold">
                                                Peserta</p>
                                            <p class="mt-1.5 text-[18px] font-bold text-[#27303d]">{{ $jumlahPeserta }}
                                            </p>
                                        </div>
                                        <div
                                            class="rounded-[10px] border border-[#ece5d7] bg-[#fcfaf6] px-3 py-4 text-center">
                                            <p class="text-[10px] uppercase tracking-wide text-[#8a8178] font-semibold">
                                                Total Suara</p>
                                            <p class="mt-1.5 text-[18px] font-bold text-[#c87918]">
                                                {{ $formatAngka($totalSuara) }}</p>
                                        </div>
                                    </div>

                                    <a href="{{ $routeDetail($eventHero) }}"
                                        class="mt-4 flex items-center justify-center gap-2 w-full rounded-[8px] bg-[#c99a32] py-3.5 text-center text-[13px] font-bold text-white transition hover:bg-[#b88625] shadow-sm">
                                        Lihat Event &amp; Vote Peserta
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M5 12h14M12 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                @else
                                    <div
                                        class="rounded-[10px] border border-[#ece5d7] bg-[#fcfaf6] p-4 text-[12px] text-[#4B463F]">
                                        <p class="font-semibold text-[#0a1526] mb-1">Pendaftaran Dibuka</p>
                                        <p>Daftar sebelum
                                            <span
                                                class="font-bold text-[#0a1526]">{{ $eventHero->batas_pendaftaran_tanggal?->format('d M Y') ?? 'segera' }}</span>
                                        </p>
                                    </div>

                                    <a href="{{ $routeDetail($eventHero) }}"
                                        class="mt-4 flex items-center justify-center gap-2 w-full rounded-[8px] bg-[#c99a32] py-3.5 text-center text-[13px] font-bold text-white transition hover:bg-[#b88625] shadow-sm">
                                        Lihat Detail Event
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M5 12h14M12 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </article>
                @endif

                {{-- CARD EVENT AKAN DATANG --}}
                @if ($eventAkanDatangLainnya->count() > 0)
                    <section class="mt-10">
                        <h2 class="display-font text-xl font-bold text-[#111827] md:text-[22px]">Event Akan Datang</h2>

                        <div class="mt-4 space-y-4">
                            @foreach ($eventAkanDatangLainnya as $event)
                                @php
                                    $bukaPendaftaran =
                                        $event->pendaftaran_dibuka &&
                                        $event->batas_pendaftaran_tanggal &&
                                        now()->lte($event->batas_pendaftaran_tanggal) &&
                                        (!$event->pembukaan_pendaftaran_tanggal ||
                                            now()->gte($event->pembukaan_pendaftaran_tanggal));
                                @endphp
                                <div
                                    class="card-shadow rounded-[12px] border border-[#ededed] bg-white overflow-hidden">
                                    {{-- Grid: Banner Kiri + Info Kanan --}}
                                    <div class="grid grid-cols-1 md:grid-cols-[240px_1fr] gap-0">

                                        {{-- BANNER KIRI --}}
                                        <a href="{{ $routeDetail($event) }}"
                                            class="relative block h-[200px] md:h-full bg-[#efe6d3] overflow-hidden group">
                                            @if ($event->banner)
                                                <img src="{{ asset('storage/' . $event->banner) }}"
                                                    alt="{{ $event->nama }}"
                                                    class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                            @else
                                                <div
                                                    class="h-full w-full flex items-center justify-center text-[#a99260] text-sm">
                                                    No banner
                                                </div>
                                            @endif

                                            {{-- Badge Akan Datang --}}
                                            <span
                                                class="absolute top-3 left-3 rounded-full bg-blue-500 px-3 py-1.5 text-[10px] font-semibold text-white shadow-md">
                                                ● Akan Datang
                                            </span>
                                        </a>

                                        {{-- INFO KANAN --}}
                                        <div class="p-5 flex flex-col justify-between">
                                            <div>
                                                <a href="{{ $routeDetail($event) }}">
                                                    <h3
                                                        class="text-[17px] font-bold text-[#13223a] hover:text-[#c99a32] transition">
                                                        {{ $event->nama }}
                                                    </h3>
                                                </a>
                                                <p class="mt-1.5 text-[12px] text-[#777] line-clamp-2">
                                                    {{ \Illuminate\Support\Str::limit($event->deskripsi ?: 'Deskripsi belum diisi.', 120) }}
                                                </p>

                                                {{-- Detail Info --}}
                                                <div class="mt-3 space-y-1.5 text-[11px] text-[#666]">
                                                    @if ($event->batas_pendaftaran_tanggal)
                                                        <div class="flex items-center gap-2">
                                                            <svg width="12" height="12" viewBox="0 0 24 24"
                                                                fill="none" stroke="currentColor"
                                                                stroke-width="2">
                                                                <rect x="3" y="4.5" width="18" height="17"
                                                                    rx="2" />
                                                                <path d="M16 2.5v4M8 2.5v4M3 9h18" />
                                                            </svg>
                                                            <span>Daftar sampai <strong class="text-[#0a1526]">
                                                                    {{ $event->batas_pendaftaran_tanggal->format('d M Y') }}
                                                                </strong></span>
                                                        </div>
                                                    @endif

                                                    @if ($event->kuota_peserta)
                                                        <div class="flex items-center gap-2">
                                                            <svg width="12" height="12" viewBox="0 0 24 24"
                                                                fill="none" stroke="currentColor"
                                                                stroke-width="2">
                                                                <path
                                                                    d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                                                                <circle cx="9" cy="7" r="4" />
                                                            </svg>
                                                            <span>Kuota: <strong
                                                                    class="text-[#0a1526]">{{ $event->kuota_peserta }}
                                                                    peserta</strong></span>
                                                        </div>
                                                    @endif

                                                    <div class="flex items-center gap-2">
                                                        <svg width="12" height="12" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor" stroke-width="2">
                                                            <circle cx="12" cy="12" r="10" />
                                                            <path d="M12 6v6l4 2" />
                                                        </svg>
                                                        <span>Acara: <strong class="text-[#0a1526]">
                                                                {{ $event->tanggal_mulai?->format('d M Y') }}
                                                                @if ($event->tanggal_selesai)
                                                                    - {{ $event->tanggal_selesai->format('d M Y') }}
                                                                @endif
                                                            </strong></span>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- TOMBOL AKSI --}}
                                            <div class="flex flex-wrap gap-2 mt-4">
                                                @if ($bukaPendaftaran)
                                                    <a href="{{ route('public.registration', $event) }}"
                                                        class="inline-flex items-center gap-2 rounded-lg bg-[#0a1526] hover:bg-[#1a2942] px-4 py-2.5 text-[12px] font-bold text-white transition shadow-sm">
                                                        Daftar Sebagai Peserta / Finalis
                                                        <svg width="12" height="12" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor" stroke-width="2.5"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M5 12h14M12 5l7 7-7 7" />
                                                        </svg>
                                                    </a>
                                                @endif

                                                <a href="{{ $routeDetail($event) }}"
                                                    class="inline-flex items-center gap-2 rounded-lg border border-[#e5e1db] bg-white hover:bg-[#faf8f2] hover:border-[#c99a32] px-4 py-2.5 text-[12px] font-semibold text-[#4b463f] transition">
                                                    Lihat Syarat &amp; Ketentuan
                                                </a>
                                            </div>
                                        </div>

                                    </div>
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

                {{-- PERINGKAT --}}
                @php
                    $tampilPeringkat = false;
                    $judulPeringkat = 'Peringkat Sementara';
                    $subjudulPeringkat = 'Peserta dengan suara terbanyak';
                    $namaEventPeringkat = null;
                    $badgePeringkat = null;
                    $peringkatData = collect();
                    $totalSuaraPeringkat = 0;
                    $routeAllPeserta = null;
                    $eventPeringkat = null;

                    if ($eventBerlangsung && $peringkatTerkini->count() > 0) {
                        $tampilPeringkat = true;
                        $eventPeringkat = $eventBerlangsung;
                        $namaEventPeringkat = $eventBerlangsung->nama;
                        $peringkatData = $peringkatTerkini;
                        $totalSuaraPeringkat = $totalSuara;
                        $routeAllPeserta = $routeDetail($eventBerlangsung);
                    } elseif (!$eventBerlangsung && $eventPeringkatAkhir && $peringkatAkhir->count() > 0) {
                        $tampilPeringkat = true;
                        $judulPeringkat = 'Peringkat Akhir';
                        $subjudulPeringkat = 'Hasil akhir suara peserta';
                        $eventPeringkat = $eventPeringkatAkhir;
                        $namaEventPeringkat = $eventPeringkatAkhir->nama;
                        $badgePeringkat = 'Telah Berakhir';
                        $peringkatData = $peringkatAkhir;
                        $totalSuaraPeringkat = $totalSuaraAkhir;
                        $routeAllPeserta = $routeDetail($eventPeringkatAkhir);
                    }
                @endphp

                @if ($tampilPeringkat)
                    <section class="mt-14">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between mb-5">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <h2 class="display-font text-2xl font-bold text-[#111827] md:text-[26px]">
                                        {{ $judulPeringkat }} Event Terkini</h2>
                                    @if ($badgePeringkat)
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full bg-black px-2.5 py-1 text-[10px] font-semibold text-white">
                                            ● {{ $badgePeringkat }}
                                        </span>
                                    @endif
                                </div>

                                <p class="text-[13px] text-[#c99a32] font-semibold mb-1">{{ $namaEventPeringkat }}</p>
                                <p class="text-[12px] text-[#6b6b6b] md:text-[13px]">{{ $subjudulPeringkat }}</p>
                            </div>

                            @if ($routeAllPeserta)
                                <a href="{{ $routeAllPeserta }}"
                                    class="whitespace-nowrap text-[13px] font-semibold text-[#c99a32] hover:underline">
                                    Lihat semua peserta →
                                </a>
                            @endif
                        </div>

                        <div
                            class="card-shadow divide-y divide-[#f0ede6] rounded-[12px] border border-[#ededed] bg-white overflow-hidden">
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
                                            ? round(($p->total_vote / $totalSuaraPeringkat) * 100, 2)
                                            : 0;

                                    $warnaRank = match ($i) {
                                        0 => 'bg-[#fdf3d9] text-[#a37b2a]',
                                        1 => 'bg-[#eef2f7] text-[#5b6b82]',
                                        2 => 'bg-[#fdeee5] text-[#a96335]',
                                        default => 'bg-[#f4f4f2] text-[#8b8e90]',
                                    };
                                @endphp

                                <div class="flex items-center gap-4 px-5 md:px-7 py-5 hover:bg-[#fdfcf9] transition">
                                    <span class="display-font w-7 text-[16px] font-bold text-[#c99a32] shrink-0">
                                        {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                                    </span>

                                    <div
                                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full {{ $warnaRank }} text-[12px] font-bold">
                                        {{ $inisial }}
                                    </div>

                                    <p
                                        class="min-w-0 flex-1 md:w-[180px] md:flex-none truncate text-[14px] md:text-[15px] font-semibold text-[#15233d]">
                                        {{ $namaP }}
                                    </p>

                                    <div class="hidden md:block flex-1 h-2 rounded-full bg-[#f4f1e9] overflow-hidden">
                                        <div class="h-full rounded-full bg-gradient-to-r from-[#c99a32] to-[#e0b254]"
                                            style="width: {{ $persen }}%"></div>
                                    </div>

                                    <span
                                        class="shrink-0 text-[14px] md:text-[15px] font-bold text-[#15233d] w-[70px] text-right">
                                        {{ number_format($persen, 2, '.', '') }}%
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- FAQ --}}
                <section class="mb-6 mt-14">
                    <h2 class="display-font text-2xl font-bold text-[#111827] md:text-[26px]">Pertanyaan Umum</h2>
                    <p class="mt-1 text-[13px] text-[#6b6b6b] md:text-[14px]">Hal yang sering ditanyakan seputar event
                    </p>

                    <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-3.5 items-start">
                        @foreach ($faq as $index => $item)
                            <div
                                class="faq-item card-shadow rounded-[12px] border border-[#ededed] bg-white overflow-hidden">
                                <button type="button"
                                    class="faq-trigger w-full flex items-center justify-between gap-3 px-5 py-4 text-left text-[14px] font-semibold text-[#15233d] hover:bg-[#fdfcf9] transition">
                                    <span>{{ $item['pertanyaan'] }}</span>
                                    <span
                                        class="faq-icon shrink-0 flex items-center justify-center w-6 h-6 rounded-full bg-[#fdf8ec] text-[#c99a32] text-[16px] font-bold transition-all duration-200">+</span>
                                </button>
                                <div
                                    class="faq-content hidden px-5 pb-4 text-[13px] leading-[1.6] text-[#666] border-t border-[#f4f1e9] pt-3">
                                    {{ $item['jawaban'] }}
                                </div>
                            </div>
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
                                <a href="{{ $routeDetail($item) }}"
                                    class="card-shadow overflow-hidden rounded-[10px] border border-[#ededed] bg-white transition hover:border-[#c99a32] hover:shadow-md block">
                                    <div class="relative h-[220px] bg-[#efe6d3]">
                                        @if ($item->banner)
                                            <img src="{{ asset('storage/' . $item->banner) }}"
                                                alt="{{ $item->nama }}" class="h-full w-full object-cover">
                                        @else
                                            <div
                                                class="h-full w-full flex items-center justify-center text-[#a99260] text-sm">
                                                Belum ada banner</div>
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
                                </a>
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
        @include('partials.footer')

        <script>
            // FAQ Accordion
            document.querySelectorAll('.faq-trigger').forEach(btn => {
                btn.addEventListener('click', () => {
                    const item = btn.closest('.faq-item');
                    const content = item.querySelector('.faq-content');
                    const icon = item.querySelector('.faq-icon');
                    const isOpen = !content.classList.contains('hidden');

                    document.querySelectorAll('.faq-content').forEach(c => c.classList.add('hidden'));
                    document.querySelectorAll('.faq-icon').forEach(i => {
                        i.classList.remove('rotate-45', 'bg-[#c99a32]', 'text-white');
                        i.classList.add('bg-[#fdf8ec]', 'text-[#c99a32]');
                        i.textContent = '+';
                    });

                    if (!isOpen) {
                        content.classList.remove('hidden');
                        icon.classList.add('rotate-45', 'bg-[#c99a32]', 'text-white');
                        icon.classList.remove('bg-[#fdf8ec]', 'text-[#c99a32]');
                        icon.textContent = '×';
                    }
                });
            });
        </script>

    </body>

    </html>
