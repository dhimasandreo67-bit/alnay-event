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
        body {
            font-family: 'Manrope', sans-serif;
            background: #FFF8F1;
        }

        .font-display {
            font-family: 'Noto Serif', serif;
        }

        .glow {
            background: radial-gradient(60% 45% at 70% 0%, rgba(194, 142, 39, .12), transparent 70%);
        }
    </style>
</head>

<body class="min-h-screen flex flex-col text-[#1D1B17]">

    @include('partials.header')


    <main class="glow flex-1">
        <div class="max-w-6xl mx-auto px-6 py-8">

            <nav class="text-xs font-semibold uppercase tracking-wide text-[#4B463F] flex flex-wrap items-center gap-2">
                @php
                    $eventId = $peserta->event_id ?? null;
                    $backUrl = $eventId ? "/detail-event/{$eventId}" : '/beranda';
                @endphp

                <a href="{{ $backUrl }}" class="hover:text-black">&larr; Daftar Kandidat</a>
                <span class="text-[#CDC5BC]">/</span>
                <span class="text-black">Detail Kandidat</span>
            </nav>

            <div id="konten" class="grid grid-cols-1 md:grid-cols-[5fr_7fr] gap-10 mt-6">
                <p class="text-[#4B463F] md:col-span-2">Memuat data peserta...</p>
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
            if (el && fotoList[i]) el.src = fotoList[i];
        }

        function tampilkanError(pesan) {
            document.getElementById('konten').innerHTML = `
                <div class="md:col-span-2 bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                    <p class="text-red-700 font-semibold mb-2">${esc(pesan)}</p>
                    <a href="/beranda" class="text-sm text-[#C28E27] hover:underline">← Kembali ke beranda</a>
                </div>`;
        }

        async function muatStatistik(eventId) {
            try {
                const res = await fetch(`/event/${eventId}/hasil-voting`, {
                    credentials: 'include'
                });

                // ✅ Handle blackout H-2 (403)
                if (!res.ok) return null;

                if (!res.ok) return null;
                const data = await res.json();
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
            'bg-[#EFE7DC] text-black',
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
                muatDukunganTerkini(p.event?.id),
            ]);

            const nama = cariJawaban(p.jawabans, 'nama', /nama/i) ?? p.user?.nama ?? 'Peserta';
            const lokasi = cariJawaban(p.jawabans, 'lokasi', /lokasi|domisili|kota|alamat/i);
            const deskripsi = cariJawaban(p.jawabans, 'bio', /bio|profil|tentang|deskripsi/i);

            // ===== Foto: ambil SEMUA jawaban tipe file/gambar + galeri_foto =====
            const galeri = [];

            // 1. Semua jawaban peserta yang tipenya file/gambar
            (p.jawabans ?? []).forEach(j => {
                const tipe = (j.pertanyaan?.tipe_input ?? '').toLowerCase();
                const label = (j.pertanyaan?.label ?? '').toLowerCase();

                // Cek apakah jawaban ini berupa file gambar
                const isFileTipe = ['file', 'upload gambar', 'upload_gambar', 'image', 'gambar'].includes(
                    tipe);
                const isLabelGambar = /upload gambar|foto|gambar|image/i.test(label);

                if ((isFileTipe || isLabelGambar) && j.jawaban) {
                    const url = fotoUrl(j.jawaban);
                    if (url) galeri.push(url);
                }
            });

            // 2. Galeri foto tambahan (kolom JSON di peserta)
            if (Array.isArray(p.galeri_foto)) {
                p.galeri_foto.map(fotoUrl).filter(Boolean).forEach(u => galeri.push(u));
            }

            // 3. Fallback: foto Google / ui-avatar
            if (galeri.length === 0) {
                const fotoAvatar = fotoUrl(p.user?.foto_url) ??
                    `https://ui-avatars.com/api/?size=512&background=0A1526&color=FFF8F1&name=${encodeURIComponent(nama)}`;
                galeri.push(fotoAvatar);
            }

            fotoList = galeri;

            const blokThumbs = fotoList.length > 1 ? `
                    <div class="grid grid-cols-3 gap-3 mt-3">
                        ${fotoList.map((url, i) => `
                                            <button type="button" onclick="gantiFoto(${i})"
                                                class="aspect-square rounded-lg overflow-hidden bg-[#EFE7DC] border border-[#CDC5BC]/40 hover:opacity-80 transition">
                                                <img src="${esc(url)}" class="w-full h-full object-cover" alt="">
                                            </button>
                                        `).join('')}
                    </div>` : '';

            const nomor = String(p.id).padStart(2, '0');

            const blokLokasi = lokasi ? `
                    <p class="flex items-center gap-2 text-[#4B463F] mb-6">
                        <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 21s-7-6.2-7-11a7 7 0 1 1 14 0c0 4.8-7 11-7 11z" />
                            <circle cx="12" cy="10" r="2.5" />
                        </svg>
                        <span>${esc(lokasi)}</span>
                    </p>` : '';

            const blokProfil = deskripsi ? `
                    <div class="mb-6">
                        <h3 class="font-display font-bold text-xl text-black mb-2">Profil Kandidat</h3>
                        <p class="text-[#4B463F] leading-relaxed whitespace-pre-line">${esc(deskripsi)}</p>
                    </div>` : '';

            const format = p.event?.format_hasil_voting ?? 'keduanya';
            let blokTotalVote = '';
            let blokPersentase = '';

            if (statistik) {
                if (format === 'angka' || format === 'keduanya') {
                    blokTotalVote = `
                            <div class="bg-[#FAF3E7] border border-[#CDC5BC]/40 p-4">
                                <p class="text-[11px] font-semibold uppercase tracking-wide text-[#4B463F] mb-1">Total Vote</p>
                                <p class="font-display font-bold text-3xl text-black">${formatAngka(statistik.totalVoteKu)}</p>
                                <p class="text-xs text-[#4B463F] mt-1">dari ${formatAngka(statistik.totalSemua)} total</p>
                            </div>`;
                }
                if (format === 'persentase' || format === 'keduanya') {
                    blokPersentase = `
                            <div class="bg-[#FAF3E7] border border-[#CDC5BC]/40 p-4">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="text-[11px] font-semibold uppercase tracking-wide text-[#4B463F]">Persentase Suara</p>
                                    <span class="text-xs font-bold text-[#7F5700]">${statistik.ranking ? '#' + statistik.ranking : ''}</span>
                                </div>
                                <p class="font-display font-bold text-3xl text-black">${statistik.persentase.toFixed(1)}%</p>
                                <div class="mt-2 h-2 bg-[#EFE7DC] rounded-full overflow-hidden">
                                    <div class="h-full bg-[#7F5700] rounded-full" style="width: ${Math.min(100, statistik.persentase)}%"></div>
                                </div>
                            </div>`;
                }
            }

            const gridCols = (format === 'keduanya') ? 'sm:grid-cols-2' : '';
            const blokStatistik = (() => {
                // ✅ Kalau blackout H-2
                if (statistik?.blackout) {
                    return `
            <div class="mb-5 bg-amber-50 border border-amber-200 rounded-lg p-5 text-center">
                <div class="w-14 h-14 rounded-full bg-amber-100 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <p class="text-amber-800 font-bold text-base mb-1">🔒 Mode Rahasia H-2</p>
                <p class="text-amber-700 text-sm">${esc(statistik.message)}</p>
                <p class="text-amber-600 text-xs mt-3">
                    Grand Final: <strong>${esc(statistik.waktuGrandFinal)} WIB</strong>
                </p>
            </div>`;
                }

                // ✅ Normal — tampil statistik
                if (statistik) {
                    return `
            <div class="grid grid-cols-1 ${gridCols} gap-3 mb-5">
                ${blokTotalVote}
                ${blokPersentase}
            </div>`;
                }

                // ✅ Fallback
                return `
        <div class="mb-5 bg-[#FAF3E7] border border-[#CDC5BC]/40 p-4 text-sm text-[#4B463F]">
            Statistik suara belum tersedia.
        </div>`;
            })();

            let isiDukungan;
            if (dukungan === null) {
                isiDukungan = '<p class="text-sm text-[#4B463F] py-3">Daftar pendukung belum tersedia.</p>';
            } else if (dukungan.length === 0) {
                isiDukungan = '<p class="text-sm text-[#4B463F] py-3">Belum ada dukungan.</p>';
            } else {
                isiDukungan = dukungan.map((d, i) => {
                    const waktu = waktuLalu(d.tanggal_vote ?? d.created_at);
                    return `
                            <div class="flex items-center gap-3 bg-[#FAF3E7]/70 px-3 py-3">
                                <div class="w-10 h-10 rounded-full ${warnaAvatar[i % warnaAvatar.length]} flex items-center justify-center text-xs font-bold shrink-0">
                                    ${esc(inisial(d.nama_tampilan))}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-black text-sm truncate">${esc(d.nama_tampilan)}</p>
                                    <p class="text-[#4B463F] text-sm">Memberikan ${formatAngka(d.jumlah_vote)} Vote</p>
                                    ${d.pesan_dukungan ? `<p class="text-[#4B463F] text-xs italic truncate">&ldquo;${esc(d.pesan_dukungan)}&rdquo;</p>` : ''}
                                </div>
                                ${waktu ? `<span class="text-xs text-[#4B463F] bg-[#EFE7DC] rounded px-2 py-1 whitespace-nowrap">${esc(waktu)}</span>` : ''}
                            </div>`;
                }).join('');
            }

            const jumlahPendukung = dukungan ? dukungan.length : 0;
            const blokDukungan = `
                    <div class="mt-8 border-t border-[#CDC5BC]/40 pt-6">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-display font-bold text-xl text-black">Daftar Pendukung Terkini</h3>
                            ${dukungan ? `<span class="text-xs font-bold text-[#7F5700] uppercase tracking-wide">${jumlahPendukung} Pendukung</span>` : ''}
                        </div>
                        <div class="space-y-2">${isiDukungan}</div>
                    </div>`;

            document.getElementById('konten').innerHTML = `
                    <div class="min-w-0">
                        <div class="relative rounded-xl overflow-hidden bg-[#EFE7DC] aspect-[4/5]">
                            <img id="foto-utama" src="${esc(fotoList[0])}" class="w-full h-full object-cover" alt="${esc(nama)}">
                            <span class="absolute top-4 right-4 bg-[#7F5700] text-white text-xs font-bold tracking-wider px-3 py-1.5 rounded">#${nomor}</span>
                        </div>
                        ${blokThumbs}
                    </div>

                    <div class="min-w-0">
                        <p class="flex flex-wrap items-center gap-x-4 text-sm font-bold uppercase tracking-wide text-[#7F5700] mb-3">
    <span>${esc(p.event?.nama ?? '')}</span>
    ${
        statistik?.blackout
            ? `<span class="normal-case font-semibold tracking-normal"> Ranking disembunyikan </span>`
            : statistik?.ranking
                ? `<span class="normal-case font-semibold tracking-normal">&#9734; Ranking: #${statistik.ranking}</span>`
                : ''
    }
