<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event & Vote Photo - ALNAY.ID</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        display: ['Georgia', 'Times New Roman', 'serif']
                    },
                    colors: {
                        alnay: '#C9962E'
                    },
                    boxShadow: {
                        card: '0 12px 28px rgba(0,0,0,.16)'
                    }
                }
            }
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            background: #fff;
        }

        .display-font {
            font-family: "Playfair Display", Georgia, serif;
        }

        .card-shadow {
            box-shadow: 0 12px 28px rgba(0, 0, 0, .16);
        }
    </style>
</head>

<body class="text-[#17233b] antialiased">

@php

    // =====================================================
    // OPSI 1 — DATA MANUAL / STATIS (AKTIF)
    // Dipakai selama tahap pengembangan UI/UX, belum ada DB.
    // Field 'id' disertakan sejak sekarang supaya nanti saat
    // Opsi 2 (database) aktif, link ke detail event tinggal
    // dipasangi ID tanpa perlu mengubah struktur di bawah.
    // =====================================================

    $eventBerlangsung = [
        'id'              => 1,
        'judul'           => 'Pemilihan Brand Ambassador ALNAY.ID',
        'foto'            => 'event-alnay1.png',
        'deskripsi'       => 'Ajang bergengsi tahunan untuk mencari representasi keanggunan budaya wanita Indonesia. Dukung kandidat favorit Anda dan jadilah bagian dari perjalanan Brand Ambassador ALNAY.ID.',
        'tanggal_mulai'   => '1 Okt 2026',
        'tanggal_selesai' => '1 Des 2026',
        'sisa_waktu'      => '12 Hari',
        'jumlah_peserta'  => '45',
        'total_suara'     => '15.4K',
    ];

    $eventAkanDatang = [
        'id'                   => 2,
        'judul'                => 'Festival Karya Batik Modern',
        'foto'                 => 'event-alnay2.png',
        'deskripsi'            => 'Pameran karya desainer lokal yang memadukan teknik batik tulis klasik dengan siluet pakaian modern.',
        'batas_registrasi'     => '30 Nov 2026',
        'batas_registrasi_raw' => '2026-11-30', // format Y-m-d, dipakai untuk perbandingan tanggal
        'syarat_peserta'       => 'Usia 17–28 Tahun / Perias Adat',
        'acara_mulai'          => '1 Feb 2027',
        'acara_selesai'        => '1 Apr 2027',
    ];

    // Kasus 5: pendaftaran sudah tutup, tapi acara belum mulai (masa jeda).
    // Dihitung dari perbandingan tanggal hari ini dengan batas_registrasi_raw.
    $pendaftaranTutup = $eventAkanDatang
        ? strtotime($eventAkanDatang['batas_registrasi_raw']) < strtotime('today')
        : false;

    // Tiga besar sementara pada event yang sedang berlangsung
    $peringkatTerkini = [
        ['nomor' => '01', 'nama' => 'Raden Roro Ayu', 'inisial' => 'RR', 'persen' => '29.35'],
        ['nomor' => '02', 'nama' => 'Made Wirawan',   'inisial' => 'MW', 'persen' => '19.39'],
        ['nomor' => '03', 'nama' => 'Siti Nurbaya',   'inisial' => 'SN', 'persen' => '19.06'],
    ];

    // Tahapan pada bagian "Alur Event"
    $alurEvent = [
        [
            'nomor'     => 1,
            'judul'     => 'Pendaftaran',
            'deskripsi' => 'Daftarkan diri sebagai peserta atau finalis sebelum batas registrasi ditutup.',
        ],
        [
            'nomor'     => 2,
            'judul'     => 'Vote Berlangsung',
            'deskripsi' => 'Dukung peserta favoritmu lewat halaman event selama masa voting berlangsung.',
        ],
        [
            'nomor'     => 3,
            'judul'     => 'Pengumuman Pemenang',
            'deskripsi' => 'Peserta dengan suara tertinggi diumumkan sebagai pemenang event.',
        ],
    ];

    // Daftar pertanyaan umum
    $faq = [
        [
            'pertanyaan' => 'Apa itu Event & Vote Photo?',
            'jawaban'    => 'Event & Vote Photo adalah rangkaian ajang budaya ALNAY.ID di mana pengunjung dapat mendukung kandidat favorit mereka melalui sistem vote.',
        ],
        [
            'pertanyaan' => 'Siapa yang boleh mendaftar?',
            'jawaban'    => 'Pendaftaran terbuka sesuai syarat dan ketentuan tiap event, umumnya mengacu pada rentang usia dan kategori peserta yang tertera di halaman pendaftaran.',
        ],
        [
            'pertanyaan' => 'Bagaimana cara memberi vote?',
            'jawaban'    => 'Buka halaman detail event, pilih kandidat favoritmu, lalu ikuti langkah pembayaran vote yang tersedia di halaman tersebut.',
        ],
        [
            'pertanyaan' => 'Kapan pemenang diumumkan?',
            'jawaban'    => 'Pemenang diumumkan setelah masa voting resmi ditutup sesuai jadwal yang tertera pada masing-masing event.',
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
    // Kolom yang perlu ada di tabel `events`:
    // id, judul, foto, deskripsi, status ('berlangsung' / 'akan_datang'),
    // tanggal_mulai, tanggal_selesai, sisa_waktu, jumlah_peserta, total_suara,
    // batas_registrasi, batas_registrasi_raw (tipe DATE),
    // acara_mulai, acara_selesai.
    //
    // Penanganan 5 kondisi:
    // 1. Hanya event akan datang        -> $eventBerlangsung otomatis null
    // 2. Hanya event berlangsung        -> $eventAkanDatang otomatis null
    // 3. Keduanya ada                   -> tampil normal
    // 4. Tidak ada event sama sekali    -> keduanya null, fallback tampil
    // 5. Pendaftaran tutup, acara jeda  -> $pendaftaranTutup jadi true,
    //    tombol daftar otomatis nonaktif tanpa perlu ganti status manual
    // =====================================================

    $qBerlangsung = $pdo->query("SELECT * FROM events WHERE status = 'berlangsung' LIMIT 1");
    $rowBerlangsung = $qBerlangsung->fetch(PDO::FETCH_ASSOC);

    $eventBerlangsung = $rowBerlangsung ? [
        'id'              => $rowBerlangsung['id'],
        'judul'           => $rowBerlangsung['judul'],
        'foto'            => $rowBerlangsung['foto'],
        'deskripsi'       => $rowBerlangsung['deskripsi'],
        'tanggal_mulai'   => $rowBerlangsung['tanggal_mulai'],
        'tanggal_selesai' => $rowBerlangsung['tanggal_selesai'],
        'sisa_waktu'      => $rowBerlangsung['sisa_waktu'],
        'jumlah_peserta'  => $rowBerlangsung['jumlah_peserta'],
        'total_suara'     => $rowBerlangsung['total_suara'] >= 1000
                                ? number_format($rowBerlangsung['total_suara'] / 1000, 1) . 'K'
                                : $rowBerlangsung['total_suara'],
    ] : null;

    $qAkanDatang = $pdo->query("SELECT * FROM events WHERE status = 'akan_datang' LIMIT 1");
    $rowAkanDatang = $qAkanDatang->fetch(PDO::FETCH_ASSOC);

    $eventAkanDatang = $rowAkanDatang ? [
        'id'                   => $rowAkanDatang['id'],
        'judul'                => $rowAkanDatang['judul'],
        'foto'                 => $rowAkanDatang['foto'],
        'deskripsi'            => $rowAkanDatang['deskripsi'],
        'batas_registrasi'     => $rowAkanDatang['batas_registrasi'],
        'batas_registrasi_raw' => $rowAkanDatang['batas_registrasi_raw'],
        'syarat_peserta'       => $rowAkanDatang['syarat_peserta'],
        'acara_mulai'          => $rowAkanDatang['acara_mulai'],
        'acara_selesai'        => $rowAkanDatang['acara_selesai'],
    ] : null;

    $pendaftaranTutup = $eventAkanDatang
        ? strtotime($eventAkanDatang['batas_registrasi_raw']) < strtotime('today')
        : false;

    $peringkatTerkini = [];

    if ($eventBerlangsung) {
        $qPeringkat = $pdo->prepare("SELECT * FROM kandidat WHERE event_id = ? ORDER BY persen DESC LIMIT 3");
        $qPeringkat->execute([$eventBerlangsung['id']]);

        foreach ($qPeringkat->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $peringkatTerkini[] = [
                'nomor'   => $row['nomor'],
                'nama'    => $row['nama'],
                'inisial' => strtoupper(substr($row['nama'], 0, 2)),
                'persen'  => $row['persen'],
            ];
        }
    }

    // $alurEvent dan $faq biasanya tetap statis karena isinya konten tetap,
    // tidak perlu diambil dari database. Kalau nanti admin ingin mengelola
    // FAQ sendiri, tinggal ganti $faq dengan hasil query ke tabel 'faqs',
    // dalam bentuk array yang sama (pertanyaan, jawaban).
    */

    // Path foto:
    // - Opsi 1 (statis)  : public/image/event/{foto}
    // - Opsi 2 (database, upload admin via storage) sebaiknya pakai:
    //   asset('storage/event/' . $eventBerlangsung['foto'])
    //   dengan syarat sudah menjalankan `php artisan storage:link`.

    // =====================================================
    // TAB "Event Terkini" / "Event Sebelumnya"
    // Dibaca dari query string ?tab=terkini / ?tab=sebelumnya,
    // default ke 'terkini' kalau tidak ada parameter sama sekali.
    // =====================================================

    $tab = request()->query('tab', 'terkini');

    // =====================================================
    // OPSI 1 — DATA MANUAL / STATIS (AKTIF)
    // Daftar event yang sudah berakhir, ditampilkan di tab
    // "Event Sebelumnya". Field 'foto' boleh null/kosong kalau
    // fotonya belum diupload, nanti otomatis tampil placeholder.
    // =====================================================

    $eventSebelumnya = [
        [
            'id'      => 3,
            'judul'   => 'Lomba Foto Baju Adat Cilik',
            'foto'    => 'event-alnay4.png',
            'tanggal' => 'Agustus 2026',
            'juara'   => 'Raden Roro Ayu',
        ],
        [
            'id'      => 4,
            'judul'   => 'Pemilihan Duta Budaya 2025',
            'foto'    => 'event-alnay3.png',
            'tanggal' => 'Desember 2025',
            'juara'   => 'Made Wirawan',
        ],
    ];

    /*
    // OPSI 2 — DATA DINAMIS DARI DATABASE (NONAKTIF)
    $qSebelumnya = $pdo->query("SELECT * FROM events WHERE status = 'selesai' ORDER BY tanggal_selesai DESC");

    $eventSebelumnya = [];
    foreach ($qSebelumnya->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $eventSebelumnya[] = [
            'id'      => $row['id'],
            'judul'   => $row['judul'],
            'foto'    => $row['foto'], // null kalau kolomnya kosong
            'tanggal' => $row['tanggal_selesai'],
            'juara'   => $row['nama_juara_1'],
        ];
    }
    */

@endphp

<!-- HEADER -->
<header class="sticky top-0 z-50 border-b border-[#e9e9e9] bg-white/95 backdrop-blur">
    <div class="mx-auto flex h-[56px] max-w-[1200px] items-center justify-between px-5 md:px-8">
        <a href="{{ route('dashboard-user') }}" class="display-font text-[16px] font-bold tracking-tight text-[#13223a]">
            EVENTALNAY.ID
        </a>

        <a href="{{ route('profil-user') }}" aria-label="Profil pengguna"
            class="flex h-9 w-9 items-center justify-center rounded-full border border-[#d9d9d9] bg-white text-[#333] transition hover:bg-[#f7f7f7]">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                <circle cx="12" cy="8" r="3.5"></circle>
                <path d="M5.5 20c.8-3.2 3-5 6.5-5s5.7 1.8 6.5 5"></path>
            </svg>
        </a>
    </div>
</header>

<!-- HERO -->
<section class="px-5 pb-8 pt-6 md:px-8 md:pt-8">
    <div class="mx-auto flex max-w-[1200px] flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="display-font font-bold text-[32px] leading-tight text-[#111827] md:text-[36px]">
                Event &amp; Vote Photo
            </h1>

            <p class="mt-3 max-w-[560px] text-[14px] leading-6 text-[#6b6b6b] md:text-[15px]">
                Dukung perwakilan terbaik dan ikuti berbagai perayaan budaya yang menginspirasi.
            </p>
        </div>

        <div class="flex shrink-0 gap-2">
            <a href="{{ route('dashboard-user', ['tab' => 'terkini']) }}"
    class="rounded-full px-4 py-2 text-[12px] font-semibold transition {{ $tab === 'terkini' ? 'bg-black text-[#d4a63d]' : 'border border-[#dedede] bg-white text-[#333] hover:bg-[#f7f7f7]' }}">
    Event Terkini
</a>

<a href="{{ route('dashboard-user', ['tab' => 'sebelumnya']) }}"
    class="rounded-full px-4 py-2 text-[12px] font-semibold transition {{ $tab === 'sebelumnya' ? 'bg-black text-[#d4a63d]' : 'border border-[#dedede] bg-white text-[#333] hover:bg-[#f7f7f7]' }}">
    Event Sebelumnya
</a>
        </div>
    </div>
</section>

<!-- KONTEN TAB -->
<main class="mx-auto max-w-[1200px] px-5 pb-6 md:px-8">

@if($tab === 'terkini')

    <!-- EVENT SEDANG BERLANGSUNG -->
    @if($eventBerlangsung)

        <article class="card-shadow overflow-hidden rounded-[10px] border border-[#ededed] bg-white">

            <div class="relative h-[260px] sm:h-[340px] md:h-[420px]">
                <img
                    src="{{ asset('image/event/' . $eventBerlangsung['foto']) }}"
                    alt="{{ $eventBerlangsung['judul'] }}"
                    class="h-full w-full object-cover">

                <span class="absolute right-4 top-4 rounded-full bg-[#c99a3a] px-3 py-1.5 text-[10px] font-semibold text-white shadow-sm">
                    ●&nbsp; Sedang Berlangsung
                </span>
            </div>

            <div class="flex flex-col gap-6 p-6 sm:p-7 md:flex-row md:items-center md:justify-between">

                <div class="max-w-[560px]">
                    <h2 class="text-[19px] font-bold leading-6 text-[#13223a] md:text-[20px]">
                        {{ $eventBerlangsung['judul'] }}
                    </h2>

                    <p class="mt-3 text-[13px] leading-[1.65] text-[#666]">
                        {{ $eventBerlangsung['deskripsi'] }}
                    </p>

                    <div class="mt-4 flex items-center gap-2 text-[12px] text-[#666]">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                            stroke="#C9962E" stroke-width="1.7">
                            <rect x="3" y="4.5" width="18" height="17" rx="2"></rect>
                            <path d="M16 2.5v4M8 2.5v4M3 9h18"></path>
                        </svg>

                        <span>
                            {{ $eventBerlangsung['tanggal_mulai'] }}
                            -
                            {{ $eventBerlangsung['tanggal_selesai'] }}
                        </span>
                    </div>
                </div>

                <div class="w-full shrink-0 md:w-[360px]">
                    <div class="grid grid-cols-3 overflow-hidden rounded-[10px] border border-[#ece5d7] bg-[#fcfaf6] text-center">
                        <div class="px-2 py-3">
                            <div class="text-[9px] text-[#888]">Sisa Waktu</div>
                            <div class="mt-1 text-[13px] font-bold text-[#27303d]">
                                {{ $eventBerlangsung['sisa_waktu'] }}
                            </div>
                        </div>

                        <div class="border-x border-[#e8e1d5] px-2 py-3">
                            <div class="text-[9px] text-[#888]">Peserta</div>
                            <div class="mt-1 text-[13px] font-bold text-[#27303d]">
                                {{ $eventBerlangsung['jumlah_peserta'] }}
                            </div>
                        </div>

                        <div class="px-2 py-3">
                            <div class="text-[9px] text-[#888]">Total Suara</div>
                            <div class="mt-1 text-[13px] font-bold text-[#c87918]">
                                {{ $eventBerlangsung['total_suara'] }}
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('detail-event') }}"
                        class="mt-4 block w-full rounded-[4px] bg-[#cf9b32] py-3 text-center text-[12px] font-semibold text-white transition hover:bg-[#b88625]">
                        Lihat Event &amp; Vote Peserta <span class="ml-1">→</span>
                    </a>
                </div>

            </div>
        </article>

    @else

        <div class="card-shadow rounded-[10px] border border-dashed border-gray-200 bg-white p-10 text-center">
            <p class="text-sm font-semibold text-[#15233d]">
                Belum ada event yang sedang berlangsung
            </p>
            <p class="mt-1 text-xs text-gray-400">
                Event terbaru akan tampil di sini begitu admin mengaktifkannya.
            </p>
        </div>

    @endif


    <!-- PENDAFTARAN DIBUKA -->
    <section class="mt-14">

        <h2 class="display-font text-2xl font-bold text-[#111827] md:text-[26px]">
            Pendaftaran Dibuka
        </h2>

        <p class="mt-1 text-[13px] text-[#6b6b6b] md:text-[14px]">
            Event berikutnya sudah bisa didaftari selama voting berjalan
        </p>

        @if($eventAkanDatang)

            <article class="card-shadow mt-5 overflow-hidden rounded-[10px] border border-[#ededed] bg-white">
                <div class="grid grid-cols-1 gap-5 p-5 md:grid-cols-2 md:gap-8 md:p-6">

                    <div class="relative h-[220px] self-start overflow-hidden rounded-[10px] md:h-[240px]">
                        <img
                            src="{{ asset('image/event/' . $eventAkanDatang['foto']) }}"
                            alt="{{ $eventAkanDatang['judul'] }}"
                            class="h-full w-full object-cover">

                        <span class="absolute left-3 top-3 rounded-full bg-black px-3 py-1.5 text-[10px] font-semibold text-white">
                            ●&nbsp; {{ $pendaftaranTutup ? 'Menunggu Acara Dimulai' : 'Akan Datang' }}
                        </span>
                    </div>

                    <div class="flex flex-col justify-center gap-3">
                        <h3 class="text-[18px] font-bold leading-6 text-[#13223a] md:text-[19px]">
                            {{ $eventAkanDatang['judul'] }}
                        </h3>

                        <p class="max-w-[560px] text-[13px] leading-[1.65] text-[#666]">
                            {{ $eventAkanDatang['deskripsi'] }}
                        </p>

                        <div class="flex flex-wrap gap-x-8 gap-y-1 text-[13px] text-[#444]">
                            <div>
                                Daftar sampai
                                <span class="font-bold text-[#13223a]">{{ $eventAkanDatang['batas_registrasi'] }}</span>
                                @if($pendaftaranTutup)
                                    <span class="ml-1 text-[11px] font-semibold text-[#c0392b]">(Ditutup)</span>
                                @endif
                            </div>

                            <div>
                                {{ $eventAkanDatang['syarat_peserta'] }}
                            </div>
                        </div>

                        <div class="text-[13px] text-[#444]">
                            Acara
                            <span class="font-bold text-[#13223a]">
                                {{ $eventAkanDatang['acara_mulai'] }} - {{ $eventAkanDatang['acara_selesai'] }}
                            </span>
                        </div>

                        <div class="mt-3 flex flex-wrap gap-2.5">

                            @if(!$pendaftaranTutup)

                                <a href="#"
                                    class="rounded-[5px] bg-black px-5 py-2.5 text-[12px] font-bold text-[#d4a63d] transition hover:bg-[#222]">
                                    Daftar Sebagai Peserta / Finalis <span class="ml-1">→</span>
                                </a>

                            @else

                                <span class="rounded-[5px] bg-gray-100 px-5 py-2.5 text-[12px] font-bold text-gray-400">
                                    Pendaftaran Telah Ditutup
                                </span>

                            @endif

                            <a href="#"
                                class="rounded-[5px] border border-[#dedede] bg-white px-5 py-2.5 text-[12px] font-semibold text-[#333] transition hover:bg-[#f7f7f7]">
                                Lihat Syarat &amp; Ketentuan
                            </a>
                        </div>
                    </div>

                </div>
            </article>

        @else

            <div class="card-shadow mt-5 rounded-[10px] border border-dashed border-gray-200 bg-white p-10 text-center">
                <p class="text-sm font-semibold text-[#15233d]">
                    Belum ada pendaftaran event yang dibuka
                </p>
                <p class="mt-1 text-xs text-gray-400">
                    Pantau terus halaman ini untuk info event berikutnya.
                </p>
            </div>

        @endif

    </section>


    <!-- ALUR EVENT -->
    <section class="mt-14">

        <h2 class="display-font text-2xl font-bold text-[#111827] md:text-[26px]">
            Alur Event
        </h2>

        <p class="mt-1 text-[13px] text-[#6b6b6b] md:text-[14px]">
            Cara ikut serta dan mendukung peserta
        </p>

        <div class="mt-5 grid grid-cols-1 gap-3.5 sm:grid-cols-3">

            @foreach($alurEvent as $langkah)

                <div class="card-shadow rounded-[10px] border border-[#ededed] bg-white p-6">

                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-[#f4ede0] text-[14px] font-bold text-[#c99a32]">
                        {{ $langkah['nomor'] }}
                    </div>

                    <h3 class="mt-4 text-[16px] font-bold text-[#13223a]">
                        {{ $langkah['judul'] }}
                    </h3>

                    <p class="mt-2 text-[13px] leading-[1.6] text-[#666]">
                        {{ $langkah['deskripsi'] }}
                    </p>

                </div>

            @endforeach

        </div>
    </section>


    <!-- PERINGKAT SEMENTARA -->
    @if($eventBerlangsung)

        <section class="mt-14">

            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">

                <div>
                    <h2 class="display-font text-2xl font-bold text-[#111827] md:text-[26px]">
                        Peringkat Sementara Event Terkini
                    </h2>

                    <p class="mt-1 text-[13px] text-[#6b6b6b] md:text-[14px]">
                        Tiga peserta dengan suara terbanyak saat ini
                        <span class="text-[#c8c8c8]">›</span>
                        <a href="{{ route('detail-event') }}" class="font-semibold text-[#c99a32] hover:underline">
                            {{ $eventBerlangsung['judul'] }}
                        </a>
                    </p>
                </div>

                <a href="{{ route('detail-event') }}"
                    class="whitespace-nowrap text-[13px] font-semibold text-[#c99a32] hover:underline">
                    Lihat semua peserta →
                </a>

            </div>

            @if(count($peringkatTerkini) > 0)

                <div class="card-shadow mt-5 divide-y divide-gray-100 rounded-[10px] border border-gray-100 bg-white">

                    @foreach($peringkatTerkini as $p)

                        <div class="flex items-center gap-4 px-5 py-4">

                            <span class="display-font w-6 text-[16px] font-bold text-[#c99a32]">
                                {{ $p['nomor'] }}
                            </span>

                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#fbf6e9] text-[11px] font-bold text-[#c99a32]">
                                {{ $p['inisial'] }}
                            </div>

                            <p class="w-44 truncate text-[14px] font-semibold text-[#15233d]">
                                {{ $p['nama'] }}
                            </p>


                            <div class="mx-2 h-1.5 flex-1 rounded-full bg-gray-100">
                                <div class="h-1.5 rounded-full bg-[#c99a32]" style="width: {{ $p['persen'] }}%"></div>
                            </div>

                            <span class="whitespace-nowrap text-[14px] font-semibold text-[#15233d]">
                                {{ $p['persen'] }}%
                            </span>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="card-shadow mt-5 rounded-[10px] border border-dashed border-gray-200 bg-white p-8 text-center">
                    <p class="text-sm font-semibold text-[#15233d]">
                        Belum ada kandidat pada event ini
                    </p>
                </div>

            @endif

        </section>

    @endif


    <!-- PERTANYAAN UMUM -->
    <section class="mb-6 mt-14">

        <h2 class="display-font text-2xl font-bold text-[#111827] md:text-[26px]">
            Pertanyaan Umum
        </h2>

        <p class="mt-1 text-[13px] text-[#6b6b6b] md:text-[14px]">
            Hal yang sering ditanyakan seputar event
        </p>

        <div class="mt-5 grid grid-cols-1 gap-3.5 sm:grid-cols-2">

            @foreach($faq as $item)

                <details class="group card-shadow rounded-[10px] border border-gray-100 bg-white px-5 py-4">

                    <summary class="flex cursor-pointer list-none items-center justify-between gap-3 text-[14px] font-semibold text-[#15233d]">
                        {{ $item['pertanyaan'] }}

                        <span class="shrink-0 text-[16px] text-[#c99a32] transition group-open:rotate-45">
                            +
                        </span>
                    </summary>

                    <p class="mt-3 text-[13px] leading-[1.6] text-[#666]">
                        {{ $item['jawaban'] }}
                    </p>

                </details>

            @endforeach

        </div>
    </section>

@else

    <!-- EVENT SEBELUMNYA -->
    <section class="pt-2">

        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <h2 class="display-font text-2xl font-bold text-[#111827] md:text-[26px]">
                Event Sebelumnya
            </h2>

            <p class="text-[13px] text-[#9a9a9a] md:text-[14px]">
                Arsip event dan pemenang
            </p>
        </div>

        @if(count($eventSebelumnya) > 0)

            <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">

                @foreach($eventSebelumnya as $item)

                    <article class="card-shadow overflow-hidden rounded-[10px] border border-[#ededed] bg-white">

                        <div class="relative h-[220px]">

                            @if($item['foto'])

                                <img
                                    src="{{ asset('image/event/' . $item['foto']) }}"
                                    alt="{{ $item['judul'] }}"
                                    class="h-full w-full object-cover">

                            @else

                                <div class="flex h-full w-full items-center justify-center bg-[#efe6d3]">
                                    <span class="text-[12px] text-[#a99260]">
                                        Foto landscape 21:9 · 2100 × 900 px
                                    </span>
                                </div>

                            @endif

                            <span class="absolute left-4 top-4 rounded-full bg-black px-3 py-1.5 text-[10px] font-semibold text-white">
                                ●&nbsp; Telah Berakhir
                            </span>
                        </div>

                        <div class="p-5">
                            <h3 class="text-[17px] font-bold leading-6 text-[#13223a]">
                                {{ $item['judul'] }}
                            </h3>

                            <div class="mt-4 space-y-2 text-[12px] text-[#777]">
                                <div class="flex items-center gap-2">
                                    <span>▣</span>
                                    <span>{{ $item['tanggal'] }}</span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <span>♕</span>
                                    <span>Juara 1 · {{ $item['juara'] }}</span>
                                </div>
                            </div>

                            <a href="{{ route('detail-event-selesai') }}"
                                class="mt-5 block w-full rounded-[4px] border border-[#777] bg-white py-2.5 text-center text-[12px] font-semibold text-[#333] transition hover:bg-[#f7f7f7]">
                                Lihat Pemenang
                            </a>
                        </div>

                    </article>

                @endforeach

            </div>

        @else

            <div class="card-shadow mt-5 rounded-[10px] border border-dashed border-gray-200 bg-white p-10 text-center">
                <p class="text-sm font-semibold text-[#15233d]">
                    Belum ada arsip event
                </p>
                <p class="mt-1 text-xs text-gray-400">
                    Event yang sudah selesai akan muncul di sini beserta pemenangnya.
                </p>
            </div>

        @endif

    </section>

@endif

</main>

<!-- FOOTER -->
<footer class="bg-[#101010] text-white">
    <div class="mx-auto max-w-[1200px] px-5 py-12 md:px-8">

        <div class="grid grid-cols-1 gap-10 border-b border-[#2a2a2a] pb-10 md:grid-cols-[1.5fr_1fr_1fr_1.15fr]">

            <div>
                <div class="flex items-center gap-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded bg-white text-[11px] font-bold text-black">
                        A
                    </div>

                    <div>
                        <div class="font-bold tracking-[.12em]">ALNAY.ID</div>
                        <div class="text-[8px] tracking-[.15em] text-[#c9962e]">
                            JAS &amp; GOWN BERBUDAYA
                        </div>
                    </div>
                </div>

                <p class="mt-4 max-w-[330px] text-[10px] leading-5 text-[#858585]">
                    Platform penjualan dan penyewaan fashion etnik ALNAY.ID yang menghadirkan
                    busana adat Lampung dengan tampilan modern dan elegan.
                </p>

                <div class="mt-4 flex gap-2">
                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#242424] text-[10px]">◎</span>
                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#242424] text-[10px]">◉</span>
                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#242424] text-[10px]">●</span>
                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#242424] text-[10px]">f</span>
                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#242424] text-[10px]">▶</span>
                </div>
            </div>

            <div>
                <h3 class="text-[10px] font-semibold tracking-[.14em]">KOLEKSI ADAT</h3>

                <ul class="mt-4 space-y-2 text-[10px] text-[#858585]">
                    <li>Jas Berbudaya</li>
                    <li>Gown Berbudaya</li>
                    <li>Set Baju Pasangan</li>
                    <li>Aksesoris Adat</li>
                    <li>Busana Lampung</li>
                </ul>
            </div>

            <div>
                <h3 class="text-[10px] font-semibold tracking-[.14em]">BUTUH BANTUAN?</h3>

                <div class="mt-4 space-y-2 text-[10px] text-[#858585]">
                    <p>Senin - Jumat: 08:00 - 20:00 WIB</p>
                    <p>Sabtu - Minggu: 09:00 - 18:00 WIB</p>

                    <button class="rounded border border-[#9b762d] px-3 py-1.5 text-[9px] text-[#d4a63d]">
                        Chat WhatsApp CS
                    </button>

                    <p class="pt-1 leading-4">Lampung, Indonesia</p>
                </div>
            </div>

            <div>
                <h3 class="text-[10px] font-semibold tracking-[.14em]">
                    LAYANAN &amp; INFORMASI
                </h3>

                <ul class="mt-4 space-y-2 text-[10px] text-[#858585]">
                    <li>Cara &amp; Syarat Sewa Busana</li>
                    <li>Aturan Deposit</li>
                    <li>Jadwal Fitting Studio</li>
                    <li>Panduan Ukur Badan Online</li>
                    <li>Pemesanan Busana</li>
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
            <div>© 2026 ALNAY.ID. All Rights Reserved.</div>

            <div class="flex flex-wrap gap-5">
                <span>Kebijakan Privasi</span>
                <span>Syarat &amp; Ketentuan</span>
                <span>Informasi ALNAY.ID</span>
            </div>
        </div>

    </div>
</footer>

</body>
</html>