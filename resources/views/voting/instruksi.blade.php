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

        .reveal-up.delay-1 { animation-delay: .06s; }
        .reveal-up.delay-2 { animation-delay: .12s; }
        .reveal-up.delay-3 { animation-delay: .18s; }
        .reveal-up.delay-4 { animation-delay: .24s; }
        .reveal-up.delay-5 { animation-delay: .30s; }

        /* ============================================ */
        /* PULSE SOFT — STATUS & TIMER                   */
        /* ============================================ */
        .pulse-soft {
            animation: pulseSoft 2.4s ease-in-out infinite;
        }

        @keyframes pulseSoft {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: .6; transform: scale(1.15); }
        }

        /* ============================================ */
        /* FLOAT — ICON TROPHY                           */
        /* ============================================ */
        .float {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-4px); }
        }

        /* ============================================ */
        /* CARD HOVER                                    */
        /* ============================================ */
        .card-premium {
            transition: all .35s cubic-bezier(.22, 1, .36, 1);
        }

        .card-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 40px -20px rgba(194, 142, 39, .18);
            border-color: #E6D3A7;
        }

        /* ============================================ */
        /* QR CONTAINER — GLOW                           */
        /* ============================================ */
        .qr-container {
            position: relative;
            background: white;
            padding: 16px;
            border-radius: 20px;
            border: 1px solid #E8DCC0;
            box-shadow: 0 12px 30px -12px rgba(194, 142, 39, .25);
            animation: qrGlow 4s ease-in-out infinite;
        }

        @keyframes qrGlow {
            0%, 100% {
                box-shadow: 0 12px 30px -12px rgba(194, 142, 39, .25);
            }
            50% {
                box-shadow: 0 18px 40px -12px rgba(194, 142, 39, .4);
            }
        }

        /* Corner ornaments */
        .qr-container::before,
        .qr-container::after {
            content: "";
            position: absolute;
            width: 24px;
            height: 24px;
            border: 2px solid #C28E27;
        }

        .qr-container::before {
            top: -6px;
            left: -6px;
            border-right: none;
            border-bottom: none;
            border-top-left-radius: 10px;
        }

        .qr-container::after {
            bottom: -6px;
            right: -6px;
            border-left: none;
            border-top: none;
            border-bottom-right-radius: 10px;
        }

        /* ============================================ */
        /* TOMBOL CEK STATUS — PULSE RING                */
        /* ============================================ */
        .btn-check {
            position: relative;
            overflow: hidden;
            transition: all .3s cubic-bezier(.22, 1, .36, 1);
        }

        .btn-check::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg,
                    transparent,
                    rgba(255, 255, 255, .25),
                    transparent);
            transform: translateX(-100%);
            transition: transform .6s ease;
        }

        .btn-check:hover::before {
            transform: translateX(100%);
        }

        .btn-check:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px -8px rgba(194, 142, 39, .5);
        }

        .btn-check::after {
            content: "";
            position: absolute;
            inset: -3px;
            border-radius: 14px;
            border: 2px solid #C28E27;
            animation: btnRing 3s ease-out infinite;
        }

        @keyframes btnRing {
            0% { opacity: .6; transform: scale(1); }
            100% { opacity: 0; transform: scale(1.08); }
        }

        /* ============================================ */
        /* TIMER — GLOW PULSE                            */
        /* ============================================ */
        #timer {
            animation: timerGlow 2s ease-in-out infinite;
        }

        @keyframes timerGlow {
            0%, 100% {
                text-shadow: 0 0 0 rgba(194, 142, 39, 0);
            }
            50% {
                text-shadow: 0 0 12px rgba(194, 142, 39, .35);
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

<body class="min-h-screen bg-[#FBF9F4] text-[#1E1B18] pb-12">

    @include('partials.header')

    <main class="max-w-4xl mx-auto px-4 mt-6 space-y-6">

        {{-- Breadcrumb --}}
        <nav class="reveal-up flex flex-wrap items-center gap-2 text-[10px] font-semibold uppercase tracking-[.12em] text-[#7F766C]">
            <span>Beranda</span>
            <span class="text-[#CDC5BC]">›</span>
            <span>{{ $event->nama ?? 'Event' }}</span>
            <span class="text-[#CDC5BC]">›</span>
            <span class="text-[#C28E27]">Pembayaran Voting</span>
        </nav>

        {{-- ============================================ --}}
        {{-- STATUS CARD                                   --}}
        {{-- ============================================ --}}
        <div class="reveal-up delay-1 card-premium bg-white border border-[#CDC5BC]/40 rounded-2xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm">

            <div class="flex items-center gap-3.5">
                <div class="float shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-[#FAF3E7] to-[#F5E9CE] flex items-center justify-center ring-1 ring-[#C28E27]/20">
                    <svg class="w-6 h-6 text-[#C28E27]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 21h8M12 17v4M7 4h10v6a5 5 0 01-10 0V4z"/>
                        <path d="M17 5h2a2 2 0 012 2v1a2 2 0 01-2 2h-2M7 5H5a2 2 0 00-2 2v1a2 2 0 002 2h2"/>
                    </svg>
                </div>
                <div>
                    <span class="inline-flex items-center gap-1.5 text-[10px] font-bold text-[#C28E27] uppercase tracking-[.15em]">
                        <span class="pulse-soft w-1.5 h-1.5 rounded-full bg-[#C28E27]"></span>
                        Menunggu Pembayaran
                    </span>
                    <h2 class="font-display font-bold text-lg text-black mt-0.5">Selesaikan Dukungan Resmi Kamu</h2>
                </div>
            </div>

            <div class="text-right shrink-0 sm:border-l sm:border-[#CDC5BC]/30 sm:pl-5">
                <span class="text-[9px] font-bold uppercase tracking-[.18em] text-[#7F766C] block">Sisa Waktu</span>
                <span id="timer" class="font-display font-bold text-2xl text-[#C28E27] tabular-nums">--:--</span>
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- PESERTA YANG DIDUKUNG                         --}}
        {{-- ============================================ --}}
        <div class="reveal-up delay-2 card-premium bg-white border border-[#CDC5BC]/40 rounded-2xl p-6 shadow-sm space-y-5">

            <div class="flex justify-between items-center border-b border-[#CDC5BC]/20 pb-3">
                <div class="flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-[#C28E27]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="text-[11px] font-bold text-[#7F766C] uppercase tracking-[.15em]">Peserta Yang Anda Dukung</span>
                </div>
                <span class="bg-[#FAF3E7] text-[#C28E27] text-[10px] font-bold px-2.5 py-1 rounded-full border border-[#C28E27]/20">
                    Kandidat #{{ str_pad($peserta->id, 2, '0', STR_PAD_LEFT) }}
                </span>
            </div>

            <div class="flex gap-4 items-start">
                <div class="shrink-0 relative">
                    <img src="{{ $peserta->foto ? asset('storage/' . $peserta->foto) : ($peserta->user->foto_custom ? asset('storage/' . $peserta->user->foto_custom) : ($peserta->user->foto_url ?? 'https://ui-avatars.com/api/?name=' . $peserta->user->nama)) }}"
                        class="w-20 h-24 object-cover rounded-xl border border-[#CDC5BC]/30 shadow-md">
                </div>

                                <div class="flex-1 min-w-0">
                    <span class="text-[10px] font-bold uppercase tracking-[.15em] text-[#C28E27] block">{{ $event->nama }}</span>
                    <h3 class="font-display font-bold text-xl text-black mt-0.5 truncate">
                        {{ $peserta->user->nama ?? $peserta->nama_panggung }}
                    </h3>
                    <p class="text-xs text-[#7F766C] mt-1">
                        @if ($blackoutAktif ?? false)
                            <span class="inline-flex items-center gap-1 text-amber-600 italic">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                                <span>Suara disembunyikan</span>
                            </span>
                        @else
                            <b class="text-[#1E1B18]">{{ number_format($peserta->total_vote) }}</b> suara terakumulasi
                        @endif
                    </p>
                </div>

                <div class="text-right shrink-0">
                    <span class="font-display font-bold text-xl text-black block tabular-nums">{{ $voting->jumlah_vote }}</span>
                    <span class="text-[10px] uppercase tracking-wider text-[#7F766C] block">Vote</span>
                    <span class="text-xs text-[#7F766C] mt-1 block">Rp {{ number_format($voting->total_bayar) }}</span>
                </div>
            </div>

            @if ($voting->pesan_dukungan)
                <div class="pt-1">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#7F766C] block mb-1.5">Pesan Dukungan</span>
                    <div class="bg-gradient-to-br from-[#FAF3E7]/80 to-[#F5E9CE]/60 rounded-xl p-3.5 border border-[#CDC5BC]/20 text-xs text-[#4B463F] italic leading-relaxed relative">
                        <svg class="absolute top-2 left-2 w-4 h-4 text-[#C28E27]/30" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                        </svg>
                        <p class="pl-6">"{{ $voting->pesan_dukungan }}"</p>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-1 text-xs">
                <div>
                    <span class="font-bold text-[10px] uppercase tracking-wider text-[#7F766C] block mb-1.5">Nama Pendukung</span>
                    <span class="inline-flex items-center gap-1.5 bg-[#FAF3E7] text-[#7F5700] font-semibold px-3 py-1.5 rounded-full border border-[#C28E27]/20">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#C28E27]"></span>
                        {{ $voting->nama_tampilan }}
                    </span>
                </div>
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- GRID: METODE BAYAR + RINCIAN                  --}}
        {{-- ============================================ --}}
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">

            {{-- ============ METODE PEMBAYARAN ============ --}}
            <div class="reveal-up delay-3 md:col-span-7 card-premium bg-white border border-[#CDC5BC]/40 rounded-2xl p-6 shadow-sm space-y-5">

                @if ($voting->metode_pembayaran === 'qris')
                    <div class="flex items-center gap-2 border-b border-[#CDC5BC]/20 pb-3">
                        <svg class="w-4 h-4 text-[#C28E27]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7" rx="1"/>
                            <rect x="14" y="3" width="7" height="7" rx="1"/>
                            <rect x="3" y="14" width="7" height="7" rx="1"/>
                            <path d="M14 14h3v3M21 14v3M14 21h3M21 21v-3"/>
                        </svg>
                        <h3 class="font-display font-bold text-base text-black">Scan QRIS Untuk Membayar</h3>
                    </div>

                    <div class="text-center py-4 space-y-4">
                        <div class="qr-container inline-block">
                            <img src="{{ $voting->qr_url ?? 'https://api.qrserver.com/v1/create-qr-code/?size=240x240&data=' . $voting->midtrans_order_id }}"
                                alt="QRIS Code" class="w-56 h-56 mx-auto block">
                        </div>

                        <p class="text-xs text-[#7F766C] max-w-xs mx-auto leading-relaxed">
                            Gunakan aplikasi <b class="text-[#1E1B18]">GoPay, OVO, DANA, ShopeePay, LinkAja</b>, atau <b class="text-[#1E1B18]">m-Banking</b> Anda untuk scan QRIS di atas.
                        </p>

                        <div class="inline-flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 rounded-full px-3.5 py-1.5 text-[11px] font-semibold">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Verifikasi Otomatis
                        </div>
                    </div>
                @else
                    @php
                        $namaBank = match ($voting->metode_pembayaran) {
                            'bri_va' => 'BRI Virtual Account (BRIVA)',
                            default => 'Virtual Account',
                        };
                    @endphp

                    <div class="flex items-center gap-2 border-b border-[#CDC5BC]/20 pb-3">
                        <svg class="w-4 h-4 text-[#C28E27]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <rect x="2" y="6" width="20" height="12" rx="2"/>
                            <path d="M2 10h20"/>
                        </svg>
                        <h3 class="font-display font-bold text-base text-black">Transfer ke {{ $namaBank }}</h3>
                    </div>

                    <div class="space-y-2">
                        <span class="text-[10px] text-[#7F766C] uppercase font-bold tracking-wider">Nomor Virtual Account</span>
                        <div class="bg-gradient-to-br from-[#FAF3E7] to-[#F5E9CE]/60 p-4 rounded-xl flex items-center justify-between border border-[#CDC5BC]/30 gap-3">
                            <span id="va-num" class="font-mono font-bold text-lg md:text-xl tracking-wider text-black">{{ $voting->va_number }}</span>
                            <button onclick="salinVA()"
                                class="shrink-0 bg-[#C28E27] text-white text-xs font-semibold px-4 py-2 rounded-lg hover:bg-[#a97b1f] transition shadow-sm hover:shadow-md">
                                Salin VA
                            </button>
                        </div>
                        <div class="flex justify-between text-[11px] text-[#7F766C] px-1 flex-wrap gap-2">
                            <span>Penerima: <b class="text-[#1E1B18]">ALNAY VOTE - {{ strtoupper($peserta->user->nama ?? ($peserta->nama_panggung ?? 'PESERTA')) }}</b></span>
                            <span>Total: <b class="text-[#C28E27]">Rp {{ number_format($voting->total_bayar) }}</b></span>
                        </div>
                    </div>

                    <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-3 text-xs flex items-start gap-2.5">
                        <svg class="w-4 h-4 text-green-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Suara otomatis terverifikasi begitu transfer diterima — <b>tidak perlu upload bukti bayar</b>.</span>
                    </div>

                    <div class="space-y-2 pt-1 text-xs">
                        <span class="font-bold text-black block text-[11px] uppercase tracking-wider">Cara Transfer</span>
                        <ol class="space-y-2 text-[#4B463F] leading-relaxed">
                            <li class="flex gap-2.5">
                                <span class="shrink-0 w-5 h-5 rounded-full bg-[#FAF3E7] text-[#C28E27] text-[10px] font-bold flex items-center justify-center">1</span>
                                <span>Buka aplikasi m-Banking Anda.</span>
                            </li>
                            <li class="flex gap-2.5">
                                <span class="shrink-0 w-5 h-5 rounded-full bg-[#FAF3E7] text-[#C28E27] text-[10px] font-bold flex items-center justify-center">2</span>
                                <span>Pilih menu <b>Transfer › Virtual Account</b>.</span>
                            </li>
                            <li class="flex gap-2.5">
                                <span class="shrink-0 w-5 h-5 rounded-full bg-[#FAF3E7] text-[#C28E27] text-[10px] font-bold flex items-center justify-center">3</span>
                                <span>Masukkan nomor VA di atas, klik <b>Lanjutkan</b>.</span>
                            </li>
                            <li class="flex gap-2.5">
                                <span class="shrink-0 w-5 h-5 rounded-full bg-[#FAF3E7] text-[#C28E27] text-[10px] font-bold flex items-center justify-center">4</span>
                                <span>Cek nama & nominal sesuai, masukkan PIN.</span>
                            </li>
                        </ol>
                    </div>
                @endif
            </div>

            {{-- ============ RINCIAN PEMBAYARAN ============ --}}
            <div class="reveal-up delay-4 md:col-span-5 bg-white border border-[#CDC5BC]/40 rounded-2xl p-6 shadow-sm space-y-4 h-fit sticky top-24">

                <div class="flex items-center gap-2 border-b border-[#CDC5BC]/20 pb-3">
                    <svg class="w-4 h-4 text-[#C28E27]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                    </svg>
                    <h3 class="font-display font-bold text-base text-black">Rincian Pembayaran</h3>
                </div>

                <div class="space-y-2.5 text-xs">
                    <div class="flex justify-between text-[#4B463F]">
                        <span>Harga per vote</span>
                        <span class="font-semibold text-[#1E1B18]">Rp {{ number_format($voting->harga_per_vote) }}</span>
                    </div>
                    <div class="flex justify-between text-[#4B463F]">
                        <span>Jumlah vote</span>
                        <span class="font-semibold text-[#1E1B18]">{{ $voting->jumlah_vote }} vote</span>
                    </div>

                    <div class="border-t border-dashed border-[#CDC5BC]/40 my-3"></div>

                    <div class="flex justify-between items-center">
                        <span class="font-bold text-xs uppercase tracking-wider text-[#7F766C]">Total Tagihan</span>
                        <span class="text-[#C28E27] font-display text-xl font-bold">Rp {{ number_format($voting->total_bayar) }}</span>
                    </div>
                </div>

                <div class="space-y-2.5 pt-4">
                    <button id="btn-cek-status" onclick="cekStatusPembayaran()"
                        class="btn-check w-full bg-gradient-to-r from-[#C28E27] to-[#A97B1F] text-white font-semibold py-3.5 rounded-xl text-xs shadow-md">
                        <span class="relative z-10">Saya Sudah Bayar</span>
                    </button>

                    <a href="/peserta/{{ $peserta->id }}"
                        class="block w-full text-center border border-[#CDC5BC] hover:bg-[#FAF3E7] hover:border-[#C28E27]/40 text-[#4B463F] hover:text-[#7F5700] font-semibold py-2.5 rounded-xl text-xs transition">
                        Batal & Kembali
                    </a>
                </div>

                <div class="pt-2 border-t border-[#CDC5BC]/20">
                    <div class="flex items-start gap-2 text-[10px] text-[#7F766C] leading-relaxed">
                        <svg class="w-3.5 h-3.5 text-[#C28E27] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Pembayaran diverifikasi otomatis oleh sistem. Vote akan langsung masuk setelah transfer diterima.</span>
                    </div>
                </div>
            </div>

        </div>

    </main>

    {{-- ============================================ --}}
    {{-- MODAL STATUS                                  --}}
    {{-- ============================================ --}}
    <div id="modal-status" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center p-4 backdrop-blur-sm">
        <div class="bg-white rounded-2xl p-6 max-w-sm w-full text-center space-y-3 animate-modal-in">
            <div id="modal-icon" class="w-14 h-14 rounded-full mx-auto flex items-center justify-center"></div>
            <h3 id="modal-judul" class="font-display font-bold text-lg text-black"></h3>
            <p id="modal-pesan" class="text-sm text-[#4B463F] leading-relaxed"></p>
            <button id="modal-btn" onclick="tutupModalStatus()"
                class="w-full bg-[#C28E27] text-white font-semibold py-2.5 rounded-xl text-sm hover:bg-[#a97b1f] transition shadow-sm">
                Tutup
            </button>
        </div>
    </div>

    <style>
        .animate-modal-in {
            animation: modalIn .3s cubic-bezier(.22, 1, .36, 1);
        }

        @keyframes modalIn {
            from {
                opacity: 0;
                transform: translateY(16px) scale(.96);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Icon inside modal — pakai SVG */
        .modal-svg {
            width: 26px;
            height: 26px;
        }
    </style>

    <script>
        function salinVA() {
            const va = document.getElementById('va-num').innerText;
            navigator.clipboard.writeText(va.replace(/\s+/g, ''));
            tampilkanModal('info', 'Tersalin', 'Nomor Virtual Account berhasil disalin.');
        }

        function tampilkanModal(jenis, judul, pesan, redirectUrl = null) {
            const ikon = document.getElementById('modal-icon');
            const btn = document.getElementById('modal-btn');

            // SVG icons — ganti emoji
            const iconSVG = {
                sukses: `<svg class="modal-svg text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>`,
                pending: `<svg class="modal-svg text-[#C28E27]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/></svg>`,
                info: `<svg class="modal-svg text-[#7F766C]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 16v-4M12 8h.01"/></svg>`
            };

            const bgClass = {
                sukses: 'bg-green-100',
                pending: 'bg-[#FAF3E7]',
                info: 'bg-[#FAF3E7]'
            };

            ikon.className = `w-14 h-14 rounded-full mx-auto flex items-center justify-center ${bgClass[jenis]}`;
            ikon.innerHTML = iconSVG[jenis];

            document.getElementById('modal-judul').textContent = judul;
            document.getElementById('modal-pesan').textContent = pesan;
            document.getElementById('modal-status').classList.remove('hidden');
            document.getElementById('modal-status').classList.add('flex');

            if (redirectUrl) {
                btn.textContent = 'Lihat Halaman Kandidat';
                btn.onclick = () => window.location.href = redirectUrl;
            } else {
                btn.textContent = 'Tutup';
                btn.onclick = tutupModalStatus;
            }
        }

        function tutupModalStatus() {
            const modal = document.getElementById('modal-status');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        async function cekStatusPembayaran() {
            const btn = document.getElementById('btn-cek-status');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="relative z-10">Mengecek...</span>';

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
                btn.innerHTML = originalText;
            }
        }

        // Timer
        let sisaDetik = {{ $voting->expired_at ? max(0, $voting->expired_at->timestamp - now()->timestamp) : 0 }};

        function tampilkanTimer() {
            const el = document.getElementById('timer');

            if (sisaDetik <= 0) {
                el.innerText = 'Kedaluwarsa';
                el.classList.remove('text-[#C28E27]');
                el.classList.add('text-[#C1442F]');
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