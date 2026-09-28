<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
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
        <div class="grid grid-cols-3 gap-4 mb-5">
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
        <div class="flex items-center gap-2 mb-4">
            <button data-tab="semua" class="tab-btn active px-4 py-2 rounded-lg text-[12px] font-semibold bg-gold text-white">Semua ({{ $totalFinalis }})</button>
            <button data-tab="perlu" class="tab-btn px-4 py-2 rounded-lg text-[12px] font-semibold bg-white border border-[#e3ded6]">Perlu Verifikasi ({{ $menungguVerifikasi }})</button>
            <button data-tab="aktif" class="tab-btn px-4 py-2 rounded-lg text-[12px] font-semibold bg-white border border-[#e3ded6]">Sudah Aktif / Lunas ({{ $lunas }})</button>
            <input id="cariFinalis" type="text" placeholder="Cari nama, no. registrasi, email..."
                   class="ml-auto border border-[#e3ded6] rounded-lg px-3 py-2 text-[12px] w-72">
        </div>

        <div class="grid grid-cols-[1fr_1.3fr] gap-5 items-start">

            {{-- ===== Kolom kiri: antrean ===== --}}
            <div class="space-y-3" id="listFinalis">
                @forelse ($finalis as $f)
                    <button type="button"
                        class="finalis-card w-full text-left bg-white border rounded-xl p-4 hover:border-gold transition {{ $loop->first ? 'border-gold ring-1 ring-gold' : 'border-[#e3ded6]' }}"
                        data-id="{{ $f['id'] }}"
                        data-status="{{ $f['statusAkun'] }}"
                        data-search="{{ strtolower($f['nama'].' '.$f['no_registrasi'].' '.$f['email']) }}">
                        <div class="flex items-start justify-between">
                            <div>
                                <div class="text-[10px] text-[#98958f]">#{{ $f['no_registrasi'] }}</div>
                                <div class="font-bold text-[14px]">{{ $f['nama'] }}</div>
                            </div>
                            @if ($f['statusAkun'] === 'aktif')
                                <span class="text-[10px] font-bold px-2 py-1 rounded-full bg-emerald-100 text-emerald-700">Lunas &amp; Aktif</span>
                            @else
                                <span class="text-[10px] font-bold px-2 py-1 rounded-full bg-amber-100 text-amber-700">Menunggu Verifikasi</span>
                            @endif
                        </div>
                        <div class="text-[11px] text-[#73716c] mt-2 flex items-center gap-2">
                            @if ($f['pembayaran'])
                                <span>{{ $f['pembayaran']->metode ?? 'Midtrans' }} · Rp {{ number_format($f['pembayaran']->jumlah, 0, ',', '.') }}</span>
                                <span>· {{ $f['pembayaran']->tanggal_bayar?->diffForHumans() ?? 'belum bayar' }}</span>
                            @else
                                <span class="text-rose-500">Belum ada transaksi pembayaran</span>
                            @endif
                        </div>
                    </button>
                @empty
                    <div class="bg-white border border-dashed border-[#e3ded6] rounded-xl p-8 text-center text-[#98958f]">
                        Belum ada finalis yang diterima untuk event ini.
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
    const cards = document.querySelectorAll('.finalis-card');
    const panel = document.getElementById('panelDetail');
    const search = document.getElementById('cariFinalis');
    const tabs = document.querySelectorAll('.tab-btn');
    let activeTab = 'semua';

    function showDetail(id) {
        const tpl = document.getElementById('detail-' + id);
        if (tpl) panel.innerHTML = tpl.innerHTML;

        cards.forEach(c => c.classList.remove('border-gold', 'ring-1', 'ring-gold'));
        const card = document.querySelector(`.finalis-card[data-id="${id}"]`);
        if (card) card.classList.add('border-gold', 'ring-1', 'ring-gold');
    }

    cards.forEach(card => {
        card.addEventListener('click', () => showDetail(card.dataset.id));
    });

    if (cards.length) showDetail(cards[0].dataset.id);

    function applyFilter() {
        const q = search.value.toLowerCase();
        cards.forEach(card => {
            const matchSearch = card.dataset.search.includes(q);
            const matchTab = activeTab === 'semua' ||
                (activeTab === 'perlu' && card.dataset.status === 'terkunci') ||
                (activeTab === 'aktif' && card.dataset.status === 'aktif');
            card.style.display = (matchSearch && matchTab) ? '' : 'none';
        });
    }

    search.addEventListener('input', applyFilter);
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active', 'bg-gold', 'text-white'));
            tabs.forEach(t => t.classList.add('bg-white', 'border', 'border-[#e3ded6]'));
            tab.classList.add('active', 'bg-gold', 'text-white');
            tab.classList.remove('bg-white', 'border', 'border-[#e3ded6]');
            activeTab = tab.dataset.tab;
            applyFilter();
        });
    });
</script>
</body>
</html>