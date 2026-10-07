<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Profil Saya - ALNAY.ID</title>

    <script src="https://cdn.tailwindcss.com"></script>
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

        /* ============================================ */
        /* CARD PREMIUM                                  */
        /* ============================================ */
        .card-premium {
            transition: all .35s cubic-bezier(.22, 1, .36, 1);
        }

        .card-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 40px -20px rgba(201, 154, 50, .15);
            border-color: #E6D3A7;
        }

        /* ============================================ */
        /* ANIMASI REVEAL — STAGGER                      */
        /* ============================================ */
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

        .reveal-up.delay-1 { animation-delay: .08s; }
        .reveal-up.delay-2 { animation-delay: .16s; }
        .reveal-up.delay-3 { animation-delay: .24s; }
        .reveal-up.delay-4 { animation-delay: .32s; }

        /* ============================================ */
        /* AVATAR — HOVER + FLOAT                        */
        /* ============================================ */
        .avatar-wrap {
            transition: transform .35s cubic-bezier(.22, 1, .36, 1);
        }

        .avatar-wrap:hover {
            transform: scale(1.05);
        }

        .avatar-ring {
            box-shadow: 0 0 0 3px white, 0 0 0 5px rgba(201, 154, 50, .25);
        }

        /* ============================================ */
        /* BTN AVATAR — PULSE SUBTLE                     */
        /* ============================================ */
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
            0% { opacity: .5; transform: scale(1); }
            100% { opacity: 0; transform: scale(1.15); }
        }

        /* ============================================ */
        /* STAT CARD — HOVER + ICON BOUNCE               */
        /* ============================================ */
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

        /* ============================================ */
        /* INPUT NAMA                                    */
        /* ============================================ */
        .input-nama {
            transition: all .25s ease;
        }

        .input-nama:focus {
            border-color: #C99A32 !important;
            background: white !important;
            box-shadow: 0 0 0 4px rgba(201, 154, 50, .12);
        }

        /* ============================================ */
        /* BTN SIMPAN — SHIMMER                          */
        /* ============================================ */
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
            background: linear-gradient(90deg,
                    transparent,
                    rgba(255, 255, 255, .3),
                    transparent);
            transition: left .6s ease;
        }

        .btn-save:hover:not(:disabled)::before {
            left: 100%;
        }

        .btn-save:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 12px 22px -8px rgba(201, 154, 50, .5);
        }

        /* ============================================ */
        /* TOAST — SLIDE + FADE                          */
        /* ============================================ */
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

        /* ============================================ */
        /* LIGHTBOX — FADE IN                            */
        /* ============================================ */
        #lightbox.flex {
            animation: lightboxIn .3s ease-out;
        }

        @keyframes lightboxIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* ============================================ */
        /* ORNAMEN JUDUL — DIAMOND                       */
        /* ============================================ */
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

        /* ============================================ */
        /* REDUCED MOTION                                */
        /* ============================================ */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
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

    {{-- HEADER --}}
    @include('partials.header')

    <main class="max-w-3xl mx-auto px-5 pt-8 pb-14">

        {{-- ============================================ --}}
        {{-- JUDUL HALAMAN                                 --}}
        {{-- ============================================ --}}
        <section class="reveal-up">
            <div class="title-ornament mb-2">
                <span class="inline-block h-1.5 w-1.5 rotate-45 bg-[#C99A32]"></span>
                <span class="text-[9px] font-bold uppercase tracking-[.24em] text-[#a37b2a]">
                    Akun Saya
                </span>
                <span class="inline-block h-1.5 w-1.5 rotate-45 bg-[#C99A32]"></span>
            </div>
            <h1 class="font-display text-2xl md:text-3xl font-bold tracking-tight text-[#111827]">Profil Saya</h1>
            <p class="mt-1.5 text-gray-500 text-xs md:text-sm">
                Kelola data akun dan lihat aktivitas vote Anda di Event ALNAY.ID.
            </p>
        </section>

        {{-- ============================================ --}}
        {{-- KARTU DATA AKUN                               --}}
        {{-- ============================================ --}}
        <section class="reveal-up delay-1 mt-6 bg-white rounded-2xl border border-[#ebe7df] card-premium overflow-hidden">

            {{-- Garis emas atas --}}
            <div class="h-[3px] w-full bg-gradient-to-r from-[#c99a32] via-[#e0b254] to-[#c99a32]"></div>

            {{-- Header — avatar + nama --}}
            <div class="flex items-center justify-between gap-4 p-5">
                <div class="flex items-center gap-4 min-w-0">
                    <div class="avatar-wrap relative shrink-0">
                        <div class="avatar-ring w-16 h-16 md:w-[80px] md:h-[80px] rounded-full overflow-hidden bg-gradient-to-br from-[#a37b2a] to-[#c99a32] text-white font-display text-2xl md:text-3xl font-bold flex items-center justify-center">
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
                                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                                <circle cx="12" cy="13" r="4"></circle>
                            </svg>
                        </button>

                        <div id="menuAvatar"
                            class="hidden absolute left-0 top-full mt-2 z-30 w-48 bg-white border border-gray-100 rounded-xl shadow-lg py-1 text-sm">
                            @if ($fotoTampil)
                                <button type="button" id="menuLihat"
                                    class="w-full text-left px-4 py-2.5 hover:bg-[#faf8f2] transition rounded-t-xl">Lihat foto</button>
                            @endif
                            <button type="button" id="menuPilih"
                                class="w-full text-left px-4 py-2.5 hover:bg-[#faf8f2] transition">Pilih foto baru</button>
                            @if ($user->foto_custom)
                                <button type="button" id="menuHapus"
                                    class="w-full text-left px-4 py-2.5 text-red-600 hover:bg-red-50 transition rounded-b-xl">Hapus foto</button>
                            @endif
                        </div>

                        <form id="formFoto" method="POST" action="{{ route('profil-user.foto') }}"
                            enctype="multipart/form-data" class="hidden">
                            @csrf
                            <input type="file" id="fileFoto" name="foto"
                                accept="image/png,image/jpeg,image/webp">
                        </form>

                        @if ($user->foto_custom)
                            <form id="formHapusFoto" method="POST" action="{{ route('profil-user.foto.hapus') }}" class="hidden">
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

            {{-- Email --}}
            <div class="flex items-center justify-between gap-4 px-5 py-4 border-t border-[#ebe7df] text-sm">
                <span class="inline-flex items-center gap-1.5 text-gray-500">
                    <svg class="w-3.5 h-3.5 text-[#c99a32]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Email
                </span>
                <span class="font-medium text-[#15233d] truncate">{{ $user->email }}</span>
            </div>

            {{-- Form edit nama --}}
            <form id="formProfil" method="POST" action="{{ route('profil-user.update') }}">
                @csrf
                @method('PUT')

                <div class="px-5 py-3 border-t border-[#ebe7df] text-sm">
                    <div class="flex items-center justify-between gap-4">
                        <label for="inputNama" class="inline-flex items-center gap-1.5 text-gray-500 shrink-0">
                            <svg class="w-3.5 h-3.5 text-[#c99a32]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Nama Tampilan
                        </label>
                        <div class="relative w-full max-w-[240px]">
                            <input type="text" id="inputNama" name="nama_custom"
                                value="{{ old('nama_custom', $user->nama_custom ?? $user->nama) }}"
                                data-awal="{{ $user->nama_custom ?? $user->nama }}" maxlength="20" autocomplete="off"
                                spellcheck="false"
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

                <div class="flex items-center justify-end px-5 py-3.5 border-t border-[#ebe7df] bg-gradient-to-r from-[#faf8f2] to-[#faf8f2]/60">
                    <button type="submit" id="btnSimpan" disabled
                        class="btn-save px-5 py-2.5 rounded-lg bg-gradient-to-r from-[#a37b2a] to-[#c99a32] text-white text-xs font-semibold transition shadow-sm disabled:bg-none disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed disabled:shadow-none">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </section>

        {{-- ============================================ --}}
        {{-- STAT CARDS                                    --}}
        {{-- ============================================ --}}
        <section class="reveal-up delay-2 mt-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">

                <div class="stat-card bg-gradient-to-br from-[#faf8f2] to-[#f5efe2] rounded-2xl border border-[#ebe7df] py-6 px-5 flex items-center justify-between">
                    <div>
                        <p class="text-4xl md:text-5xl font-bold text-[#c99a32] tabular-nums leading-none tracking-tight">{{ $totalVote }}</p>
                        <p class="mt-2 text-[10px] uppercase tracking-[.15em] text-gray-500 font-semibold">Total Vote Diberikan</p>
                    </div>
                    <div class="stat-icon w-12 h-12 rounded-xl bg-white/70 flex items-center justify-center ring-1 ring-[#c99a32]/20">
                        <svg class="w-5 h-5 text-[#c99a32]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                </div>

                <div class="stat-card bg-gradient-to-br from-[#faf8f2] to-[#f5efe2] rounded-2xl border border-[#ebe7df] py-6 px-5 flex items-center justify-between">
                    <div>
                        <p class="text-4xl md:text-5xl font-bold text-[#c99a32] tabular-nums leading-none tracking-tight">{{ $totalEvent }}</p>
                        <p class="mt-2 text-[10px] uppercase tracking-[.15em] text-gray-500 font-semibold">Event yang Diikuti</p>
                    </div>
                    <div class="stat-icon w-12 h-12 rounded-xl bg-white/70 flex items-center justify-center ring-1 ring-[#c99a32]/20">
                        <svg class="w-5 h-5 text-[#c99a32]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>

            </div>
        </section>

        {{-- ============================================ --}}
        {{-- STATUS PENDAFTARAN                            --}}
        {{-- ============================================ --}}
        @if ($pendaftarans->count() > 0)
            <section class="reveal-up delay-3 mt-6">
                <div class="mb-4">
                    <div class="title-ornament mb-2">
                        <span class="inline-block h-1.5 w-1.5 rotate-45 bg-[#C99A32]"></span>
                        <span class="text-[9px] font-bold uppercase tracking-[.24em] text-[#a37b2a]">
                            Riwayat
                        </span>
                        <span class="inline-block h-1.5 w-1.5 rotate-45 bg-[#C99A32]"></span>
                    </div>
                    <h2 class="font-display text-lg md:text-xl font-bold text-[#15233d]">Status Pendaftaran</h2>
                    <p class="text-gray-500 text-xs mt-0.5">Pantau status pendaftaran event kamu di sini</p>
                </div>

                <div class="space-y-4">
                    @foreach ($pendaftarans as $p)
                        @php
                            $eventP = $p->event;
                            $pembayaran = $p->pembayaran->sortByDesc('id')->first();
                            $sudahBayar = $pembayaran && $pembayaran->status_midtrans === 'settlement';

                            $adalahCardTerbaru =
                                $pendaftarans->where('event_id', $p->event_id)->sortByDesc('id')->first()?->id ===
                                $p->id;

                            $sudahDaftarUlang = $pendaftarans->contains(function ($other) use ($p) {
                                return $other->id !== $p->id &&
                                    $other->event_id === $p->event_id &&
                                    in_array($other->status, ['menunggu_review', 'diterima'], true);
                            });

                            $meta = match ($p->status) {
                                'menunggu_review' => [
                                    'label' => 'Menunggu Review',
                                    'bg' => 'bg-amber-50',
                                    'text' => 'text-amber-700',
                                    'border' => 'border-amber-200',
                                ],
                                'diterima' => [
                                    'label' => $sudahBayar ? 'Peserta Aktif' : 'Diterima',
                                    'bg' => 'bg-emerald-50',
                                    'text' => 'text-emerald-700',
                                    'border' => 'border-emerald-200',
                                ],
                                'ditolak' => [
                                    'label' => 'Ditolak',
                                    'bg' => 'bg-rose-50',
                                    'text' => 'text-rose-700',
                                    'border' => 'border-rose-200',
                                ],
                                default => [
                                    'label' => ucfirst($p->status),
                                    'bg' => 'bg-gray-50',
                                    'text' => 'text-gray-700',
                                    'border' => 'border-gray-200',
                                ],
                            };
                        @endphp

                        <div class="card-premium bg-white rounded-2xl border border-[#ebe7df] p-5 shadow-sm">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-12 h-12 shrink-0 rounded-xl bg-[#22212b] overflow-hidden">
                                        @if ($eventP && $eventP->banner)
                                            <img src="{{ asset('storage/' . $eventP->banner) }}"
                                                alt="{{ $eventP->nama }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-[#15233d] truncate">
                                            {{ $eventP->nama ?? 'Event' }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            {{ $p->namaTampil() }} · Didaftarkan
                                            {{ $p->tanggal_daftar?->format('d M Y') ?? '-' }}
                                        </p>
                                    </div>
                                </div>

                                <span
                                    class="shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-full {{ $meta['bg'] }} border {{ $meta['border'] }} {{ $meta['text'] }} text-[10px] font-semibold">
                                    {{ $meta['label'] }}
                                </span>
                            </div>

                            @if ($p->status === 'ditolak' && $p->catatan_admin)
                                <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 p-4">
                                    <p class="text-[10px] font-bold uppercase tracking-wide text-rose-700 mb-1">Alasan Penolakan</p>
                                    <p class="text-sm text-rose-800 whitespace-pre-line">{{ $p->catatan_admin }}</p>
                                </div>
                            @endif

                            @if ($p->status === 'diterima' && !$sudahBayar)
                                <div class="mt-4 rounded-xl border border-[#ece5d7] bg-[#fcfaf6] p-4">
                                    <p class="text-xs text-[#4B463F] mb-1">Biaya Pendaftaran</p>
                                    <p class="text-lg font-bold text-[#13223a]">
                                        Rp {{ number_format($eventP->biaya_pendaftaran ?? 0, 0, ',', '.') }}
                                    </p>
                                    <a href="{{ route('peserta.pembayaran', $p->id) }}"
                                        class="mt-3 block w-full rounded-lg bg-gradient-to-r from-[#cf9b32] to-[#b88625] py-2.5 text-center text-[12px] font-semibold text-white transition hover:from-[#b88625] hover:to-[#a3741d] shadow-sm hover:shadow-md">
                                        Bayar Sekarang →
                                    </a>
                                </div>
                            @endif

                            @if ($sudahBayar)
                                <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                                    <p class="text-xs font-semibold text-emerald-700 inline-flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Pembayaran Lunas
                                    </p>
                                    <p class="text-xs text-emerald-600 mt-1">
                                        {{ $pembayaran->tanggal_bayar?->format('d M Y, H:i') ?? '' }} WIB
                                    </p>
                                </div>
                            @endif

                            @if ($p->status === 'ditolak' && $eventP && !$sudahDaftarUlang && $adalahCardTerbaru)
                                <div class="mt-4 rounded-xl border border-blue-200 bg-blue-50 p-4">
                                    <p class="text-xs text-blue-800 mb-2">
                                        Kamu bisa mendaftar ulang untuk event ini dengan data yang sudah diperbaiki.
                                    </p>
                                    <a href="{{ route('public.registration', $eventP) }}"
                                        class="inline-flex items-center justify-center w-full rounded-lg bg-gradient-to-r from-[#cf9b32] to-[#b88625] py-2.5 text-center text-[12px] font-semibold text-white transition hover:from-[#b88625] hover:to-[#a3741d] shadow-sm hover:shadow-md">
                                        Daftar Ulang ke Event Ini →
                                    </a>
                                </div>
                            @endif

                            @if ($p->status === 'ditolak' && !$adalahCardTerbaru)
                                <div class="mt-4 rounded-xl border border-gray-200 bg-gray-50 p-4">
                                    <p class="text-xs text-gray-500">Riwayat pendaftaran sebelumnya.</p>
                                </div>
                            @endif

                            @if ($p->status === 'ditolak' && $eventP && $sudahDaftarUlang)
                                <div class="mt-4 rounded-xl border border-gray-200 bg-gray-50 p-4">
                                    <p class="text-xs text-gray-600">Kamu sudah terdaftar untuk event ini.</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- ============================================ --}}
        {{-- KELUAR + KEMBALI                              --}}
        {{-- ============================================ --}}
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

    {{-- ============================================ --}}
    {{-- LIGHTBOX                                      --}}
    {{-- ============================================ --}}
    @if ($fotoTampil)
        <div id="lightbox" class="hidden fixed inset-0 z-[60] items-center justify-center bg-black/80 px-5 backdrop-blur-sm">
            <div class="relative">
                <div class="w-64 h-64 md:w-80 md:h-80 rounded-full overflow-hidden bg-gradient-to-br from-[#a37b2a] to-[#c99a32] text-white font-display text-8xl font-bold flex items-center justify-center shadow-2xl ring-4 ring-white/20">
                    <img src="{{ $fotoTampil }}" alt="Foto profil" class="w-full h-full object-cover">
                </div>
                <button type="button" id="lightTutup" aria-label="Tutup"
                    class="absolute -top-2 -right-2 w-9 h-9 rounded-full bg-white text-[#15233d] shadow-lg flex items-center justify-center text-lg leading-none hover:scale-110 transition">
                    &times;
                </button>
            </div>
        </div>
    @endif

    {{-- ============================================ --}}
    {{-- TOAST                                         --}}
    {{-- ============================================ --}}
    <div id="toast"
        data-pesan="{{ session('status') ?? ($errors->first('foto') ?? $errors->first('nama_custom')) }}"
        class="hidden fixed bottom-6 left-1/2 -translate-x-1/2 z-[70] px-4 py-2.5 rounded-xl bg-gradient-to-r from-[#15233d] to-[#1f3357] text-white text-xs shadow-xl">
    </div>

    <script>
        const $ = id => document.getElementById(id);

        let toastTimer;
        const tampilToast = teks => {
            const t = $('toast');
            t.textContent = teks;
            t.classList.remove('hidden');
            clearTimeout(toastTimer);
            toastTimer = setTimeout(() => t.classList.add('hidden'), 2600);
        };
        if ($('toast').dataset.pesan) tampilToast($('toast').dataset.pesan);

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
    </script>

</body>

</html>