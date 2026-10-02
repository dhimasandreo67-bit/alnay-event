<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Pendaftaran - ALNAY.ID</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
        .card-shadow { box-shadow: 0 8px 30px rgba(21, 35, 61, 0.08); }
    </style>
</head>
<body class="bg-[#faf9f6] text-[#15233d]">

    @php
        $jumlah = (int) ($event->biaya_pendaftaran ?? 0);
        $orderId = $pembayaran?->midtrans_order_id ?? '-';
        $batas = now()->addHours(2);
    @endphp

    {{-- HEADER --}}
    <header class="sticky top-0 z-40 border-b border-[#e9e9e9] bg-white/95 backdrop-blur">
        <div class="mx-auto flex h-[56px] max-w-[1200px] items-center justify-between px-5 md:px-8">
            <a href="{{ route('dashboard-user') }}" class="font-display text-[16px] font-bold tracking-tight text-[#13223a]">
                EVENT ALNAY.ID
            </a>
            <a href="{{ route('profil-user') }}" aria-label="Profil pengguna"
                class="flex h-9 w-9 items-center justify-center rounded-full border border-[#c99a32] bg-[#fbf6e9] text-[#c99a32] transition hover:bg-[#f7f0dc] overflow-hidden">
                @php $u = auth('pelanggan')->user(); @endphp
                @if ($u->foto_custom || $u->foto_url)
                    <img src="{{ $u->foto_custom ? asset('storage/'.$u->foto_custom) : $u->foto_url }}" alt="{{ $u->nama }}" class="w-full h-full object-cover">
                @else
                    <span class="text-[11px] font-bold">{{ strtoupper(substr($u->nama_custom ?: $u->nama, 0, 1)) }}</span>
                @endif
            </a>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-5 pt-8 pb-14">

        {{-- Judul --}}
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-6">
            <div>
                <h1 class="font-display text-2xl md:text-3xl font-bold text-[#111827]">
                    Pembayaran Pendaftaran
                </h1>
                <p class="text-gray-500 text-xs md:text-sm mt-1.5">
                    Bayar administrasi untuk mengaktifkan akun sebagai peserta event
                </p>
            </div>
            <span class="inline-flex items-center gap-1.5 self-start px-3 py-1.5 rounded-full bg-amber-50 border border-amber-200 text-amber-700 text-[11px] font-semibold">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                Menunggu Pembayaran
            </span>
        </div>

        {{-- Countdown --}}
        <div class="mb-5 flex items-center gap-2 text-[13px] text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-4 py-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
            </svg>
            <span>Bayar sebelum <strong id="countdown" data-target="{{ $batas->toIso8601String() }}">--:--:--</strong> agar kode tidak kadaluwarsa</span>
        </div>

        {{-- 2 Kolom --}}
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_380px] gap-5">

            {{-- KOLOM KIRI --}}
            <div class="space-y-5">

                {{-- Card Detail Pendaftaran --}}
                <div class="bg-white rounded-xl border border-gray-100 card-shadow p-5">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 shrink-0 rounded-lg bg-[#f4ede0] overflow-hidden flex items-center justify-center">
                            @if ($event->banner)
                                <img src="{{ asset('storage/' . $event->banner) }}" alt="{{ $event->nama }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-6 h-6 text-[#a99260]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <rect x="3" y="4" width="18" height="16" rx="2"/>
                                </svg>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[10px] uppercase tracking-wide text-gray-400 font-semibold">Pendaftaran Peserta</p>
                            <p class="text-[15px] font-bold text-[#15233d] truncate">{{ $event->nama }}</p>
                            <p class="text-[12px] text-gray-500 mt-0.5">{{ $peserta->nama_pendaftaran ?: $u->nama }}</p>
                        </div>
                        <p class="text-[17px] font-bold text-[#15233d] shrink-0">Rp{{ number_format($jumlah, 0, ',', '.') }}</p>
                    </div>
                </div>

                {{-- Metode Pembayaran --}}
                <div class="bg-white rounded-xl border border-gray-100 card-shadow p-5">
                    <h2 class="text-[15px] font-bold text-[#15233d] mb-1">Metode Pembayaran</h2>
                    <p class="text-[12px] text-gray-500 mb-4">Bayar online agar pendaftaran langsung diproses</p>

                    <div class="space-y-2">

                        {{-- QRIS --}}
                        <label class="flex items-center gap-3 border border-gray-200 rounded-lg px-4 py-3.5 cursor-pointer hover:border-[#c99a32] transition group">
                            <input type="radio" name="metode" value="qris" checked
                                class="w-4 h-4 accent-[#a37b2a]">
                            <div class="flex items-center gap-2.5 flex-1">
                                <div class="w-8 h-8 rounded-md bg-[#f4ede0] flex items-center justify-center">
                                    <svg class="w-4 h-4 text-[#a37b2a]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                        <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                                        <rect x="3" y="14" width="7" height="7"/><path d="M14 14h3v3h-3zM21 14v3M18 21h3"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[13px] font-semibold text-[#15233d]">QRIS (Semua Bank & Dompet Digital)</p>
                                    <p class="text-[10px] text-gray-400 mt-0.5">BCA, Mandiri, OVO, GoPay, ShopeePay, Allobank, Dana</p>
                                </div>
                            </div>
                        </label>

                        {{-- Virtual Account BRI --}}
                        <label class="flex items-center gap-3 border border-gray-200 rounded-lg px-4 py-3.5 cursor-pointer hover:border-[#c99a32] transition group">
                            <input type="radio" name="metode" value="bri_va"
                                class="w-4 h-4 accent-[#a37b2a]">
                            <div class="flex items-center gap-2.5 flex-1">
                                <div class="w-8 h-8 rounded-md bg-blue-50 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                        <rect x="3" y="6" width="18" height="12" rx="2"/><path d="M3 10h18"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[13px] font-semibold text-[#15233d]">Virtual Account BRI</p>
                                    <p class="text-[10px] text-gray-400 mt-0.5">Khusus Bank BRI</p>
                                </div>
                            </div>
                        </label>

                    </div>
                </div>

            </div>

            {{-- KOLOM KANAN — Rincian Harga --}}
            <aside>
                <div class="bg-white rounded-xl border border-gray-100 card-shadow p-5 sticky top-20">
                    <h2 class="text-[15px] font-bold text-[#15233d] mb-4">Rincian Harga</h2>

                    <div class="space-y-3 text-[13px]">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Pendaftaran Peserta ×1</span>
                            <span class="font-semibold text-[#15233d]">Rp{{ number_format($jumlah, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between border-t border-gray-100 mt-4 pt-4">
                        <span class="text-[13px] font-semibold text-[#15233d]">Total Harga</span>
                        <span class="text-[15px] font-bold text-[#15233d]">Rp{{ number_format($jumlah, 0, ',', '.') }}</span>
                    </div>

                    <div class="mt-4 rounded-lg bg-[#13223a] p-4">
                        <p class="text-[10px] uppercase tracking-wide text-gray-400 font-semibold">Total Dibayar Sekarang</p>
                        <p class="text-[22px] font-bold text-[#d4a63d] mt-1">Rp{{ number_format($jumlah, 0, ',', '.') }}</p>
                    </div>

                    <button type="button" onclick="mulaiBayar()"
                        class="mt-4 w-full rounded-lg bg-[#cf9b32] py-3.5 text-center text-[13px] font-bold text-white transition hover:bg-[#b88625]">
                        Bayar Sekarang
                    </button>

                    <p class="text-[10px] text-gray-400 text-center mt-3 leading-relaxed">
                        Pendaftaran diproses otomatis begitu pembayaran terverifikasi, biasanya dalam 1–5 menit.
                    </p>
                </div>
            </aside>

        </div>

    </main>

    {{-- Midtrans Snap JS --}}
    <script
        src="{{ config('services.midtrans.is_production')
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <script>
        // Countdown
        (function() {
            const el = document.getElementById('countdown');
            const target = new Date(el.dataset.target).getTime();

            function tick() {
                const now = Date.now();
                const diff = Math.max(0, target - now);

                const h = String(Math.floor(diff / 3600000)).padStart(2, '0');
                const m = String(Math.floor(diff % 3600000 / 60000)).padStart(2, '0');
                const s = String(Math.floor(diff % 60000 / 1000)).padStart(2, '0');

                el.textContent = `${h}:${m}:${s}`;

                if (diff > 0) requestAnimationFrame(tick);
            }
            tick();
        })();

        // Bayar
        async function mulaiBayar() {
    const btn = event.target;
    const originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Memproses...';

    // Ambil metode yang dipilih
    const metode = document.querySelector('input[name="metode"]:checked')?.value;

    if (!metode) {
        toast('Pilih metode pembayaran dulu.', 'warning');
        btn.disabled = false;
        btn.textContent = originalText;
        return;
    }

    try {
        const res = await fetch('/peserta/{{ $peserta->id }}/charge', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ payment_method: metode }),
        });

        const data = await res.json();

        if (!res.ok) {
            swalAlert(data.message || 'Gagal memproses pembayaran.', 'error');
            btn.disabled = false;
            btn.textContent = originalText;
            return;
        }

        // Redirect ke halaman instruksi
        window.location.href = data.redirect_url;

    } catch (err) {
        swalAlert('Terjadi kesalahan: ' + err.message, 'error');
        btn.disabled = false;
        btn.textContent = originalText;
    }
}
    </script>

    @include('partials.swal')

</body>
</html>