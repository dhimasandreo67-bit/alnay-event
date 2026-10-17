<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kandidat - Event AL-NAY.ID</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@400;600;700&family=Manrope:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Manrope', sans-serif;
            background: #FFF8F1;
        }

        .font-display {
            font-family: 'Noto Serif', serif;
        }

        .glow {
            background: radial-gradient(60% 45% at 70% 0%, rgba(194, 142, 39, .11), transparent 70%);
        }

        .photo-frame {
            position: relative;
            overflow: hidden;
            background: #EFE7DC;
        }

        .photo-frame img {
            transition: transform .5s ease;
        }

        .photo-frame:hover img {
            transform: scale(1.025);
        }

        .thumb {
            transition: transform .2s ease, border-color .2s ease, opacity .2s ease;
        }

        .thumb:hover {
            transform: translateY(-2px);
            opacity: .9;
        }

        .stat-card {
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(29, 27, 23, .06);
        }

        .vote-button {
            transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
        }

        .vote-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(194, 142, 39, .22);
        }

        .support-item {
            transition: transform .2s ease, background .2s ease;
        }

        .support-item:hover {
            transform: translateX(2px);
            background: #FBF3E7;
        }

        .content-enter {
            animation: contentEnter .45s ease both;
        }

        @keyframes contentEnter {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .text-reveal {
            opacity: 0;
            transform: translateY(12px);
            animation: textReveal .55s ease forwards
        }

        .text-reveal-1 {
            animation-delay: .08s
        }

        .text-reveal-2 {
            animation-delay: .16s
        }

        .text-reveal-3 {
            animation-delay: .24s
        }

        .text-reveal-4 {
            animation-delay: .32s
        }

        .text-reveal-5 {
            animation-delay: .40s
        }

        @keyframes textReveal {
            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .progress-bar {
            animation: progressIn .8s ease both;
            transform-origin: left;
        }

        @keyframes progressIn {
            from {
                transform: scaleX(0);
            }

            to {
                transform: scaleX(1);
            }
        }
    </style>
</head>

<body class="min-h-screen flex flex-col text-[#1D1B17]">
    @include('partials.header')

    <main class="glow flex-1">
        <div class="max-w-6xl mx-auto px-5 md:px-6 py-6 md:py-7">
            <nav
                class="flex flex-wrap items-center gap-2 text-[10px] font-semibold uppercase tracking-[.12em] text-[#6A6259]">
                @php
                    $eventId = $peserta->event_id ?? null;
                    $backUrl = $eventId ? "/detail-event/{$eventId}" : '/beranda';
                @endphp
                <a href="{{ $backUrl }}" class="transition hover:text-[#C28E27]">
                    &larr; Daftar Kandidat
                </a>
                <span class="text-[#CDC5BC]">/</span>
                <span class="text-[#1D1B17]">Detail Kandidat</span>
            </nav>

            <div id="konten" class="grid grid-cols-1 md:grid-cols-[5fr_7fr] gap-5 md:gap-7 lg:gap-10 mt-5">
                <p class="text-sm text-[#4B463F] md:col-span-2">Memuat data peserta...</p>
            </div>
        </div>
    </main>

    @include('partials.footer')

    <script>
        const pesertaId = {{ (int) $pesertaId }};
        let hargaPerVote = 0;
        let fotoList = [];

        function esc(s) {
            return String(s ?? '').replace(/[&<>"']/g, c => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            } [c]));
        }

        function formatAngka(n) {
            return new Intl.NumberFormat('id-ID').format(n ?? 0);
        }

        function inisial(nama) {
            return (nama ?? '?').trim().split(/\s+/).slice(0, 2).map(s => s[0]?.toUpperCase()).join('');
        }

        function fotoUrl(u) {
            if (!u) return null;
            let url = String(u);
            if (!/^(https?:)?\/\//.test(url) && !url.startsWith('/')) url = '/storage/' + url;
            return url.replace(/=s\d+-c$/, '=s800-c');
        }

        function waktuLalu(t) {
            if (!t) return '';
            const d = new Date(t);
            if (isNaN(d)) return '';
            const detik = Math.max(0, Math.floor((Date.now() - d.getTime()) / 1000));
            if (detik < 60) return 'baru saja';
            const menit = Math.floor(detik / 60);
            if (menit < 60) return `${menit}m lalu`;
            const jam = Math.floor(menit / 60);
            if (jam < 24) return `${jam}j lalu`;
            return `${Math.floor(jam / 24)}h lalu`;
        }

        function cariJawaban(jawabans, tag, kataKunci) {
            const daftar = jawabans ?? [];
            let j = daftar.find(x => x.pertanyaan?.tag_tampilan === tag);
            if (!j && kataKunci) {
                j = daftar.find(x => kataKunci.test(x.pertanyaan?.label ?? '') && x.jawaban);
            }
            return j?.jawaban ? j.jawaban : null;
        }

        function gantiFoto(i) {
            const el = document.getElementById('foto-utama');
            if (el && fotoList[i]) {
                el.style.opacity = '0';
                setTimeout(() => {
                    el.src = fotoList[i];
                    el.onload = () => {
                        el.style.opacity = '1';
                    };
                }, 100);
            }
        }

        function tampilkanError(pesan) {
            document.getElementById('konten').innerHTML = `
                <div class="md:col-span-2 rounded-xl border border-red-200 bg-red-50 p-6 text-center">
                    <p class="mb-2 font-semibold text-red-700">${esc(pesan)}</p>
                    <a href="/beranda" class="text-sm font-semibold text-[#C28E27] hover:underline">
                        &larr; Kembali ke beranda
                    </a>
                </div>`;
        }

        async function muatStatistik(eventId) {
            try {
                const res = await fetch(`/event/${eventId}/hasil-voting`, {
                    credentials: 'include'
                });
                if (!res.ok) return null;
                const data = await res.json();

                // ===== BLACKOUT INFO =====
                const blackout = data.blackout ?? false;
                const waktuGrandFinal = data.waktu_grand_final ?? null;
                const message = data.message ?? 'Peringkat sementara dirahasiakan.';

                const daftar = (data.hasil_voting ?? data.peserta ?? []).slice()
                    .sort((a, b) => parseInt(b.total_vote ?? 0) - parseInt(a.total_vote ?? 0));
                const totalSemua = daftar.reduce((sum, h) => sum + parseInt(h.total_vote ?? 0), 0);
                const indexKu = daftar.findIndex(h => h.id === pesertaId);
                const totalVoteKu = indexKu !== -1 ? parseInt(daftar[indexKu].total_vote ?? 0) : 0;
                const persentase = totalSemua > 0 ? (totalVoteKu / totalSemua * 100) : 0;

                return {
                    totalVoteKu,
                    totalSemua,
                    persentase,
                    ranking: indexKu !== -1 ? indexKu + 1 : null,
                    blackout,
                    waktuGrandFinal,
                    message,
                };
            } catch (e) {
                console.error('muatStatistik error:', e);
                return null;
            }
        }

        async function muatDukunganTerkini(eventId) {
            try {
                const res = await fetch(`/event/${eventId}/peserta/${pesertaId}/dukungan-terkini`, {
                    credentials: 'include'
                });
                if (!res.ok) return null;
                const data = await res.json();
                return Array.isArray(data) ? data : (data.dukungan_terkini ?? []);
            } catch (e) {
                console.error('muatDukunganTerkini error:', e);
                return null;
            }
        }

        const warnaAvatar = [
            'bg-[#7F5700] text-white',
            'bg-black text-white',
            'bg-[#EFE7DC] text-black'
        ];

        async function muatPeserta() {
        try {
            const res = await fetch(`/peserta/${pesertaId}`, {
                headers: {
                    'Accept': 'application/json'
                },
                credentials: 'include'
            });

            if (res.status === 401) {
                tampilkanError('Kamu belum login. Silakan login dulu untuk melihat detail kandidat.');
                return;
            }

            if (res.status === 404) {
                tampilkanError('Peserta tidak ditemukan.');
                return;
            }

            if (!res.ok) {
                tampilkanError('Gagal memuat data peserta. Status: ' + res.status);
                return;
            }

            const p = await res.json();
            hargaPerVote = p.event?.harga_per_vote ?? 0;

            const [statistik, dukungan] = await Promise.all([
                muatStatistik(p.event?.id),
                muatDukunganTerkini(p.event?.id)
            ]);

            // ✅ Voting aktif dari setting admin
            const votingAktif = p.event?.voting_aktif === true;

            // ✅ Sembunyikan SEMUA tampilan vote kalau voting dimatikan ATAU blackout
            const sembunyikanVote = !votingAktif || (statistik?.blackout ?? false);

            const nama = cariJawaban(p.jawabans, 'nama', /nama/i) ?? p.user?.nama ?? 'Peserta';
            const lokasi = cariJawaban(p.jawabans, 'lokasi', /lokasi|domisili|kota|alamat/i);
            const deskripsi = cariJawaban(p.jawabans, 'bio', /bio|profil|tentang|deskripsi/i);

            // ===== Foto: ambil SEMUA jawaban tipe file/gambar + galeri_foto =====
            const galeri = [];

            (p.jawabans ?? []).forEach(j => {
                const tipe = (j.pertanyaan?.tipe_input ?? '').toLowerCase();
                const label = (j.pertanyaan?.label ?? '').toLowerCase();
                const isFileTipe = ['file', 'upload gambar', 'upload_gambar', 'image', 'gambar'].includes(
                    tipe);
                const isLabelGambar = /upload gambar|foto|gambar|image/i.test(label);

                if ((isFileTipe || isLabelGambar) && j.jawaban) {
                    const url = fotoUrl(j.jawaban);
                    if (url) galeri.push(url);
                }
            });

            if (Array.isArray(p.galeri_foto)) {
                p.galeri_foto.map(fotoUrl).filter(Boolean).forEach(u => galeri.push(u));
            }

            if (galeri.length === 0) {
                const fotoCustom = p.user?.foto_custom ?
                    `/storage/${p.user.foto_custom}` :
                    null;
                const fotoGoogle = fotoUrl(p.user?.foto_url);
                const fotoAvatar = fotoCustom ?? fotoGoogle ??
                    `https://ui-avatars.com/api/?size=512&background=0A1526&color=FFF8F1&name=${encodeURIComponent(nama)}`;
                galeri.push(fotoAvatar);
            }

            fotoList = galeri;

            const blokThumbs = fotoList.length > 1 ? `
                      <div class="mt-2.5 grid gap-2 mx-auto w-full max-w-[420px] md:max-w-none"
                        style="grid-template-columns: repeat(${Math.min(fotoList.length, 5)}, minmax(0, 1fr));">
                    ${fotoList.map((url,i) => `
                                <button type="button"
                                    onclick="gantiFoto(${i})"
                                    class="thumb aspect-square overflow-hidden rounded-lg border border-[#D8D0C6] bg-[#EFE7DC] hover:border-[#C28E27]">
                                    <img src="${esc(url)}" class="w-full h-full object-cover" alt="">
                                </button>
                            `).join('')}
                </div>` : '';

            const nomor = String(p.id).padStart(2, '0');

            const blokLokasi = lokasi ? `
                    <p class="mb-5 flex items-center gap-2 text-sm text-[#4B463F] text-reveal text-reveal-2">
                        <svg class="h-4 w-4 shrink-0 text-[#C28E27]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 21s-7-6.2-7-11a7 7 0 1 1 14 0c0 4.8-7 11-7 11z"/>
                            <circle cx="12" cy="10" r="2.5"/>
                        </svg>
                        <span>${esc(lokasi)}</span>
                    </p>` : '';

            const blokProfil = deskripsi ? `
                          <div class="mb-5 text-reveal text-reveal-3">
                        <div class="mb-2 flex items-center gap-2">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-[#C28E27]/10">
                                <svg class="w-3.5 h-3.5 text-[#C28E27]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </span>
                            <h3 class="font-display text-lg font-bold text-black">Profil Kandidat</h3>
                        </div>
                        <p class="text-sm leading-7 text-[#4B463F] whitespace-pre-line">${esc(deskripsi)}</p>
                    </div>` : '';

            const format = p.event?.format_hasil_voting ?? 'keduanya';
            let blokTotalVote = '';
            let blokPersentase = '';
            if (statistik) {
                if (format === 'angka' || format === 'keduanya') {
                    if (sembunyikanVote) {
                        blokTotalVote = `
                            <div class="stat-card rounded-xl border border-[#E3D7C5] bg-[#FAF3E7] p-4">
                                <p class="mb-1 text-[9px] font-bold uppercase tracking-[.14em] text-[#6A6259]">Total Vote</p>
                                <p class="font-display text-2xl font-bold text-[#C28E27] flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                    </svg>
                                    Disembunyikan
                                </p>
                                <p class="mt-1 text-[10px] text-[#6A6259] italic">Menjaga ketegangan menjelang malam puncak</p>
                            </div>`;
                    } else {
                        blokTotalVote = `
                            <div class="stat-card rounded-xl border border-[#E3D7C5] bg-[#FAF3E7] p-4">
                                <p class="mb-1 text-[9px] font-bold uppercase tracking-[.14em] text-[#6A6259]">Total Vote</p>
                                <p class="font-display text-2xl font-bold text-black">${formatAngka(statistik.totalVoteKu)}</p>
                                <p class="mt-1 text-[10px] text-[#6A6259]">dari ${formatAngka(statistik.totalSemua)} total</p>
                            </div>`;
                    }
                }

                if (format === 'persentase' || format === 'keduanya') {
                    if (sembunyikanVote) {
                        blokPersentase = `
                        <div class="stat-card rounded-xl border border-[#E3D7C5] bg-[#FAF3E7] p-4">
                            <div class="mb-1 flex items-center justify-between">
                                <p class="text-[9px] font-bold uppercase tracking-[.14em] text-[#6A6259]">Persentase Suara</p>
                                <span class="text-[10px] font-bold text-[#C28E27]">🔒</span>
                            </div>
                            <p class="font-display text-2xl font-bold text-[#C28E27] flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                                Disembunyikan
                            </p>
                            <p class="mt-1 text-[10px] text-[#6A6259] italic">Menjaga ketegangan</p>
                        </div>`;
                    } else {
                        blokPersentase = `
                        <div class="stat-card rounded-xl border border-[#E3D7C5] bg-[#FAF3E7] p-4">
                            <div class="mb-1 flex items-center justify-between">
                                <p class="text-[9px] font-bold uppercase tracking-[.14em] text-[#6A6259]">Persentase Suara</p>
                                <span class="text-[10px] font-bold text-[#7F5700]">${statistik.ranking ? '#' + statistik.ranking : ''}</span>
                            </div>
                            <p class="font-display text-2xl font-bold text-black">${statistik.persentase.toFixed(1)}%</p>
                            <div class="mt-2.5 h-1.5 overflow-hidden rounded-full bg-[#E7DED1]">
                                <div class="progress-bar h-full rounded-full bg-[#7F5700]" style="width:${Math.min(100,statistik.persentase)}%"></div>
                            </div>
                        </div>`;
                    }
                }
            }

            const gridCols = format === 'keduanya' ? 'sm:grid-cols-2' : '';

            const blokStatistik = (() => {
            // ── KASUS 1: Voting DIMATIKAN admin → pesan simpel, tanpa akses statistik
            if (!votingAktif) {
                return `
                    <div class="mb-5 rounded-xl border border-orange-200 bg-orange-50 px-4 py-5 text-center">
                        <div class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-full bg-orange-100">
                            <svg class="h-5 w-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                        </div>
                        <p class="font-display text-base font-bold text-orange-800">Voting Sedang Ditutup</p>
                        <p class="mt-1 text-[11px] leading-relaxed text-orange-700">
                            Statistik suara tidak ditampilkan saat voting ditutup.
                        </p>
                    </div>`;
            }

            // ── KASUS 2: Blackout aktif (statistik PASTI ada karena API hanya 200)
            if (statistik?.blackout) {
                return `
                    <div class="notice-reveal relative mb-5 overflow-hidden rounded-2xl border border-[#E8DCC0] bg-gradient-to-br from-[#FDFBF6] via-white to-[#FAF6ED] p-6 text-center shadow-sm">
                        <div class="notice-glow pointer-events-none absolute -top-10 -right-10 h-32 w-32 rounded-full bg-[#C28E27]/15 blur-3xl"></div>

                        <div class="notice-fade-up delay-1 relative mb-5 flex items-center justify-center gap-2">
                            <span class="h-px w-12 bg-gradient-to-r from-transparent to-[#C28E27]/40"></span>
                            <svg class="h-4 w-4 text-[#C28E27] notice-pulse" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z"/>
                            </svg>
                            <span class="h-px w-12 bg-gradient-to-l from-transparent to-[#C28E27]/40"></span>
                        </div>

                        <div class="notice-fade-up delay-2 notice-float relative mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-[#C28E27]/10 ring-1 ring-[#C28E27]/20">
                            <span class="notice-ring absolute inset-0 rounded-full ring-1 ring-[#C28E27]/30"></span>
                            <svg class="h-5 w-5 text-[#C28E27] notice-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                            </svg>
                        </div>

                        <p class="notice-fade-up delay-3 relative mb-3 font-display text-lg font-bold leading-snug text-[#1D1B17] md:text-xl">
                            Siapa yang akan membawa pulang mahkota?
                        </p>

                        <p class="notice-fade-up delay-4 relative mx-auto max-w-xs text-[12px] leading-relaxed text-[#6A6259]">
                            Untuk menjaga ketegangan menjelang malam puncak, kami merahasiakan hasil sementara saat ini.
                        </p>

                        <div class="notice-fade-up delay-5 relative mx-auto my-4 flex items-center justify-center gap-1.5">
                            <span class="h-1 w-1 rounded-full bg-[#C28E27]/40 notice-pulse delay-a"></span>
                            <span class="h-1 w-1 rounded-full bg-[#C28E27]/30 notice-pulse delay-b"></span>
                            <span class="h-1 w-1 rounded-full bg-[#C28E27]/20 notice-pulse delay-c"></span>
                        </div>

                        <div class="notice-fade-up delay-6 relative inline-flex flex-col items-center gap-1">
                            <p class="text-[9px] font-bold uppercase tracking-[0.25em] text-[#8A8178]">
                                Malam Puncak
                            </p>
                            <p class="font-display text-sm font-bold text-[#1D1B17]">
                                ${esc(statistik.waktuGrandFinal ?? '-')} WIB
                            </p>
                        </div>
                    </div>`;
            }

            // ── KASUS 3: Normal, statistik tersedia
            if (statistik) {
                return `
                    <div class="mb-5 grid grid-cols-1 ${gridCols} gap-2.5 text-reveal text-reveal-4">
                        ${blokTotalVote}
                        ${blokPersentase}
                    </div>`;
            }

            // ── KASUS 4: API gagal (403 karena tampilkan_hasil_voting = false)
            //    Voting masih aktif, tapi hasil tidak boleh publik → pesan halus
            return `
                <div class="mb-5 rounded-xl border border-[#E3D7C5] bg-[#FAF3E7] px-4 py-4 text-center">
                    <p class="text-xs font-medium text-[#6A6259]">
                        Statistik suara akan ditampilkan setelah panitia mempublikasikannya.
                    </p>
                </div>`;
        })();

            let isiDukungan;

            if (dukungan === null) {
                isiDukungan = '<p class="py-3 text-sm text-[#4B463F]">Daftar pendukung belum tersedia.</p>';
            } else if (dukungan.length === 0) {
                isiDukungan = '<p class="py-3 text-sm text-[#4B463F]">Belum ada dukungan.</p>';
            } else {
                isiDukungan = dukungan.map((d, i) => {
                    const waktu = waktuLalu(d.tanggal_vote ?? d.created_at);

                    return `
                            <div class="support-item flex items-center gap-3 rounded-lg border border-[#E9E1D7] bg-white px-3 py-2.5">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full ${warnaAvatar[i % warnaAvatar.length]} text-[10px] font-bold">
                                    ${esc(inisial(d.nama_tampilan))}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-xs font-semibold text-black">${esc(d.nama_tampilan)}</p>
                                    <p class="text-[10px] text-[#4B463F]">Memberikan ${formatAngka(d.jumlah_vote)} Vote</p>
                                    ${d.pesan_dukungan ? `<p class="truncate text-[10px] italic text-[#6A6259]">&ldquo;${esc(d.pesan_dukungan)}&rdquo;</p>` : ''}
                                </div>
                                ${waktu ? `<span class="whitespace-nowrap rounded-full bg-[#F3EDE4] px-2 py-1 text-[9px] text-[#6A6259]">${esc(waktu)}</span>` : ''}
                            </div>`;
                }).join('');
            }

            const jumlahPendukung = dukungan ? dukungan.length : 0;

            const blokDukungan = `
                    <div class="mt-7 border-t border-[#D8D0C6]/60 pt-5">
                        <div class="mb-3 flex items-center justify-between">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-[#C28E27]/10">
                                        <svg class="w-3.5 h-3.5 text-[#C28E27]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </span>
                                    <h3 class="font-display text-lg font-bold text-black">Pendukung Terkini</h3>
                                </div>
                            </div>
                            ${dukungan ? `<span class="rounded-full bg-[#FAF3E7] px-2.5 py-1 text-[9px] font-bold uppercase tracking-wide text-[#7F5700]">${jumlahPendukung} Pendukung</span>` : ''}
                        </div>
                        <div class="space-y-1.5">${isiDukungan}</div>
                    </div>`;

            document.getElementById('konten').innerHTML = `
    {{-- ✅ KOLOM KIRI: Foto --}}
    <div class="content-enter min-w-0 flex justify-center">
        <div class="w-full max-w-[420px] md:max-w-none">
            <div class="photo-frame aspect-square sm:aspect-[4/5] max-h-[60vh] sm:max-h-none rounded-2xl shadow-[0_12px_30px_rgba(29,27,23,.08)]">
                <img id="foto-utama"
                    src="${esc(fotoList[0])}"
                    class="h-full w-full object-cover transition-opacity duration-200"
                    alt="${esc(nama)}">
                <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-black/25 to-transparent pointer-events-none"></div>
                <span class="absolute right-3 top-3 rounded-full bg-[#7F5700] px-3 py-1.5 text-[9px] font-bold tracking-[.15em] text-white shadow-sm">
                    #${nomor}
                </span>
            </div>
            ${blokThumbs}
        </div>
    </div>

    {{-- ✅ KOLOM KANAN: Info --}}
    <div class="content-enter min-w-0">
        <p class="mb-2.5 flex flex-wrap items-center gap-x-4 gap-y-1 text-[9px] font-bold uppercase tracking-[.16em] text-[#7F5700]">
            <span>${esc(p.event?.nama ?? '')}</span>
            ${
                sembunyikanVote
                    ? `<span class="normal-case font-semibold tracking-normal">Ranking disembunyikan</span>`
                    : statistik?.ranking
                        ? `<span class="normal-case font-semibold tracking-normal">&#9734; Ranking: #${statistik.ranking}</span>`
                        : ''
            }
        </p>

        <h1 class="font-display text-3xl md:text-4xl font-semibold leading-tight text-[#1D1B17] text-reveal text-reveal-1">${nama}</h1>

        ${blokLokasi}
        ${blokProfil}
                ${blokStatistik}

        ${
            (() => {
                // ✅ PRIORITAS 1: Toggle admin OFF → voting ditutup
                if (!votingAktif) {
                    return `
                        <div class="block w-full cursor-not-allowed rounded-xl border border-orange-200 bg-orange-50 py-3.5 text-center text-sm font-semibold text-orange-700">
                            Voting ditutup tombol vote dinonaktifkan.
                        </div>`;
                }

                if (p.event?.voting_mulai && new Date(p.event.voting_mulai) > new Date()) {
                    return `
                        <div class="block w-full cursor-not-allowed rounded-xl border border-blue-100 bg-blue-50 py-3.5 text-center text-sm font-semibold text-blue-700">
                            Voting belum dibuka
                        </div>`;
                }

                // ✅ Toggle ON = admin override → TOMBOL VOTE TAMPIL
                // (auto_freeze & deadline TIDAK lagi mengunci tombol;
                //  admin yang pegang kontrol penuh via toggle)
                return `
                    <a href="/peserta/${pesertaId}/checkout"
                        class="vote-button block w-full rounded-xl bg-[#C28E27] py-3.5 text-center text-sm font-semibold tracking-wide text-[#FFF8F1] text-reveal text-reveal-5">
                        Rp ${formatAngka(hargaPerVote)} / Vote
                    </a>`;
                        })()
        }

    ${blokDukungan}
    </div>`;
        } catch (e) {
            console.error('muatPeserta error:', e);
            tampilkanError('Terjadi kesalahan saat memuat data. Cek Console untuk detail.');
        }
    }

    // Boot
    muatPeserta();
    </script>
</body>
</html>
