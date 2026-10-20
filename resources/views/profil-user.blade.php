<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Profil Saya - ALNAY.ID</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <link rel="icon" type="image/png" href="{{ asset('images/alnaylogo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .font-display {
            font-family: 'Playfair Display', serif;
        }

        .card-premium {
            transition: all .35s cubic-bezier(.22, 1, .36, 1);
        }

        .card-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 40px -20px rgba(201, 154, 50, .15);
            border-color: #E6D3A7;
        }

        .reveal-up {
            animation: revealUp .6s cubic-bezier(.22, 1, .36, 1) both;
        }

        @keyframes revealUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .reveal-up.delay-1 {
            animation-delay: .08s;
        }

        .reveal-up.delay-2 {
            animation-delay: .16s;
        }

        .reveal-up.delay-3 {
            animation-delay: .24s;
        }

        .reveal-up.delay-4 {
            animation-delay: .32s;
        }

        .avatar-wrap {
            transition: transform .35s cubic-bezier(.22, 1, .36, 1);
        }

        .avatar-wrap:hover {
            transform: scale(1.05);
        }

        .avatar-ring {
            box-shadow: 0 0 0 3px white, 0 0 0 5px rgba(201, 154, 50, .25);
        }

        .btn-avatar {
            transition: all .25s ease;
        }

        .btn-avatar:hover {
            transform: scale(1.1);
            background: #FBF6E9;
        }

        .btn-avatar::after {
            content: "";
            position: absolute;
            inset: -3px;
            border-radius: 50%;
            border: 2px solid #C99A32;
            animation: btnRing 3s ease-out infinite;
            pointer-events: none;
        }

        @keyframes btnRing {
            0% {
                opacity: .5;
                transform: scale(1);
            }

            100% {
                opacity: 0;
                transform: scale(1.15);
            }
        }

        .stat-card {
            transition: all .35s cubic-bezier(.22, 1, .36, 1);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            background: linear-gradient(135deg, #FAF3E7, #F5E9CE);
            box-shadow: 0 20px 40px -20px rgba(201, 154, 50, .25);
        }

        .stat-icon {
            transition: transform .35s cubic-bezier(.22, 1, .36, 1);
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.15) rotate(-5deg);
        }

        .input-nama {
            transition: all .25s ease;
        }

        .input-nama:focus {
            border-color: #C99A32 !important;
            background: white !important;
            box-shadow: 0 0 0 4px rgba(201, 154, 50, .12);
        }

        .btn-save {
            position: relative;
            overflow: hidden;
            transition: all .3s cubic-bezier(.22, 1, .36, 1);
        }

        .btn-save::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .3), transparent);
            transition: left .6s ease;
        }

        .btn-save:hover:not(:disabled)::before {
            left: 100%;
        }

        .btn-save:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 12px 22px -8px rgba(201, 154, 50, .5);
        }

        #toast {
            animation: toastIn .35s cubic-bezier(.22, 1, .36, 1);
        }

        @keyframes toastIn {
            from {
                opacity: 0;
                transform: translate(-50%, 12px);
            }

            to {
                opacity: 1;
                transform: translate(-50%, 0);
            }
        }

        #lightbox.flex {
            animation: lightboxIn .3s ease-out;
        }

        @keyframes lightboxIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .title-ornament {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .title-ornament::before,
        .title-ornament::after {
            content: "";
            width: 24px;
            height: 1px;
            background: linear-gradient(90deg, transparent, #C99A32);
        }

        .title-ornament::after {
            background: linear-gradient(90deg, #C99A32, transparent);
        }

        .metode-opt .dot {
            display: block;
            width: 8px;
            height: 8px;
            border-radius: 9999px;
            background: transparent;
        }

        .metode-opt.is-on {
            border-color: #C99A32;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(201, 154, 50, .14);
        }

        .metode-opt.is-on .dot {
            background: #C99A32;
        }

        .metode-opt.is-on .ring {
            border-color: #C99A32;
        }

        .metode-bayar-opt .dot {
            display: block;
            width: 8px;
            height: 8px;
            border-radius: 9999px;
            background: transparent;
        }

        .metode-bayar-opt.is-on {
            border-color: #C99A32;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(201, 154, 50, .14);
        }

        .metode-bayar-opt.is-on .dot {
            background: #C99A32;
        }

        .metode-bayar-opt.is-on .ring {
            border-color: #C99A32;
        }

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body class="bg-[#faf9f6] text-[#15233d]">

    @php
        $namaTampil = $user->nama_custom ?: $user->nama;
        $fotoTampil = $user->foto_custom ? asset('storage/' . $user->foto_custom) : $user->foto_url;
        $inisial = strtoupper(substr(trim($namaTampil), 0, 1));
        $bergabung = $user->tanggal_daftar ? $user->tanggal_daftar->translatedFormat('F Y') : '-';
    @endphp

    @include('partials.header')

    <main class="max-w-3xl mx-auto px-5 pt-8 pb-14">

        {{-- JUDUL --}}
        <section class="reveal-up">
            <div class="title-ornament mb-2">
                <span class="inline-block h-1.5 w-1.5 rotate-45 bg-[#C99A32]"></span>
                <span class="text-[9px] font-bold uppercase tracking-[.24em] text-[#a37b2a]">Akun Saya</span>
                <span class="inline-block h-1.5 w-1.5 rotate-45 bg-[#C99A32]"></span>
            </div>
            <h1 class="font-display text-2xl md:text-3xl font-bold tracking-tight text-[#111827]">Profil Saya</h1>
            <p class="mt-1.5 text-gray-500 text-xs md:text-sm">
                Kelola data akun dan lihat aktivitas vote Anda di Event ALNAY.ID.
            </p>
        </section>

        {{-- KARTU DATA AKUN --}}
        <section class="reveal-up delay-1 mt-6 bg-white rounded-2xl border border-[#ebe7df] card-premium overflow-hidden">
            <div class="h-[3px] w-full bg-gradient-to-r from-[#c99a32] via-[#e0b254] to-[#c99a32]"></div>

            <div class="flex items-center justify-between gap-4 p-5">
                <div class="flex items-center gap-4 min-w-0">
                    <div class="avatar-wrap relative shrink-0">
                        <div
                            class="avatar-ring w-16 h-16 md:w-[80px] md:h-[80px] rounded-full overflow-hidden bg-gradient-to-br from-[#a37b2a] to-[#c99a32] text-white font-display text-2xl md:text-3xl font-bold flex items-center justify-center">
                            @if ($fotoTampil)
                                <img id="avatarImg" src="{{ $fotoTampil }}" alt="Foto profil"
                                    class="w-full h-full object-cover">
                            @else
                                <span id="avatarInisial">{{ $inisial }}</span>
                            @endif
                        </div>

                        <button type="button" id="btnAvatar" aria-label="Edit foto profil"
                            class="btn-avatar absolute -bottom-0.5 -right-0.5 w-8 h-8 rounded-full bg-white border border-[#C99A32]/30 shadow-md flex items-center justify-center text-[#a37b2a]">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8">
                                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z">
                                </path>
                                <circle cx="12" cy="13" r="4"></circle>
                            </svg>
                        </button>

                        <div id="menuAvatar"
                            class="hidden absolute left-0 top-full mt-2 z-30 w-48 bg-white border border-gray-100 rounded-xl shadow-lg py-1 text-sm">
                            @if ($fotoTampil)
                                <button type="button" id="menuLihat"
                                    class="w-full text-left px-4 py-2.5 hover:bg-[#faf8f2] transition rounded-t-xl">Lihat
                                    foto</button>
                            @endif
                            <button type="button" id="menuPilih"
                                class="w-full text-left px-4 py-2.5 hover:bg-[#faf8f2] transition">Pilih foto
                                baru</button>
                            @if ($user->foto_custom)
                                <button type="button" id="menuHapus"
                                    class="w-full text-left px-4 py-2.5 text-red-600 hover:bg-red-50 transition rounded-b-xl">Hapus
                                    foto</button>
                            @endif
                        </div>

                        <form id="formFoto" method="POST" action="{{ route('profil-user.foto') }}"
                            enctype="multipart/form-data" class="hidden">
                            @csrf
                            <input type="file" id="fileFoto" name="foto"
                                accept="image/png,image/jpeg,image/webp">
                        </form>

                        @if ($user->foto_custom)
                            <form id="formHapusFoto" method="POST" action="{{ route('profil-user.foto.hapus') }}"
                                class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endif
                    </div>

                    <div class="min-w-0">
                        <h2 class="font-display text-lg md:text-xl font-bold text-[#15233d] truncate">
                            {{ $namaTampil }}
                        </h2>
                        <p class="mt-0.5 text-gray-500 text-xs md:text-sm">
                            <span class="inline-flex items-center gap-1.5">
                                <span class="inline-block h-1.5 w-1.5 rounded-full bg-[#c99a32]"></span>
                                Bergabung sejak {{ $bergabung }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between gap-4 px-5 py-4 border-t border-[#ebe7df] text-sm">
                <span class="inline-flex items-center gap-1.5 text-gray-500">
                    <svg class="w-3.5 h-3.5 text-[#c99a32]" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Email
                </span>
                <span class="font-medium text-[#15233d] truncate">{{ $user->email }}</span>
            </div>

            <form id="formProfil" method="POST" action="{{ route('profil-user.update') }}">
                @csrf
                @method('PUT')

                <div class="px-5 py-3 border-t border-[#ebe7df] text-sm">
                    <div class="flex items-center justify-between gap-4">
                        <label for="inputNama" class="inline-flex items-center gap-1.5 text-gray-500 shrink-0">
                            <svg class="w-3.5 h-3.5 text-[#c99a32]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Nama Tampilan
                        </label>
                        <div class="relative w-full max-w-[240px]">
                            <input type="text" id="inputNama" name="nama_custom"
                                value="{{ old('nama_custom', $user->nama_custom ?? $user->nama) }}"
                                data-awal="{{ $user->nama_custom ?? $user->nama }}" maxlength="20"
                                autocomplete="off" spellcheck="false"
                                class="input-nama w-full rounded-lg border border-transparent hover:border-[#C99A32]/30 bg-transparent pl-3 pr-8 py-2 text-right font-medium text-[#15233d] transition focus:outline-none">
                            <svg class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-[#c99a32]/50"
                                width="13" height="13" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.8">
                                <path d="M12 20h9"></path>
                                <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="flex justify-end min-h-[18px] mt-1 text-[11px]">
                        @error('nama_custom')
                            <p id="pesan" class="text-red-600">{{ $message }}</p>
                        @else
                            <p id="pesan" class="text-gray-400">1–20 karakter: huruf dan spasi</p>
                        @enderror
                    </div>
                </div>

                <div
                    class="flex items-center justify-end px-5 py-3.5 border-t border-[#ebe7df] bg-gradient-to-r from-[#faf8f2] to-[#faf8f2]/60">
                    <button type="submit" id="btnSimpan" disabled
                        class="btn-save px-5 py-2.5 rounded-lg bg-gradient-to-r from-[#a37b2a] to-[#c99a32] text-white text-xs font-semibold transition shadow-sm disabled:bg-none disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed disabled:shadow-none">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </section>

        {{-- STAT CARDS --}}
        <section class="reveal-up delay-2 mt-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div
                    class="stat-card bg-gradient-to-br from-[#faf8f2] to-[#f5efe2] rounded-2xl border border-[#ebe7df] py-6 px-5 flex items-center justify-between">
                    <div>
                        <p class="text-4xl md:text-5xl font-bold text-[#c99a32] tabular-nums leading-none tracking-tight">
                            {{ $totalVote }}</p>
                        <p class="mt-2 text-[10px] uppercase tracking-[.15em] text-gray-500 font-semibold">Total Vote
                            Diberikan</p>
                    </div>
                    <div
                        class="stat-icon w-12 h-12 rounded-xl bg-white/70 flex items-center justify-center ring-1 ring-[#c99a32]/20">
                        <svg class="w-5 h-5 text-[#c99a32]" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                </div>

                <div
                    class="stat-card bg-gradient-to-br from-[#faf8f2] to-[#f5efe2] rounded-2xl border border-[#ebe7df] py-6 px-5 flex items-center justify-between">
                    <div>
                        <p class="text-4xl md:text-5xl font-bold text-[#c99a32] tabular-nums leading-none tracking-tight">
                            {{ $totalEvent }}</p>
                        <p class="mt-2 text-[10px] uppercase tracking-[.15em] text-gray-500 font-semibold">Event yang
                            Diikuti</p>
                    </div>
                    <div
                        class="stat-icon w-12 h-12 rounded-xl bg-white/70 flex items-center justify-center ring-1 ring-[#c99a32]/20">
                        <svg class="w-5 h-5 text-[#c99a32]" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </section>

        {{-- STATUS PENDAFTARAN --}}
        @if ($pendaftarans->count() > 0)

            @php
                $tones = [
                    'ok' => 'bg-[#e9f8f1] text-[#0f7a52]',
                    'wait' => 'bg-[#fff3d6] text-[#9a6f10]',
                    'info' => 'bg-[#e8f0fb] text-[#2f5fa8]',
                    'neutral' => 'bg-[#efede7] text-[#6f6a5c]',
                    'bad' => 'bg-[#fdeaea] text-[#b23b3b]',
                ];
                $chip = fn(string $label, string $tone) => '<span class="shrink-0 rounded-full px-2.5 py-1 text-[10.5px] font-semibold ' .
                    $tones[$tone] . '">' . e($label) . '</span>';
                $rp = fn($n) => 'Rp ' . number_format((int) $n, 0, ',', '.');
                $toC = fn($v) => $v ? ($v instanceof \Carbon\CarbonInterface ? $v : \Carbon\Carbon::parse($v)) : null;
                $tgl = fn($c) => $c ? $c->translatedFormat('d M Y') : '-';
                $tglJam = fn($c) => $c ? $c->translatedFormat('l, d M Y · H.i') . ' WIB' : '-';
                $routeAda = fn($nama) => \Illuminate\Support\Facades\Route::has($nama);

                $GOLD = 'mt-4 block w-full rounded-lg bg-gradient-to-r from-[#c99a32] to-[#b8862a] py-3 text-center text-[13px] font-semibold text-white shadow-sm transition hover:opacity-90';
                $OUTLINE = 'mt-4 block w-full rounded-lg border border-[#d9d9d9] bg-white py-3 text-center text-[13px] font-semibold text-[#333] transition hover:bg-[#f7f7f7]';
                $CHECK = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>';
                $CROSS = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M6 6l12 12M18 6L6 18"/></svg>';

                $BADGE = [
                    'calon' => ['border-[#f0dca8] bg-[#fff6e0] text-[#a37b2a]', 'bg-[#c99a32]', 'Calon Peserta'],
                    'peserta' => ['border-[#bfe8d6] bg-[#e9f8f1] text-[#0f7a52]', 'bg-[#0f7a52]', 'Peserta'],
                    'gugur' => ['border-[#f1cccc] bg-[#fdeaea] text-[#b23b3b]', 'bg-[#b23b3b]', 'Tidak Lolos'],
                ];
            @endphp

            <section class="reveal-up delay-3 mt-6">
                <div class="mb-4">
                    <div class="title-ornament mb-2">
                        <span class="inline-block h-1.5 w-1.5 rotate-45 bg-[#C99A32]"></span>
                        <span class="text-[9px] font-bold uppercase tracking-[.24em] text-[#a37b2a]">Riwayat</span>
                        <span class="inline-block h-1.5 w-1.5 rotate-45 bg-[#C99A32]"></span>
                    </div>
                    <h2 class="font-display text-lg md:text-xl font-bold text-[#15233d]">Status Pendaftaran</h2>
                    <p class="text-gray-500 text-xs mt-0.5">Pantau tahapan pendaftaran event kamu di sini</p>
                </div>

                <div class="space-y-4">
                    @foreach ($pendaftarans as $p)
                        @php
                            $e = $p->event;
                            $now = now();

                            /* ✅ SAKLAR ALUR EVENT */
                            $butuhAudisi = (bool) ($e->butuh_audisi ?? $e->audisi_aktif ?? false);
                            $butuhRegistrasi = (bool) ($e->butuh_registrasi ?? true);
                        /* ---------- Biaya & pengaturan event ---------- */
                        $biayaDaftar = (int) ($e->biaya_pendaftaran ?? 0);
                        $biayaRegis  = (int) ($e->harga_registrasi ?? 0);
                        $dpRegis     = (int) ($e->harga_registrasi_dp ?? 0);
                        // NOTE: field lama 'audisi_tanggal' tidak pernah terisi — kolom yang benar
                        // dipakai di "Audisi Aktif" banner adalah 'tanggal_audisi'. Disamakan di sini
                        // supaya kartu langkah 3 tidak lagi bilang "Belum ditentukan" padahal sudah diisi.
                        $audTgl      = $toC($e->tanggal_audisi ?? null);
                        $audLokasi   = $e->lokasi_audisi ?? $e->audisi_lokasi ?? null;
                        $vMulai      = $toC($e->voting_mulai ?? null);
                        $vAkhir      = $toC($e->voting_selesai ?? null);

                            /* Biaya & pengaturan event */
                            $biayaDaftar = (int) ($e->biaya_pendaftaran ?? 0);
                            $biayaRegis = (int) ($e->harga_registrasi ?? 0);
                            $dpRegis = (int) ($e->harga_registrasi_dp ?? 0);
                            $audTgl = $toC($e->tanggal_audisi ?? null);
                            $audLokasi = $e->audisi_lokasi ?? null;
                            $vMulai = $toC($e->voting_mulai ?? null);
                            $vAkhir = $toC($e->voting_selesai ?? null);

                            /* Tahap 2: pembayaran pendaftaran */
                            $bayarDaftar = $p->pembayaran
                                ->filter(fn($x) => $x->status_midtrans === 'settlement')
                                ->sortByDesc('id')
                                ->first();
                            $sudahBayar = (bool) $bayarDaftar;
                            $tglLunas = $bayarDaftar ? $toC($bayarDaftar->tanggal_bayar ?? $bayarDaftar->created_at) : null;

                            /* Registrasi */
                            $cicilan = $p->pembayaranRegistrasi
                                ->filter(fn($x) => $x->status_midtrans === 'settlement')
                                ->sortBy('id')
                                ->values();
                            $totalRegis = (int) $cicilan->sum('jumlah');
                            $regStatus = $totalRegis <= 0
                                ? 'belum'
                                : ($biayaRegis > 0 && $totalRegis >= $biayaRegis ? 'lunas' : 'dp');
                            $sisaRegis = max($biayaRegis - $totalRegis, 0);
                            $dpTampil = $dpRegis > 0 ? $dpRegis : (int) round($biayaRegis / 4);

                            /* Voting */
                            $eventMulai = $e->tanggal_mulai ? \Carbon\Carbon::parse($e->tanggal_mulai) : null;
                            $eventSelesai = $e->tanggal_selesai ? \Carbon\Carbon::parse($e->tanggal_selesai) : null;
                            $eventSedangBerlangsung = $eventMulai && $now->gte($eventMulai) && (!$eventSelesai || $now->lte($eventSelesai));

                        if (!$votingAktif) {
                            $vStatus = 'belum';   // Admin menonaktifkan voting dari halaman Settings
                        } elseif ($vMulai && $now->lt($vMulai)) {
                            $vStatus = 'belum';   // Ada jadwal mulai & sekarang masih sebelum waktunya
                        } elseif ($vAkhir && $now->gt($vAkhir)) {
                            $vStatus = 'selesai'; // Sekarang sudah melewati batas akhir voting
                        } else {
                            $vStatus = 'jalan';   // ✅ Toggle ON + tidak ada jadwal ATAU sedang dalam periode
                        }

                        // Variabel pendukung untuk tampilan UI
                        $sisaHari = $vAkhir ? max((int) $now->copy()->startOfDay()->diffInDays($vAkhir->copy()->startOfDay(), false), 0) : null;
                        $teksSisa = $sisaHari === null ? '' : ($sisaHari === 0 ? 'hari ini hari terakhir' : 'sisa ' . $sisaHari . ' hari');
                        $bisaBayarRegis = $vStatus !== 'selesai';
                        $voteDone = $vStatus === 'selesai';

                        /* ---------- Tahap 3: audisi (+ bypass saat audisi off) ---------- */
                        $hasil = $p->hasil_audisi ?? null;
                        if (!$audisiAktif) {
                            $hasil = 'lolos'; // audisi nonaktif: semua dianggap lolos (tampilan saja)
                        }

                        $urutan     = $e->alurTahap();
                        $punyaRegis = $e->punyaTahap('registrasi');
                        $no         = fn (string $k) => ($i = array_search($k, $urutan, true)) === false ? null : $i + 1;

                        $susulan = $p->audisi_susulan ?? null;
                        if ($susulan === null && $audisiAktif) {
                            $susulan = $audTgl && ($sudahBayar ? ($tglLunas && $tglLunas->gt($audTgl)) : $audTgl->isPast());
                        }

                        /* ---------- Status umum + tahap berjalan ---------- */
                        $fail = false;
                        $lockedAudisi = null;
                        $bodyKey = null;

                        if (!$sudahBayar) {
                            $cur = $no('pembayaran'); $ov = 'calon'; $chipCur = ['Belum dibayar', 'wait']; $bodyKey = 'bayar';
                            if ($susulan) {
                                $lockedAudisi = 'Audisi resmi sudah lewat. Kamu akan mengikuti audisi susulan yang diatur admin.';
                                $hint = 'Formulir sudah terkirim. Selesaikan pembayaran pendaftaran, lalu ikuti audisi susulan yang diatur admin.';
                            } else {
                                $hint = $audisiAktif
                                    ? 'Formulir sudah terkirim. Selesaikan pembayaran pendaftaran untuk lanjut ke audisi.'
                                    : 'Formulir sudah terkirim. Selesaikan pembayaran pendaftaran untuk lanjut ke voting' . ($punyaRegis ? ' dan registrasi.' : '.');
                            }
                        } elseif ($hasil === 'tidak_lolos') {
                            $cur = $no('audisi'); $ov = 'gugur'; $fail = true; $chipCur = ['Tidak lolos', 'bad']; $bodyKey = 'gugur';
                            $hint = 'Kamu belum lolos audisi, sehingga proses berhenti di tahap ini.';
                        } elseif ($hasil === 'lolos') {
                            $cur = $no('voting'); $ov = 'peserta'; $chipCur = null;
                            $hint = match (true) {
                                $vStatus === 'selesai' => 'Voting sudah berakhir. Terima kasih atas partisipasimu.',
                                ! $punyaRegis && $vStatus === 'jalan' => 'Voting sedang berlangsung. Ajak teman-temanmu mendukung kamu.',
                                ! $punyaRegis => 'Kamu sudah resmi menjadi peserta. Tinggal menunggu voting dibuka.',
                                $regStatus === 'lunas' && $vStatus === 'jalan' => 'Voting sedang berlangsung. Ajak teman-temanmu mendukung kamu.',
                                $regStatus === 'lunas' => 'Registrasi lunas. Tinggal menunggu voting dibuka.',
                                $regStatus === 'dp' && $vStatus === 'jalan' => 'Voting sedang berlangsung. Sisa registrasi masih bisa dilunasi sebelum voting berakhir.',
                                $regStatus === 'dp' => 'DP sudah masuk. Sisanya bisa dilunasi kapan saja, asal sebelum voting berakhir.',
                                $vStatus === 'jalan' => 'Voting sedang berlangsung. Registrasimu belum dibayar, selesaikan sebelum voting berakhir.',
                                default => 'Kamu sudah resmi menjadi peserta. Registrasi bisa dibayar kapan saja, asal sebelum voting berakhir.',
                            };
                        } elseif ($susulan) {
                            $cur = $no('audisi'); $ov = 'calon'; $chipCur = ['Menunggu hasil', 'wait']; $bodyKey = 'susulan';
                            $hint = 'Pendaftaran lunas. Audisi resmi sudah lewat, jadi kamu mengikuti audisi susulan yang informasinya disampaikan admin.';
                        } elseif (!$audTgl) {
                            $cur = $no('audisi'); $ov = 'calon'; $chipCur = ['Jadwal belum ditentukan', 'neutral']; $bodyKey = 'jadwal';
                            $hint = 'Pendaftaran lunas. Jadwal audisi akan diumumkan admin.';
                        } elseif ($audTgl->isFuture()) {
                            $cur = $no('audisi'); $ov = 'calon'; $chipCur = ['Terjadwal', 'info']; $bodyKey = 'terjadwal';
                            $hint = 'Audisi sudah terjadwal. Pastikan kamu hadir sesuai jadwal.';
                        } else {
                            $cur = $no('audisi'); $ov = 'calon'; $chipCur = ['Menunggu hasil', 'wait']; $bodyKey = 'menunggu';
                            $hint = 'Audisi sudah berlangsung. Menunggu pengumuman hasil dari admin.';
                        }

                            /* Audisi */
                            $hasil = $p->hasil_audisi ?? null;
                            $susulan = $p->audisi_susulan ?? null;
                            if ($susulan === null) {
                                $susulan = $audTgl && ($sudahBayar ? $tglLunas && $tglLunas->gt($audTgl) : $audTgl->isPast());
                            }

                                                /* ---------- ✅ DAFTAR LANGKAH — satu blok, disusun dari $e->alurTahap() ---------- */
                        $katalog = [
                            'formulir' => [
                                't' => 'Formulir pendaftaran',
                                'd' => 'Data diri dan foto yang kamu kirim',
                                'chipDone' => ['Terkirim', 'ok'],
                                'txtDone'  => 'Formulir terkirim · ' . $tgl($toC($p->tanggal_daftar)),
                            ],
                            'pembayaran' => [
                                't' => 'Pembayaran pendaftaran',
                                'd' => 'Biaya ' . $rp($biayaDaftar) . ', dibayar sekali (lunas)',
                                'chipDone' => ['Lunas', 'ok'],
                                'txtDone'  => 'Dibayar ' . $rp($bayarDaftar->jumlah ?? $biayaDaftar) . ' · ' . $tgl($tglLunas),
                            ],
                            'audisi' => [
                                't' => 'Audisi',
                                'd' => 'Penilaian peserta, hasil ditentukan admin',
                                'chipDone' => ['Lolos', 'ok'],
                                'txtDone'  => 'Dinyatakan lolos oleh admin'
                                    . (($p->tanggal_hasil_audisi ?? null) ? ' · ' . $tgl($toC($p->tanggal_hasil_audisi)) : ''),
                            ],
                            'voting' => [
                                't' => 'Voting',
                                'd' => ($vMulai || $vAkhir)
                                    ? 'Periode ' . ($vMulai ? $tgl($vMulai) : 'TBA') . ' – ' . ($vAkhir ? $tgl($vAkhir) : 'TBA')
                                    : 'Voting publik, hasil ditentukan perolehan suara',
                                'state'    => $peserta ? ($voteDone ? 'done' : ($vStatus === 'jalan' ? 'current' : 'locked')) : 'locked',
                                'chipDone' => ['Berakhir', 'ok'],
                                'chipCur'  => $vStatus === 'jalan' ? ['Berlangsung', 'ok'] : ['Belum dibuka', 'wait'],
                                'txtDone'  => 'Voting berakhir' . ($vAkhir ? ' · ' . $tgl($vAkhir) : ''),
                            ],
                            'registrasi' => [
                                't' => 'Registrasi',
                                'd' => 'Biaya ' . $rp($biayaRegis) . ', bisa lunas atau cicil 2 kali',
                                'state'    => $peserta ? ($regDone ? 'done' : 'current') : 'locked',
                                'chipDone' => ['Lunas', 'ok'],
                                'chipCur'  => $regStatus === 'dp' ? ['DP terbayar', 'info'] : ['Belum dibayar', 'wait'],
                                'txtDone'  => 'Registrasi lunas'
                                    . ($cicilan->last() ? ' · ' . $tgl($toC($cicilan->last()->tanggal_bayar ?? $cicilan->last()->created_at)) : ''),
                            ],
                        ];

                        $steps = [];
                        foreach ($urutan as $i => $key) {
                            $steps[] = ['key' => $key, 'n' => $i + 1] + $katalog[$key];
                        }

                            // Done state — Registrasi (step terakhir kalau butuh_registrasi = true)
                            if ($butuhRegistrasi) {
                                $regStepNum = $butuhAudisi ? 4 : 3;
                                $doneChip[$regStepNum] = ['Lunas', 'ok'];
                                $doneTxt[$regStepNum] = 'Registrasi sudah selesai · ' .
                                    ($cicilan->last() ? $tgl($toC($cicilan->last()->tanggal_bayar ?? $cicilan->last()->created_at)) : '-');
                            }

                            /* Status umum */
                            $fail = false;
                            $lockedAudisi = null;
                            $bodyKey = null;

                            if (!$sudahBayar) {
                                $cur = 2;
                                $ov = 'calon';
                                $chipCur = ['Belum dibayar', 'wait'];
                                $bodyKey = 'bayar';
                                if ($susulan) {
                                    $lockedAudisi = 'Audisi resmi sudah lewat. Kamu akan mengikuti audisi susulan yang diatur admin.';
                                    $hint = 'Formulir sudah terkirim. Selesaikan pembayaran pendaftaran, lalu ikuti audisi susulan yang diatur admin.';
                                } else {
                                    $hint = 'Formulir sudah terkirim. Selesaikan pembayaran pendaftaran untuk lanjut.';
                                }
                            } elseif ($hasil === 'tidak_lolos') {
                                $cur = $totalSteps + 1;
                                $ov = 'gugur';
                                $fail = true;
                                $chipCur = ['Tidak lolos', 'bad'];
                                $bodyKey = 'gugur';
                                $hint = 'Kamu belum lolos audisi, sehingga proses berhenti di tahap ini.';
                            } elseif ($hasil === 'lolos') {
                                $cur = $totalSteps + 1;
                                $ov = 'peserta';
                                $chipCur = null;
                                $hint = match (true) {
                                    $vStatus === 'selesai' => 'Voting sudah berakhir. Terima kasih atas partisipasimu.',
                                    !$butuhRegistrasi => 'Kamu sudah resmi menjadi peserta. Selamat mengikuti voting!',
                                    $regStatus === 'lunas' && $vStatus === 'jalan' => 'Voting sedang berlangsung. Ajak teman-temanmu mendukung kamu.',
                                    $regStatus === 'lunas' => 'Registrasi lunas. Tinggal menunggu voting dibuka.',
                                    $regStatus === 'dp' && $vStatus === 'jalan' => 'Voting sedang berlangsung. Sisa registrasi masih bisa dilunasi sebelum voting berakhir.',
                                    $regStatus === 'dp' => 'DP sudah masuk. Sisanya bisa dilunasi kapan saja, asal sebelum voting berakhir.',
                                    $vStatus === 'jalan' => 'Voting sedang berlangsung. Registrasimu belum dibayar, selesaikan sebelum voting berakhir.',
                                    default => 'Kamu sudah resmi menjadi peserta. Registrasi bisa dibayar kapan saja, asal sebelum voting berakhir.',
                                };
                            } elseif (!$butuhAudisi) {
                                /* Event tanpa audisi — langsung jadi peserta setelah bayar */
                                $cur = $totalSteps + 1;
                                $ov = 'peserta';
                                $chipCur = null;
                                $hint = match (true) {
                                    $vStatus === 'selesai' => 'Voting sudah berakhir. Terima kasih atas partisipasimu.',
                                    !$butuhRegistrasi => 'Kamu sudah resmi menjadi peserta. Selamat mengikuti voting!',
                                    $regStatus === 'lunas' && $vStatus === 'jalan' => 'Voting sedang berlangsung. Ajak teman-temanmu mendukung kamu.',
                                    $regStatus === 'lunas' => 'Registrasi lunas. Tinggal menunggu voting dibuka.',
                                    $regStatus === 'dp' && $vStatus === 'jalan' => 'Voting sedang berlangsung. Sisa registrasi masih bisa dilunasi sebelum voting berakhir.',
                                    $regStatus === 'dp' => 'DP sudah masuk. Sisanya bisa dilunasi kapan saja, asal sebelum voting berakhir.',
                                    $vStatus === 'jalan' => 'Voting sedang berlangsung. Registrasimu belum dibayar, selesaikan sebelum voting berakhir.',
                                    default => 'Kamu sudah resmi menjadi peserta. Registrasi bisa dibayar kapan saja, asal sebelum voting berakhir.',
                                };
                            } elseif ($susulan) {
                                $cur = 3;
                                $ov = 'calon';
                                $chipCur = ['Menunggu hasil', 'wait'];
                                $bodyKey = 'susulan';
                                $hint = 'Pendaftaran lunas. Audisi resmi sudah lewat, jadi kamu mengikuti audisi susulan yang informasinya disampaikan admin.';
                            } elseif (!$audTgl || !$audLokasi) {
                                $cur = 3;
                                $ov = 'calon';
                                $chipCur = ['Jadwal belum ditentukan', 'neutral'];
                                $bodyKey = 'jadwal';
                                $hint = 'Pendaftaran lunas. Jadwal audisi akan diumumkan admin.';
                            } elseif ($audTgl->isFuture()) {
                                $cur = 3;
                                $ov = 'calon';
                                $chipCur = ['Terjadwal', 'info'];
                                $bodyKey = 'terjadwal';
                                $hint = 'Audisi sudah terjadwal. Pastikan kamu hadir sesuai jadwal.';
                            } else {
                                $cur = 3;
                                $ov = 'calon';
                                $chipCur = ['Menunggu hasil', 'wait'];
                                $bodyKey = 'menunggu';
                                $hint = 'Audisi sudah berlangsung. Menunggu pengumuman hasil dari admin.';
                            }

                            $peserta = $ov === 'peserta';
                            [$bc, $bd, $bt] = $BADGE[$ov];

                            /* Progress */
                            $pct = $peserta ? 100 : (($cur - 1) / $totalSteps) * 100 + 8;
                            $barClr = $fail ? 'bg-[#d9534f]' : 'bg-[#c99a32]';
                            $labelKiri = $fail
                                ? 'Proses berhenti di langkah ' . $cur
                                : ($peserta ? 'Seleksi selesai' : 'Langkah ' . $cur . ' dari ' . $totalSteps);
                            $labelKanan = $fail
                                ? 'Tidak dilanjutkan'
                                : ($peserta ? 'Kamu sudah menjadi peserta' : 'Dalam proses');

                            /* URL */
                            $urlBayarDaftar = $routeAda('peserta.pembayaran') ? route('peserta.pembayaran', $p->id) : '#';
                            $urlBayarRegis = $routeAda('peserta.registrasi.show') ? route('peserta.registrasi.show', $p->id) : '#';
                            $urlDataDaftar = $routeAda('peserta.pendaftaran.show') ? route('peserta.pendaftaran.show', $p->id) : '#';

                            $urlEvent = '/beranda';
                            if ($e) {
                                $tMulai = $e->tanggal_mulai ? \Carbon\Carbon::parse($e->tanggal_mulai) : null;
                                $tSelesai = $e->tanggal_selesai ? \Carbon\Carbon::parse($e->tanggal_selesai) : null;

                                if ($tMulai && $now->lt($tMulai)) {
                                    $urlEvent = route('detail-event.akan-datang', $e);
                                } elseif ($tSelesai && $now->gt($tSelesai)) {
                                    $urlEvent = route('detail-event.selesai', $e);
                                } else {
                                    $urlEvent = route('detail-event.berlangsung', $e);
                                }
                            }
                            $urlProfilPeserta = $urlEvent;
                        @endphp

                        <div class="card-premium bg-white rounded-2xl border border-[#ebe7df] p-5 sm:p-6 shadow-sm">

                            {{-- HEADER --}}
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-12 h-12 shrink-0 rounded-xl bg-[#22212b] overflow-hidden">
                                        @if ($e && $e->banner)
                                            <img src="{{ asset('storage/' . $e->banner) }}" alt="{{ $e->nama }}"
                                                class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-[#15233d] truncate">{{ $e->nama ?? 'Event' }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            {{ $p->namaTampil() }} · Didaftarkan {{ $p->tanggal_daftar?->format('d M Y') ?? '-' }}
                                        </p>
                                    </div>
                                </div>

                                <span class="shrink-0 inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-[10px] font-semibold {{ $bc }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $bd }}"></span>{{ $bt }}
                                </span>
                            </div>

                            {{-- PROGRESS --}}
                            <div class="mt-5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-semibold {{ $fail ? 'text-[#b23b3b]' : 'text-[#a37b2a]' }}">{{ $labelKiri }}</span>
                                    <span class="text-gray-400">{{ $labelKanan }}</span>
                                </div>
                                <div class="mt-1.5 h-1.5 rounded-full bg-[#eee6d2]">
                                    <div class="h-1.5 rounded-full {{ $barClr }} transition-all" style="width: {{ $pct }}%"></div>
                                </div>
                                <p class="mt-2 text-xs text-[#666]">{{ $hint }}</p>
                            </div>

                            {{-- TIMELINE --}}
                            <ol class="mt-6 border-t border-[#f0eadb] pt-6">
                                @foreach ($steps as $s)
                                    @php
                                        $last = $loop->last;
                                        $state = $s['n'] < $cur ? 'done' : ($s['n'] === $cur ? ($fail ? 'fail' : 'current') : 'locked');
                                    @endphp

                                    <li class="relative pl-12 {{ $last ? '' : 'pb-5' }}">
                                        @unless ($last)
                                            <span class="absolute left-[15px] top-9 bottom-1 w-px {{ $state === 'done' ? 'bg-[#e3c887]' : 'bg-[#e8e3d6]' }}"></span>
                                        @endunless

                                    {{-- Titik --}}
                                    @if ($state === 'done')
                                        <span class="absolute left-0 top-0 flex h-8 w-8 items-center justify-center rounded-full bg-[#c99a32] text-white">{!! $CHECK !!}</span>
                                    @elseif ($state === 'fail')
                                        <span class="absolute left-0 top-0 flex h-8 w-8 items-center justify-center rounded-full bg-[#d9534f] text-white shadow-[0_0_0_4px_rgba(217,83,79,.15)]">{!! $CROSS !!}</span>
                                    @elseif ($state === 'current')
                                        <span class="absolute left-0 top-0 flex h-8 w-8 items-center justify-center rounded-full border-2 border-[#c99a32] bg-white text-[13px] font-bold text-[#c99a32] shadow-[0_0_0_4px_rgba(201,154,50,.15)]">{{ $s['n'] }}</span>
                                    @else
                                        <span class="absolute left-0 top-0 flex h-8 w-8 items-center justify-center rounded-full bg-[#f3f1ec] text-[13px] font-semibold text-[#b5ad9c]">{{ $s['n'] }}</span>
                                    @endif

                                    {{-- Isi --}}
                                    @if ($state === 'done')
                                        <div class="flex items-start justify-between gap-3 pt-1">
                                            <p class="text-sm font-semibold text-[#15233d]">{{ $s['t'] }}</p>
                                            {!! $chip(...$s['chipDone']) !!}
                                        </div>
                                        <p class="mt-0.5 text-xs text-gray-500">{{ $s['txtDone'] }}</p>
                                        @if ($s['key'] === 'formulir')
                                            <a href="{{ $urlDataDaftar }}" class="mt-1.5 inline-flex items-center gap-1 text-xs font-medium text-[#a37b2a] hover:text-[#c99a32]">
                                                Lihat data pendaftaran <span>›</span>
                                            </a>
                                        @endif

                                    @elseif ($state === 'locked')
                                        @php $infoKunci = $s['key'] === 'audisi' && $lockedAudisi; @endphp
                                        <p class="pt-1 text-sm font-semibold text-[#a9a39a]">{{ $s['t'] }}</p>
                                        <p class="mt-0.5 text-xs {{ $infoKunci ? 'text-[#a08a50]' : 'text-[#b9b3a8]' }}">{{ $infoKunci ? $lockedAudisi : $s['d'] }}</p>

                                    @else
                                        <div class="rounded-xl border p-4 {{ $fail ? 'border-[#f1cccc] bg-[#fff8f8]' : 'border-[#ecdfc0] bg-[#fffcf3]' }}">
                                            <div class="flex items-start justify-between gap-3">
                                                <p class="text-sm font-semibold text-[#15233d]">{{ $s['t'] }}</p>
                                                {!! $chip(...$doneChip[$s['n']]) !!}
                                            </div>
                                            <p class="mb-3 mt-0.5 text-xs text-gray-500">{{ $s['d'] }}</p>

                                            @switch($bodyKey)
                                                @case('bayar')
                                                    <div class="flex items-end justify-between gap-3">
                                                        <div>
                                                            <p class="text-xs text-gray-500">Biaya pendaftaran</p>
                                                            <p class="text-[22px] font-bold text-[#13223a]">{{ $rp($biayaDaftar) }}</p>
                                                        </div>
                                                        <span class="text-[11px] text-gray-400">Sekali bayar, langsung lunas</span>
                                                    </div>
                                                    <a href="{{ $urlBayarDaftar }}" class="{{ $GOLD }}">Bayar Sekarang →</a>
                                                    @break

                                                @case('jadwal')
                                                    <div class="divide-y divide-[#f0eadb] rounded-lg border border-[#ece5d7] bg-white text-[12.5px]">
                                                        <div class="flex items-center justify-between gap-4 px-3.5 py-2.5">
                                                            <span class="text-gray-500">Tanggal</span>
                                                            @if ($audTgl)
                                                                <span class="text-right font-medium">{{ $tglJam($audTgl) }}</span>
                                                            @else
                                                                <span class="italic text-[#b0a999]">Belum ditentukan</span>
                                                            @endif
                                                        </div>
                                                        <div class="flex items-center justify-between gap-4 px-3.5 py-2.5">
                                                            <span class="text-gray-500">Lokasi</span>
                                                            @if ($audLokasi)
                                                                <span class="text-right font-medium">{{ $audLokasi }}</span>
                                                            @else
                                                                <span class="italic text-[#b0a999]">Belum ditentukan</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <p class="mt-3 text-xs leading-relaxed text-gray-500">Admin akan mengumumkan jadwal dan lokasi audisi. Setelah ditentukan, informasinya muncul otomatis di sini.</p>
                                                    @break

                                                @case('terjadwal')
                                                @case('menunggu')
                                                    <div class="divide-y divide-[#f0eadb] rounded-lg border border-[#ece5d7] bg-white text-[12.5px]">
                                                        <div class="flex items-center justify-between gap-4 px-3.5 py-2.5">
                                                            <span class="text-gray-500">Tanggal</span>
                                                            <span class="text-right font-medium">{{ $tglJam($audTgl) }}</span>
                                                        </div>
                                                        <div class="flex items-center justify-between gap-4 px-3.5 py-2.5">
                                                            <span class="text-gray-500">Lokasi</span>
                                                            @if ($audLokasi)
                                                                <span class="text-right font-medium">{{ $audLokasi }}</span>
                                                            @else
                                                                <span class="text-right italic text-[#b0a999]">Akan diumumkan admin</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <p class="mt-3 text-xs leading-relaxed text-gray-500">
                                                        @if ($bodyKey === 'terjadwal')
                                                            Hadir tepat waktu. Kalau ada perubahan, jadwal di sini ikut diperbarui.
                                                        @else
                                                            Audisi sudah berlangsung. Hasilnya akan diumumkan admin dan muncul di halaman ini.
                                                        @endif
                                                    </p>
                                                    @break

                                                @case('susulan')
                                                    <p class="text-[12.5px] leading-relaxed text-[#666]">Audisi resmi sudah berlangsung sebelum kamu mendaftar. Informasi audisi susulan akan disampaikan langsung oleh admin, dan hasilnya muncul di halaman ini.</p>
                                                    @break

                                                @case('gugur')
                                                    <div class="rounded-lg border border-[#f1cccc] bg-white px-3.5 py-3 text-[12.5px] leading-relaxed text-[#6b4a4a]">
                                                        Terima kasih sudah mengikuti audisi. Sayangnya kamu belum lolos, sehingga tidak dilanjutkan ke tahap berikutnya. Biaya pendaftaran tidak dikembalikan.
                                                    </div>
                                                    @break
                                            @endswitch
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ol>

                        {{-- ============ SETELAH LOLOS: REGISTRASI + VOTING ============ --}}
                        @if ($peserta)
                            <div class="mt-6 border-t border-[#f0eadb] pt-6">

                                <div class="rounded-lg bg-[#e9f8f1] px-3.5 py-3 text-[12.5px] text-[#0f6b49]">
                                    <p class="font-semibold">Selamat! Kamu resmi menjadi peserta.</p>
                                    <p class="mt-0.5">Profilmu sudah tampil di daftar peserta event.
                                        <a href="{{ $urlProfilPeserta }}" class="font-semibold underline">Lihat profil saya</a>
                                    </p>
                                </div>

                                @if ($punyaRegis)
                            <p class="mt-4 text-xs text-gray-500">Registrasi dan voting berjalan bersamaan. Registrasi bisa dibayar kapan saja, asal sebelum voting berakhir.</p>
                            @endif

                                <div class="mt-3 space-y-3.5">

                                    {{-- ---------- REGISTRASI ---------- --}}
                                    @if ($punyaRegis)
                                    @php
                                        $descRegis = 'Biaya ' . $rp($biayaRegis) . ', bisa lunas atau cicil 2 kali';
                                        $okRegis = $regStatus === 'lunas';
                                    @endphp
                                    <div class="rounded-xl border p-4 {{ $okRegis ? 'border-[#cfe6d8] bg-[#f6fbf8]' : 'border-[#ecdfc0] bg-[#fffcf3]' }}">
                                        <div class="flex items-start justify-between gap-3">
                                            <p class="text-sm font-semibold text-[#15233d]">Registrasi</p>
                                            @if ($regStatus === 'lunas')
                                                {!! $chip('Lunas', 'ok') !!}
                                            @elseif ($regStatus === 'dp')
                                                {!! $chip('DP terbayar', 'info') !!}
                                            @else
                                                {!! $chip('Belum dibayar', 'wait') !!}
                                            @endif
                                        @elseif ($state === 'locked')
                                            @php $infoKunci = $s['n'] === 3 && $lockedAudisi; @endphp
                                            <p class="pt-1 text-sm font-semibold text-[#a9a39a]">{{ $s['t'] }}</p>
                                            <p class="mt-0.5 text-xs {{ $infoKunci ? 'text-[#a08a50]' : 'text-[#b9b3a8]' }}">
                                                {{ $infoKunci ? $lockedAudisi : $s['d'] }}</p>
                                        @else
                                            <div class="rounded-xl border p-4 {{ $fail ? 'border-[#f1cccc] bg-[#fff8f8]' : 'border-[#ecdfc0] bg-[#fffcf3]' }}">
                                                <div class="flex items-start justify-between gap-3">
                                                    <p class="text-sm font-semibold text-[#15233d]">{{ $s['t'] }}</p>
                                                    {!! $chip(...$chipCur) !!}
                                                </div>
                                                <p class="mb-3 mt-0.5 text-xs text-gray-500">{{ $s['d'] }}</p>

                                                @switch($bodyKey)
                                                    @case('bayar')
                                                        <div class="flex items-end justify-between gap-3">
                                                            <div>
                                                                <p class="text-xs text-gray-500">Biaya pendaftaran</p>
                                                                <p class="text-[22px] font-bold text-[#13223a]">{{ $rp($biayaDaftar) }}</p>
                                                            </div>
                                                            <span class="text-[11px] text-gray-400">Sekali bayar, langsung lunas</span>
                                                        </div>
                                                        <a href="{{ $urlBayarDaftar }}" class="{{ $GOLD }}">Bayar Sekarang</a>
                                                    @break

                                                    @case('jadwal')
                                                        <div class="divide-y divide-[#f0eadb] rounded-lg border border-[#ece5d7] bg-white text-[12.5px]">
                                                            <div class="flex items-center justify-between gap-4 px-3.5 py-2.5">
                                                                <span class="text-gray-500">Tanggal</span>
                                                                @if ($audTgl)
                                                                    <span class="text-right font-medium">{{ $tglJam($audTgl) }}</span>
                                                                @else
                                                                    <span class="italic text-[#b0a999]">Belum ditentukan</span>
                                                                @endif
                                                            </div>
                                                            <div class="flex items-center justify-between gap-4 px-3.5 py-2.5">
                                                                <span class="text-gray-500">Lokasi</span>
                                                                @if ($audLokasi)
                                                                    <span class="text-right font-medium">{{ $audLokasi }}</span>
                                                                @else
                                                                    <span class="italic text-[#b0a999]">Belum ditentukan</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <p class="mt-3 text-xs leading-relaxed text-gray-500">Admin akan mengumumkan jadwal dan lokasi audisi. Setelah ditentukan, informasinya muncul otomatis di sini.</p>
                                                    @break

                                                    @case('terjadwal')
                                                    @case('menunggu')
                                                        <div class="divide-y divide-[#f0eadb] rounded-lg border border-[#ece5d7] bg-white text-[12.5px]">
                                                            <div class="flex items-center justify-between gap-4 px-3.5 py-2.5">
                                                                <span class="text-gray-500">Tanggal</span>
                                                                <span class="text-right font-medium">{{ $tglJam($audTgl) }}</span>
                                                            </div>
                                                            <div class="flex items-center justify-between gap-4 px-3.5 py-2.5">
                                                                <span class="text-gray-500">Lokasi</span>
                                                                <span class="text-right font-medium">{{ $audLokasi }}</span>
                                                            </div>
                                                        </div>
                                                        <p class="mt-3 text-xs leading-relaxed text-gray-500">
                                                            @if ($bodyKey === 'terjadwal')
                                                                Hadir tepat waktu. Kalau ada perubahan, jadwal di sini ikut diperbarui.
                                                            @else
                                                                Audisi sudah berlangsung. Hasilnya akan diumumkan admin dan muncul di halaman ini.
                                                            @endif
                                                        </p>
                                                    @break

                                                    @case('susulan')
                                                        <p class="text-[12.5px] leading-relaxed text-[#666]">
                                                            Audisi resmi sudah berlangsung sebelum kamu mendaftar. Informasi audisi susulan akan disampaikan langsung oleh admin, dan hasilnya muncul di halaman ini.
                                                        </p>
                                                    @break

                                                    @case('gugur')
                                                        <div class="rounded-lg border border-[#f1cccc] bg-white px-3.5 py-3 text-[12.5px] leading-relaxed text-[#6b4a4a]">
                                                            Terima kasih sudah mengikuti audisi. Sayangnya kamu belum lolos, sehingga tidak dilanjutkan ke tahap berikutnya. Biaya pendaftaran tidak dikembalikan.
                                                        </div>
                                                    @break
                                                @endswitch
                                            </div>
                                        @endif
                                    </li>
                                @endforeach
                            </ol>

                            {{-- SETELAH LOLOS: REGISTRASI + VOTING --}}
                            @if ($peserta)
                                <div class="mt-6 border-t border-[#f0eadb] pt-6">

                                    <div class="rounded-lg bg-[#e9f8f1] px-3.5 py-3 text-[12.5px] text-[#0f6b49]">
                                        <p class="font-semibold">Selamat! Kamu resmi menjadi peserta.</p>
                                        <p class="mt-0.5">Profilmu sudah tampil di daftar peserta event.
                                            <a href="{{ $urlProfilPeserta }}" class="font-semibold underline">Lihat profil saya</a>
                                        </p>
                                    </div>

                                    @if ($butuhRegistrasi)
                                        <p class="mt-4 text-xs text-gray-500">Registrasi dan voting berjalan bersamaan. Registrasi bisa dibayar kapan saja, asal sebelum voting berakhir.</p>
                                    @endif

                                    <div class="mt-3 space-y-3.5">

                                        {{-- REGISTRASI — cuma kalau butuh_registrasi = true --}}
                                        @if ($butuhRegistrasi)
                                            @php
                                                $descRegis = 'Biaya ' . $rp($biayaRegis) . ', bisa lunas atau DP dulu';
                                                $okRegis = $regStatus === 'lunas';
                                            @endphp
                                            <div class="rounded-xl border p-4 {{ $okRegis ? 'border-[#cfe6d8] bg-[#f6fbf8]' : 'border-[#ecdfc0] bg-[#fffcf3]' }}">
                                                <div class="flex items-start justify-between gap-3">
                                                    <p class="text-sm font-semibold text-[#15233d]">Registrasi</p>
                                                    @if ($regStatus === 'lunas')
                                                        {!! $chip('Lunas', 'ok') !!}
                                                    @elseif ($regStatus === 'dp')
                                                        {!! $chip('DP terbayar', 'info') !!}
                                                    @else
                                                        {!! $chip('Belum dibayar', 'wait') !!}
                                                    @endif
                                                </div>
                                                <p class="mb-3 mt-0.5 text-xs text-gray-500">{{ $descRegis }}</p>

                                                @if ($regStatus === 'lunas')
                                                    <p class="text-[12.5px] text-[#666]">
                                                        Dibayar {{ $rp($totalRegis) }}
                                                        @if ($cicilan->last())
                                                            · {{ $tgl($toC($cicilan->last()->tanggal_bayar ?? $cicilan->last()->created_at)) }}
                                                        @endif.
                                                        Terima kasih, registrasimu sudah selesai.
                                                    </p>
                                                @elseif ($regStatus === 'dp')
                                                    <p class="text-xs text-gray-500">Terbayar</p>
                                                    <p class="text-xl font-bold text-[#13223a]">{{ $rp($totalRegis) }}
                                                        <span class="text-xs font-medium text-gray-400">dari {{ $rp($biayaRegis) }}</span>
                                                    </p>
                                                    <div class="mt-2 h-1.5 rounded-full bg-[#eee6d2]">
                                                        <div class="h-1.5 rounded-full bg-[#c99a32]"
                                                            style="width: {{ $biayaRegis > 0 ? min(round(($totalRegis / $biayaRegis) * 100), 100) : 0 }}%">
                                                        </div>
                                                    </div>

                                                    <div class="mt-4 divide-y divide-[#f0eadb] rounded-lg border border-[#ece5d7] bg-white text-[12.5px]">
                                                        <div class="flex items-center justify-between gap-3 px-3.5 py-3">
                                                            <div>
                                                                <p class="font-medium">Pembayaran DP</p>
                                                                <p class="text-[11px] text-gray-400">Dibayar
                                                                    {{ $tgl($toC($cicilan->first()->tanggal_bayar ?? ($cicilan->first()->created_at ?? null))) }}
                                                                </p>
                                                            </div>
                                                            <div class="flex items-center gap-3">
                                                                <b>{{ $rp($totalRegis) }}</b>{!! $chip('Lunas', 'ok') !!}
                                                            </div>
                                                        </div>
                                                        <div class="flex items-center justify-between gap-3 px-3.5 py-3">
                                                            <div>
                                                                <p class="font-medium">Pembayaran Pelunasan</p>
                                                                <p class="text-[11px] text-gray-400">
                                                                    Batas bayar: sebelum voting berakhir{{ $vAkhir ? ', ' . $tgl($vAkhir) : '' }}
                                                                    @if ($vStatus === 'jalan' && $teksSisa)
                                                                        <span class="font-semibold text-[#9a6f10]">· {{ $teksSisa }}</span>
                                                                    @endif
                                                                </p>
                                                            </div>
                                                            <div class="flex items-center gap-3">
                                                                <b>{{ $rp($sisaRegis) }}</b>{!! $chip('Belum dibayar', 'wait') !!}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @if ($bisaBayarRegis)
                                                        <button type="button" data-bayar-regis="{{ $p->id }}"
                                                            data-metode="pelunasan"
                                                            class="{{ $GOLD }} inline-flex items-center justify-center gap-2 w-full">
                                                            Bayar Sisa {{ $rp($sisaRegis) }}
                                                        </button>
                                                        <p class="mt-2 text-[11.5px] text-gray-400">Sisa pembayaran bisa dilunasi kapan saja, asal sebelum voting berakhir.</p>
                                                    @else
                                                        <p class="mt-3 text-[12px] text-[#9a6f10]">Batas pembayaran sudah berakhir karena voting telah selesai. Silakan hubungi admin.</p>
                                                    @endif
                                                @else
                                                    @if ($vStatus === 'jalan')
                                                        <div class="mb-3 rounded-lg bg-[#fff3d6] px-3.5 py-2.5 text-xs text-[#8a6310]">
                                                            Voting sedang berjalan. Selesaikan registrasi sebelum voting berakhir{{ $vAkhir ? ', ' . $tgl($vAkhir) : '' }}
                                                            @if ($teksSisa)
                                                                <b>({{ $teksSisa }})</b>
                                                            @endif.
                                                        </div>
                                                    @endif

                                                    @if ($bisaBayarRegis)
                                                        <div class="form-regis" data-form-regis="{{ $p->id }}">
                                                            <p class="text-[12.5px] text-[#666]">Pilih cara pembayaran:</p>
                                                            <div class="mt-3 grid gap-3 sm:grid-cols-2">
                                                                <label
                                                                    class="metode-opt is-on cursor-pointer rounded-xl border border-[#e6e0d2] bg-white/60 p-3.5 text-left hover:border-[#d9c48a]"
                                                                    data-metode="lunas">
                                                                    <input type="radio" name="metode_{{ $p->id }}"
                                                                        value="lunas" class="sr-only" checked>
                                                                    <span class="flex items-center gap-2.5">
                                                                        <span class="ring flex h-4 w-4 items-center justify-center rounded-full border border-[#cfc8b6]"><span class="dot"></span></span>
                                                                        <span class="text-[13px] font-semibold">Bayar Lunas</span>
                                                                    </span>
                                                                    <span class="mt-2 block text-[17px] font-bold">{{ $rp($biayaRegis) }}</span>
                                                                    <span class="mt-0.5 block text-[11.5px] leading-snug text-gray-500">Sekali bayar, registrasi langsung selesai.</span>
                                                                </label>
                                                                <label
                                                                    class="metode-opt cursor-pointer rounded-xl border border-[#e6e0d2] bg-white/60 p-3.5 text-left hover:border-[#d9c48a]"
                                                                    data-metode="cicil">
                                                                    <input type="radio" name="metode_{{ $p->id }}"
                                                                        value="cicil" class="sr-only">
                                                                    <span class="flex items-center gap-2.5">
                                                                        <span class="ring flex h-4 w-4 items-center justify-center rounded-full border border-[#cfc8b6]"><span class="dot"></span></span>
                                                                        <span class="text-[13px] font-semibold">Bayar DP</span>
                                                                    </span>
                                                                    <span class="mt-2 block text-[17px] font-bold">DP {{ $rp($dpTampil) }}</span>
                                                                    <span class="mt-0.5 block text-[11.5px] leading-snug text-gray-500">Sisa {{ $rp(max($biayaRegis - $dpTampil, 0)) }} dibayar di cicilan kedua.</span>
                                                                </label>
                                                            </div>

                                                            <div data-cicil-info
                                                                class="mt-3 hidden divide-y divide-[#f0eadb] rounded-lg border border-[#ece5d7] bg-white text-[12.5px]">
                                                                <div class="flex items-center justify-between gap-4 px-3.5 py-2.5">
                                                                    <span class="text-gray-500">Pembayaran DP</span><span class="font-medium">{{ $rp($dpTampil) }}</span>
                                                                </div>
                                                                <div class="flex items-center justify-between gap-4 px-3.5 py-2.5">
                                                                    <span class="text-gray-500">Pembayaran Pelunasan</span><span class="font-medium">{{ $rp(max($biayaRegis - $dpTampil, 0)) }}</span>
                                                                </div>
                                                            </div>

                                                            {{-- Pilihan Metode Pembayaran --}}
                                                            <div class="mt-4">
                                                                <p class="text-[12.5px] text-[#666] mb-2">Pilih metode pembayaran:</p>
                                                                <div class="grid gap-3 sm:grid-cols-2">
                                                                    {{-- BRI VA --}}
                                                                    <label
                                                                        class="metode-bayar-opt is-on cursor-pointer rounded-xl border border-[#e6e0d2] bg-white/60 p-3.5 text-left hover:border-[#d9c48a]"
                                                                        data-payment-method="bri_va">
                                                                        <input type="radio" name="payment_method_{{ $p->id }}" value="bri_va" class="sr-only" checked>
                                                                        <span class="flex items-center gap-2.5">
                                                                            <span class="ring flex h-4 w-4 items-center justify-center rounded-full border border-[#cfc8b6]">
                                                                                <span class="dot"></span>
                                                                            </span>
                                                                            <span class="flex items-center gap-2">
                                                                                <svg class="w-4 h-4 text-[#c99a32]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                                                </svg>
                                                                                <span class="text-[13px] font-semibold">BRI Virtual Account</span>
                                                                            </span>
                                                                        </span>
                                                                        <span class="mt-0.5 block text-[11.5px] leading-snug text-gray-500">Transfer via m-Banking / ATM</span>
                                                                    </label>
                                                                    {{-- QRIS --}}
                                                                    <label
                                                                        class="metode-bayar-opt cursor-pointer rounded-xl border border-[#e6e0d2] bg-white/60 p-3.5 text-left hover:border-[#d9c48a]"
                                                                        data-payment-method="qris">
                                                                        <input type="radio" name="payment_method_{{ $p->id }}" value="qris" class="sr-only">
                                                                        <span class="flex items-center gap-2.5">
                                                                            <span class="ring flex h-4 w-4 items-center justify-center rounded-full border border-[#cfc8b6]">
                                                                                <span class="dot"></span>
                                                                            </span>
                                                                            <span class="flex items-center gap-2">
                                                                                <svg class="w-4 h-4 text-[#c99a32]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                                                                </svg>
                                                                                <span class="text-[13px] font-semibold">QRIS</span>
                                                                            </span>
                                                                        </span>
                                                                        <span class="mt-0.5 block text-[11.5px] leading-snug text-gray-500">Scan QR pakai e-wallet / m-Banking</span>
                                                                    </label>
                                                                </div>
                                                            </div>

                                                            <button type="button" data-bayar-regis="{{ $p->id }}"
                                                                data-metode="lunas"
                                                                data-lunas="Bayar Lunas {{ $rp($biayaRegis) }}"
                                                                data-cicil="Bayar DP {{ $rp($dpTampil) }}"
                                                                class="{{ $GOLD }} inline-flex items-center justify-center gap-2 w-full">
                                                                Bayar Lunas {{ $rp($biayaRegis) }}
                                                            </button>
                                                            <p class="mt-2 text-[11.5px] text-gray-400">Bisa dibayar kapan saja, asal sebelum voting berakhir{{ $vAkhir ? ' (' . $tgl($vAkhir) . ')' : '' }}.</p>
                                                        </div>
                                                    @else
                                                        <p class="text-[12px] text-[#9a6f10]">Batas pembayaran sudah berakhir karena voting telah selesai. Silakan hubungi admin.</p>
                                                    @endif
                                                @endif
                                            </div>
                                        @endif

                                        {{-- VOTING --}}
                                        @php $okVote = $vStatus === 'jalan'; @endphp
                                        <div class="rounded-xl border p-4 {{ $okVote ? 'border-[#cfe6d8] bg-[#f6fbf8]' : 'border-[#ecdfc0] bg-[#fffcf3]' }}">
                                            <div class="flex items-start justify-between gap-3">
                                                <p class="text-sm font-semibold text-[#15233d]">Voting</p>
                                                @if ($vStatus === 'jalan')
                                                    {!! $chip('Berlangsung', 'ok') !!}
                                                @elseif ($vStatus === 'selesai')
                                                    {!! $chip('Berakhir', 'neutral') !!}
                                                @elseif ($vStatus === 'ditutup')
                                                    {!! $chip('Ditutup', 'bad') !!}
                                                @elseif ($vStatus === 'event_belum_mulai')
                                                    {!! $chip('Event Belum Mulai', 'wait') !!}
                                                @else
                                                    {!! $chip('Belum dibuka', 'neutral') !!}
                                                @endif
                                            </div>

                                            @if ($vStatus === 'belum')
                                                <p class="mb-3 mt-0.5 text-xs text-gray-500">Dibuka sesuai jadwal event</p>
                                                <p class="text-[12.5px] text-[#666]">
                                                    @if ($vMulai)
                                                        Voting dibuka <b class="text-[#15233d]">{{ $vMulai->translatedFormat('d M Y, H.i') }} WIB</b>
                                                        @if ($vAkhir)
                                                            dan berakhir {{ $tgl($vAkhir) }}
                                                        @endif.
                                                    @else
                                                        Jadwal voting akan diumumkan di halaman event.
                                                    @endif
                                                </p>
                                                <a href="{{ $urlEvent }}" class="{{ $OUTLINE }}">Lihat Halaman Event</a>
                                            @else
                                                <p class="mb-3 mt-0.5 text-xs text-gray-500">
                                                    {{ $vStatus === 'jalan' ? 'Berakhir' : 'Berakhir pada' }} {{ $tgl($vAkhir) }}
                                                </p>
                                                @php
                                                    $totalSemuaVote = \App\Models\Voting::where('event_id', $e->id)
                                                        ->where('status_midtrans', 'settlement')
                                                        ->sum('jumlah_vote');

                                                    $totalVoteKu = (int) ($p->total_vote ?? 0);
                                                    $persenSuara = $totalSemuaVote > 0 ? round(($totalVoteKu / $totalSemuaVote) * 100, 1) : 0;

                                                    $peringkatKu = $p->peringkat ?? null;
                                                    $totalPeserta = $p->total_peserta ?? null;

                                                    if ($peringkatKu === null) {
                                                        $semuaPeserta = \App\Models\Peserta::where('event_id', $e->id)
                                                            ->where('status', 'diterima')
                                                            ->orderByDesc('total_vote')
                                                            ->pluck('id')
                                                            ->toArray();
                                                        $indexKu = array_search($p->id, $semuaPeserta);
                                                        $peringkatKu = $indexKu !== false ? $indexKu + 1 : null;
                                                        $totalPeserta = count($semuaPeserta);
                                                    }
                                                @endphp

                                                <div class="grid grid-cols-2 overflow-hidden rounded-lg border border-[#ece5d7] bg-white text-center">
                                                    <div class="px-2 py-3">
                                                        <p class="text-[10px] text-gray-500">
                                                            {{ $vStatus === 'jalan' ? 'Persentase Sementara' : 'Persentase Akhir' }}
                                                        </p>
                                                        <p class="mt-1 text-base font-bold text-[#c87918] tabular-nums">{{ $persenSuara }}%</p>
                                                    </div>
                                                    <div class="border-l border-[#e8e1d5] px-2 py-3">
                                                        <p class="text-[10px] text-gray-500">
                                                            Peringkat {{ $vStatus === 'jalan' ? 'saat ini' : 'akhir' }}
                                                        </p>
                                                        <p class="mt-1 text-base font-bold">
                                                            {{ $peringkatKu ? '#' . $peringkatKu : '-' }}
                                                            @if ($totalPeserta)
                                                                <span class="text-[11px] font-medium text-gray-400">dari {{ $totalPeserta }}</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            @if ($bisaBayarRegis)
                                                <form method="POST" action="{{ $urlBayarRegis }}">
                                                    @csrf
                                                    <input type="hidden" name="metode" value="pelunasan">
                                                    <button type="submit" class="{{ $GOLD }}">Bayar Sisa {{ $rp($sisaRegis) }} →</button>
                                                </form>
                                                <p class="mt-2 text-[11.5px] text-gray-400">Sisa pembayaran bisa dilunasi kapan saja, asal sebelum voting berakhir. Registrasi yang belum lunas saat voting berakhir dapat membuat profilmu dikeluarkan dari peringkat.</p>
                                            @else
                                                <p class="mt-3 text-[12px] text-[#9a6f10]">Batas pembayaran sudah berakhir karena voting telah selesai. Silakan hubungi admin.</p>
                                            @endif

                                        @else
                                            @if ($vStatus === 'jalan')
                                                <div class="mb-3 rounded-lg bg-[#fff3d6] px-3.5 py-2.5 text-xs text-[#8a6310]">
                                                    Voting sedang berjalan. Selesaikan registrasi sebelum voting berakhir{{ $vAkhir ? ', ' . $tgl($vAkhir) : '' }}
                                                    @if ($teksSisa) <b>({{ $teksSisa }})</b>@endif.
                                                </div>
                                            @endif

                                            @if ($bisaBayarRegis)
                                                <form method="POST" action="{{ $urlBayarRegis }}" class="form-regis">
                                                    @csrf
                                                    <p class="text-[12.5px] text-[#666]">Pilih cara pembayaran:</p>
                                                    <div class="mt-3 grid gap-3 sm:grid-cols-2">
                                                        <label class="metode-opt is-on cursor-pointer rounded-xl border border-[#e6e0d2] bg-white/60 p-3.5 text-left hover:border-[#d9c48a]">
                                                            <input type="radio" name="metode" value="lunas" class="sr-only" checked>
                                                            <span class="flex items-center gap-2.5">
                                                                <span class="ring flex h-4 w-4 items-center justify-center rounded-full border border-[#cfc8b6]"><span class="dot"></span></span>
                                                                <span class="text-[13px] font-semibold">Bayar Lunas</span>
                                                            </span>
                                                            <span class="mt-2 block text-[17px] font-bold">{{ $rp($biayaRegis) }}</span>
                                                            <span class="mt-0.5 block text-[11.5px] leading-snug text-gray-500">Sekali bayar, registrasi langsung selesai.</span>
                                                        </label>
                                                        <label class="metode-opt cursor-pointer rounded-xl border border-[#e6e0d2] bg-white/60 p-3.5 text-left hover:border-[#d9c48a]">
                                                            <input type="radio" name="metode" value="cicil" class="sr-only">
                                                            <span class="flex items-center gap-2.5">
                                                                <span class="ring flex h-4 w-4 items-center justify-center rounded-full border border-[#cfc8b6]"><span class="dot"></span></span>
                                                                <span class="text-[13px] font-semibold">Cicil 2 Kali</span>
                                                            </span>
                                                            <span class="mt-2 block text-[17px] font-bold">DP {{ $rp($dpTampil) }}</span>
                                                            <span class="mt-0.5 block text-[11.5px] leading-snug text-gray-500">Sisa {{ $rp(max($biayaRegis - $dpTampil, 0)) }} dibayar di cicilan kedua.</span>
                                                        </label>
                                                    </div>

                                                    <div data-cicil-info class="mt-3 hidden divide-y divide-[#f0eadb] rounded-lg border border-[#ece5d7] bg-white text-[12.5px]">
                                                        <div class="flex items-center justify-between gap-4 px-3.5 py-2.5"><span class="text-gray-500">Cicilan 1 · DP (bayar sekarang)</span><span class="font-medium">{{ $rp($dpTampil) }}</span></div>
                                                        <div class="flex items-center justify-between gap-4 px-3.5 py-2.5"><span class="text-gray-500">Cicilan 2 · Pelunasan</span><span class="font-medium">{{ $rp(max($biayaRegis - $dpTampil, 0)) }}</span></div>
                                                    </div>

                                                    <button type="submit" data-submit
                                                        data-lunas="Bayar Lunas {{ $rp($biayaRegis) }} →"
                                                        data-cicil="Bayar DP {{ $rp($dpTampil) }} →"
                                                        class="{{ $GOLD }}">Bayar Lunas {{ $rp($biayaRegis) }} →</button>
                                                    <p class="mt-2 text-[11.5px] text-gray-400">Bisa dibayar kapan saja, asal sebelum voting berakhir{{ $vAkhir ? ' (' . $tgl($vAkhir) . ')' : '' }}. Registrasi yang belum lunas saat voting berakhir dapat membuat profilmu dikeluarkan dari peringkat.</p>
                                                </form>
                                            @else
                                                <p class="text-[12px] text-[#9a6f10]">Batas pembayaran sudah berakhir karena voting telah selesai. Silakan hubungi admin.</p>
                                            @endif
                                        @endif
                                    </div>

                                    @endif

                                {{-- ---------- VOTING ---------- --}}
                                    @php $okVote = $vStatus === 'jalan'; @endphp
                                    <div class="rounded-xl border p-4 {{ $okVote ? 'border-[#cfe6d8] bg-[#f6fbf8]' : 'border-[#ecdfc0] bg-[#fffcf3]' }}">
                                        <div class="flex items-start justify-between gap-3">
                                            <p class="text-sm font-semibold text-[#15233d]">Voting</p>
                                            @if ($vStatus === 'jalan')
                                                {!! $chip('Berlangsung', 'ok') !!}
                                            @elseif ($vStatus === 'selesai')
                                                {!! $chip('Berakhir', 'neutral') !!}
                                            @else
                                                {!! $chip('Belum Dibuka', 'neutral') !!}
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- KELUAR + KEMBALI --}}
        <div class="reveal-up delay-4 flex flex-col items-center gap-3 mt-10">
            <a href="/beranda"
                class="inline-flex items-center gap-1.5 text-xs text-[#a37b2a] hover:text-[#c99a32] transition font-medium">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Beranda
            </a>

            <form method="POST" action="{{ route('profil-user.logout') }}">
                @csrf
                <button type="submit" class="text-xs text-gray-500 hover:text-red-600 transition">
                    Keluar dari Akun
                </button>
            </form>
        </div>

    </main>

    {{-- LIGHTBOX --}}
    @if ($fotoTampil)
        <div id="lightbox"
            class="hidden fixed inset-0 z-[60] items-center justify-center bg-black/80 px-5 backdrop-blur-sm">
            <div class="relative">
                <div
                    class="w-64 h-64 md:w-80 md:h-80 rounded-full overflow-hidden bg-gradient-to-br from-[#a37b2a] to-[#c99a32] text-white font-display text-8xl font-bold flex items-center justify-center shadow-2xl ring-4 ring-white/20">
                    <img src="{{ $fotoTampil }}" alt="Foto profil" class="w-full h-full object-cover">
                </div>
                <button type="button" id="lightTutup" aria-label="Tutup"
                    class="absolute -top-2 -right-2 w-9 h-9 rounded-full bg-white text-[#15233d] shadow-lg flex items-center justify-center text-lg leading-none hover:scale-110 transition">
                    &times;
                </button>
            </div>
        </div>
    @endif

    {{-- TOAST --}}
    @if (session('status'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                toast(@json(session('status')), 'success');
            });
        </script>
    @endif

    @if ($errors->first('foto'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                swalAlert(@json($errors->first('foto')), 'error');
            });
        </script>
    @endif

    @if ($errors->first('nama_custom'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                swalAlert(@json($errors->first('nama_custom')), 'error');
            });
        </script>
    @endif

    <script>
        const $ = id => document.getElementById(id);

        let toastTimer;
        const tampilToast = (teks) => {
            if (typeof toast === 'function') {
                toast(teks, 'info');
            } else {
                alert(teks);
            }
        };

        // Edit nama
        const input = $('inputNama');
        const btnSimpan = $('btnSimpan');
        const pesan = $('pesan');
        const infoAwal = '1–20 karakter: huruf dan spasi';
        const valid = v => /^[A-Za-z\s]{1,20}$/.test(v);

        input.addEventListener('input', () => {
            const v = input.value.trim();
            if (!valid(v)) {
                pesan.textContent = 'Hanya huruf dan spasi, maksimal 20 karakter';
                pesan.className = 'text-red-600';
                btnSimpan.disabled = true;
            } else {
                pesan.textContent = infoAwal;
                pesan.className = 'text-gray-400';
                btnSimpan.disabled = (v === input.dataset.awal);
            }
        });

        // Menu foto
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
            if (!menu.contains(e.target) && e.target !== btnAvatar) bukaMenu(false);
        });

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

        if ($('menuHapus')) {
            $('menuHapus').addEventListener('click', () => {
                bukaMenu(false);
                $('formHapusFoto').requestSubmit();
            });
        }

        // Lightbox
        if ($('lightbox')) {
            const lightbox = $('lightbox');
            const tutupLihat = () => {
                lightbox.classList.add('hidden');
                lightbox.classList.remove('flex');
            };
            if ($('menuLihat')) {
                $('menuLihat').addEventListener('click', () => {
                    bukaMenu(false);
                    lightbox.classList.remove('hidden');
                    lightbox.classList.add('flex');
                });
            }
            $('lightTutup').addEventListener('click', tutupLihat);
            lightbox.addEventListener('click', e => {
                if (e.target === lightbox) tutupLihat();
            });
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') {
                    bukaMenu(false);
                    tutupLihat();
                }
            });
        }

        // REGISTRASI — AJAX
        document.querySelectorAll('.form-regis').forEach(form => {
            form.querySelectorAll('.metode-opt').forEach(opt => {
                opt.addEventListener('click', () => {
                    form.querySelectorAll('.metode-opt').forEach(x => x.classList.remove('is-on'));
                    opt.classList.add('is-on');
                    opt.querySelector('input').checked = true;

                    const val = opt.dataset.metode;
                    const btn = form.querySelector('[data-bayar-regis]');
                    const info = form.querySelector('[data-cicil-info]');

                    btn.dataset.metode = val;
                    if (val === 'cicil') {
                        btn.textContent = btn.dataset.cicil;
                        info.classList.remove('hidden');
                    } else {
                        btn.textContent = btn.dataset.lunas;
                        info.classList.add('hidden');
                    }
                });
            });
        });

        

        // Toggle pilihan metode bayar (QRIS / VA)
        document.querySelectorAll('.metode-bayar-opt').forEach(opt => {
            opt.addEventListener('click', () => {
                const parent = opt.parentElement;
                if (parent) {
                    parent.querySelectorAll('.metode-bayar-opt').forEach(x => x.classList.remove('is-on'));
                }
                opt.classList.add('is-on');
                opt.querySelector('input').checked = true;
            });
        });

        document.querySelectorAll('[data-bayar-regis]').forEach(btn => {
            btn.addEventListener('click', async function() {
                const pesertaId = this.dataset.bayarRegis;
                const metode = this.dataset.metode;

                // Ambil payment_method dari radio yang dipilih
                const paymentMethodEl = document.querySelector(`input[name="payment_method_${pesertaId}"]:checked`);
                const paymentMethod = paymentMethodEl?.value || 'bri_va';

                const originalText = this.innerHTML;
                this.disabled = true;
                this.innerHTML = 'Memproses...';

                try {
                    const res1 = await fetch(`/peserta/${pesertaId}/registrasi/bayar`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            metode: metode,
                            payment_method: paymentMethod,   // ← kirim pilihan user
                        }),
                    });

                    const data1 = await res1.json();
                    if (!res1.ok) throw new Error(data1.message || 'Gagal memproses.');

                    window.location.href = `/peserta/${pesertaId}/registrasi/instruksi`;

                } catch (err) {
                    alert('Error: ' + err.message);
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            });
        });
    </script>

</body>

</html>