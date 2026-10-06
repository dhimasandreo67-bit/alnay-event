<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koleksi Produk - ALNAY.ID</title>

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
         DATA KATALOG PRODUK
    ========================================================== -->

    @php

        // =====================================================
        // OPSI 1 — DATA MANUAL / STATIS (AKTIF)
        // Dipakai selama tahap pengembangan UI/UX, belum ada DB.
        // Field 'id' disertakan sejak sekarang supaya nanti saat
        // Opsi 2 (database) aktif, link ke detail produk tinggal
        // dipasangi ID tanpa perlu mengubah struktur di bawah.
        // =====================================================

        // User yang sedang login, dipakai untuk avatar di header.
        $user = [
            'nama' => 'Perrell Brown',
            'foto' => null, // null → tampil inisial
        ];

        $inisialUser = collect(explode(' ', $user['nama']))->map(fn($k) => strtoupper($k[0]))->take(2)->implode('');

        // Daftar kategori untuk pill filter & sidebar (dengan jumlah produk per kategori)
        $kategoriFilter = [
            ['id' => 1, 'nama' => 'Jas & Beskap',    'jumlah' => 42],
            ['id' => 2, 'nama' => 'Gown & Kebaya',    'jumlah' => 36],
            ['id' => 3, 'nama' => 'Dress',             'jumlah' => 18],
            ['id' => 4, 'nama' => 'Aksesoris',         'jumlah' => 21],
            ['id' => 5, 'nama' => 'Tenun & Songket',  'jumlah' => 7],
            ['id' => 6, 'nama' => 'Kemeja Batik',      'jumlah' => 4],
        ];

        // Pilihan rentang harga untuk sidebar
        $rentangHarga = [
            ['id' => 'bawah-1jt',  'label' => 'Di bawah Rp 1.000.000'],
            ['id' => '1jt-3jt',    'label' => 'Rp 1.000.000 – 3.000.000'],
            ['id' => '3jt-5jt',    'label' => 'Rp 3.000.000 – 5.000.000'],
            ['id' => 'atas-5jt',   'label' => 'Di atas Rp 5.000.000'],
        ];

        // Pilihan warna untuk sidebar
        $warnaFilter = ['#7a1f2b', '#1a1a1a', '#c99a32', '#e8ddc8', '#2f5c3c', '#6b4a2a'];

        // Filter yang sedang aktif (sementara statis, nanti dibaca dari request()->query(...))
        $filterAktif = [
            'jenis_transaksi' => ['bisa-disewa'],
            'kategori'        => [1], // id kategori "Jas & Beskap"
            'rentang_harga'   => '1jt-3jt',
            'urutan'          => 'Terbaru',
        ];

        $totalProduk  = 128;
        $halamanAktif = 1;
        $totalHalaman = 11;

        // Daftar produk halaman ini
        $produkKatalog = [
            [
                'id' => 1, 'nama' => 'Long Dress Hitam Marun', 'deskripsi' => 'Kebaya ekor bordir emas & mahkota',
                'foto' => 'produk1.jpg', 'badge' => 'Terjual', 'tag' => 'Sewa & Beli',
                'harga_sewa' => 'Rp 2.500.000 /3h', 'harga_beli' => 'Rp 9.500.000', 'mode' => 'dua',
            ],
            [
                'id' => 2, 'nama' => 'Jas Hitam Tenun Gold', 'deskripsi' => 'Beskap furing sutera & kain tenun',
                'foto' => 'produk2.jpg', 'badge' => 'Tersedia', 'tag' => 'Sewa & Beli',
                'harga_sewa' => 'Rp 1.800.000 /3h', 'harga_beli' => 'Rp 5.900.000', 'mode' => 'dua',
            ],
            [
                'id' => 3, 'nama' => 'Gown Merah Maroon Pengantin', 'deskripsi' => 'Lengkap jas, gaun, mahkota, dan selop',
                'foto' => 'produk3.jpg', 'badge' => 'Disewa', 'tag' => 'Khusus Sewa',
                'harga_sewa' => 'Rp 3.500.000 /3h', 'harga_beli' => null, 'status_jual' => 'Tidak Dijual (Koleksi Sewa)', 'mode' => 'satu',
            ],
            [
                'id' => 4, 'nama' => 'Mahkota Suntiang Padang Gold', 'deskripsi' => 'Sepuh emas asli dengan kembang goyang',
                'foto' => 'produk4.jpg', 'badge' => 'Tersedia', 'tag' => 'Khusus Sewa',
                'harga_sewa' => 'Rp 850.000 /3h', 'harga_beli' => null, 'status_jual' => 'Tidak Dijual (Koleksi Sewa)', 'mode' => 'satu',
            ],
            [
                'id' => 5, 'nama' => 'Set Pasangan Sunda Siger Emas', 'deskripsi' => 'Kebaya payet & beskap kancing kuningan',
                'foto' => 'produk5.jpg', 'badge' => 'Tersedia', 'tag' => 'Sewa & Beli',
                'harga_sewa' => 'Rp 3.200.000 /3h', 'harga_beli' => 'Rp 11.400.000', 'mode' => 'dua',
            ],
            [
                'id' => 6, 'nama' => 'Jas Beskap Beludru Jawa Klasik', 'deskripsi' => 'Bordir ornamen garuda & jarik prada',
                'foto' => 'produk6.jpg', 'badge' => 'Tersedia', 'tag' => 'Sewa & Beli',
                'harga_sewa' => 'Rp 1.600.000 /3h', 'harga_beli' => 'Rp 4.900.000', 'mode' => 'dua',
            ],
            [
                'id' => 7, 'nama' => 'Gaun Pengantin Brokat Payet', 'deskripsi' => 'Jubah veil renda 3 meter & korset presisi',
                'foto' => 'produk7.jpg', 'badge' => 'Terjual', 'tag' => 'Sewa & Beli',
                'harga_sewa' => 'Rp 2.400.000 /3h', 'harga_beli' => 'Rp 8.100.000', 'mode' => 'dua',
            ],
            [
                'id' => 8, 'nama' => 'Set Aksesoris Tanjak & Keris Palembang', 'deskripsi' => 'Ornamen adat Melayu & Palembang',
                'foto' => 'produk8.jpg', 'badge' => 'Tersedia', 'tag' => 'Khusus Sewa',
                'harga_sewa' => 'Rp 750.000 /3h', 'harga_beli' => null, 'status_jual' => 'Tidak Dijual (Koleksi Sewa)', 'mode' => 'satu',
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

        $user = [
            'nama' => auth()->user()->name,
            'foto' => auth()->user()->foto,
        ];
        $inisialUser = collect(explode(' ', $user['nama']))->map(fn($k) => strtoupper($k[0]))->take(2)->implode('');

        $qKategori = $pdo->query("SELECT kategori_produk.*, COUNT(produk.id) AS jumlah
                                   FROM kategori_produk
                                   LEFT JOIN produk ON produk.kategori_id = kategori_produk.id
                                   GROUP BY kategori_produk.id ORDER BY kategori_produk.urutan ASC");
        $kategoriFilter = [];
        foreach ($qKategori->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $kategoriFilter[] = ['id' => $row['id'], 'nama' => $row['nama'], 'jumlah' => $row['jumlah']];
        }

        // Filter aktif dibaca dari query string halaman ini, misal:
        // /koleksi?kategori[]=1&harga=1jt-3jt&transaksi[]=bisa-disewa&urutan=terbaru&halaman=1
        $filterAktif = [
            'jenis_transaksi' => request()->query('transaksi', []),
            'kategori'        => request()->query('kategori', []),
            'rentang_harga'   => request()->query('harga'),
            'urutan'          => request()->query('urutan', 'terbaru'),
        ];
        $halamanAktif = (int) request()->query('halaman', 1);
        $perHalaman   = 12;

        $where  = [];
        $params = [];

        if (!empty($filterAktif['kategori'])) {
            $in = implode(',', array_fill(0, count($filterAktif['kategori']), '?'));
            $where[] = "kategori_id IN ($in)";
            $params  = array_merge($params, $filterAktif['kategori']);
        }

        if (in_array('bisa-disewa', $filterAktif['jenis_transaksi'])) {
            $where[] = "bisa_disewa = 1";
        }
        if (in_array('bisa-beli', $filterAktif['jenis_transaksi'])) {
            $where[] = "bisa_dibeli = 1";
        }

        $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $orderSql = $filterAktif['urutan'] === 'terlaris' ? 'ORDER BY jumlah_ulasan DESC' : 'ORDER BY created_at DESC';

        $qTotal = $pdo->prepare("SELECT COUNT(*) FROM produk $whereSql");
        $qTotal->execute($params);
        $totalProduk  = (int) $qTotal->fetchColumn();
        $totalHalaman = (int) ceil($totalProduk / $perHalaman);

        $offset = ($halamanAktif - 1) * $perHalaman;
        $qProduk = $pdo->prepare("SELECT * FROM produk $whereSql $orderSql LIMIT $perHalaman OFFSET $offset");
        $qProduk->execute($params);

        $produkKatalog = [];
        foreach ($qProduk->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $produkKatalog[] = [
                'id'          => $row['id'],
                'nama'        => $row['nama'],
                'deskripsi'   => $row['deskripsi'],
                'foto'        => $row['foto'],
                'badge'       => $row['status_stok'],
                'tag'         => $row['tipe_layanan'],
                'harga_sewa'  => 'Rp ' . number_format($row['harga_sewa'], 0, ',', '.') . ' /3h',
                'harga_beli'  => $row['harga_beli'] ? 'Rp ' . number_format($row['harga_beli'], 0, ',', '.') : null,
                'status_jual' => $row['harga_beli'] ? null : 'Tidak Dijual (Koleksi Sewa)',
                'mode'        => $row['harga_beli'] ? 'dua' : 'satu',
            ];
        }
        */

        // Path foto:
        // - Opsi 1 (statis)  : public/image/produk/{foto}
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
            <a href="{{ route('beranda') }}" class="flex items-center gap-2 shrink-0">
                <span class="font-display text-[20px] font-bold text-[#13223a]">ALNAY.ID</span>
                <span class="hidden text-[9px] font-semibold tracking-widest text-[#c99a32] sm:inline">
                    JAS &amp; GOWN<br>BERBUDAYA
                </span>
            </a>

            <!-- Search -->
            <div class="order-3 w-full sm:order-2 sm:w-auto sm:flex-1">
                <div class="relative">
                    <input type="text" placeholder="Cari jas adat, gown pengantin, siger, beskap..."
                        class="h-11 w-full rounded-full border border-gray-200 bg-[#faf9f6] pl-5 pr-11 text-sm focus:outline-none focus:ring-1 focus:ring-[#c99a32]">
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
                </div>
            </div>

            <!-- Actions -->
            <div class="order-2 flex shrink-0 items-center gap-4 sm:order-3 text-[13px] font-medium">

                <a href="#" class="relative flex items-center gap-1.5 text-[#333]" aria-label="Keranjang">
                    <span class="relative">
                        🛍️
                        <span class="absolute -right-2 -top-2 flex h-4 w-4 items-center justify-center rounded-full bg-[#c99a32] text-[9px] font-bold text-white">2</span>
                    </span>
                    <span class="hidden sm:inline">Keranjang</span>
                </a>

                <a href="#" class="text-[#333]" aria-label="Notifikasi">🔔</a>

                <a href="{{ route('profil-user') }}" class="flex items-center gap-2 text-[#15233d]">

                    @if($user['foto'])
                        <img src="{{ asset('storage/profil/' . $user['foto']) }}" alt="{{ $user['nama'] }}"
                            class="h-8 w-8 rounded-full object-cover">
                    @else
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#15233d] text-[11px] font-bold text-white">
                            {{ $inisialUser }}
                        </span>
                    @endif

                    <span class="hidden text-xs font-bold uppercase tracking-wide sm:inline">
                        {{ $user['nama'] }}
                    </span>

                    <span class="hidden text-gray-400 sm:inline">▾</span>
                </a>

            </div>
        </div>

        <!-- Nav -->
        <nav class="border-t border-[#f1f1f1] bg-[#fdfcf9]">
            <div class="mx-auto flex max-w-[1280px] flex-wrap items-center justify-center gap-x-7 gap-y-2 px-5 py-2.5 text-[12px] font-semibold text-[#555] md:px-8">
                <a href="{{ route('beranda') }}" class="flex items-center gap-1.5 hover:text-[#c99a32]">🏠 Beranda</a>
                <a href="{{ route('katalog-produk') }}" class="flex items-center gap-1.5 text-[#c99a32]">🏷️ Koleksi ▾</a>
                <a href="#" class="flex items-center gap-1.5 hover:text-[#c99a32]">👤 Cara Sewa</a>
                <a href="#" class="flex items-center gap-1.5 hover:text-[#c99a32]">ⓘ Tentang Kami</a>
                <a href="#" class="flex items-center gap-1.5 hover:text-[#c99a32]">★ Testimoni</a>
                <a href="{{ route('dashboard-user') }}" class="flex items-center gap-1.5 hover:text-[#c99a32]">📅 Event</a>
                <a href="{{ route('riwayat-vote') }}" class="flex items-center gap-1.5 hover:text-[#c99a32]">↻ Riwayat</a>
            </div>
        </nav>
    </header>


    <!-- =========================================================
         JUDUL HALAMAN + PILL KATEGORI
    ========================================================== -->
    <section class="bg-[#f3efe4]">
        <div class="mx-auto max-w-[1280px] px-5 py-8 md:px-8">

            <h1 class="font-display text-[32px] font-bold text-[#111827]">Koleksi Produk</h1>

            <p class="mt-2 text-sm text-gray-500">
                {{ $totalProduk }} busana adat siap sewa maupun beli — cari berdasarkan kategori, warna, atau harga.
            </p>

            <div class="mt-5 flex flex-wrap gap-2">

                <a href="{{ route('katalog-produk') }}"
                    class="rounded-full bg-[#111111] px-4 py-2 text-xs font-semibold text-white">
                    Semua
                </a>

                @foreach($kategoriFilter as $k)
                    <a href="{{ route('katalog-produk', ['kategori' => $k['id']]) }}"
                        class="rounded-full border border-gray-200 bg-white px-4 py-2 text-xs font-semibold text-[#333] transition hover:border-[#c99a32] hover:text-[#c99a32]">
                        {{ $k['nama'] }}
                    </a>
                @endforeach

            </div>

        </div>
    </section>


    <main class="mx-auto max-w-[1280px] px-5 py-8 md:px-8">

        <div class="flex flex-col gap-8 lg:flex-row">

            <!-- =================================================
                 SIDEBAR FILTER
            ================================================== -->

            <aside class="w-full shrink-0 lg:w-[270px]">

                <div class="card-shadow rounded-xl border border-gray-100 bg-white p-5">

                    <!-- Jenis Transaksi -->
                    <div>
                        <h3 class="text-xs font-bold text-[#15233d]">Jenis Transaksi</h3>

                        <label class="mt-3 flex items-center gap-2 text-xs text-[#444]">
                            <input type="checkbox" class="h-4 w-4 accent-[#c99a32]"
                                {{ in_array('bisa-disewa', $filterAktif['jenis_transaksi']) ? 'checked' : '' }}>
                            Bisa Disewa
                        </label>

                        <label class="mt-2 flex items-center gap-2 text-xs text-[#444]">
                            <input type="checkbox" class="h-4 w-4 accent-[#c99a32]"
                                {{ in_array('bisa-beli', $filterAktif['jenis_transaksi']) ? 'checked' : '' }}>
                            Bisa Beli/Disewa
                        </label>
                    </div>

                    <!-- Kategori -->
                    <div class="mt-6 border-t border-gray-100 pt-5">
                        <h3 class="text-xs font-bold text-[#15233d]">Kategori</h3>

                        @foreach($kategoriFilter as $k)
                            <label class="mt-3 flex items-center justify-between gap-2 text-xs text-[#444]">
                                <span class="flex items-center gap-2">
                                    <input type="checkbox" class="h-4 w-4 accent-[#c99a32]"
                                        {{ in_array($k['id'], $filterAktif['kategori']) ? 'checked' : '' }}>
                                    {{ $k['nama'] }}
                                </span>
                                <span class="text-gray-400">{{ $k['jumlah'] }}</span>
                            </label>
                        @endforeach
                    </div>

                    <!-- Rentang Harga -->
                    <div class="mt-6 border-t border-gray-100 pt-5">
                        <h3 class="text-xs font-bold text-[#15233d]">Rentang Harga</h3>

                        <div class="mt-3 space-y-2">
                            @foreach($rentangHarga as $r)
                                <button type="button" @class([
                                    'block w-full rounded-md border px-3 py-2 text-left text-xs',
                                    'border-[#c99a32] bg-[#fbf6e9] text-[#9a7a28] font-semibold' => $filterAktif['rentang_harga'] === $r['id'],
                                    'border-gray-200 text-[#444] hover:border-gray-300' => $filterAktif['rentang_harga'] !== $r['id'],
                                ])>
                                    {{ $r['label'] }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Warna -->
                    <div class="mt-6 border-t border-gray-100 pt-5">
                        <h3 class="text-xs font-bold text-[#15233d]">Warna</h3>

                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach($warnaFilter as $w)
                                <button type="button" class="h-6 w-6 rounded-full border border-gray-200"
                                    style="background:{{ $w }}" aria-label="Warna {{ $w }}"></button>
                            @endforeach
                        </div>
                    </div>

                    <a href="{{ route('katalog-produk') }}"
                        class="mt-6 block w-full rounded-md border border-gray-200 bg-[#faf9f6] py-2.5 text-center text-xs font-semibold text-[#444] hover:bg-gray-100">
                        Reset Semua Filter
                    </a>

                </div>

            </aside>


            <!-- =================================================
                 HASIL PRODUK
            ================================================== -->

            <div class="flex-1">

                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                    <div class="flex items-center gap-3 text-xs text-gray-500">
                        <span class="flex items-center gap-1.5 font-semibold text-[#15233d]">▤ Filter</span>
                        <span>Menampilkan {{ min(count($produkKatalog), $totalProduk) }} dari {{ $totalProduk }} produk</span>
                    </div>

                    <div class="text-xs text-gray-500">
                        Urutkan: <span class="font-semibold text-[#15233d]">{{ $filterAktif['urutan'] }} ▾</span>
                    </div>

                </div>

                @if(count($produkKatalog) > 0)

                    <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

                        @foreach($produkKatalog as $p)

                            <div class="card-shadow overflow-hidden rounded-lg border border-gray-100 bg-white">

                                <div class="relative h-[200px] bg-gray-100">
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
                                        @elseif(!empty($p['status_jual']))
                                            <div class="flex justify-between">
                                                <span class="text-gray-400">Status:</span>
                                                <span class="font-semibold text-[#15233d]">{{ $p['status_jual'] }}</span>
                                            </div>
                                        @endif
                                    </div>

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


                    <!-- =============================================
                         PAGINATION
                    ============================================== -->

                    <div class="mt-8 flex flex-wrap items-center justify-center gap-2 text-xs">

                        <a href="{{ route('katalog-produk', ['halaman' => max($halamanAktif - 1, 1)]) }}"
                            class="rounded-md border border-gray-200 bg-white px-3 py-2 font-semibold text-[#444] hover:bg-gray-50">
                            ← Sebelumnya
                        </a>

                        @for($i = 1; $i <= min(3, $totalHalaman); $i++)
                            <a href="{{ route('katalog-produk', ['halaman' => $i]) }}" @class([
                                'h-9 w-9 flex items-center justify-center rounded-md font-semibold',
                                'bg-[#111111] text-white' => $i === $halamanAktif,
                                'border border-gray-200 bg-white text-[#444] hover:bg-gray-50' => $i !== $halamanAktif,
                            ])>
                                {{ $i }}
                            </a>
                        @endfor

                        @if($totalHalaman > 4)
                            <span class="px-1 text-gray-400">…</span>
                            <a href="{{ route('katalog-produk', ['halaman' => $totalHalaman]) }}"
                                class="h-9 w-9 flex items-center justify-center rounded-md border border-gray-200 bg-white font-semibold text-[#444] hover:bg-gray-50">
                                {{ $totalHalaman }}
                            </a>
                        @endif

                        <a href="{{ route('katalog-produk', ['halaman' => min($halamanAktif + 1, $totalHalaman)]) }}"
                            class="rounded-md border border-gray-200 bg-white px-3 py-2 font-semibold text-[#444] hover:bg-gray-50">
                            Selanjutnya →
                        </a>

                    </div>

                @else

                    <div class="mt-5 rounded-lg border border-dashed border-gray-200 bg-white p-14 text-center">
                        <p class="text-sm font-semibold text-[#15233d]">Tidak ada produk yang cocok</p>
                        <p class="mt-1 text-xs text-gray-400">Coba ubah atau reset filter yang kamu pilih.</p>

                        <a href="{{ route('katalog-produk') }}"
                            class="mt-4 inline-block rounded-md bg-[#c99a32] px-5 py-2.5 text-xs font-semibold text-white hover:bg-[#b3882a]">
                            Reset Filter
                        </a>
                    </div>

                @endif

            </div>

        </div>

    </main>


    <!-- =========================================================
         FOOTER
    ========================================================== -->
    <footer class="mt-6 bg-[#111111] text-white">
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
                        Platform penyewaan dan bespoke atelier busana adat Nusantara nomor satu di Indonesia. Menghadirkan mahakarya budaya dengan standar kualitas tailoring internasional.
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
                        <li>Mahkota & Suntiang</li>
                        <li>Tenun Songket Asli</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-[10px] font-semibold tracking-[.14em]">BUTUH BANTUAN?</h3>
                    <div class="mt-4 space-y-2 text-[10px] text-[#858585]">
                        <p>Senin - Jumat: 08:00 - 20:00 WIB</p>
                        <p>Sabtu - Minggu: 09:00 - 18:00 WIB</p>
                        <button class="rounded border border-[#9b762d] px-3 py-1.5 text-[9px] text-[#d4a63d]">Chat WhatsApp CS</button>
                        <p class="pt-1 leading-4">Jl. Mahakarya Adat No. 18, Bandung Wetan, Jawa Barat</p>
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
                    <div class="text-[8px] text-[#777]">EKSPEDISI KHUSUS &amp; KURIR AMAN</div>
                    <div class="mt-2 flex flex-wrap justify-start gap-2 md:justify-end">
                        <span class="rounded border border-[#444] px-3 py-1 text-[8px] text-[#aaa]">JNE YES</span>
                        <span class="rounded border border-[#444] px-3 py-1 text-[8px] text-[#aaa]">GoSend Instant</span>
                        <span class="rounded border border-[#444] px-3 py-1 text-[8px] text-[#aaa]">GrabExpress</span>
                        <span class="rounded border border-[#444] px-3 py-1 text-[8px] text-[#aaa]">Paxel Kargo</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-3 pt-5 text-[9px] text-[#626262] md:flex-row md:items-center md:justify-between">
                <div>© 2026 AL-NAY.ID (Jas & Gown Berbudaya). All Rights Reserved.</div>
                <div class="flex flex-wrap gap-5">
                    <span>Kebijakan Privasi</span>
                    <span>Syarat &amp; Ketentuan Sewa</span>
                    <span>Keanggunan Adat Nusantara</span>
                </div>
            </div>

        </div>
    </footer>

</body>
</html>