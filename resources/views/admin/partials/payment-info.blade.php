{{-- Tampilkan hanya jika peserta sudah diterima --}}
@if ($peserta->status === 'diterima' && $peserta->pembayaran)
    <div class="bg-gradient-to-br from-amber-50 to-orange-50 border-2 border-amber-300 rounded-2xl p-6 my-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 bg-amber-500 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-amber-900">Pembayaran Pendaftaran</h3>
                <p class="text-sm text-amber-700">Silakan transfer sesuai nominal unik di bawah ini</p>
            </div>
        </div>

        @if ($peserta->pembayaran->is_lunas)
            <div class="bg-emerald-100 border border-emerald-300 rounded-lg p-4 text-center">
                <span class="text-emerald-800 font-bold text-lg">✅ Pembayaran Sudah Diterima</span>
                <p class="text-emerald-700 text-sm mt-1">Akun Anda sedang dalam proses aktivasi.</p>
            </div>
        @else
            {{-- Nominal Besar --}}
            <div class="bg-white rounded-xl p-5 text-center mb-4 border border-amber-200">
                <p class="text-sm text-slate-500 mb-1">Transfer Tepat Sejumlah</p>
                <div class="text-4xl font-black text-amber-600 tracking-tight">
                    Rp {{ number_format($peserta->pembayaran->jumlah, 0, ',', '.') }}
                </div>
                <p class="text-xs text-slate-500 mt-2">
                    ⚠️ <strong>WAJIB</strong> transfer <strong>persis</strong> sesuai nominal di atas 
                    (termasuk kode unik <strong class="font-mono bg-amber-100 px-2 py-0.5 rounded">{{ $peserta->pembayaran->kode_unik }}</strong>)
                </p>
            </div>

            {{-- Detail Breakdown --}}
            <div class="bg-white rounded-lg p-4 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-600">Biaya Pendaftaran</span>
                    <span class="font-medium">Rp {{ number_format($peserta->pembayaran->tarif_dasar, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Kode Unik (3 digit terakhir No. Reg)</span>
                    <span class="font-mono font-bold text-amber-700">+ {{ $peserta->pembayaran->kode_unik }}</span>
                </div>
                <div class="flex justify-between border-t border-slate-200 pt-2 font-bold">
                    <span class="text-slate-800">Total Transfer</span>
                    <span class="text-amber-700">Rp {{ number_format($peserta->pembayaran->jumlah, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Info Rekening --}}
            <div class="mt-4 bg-white rounded-lg p-4 border border-slate-200">
                <p class="text-xs text-slate-500 mb-2">Transfer ke Rekening:</p>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-bold text-slate-800">BCA 1234567890</p>
                        <p class="text-sm text-slate-600">a.n. ALNAY.ID</p>
                    </div>
                    <button onclick="navigator.clipboard.writeText('1234567890')" 
                            class="text-xs bg-slate-100 hover:bg-slate-200 px-3 py-1.5 rounded-lg transition">
                        📋 Salin
                    </button>
                </div>
            </div>

            {{-- Instruksi --}}
            <div class="mt-4 text-xs text-slate-600 bg-white rounded-lg p-4 border border-slate-200">
                <p class="font-semibold text-slate-700 mb-2">📌 Instruksi Penting:</p>
                <ol class="list-decimal list-inside space-y-1">
                    <li>Transfer <strong>tepat</strong> sesuai nominal unik di atas</li>
                    <li>Kode unik <strong>{{ $peserta->pembayaran->kode_unik }}</strong> membantu kami mengidentifikasi pembayaran Anda secara otomatis</li>
                    <li>Jangan dibulatkan (jangan transfer Rp 90.000 saja)</li>
                    <li>Simpan bukti transfer untuk verifikasi</li>
                    <li>Akun akan aktif otomatis dalam 1x24 jam setelah pembayaran terverifikasi</li>
                </ol>
            </div>
        @endif
    </div>
@endif