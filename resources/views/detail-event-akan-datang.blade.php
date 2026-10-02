<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Syarat & Ketentuan Event - ALNAY.ID</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

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

    <!-- =========================================================
         DATA EVENT (AKAN DATANG)
    ========================================================== -->

    @php

        // =====================================================
        // OPSI 1 — DATA MANUAL / STATIS (AKTIF)
        // Dipakai selama tahap pengembangan UI/UX, belum ada DB.
        //
        // Cara mencoba 3 kondisi pendaftaran (ubah tanggalnya):
        // - Belum dibuka  : 'buka_daftar'  di masa depan
        // - Sedang dibuka : 'buka_daftar'  sudah lewat, 'batas_daftar' belum
        // - Sudah ditutup : 'batas_daftar' sudah lewat
        // 'selesai' boleh diisi null (admin tidak wajib mengisinya).
        // =====================================================

        $event = [
            'id'               => 2,
            'judul'            => 'Pemilihan Duta Budaya 2026',
            'status'           => 'Event Belum Dimulai',
            'deskripsi'        => 'Dukung kandidat favorit Anda untuk menjadi wajah representasi kekayaan budaya Nusantara. Setiap suara menentukan siapa yang akan membawa narasi warisan kita ke kancah global.',
            'foto'             => 'event-alnay1.png',
            'lokasi'           => 'Gedung Serbaguna, Bandar Lampung',
            'biaya_daftar'     => 50000,
            'harga_vote'       => 5000,
            'kuota'            => 100,
            'buka_daftar'      => '2026-09-28 08:00:00',
            'batas_daftar'     => '2026-10-20 23:59:00',
            'mulai'            => '2026-10-25 09:00:00',
            'selesai'          => '2026-11-10 21:00:00',
            'syarat_ketentuan' => [
                'Warga Negara Indonesia berusia 17–28 tahun.',
                'Sehat jasmani dan rohani, serta berperilaku baik.',
                'Memiliki akun Google aktif untuk masuk ke ALNAY.ID.',
                'Bersedia mengikuti seluruh rangkaian hingga malam final.',
                'Voting hanya dapat dilakukan setelah masuk dengan akun Google.',
                'Suara yang sudah diberikan tidak dapat dibatalkan atau dipindahkan.',
                'Kandidat tidak diperbolehkan memberikan suara untuk dirinya sendiri.',
                'Kecurangan seperti akun palsu atau bot berujung diskualifikasi.',
                'Keputusan panitia bersifat final.',
            ],
        ];

        /*
        // =====================================================
        // OPSI 2 — DATA DINAMIS DARI DATABASE (NONAKTIF)
        // Aktifkan blok ini (dan comment Opsi 1 di atas) setelah
        // database & halaman admin sudah tersedia.
        // Data tetap dirapikan ke bentuk array yang sama seperti
        // Opsi 1, supaya bagian HTML di bawah tidak perlu diubah.
        //
        // Catatan: syarat_ketentuan disimpan sebagai satu kolom teks
        // biasa di database (satu poin per baris, dipisah Enter),
        // lalu dipecah menjadi array di sini. Form admin cukup satu
        // kolom "Syarat & Ketentuan" untuk ini.
        //
        // $event bisa bernilai null kalau ID tidak ditemukan di
        // database (dicek lewat @if($event) di bagian HTML).
        // =====================================================

        $event_id = request()->route('id'); // dari route('detail-event-akan-datang', $id)

        $q_event = $pdo->prepare("SELECT * FROM events WHERE id = ?");
        $q_event->execute([$event_id]);
        $row_event = $q_event->fetch(PDO::FETCH_ASSOC);

        $pecah_baris = fn ($teks) => array_values(array_filter(array_map('trim', preg_split('/\R/', (string) $teks))));

        $event = $row_event ? [
            'id'               => $row_event['id'],
            'judul'            => $row_event['judul'],
            'status'           => 'Event Belum Dimulai',
            'deskripsi'        => $row_event['deskripsi'],
            'foto'             => $row_event['foto'],
            'lokasi'           => $row_event['lokasi'],
            'biaya_daftar'     => $row_event['biaya_daftar'],
            'harga_vote'       => $row_event['harga_vote'],
            'kuota'            => $row_event['kuota'],
            'buka_daftar'      => $row_event['buka_daftar'],
            'batas_daftar'     => $row_event['batas_daftar'],
            'mulai'            => $row_event['tanggal_mulai'],
            'selesai'          => $row_event['tanggal_selesai'],
            'syarat_ketentuan' => $pecah_baris($row_event['syarat_ketentuan']),
        ] : null;
        */

        // Path foto:
        // - Opsi 1 (statis)  : public/image/event/{foto}
        // - Opsi 2 (database, upload admin via storage) sebaiknya pakai:
        //   asset('storage/event/' . $event['foto'])
        //   dengan syarat sudah menjalankan `php artisan storage:link`.


        // =====================================================
        // LOGIKA TURUNAN (dipakai Opsi 1 maupun Opsi 2)
        // Semua dihitung dari tanggal yang diisi admin, jadi
        // admin tidak perlu mengisi tahap verifikasi secara manual.
        // =====================================================

        if ($event) {

            $tz  = 'Asia/Jakarta';
            $now = \Carbon\Carbon::now($tz);

            $tgl = fn ($v) => $v ? \Carbon\Carbon::parse($v, $tz)->locale('id') : null;

            $buka    = $tgl($event['buka_daftar']);
            $batas   = $tgl($event['batas_daftar']);
            $mulai   = $tgl($event['mulai']);
            $selesai = $tgl($event['selesai']); // bisa null

            $rp = fn ($n) => $n ? 'Rp ' . number_format($n, 0, ',', '.') : 'Gratis';

            // Rentang tanggal: "1 – 20 Oktober 2026" (satu bulan)
            //                  "27 Okt – 10 Nov 2026" (lintas bulan)
            //                  "12 Oktober 2026"      (satu tanggal)
            $rentang = function ($a, $b = null) {
                if (!$b) {
                    return $a->translatedFormat('j F Y');
                }
                if ($a->isSameMonth($b)) {
                    return $a->format('j') . ' – ' . $b->translatedFormat('j F Y');
                }
                return $a->translatedFormat('j M') . ' – ' . $b->translatedFormat('j M Y');
            };

            // ---- Status pendaftaran (belum / buka / tutup) ----
            if ($now->lt($buka)) {
                $daftar = 'belum';
            } elseif ($now->lte($batas)) {
                $daftar = 'buka';
            } else {
                $daftar = 'tutup';
            }

            // ---- Hitung mundur mengikuti status pendaftaran ----
            [$cdLabel, $cdTarget] = match ($daftar) {
                'belum' => ['Pendaftaran Dibuka Dalam',  $buka],
                'buka'  => ['Pendaftaran Ditutup Dalam', $batas],
                default => ['Voting Dimulai Dalam',      $mulai],
            };

            $detik   = max(0, $cdTarget->timestamp - $now->timestamp);
            $cdHari  = intdiv($detik, 86400);
            $cdJam   = intdiv($detik % 86400, 3600);
            $cdMenit = intdiv($detik % 3600, 60);

            // ---- Kartu pendaftaran (tanggal terpenting + tombol) ----
            [$infoLabel, $infoTanggal] = match ($daftar) {
                'belum' => ['Pendaftaran dibuka',  $buka],
                'buka'  => ['Batas pendaftaran',   $batas],
                default => ['Pendaftaran ditutup', $batas],
            };

            $ctaAktif = $daftar === 'buka';
            $ctaTeks  = match ($daftar) {
                'buka'  => 'Daftar Sebagai Kandidat →',
                'belum' => 'Pendaftaran Belum Dibuka',
                default => 'Pendaftaran Sudah Ditutup',
            };
            $ctaCatatan = match ($daftar) {
                'buka'  => $event['biaya_daftar'] ? 'Biaya pendaftaran ' . $rp($event['biaya_daftar']) . '.' : 'Pendaftaran gratis.',
                'belum' => 'Dibuka ' . $buka->translatedFormat('j M Y') . '.',
                default => 'Nantikan event berikutnya.',
            };

            // ---- Verifikasi Data (otomatis) ----
            // Dimulai sehari setelah batas pendaftaran, berakhir sehari
            // sebelum voting dibuka. Kalau tidak ada selisih hari, tahap
            // ini tidak ditampilkan.
            $verifAwal  = $batas->copy()->addDay()->startOfDay();
            $verifAkhir = $mulai->copy()->subDay()->startOfDay();

            // ---- Jadwal Event (urut dari awal sampai akhir) ----
            $jadwal = [
                ['nama' => 'Pendaftaran Kandidat', 'tanggal' => $rentang($buka, $batas)],
            ];

            if ($verifAwal->lte($verifAkhir)) {
                $jadwal[] = ['nama' => 'Verifikasi Data', 'tanggal' => $rentang($verifAwal, $verifAkhir)];
            }

            $jadwal[] = [
                'nama'    => 'Voting Publik',
                'tanggal' => $selesai ? $rentang($mulai, $selesai) : 'Mulai ' . $rentang($mulai),
            ];

            // Pemenang tidak punya tanggal khusus: tampil setelah voting selesai
            $jadwal[] = ['nama' => 'Pengumuman Pemenang', 'tanggal' => 'Setelah voting berakhir'];
        }

    @endphp


    <!-- =========================================================
         HEADER (Disamakan dengan Dashboard)
    ========================================================== -->
    <header class="sticky top-0 z-50 border-b border-[#e9e9e9] bg-white/95 backdrop-blur">
    <div class="mx-auto flex h-[56px] max-w-[1200px] items-center justify-between px-5 md:px-8">

        <!-- Logo / Nama Brand -->
        <a href="{{ route('dashboard-user') }}" class="font-display text-[16px] font-bold tracking-tight text-[#13223a]">
    EVENTALNAY.ID
