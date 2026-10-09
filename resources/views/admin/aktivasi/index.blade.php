<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Aktivasi Akun Peserta | ALNAY.ID</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-cream font-['Inter'] text-[13px] text-[#22201d]">
<div class="flex min-h-screen">
    @include('admin.partials.sidebar', ['active' => 'activation', 'event' => $event])
    @include('admin.partials.pwa-head')

    <main class="flex-1 min-w-0 p-6 ml-[235px]">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h1 class="text-2xl font-bold m-0">Verifikasi Pembayaran & Aktivasi Akun</h1>
                <p class="text-[#73716c] text-[12px] mt-1 max-w-2xl">
                    Tinjau status pembayaran peserta yang sudah diterima untuk mengaktifkan akses akun resmi mereka.
                </p>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-lg bg-emerald-50 text-emerald-700 px-4 py-3 text-sm">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 rounded-lg bg-rose-50 text-rose-700 px-4 py-3 text-sm">{{ session('error') }}</div>
        @endif

        {{-- Stat cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-5">
            <div class="bg-white rounded-xl border border-[#e3ded6] p-4">
                <div class="text-[10px] uppercase text-[#98958f] font-bold">Total Finalis Diterima</div>
                <div class="text-3xl font-bold mt-1">{{ $totalFinalis }} <span class="text-sm text-[#98958f] font-normal">/ {{ $event?->kuota_peserta ?? '-' }} Kuota</span></div>
            </div>
            <div class="bg-white rounded-xl border border-[#e3ded6] p-4">
                <div class="text-[10px] uppercase text-[#98958f] font-bold">Menunggu Verifikasi</div>
                <div class="text-3xl font-bold mt-1 text-amber-600">{{ $menungguVerifikasi }} <span class="text-sm text-[#98958f] font-normal">Peserta</span></div>
            </div>
            <div class="bg-white rounded-xl border border-[#e3ded6] p-4">
                <div class="text-[10px] uppercase text-[#98958f] font-bold">Akun Sudah Aktif & Lunas</div>
                <div class="text-3xl font-bold mt-1 text-emerald-600">{{ $lunas }} <span class="text-sm text-[#98958f] font-normal">Finalis Aktif</span></div>
            </div>
        </div>

        {{-- Tabs + search --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 mb-4">
            <button type="button" data-tab="semua"
                    class="tab-btn active px-4 py-2 rounded-lg text-[12px] font-semibold bg-gold text-white transition">
                Semua ({{ $totalFinalis }})
            </button>

            <button type="button" data-tab="perlu"
                    class="tab-btn px-4 py-2 rounded-lg text-[12px] font-semibold bg-white border border-[#e3ded6] text-[#22201d] transition">
                Perlu Verifikasi ({{ $menungguVerifikasi }})
            </button>

            <button type="button" data-tab="aktif"
                    class="tab-btn px-4 py-2 rounded-lg text-[12px] font-semibold bg-white border border-[#e3ded6] text-[#22201d] transition">
                Sudah Aktif / Lunas ({{ $lunas }})
            </button>

            <input id="cariFinalis"
                type="text"
                placeholder="Cari nama, no. registrasi, email..."
                class="md:col-span-3 w-full border border-[#e3ded6] rounded-lg px-3 py-2 text-[12px] focus:outline-none focus:ring-2 focus:ring-gold/30">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4 mb-5">

            {{-- ===== Kolom kiri: antrean ===== --}}
            <div class="space-y-3">
                @forelse ($finalis as $f)
                    <div class="bg-white rounded-xl border border-slate-200 p-4 hover:border-amber-300 transition cursor-pointer peserta-card"
                        onclick="showDetail({{ $f['id'] }})"
                        id="card-{{ $f['id'] }}"
                        data-status="{{ $f['statusAkun'] }}"
                        data-nama="{{ strtolower($f['nama']) }}"
                        data-email="{{ strtolower($f['email']) }}"
                        data-no-reg="{{ strtolower($f['no_registrasi']) }}">
                        
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-mono text-slate-500">#{{ $f['no_registrasi'] }}</span>

                            @if ($f['nominal_unik'])
                                <span class="text-xs font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                                    {{ $f['nominal_unik'] }}
                                </span>
                            @endif

                            @if ($f['statusAkun'] === 'aktif')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Lunas & Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                    Menunggu Verifikasi
                                </span>
                            @endif
                        </div>

                        <h3 class="font-semibold text-slate-800">{{ $f['nama'] }}</h3>

                        @if ($f['kode_unik'])
                            <p class="text-xs text-slate-500 mt-1">
                                Kode unik: <span class="font-mono font-bold text-amber-600">{{ $f['kode_unik'] }}</span>
                            </p>
                        @endif

                        @if ($f['pembayaran'])
                            <p class="text-xs text-slate-500 mt-2">
                                {{ $f['pembayaran']->metode ?? 'Transfer Bank' }} ·
                                Rp {{ number_format($f['pembayaran']->jumlah, 0, ',', '.') }} ·
                                {{ $f['pembayaran']->tanggal_bayar?->diffForHumans() ?? 'belum bayar' }}
                            </p>
                        @else
                            <p class="text-xs text-rose-500 mt-2">Belum ada transaksi pembayaran</p>
                        @endif
                    </div>
                @empty
                    <div class="bg-white rounded-xl border border-slate-200 p-8 text-center">
                        <p class="text-slate-500">Belum ada finalis yang diterima untuk event ini.</p>
                    </div>
                @endforelse
            </div>

            {{-- ===== Kolom kanan: detail ===== --}}
            <div class="bg-white border border-[#e3ded6] rounded-xl p-5 sticky top-6" id="panelDetail">
                <p class="text-[#98958f] text-sm text-center py-10">Pilih peserta di kiri untuk melihat detail.</p>
            </div>
        </div>
    </main>
</div>

{{-- Template detail per peserta (disembunyikan, dibaca JS) --}}
<div class="hidden">
    @foreach ($finalis as $f)
        <template id="detail-{{ $f['id'] }}">
            <div>
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <div class="text-[10px] text-[#98958f]">No. Reg: #{{ $f['no_registrasi'] }}</div>
                        <h2 class="text-xl font-bold">{{ $f['nama'] }}</h2>
                        <p class="text-[11px] text-[#73716c]">{{ $f['email'] }}</p>
                    </div>
                    @if ($f['statusAkun'] === 'aktif')
                        <span class="text-[10px] font-bold px-3 py-1.5 rounded-full bg-emerald-100 text-emerald-700">Aktif</span>
                    @else
                        <span class="text-[10px] font-bold px-3 py-1.5 rounded-full bg-rose-100 text-rose-700">Belum Aktif (Terkunci)</span>
                    @endif
                </div>

                <div class="bg-[#f7f5f0] rounded-lg p-4 mb-4">
                    <div class="text-[10px] uppercase text-[#98958f] font-bold mb-2">Detail Transaksi Midtrans</div>
                    @if ($f['pembayaran'])
                        <div class="grid grid-cols-2 gap-2 text-[12px]">
                            <div><span class="text-[#73716c]">Order ID</span><br><strong>{{ $f['pembayaran']->midtrans_order_id }}</strong></div>
                            <div><span class="text-[#73716c]">Transaction ID</span><br><strong>{{ $f['pembayaran']->midtrans_transaction_id ?? '-' }}</strong></div>
                            <div><span class="text-[#73716c]">Metode</span><br><strong>{{ $f['pembayaran']->metode ?? '-' }}</strong></div>
                            <div><span class="text-[#73716c]">Nominal</span><br><strong>Rp {{ number_format($f['pembayaran']->jumlah, 0, ',', '.') }}</strong></div>
                            <div><span class="text-[#73716c]">Status Midtrans</span><br><strong class="uppercase">{{ $f['pembayaran']->status_midtrans }}</strong></div>
                            <div><span class="text-[#73716c]">Tanggal Bayar</span><br><strong>{{ $f['pembayaran']->tanggal_bayar?->format('d M Y, H:i') ?? '-' }} WIB</strong></div>
                        </div>
                    @else
                        <p class="text-rose-500 text-[12px]">Belum ada transaksi pembayaran tercatat untuk peserta ini.</p>
                    @endif
                </div>

                <div class="flex items-center justify-between pt-3 border-t">
                    <form action="{{ route('admin.activation.verifikasi', $f['id']) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 text-[12px] font-bold bg-gold text-white rounded-lg hover:bg-[#a06f1a] transition">
                            Cek & Sinkronkan Status Pembayaran
                        </button>
                    </form>
                </div>
            </div>
        </template>
    @endforeach
</div>

{{-- ==========================================
     JAVASCRIPT YANG SUDAH DIPERBAIKI & DIRAPIKAN
========================================== --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Ambil elemen dengan aman
    const cards = document.querySelectorAll('.peserta-card');
    const panel = document.getElementById('panelDetail');
    const searchInput = document.getElementById('cariFinalis'); // Sesuai dengan ID di HTML
    const tabs = document.querySelectorAll('.tab-btn');
    
    let currentFilter = 'semua';
    let searchQuery = '';

    // 2. Fungsi Menampilkan Detail Peserta
    window.showDetail = function (id) {
        const tpl = document.getElementById('detail-' + id);
        if (tpl && panel) {
            panel.innerHTML = tpl.innerHTML;
        }

        // Highlighting kartu yang aktif
        cards.forEach(c => {
            c.classList.remove('border-gold', 'ring-1', 'ring-gold');
        });
        const activeCard = document.getElementById('card-' + id);
        if (activeCard) {
            activeCard.classList.add('border-gold', 'ring-1', 'ring-gold');
        }
    };

    // Tampilkan detail kartu pertama saat pertama dimuat
    if (cards.length > 0) {
        const firstId = cards[0].id.replace('card-', '');
        showDetail(firstId);
    }

    // 3. Fungsi Filter Gabungan (Tab + Search)
    function applyFilters() {
        cards.forEach(card => {
            const status = card.dataset.status;
            const nama = card.dataset.nama || '';
            const email = card.dataset.email || '';
            const noReg = card.dataset.noReg || '';

            // Cek kecocokan Tab
            let matchTab = false;
            if (currentFilter === 'semua') {
                matchTab = true;
            } else if (currentFilter === 'perlu') {
                matchTab = (status !== 'aktif');
            } else if (currentFilter === 'aktif') {
                matchTab = (status === 'aktif');
            }

            // Cek kecocokan Search
            let matchSearch = true;
            if (searchQuery) {
                matchSearch = nama.includes(searchQuery) || 
                              email.includes(searchQuery) || 
                              noReg.includes(searchQuery);
            }

            // Terapkan tampilan
            card.style.display = (matchTab && matchSearch) ? '' : 'none';
        });
    }

    // 4. Event Listener Search (AMAN: sudah dicek apakah elemen ada)
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            searchQuery = e.target.value.toLowerCase().trim();
            applyFilters();
        });
    }

    // 5. Event Listener Tabs
    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            // Reset style semua tab
            tabs.forEach(t => {
                t.classList.remove('active', 'bg-gold', 'text-white');
                t.classList.add('bg-white', 'border', 'border-[#e3ded6]', 'text-[#22201d]');
            });

            // Set style tab yang diklik
            this.classList.add('active', 'bg-gold', 'text-white');
            this.classList.remove('bg-white', 'border', 'border-[#e3ded6]', 'text-[#22201d]');

            // Update filter dan jalankan
            currentFilter = this.dataset.tab;
            applyFilters();
        });
    });
});
</script>
</body>
</html>