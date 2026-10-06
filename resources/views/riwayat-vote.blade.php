<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Riwayat Vote - ALNAY.ID</title>

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
         DATA RIWAYAT VOTE
    ========================================================== -->

    @php

        // =====================================================
        // OPSI 1 — DATA MANUAL / STATIS (AKTIF)
        // Dipakai selama tahap pengembangan UI/UX, belum ada DB.
        // =====================================================

        $ringkasan = [
            'total_vote'  => 37,
            'total_event' => 2,
            'total_biaya' => 'Rp 185.000',
        ];

        // Status yang dipakai: 'Berhasil', 'Menunggu', 'Gagal'
        $riwayat = [
            [
                'event'    => 'Pemilihan Duta Budaya 2024',
                'nomor'    => '01',
                'kandidat' => 'Raden Roro Ayu',
                'asal'     => 'Jawa Tengah',
                'foto'     => 'kandidat1.jpg',
                'jumlah'   => 10,
                'total'    => 'Rp 50.000',
                'tanggal'  => '24 Sep 2026, 19:42',
                'status'   => 'Berhasil',
            ],
            [
                'event'    => 'Pemilihan Duta Budaya 2024',
                'nomor'    => '02',
                'kandidat' => 'Made Wirawan',
                'asal'     => 'Bali',
                'foto'     => 'kandidat2.jpg',
                'jumlah'   => 5,
                'total'    => 'Rp 25.000',
                'tanggal'  => '22 Sep 2026, 08:15',
                'status'   => 'Berhasil',
            ],
            [
                'event'    => 'Pemilihan Duta Budaya 2024',
                'nomor'    => '03',
                'kandidat' => 'Siti Nurbaya',
                'asal'     => 'Sumatera Barat',
                'foto'     => 'kandidat3.jpg',
                'jumlah'   => 2,
                'total'    => 'Rp 10.000',
                'tanggal'  => '21 Sep 2026, 21:03',
                'status'   => 'Menunggu',
            ],
            [
                'event'    => 'Lomba Foto Baju Adat Cilik',
                'nomor'    => '04',
                'kandidat' => 'Bimo Susanto',
                'asal'     => 'DKI Jakarta',
                'foto'     => 'kandidat4.jpg',
                'jumlah'   => 20,
                'total'    => 'Rp 100.000',
                'tanggal'  => '12 Agu 2026, 14:27',
                'status'   => 'Berhasil',
            ],
            [
                'event'    => 'Lomba Foto Baju Adat Cilik',
                'nomor'    => '04',
                'kandidat' => 'Bimo Susanto',
                'asal'     => 'DKI Jakarta',
                'foto'     => 'kandidat4.jpg',
                'jumlah'   => 3,
                'total'    => 'Rp 15.000',
                'tanggal'  => '10 Agu 2026, 09:50',
                'status'   => 'Gagal',
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

        $authUser = auth()->user();
        $votes    = $authUser->votes()->with('kandidat.event')->latest()->get();

        $ringkasan = [
            'total_vote'  => $votes->where('status', 'Berhasil')->sum('jumlah'),
            'total_event' => $votes->pluck('kandidat.event_id')->unique()->count(),
            'total_biaya' => 'Rp ' . number_format($votes->where('status', 'Berhasil')->sum('total'), 0, ',', '.'),
        ];

        $riwayat = [];
        foreach ($votes as $v) {
            $riwayat[] = [
                'event'    => $v->kandidat->event->judul,
                'nomor'    => $v->kandidat->nomor,
                'kandidat' => $v->kandidat->nama,
                'asal'     => $v->kandidat->asal,
                'foto'     => $v->kandidat->foto,
                'jumlah'   => $v->jumlah,
                'total'    => 'Rp ' . number_format($v->total, 0, ',', '.'),
                'tanggal'  => $v->created_at->translatedFormat('d M Y, H:i'),
                'status'   => $v->status,
            ];
        }
        */

        // Gaya badge untuk tiap status
        $badge = [
            'Berhasil' => 'bg-[#e8f5ec] border-[#cfe8d8] text-[#2f7a4a]',
            'Menunggu' => 'bg-[#fbf6e9] border-[#eee5d2] text-[#c99a32]',
            'Gagal'    => 'bg-[#fdecec] border-[#f5cfcf] text-[#c0392b]',
        ];

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

        <!-- Profil Pengguna (aktif) -->
        <a href="{{ route('profil-user') }}" aria-label="Profil pengguna"
            class="flex h-9 w-9 items-center justify-center rounded-full border border-[#c99a32] bg-[#fbf6e9] text-[#c99a32] transition hover:bg-[#f7f0dc]">
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

    <main class="max-w-3xl mx-auto px-5 pt-6 pb-14">


        <!-- =====================================================
             TOMBOL KEMBALI
        ====================================================== -->

        <a href="{{ route('profil-user') }}"
            class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-[#c99a32] transition">
            ← Kembali ke Profil
        </a>


        <!-- =====================================================
             JUDUL HALAMAN
        ====================================================== -->

        <section class="mt-4">

            <h1 class="font-display text-2xl md:text-3xl font-bold text-[#111827]">
                Riwayat Vote Saya
            </h1>

            <p class="text-gray-500 text-xs md:text-sm mt-1.5">
                Daftar seluruh vote yang pernah Anda berikan di Event ALNAY.ID.
            </p>

        </section>


        <!-- =====================================================
             RINGKASAN
        ====================================================== -->

        <section class="mt-6 bg-white rounded-xl border border-gray-100 shadow-sm p-5">

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">

                <div class="bg-[#faf8f2] rounded-lg py-6 text-center">

                    <p class="font-display text-3xl font-bold text-[#c99a32]">
                        {{ $ringkasan['total_vote'] }}
                    </p>

                    <p class="text-xs text-gray-500 mt-1">
                        Total Vote Diberikan
                    </p>

                </div>

                <div class="bg-[#faf8f2] rounded-lg py-6 text-center">

                    <p class="font-display text-3xl font-bold text-[#c99a32]">
                        {{ $ringkasan['total_event'] }}
                    </p>

                    <p class="text-xs text-gray-500 mt-1">
                        Event yang telah Diikuti
                    </p>

                </div>

                <div class="bg-[#faf8f2] rounded-lg py-6 text-center">

                    <p class="font-display text-3xl font-bold text-[#c99a32]">
                        {{ $ringkasan['total_biaya'] }}
                    </p>

                    <p class="text-xs text-gray-500 mt-1">
                        Total Pembayaran
                    </p>

                </div>

            </div>

        </section>


        <!-- =====================================================
             DAFTAR RIWAYAT
        ====================================================== -->

        <section class="mt-7">


            <!-- TITLE + SEARCH -->

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                <h2 class="font-display text-lg md:text-xl font-bold text-[#15233d]">
                    Semua Transaksi Vote
                </h2>


                <div class="flex gap-2.5">

                    <!-- Search -->

                    <div class="relative flex-1">

                        <input
                            type="text"
                            placeholder="Cari kandidat / event..."
                            class="w-full sm:w-60 h-10 pl-9 pr-4 rounded-md border border-gray-300 bg-white text-sm focus:outline-none focus:ring-1 focus:ring-[#c99a32]"
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


                    <!-- Filter -->

                    <button
                        class="h-10 px-4 bg-white border border-gray-300 rounded-md text-sm font-medium hover:bg-gray-50 transition">

                        <span class="mr-1.5">☷</span>
                        Filter

                    </button>

                </div>

            </div>


            <!-- LIST -->

            <div class="mt-3.5 bg-white rounded-xl border border-gray-100 shadow-sm divide-y divide-gray-100">

                @forelse($riwayat as $r)

                    <div class="flex items-center gap-3.5 p-4">

                        <!-- FOTO -->

                        <div class="relative w-14 h-14 shrink-0 rounded-lg overflow-hidden">

                            <img
                                src="{{ asset('image/event/kandidat/' . $r['foto']) }}"
                                alt="{{ $r['kandidat'] }}"
                                class="w-full h-full object-cover"
                            >

                            <span class="absolute top-1 left-1 bg-white px-1.5 py-0.5 rounded text-[9px] font-semibold text-[#15233d] shadow-sm">
                                {{ $r['nomor'] }}
                            </span>

                        </div>


                        <!-- INFORMASI -->

                        <div class="flex-1 min-w-0">

                            <div class="flex items-center gap-2">

                                <h3 class="font-semibold text-sm text-[#15233d] truncate">
                                    {{ $r['kandidat'] }}
                                </h3>

                                <span class="hidden sm:inline text-[10px] font-semibold text-[#c99a32] bg-[#fbf6e9] px-2 py-1 rounded whitespace-nowrap">
                                    {{ $r['asal'] }}
                                </span>

                            </div>

                            <p class="text-xs text-gray-500 mt-0.5 truncate">
                                {{ $r['event'] }}
                            </p>

                            <p class="text-[11px] text-gray-400 mt-0.5">
                                {{ $r['tanggal'] }}
                            </p>

                        </div>


                        <!-- JUMLAH & STATUS -->

                        <div class="text-right shrink-0">

                            <p class="text-sm font-bold text-[#15233d]">
                                {{ $r['jumlah'] }} vote
                            </p>

                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ $r['total'] }}
                            </p>

                            <span class="inline-flex mt-1.5 px-2.5 py-0.5 rounded-full border text-[10px] font-semibold {{ $badge[$r['status']] ?? $badge['Menunggu'] }}">
                                {{ $r['status'] }}
                            </span>

                        </div>

                    </div>

                @empty

                    <div class="py-12 text-center">

                        <p class="text-sm font-semibold text-[#15233d]">
                            Belum ada riwayat vote
                        </p>

                        <p class="text-xs text-gray-500 mt-1">
                            Vote kandidat favoritmu di event yang sedang berlangsung.
                        </p>

                        <a href="{{ route('dashboard-user') }}"
                            class="inline-block mt-4 px-5 py-2 rounded-md bg-[#c69220] hover:bg-[#b58219] text-white text-sm font-medium transition">
                            Lihat Event
                        </a>

                    </div>

                @endforelse

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

        </section>

    </main>


</body>
</html>