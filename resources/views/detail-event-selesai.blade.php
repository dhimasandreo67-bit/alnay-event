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
        // Cukup isi $kandidat (urutan bebas). Tiga besar
        // ($pemenang) dan peringkat 4–6 ($peringkat_lain) dihitung
        // otomatis dari perolehan suara di bagian "LOGIKA TURUNAN"
        // di bawah.
        // =====================================================

        $event = [
            'id'              => 1,
            'judul'           => 'Pemilihan Duta Budaya 2024',
            'status'          => 'Telah Berakhir',
            'deskripsi'       => 'Dukung kandidat favorit Anda untuk menjadi wajah representasi kekayaan budaya Nusantara. Setiap suara menentukan siapa yang akan membawa narasi warisan kita ke kancah global.',
            'foto'            => 'event-alnay3.png',
            'jumlah_kandidat' => 24,
            'total_vote'      => '15.4K',
            'total_suara'     => '15,400',
        ];

        // Daftar kandidat untuk grid "Daftar Kandidat"
        $kandidat = [
            ['nomor' => '01', 'nama' => 'Raden Roro Ayu','foto' => 'kandidat1.jpg', 'persen' => '29.35'],
            ['nomor' => '02', 'nama' => 'Made Wirawan',  'foto' => 'kandidat2.jpg', 'persen' => '19.39'],
            ['nomor' => '03', 'nama' => 'Siti Nurbaya',  'foto' => 'kandidat3.jpg', 'persen' => '19.06'],
            ['nomor' => '04', 'nama' => 'Bimo Susanto',  'foto' => 'kandidat4.jpg', 'persen' => '19.2'],
            ['nomor' => '05', 'nama' => 'Ega Daniswara', 'foto' => 'kandidat5.jpg', 'persen' => '17.7'],
            ['nomor' => '06', 'nama' => 'I Gede Anune',  'foto' => 'kandidat6.jpg', 'persen' => '16.2'],
            ['nomor' => '07', 'nama' => 'Siti Nurbaya',  'foto' => 'kandidat7.jpg', 'persen' => '14.7'],
            ['nomor' => '08', 'nama' => 'Bimo Susanto',  'foto' => 'kandidat3.jpg', 'persen' => '14.0'],
        ];

        /*
        // =====================================================
        // OPSI 2 — DATA DINAMIS DARI DATABASE (NONAKTIF)
        // Aktifkan blok ini (dan comment Opsi 1 di atas) setelah
        // database & halaman admin sudah tersedia.
        // Data tetap dirapikan ke bentuk array yang sama seperti
        // Opsi 1, supaya bagian HTML di bawah tidak perlu diubah.
        //
        // $event bisa bernilai null kalau ID tidak ditemukan di
        // database (dicek lewat @if($event) di bagian HTML).
        // =====================================================

        $event_id = request()->route('id'); // dari route('detail-event-selesai', $id)

        $q_event = $pdo->prepare("SELECT * FROM events WHERE id = ?");
        $q_event->execute([$event_id]);
        $row_event = $q_event->fetch(PDO::FETCH_ASSOC);

        $event = $row_event ? [
            'id'              => $row_event['id'],
            'judul'           => $row_event['judul'],
            'status'          => 'Telah Berakhir', // label tetap, bukan isi kolom status
            'deskripsi'       => $row_event['deskripsi'],
            'foto'            => $row_event['foto'],
            'jumlah_kandidat' => $row_event['jumlah_kandidat'],
            'total_vote'      => $row_event['total_vote'],
            'total_suara'     => $row_event['total_suara'],
        ] : null;

        $kandidat       = [];
        $pemenang       = [];
        $peringkat_lain = [];

        if ($event) {
            $q_kandidat = $pdo->prepare("SELECT * FROM kandidat WHERE event_id = ? ORDER BY persen DESC");
            $q_kandidat->execute([$event['id']]);

            foreach ($q_kandidat->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $kandidat[] = [
                    'nomor'  => $row['nomor'],
                    'nama'   => $row['nama'],
                    'foto'   => $row['foto'],
                    'persen' => $row['persen'],
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
        // Kandidat diurutkan otomatis dari perolehan suara
        // tertinggi. Tiga besar dan peringkat 4–6 diambil dari
        // urutan itu, jadi tidak perlu ditulis manual lagi.
        // =====================================================

        usort($kandidat, fn ($a, $b) => (float) $b['persen'] <=> (float) $a['persen']);

        // Inisial dari huruf pertama dua kata pertama nama
        $inisial = fn ($nama) => strtoupper(implode('', array_map(
            fn ($kata) => mb_substr($kata, 0, 1),
            array_slice(preg_split('/\s+/', trim($nama)), 0, 2)
        )));

        // Tiga besar untuk kartu "Pemenang & Hasil Akhir"
        $pemenang = [];

        foreach (array_slice($kandidat, 0, 3) as $i => $k) {
            $k['juara'] = $i + 1;
            $pemenang[] = $k;
        }

        // Peringkat 4–6 untuk daftar ranking di bawah kartu pemenang
        // ('peringkat' = nomor urut peringkat, berbeda dari 'nomor' kandidat)
        $peringkat_lain = [];

        foreach (array_slice($kandidat, 3, 3) as $i => $k) {
            $k['peringkat']   = sprintf('%02d', $i + 4);
            $k['inisial']     = $inisial($k['nama']);
            $peringkat_lain[] = $k;
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

                <div class="grid grid-cols-1 lg:grid-cols-[1fr_200px] gap-5">


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

                    </div>


                    <!-- STATISTIK -->

                    <div class="bg-[#faf8f2] border border-[#eee5d2] rounded-lg p-3.5 flex items-center">

                        <div class="grid grid-cols-2 gap-4 w-full text-center">

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

        </section>


        <!-- =====================================================
             PEMENANG & HASIL AKHIR
        ====================================================== -->

        <section class="mt-8">

            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-2">

                <div>
                    <h2 class="font-display text-lg md:text-xl font-bold text-[#15233d]">
                        Pemenang & Hasil Akhir
                    </h2>

                    <p class="text-gray-500 text-xs md:text-sm mt-1">
                        Tiga besar dengan perolehan suara tertinggi, disusul peringkat 4–6.
                    </p>
                </div>

                <p class="text-xs text-gray-500 whitespace-nowrap">
                    Total {{ $event['total_suara'] }} suara dari {{ $event['jumlah_kandidat'] }} kandidat
                </p>

            </div>

            @if(count($pemenang) > 0)

                <!-- KARTU TIGA BESAR -->

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 mt-4 items-end">

                    @foreach($pemenang as $p)

                        <div class="bg-white rounded-lg overflow-hidden shadow-sm
                            {{ $p['juara'] == 1 ? 'border-2 border-[#c99a32] shadow-md sm:order-2' : 'border border-gray-100 ' . ($p['juara'] == 2 ? 'sm:order-1' : 'sm:order-3') }}">

                            <div class="relative overflow-hidden {{ $p['juara'] == 1 ? 'h-[230px] sm:h-[270px]' : 'h-[190px] sm:h-[210px]' }}">

                                <img
                                    src="{{ asset('image/event/kandidat/' . $p['foto']) }}"
                                    alt="{{ $p['nama'] }}"
                                    class="w-full h-full object-cover"
                                >

                                <span class="absolute top-2.5 left-2.5 bg-white px-2.5 py-1 rounded-md text-xs font-semibold text-[#15233d] shadow-sm">
                                    {{ $p['nomor'] }}
                                </span>

                                <span class="absolute top-2.5 right-2.5 bg-[#c99a32] text-white text-[10px] font-semibold px-2.5 py-1 rounded-md uppercase tracking-wide">
                                    Juara {{ $p['juara'] }}
                                </span>

                            </div>

                            <div class="p-3">

                                <div class="flex items-center justify-between gap-2">

                                    <h3 class="font-semibold text-sm text-[#15233d]">
                                        {{ $p['nama'] }}
                                    </h3>

                                </div>

                                <p class="text-base font-bold text-[#15233d] mt-1.5">
                                    {{ $p['persen'] }}% <span class="text-xs font-normal text-gray-400">Vote</span>
                                </p>

                                <div class="w-full h-1.5 bg-gray-100 rounded-full mt-2">
                                    <div class="h-1.5 bg-[#c99a32] rounded-full" style="width: {{ $p['persen'] }}%"></div>
                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

                @if(count($peringkat_lain) > 0)

                    <!-- PERINGKAT 4–6 -->

                    <div class="bg-white rounded-lg border border-gray-100 shadow-sm mt-4 divide-y divide-gray-100">

                        @foreach($peringkat_lain as $r)

                            <div class="flex items-center gap-3 px-4 py-3">

                                <span class="text-gray-300 font-semibold text-sm w-5">
                                    {{ $r['peringkat'] }}
                                </span>

                                <div class="w-8 h-8 rounded-full bg-[#fbf6e9] text-[#c99a32] text-[11px] font-bold flex items-center justify-center shrink-0">
                                    {{ $r['inisial'] }}
                                </div>

                                <p class="text-sm font-semibold text-[#15233d] w-40 truncate">
                                    {{ $r['nama'] }}
                                </p>

                                <div class="flex-1 h-1.5 bg-gray-100 rounded-full mx-2">
                                    <div class="h-1.5 bg-[#c99a32] rounded-full" style="width: {{ $r['persen'] }}%"></div>
                                </div>

                                <span class="text-sm font-semibold text-[#15233d] whitespace-nowrap">
                                    {{ $r['persen'] }}% <span class="text-xs font-normal text-gray-400">suara</span>
                                </span>

                            </div>

                        @endforeach

                    </div>

                @endif

            @else

                <div class="mt-4 rounded-lg border border-dashed border-gray-200 bg-white p-10 text-center">
                    <p class="text-sm font-semibold text-[#15233d]">
                        Belum ada data pemenang
                    </p>
                    <p class="mt-1 text-xs text-gray-400">
                        Hasil akhir akan tampil di sini begitu data kandidat tersedia.
                    </p>
                </div>

            @endif

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
                                        {{ $item['persen'] }}% Vote
                                    </span>

                                </div>


                                <!-- DETAIL BUTTON -->

                                <button
                                    class="w-full mt-2.5 py-2 rounded-md bg-[#c69220] hover:bg-[#b58219] text-white text-sm font-medium transition">

                                    Detail

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


</body>
</html>