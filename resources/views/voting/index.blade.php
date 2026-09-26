<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Peserta - Event AL-NAY.ID</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@400;600;700&family=Manrope:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Manrope', sans-serif;
        }

        .font-display {
            font-family: 'Noto Serif', serif;
        }
    </style>
</head>

<body class="min-h-screen bg-[#FFF8F1]">

    <header class="border-b border-[#CDC5BC]/30 bg-[#FBF9F4]/95 backdrop-blur">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <span class="font-display font-bold text-xl text-black">EVENT ALNAY.ID</span>
        </div>
    </header>

    <div class="max-w-5xl mx-auto px-6 py-8">

        <a href="/beranda" class="text-xs font-semibold uppercase tracking-wide text-[#4B463F] hover:text-black">
            &larr; Kembali ke Daftar Kandidat
        </a>

        <div id="konten" class="grid grid-cols-1 md:grid-cols-2 gap-10 mt-4">
            <p class="text-[#4B463F] col-span-2">Memuat data peserta...</p>
        </div>

    </div>

    <script>
        const pesertaId = {{ $pesertaId }};
        let hargaPerVote = 0;

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
            return new Intl.NumberFormat('id-ID').format(n);
        }

        // Bentuk data hasilVoting() sekarang: { event, peserta, hasil_voting }
        // dan tiap item di hasil_voting adalah objek Peserta (field id, total_vote, dst)
        async function muatStatistik(eventId) {
            const res = await fetch(`/event/${eventId}/hasil-voting`, {
                credentials: 'include'
            });
            if (!res.ok) return null;

            const data = await res.json();
            const daftar = data.hasil_voting ?? data.peserta ?? [];
            const totalSemua = daftar.reduce((sum, h) => sum + parseInt(h.total_vote ?? 0), 0);
            const indexKu = daftar.findIndex(h => h.id === pesertaId);
            const totalVoteKu = indexKu !== -1 ? parseInt(daftar[indexKu].total_vote ?? 0) : 0;
            const persentase = totalSemua > 0 ? (totalVoteKu / totalSemua * 100) : 0;
            const ranking = indexKu !== -1 ? indexKu + 1 : null;

            return {
                totalVoteKu,
                totalSemua,
                persentase,
                ranking,
                totalPeserta: daftar.length,
            };
        }

        // dukunganTerkini() sekarang mengembalikan array langsung, bukan {dukungan_terkini: [...]}
        async function muatDukunganTerkini(eventId) {
            const res = await fetch(`/event/${eventId}/peserta/${pesertaId}/dukungan-terkini`, {
                credentials: 'include'
            });
            if (!res.ok) return null;

            const data = await res.json();
            return Array.isArray(data) ? data : (data.dukungan_terkini ?? []);
        }

        function cariJawaban(jawabans, tag) {
            const ditemukan = (jawabans ?? []).find(j => j.pertanyaan?.tag_tampilan === tag);
            return ditemukan ? ditemukan.jawaban : null;
        }

        function inisial(nama) {
            return (nama ?? '?').trim().split(/\s+/).slice(0, 2).map(s => s[0]?.toUpperCase()).join('');
        }

        async function muatPeserta() {
            const res = await fetch(`/peserta/${pesertaId}`, {
                headers: {
                    'Accept': 'application/json'
                }
            });
            if (!res.ok) {
                document.getElementById('konten').innerHTML =
                    '<p class="text-red-600 col-span-2">Peserta tidak ditemukan.</p>';
                return;
            }
            const p = await res.json();
            hargaPerVote = p.event?.harga_per_vote ?? 0;

            const statistik = await muatStatistik(p.event?.id);
            const dukungan = await muatDukunganTerkini(p.event?.id);

            const lokasi = cariJawaban(p.jawabans, 'lokasi');
            const deskripsi = cariJawaban(p.jawabans, 'deskripsi');

            let blokGaleri = '';
            if (p.galeri_foto && p.galeri_foto.length > 0) {
                const thumbnails = p.galeri_foto.map(url => `
                    <div class="aspect-square rounded-lg overflow-hidden bg-[#EFE7DC]">
                        <img src="${url}" class="w-full h-full object-cover">
                    </div>
                `).join('');
                blokGaleri = `<div class="grid grid-cols-3 gap-2 mt-3">${thumbnails}</div>`;
            }

            let blokLokasi = '';
            if (lokasi) {
                blokLokasi = `
                    <p class="flex items-center gap-1 text-[#4B463F] text-sm mb-4">
                        <span>&#128205;</span> ${lokasi}
                    </p>
                `;
            }

            let blokProfil = '';
            if (deskripsi) {
                blokProfil = `
                    <div class="mb-6">
                        <h3 class="font-display font-bold text-lg text-black mb-2">Profil Kandidat</h3>
                        <p class="text-[#4B463F] text-sm leading-relaxed">${deskripsi}</p>
                    </div>
                `;
            }

            let blokStatistik = '';
            if (statistik) {
                const labelRanking = statistik.ranking ? `#${statistik.ranking}` : '-';
                blokStatistik = `
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <div class="bg-[#FAF3E7] border border-[#CDC5BC]/30 rounded-lg p-4">
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-[#4B463F] mb-1">Total Vote</p>
                            <p class="font-display font-bold text-2xl text-black">${formatAngka(statistik.totalVoteKu)}</p>
                            <p class="text-xs text-[#4B463F] mt-1">dari ${formatAngka(statistik.totalSemua)} total</p>
                        </div>
                        <div class="bg-[#FAF3E7] border border-[#CDC5BC]/30 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-1">
                                <p class="text-[11px] font-semibold uppercase tracking-wide text-[#4B463F]">Persentase Suara</p>
                                <span class="text-xs font-bold text-[#7F5700]">${labelRanking}</span>
                            </div>
                            <p class="font-display font-bold text-2xl text-black">${statistik.persentase.toFixed(1)}%</p>
                            <div class="mt-2 h-2 bg-[#EFE7DC] rounded-full overflow-hidden">
                                <div class="h-full bg-[#7F5700] rounded-full" style="width: ${statistik.persentase}%"></div>
                            </div>
                        </div>
                    </div>
                `;
            }

            let blokDukungan = '';
            if (dukungan !== null) {
                const daftarHtml = dukungan.length > 0 ?
                    dukungan.map(d => `
                        <div class="flex items-center gap-3 border-b border-[#CDC5BC]/30 py-3 last:border-b-0">
                            <div class="w-9 h-9 rounded-full bg-[#0A1526] text-[#FFF8F1] flex items-center justify-center text-xs font-bold flex-shrink-0">
                                ${inisial(d.nama_tampilan)}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-black text-sm">${d.nama_tampilan}</p>
                                ${d.pesan_dukungan ? `<p class="text-[#4B463F] text-sm truncate">${d.pesan_dukungan}</p>` : `<p class="text-[#4B463F] text-sm">Memberikan ${formatAngka(d.jumlah_vote)} vote</p>`}
                            </div>
                            ${d.pesan_dukungan ? `<p class="text-xs text-[#7F5700] font-semibold whitespace-nowrap">${formatAngka(d.jumlah_vote)} vote</p>` : ''}
                        </div>
                    `).join('') :
                    '<p class="text-sm text-[#4B463F] py-3">Belum ada dukungan.</p>';

                blokDukungan = `
                    <div class="mt-8 bg-white border border-[#CDC5BC]/30 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="font-display font-bold text-lg text-black">Daftar Pendukung Terkini</h3>
                            <span class="text-xs font-semibold text-[#7F5700] uppercase tracking-wide">${dukungan.length} Pendukung</span>
                        </div>
                        ${daftarHtml}
                    </div>
                `;
            }

            document.getElementById('konten').innerHTML = `
                <div>
                    <div class="bg-[#EFE7DC] rounded-xl overflow-hidden aspect-[4/5]">
                        <img src="${p.user?.foto_url ?? 'https://ui-avatars.com/api/?size=512&name=' + encodeURIComponent(p.user?.nama ?? '?')}"
                             class="w-full h-full object-cover">
                    </div>
                    ${blokGaleri}
                </div>
                <div>
                    <p class="inline-block bg-[#FAF3E7] text-xs font-bold uppercase tracking-wide text-[#7F5700] px-3 py-1 rounded-full mb-3">${p.event?.nama ?? ''}</p>
                    <h1 class="font-display font-bold text-4xl text-black mb-2">${p.user?.nama ?? 'Peserta'}</h1>
                    ${blokLokasi}
                    ${blokProfil}
                    ${blokStatistik}

                    <a href="/peserta/${pesertaId}/checkout"
                        class="block w-full text-center bg-[#C28E27] text-[#FFF8F1] rounded-md py-3 text-sm font-semibold tracking-wide hover:bg-[#a97b1f] transition">
                        Dukung Sekarang (Rp ${formatAngka(hargaPerVote)} / Vote)
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