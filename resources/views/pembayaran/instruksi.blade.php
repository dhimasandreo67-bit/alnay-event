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
    </style>
</head>
<body class="bg-[#faf9f6] text-[#15233d] min-h-screen">

    <header class="sticky top-0 z-40 border-b border-[#e9e9e9] bg-white/95 backdrop-blur">
        <div class="mx-auto flex h-[56px] max-w-[1200px] items-center justify-between px-5 md:px-8">
            <a href="{{ route('dashboard-user') }}" class="font-display text-[16px] font-bold tracking-tight text-[#13223a]">
                EVENT ALNAY.ID
            </a>
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-5 pt-8 pb-14">

        {{-- Judul --}}
        <div class="text-center mb-6">
            <h1 class="font-display text-2xl md:text-3xl font-bold text-[#111827]">
                Instruksi Pembayaran
            </h1>
            <p class="text-gray-500 text-xs md:text-sm mt-1.5">
                Selesaikan pembayaran sesuai metode yang dipilih
            </p>
        </div>

        {{-- Countdown --}}
        <div class="mb-5 flex items-center justify-center gap-2 text-[13px] text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-4 py-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
            </svg>
            <span>Selesaikan dalam <strong id="countdown">--:--:--</strong></span>
        </div>

        @if ($pembayaran->payment_method === 'qris')
            {{-- QRIS --}}
            <div class="bg-white rounded-xl border border-gray-100 card-shadow p-6 text-center">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#f4ede0] text-[#a37b2a] text-[11px] font-semibold mb-4">
                    QRIS
                </div>
                <h2 class="text-lg font-bold text-[#15233d] mb-1">Scan QR Code</h2>
                <p class="text-sm text-gray-500 mb-5">
                    Buka aplikasi bank / e-wallet, scan QR di bawah
                </p>

                @if ($pembayaran->qr_url)
                    <div class="inline-block p-4 bg-white border-2 border-gray-100 rounded-xl">
                        <img src="{{ $pembayaran->qr_url }}" alt="QR Code" class="w-64 h-64 object-contain">
                    </div>
                @else
                    <p class="text-rose-600">QR tidak tersedia</p>
                @endif

                <div class="mt-5 text-center">
                    <p class="text-[11px] text-gray-400 uppercase tracking-wide">Total Bayar</p>
                    <p class="text-2xl font-bold text-[#15233d] mt-1">
                        Rp{{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                    </p>
                </div>

                <p class="mt-5 text-[11px] text-gray-400">
                    Order ID: {{ $pembayaran->midtrans_order_id }}
                </p>
            </div>

        @elseif ($pembayaran->payment_method === 'bri_va')
            {{-- Virtual Account BRI --}}
            <div class="bg-white rounded-xl border border-gray-100 card-shadow p-6">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50 text-blue-700 text-[11px] font-semibold mb-4">
                    Virtual Account BRI
                </div>
                <h2 class="text-lg font-bold text-[#15233d] mb-1">Nomor Virtual Account</h2>
                <p class="text-sm text-gray-500 mb-5">
                    Transfer ke nomor VA di bawah dari aplikasi BRI
                </p>

                <div class="bg-[#faf8f2] border border-[#ece5d7] rounded-lg p-5 text-center">
                    <p class="text-[11px] text-gray-400 uppercase tracking-wide">Nomor VA</p>
                    <p class="text-2xl font-mono font-bold text-[#15233d] mt-1 tracking-wider">
                        {{ $pembayaran->va_number ?? '-' }}
                    </p>

                    <button onclick="copyVA('{{ $pembayaran->va_number }}')"
                        class="mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-white border border-[#ece5d7] text-[11px] font-semibold text-[#4B463F] hover:bg-[#f4ede0] transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                        </svg>
                        Copy Nomor VA
                    </button>
                </div>

                <div class="mt-5 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Atas Nama</span>
                        <span class="font-semibold text-[#15233d]">ALNAY.ID</span>
                    </div>
                    <div class="flex justify-between border-t border-gray-100 pt-3">
                        <span class="text-gray-500">Total Bayar</span>
                        <span class="font-bold text-[#15233d]">Rp{{ number_format($pembayaran->jumlah, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="mt-5 rounded-lg bg-[#faf8f2] p-4 text-[12px] text-[#4B463F]">
                    <p class="font-semibold mb-2">Cara Bayar:</p>
                    <ol class="list-decimal list-inside space-y-1 text-gray-600">
                        <li>Buka aplikasi BRImo atau ATM BRI</li>
                        <li>Pilih menu <strong>Transfer → Virtual Account</strong></li>
                        <li>Masukkan nomor VA di atas</li>
                        <li>Konfirmasi nominal & selesaikan pembayaran</li>
                    </ol>
                </div>

                <p class="mt-5 text-[11px] text-gray-400 text-center">
                    Order ID: {{ $pembayaran->midtrans_order_id }}
                </p>
            </div>
        @endif

        {{-- Status polling --}}
        <div class="mt-5 text-center">
            <p id="status-text" class="text-[13px] text-gray-500">
                <span class="inline-block w-2 h-2 rounded-full bg-amber-400 animate-pulse mr-2"></span>
                Menunggu pembayaran...
            </p>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('profil-user') }}" class="text-[12px] text-[#a37b2a] hover:text-[#c99a32] transition font-medium">
                Bayar Nanti (lihat di profil) →
            </a>
        </div>

    </main>

    <script>
        // Countdown 2 jam
        (function() {
            const el = document.getElementById('countdown');
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

        // Copy VA
        function copyVA(va) {
            navigator.clipboard.writeText(va).then(() => {
                alert('Nomor VA berhasil dicopy!');
            });
        }

        // Polling status tiap 5 detik
        setInterval(async () => {
            try {
                const res = await fetch('{{ route('peserta.pembayaran.status', $peserta->id) }}', {
                    credentials: 'same-origin',
                    headers: { 'Accept': 'application/json' },
                });
                const data = await res.json();
                if (data.lunas) {
                    document.getElementById('status-text').innerHTML =
                        '<span class="inline-block w-2 h-2 rounded-full bg-emerald-500 mr-2"></span>Pembayaran berhasil! Mengalihkan...';
                    setTimeout(() => window.location.href = '{{ route('profil-user') }}', 1500);
                }
            } catch (e) {}
        }, 5000);
    </script>

</body>
</html>