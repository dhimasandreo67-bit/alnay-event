<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Profil Saya - ALNAY.ID</title>

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

        .card-shadow {
            box-shadow: 0 8px 30px rgba(21, 35, 61, 0.12);
        }
    </style>
</head>

<body class="bg-[#faf9f6] text-[#15233d]">

    <!-- =========================================================
         DATA PROFIL & STATUS PESERTA
    ========================================================== -->

    @php

        // =====================================================
        // OPSI 1 — DATA MANUAL / STATIS (AKTIF)
        // Dipakai selama tahap pengembangan UI/UX, belum ada DB.
        // =====================================================

        $user = [
            'nama'         => 'Perrell Brown',
            'username'     => 'perrell',
            'foto'         => null, // isi URL foto jika sudah ada, null = tampilkan inisial
            'email'        => 'perrell.brown@email.com',
            'bergabung'    => 'September 2026',
            'total_vote'   => 37,
            'total_event' => 2,
        ];

        // URL tujuan form (sementara '#', belum ada route/controller)
        $url = [
            'update'     => '#',
            'foto'       => '#',
            'foto_hapus' => '#',
        ];

        // Isi null jika user bukan peserta/kandidat di event manapun
        $peserta = [
            'event'          => 'Pemilihan Duta Budaya 2024',
            'nomor'          => '01',
            'nama_kandidat'  => 'Raden Roro Ayu',
            'status'         => 'Terverifikasi',
            'vote_diterima'  => '2.940',
            'peringkat'      => 1,
            'total_kandidat' => 24,
            'voting_dimulai' => true, // ubah ke false untuk melihat tampilan tanda strip (-)
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

        $user = [
            'nama'       => $authUser->name,
            'username'   => $authUser->username,
            'foto'       => $authUser->foto ? asset('storage/' . $authUser->foto) : null,
            'email'      => $authUser->email,
            'bergabung'  => $authUser->created_at->translatedFormat('F Y'),
            'total_vote' => $authUser->votes()->count(),
            'total_event' => $authUser->votes()->distinct('event_id')->count('event_id'),
        ];

        $url = [
            'update'     => route('profil-update'),
            'foto'       => route('profil-foto'),
            'foto_hapus' => route('profil-foto-hapus'),
        ];

        $kandidatUser = $authUser->kandidat;

        $peserta = $kandidatUser ? [
            'event'          => $kandidatUser->event->judul,
            'nomor'          => $kandidatUser->nomor,
            'nama_kandidat'  => $kandidatUser->nama,
            'status'         => $kandidatUser->status,
            'vote_diterima'  => number_format($kandidatUser->total_vote, 0, ',', '.'),
            'peringkat'      => $kandidatUser->peringkat,
            'total_kandidat' => $kandidatUser->event->jumlah_kandidat,
            'voting_dimulai' => $kandidatUser->event->voting_aktif, // sesuaikan dengan kolom status voting di tabel event
        ] : null;
        */

        // Inisial untuk avatar (dari username)
        $inisial = strtoupper(substr($user['username'], 0, 1));

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

    <main class="max-w-3xl mx-auto px-5 pt-8 pb-14">


        <!-- =====================================================
             JUDUL HALAMAN
        ====================================================== -->

        <section>

            <h1 class="font-display text-2xl md:text-3xl font-bold text-[#111827]">
                Profil Saya
            </h1>

            <p class="text-gray-500 text-xs md:text-sm mt-1.5">
                Kelola data akun dan lihat aktivitas vote Anda di Event ALNAY.ID.
            </p>

        </section>


        <!-- =====================================================
             KARTU DATA AKUN
        ====================================================== -->

        <section class="mt-6 bg-white rounded-xl border border-gray-100 card-shadow overflow-hidden">

            <!-- Identitas -->
            <div class="flex items-center justify-between gap-4 p-5">

                <div class="flex items-center gap-4 min-w-0">

                    <!-- Foto Profil + tombol edit -->
                    <div class="relative shrink-0">

                        <div class="w-14 h-14 md:w-[72px] md:h-[72px] rounded-full overflow-hidden bg-[#a37b2a] text-white font-display text-xl md:text-2xl font-bold flex items-center justify-center">
                            @if($user['foto'])
                                <img id="avatarImg" src="{{ $user['foto'] }}" alt="Foto profil" class="w-full h-full object-cover">
                            @else
                                <span id="avatarInisial">{{ $inisial }}</span>
                            @endif
                        </div>

                        <button type="button" id="btnAvatar" aria-label="Edit foto profil" aria-haspopup="menu" aria-expanded="false"
                            class="absolute -bottom-0.5 -right-0.5 w-7 h-7 rounded-full bg-white border border-gray-200 shadow-sm flex items-center justify-center text-[#a37b2a] hover:bg-[#fbf6e9] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#c99a32]/40 transition">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                                <circle cx="12" cy="13" r="4"></circle>
                            </svg>
                        </button>

                        <!-- Menu foto -->
                        <div id="menuAvatar" role="menu" class="hidden absolute left-0 top-full mt-2 z-30 w-48 bg-white border border-gray-100 rounded-lg shadow-lg py-1 text-sm">

                            <button type="button" id="menuLihat" role="menuitem" class="w-full text-left px-4 py-2.5 hover:bg-[#faf8f2] transition">
                                Lihat foto
                            </button>

                            <button type="button" id="menuPilih" role="menuitem" class="w-full text-left px-4 py-2.5 hover:bg-[#faf8f2] transition">
                                Pilih foto
                            </button>

                            @if($user['foto'])
                            <button type="button" id="menuHapus" role="menuitem" class="w-full text-left px-4 py-2.5 text-red-600 hover:bg-red-50 transition">
                                Hapus foto
                            </button>
                            @endif

                        </div>

                        <!-- Form upload foto (otomatis terkirim setelah file dipilih) -->
                        <form id="formFoto" method="POST" action="{{ $url['foto'] }}" enctype="multipart/form-data" class="hidden">
                            @csrf
                            <input type="file" id="fileFoto" name="foto" accept="image/png,image/jpeg,image/webp">
                        </form>

                        <!-- Form hapus foto -->
                        @if($user['foto'])
                        <form id="formHapusFoto" method="POST" action="{{ $url['foto_hapus'] }}" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                        @endif

                    </div>

                    <div class="min-w-0">

                        <h2 class="font-display text-lg md:text-xl font-bold text-[#15233d] truncate">
                            {{ $user['username'] }}
                        </h2>

                        <p class="text-gray-500 text-xs md:text-sm mt-0.5">
                            Bergabung sejak {{ $user['bergabung'] }}
                        </p>

                    </div>

                </div>

            </div>


            <!-- Email -->
            <div class="flex items-center justify-between gap-4 px-5 py-4 border-t border-gray-100 text-sm">

                <span class="text-gray-500">Email</span>

                <span class="font-medium text-[#15233d] truncate">
                    {{ $user['email'] }}
                </span>

            </div>


            <!-- Username (klik lalu ketik) + Simpan -->
            <form id="formProfil" method="POST" action="{{ $url['update'] }}">
                @csrf
                @method('PUT')

                <div class="px-5 py-3 border-t border-gray-100 text-sm">

                    <div class="flex items-center justify-between gap-4">

                        <label for="inputUsername" class="text-gray-500 shrink-0">Username</label>

                        <div class="relative w-full max-w-[240px]">
                            <input type="text" id="inputUsername" name="username"
                                value="{{ old('username', $user['username']) }}"
                                data-awal="{{ $user['username'] }}"
                                maxlength="20" autocomplete="off" spellcheck="false"
                                class="w-full rounded-md border border-transparent hover:border-gray-200 bg-transparent pl-3 pr-8 py-2 text-right font-medium text-[#15233d] transition focus:outline-none focus:border-[#c99a32] focus:bg-white focus:ring-2 focus:ring-[#c99a32]/20">

                            <svg class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-300" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 20h9"></path>
                                <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
                            </svg>
                        </div>

                    </div>

                    <div class="flex justify-end min-h-[18px] mt-1 text-[11px]">
                        @error('username')
                            <p id="pesan" class="text-red-600">{{ $message }}</p>
                        @else
                            <p id="pesan" class="text-gray-400">3–20 karakter: huruf kecil, angka, atau _</p>
                        @enderror
                    </div>

                </div>

                <div class="flex items-center justify-end px-5 py-3.5 border-t border-gray-100 bg-[#faf8f2]">
                    <button type="submit" id="btnSimpan" disabled
                        class="px-4 py-2 rounded-md bg-[#a37b2a] text-white text-xs font-medium transition hover:bg-[#c99a32] disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed">
                        Simpan Perubahan
                    </button>
                </div>

            </form>

        </section>


        <!-- =====================================================
     KARTU RINGKASAN AKTIVITAS
====================================================== -->

<section class="mt-5 bg-white rounded-xl border border-gray-100 card-shadow p-5">

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">

        <!-- Total Vote Diberikan -->
        <div class="bg-[#faf8f2] rounded-lg py-7 text-center">

            <p class="font-display text-3xl md:text-4xl font-bold text-[#c99a32]">
                {{ $user['total_vote'] }}
            </p>

            <p class="text-xs text-gray-500 mt-1">
                Total Vote Diberikan
            </p>

        </div>

        <!-- Event yang Telah Diikuti -->
        <div class="bg-[#faf8f2] rounded-lg py-7 text-center">

            <p class="font-display text-3xl md:text-4xl font-bold text-[#c99a32]">
                {{ $user['total_event'] }}
            </p>

            <p class="text-xs text-gray-500 mt-1">
                Event yang telah Diikuti
            </p>

        </div>

    </div>

    {{-- Riwayat Vote: disembunyikan dulu (detail riwayat vote peserta belum siap).
         Untuk menampilkan lagi, hapus tanda komentar Blade di awal dan akhir blok ini.

    <div class="flex justify-center mt-5">

        <a href="{{ route('riwayat-vote') }}" class="text-xs font-medium text-[#a37b2a] hover:text-[#c99a32] transition">
    Lihat Riwayat Vote Saya ›
</a>

    </div>

    --}}

</section>


        <!-- =====================================================
             KARTU STATUS PESERTA EVENT
        ====================================================== -->

        @if($peserta)

        <section class="mt-5 bg-white rounded-xl border border-gray-100 card-shadow p-5">

            <h2 class="text-base font-semibold text-[#15233d]">
                Status Peserta Event
            </h2>

            <p class="text-gray-500 text-xs mt-0.5">
                Anda terdaftar sebagai kandidat di event berikut
            </p>


            <!-- Event yang diikuti -->
            <div class="flex items-center justify-between gap-3 mt-4 bg-[#faf8f2] rounded-lg p-4">

                <div class="flex items-center gap-3 min-w-0">

                    <div class="w-12 h-12 shrink-0 rounded-md bg-[#22212b]"></div>

                    <div class="min-w-0">

                        <p class="text-sm font-semibold text-[#15233d] truncate">
                            {{ $peserta['event'] }}
                        </p>

                        <p class="text-xs text-gray-500 mt-0.5 truncate">
                            Kandidat #{{ $peserta['nomor'] }} · {{ $peserta['nama_kandidat'] }}
                        </p>

                    </div>

                </div>

                <span class="shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-[#e8f5ec] border border-[#cfe8d8] text-[#2f7a4a] text-[10px] font-semibold">
                    ✓ {{ $peserta['status'] }}
                </span>

            </div>


            <!-- Statistik peserta -->
            <div class="mt-3 divide-y divide-gray-100 text-sm">

                <div class="flex items-center justify-between py-3.5">
                    <span class="text-gray-500">Total Vote Diterima</span>
                    <span class="font-semibold text-[#15233d]">
                        {{ $peserta['voting_dimulai'] ? $peserta['vote_diterima'] . ' vote' : '-' }}
                    </span>
                </div>

                <div class="flex items-center justify-between py-3.5">
                    <span class="text-gray-500">Peringkat Saat Ini</span>
                    <span class="font-semibold text-[#15233d]">
                        {{ $peserta['voting_dimulai'] ? '#' . $peserta['peringkat'] . ' dari ' . $peserta['total_kandidat'] . ' kandidat' : '-' }}
                    </span>
                </div>

            </div>


            <!-- Tautan aksi -->
            <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 mt-3">

                <a href="#" class="text-xs font-medium text-[#a37b2a] hover:text-[#c99a32] transition">
                    Lihat &amp; Edit Data Pendaftaran ›
                </a>

            </div>

        </section>

        @endif


        <!-- =====================================================
             KELUAR DARI AKUN
        ====================================================== -->

        <div class="flex justify-center mt-8">

            <a href="#" class="text-xs text-gray-500 hover:text-[#15233d] transition">
                Keluar dari Akun
            </a>

        </div>

    </main>


    <!-- =========================================================
         LIHAT FOTO PROFIL (POPUP)
    ========================================================== -->
    <div id="lightbox" class="hidden fixed inset-0 z-[60] items-center justify-center bg-black/70 px-5">

        <div class="relative">

            <div class="w-64 h-64 md:w-80 md:h-80 rounded-full overflow-hidden bg-[#a37b2a] text-white font-display text-8xl font-bold flex items-center justify-center">
                @if($user['foto'])
                    <img src="{{ $user['foto'] }}" alt="Foto profil" class="w-full h-full object-cover">
                @else
                    <span id="lightInisial">{{ $inisial }}</span>
                @endif
            </div>

            <button type="button" id="lightTutup" aria-label="Tutup"
                class="absolute -top-2 -right-2 w-8 h-8 rounded-full bg-white text-[#15233d] shadow flex items-center justify-center text-lg leading-none">
                &times;
            </button>

        </div>

    </div>


    <!-- =========================================================
         NOTIFIKASI (status sukses / error upload foto)
    ========================================================== -->
    <div id="toast" data-pesan="{{ session('status') ?? $errors->first('foto') }}"
        class="hidden fixed bottom-6 left-1/2 -translate-x-1/2 z-[70] px-4 py-2.5 rounded-md bg-[#15233d] text-white text-xs shadow-lg"></div>


    <!-- =========================================================
         SCRIPT: edit username, menu foto, lihat foto
    ========================================================== -->
    <script>
        const $ = id => document.getElementById(id);

        // ---------- Notifikasi ----------
        let toastTimer;
        const tampilToast = teks => {
            const t = $('toast');
            t.textContent = teks;
            t.classList.remove('hidden');
            clearTimeout(toastTimer);
            toastTimer = setTimeout(() => t.classList.add('hidden'), 2600);
        };
        if ($('toast').dataset.pesan) tampilToast($('toast').dataset.pesan);


        // ---------- Mode statis (Opsi 1): form belum dikirim ke server ----------
        const modeStatis = $('formProfil').getAttribute('action') === '#';
        if (modeStatis) {
            document.querySelectorAll('form').forEach(f => {
                f.addEventListener('submit', e => {
                    e.preventDefault();
                    tampilToast('Mode statis: data belum disimpan ke database');
                });
            });
        }


        // ---------- Edit username ----------
        const input = $('inputUsername');
        const btnSimpan = $('btnSimpan');
        const pesan = $('pesan');
        const infoAwal = '3–20 karakter: huruf kecil, angka, atau _';
        const valid = v => /^[a-z0-9_]{3,20}$/.test(v);

        input.addEventListener('input', () => {
            const v = input.value.trim().toLowerCase();

            if (!valid(v)) {
                pesan.textContent = 'Gunakan 3–20 huruf kecil, angka, atau _';
                pesan.className = 'text-red-600';
                btnSimpan.disabled = true;
            } else {
                pesan.textContent = infoAwal;
                pesan.className = 'text-gray-400';
                btnSimpan.disabled = (v === input.dataset.awal);
            }
        });


        // ---------- Menu foto profil ----------
        const menu = $('menuAvatar');
        const btnAvatar = $('btnAvatar');
        const fileFoto = $('fileFoto');

        const bukaMenu = buka => {
            menu.classList.toggle('hidden', !buka);
            btnAvatar.setAttribute('aria-expanded', buka);
        };

        btnAvatar.addEventListener('click', e => {
            e.stopPropagation();
            bukaMenu(menu.classList.contains('hidden'));
        });

        document.addEventListener('click', e => {
            if (!menu.contains(e.target)) bukaMenu(false);
        });

        // Pilih foto -> validasi ukuran -> kirim otomatis
        $('menuPilih').addEventListener('click', () => {
            bukaMenu(false);
            fileFoto.click();
        });

        fileFoto.addEventListener('change', () => {
            const f = fileFoto.files[0];
            if (!f) return;

            if (f.size > 2 * 1024 * 1024) {
                tampilToast('Ukuran foto maksimal 2 MB');
                fileFoto.value = '';
                return;
            }

            $('formFoto').requestSubmit();
        });

        // Hapus foto (hanya ada jika user punya foto)
        if ($('menuHapus')) {
            $('menuHapus').addEventListener('click', () => {
                bukaMenu(false);
                $('formHapusFoto').requestSubmit();
            });
        }


        // ---------- Lihat foto ----------
        const lightbox = $('lightbox');
        const tutupLihat = () => {
            lightbox.classList.add('hidden');
            lightbox.classList.remove('flex');
        };

        $('menuLihat').addEventListener('click', () => {
            bukaMenu(false);
            lightbox.classList.remove('hidden');
            lightbox.classList.add('flex');
        });

        $('lightTutup').addEventListener('click', tutupLihat);
        lightbox.addEventListener('click', e => { if (e.target === lightbox) tutupLihat(); });

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') { bukaMenu(false); tutupLihat(); }
        });
    </script>

</body>
</html>