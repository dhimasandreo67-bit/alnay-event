<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detail Event & Kandidat - ALNAY.ID</title>

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
         DATA EVENT & KANDIDAT
    ========================================================== -->

    @php

        // =====================================================
        // OPSI 1 — DATA MANUAL / STATIS (AKTIF)
        // Dipakai selama tahap pengembangan UI/UX, belum ada DB.
        // Field 'id' disertakan sejak sekarang supaya nanti saat
        // Opsi 2 (database) aktif, tidak perlu mengubah struktur
        // di bagian HTML.
        //
        // Hitung mundur dihitung otomatis dari 'selesai' (lihat
        // bagian LOGIKA TURUNAN di bawah). 'selesai' boleh diisi
        // null (admin tidak wajib mengisinya), maka hitung mundur
        // disembunyikan.
        // =====================================================

        $event = [
            'id'               => 1,
            'judul'            => 'Pemilihan Duta Budaya 2024',
            'status'           => 'Voting Sedang Berlangsung',
            'deskripsi'        => 'Dukung kandidat favorit Anda untuk menjadi wajah representasi kekayaan budaya Nusantara. Setiap suara menentukan siapa yang akan membawa narasi warisan kita ke kancah global.',
            'foto'             => 'event-alnay1.png',
            'lokasi'           => 'Gedung Serbaguna, Bandar Lampung',
            'harga_vote'       => 5000,
            'buka_daftar'      => '2026-09-01 08:00:00', // dari form "Buat Event Baru" -> Pendaftaran
            'batas_daftar'     => '2026-09-20 23:59:00',
            'mulai'            => '2026-10-01 09:00:00',
            'selesai'          => '2026-11-10 21:00:00',
            'jumlah_kandidat'  => 24,
            'total_vote'       => '15.4K',
            'syarat_ketentuan' => [
                'Voting hanya dapat dilakukan setelah masuk dengan akun Google.',
                'Suara yang sudah diberikan tidak dapat dibatalkan atau dipindahkan.',
                'Kandidat tidak diperbolehkan memberikan suara untuk dirinya sendiri.',
                'Kecurangan seperti akun palsu atau bot berujung diskualifikasi.',
                'Keputusan panitia bersifat final.',
            ],
        ];

        $kandidat = [
            ['nomor' => '01', 'nama' => 'Raden Roro Ayu','foto' => 'kandidat1.jpg', 'vote' => '1,245'],
            ['nomor' => '02', 'nama' => 'Made Wirawan',  'foto' => 'kandidat2.jpg', 'vote' => '1,102'],
            ['nomor' => '03', 'nama' => 'Siti Nurbaya',  'foto' => 'kandidat3.jpg', 'vote' => '985'],
            ['nomor' => '04', 'nama' => 'Bimo Susanto',  'foto' => 'kandidat4.jpg', 'vote' => '1,420'],
        ];

        /*
        // =====================================================
        // OPSI 2 — DATA DINAMIS DARI DATABASE (NONAKTIF)
        // Aktifkan blok ini (dan comment Opsi 1 di atas) setelah
        // database & halaman admin sudah tersedia.
        // Data tetap dirapikan ke bentuk array yang sama seperti
        // Opsi 1, supaya bagian HTML di bawah tidak perlu diubah.
        //
        // Catatan: syarat_ketentuan disimpan sebagai teks biasa di
        // database (satu poin per baris, dipisah Enter), lalu
        // dipecah menjadi array di sini. Form admin cukup satu
        // kolom "Syarat & Ketentuan" untuk ini.
        //
        // $event bisa bernilai null kalau ID tidak ditemukan di
        // database (dicek lewat @if($event) di bagian HTML).
        // =====================================================

        $event_id = request()->route('id'); // dari route('detail-event', $id)

        $q_event = $pdo->prepare("SELECT * FROM events WHERE id = ?");
        $q_event->execute([$event_id]);
        $row_event = $q_event->fetch(PDO::FETCH_ASSOC);

        $pecah_baris = fn ($teks) => array_values(array_filter(array_map('trim', preg_split('/\R/', (string) $teks))));

        $event = $row_event ? [
            'id'               => $row_event['id'],
            'judul'            => $row_event['judul'],
            'status'           => 'Voting Sedang Berlangsung', // label tetap, bukan isi kolom status
            'deskripsi'        => $row_event['deskripsi'],
            'foto'             => $row_event['foto'],
            'lokasi'           => $row_event['lokasi'],
            'harga_vote'       => $row_event['harga_vote'],
            'buka_daftar'      => $row_event['buka_daftar'],
            'batas_daftar'     => $row_event['batas_daftar'],
            'mulai'            => $row_event['tanggal_mulai'],
            'selesai'          => $row_event['tanggal_selesai'], // boleh null
            'jumlah_kandidat'  => $row_event['jumlah_kandidat'],
            'total_vote'       => $row_event['total_vote'],
            'syarat_ketentuan' => $pecah_baris($row_event['syarat_ketentuan']),
        ] : null;

        $kandidat = [];

        if ($event) {
            $q_kandidat = $pdo->prepare("SELECT * FROM kandidat WHERE event_id = ? ORDER BY vote DESC");
            $q_kandidat->execute([$event['id']]);

            foreach ($q_kandidat->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $kandidat[] = [
                    'nomor' => $row['nomor'],
                    'nama'  => $row['nama'],
                    'foto'  => $row['foto'],
                    'vote'  => number_format($row['vote'], 0, ',', '.'),
                ];
            }
        }
        */

        // Path foto:
        // - Opsi 1 (statis)  : public/image/event/{foto}
        // - Opsi 2 (database, upload admin via storage) sebaiknya pakai:
        //   asset('storage/event/' . $event['foto'])
        //   dengan syarat sudah menjalankan `php artisan storage:link`.


        // =====================================================
        // LOGIKA TURUNAN (dipakai Opsi 1 maupun Opsi 2)
        // Semua dihitung dari data event di atas.
        // Biaya pendaftaran, kuota, dan batas pendaftaran sengaja
        // tidak ditampilkan di halaman ini (khusus untuk pemilih).
        // =====================================================

        if ($event) {

            $tz  = 'Asia/Jakarta';
            $now = \Carbon\Carbon::now($tz);

            $tgl = fn ($v) => $v ? \Carbon\Carbon::parse($v, $tz)->locale('id') : null;

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

            $periode = $selesai ? $rentang($mulai, $selesai) : 'Mulai ' . $rentang($mulai);

            // ---- Jadwal Event (sama persis dengan halaman "Akan Datang") ----
            // Verifikasi Data otomatis: mulai sehari setelah batas daftar,
            // berakhir sehari sebelum voting dibuka. Kalau tidak ada
            // selisih hari, tahap ini tidak ditampilkan.
            $bukaDaftar  = $tgl($event['buka_daftar']);
            $batasDaftar = $tgl($event['batas_daftar']);
            $verifAwal   = $batasDaftar->copy()->addDay()->startOfDay();
            $verifAkhir  = $mulai->copy()->subDay()->startOfDay();

            $jadwal = [
                ['nama' => 'Pendaftaran Kandidat', 'tanggal' => $rentang($bukaDaftar, $batasDaftar)],
            ];

            if ($verifAwal->lte($verifAkhir)) {
                $jadwal[] = ['nama' => 'Verifikasi Data', 'tanggal' => $rentang($verifAwal, $verifAkhir)];
            }

            $jadwal[] = ['nama' => 'Voting Publik', 'tanggal' => $periode];
            $jadwal[] = ['nama' => 'Pengumuman Pemenang', 'tanggal' => 'Setelah voting berakhir'];

            // ---- Hitung mundur sisa waktu voting ----
            // Hanya ada kalau admin mengisi tanggal selesai.
            $cdLabel = $selesai ? 'Sisa Waktu Voting' : 'Voting Sedang Berjalan';

            $detik   = $selesai ? max(0, $selesai->timestamp - $now->timestamp) : 0;
            $cdHari  = intdiv($detik, 86400);
            $cdJam   = intdiv($detik % 86400, 3600);
            $cdMenit = intdiv($detik % 3600, 60);
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
                                Voting {{ $periode }}
                            </span>

                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-[#faf8f2] border border-gray-100 text-[11px] text-gray-600">
                                {{ $rp($event['harga_vote']) }} / vote
                            </span>

                            @if($event['lokasi'])
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-[#faf8f2] border border-gray-100 text-[11px] text-gray-600">
                                {{ $event['lokasi'] }}
                            </span>
                            @endif

                        </div>


                        <!-- Tautan syarat & ketentuan (membuka popup) -->
                        <button type="button" data-buka-syarat
                            class="inline-block mt-3 text-xs font-medium text-[#a37b2a] hover:text-[#c99a32] transition">
                            Lihat Syarat &amp; Ketentuan ›
                        </button>

                    </div>


                    <!-- COUNTDOWN -->
                    <!-- HP: tersusun ke bawah | tablet: hitung mundur kiri, statistik kanan | desktop: tersusun ke bawah di kolom sempit -->

                    <div class="bg-[#faf8f2] border border-[#eee5d2] rounded-lg p-3.5 sm:p-4 lg:p-3.5 self-start">

                        <div class="sm:grid sm:grid-cols-2 sm:items-center sm:gap-6 lg:block">


                            <!-- Hitung mundur -->

                            <div>

                                <p class="text-center text-[10px] font-bold text-gray-500 uppercase tracking-widest">

                                    {{ $cdLabel }}

                                </p>


                                @if($selesai)

                                    <!-- Angka dan labelnya satu kolom, jadi selalu sejajar -->
                                    <div class="flex items-start justify-center gap-2 mt-2">

                                        <div class="min-w-[36px] text-center">
                                            <span class="block text-xl font-bold leading-none text-[#c99a32]">
                                                {{ sprintf('%02d', $cdHari) }}
                                            </span>
                                            <span class="mt-1.5 block text-[8px] font-semibold uppercase text-gray-500">Hari</span>
                                        </div>

                                        <span class="text-xl leading-none text-[#c99a32]">:</span>

                                        <div class="min-w-[36px] text-center">
                                            <span class="block text-xl font-bold leading-none text-[#c99a32]">
                                                {{ sprintf('%02d', $cdJam) }}
                                            </span>
                                            <span class="mt-1.5 block text-[8px] font-semibold uppercase text-gray-500">Jam</span>
                                        </div>

                                        <span class="text-xl leading-none text-[#c99a32]">:</span>

                                        <div class="min-w-[36px] text-center">
                                            <span class="block text-xl font-bold leading-none text-[#c99a32]">
                                                {{ sprintf('%02d', $cdMenit) }}
                                            </span>
                                            <span class="mt-1.5 block text-[8px] font-semibold uppercase text-gray-500">Menit</span>
                                        </div>

                                    </div>

                                @endif

                            </div>


                            <!-- Statistik -->

                            <div class="grid grid-cols-2 gap-3.5 border-t border-gray-200 mt-3.5 pt-3.5 text-center
                                        sm:grid-cols-1 sm:gap-3 sm:border-t-0 sm:border-l sm:mt-0 sm:pt-0 sm:pl-6 sm:text-left
                                        lg:grid-cols-2 lg:gap-3.5 lg:border-l-0 lg:border-t lg:mt-3.5 lg:pt-3.5 lg:pl-0">

                                <div>

                                    <p class="text-lg font-bold">
                                        {{ $event['jumlah_kandidat'] }}
                                    </p>

                                    <p class="text-xs text-gray-500 mt-1">
                                        Kandidat
                                    </p>

                                </div>


                                <div>

                                    <p class="text-lg font-bold">
                                        {{ $event['total_vote'] }}
                                    </p>

                                    <p class="text-xs text-gray-500 mt-1">
                                        Total Vote
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>


        <!-- =====================================================
             CANDIDATE SECTION
        ====================================================== -->

        <section class="mt-7">


            <!-- TITLE + SEARCH -->

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">

                <h2 class="font-display text-lg md:text-xl font-bold text-[#15233d]">
                    Daftar Kandidat
                </h2>


                <div class="flex gap-2.5">

                    <!-- Search -->

                    <div class="relative">

                        <input
                            type="text"
                            placeholder="Cari kandidat..."
                            class="w-full lg:w-60 h-10 pl-9 pr-4 rounded-md border border-gray-300 bg-white text-sm focus:outline-none focus:ring-1 focus:ring-[#c99a32]"
                        >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4 absolute left-3 top-3 text-gray-400"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                d="m21 21-4.35-4.35m1.35-5.4a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z"/>

                        </svg>

                    </div>


                </div>

            </div>


            @if(count($kandidat) > 0)

                <!-- =================================================
                     CANDIDATE CARDS
                ================================================== -->

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 mt-3.5">

                    @foreach($kandidat as $item)

                        <div class="bg-white rounded-lg overflow-hidden border border-gray-100 shadow-sm hover:shadow-md transition">


                            <!-- FOTO -->

                            <div class="relative h-[170px] overflow-hidden">

                                <img
                                    src="{{ asset('image/event/kandidat/' . $item['foto']) }}"
                                    alt="{{ $item['nama'] }}"
                                    class="w-full h-full object-cover"
                                >


                                <!-- NOMOR -->

                                <span class="absolute top-2.5 left-2.5 bg-white px-2.5 py-1.5 rounded-md text-xs font-semibold text-[#15233d] shadow-sm">

                                    {{ $item['nomor'] }}

                                </span>

                            </div>


                            <!-- INFORMATION -->

                            <div class="p-3">


                                <div class="flex items-start justify-between gap-2">

                                    <h3 class="font-semibold text-sm text-[#15233d]">

                                        {{ $item['nama'] }}

                                    </h3>

                                </div>


                                <!-- VOTE -->

                                <div class="flex items-center gap-1.5 mt-2.5 text-xs text-gray-500">

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="w-3.5 h-3.5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor">

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.5"
                                            d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 0115 0"/>

                                    </svg>

                                    <span>
                                        {{ $item['vote'] }} suara
                                    </span>

                                </div>


                                <!-- VOTE BUTTON -->

                                <button
                                    class="w-full mt-2.5 py-2 rounded-md bg-[#c69220] hover:bg-[#b58219] text-white text-sm font-medium transition">

                                    {{ $rp($event['harga_vote']) }} / Vote

                                </button>

                            </div>

                        </div>

                    @endforeach

                </div>


                <!-- =================================================
                     LOAD MORE
                ================================================== -->

                <div class="flex justify-center mt-5">

                    <button
                        class="px-5 py-2 border border-gray-300 bg-white rounded-md font-display text-sm font-semibold text-[#15233d] hover:bg-gray-50 transition">

                        Tampilkan Lebih Banyak

                    </button>

                </div>

            @else

                <div class="mt-3.5 rounded-lg border border-dashed border-gray-200 bg-white p-10 text-center">
                    <p class="text-sm font-semibold text-[#15233d]">
                        Belum ada kandidat pada event ini
                    </p>
                    <p class="mt-1 text-xs text-gray-400">
                        Kandidat akan muncul di sini begitu admin menambahkannya.
                    </p>
                </div>

            @endif

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

                        <span class="px-3 py-1 bg-gray-800 rounded text-xs">
                            BCA
                        </span>

                        <span class="px-3 py-1 bg-gray-800 rounded text-xs">
                            MANDIRI
                        </span>

                        <span class="px-3 py-1 bg-gray-800 rounded text-xs">
                            BNI
                        </span>

                        <span class="px-3 py-1 bg-gray-800 rounded text-xs">
                            BRI
                        </span>

                        <span class="px-3 py-1 bg-gray-800 rounded text-xs">
                            QRIS
                        </span>

                        <span class="px-3 py-1 bg-gray-800 rounded text-xs">
                            VISA / MASTER
                        </span>

                    </div>

                </div>


                <div class="text-right">

                    <p class="text-xs text-gray-500 uppercase tracking-wider">
                        Ekspedisi Khusus & Kurir Aman
                    </p>

                    <div class="flex gap-2 mt-3 justify-end">

                        <span class="px-3 py-1 bg-gray-800 rounded text-xs">
                            JNE YES
                        </span>

                        <span class="px-3 py-1 bg-gray-800 rounded text-xs">
                            GoSend Instant
                        </span>

                        <span class="px-3 py-1 bg-gray-800 rounded text-xs">
                            GrabExpress
                        </span>

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


    @if($event)

    <!-- =========================================================
         POPUP SYARAT & KETENTUAN
         Khusus pemilih: tanpa biaya pendaftaran, kuota, dan
         batas pendaftaran.
    ========================================================== -->

    <div id="modalSyarat" class="hidden fixed inset-0 z-[60] items-end sm:items-center justify-center bg-black/50 sm:px-5">

        <div class="flex max-h-[88vh] w-full max-w-xl flex-col overflow-hidden rounded-t-2xl sm:rounded-2xl bg-white shadow-2xl">


            <!-- Header popup -->

            <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">

                <h2 class="font-display text-lg font-bold text-[#15233d]">
                    Syarat &amp; Ketentuan
                </h2>

                <button type="button" data-tutup-syarat aria-label="Tutup"
                    class="flex h-8 w-8 items-center justify-center rounded-full text-xl text-gray-400 hover:bg-gray-100 transition">
                    &times;
                </button>

            </div>


            <!-- Isi popup -->

            <div class="overflow-y-auto px-5 py-5 space-y-6">

                <!-- Cara Voting -->
                <div>

                    <h3 class="text-sm font-semibold text-[#15233d]">
                        Cara Voting
                    </h3>

                    <ol class="mt-3 grid gap-2.5 text-sm text-[#15233d]">

                        <li class="flex items-center gap-3 rounded-lg bg-[#faf8f2] p-3">
                            <span class="font-display text-lg font-bold text-[#c99a32]">1</span>
                            Pilih kandidat favorit Anda.
                        </li>

                        <li class="flex items-center gap-3 rounded-lg bg-[#faf8f2] p-3">
                            <span class="font-display text-lg font-bold text-[#c99a32]">2</span>
                            Tekan tombol Vote ({{ $rp($event['harga_vote']) }} per vote) dan selesaikan pembayaran.
                        </li>

                        <li class="flex items-center gap-3 rounded-lg bg-[#faf8f2] p-3">
                            <span class="font-display text-lg font-bold text-[#c99a32]">3</span>
                            Suara tercatat dan langsung masuk ke perolehan kandidat.
                        </li>

                    </ol>

                </div>


                <!-- Syarat & Ketentuan Event -->
                @if(count($event['syarat_ketentuan']) > 0)
                <div>

                    <h3 class="text-sm font-semibold text-[#15233d]">
                        Syarat &amp; Ketentuan Event
                    </h3>

                    <ul class="mt-3 space-y-2 text-sm text-gray-600 list-disc pl-5 marker:text-[#c99a32]">

                        @foreach($event['syarat_ketentuan'] as $poin)
                            <li>{{ $poin }}</li>
                        @endforeach

                    </ul>

                </div>
                @endif


                <!-- Jadwal Event -->
                <div>

                    <h3 class="text-sm font-semibold text-[#15233d]">
                        Jadwal Event
                    </h3>

                    <ol class="relative mt-3 space-y-4 border-l border-[#e8dcc0] pl-5">

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


                <!-- Informasi Event -->
                <div>

                    <h3 class="text-sm font-semibold text-[#15233d]">
                        Informasi Event
                    </h3>

                    <div class="mt-1 divide-y divide-gray-100">

                        <div class="flex justify-between gap-4 py-2.5 text-sm">
                            <span class="text-gray-500">Periode Voting</span>
                            <span class="text-right font-medium text-[#15233d]">{{ $periode }}</span>
                        </div>

                        @if($event['lokasi'])
                        <div class="flex justify-between gap-4 py-2.5 text-sm">
                            <span class="text-gray-500">Tempat</span>
                            <span class="text-right font-medium text-[#15233d]">{{ $event['lokasi'] }}</span>
                        </div>
                        @endif

                        <div class="flex justify-between gap-4 py-2.5 text-sm">
                            <span class="text-gray-500">Harga / Vote</span>
                            <span class="text-right font-medium text-[#15233d]">{{ $rp($event['harga_vote']) }} per vote</span>
                        </div>

                    </div>

                </div>

            </div>


            <!-- Footer popup -->

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
        (function () {

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

            document.querySelectorAll('[data-buka-syarat]').forEach(function (el) {
                el.addEventListener('click', buka);
            });

            document.querySelectorAll('[data-tutup-syarat]').forEach(function (el) {
                el.addEventListener('click', tutup);
            });

            // Klik area gelap di luar popup menutup popup
            modal.addEventListener('click', function (e) {
                if (e.target === modal) tutup();
            });

            // Tombol Esc menutup popup
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') tutup();
            });

        })();
    </script>

    @endif


</body>
</html>