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

        {{-- ============================== --}}
        {{-- HEADER + MODE SWITCH            --}}
        {{-- ============================== --}}
        <div class="flex items-start justify-between gap-4 mb-6 flex-wrap">
            <div class="min-w-0">
                <h1 class="text-2xl font-bold m-0">Verifikasi Pembayaran & Aktivasi Akun</h1>
                <p class="text-[#73716c] text-[12px] mt-1 max-w-2xl">
                    Tinjau status pembayaran peserta yang sudah diterima untuk mengaktifkan akses akun resmi mereka.
                </p>
            </div>

            {{-- Mode Switch — kanan atas --}}
            <div class="inline-flex items-center gap-0 bg-white border border-[#e3ded6] rounded-xl p-1 shadow-sm">
                <a href="{{ route('admin.activation', ['event' => $event?->id, 'mode' => 'pendaftaran']) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-[12px] font-semibold transition-all
                          {{ ($mode ?? 'pendaftaran') === 'pendaftaran'
                                ? 'bg-gold text-white shadow-sm'
                                : 'text-[#73716c] hover:bg-[#f7f5f0] hover:text-[#22201d]' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 11H3v10h6V11zM21 3h-6v18h6V3zM15 7H9v14h6V7z"/>
                    </svg>
                    Pendaftaran
                </a>
                <a href="{{ route('admin.activation', ['event' => $event?->id, 'mode' => 'registrasi']) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-[12px] font-semibold transition-all
                          {{ ($mode ?? '') === 'registrasi'
                                ? 'bg-gold text-white shadow-sm'
                                : 'text-[#73716c] hover:bg-[#f7f5f0] hover:text-[#22201d]' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="9" y1="15" x2="15" y2="15"/>
                    </svg>
                    Registrasi
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-lg bg-emerald-50 text-emerald-700 px-4 py-3 text-sm border border-emerald-200">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 rounded-lg bg-rose-50 text-rose-700 px-4 py-3 text-sm border border-rose-200">{{ session('error') }}</div>
        @endif

        {{-- ============================== --}}
        {{-- STAT CARDS                      --}}
        {{-- ============================== --}}
        @if (($mode ?? 'pendaftaran') === 'pendaftaran')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
                <div class="bg-white rounded-xl border border-[#e3ded6] p-4">
                    <div class="text-[10px] uppercase text-[#98958f] font-bold">Total Finalis Diterima</div>
                    <div class="text-3xl font-bold mt-1">{{ $totalFinalis }}
                        <span class="text-sm text-[#98958f] font-normal">/ {{ $event?->kuota_peserta ?? '-' }} Kuota</span>
                    </div>
                </div>
                <div class="bg-white rounded-xl border border-[#e3ded6] p-4">
                    <div class="text-[10px] uppercase text-[#98958f] font-bold">Menunggu Verifikasi</div>
                    <div class="text-3xl font-bold mt-1 text-amber-600">{{ $menungguVerifikasi }}
                        <span class="text-sm text-[#98958f] font-normal">Peserta</span>
                    </div>
                </div>
                <div class="bg-white rounded-xl border border-[#e3ded6] p-4">
                    <div class="text-[10px] uppercase text-[#98958f] font-bold">Akun Sudah Aktif & Lunas</div>
                    <div class="text-3xl font-bold mt-1 text-emerald-600">{{ $lunas }}
                        <span class="text-sm text-[#98958f] font-normal">Finalis Aktif</span>
                    </div>
                </div>
            </div>

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
                <input id="cariFinalis" type="text" placeholder="Cari nama, no. registrasi, email..."
                    class="md:col-span-3 w-full border border-[#e3ded6] rounded-lg px-3 py-2 text-[12px] focus:outline-none focus:ring-2 focus:ring-gold/30">
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-5">
                <div class="bg-white rounded-xl border border-[#e3ded6] p-4">
                    <div class="text-[10px] uppercase text-[#98958f] font-bold">Total Peserta</div>
                    <div class="text-3xl font-bold mt-1">{{ $totalFinalis }}</div>
                </div>
                <div class="bg-white rounded-xl border border-[#e3ded6] p-4">
                    <div class="text-[10px] uppercase text-[#98958f] font-bold">Sudah Audisi</div>
                    <div class="text-3xl font-bold mt-1 text-emerald-600">{{ $sudahAudisi }}</div>
                </div>
                <div class="bg-white rounded-xl border border-[#e3ded6] p-4">
                    <div class="text-[10px] uppercase text-[#98958f] font-bold">Registrasi DP</div>
                    <div class="text-3xl font-bold mt-1 text-amber-600">{{ $registrasiDp }}</div>
                </div>
                <div class="bg-white rounded-xl border border-[#e3ded6] p-4">
                    <div class="text-[10px] uppercase text-[#98958f] font-bold">Registrasi Lunas</div>
                    <div class="text-3xl font-bold mt-1 text-emerald-700">{{ $registrasiLunas }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-4">
                <button type="button" data-tab="audisi-aktif"
                        class="tab-btn active px-4 py-2 rounded-lg text-[12px] font-semibold bg-gold text-white transition">
                    Sudah Audisi ({{ $sudahAudisi }})
                </button>
                <button type="button" data-tab="audisi-belum"
                        class="tab-btn px-4 py-2 rounded-lg text-[12px] font-semibold bg-white border border-[#e3ded6] text-[#22201d] transition">
                    Belum Audisi ({{ $belumAudisi }})
                </button>
                <input id="cariFinalis" type="text" placeholder="Cari nama, no. registrasi, email..."
                    class="md:col-span-2 w-full border border-[#e3ded6] rounded-lg px-3 py-2 text-[12px] focus:outline-none focus:ring-2 focus:ring-gold/30">
            </div>
        @endif

        {{-- ============================== --}}
        {{-- GRID: LIST KIRI + PANEL KANAN   --}}
        {{-- ============================== --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">

            {{-- ===== KOLOM KIRI: LIST PESERTA ===== --}}
            <div class="space-y-3">
                @php $isModeRegistrasi = ($mode ?? 'pendaftaran') === 'registrasi'; @endphp

                @forelse ($finalis as $f)
                    @if (! $isModeRegistrasi)
                        {{-- ===== KARTU MODE PENDAFTARAN — bisa diklik ===== --}}
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
                            <p class="text-xs text-slate-500 mt-0.5">{{ $f['email'] }}</p>

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
                    @else
                        {{-- ===== KARTU MODE REGISTRASI — bisa diklik ===== --}}
                        <div class="bg-white rounded-xl border border-slate-200 p-4 hover:border-amber-300 transition cursor-pointer peserta-card"
                            onclick="showDetail({{ $f['id'] }})"
                            id="card-{{ $f['id'] }}"
                            data-status="{{ $f['sudahAudisi'] ? 'audisi-aktif' : 'audisi-belum' }}"
                            data-nama="{{ strtolower($f['nama']) }}"
                            data-email="{{ strtolower($f['email']) }}"
                            data-no-reg="{{ strtolower($f['no_registrasi']) }}">

                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-mono text-slate-500">#{{ $f['no_registrasi'] }}</span>

                                @if ($f['sudahAudisi'])
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Sudah Audisi
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                        Belum Audisi
                                    </span>
                                @endif
                            </div>

                            <h3 class="font-semibold text-slate-800">{{ $f['nama'] }}</h3>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $f['email'] }}</p>

                            <div class="mt-3 flex items-center justify-between">
                                <span class="text-[10px] uppercase font-bold text-slate-400">Registrasi</span>
                                @php
                                    $statusReg = $f['statusRegistrasi'];
                                    $regMeta = match ($statusReg) {
                                        'lunas' => ['label' => 'Lunas', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                                        'dp'    => ['label' => 'DP',    'class' => 'bg-amber-50 text-amber-700 border-amber-200'],
                                        default => ['label' => 'Belum Bayar', 'class' => 'bg-rose-50 text-rose-700 border-rose-200'],
                                    };
                                @endphp
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full border {{ $regMeta['class'] }}">
                                    {{ $regMeta['label'] }}
                                </span>
                            </div>

                            {{-- Tombol Toggle Audisi — STOP PROPAGATION biar gak buka detail --}}
                            <form action="{{ route('admin.activation.toggle-audisi', $f['id']) }}" method="POST"
                                  class="mt-3" onclick="event.stopPropagation();">
                                @csrf
                                <button type="submit"
                                    class="w-full text-[11px] font-bold px-3 py-1.5 rounded-lg transition
                                           {{ $f['sudahAudisi'] ? 'bg-gray-100 text-gray-700 hover:bg-gray-200' : 'bg-gold text-white hover:bg-[#a06f1a]' }}">
                                    {{ $f['sudahAudisi'] ? 'Batalkan Audisi' : 'Tandai Sudah Audisi' }}
                                </button>
                            </form>
                        </div>
                    @endif
                @empty
                    <div class="bg-white rounded-xl border border-slate-200 p-8 text-center">
                        <p class="text-slate-500">Belum ada finalis yang diterima untuk event ini.</p>
                    </div>
                @endforelse
            </div>

            {{-- ===== KOLOM KANAN: PANEL DETAIL (STICKY) ===== --}}
            <div class="bg-white border border-[#e3ded6] rounded-xl p-5 sticky top-6 max-h-[calc(100vh-48px)] overflow-y-auto" id="panelDetail">
                <p class="text-[#98958f] text-sm text-center py-10">Pilih peserta di kiri untuk melihat detail.</p>
            </div>
        </div>
    </main>
</div>

{{-- ============================================
     TEMPLATE DETAIL PER PESERTA (Hidden)
     ============================================ --}}
<div class="hidden">
    @foreach ($finalis as $f)
        <template id="detail-{{ $f['id'] }}">
            @php $isModeRegistrasi = ($mode ?? 'pendaftaran') === 'registrasi'; @endphp

            <div>
                {{-- HEADER --}}
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <div class="text-[10px] text-[#98958f]">No. Reg: #{{ $f['no_registrasi'] }}</div>
                        <h2 class="text-xl font-bold">{{ $f['nama'] }}</h2>
                        <p class="text-[11px] text-[#73716c]">{{ $f['email'] }}</p>
                    </div>

                    @if ($isModeRegistrasi)
                        @if ($f['sudahAudisi'])
                            <span class="text-[10px] font-bold px-3 py-1.5 rounded-full bg-emerald-100 text-emerald-700">Sudah Audisi</span>
                        @else
                            <span class="text-[10px] font-bold px-3 py-1.5 rounded-full bg-gray-100 text-gray-600">Belum Audisi</span>
                        @endif
                    @else
                        @if ($f['statusAkun'] === 'aktif')
                            <span class="text-[10px] font-bold px-3 py-1.5 rounded-full bg-emerald-100 text-emerald-700">Aktif</span>
                        @else
                            <span class="text-[10px] font-bold px-3 py-1.5 rounded-full bg-rose-100 text-rose-700">Belum Aktif</span>
                        @endif
                    @endif
                </div>

                @if ($isModeRegistrasi)
                    {{-- ===== MODE REGISTRASI ===== --}}
                    <div class="bg-[#f7f5f0] rounded-lg p-4 mb-4">
                        <div class="text-[10px] uppercase text-[#98958f] font-bold mb-2">Status Registrasi</div>
                        @php
                            $statusReg = $f['statusRegistrasi'];
                            $statusMeta = match ($statusReg) {
                                'lunas' => ['label' => 'LUNAS', 'class' => 'bg-emerald-100 text-emerald-700'],
                                'dp'    => ['label' => 'DP (Belum Lunas)', 'class' => 'bg-amber-100 text-amber-700'],
                                default => ['label' => 'Belum Bayar', 'class' => 'bg-rose-100 text-rose-700'],
                            };
                        @endphp
                        <span class="inline-block text-[12px] font-bold px-3 py-1.5 rounded-lg {{ $statusMeta['class'] }}">
                            {{ $statusMeta['label'] }}
                        </span>

                        @if ($f['sisaPembayaranDp'])
                            <p class="text-[11px] text-amber-700 mt-2">
                                Sisa pelunasan: <strong>Rp {{ number_format($f['sisaPembayaranDp'], 0, ',', '.') }}</strong>
                            </p>
                        @endif
                    </div>

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
                                <div class="flex justify-between"><span class="text-[#73716c]">Bayar</span><strong>{{ $f['lunas']->tanggal_bayar?->format('d M Y, H:i') ?? '-' }}</strong></div>
                            </div>
                        </div>
                    @endif

                    @if (! $f['dp1'] && ! $f['dp2'] && ! $f['lunas'])
                        <div class="bg-[#f7f5f0] rounded-lg p-4 mb-4 text-center">
                            <p class="text-[12px] text-slate-500">Peserta belum melakukan pembayaran registrasi.</p>
                        </div>
                    @endif

                    <div class="pt-3 border-t border-[#e3ded6]">
                        <form action="{{ route('admin.activation.toggle-audisi', $f['id']) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full text-[12px] font-bold px-4 py-2.5 rounded-lg transition
                                    {{ $f['sudahAudisi'] ? 'bg-gray-100 text-gray-700 hover:bg-gray-200' : 'bg-gold text-white hover:bg-[#a06f1a]' }}">
                                {{ $f['sudahAudisi'] ? 'Batalkan Audisi' : 'Tandai Sudah Audisi' }}
                            </button>
                        </form>
                    </div>
                @else
                    {{-- ===== MODE PENDAFTARAN ===== --}}
                    <div class="bg-[#f7f5f0] rounded-lg p-4 mb-4">
                        <div class="text-[10px] uppercase text-[#98958f] font-bold mb-2">Detail Transaksi Midtrans</div>
                        @if ($f['pembayaran'])
                            <div class="grid grid-cols-2 gap-2 text-[12px]">
                                <div><span class="text-[#73716c]">Order ID</span><br><strong>{{ $f['pembayaran']->midtrans_order_id }}</strong></div>
                                <div><span class="text-[#73716c]">Transaction ID</span><br><strong>{{ $f['pembayaran']->midtrans_transaction_id ?? '-' }}</strong></div>
                                <div><span class="text-[#73716c]">Metode</span><br><strong>{{ $f['pembayaran']->metode ?? '-' }}</strong></div>
                                <div><span class="text-[#73716c]">Nominal</span><br><strong>Rp {{ number_format($f['pembayaran']->jumlah, 0, ',', '.') }}</strong></div>
                                <div><span class="text-[#73716c]">Status</span><br><strong class="uppercase">{{ $f['pembayaran']->status_midtrans }}</strong></div>
                                <div><span class="text-[#73716c]">Tanggal</span><br><strong>{{ $f['pembayaran']->tanggal_bayar?->format('d M Y, H:i') ?? '-' }}</strong></div>
                            </div>
                        @else
                            <p class="text-rose-500 text-[12px]">Belum ada transaksi pembayaran.</p>
                        @endif
                    </div>

                    <div class="pt-3 border-t border-[#e3ded6]">
                        <form action="{{ route('admin.activation.verifikasi', $f['id']) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="px-4 py-2 text-[12px] font-bold bg-gold text-white rounded-lg hover:bg-[#a06f1a] transition">
                                Cek & Sinkronkan Status Pembayaran
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </template>
    @endforeach
</div>

{{-- ============================================
     JAVASCRIPT
     ============================================ --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const cards = document.querySelectorAll('.peserta-card');
    const panel = document.getElementById('panelDetail');
    const searchInput = document.getElementById('cariFinalis');
    const tabs = document.querySelectorAll('.tab-btn');

    let currentFilter = 'semua';
    let searchQuery = '';

    window.showDetail = function (id) {
        const tpl = document.getElementById('detail-' + id);
        if (tpl && panel) {
            panel.innerHTML = tpl.innerHTML;
        }

        cards.forEach(c => {
            c.classList.remove('border-gold', 'ring-1', 'ring-gold');
        });
        const activeCard = document.getElementById('card-' + id);
        if (activeCard) {
            activeCard.classList.add('border-gold', 'ring-1', 'ring-gold');
        }
    };

    if (cards.length > 0) {
        const firstId = cards[0].id.replace('card-', '');
        showDetail(firstId);
    }

    function applyFilters() {
        cards.forEach(card => {
            const status = card.dataset.status;
            const nama = card.dataset.nama || '';
            const email = card.dataset.email || '';
            const noReg = card.dataset.noReg || '';

            let matchTab = false;
            if (currentFilter === 'semua') {
                matchTab = true;
            } else if (currentFilter === 'perlu') {
                matchTab = (status !== 'aktif');
            } else if (currentFilter === 'aktif') {
                matchTab = (status === 'aktif');
            } else if (currentFilter === 'audisi-aktif') {
                matchTab = (status === 'audisi-aktif');
            } else if (currentFilter === 'audisi-belum') {
                matchTab = (status === 'audisi-belum');
            }

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
});
</script>
</body>
</html>