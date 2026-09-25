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

    <script type="text/javascript"
        src="{{ $midtransIsProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
        data-client-key="{{ $midtransClientKey }}"></script>

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

    <!-- Modal Form Vote -->
    <div id="modal-vote" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-display font-bold text-xl text-black">Beri Suara</h2>
                <button onclick="tutupModalVote()" class="text-[#4B463F] text-2xl leading-none">&times;</button>
            </div>

            <label class="block text-sm font-medium text-black mb-1">Jumlah Vote</label>
            <input type="number" id="input-jumlah-vote" value="1" min="1"
                class="w-full border border-[#CDC5BC] rounded-md px-3 py-2 mb-4">

            <label class="block text-sm font-medium text-black mb-1">Tampilkan nama sebagai</label>
            <select id="input-tipe-nama" class="w-full border border-[#CDC5BC] rounded-md px-3 py-2 mb-4">
                <option value="asli">Nama asli saya</option>
                <option value="samaran">Nama samaran</option>
                <option value="anonim">Anonim</option>
            </select>

            <div id="wrap-nama-samaran" class="hidden mb-4">
                <label class="block text-sm font-medium text-black mb-1">Nama Samaran</label>
                <input type="text" id="input-nama-samaran"
                    class="w-full border border-[#CDC5BC] rounded-md px-3 py-2">
            </div>

            <label class="block text-sm font-medium text-black mb-1">Pesan Dukungan (opsional)</label>
            <textarea id="input-pesan-dukungan" rows="3" maxlength="1000"
                class="w-full border border-[#CDC5BC] rounded-md px-3 py-2 mb-4"
                placeholder="Tulis pesan dukungan..."></textarea>

            <p id="pesan-error" class="text-sm text-red-600 mb-3 hidden"></p>

            <button onclick="kirimVote()"
                class="w-full bg-[#C28E27] text-[#FFF8F1] rounded-md py-3 text-sm font-semibold tracking-wide hover:bg-[#a97b1f] transition">
                Bayar &amp; Vote
            </button>
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

        function bukaModalVote() {
            document.getElementById('modal-vote').classList.remove('hidden');
        }

        function tutupModalVote() {
            document.getElementById('modal-vote').classList.add('hidden');
        }

        async function muatStatistik(eventId) {
            const res = await fetch(`/event/${eventId}/hasil-voting`, {
                credentials: 'include'
            });
            if (!res.ok) return null; // flag mati / tidak berizin -> statistik disembunyikan

            const data = await res.json();
            const hasil = data.hasil_voting ?? [];
            const totalSemua = hasil.reduce((sum, h) => sum + parseInt(h.total_vote), 0);
            const indexKu = hasil.findIndex(h => h.peserta_id === pesertaId);
            const totalVoteKu = indexKu !== -1 ? parseInt(hasil[indexKu].total_vote) : 0;
            const persentase = totalSemua > 0 ? (totalVoteKu / totalSemua * 100) : 0;
            const ranking = indexKu !== -1 ? indexKu + 1 : null;

            return {
                totalVoteKu,
                totalSemua,
                persentase,
                ranking,
                totalPeserta: hasil.length,
            };
        }

        async function muatDukunganTerkini(eventId) {
            const res = await fetch(`/event/${eventId}/peserta/${pesertaId}/dukungan-terkini`, {
                credentials: 'include'
            });
            if (!res.ok) return null; // flag mati / tidak berizin -> bagian ini disembunyikan

            const data = await res.json();
            return data.dukungan_terkini ?? [];
        }

        function cariJawaban(jawabans, tag) {
            const ditemukan = (jawabans ?? []).find(j => j.pertanyaan?.tag_tampilan === tag);
            return ditemukan ? ditemukan.jawaban : null;
        }

        function inisial(nama) {
            return (nama ?? '?').trim().split(/\s+/).slice(0, 2).map(s => s[0]?.toUpperCase()).join('');
        }

        async function muatPeserta() {
            const res = await fetch(`/peserta/${pesertaId}`);
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
                const daftarHtml = dukungan.length > 0
                    ? dukungan.map(d => `
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
                    `).join('')
                    : '<p class="text-sm text-[#4B463F] py-3">Belum ada dukungan.</p>';

                blokDukungan = `
                    <div class="mt-8 bg-white border border-[#CDC5BC]/30 rounded-lg p-4">
                        <h3 class="font-display font-bold text-lg text-black mb-1">Daftar Pendukung Terkini</h3>
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
                    <p class="text-xs font-bold uppercase tracking-wide text-[#7F5700] mb-2">${p.event?.nama ?? ''}</p>
                    <h1 class="font-display font-bold text-4xl text-black mb-2">${p.user?.nama ?? 'Peserta'}</h1>
                    ${blokLokasi}
                    ${blokProfil}
                    ${blokStatistik}
                    <button onclick="bukaModalVote()"
                        class="w-full bg-[#C28E27] text-[#FFF8F1] rounded-md py-3 text-sm font-semibold tracking-wide hover:bg-[#a97b1f] transition">
                        Rp ${formatAngka(hargaPerVote)} / Vote
                    </button>
                    ${blokDukungan}
                </div>
            `;
        }

        document.getElementById('input-tipe-nama').addEventListener('change', function() {
            document.getElementById('wrap-nama-samaran').classList.toggle('hidden', this.value !== 'samaran');
        });

        async function kirimVote() {
            const jumlahVote = document.getElementById('input-jumlah-vote').value;
            const tipeNama = document.getElementById('input-tipe-nama').value;
            const namaSamaran = document.getElementById('input-nama-samaran').value;
            const pesanDukungan = document.getElementById('input-pesan-dukungan').value;
            const pesanError = document.getElementById('pesan-error');

            const res = await fetch(`/peserta/${pesertaId}/vote`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                credentials: 'include',
                body: JSON.stringify({
                    jumlah_vote: parseInt(jumlahVote),
                    tipe_nama: tipeNama,
                    nama_samaran: namaSamaran,
                    pesan_dukungan: pesanDukungan || null,
                }),
            });

            const data = await res.json();

            if (!res.ok) {
                pesanError.textContent = data.message ?? 'Terjadi kesalahan.';
                pesanError.classList.remove('hidden');
                return;
            }

            pesanError.classList.add('hidden');
            tutupModalVote();

            snap.pay(data.snap_token, {
                onSuccess: function() {
                    alert('Pembayaran berhasil! Terima kasih sudah vote.');
                },
                onPending: function() {
                    alert('Pembayaran sedang diproses.');
                },
                onError: function() {
                    alert('Pembayaran gagal.');
                },
                onClose: function() {
                    alert('Kamu menutup popup sebelum menyelesaikan pembayaran.');
                }
            });
        }

        (async () => {
            const loggedIn = await cekLogin();
            if (!loggedIn) return;
            muatPeserta();
        })();
    </script>
</body>

</html>