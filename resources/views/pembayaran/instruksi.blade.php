<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instruksi Pembayaran - ALNAY.ID</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }

        .card-shadow {
            box-shadow: 0 4px 24px -8px rgba(21, 35, 61, 0.08),
                        0 2px 8px -4px rgba(21, 35, 61, 0.04);
        }

        .qr-frame {
            position: relative;
            background: #fff;
            border-radius: 16px;
            padding: 16px;
            border: 2px solid #c99a32;
            box-shadow: 0 8px 32px -8px rgba(201, 154, 50, 0.25);
        }

        .qr-frame::before {
            content: '';
            position: absolute;
            inset: -6px;
            border-radius: 20px;
            background: linear-gradient(135deg, #c99a32 0%, #e0b254 50%, #c99a32 100%);
            z-index: -1;
            opacity: 0.15;
        }

        .pulse-ring {
            animation: pulseRing 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulseRing {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        .shimmer {
            background: linear-gradient(90deg, #f0ece2 0%, #fdf8ec 50%, #f0ece2 100%);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
    </style>
</head>
<body class="bg-[#faf9f6] text-[#15233d] min-h-screen">

    {{-- ============ HEADER ============ --}}
    <header class="sticky top-0 z-40 border-b border-[#e9e9e9] bg-white/95 backdrop-blur">
        <div class="mx-auto flex h-[64px] max-w-3xl items-center justify-between px-5">

            {{-- Back button --}}
            <a href="{{ route('dashboard-user') }}"
                class="flex items-center gap-2 text-[13px] font-semibold text-[#6b6b6b] hover:text-[#c99a32] transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>

            {{-- Brand --}}
            <a href="{{ route('dashboard-user') }}" class="font-display text-[15px] font-bold tracking-tight text-[#13223a]">
                EVENT <span class="text-[#c99a32]">ALNAY.ID</span>
            </a>

            {{-- Spacer --}}
            <div class="w-[60px]"></div>
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-5 pt-6 pb-16">

        {{-- ============ JUDUL ============ --}}
        <div class="text-center mb-6">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#c99a32]/10 border border-[#c99a32]/20 text-[10px] font-bold uppercase tracking-[0.15em] text-[#a37b2a] mb-3">
                <span class="w-1.5 h-1.5 rounded-full bg-[#c99a32]"></span>
                Instruksi Pembayaran
            </div>
            <h1 class="font-display text-2xl md:text-3xl font-bold text-[#111827]">
                Selesaikan Pembayaran
            </h1>
            <p class="text-gray-500 text-xs md:text-sm mt-1.5">
                Ikuti instruksi di bawah sesuai metode yang kamu pilih
            </p>
        </div>

        {{-- ============ COUNTDOWN ============ --}}
        <div class="mb-5 rounded-2xl border border-amber-200 bg-gradient-to-br from-amber-50 to-amber-100/50 px-4 py-3.5">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-amber-200/60">
                        <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                            <circle cx="12" cy="12" r="10"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-amber-700">Batas Waktu Pembayaran</p>
                        <p class="text-[11px] text-amber-600">Selesaikan sebelum waktu habis</p>
                    </div>
                </div>
                <div class="text-right">
                    <p id="countdown" class="font-mono text-lg font-bold text-amber-800 tabular-nums">--:--:--</p>
                </div>
            </div>
        </div>

        {{-- ============ CARD UTAMA ============ --}}
        <div class="card-shadow bg-white rounded-2xl border border-[#ece5d7] overflow-hidden">

            {{-- Gold top line --}}
            <div class="h-1 w-full bg-gradient-to-r from-transparent via-[#c99a32] to-transparent"></div>

            <div class="p-6 sm:p-8">

                @if ($pembayaran->payment_method === 'qris')
                    {{-- ============ QRIS ============ --}}
                    <div class="text-center">

                        {{-- Badge metode --}}
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#fdf8ec] border border-[#f0e8d8] text-[10px] font-bold uppercase tracking-wider text-[#a37b2a] mb-5">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <rect x="3" y="3" width="7" height="7"/>
                                <rect x="14" y="3" width="7" height="7"/>
                                <rect x="3" y="14" width="7" height="7"/>
                                <rect x="14" y="14" width="7" height="7"/>
                            </svg>
                            QRIS
                        </div>

                        <h2 class="font-display text-xl font-bold text-[#0a1526] mb-1">Scan QR Code</h2>
                        <p class="text-[12px] text-[#8a8178] mb-6">
                            Buka aplikasi bank / e-wallet favoritmu, lalu scan QR di bawah
                        </p>

                        {{-- QR Code --}}
                        @if ($pembayaran->qr_url)
                            <div class="inline-block qr-frame mb-6">
                                <img src="{{ $pembayaran->qr_url }}" alt="QR Code"
                                    class="w-56 h-56 sm:w-64 sm:h-64 object-contain">
                            </div>
                        @else
                            <div class="inline-flex flex-col items-center justify-center w-64 h-64 rounded-2xl border-2 border-dashed border-rose-200 bg-rose-50 mb-6">
                                <svg class="w-10 h-10 text-rose-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <circle cx="12" cy="12" r="10"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/>
                                </svg>
                                <p class="text-rose-600 text-sm font-semibold">QR tidak tersedia</p>
                                <p class="text-rose-400 text-xs mt-1">Hubungi admin</p>
                            </div>
                        @endif

                        {{-- Total bayar --}}
                        <div class="bg-[#faf8f2] border border-[#ece5d7] rounded-xl px-5 py-4 mb-5 max-w-xs mx-auto">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-[#8a8178] mb-1">Total Bayar</p>
                            <p class="font-display text-2xl font-bold text-[#0a1526]">
                                Rp{{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                            </p>
                        </div>

                        {{-- Order ID --}}
                        <div class="flex items-center justify-center gap-2 text-[11px] text-[#8a8178]">
                            <span>Order ID:</span>
                            <span class="font-mono text-[#4b463f]">{{ $pembayaran->midtrans_order_id }}</span>
                        </div>

                    </div>

                @elseif ($pembayaran->payment_method === 'bri_va')
                    {{-- ============ VA BRI ============ --}}
                    <div>

                        {{-- Badge metode --}}
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 border border-blue-200 text-[10px] font-bold uppercase tracking-wider text-blue-700 mb-5">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <rect x="2" y="6" width="20" height="12" rx="2"/>
                                <path d="M2 10h20"/>
                            </svg>
                            Virtual Account BRI
                        </div>

                        <h2 class="font-display text-xl font-bold text-[#0a1526] mb-1">Nomor Virtual Account</h2>
                        <p class="text-[12px] text-[#8a8178] mb-5">
                            Transfer ke nomor VA di bawah dari aplikasi BRImo atau ATM BRI
                        </p>

                        {{-- Nomor VA --}}
                        <div class="bg-gradient-to-br from-[#faf8f2] to-[#fdfcf9] border border-[#ece5d7] rounded-xl p-5 text-center mb-5">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-[#8a8178] mb-2">Nomor VA</p>
                            <p class="font-mono text-2xl font-bold text-[#0a1526] tracking-wider mb-3">
                                {{ $pembayaran->va_number ?? '-' }}
                            </p>

                            <button onclick="copyVA('{{ $pembayaran->va_number }}')"
                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-[#0a1526] hover:bg-[#1a2942] text-[11px] font-semibold text-white transition shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <rect x="9" y="9" width="13" height="13" rx="2"/>
                                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                                </svg>
                                Salin Nomor VA
                            </button>
                        </div>

                        {{-- Detail --}}
                        <div class="bg-[#faf8f2] border border-[#ece5d7] rounded-xl divide-y divide-[#ece5d7] mb-5">
                            <div class="flex items-center justify-between px-4 py-3">
                                <span class="text-[12px] text-[#8a8178]">Atas Nama</span>
                                <span class="text-[12px] font-semibold text-[#0a1526]">ALNAY.ID</span>
                            </div>
                            <div class="flex items-center justify-between px-4 py-3">
                                <span class="text-[12px] text-[#8a8178]">Total Bayar</span>
                                <span class="font-display text-base font-bold text-[#0a1526]">
                                    Rp{{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        {{-- Cara bayar --}}
                        <div class="rounded-xl bg-[#fdf8ec] border border-[#f0e8d8] p-4 mb-5">
                            <p class="text-[11px] font-bold text-[#a37b2a] uppercase tracking-wider mb-3">Cara Bayar</p>
                            <ol class="space-y-2 text-[12px] text-[#4b463f]">
                                <li class="flex items-start gap-2.5">
                                    <span class="flex-shrink-0 w-5 h-5 rounded-full bg-[#c99a32] text-white text-[10px] font-bold flex items-center justify-center mt-0.5">1</span>
                                    <span>Buka aplikasi <strong>BRImo</strong> atau ATM BRI</span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <span class="flex-shrink-0 w-5 h-5 rounded-full bg-[#c99a32] text-white text-[10px] font-bold flex items-center justify-center mt-0.5">2</span>
                                    <span>Pilih menu <strong>Transfer → Virtual Account</strong></span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <span class="flex-shrink-0 w-5 h-5 rounded-full bg-[#c99a32] text-white text-[10px] font-bold flex items-center justify-center mt-0.5">3</span>
                                    <span>Masukkan nomor VA di atas</span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <span class="flex-shrink-0 w-5 h-5 rounded-full bg-[#c99a32] text-white text-[10px] font-bold flex items-center justify-center mt-0.5">4</span>
                                    <span>Konfirmasi nominal & selesaikan pembayaran</span>
                                </li>
                            </ol>
                        </div>

                        {{-- Order ID --}}
                        <div class="flex items-center justify-center gap-2 text-[11px] text-[#8a8178]">
                            <span>Order ID:</span>
                            <span class="font-mono text-[#4b463f]">{{ $pembayaran->midtrans_order_id }}</span>
                        </div>

                    </div>
                @endif
            </div>

            {{-- ============ STATUS POLLING ============ --}}
            <div class="border-t border-[#f0ece2] bg-[#fdfcf9] px-6 py-4">
                <div id="status-text" class="flex items-center justify-center gap-2.5 text-[12px] text-[#8a8178]">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="pulse-ring absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span>
                    </span>
                    <span>Menunggu pembayaran...</span>
                </div>
            </div>
        </div>

        {{-- ============ AKSI BAWAH ============ --}}
        <div class="mt-6 flex flex-col sm:flex-row items-center justify-center gap-3">

            <button type="button" onclick="cekStatusManual()"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-[#e5e1db] bg-white text-[12px] font-semibold text-[#4b463f] hover:border-[#c99a32] hover:text-[#c99a32] transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Cek Status Pembayaran
            </button>

            <a href="{{ route('profil-user') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-[12px] font-semibold text-[#a37b2a] hover:text-[#c99a32] transition">
                Bayar Nanti
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

    </main>

    <script>
        // ============================================
        // COUNTDOWN 2 JAM
        // ============================================
        (function() {
            const el = document.getElementById('countdown');
            if (!el) return;
            const target = new Date('{{ now()->addHours(2)->toIso8601String() }}').getTime();

            function tick() {
                const diff = Math.max(0, target - Date.now());
                const h = String(Math.floor(diff / 3600000)).padStart(2, '0');
                const m = String(Math.floor(diff % 3600000 / 60000)).padStart(2, '0');
                const s = String(Math.floor(diff % 60000 / 1000)).padStart(2, '0');
                el.textContent = `${h}:${m}:${s}`;
                if (diff > 0) requestAnimationFrame(tick);
            }
            tick();
        })();

        // ============================================
        // COPY VA
        // ============================================
        function copyVA(va) {
            navigator.clipboard.writeText(va).then(() => {
                if (typeof toast === 'function') {
                    toast('Nomor VA berhasil disalin!', 'success');
                } else {
                    alert('Nomor VA berhasil disalin!');
                }
            }).catch(() => {
                alert('Gagal menyalin. Salin manual: ' + va);
            });
        }

        // ============================================
        // POLLING STATUS PEMBAYARAN
        // ============================================
        (function() {
            const statusEl = document.getElementById('status-text');
            const statusUrl = '{{ route("peserta.pembayaran.status", $peserta->id) }}';
            const profilUrl = '{{ route("profil-user") }}';
            const pembayaranUrl = '{{ route("peserta.pembayaran", $peserta->id) }}';

            let statusLock = false;
            let intervalId = null;

            function renderPending() {
                statusEl.innerHTML =
                    '<span class="relative flex h-2.5 w-2.5">' +
                        '<span class="pulse-ring absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>' +
                        '<span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span>' +
                    '</span>' +
                    '<span>Menunggu pembayaran...</span>';
                statusEl.className = 'flex items-center justify-center gap-2.5 text-[12px] text-[#8a8178]';
            }

            function renderSuccess(data) {
                statusEl.innerHTML =
                    '<span class="flex h-2.5 w-2.5 rounded-full bg-emerald-500"></span>' +
                    '<strong class="text-emerald-700">Pembayaran Berhasil!</strong>';
                statusEl.className = 'flex items-center justify-center gap-2.5 text-[12px]';

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Pembayaran Berhasil!',
                        html: 'Terima kasih, pembayaran kamu sudah kami terima.' +
                              '<br><span class="text-xs text-gray-500">Order: ' + (data.order_id || '-') + '</span>',
                        confirmButtonText: 'Lihat Profil',
                        confirmButtonColor: '#c99a32',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                    }).then(() => {
                        window.location.href = profilUrl;
                    });
                } else {
                    alert('Pembayaran Berhasil! Mengalihkan...');
                    setTimeout(() => window.location.href = profilUrl, 1500);
                }
            }

            function renderFailed(data) {
                const isExpired = data.expired;
                statusEl.innerHTML =
                    '<span class="flex h-2.5 w-2.5 rounded-full bg-rose-500"></span>' +
                    '<strong class="text-rose-700">' +
                    (isExpired ? 'Pembayaran Kadaluarsa' : 'Pembayaran Gagal') +
                    '</strong>';
                statusEl.className = 'flex items-center justify-center gap-2.5 text-[12px]';

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: isExpired ? 'Pembayaran Kadaluarsa' : 'Pembayaran Gagal',
                        text: isExpired
                            ? 'Batas waktu pembayaran sudah habis. Silakan buat QR baru.'
                            : 'Pembayaran gagal atau dibatalkan. Silakan coba lagi.',
                        confirmButtonText: 'Coba Lagi',
                        confirmButtonColor: '#c99a32',
                        allowOutsideClick: false,
                    }).then(() => {
                        window.location.href = pembayaranUrl;
                    });
                } else {
                    alert(isExpired ? 'Pembayaran Kadaluarsa' : 'Pembayaran Gagal');
                    window.location.href = pembayaranUrl;
                }
            }

            async function cekStatus() {
                if (statusLock) return;

                try {
                    const res = await fetch(statusUrl, {
                        credentials: 'same-origin',
                        headers: { 'Accept': 'application/json' },
                    });
                    const data = await res.json();

                    if (data.lunas) {
                        statusLock = true;
                        if (intervalId) clearInterval(intervalId);
                        renderSuccess(data);
                    } else if (data.gagal || data.expired) {
                        statusLock = true;
                        if (intervalId) clearInterval(intervalId);
                        renderFailed(data);
                    } else {
                        renderPending();
                    }
                } catch (e) {
                    // diamkan, coba lagi nanti
                }
            }

            // Expose untuk tombol "Cek Status Manual"
            window.cekStatusManual = async function() {
                statusEl.innerHTML =
                    '<span class="inline-block w-2.5 h-2.5 rounded-full bg-amber-400 animate-pulse"></span>' +
                    '<span>Memeriksa status...</span>';
                await cekStatus();
            };

            // Cek pertama kali setelah 2 detik
            setTimeout(cekStatus, 2000);

            // Polling tiap 3 detik
            intervalId = setInterval(cekStatus, 3000);
        })();
    </script>

    @include('partials.swal')

</body>
</html>