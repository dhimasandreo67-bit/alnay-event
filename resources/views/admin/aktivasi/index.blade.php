<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Verifikasi Pembayaran | ALNAY.ID</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>

<body class="bg-cream font-['Inter'] text-[13px] text-[#22201d]">
    <div class="flex min-h-screen">
        @include('admin.partials.sidebar', ['active' => 'activation', 'event' => $event])
        @include('admin.partials.pwa-head')

        <main class="flex-1 min-w-0 p-6 ml-[235px]">

            {{-- HEADER + MODE SWITCH --}}
            <div class="flex items-start justify-between gap-4 mb-6 flex-wrap">
                <div class="min-w-0">
                    <h1 class="text-2xl font-bold m-0">Verifikasi Pembayaran</h1>
                    <p class="text-[#73716c] text-[12px] mt-1 max-w-2xl">
                        Cek bukti pembayaran dan sinkronkan status dengan Midtrans.
                    </p>
                </div>

                <div class="inline-flex items-center gap-0 bg-white border border-[#e3ded6] rounded-xl p-1 shadow-sm">
                    <a href="{{ route('admin.activation', ['event' => $event?->id, 'mode' => 'pendaftaran']) }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-[12px] font-semibold transition-all
                          {{ ($mode ?? 'pendaftaran') === 'pendaftaran'
                              ? 'bg-gold text-white shadow-sm'
                              : 'text-[#73716c] hover:bg-[#f7f5f0] hover:text-[#22201d]' }}">
                        Pendaftaran
                    </a>
                    <a href="{{ route('admin.activation', ['event' => $event?->id, 'mode' => 'registrasi']) }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-[12px] font-semibold transition-all
                          {{ ($mode ?? '') === 'registrasi'
                              ? 'bg-gold text-white shadow-sm'
                              : 'text-[#73716c] hover:bg-[#f7f5f0] hover:text-[#22201d]' }}">
                        Registrasi
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-4 rounded-lg bg-emerald-50 text-emerald-700 px-4 py-3 text-sm border border-emerald-200">
                    {{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 rounded-lg bg-rose-50 text-rose-700 px-4 py-3 text-sm border border-rose-200">
                    {{ session('error') }}</div>
            @endif

            @php $isModeRegistrasi = ($mode ?? 'pendaftaran') === 'registrasi'; @endphp

            {{-- ============================== --}}
            {{-- STAT CARDS & FILTER             --}}
            {{-- ============================== --}}
            @if (!$isModeRegistrasi)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
                    <div class="bg-white rounded-xl border border-[#e3ded6] p-4">
                        <div class="text-[10px] uppercase text-[#98958f] font-bold">Total Peserta</div>
                        <div class="text-3xl font-bold mt-1">{{ $totalFinalis }}
                            <span class="text-sm text-[#98958f] font-normal">/ {{ $event?->kuota_peserta ?? '-' }} Kuota</span>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl border border-[#e3ded6] p-4">
                        <div class="text-[10px] uppercase text-[#98958f] font-bold">Belum Bayar</div>
                        <div class="text-3xl font-bold mt-1 text-amber-600">{{ $menungguVerifikasi }}
                            <span class="text-sm text-[#98958f] font-normal">Peserta</span>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl border border-[#e3ded6] p-4">
                        <div class="text-[10px] uppercase text-[#98958f] font-bold">Sudah Lunas</div>
                        <div class="text-3xl font-bold mt-1 text-emerald-600">{{ $lunas }}
                            <span class="text-sm text-[#98958f] font-normal">Peserta</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 mb-4">
                    <button type="button" data-tab="semua"
                        class="tab-btn active px-4 py-2 rounded-lg text-[12px] font-semibold bg-gold text-white transition">
                        Semua ({{ $totalFinalis }})
                    </button>
                    <button type="button" data-tab="belum-bayar"
                        class="tab-btn px-4 py-2 rounded-lg text-[12px] font-semibold bg-white border border-[#e3ded6] text-[#22201d] transition">
                        Belum Bayar ({{ $menungguVerifikasi }})
                    </button>
                    <button type="button" data-tab="aktif"
                        class="tab-btn px-4 py-2 rounded-lg text-[12px] font-semibold bg-white border border-[#e3ded6] text-[#22201d] transition">
                        Sudah Lunas ({{ $lunas }})
                    </button>
                    <input id="cariFinalis" type="text" placeholder="Cari nama, no. registrasi, email..."
                        class="md:col-span-3 w-full border border-[#e3ded6] rounded-lg px-3 py-2 text-[12px] focus:outline-none focus:ring-2 focus:ring-gold/30">
                </div>
            @else
                <div class="flex flex-wrap items-center gap-2 mb-4 p-3 bg-[#faf8f2] border border-[#e3ded6] rounded-lg">
                    <span class="text-[11px] font-bold text-slate-600 uppercase tracking-wider">Tampilan Publik:</span>

                    <form action="{{ route('admin.activation.toggle-tampil-semua', $event?->id) }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="tampil" value="1">
                        <input type="hidden" name="mode" value="registrasi">
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-emerald-500 hover:bg-emerald-600 text-white text-[11px] font-bold transition">
                            Tampilkan Semua
                        </button>
                    </form>

                    <form action="{{ route('admin.activation.toggle-tampil-semua', $event?->id) }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="tampil" value="0">
                        <input type="hidden" name="mode" value="registrasi">
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-rose-500 hover:bg-rose-600 text-white text-[11px] font-bold transition">
                            Sembunyikan Semua
                        </button>
                    </form>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
                    <div class="bg-white rounded-xl border border-[#e3ded6] p-4">
                        <div class="text-[10px] uppercase text-[#98958f] font-bold">Total Peserta Lolos Audisi</div>
                        <div class="text-3xl font-bold mt-1">{{ $totalFinalis }}</div>
                    </div>
                    <div class="bg-white rounded-xl border border-[#e3ded6] p-4">
                        <div class="text-[10px] uppercase text-[#98958f] font-bold">Sudah Bayar (DP + Lunas)</div>
                        <div class="text-3xl font-bold mt-1 text-amber-600">{{ $registrasiDp + $registrasiLunas }}</div>
                    </div>
                    <div class="bg-white rounded-xl border border-[#e3ded6] p-4">
                        <div class="text-[10px] uppercase text-[#98958f] font-bold">Lunas</div>
                        <div class="text-3xl font-bold mt-1 text-emerald-700">{{ $registrasiLunas }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-2 mb-4">
                    <button type="button" data-tab="semua"
                        class="tab-btn active px-4 py-2 rounded-lg text-[12px] font-semibold bg-gold text-white transition">
                        Semua ({{ $totalFinalis }})
                    </button>
                    <button type="button" data-tab="belum-bayar"
                        class="tab-btn px-4 py-2 rounded-lg text-[12px] font-semibold bg-white border border-[#e3ded6] text-[#22201d] transition">
                        Belum Bayar
                    </button>
                    <button type="button" data-tab="dp"
                        class="tab-btn px-4 py-2 rounded-lg text-[12px] font-semibold bg-white border border-[#e3ded6] text-[#22201d] transition">
                        DP
                    </button>
                    <button type="button" data-tab="lunas"
                        class="tab-btn px-4 py-2 rounded-lg text-[12px] font-semibold bg-white border border-[#e3ded6] text-[#22201d] transition">
                        Lunas
                    </button>
                    <input id="cariFinalis" type="text" placeholder="Cari nama, no. registrasi, email..."
                        class="md:col-span-4 w-full border border-[#e3ded6] rounded-lg px-3 py-2 text-[12px] focus:outline-none focus:ring-2 focus:ring-gold/30">
                </div>
            @endif

            {{-- ============================== --}}
            {{-- GRID: LIST KIRI + PANEL KANAN   --}}
            {{-- ============================== --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">

                {{-- ===== KOLOM KIRI: LIST PESERTA ===== --}}
                <div class="space-y-3">
                    @forelse ($finalis as $f)
                        @php
                            if ($isModeRegistrasi) {
                                $statusReg = $f['statusRegistrasi'] ?? 'belum';
                                $badge = match ($statusReg) {
                                    'lunas' => ['label' => 'Lunas', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                                    'dp'    => ['label' => 'DP',    'class' => 'bg-amber-50 text-amber-700 border-amber-200'],
                                    default => ['label' => 'Belum Bayar', 'class' => 'bg-rose-50 text-rose-700 border-rose-200'],
                                };
                                $dataStatus = $statusReg === 'lunas' ? 'lunas' : ($statusReg === 'dp' ? 'dp' : 'belum-bayar');
                            } else {
                                $sudahBayarPendaftaran = $f['statusAkun'] === 'aktif';
                                $badge = $sudahBayarPendaftaran
                                    ? ['label' => 'Lunas', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200']
                                    : ['label' => 'Belum Bayar', 'class' => 'bg-rose-50 text-rose-700 border-rose-200'];
                                $dataStatus = $sudahBayarPendaftaran ? 'aktif' : 'belum-bayar';
                            }
                        @endphp

                        <div class="bg-white rounded-xl border border-slate-200 p-4 hover:border-amber-300 transition cursor-pointer peserta-card"
                            onclick="showDetail({{ $f['id'] }})" id="card-{{ $f['id'] }}"
                            data-status="{{ $dataStatus }}"
                            data-nama="{{ strtolower($f['nama']) }}"
                            data-email="{{ strtolower($f['email']) }}"
                            data-no-reg="{{ strtolower($f['no_registrasi']) }}">

                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-mono text-slate-500">#{{ $f['no_registrasi'] }}</span>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold border {{ $badge['class'] }}">
                                    {{ $badge['label'] }}
                                </span>
                            </div>

                            <h3 class="font-semibold text-slate-800">{{ $f['nama'] }}</h3>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $f['email'] }}</p>

                            @if (!$isModeRegistrasi)
                                @if ($f['pembayaran'])
                                    <p class="text-xs text-slate-500 mt-2">
                                        {{ $f['pembayaran']->metode ?? 'Transfer Bank' }} ·
                                        Rp {{ number_format($f['pembayaran']->jumlah, 0, ',', '.') }} ·
                                        {{ $f['pembayaran']->tanggal_bayar?->diffForHumans() ?? 'belum bayar' }}
                                    </p>
                                @else
                                    <p class="text-xs text-rose-500 mt-2">Belum ada transaksi pembayaran</p>
                                @endif
                            @else
                                <form action="{{ route('admin.activation.toggle-tampil', $f['id']) }}" method="POST"
                                    class="mt-2 toggle-tampil-form" onclick="event.stopPropagation();">
                                    @csrf
                                    <input type="hidden" name="mode" value="registrasi">
                                    <button type="submit"
                                        class="toggle-tampil-btn w-full inline-flex items-center justify-center gap-1.5 text-[11px] font-bold px-3 py-1.5 rounded-lg transition
                                        {{ $f['tampilDiPublik'] ?? true ? 'bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 border border-gray-200' }}">
                                        @if ($f['tampilDiPublik'] ?? true)
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                            Tampil di Publik
                                        @else
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                                <line x1="1" y1="1" x2="23" y2="23"/>
                                            </svg>
                                            Disembunyikan
                                        @endif
                                    </button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <div class="bg-white rounded-xl border border-slate-200 p-8 text-center">
                            <p class="text-slate-500">Belum ada peserta untuk event ini.</p>
                        </div>
                    @endforelse
                </div>

                {{-- ===== KOLOM KANAN: PANEL DETAIL ===== --}}
                <div class="bg-white border border-[#e3ded6] rounded-xl p-5 sticky top-6 max-h-[calc(100vh-48px)] overflow-y-auto"
                    id="panelDetail">
                    <p class="text-[#98958f] text-sm text-center py-10">Pilih peserta di kiri untuk melihat detail.</p>
                </div>
            </div>
        </main>
    </div>

    {{-- TEMPLATE DETAIL --}}
    <div class="hidden">
        @foreach ($finalis as $f)
            <template id="detail-{{ $f['id'] }}">
                <div>
                    {{-- HEADER --}}
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <div class="text-[10px] text-[#98958f]">No. Reg: #{{ $f['no_registrasi'] }}</div>
                            <h2 class="text-xl font-bold">{{ $f['nama'] }}</h2>
                            <p class="text-[11px] text-[#73716c]">{{ $f['email'] }}</p>
                        </div>
                    </div>

                    @if (!$isModeRegistrasi)
                        {{-- ============ MODE PENDAFTARAN ============ --}}
                        @if ($f['pembayaran'])
                            @php $p = $f['pembayaran']; @endphp
                            <div class="bg-[#f7f5f0] rounded-lg p-4 mb-4">
                                <div class="text-[10px] uppercase text-[#98958f] font-bold mb-3">Bukti Pembayaran Pendaftaran</div>
                                <div class="space-y-2 text-[12px]">
                                    <div class="flex justify-between"><span class="text-slate-500">Order ID</span><strong class="font-mono text-[10px]">{{ $p->midtrans_order_id ?? '-' }}</strong></div>
                                    <div class="flex justify-between"><span class="text-slate-500">Transaction ID</span><strong class="font-mono text-[10px]">{{ $p->midtrans_transaction_id ?? '-' }}</strong></div>
                                    <div class="flex justify-between"><span class="text-slate-500">Metode</span><strong>{{ $p->metode ?? '-' }}</strong></div>
                                    <div class="flex justify-between"><span class="text-slate-500">Nominal</span><strong>Rp {{ number_format($p->jumlah, 0, ',', '.') }}</strong></div>
                                    <div class="flex justify-between"><span class="text-slate-500">Status</span>
                                        <strong class="uppercase {{ $p->status_midtrans === 'settlement' ? 'text-emerald-700' : 'text-amber-700' }}">
                                            {{ $p->status_midtrans ?? '-' }}
                                        </strong>
                                    </div>
                                    <div class="flex justify-between"><span class="text-slate-500">Tanggal</span><strong>{{ $p->tanggal_bayar?->format('d M Y, H:i') ?? '-' }}</strong></div>
                                </div>
                            </div>

                            {{-- Tombol Sinkronkan --}}
                            <form action="{{ route('admin.activation.verifikasi', $f['id']) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gold text-white font-bold rounded-md hover:bg-[#a3721a] transition text-[12px]">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="23 4 23 10 17 10"/>
                                        <polyline points="1 20 1 14 7 14"/>
                                        <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/>
                                    </svg>
                                    Sinkronkan Status Pembayaran
                                </button>
                            </form>
                        @else
                            <div class="bg-[#f7f5f0] rounded-lg p-4 mb-4 text-center">
                                <p class="text-[12px] text-slate-500">Belum ada transaksi pembayaran pendaftaran.</p>
                            </div>
                        @endif
                    @else
                        {{-- ============ MODE REGISTRASI ============ --}}
                        @if ($f['dp1'])
                            <div class="bg-white border border-[#e3ded6] rounded-lg p-4 mb-3">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[11px] font-bold text-slate-700">DP Gelombang 1</span>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full uppercase
                                        {{ $f['dp1']->status_midtrans === 'settlement' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ $f['dp1']->status_midtrans }}
                                    </span>
                                </div>
                                <div class="space-y-1 text-[11px]">
                                    <div class="flex justify-between"><span class="text-[#73716c]">Nominal</span><strong>Rp {{ number_format($f['dp1']->jumlah, 0, ',', '.') }}</strong></div>
                                    <div class="flex justify-between"><span class="text-[#73716c]">Order ID</span><strong class="font-mono text-[10px]">{{ $f['dp1']->midtrans_order_id ?? '-' }}</strong></div>
                                    <div class="flex justify-between"><span class="text-[#73716c]">Metode</span><strong>{{ $f['dp1']->metode ?? '-' }}</strong></div>
                                    <div class="flex justify-between"><span class="text-[#73716c]">Bayar</span><strong>{{ $f['dp1']->tanggal_bayar?->format('d M Y, H:i') ?? '-' }}</strong></div>
                                </div>
                            </div>
                        @endif

                        @if ($f['dp2'])
                            <div class="bg-white border border-[#e3ded6] rounded-lg p-4 mb-3">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[11px] font-bold text-slate-700">DP Gelombang 2 (Pelunasan)</span>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full uppercase
                                        {{ $f['dp2']->status_midtrans === 'settlement' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ $f['dp2']->status_midtrans }}
                                    </span>
                                </div>
                                <div class="space-y-1 text-[11px]">
                                    <div class="flex justify-between"><span class="text-[#73716c]">Nominal</span><strong>Rp {{ number_format($f['dp2']->jumlah, 0, ',', '.') }}</strong></div>
                                    <div class="flex justify-between"><span class="text-[#73716c]">Order ID</span><strong class="font-mono text-[10px]">{{ $f['dp2']->midtrans_order_id ?? '-' }}</strong></div>
                                    <div class="flex justify-between"><span class="text-[#73716c]">Metode</span><strong>{{ $f['dp2']->metode ?? '-' }}</strong></div>
                                    <div class="flex justify-between"><span class="text-[#73716c]">Bayar</span><strong>{{ $f['dp2']->tanggal_bayar?->format('d M Y, H:i') ?? '-' }}</strong></div>
                                </div>
                            </div>
                        @endif

                        @if ($f['lunas'])
                            <div class="bg-white border border-[#e3ded6] rounded-lg p-4 mb-3">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[11px] font-bold text-slate-700">Pembayaran Lunas</span>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full uppercase
                                        {{ $f['lunas']->status_midtrans === 'settlement' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ $f['lunas']->status_midtrans }}
                                    </span>
                                </div>
                                <div class="space-y-1 text-[11px]">
                                    <div class="flex justify-between"><span class="text-[#73716c]">Nominal</span><strong>Rp {{ number_format($f['lunas']->jumlah, 0, ',', '.') }}</strong></div>
                                    <div class="flex justify-between"><span class="text-[#73716c]">Order ID</span><strong class="font-mono text-[10px]">{{ $f['lunas']->midtrans_order_id ?? '-' }}</strong></div>
                                    <div class="flex justify-between"><span class="text-[#73716c]">Metode</span><strong>{{ $f['lunas']->metode ?? '-' }}</strong></div>
                                    <div class="flex justify-between"><span class="text-[#73716c]">Bayar</span><strong>{{ $f['lunas']->tanggal_bayar?->format('d M Y, H:i') ?? '-' }}</strong></div>
                                </div>
                            </div>
                        @endif

                        @if (!$f['dp1'] && !$f['dp2'] && !$f['lunas'])
                            <div class="bg-[#f7f5f0] rounded-lg p-4 mb-4 text-center">
                                <p class="text-[12px] text-slate-500">Peserta belum melakukan pembayaran registrasi.</p>
                            </div>
                        @endif

                        {{-- Tombol Sinkronkan Registrasi --}}
                        <form action="{{ route('admin.activation.verifikasi', $f['id']) }}" method="POST" class="mb-3">
                            @csrf
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gold text-white font-bold rounded-md hover:bg-[#a3721a] transition text-[12px]">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="23 4 23 10 17 10"/>
                                    <polyline points="1 20 1 14 7 14"/>
                                    <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/>
                                </svg>
                                Sinkronkan Status Registrasi
                            </button>
                        </form>

                        
                    @endif
                </div>
            </template>
        @endforeach
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const cards = document.querySelectorAll('.peserta-card');
        const panel = document.getElementById('panelDetail');

        window.showDetail = function (id) {
            const tpl = document.getElementById('detail-' + id);
            if (tpl && panel) panel.innerHTML = tpl.innerHTML;

            cards.forEach(c => c.classList.remove('border-gold', 'ring-1', 'ring-gold'));
            const activeCard = document.getElementById('card-' + id);
            if (activeCard) activeCard.classList.add('border-gold', 'ring-1', 'ring-gold');
        };

        if (cards.length > 0) {
            const firstId = cards[0].id.replace('card-', '');
            showDetail(firstId);
        }

        const searchInput = document.getElementById('cariFinalis');
        const tabs = document.querySelectorAll('.tab-btn');

        let currentFilter = 'semua';
        let searchQuery = '';

        function applyFilters() {
            cards.forEach(card => {
                const status = card.dataset.status;
                const nama = card.dataset.nama || '';
                const email = card.dataset.email || '';
                const noReg = card.dataset.noReg || '';

                let matchTab = false;
                if (currentFilter === 'semua') matchTab = true;
                else if (currentFilter === 'perlu') matchTab = (status !== 'aktif');
                else if (currentFilter === 'aktif') matchTab = (status === 'aktif');
                else if (currentFilter === 'belum-bayar') matchTab = (status === 'belum-bayar');
                else if (currentFilter === 'dp') matchTab = (status === 'dp');
                else if (currentFilter === 'lunas') matchTab = (status === 'lunas');

                let matchSearch = true;
                if (searchQuery) {
                    matchSearch = nama.includes(searchQuery) ||
                                  email.includes(searchQuery) ||
                                  noReg.includes(searchQuery);
                }

                card.style.display = (matchTab && matchSearch) ? '' : 'none';
            });
        }

        if (searchInput) {
            searchInput.addEventListener('input', function (e) {
                searchQuery = e.target.value.toLowerCase().trim();
                applyFilters();
            });
        }

        tabs.forEach(tab => {
            tab.addEventListener('click', function () {
                tabs.forEach(t => {
                    t.classList.remove('active', 'bg-gold', 'text-white');
                    t.classList.add('bg-white', 'border', 'border-[#e3ded6]', 'text-[#22201d]');
                });
                this.classList.add('active', 'bg-gold', 'text-white');
                this.classList.remove('bg-white', 'border', 'border-[#e3ded6]', 'text-[#22201d]');
                currentFilter = this.dataset.tab;
                applyFilters();
            });
        });

        document.querySelectorAll('.toggle-tampil-form').forEach(form => {
            form.addEventListener('submit', async function (e) {
                e.preventDefault();
                e.stopPropagation();

                const btn = form.querySelector('.toggle-tampil-btn');
                if (!btn) return;

                const originalHTML = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = 'Memproses...';

                try {
                    const token = form.querySelector('input[name="_token"]').value;
                    const res = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: new FormData(form),
                    });

                    const data = await res.json();
                    if (!res.ok || !data.success) throw new Error(data.message || 'Gagal.');

                    // Icon SVG + text
                    const iconEye = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
                    const iconEyeOff = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';

                    btn.innerHTML = (data.tampilDiPublik ? iconEye + ' Tampil di Publik' : iconEyeOff + ' Disembunyikan');
                    btn.classList.remove(
                        'bg-blue-50', 'text-blue-700', 'hover:bg-blue-100', 'border-blue-200',
                        'bg-gray-100', 'text-gray-600', 'hover:bg-gray-200', 'border-gray-200'
                    );

                    if (data.tampilDiPublik) {
                        btn.classList.add('bg-blue-50', 'text-blue-700', 'hover:bg-blue-100', 'border-blue-200');
                    } else {
                        btn.classList.add('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200', 'border-gray-200');
                    }

                    if (typeof toast === 'function') toast(data.message, 'success');
                } catch (err) {
                    btn.innerHTML = originalHTML;
                    alert('Gagal: ' + err.message);
                } finally {
                    btn.disabled = false;
                }
            });
        });
    });
    </script>
</body>

</html>