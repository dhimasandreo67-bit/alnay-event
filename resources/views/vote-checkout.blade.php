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

        /* ============================================ */
        /* ANIMASI ENTRANCE — STAGGER                    */
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
        /* CARD PREMIUM                                  */
        /* ============================================ */
        .card-premium {
            transition: all .35s cubic-bezier(.22, 1, .36, 1);
        }

        .card-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 40px -20px rgba(194, 142, 39, .15);
            border-color: #E6D3A7;
        }

        /* ============================================ */
        /* VOTE PRESET CHIP                              */
        /* ============================================ */
        .vote-chip {
            position: relative;
            overflow: hidden;
            transition: all .25s cubic-bezier(.22, 1, .36, 1);
        }

        .vote-chip:hover {
            transform: translateY(-2px);
            border-color: #C28E27;
            background: #FAF3E7;
            box-shadow: 0 6px 15px -8px rgba(194, 142, 39, .4);
        }

        .vote-chip.active {
            background: linear-gradient(135deg, #C28E27, #A97B1F);
            color: white;
            border-color: #A97B1F;
            box-shadow: 0 8px 18px -6px rgba(194, 142, 39, .55);
        }

        .vote-chip.active:hover {
            background: linear-gradient(135deg, #B8821F, #96681A);
        }

        /* ============================================ */
        /* RADIO CUSTOM — METODE BAYAR                   */
        /* ============================================ */
        .pay-option {
            position: relative;
            cursor: pointer;
            transition: all .25s cubic-bezier(.22, 1, .36, 1);
        }

        .pay-option:hover {
            transform: translateY(-2px);
            border-color: #C28E27;
            background: #FFFCF5;
            box-shadow: 0 10px 22px -14px rgba(194, 142, 39, .35);
        }

        .pay-option input[type="radio"] {
            appearance: none;
            -webkit-appearance: none;
            width: 18px;
            height: 18px;
            border: 2px solid #CDC5BC;
            border-radius: 50%;
            position: relative;
            cursor: pointer;
            transition: all .2s ease;
            flex-shrink: 0;
        }

        .pay-option input[type="radio"]:checked {
            border-color: #C28E27;
            background: #C28E27;
            box-shadow: 0 0 0 4px rgba(194, 142, 39, .18);
        }

        .pay-option:has(input[type="radio"]:checked) {
            border-color: #C28E27;
            background: #FFFCF5;
            box-shadow: 0 0 0 3px rgba(194, 142, 39, .1);
        }

        /* ============================================ */
        /* TOTAL PULSE                                   */
        /* ============================================ */
        .total-pulse {
            animation: totalPulse 2.5s ease-in-out infinite;
        }

        @keyframes totalPulse {
            0%, 100% { text-shadow: 0 0 0 rgba(194, 142, 39, 0); }
            50% { text-shadow: 0 0 14px rgba(194, 142, 39, .3); }
        }

        /* ============================================ */
        /* BTN SUBMIT — SHIMMER + PULSE RING             */
        /* ============================================ */
        .btn-submit {
            position: relative;
            overflow: hidden;
            transition: all .3s cubic-bezier(.22, 1, .36, 1);
        }

        .btn-submit::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg,
                    transparent,
                    rgba(255, 255, 255, .28),
                    transparent);
            transition: left .7s ease;
        }

        .btn-submit:hover::before {
            left: 100%;
        }

        .btn-submit:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 14px 28px -8px rgba(194, 142, 39, .55);
        }

        /* ============================================ */
        /* INPUT FOCUS GLOW                              */
        /* ============================================ */
        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #C28E27 !important;
            box-shadow: 0 0 0 4px rgba(194, 142, 39, .1);
        }

        /* ============================================ */
        /* STICKY COLUMN — DESKTOP                       */
        /* ============================================ */
        @media (min-width: 768px) {
            .sticky-col {
                position: sticky;
                top: 90px;
            }
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

<body class="min-h-screen bg-[#FFF8F1] text-[#1E1B18]">

    @include('partials.header')

    <main class="max-w-5xl mx-auto px-5 md:px-6 py-6 md:py-8">

        {{-- Breadcrumb / Back --}}
        <a href="javascript:history.back()"
            class="reveal-up inline-flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[.12em] text-[#7F766C] hover:text-[#C28E27] transition">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Batalkan / Kembali
        </a>

        <div class="mt-5 grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-7">

            {{-- ============================================ --}}
            {{-- KOLOM KIRI — KANDIDAT PILIHAN               --}}
            {{-- ============================================ --}}
            <div class="reveal-up delay-1 md:col-span-1 sticky-col">
                <div class="card-premium bg-white border border-[#CDC5BC]/40 rounded-2xl p-5 shadow-sm">

                    <div class="flex items-center gap-2 mb-4 pb-3 border-b border-[#CDC5BC]/30">
                        <svg class="w-4 h-4 text-[#C28E27]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <h2 class="font-display font-bold text-sm text-[#1E1B18]">Kandidat Pilihan</h2>
                    </div>

                    {{-- Foto --}}
                    <div class="relative aspect-[4/5] bg-[#EFE7DC] rounded-xl overflow-hidden mb-4 shadow-md">
                        <img id="peserta-foto" src="" alt="Foto Peserta" class="w-full h-full object-cover hidden">
                        <div id="peserta-foto-skeleton" class="w-full h-full animate-pulse bg-gradient-to-br from-[#EFE7DC] to-[#E5DBC8]"></div>

                        {{-- Gradient overlay --}}
                        <div class="absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-black/30 to-transparent pointer-events-none"></div>
                    </div>

                    <h3 id="peserta-nama" class="font-display font-bold text-lg text-[#1E1B18] leading-tight">Memuat...</h3>
                    <p id="event-nama" class="mt-1 inline-flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-[.15em] text-[#C28E27]">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#C28E27]"></span>
                        <span id="event-nama-text">-</span>
                    </p>

                    <hr class="my-4 border-dashed border-[#CDC5BC]/40">

                    <div class="flex justify-between items-center text-xs">
                        <span class="text-[#7F766C] font-semibold">Harga per Vote</span>
                        <span id="harga-label" class="font-display font-bold text-base text-[#1E1B18]">Rp 0</span>
                    </div>
                </div>
            </div>

            {{-- ============================================ --}}
            {{-- KOLOM KANAN — FORMULIR DUKUNGAN             --}}
            {{-- ============================================ --}}
            <div class="reveal-up delay-2 md:col-span-2">
                <div class="card-premium bg-white border border-[#CDC5BC]/40 rounded-2xl p-6 md:p-7 shadow-sm">

                    <div class="flex items-center gap-2.5 mb-6 pb-4 border-b border-[#CDC5BC]/30">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#FAF3E7] to-[#F5E9CE] flex items-center justify-center ring-1 ring-[#C28E27]/20">
                            <svg class="w-4 h-4 text-[#C28E27]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="font-display font-bold text-xl text-[#1E1B18]">Formulir Dukungan</h1>
                            <p class="text-[11px] text-[#7F766C] mt-0.5">Isi data di bawah untuk melanjutkan pembayaran</p>
                        </div>
                    </div>

                    <form id="form-vote" onsubmit="prosesPembayaran(event)" class="space-y-5">

                        {{-- Nama Pemilih --}}
                        <div>
                            <label for="input-nama" class="block text-xs font-bold uppercase tracking-wider text-[#4B463F] mb-2">
                                Nama Pemilih <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="input-nama" required value="seseorang"
                                placeholder="Masukkan nama Anda"
                                class="w-full border border-[#CDC5BC] rounded-xl px-4 py-3 text-sm bg-white transition-all">
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="input-email" class="block text-xs font-bold uppercase tracking-wider text-[#4B463F] mb-2">
                                Email Anda <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="input-email" required
                                value="{{ auth('pelanggan')->user()->email ?? '' }}" placeholder="contoh@email.com"
                                class="w-full border border-[#CDC5BC] rounded-xl px-4 py-3 text-sm bg-white transition-all">
                            <p class="mt-1.5 flex items-start gap-1.5 text-[11px] text-[#7F766C]">
                                <svg class="w-3 h-3 shrink-0 mt-0.5 text-[#C28E27]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Bukti transaksi dan invoice akan dikirim ke email ini.
                            </p>
                        </div>

                        {{-- Jumlah Vote --}}
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-[#4B463F] mb-2">
                                Jumlah Vote <span class="text-red-500">*</span>
                            </label>

                            <div class="grid grid-cols-4 gap-2 mb-3">
                                <button type="button" onclick="setVote(5, this)" data-vote="5"
                                    class="vote-chip border border-[#CDC5BC] rounded-xl py-2.5 text-xs font-bold text-[#4B463F]">5</button>
                                <button type="button" onclick="setVote(10, this)" data-vote="10"
                                    class="vote-chip border border-[#CDC5BC] rounded-xl py-2.5 text-xs font-bold text-[#4B463F]">10</button>
                                <button type="button" onclick="setVote(25, this)" data-vote="25"
                                    class="vote-chip border border-[#CDC5BC] rounded-xl py-2.5 text-xs font-bold text-[#4B463F]">25</button>
                                <button type="button" onclick="setVote(50, this)" data-vote="50"
                                    class="vote-chip border border-[#CDC5BC] rounded-xl py-2.5 text-xs font-bold text-[#4B463F]">50</button>
                            </div>

                            <input type="number" id="input-jumlah-vote" value="1" min="1" required
                                placeholder="Atau ketik jumlah manual..."
                                class="w-full border border-[#CDC5BC] rounded-xl px-4 py-3 text-sm bg-white transition-all">
                        </div>

                        {{-- Pesan Dukungan --}}
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label for="input-pesan-dukungan" class="text-xs font-bold uppercase tracking-wider text-[#4B463F]">
                                    Pesan Dukungan <span class="text-[#7F766C] font-normal normal-case tracking-normal">(Opsional)</span>
                                </label>
                                <span id="counter-pesan" class="text-[10px] text-[#7F766C] tabular-nums">0/1000</span>
                            </div>
                            <textarea id="input-pesan-dukungan" rows="3" maxlength="1000"
                                placeholder="Tuliskan kata-kata semangat untuk kandidat..."
                                class="w-full border border-[#CDC5BC] rounded-xl px-4 py-3 text-sm bg-white transition-all resize-none"></textarea>
                        </div>

                        {{-- Metode Pembayaran --}}
                        <div class="space-y-4 pt-2">
                            <div class="flex items-center gap-2 pb-2 border-b border-[#CDC5BC]/30">
                                <svg class="w-4 h-4 text-[#C28E27]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                <label class="text-xs font-bold uppercase tracking-wider text-[#4B463F]">
                                    Metode Pembayaran
                                </label>
                            </div>

                            {{-- QRIS --}}
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-[.15em] text-[#7F766C] mb-2.5">
                                    Pembayaran Instant (Scan QR)
                                </p>
                                <label class="pay-option block border border-[#CDC5BC]/60 rounded-xl p-4 flex items-start gap-3 bg-white">
                                    <input type="radio" name="payment_type" value="qris" checked>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-3.5 h-3.5 text-[#C28E27]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <rect x="3" y="3" width="7" height="7" rx="1"/>
                                                <rect x="14" y="3" width="7" height="7" rx="1"/>
                                                <rect x="3" y="14" width="7" height="7" rx="1"/>
                                                <path d="M14 14h3v3M21 14v3M14 21h3M21 21v-3"/>
                                            </svg>
                                            <p class="text-xs font-bold text-black">QRIS</p>
                                            <span class="rounded-full bg-[#FAF3E7] px-2 py-0.5 text-[9px] font-bold text-[#7F5700] uppercase tracking-wider">Populer</span>
                                        </div>
                                        <p class="text-[10px] text-[#7F766C] mt-1">Semua E-Wallet & M-Banking — BCA, GoPay, OVO, ShopeePay, Dana, LinkAja, Mandiri</p>
                                    </div>
                                </label>
                            </div>

                            {{-- BRI VA --}}
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-[.15em] text-[#7F766C] mb-2.5">
                                    Virtual Account (Transfer Bank)
                                </p>
                                <label class="pay-option block border border-[#CDC5BC]/60 rounded-xl p-4 flex items-start gap-3 bg-white">
                                    <input type="radio" name="payment_type" value="bri_va">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-3.5 h-3.5 text-[#C28E27]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <rect x="2" y="6" width="20" height="12" rx="2"/>
                                                <path d="M2 10h20"/>
                                            </svg>
                                            <p class="text-xs font-bold text-black">BRI Virtual Account (BRIVA)</p>
                                        </div>
                                        <p class="text-[10px] text-[#7F766C] mt-1">Via BRImo atau ATM BRI</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Total --}}
                        <div class="bg-gradient-to-br from-[#FAF3E7] to-[#F5E9CE]/60 border border-[#CDC5BC]/40 p-4 rounded-xl flex items-center justify-between mt-6">
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-[#7F766C] block">Total Pembayaran</span>
                                <span class="text-[10px] text-[#7F766C]">Belum termasuk biaya admin</span>
                            </div>
                            <span id="total-pembayaran" class="total-pulse font-display font-bold text-2xl text-[#7F5700] tabular-nums">Rp 0</span>
                        </div>

                        {{-- Error --}}
                        <p id="pesan-error" class="hidden text-xs text-red-700 bg-red-50 border border-red-200 rounded-xl p-3"></p>

                        {{-- Submit --}}
                        <button type="submit" id="btn-bayar"
                            class="btn-submit w-full bg-gradient-to-r from-[#C28E27] to-[#A97B1F] text-white rounded-xl py-3.5 text-sm font-bold tracking-wide shadow-md">
                            <span class="relative z-10 flex items-center justify-center gap-2">
                                Lanjut ke Pembayaran
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                </svg>
                            </span>
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </main>

    <script>
        const pesertaId = {{ $pesertaId }};
        let hargaPerVote = 0;

        function formatAngka(n) {
            return new Intl.NumberFormat('id-ID').format(n);
        }

        // Set vote + auto highlight chip
        function setVote(jumlah, btn) {
            document.getElementById('input-jumlah-vote').value = jumlah;
            document.querySelectorAll('.vote-chip').forEach(el => el.classList.remove('active'));
            if (btn) btn.classList.add('active');
            hitungTotal();
        }

        // Sync active chip saat input manual
        function syncActiveChip() {
            const val = parseInt(document.getElementById('input-jumlah-vote').value) || 0;
            document.querySelectorAll('.vote-chip').forEach(el => {
                const chipVal = parseInt(el.dataset.vote);
                el.classList.toggle('active', chipVal === val);
            });
        }

        // Counter pesan
        function updateCounter() {
            const textarea = document.getElementById('input-pesan-dukungan');
            const counter = document.getElementById('counter-pesan');
            const len = textarea.value.length;
            const max = textarea.maxLength;
            counter.textContent = `${len}/${max}`;
            counter.classList.toggle('text-red-500', len >= max * 0.9);
            counter.classList.toggle('text-[#7F766C]', len < max * 0.9);
        }

        // Muat data peserta
        async function muatPeserta() {
            try {
                const res = await fetch(`/api/peserta/${pesertaId}`, {
                    credentials: 'include'
                });
                if (!res.ok) throw new Error('Gagal mengambil data peserta');

                const p = await res.json();
                hargaPerVote = p.harga_per_vote ?? 0;

                // Nama
                document.getElementById('peserta-nama').textContent = p.nama ?? 'Peserta';
                const eventText = p.event?.nama ?? p.kategori ?? 'Event Voting';
                document.getElementById('event-nama-text').textContent = eventText;
                document.getElementById('harga-label').textContent = `Rp ${formatAngka(hargaPerVote)}`;

                // Foto — prioritas foto kandidat dari API
                const img = document.getElementById('peserta-foto');
                const fotoUrl = p.foto ??
                    `https://ui-avatars.com/api/?size=512&background=C28E27&color=FFF8F1&name=${encodeURIComponent(p.nama ?? '?')}`;
                img.src = fotoUrl;

                img.onload = () => {
                    img.classList.remove('hidden');
                    document.getElementById('peserta-foto-skeleton').classList.add('hidden');
                };

                hitungTotal();
            } catch (err) {
                console.error(err);
                if (typeof swalAlert === 'function') {
                    swalAlert('Gagal memuat detail peserta.', 'error');
                }
            }
        }

        // Hitung total
        function hitungTotal() {
            const qty = parseInt(document.getElementById('input-jumlah-vote').value) || 0;
            const total = qty * hargaPerVote;
            document.getElementById('total-pembayaran').textContent = `Rp ${formatAngka(total)}`;
        }

        // Event listeners
        document.getElementById('input-jumlah-vote').addEventListener('input', () => {
            hitungTotal();
            syncActiveChip();
        });

        document.getElementById('input-pesan-dukungan').addEventListener('input', updateCounter);

        // Proses pembayaran
        async function prosesPembayaran(e) {
            e.preventDefault();
            const btn = document.getElementById('btn-bayar');
            const pesanError = document.getElementById('pesan-error');

            btn.disabled = true;
            btn.innerHTML = '<span class="relative z-10">Memproses Transaksi...</span>';
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

                if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    throw new Error('URL redirect tidak ditemukan.');
                }

            } catch (err) {
                pesanError.textContent = err.message;
                pesanError.classList.remove('hidden');
                btn.disabled = false;
                btn.innerHTML = `
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        Lanjut ke Pembayaran
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                        </svg>
                    </span>`;
            }
        }

        // Boot
        muatPeserta();
        updateCounter();
    </script>

    @include('partials.swal')

</body>

</html>