</a>

        <!-- Profil Pengguna -->
        <a href="{{ route('profil-user') }}" aria-label="Profil pengguna"
            class="flex h-9 w-9 items-center justify-center rounded-full border border-[#d9d9d9] bg-white text-[#333] transition hover:bg-[#f7f7f7]">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                <circle cx="12" cy="8" r="3.5"></circle>
                <path d="M5.5 20c.8-3.2 3-5 6.5-5s5.7 1.8 6.5 5"></path>
            </svg>
        </a>

    </div>
</header>


    <!-- =========================================================
         MAIN CONTENT
    ========================================================== -->

    <main class="max-w-5xl mx-auto px-5 pt-0 pb-6">

    @if($event)

        <!-- =====================================================
             EVENT BANNER
        ====================================================== -->

        <section class="relative overflow-hidden rounded-xl mx-0 md:-mx-10 mt-4">
            <img
                src="{{ asset('image/event/' . $event['foto']) }}"
                alt="{{ $event['judul'] }}"
                class="w-full h-[220px] sm:h-[300px] md:h-[380px] object-cover object-center"
            >
        </section>


        <!-- =====================================================
             EVENT INFORMATION
        ====================================================== -->

        <section class="relative -mt-5 mx-4 bg-white rounded-xl shadow-sm border border-gray-100">

            <div class="p-4 md:p-5">

                <div class="grid grid-cols-1 lg:grid-cols-[1fr_220px] gap-5">


                    <!-- EVENT DESCRIPTION -->

                    <div>

                        <!-- Status -->
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-[#c99a32] text-white text-[10px] font-semibold uppercase tracking-wider">

                            {{ $event['status'] }}

                        </span>


                        <!-- Title -->
                        <h1 class="font-display text-xl md:text-2xl font-bold text-[#111827] mt-2.5">

                            {{ $event['judul'] }}

                        </h1>


                        <!-- Description -->
                        <p class="text-gray-500 text-xs md:text-sm leading-relaxed mt-2.5 max-w-3xl">

                            {{ $event['deskripsi'] }}

                        </p>


                        <!-- Info singkat -->
                        <div class="flex flex-wrap gap-2 mt-3.5">

                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-[#faf8f2] border border-gray-100 text-[11px] text-gray-600">
                                Voting mulai {{ $mulai->translatedFormat('j M Y') }}
                            </span>

                            @if($event['lokasi'])
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-[#faf8f2] border border-gray-100 text-[11px] text-gray-600">
                                {{ $event['lokasi'] }}
                            </span>
                            @endif

                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-[#faf8f2] border border-gray-100 text-[11px] text-gray-600">
                                {{ $rp($event['harga_vote']) }} / vote
                            </span>

                        </div>

                    </div>


                    <!-- COUNTDOWN (mengikuti status pendaftaran) -->
                    <!-- HP: tersusun ke bawah | tablet: hitung mundur kiri, statistik kanan | desktop: tersusun ke bawah di kolom sempit -->

                    <div class="bg-[#faf8f2] border border-[#eee5d2] rounded-lg p-3.5 sm:p-4 lg:p-3.5 self-start">

                        <div class="sm:grid sm:grid-cols-2 sm:items-center sm:gap-6 lg:block">


                            <!-- Hitung mundur -->

                            <div>

                                <p class="text-center text-[10px] font-bold text-gray-500 uppercase tracking-widest">

                                    {{ $cdLabel }}

                                </p>


                                <div class="flex items-center justify-center gap-1.5 mt-1.5">

                                    <span class="text-xl font-bold text-[#c99a32]">
                                        {{ sprintf('%02d', $cdHari) }}
                                    </span>

                                    <span class="text-xl text-[#c99a32]">:</span>

                                    <span class="text-xl font-bold text-[#c99a32]">
                                        {{ sprintf('%02d', $cdJam) }}
                                    </span>

                                    <span class="text-xl text-[#c99a32]">:</span>

                                    <span class="text-xl font-bold text-[#c99a32]">
                                        {{ sprintf('%02d', $cdMenit) }}
                                    </span>

                                </div>


                                <div class="flex justify-center gap-6 text-[8px] text-gray-500 font-semibold uppercase mt-1">

                                    <span>Hari</span>
                                    <span>Jam</span>
                                    <span>Menit</span>

                                </div>

                            </div>


                            <!-- Statistik -->

                            <div class="grid grid-cols-2 gap-3.5 border-t border-gray-200 mt-3.5 pt-3.5 text-center
                                        sm:grid-cols-1 sm:gap-3 sm:border-t-0 sm:border-l sm:mt-0 sm:pt-0 sm:pl-6 sm:text-left
                                        lg:grid-cols-2 lg:gap-3.5 lg:border-l-0 lg:border-t lg:mt-3.5 lg:pt-3.5 lg:pl-0">

                                <div>

                                    <p class="text-base font-bold whitespace-nowrap">
                                        {{ $rp($event['biaya_daftar']) }}
                                    </p>

                                    <p class="text-xs text-gray-500 mt-1">
                                        Biaya Daftar
                                    </p>

                                </div>


                                <div>

                                    <p class="text-base font-bold whitespace-nowrap">
                                        {{ $event['kuota'] ? $event['kuota'] . ' orang' : '-' }}
                                    </p>

                                    <p class="text-xs text-gray-500 mt-1">
                                        Kuota Peserta
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>


        <!-- =====================================================
             SYARAT & KETENTUAN SECTION
        ====================================================== -->

        <section class="mt-7">

            <h2 class="font-display text-lg md:text-xl font-bold text-[#15233d]">
                Syarat &amp; Ketentuan
            </h2>

            <p class="text-xs text-gray-500 mt-1">
                Daftar kandidat akan tampil setelah voting dimulai.
            </p>


            <div class="grid grid-cols-1 lg:grid-cols-[1fr_270px] gap-5 mt-3.5 items-start">


                <!-- KOLOM KIRI -->

                <div class="space-y-4">

                    <!-- Syarat & Ketentuan Event -->
                    @if(count($event['syarat_ketentuan']) > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-5">

                        <h3 class="font-display text-base md:text-lg font-bold text-[#15233d]">
                            Syarat &amp; Ketentuan Event
                        </h3>

                        <ul class="mt-3 space-y-2 text-xs md:text-sm text-gray-600 list-disc pl-5 marker:text-[#c99a32]">

                            @foreach($event['syarat_ketentuan'] as $poin)
                                <li>{{ $poin }}</li>
                            @endforeach

                        </ul>

                    </div>
                    @endif


                    <!-- Alur Pendaftaran -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-5">

                        <h3 class="font-display text-base md:text-lg font-bold text-[#15233d]">
                            Alur Pendaftaran
                        </h3>

                        <ol class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 mt-3">

                            <li class="rounded-lg bg-[#faf8f2] p-3">
                                <span class="font-display text-lg font-bold text-[#c99a32]">1</span>
                                <p class="text-xs md:text-sm font-semibold text-[#15233d]">Masuk dengan Google</p>
                            </li>

                            <li class="rounded-lg bg-[#faf8f2] p-3">
                                <span class="font-display text-lg font-bold text-[#c99a32]">2</span>
                                <p class="text-xs md:text-sm font-semibold text-[#15233d]">Isi formulir &amp; unggah foto</p>
                            </li>

                            <li class="rounded-lg bg-[#faf8f2] p-3">
                                <span class="font-display text-lg font-bold text-[#c99a32]">3</span>
                                <p class="text-xs md:text-sm font-semibold text-[#15233d]">Verifikasi admin</p>
                            </li>

                            <li class="rounded-lg bg-[#faf8f2] p-3">
                                <span class="font-display text-lg font-bold text-[#c99a32]">4</span>
                                <p class="text-xs md:text-sm font-semibold text-[#15233d]">Tampil di halaman event</p>
                            </li>

                        </ol>

                    </div>

                </div>


                <!-- KOLOM KANAN (menempel saat halaman digulir) -->

                <aside class="space-y-4 lg:sticky lg:top-20">

                    <!-- Pendaftaran Kandidat -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-5">

                        <h3 class="text-sm font-semibold text-[#15233d]">
                            Pendaftaran Kandidat
                        </h3>

                        <p class="text-xs text-gray-500 mt-3">
                            {{ $infoLabel }}
                        </p>

                        <p class="text-sm font-semibold text-[#15233d] mt-0.5">
                            {{ $infoTanggal->translatedFormat('l, j M Y') }}
                        </p>

                        <p class="text-xs text-gray-500">
                            Pukul {{ $infoTanggal->format('H.i') }} WIB
                        </p>


                        @if($ctaAktif)

                            {{-- Ganti href dengan route halaman formulir pendaftaran peserta --}}
                            <a href="#"
                                class="mt-4 block w-full rounded-md bg-[#15233d] hover:bg-[#1f3357] px-4 py-3 text-center text-sm font-semibold text-white transition">
                                {{ $ctaTeks }}
                            </a>

                        @else

                            <span aria-disabled="true"
                                class="mt-4 block w-full rounded-md bg-gray-100 px-4 py-3 text-center text-sm font-semibold text-gray-400 cursor-not-allowed">
                                {{ $ctaTeks }}
                            </span>

                        @endif


                        <p class="mt-2 text-center text-xs text-gray-500">
                            {{ $ctaCatatan }}
                        </p>

                    </div>


                    <!-- Jadwal Event -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-5">

                        <h3 class="text-sm font-semibold text-[#15233d]">
                            Jadwal Event
                        </h3>

                        <ol class="relative mt-4 space-y-4 border-l border-[#e8dcc0] pl-5">

                            @foreach($jadwal as $tahap)

                                <li class="relative">

                                    <span class="absolute -left-[26px] top-1 h-2.5 w-2.5 rounded-full bg-[#c99a32]"></span>

                                    <p class="text-sm font-medium text-[#15233d]">
                                        {{ $tahap['nama'] }}
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        {{ $tahap['tanggal'] }}
                                    </p>

                                </li>

                            @endforeach

                        </ol>

                    </div>

                </aside>

            </div>

        </section>

    @else

        <!-- =====================================================
             FALLBACK: EVENT TIDAK DITEMUKAN
        ====================================================== -->

        <div class="mt-10 rounded-xl border border-dashed border-gray-200 bg-white p-14 text-center">
            <p class="text-base font-semibold text-[#15233d]">
                Event tidak ditemukan
            </p>
            <p class="mt-1 text-sm text-gray-400">
                Event mungkin sudah dihapus atau tautan yang kamu buka sudah tidak berlaku.
            </p>

            <a href="{{ route('dashboard-user') }}"
                class="mt-5 inline-block rounded-md bg-[#c69220] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#b58219]">
                Kembali ke Dashboard
            </a>
        </div>

    @endif

    </main>


    <!-- =========================================================
         FOOTER
    ========================================================== -->

    <footer class="bg-[#111111] text-white mt-14">

        <div class="max-w-5xl mx-auto px-5 py-8">


            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">


                <!-- BRAND -->

                <div>

                    <div class="flex items-center gap-3">

                        <div class="w-8 h-8 bg-white flex items-center justify-center text-black text-xs font-bold">
                            AL
                        </div>

                        <div>

                            <h3 class="font-display text-sm font-bold tracking-wider">
                                ALNAY.ID
                            </h3>

                            <p class="text-[8px] text-[#c99a32] tracking-widest">
                                JAS & GOWN BERBUDAYA
                            </p>

                        </div>

                    </div>


                    <p class="text-gray-400 text-sm leading-relaxed mt-4">

                        Platform penyewaan dan bespoke atelier busana adat Nusantara nomor satu di Indonesia.

                    </p>

                </div>


                <!-- KOLEKSI -->

                <div>

                    <h4 class="text-xs font-semibold tracking-widest">
                        KOLEKSI ADAT
                    </h4>

                    <ul class="text-gray-400 text-sm space-y-2 mt-4">

                        <li>Jas Beskap Pria</li>
                        <li>Gown Kebaya Pengantin</li>
                        <li>Set Baju Pasangan</li>
                        <li>Mahkota & Suntingan</li>
                        <li>Tenun Songket Asli</li>

                    </ul>

                </div>


                <!-- BANTUAN -->

                <div>

                    <h4 class="text-xs font-semibold tracking-widest">
                        BUTUH BANTUAN?
                    </h4>

                    <p class="text-gray-400 text-sm mt-4">
                        Senin - Jumat: 08:00 - 20:00 WIB
                    </p>

                    <p class="text-gray-400 text-sm mt-2">
                        Sabtu - Minggu: 09:00 - 18:00 WIB
                    </p>

                    <button class="mt-3 bg-[#c69220] px-4 py-2 rounded text-xs">
                        Chat WhatsApp CS
                    </button>

                </div>


                <!-- LAYANAN -->

                <div>

                    <h4 class="text-xs font-semibold tracking-widest">
                        LAYANAN & INFORMASI
                    </h4>

                    <ul class="text-gray-400 text-sm space-y-2 mt-4">

                        <li>Cara & Syarat Sewa Busana</li>
                        <li>Aturan Deposit & Asuransi</li>
                        <li>Jadwal Fitting Studio</li>
                        <li>Panduan Ukur Badan Online</li>
                        <li>Pemesanan Custom Bespoke</li>

                    </ul>

                </div>

            </div>


            <!-- PAYMENT -->

            <div class="border-t border-gray-800 mt-8 pt-5 flex flex-col lg:flex-row justify-between gap-4">

                <div>

                    <p class="text-xs text-gray-500 uppercase tracking-wider">
                        Metode Pembayaran Resmi
                    </p>

                    <div class="flex gap-2 mt-3">

                        <span class="px-3 py-1 bg-gray-800 rounded text-xs">BCA</span>
                        <span class="px-3 py-1 bg-gray-800 rounded text-xs">MANDIRI</span>
                        <span class="px-3 py-1 bg-gray-800 rounded text-xs">BNI</span>
                        <span class="px-3 py-1 bg-gray-800 rounded text-xs">BRI</span>
                        <span class="px-3 py-1 bg-gray-800 rounded text-xs">QRIS</span>
                        <span class="px-3 py-1 bg-gray-800 rounded text-xs">VISA / MASTER</span>

                    </div>

                </div>


                <div class="text-right">

                    <p class="text-xs text-gray-500 uppercase tracking-wider">
                        Ekspedisi Khusus & Kurir Aman
                    </p>

                    <div class="flex gap-2 mt-3 justify-end">

                        <span class="px-3 py-1 bg-gray-800 rounded text-xs">JNE YES</span>
                        <span class="px-3 py-1 bg-gray-800 rounded text-xs">GoSend Instant</span>
                        <span class="px-3 py-1 bg-gray-800 rounded text-xs">GrabExpress</span>

                    </div>

                </div>

            </div>


            <!-- COPYRIGHT -->

            <div class="border-t border-gray-800 mt-6 pt-5 flex flex-col md:flex-row justify-between gap-3 text-xs text-gray-500">

                <p>
                    © 2026 ALNAY.ID (Jas & Gown Berbudaya). All Rights Reserved.
                </p>

                <div class="flex gap-5">

                    <a href="#" class="hover:text-white">
                        Kebijakan Privasi
                    </a>

                    <a href="#" class="hover:text-white">
                        Syarat & Ketentuan Sewa
                    </a>

                    <a href="#" class="hover:text-white">
                        Keagungan Adat Nusantara
                    </a>

                </div>

            </div>

        </div>

    </footer>


</body>
</html>