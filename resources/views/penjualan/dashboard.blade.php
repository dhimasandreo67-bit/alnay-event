<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ALNAY.ID — Jas & Gown Berbudaya</title>

    <script src="https://cdn.tailwindcss.com"></script>

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

        .card-shadow {
            box-shadow: 0 10px 24px rgba(0, 0, 0, .08);
        }
    </style>
</head>

<body class="bg-[#faf9f6] text-[#1a1a1a]">

    <!-- =========================================================
         DATA BERANDA
    ========================================================== -->

    @php

        // =====================================================
        // OPSI 1 — DATA MANUAL / STATIS (AKTIF)
        // Dipakai selama tahap pengembangan UI/UX, belum ada DB.
        // Field 'id' disertakan sejak sekarang supaya nanti saat
        // Opsi 2 (database) aktif, link ke detail produk tinggal
        // dipasangi ID tanpa perlu mengubah struktur di bawah.
        // =====================================================

        $kategori = [
            ['id' => 1, 'nama' => 'Jas & Beskap',    'subjudul' => 'Pria & Pengantin', 'foto' => 'kategori-jas.jpg'],
            ['id' => 2, 'nama' => 'Gown & Kebaya',    'subjudul' => 'Akad & Resepsi',    'foto' => 'kategori-gown.jpg'],
            ['id' => 3, 'nama' => 'Dress',             'subjudul' => 'Adat Lengkap',       'foto' => 'kategori-dress.jpg'],
            ['id' => 4, 'nama' => 'Aksesoris',         'subjudul' => 'Mahkota & Bros',     'foto' => 'kategori-aksesoris.jpg'],
            ['id' => 5, 'nama' => 'Tenun & Songket',  'subjudul' => 'Kain Warisan Asli',  'foto' => 'kategori-tenun.jpg'],
            ['id' => 6, 'nama' => 'Kemeja Batik',      'subjudul' => 'Berbagai Motif',     'foto' => 'kategori-batik.jpg'],
        ];

        $produkTerbaru = [
            [
                'id' => 1, 'nama' => 'Long Dress Hitam Marun', 'deskripsi' => 'Kebaya ekor bordir emas & mahkota',
                'foto' => 'produk1.jpg', 'badge' => 'Terjual', 'tag' => 'Sewa & Beli',
                'harga_sewa' => 'Rp 2.500.000 /hr', 'harga_beli' => 'Rp 9.500.000',
                'warna' => ['#c99a32', '#e9e4da', '#111111'], 'mode' => 'dua',
            ],
            [
                'id' => 2, 'nama' => 'Jas Hitam Tenun Gold', 'deskripsi' => 'Beskap furing sutera & kain tenun',
                'foto' => 'produk2.jpg', 'badge' => 'Tersedia', 'tag' => 'Sewa & Beli',
                'harga_sewa' => 'Rp 1.800.000 /hr', 'harga_beli' => 'Rp 5.900.000',
                'warna' => ['#111111', '#8a6a2a', '#5b4a2a'], 'mode' => 'dua',
            ],
            [
                'id' => 3, 'nama' => 'Gown Merah Maroon Pengantin', 'deskripsi' => 'Lengkap jas, gaun, mahkota, dan selop',
                'foto' => 'produk3.jpg', 'badge' => 'Disewa', 'tag' => 'Khusus Sewa',
                'harga_sewa' => 'Rp 3.500.000 /hr', 'harga_beli' => null,
                'warna' => [], 'mode' => 'satu',
            ],
            [
                'id' => 4, 'nama' => 'Mahkota Suntiang Padang 9 Tingkat', 'deskripsi' => 'Sepuh emas asli dengan kembang goyang',
                'foto' => 'produk4.jpg', 'badge' => 'Tersedia', 'tag' => 'Khusus Sewa',
                'harga_sewa' => 'Rp 850.000 /hr', 'harga_beli' => null,
                'warna' => ['#c99a32', '#e9e4da'], 'mode' => 'satu',
            ],
        ];

        $produkTerpopuler = [
            [
                'id' => 5, 'peringkat' => 1, 'nama' => 'Set Pasangan Sunda Siger Emas', 'deskripsi' => 'Kebaya payet & beskap kancing kuningan',
                'foto' => 'produk5.jpg', 'rating' => '5.0', 'jumlah_ulasan' => 340,
                'harga_sewa' => 'Rp 3.200.000 /hr', 'harga_beli' => null, 'mode' => 'satu-sewa-busana',
            ],
            [
                'id' => 6, 'peringkat' => 2, 'nama' => 'Jas Beskap Beludru Jawa Klasik', 'deskripsi' => 'Bordir ornamen garuda & jarik prada',
                'foto' => 'produk6.jpg', 'rating' => '4.9', 'jumlah_ulasan' => 215,
                'harga_sewa' => 'Rp 1.600.000 /hr', 'harga_beli' => 'ada', 'mode' => 'dua',
            ],
            [
                'id' => 7, 'peringkat' => 3, 'nama' => 'Gaun Pengantin Brokat Payet...', 'deskripsi' => 'Jubah veil renda 3 meter & korset presisi',
                'foto' => 'produk7.jpg', 'rating' => '4.9', 'jumlah_ulasan' => 184,
                'harga_sewa' => 'Rp 2.400.000 /hr', 'harga_beli' => 'ada', 'mode' => 'dua',
            ],
            [
                'id' => 8, 'peringkat' => 4, 'nama' => 'Set Aksesoris Tanjak & Keris...', 'deskripsi' => 'Ornamen adat Melayu & Palembang',
                'foto' => 'produk8.jpg', 'rating' => '4.8', 'jumlah_ulasan' => 152,
                'harga_sewa' => 'Rp 750.000 /hr', 'harga_beli' => null, 'mode' => 'satu-sewa-sekarang',
            ],
        ];

        // Ringkasan event, dipakai untuk dua kartu promosi event di beranda.
        // Data lengkapnya tetap diambil dari halaman dashboard event ($eventBerlangsung
        // dan $eventAkanDatang di dashboard-user.blade.php), di sini cukup cuplikan saja.
        $eventRingkasBerlangsung = [
            'judul'    => 'Ayo vote',
            'info'     => 'Berakhir 20 Sep · voting aktif',
            'foto'     => 'event-alnay1.png',
            'badge'    => 'Sedang Berlangsung',
        ];

        $eventRingkasAkanDatang = [
            'judul'    => 'Ayo daftar',
            'info'     => 'Mulai 10 Okt · pendaftaran dibuka',
            'foto'     => 'event-alnay2.png',
            'badge'    => 'Akan Datang',
        ];

        $testimoni = [
            [
                'nama'  => 'Raditya & Anindya',
                'lokasi'=> 'Pernikahan Adat Lampung di Jakarta',
                'pesan' => 'Sewa set Pengantin Lampung Saibatin untuk resepsi sangat mewah dan gagahnya pas dipakai. Koipiah dan sigernya surprisingly nyaman tidak berat di kepala. Pengiriman ke Jakarta tepat waktu dan wangi sekali!',
                'inisial' => 'RA',
            ],
            [
                'nama'  => 'dr. Fathia Maheswari',
                'lokasi'=> 'Akad Nikah Minang di Bukittinggi',
                'pesan' => 'Suntiang Minang-nya benar-benar standar adibusana! Semua keluarga besar Padang memuji kerapian songket dan detail aksesorisnya. Terima kasih tim ALNAY.ID yang sigap bantu fitting via video call.',
                'inisial' => 'FM',
            ],
            [
                'nama'  => 'Dian Sastroatmodjo',
                'lokasi'=> 'Direktur Komunitas Budaya Nusantara',
                'pesan' => 'Kami menyewa 16 pasang baju adat untuk karnaval delegasi seni internasional. Dari Bugis, Dayak, sampai Bali semuanya lengkap dengan properti tombak dan mahkotanya. Nilai budaya yang sangat terawat!',
                'inisial' => 'DS',
            ],
        ];

        /*
        // =====================================================
        // OPSI 2 — DATA DINAMIS DARI DATABASE (NONAKTIF)
        // Aktifkan blok ini (dan comment Opsi 1 di atas) setelah
        // database & halaman admin sudah tersedia.
        // Data tetap dirapikan ke bentuk array yang sama seperti
        // Opsi 1, supaya bagian HTML di bawah tidak perlu diubah.
        // =====================================================

        $qKategori = $pdo->query("SELECT * FROM kategori_produk ORDER BY urutan ASC");
        $kategori = [];
        foreach ($qKategori->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $kategori[] = [
                'id'       => $row['id'],
                'nama'     => $row['nama'],
                'subjudul' => $row['subjudul'],
                'foto'     => $row['foto'],
            ];
        }

        $qTerbaru = $pdo->query("SELECT * FROM produk ORDER BY created_at DESC LIMIT 4");
        $produkTerbaru = [];
        foreach ($qTerbaru->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $produkTerbaru[] = [
                'id'          => $row['id'],
                'nama'        => $row['nama'],
                'deskripsi'   => $row['deskripsi'],
                'foto'        => $row['foto'],
                'badge'       => $row['status_stok'],      // 'Tersedia' / 'Disewa' / 'Terjual'
                'tag'         => $row['tipe_layanan'],      // 'Sewa & Beli' / 'Khusus Sewa'
                'harga_sewa'  => $row['harga_sewa'] ? 'Rp ' . number_format($row['harga_sewa'], 0, ',', '.') . ' /hr' : null,
                'harga_beli'  => $row['harga_beli'] ? 'Rp ' . number_format($row['harga_beli'], 0, ',', '.') : null,
                'warna'       => json_decode($row['warna_hex'] ?? '[]', true),
                'mode'        => $row['harga_beli'] ? 'dua' : 'satu',
            ];
        }

        $qPopuler = $pdo->query("SELECT * FROM produk ORDER BY jumlah_ulasan DESC LIMIT 4");
        $produkTerpopuler = [];
        foreach ($qPopuler->fetchAll(PDO::FETCH_ASSOC) as $i => $row) {
            $produkTerpopuler[] = [
                'id'             => $row['id'],
                'peringkat'      => $i + 1,
                'nama'           => $row['nama'],
                'deskripsi'      => $row['deskripsi'],
                'foto'           => $row['foto'],
                'rating'         => number_format($row['rating'], 1),
                'jumlah_ulasan'  => $row['jumlah_ulasan'],
                'harga_sewa'     => 'Rp ' . number_format($row['harga_sewa'], 0, ',', '.') . ' /hr',
                'harga_beli'     => $row['harga_beli'] ? 'ada' : null,
                'mode'           => $row['harga_beli'] ? 'dua' : 'satu-sewa-sekarang',
            ];
        }

        $qTestimoni = $pdo->query("SELECT * FROM testimoni WHERE tampilkan_di_beranda = 1 ORDER BY created_at DESC LIMIT 3");
        $testimoni = [];
        foreach ($qTestimoni->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $testimoni[] = [
                'nama'    => $row['nama'],
                'lokasi'  => $row['lokasi'],
                'pesan'   => $row['pesan'],
                'inisial' => strtoupper(substr($row['nama'], 0, 2)),
            ];
        }
        */

        // Path foto:
        // - Opsi 1 (statis)  : public/image/produk/{foto}, public/image/kategori/{foto}
        // - Opsi 2 (database, upload admin via storage) sebaiknya pakai:
        //   asset('storage/produk/' . $item['foto'])
        //   dengan syarat sudah menjalankan `php artisan storage:link`.

    @endphp


    <!-- =========================================================
         TOP BAR
    ========================================================== -->
    <div class="bg-[#111111] text-white">
        <div class="mx-auto flex max-w-[1280px] flex-col gap-1 px-5 py-2 text-[11px] sm:flex-row sm:items-center sm:justify-between md:px-8">
            <p>
                <span class="text-[#c99a32]">●</span>
                Promo Eksklusif Pernikahan 2026: Diskon Sewa hingga 20% &amp; Gratis Fitting Studio.
            </p>

            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-[#bdbdbd]">
                <span>Studio: Senin - Sabtu (09.00 - 19.00 WIB)</span>
                <span class="flex items-center gap-1 text-white">☎ WA CS: 0812-3456-7890</span>
            </div>
        </div>
    </div>


    <!-- =========================================================
         HEADER
    ========================================================== -->
    <header class="border-b border-[#ededed] bg-white">
        <div class="mx-auto flex max-w-[1280px] flex-wrap items-center justify-between gap-4 px-5 py-4 md:px-8">

            <!-- Logo -->
            <a href="#" class="flex items-center gap-2 shrink-0">
                <span class="font-display text-[20px] font-bold text-[#13223a]">ALNAY.ID</span>
                <span class="hidden text-[9px] font-semibold tracking-widest text-[#c99a32] sm:inline">
                    JAS &amp; GOWN<br>BERBUDAYA
                </span>
            </a>

            <!-- Search -->
            <div class="order-3 w-full sm:order-2 sm:w-auto sm:flex-1 sm:max-w-[420px]">
                <div class="relative">
                    <input type="text" placeholder="Cari jas adat, gown pengantin, siger, beskap..."
                        class="h-11 w-full rounded-full border border-gray-200 bg-[#faf9f6] pl-5 pr-11 text-sm focus:outline-none focus:ring-1 focus:ring-[#c99a32]">
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
                </div>
            </div>

            <!-- Actions -->
            <div class="order-2 flex shrink-0 items-center gap-4 sm:order-3 text-[13px] font-medium">

                <a href="#" class="relative text-[#333]" aria-label="Keranjang">
                    🛒
                    <span class="absolute -right-2 -top-2 flex h-4 w-4 items-center justify-center rounded-full bg-[#c99a32] text-[9px] font-bold text-white">2</span>
                </a>

                <a href="#" class="text-[#333]" aria-label="Notifikasi">🔔</a>

                <a href="#" class="hidden text-[#333] hover:text-[#c99a32] sm:inline">Masuk</a>

                <a href="#" class="rounded-md bg-[#c99a32] px-4 py-2 text-xs font-bold text-white transition hover:bg-[#b3882a]">
                    Registrasi
                </a>

            </div>
        </div>

        <!-- Nav -->
        <nav class="border-t border-[#f1f1f1] bg-[#fdfcf9]">
            <div class="mx-auto flex max-w-[1280px] flex-wrap items-center gap-x-7 gap-y-2 px-5 py-2.5 text-[12px] font-semibold text-[#555] md:px-8">
                <a href="#" class="flex items-center gap-1.5 text-[#c99a32]">🏠 Beranda</a>
                <a href="#" class="flex items-center gap-1.5 hover:text-[#c99a32]">Koleksi ▾</a>
                <a href="#" class="flex items-center gap-1.5 hover:text-[#c99a32]">Cara Sewa</a>
                <a href="#" class="flex items-center gap-1.5 hover:text-[#c99a32]">Tentang Kami</a>
                <a href="#" class="flex items-center gap-1.5 hover:text-[#c99a32]">★ Testimoni</a>
                <a href="{{ route('dashboard-user') }}" class="flex items-center gap-1.5 hover:text-[#c99a32]">Event</a>
                <a href="{{ route('riwayat-vote') }}" class="flex items-center gap-1.5 hover:text-[#c99a32]">Riwayat</a>
            </div>
        </nav>
    </header>


    <main class="mx-auto max-w-[1280px] px-5 py-8 md:px-8">

        <!-- =====================================================
             HERO
        ====================================================== -->
        <section class="overflow-hidden rounded-2xl bg-[#17140f]">
            <div class="grid grid-cols-1 gap-8 p-6 sm:p-10 lg:grid-cols-2 lg:items-center">

                <div class="text-white">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1 text-[10px] font-semibold text-[#e6c877]">
                        ● Jas &amp; Gown Berbudaya — Elegan dalam Setiap Momen
                    </span>

                    <h1 class="font-display mt-4 text-[32px] font-bold leading-tight sm:text-[40px]">
                        Jas &amp; Gown Berbudaya, Dicipta untuk Momen Abadi Anda.
                    </h1>

                    <p class="mt-4 max-w-[460px] text-sm leading-relaxed text-[#cfcac0]">
                        Temukan koleksi Fashion Etnik pada ALNAY.ID dengan sentuhan elegan dan modern. Tersedia untuk pembelian maupun penyewaan untuk melengkapi berbagai momen istimewa Anda.
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="#" class="rounded-md bg-[#c99a32] px-5 py-3 text-xs font-bold text-white transition hover:bg-[#b3882a]">
                            Jelajahi Koleksi Produk
                        </a>
                        <a href="#" class="flex items-center gap-2 rounded-md border border-white/30 px-5 py-3 text-xs font-bold text-white transition hover:bg-white/10">
                            💬 Konsultasi WhatsApp
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="relative row-span-2 overflow-hidden rounded-xl border-4 border-[#c99a32]">
                        <img src="{{ asset('image/home/hero-pasangan.jpg') }}" alt="Busana adat berpasangan" class="h-full w-full object-cover">
                        <div class="absolute inset-x-0 bottom-0 bg-black/60 p-3">
                            <p class="text-[10px] font-semibold text-[#e6c877]">Busana Adat Berpasangan</p>
                            <p class="text-[9px] text-gray-300">Perona budaya dalam balutan busana istimewa</p>
                        </div>
                    </div>

                    <div class="overflow-hidden rounded-xl">
                        <img src="{{ asset('image/home/hero-gown.jpg') }}" alt="Gown pengantin" class="h-full w-full object-cover">
                    </div>

                    <div class="overflow-hidden rounded-xl">
                        <img src="{{ asset('image/home/hero-jas.jpg') }}" alt="Jas beskap modern" class="h-full w-full object-cover">
                    </div>
                </div>

            </div>
        </section>


        <!-- =====================================================
             KATEGORI
        ====================================================== -->
        <section class="mt-10">

            <div class="flex flex-wrap justify-center gap-6 sm:gap-10">

                @foreach($kategori as $k)

                    <a href="#" class="flex w-20 flex-col items-center gap-2 text-center">

                        <div class="h-16 w-16 overflow-hidden rounded-full border border-gray-200 bg-white">
                            <img src="{{ asset('image/kategori/' . $k['foto']) }}" alt="{{ $k['nama'] }}" class="h-full w-full object-cover">
                        </div>

                        <div>
                            <p class="text-[11px] font-semibold text-[#15233d]">{{ $k['nama'] }}</p>
                            <p class="text-[9px] text-gray-400">{{ $k['subjudul'] }}</p>
                        </div>

                    </a>

                @endforeach

            </div>
        </section>


        <!-- =====================================================
             PRODUK TERBARU
        ====================================================== -->
        <section class="mt-14">

            <div class="flex items-end justify-between">
                <div>
                    <h2 class="font-display text-xl font-bold text-[#15233d] sm:text-2xl">Produk Terbaru</h2>
                    <p class="mt-1 text-xs text-gray-500">Rilisan busana adat paling baru</p>
                </div>

                <a href="#" class="whitespace-nowrap text-xs font-semibold text-[#c99a32] hover:underline">
                    Lihat Semua →
                </a>
            </div>

            @if(count($produkTerbaru) > 0)

                <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

                    @foreach($produkTerbaru as $p)

                        <div class="card-shadow overflow-hidden rounded-lg border border-gray-100 bg-white">

                            <div class="relative h-[200px]">
                                <img src="{{ asset('image/produk/' . $p['foto']) }}" alt="{{ $p['nama'] }}" class="h-full w-full object-cover">

                                <span @class([
                                    'absolute left-2.5 top-2.5 rounded px-2 py-1 text-[9px] font-bold uppercase text-white',
                                    'bg-[#c0392b]' => $p['badge'] === 'Terjual',
                                    'bg-[#2f7a4a]' => $p['badge'] === 'Tersedia',
                                    'bg-[#111111]' => $p['badge'] === 'Disewa',
                                ])>
                                    {{ $p['badge'] }}
                                </span>

                                <span class="absolute bottom-2.5 left-2.5 rounded bg-black/70 px-2 py-1 text-[9px] font-semibold text-white">
                                    {{ $p['tag'] }}
                                </span>
                            </div>

                            <div class="p-3">

                                <h3 class="text-[13px] font-bold text-[#15233d]">{{ $p['nama'] }}</h3>
                                <p class="mt-0.5 text-[11px] text-gray-500">{{ $p['deskripsi'] }}</p>

                                <div class="mt-2.5 space-y-0.5 text-[11px]">
                                    <div class="flex justify-between">
                                        <span class="text-gray-400">Sewa:</span>
                                        <span class="font-bold text-[#15233d]">{{ $p['harga_sewa'] }}</span>
                                    </div>

                                    @if($p['harga_beli'])
                                        <div class="flex justify-between">
                                            <span class="text-gray-400">Beli Baru:</span>
                                            <span class="font-bold text-[#c99a32]">{{ $p['harga_beli'] }}</span>
                                        </div>
                                    @endif
                                </div>

                                @if(count($p['warna']) > 0)
                                    <div class="mt-2 flex gap-1.5">
                                        @foreach($p['warna'] as $w)
                                            <span class="h-3 w-3 rounded-full border border-gray-200" style="background:{{ $w }}"></span>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="mt-3 flex gap-2">
                                    @if($p['mode'] === 'dua')
                                        <a href="#" class="flex-1 rounded-md border border-gray-300 py-2 text-center text-[10px] font-bold text-[#333] hover:bg-gray-50">
                                            Sewa
                                        </a>
                                        <a href="#" class="flex-1 rounded-md bg-[#c99a32] py-2 text-center text-[10px] font-bold text-white hover:bg-[#b3882a]">
                                            Beli Baru
                                        </a>
                                    @else
                                        <a href="#" class="w-full rounded-md bg-[#c99a32] py-2 text-center text-[10px] font-bold text-white hover:bg-[#b3882a]">
                                            Sewa Sekarang
                                        </a>
                                    @endif
                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="mt-5 rounded-lg border border-dashed border-gray-200 bg-white p-10 text-center">
                    <p class="text-sm font-semibold text-[#15233d]">Belum ada produk terbaru</p>
                </div>

            @endif

        </section>


        <!-- =====================================================
             PRODUK TERPOPULER
        ====================================================== -->
        <section class="mt-14">

            <div class="flex items-end justify-between">
                <div>
                    <h2 class="font-display text-xl font-bold text-[#15233d] sm:text-2xl">Produk Terpopuler</h2>
                    <p class="mt-1 text-xs text-gray-500">Favorit pilihan calon pengantin dan keluarga untuk momen sakral terbaik</p>
                </div>

                <a href="#" class="whitespace-nowrap text-xs font-semibold text-[#c99a32] hover:underline">
                    Lihat Semua →
                </a>
            </div>

            @if(count($produkTerpopuler) > 0)

                <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

                    @foreach($produkTerpopuler as $p)

                        <div class="card-shadow overflow-hidden rounded-lg border border-gray-100 bg-white">

                            <div class="relative h-[200px]">
                                <img src="{{ asset('image/produk/' . $p['foto']) }}" alt="{{ $p['nama'] }}" class="h-full w-full object-cover">

                                <span class="absolute left-2.5 top-2.5 rounded bg-[#c99a32] px-2 py-1 text-[9px] font-bold text-white">
                                    TOP #{{ $p['peringkat'] }}
                                </span>
                            </div>

                            <div class="p-3">

                                <p class="text-[10px] font-semibold text-[#c99a32]">
                                    ★ {{ $p['rating'] }} <span class="text-gray-400">({{ $p['jumlah_ulasan'] }} sewa)</span>
                                </p>

                                <h3 class="mt-1 text-[13px] font-bold text-[#15233d]">{{ $p['nama'] }}</h3>
                                <p class="mt-0.5 text-[11px] text-gray-500">{{ $p['deskripsi'] }}</p>

                                <div class="mt-2.5 flex justify-between text-[11px]">
                                    <span class="text-gray-400">Sewa:</span>
                                    <span class="font-bold text-[#15233d]">{{ $p['harga_sewa'] }}</span>
                                </div>

                                <div class="mt-3 flex gap-2">
                                    @if($p['mode'] === 'dua')
                                        <a href="#" class="flex-1 rounded-md border border-gray-300 py-2 text-center text-[10px] font-bold text-[#333] hover:bg-gray-50">
                                            Sewa
                                        </a>
                                        <a href="#" class="flex-1 rounded-md bg-[#111111] py-2 text-center text-[10px] font-bold text-white hover:bg-[#222]">
                                            Beli Baru
                                        </a>
                                    @elseif($p['mode'] === 'satu-sewa-busana')
                                        <a href="#" class="w-full rounded-md bg-[#111111] py-2 text-center text-[10px] font-bold text-white hover:bg-[#222]">
                                            Sewa Busana
                                        </a>
                                    @else
                                        <a href="#" class="w-full rounded-md bg-[#c99a32] py-2 text-center text-[10px] font-bold text-white hover:bg-[#b3882a]">
                                            Sewa Sekarang
                                        </a>
                                    @endif
                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="mt-5 rounded-lg border border-dashed border-gray-200 bg-white p-10 text-center">
                    <p class="text-sm font-semibold text-[#15233d]">Belum ada produk terpopuler</p>
                </div>

            @endif

        </section>


        <!-- =====================================================
             PROMO EVENT (2 KARTU)
        ====================================================== -->
        <section class="mt-14 grid grid-cols-1 gap-5 sm:grid-cols-2">

            <!-- Event berlangsung -->
            <div class="relative overflow-hidden rounded-xl bg-[#17140f] p-6 text-white">
                <img src="{{ asset('image/event/' . $eventRingkasBerlangsung['foto']) }}" alt=""
                    class="absolute right-0 top-0 h-full w-[45%] object-cover opacity-80">

                <div class="relative z-10 max-w-[60%]">
                    <span class="inline-flex items-center gap-1 rounded-full bg-[#c99a32]/20 px-2.5 py-1 text-[9px] font-semibold text-[#e6c877]">
                        ● {{ $eventRingkasBerlangsung['badge'] }}
                    </span>

                    <p class="mt-3 text-xs text-gray-300">Event hebat ALNAY.ID</p>
                    <h3 class="font-display text-xl font-bold">{{ $eventRingkasBerlangsung['judul'] }}</h3>
                    <p class="mt-1 text-[11px] text-gray-400">{{ $eventRingkasBerlangsung['info'] }}</p>

                    <a href="{{ route('dashboard-user') }}"
                        class="mt-4 inline-block rounded-md bg-[#c99a32] px-4 py-2 text-[11px] font-bold text-white hover:bg-[#b3882a]">
                        Lihat &amp; Vote
                    </a>
                </div>
            </div>

            <!-- Event akan datang -->
            <div class="relative overflow-hidden rounded-xl bg-[#17140f] p-6 text-white">
                <img src="{{ asset('image/event/' . $eventRingkasAkanDatang['foto']) }}" alt=""
                    class="absolute right-0 top-0 h-full w-[45%] object-cover opacity-80">

                <div class="relative z-10 max-w-[60%]">
                    <span class="inline-flex items-center gap-1 rounded-full bg-white/10 px-2.5 py-1 text-[9px] font-semibold text-gray-200">
                        ● {{ $eventRingkasAkanDatang['badge'] }}
                    </span>

                    <p class="mt-3 text-xs text-gray-300">Event hebat ALNAY.ID</p>
                    <h3 class="font-display text-xl font-bold">{{ $eventRingkasAkanDatang['judul'] }}</h3>
                    <p class="mt-1 text-[11px] text-gray-400">{{ $eventRingkasAkanDatang['info'] }}</p>

                    <a href="{{ route('dashboard-user') }}"
                        class="mt-4 inline-block rounded-md bg-[#c99a32] px-4 py-2 text-[11px] font-bold text-white hover:bg-[#b3882a]">
                        Daftar Sekarang
                    </a>
                </div>
            </div>

        </section>


        <!-- =====================================================
             OPSI FLEKSIBEL: SEWA ATAU BELI
        ====================================================== -->
        <section class="mt-16 text-center">

            <p class="text-[11px] font-semibold uppercase tracking-widest text-[#c99a32]">Transparansi Mutu</p>
            <h2 class="font-display mt-1 text-2xl font-bold text-[#15233d]">Opsi Fleksibel: Sewa atau Beli</h2>
            <p class="mx-auto mt-2 max-w-[520px] text-xs text-gray-500">
                Kejelasan komitmen higienitas, fitting presisi, dan garansi busana tanpa biaya tersembunyi.
            </p>

            <div class="mx-auto mt-6 grid max-w-[920px] grid-cols-1 gap-5 text-left md:grid-cols-2">

                <!-- Sewa -->
                <div class="card-shadow relative rounded-xl border border-[#eee5d2] bg-[#faf8f2] p-6">

                    <span class="absolute -top-3 left-6 rounded-full bg-[#c99a32] px-3 py-1 text-[9px] font-bold text-white">
                        PALING DIMINATI
                    </span>

                    <h3 class="font-display text-lg font-bold text-[#15233d]">Layanan Sewa Busana</h3>
                    <p class="mt-1 text-xs text-gray-500">
                        Solusi praktis dan hemat untuk akad nikah, resepsi, wisuda, atau pentas budaya.
                    </p>

                    <ul class="mt-4 space-y-2 text-xs text-gray-600">
                        <li class="flex gap-2"><span class="text-[#c99a32]">✓</span> Durasi Sewa: Fleksibel 3 sampai 7 hari kerja.</li>
                        <li class="flex gap-2"><span class="text-[#c99a32]">✓</span> Gratis Alteration: Penyesuaian ukuran pinggang & kancing aman.</li>
                        <li class="flex gap-2"><span class="text-[#c99a32]">✓</span> Termasuk Laundry: Busana kembali tidak perlu dicuci sendiri.</li>
                        <li class="flex gap-2"><span class="text-[#c99a32]">✓</span> Steril & Higienis: Ozon steaming dan garment bag anti debu.</li>
                    </ul>

                    <a href="#" class="mt-5 inline-block rounded-md bg-[#c99a32] px-5 py-2.5 text-xs font-bold text-white hover:bg-[#b3882a]">
                        Eksplorasi Katalog Sewa
                    </a>
                </div>

                <!-- Beli -->
                <div class="card-shadow rounded-xl border border-gray-100 bg-white p-6">

                    <h3 class="font-display text-lg font-bold text-[#15233d]">Beli &amp; Custom Bespoke</h3>
                    <p class="mt-1 text-xs text-gray-500">
                        Mahakarya eksklusif hak milik selamanya sebagai busana warisan keluarga.
                    </p>

                    <ul class="mt-4 space-y-2 text-xs text-gray-600">
                        <li class="flex gap-2"><span class="text-[#c99a32]">✓</span> Pola Tailoring Personal: Disesuaikan 100% dengan lekuk tubuh.</li>
                        <li class="flex gap-2"><span class="text-[#c99a32]">✓</span> Material Sutera & Songket Asli: Pilihan bahan tenun tangan terbaik.</li>
                        <li class="flex gap-2"><span class="text-[#c99a32]">✓</span> Sulam Emas Tradisional: Dikerjakan manual oleh perajin adibusana.</li>
                        <li class="flex gap-2"><span class="text-[#c99a32]">✓</span> Garansi Fitting Ulang: Revisi hingga nyaman dan pas dipakai.</li>
                    </ul>

                    <a href="#" class="mt-5 inline-block rounded-md bg-[#111111] px-5 py-2.5 text-xs font-bold text-white hover:bg-[#222]">
                        Konsultasi Bespoke
                    </a>
                </div>

            </div>
        </section>


        <!-- =====================================================
             OFFLINE ATELIER & STUDIO
        ====================================================== -->
        <section class="mt-16 grid grid-cols-1 items-center gap-8 lg:grid-cols-2">

            <div>
                <p class="text-[11px] font-semibold uppercase tracking-widest text-[#c99a32]">Galeri &amp; Ruang Fitting</p>
                <h2 class="font-display mt-1 text-2xl font-bold text-[#15233d]">Offline Atelier &amp; Studio ALNAY.ID</h2>
                <p class="mt-3 max-w-[420px] text-xs leading-relaxed text-gray-500">
                    Kunjungi studio kami untuk konsultasi langsung, sesi fitting presisi, dan melihat koleksi busana adat secara nyata sebelum menyewa atau membeli.
                </p>

                <div class="mt-5 flex flex-wrap gap-3">
                    <a href="#" class="rounded-md bg-[#111111] px-5 py-2.5 text-xs font-bold text-white hover:bg-[#222]">
                        Info Lokasi &amp; Booking Jadwal
                    </a>
                    <a href="#" class="rounded-md border border-gray-300 px-5 py-2.5 text-xs font-bold text-[#333] hover:bg-gray-50">
                        Panduan Sewa Online
                    </a>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-xl bg-[#17140f]">
                <img src="{{ asset('image/home/atelier.jpg') }}" alt="Atelier ALNAY.ID" class="h-[280px] w-full object-cover opacity-80">

                <div class="absolute bottom-4 left-4 right-4 flex items-center gap-3 rounded-lg bg-black/70 p-3 text-white">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded bg-[#c99a32] text-[11px] font-bold">AL</div>
                    <div>
                        <p class="text-xs font-bold">Atelier &amp; Fashion Etnik</p>
                        <p class="text-[10px] text-gray-300">
                            Jl. Manakarsa Adat No. 18, Bandung Wetan, Kecamatan Kemuning, Kota Bandung, Jawa Barat 15158 · Buka 09.00 - 19.00
                        </p>
                    </div>
                </div>
            </div>

        </section>


        <!-- =====================================================
             TESTIMONI
        ====================================================== -->
        <section class="mt-16">

            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-widest text-[#c99a32]">Kisah Bahagia Bersama ALNAY.ID</p>
                    <h2 class="font-display mt-1 text-2xl font-bold text-[#15233d]">Testimoni Pengantin &amp; Klien</h2>
                </div>

                <p class="text-xs text-gray-500">
                    Kepuasan ★ 4.97 dari 1.800+ Acara
                </p>
            </div>

            @if(count($testimoni) > 0)

                <div class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-3">

                    @foreach($testimoni as $t)

                        <div class="card-shadow rounded-xl border border-gray-100 bg-white p-5">

                            <p class="text-[#c99a32]">★★★★★</p>

                            <p class="mt-3 text-xs leading-relaxed text-gray-600">
                                "{{ $t['pesan'] }}"
                            </p>

                            <div class="mt-4 flex items-center gap-3">
                                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-[#fbf6e9] text-[11px] font-bold text-[#c99a32]">
                                    {{ $t['inisial'] }}
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-[#15233d]">{{ $t['nama'] }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $t['lokasi'] }}</p>
                                </div>
                            </div>

                        </div>

                    @endforeach

                </div>

            @endif

        </section>

    </main>


    <!-- =========================================================
         FOOTER
    ========================================================== -->
    <footer class="mt-16 bg-[#111111] text-white">
        <div class="mx-auto max-w-[1280px] px-5 py-12 md:px-8">

            <div class="grid grid-cols-1 gap-10 border-b border-[#2a2a2a] pb-10 md:grid-cols-[1.5fr_1fr_1fr_1.15fr]">

                <div>
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded bg-white text-[11px] font-bold text-black">A</div>
                        <div>
                            <div class="font-bold tracking-[.12em]">ALNAY.ID</div>
                            <div class="text-[8px] tracking-[.15em] text-[#c9962e]">JAS &amp; GOWN BERBUDAYA</div>
                        </div>
                    </div>

                    <p class="mt-4 max-w-[330px] text-[10px] leading-5 text-[#858585]">
                        Platform penyewaan dan bespoke atelier busana adat Nusantara nomor satu di Indonesia. Menghadirkan hakikatnya busana adat dengan standar kualitas tailoring adibusana.
                    </p>

                    <div class="mt-4 flex gap-2">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#242424] text-[10px]">◎</span>
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#242424] text-[10px]">◉</span>
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#242424] text-[10px]">f</span>
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#242424] text-[10px]">▶</span>
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#242424] text-[10px]">●</span>
                    </div>
                </div>

                <div>
                    <h3 class="text-[10px] font-semibold tracking-[.14em]">KOLEKSI ADAT</h3>
                    <ul class="mt-4 space-y-2 text-[10px] text-[#858585]">
                        <li>Jas Beskap Pria</li>
                        <li>Gown Kebaya Pengantin</li>
                        <li>Set Baju Pasangan</li>
                        <li>Mahkota & Suntingan</li>
                        <li>Tenun Songket Asli</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-[10px] font-semibold tracking-[.14em]">BUTUH BANTUAN?</h3>
                    <div class="mt-4 space-y-2 text-[10px] text-[#858585]">
                        <p>Senin - Jumat: 08:00 - 20:00 WIB</p>
                        <p>Sabtu - Minggu: 09:00 - 18:00 WIB</p>
                        <button class="rounded border border-[#9b762d] px-3 py-1.5 text-[9px] text-[#d4a63d]">Chat WhatsApp CS</button>
                        <p class="pt-1 leading-4">Jl. Manakarsa Adat No. 18, Bandung Wetan, Jawa Barat</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-[10px] font-semibold tracking-[.14em]">LAYANAN &amp; INFORMASI</h3>
                    <ul class="mt-4 space-y-2 text-[10px] text-[#858585]">
                        <li>Cara & Syarat Sewa Busana</li>
                        <li>Aturan Deposit & Asuransi</li>
                        <li>Jadwal Fitting Studio</li>
                        <li>Panduan Ukur Badan Online</li>
                        <li>Pemesanan Custom Bespoke</li>
                    </ul>
                </div>
            </div>

            <div class="flex flex-col gap-7 border-b border-[#2a2a2a] py-7 md:flex-row md:items-center md:justify-between">
                <div>
                    <div class="text-[8px] text-[#777]">METODE PEMBAYARAN RESMI</div>
                    <div class="mt-2 flex flex-wrap gap-2">
                        <span class="rounded border border-[#444] px-3 py-1 text-[8px] text-[#aaa]">BCA</span>
                        <span class="rounded border border-[#444] px-3 py-1 text-[8px] text-[#aaa]">MANDIRI</span>
                        <span class="rounded border border-[#444] px-3 py-1 text-[8px] text-[#aaa]">BNI</span>
                        <span class="rounded border border-[#444] px-3 py-1 text-[8px] text-[#aaa]">BRI</span>
                        <span class="rounded border border-[#444] px-3 py-1 text-[8px] text-[#aaa]">QRIS</span>
                        <span class="rounded border border-[#444] px-3 py-1 text-[8px] text-[#aaa]">VISA / MASTER</span>
                    </div>
                </div>

                <div class="md:text-right">
                    <div class="text-[8px] text-[#777]">EKSPEDISI &amp; KURIR</div>
                    <div class="mt-2 flex flex-wrap justify-start gap-2 md:justify-end">
                        <span class="rounded border border-[#444] px-3 py-1 text-[8px] text-[#aaa]">JNE YES</span>
                        <span class="rounded border border-[#444] px-3 py-1 text-[8px] text-[#aaa]">GoSend Instant</span>
                        <span class="rounded border border-[#444] px-3 py-1 text-[8px] text-[#aaa]">GrabExpress</span>
                        <span class="rounded border border-[#444] px-3 py-1 text-[8px] text-[#aaa]">Paxel Kargo</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-3 pt-5 text-[9px] text-[#626262] md:flex-row md:items-center md:justify-between">
                <div>© 2026 ALNAY.ID (Jas & Gown Berbudaya). All Rights Reserved.</div>
                <div class="flex flex-wrap gap-5">
                    <span>Kebijakan Privasi</span>
                    <span>Syarat &amp; Ketentuan Sewa</span>
                    <span>Keagungan Adat Nusantara</span>
                </div>
            </div>

        </div>
    </footer>

</body>
</html>