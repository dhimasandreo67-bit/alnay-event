<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Registrasi - {{ $event->nama }} | ALNAY.ID</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <link rel="icon" type="image/png" href="{{ asset('images/alnaylogo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }

        .card-premium { transition: all .35s cubic-bezier(.22, 1, .36, 1); }

        .reveal-up { animation: revealUp .6s cubic-bezier(.22, 1, .36, 1) both; }
        @keyframes revealUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .reveal-up.delay-1 { animation-delay: .08s; }
        .reveal-up.delay-2 { animation-delay: .16s; }

        .title-ornament { display: inline-flex; align-items: center; gap: 8px; }
        .title-ornament::before,
        .title-ornament::after {
            content: "";
            width: 24px;
            height: 1px;
            background: linear-gradient(90deg, transparent, #C99A32);
        }
        .title-ornament::after {
            background: linear-gradient(90deg, #C99A32, transparent);
        }

        /* Pilihan metode */
        .metode-opt { transition: all .2s ease; cursor: pointer; }
        .metode-opt .dot {
            display: block;
            width: 8px;
            height: 8px;
            border-radius: 9999px;
            background: transparent;
        }
        .metode-opt.is-on {
            border-color: #C99A32;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(201, 154, 50, .14);
        }
        .metode-opt.is-on .dot { background: #C99A32; }
        .metode-opt.is-on .ring { border-color: #C99A32; }

        .btn-gold {
            transition: all .3s cubic-bezier(.22, 1, .36, 1);
        }
        .btn-gold:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 22px -8px rgba(201, 154, 50, .5);
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: .01ms !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body class="bg-[#faf9f6] text-[#15233d]">

    @include('partials.header')

    <main class="max-w-3xl mx-auto px-5 pt-8 pb-14">

        {{-- Judul --}}
        <section class="reveal-up">
            <div class="title-ornament mb-2">
                <span class="inline-block h-1.5 w-1.5 rotate-45 bg-[#C99A32]"></span>
                <span class="text-[9px] font-bold uppercase tracking-[.24em] text-[#a37b2a]">
                    Pembayaran
                </span>
                <span class="inline-block h-1.5 w-1.5 rotate-45 bg-[#C99A32]"></span>
            </div>
            <h1 class="font-display text-2xl md:text-3xl font-bold tracking-tight text-[#111827]">
                Pembayaran Registrasi
            </h1>
            <p class="mt-1.5 text-gray-500 text-xs md:text-sm">
                Event: <b class="text-[#15233d]">{{ $event->nama }}</b>
            </p>
        </section>

        {{-- Back link --}}
        <a href="{{ route('profil-user') }}"
            class="inline-flex items-center gap-1.5 text-xs text-[#a37b2a] hover:text-[#c99a32] transition font-medium mt-4">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Profil
        </a>

        {{-- Kartu Pembayaran --}}
        <section class="reveal-up delay-1 mt-6 bg-white rounded-2xl border border-[#ebe7df] card-premium overflow-hidden">
            <div class="h-[3px] w-full bg-gradient-to-r from-[#c99a32] via-[#e0b254] to-[#c99a32]"></div>

            <div class="p-5 sm:p-6">

                {{-- Info Biaya --}}
                <div class="mb-5 pb-5 border-b border-[#f0eadb]">
                    <p class="text-xs text-gray-500 mb-1">Total Biaya Registrasi</p>
                    <p class="font-display text-2xl font-bold text-[#c99a32]">
                        Rp {{ number_format($biayaRegis, 0, ',', '.') }}
                    </p>
                </div>

                <form id="formRegis" method="POST" action="{{ route('peserta.registrasi.bayar', $peserta->id) }}">
                    @csrf

                    <p class="text-[12.5px] text-[#666] mb-3">Pilih cara pembayaran:</p>

                    {{-- Pilihan Lunas / Cicil --}}
                    <div class="grid gap-3 sm:grid-cols-2 mb-5">
                        <label class="metode-opt is-on rounded-xl border border-[#e6e0d2] bg-white/60 p-3.5 text-left hover:border-[#d9c48a]">
                            <input type="radio" name="metode" value="lunas" class="sr-only" checked>
                            <span class="flex items-center gap-2.5">
                                <span class="ring flex h-4 w-4 items-center justify-center rounded-full border border-[#cfc8b6]">
                                    <span class="dot"></span>
                                </span>
                                <span class="text-[13px] font-semibold">Bayar Lunas</span>
                            </span>
                            <span class="mt-2 block text-[17px] font-bold">Rp {{ number_format($biayaRegis, 0, ',', '.') }}</span>
                            <span class="mt-0.5 block text-[11.5px] leading-snug text-gray-500">
                                Sekali bayar, registrasi langsung selesai.
                            </span>
                        </label>

                        <label class="metode-opt rounded-xl border border-[#e6e0d2] bg-white/60 p-3.5 text-left hover:border-[#d9c48a]">
                            <input type="radio" name="metode" value="cicil" class="sr-only">
                            <span class="flex items-center gap-2.5">
                                <span class="ring flex h-4 w-4 items-center justify-center rounded-full border border-[#cfc8b6]">
                                    <span class="dot"></span>
                                </span>
                                <span class="text-[13px] font-semibold">Cicil 2 Kali</span>
                            </span>
                            <span class="mt-2 block text-[17px] font-bold">DP Rp {{ number_format($dpRegis, 0, ',', '.') }}</span>
                            <span class="mt-0.5 block text-[11.5px] leading-snug text-gray-500">
                                Sisa Rp {{ number_format($biayaRegis - $dpRegis, 0, ',', '.') }} dibayar nanti.
                            </span>
                        </label>
                    </div>

                    {{-- Info cicil --}}
                    <div data-cicil-info class="hidden divide-y divide-[#f0eadb] rounded-lg border border-[#ece5d7] bg-white text-[12.5px] mb-5">
                        <div class="flex items-center justify-between gap-4 px-3.5 py-2.5">
                            <span class="text-gray-500">Cicilan 1 · DP (bayar sekarang)</span>
                            <span class="font-medium">Rp {{ number_format($dpRegis, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between gap-4 px-3.5 py-2.5">
                            <span class="text-gray-500">Cicilan 2 · Pelunasan</span>
                            <span class="font-medium">Rp {{ number_format($biayaRegis - $dpRegis, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- Tombol --}}
                    <button type="submit" id="btnBayar"
                        class="btn-gold w-full rounded-lg bg-gradient-to-r from-[#c99a32] to-[#b8862a] py-3.5 text-center text-[13px] font-bold text-white shadow-sm">
                        Bayar Sekarang
                    </button>

                    <p class="mt-3 text-[11.5px] text-gray-400 text-center">
                        Pembayaran aman melalui Midtrans. Setelah bayar, halaman akan otomatis kembali ke profil.
                    </p>
                </form>

            </div>
        </section>

    </main>

    <script>
        // Pilihan metode — toggle style
        document.querySelectorAll('.metode-opt').forEach(el => {
            el.addEventListener('click', () => {
                document.querySelectorAll('.metode-opt').forEach(x => x.classList.remove('is-on'));
                el.classList.add('is-on');
                el.querySelector('input').checked = true;

                const val = el.querySelector('input').value;
                document.querySelector('[data-cicil-info]').classList.toggle('hidden', val !== 'cicil');
            });
        });

        // Submit form — panggil Midtrans
        document.getElementById('formRegis').addEventListener('submit', async (e) => {
            e.preventDefault();

            const btn = document.getElementById('btnBayar');
            btn.disabled = true;
            btn.textContent = 'Memproses...';

            const formData = new FormData(e.target);

            try {
                const res = await fetch(e.target.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                const data = await res.json();

                if (!res.ok) {
                    throw new Error(data.message || 'Gagal memproses pembayaran');
                }

                // Buka Snap popup Midtrans
                if (data.snap_token) {
                    snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            window.location.href = '{{ route('profil-user') }}';
                        },
                        onPending: function(result) {
                            window.location.href = '{{ route('profil-user') }}';
                        },
                        onError: function(result) {
                            alert('Pembayaran gagal. Coba lagi.');
                            btn.disabled = false;
                            btn.textContent = 'Bayar Sekarang';
                        },
                        onClose: function() {
                            btn.disabled = false;
                            btn.textContent = 'Bayar Sekarang';
                        }
                    });
                } else {
                    alert('Snap token tidak diterima.');
                    btn.disabled = false;
                    btn.textContent = 'Bayar Sekarang';
                }
            } catch (err) {
                alert('Error: ' + err.message);
                btn.disabled = false;
                btn.textContent = 'Bayar Sekarang';
            }
        });

        // ============================================
// REGISTRASI — AJAX + SNAP
// ============================================

// Toggle metode (lunas / cicil)
document.querySelectorAll('.form-regis').forEach(form => {
    form.querySelectorAll('.metode-opt').forEach(opt => {
        opt.addEventListener('click', () => {
            form.querySelectorAll('.metode-opt').forEach(x => x.classList.remove('is-on'));
            opt.classList.add('is-on');
            opt.querySelector('input').checked = true;

            const val = opt.dataset.metode;
            const btn = form.querySelector('[data-bayar-regis]');
            const info = form.querySelector('[data-cicil-info]');

            btn.dataset.metode = val;
            if (val === 'cicil') {
                btn.textContent = btn.dataset.cicil;
                info.classList.remove('hidden');
            } else {
                btn.textContent = btn.dataset.lunas;
                info.classList.add('hidden');
            }
        });
    });
});

// Klik tombol bayar → AJAX ke RegistrasiController
document.querySelectorAll('[data-bayar-regis]').forEach(btn => {
    btn.addEventListener('click', async function() {
        const pesertaId = this.dataset.bayarRegis;
        const metode = this.dataset.metode;

        const originalText = this.innerHTML;
        this.disabled = true;
        this.innerHTML = 'Memproses...';

        try {
            const res = await fetch(`/peserta/${pesertaId}/registrasi/bayar`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ metode: metode }),
            });

            const data = await res.json();

            if (!res.ok) {
                throw new Error(data.message || 'Gagal memproses.');
            }

            if (data.snap_token) {
                snap.pay(data.snap_token, {
                    onSuccess: function() {
                        window.location.reload();
                    },
                    onPending: function() {
                        window.location.reload();
                    },
                    onError: function() {
                        alert('Pembayaran gagal. Coba lagi.');
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    },
                    onClose: function() {
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    }
                });
            }
        } catch (err) {
            alert('Error: ' + err.message);
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    });
});
    </script>

</body>

</html>