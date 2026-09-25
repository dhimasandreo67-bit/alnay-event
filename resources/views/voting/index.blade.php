<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Peserta - Event AL-NAY.ID</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@400;600;700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script type="text/javascript"
        src="{{ $midtransIsProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
        data-client-key="{{ $midtransClientKey }}"></script>

    <style>
        body { font-family: 'Manrope', sans-serif; }
        .font-display { font-family: 'Noto Serif', serif; }
    </style>
</head>
<body class="min-h-screen bg-[#FFF8F1]">

    <header class="border-b border-[#CDC5BC]/30 bg-[#FBF9F4]/95 backdrop-blur">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <span class="font-display font-bold text-xl text-black">EVENT ALNAY.ID</span>
        </div>
    </header>

    <div class="max-w-4xl mx-auto px-6 py-10">

        <div id="konten" class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <p class="text-[#4B463F] col-span-2">Memuat data peserta...</p>
        </div>

        <div id="form-vote" class="hidden mt-10 max-w-md bg-white border border-[#CDC5BC]/40 rounded-2xl p-6">
            <h2 class="font-display font-bold text-xl text-black mb-4">Beri Suara</h2>

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

            <p id="pesan-error" class="text-sm text-red-600 mb-3 hidden"></p>

            <button onclick="kirimVote()"
                    class="w-full bg-[#C28E27] text-[#FFF8F1] rounded-md py-3 text-sm font-semibold tracking-wide hover:bg-[#a97b1f] transition">
                Bayar &amp; Vote
            </button>
        </div>
    </div>

    <script>
        const pesertaId = {{ $pesertaId }};

        async function cekLogin() {
            const res = await fetch('/auth/me', { credentials: 'include' });
            if (res.status === 401) {
                window.location.href = '/login';
                return false;
            }
            return true;
        }

        function formatAngka(n) {
            return new Intl.NumberFormat('id-ID').format(n);
        }

        async function muatStatistik(eventId) {
            const res = await fetch(`/event/${eventId}/hasil-voting`, { credentials: 'include' });
            if (!res.ok) return null; // flag mati / tidak berizin -> statistik disembunyikan

            const data = await res.json();
            const hasil = data.hasil_voting ?? [];
            const totalSemua = hasil.reduce((sum, h) => sum + parseInt(h.total_vote), 0);
            const punyaKu = hasil.find(h => h.peserta_id === pesertaId);
            const totalVoteKu = punyaKu ? parseInt(punyaKu.total_vote) : 0;
            const persentase = totalSemua > 0 ? (totalVoteKu / totalSemua * 100) : 0;

            return { totalVoteKu, persentase };
        }

        async function muatPeserta() {
            const res = await fetch(`/peserta/${pesertaId}`);
            if (!res.ok) {
                document.getElementById('konten').innerHTML =
                    '<p class="text-red-600 col-span-2">Peserta tidak ditemukan.</p>';
                return;
            }
            const p = await res.json();
            const statistik = await muatStatistik(p.event?.id);

            let blokStatistik = '';
            if (statistik) {
                blokStatistik = `
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <div class="bg-[#FAF3E7] border border-[#CDC5BC]/30 p-4">
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-[#4B463F] mb-1">Total Vote</p>
                            <p class="font-display font-bold text-2xl text-black">${formatAngka(statistik.totalVoteKu)}</p>
                        </div>
                        <div class="bg-[#FAF3E7] border border-[#CDC5BC]/30 p-4">
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-[#4B463F] mb-1">Persentase</p>
                            <p class="font-display font-bold text-2xl text-black">${statistik.persentase.toFixed(1)}%</p>
                            <div class="mt-2 h-2 bg-[#EFE7DC] rounded-full overflow-hidden">
                                <div class="h-full bg-[#7F5700] rounded-full" style="width: ${statistik.persentase}%"></div>
                            </div>
                        </div>
                    </div>
                `;
            }

            document.getElementById('konten').innerHTML = `
                <div>
                    <div class="bg-[#EFE7DC] rounded-xl overflow-hidden aspect-[4/5]">
                        <img src="${p.user?.foto_url ?? 'https://ui-avatars.com/api/?size=512&name=' + encodeURIComponent(p.user?.nama ?? '?')}"
                             class="w-full h-full object-cover">
                    </div>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-[#7F5700] mb-2">${p.event?.nama ?? ''}</p>
                    <h1 class="font-display font-bold text-4xl text-black mb-4">${p.user?.nama ?? 'Peserta'}</h1>
                    ${blokStatistik}
                </div>
            `;

            document.getElementById('form-vote').classList.remove('hidden');
        }

        document.getElementById('input-tipe-nama').addEventListener('change', function () {
            document.getElementById('wrap-nama-samaran').classList.toggle('hidden', this.value !== 'samaran');
        });

        async function kirimVote() {
            const jumlahVote = document.getElementById('input-jumlah-vote').value;
            const tipeNama = document.getElementById('input-tipe-nama').value;
            const namaSamaran = document.getElementById('input-nama-samaran').value;
            const pesanError = document.getElementById('pesan-error');

            const res = await fetch(`/peserta/${pesertaId}/vote`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                credentials: 'include',
                body: JSON.stringify({
                    jumlah_vote: parseInt(jumlahVote),
                    tipe_nama: tipeNama,
                    nama_samaran: namaSamaran,
                }),
            });

            const data = await res.json();

            if (!res.ok) {
                pesanError.textContent = data.message ?? 'Terjadi kesalahan.';
                pesanError.classList.remove('hidden');
                return;
            }

            pesanError.classList.add('hidden');

            snap.pay(data.snap_token, {
                onSuccess: function () { alert('Pembayaran berhasil! Terima kasih sudah vote.'); },
                onPending: function () { alert('Pembayaran sedang diproses.'); },
                onError: function () { alert('Pembayaran gagal.'); },
                onClose: function () { alert('Kamu menutup popup sebelum menyelesaikan pembayaran.'); }
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