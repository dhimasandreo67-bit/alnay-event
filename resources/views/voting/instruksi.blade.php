<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Voting - EVENT ALNAY.ID</title>

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

<body class="min-h-screen bg-[#FBF9F4] text-[#1E1B18] pb-12">

    <header class="border-b border-[#CDC5BC]/30 bg-white">
        <div class="max-w-5xl mx-auto px-6 py-4 flex items-center justify-between">
            <span class="font-display font-bold text-xl tracking-wider text-black">EVENT ALNAY.ID</span>
            <div
                class="w-8 h-8 rounded-full border border-[#CDC5BC] flex items-center justify-center text-xs font-bold">
                {{ strtoupper(substr($voting->nama_tampilan ?? 'U', 0, 1)) }}
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 mt-6 space-y-6">

        <p class="text-xs text-[#7F766C]">
            Beranda &rsaquo; {{ $event->nama ?? 'Event' }} &rsaquo; <span
                class="text-[#C28E27] font-semibold">Pembayaran Voting</span>
        </p>

        <div class="bg-white border border-[#CDC5BC]/40 rounded-2xl p-5 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-[#FAF3E7] flex items-center justify-center text-[#C28E27]">
                    🏆
                </div>
                <div>
                    <span class="text-[11px] font-bold text-[#C28E27] uppercase tracking-wider block">Menunggu
                        Pembayaran</span>
                    <h2 class="font-display font-bold text-lg text-black">Selesaikan Dukungan Resmi Kamu</h2>
                </div>
            </div>

            <div class="text-right">
                <span class="text-[10px] font-semibold uppercase text-[#7F766C] block">SISA WAKTU</span>
                <span id="timer" class="font-display font-bold text-2xl text-[#C28E27]">59:44</span>
            </div>
        </div>

        <div class="bg-white border border-[#CDC5BC]/40 rounded-2xl p-6 shadow-sm space-y-4">
            <div class="flex justify-between items-center border-b border-[#CDC5BC]/20 pb-3">
                <span class="text-xs font-bold text-[#7F766C] uppercase">PESERTA YANG ANDA DUKUNG</span>
                <span
                    class="bg-[#FAF3E7] text-[#C28E27] text-[10px] font-bold px-2.5 py-1 rounded-full border border-[#C28E27]/20">
                    Kandidat #{{ $peserta->id }}
                </span>
            </div>

            <div class="flex gap-4 items-center">
                <img src="{{ $peserta->foto ? asset('storage/' . $peserta->foto) : $peserta->user->foto_url ?? 'https://ui-avatars.com/api/?name=' . $peserta->user->nama }}"
                    class="w-16 h-20 object-cover rounded-xl border border-[#CDC5BC]/30">

                <div class="flex-1">
                    <span class="text-[11px] font-semibold uppercase text-[#C28E27] block">{{ $event->nama }}</span>
                    <h3 class="font-display font-bold text-lg text-black">
                        {{ $peserta->user->nama ?? $peserta->nama_panggung }}</h3>
                    <p class="text-xs text-[#7F766C] mt-0.5"><b>{{ number_format($peserta->total_vote) }}</b> suara
                        terakumulasi</p>
                </div>

                <div class="text-right">
                    <span class="font-bold text-base text-black block">{{ $voting->jumlah_vote }} Vote</span>
                    <span class="text-xs text-[#7F766C]">Rp {{ number_format($voting->total_bayar) }}</span>
                </div>
            </div>

            @if ($voting->pesan_dukungan)
                <span class="font-semibold text-[#7F766C] block mb-1 rid grid-cols-1 md:grid-cols-2 gap-4 pt-2 text-xs">Pesan dukungan</span>
                <div class="bg-[#FAF3E7]/60 rounded-xl p-3 border border-[#CDC5BC]/20 text-xs text-[#4B463F] italic">
                    "{{ $voting->pesan_dukungan }}"
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2 text-xs">
                <div>
                    <span class="font-semibold text-[#7F766C] block mb-1">Nama pendukung</span>
                    <span
                        class="inline-block bg-[#FAF3E7] text-[#C28E27] font-semibold px-2.5 py-0.5 rounded-full border border-[#C28E27]/20">
                        {{ $voting->nama_tampilan }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">

            <div class="md:col-span-7 bg-white border border-[#CDC5BC]/40 rounded-2xl p-6 shadow-sm space-y-4">

                @if ($voting->metode_pembayaran === 'qris')
                    <h3 class="font-display font-bold text-base text-black border-b border-[#CDC5BC]/20 pb-2">Scan QRIS
                        Untuk Membayar</h3>
                    <div class="text-center py-4 space-y-3">
                        <div class="inline-block bg-white p-3 border border-[#CDC5BC] rounded-2xl shadow-sm">
                            <img src="{{ $voting->qr_url ?? 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=' . $voting->midtrans_order_id }}"
                                alt="QRIS Code" class="w-52 h-52 mx-auto">
                        </div>
                        <p class="text-xs text-[#7F766C] max-w-xs mx-auto">
                            Gunakan aplikasi GoPay, OVO, DANA, ShopeePay, LinkAja, atau m-Banking Anda untuk melakukan
                            scan QRIS di atas.
                        </p>
                    </div>
                @else
                    @php
                        $namaBank = match ($voting->metode_pembayaran) {
                            'bri_va' => 'BRI Virtual Account (BRIVA)',
                            default => 'Virtual Account',
                        };
                    @endphp

                    <h3 class="font-display font-bold text-base text-black border-b border-[#CDC5BC]/20 pb-2">Transfer
                        ke {{ $namaBank }}</h3>

                    <div class="space-y-2">
                        <span class="text-[11px] text-[#7F766C] uppercase font-semibold">Nomor Virtual Account</span>
                        <div
                            class="bg-[#FAF3E7] p-3.5 rounded-xl flex items-center justify-between border border-[#CDC5BC]/30">
                            <span id="va-num"
                                class="font-mono font-bold text-lg md:text-xl tracking-wider text-black">{{ $voting->va_number }}</span>
                            <button onclick="salinVA()"
                                class="bg-[#C28E27] text-white text-xs font-semibold px-4 py-2 rounded-lg hover:bg-[#a97b1f] transition">
                                Salin No. VA
                            </button>
                        </div>
                        <div class="flex justify-between text-[11px] text-[#7F766C] px-1">
                            <span>Nama Penerima: <b>ALNAY VOTE -
                                    {{ strtoupper($peserta->user->nama ?? ($peserta->nama_panggung ?? 'PESERTA')) }}</b></span>
                            <span>Total: <b class="text-[#C28E27]">Rp
                                    {{ number_format($voting->total_bayar) }}</b></span>
                        </div>
                    </div>

                    <div
                        class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-3 text-xs flex items-center gap-2">
                        <span>✓</span>
                        <span>Suara otomatis terverifikasi begitu transfer diterima — tidak perlu upload bukti
                            bayar.</span>
                    </div>

                    <div class="space-y-2 pt-2 text-xs">
                        <span class="font-bold text-black block">Cara Transfer via {{ $namaBank }}</span>
                        <ol class="list-decimal list-inside space-y-1 text-[#4B463F] leading-relaxed">
                            <li>Buka aplikasi m-Banking Anda.</li>
                            <li>Pilih menu <b>Transfer / Pembayaran &rsaquo; Virtual Account</b>.</li>
                            <li>Masukkan nomor VA di atas, lalu klik <b>Lanjutkan</b>.</li>
                            <li>Cek nama & nominal sudah sesuai, lalu masukkan PIN untuk menyelesaikan.</li>
                        </ol>
                    </div>
                @endif

            </div>

            <div class="md:col-span-5 bg-white border border-[#CDC5BC]/40 rounded-2xl p-6 shadow-sm space-y-4 h-fit">
                <h3 class="font-display font-bold text-base text-black border-b border-[#CDC5BC]/20 pb-2">Rincian
                    Pembayaran</h3>

                <div class="space-y-2 text-xs">
                    <div class="flex justify-between text-[#4B463F]">
                        <span>Harga per vote</span>
                        <span>Rp {{ number_format($voting->harga_per_vote) }}</span>
                    </div>
                    <div class="flex justify-between text-[#4B463F]">
                        <span>Jumlah vote</span>
                        <span>{{ $voting->jumlah_vote }} vote</span>
                    </div>
                    <hr class="border-[#CDC5BC]/20 my-2">
                    <div class="flex justify-between font-bold text-sm text-black">
                        <span>Total Tagihan</span>
                        <span class="text-[#C28E27] font-display text-lg">Rp
                            {{ number_format($voting->total_bayar) }}</span>
                    </div>
                </div>

                <div class="space-y-2 pt-4">
                    <button id="btn-cek-status" onclick="cekStatusPembayaran()"
                        class="w-full bg-[#C28E27] hover:bg-[#a97b1f] text-white font-semibold py-3 rounded-xl text-xs transition shadow-md">
                        Saya Sudah Bayar (Cek Status)
                    </button>

                    <a href="/peserta/{{ $peserta->id }}"
                        class="block w-full text-center border border-[#CDC5BC] hover:bg-gray-50 text-[#4B463F] font-semibold py-2.5 rounded-xl text-xs transition">
                        Batal
                    </a>
                </div>
            </div>

        </div>

    </main>

    <!-- Modal Hasil Cek Status -->
    <div id="modal-status" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-6 max-w-sm w-full text-center space-y-3">
            <div id="modal-icon" class="w-14 h-14 rounded-full mx-auto flex items-center justify-center text-2xl"></div>
            <h3 id="modal-judul" class="font-display font-bold text-lg text-black"></h3>
            <p id="modal-pesan" class="text-sm text-[#4B463F]"></p>
            <button id="modal-btn" onclick="tutupModalStatus()"
                class="w-full bg-[#C28E27] text-white font-semibold py-2.5 rounded-xl text-sm hover:bg-[#a97b1f] transition">
                Tutup
            </button>
        </div>
    </div>

        <script>
        function salinVA() {
            const va = document.getElementById('va-num').innerText;
            navigator.clipboard.writeText(va.replace(/\s+/g, ''));
            tampilkanModal('info', 'Tersalin', 'Nomor Virtual Account berhasil disalin.');
        }

        function tampilkanModal(jenis, judul, pesan, redirectUrl = null) {
            const ikon = document.getElementById('modal-icon');
            const btn = document.getElementById('modal-btn');

            if (jenis === 'sukses') {
                ikon.className = 'w-14 h-14 rounded-full mx-auto flex items-center justify-center text-2xl bg-green-100 text-green-600';
                ikon.textContent = '✓';
            } else if (jenis === 'pending') {
                ikon.className = 'w-14 h-14 rounded-full mx-auto flex items-center justify-center text-2xl bg-[#FAF3E7] text-[#C28E27]';
                ikon.textContent = '⏳';
            } else {
                ikon.className = 'w-14 h-14 rounded-full mx-auto flex items-center justify-center text-2xl bg-[#FAF3E7] text-[#7F766C]';
                ikon.textContent = 'i';
            }

            document.getElementById('modal-judul').textContent = judul;
            document.getElementById('modal-pesan').textContent = pesan;
            document.getElementById('modal-status').classList.remove('hidden');

            if (redirectUrl) {
                btn.textContent = 'Lihat Halaman Kandidat';
                btn.onclick = () => window.location.href = redirectUrl;
            } else {
                btn.textContent = 'Tutup';
                btn.onclick = tutupModalStatus;
            }
        }

        function tutupModalStatus() {
            document.getElementById('modal-status').classList.add('hidden');
        }

        async function cekStatusPembayaran() {
            const btn = document.getElementById('btn-cek-status');
            btn.disabled = true;
            btn.textContent = 'Mengecek...';

            try {
                const res = await fetch(`/voting/{{ $voting->midtrans_order_id }}/status`);
                const data = await res.json();

                if (data.status_midtrans === 'settlement') {
                    tampilkanModal('sukses', 'Pembayaran Berhasil', 'Terima kasih sudah memberikan dukungan vote.', data.redirect_url);
                } else {
                    tampilkanModal('pending', 'Belum Kami Terima', 'Pembayaran belum kami terima. Silakan selesaikan transfer/pembayaran terlebih dahulu.');
                }
            } catch (err) {
                tampilkanModal('pending', 'Gagal Mengecek', 'Terjadi kendala saat mengecek status. Coba lagi sebentar lagi.');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Saya Sudah Bayar (Cek Status)';
            }
        }

        // Timer dihitung dari waktu kedaluwarsa asli (expired_at)
        let sisaDetik = {{ $voting->expired_at ? max(0, $voting->expired_at->timestamp - now()->timestamp) : 0 }};

        function tampilkanTimer() {
            const el = document.getElementById('timer');

            if (sisaDetik <= 0) {
                el.innerText = 'Kedaluwarsa';
                return; 
            }

            const jam = Math.floor(sisaDetik / 3600);
            const menit = String(Math.floor((sisaDetik % 3600) / 60)).padStart(2, '0');
            const detik = String(sisaDetik % 60).padStart(2, '0');

            el.innerText = jam > 0 ? `${jam}:${menit}:${detik}` : `${menit}:${detik}`;
        }

        tampilkanTimer();
        setInterval(() => {
            if (sisaDetik > 0) {
                sisaDetik--;
                tampilkanTimer();
            }
        }, 1000);
    </script>
</body>

</html>
