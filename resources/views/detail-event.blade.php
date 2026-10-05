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
    </style>
</head>

<body class="bg-[#faf9f6] text-[#15233d]">

    @php
        // =====================================================
        // DATA DARI CONTROLLER
        // $event, $pesertas, $totalSuara, $jumlahPeserta, $sisaWaktu
        // =====================================================

        // Helper format angka — 15400 → 15.4K
        $formatAngka = function ($angka) {
            if ($angka >= 1000000) {
                return round($angka / 1000000, 1) . 'M';
            }
            if ($angka >= 1000) {
                return round($angka / 1000, 1) . 'K';
            }
            return number_format($angka, 0, ',', '.');
        };

        // Helper format rupiah
        $rp = fn($n) => $n ? 'Rp ' . number_format($n, 0, ',', '.') : 'Gratis';

        // Helper inisial
        $inisial = fn($nama) => strtoupper(
            implode(
                '',
                array_map(fn($kata) => mb_substr($kata, 0, 1), array_slice(preg_split('/\s+/', trim($nama)), 0, 2)),
            ),
        );

        // Pecah ketentuan voting
        $pecahBaris = fn($teks) => array_values(array_filter(array_map('trim', preg_split('/\R/', (string) $teks))));
        $ketentuanVoting = $pecahBaris($event->syarat_ketentuan ?? '');

        // =====================================================
        // LOGIKA TURUNAN
        // =====================================================

        $tz = 'Asia/Jakarta';
        $now = \Carbon\Carbon::now($tz);

        $mulai = $event->tanggal_mulai ? \Carbon\Carbon::parse($event->tanggal_mulai, $tz)->locale('id') : null;
        $selesai = $event->tanggal_selesai ? \Carbon\Carbon::parse($event->tanggal_selesai, $tz)->locale('id') : null;

        // Rentang tanggal
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

        // ---- Jadwal Event (timeline) ----
        $bukaDaftar = $event->pembukaan_pendaftaran_tanggal
            ? \Carbon\Carbon::parse($event->pembukaan_pendaftaran_tanggal, $tz)->locale('id')
            : null;
        $batasDaftar = $event->batas_pendaftaran_tanggal
            ? \Carbon\Carbon::parse($event->batas_pendaftaran_tanggal, $tz)->locale('id')
            : null;

        $jadwal = [];

        if ($batasDaftar) {
            // Pendaftaran Kandidat — pakai bukaDaftar kalau ada, kalau gak ada pakai "Sampai [batas]"
            $jadwal[] = [
                'nama' => 'Pendaftaran Kandidat',
                'tanggal' => $bukaDaftar
                    ? $rentang($bukaDaftar, $batasDaftar)
                    : 'Sampai ' . $batasDaftar->translatedFormat('j F Y'),
            ];

            // Verifikasi Data — cuma kalau ada tanggal mulai
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
            // Kalau gak ada batas daftar — langsung voting
            $jadwal[] = [
                'nama' => 'Pendaftaran Kandidat',
                'tanggal' => 'Sudah ditutup',
            ];
        }

        $jadwal[] = ['nama' => 'Voting Publik', 'tanggal' => $periode];
        $jadwal[] = ['nama' => 'Pengumuman Pemenang', 'tanggal' => 'Setelah voting berakhir'];

        // Hitung mundur
        $cdLabel = $selesai ? 'Sisa Waktu Voting' : 'Voting Sedang Berjalan';
        $detik = $selesai ? max(0, $selesai->timestamp - $now->timestamp) : 0;
        $cdHari = intdiv($detik, 86400);
        $cdJam = intdiv($detik % 86400, 3600);
        $cdMenit = intdiv($detik % 3600, 60);

        // Ubah $pesertas jadi array kandidat
        $kandidat = $pesertas
            ->map(function ($p, $i) use ($totalSuara) {
                $nama = $p->namaTampil();
                return [
                    'id' => $p->id,
                    'nomor' => str_pad($i + 1, 2, '0', STR_PAD_LEFT),
                    'nama' => $nama,
                    'asal' => '-',
                    'foto' => $p->user?->foto_custom ? asset('storage/' . $p->user->foto_custom) : $p->user?->foto_url,
                    'inisial' => strtoupper(
                        implode(
                            '',
                            array_map(
                                fn($kata) => mb_substr($kata, 0, 1),
                                array_slice(preg_split('/\s+/', trim($nama)), 0, 2),
                            ),
                        ),
                    ),
                    'vote' => number_format($p->total_vote, 0, ',', '.'),
                ];
            })
            ->toArray();
    @endphp

    {{-- HEADER --}}
    <header class="sticky top-0 z-50 border-b border-[#e9e9e9] bg-white/95 backdrop-blur">
        <div class="mx-auto flex h-[56px] max-w-[1200px] items-center justify-between px-5 md:px-8">
            <a href="{{ route('dashboard-user') }}"
                class="font-display text-[16px] font-bold tracking-tight text-[#13223a]">
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

    <main class="max-w-5xl mx-auto px-5 pt-0 pb-6">

        {{-- EVENT BANNER --}}
        <section class="relative overflow-hidden rounded-xl mx-0 md:-mx-10 mt-4">
            @if ($event->banner)
                <img src="{{ asset('storage/' . $event->banner) }}" alt="{{ $event->nama }}"
                    class="w-full h-[220px] sm:h-[300px] md:h-[380px] object-cover object-center">
            @else
                <div
                    class="w-full h-[220px] sm:h-[300px] md:h-[380px] bg-gradient-to-br from-[#0a1526] to-[#1a2942] flex items-center justify-center">
                    <p class="font-display text-3xl font-bold text-[#c99a32] tracking-wider">{{ $event->nama }}</p>
                </div>
            @endif
        </section>

        @if (!$event->voting_aktif)
            <div class="mx-4 mt-4 rounded-xl border-2 border-amber-300 bg-amber-50 p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v2m0 4h.01M5 19h14a2 2 0 001.84-2.75L13.74 4a2 2 0 00-3.5 0L3.16 16.25A2 2 0 005 19z" />
                </svg>
                <div>
                    <p class="text-[13px] font-bold text-amber-800 m-0">Voting Ditutup</p>
                    <p class="text-[12px] text-amber-700 mt-0.5 m-0">
                        Admin telah menutup voting untuk event ini. Kamu masih bisa melihat hasil & daftar kandidat.
                    </p>
                </div>
            </div>
        @endif

        {{-- EVENT INFORMATION --}}
        <section class="relative -mt-5 mx-4 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-4 md:p-5">
                <div class="grid grid-cols-1 lg:grid-cols-[1fr_220px] gap-5">

                    <div>
                        @if (!$event->voting_aktif)
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-gray-500 text-white text-[10px] font-semibold uppercase tracking-wider">
                                <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                                Voting Ditutup
                            </span>
                        @endif

                        <h1 class="font-display text-xl md:text-2xl font-bold text-[#111827] mt-2.5">
                            {{ $event->nama }}
                        </h1>

                        <p class="text-gray-500 text-xs md:text-sm leading-relaxed mt-2.5 max-w-3xl">
                            {{ $event->deskripsi ?: 'Deskripsi belum diisi.' }}
                        </p>

                        <div class="flex flex-wrap gap-2 mt-3.5">
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-full bg-[#faf8f2] border border-gray-100 text-[11px] text-gray-600">
                                Voting {{ $periode }}
                            </span>
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-full bg-[#faf8f2] border border-gray-100 text-[11px] text-gray-600">
                                {{ $rp($event->harga_per_vote) }} / vote
                            </span>
                            @if ($event->lokasi)
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full bg-[#faf8f2] border border-gray-100 text-[11px] text-gray-600">
                                    {{ $event->lokasi }}
                                </span>
                            @endif
                        </div>

                        <button type="button" data-buka-syarat
                            class="inline-block mt-3 text-xs font-medium text-[#a37b2a] hover:text-[#c99a32] transition">
                            Lihat Syarat &amp; Ketentuan ›
                        </button>
                    </div>

                    {{-- COUNTDOWN --}}
                    <div class="bg-[#faf8f2] border border-[#eee5d2] rounded-lg p-3.5 sm:p-4 lg:p-3.5 self-start">
                        <div class="sm:grid sm:grid-cols-2 sm:items-center sm:gap-6 lg:block">

                            <div>
                                <p class="text-center text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                                    {{ $cdLabel }}
                                </p>

                                @if ($selesai)
                                    <div class="flex items-start justify-center gap-2 mt-2">
                                        <div class="min-w-[36px] text-center">
                                            <span class="block text-xl font-bold leading-none text-[#c99a32]">
                                                {{ sprintf('%02d', $cdHari) }}
                                            </span>
                                            <span
                                                class="mt-1.5 block text-[8px] font-semibold uppercase text-gray-500">Hari</span>
                                        </div>
                                        <span class="text-xl leading-none text-[#c99a32]">:</span>
                                        <div class="min-w-[36px] text-center">
                                            <span class="block text-xl font-bold leading-none text-[#c99a32]">
                                                {{ sprintf('%02d', $cdJam) }}
                                            </span>
                                            <span
                                                class="mt-1.5 block text-[8px] font-semibold uppercase text-gray-500">Jam</span>
                                        </div>
                                        <span class="text-xl leading-none text-[#c99a32]">:</span>
                                        <div class="min-w-[36px] text-center">
                                            <span class="block text-xl font-bold leading-none text-[#c99a32]">
                                                {{ sprintf('%02d', $cdMenit) }}
                                            </span>
                                            <span
                                                class="mt-1.5 block text-[8px] font-semibold uppercase text-gray-500">Menit</span>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div
                                class="grid grid-cols-2 gap-3.5 border-t border-gray-200 mt-3.5 pt-3.5 text-center
                                        sm:grid-cols-1 sm:gap-3 sm:border-t-0 sm:border-l sm:mt-0 sm:pt-0 sm:pl-6 sm:text-left
                                        lg:grid-cols-2 lg:gap-3.5 lg:border-l-0 lg:border-t lg:mt-3.5 lg:pt-3.5 lg:pl-0">
                                <div>
                                    <p class="text-lg font-bold">{{ $jumlahPeserta }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Kandidat</p>
                                </div>
                                <div>
                                    <p class="text-lg font-bold">{{ $formatAngka($totalSuara) }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Total Vote</p>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </section>

        {{-- CANDIDATE SECTION --}}
        <section class="mt-7">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                <h2 class="font-display text-lg md:text-xl font-bold text-[#15233d]">Daftar Kandidat</h2>

                <div class="flex gap-2.5">
                    <div class="relative">
                        <input type="text" id="searchKandidat" placeholder="Cari kandidat..."
                            class="w-full lg:w-60 h-10 pl-9 pr-4 rounded-md border border-gray-300 bg-white text-sm focus:outline-none focus:ring-1 focus:ring-[#c99a32]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-3 top-3 text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="m21 21-4.35-4.35m1.35-5.4a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            @if (count($kandidat) > 0)
                <div id="gridKandidat" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 mt-3.5">
                    @foreach ($kandidat as $item)
                        <div class="kandidat-card bg-white rounded-lg overflow-hidden border border-gray-100 shadow-sm hover:shadow-md transition"
                            data-nama="{{ strtolower($item['nama']) }}">
                            <div class="relative h-[170px] overflow-hidden bg-[#fbf6e9]">
                                @if ($item['foto'])
                                    <img src="{{ $item['foto'] }}" alt="{{ $item['nama'] }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span
                                            class="font-display text-4xl font-bold text-[#c99a32]">{{ $item['inisial'] }}</span>
                                    </div>
                                @endif
                                <span
                                    class="absolute top-2.5 left-2.5 bg-white px-2.5 py-1.5 rounded-md text-xs font-semibold text-[#15233d] shadow-sm">
                                    {{ $item['nomor'] }}
                                </span>
                            </div>

                            <div class="p-3">
                                <div class="flex items-start justify-between gap-2">
                                    <h3 class="font-semibold text-sm text-[#15233d]">{{ $item['nama'] }}</h3>
                                    <span
                                        class="text-[10px] font-semibold text-[#c99a32] bg-[#fbf6e9] px-2 py-1 rounded whitespace-nowrap">
                                        {{ $item['asal'] }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-1.5 mt-2.5 text-xs text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 0115 0" />
                                    </svg>
                                    <span>{{ $item['vote'] }} suara</span>
                                </div>

                                @php
    // Link ke halaman DETAIL KANDIDAT — bukan langsung checkout
    $voteUrl = '/peserta/' . $item['id'];
@endphp

                                <a href="/peserta/{{ $item['id'] }}"
                                    class="block text-center w-full mt-2.5 py-2 rounded-md bg-[#c69220] hover:bg-[#b58219] text-white text-sm font-medium transition">
                                    Detail
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div id="emptySearch"
                    class="hidden mt-3.5 rounded-lg border border-dashed border-gray-200 bg-white p-10 text-center">
                    <p class="text-sm font-semibold text-[#15233d]">Kandidat tidak ditemukan</p>
                    <p class="mt-1 text-xs text-gray-400">Coba kata kunci lain.</p>
                </div>
            @else
                <div class="mt-3.5 rounded-lg border border-dashed border-gray-200 bg-white p-10 text-center">
                    <p class="text-sm font-semibold text-[#15233d]">Belum ada kandidat pada event ini</p>
                    <p class="mt-1 text-xs text-gray-400">Kandidat akan muncul di sini begitu admin menambahkannya.
                    </p>
                </div>
            @endif
        </section>

    </main>

    {{-- FOOTER --}}
    @include('partials.footer')

    {{-- POPUP SYARAT & KETENTUAN VOTING --}}
    <div id="modalSyarat"
        class="hidden fixed inset-0 z-[60] items-end sm:items-center justify-center bg-black/50 sm:px-5">
        <div
            class="flex max-h-[88vh] w-full max-w-xl flex-col overflow-hidden rounded-t-2xl sm:rounded-2xl bg-white shadow-2xl">

            <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
                <h2 class="font-display text-lg font-bold text-[#15233d]">Syarat &amp; Ketentuan</h2>
                <button type="button" data-tutup-syarat aria-label="Tutup"
                    class="flex h-8 w-8 items-center justify-center rounded-full text-xl text-gray-400 hover:bg-gray-100 transition">
                    &times;
                </button>
            </div>

            <div class="overflow-y-auto px-5 py-5 space-y-6">
                <div>
                    <h3 class="text-sm font-semibold text-[#15233d]">Cara Voting</h3>
                    <ol class="mt-3 grid gap-2.5 text-sm text-[#15233d]">
                        <li class="flex items-center gap-3 rounded-lg bg-[#faf8f2] p-3">
                            <span class="font-display text-lg font-bold text-[#c99a32]">1</span>
                            Pilih kandidat favorit Anda.
                        </li>
                        <li class="flex items-center gap-3 rounded-lg bg-[#faf8f2] p-3">
                            <span class="font-display text-lg font-bold text-[#c99a32]">2</span>
                            Tekan tombol Vote ({{ $rp($event->harga_per_vote) }} per vote) dan selesaikan pembayaran.
                        </li>
                        <li class="flex items-center gap-3 rounded-lg bg-[#faf8f2] p-3">
                            <span class="font-display text-lg font-bold text-[#c99a32]">3</span>
                            Suara tercatat dan langsung masuk ke perolehan kandidat.
                        </li>
                    </ol>
                </div>

                @if (count($ketentuanVoting) > 0)
                    <div>
                        <h3 class="text-sm font-semibold text-[#15233d]">Syarat &amp; Ketentuan Event</h3>
                        <ul class="mt-3 space-y-2 text-sm text-gray-600 list-disc pl-5 marker:text-[#c99a32]">
                            @foreach ($ketentuanVoting as $ketentuan)
                                <li>{{ $ketentuan }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Jadwal Event -->
                <div>
                    <h3 class="text-sm font-semibold text-[#15233d]">Jadwal Event</h3>
                    <ol class="relative mt-3 space-y-4 border-l border-[#e8dcc0] pl-5">
                        @foreach ($jadwal as $tahap)
                            <li class="relative">
                                <span class="absolute -left-[26px] top-1 h-2.5 w-2.5 rounded-full bg-[#c99a32]"></span>
                                <p class="text-sm font-medium text-[#15233d]">{{ $tahap['nama'] }}</p>
                                <p class="text-xs text-gray-500">{{ $tahap['tanggal'] }}</p>
                            </li>
                        @endforeach
                    </ol>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-[#15233d]">Informasi Event</h3>
                    <div class="mt-1 divide-y divide-gray-100">
                        <div class="flex justify-between gap-4 py-2.5 text-sm">
                            <span class="text-gray-500">Periode Voting</span>
                            <span class="text-right font-medium text-[#15233d]">{{ $periode }}</span>
                        </div>
                        @if ($event->lokasi)
                            <div class="flex justify-between gap-4 py-2.5 text-sm">
                                <span class="text-gray-500">Tempat</span>
                                <span class="text-right font-medium text-[#15233d]">{{ $event->lokasi }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between gap-4 py-2.5 text-sm">
                            <span class="text-gray-500">Harga / Vote</span>
                            <span class="text-right font-medium text-[#15233d]">{{ $rp($event->harga_per_vote) }} per
                                vote</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-100 px-5 py-3.5 text-right">
                <button type="button" data-tutup-syarat
                    class="rounded-md bg-[#15233d] px-5 py-2.5 text-xs font-semibold text-white transition hover:bg-[#1f3357]">
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
                if (empty) empty.classList.toggle('hidden', visible > 0);
            });
        })();
    </script>

</body>

</html>
