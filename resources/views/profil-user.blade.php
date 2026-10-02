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
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
        .card-shadow { box-shadow: 0 8px 30px rgba(21, 35, 61, 0.12); }
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
    <header class="sticky top-0 z-50 border-b border-[#e9e9e9] bg-white/95 backdrop-blur">
        <div class="mx-auto flex h-[56px] max-w-[1200px] items-center justify-between px-5 md:px-8">
            <a href="{{ route('dashboard-user') }}" class="flex items-center gap-2.5">
                <img src="{{ asset('images/alnaylogo.png') }}" alt="ALNAY.ID"
                    class="h-9 w-9 object-contain">
                <span class="brand-font text-[16px] font-bold tracking-tight text-[#13223a]">
                    EVENT ALNAY.ID
                </span>
            </a>

            <a href="{{ route('profil-user') }}" aria-label="Profil pengguna"
                class="flex h-9 w-9 items-center justify-center rounded-full border border-[#c99a32] bg-[#fbf6e9] text-[#c99a32] transition hover:bg-[#f7f0dc] overflow-hidden">
                @if ($fotoTampil)
                    <img src="{{ $fotoTampil }}" alt="{{ $namaTampil }}" class="w-full h-full object-cover">
                @else
                    <span class="text-[11px] font-bold">{{ $inisial }}</span>
                @endif
            </a>
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-5 pt-8 pb-14">

        {{-- JUDUL --}}
        <section>
            <h1 class="font-display text-2xl md:text-3xl font-bold text-[#111827]">Profil Saya</h1>
            <p class="text-gray-500 text-xs md:text-sm mt-1.5">
                Kelola data akun dan lihat aktivitas vote Anda di Event ALNAY.ID.
            </p>
        </section>

        {{-- KARTU DATA AKUN --}}
        <section class="mt-6 bg-white rounded-xl border border-gray-100 card-shadow overflow-hidden">
            <div class="flex items-center justify-between gap-4 p-5">
                <div class="flex items-center gap-4 min-w-0">
                    <div class="relative shrink-0">
                        <div class="w-14 h-14 md:w-[72px] md:h-[72px] rounded-full overflow-hidden bg-[#a37b2a] text-white font-display text-xl md:text-2xl font-bold flex items-center justify-center">
                            @if ($fotoTampil)
                                <img id="avatarImg" src="{{ $fotoTampil }}" alt="Foto profil" class="w-full h-full object-cover">
                            @else
                                <span id="avatarInisial">{{ $inisial }}</span>
                            @endif
                        </div>

                        <button type="button" id="btnAvatar" aria-label="Edit foto profil"
                            class="absolute -bottom-0.5 -right-0.5 w-7 h-7 rounded-full bg-white border border-gray-200 shadow-sm flex items-center justify-center text-[#a37b2a] hover:bg-[#fbf6e9] transition">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                                <circle cx="12" cy="13" r="4"></circle>
                            </svg>
                        </button>

                        <div id="menuAvatar" class="hidden absolute left-0 top-full mt-2 z-30 w-48 bg-white border border-gray-100 rounded-lg shadow-lg py-1 text-sm">
                            @if ($fotoTampil)
                                <button type="button" id="menuLihat" class="w-full text-left px-4 py-2.5 hover:bg-[#faf8f2] transition">Lihat foto</button>
                            @endif
                            <button type="button" id="menuPilih" class="w-full text-left px-4 py-2.5 hover:bg-[#faf8f2] transition">Pilih foto</button>
                            @if ($user->foto_custom)
                                <button type="button" id="menuHapus" class="w-full text-left px-4 py-2.5 text-red-600 hover:bg-red-50 transition">Hapus foto</button>
                            @endif
                        </div>

                        <form id="formFoto" method="POST" action="{{ route('profil-user.foto') }}" enctype="multipart/form-data" class="hidden">
                            @csrf
                            <input type="file" id="fileFoto" name="foto" accept="image/png,image/jpeg,image/webp">
                        </form>

                        @if ($user->foto_custom)
                            <form id="formHapusFoto" method="POST" action="{{ route('profil-user.foto.hapus') }}" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endif
                    </div>

                    <div class="min-w-0">
                        <h2 class="font-display text-lg md:text-xl font-bold text-[#15233d] truncate">{{ $namaTampil }}</h2>
                        <p class="text-gray-500 text-xs md:text-sm mt-0.5">Bergabung sejak {{ $bergabung }}</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between gap-4 px-5 py-4 border-t border-gray-100 text-sm">
                <span class="text-gray-500">Email</span>
                <span class="font-medium text-[#15233d] truncate">{{ $user->email }}</span>
            </div>

            <form id="formProfil" method="POST" action="{{ route('profil-user.update') }}">
                @csrf
                @method('PUT')

                <div class="px-5 py-3 border-t border-gray-100 text-sm">
                    <div class="flex items-center justify-between gap-4">
                        <label for="inputNama" class="text-gray-500 shrink-0">Nama Tampilan</label>
                        <div class="relative w-full max-w-[240px]">
                            <input type="text" id="inputNama" name="nama_custom"
                                value="{{ old('nama_custom', $user->nama_custom ?? $user->nama) }}"
                                data-awal="{{ $user->nama_custom ?? $user->nama }}"
                                maxlength="20" autocomplete="off" spellcheck="false"
                                class="w-full rounded-md border border-transparent hover:border-gray-200 bg-transparent pl-3 pr-8 py-2 text-right font-medium text-[#15233d] transition focus:outline-none focus:border-[#c99a32] focus:bg-white focus:ring-2 focus:ring-[#c99a32]/20">
                            <svg class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-300" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
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

                <div class="flex items-center justify-end px-5 py-3.5 border-t border-gray-100 bg-[#faf8f2]">
                    <button type="submit" id="btnSimpan" disabled
                        class="px-4 py-2 rounded-md bg-[#a37b2a] text-white text-xs font-medium transition hover:bg-[#c99a32] disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </section>

        {{-- KARTU RINGKASAN AKTIVITAS --}}
        <section class="mt-5 bg-white rounded-xl border border-gray-100 card-shadow p-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div class="bg-[#faf8f2] rounded-lg py-7 text-center">
                    <p class="font-display text-3xl md:text-4xl font-bold text-[#c99a32]">{{ $totalVote }}</p>
                    <p class="text-xs text-gray-500 mt-1">Total Vote Diberikan</p>
                </div>
                <div class="bg-[#faf8f2] rounded-lg py-7 text-center">
                    <p class="font-display text-3xl md:text-4xl font-bold text-[#c99a32]">{{ $totalEvent }}</p>
                    <p class="text-xs text-gray-500 mt-1">Event yang telah Diikuti</p>
                </div>
            </div>
        </section>

        {{-- KARTU STATUS PENDAFTARAN --}}
        @if ($pendaftarans->count() > 0)
            <section class="mt-5">
                <div class="mb-4">
                    <h2 class="font-display text-lg font-bold text-[#15233d]">Status Pendaftaran</h2>
                    <p class="text-gray-500 text-xs mt-0.5">Pantau status pendaftaran event kamu di sini</p>
                </div>

                <div class="space-y-4">
                    @foreach ($pendaftarans as $p)
                        @php
                            $eventP = $p->event;
                            $pembayaran = $p->pembayaran->sortByDesc('id')->first();
                            $sudahBayar = $pembayaran && $pembayaran->status_midtrans === 'settlement';

                            // Cek apakah user sudah punya pendaftaran LAIN di event yang sama
                            // dengan status menunggu_review atau diterima
                            $sudahDaftarUlang = $pendaftarans->contains(function ($other) use ($p) {
                                return $other->id !== $p->id
                                    && $other->event_id === $p->event_id
                                    && in_array($other->status, ['menunggu_review', 'diterima'], true);
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

                        <div class="bg-white rounded-xl border border-gray-100 card-shadow p-5">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-12 h-12 shrink-0 rounded-md bg-[#22212b] overflow-hidden">
                                        @if ($eventP && $eventP->banner)
                                            <img src="{{ asset('storage/' . $eventP->banner) }}" alt="{{ $eventP->nama }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-[#15233d] truncate">{{ $eventP->nama ?? 'Event' }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            {{ $p->namaTampil() }} · Didaftarkan · Didaftarkan {{ $p->tanggal_daftar?->format('d M Y') ?? '-' }}
                                        </p>
                                    </div>
                                </div>

                                <span class="shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-full {{ $meta['bg'] }} border {{ $meta['border'] }} {{ $meta['text'] }} text-[10px] font-semibold">
                                    {{ $meta['label'] }}
                                </span>
                            </div>

                            {{-- Catatan admin (kalau ditolak) --}}
                            @if ($p->status === 'ditolak' && $p->catatan_admin)
                                <div class="mt-4 rounded-lg border border-rose-200 bg-rose-50 p-4">
                                    <p class="text-[10px] font-bold uppercase tracking-wide text-rose-700 mb-1">Alasan Penolakan</p>
                                    <p class="text-sm text-rose-800 whitespace-pre-line">{{ $p->catatan_admin }}</p>
                                </div>
                            @endif

                            {{-- Tombol Bayar (kalau diterima & belum bayar) --}}
                            @if ($p->status === 'diterima' && ! $sudahBayar)
                                <div class="mt-4 rounded-lg border border-[#ece5d7] bg-[#fcfaf6] p-4">
                                    <p class="text-xs text-[#4B463F] mb-1">Biaya Pendaftaran</p>
                                    <p class="text-lg font-bold text-[#13223a]">
                                        Rp {{ number_format($eventP->biaya_pendaftaran ?? 0, 0, ',', '.') }}
                                    </p>
                                    <a href="{{ route('peserta.pembayaran', $p->id) }}"
                                        class="mt-3 block w-full rounded-[4px] bg-[#cf9b32] py-2.5 text-center text-[12px] font-semibold text-white transition hover:bg-[#b88625]">
                                        Bayar Sekarang →
                                    </a>
                                </div>
                            @endif

                            {{-- Info sudah bayar --}}
                            @if ($sudahBayar)
                                <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 p-4">
                                    <p class="text-xs font-semibold text-emerald-700">✓ Pembayaran Lunas</p>
                                    <p class="text-xs text-emerald-600 mt-1">
                                        {{ $pembayaran->tanggal_bayar?->format('d M Y, H:i') ?? '' }} WIB
                                    </p>
                                </div>
                            @endif

                            {{-- Tombol Daftar Ulang (kalau ditolak & BELUM daftar ulang) --}}
                            @if ($p->status === 'ditolak' && $eventP && ! $sudahDaftarUlang)
                                <div class="mt-4 rounded-lg border border-blue-200 bg-blue-50 p-4">
                                    <p class="text-xs text-blue-800 mb-2">
                                        Kamu bisa mendaftar ulang untuk event ini dengan data yang sudah diperbaiki.
                                    </p>
                                    <a href="{{ route('public.registration', $eventP) }}"
                                        class="inline-flex items-center justify-center w-full rounded-[4px] bg-[#cf9b32] py-2.5 text-center text-[12px] font-semibold text-white transition hover:bg-[#b88625]">
                                        Daftar Ulang ke Event Ini →
                                    </a>
                                </div>
                            @endif

                            {{-- Info sudah daftar ulang --}}
                            @if ($p->status === 'ditolak' && $eventP && $sudahDaftarUlang)
                                <div class="mt-4 rounded-lg border border-gray-200 bg-gray-50 p-4">
                                    <p class="text-xs text-gray-600">
                                        Kamu sudah terdaftar untuk event ini
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- KELUAR + KEMBALI --}}
        <div class="flex flex-col items-center gap-3 mt-8">
            <a href="/beranda"
                class="inline-flex items-center gap-1.5 text-xs text-[#a37b2a] hover:text-[#c99a32] transition font-medium">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
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
        <div id="lightbox" class="hidden fixed inset-0 z-[60] items-center justify-center bg-black/70 px-5">
            <div class="relative">
                <div class="w-64 h-64 md:w-80 md:h-80 rounded-full overflow-hidden bg-[#a37b2a] text-white font-display text-8xl font-bold flex items-center justify-center">
                    <img src="{{ $fotoTampil }}" alt="Foto profil" class="w-full h-full object-cover">
                </div>
                <button type="button" id="lightTutup" aria-label="Tutup" class="absolute -top-2 -right-2 w-8 h-8 rounded-full bg-white text-[#15233d] shadow flex items-center justify-center text-lg leading-none">&times;</button>
            </div>
        </div>
    @endif

    {{-- TOAST --}}
    <div id="toast" data-pesan="{{ session('status') ?? ($errors->first('foto') ?? $errors->first('nama_custom')) }}"
        class="hidden fixed bottom-6 left-1/2 -translate-x-1/2 z-[70] px-4 py-2.5 rounded-md bg-[#15233d] text-white text-xs shadow-lg"></div>

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
                if (e.key === 'Escape') { bukaMenu(false); tutupLihat(); }
            });
        }
    </script>

</body>

</html>