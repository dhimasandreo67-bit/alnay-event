<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detail Produk (Beli) - ALNAY.ID</title>

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
         DATA PRODUK, PENGGUNA & MENU
    ========================================================== -->

    @php

        // =====================================================
        // OPSI 1 — DATA MANUAL / STATIS (AKTIF)
        // Dipakai selama tahap pengembangan UI/UX, belum ada DB.
        // Field 'id' disertakan sejak sekarang supaya nanti saat
        // Opsi 2 (database) aktif, tidak perlu mengubah struktur
        // di bagian HTML.
        //
        // - 'foto' pada galeri boleh null: otomatis tampil panel
        //   placeholder berwarna sesuai field 'warna'.
        // - 'stok' = 0 pada varian berarti "Stok Habis" dan
        //   varian itu tidak bisa dipilih.
        // =====================================================

        $produk = [
            'id'            => 1,
            'nama'          => 'Set Pasangan Sunda Siger Emas',
            'subjudul'      => 'Adibusana Kebaya Payet Bordir Tangan & Beskap Pria Kancing Kuningan',
            'label'         => 'Sewa & Beli',
            'status'        => 'Tersedia',
            'rating'        => 5.0,
            'jumlah_ulasan' => 340,
            'jumlah_disewa' => '1.200+',
            'harga'         => 3200000,
            'termasuk'      => 'kebaya, beskap, siger, selop, dan aksesoris lengkap',
            'kategori'      => 'Gown & Kebaya Adat Sunda',
            'material'      => 'Beludru, brokat payet, kain prada',
            'ukuran'        => ['S', 'M', 'L', 'XL', 'Custom'],
            'deskripsi'     => [
                'Set pasangan busana adat Sunda ini terdiri dari kebaya payet lengkap dengan bordir tangan dan beskap pria dengan kancing kuningan asli. Dirancang untuk prosesi akad maupun resepsi, dengan siger emas dan aksesoris pelengkap yang menghadirkan kesan agung namun tetap nyaman dipakai seharian.',
                'Setiap busana melalui proses steam sterilisasi dan pemeriksaan jahitan sebelum dikirim, sehingga siap pakai begitu diterima.',
            ],
            'varian'        => [
                ['nama' => 'Emas Klasik',   'warna' => '#b8892a', 'label' => 'Terlaris', 'stok' => 12],
                ['nama' => 'Merah Marun',   'warna' => '#7a1424', 'label' => null,       'stok' => 8],
                ['nama' => 'Hitam Polos',   'warna' => '#16161a', 'label' => null,       'stok' => 5],
                ['nama' => 'Motif Songket', 'warna' => '#b3b3b8', 'label' => null,       'stok' => 0],
                ['nama' => 'Biru Navy',     'warna' => '#8a93a6', 'label' => null,       'stok' => 0],
            ],
            'galeri'        => [
                ['foto' => null, 'warna' => '#2e2410'],
                ['foto' => null, 'warna' => '#2b2010'],
                ['foto' => null, 'warna' => '#6e1423'],
                ['foto' => null, 'warna' => '#14302b'],
            ],
        ];

        // Pengguna yang sedang login (untuk header)
        $pengguna = [
            'nama'       => 'Perrell Brown',
            'foto'       => null, // null = tampilkan inisial
            'keranjang'  => 2,
            'notifikasi' => true,
        ];

        /*
        // =====================================================
        // OPSI 2 — DATA DINAMIS DARI DATABASE (NONAKTIF)
        // Aktifkan blok ini (dan comment Opsi 1 di atas) setelah
        // database & halaman admin sudah tersedia.
        // Data tetap dirapikan ke bentuk array yang sama seperti
        // Opsi 1, supaya bagian HTML di bawah tidak perlu diubah.
        //
        // Catatan:
        // - ukuran disimpan sebagai teks dipisah koma ("S,M,L,XL,Custom")
        // - deskripsi disimpan sebagai teks, paragraf dipisah baris kosong
        // - varian dan foto galeri diambil dari tabel terpisah
        //
        // $produk bisa bernilai null kalau ID tidak ditemukan di
        // database (dicek lewat @if($produk) di bagian HTML).
        // =====================================================

        $produk_id = request()->route('id'); // dari route('detail-produk-beli', $id)

        $q_produk = $pdo->prepare("SELECT * FROM produk WHERE id = ?");
        $q_produk->execute([$produk_id]);
        $row_produk = $q_produk->fetch(PDO::FETCH_ASSOC);

        $produk = $row_produk ? [
            'id'            => $row_produk['id'],
            'nama'          => $row_produk['nama'],
            'subjudul'      => $row_produk['subjudul'],
            'label'         => $row_produk['label'],
            'status'        => $row_produk['stok_total'] > 0 ? 'Tersedia' : 'Habis',
            'rating'        => $row_produk['rating'],
            'jumlah_ulasan' => $row_produk['jumlah_ulasan'],
            'jumlah_disewa' => $row_produk['jumlah_disewa'],
            'harga'         => $row_produk['harga_beli'],
            'termasuk'      => $row_produk['termasuk'],
            'kategori'      => $row_produk['kategori'],
            'material'      => $row_produk['material'],
            'ukuran'        => array_map('trim', explode(',', $row_produk['ukuran'])),
            'deskripsi'     => array_values(array_filter(array_map('trim', preg_split('/\R{2,}/', (string) $row_produk['deskripsi'])))),
            'varian'        => [],
            'galeri'        => [],
        ] : null;

        if ($produk) {

            $q_varian = $pdo->prepare("SELECT * FROM produk_varian WHERE produk_id = ? ORDER BY urutan ASC");
            $q_varian->execute([$produk['id']]);

            foreach ($q_varian->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $produk['varian'][] = [
                    'nama'  => $row['nama'],
                    'warna' => $row['kode_warna'],
                    'label' => $row['label'], // null kalau kosong
                    'stok'  => (int) $row['stok'],
                ];
            }

            $q_galeri = $pdo->prepare("SELECT * FROM produk_foto WHERE produk_id = ? ORDER BY urutan ASC");
            $q_galeri->execute([$produk['id']]);

            foreach ($q_galeri->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $produk['galeri'][] = [
                    'foto'  => $row['foto'], // null kalau kolomnya kosong
                    'warna' => $row['warna_placeholder'] ?? '#2a2010',
                ];
            }
        }

        $authUser = auth()->user();

        $pengguna = [
            'nama'       => $authUser->username ?? $authUser->name,
            'foto'       => $authUser->foto ? asset('storage/' . $authUser->foto) : null,
            'keranjang'  => 0, // ganti dengan jumlah item keranjang pengguna
            'notifikasi' => false,
        ];
        */

        // Path foto:
        // - Opsi 1 (statis)  : public/image/produk/{foto}
        // - Opsi 2 (database, upload admin via storage) sebaiknya pakai:
        //   asset('storage/produk/' . $foto)
        //   dengan syarat sudah menjalankan `php artisan storage:link`.


        // =====================================================
        // INFORMASI TETAP (promo, studio, menu, pengiriman)
        // Ganti '#' dengan route() halaman masing-masing.
        // =====================================================

        $info = [
            'promo'  => 'Promo Eksklusif Pernikahan 2026: Diskon Sewa hingga 20% & Gratis Fitting Studio.',
            'studio' => 'Studio: Senin - Sabtu (09.00 - 19.00 WIB)',
            'wa_cs'  => 'WA CS: 0812-3456-7890',
        ];

        $url = [
            'sewa'     => '#', // halaman detail produk versi "Sewa Busana"
            'keranjang' => '#',
            'koleksi'  => '#',
            'panduan'  => '#', // Panduan Ukur Mandiri
        ];

        $menu = [
            ['label' => 'Beranda',      'url' => '#', 'dropdown' => false, 'icon' => 'M3 10.5 12 3l9 7.5V21H3z'],
            ['label' => 'Koleksi',      'url' => '#', 'dropdown' => true,  'icon' => 'M12 3a2 2 0 0 1 2 2c0 1-1 1.5-2 2M12 7 3 14h18z'],
            ['label' => 'Cara Sewa',    'url' => '#', 'dropdown' => false, 'icon' => 'M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM4 21a8 8 0 0 1 16 0'],
            ['label' => 'Tentang Kami', 'url' => '#', 'dropdown' => false, 'icon' => 'M12 22a10 10 0 1 0 0-20 10 10 0 0 0 0 20zM9.5 9a2.5 2.5 0 0 1 5 0c0 1.5-2.5 2-2.5 3.5M12 17h.01'],
            ['label' => 'Testimoni',    'url' => '#', 'dropdown' => false, 'icon' => 'm12 3 2.6 5.6 6.1.7-4.5 4.2 1.2 6L12 16.6 6.6 19.5l1.2-6L3.3 9.3l6.1-.7z'],
            ['label' => 'Event',        'url' => '#', 'dropdown' => false, 'icon' => 'M20.6 13.4 13.4 20.6a2 2 0 0 1-2.8 0L3 13V3h10l7.6 7.6a2 2 0 0 1 0 2.8zM7.5 7.5h.01'],
            ['label' => 'Riwayat',      'url' => '#', 'dropdown' => false, 'icon' => 'M3 12a9 9 0 1 0 3-6.7L3 8M3 3v5h5M12 7v5l3 2'],
        ];

        $pengiriman = [
            ['kode' => 'toko',  'judul' => 'Ambil di Toko',      'deskripsi' => 'Gratis, tanpa ongkos kirim. Ambil kapan saja saat jam operasional studio.'],
            ['kode' => 'kirim', 'judul' => 'Dikirim ke Alamat',  'deskripsi' => 'Untuk Anda yang tidak bisa datang ke toko. Ongkos kirim mengikuti lokasi, dihitung otomatis saat checkout.'],
        ];

        $sosial = [
            ['nama' => 'Instagram', 'url' => '#', 'icon' => 'M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zM16 11.4A4 4 0 1 1 12.6 8 4 4 0 0 1 16 11.4zM17.5 6.5h.01'],
            ['nama' => 'WhatsApp',  'url' => '#', 'icon' => 'M21 11.5a8.4 8.4 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.4 8.4 0 0 1-3.8-.9L3 21l1.9-5.7a8.4 8.4 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.4 8.4 0 0 1 3.8-.9h.5a8.5 8.5 0 0 1 8 8z'],
            ['nama' => 'TikTok',    'url' => '#', 'icon' => 'M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5'],
            ['nama' => 'Facebook',  'url' => '#', 'icon' => 'M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z'],
            ['nama' => 'YouTube',   'url' => '#', 'icon' => 'M22.5 6.4a2.8 2.8 0 0 0-1.9-2C18.9 4 12 4 12 4s-6.9 0-8.6.5a2.8 2.8 0 0 0-1.9 2A29 29 0 0 0 1 11.8a29 29 0 0 0 .5 5.3A2.8 2.8 0 0 0 3.4 19c1.7.5 8.6.5 8.6.5s6.9 0 8.6-.5a2.8 2.8 0 0 0 1.9-2 29 29 0 0 0 .5-5.2 29 29 0 0 0-.5-5.4zM9.75 15 15.5 11.75 9.75 8.5z'],
        ];

        // ---------------------------------------------------
        // LOGIKA TURUNAN (dipakai Opsi 1 maupun Opsi 2)
        // ---------------------------------------------------

        $rp = fn ($n) => 'Rp ' . number_format($n, 0, ',', '.');

        $inisial = strtoupper(mb_substr($pengguna['nama'], 0, 1));

        if ($produk) {

            // Varian yang terpilih di awal = varian pertama yang stoknya masih ada
            $varianAwal = 0;
            foreach ($produk['varian'] as $i => $v) {
                if ($v['stok'] > 0) {
                    $varianAwal = $i;
                    break;
                }
            }

            // Ukuran awal = M kalau ada, kalau tidak ukuran pertama
            $ukuranAwal = in_array('M', $produk['ukuran']) ? 'M' : ($produk['ukuran'][0] ?? '');

            $varianTerpilih = $produk['varian'][$varianAwal] ?? null;
            $galeriAwal     = $produk['galeri'][0] ?? ['foto' => null, 'warna' => '#2a2010'];
        }

    @endphp


    <!-- =========================================================
         HEADER
    ========================================================== -->

    <!-- Info promo -->
    <div class="bg-[#111111] text-white">
        <div class="mx-auto flex max-w-[1200px] items-center justify-between gap-4 px-5 py-2 text-[11px] md:px-8">

            <p class="flex items-center gap-2">
                <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-[#c99a32]"></span>
                {{ $info['promo'] }}
            </p>

            <div class="hidden shrink-0 items-center gap-5 text-gray-300 md:flex">
                <span>{{ $info['studio'] }}</span>
                <span class="font-semibold text-white">{{ $info['wa_cs'] }}</span>
            </div>

        </div>
    </div>


    <header class="sticky top-0 z-50 border-b border-[#e9e9e9] bg-white/95 backdrop-blur">

        <div class="mx-auto flex h-[64px] max-w-[1200px] items-center gap-4 px-5 md:gap-8 md:px-8">

            <!-- Logo -->
            <a href="#" class="flex shrink-0 items-center gap-2.5">

                <span class="flex h-10 w-10 items-center justify-center border border-[#c99a32] bg-black text-[11px] font-bold text-[#c99a32]">
                    AL
                </span>

                <span>
                    <span class="block font-display text-[17px] font-bold leading-none tracking-[.08em] text-[#13223a]">ALNAY.ID</span>
                    <span class="mt-1 block text-[7px] font-semibold tracking-[.2em] text-[#c99a32]">JAS &amp; GOWN BERBUDAYA</span>
                </span>

            </a>


            <!-- Pencarian -->
            <div class="relative hidden max-w-xl flex-1 md:block">

                <svg class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m1.35-5.4a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z"/>
                </svg>

                <input type="text" placeholder="Cari jas adat, gown pengantin, siger, beskap..."
                    class="h-10 w-full rounded-full border border-[#e6e1d6] bg-[#faf8f2] pl-11 pr-4 text-sm focus:border-[#c99a32] focus:outline-none">

            </div>


            <!-- Aksi pengguna -->
            <div class="ml-auto flex items-center gap-4 md:gap-5">

                <!-- Keranjang -->
                <a href="{{ $url['keranjang'] }}" class="relative flex items-center gap-1.5 text-sm text-[#15233d]">

                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12l1 13H5zM9 7a3 3 0 0 1 6 0"/>
                    </svg>

                    <span class="hidden sm:inline">Keranjang</span>

                    @if($pengguna['keranjang'] > 0)
                        <span class="absolute -right-2 -top-2 flex h-4 w-4 items-center justify-center rounded-full bg-[#c99a32] text-[9px] font-bold text-white sm:-right-4">
                            {{ $pengguna['keranjang'] }}
                        </span>
                    @endif

                </a>


                <!-- Notifikasi -->
                <button type="button" aria-label="Notifikasi" class="relative text-[#15233d]">

                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9zM10 21a2 2 0 0 0 4 0"/>
                    </svg>

                    @if($pengguna['notifikasi'])
                        <span class="absolute -right-0.5 -top-0.5 h-2 w-2 rounded-full bg-red-500"></span>
                    @endif

                </button>


                <!-- Profil -->
                <div class="flex items-center gap-2.5">

                    <div class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-full bg-[#a37b2a] text-sm font-bold text-white">
                        @if($pengguna['foto'])
                            <img src="{{ $pengguna['foto'] }}" alt="{{ $pengguna['nama'] }}" class="h-full w-full object-cover">
                        @else
                            {{ $inisial }}
                        @endif
                    </div>

                    <span class="hidden text-xs font-semibold uppercase tracking-wide text-[#15233d] lg:inline">
                        {{ $pengguna['nama'] }}
                    </span>

                    <svg class="hidden h-4 w-4 text-gray-500 lg:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
                    </svg>

                </div>

            </div>

        </div>


        <!-- Menu navigasi -->
        <nav class="border-t border-[#eeeeee] bg-[#faf9f6]">

            <div class="mx-auto flex max-w-[1200px] items-center gap-7 overflow-x-auto px-5 py-3 md:justify-center md:gap-10 md:px-8">

                @foreach($menu as $item)

                    <a href="{{ $item['url'] }}" class="flex shrink-0 items-center gap-1.5 text-[12px] font-medium uppercase tracking-wide text-[#15233d] transition hover:text-[#c99a32]">

                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <path d="{{ $item['icon'] }}"/>
                        </svg>

                        {{ $item['label'] }}

                        @if($item['dropdown'])
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
                            </svg>
                        @endif

                    </a>

                @endforeach

            </div>

        </nav>

    </header>


    <!-- =========================================================
         MAIN CONTENT
    ========================================================== -->

    <main class="mx-auto max-w-[1200px] px-5 py-10 md:px-8 md:py-12">

    @if($produk)

        <div id="produkRoot"
            data-varian-awal="{{ $varianAwal }}"
            data-ukuran-awal="{{ $ukuranAwal }}"
            class="grid grid-cols-1 gap-10 lg:grid-cols-2 lg:gap-14">


            <!-- =================================================
                 KOLOM KIRI: GALERI + DESKRIPSI
            ================================================== -->

            <div>

                <!-- Foto utama -->
                <div id="fotoUtama"
                    class="relative flex aspect-[1/0.97] items-center justify-center overflow-hidden rounded-xl"
                    style="background: {{ $galeriAwal['warna'] }}">

                    <span class="absolute left-4 top-4 z-10 rounded-md bg-white px-3 py-1.5 text-[11px] font-bold uppercase tracking-wide text-[#15233d]">
                        {{ $produk['status'] }}
                    </span>

                    <img id="fotoUtamaImg"
                        src="{{ $galeriAwal['foto'] ? asset('image/produk/' . $galeriAwal['foto']) : '' }}"
                        alt="{{ $produk['nama'] }}"
                        class="{{ $galeriAwal['foto'] ? '' : 'hidden' }} h-full w-full object-cover">

                    <!-- Placeholder saat foto belum ada -->
                    <div id="fotoUtamaKosong" class="{{ $galeriAwal['foto'] ? 'hidden' : '' }} px-8 text-center">

                        <p class="font-display text-2xl font-medium text-[#d4a63d] md:text-3xl">
                            {{ $produk['nama'] }}
                        </p>

                        <p class="mt-3 text-sm text-white/90">
                            {{ $produk['subjudul'] }}
                        </p>

                    </div>

                </div>


                <!-- Thumbnail -->
                @if(count($produk['galeri']) > 1)
                <div class="mt-3 flex gap-3">

                    @foreach($produk['galeri'] as $i => $g)

                        <button type="button" data-thumb
                            data-warna="{{ $g['warna'] }}"
                            data-src="{{ $g['foto'] ? asset('image/produk/' . $g['foto']) : '' }}"
                            aria-label="Foto {{ $i + 1 }}"
                            class="h-[72px] w-[72px] shrink-0 overflow-hidden rounded-lg border-2 transition sm:h-[84px] sm:w-[84px]
                                {{ $i === 0 ? 'border-[#c99a32]' : 'border-transparent' }}"
                            style="background: {{ $g['warna'] }}">

                            @if($g['foto'])
                                <img src="{{ asset('image/produk/' . $g['foto']) }}" alt="" class="h-full w-full object-cover">
                            @endif

                        </button>

                    @endforeach

                </div>
                @endif


                <!-- Deskripsi -->
                <div class="mt-10">

                    <h2 class="font-display text-xl font-bold text-[#15233d]">
                        Deskripsi Produk
                    </h2>

                    <div class="mt-4 space-y-4 text-sm leading-7 text-gray-500">

                        @foreach($produk['deskripsi'] as $paragraf)
                            <p>{{ $paragraf }}</p>
                        @endforeach

                    </div>


                    <!-- Spesifikasi -->
                    <div class="mt-6 divide-y divide-[#eee5d2] border-b border-[#eee5d2] text-sm">

                        <div class="flex items-center justify-between gap-4 py-3.5">
                            <span class="text-gray-500">Kategori</span>
                            <span class="text-right font-semibold text-[#15233d]">{{ $produk['kategori'] }}</span>
                        </div>

                        <div class="flex items-center justify-between gap-4 py-3.5">
                            <span class="text-gray-500">Material</span>
                            <span class="text-right font-semibold text-[#15233d]">{{ $produk['material'] }}</span>
                        </div>

                        <div class="flex items-center justify-between gap-4 py-3.5">
                            <span class="text-gray-500">Ukuran Tersedia</span>
                            <span class="text-right font-semibold text-[#15233d]">{{ implode(', ', $produk['ukuran']) }}</span>
                        </div>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 KOLOM KANAN: PILIHAN PRODUK
            ================================================== -->

            <div>

                <!-- Label -->
                <span class="inline-flex items-center rounded-md bg-[#15110d] px-3 py-1.5 text-[11px] font-semibold text-[#d4a63d]">
                    {{ $produk['label'] }}
                </span>


                <!-- Judul -->
                <h1 class="mt-4 font-display text-3xl font-bold leading-tight text-[#111827] md:text-[34px]">
                    {{ $produk['nama'] }}
                </h1>


                <!-- Rating -->
                <div class="mt-3 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-gray-500">

                    <span class="flex gap-0.5 text-[#c99a32]">

                        @for($i = 1; $i <= 5; $i++)
                            <svg class="h-4 w-4" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"
                                fill="{{ $i <= round($produk['rating']) ? 'currentColor' : 'none' }}">
                                <path d="m12 3 2.6 5.6 6.1.7-4.5 4.2 1.2 6L12 16.6 6.6 19.5l1.2-6L3.3 9.3l6.1-.7z"/>
                            </svg>
                        @endfor

                    </span>

                    <span class="font-semibold text-[#15233d]">{{ number_format($produk['rating'], 1) }}</span>

                    <span>({{ $produk['jumlah_ulasan'] }} ulasan)</span>

                    <span class="text-gray-300">|</span>

                    <span>{{ $produk['jumlah_disewa'] }} kali disewa</span>

                </div>


                <!-- Mode: Sewa / Beli -->
                <div class="mt-6 grid grid-cols-2 overflow-hidden rounded-lg border border-[#e3dccc] bg-white text-sm font-medium">

                    <a href="{{ $url['sewa'] }}" class="py-3 text-center text-gray-600 transition hover:bg-gray-50">
                        Sewa Busana
                    </a>

                    <span class="bg-[#15110d] py-3 text-center font-semibold text-[#d4a63d]">
                        Beli Baru
                    </span>

                </div>


                <!-- Harga -->
                <div class="mt-6 rounded-xl bg-[#f5f0e6] p-6">

                    <p class="font-display text-[34px] font-medium leading-none text-[#15233d]">
                        {{ $rp($produk['harga']) }}
                    </p>

                    <p class="mt-4 text-[13px] leading-6 text-gray-500">
                        Harga sudah termasuk {{ $produk['termasuk'] }}. Busana ini sepenuhnya
                        <strong class="font-semibold text-[#15233d]">milik Anda</strong>
                        — tidak perlu dikembalikan, tidak ada deposit maupun jaminan KTP.
                    </p>

                    <div class="mt-4 border-t border-[#e6dcc4] pt-4 text-[13px] text-gray-500">
                        Stok tersedia:
                        <span id="stokJumlah" class="font-semibold text-[#15233d]">{{ $varianTerpilih['stok'] ?? 0 }} set</span>
                        <span id="stokDetail">({{ $varianTerpilih['nama'] ?? '-' }}, ukuran {{ $ukuranAwal }})</span>
                    </div>

                </div>


                <!-- Varian warna & motif -->
                <div class="mt-7">

                    <h3 class="text-sm font-semibold text-[#15233d]">
                        Varian Warna &amp; Motif
                    </h3>

                    <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2">

                        @foreach($produk['varian'] as $i => $v)

                            <button type="button" data-varian
                                data-nama="{{ $v['nama'] }}"
                                data-stok="{{ $v['stok'] }}"
                                {{ $v['stok'] <= 0 ? 'disabled' : '' }}
                                class="flex items-center gap-3 rounded-lg border px-4 py-3 text-left transition disabled:cursor-not-allowed
                                    {{ $i === $varianAwal ? 'border-[#c99a32] ring-1 ring-[#c99a32] bg-[#fbf6e9]' : 'border-gray-200 bg-white hover:border-gray-300' }}">

                                <span class="h-7 w-7 shrink-0 rounded-md" style="background: {{ $v['warna'] }}"></span>

                                <span class="min-w-0">

                                    <span class="block text-sm font-medium text-[#15233d]">{{ $v['nama'] }}</span>

                                    @if($v['stok'] <= 0)
                                        <span class="block text-[10px] font-medium text-red-500">Stok Habis</span>
                                    @elseif($v['label'])
                                        <span class="block text-[10px] font-medium text-[#a37b2a]">{{ $v['label'] }}</span>
                                    @endif

                                </span>

                            </button>

                        @endforeach

                    </div>

                </div>


                <!-- Ukuran -->
                <div class="mt-7">

                    <div class="flex items-center justify-between">

                        <h3 class="text-sm font-semibold text-[#15233d]">Ukuran</h3>

                        <a href="{{ $url['panduan'] }}" class="text-xs font-medium text-[#a37b2a] transition hover:text-[#c99a32]">
                            Panduan Ukur Mandiri
                        </a>

                    </div>

                    <div class="mt-3 flex flex-wrap gap-2.5">

                        @foreach($produk['ukuran'] as $u)

                            <button type="button" data-ukuran="{{ $u }}"
                                class="h-11 min-w-[46px] rounded-lg border px-4 text-sm font-semibold transition
                                    {{ $u === $ukuranAwal ? 'border-[#15110d] bg-[#15110d] text-[#d4a63d]' : 'border-gray-200 bg-white text-[#15233d] hover:border-gray-300' }}">
                                {{ $u }}
                            </button>

                        @endforeach

                    </div>

                </div>


                <!-- Jumlah -->
                <div class="mt-7">

                    <h3 class="text-sm font-semibold text-[#15233d]">Jumlah</h3>

                    <div class="mt-3 inline-flex items-center overflow-hidden rounded-lg border border-gray-200 bg-white">

                        <button type="button" id="kurangi" aria-label="Kurangi"
                            class="flex h-10 w-10 items-center justify-center text-gray-500 transition hover:bg-gray-50">
                            −
                        </button>

                        <span id="jumlah" class="w-10 text-center text-sm font-semibold">1</span>

                        <button type="button" id="tambah" aria-label="Tambah"
                            class="flex h-10 w-10 items-center justify-center text-gray-500 transition hover:bg-gray-50">
                            +
                        </button>

                    </div>

                </div>


                <!-- Metode pengiriman -->
                <div class="mt-7">

                    <h3 class="text-sm font-semibold text-[#15233d]">Metode Pengiriman</h3>

                    <div class="mt-3 space-y-3">

                        @foreach($pengiriman as $i => $p)

                            <button type="button" data-kirim="{{ $p['kode'] }}"
                                class="block w-full rounded-lg border p-4 text-left transition
                                    {{ $i === 0 ? 'border-[#2f7a4a] ring-1 ring-[#2f7a4a] bg-[#f1f8f3]' : 'border-gray-200 bg-[#faf9f6] hover:border-gray-300' }}">

                                <span class="flex items-center justify-between gap-3">

                                    <span class="text-sm font-semibold text-[#15233d]">{{ $p['judul'] }}</span>

                                    <span data-dipilih class="{{ $i === 0 ? '' : 'hidden' }} rounded bg-[#2f7a4a] px-2 py-0.5 text-[10px] font-semibold text-white">
                                        Dipilih
                                    </span>

                                </span>

                                <span class="mt-1.5 block text-[13px] leading-5 text-gray-500">
                                    {{ $p['deskripsi'] }}
                                </span>

                            </button>

                        @endforeach

                    </div>

                </div>


                <!-- Tombol aksi -->
                <div class="mt-7 grid grid-cols-1 gap-3 sm:grid-cols-2">

                    {{-- Hubungkan kedua tombol ini ke route keranjang / checkout --}}
                    <button type="button"
                        class="rounded-md bg-[#c69220] py-3.5 text-sm font-semibold text-white transition hover:bg-[#b58219]">
                        Beli Sekarang
                    </button>

                    <button type="button"
                        class="rounded-md border border-gray-300 bg-white py-3.5 text-sm font-medium text-[#15233d] transition hover:bg-gray-50">
                        Tambah ke Keranjang
                    </button>

                </div>

            </div>

        </div>

    @else

        <!-- =====================================================
             FALLBACK: PRODUK TIDAK DITEMUKAN
        ====================================================== -->

        <div class="mt-10 rounded-xl border border-dashed border-gray-200 bg-white p-14 text-center">

            <p class="text-base font-semibold text-[#15233d]">
                Produk tidak ditemukan
            </p>

            <p class="mt-1 text-sm text-gray-400">
                Produk mungkin sudah dihapus atau tautan yang kamu buka sudah tidak berlaku.
            </p>

            <a href="{{ $url['koleksi'] }}"
                class="mt-5 inline-block rounded-md bg-[#c69220] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#b58219]">
                Kembali ke Koleksi
            </a>

        </div>

    @endif

    </main>


    <!-- =========================================================
         FOOTER
    ========================================================== -->

    <footer class="bg-[#111111] text-white mt-14">

        <div class="max-w-[1200px] mx-auto px-5 py-10 md:px-8">


            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-[1.6fr_1fr_1.2fr_1.2fr]">


                <!-- BRAND -->

                <div>

                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center border border-[#c99a32] bg-black text-xs font-bold text-[#c99a32]">
                            AL
                        </div>

                        <div>

                            <h3 class="font-display text-base font-bold tracking-[.12em]">
                                AL-NAY.ID
                            </h3>

                            <p class="text-[8px] tracking-[.2em] text-[#c99a32]">
                                JAS &amp; GOWN BERBUDAYA
                            </p>

                        </div>

                    </div>


                    <p class="mt-4 max-w-[360px] text-sm leading-relaxed text-gray-400">

                        Platform penyewaan dan bespoke atelier busana adat Nusantara nomor satu di Indonesia.
                        Menghadirkan mahakarya budaya dengan standar kualitas tailoring adibusana internasional.

                    </p>


                    <div class="mt-5 flex gap-2.5">

                        @foreach($sosial as $s)

                            <a href="{{ $s['url'] }}" target="_blank" rel="noopener" aria-label="{{ $s['nama'] }}"
                                class="flex h-9 w-9 items-center justify-center rounded-full border border-[#2a2a2a] bg-[#1a1a1a] text-[#cfcfcf] transition hover:border-[#9b762d] hover:text-[#d4a63d]">

                                <svg class="h-[17px] w-[17px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="{{ $s['icon'] }}"/>
                                </svg>

                            </a>

                        @endforeach

                    </div>

                </div>


                <!-- KOLEKSI -->

                <div>

                    <h4 class="text-xs font-semibold tracking-widest">
                        KOLEKSI ADAT
                    </h4>

                    <ul class="mt-4 space-y-2 text-sm text-gray-400">

                        <li>Jas Beskap Pria</li>
                        <li>Gown Kebaya Pengantin</li>
                        <li>Set Baju Pasangan</li>
                        <li>Mahkota &amp; Suntingan</li>
                        <li>Tenun Songket Asli</li>

                    </ul>

                </div>


                <!-- BANTUAN -->

                <div>

                    <h4 class="text-xs font-semibold tracking-widest">
                        BUTUH BANTUAN?
                    </h4>

                    <p class="mt-4 text-sm text-gray-400">
                        Senin - Jumat: 08:00 - 20:00 WIB
                    </p>

                    <p class="mt-2 text-sm text-gray-400">
                        Sabtu - Minggu: 09:00 - 18:00 WIB
                    </p>

                    <a href="#" class="mt-3 inline-block rounded border border-[#9b762d] px-4 py-2 text-xs text-[#d4a63d] transition hover:bg-[#9b762d]/10">
                        Chat WhatsApp CS
                    </a>

                    <p class="mt-3 text-xs leading-5 text-gray-500">
                        Jl. Mahakarya Adat No. 18, Bandung Wetan, Jawa Barat
                    </p>

                </div>


                <!-- LAYANAN -->

                <div>

                    <h4 class="text-xs font-semibold tracking-widest">
                        LAYANAN &amp; INFORMASI
                    </h4>

                    <ul class="mt-4 space-y-2 text-sm text-gray-400">

                        <li>Cara &amp; Syarat Sewa Busana</li>
                        <li>Aturan Deposit &amp; Asuransi</li>
                        <li>Jadwal Fitting Studio</li>
                        <li>Panduan Ukur Badan Online</li>
                        <li>Pemesanan Custom Bespoke</li>

                    </ul>

                </div>

            </div>


            <!-- PAYMENT -->

            <div class="mt-10 flex flex-col justify-between gap-5 border-t border-gray-800 pt-6 lg:flex-row">

                <div>

                    <p class="text-xs uppercase tracking-wider text-gray-500">
                        Metode Pembayaran Resmi
                    </p>

                    <div class="mt-3 flex flex-wrap gap-2">

                        <span class="rounded border border-gray-700 bg-gray-800/60 px-3 py-1 text-xs">BCA</span>
                        <span class="rounded border border-gray-700 bg-gray-800/60 px-3 py-1 text-xs">MANDIRI</span>
                        <span class="rounded border border-gray-700 bg-gray-800/60 px-3 py-1 text-xs">BNI</span>
                        <span class="rounded border border-gray-700 bg-gray-800/60 px-3 py-1 text-xs">BRI</span>
                        <span class="rounded border border-gray-700 bg-gray-800/60 px-3 py-1 text-xs">QRIS</span>
                        <span class="rounded border border-gray-700 bg-gray-800/60 px-3 py-1 text-xs">VISA / MASTER</span>

                    </div>

                </div>


                <div class="lg:text-right">

                    <p class="text-xs uppercase tracking-wider text-gray-500">
                        Ekspedisi Khusus &amp; Kurir Aman
                    </p>

                    <div class="mt-3 flex flex-wrap gap-2 lg:justify-end">

                        <span class="rounded border border-gray-700 bg-gray-800/60 px-3 py-1 text-xs">JNE YES</span>
                        <span class="rounded border border-gray-700 bg-gray-800/60 px-3 py-1 text-xs">GoSend Instant</span>
                        <span class="rounded border border-gray-700 bg-gray-800/60 px-3 py-1 text-xs">GrabExpress</span>
                        <span class="rounded border border-gray-700 bg-gray-800/60 px-3 py-1 text-xs">Paxel Kargo</span>

                    </div>

                </div>

            </div>


            <!-- COPYRIGHT -->

            <div class="mt-6 flex flex-col justify-between gap-3 border-t border-gray-800 pt-5 text-xs text-gray-500 md:flex-row">

                <p>
                    © 2026 AL-NAY.ID (Jas &amp; Gown Berbudaya). All Rights Reserved.
                </p>

                <div class="flex flex-wrap gap-5">

                    <a href="#" class="hover:text-white">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-white">Syarat &amp; Ketentuan Sewa</a>
                    <a href="#" class="hover:text-white">Keagungan Adat Nusantara</a>

                </div>

            </div>

        </div>

    </footer>


    @if($produk)

    <!-- =========================================================
         SCRIPT: galeri, varian, ukuran, jumlah, pengiriman
    ========================================================== -->
    <script>
        (function () {

            var root = document.getElementById('produkRoot');

            var state = {
                varian: parseInt(root.dataset.varianAwal, 10),
                ukuran: root.dataset.ukuranAwal,
                jumlah: 1
            };

            // Menukar kelas aktif / nonaktif pada sebuah elemen
            function tandai(el, aktif, kelasAktif, kelasNonaktif) {
                kelasAktif.forEach(function (c) { el.classList.toggle(c, aktif); });
                kelasNonaktif.forEach(function (c) { el.classList.toggle(c, !aktif); });
            }


            // ---------- Galeri ----------
            var fotoUtama = document.getElementById('fotoUtama');
            var fotoImg   = document.getElementById('fotoUtamaImg');
            var fotoKosong = document.getElementById('fotoUtamaKosong');
            var thumbs = document.querySelectorAll('[data-thumb]');

            thumbs.forEach(function (btn) {
                btn.addEventListener('click', function () {

                    fotoUtama.style.background = btn.dataset.warna;

                    if (btn.dataset.src) {
                        fotoImg.src = btn.dataset.src;
                        fotoImg.classList.remove('hidden');
                        fotoKosong.classList.add('hidden');
                    } else {
                        fotoImg.classList.add('hidden');
                        fotoKosong.classList.remove('hidden');
                    }

                    thumbs.forEach(function (t) {
                        tandai(t, t === btn, ['border-[#c99a32]'], ['border-transparent']);
                    });
                });
            });


            // ---------- Stok & jumlah ----------
            var varianBtns = document.querySelectorAll('[data-varian]');
            var jumlahEl = document.getElementById('jumlah');

            function stokSaatIni() {
                return parseInt(varianBtns[state.varian].dataset.stok, 10) || 0;
            }

            function perbaruiStok() {
                var v = varianBtns[state.varian];
                document.getElementById('stokJumlah').textContent = stokSaatIni() + ' set';
                document.getElementById('stokDetail').textContent = '(' + v.dataset.nama + ', ukuran ' + state.ukuran + ')';

                if (state.jumlah > stokSaatIni()) state.jumlah = Math.max(1, stokSaatIni());
                jumlahEl.textContent = state.jumlah;
            }


            // ---------- Varian ----------
            varianBtns.forEach(function (btn, i) {
                btn.addEventListener('click', function () {

                    if (btn.disabled) return;

                    state.varian = i;

                    varianBtns.forEach(function (b) {
                        tandai(b, b === btn,
                            ['border-[#c99a32]', 'ring-1', 'ring-[#c99a32]', 'bg-[#fbf6e9]'],
                            ['border-gray-200', 'bg-white']);
                    });

                    perbaruiStok();
                });
            });


            // ---------- Ukuran ----------
            var ukuranBtns = document.querySelectorAll('[data-ukuran]');

            ukuranBtns.forEach(function (btn) {
                btn.addEventListener('click', function () {

                    state.ukuran = btn.dataset.ukuran;

                    ukuranBtns.forEach(function (b) {
                        tandai(b, b === btn,
                            ['border-[#15110d]', 'bg-[#15110d]', 'text-[#d4a63d]'],
                            ['border-gray-200', 'bg-white', 'text-[#15233d]']);
                    });

                    perbaruiStok();
                });
            });


            // ---------- Jumlah ----------
            document.getElementById('kurangi').addEventListener('click', function () {
                if (state.jumlah > 1) state.jumlah--;
                jumlahEl.textContent = state.jumlah;
            });

            document.getElementById('tambah').addEventListener('click', function () {
                if (state.jumlah < stokSaatIni()) state.jumlah++;
                jumlahEl.textContent = state.jumlah;
            });


            // ---------- Metode pengiriman ----------
            var kirimBtns = document.querySelectorAll('[data-kirim]');

            kirimBtns.forEach(function (btn) {
                btn.addEventListener('click', function () {

                    kirimBtns.forEach(function (b) {
                        var aktif = b === btn;

                        tandai(b, aktif,
                            ['border-[#2f7a4a]', 'ring-1', 'ring-[#2f7a4a]', 'bg-[#f1f8f3]'],
                            ['border-gray-200', 'bg-[#faf9f6]']);

                        b.querySelector('[data-dipilih]').classList.toggle('hidden', !aktif);
                    });
                });
            });

        })();
    </script>

    @endif


</body>
</html>