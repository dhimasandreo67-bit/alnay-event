<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $event->nama }} - Akan Datang | ALNAY.ID</title>

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
                <img src="{{ asset('storage/' . $event->banner) }}" alt="{{ $event->nama }}"
                    class="w-full h-[220px] sm:h-[300px] md:h-[380px] object-cover object-center">
            @else
                <div
                    class="w-full h-[220px] sm:h-[300px] md:h-[380px] bg-gradient-to-br from-[#0a1526] to-[#1a2942] flex items-center justify-center">
                    <p class="font-display text-3xl font-bold text-[#c99a32] tracking-wider">{{ $event->nama }}</p>
                </div>
            @endif
        </section>

        {{-- EVENT INFORMATION --}}
        <section class="relative -mt-5 mx-4 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-4 md:p-5"> 
                <div class="grid grid-cols-1 lg:grid-cols-[1fr_200px] gap-5">

                    <div>
                        <!-- Status -->
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-[#c99a32] text-white text-[10px] font-semibold uppercase tracking-wider">
                            Event Belum Dimulai
                        </span>

                        <h1 class="font-display text-xl md:text-2xl font-bold text-[#111827] mt-2.5">
                            {{ $event->nama }}
                        </h1>

                        <p class="text-gray-500 text-xs md:text-sm leading-relaxed mt-2.5 max-w-3xl">
                            {{ $event->deskripsi ?: 'Deskripsi belum diisi.' }}
                        </p>

                        <div class="flex flex-wrap gap-2 mt-3.5">
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-full bg-[#faf8f2] border border-[#eee5d2] text-[10px] text-[#4B463F]">
                                Voting mulai {{ $event->tanggal_mulai?->translatedFormat('d M Y') ?? '-' }}
                            </span>
                            @if ($event->lokasi)
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full bg-[#faf8f2] border border-[#eee5d2] text-[10px] text-[#4B463F]">
                                    {{ $event->lokasi }}
                                </span>
                            @endif
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-full bg-[#faf8f2] border border-[#eee5d2] text-[10px] text-[#4B463F]">
                                Rp {{ number_format($event->harga_per_vote ?? 0, 0, ',', '.') }} / vote
                            </span>
                        </div>
                    </div>

                    <div class="bg-[#faf8f2] border border-[#eee5d2] rounded-lg p-3.5 self-start mt-9">
                        <p class="text-[10px] uppercase tracking-wider text-[#8a8178] font-semibold text-center">
                            @if (!$event->pendaftaran_dibuka)
                                Pendaftaran Ditutup
                            @elseif ($sisaHari !== null)
                                Pendaftaran Ditutup Dalam
                            @else
                                Status Pendaftaran
                            @endif
                        </p>

                        @if (!$event->pendaftaran_dibuka)
                            {{-- Pendaftaran ditutup manual — gak ada countdown --}}
                            <p class="text-center text-sm font-semibold text-rose-500 mt-3">
                                Pendaftaran Ditutup
                            </p>
                        @elseif ($sisaHari !== null)
                            <div class="grid grid-cols-3 gap-1 mt-3 text-center">
                                <div>
                                    <p class="text-lg font-bold text-[#c99a32]">{{ sprintf('%02d', $sisaHari) }}</p>
                                    <p class="text-[9px] text-gray-500">HARI</p>
                                </div>
                                <div>
                                    <p class="text-lg font-bold text-[#c99a32]">{{ sprintf('%02d', $sisaJam) }}</p>
                                    <p class="text-[9px] text-gray-500">JAM</p>
                                </div>
                                <div>
                                    <p class="text-lg font-bold text-[#c99a32]">{{ sprintf('%02d', $sisaMenit) }}</p>
                                    <p class="text-[9px] text-gray-500">MENIT</p>
                                </div>
                            </div>
                        @else
                            <p class="text-center text-sm font-semibold text-rose-500 mt-3">{{ $statusPendaftaran }}
                            </p>
                        @endif

                        <div class="grid grid-cols-2 gap-3 mt-4 pt-3 border-t border-[#eee5d2] text-center">
                            <div>
                                <p class="text-sm font-bold">Rp
                                    {{ number_format($event->biaya_pendaftaran ?? 0, 0, ',', '.') }}</p>
                                <p class="text-[9px] text-gray-500 mt-0.5">Biaya Daftar</p>
                            </div>
                            <div>
                                <p class="text-sm font-bold">{{ $event->kuota_peserta ?? '-' }} orang</p>
                                <p class="text-[9px] text-gray-500 mt-0.5">Kuota Peserta</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        {{-- SYARAT & KETENTUAN --}}
        <section class="mt-8">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-2 mb-4">
                <div>
                    <h2 class="font-display text-lg md:text-xl font-bold text-[#15233d]">Syarat &amp; Ketentuan</h2>
                    <p class="text-gray-500 text-xs md:text-sm mt-1">Daftar kandidat akan tampil setelah voting dimulai.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-5">

                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                    <h3 class="font-display text-base font-bold text-[#15233d]">Syarat &amp; Ketentuan Event</h3>
                    <ul class="mt-4 space-y-2.5 text-sm text-[#4B463F]">
                        @forelse ($syaratList as $syarat)
                            <li class="flex items-start gap-2.5">
                                <span class="shrink-0 w-1.5 h-1.5 rounded-full bg-[#c99a32] mt-2"></span>
                                <span>{{ $syarat }}</span>
                            </li>
                        @empty
                            <li class="text-gray-400 text-xs">Syarat &amp; ketentuan belum diisi admin.</li>
                        @endforelse
                    </ul>
                </div>

                <aside class="space-y-4">
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                        <h3 class="font-display text-base font-bold text-[#15233d]">Pendaftaran Kandidat</h3>

                        <div class="mt-3 space-y-2 text-xs">
                            <div class="flex justify-between gap-3">
                                <span class="text-gray-500">Batas pendaftaran</span>
                                <span class="font-semibold text-[#15233d] text-right">
                                    {{ $batasDaftar?->translatedFormat('l, d F Y') ?? '-' }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Pukul</span>
                                <span class="font-semibold text-[#15233d]">
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
                            // Cek flag admin + waktu
                            $bukaPendaftaran =
                                $event->pendaftaran_dibuka && $statusPendaftaran === 'Pendaftaran Dibuka';
                        @endphp

                        @if ($bukaPendaftaran)
                            <a href="{{ route('public.registration', $event) }}"
                                class="mt-4 flex items-center justify-center gap-2 w-full rounded-lg bg-[#0a1526] py-3 text-white text-xs font-semibold transition hover:bg-[#1a2942]">
                                Daftar Sebagai Kandidat →
                            </a>
                            <p class="mt-2 text-[10px] text-gray-400 text-center">
                                Biaya pendaftaran Rp {{ number_format($event->biaya_pendaftaran ?? 0, 0, ',', '.') }}.
                            </p>
                        @endif
                    </div>

                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                        <h3 class="font-display text-base font-bold text-[#15233d]">Jadwal Event</h3>

                        <div class="mt-3 space-y-3 text-xs">
                            @foreach ($jadwal as $tahap)
                                <div class="flex items-start gap-3">
                                    <span class="shrink-0 w-2 h-2 rounded-full bg-[#c99a32] mt-1"></span>
                                    <div>
                                        <p class="font-semibold text-[#15233d]">{{ $tahap['nama'] }}</p>
                                        <p class="text-gray-500 mt-0.5">{{ $tahap['tanggal'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </aside>

            </div>
        </section>

        {{-- ALUR PENDAFTARAN --}}
        <section class="mt-8">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-2 mb-5">
                <div>
                    <h2 class="font-display text-lg md:text-xl font-bold text-[#15233d]">Alur Pendaftaran</h2>
                    <p class="text-gray-500 text-xs md:text-sm mt-1">Cara ikut serta sebagai kandidat</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
                @php
                    $alurPendaftaran = [
                        ['1', 'Masuk dengan Google', 'Login pakai akun Google — cuma butuh 1 detik.'],
                        ['2', 'Isi Formulir', 'Lengkapi data diri & unggah foto terbaikmu.'],
                        ['3', 'Verifikasi Admin', 'Admin akan memverifikasi pendaftaranmu.'],
                        ['4', 'Tampil di Event', 'Kandidat tampil & siap menerima vote publik.'],
                    ];
                @endphp

                @foreach ($alurPendaftaran as [$nomor, $judul, $deskripsi])
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-full bg-[#f4ede0] text-[14px] font-bold text-[#c99a32]">
                            {{ $nomor }}
                        </div>
                        <h3 class="mt-4 text-[16px] font-bold text-[#15233d]">{{ $judul }}</h3>
                        <p class="mt-2 text-[13px] leading-[1.6] text-gray-500">{{ $deskripsi }}</p>
                    </div>
                @endforeach
            </div>
        </section>

    </main>

    @include('partials.footer')

</body>

</html>