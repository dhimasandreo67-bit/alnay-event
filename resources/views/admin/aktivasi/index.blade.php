<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    @include('admin.partials.pwa-head')
    <title>Aktivasi Akun Peserta | ALNAY.ID</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { gold: '#ba8320', cream: '#f7f5f0' } } } };
    </script>
</head>
<body class="bg-cream font-['Inter'] text-[13px] text-[#22201d]">
<div class="flex min-h-screen">
    @include('admin.partials.sidebar', ['active' => 'activation', 'event' => $event])

    <main class="flex-1 min-w-0 p-6 ml-[235px]">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h1 class="text-2xl font-bold m-0">Verifikasi Pembayaran &amp; Aktivasi Akun</h1>
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
                <div class="text-[10px] uppercase text-[#98958f] font-bold">Akun Sudah Aktif &amp; Lunas</div>
                <div class="text-3xl font-bold mt-1 text-emerald-600">{{ $lunas }} <span class="text-sm text-[#98958f] font-normal">Finalis Aktif</span></div>
            </div>
        </div>

        {{-- Tabs + search --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 mb-4">
            <button data-tab="semua"
                    class="tab-btn active px-4 py-2 rounded-lg text-[12px] font-semibold bg-gold text-white">
                Semua ({{ $totalFinalis }})
            </button>

            <button data-tab="perlu"
                    class="tab-btn px-4 py-2 rounded-lg text-[12px] font-semibold bg-white border border-[#e3ded6]">
                Perlu Verifikasi ({{ $menungguVerifikasi }})
            </button>

            <button data-tab="aktif"
                    class="tab-btn px-4 py-2 rounded-lg text-[12px] font-semibold bg-white border border-[#e3ded6]">
                Sudah Aktif / Lunas ({{ $lunas }})
            </button>

            <input id="cariFinalis"
                type="text"
                placeholder="Cari nama, no. registrasi, email..."
                class="md:col-span-3 w-full border border-[#e3ded6] rounded-lg px-3 py-2 text-[12px]">
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

                        {{-- TAMBAHKAN BADGE NOMINAL UNIK DI SINI --}}
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

                    {{-- TAMBAHKAN KODE UNIK --}}
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
                <p class="text-[#98958f] text-sm">Pilih peserta di kiri untuk lihat detail.</p>
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
                                class="px-4 py-2 text-[12px] font-bold bg-gold text-white rounded-lg hover:bg-[#a06f1a]">
                            Cek &amp; Sinkronkan Status Pembayaran
                        </button>
                    </form>
                </div>
            </div>
        </template>
    @endforeach
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cards = document.querySelectorAll('.peserta-card');
        const panel = document.getElementById('panelDetail');
        const searchInput = document.getElementById('cariFinalis');
        const tabs = document.querySelectorAll('.tab-btn');
        let activeTab = 'semua';

        // 1. FUNGSI MENAMPILKAN DETAIL PESERTA
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

        // Show detail kartu pertama saat pertama dimuat
        if (cards.length > 0) {
            const firstId = cards[0].id.replace('card-', '');
            showDetail(firstId);
        }

        // 2. FUNGSI FILTER TAB & SEARCH
        function applyFilters() {
            const query = searchInput ? searchInput.value.toLowerCase().trim() : '';

            cards.forEach(card => {
                const status = card.dataset.status; // 'aktif' atau 'terkunci' / 'pending'
                const nama = card.dataset.nama || '';
                const email = card.dataset.email || '';
                const noReg = card.dataset.noReg || '';

                // Filter Tab Status
                let matchTab = false;
                if (activeTab === 'semua') {
                    matchTab = true;
                } else if (activeTab === 'perlu') {
                    matchTab = (status !== 'aktif');
                } else if (activeTab === 'aktif') {
                    matchTab = (status === 'aktif');
                }

                // Filter Pencarian
                let matchSearch = true;
                if (query) {
                    matchSearch = nama.includes(query) || 
                                  email.includes(query) || 
                                  noReg.includes(query);
                }

                // Tampilkan / Sembunyikan elemen
                if (matchTab && matchSearch) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Event Listener Input Pencarian
        if (searchInput) {
            searchInput.addEventListener('input', applyFilters);
        }

        // Event Listener Tab Click & Styling Active
        tabs.forEach(tab => {
            tab.addEventListener('click', function () {
                // Reset style semua tab
                tabs.forEach(t => {
                    t.classList.remove('active', 'bg-gold', 'text-white');
                    t.classList.add('bg-white', 'border', 'border-[#e3ded6]', 'text-[#22201d]');
                });

                // Set style tab aktif
                this.classList.add('active', 'bg-gold', 'text-white');
                this.classList.remove('bg-white', 'border', 'border-[#e3ded6]', 'text-[#22201d]');

                activeTab = this.dataset.tab; // 'semua', 'perlu', atau 'aktif'
                applyFilters();
            });
        });
    });

    let currentFilter = 'semua';
    let searchQuery = '';

    function filterPeserta(status) {
        currentFilter = status;
        applyFilters();
        updateTabStyles();
    }

    function updateTabStyles() {
        const tabs = ['semua', 'perlu_verifikasi', 'lunas'];
        tabs.forEach(tab => {
            const tabEl = document.getElementById('tab-' + tab);
            if (tab === currentFilter) {
                tabEl.className = 'px-4 py-2 bg-amber-600 text-white text-sm font-medium rounded-lg transition';
            } else {
                tabEl.className = 'px-4 py-2 bg-white border border-slate-200 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-50 transition';
            }
        });
    }

    function applyFilters() {
        const cards = document.querySelectorAll('.peserta-card');
        cards.forEach(card => {
            const cardStatus = card.dataset.status;
            const cardNama = card.dataset.nama;
            const cardEmail = card.dataset.email;
            const cardNoReg = card.dataset.noReg;

            let statusMatch = false;
            if (currentFilter === 'semua') {
                statusMatch = true;
            } else if (currentFilter === 'perlu_verifikasi') {
                statusMatch = cardStatus !== 'aktif';
            } else if (currentFilter === 'lunas') {
                statusMatch = cardStatus === 'aktif';
            }

            let searchMatch = true;
            if (searchQuery) {
                searchMatch = cardNama.includes(searchQuery) || 
                              cardEmail.includes(searchQuery) || 
                              cardNoReg.includes(searchQuery);
            }

            card.style.display = (statusMatch && searchMatch) ? '' : 'none';
        });
    }

    document.getElementById('search-input').addEventListener('input', function(e) {
        searchQuery = e.target.value.toLowerCase();
        applyFilters();
    });

    document.addEventListener('DOMContentLoaded', function() {
        const firstCard = document.querySelector('.peserta-card');
        if (firstCard) {
            const id = firstCard.id.replace('card-', '');
            showDetail(id);
        }
    });
</script>
</body>
</html>