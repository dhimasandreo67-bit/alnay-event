<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout Vote - Event AL-NAY.ID</title>

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

<body class="min-h-screen bg-[#FFF8F1] text-[#1E1B18]">

    @include('partials.header')

    <main class="max-w-4xl mx-auto px-6 py-8">

        <a href="javascript:history.back()"
            class="text-xs font-semibold uppercase tracking-wide text-[#4B463F] hover:text-black">
            &larr; Batalkan / Kembali
        </a>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-8">

            <div class="md:col-span-1 bg-white border border-[#CDC5BC]/30 rounded-xl p-5 h-fit shadow-sm">
                <h2 class="font-display font-bold text-base text-black mb-4">Kandidat Pilihan Anda</h2>
                <div class="aspect-[4/5] bg-[#EFE7DC] rounded-lg overflow-hidden mb-3">
                    <img id="peserta-foto" src="" alt="Foto Peserta" class="w-full h-full object-cover hidden">
                    <div id="peserta-foto-skeleton" class="w-full h-full animate-pulse bg-[#EFE7DC]"></div>
                </div>
                <h3 id="peserta-nama" class="font-display font-bold text-lg text-black">Memuat...</h3>
                <p id="event-nama" class="text-xs text-[#7F5700] font-semibold uppercase tracking-wide mt-1">-</p>

                <hr class="my-4 border-[#CDC5BC]/30">
                <div class="flex justify-between text-xs text-[#4B463F]">
                    <span>Harga per Vote:</span>
                    <span id="harga-label" class="font-bold text-black">Rp 0</span>
                </div>
            </div>

            <div class="md:col-span-2 bg-white border border-[#CDC5BC]/30 rounded-xl p-6 shadow-sm">
                <h1 class="font-display font-bold text-2xl text-black mb-6">Formulir Dukungan</h1>

                <form id="form-vote" onsubmit="prosesPembayaran(event)" class="space-y-5">

                    <div>
                        <label class="block text-sm font-semibold text-black mb-1">Nama Pemilih <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="input-nama" required value="seseorang"
                            placeholder="Masukkan nama Anda"
                            class="w-full border border-[#CDC5BC] rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#C28E27]">
                        <div>
                            <label class="block text-sm font-semibold text-black mb-1">Email Anda <span
                                    class="text-red-500">*</span></label>
                            <input type="email" id="input-email" required
                                value="{{ auth('pelanggan')->user()->email ?? '' }}" placeholder="contoh@email.com"
                                class="w-full border border-[#CDC5BC] rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#C28E27]">
                            <p class="text-[11px] text-[#7F766C] mt-1">Bukti transaksi dan invoice akan dikirimkan ke
                                email
                                ini.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-black mb-1">Jumlah Vote <span
                                    class="text-red-500">*</span></label>

                            <div class="grid grid-cols-4 gap-2 mb-2">
                                <button type="button" onclick="setVote(5)"
                                    class="btn-quick-vote border border-[#CDC5BC] rounded-lg py-1.5 text-xs font-semibold hover:border-[#C28E27] hover:bg-[#FAF3E7] transition">5
                                    Vote</button>
                                <button type="button" onclick="setVote(10)"
                                    class="btn-quick-vote border border-[#CDC5BC] rounded-lg py-1.5 text-xs font-semibold hover:border-[#C28E27] hover:bg-[#FAF3E7] transition">10
                                    Vote</button>
                                <button type="button" onclick="setVote(25)"
                                    class="btn-quick-vote border border-[#CDC5BC] rounded-lg py-1.5 text-xs font-semibold hover:border-[#C28E27] hover:bg-[#FAF3E7] transition">25
                                    Vote</button>
                                <button type="button" onclick="setVote(50)"
                                    class="btn-quick-vote border border-[#CDC5BC] rounded-lg py-1.5 text-xs font-semibold hover:border-[#C28E27] hover:bg-[#FAF3E7] transition">50
                                    Vote</button>
                            </div>
                            <input type="number" id="input-jumlah-vote" value="1" min="1" required
                                placeholder="Atau ketik jumlah manual..."
                                class="w-full border border-[#CDC5BC] rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#C28E27]">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-black mb-1">Pesan Dukungan (Opsional)</label>
                            <textarea id="input-pesan-dukungan" rows="3" maxlength="1000"
                                placeholder="Tuliskan kata-kata semangat untuk kandidat..."
                                class="w-full border border-[#CDC5BC] rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#C28E27]"></textarea>
                        </div>

                        <div class="space-y-4 pt-2">
                            <label class="block text-sm font-bold text-black border-b border-[#CDC5BC]/30 pb-2">
                                Pilih Metode Pembayaran
                            </label>

                            <div class="space-y-2">
                                <span class="text-xs font-semibold uppercase text-[#7F766C]">Pembayaran Instant (Scan
                                    QR)</span>
                                <div>
                                    <label
                                        class="border border-[#CDC5BC]/60 rounded-xl p-3.5 flex items-center gap-3 cursor-pointer hover:border-[#C28E27] transition">
                                        <input type="radio" name="payment_type" value="qris" checked
                                            class="accent-[#C28E27]">
                                        <div>
                                            <p class="text-xs font-bold text-black">QRIS (Semua E-Wallet & M-Banking)
                                            </p>
                                            <p class="text-[10px] text-[#7F766C]">BCA, GoPay, OVO, ShopeePay, Dana,
                                                LinkAja,
                                                Mandiri, dll.</p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="space-y-2 pt-2">
                                <span class="text-xs font-semibold uppercase text-[#7F766C]">Virtual Account (Transfer
                                    Bank)</span>
                                <div class="grid grid-cols-1 gap-2.5">
                                    <label
                                        class="border border-[#CDC5BC]/60 rounded-xl p-3 flex items-center gap-3 cursor-pointer hover:border-[#C28E27] transition">
                                        <input type="radio" name="payment_type" value="bri_va"
                                            class="accent-[#C28E27]">
                                        <div>
                                            <p class="text-xs font-bold text-black">BRI Virtual Account (BRIVA)</p>
                                            <p class="text-[10px] text-[#7F766C]">BRImo, ATM BRI</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-[#FAF3E7] border border-[#CDC5BC]/30 p-4 rounded-xl flex items-center justify-between mt-6">
                            <span class="text-xs font-semibold uppercase text-[#4B463F]">Total Pembayaran:</span>
                            <span id="total-pembayaran" class="font-display font-bold text-xl text-[#7F5700]">Rp
                                0</span>
                        </div>

                        <p id="pesan-error" class="text-sm text-red-600 hidden"></p>

                        <button type="submit" id="btn-bayar"
                            class="w-full bg-[#C28E27] text-[#FFF8F1] rounded-xl py-3.5 text-sm font-semibold tracking-wide hover:bg-[#a97b1f] transition shadow-md">
                            Lanjut ke Pembayaran
                        </button>
                </form>
            </div>

        </div>

    </main>

    <script>
        const pesertaId = {{ $pesertaId }};
        let hargaPerVote = 0;

        function formatAngka(n) {
            return new Intl.NumberFormat('id-ID').format(n);
        }

        function setVote(jumlah) {
            document.getElementById('input-jumlah-vote').value = jumlah;
            hitungTotal();
        }

        async function muatPeserta() {
            try {
                const res = await fetch(`/api/peserta/${pesertaId}`);
                if (!res.ok) throw new Error('Gagal mengambil data peserta');

                const p = await res.json();
                hargaPerVote = p.harga_per_vote ?? 0;

                document.getElementById('peserta-nama').textContent = p.nama ?? 'Peserta';
                document.getElementById('event-nama').textContent = p.kategori ?? p.event?.nama ?? 'Event Voting';
                document.getElementById('harga-label').textContent = `Rp ${formatAngka(hargaPerVote)}`;

                const img = document.getElementById('peserta-foto');
                img.src = p.foto ?? `https://ui-avatars.com/api/?size=512&name=${encodeURIComponent(p.nama ?? '?')}`;
                img.classList.remove('hidden');
                document.getElementById('peserta-foto-skeleton').classList.add('hidden');

                hitungTotal();
            } catch (err) {
                console.error(err);
                swalAlert('Gagal memuat detail peserta.', 'error'); 
            }
        }

        function hitungTotal() {
            const qty = parseInt(document.getElementById('input-jumlah-vote').value) || 0;
            const total = qty * hargaPerVote;
            document.getElementById('total-pembayaran').textContent = `Rp ${formatAngka(total)}`;
        }

        document.getElementById('input-jumlah-vote').addEventListener('input', hitungTotal);

        async function prosesPembayaran(e) {
            e.preventDefault();
            const btn = document.getElementById('btn-bayar');
            const pesanError = document.getElementById('pesan-error');

            btn.disabled = true;
            btn.textContent = 'Memproses Transaksi...';
            pesanError.classList.add('hidden');

            const selectedPayment = document.querySelector('input[name="payment_type"]:checked');

            const payload = {
                nama: document.getElementById('input-nama').value,
                email: document.getElementById('input-email').value,
                jumlah_vote: parseInt(document.getElementById('input-jumlah-vote').value),
                pesan_dukungan: document.getElementById('input-pesan-dukungan').value || null,
                metode_pembayaran: selectedPayment ? selectedPayment.value : 'qris'
            };

            try {
                const res = await fetch(`/peserta/${pesertaId}/vote`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'include',
                    body: JSON.stringify(payload)
                });

                const data = await res.json();

                if (!res.ok) {
                    throw new Error(data.message || 'Terjadi kesalahan sistem.');
                }

                // REDIRECT LANGSUNG KE HALAMAN INSTRUKSI
                if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    throw new Error('URL redirect tidak ditemukan.');
                }

            } catch (err) {
                pesanError.textContent = err.message;
                pesanError.classList.remove('hidden');
                btn.disabled = false;
                btn.textContent = 'Lanjut ke Pembayaran';
            }
        }

        muatPeserta();
    </script>
    
    @include('partials.swal')

</body>

</html>
