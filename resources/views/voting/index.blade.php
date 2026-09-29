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

    <header class="border-b border-[#CDC5BC]/30 bg-[#FBF9F4]/95 backdrop-blur">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="/beranda" class="font-display font-bold text-xl text-[#0A1526]">EVENT ALNAY.ID</a>
            <span class="w-11 h-11 rounded-full border border-[#CDC5BC] flex items-center justify-center text-[#4B463F]">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                    stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="8" r="4" />
                    <path d="M4 21c0-4 3.6-7 8-7s8 3 8 7" />
                </svg>
            </span>
        </div>
    </header>

    <main class="glow flex-1">
        <div class="max-w-6xl mx-auto px-6 py-8">

            <nav class="text-xs font-semibold uppercase tracking-wide text-[#4B463F] flex flex-wrap items-center gap-2">
                <a href="/beranda" class="hover:text-black">&larr; Kembali ke Daftar Kandidat</a>
                <span class="text-[#CDC5BC]">/</span>
                <span class="text-black">Detail Kandidat</span>
            </nav>

            <div id="konten" class="grid grid-cols-1 md:grid-cols-[5fr_7fr] gap-10 mt-6">
                <p class="text-[#4B463F] md:col-span-2">Memuat data peserta...</p>
            </div>

        </div>
    </main>

    <footer class="bg-[#0F0D0B] text-[#CFC7BC] mt-16">
        <div class="max-w-6xl mx-auto px-6 pt-12 pb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <div>
                    <p class="font-display font-bold text-xl tracking-[0.2em] text-white">ALNAY.ID</p>
                    <p class="text-[11px] tracking-[0.25em] text-[#C28E27] uppercase mt-1">Jas &amp; Gown Berbudaya</p>
                    <p class="text-sm leading-relaxed mt-4 text-[#A39B90]">
                        Platform penyewaan dan bespoke atelier busana adat Nusantara nomor satu di Indonesia.
                        Menghadirkan mahakarya budaya dengan standar kualitas tailoring adibusana internasional.
                    </p>
                    <div class="flex gap-2 mt-5">
                        <span class="w-9 h-9 rounded-full bg-[#1C1916] flex items-center justify-center text-[10px] font-bold">IG</span>
                        <span class="w-9 h-9 rounded-full bg-[#1C1916] flex items-center justify-center text-[10px] font-bold">WA</span>
                        <span class="w-9 h-9 rounded-full bg-[#1C1916] flex items-center justify-center text-[10px] font-bold">FB</span>
                        <span class="w-9 h-9 rounded-full bg-[#1C1916] flex items-center justify-center text-[10px] font-bold">YT</span>
                    </div>
                </div>

                <div>
                    <h4 class="text-xs font-bold uppercase tracking-[0.2em] text-white mb-4">Koleksi Adat</h4>
                    <ul class="space-y-2 text-sm text-[#A39B90]">
                        <li><a href="#" class="hover:text-white">Jas Beskap Pria</a></li>
                        <li><a href="#" class="hover:text-white">Gown Kebaya Pengantin</a></li>
                        <li><a href="#" class="hover:text-white">Set Baju Pasangan</a></li>
                        <li><a href="#" class="hover:text-white">Mahkota &amp; Suntiang</a></li>
                        <li><a href="#" class="hover:text-white">Tenun Songket Asli</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xs font-bold uppercase tracking-[0.2em] text-white mb-4">Butuh Bantuan?</h4>
                    <p class="text-sm text-white">Senin - Jumat: 08:00 - 20:00 WIB</p>
                    <p class="text-sm text-[#A39B90] mt-1">Sabtu - Minggu: 09:00 - 18:00 WIB</p>
                    <a href="#"
                        class="inline-block mt-4 border border-[#C28E27] text-[#C28E27] text-sm px-4 py-2 rounded hover:bg-[#C28E27] hover:text-black transition">
                        Chat WhatsApp CS
                    </a>
                    <p class="text-xs text-[#A39B90] mt-4">Jl. Mahakarya Adat No. 18, Bandung Wetan, Jawa Barat</p>
                </div>

                <div>
                    <h4 class="text-xs font-bold uppercase tracking-[0.2em] text-white mb-4">Layanan &amp; Informasi</h4>
                    <ul class="space-y-2 text-sm text-[#A39B90]">
                        <li><a href="#" class="hover:text-white">Cara &amp; Syarat Sewa Busana</a></li>
                        <li><a href="#" class="hover:text-white">Aturan Deposit &amp; Asuransi</a></li>
                        <li><a href="#" class="hover:text-white">Jadwal Fitting Studio</a></li>
                        <li><a href="#" class="hover:text-white">Panduan Ukur Badan Online</a></li>
                        <li><a href="#" class="hover:text-white">Pemesanan Custom Bespoke</a></li>
                    </ul>
                </div>
            </div>

            <div class="flex flex-col md:flex-row md:justify-between gap-6 mt-10">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide mb-3">Metode Pembayaran Resmi</p>
                    <div class="flex flex-wrap gap-2 text-xs">
                        <span class="border border-[#2C2823] bg-[#1C1916] rounded px-3 py-1.5">BCA</span>
                        <span class="border border-[#2C2823] bg-[#1C1916] rounded px-3 py-1.5">MANDIRI</span>
                        <span class="border border-[#2C2823] bg-[#1C1916] rounded px-3 py-1.5">BNI</span>
                        <span class="border border-[#2C2823] bg-[#1C1916] rounded px-3 py-1.5">BRI</span>
                        <span class="border border-[#2C2823] bg-[#1C1916] rounded px-3 py-1.5">QRIS</span>
                        <span class="border border-[#2C2823] bg-[#1C1916] rounded px-3 py-1.5">VISA / MASTER</span>
                    </div>
                </div>
                <div class="md:text-right">
                    <p class="text-xs font-semibold uppercase tracking-wide mb-3">Ekspedisi Khusus &amp; Kurir Aman</p>
                    <div class="flex flex-wrap md:justify-end gap-2 text-xs">
                        <span class="border border-[#2C2823] bg-[#1C1916] rounded px-3 py-1.5">JNE YES</span>
                        <span class="border border-[#2C2823] bg-[#1C1916] rounded px-3 py-1.5">GoSend Instant</span>
                        <span class="border border-[#2C2823] bg-[#1C1916] rounded px-3 py-1.5">GrabExpress</span>
                        <span class="border border-[#2C2823] bg-[#1C1916] rounded px-3 py-1.5">Paxel Kargo</span>
                    </div>
                </div>
            </div>

            <div class="border-t border-[#2C2823] mt-8 pt-5 flex flex-col md:flex-row md:justify-between gap-3 text-xs text-[#8A8278]">
                <p>&copy; 2026 AL-NAY.ID (Jas &amp; Gown Berbudaya). All Rights Reserved.</p>
                <div class="flex flex-wrap gap-x-6 gap-y-1">
                    <a href="#" class="hover:text-white">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-white">Syarat &amp; Ketentuan Sewa</a>
                    <a href="#" class="hover:text-white">Keanggunan Adat Nusantara</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        const pesertaId = {{ (int) $pesertaId }};
        let hargaPerVote = 0;
        let fotoList = [];

        // Escape semua teks dari pengguna sebelum masuk innerHTML (cegah XSS dari jawaban formulir)
        function esc(s) {
            return String(s ?? '').replace(/[&<>"']/g, c => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            }[c]));
        }

        async function cekLogin() {
            const res = await fetch('/auth/me', {
                credentials: 'include'
            });
            if (res.status === 401) {
                window.location.href = '/login';
                return false;
            }
            return true;
        }

        function formatAngka(n) {
            return new Intl.NumberFormat('id-ID').format(n ?? 0);
        }

        function inisial(nama) {
            return (nama ?? '?').trim().split(/\s+/).slice(0, 2).map(s => s[0]?.toUpperCase()).join('');
        }

        // Path relatif -> /storage/..., dan foto Google diperbesar (default s96 terlalu buram)
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

        // Cari jawaban berdasarkan tag_tampilan; kalau tidak ada, cari lewat kata kunci di label pertanyaan
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

        // Bentuk data hasilVoting(): { event, peserta, hasil_voting }, tiap item punya id & total_vote
        async function muatStatistik(eventId) {
            const res = await fetch(`/event/${eventId}/hasil-voting`, {
                credentials: 'include'
            });
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
        }

        async function muatDukunganTerkini(eventId) {
            const res = await fetch(`/event/${eventId}/peserta/${pesertaId}/dukungan-terkini`, {
                credentials: 'include'
            });
            if (!res.ok) return null;

            const data = await res.json();
            return Array.isArray(data) ? data : (data.dukungan_terkini ?? []);
        }

        const warnaAvatar = [
            'bg-[#7F5700] text-white',
            'bg-black text-white',
            'bg-[#EFE7DC] text-black',
        ];

        async function muatPeserta() {
            const res = await fetch(`/peserta/${pesertaId}`, {
                headers: {
                    'Accept': 'application/json'
                }
            });
            if (!res.ok) {
                document.getElementById('konten').innerHTML =
                    '<p class="text-red-600 md:col-span-2">Peserta tidak ditemukan.</p>';
                return;
            }
            const p = await res.json();
            hargaPerVote = p.event?.harga_per_vote ?? 0;

            const [statistik, dukungan] = await Promise.all([
                muatStatistik(p.event?.id),
                muatDukunganTerkini(p.event?.id),
            ]);

            const nama = p.user?.nama ?? 'Peserta';
            const lokasi = cariJawaban(p.jawabans, 'lokasi', /lokasi|domisili|kota/i);
            const deskripsi = cariJawaban(p.jawabans, 'deskripsi', /deskripsi|profil|tentang|bio/i);

            // ===== Foto: galeri kalau ada, kalau tidak pakai foto akun =====
            const galeri = (p.galeri_foto ?? []).map(fotoUrl).filter(Boolean);
            const fotoAvatar = fotoUrl(p.user?.foto_url) ??
                `https://ui-avatars.com/api/?size=512&background=0A1526&color=FFF8F1&name=${encodeURIComponent(nama)}`;
            fotoList = galeri.length ? galeri : [fotoAvatar];

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

            // ===== Lokasi & profil =====
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

            // ===== Statistik =====
            const blokStatistik = statistik ? `
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-5">
                    <div class="bg-[#FAF3E7] border border-[#CDC5BC]/40 p-4">
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-[#4B463F] mb-1">Total Vote</p>
                        <p class="font-display font-bold text-3xl text-black">${formatAngka(statistik.totalVoteKu)}</p>
                        <p class="text-xs text-[#4B463F] mt-1">dari ${formatAngka(statistik.totalSemua)} total</p>
                    </div>
                    <div class="bg-[#FAF3E7] border border-[#CDC5BC]/40 p-4">
                        <div class="flex items-center justify-between mb-1">
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-[#4B463F]">Persentase Suara</p>
                            <span class="text-xs font-bold text-[#7F5700]">${statistik.ranking ? '#' + statistik.ranking : ''}</span>
                        </div>
                        <p class="font-display font-bold text-3xl text-black">${statistik.persentase.toFixed(1)}%</p>
                        <div class="mt-2 h-2 bg-[#EFE7DC] rounded-full overflow-hidden">
                            <div class="h-full bg-[#7F5700] rounded-full" style="width: ${Math.min(100, statistik.persentase)}%"></div>
                        </div>
                    </div>
                </div>` : `
                <div class="mb-5 bg-[#FAF3E7] border border-[#CDC5BC]/40 p-4 text-sm text-[#4B463F]">
                    Statistik suara belum tersedia.
                </div>`;

            // ===== Pendukung terkini =====
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

            // ===== Susun halaman =====
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
                        ${statistik?.ranking ? `<span class="normal-case font-semibold tracking-normal">&#9734; Ranking: #${statistik.ranking}</span>` : ''}
                    </p>
                    <h1 class="font-display font-bold text-4xl md:text-5xl text-black mb-3">${esc(nama)}</h1>
                    ${blokLokasi}
                    ${blokProfil}
                    ${blokStatistik}

                    <a href="/peserta/${pesertaId}/checkout"
                        class="block w-full text-center bg-[#C28E27] text-[#FFF8F1] py-4 text-base font-semibold tracking-wide hover:bg-[#a97b1f] transition">
                        Rp ${formatAngka(hargaPerVote)} / Vote
                    </a>

                    ${blokDukungan}
                </div>
            `;
        }

        (async () => {
            const loggedIn = await cekLogin();
            if (!loggedIn) return;
            muatPeserta();
        })();
    </script>
</body>

</html>