</p>
                        <h1 class="font-display font-bold text-4xl md:text-5xl text-black mb-3">${esc(nama)}</h1>
                        ${blokLokasi}
                        ${blokProfil}
                        ${blokStatistik}

                        ${
                            (() => {
                                // Belum dibuka?
                                if (p.event?.voting_mulai && new Date(p.event.voting_mulai) > new Date()) {
                                    return `<div class="block w-full text-center bg-blue-50 text-blue-700 py-4 text-base font-semibold tracking-wide rounded cursor-not-allowed">
                                                    Voting belum dibuka
                                                </div>`;
                                }
                                // Sudah ditutup? (auto freeze)
                                if (p.event?.auto_freeze_voting && p.event?.voting_selesai && new Date(p.event.voting_selesai) < new Date()) {
                                    return ` < div class =
                "block w-full text-center bg-gray-200 text-gray-600 py-4 text-base font-semibold tracking-wide rounded cursor-not-allowed" >
                Voting Ditutup <
                /div>`;
    }
    // Status event = akan datang?
    if (p.event?.status === 'akan_datang') {
        return `<div class="block w-full text-center bg-gray-200 text-gray-500 py-4 text-base font-semibold tracking-wide rounded cursor-not-allowed">
                                                    Voting belum dibuka
                                                </div>`;
    }
    // Default — tampilkan tombol vote
    return `<a href="/peserta/${pesertaId}/checkout"
                                                class="block w-full text-center bg-[#C28E27] text-[#FFF8F1] py-4 text-base font-semibold tracking-wide hover:bg-[#a97b1f] transition">
                                                Rp ${formatAngka(hargaPerVote)} / Vote
                                            </a>`;
    })()
    }

    ${blokDukungan} 
    </div>
    `;
                        } catch (e) {
                            console.error('muatPeserta error:', e);
                            tampilkanError('Terjadi kesalahan saat memuat data. Cek Console untuk detail.');
                        }
                    }

                    // Boot — selalu panggil muatPeserta (gak perlu cekLogin dulu, karena fetch akan handle 401)
                    muatPeserta();
    </script>
</body>

</html>
