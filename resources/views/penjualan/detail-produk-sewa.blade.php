<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk (Sewa) - ALNAY.ID</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
    </style>
</head>

<body class="bg-[#faf9f6] text-[#15233d]">

    @php
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

        $pengguna = [
            'nama'       => 'Perrell Brown',
            'foto'       => null,
            'keranjang'  => 2,
            'notifikasi' => true,
        ];

        $info = [
            'promo'  => 'Promo Eksklusif Pernikahan 2026: Diskon Sewa hingga 20% & Gratis Fitting Studio.',
            'studio' => 'Studio: Senin - Sabtu (09.00 - 19.00 WIB)',
            'wa_cs'  => 'WA CS: 0812-3456-7890',
        ];

        $url = [
            'sewa'      => '#',
            'keranjang' => '#',
            'koleksi'   => '#',
            'panduan'   => '#',
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

        $sosial = [
            ['nama' => 'Instagram', 'url' => '#', 'icon' => 'M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zM16 11.4A4 4 0 1 1 12.6 8 4 4 0 0 1 16 11.4zM17.5 6.5h.01'],
            ['nama' => 'WhatsApp',  'url' => '#', 'icon' => 'M21 11.5a8.4 8.4 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.4 8.4 0 0 1-3.8-.9L3 21l1.9-5.7a8.4 8.4 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.4 8.4 0 0 1 3.8-.9h.5a8.5 8.5 0 0 1 8 8z'],
            ['nama' => 'TikTok',    'url' => '#', 'icon' => 'M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5'],
            ['nama' => 'Facebook',  'url' => '#', 'icon' => 'M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z'],
            ['nama' => 'YouTube',   'url' => '#', 'icon' => 'M22.5 6.4a2.8 2.8 0 0 0-1.9-2C18.9 4 12 4 12 4s-6.9 0-8.6.5a2.8 2.8 0 0 0-1.9 2A29 29 0 0 0 1 11.8a29 29 0 0 0 .5 5.3A2.8 2.8 0 0 0 3.4 19c1.7.5 8.6.5 8.6.5s6.9 0 8.6-.5a2.8 2.8 0 0 0 1.9-2 29 29 0 0 0 .5-5.2 29 29 0 0 0-.5-5.4zM9.75 15 15.5 11.75 9.75 8.5z'],
        ];

        $rp = fn ($n) => 'Rp ' . number_format($n, 0, ',', '.');
        $inisial = strtoupper(mb_substr($pengguna['nama'], 0, 1));

        $varianAwal = 0;
        foreach ($produk['varian'] as $i => $v) {
            if ($v['stok'] > 0) { $varianAwal = $i; break; }
        }
        $ukuranAwal = in_array('M', $produk['ukuran']) ? 'M' : ($produk['ukuran'][0] ?? '');
        $varianTerpilih = $produk['varian'][$varianAwal] ?? null;
        $galeriAwal     = $produk['galeri'][0] ?? ['foto' => null, 'warna' => '#2a2010'];
    @endphp

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
            <a href="#" class="flex shrink-0 items-center gap-2.5">
                <span class="flex h-10 w-10 items-center justify-center border border-[#c99a32] bg-black text-[11px] font-bold text-[#c99a32]">AL</span>
                <span>
                    <span class="block font-display text-[17px] font-bold leading-none tracking-[.08em] text-[#13223a]">ALNAY.ID</span>
                    <span class="mt-1 block text-[7px] font-semibold tracking-[.2em] text-[#c99a32]">JAS &amp; GOWN BERBUDAYA</span>
                </span>
            </a>

            <div class="relative hidden max-w-xl flex-1 md:block">
                <svg class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m1.35-5.4a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z"/>
                </svg>
                <input type="text" placeholder="Cari jas adat, gown pengantin, siger, beskap..."
                    class="h-10 w-full rounded-full border border-[#e6e1d6] bg-[#faf8f2] pl-11 pr-4 text-sm focus:border-[#c99a32] focus:outline-none">
            </div>

            <div class="ml-auto flex items-center gap-4 md:gap-5">
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

                <button type="button" aria-label="Notifikasi" class="relative text-[#15233d]">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9zM10 21a2 2 0 0 0 4 0"/>
                    </svg>
                    @if($pengguna['notifikasi'])
                        <span class="absolute -right-0.5 -top-0.5 h-2 w-2 rounded-full bg-red-500"></span>
                    @endif
                </button>

                <div class="flex items-center gap-2.5">
                    <div class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-full bg-[#a37b2a] text-sm font-bold text-white">
                        @if($pengguna['foto'])
                            <img src="{{ $pengguna['foto'] }}" alt="{{ $pengguna['nama'] }}" class="h-full w-full object-cover">
                        @else
                            {{ $inisial }}
                        @endif
                    </div>
                    <span class="hidden text-xs font-semibold uppercase tracking-wide text-[#15233d] lg:inline">{{ $pengguna['nama'] }}</span>
                    <svg class="hidden h-4 w-4 text-gray-500 lg:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
                    </svg>
                </div>
            </div>
        </div>

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

    <main class="mx-auto max-w-[1200px] px-5 py-10 md:px-8 md:py-12">

    @if($produk)

        <div id="produkRoot"
            data-varian-awal="{{ $varianAwal }}"
            data-ukuran-awal="{{ $ukuranAwal }}"
            class="grid grid-cols-1 gap-10 lg:grid-cols-2 lg:gap-14">

            <!-- KOLOM KIRI -->
            <div>
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

                    <div id="fotoUtamaKosong" class="{{ $galeriAwal['foto'] ? 'hidden' : '' }} px-8 text-center">
                        <p class="font-display text-2xl font-medium text-[#d4a63d] md:text-3xl">{{ $produk['nama'] }}</p>
                        <p class="mt-3 text-sm text-white/90">{{ $produk['subjudul'] }}</p>
                    </div>
                </div>

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

                <div class="mt-10">
                    <h2 class="font-display text-xl font-bold text-[#15233d]">Deskripsi Produk</h2>
                    <div class="mt-4 space-y-4 text-sm leading-7 text-gray-500">
                        @foreach($produk['deskripsi'] as $paragraf)
                            <p>{{ $paragraf }}</p>
                        @endforeach
                    </div>

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

            <!-- KOLOM KANAN -->
            <div>
                <span class="inline-flex items-center rounded-md bg-[#15110d] px-3 py-1.5 text-[11px] font-semibold text-[#d4a63d]">
                    {{ $produk['label'] }}
                </span>

                <h1 class="mt-4 font-display text-3xl font-bold leading-tight text-[#111827] md:text-[34px]">
                    {{ $produk['nama'] }}
                </h1>

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

                <div class="mt-6 grid grid-cols-2 overflow-hidden rounded-lg border border-[#e3dccc] bg-white text-sm font-medium">
                    <span class="bg-[#15110d] py-3 text-center font-semibold text-[#d4a63d]">Sewa Busana</span>
                    <a href="#" class="py-3 text-center text-gray-600 transition hover:bg-gray-50">Beli Baru</a>
                </div>

                <div class="mt-6 rounded-xl bg-[#f5f0e6] p-6">
                    <p class="font-display text-[34px] font-medium leading-none text-[#15233d]">
                        {{ $rp($produk['harga']) }} <span class="text-lg font-normal text-gray-500">/ sewa</span>
                    </p>
                    <p class="mt-4 text-[13px] leading-6 text-gray-500">
                        Jadwal Penyewaan: Penyewaan memiliki 3 tahap utama: pengambilan, pemakaian, dan pengembalian. Contoh normal: diambil hari Senin, dipakai untuk acara hari Selasa, dikembalikan hari Rabu.
                    </p>
                    <div class="mt-4 rounded-lg border border-dashed border-[#d4a63d] bg-[#fdf9ef] p-4 text-[13px] leading-6 text-gray-600">
                        Ingin pemakaian lebih dari 1 hari? Tidak perlu bayar harga sewa penuh lagi — cukup tambahan <strong class="font-semibold text-[#15233d]">Rp25.000 per hari</strong>. Contoh: pemakaian Selasa–Rabu (2 hari), dikembalikan Kamis, dikenakan tambahan Rp25.000.
                    </div>
                    <div class="mt-4 border-t border-[#e6dcc4] pt-4 text-[13px] text-gray-500">
                        Stok tersedia:
                        <span id="stokJumlah" class="font-semibold text-[#15233d]">{{ $varianTerpilih['stok'] ?? 0 }} set</span>
                        <span id="stokDetail">({{ $varianTerpilih['nama'] ?? '-' }}, ukuran {{ $ukuranAwal }})</span>
                    </div>
                </div>

                <!-- Kalender Interaktif -->
                <div class="mt-7">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-[#15233d]">Tanggal Sewa</h3>
                        <div class="flex items-center gap-1">
                            <button type="button" id="btnPrev"
                                class="flex h-7 w-7 items-center justify-center rounded border border-gray-200 text-gray-500 hover:bg-gray-50 disabled:opacity-30 disabled:cursor-not-allowed">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m15 19-7-7 7-7"/></svg>
                            </button>
                            <button type="button" id="btnNext"
                                class="flex h-7 w-7 items-center justify-center rounded border border-gray-200 text-gray-500 hover:bg-gray-50 disabled:opacity-30 disabled:cursor-not-allowed">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m9 5 7 7-7 7"/></svg>
                            </button>
                        </div>
                    </div>

                    <p class="mt-3 text-sm font-semibold text-[#15233d]" id="labelBulan">September 2026</p>

                    <div class="mt-3 grid grid-cols-7 gap-1 text-center text-xs">
                        <span class="py-1.5 font-medium text-gray-400">S</span>
                        <span class="py-1.5 font-medium text-gray-400">S</span>
                        <span class="py-1.5 font-medium text-gray-400">R</span>
                        <span class="py-1.5 font-medium text-gray-400">K</span>
                        <span class="py-1.5 font-medium text-gray-400">J</span>
                        <span class="py-1.5 font-medium text-gray-400">S</span>
                        <span class="py-1.5 font-medium text-gray-400">M</span>
                    </div>

                    <div id="gridKalender" class="mt-1 grid grid-cols-7 gap-1 text-center text-xs"></div>

                    <div class="mt-4 flex flex-wrap gap-4 text-xs text-gray-500">
                        <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-[#15110d]"></span> Dipilih</span>
                        <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-[#f5e6e6]"></span> Hari Pakai</span>
                        <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-[#e5d9c4]"></span> Sudah dibooking</span>
                    </div>

                    <div class="mt-4 grid grid-cols-3 divide-x divide-[#eee5d2] rounded-lg border border-[#eee5d2] bg-white p-4 text-xs">
                        <div class="text-center">
                            <p class="text-gray-500">Ambil / Diantar</p>
                            <p class="mt-1 font-semibold text-[#15233d]" id="ringkasAmbil">Klik tanggal</p>
                        </div>
                        <div class="text-center">
                            <p class="text-gray-500">Sewa</p>
                            <p class="mt-1 font-semibold text-[#15233d]" id="ringkasSewa">—</p>
                            <p class="mt-0.5 text-[10px] text-gray-400" id="ringkasDurasi"></p>
                        </div>
                        <div class="text-center">
                            <p class="text-gray-500">Kembali</p>
                            <p class="mt-1 font-semibold text-[#15233d]" id="ringkasKembali">—</p>
                        </div>
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

                <div class="mt-7 grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <button type="button"
                        class="rounded-md bg-[#c69220] py-3.5 text-sm font-semibold text-white transition hover:bg-[#b58219]">
                        Sewa Sekarang
                    </button>
                    <button type="button"
                        class="rounded-md border border-gray-300 bg-white py-3.5 text-sm font-medium text-[#15233d] transition hover:bg-gray-50">
                        Tambah ke Keranjang
                    </button>
                </div>

                <div class="mt-4">
                    <button type="button"
                        class="w-full rounded-md border border-dashed border-[#d4a63d] bg-[#fdf9ef] py-3.5 text-sm font-semibold text-[#a37b2a] transition hover:bg-[#faf3e3]">
                        Konsultasi Produk Ini via WhatsApp
                    </button>
                </div>
            </div>

        </div>

    @else

        <div class="mt-10 rounded-xl border border-dashed border-gray-200 bg-white p-14 text-center">
            <p class="text-base font-semibold text-[#15233d]">Produk tidak ditemukan</p>
            <p class="mt-1 text-sm text-gray-400">Produk mungkin sudah dihapus atau tautan yang kamu buka sudah tidak berlaku.</p>
            <a href="{{ $url['koleksi'] }}"
                class="mt-5 inline-block rounded-md bg-[#c69220] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#b58219]">
                Kembali ke Koleksi
            </a>
        </div>

    @endif

    </main>

    <!-- FOOTER -->
    <footer class="bg-[#111111] text-white mt-14">
        <div class="max-w-[1200px] mx-auto px-5 py-10 md:px-8">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-[1.6fr_1fr_1.2fr_1.2fr]">

                <div>
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center border border-[#c99a32] bg-black text-xs font-bold text-[#c99a32]">AL</div>
                        <div>
                            <h3 class="font-display text-base font-bold tracking-[.12em]">AL-NAY.ID</h3>
                            <p class="text-[8px] tracking-[.2em] text-[#c99a32]">JAS &amp; GOWN BERBUDAYA</p>
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

                <div>
                    <h4 class="text-xs font-semibold tracking-widest">KOLEKSI ADAT</h4>
                    <ul class="mt-4 space-y-2 text-sm text-gray-400">
                        <li>Jas Beskap Pria</li>
                        <li>Gown Kebaya Pengantin</li>
                        <li>Set Baju Pasangan</li>
                        <li>Mahkota &amp; Suntingan</li>
                        <li>Tenun Songket Asli</li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xs font-semibold tracking-widest">BUTUH BANTUAN?</h4>
                    <p class="mt-4 text-sm text-gray-400">Senin - Jumat: 08:00 - 20:00 WIB</p>
                    <p class="mt-2 text-sm text-gray-400">Sabtu - Minggu: 09:00 - 18:00 WIB</p>
                    <a href="#" class="mt-3 inline-block rounded border border-[#9b762d] px-4 py-2 text-xs text-[#d4a63d] transition hover:bg-[#9b762d]/10">
                        Chat WhatsApp CS
                    </a>
                    <p class="mt-3 text-xs leading-5 text-gray-500">Jl. Mahakarya Adat No. 18, Bandung Wetan, Jawa Barat</p>
                </div>

                <div>
                    <h4 class="text-xs font-semibold tracking-widest">LAYANAN &amp; INFORMASI</h4>
                    <ul class="mt-4 space-y-2 text-sm text-gray-400">
                        <li>Cara &amp; Syarat Sewa Busana</li>
                        <li>Aturan Deposit &amp; Asuransi</li>
                        <li>Jadwal Fitting Studio</li>
                        <li>Panduan Ukur Badan Online</li>
                        <li>Pemesanan Custom Bespoke</li>
                    </ul>
                </div>

            </div>

            <div class="mt-10 flex flex-col justify-between gap-5 border-t border-gray-800 pt-6 lg:flex-row">
                <div>
                    <p class="text-xs uppercase tracking-wider text-gray-500">Metode Pembayaran Resmi</p>
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
                    <p class="text-xs uppercase tracking-wider text-gray-500">Ekspedisi Khusus &amp; Kurir Aman</p>
                    <div class="mt-3 flex flex-wrap gap-2 lg:justify-end">
                        <span class="rounded border border-gray-700 bg-gray-800/60 px-3 py-1 text-xs">JNE YES</span>
                        <span class="rounded border border-gray-700 bg-gray-800/60 px-3 py-1 text-xs">GoSend Instant</span>
                        <span class="rounded border border-gray-700 bg-gray-800/60 px-3 py-1 text-xs">GrabExpress</span>
                        <span class="rounded border border-gray-700 bg-gray-800/60 px-3 py-1 text-xs">Paxel Kargo</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex flex-col justify-between gap-3 border-t border-gray-800 pt-5 text-xs text-gray-500 md:flex-row">
                <p>© 2026 AL-NAY.ID (Jas &amp; Gown Berbudaya). All Rights Reserved.</p>
                <div class="flex flex-wrap gap-5">
                    <a href="#" class="hover:text-white">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-white">Syarat &amp; Ketentuan Sewa</a>
                    <a href="#" class="hover:text-white">Keagungan Adat Nusantara</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- SCRIPT -->
    <script>
        (function () {
            var root = document.getElementById('produkRoot');
            if (!root) return;

            // =====================================================
            // STATE
            // =====================================================
            var state = {
                varian: parseInt(root.dataset.varianAwal, 10),
                ukuran: root.dataset.ukuranAwal
            };

            // =====================================================
            // KALENDER
            // =====================================================
            var kalender = {
                bulan: 8,       // 0-index: 8 = September
                tahun: 2026,
                ambil: null,
                kembali: null,
                dibooking: ['2026-09-20'],
                minBulan: 8,
                minTahun: 2026
            };

            var namaBulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

            var gridKalender   = document.getElementById('gridKalender');
            var labelBulan     = document.getElementById('labelBulan');
            var btnPrev        = document.getElementById('btnPrev');
            var btnNext        = document.getElementById('btnNext');
            var ringkasAmbil   = document.getElementById('ringkasAmbil');
            var ringkasKembali = document.getElementById('ringkasKembali');
            var ringkasSewa    = document.getElementById('ringkasSewa');
            var ringkasDurasi  = document.getElementById('ringkasDurasi');

            function formatTanggal(d) {
                if (!d) return '—';
                return d.getDate() + ' ' + namaBulan[d.getMonth()] + ' ' + d.getFullYear();
            }

            function pad(n) { return n < 10 ? '0' + n : '' + n; }

            function keyTanggal(d) {
                return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate());
            }

            function isDibooking(d) {
                return kalender.dibooking.indexOf(keyTanggal(d)) !== -1;
            }

            function sameDay(a, b) {
                if (!a || !b) return false;
                return a.getFullYear() === b.getFullYear()
                    && a.getMonth() === b.getMonth()
                    && a.getDate() === b.getDate();
            }

            // Cek apakah tanggal ada di rentang (eksklusif)
            function isBetween(d, start, end) {
                if (!start || !end) return false;
                var t = d.getTime();
                return t > start.getTime() && t < end.getTime();
            }

            // Cek apakah tanggal ada di rentang hari pakai (ambil+1 s/d kembali-1)
            function isHariPakai(d, ambil, kembali) {
                if (!ambil || !kembali) return false;
                var mulai = new Date(ambil.getTime());
                mulai.setDate(mulai.getDate() + 1);
                var akhir = new Date(kembali.getTime());
                akhir.setDate(akhir.getDate() - 1);
                if (mulai.getTime() > akhir.getTime()) return false;
                var t = d.getTime();
                return t >= mulai.getTime() && t <= akhir.getTime();
            }

            function renderKalender() {
                labelBulan.textContent = namaBulan[kalender.bulan] + ' ' + kalender.tahun;
                gridKalender.innerHTML = '';

                var firstDay = new Date(kalender.tahun, kalender.bulan, 1).getDay();
                var daysInMonth = new Date(kalender.tahun, kalender.bulan + 1, 0).getDate();
                var daysPrevMonth = new Date(kalender.tahun, kalender.bulan, 0).getDate();

                // Cell bulan sebelumnya (disabled)
                for (var i = 0; i < firstDay; i++) {
                    var elPrev = document.createElement('span');
                    elPrev.className = 'py-2 text-gray-300 select-none';
                    elPrev.textContent = daysPrevMonth - firstDay + i + 1;
                    gridKalender.appendChild(elPrev);
                }

                // Cell bulan ini
                for (var d = 1; d <= daysInMonth; d++) {
                    (function (day) {
                        var tanggal = new Date(kalender.tahun, kalender.bulan, day);
                        var btn = document.createElement('button');
                        btn.type = 'button';
                        btn.textContent = day;
                        btn.dataset.tanggal = keyTanggal(tanggal);

                        var kelas = 'py-2 rounded-md transition select-none ';

                        var dibooking = isDibooking(tanggal);
                        var isAmbil   = sameDay(tanggal, kalender.ambil);
                        var isKembali = sameDay(tanggal, kalender.kembali);
                        var isPakai   = isHariPakai(tanggal, kalender.ambil, kalender.kembali);

                        if (dibooking) {
                            kelas += 'bg-[#e5d9c4] text-[#15233d] font-semibold cursor-not-allowed';
                            btn.disabled = true;
                        } else if (isAmbil || isKembali) {
                            kelas += 'bg-[#15110d] text-[#d4a63d] font-semibold hover:bg-[#2a2118]';
                        } else if (isPakai) {
                            kelas += 'bg-[#f5e6e6] text-[#7a1424] font-semibold hover:bg-[#efd6d6]';
                        } else {
                            kelas += 'text-[#15233d] hover:bg-[#f5efe2]';
                        }

                        btn.className = kelas;
                        btn.addEventListener('click', function () { pilihTanggal(tanggal); });
                        gridKalender.appendChild(btn);
                    })(d);
                }

                // Cell sisa
                var totalCell = firstDay + daysInMonth;
                var sisa = (7 - (totalCell % 7)) % 7;
                for (var s = 1; s <= sisa; s++) {
                    var elNext = document.createElement('span');
                    elNext.className = 'py-2 text-gray-300 select-none';
                    elNext.textContent = s;
                    gridKalender.appendChild(elNext);
                }

                btnPrev.disabled = (kalender.tahun === kalender.minTahun && kalender.bulan <= kalender.minBulan);
                btnNext.disabled = false;
            }

            function pilihTanggal(tanggal) {
                // Belum ada ambil, atau sudah lengkap → mulai pilih ambil baru
                if (!kalender.ambil || (kalender.ambil && kalender.kembali)) {
                    kalender.ambil = tanggal;
                    kalender.kembali = null;
                } else {
                    if (tanggal.getTime() < kalender.ambil.getTime()) {
                        // Klik sebelum ambil → jadikan ambil baru
                        kalender.ambil = tanggal;
                        kalender.kembali = null;
                    } else if (tanggal.getTime() === kalender.ambil.getTime()) {
                        // Klik tanggal sama → reset
                        kalender.ambil = null;
                        kalender.kembali = null;
                    } else {
                        // Minimal 3 hari (ambil, pakai, kembali)
                        var selisih = Math.round((tanggal - kalender.ambil) / (1000 * 60 * 60 * 24));
                        if (selisih < 2) {
                            alert('Minimal sewa 3 hari: 1 hari ambil, 1 hari pakai, 1 hari kembali.');
                            return;
                        }

                        // Cek bentrok tanggal dibooking
                        var adaBentrok = false;
                        var cur = new Date(kalender.ambil.getTime());
                        cur.setDate(cur.getDate() + 1);
                        while (cur.getTime() <= tanggal.getTime()) {
                            if (isDibooking(cur)) { adaBentrok = true; break; }
                            cur.setDate(cur.getDate() + 1);
                        }

                        if (adaBentrok) {
                            alert('Tidak bisa memilih rentang ini karena ada tanggal yang sudah dibooking di antaranya.');
                            return;
                        }

                        kalender.kembali = tanggal;
                    }
                }

                renderKalender();
                perbaruiRingkasan();
            }

            function perbaruiRingkasan() {
                ringkasAmbil.textContent   = kalender.ambil ? formatTanggal(kalender.ambil) : 'Klik tanggal';
                ringkasKembali.textContent = kalender.kembali ? formatTanggal(kalender.kembali) : '—';

                if (kalender.ambil && kalender.kembali) {
                    // Hari pakai = (ambil + 1) s/d (kembali - 1)
                    var mulaiPakai = new Date(kalender.ambil.getTime());
                    mulaiPakai.setDate(mulaiPakai.getDate() + 1);

                    var akhirPakai = new Date(kalender.kembali.getTime());
                    akhirPakai.setDate(akhirPakai.getDate() - 1);

                    if (mulaiPakai.getTime() > akhirPakai.getTime()) {
                        ringkasSewa.textContent   = 'Tidak ada hari pakai';
                        ringkasDurasi.textContent = '(pilih minimal 3 hari)';
                        return;
                    }

                    var tglMulai     = mulaiPakai.getDate();
                    var tglAkhir     = akhirPakai.getDate();
                    var bulanMulai   = namaBulan[mulaiPakai.getMonth()];
                    var bulanAkhir   = namaBulan[akhirPakai.getMonth()];
                    var tahunMulai   = mulaiPakai.getFullYear();
                    var tahunAkhir   = akhirPakai.getFullYear();

                    var rentangSewa;
                    if (tahunMulai === tahunAkhir && bulanMulai === bulanAkhir && tglMulai === tglAkhir) {
                        // Satu hari pakai
                        rentangSewa = tglMulai + ' ' + bulanMulai + ' ' + tahunMulai;
                    } else if (tahunMulai === tahunAkhir && bulanMulai === bulanAkhir) {
                        // Sama bulan
                        rentangSewa = tglMulai + ' – ' + tglAkhir + ' ' + bulanMulai + ' ' + tahunMulai;
                    } else if (tahunMulai === tahunAkhir) {
                        // Beda bulan, sama tahun
                        rentangSewa = tglMulai + ' ' + bulanMulai + ' – ' + tglAkhir + ' ' + bulanAkhir + ' ' + tahunMulai;
                    } else {
                        // Beda tahun
                        rentangSewa = tglMulai + ' ' + bulanMulai + ' ' + tahunMulai + ' – ' + tglAkhir + ' ' + bulanAkhir + ' ' + tahunAkhir;
                    }

                    var jumlahHari = Math.round((akhirPakai - mulaiPakai) / (1000 * 60 * 60 * 24)) + 1;

                    ringkasSewa.textContent   = rentangSewa;
                    ringkasDurasi.textContent = '(' + jumlahHari + ' hari pakai)';
                } else {
                    ringkasSewa.textContent   = '—';
                    ringkasDurasi.textContent = '';
                }
            }

            btnPrev.addEventListener('click', function () {
                if (btnPrev.disabled) return;
                kalender.bulan--;
                if (kalender.bulan < 0) { kalender.bulan = 11; kalender.tahun--; }
                renderKalender();
            });

            btnNext.addEventListener('click', function () {
                kalender.bulan++;
                if (kalender.bulan > 11) { kalender.bulan = 0; kalender.tahun++; }
                renderKalender();
            });

            renderKalender();
            perbaruiRingkasan();

            // =====================================================
            // GALERI
            // =====================================================
            var fotoUtama  = document.getElementById('fotoUtama');
            var fotoImg    = document.getElementById('fotoUtamaImg');
            var fotoKosong = document.getElementById('fotoUtamaKosong');
            var thumbs     = document.querySelectorAll('[data-thumb]');

            function tandai(el, aktif, kelasAktif, kelasNonaktif) {
                kelasAktif.forEach(function (c) { el.classList.toggle(c, aktif); });
                kelasNonaktif.forEach(function (c) { el.classList.toggle(c, !aktif); });
            }

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

            // =====================================================
            // VARIAN & UKURAN
            // =====================================================
            var varianBtns = document.querySelectorAll('[data-varian]');

            function stokSaatIni() {
                return parseInt(varianBtns[state.varian].dataset.stok, 10) || 0;
            }

            function perbaruiStok() {
                var v = varianBtns[state.varian];
                document.getElementById('stokJumlah').textContent = stokSaatIni() + ' set';
                document.getElementById('stokDetail').textContent = '(' + v.dataset.nama + ', ukuran ' + state.ukuran + ')';
            }

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

        })();
    </script>

</body>
</html>