<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | ALNAY.ID</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css'])
    @include('admin.partials.pwa-head')
</head>
<body class="bg-[#f7f6f3] text-[#24211d] font-sans">
    <div class="flex min-h-screen">
        @include('admin.partials.sidebar', [
            'active' => 'dashboard',
            'event' => $event ?? null,
        ])

        <main class="flex-1 min-w-0 ml-[235px] mt-[80px]">
            @include('admin.partials.header', ['variant' => 'dashboard', 'event' => $event])

            <div class="p-6 md:p-8 space-y-6">

                {{-- ============================================
                    EVENT HEADER
                ============================================= --}}
<div class="bg-white rounded-xl border border-[#e7e2da] p-4 sm:p-5">
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
        {{-- Kiri: info event --}}
        <div class="min-w-0 flex-1">
            @if (isset($events) && $events->count() > 1)
                <div class="mb-2">
                    <select onchange="window.location.href='{{ url('/admin') }}/' + this.value"
                        class="max-w-full border border-[#e7e2da] rounded-lg px-3 py-1.5 text-sm bg-white text-[#24211d] focus:border-[#C28E27] focus:ring-2 focus:ring-[#C28E27]/20 outline-none">
                        @foreach ($events as $ev)
                            <option value="{{ $ev->id }}" @selected(($event?->id ?? 0) === $ev->id)>
                                {{ $ev->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <h1 class="text-xl sm:text-2xl font-bold text-[#24211d] leading-snug truncate">
                {{ $event?->nama ?? 'Belum ada event' }}
            </h1>

            <div class="mt-1.5 flex flex-col sm:flex-row sm:flex-wrap sm:items-center gap-x-3 gap-y-1 text-[12px] text-[#8c8982]">
                <span>
                    @if ($event?->tanggal_mulai && $event?->tanggal_selesai)
                        {{ \Carbon\Carbon::parse($event->tanggal_mulai)->format('d M Y') }},
                        {{ $event->waktu_mulai ? \Carbon\Carbon::parse($event->waktu_mulai)->format('H:i') : '00:00' }}
                        –
                        {{ \Carbon\Carbon::parse($event->tanggal_selesai)->format('d M Y') }},
                        {{ $event->waktu_selesai ? \Carbon\Carbon::parse($event->waktu_selesai)->format('H:i') : '23:59' }}
                        WIB
                    @else
                        Jadwal event belum ditentukan
                    @endif
                </span>
                <span class="hidden sm:inline text-[#d4cfc6]">·</span>
                <span>
                    Pendaftaran:
                    @if ($event?->pembukaan_pendaftaran_tanggal && $event?->batas_pendaftaran_tanggal)
                        {{ \Carbon\Carbon::parse($event->pembukaan_pendaftaran_tanggal)->format('d') }} –
                        {{ \Carbon\Carbon::parse($event->batas_pendaftaran_tanggal)->format('d M Y') }}
                    @else
                        -
                    @endif
                </span>
                <span class="hidden sm:inline text-[#d4cfc6]">·</span>
                <span>
                    Kuota: {{ $event?->kuota_peserta ?? 'Tidak terbatas' }} orang
                </span>
            </div>
        </div>

        {{-- Kanan: aksi --}}
        @if ($event)
            <div class="flex flex-wrap items-center gap-2 shrink-0">
                <a href="{{ route('admin.peserta.export', $event->id) }}"
                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs sm:text-sm rounded-lg transition shadow-sm">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <span class="whitespace-nowrap">Download</span>
                </a>
                <button type="button" onclick="openImportModal()"
                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs sm:text-sm rounded-lg transition shadow-sm cursor-pointer">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    <span class="whitespace-nowrap">Import CSV</span>
                </button>
                <a href="{{ route('admin.registrations', $event->id) }}"
                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-[#ba8320] hover:bg-[#a06f1a] text-white font-semibold text-xs sm:text-sm rounded-lg transition shadow-sm">
                    <span class="whitespace-nowrap">+ Tambah Manual</span>
                </a>
                <button type="button" onclick="openModalTambahKandidat()"
                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs sm:text-sm rounded-lg transition shadow-sm cursor-pointer">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    <span class="whitespace-nowrap">+ Kandidat Voting</span>
                </button>
            </div>
        @endif
    </div>
</div>

                {{-- ============================================
                    CARD STATISTIK (DINAMIS PER TAB)
                ============================================= --}}
                <div id="stats-container"
                    class="grid grid-cols-1 gap-4 w-full">
                    {{-- Card akan di-render oleh JavaScript berdasarkan tab aktif --}}
                </div>

                {{-- ============================================
                    TOOLBAR: SWITCHER + SEARCH + FILTER + PER PAGE
                ============================================= --}}
                <div class="bg-white rounded-xl border border-[#e7e2da] p-4">
                    <div class="flex flex-col lg:flex-row lg:items-center gap-3">

                        {{-- Switcher Tab --}}
                        <div class="flex items-center gap-1 bg-[#f4f1e9] rounded-lg p-1 shrink-0">
                            <button onclick="switchTab('pendaftaran')" id="btn-tab-pendaftaran"
                                class="tab-switcher px-4 py-2 text-sm font-semibold rounded-md bg-[#C28E27] text-white shadow-sm transition">
                                Pendaftaran
                                <span class="ml-1 px-1.5 py-0.5 bg-white/20 text-white text-xs rounded-full">{{ $totalPendaftar }}</span>
                            </button>
                            <button onclick="switchTab('audisi')" id="btn-tab-audisi"
                                class="tab-switcher px-4 py-2 text-sm font-semibold rounded-md text-[#8c8982] hover:text-[#24211d] transition">
                                Audisi
                                <span class="ml-1 px-1.5 py-0.5 bg-[#e7e2da] text-[#8c8982] text-xs rounded-full">{{ $totalAudisi }}</span>
                            </button>
                            <button onclick="switchTab('registrasi')" id="btn-tab-registrasi"
                                class="tab-switcher px-4 py-2 text-sm font-semibold rounded-md text-[#8c8982] hover:text-[#24211d] transition">
                                Registrasi
                                <span class="ml-1 px-1.5 py-0.5 bg-[#e7e2da] text-[#8c8982] text-xs rounded-full">{{ $totalRegistrasi ?? 0 }}</span>
                            </button>
                            <button onclick="switchTab('kandidat')" id="btn-tab-kandidat"
                                class="tab-switcher px-4 py-2 text-sm font-semibold rounded-md text-[#8c8982] hover:text-[#24211d] transition">
                                Kandidat Voting
                                <span class="ml-1 px-1.5 py-0.5 bg-[#e7e2da] text-[#8c8982] text-xs rounded-full">{{ $totalKandidatVoting ?? 0 }}</span>
                            </button>
                        </div>

                        {{-- Search Bar (Real-time Frontend Filter) --}}
                        <div class="flex-1 max-w-md">
                            <div class="relative">
                                <input type="text" id="searchInput" name="search" value="{{ request('search') }}"
                                    placeholder="Cari nama, email, atau status peserta..."
                                    class="w-full pl-10 pr-4 py-2.5 text-sm border border-[#e7e2da] rounded-lg focus:border-[#C28E27] focus:ring-2 focus:ring-[#C28E27]/20 outline-none bg-white">
                                
                                <svg class="w-4 h-4 absolute left-3 top-3 text-[#8c8982]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>

                                <button type="button" id="clearSearch" class="hidden absolute right-3 top-2.5 text-[#8c8982] hover:text-red-500 cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- ✅ Filter Status (Dinamis per Tab) --}}
                        <div id="filter-container" class="flex items-center gap-2 shrink-0">
                            {{-- Filter akan di-render oleh JavaScript --}}
                        </div>

                        {{-- Per Page Dropdown --}}
                        <div class="flex items-center gap-2 shrink-0">
                            <select onchange="updatePerPage(this.value)" class="border border-[#e7e2da] rounded-lg px-3 py-2 text-sm bg-white focus:border-[#C28E27] outline-none">
                                @foreach([10, 25, 50, 100] as $opt)
                                    <option value="{{ $opt }}" @selected(request('per_page', 10) == $opt)>{{ $opt }} Baris</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- ============================================
                    PANEL: PENDAFTARAN
                ============================================= --}}
                <div id="panel-pendaftaran" class="tab-panel bg-white rounded-xl border border-[#e7e2da] overflow-hidden">
                    <div class="px-4 py-3 border-b border-[#e7e2da] flex flex-wrap items-center justify-between gap-2">
                        <h3 class="text-sm font-semibold text-[#24211d]">Daftar Peserta</h3>
                        <span class="text-[11px] text-[#8c8982]">
                            Update terakhir: {{ $lastUpdate ? \Carbon\Carbon::parse($lastUpdate)->format('d M Y, H:i') : '-' }} WIB
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-[13px] min-w-[900px]">
                            <thead>
                                <tr class="bg-[#faf8f4] text-[11px] text-[#8c8982] uppercase tracking-wide">
                                    <th class="px-3 py-2.5 text-left font-semibold w-10">No</th>
                                    <th class="px-3 py-2.5 text-left font-semibold min-w-[180px]">Nama Peserta</th>
                                    <th class="px-3 py-2.5 text-left font-semibold whitespace-nowrap">Waktu Daftar</th>
                                    <th class="px-3 py-2.5 text-left font-semibold whitespace-nowrap">Status Pendaftaran</th>
                                    <th class="px-3 py-2.5 text-right font-semibold whitespace-nowrap">Total Biaya</th>
                                    <th class="px-3 py-2.5 text-right font-semibold whitespace-nowrap">Sudah Dibayar</th>
                                    <th class="px-3 py-2.5 text-left font-semibold whitespace-nowrap">Status Pembayaran</th>
                                    <th class="px-3 py-2.5 text-left font-semibold whitespace-nowrap">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#e7e2da]">
                                @forelse($pesertas as $index => $peserta)
                                    @php
                                        $totalBiaya = $event->biaya_pendaftaran ?? 0;
                                        $sudahDibayar = $peserta->pembayaran->where('status_midtrans', 'settlement')->sum('jumlah');
                                        $sisa = max(0, $totalBiaya - $sudahDibayar);
                                        $isLunas = $sudahDibayar >= $totalBiaya && $totalBiaya > 0;
                                    @endphp
                                    <tr class="hover:bg-[#faf8f4] transition" data-status="diterima">
                                        {{-- No --}}
                                        <td class="px-3 py-3 text-[#8c8982] align-top">
                                            {{ ($pesertas->currentPage() - 1) * $pesertas->perPage() + $index + 1 }}
                                        </td>

                                        {{-- Nama --}}
                                        <td class="px-3 py-3 align-top">
                                            <div class="font-medium text-[#24211d] leading-snug">
                                                {{ $peserta->namaTampil() }}
                                            </div>
                                            @if ($peserta->user && $peserta->namaTampil() !== ($peserta->user->nama_custom ?: $peserta->user->nama))
                                                <div class="text-[11px] text-[#8c8982] mt-0.5">
                                                    akun: {{ $peserta->user->nama_custom ?: $peserta->user->nama }}
                                                </div>
                                            @endif
                                            <div class="text-[11px] text-[#8c8982] mt-0.5 truncate max-w-[200px]">
                                                {{ $peserta->user->email ?? '-' }}
                                            </div>
                                            <a href="{{ route('admin.peserta.show', $peserta->id) }}"
                                               class="inline-block mt-1 text-[11px] text-[#C28E27] hover:text-[#a3721a] hover:underline font-medium">
                                                Lihat selengkapnya →
                                            </a>
                                        </td>

                                        {{-- Waktu Daftar --}}
                                        <td class="px-3 py-3 text-[#8c8982] text-[12px] align-top whitespace-nowrap">
                                            @if ($peserta->created_at)
                                                <div>{{ \Carbon\Carbon::parse($peserta->created_at)->format('d M Y') }}</div>
                                                <div class="text-[11px]">{{ \Carbon\Carbon::parse($peserta->created_at)->format('H:i') }} WIB</div>
                                            @else
                                                -
                                            @endif
                                        </td>

                                        {{-- Status Pendaftaran --}}
                                        <td class="px-3 py-3 align-top">
                                            <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-2 py-0.5 text-[11px] font-semibold text-emerald-700">
                                                Diterima
                                            </span>
                                        </td>

                                        {{-- Total Biaya --}}
                                        <td class="px-3 py-3 text-[#24211d] text-right align-top whitespace-nowrap font-medium">
                                            Rp {{ number_format($totalBiaya, 0, ',', '.') }}
                                        </td>

                                        {{-- Sudah Dibayar --}}
                                        <td class="px-3 py-3 text-[#24211d] text-right align-top whitespace-nowrap font-medium">
                                            Rp {{ number_format($sudahDibayar, 0, ',', '.') }}
                                        </td>

                                        {{-- Status Pembayaran --}}
                                        <td class="px-3 py-3 align-top">
                                            @if($isLunas)
                                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 whitespace-nowrap">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>
                                                    Lunas
                                                </span>
                                            @elseif($sudahDibayar > 0)
                                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200 whitespace-nowrap">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0"></span>
                                                    DP (Sisa: Rp {{ number_format($sisa, 0, ',', '.') }})
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600 border border-slate-200 whitespace-nowrap">
                                                    Belum Bayar
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Aksi --}}
                                        <td class="px-3 py-3 align-top">
                                            <div class="flex items-center gap-1.5 flex-wrap">
                                                <button type="button"
                                                    onclick='openModalPembayaranKandidat({{ $peserta->id }}, {{ $totalBiaya }}, {{ $sudahDibayar }}, {{ json_encode($peserta->namaTampil()) }}, {!! json_encode($peserta->pembayaran) !!})'
                                                    class="px-2.5 py-1 text-[11px] font-semibold text-blue-700 border border-blue-200 rounded-md hover:bg-blue-600 hover:text-white transition cursor-pointer whitespace-nowrap">
                                                    {{ $isLunas ? 'Ubah' : ($sudahDibayar > 0 ? 'Tambah DP' : 'Bayar') }}
                                                </button>
                                                <a href="{{ route('admin.peserta.edit', $peserta->id) }}"
                                                   class="px-2.5 py-1 text-[11px] font-semibold text-[#C28E27] border border-[#C28E27] rounded-md hover:bg-[#C28E27] hover:text-white transition whitespace-nowrap">
                                                    Edit
                                                </a>
                                                <form method="POST" action="{{ route('admin.peserta.destroy', $peserta->id) }}"
                                                      onsubmit="event.preventDefault(); konfirmasi('Yakin hapus peserta ini?', () => this.submit(), { title: 'Hapus Peserta', confirmText: 'Ya, Hapus', confirmColor: '#dc2626' });"
                                                      class="inline m-0 p-0">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="px-2.5 py-1 text-[11px] font-semibold text-red-600 border border-red-200 rounded-md hover:bg-red-600 hover:text-white transition cursor-pointer whitespace-nowrap">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-12 text-center text-[#8c8982]">
                                            <p class="text-sm">{{ request('search') ? 'Tidak ada peserta yang cocok dengan pencarian.' : 'Belum ada peserta tercatat.' }}</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($pesertas instanceof \Illuminate\Pagination\LengthAwarePaginator && $pesertas->hasPages())
                        <div class="px-4 py-3 border-t border-[#e7e2da] flex flex-wrap items-center justify-between gap-2">
                            <span class="text-[11px] text-[#8c8982]">
                                Menampilkan {{ $pesertas->firstItem() }} – {{ $pesertas->lastItem() }} dari {{ $pesertas->total() }} peserta
                            </span>
                            <div class="flex items-center gap-1">
                                @if ($pesertas->onFirstPage())
                                    <span class="px-2.5 py-1 text-[11px] text-[#ccc] border border-[#e7e2da] rounded-md">‹</span>
                                @else
                                    <a href="{{ $pesertas->previousPageUrl() }}" class="px-2.5 py-1 text-[11px] text-[#24211d] border border-[#e7e2da] rounded-md hover:bg-[#faf8f4]">‹</a>
                                @endif
                                @for ($page = max(1, $pesertas->currentPage() - 2); $page <= min($pesertas->lastPage(), $pesertas->currentPage() + 2); $page++)
                                    @if ($page === $pesertas->currentPage())
                                        <span class="px-2.5 py-1 text-[11px] font-semibold bg-[#24211d] text-white rounded-md">{{ $page }}</span>
                                    @else
                                        <a href="{{ $pesertas->url($page) }}" class="px-2.5 py-1 text-[11px] text-[#24211d] border border-[#e7e2da] rounded-md hover:bg-[#faf8f4]">{{ $page }}</a>
                                    @endif
                                @endfor
                                @if ($pesertas->hasMorePages())
                                    <a href="{{ $pesertas->nextPageUrl() }}" class="px-2.5 py-1 text-[11px] text-[#24211d] border border-[#e7e2da] rounded-md hover:bg-[#faf8f4]">›</a>
                                @else
                                    <span class="px-2.5 py-1 text-[11px] text-[#ccc] border border-[#e7e2da] rounded-md">›</span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                {{-- ============================================
                    PANEL: AUDISI
                ============================================= --}}
                <div id="panel-audisi" class="tab-panel hidden bg-white rounded-xl border border-[#e7e2da] overflow-hidden">
                    <div class="p-4 border-b border-[#e7e2da] flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-[#24211d]">Peserta Audisi</h3>
                            <p class="text-xs text-[#8c8982] mt-0.5">Peserta diterima & sudah melunasi pembayaran</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-[#faf8f4] text-xs text-[#8c8982] uppercase tracking-wide">
                                    <th class="px-4 py-3 text-left font-semibold">No</th>
                                    <th class="px-4 py-3 text-left font-semibold">Peserta</th>
                                    <th class="px-4 py-3 text-left font-semibold">Waktu Daftar</th>
                                    <th class="px-4 py-3 text-left font-semibold">Waktu Bayar</th>
                                    <th class="px-4 py-3 text-left font-semibold">Status Audisi</th>
                                    <th class="px-4 py-3 text-left font-semibold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#e7e2da]">
                                @forelse($pesertaAudisi as $index => $peserta)
                                    @php
                                        $bayar = $peserta->pembayaran->sortByDesc('id')->first();
                                        $hasilAudisi = $peserta->hasil_audisi ?? null;
                                        $audisiMeta = match($hasilAudisi) {
                                            'lolos' => ['label' => 'Lolos Audisi', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                                            'tidak_lolos' => ['label' => 'Tidak Lolos', 'class' => 'bg-rose-50 text-rose-700 border-rose-200'],
                                            default => ['label' => 'Belum Dinilai', 'class' => 'bg-slate-100 text-slate-600 border-slate-200'],
                                        };
                                    @endphp
                                    <tr class="hover:bg-[#faf8f4] transition">
                                        <td class="px-4 py-3 text-[#8c8982]">
                                            {{ ($pesertaAudisi->currentPage() - 1) * $pesertaAudisi->perPage() + $index + 1 }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <p class="font-medium text-[#24211d]">{{ $peserta->namaTampil() }}</p>
                                            @if ($peserta->user && $peserta->namaTampil() !== ($peserta->user->nama_custom ?: $peserta->user->nama))
                                                <span class="block text-xs text-[#8c8982]">
                                                    akun: {{ $peserta->user->nama_custom ?: $peserta->user->nama }}
                                                </span>
                                            @endif
                                            @foreach ($peserta->jawabans as $jawaban)
                                                @php
                                                    $labelLower = strtolower(trim($jawaban->pertanyaan?->label ?? ''));
                                                @endphp
                                                @if (in_array($labelLower, ['email', 'e-mail', 'alamat email'], true))
                                                    <span class="block text-xs text-[#8c8982]">
                                                        {{ $jawaban->pertanyaan?->label ?? 'Email' }}: {{ is_array($jawaban->jawaban) ? implode(', ', $jawaban->jawaban) : $jawaban->jawaban }}
                                                    </span>
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="px-4 py-3 text-[#8c8982] text-xs">
                                            @if ($peserta->created_at)
                                                {{ \Carbon\Carbon::parse($peserta->created_at)->format('d M Y') }}<br>
                                                {{ \Carbon\Carbon::parse($peserta->created_at)->format('H:i') }} WIB
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-xs">
                                            @if ($bayar?->tanggal_bayar)
                                                <span class="inline-flex px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded-full text-xs font-semibold border border-emerald-200">
                                                    Lunas
                                                </span><br>
                                                <span class="text-[#8c8982]">
                                                    {{ \Carbon\Carbon::parse($bayar->tanggal_bayar)->format('d M Y') }}<br>
                                                    {{ \Carbon\Carbon::parse($bayar->tanggal_bayar)->format('H:i') }} WIB
                                                </span>
                                            @else
                                                <span class="text-[#8c8982]">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex flex-col gap-1.5">
                                                {{-- Badge Status --}}
                                                <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full border {{ $audisiMeta['class'] }} w-fit">
                                                    {{ $audisiMeta['label'] }}
                                                </span>

                                                {{-- ✅ Tombol Lihat Catatan --}}
                                                @if($peserta->catatan_admin && $hasilAudisi === 'tidak_lolos')
                                                    <button type="button"
                                                        onclick="openModalCatatanAdmin({{ $peserta->id }}, {{ json_encode($peserta->namaTampil()) }}, {{ json_encode($peserta->catatan_admin) }})"
                                                        class="flex items-center gap-1 text-[10px] text-gray-500 hover:text-amber-600 transition">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                                        </svg>
                                                        <span class="max-w-[120px] truncate">Lihat catatan</span>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <button type="button"
                                                    onclick="updateHasilAudisi({{ $peserta->id }}, 'lolos', this)"
                                                    class="px-3 py-1.5 text-xs font-semibold text-emerald-700 border border-emerald-200 rounded-lg hover:bg-emerald-600 hover:text-white transition">
                                                    ✓ Loloskan
                                                </button>
                                                <button type="button"
                                                    onclick='openModalTolak({{ $peserta->id }}, {{ json_encode($peserta->namaTampil()) }}, {{ json_encode($peserta->catatan_admin ?? '') }})'
                                                    class="px-3 py-1.5 text-xs font-semibold text-rose-700 border border-rose-200 rounded-lg hover:bg-rose-600 hover:text-white transition cursor-pointer">
                                                    ✕ Tidak Lolos
                                                </button>

                                                {{-- ✅ TOMBOL HAPUS UNTUK TAB AUDISI --}}
                                                <form method="POST" action="{{ route('admin.peserta.destroy', $peserta->id) }}"
                                                    onsubmit="event.preventDefault(); konfirmasi('Yakin hapus peserta audisi ini?', () => this.submit(), { title: 'Hapus Peserta', confirmText: 'Ya, Hapus', confirmColor: '#dc2626' });"
                                                    class="inline m-0 p-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="px-3 py-1.5 text-xs font-semibold text-red-600 border border-red-200 rounded-lg hover:bg-red-600 hover:text-white transition cursor-pointer">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-12 text-center text-[#8c8982]">
                                            <p class="text-sm">Belum ada peserta audisi.</p>
                                            <p class="text-xs mt-1">Peserta akan muncul di sini setelah status diterima dan pembayaran lunas.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($pesertaAudisi instanceof \Illuminate\Pagination\LengthAwarePaginator && $pesertaAudisi->hasPages())
                        <div class="px-4 py-3 border-t border-[#e7e2da] flex items-center justify-between">
                            <span class="text-xs text-[#8c8982]">
                                Menampilkan {{ $pesertaAudisi->firstItem() }} – {{ $pesertaAudisi->lastItem() }} dari {{ $pesertaAudisi->total() }} peserta audisi
                            </span>
                            <div class="flex items-center gap-1">
                                @if ($pesertaAudisi->onFirstPage())
                                    <span class="px-3 py-1.5 text-xs text-[#ccc] border border-[#e7e2da] rounded-lg">‹</span>
                                @else
                                    <a href="{{ $pesertaAudisi->previousPageUrl() }}" class="px-3 py-1.5 text-xs text-[#24211d] border border-[#e7e2da] rounded-lg hover:bg-[#faf8f4]">‹</a>
                                @endif
                                @for ($page = max(1, $pesertaAudisi->currentPage() - 2); $page <= min($pesertaAudisi->lastPage(), $pesertaAudisi->currentPage() + 2); $page++)
                                    @if ($page === $pesertaAudisi->currentPage())
                                        <span class="px-3 py-1.5 text-xs font-semibold bg-[#24211d] text-white rounded-lg">{{ $page }}</span>
                                    @else
                                        <a href="{{ $pesertaAudisi->url($page) }}" class="px-3 py-1.5 text-xs text-[#24211d] border border-[#e7e2da] rounded-lg hover:bg-[#faf8f4]">{{ $page }}</a>
                                    @endif
                                @endfor
                                @if ($pesertaAudisi->hasMorePages())
                                    <a href="{{ $pesertaAudisi->nextPageUrl() }}" class="px-3 py-1.5 text-xs text-[#24211d] border border-[#e7e2da] rounded-lg hover:bg-[#faf8f4]">›</a>
                                @else
                                    <span class="px-3 py-1.5 text-xs text-[#ccc] border border-[#e7e2da] rounded-lg">›</span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div> {{-- ✅ TUTUP PANEL AUDISI DI SINI --}}

    {{-- ============================================
        MODAL IMPORT CSV PESERTA
    ============================================= --}}
    <div id="importModal" class="hidden fixed inset-0 z-[999] bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-[#e7e2da] relative">
            <div class="flex items-center justify-between pb-4 border-b border-[#e7e2da]">
                <h3 class="text-base font-bold text-[#24211d]">Import Data Peserta via CSV</h3>
                <button type="button" onclick="closeImportModal()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            @if($event)
                <form action="{{ route('admin.peserta.import', $event->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 pt-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">1. Download Template CSV Sesuai Form Builder</label>
                        <a href="{{ route('admin.peserta.template', $event->id) }}" 
                        class="inline-flex items-center gap-2 px-3.5 py-2 bg-[#f4f1e9] text-[#ba8320] hover:bg-[#eae4d5] rounded-lg text-xs font-bold transition border border-[#e7e2da]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Download Template CSV untuk Event Ini
                        </a>
                        <p class="text-[11px] text-gray-500 mt-1">Template ini otomatis berisi kolom sesuai pertanyaan yang Anda susun di Form Builder.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">2. Upload File CSV yang Telah Diisi <span class="text-red-500">*</span></label>
                        <input type="file" name="file_csv" accept=".csv, .txt" required
                            class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#ba8320] file:text-white hover:file:bg-[#a06f1a] transition border border-[#e7e2da] rounded-lg p-1.5 bg-gray-50 cursor-pointer">
                        <p class="text-[11px] text-gray-500 mt-1">Format file harus .csv dengan ukuran maksimal 5MB.</p>
                    </div>

                    <div class="pt-4 border-t border-[#e7e2da] flex items-center justify-end gap-3">
                        <button type="button" onclick="closeImportModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 bg-[#ba8320] hover:bg-[#a06f1a] text-white text-xs font-bold rounded-lg transition shadow-sm cursor-pointer">
                            Mulai Import Data
                        </button>
                    </div>
                </form>
            @else
                <p class="py-6 text-center text-xs text-gray-500">Pilih event terlebih dahulu untuk melakukan import data.</p>
            @endif
        </div>
    </div>
    {{-- ============================================
        MODAL TAMBAH KANDIDAT VOTING LANGSUNG
    ============================================= --}}
    <div id="modalTambahKandidat" class="hidden fixed inset-0 z-[999] bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl border border-[#e7e2da] relative my-8">
            <div class="flex items-center justify-between pb-4 border-b border-[#e7e2da]">
                <h3 class="text-base font-bold text-[#24211d]">Tambah Kandidat Voting Langsung</h3>
                <button type="button" onclick="closeModalTambahKandidat()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            @if($event)
                <form action="{{ route('admin.kandidat.store', $event->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    {{-- Field Dasar Wajib --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Lengkap Kandidat <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" required placeholder="Masukkan nama lengkap"
                            class="w-full text-xs border border-[#e7e2da] rounded-lg p-2.5 outline-none focus:border-[#C28E27] focus:ring-1 focus:ring-[#C28E27]">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Alamat Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" required placeholder="email@domain.com"
                            class="w-full text-xs border border-[#e7e2da] rounded-lg p-2.5 outline-none focus:border-[#C28E27] focus:ring-1 focus:ring-[#C28E27]">
                    </div>

                    {{-- Field Dinamis Sesuai Form Builder Event --}}
                    @foreach($event->pertanyaans()->whereNotIn('tipe_input', ['heading', 'subheading'])->orderBy('urutan')->get() as $q)
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">
                                {{ $q->label }} @if($q->wajib) <span class="text-red-500">*</span> @endif
                            </label>

                            @if($q->tipe_input === 'textarea')
                                <textarea name="jawaban_{{ $q->id }}" @if($q->wajib) required @endif rows="3" placeholder="{{ $q->placeholder }}"
                                    class="w-full text-xs border border-[#e7e2da] rounded-lg p-2.5 outline-none focus:border-[#C28E27]"></textarea>
                            
                            @elseif($q->tipe_input === 'select' || $q->tipe_input === 'dropdown')
                                <select name="jawaban_{{ $q->id }}" @if($q->wajib) required @endif
                                    class="w-full text-xs border border-[#e7e2da] rounded-lg p-2.5 outline-none focus:border-[#C28E27] bg-white">
                                    <option value="">-- Pilih Opsi --</option>
                                    @foreach(json_decode($q->opsi ?? '[]', true) as $opt)
                                        <option value="{{ $opt }}">{{ $opt }}</option>
                                    @endforeach
                                </select>

                            @elseif($q->tipe_input === 'file')
                                <input type="file" name="jawaban_{{ $q->id }}" @if($q->wajib) required @endif
                                    class="w-full text-xs text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#C28E27] file:text-white hover:file:bg-[#a3721a] border border-[#e7e2da] rounded-lg p-1 bg-gray-50">

                            @else
                                <input type="text" name="jawaban_{{ $q->id }}" @if($q->wajib) required @endif placeholder="{{ $q->placeholder }}"
                                    class="w-full text-xs border border-[#e7e2da] rounded-lg p-2.5 outline-none focus:border-[#C28E27]">
                            @endif
                        </div>
                    @endforeach

                    <div class="pt-4 border-t border-[#e7e2da] flex items-center justify-end gap-3 sticky bottom-0 bg-white py-2">
                        <button type="button" onclick="closeModalTambahKandidat()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 bg-[#C28E27] hover:bg-[#a3721a] text-white text-xs font-bold rounded-lg transition shadow-sm cursor-pointer">
                            Simpan & Aktifkan Kandidat
                        </button>
                    </div>
                </form>
            @else
                <p class="py-6 text-center text-xs text-gray-500">Pilih event terlebih dahulu.</p>
            @endif
        </div>
    </div>

    {{-- ============================================
    PANEL: KANDIDAT VOTING
============================================= --}}
<div id="panel-kandidat" class="tab-panel hidden bg-white rounded-xl border border-[#e7e2da] overflow-hidden">
    <div class="p-4 border-b border-[#e7e2da] flex items-center justify-between">
        <div>
            <h3 class="text-sm font-semibold text-[#24211d]">Daftar Kandidat Voting Langsung</h3>
            <p class="text-xs text-[#8c8982] mt-0.5">Peserta yang didaftarkan manual & pembayaran diatur admin.</p>
        </div>
        <button type="button" onclick="openModalTambahKandidat()" class="px-4 py-2 bg-[#C28E27] hover:bg-[#a3721a] text-white text-xs font-bold rounded-lg transition flex items-center gap-2 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Kandidat
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-[#faf8f4] text-xs text-[#8c8982] uppercase tracking-wide">
                    <th class="px-4 py-3 text-left font-semibold">No</th>
                    <th class="px-4 py-3 text-left font-semibold">Nama Kandidat</th>
                    <th class="px-4 py-3 text-left font-semibold">Total Biaya</th>
                    <th class="px-4 py-3 text-left font-semibold">Sudah Dibayar</th>
                    <th class="px-4 py-3 text-left font-semibold">Status Pembayaran</th>
                    <th class="px-4 py-3 text-left font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e7e2da]">
                @forelse($kandidatVoting as $index => $peserta)
                    @php
    $totalBiaya = $event->biaya_pendaftaran ?? 0;
    // ✅ Akumulasikan seluruh pembayaran yang sudah settlement
    $sudahDibayar = $peserta->pembayaran->where('status_midtrans', 'settlement')->sum('jumlah');
    $sisa = max(0, $totalBiaya - $sudahDibayar);
    $isLunas = $sudahDibayar >= $totalBiaya && $totalBiaya > 0;
@endphp
                    <tr class="hover:bg-[#faf8f4] transition">
                        <td class="px-4 py-3 text-[#8c8982]">
                            {{ ($kandidatVoting->currentPage() - 1) * $kandidatVoting->perPage() + $index + 1 }}
                        </td>
                        <td class="px-4 py-3 font-medium text-[#24211d]">
                            {{ $peserta->namaTampil() }}
                            <span class="block text-xs text-[#8c8982] font-normal">{{ $peserta->user->email ?? '-' }}</span>
                        </td>
                        <td class="px-4 py-3 text-[#8c8982]">
                            Rp {{ number_format($totalBiaya, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-[#8c8982]">
                            Rp {{ number_format($sudahDibayar, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3">
                            @if($isLunas)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Lunas
                                </span>
                            @elseif($sudahDibayar > 0)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    DP (Sisa: Rp {{ number_format($sisa, 0, ',', '.') }})
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                    Belum Bayar
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2 flex-wrap">
                                {{-- ✅ TOMBOL BAYAR/UBAH (Menggunakan json_encode agar aman dari kutip) --}}
                                <button type="button"
                                    onclick='openModalPembayaranKandidat({{ $peserta->id }}, {{ $totalBiaya }}, {{ $sudahDibayar }}, {{ json_encode($peserta->namaTampil()) }}, {!! json_encode($peserta->pembayaran) !!})'
                                    class="px-3 py-1.5 text-xs font-semibold text-blue-700 border border-blue-200 rounded-lg hover:bg-blue-600 hover:text-white transition cursor-pointer">
                                    {{ $isLunas ? 'Ubah' : ($sudahDibayar > 0 ? 'Tambah DP' : 'Bayar') }}
                                </button>

                                {{-- TOMBOL EDIT --}}
                                <a href="{{ route('admin.peserta.edit', $peserta->id) }}"
                                    class="px-3 py-1.5 text-xs font-semibold text-[#C28E27] border border-[#C28E27] rounded-lg hover:bg-[#C28E27] hover:text-white transition">
                                    Edit
                                </a>

                                {{-- ✅ TOMBOL HAPUS (Form terisolasi dengan return confirm yang standar) --}}
                                <form method="POST" action="{{ route('admin.peserta.destroy', $peserta->id) }}"
                                    onsubmit="event.preventDefault(); konfirmasi('Yakin hapus kandidat ini?', () => this.submit(), { title: 'Hapus Kandidat', confirmText: 'Ya, Hapus', confirmColor: '#dc2626' });"
                                    class="inline m-0 p-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1.5 text-xs font-semibold text-red-600 border border-red-200 rounded-lg hover:bg-red-600 hover:text-white transition cursor-pointer">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-[#8c8982]">
                            <p class="text-sm">Belum ada kandidat voting langsung.</p>
                            <p class="text-xs mt-1">Klik tombol "Tambah Kandidat" untuk menambahkan.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination Kandidat --}}
    @if ($kandidatVoting instanceof \Illuminate\Pagination\LengthAwarePaginator && $kandidatVoting->hasPages())
        <div class="px-4 py-3 border-t border-[#e7e2da] flex items-center justify-between">
            <span class="text-xs text-[#8c8982]">
                Menampilkan {{ $kandidatVoting->firstItem() }} – {{ $kandidatVoting->lastItem() }} dari {{ $kandidatVoting->total() }} kandidat
            </span>
            <div class="flex items-center gap-1">
                @if ($kandidatVoting->onFirstPage())
                    <span class="px-3 py-1.5 text-xs text-[#ccc] border border-[#e7e2da] rounded-lg">‹</span>
                @else
                    <a href="{{ $kandidatVoting->previousPageUrl() }}" class="px-3 py-1.5 text-xs text-[#24211d] border border-[#e7e2da] rounded-lg hover:bg-[#faf8f4]">‹</a>
                @endif
                @for ($page = max(1, $kandidatVoting->currentPage() - 2); $page <= min($kandidatVoting->lastPage(), $kandidatVoting->currentPage() + 2); $page++)
                    @if ($page === $kandidatVoting->currentPage())
                        <span class="px-3 py-1.5 text-xs font-semibold bg-[#24211d] text-white rounded-lg">{{ $page }}</span>
                    @else
                        <a href="{{ $kandidatVoting->url($page) }}" class="px-3 py-1.5 text-xs text-[#24211d] border border-[#e7e2da] rounded-lg hover:bg-[#faf8f4]">{{ $page }}</a>
                    @endif
                @endfor
                @if ($kandidatVoting->hasMorePages())
                    <a href="{{ $kandidatVoting->nextPageUrl() }}" class="px-3 py-1.5 text-xs text-[#24211d] border border-[#e7e2da] rounded-lg hover:bg-[#faf8f4]">›</a>
                @else
                    <span class="px-3 py-1.5 text-xs text-[#ccc] border border-[#e7e2da] rounded-lg">›</span>
                @endif
            </div>
        </div>
    @endif
</div>

{{-- ============================================
    ✅ TAB BARU: REGISTRASI (peserta pendaftaran yang sudah diterima,
    bayar biaya registrasi — TIDAK termasuk kandidat voting)
============================================= --}}
<div id="panel-registrasi" class="tab-panel hidden bg-white rounded-xl border border-[#e7e2da] overflow-hidden">
    <div class="p-4 border-b border-[#e7e2da]">
        <h3 class="text-sm font-semibold text-[#24211d]">Pembayaran Registrasi Peserta</h3>
        <p class="text-xs text-[#8c8982] mt-0.5">Peserta pendaftaran (bukan kandidat voting) yang sudah diterima dan perlu membayar biaya registrasi. Gunakan tombol Bayar untuk input pembayaran offline.</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-[#faf8f4] text-xs text-[#8c8982] uppercase tracking-wide">
                    <th class="px-4 py-3 text-left font-semibold">No</th>
                    <th class="px-4 py-3 text-left font-semibold">Nama Peserta</th>
                    <th class="px-4 py-3 text-left font-semibold">Total Biaya Registrasi</th>
                    <th class="px-4 py-3 text-left font-semibold">Sudah Dibayar</th>
                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                    <th class="px-4 py-3 text-left font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e7e2da]">
                @forelse ($pesertaRegistrasi as $index => $peserta)
                    @php
                        $totalBiayaRegis = $event->harga_registrasi ?? 0;
                        $sudahDibayarRegis = $peserta->pembayaranRegistrasi
                            ->where('status_midtrans', 'settlement')
                            ->sum('jumlah');
                        $sisaRegis = max(0, $totalBiayaRegis - $sudahDibayarRegis);
                        $statusRegis = $peserta->status_registrasi ?? 'belum';
                    @endphp
                    <tr class="hover:bg-[#faf8f4] transition">
                        <td class="px-4 py-3 text-[#8c8982]">
                            {{ ($pesertaRegistrasi->currentPage() - 1) * $pesertaRegistrasi->perPage() + $index + 1 }}
                        </td>
                        <td class="px-4 py-3 font-medium text-[#24211d]">
                            {{ $peserta->namaTampil() }}
                            <span class="block text-xs text-[#8c8982] font-normal">{{ $peserta->user->email ?? '-' }}</span>
                        </td>
                        <td class="px-4 py-3 text-[#8c8982]">
                            Rp {{ number_format($totalBiayaRegis, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-[#8c8982]">
                            Rp {{ number_format($sudahDibayarRegis, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3">
                            @if ($statusRegis === 'lunas')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Lunas
                                </span>
                            @elseif ($statusRegis === 'dp')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    DP (Sisa: Rp {{ number_format($sisaRegis, 0, ',', '.') }})
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                    Belum Bayar
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2 flex-wrap">
                                <button type="button"
                                    onclick='openModalPembayaranRegistrasi({{ $peserta->id }}, {{ $totalBiayaRegis }}, {{ $sudahDibayarRegis }}, {!! json_encode($peserta->namaTampil()) !!}, {!! json_encode($peserta->pembayaranRegistrasi) !!})'
                                    class="px-3 py-1.5 text-xs font-semibold text-blue-700 border border-blue-200 rounded-lg hover:bg-blue-600 hover:text-white transition cursor-pointer">
                                    {{ $statusRegis === 'lunas' ? 'Ubah' : ($sudahDibayarRegis > 0 ? 'Tambah DP' : 'Bayar') }}
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-[#8c8982]">
                            <p class="text-sm">Belum ada peserta yang perlu membayar registrasi.</p>
                            <p class="text-xs mt-1">Peserta muncul di sini setelah statusnya "Diterima".</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination Registrasi --}}
    @if ($pesertaRegistrasi instanceof \Illuminate\Pagination\LengthAwarePaginator && $pesertaRegistrasi->hasPages())
        <div class="px-4 py-3 border-t border-[#e7e2da] flex items-center justify-between">
            <span class="text-xs text-[#8c8982]">
                Menampilkan {{ $pesertaRegistrasi->firstItem() }} – {{ $pesertaRegistrasi->lastItem() }} dari {{ $pesertaRegistrasi->total() }} peserta
            </span>
            <div class="flex items-center gap-1">
                @if ($pesertaRegistrasi->onFirstPage())
                    <span class="px-3 py-1.5 text-xs text-[#ccc] border border-[#e7e2da] rounded-lg">‹</span>
                @else
                    <a href="{{ $pesertaRegistrasi->previousPageUrl() }}" class="px-3 py-1.5 text-xs text-[#24211d] border border-[#e7e2da] rounded-lg hover:bg-[#faf8f4]">‹</a>
                @endif
                @for ($page = max(1, $pesertaRegistrasi->currentPage() - 2); $page <= min($pesertaRegistrasi->lastPage(), $pesertaRegistrasi->currentPage() + 2); $page++)
                    @if ($page === $pesertaRegistrasi->currentPage())
                        <span class="px-3 py-1.5 text-xs font-semibold bg-[#24211d] text-white rounded-lg">{{ $page }}</span>
                    @else
                        <a href="{{ $pesertaRegistrasi->url($page) }}" class="px-3 py-1.5 text-xs text-[#24211d] border border-[#e7e2da] rounded-lg hover:bg-[#faf8f4]">{{ $page }}</a>
                    @endif
                @endfor
                @if ($pesertaRegistrasi->hasMorePages())
                    <a href="{{ $pesertaRegistrasi->nextPageUrl() }}" class="px-3 py-1.5 text-xs text-[#24211d] border border-[#e7e2da] rounded-lg hover:bg-[#faf8f4]">›</a>
                @else
                    <span class="px-3 py-1.5 text-xs text-[#ccc] border border-[#e7e2da] rounded-lg">›</span>
                @endif
            </div>
        </div>
    @endif
</div>

{{-- ============================================
    ✅ MODAL PEMBAYARAN MANUAL REGISTRASI (peserta pendaftaran)
============================================= --}}
<div id="modalPembayaranRegistrasi"
     class="fixed inset-0 z-[9999] bg-black/60 backdrop-blur-sm items-center justify-center p-4"
     style="display: none;">

    <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl border border-[#e7e2da] relative max-h-[90vh] overflow-y-auto">

        <div class="flex items-center justify-between pb-4 border-b border-[#e7e2da] mb-4">
            <h3 class="text-base font-bold text-[#24211d]">Input Pembayaran Registrasi & Riwayat</h3>
            <button type="button" onclick="closeModalPembayaranRegistrasi()"
                    class="text-gray-400 hover:text-gray-600 p-1 rounded-lg cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="formPembayaranRegistrasi" action="{{ route('admin.registrasi.pembayaran') }}" method="POST">
            @csrf
            <input type="hidden" name="peserta_id" id="regbayar_peserta_id">
            <input type="hidden" name="jumlah_bayar" id="regbayar_jumlah_bayar_raw">

            <div class="space-y-4">
                <div class="bg-[#faf8f4] p-3 rounded-lg border border-[#e7e2da]">
                    <p class="text-xs text-[#8c8982] mb-1">Peserta</p>
                    <p id="regbayar_nama_peserta" class="text-sm font-semibold text-[#24211d]">-</p>
                </div>

                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 mb-1">Total Biaya</label>
                        <input type="text" id="regbayar_display_total" readonly
                               class="w-full text-xs border border-[#e7e2da] rounded-lg p-2 bg-gray-50 text-gray-600 font-medium">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 mb-1">Sudah Dibayar</label>
                        <input type="text" id="regbayar_display_sudah" readonly
                               class="w-full text-xs border border-[#e7e2da] rounded-lg p-2 bg-gray-50 text-emerald-600 font-medium">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 mb-1">Sisa Pembayaran</label>
                        <input type="text" id="regbayar_display_sisa" readonly
                               class="w-full text-xs border border-[#e7e2da] rounded-lg p-2 bg-gray-50 text-amber-600 font-bold">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Riwayat Pembayaran Registrasi Sebelumnya</label>
                    <div class="max-h-36 overflow-y-auto border border-[#e7e2da] rounded-lg bg-[#faf8f4]">
                        <table class="w-full text-xs text-left">
                            <thead class="bg-gray-100 text-[#8c8982] uppercase border-b border-[#e7e2da] sticky top-0">
                                <tr>
                                    <th class="px-3 py-2 font-semibold">Tgl Bayar</th>
                                    <th class="px-3 py-2 font-semibold">Metode</th>
                                    <th class="px-3 py-2 font-semibold text-right">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody id="riwayat_pembayaran_registrasi_list" class="divide-y divide-[#e7e2da]">
                                <tr>
                                    <td colspan="3" class="px-3 py-3 text-center text-gray-400 italic">Belum ada riwayat pembayaran.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                        Jumlah Bayar Sekarang (Rp) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs text-gray-500 font-bold">Rp</span>
                        <input type="text" id="regbayar_jumlah_bayar_formatted" required
                               placeholder="Contoh: 50.000"
                               class="w-full pl-9 pr-4 py-2 text-sm border border-[#e7e2da] rounded-lg outline-none focus:border-[#C28E27] focus:ring-2 focus:ring-[#C28E27]/20 font-semibold">
                    </div>
                    <p class="text-[11px] text-gray-500 mt-1">Otomatis terisi sisa tagihan, dapat disesuaikan jika ingin membayar sebagian (DP).</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">
                            Metode Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <select name="metode" required
                                class="w-full text-xs border border-[#e7e2da] rounded-lg p-2.5 outline-none focus:border-[#C28E27] bg-white">
                            <option value="">-- Pilih Metode --</option>
                            <option value="transfer_bank">Transfer Bank</option>
                            <option value="tunai">Tunai</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">
                            Waktu Bayar <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" name="tanggal_bayar" required
                               value="{{ now()->timezone('Asia/Jakarta')->format('Y-m-d\TH:i') }}"
                               class="w-full text-xs border border-[#e7e2da] rounded-lg p-2.5 outline-none focus:border-[#C28E27]">
                    </div>
                </div>
            </div>

            {{-- Footer: keterangan riwayat + aksi pembayaran --}}
                <div class="flex items-center justify-end gap-3">
                    <button type="button" onclick="closeModalPembayaranRegistrasi()"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition shadow-sm cursor-pointer flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Pembayaran
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ============================================
    MODAL PEMBAYARAN MANUAL KANDIDAT VOTING
============================================= --}}
<div id="modalPembayaranKandidat"
     class="fixed inset-0 z-[9999] bg-black/60 backdrop-blur-sm items-center justify-center p-4"
     style="display: none;">

    <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl border border-[#e7e2da] relative max-h-[90vh] overflow-y-auto">

        {{-- Header --}}
        <div class="flex items-center justify-between pb-4 border-b border-[#e7e2da] mb-4">
            <h3 class="text-base font-bold text-[#24211d]">Input Pembayaran Manual & Riwayat</h3>
            <button type="button" onclick="closeModalPembayaranKandidat()"
                    class="text-gray-400 hover:text-gray-600 p-1 rounded-lg cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Form Input Pembayaran Baru --}}
        <form id="formPembayaranManual" action="{{ route('admin.kandidat.pembayaran') }}" method="POST">
            @csrf
            <input type="hidden" name="peserta_id" id="bayar_peserta_id">
            <input type="hidden" name="total_biaya" id="bayar_total_biaya">

            <div class="space-y-4">
                {{-- Info Peserta --}}
                <div class="bg-[#faf8f4] p-3 rounded-lg border border-[#e7e2da]">
                    <p class="text-xs text-[#8c8982] mb-1">Peserta Kandidat</p>
                    <p id="bayar_nama_peserta" class="text-sm font-semibold text-[#24211d]">-</p>
                </div>

                {{-- Ringkasan Biaya --}}
                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 mb-1">Total Biaya</label>
                        <input type="text" id="bayar_display_total" readonly
                               class="w-full text-xs border border-[#e7e2da] rounded-lg p-2 bg-gray-50 text-gray-600 font-medium">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 mb-1">Sudah Dibayar</label>
                        <input type="text" id="bayar_display_sudah" readonly
                               class="w-full text-xs border border-[#e7e2da] rounded-lg p-2 bg-gray-50 text-emerald-600 font-medium">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 mb-1">Sisa Pembayaran</label>
                        <input type="text" id="bayar_display_sisa" readonly
                               class="w-full text-xs border border-[#e7e2da] rounded-lg p-2 bg-gray-50 text-amber-600 font-bold">
                    </div>
                </div>

                {{-- ✅ RIWAYAT PEMBAYARAN SEBELUMNYA --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Riwayat Pembayaran Sebelumnya</label>
                    <div class="max-h-36 overflow-y-auto border border-[#e7e2da] rounded-lg bg-[#faf8f4]">
                        <table class="w-full text-xs text-left">
                            <thead class="bg-gray-100 text-[#8c8982] uppercase border-b border-[#e7e2da] sticky top-0">
                                <tr>
                                    <th class="px-3 py-2 font-semibold">Tgl Bayar</th>
                                    <th class="px-3 py-2 font-semibold">Metode</th>
                                    <th class="px-3 py-2 font-semibold text-right">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody id="riwayat_pembayaran_list" class="divide-y divide-[#e7e2da]">
                                {{-- Di-render secara dinamis via JavaScript --}}
                                <tr>
                                    <td colspan="3" class="px-3 py-3 text-center text-gray-400 italic">Belum ada riwayat pembayaran.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Input Jumlah Bayar Sekarang (Dengan Format Rupiah) --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                        Jumlah Bayar Sekarang (Rp) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs text-gray-500 font-bold">Rp</span>
                        <input type="text" id="bayar_jumlah_bayar_formatted" required
                               placeholder="Contoh: 50.000"
                               class="w-full pl-9 pr-4 py-2 text-sm border border-[#e7e2da] rounded-lg outline-none focus:border-[#C28E27] focus:ring-2 focus:ring-[#C28E27]/20 font-semibold">
                    </div>
                    {{-- Input hidden asli yang dikirim ke backend --}}
                    <input type="hidden" name="jumlah_bayar" id="bayar_jumlah_bayar_raw">
                    <p class="text-[11px] text-gray-500 mt-1">Otomatis terisi sisa tagihan, dapat disesuaikan jika ingin membayar sebagian (DP).</p>
                </div>

                {{-- Metode & Tanggal --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">
                            Metode Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <select name="metode" required
                                class="w-full text-xs border border-[#e7e2da] rounded-lg p-2.5 outline-none focus:border-[#C28E27] bg-white">
                            <option value="">-- Pilih Metode --</option>
                            <option value="transfer_bank">Transfer Bank</option>
                            <option value="tunai">Tunai</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">
                            Tanggal Bayar <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_bayar" required value="{{ date('Y-m-d') }}"
                               class="w-full text-xs border border-[#e7e2da] rounded-lg p-2.5 outline-none focus:border-[#C28E27]">
                    </div>
                </div>
            </div>

            {{-- Footer: keterangan riwayat + aksi pembayaran --}}
                <div class="flex items-center justify-end gap-3">
                    <button type="button" onclick="closeModalPembayaranKandidat()"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition shadow-sm cursor-pointer flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Pembayaran
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ============================================
    MODAL TAMBAH KANDIDAT VOTING (FORM BUILDER)
============================================= --}}
<div id="modalKandidat" class="fixed inset-0 z-[9999] hidden" aria-hidden="true">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeModalTambahKandidat()"></div>

    {{-- Modal Content --}}
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[85vh] flex flex-col border border-[#e7e2da]">
        
        {{-- Header Modal --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-[#e7e2da] bg-[#faf8f4] rounded-t-2xl">
            <div>
                <h3 class="text-lg font-bold text-[#24211d]">Tambah Kandidat Voting Langsung</h3>
                <p class="text-xs text-[#8c8982] mt-0.5">Form disesuaikan dengan Form Builder: {{ $event?->nama ?? '-' }}</p>
            </div>
            <button type="button" onclick="closeModalTambahKandidat()" class="text-[#8c8982] hover:text-rose-600 hover:bg-rose-50 p-2 rounded-full transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Body Modal (Scrollable) --}}
        <div class="flex-1 overflow-y-auto p-6">
            @if($event && isset($pertanyaans) && $pertanyaans->count() > 0)
                <form id="formKandidatVoting" action="{{ route('admin.kandidat.store', $event->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
            

                    {{-- Field Dinamis dari Form Builder --}}
                    <div class="space-y-4">
                        <p class="text-xs font-bold text-[#8c8982] uppercase tracking-wide mb-2">Data Tambahan (Form Builder)</p>
                        
                        @foreach($pertanyaans as $q)
                            <div class="border border-[#e7e2da] rounded-xl p-4 bg-white">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    {{ $q->label }} 
                                    @if($q->wajib) <span class="text-red-500">*</span> @endif
                                </label>

                                @php
                                    $configRaw = $q->konfigurasi ?? [];
                                    $config = is_string($configRaw) ? (json_decode($configRaw, true) ?? []) : (is_array($configRaw) ? $configRaw : []);
                                    $placeholder = $config['placeholder'] ?? '';
                                    $help = $config['help'] ?? '';
                                    
                                    $optionsRaw = $q->pilihan_jawaban ?? [];
                                    $options = is_string($optionsRaw) ? (json_decode($optionsRaw, true) ?? []) : (is_array($optionsRaw) ? $optionsRaw : []);
                                @endphp

                                @if($help)
                                    <p class="text-xs text-gray-500 mb-2">{{ $help }}</p>
                                @endif

                                {{-- Text Input --}}
                                @if(in_array($q->tipe_input, ['text', 'teks']))
                                    <input type="text" name="jawaban_{{ $q->id }}" @if($q->wajib) required @endif placeholder="{{ $placeholder }}"
                                        class="w-full text-sm border border-[#e7e2da] rounded-lg p-2.5 outline-none focus:border-[#C28E27]">

                                {{-- Email Input --}}
                                @elseif($q->tipe_input === 'email')
                                    <input type="email" name="jawaban_{{ $q->id }}" @if($q->wajib) required @endif placeholder="{{ $placeholder ?: 'email@domain.com' }}"
                                        class="w-full text-sm border border-[#e7e2da] rounded-lg p-2.5 outline-none focus:border-[#C28E27]">

                                {{-- Number Input --}}
                                @elseif(in_array($q->tipe_input, ['number', 'angka']))
                                    <input type="number" name="jawaban_{{ $q->id }}" @if($q->wajib) required @endif placeholder="{{ $placeholder }}"
                                        class="w-full text-sm border border-[#e7e2da] rounded-lg p-2.5 outline-none focus:border-[#C28E27]">

                                {{-- Textarea --}}
                                @elseif(in_array($q->tipe_input, ['textarea', 'teks_panjang']))
                                    <textarea name="jawaban_{{ $q->id }}" @if($q->wajib) required @endif rows="3" placeholder="{{ $placeholder }}"
                                        class="w-full text-sm border border-[#e7e2da] rounded-lg p-2.5 outline-none focus:border-[#C28E27]"></textarea>

                                {{-- Select / Dropdown --}}
                                @elseif(in_array($q->tipe_input, ['select', 'dropdown']))
                                    <select name="jawaban_{{ $q->id }}" @if($q->wajib) required @endif
                                        class="w-full text-sm border border-[#e7e2da] rounded-lg p-2.5 outline-none focus:border-[#C28E27] bg-white">
                                        <option value="">-- Pilih {{ $q->label }} --</option>
                                        @foreach($options as $opt)
                                            <option value="{{ $opt }}">{{ $opt }}</option>
                                        @endforeach
                                    </select>

                                {{-- Radio --}}
                                @elseif($q->tipe_input === 'radio')
                                    <div class="space-y-2">
                                        @foreach($options as $opt)
                                            <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                                                <input type="radio" name="jawaban_{{ $q->id }}" value="{{ $opt }}" @if($q->wajib) required @endif
                                                    class="w-4 h-4 text-[#C28E27] border-gray-300 focus:ring-[#C28E27]">
                                                <span>{{ $opt }}</span>
                                            </label>
                                        @endforeach
                                    </div>

                                {{-- Checkbox --}}
                                @elseif($q->tipe_input === 'checkbox')
                                    <div class="space-y-2">
                                        @foreach($options as $opt)
                                            <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                                                <input type="checkbox" name="jawaban_{{ $q->id }}[]" value="{{ $opt }}"
                                                    class="w-4 h-4 text-[#C28E27] border-gray-300 rounded focus:ring-[#C28E27]">
                                                <span>{{ $opt }}</span>
                                            </label>
                                        @endforeach
                                    </div>

                                {{-- File Upload --}}
                                @elseif($q->tipe_input === 'file')
                                    <input type="file" name="jawaban_{{ $q->id }}" @if($q->wajib) required @endif
                                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#C28E27] file:text-white hover:file:bg-[#a3721a] border border-[#e7e2da] rounded-lg p-1.5 bg-gray-50">
                                    <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, PDF. Maks 5MB.</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </form>
            @else
                <div class="py-12 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-sm text-gray-500">Belum ada form yang dibuat untuk event ini.</p>
                    <a href="{{ route('admin.form-builder', $event?->id) }}" class="inline-flex items-center gap-1 mt-2 text-sm text-[#C28E27] hover:underline font-medium">
                        Buat Form Pendaftaran →
                    </a>
                </div>
            @endif
        </div>

        {{-- Footer Modal (Sticky) --}}
        @if($event && isset($pertanyaans) && $pertanyaans->count() > 0)
        <div class="px-6 py-4 border-t border-[#e7e2da] bg-white rounded-b-2xl flex items-center justify-end gap-3">
            <button type="button" onclick="closeModalTambahKandidat()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition cursor-pointer">
                Batal
            </button>
            <button type="submit" form="formKandidatVoting" class="px-5 py-2 bg-[#C28E27] hover:bg-[#a3721a] text-white text-sm font-bold rounded-lg transition shadow-sm cursor-pointer flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan & Aktifkan Kandidat
            </button>
        </div>
        @endif
    </div>
</div>

{{-- ============================================
    MODAL TIDAK LOLOS AUDISI
============================================= --}}
<div id="modalTolakAudisi" class="hidden fixed inset-0 z-[9999] bg-black/60 backdrop-blur-sm items-center justify-center p-4" style="display: none;">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-[#e7e2da] relative max-h-[90vh] overflow-y-auto">
        
        {{-- Header --}}
        <div class="flex items-center justify-between pb-4 border-b border-[#e7e2da] mb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-[#24211d]">Tandai Tidak Lolos</h3>
                    <p class="text-xs text-[#8c8982]">Konfirmasi penolakan peserta</p>
                </div>
            </div>
            <button type="button" onclick="closeModalTolak()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="formTolakAudisi" method="POST">
            @csrf
            <input type="hidden" id="tolak_peserta_id" name="peserta_id">
            
            <div class="space-y-4">
                {{-- Info Peserta --}}
                <div class="bg-rose-50 p-3 rounded-lg border border-rose-200">
                    <p class="text-xs text-rose-800 font-semibold mb-1">Peserta:</p>
                    <p id="tolak_nama_peserta" class="text-sm font-bold text-rose-900">-</p>
                </div>

                {{-- ✅ Catatan Opsional --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                        Catatan Penolakan <span class="text-gray-400 font-normal">(Opsional)</span>
                    </label>
                    <textarea name="catatan_admin" id="tolak_catatan" rows="3"
                        placeholder="Contoh: Suara kurang stabil, tidak sesuai kriteria, dll."
                        class="w-full text-sm border border-[#e7e2da] rounded-lg p-2.5 outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 resize-none"></textarea>
                    <p class="text-[11px] text-gray-500 mt-1">
                        💡 Catatan ini hanya terlihat oleh admin. Boleh dikosongkan.
                    </p>
                </div>

                {{-- Info --}}
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                    <div class="flex gap-2">
                        <svg class="w-4 h-4 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-xs text-amber-700">
                            Peserta yang ditandai <strong>tidak lolos</strong> tidak akan masuk ke tahap voting.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="pt-4 border-t border-[#e7e2da] flex items-center justify-end gap-3 mt-6">
                <button type="button" onclick="closeModalTolak()" 
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" 
                    class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-lg transition shadow-sm cursor-pointer flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Tandai Tidak Lolos
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ============================================
    MODAL CATATAN ADMIN (DIPERBAIKI)
============================================= --}}
<div id="modalCatatanAdmin" 
     class="hidden fixed inset-0 z-[9999] overflow-y-auto" 
     style="display: none;">
    
    {{-- Backdrop dengan blur penuh --}}
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" 
         onclick="closeModalCatatanAdmin()"></div>

    {{-- Modal Content --}}
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl border border-[#e7e2da] relative z-10">
            
            {{-- Header --}}
            <div class="flex items-center gap-3 px-6 py-4 border-b border-[#e7e2da]">
                <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-base font-bold text-[#24211d]">Catatan Admin</h3>
                    <p class="text-xs text-[#8c8982]">Alasan penolakan peserta</p>
                </div>
                <button type="button" onclick="closeModalCatatanAdmin()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="p-6 space-y-4">
                {{-- Info Peserta --}}
                <div class="bg-amber-50 p-3 rounded-lg border border-amber-200">
                    <p class="text-xs text-amber-800 font-semibold mb-1">Peserta:</p>
                    <p id="catatan_nama_peserta" class="text-sm font-bold text-amber-900">-</p>
                </div>

                {{-- Isi Catatan --}}
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <p class="text-xs text-gray-500 font-semibold mb-2">Isi Catatan:</p>
                    <p id="catatan_isi" class="text-sm text-gray-800 whitespace-pre-line leading-relaxed"></p>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-[#e7e2da] flex items-center justify-end">
                <button type="button" onclick="closeModalCatatanAdmin()"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg transition cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

    {{-- ============================================
        JAVASCRIPT: TAB SWITCHER, CARD DINAMIS, FILTER
    ============================================= --}}
    <script>
    // Data statistik dari PHP
    const statsData = {
        pendaftaran: { total: {{ $totalPendaftar }} },
        audisi: {
            total: {{ $totalAudisi }},
            lolos: {{ $lolosAudisi ?? 0 }},
            tidak_lolos: {{ $tidakLolosAudisi ?? 0 }},
            belum_dinilai: {{ $belumDinilai ?? 0 }}
        },
        kandidat: { total: {{ $totalKandidatVoting ?? 0 }} },
        registrasi: {
            total: {{ $totalRegistrasi ?? 0 }},
            lunas: {{ $registrasiLunas ?? 0 }},
            dp: {{ $registrasiDp ?? 0 }},
            belum: {{ $registrasiBelum ?? 0 }}
        }
    };

    // Template card untuk tab Pendaftaran
    const cardPendaftaran = `
        <div class="bg-white rounded-xl border border-[#e7e2da] p-6
                    flex items-center justify-between gap-4
                    hover:shadow-sm transition-shadow duration-200">
            <div class="flex items-center gap-4 min-w-0">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center
                            rounded-xl bg-[#fbf3df]">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        width="27" height="27" viewBox="0 0 24 24"
                        fill="none" stroke="#C28E27" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="10" cy="7" r="4"/>
                        <path d="M20 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-[#8c8982]
                            uppercase tracking-wider">
                        Total Pendaftar
                    </p>
                    <p class="text-3xl font-bold text-[#24211d] mt-1">
                        ${statsData.pendaftaran.total}
                    </p>
                    <p class="text-xs text-[#8c8982] mt-1">
                        Peserta tercatat dalam sistem
                    </p>
                </div>
            </div>
        </div>
    `;

    // Template card untuk tab Audisi
    const cardAudisi = `
        <div class="bg-white rounded-xl border border-[#e7e2da] p-5 border-b-4 border-b-[#C28E27]">
            <p class="text-xs font-semibold text-[#8c8982] uppercase tracking-wide">Total Audisi</p>
            <p class="text-3xl font-bold text-[#24211d] mt-1">${statsData.audisi.total}</p>
            <p class="text-xs text-[#8c8982] mt-1">Peserta eligible</p>
        </div>
        <div class="bg-white rounded-xl border border-[#e7e2da] p-5 border-b-4 border-b-emerald-500">
            <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wide">Lolos Audisi</p>
            <p class="text-3xl font-bold text-[#24211d] mt-1">${statsData.audisi.lolos}</p>
            <p class="text-xs text-[#8c8982] mt-1">Finalis terpilih</p>
        </div>
        <div class="bg-white rounded-xl border border-[#e7e2da] p-5 border-b-4 border-b-rose-500">
            <p class="text-xs font-semibold text-rose-600 uppercase tracking-wide">Tidak Lolos</p>
            <p class="text-3xl font-bold text-[#24211d] mt-1">${statsData.audisi.tidak_lolos}</p>
            <p class="text-xs text-[#8c8982] mt-1">Tidak melanjutkan</p>
        </div>
        <div class="bg-white rounded-xl border border-[#e7e2da] p-5 border-b-4 border-b-slate-400">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Belum Dinilai</p>
            <p class="text-3xl font-bold text-[#24211d] mt-1">${statsData.audisi.belum_dinilai}</p>
            <p class="text-xs text-[#8c8982] mt-1">Menunggu keputusan</p>
        </div>
    `;

    // Template card untuk tab Kandidat
    const cardKandidat = `
        <div class="bg-white rounded-xl border border-[#e7e2da] p-6 flex items-center justify-between gap-4">
            <div class="flex items-center gap-4 min-w-0">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-[#fbf3df]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="27" height="27" viewBox="0 0 24 24" fill="none" stroke="#C28E27" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="10" cy="7" r="4"/>
                        <path d="M20 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-[#8c8982] uppercase tracking-wider">Total Kandidat Voting Langsung</p>
                    <p class="text-3xl font-bold text-[#24211d] mt-1">${statsData.kandidat.total}</p>
                    <p class="text-xs text-[#8c8982] mt-1">Peserta jalur voting langsung</p>
                </div>
            </div>
        </div>
    `;

    // Template card untuk tab Registrasi
    const cardRegistrasi = `
        <div class="bg-white rounded-xl border border-[#e7e2da] p-5 border-b-4 border-b-[#C28E27]">
            <p class="text-xs font-semibold text-[#8c8982] uppercase tracking-wide">Total Registrasi</p>
            <p class="text-3xl font-bold text-[#24211d] mt-1">${statsData.registrasi.total}</p>
            <p class="text-xs text-[#8c8982] mt-1">Peserta diterima</p>
        </div>
        <div class="bg-white rounded-xl border border-[#e7e2da] p-5 border-b-4 border-b-emerald-500">
            <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wide">Lunas</p>
            <p class="text-3xl font-bold text-[#24211d] mt-1">${statsData.registrasi.lunas}</p>
            <p class="text-xs text-[#8c8982] mt-1">Registrasi selesai</p>
        </div>
        <div class="bg-white rounded-xl border border-[#e7e2da] p-5 border-b-4 border-b-amber-500">
            <p class="text-xs font-semibold text-amber-600 uppercase tracking-wide">DP (Sebagian)</p>
            <p class="text-3xl font-bold text-[#24211d] mt-1">${statsData.registrasi.dp}</p>
            <p class="text-xs text-[#8c8982] mt-1">Baru bayar sebagian</p>
        </div>
        <div class="bg-white rounded-xl border border-[#e7e2da] p-5 border-b-4 border-b-slate-400">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Belum Bayar</p>
            <p class="text-3xl font-bold text-[#24211d] mt-1">${statsData.registrasi.belum}</p>
            <p class="text-xs text-[#8c8982] mt-1">Menunggu pembayaran</p>
        </div>
    `;

    // Template opsi filter untuk masing-masing tab
    const filterPendaftaran = `
        <select onchange="applyFilter(this.value, 'pendaftaran')" class="border border-[#e7e2da] rounded-lg px-3 py-2 text-sm bg-white focus:border-[#C28E27] outline-none">
            <option value="">Status Pembayaran</option>
            <option value="settlement" ${getURLParam('pendaftaran_filter') === 'settlement' ? 'selected' : ''}>Lunas</option>
            <option value="pending" ${getURLParam('pendaftaran_filter') === 'pending' ? 'selected' : ''}>Pending</option>
            <option value="belum_bayar" ${getURLParam('pendaftaran_filter') === 'belum_bayar' ? 'selected' : ''}>Belum Bayar</option>
        </select>
    `;

    const filterAudisi = `
        <select onchange="applyFilter(this.value, 'audisi')" class="border border-[#e7e2da] rounded-lg px-3 py-2 text-sm bg-white focus:border-[#C28E27] outline-none">
            <option value="">Status Audisi</option>
            <option value="lolos" ${getURLParam('audisi_filter') === 'lolos' ? 'selected' : ''}>Lolos Audisi</option>
            <option value="tidak_lolos" ${getURLParam('audisi_filter') === 'tidak_lolos' ? 'selected' : ''}>Tidak Lolos</option>
            <option value="belum_dinilai" ${getURLParam('audisi_filter') === 'belum_dinilai' ? 'selected' : ''}>Belum Dinilai</option>
        </select>
    `;

    const filterKandidat = `
        <select onchange="applyFilter(this.value, 'kandidat')" class="border border-[#e7e2da] rounded-lg px-3 py-2 text-sm bg-white focus:border-[#C28E27] outline-none">
            <option value="">Status Pembayaran</option>
            <option value="lunas" ${getURLParam('kandidat_filter') === 'lunas' ? 'selected' : ''}>Lunas</option>
            <option value="dp" ${getURLParam('kandidat_filter') === 'dp' ? 'selected' : ''}>DP (Sebagian)</option>
            <option value="belum_bayar" ${getURLParam('kandidat_filter') === 'belum_bayar' ? 'selected' : ''}>Belum Bayar</option>
        </select>
    `;

    const filterRegistrasi = `
        <select onchange="applyFilter(this.value, 'registrasi')" class="border border-[#e7e2da] rounded-lg px-3 py-2 text-sm bg-white focus:border-[#C28E27] outline-none">
            <option value="">Status Registrasi</option>
            <option value="lunas" ${getURLParam('registrasi_filter') === 'lunas' ? 'selected' : ''}>Lunas</option>
            <option value="dp" ${getURLParam('registrasi_filter') === 'dp' ? 'selected' : ''}>DP (Sebagian)</option>
            <option value="belum" ${getURLParam('registrasi_filter') === 'belum' ? 'selected' : ''}>Belum Bayar</option>
        </select>
    `;

    // ==========================================
    // BRIDGE TOAST — memakai fungsi toast yang sudah ada di pwa-head
    // ==========================================
    function panggilToast(message, type = 'success') {
        // Otomatis memakai fungsi toast mana pun yang tersedia dari pwa-head
        if (typeof window.showAdminToast === 'function') return window.showAdminToast(message, type);
        if (typeof window.showToast === 'function')      return window.showToast(message, type);
        if (typeof window.flashToast === 'function')     return window.flashToast(message, type);
        if (typeof window.toast === 'function')          return window.toast(message, type);
        console.warn('[Toast] Fungsi toast tidak ditemukan:', message);
    }

    // Helper untuk membaca URL parameter
    function getURLParam(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

    // Fungsi untuk mengubah filter dan merefresh halaman dengan tetap mempertahankan tab aktif
    function applyFilter(val, tabType) {
        const url = new URL(window.location.href);
        
        // Hapus semua filter tab lain agar bersih
        url.searchParams.delete('pendaftaran_filter');
        url.searchParams.delete('audisi_filter');
        url.searchParams.delete('kandidat_filter');
        url.searchParams.delete('registrasi_filter');
        url.searchParams.delete('page');
        url.searchParams.delete('audisi_page');
        url.searchParams.delete('kandidat_page');
        url.searchParams.delete('registrasi_page');

        if (val) {
            url.searchParams.set(tabType + '_filter', val);
        }

        window.location.href = url.toString();
    }

    // Perbarui fungsi updateStatsAndFilter yang sudah ada
    function updateStatsAndFilter(tab) {
        const statsContainer = document.getElementById('stats-container');
        const filterContainer = document.getElementById('filter-container');

        if (!statsContainer || !filterContainer) return;

        if (tab === 'pendaftaran') {
            statsContainer.className = 'grid grid-cols-1 gap-4 w-full';
            statsContainer.innerHTML = cardPendaftaran;
            filterContainer.innerHTML = filterPendaftaran;
        } else if (tab === 'audisi') {
            statsContainer.className = 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 w-full';
            statsContainer.innerHTML = cardAudisi;
            filterContainer.innerHTML = filterAudisi;
        } else if (tab === 'kandidat') {
            statsContainer.className = 'grid grid-cols-1 gap-4 w-full';
            statsContainer.innerHTML = cardKandidat;
            filterContainer.innerHTML = filterKandidat;
        } else if (tab === 'registrasi') {
            statsContainer.className = 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 w-full';
            statsContainer.innerHTML = cardRegistrasi;
            filterContainer.innerHTML = filterRegistrasi;
        }
    }

    // Fungsi switch tab
    function switchTab(tab) {
        // Simpan ke localStorage & URL hash
        localStorage.setItem('activeTab', tab);
        window.location.hash = tab;

        // Sembunyikan semua panel
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));

        // Reset semua tombol switcher
        document.querySelectorAll('.tab-switcher').forEach(b => {
            b.classList.remove('bg-[#C28E27]', 'text-white', 'shadow-sm');
            b.classList.add('text-[#8c8982]');
            const badge = b.querySelector('span');
            if (badge) {
                badge.classList.remove('bg-white/20', 'text-white');
                badge.classList.add('bg-[#e7e2da]', 'text-[#8c8982]');
            }
        });

        // Tampilkan panel yang dipilih
        const panel = document.getElementById('panel-' + tab);
        if (panel) panel.classList.remove('hidden');

        // Highlight tombol aktif
        const activeBtn = document.getElementById('btn-tab-' + tab);
        if (activeBtn) {
            activeBtn.classList.remove('text-[#8c8982]');
            activeBtn.classList.add('bg-[#C28E27]', 'text-white', 'shadow-sm');
            const badge = activeBtn.querySelector('span');
            if (badge) {
                badge.classList.remove('bg-[#e7e2da]', 'text-[#8c8982]');
                badge.classList.add('bg-white/20', 'text-white');
            }
        }

        // Update card dan filter
        updateStatsAndFilter(tab);
    }

    // Fungsi per page
    function updatePerPage(val) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', val);
        url.searchParams.delete('page');
        url.searchParams.delete('audisi_page');
        url.searchParams.delete('registrasi_page');
        window.location.href = url.toString();
    }

    // Modal functions - gunakan ID yang sesuai dengan blade
    function openModalTambahKandidat() {
        const modal = document.getElementById('modalKandidat');
        if (modal) {
            modal.classList.remove('hidden');
            modal.style.display = 'block';
            document.body.classList.add('overflow-hidden');
        } else {
            console.error('Modal dengan ID "modalKandidat" tidak ditemukan!');
        }
    }

    function closeModalTambahKandidat() {
        const modal = document.getElementById('modalKandidat');
        if (modal) {
            modal.classList.add('hidden');
            modal.style.display = 'none';
            document.body.classList.remove('overflow-hidden');
        }
    }

    // Import modal
    function openImportModal() {
        const modal = document.getElementById('importModal');
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    function closeImportModal() {
        const modal = document.getElementById('importModal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    // Preview image
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        if (!preview) return;
        
        const img = preview.querySelector('img');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Update hasil audisi via AJAX
    function updateHasilAudisi(pesertaId, hasil, btnElement) {
        if (!btnElement) return;
        
        // Disable tombol sementara
        const buttons = btnElement.parentElement.querySelectorAll('button');
        buttons.forEach(b => b.disabled = true);
        
        // Tampilkan loading state
        const originalText = btnElement.innerHTML;
        btnElement.innerHTML = '⏳...';

        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            console.error('CSRF token tidak ditemukan');
            buttons.forEach(b => b.disabled = false);
            btnElement.innerHTML = originalText;
            return;
        }

        fetch(`/admin/peserta/${pesertaId}/hasil-audisi`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                hasil_audisi: hasil
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update badge status di row ini saja
                const row = btnElement.closest('tr');
                if (row) {
                    const badgeCell = row.querySelector('td:nth-child(5)');
                    if (badgeCell) {
                        let badgeClass, badgeText;
                        if (hasil === 'lolos') {
                            badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                            badgeText = 'Lolos Audisi';
                        } else {
                            badgeClass = 'bg-rose-50 text-rose-700 border-rose-200';
                            badgeText = 'Tidak Lolos';
                        }
                        badgeCell.innerHTML = `<span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full border ${badgeClass}">${badgeText}</span>`;
                    }
                }
                
                // Update statistik
                updateStatsAfterAction(hasil);
                
                // Toast
                showToast(`Peserta ${hasil === 'lolos' ? 'lolos' : 'tidak lolos'} audisi`, 'success');
            } else {
                showToast('Gagal mengupdate status', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan', 'error');
        })
        .finally(() => {
            buttons.forEach(b => {
                b.disabled = false;
                if (b === btnElement) b.innerHTML = originalText;
            });
        });
    }

    // Helper update statistik
    function updateStatsAfterAction(hasil) {
        if (hasil === 'lolos') {
            statsData.audisi.lolos++;
            statsData.audisi.belum_dinilai--;
        } else {
            statsData.audisi.tidak_lolos++;
            statsData.audisi.belum_dinilai--;
        }
        
        const activePanel = document.getElementById('panel-audisi');
        if (activePanel && !activePanel.classList.contains('hidden')) {
            updateStatsAndFilter('audisi');
        }
    }

    // Helper toast
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 px-4 py-3 rounded-lg shadow-lg text-white text-sm font-medium z-50 ${
            type === 'success' ? 'bg-emerald-600' : 'bg-red-600'
        }`;
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s';
            setTimeout(() => toast.remove(), 300);
        }, 2000);
    }

    // Inisialisasi saat DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const url = new URL(window.location.href);
                        const query = this.value.trim();
                        
                        if (query) {
                            url.searchParams.set('search', query);
                        } else {
                            url.searchParams.delete('search');
                        }
                        
                        // Reset halaman pagination setiap kali melakukan pencarian baru
                        url.searchParams.delete('page');
                        url.searchParams.delete('audisi_page');
                        url.searchParams.delete('kandidat_page');
                        url.searchParams.delete('registrasi_page');
                        
                        window.location.href = url.toString();
                    }
                });
            }

        // Initialize tab
        const hash = window.location.hash.replace('#', '');
        const savedTab = localStorage.getItem('activeTab');
        const validTabs = ['pendaftaran', 'audisi', 'kandidat', 'registrasi'];
        
        const activeTab = validTabs.includes(hash) ? hash : 
                        validTabs.includes(savedTab) ? savedTab : 
                        'pendaftaran';
        
        switchTab(activeTab);

        // Close modal on ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModalTambahKandidat();
                closeImportModal();
            }
        });

        // Close modal on backdrop click
        document.querySelectorAll('[id^="modal"]').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            });
        });
    });

    // Data riwayat pembayaran (disimpan sementara)
    let riwayatPembayaranData = [];

    // ==========================================
    // FUNGSI MODAL PEMBAYARAN KANDIDAT & RIWAYAT
    // ==========================================
    function openModalPembayaranKandidat(pesertaId, totalBiaya, sudahDibayar, namaPeserta, listPembayaran = []) {
        // Isi nilai hidden & teks informasi dasar
        document.getElementById('bayar_peserta_id').value = pesertaId;
        document.getElementById('bayar_total_biaya').value = totalBiaya;
        document.getElementById('bayar_nama_peserta').textContent = namaPeserta;

        // Format Rupiah helper
        const fmt = (num) => 'Rp ' + new Intl.NumberFormat('id-ID').format(num);
        document.getElementById('bayar_display_total').value = fmt(totalBiaya);
        document.getElementById('bayar_display_sudah').value = fmt(sudahDibayar);

        // Hitung Sisa
        const sisa = Math.max(0, totalBiaya - sudahDibayar);
        document.getElementById('bayar_display_sisa').value = fmt(sisa);

        // 1. Render Tabel Riwayat Pembayaran
        const tbody = document.getElementById('riwayat_pembayaran_list');
        tbody.innerHTML = '';

        if (listPembayaran && listPembayaran.length > 0) {
            listPembayaran.forEach(item => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-gray-50';
                
                // Format tanggal bayar
                let tglFormatted = '-';
                if (item.tanggal_bayar) {
                    const d = new Date(item.tanggal_bayar);
                    tglFormatted = d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
                }

                tr.innerHTML = `
                    <td class="px-3 py-2 text-gray-700">${tglFormatted}</td>
                    <td class="px-3 py-2 text-gray-700 uppercase font-medium">${item.metode || item.status_midtrans || 'Manual'}</td>
                    <td class="px-3 py-2 text-right font-semibold text-emerald-600">${fmt(item.jumlah || 0)}</td>
                `;
                tbody.appendChild(tr);
            });
        } else {
            tbody.innerHTML = `<tr><td colspan="3" class="px-3 py-3 text-center text-gray-400 italic">Belum ada riwayat pembayaran.</td></tr>`;
        }

        // 2. Set otomatis jumlah bayar dengan Sisa Pembayaran (agar jika DP, angkanya langsung pas ke sisa)
        const inputFormatted = document.getElementById('bayar_jumlah_bayar_formatted');
        const inputRaw = document.getElementById('bayar_jumlah_bayar_raw');
        
        inputRaw.value = sisa;
        inputFormatted.value = sisa > 0 ? new Intl.NumberFormat('id-ID').format(sisa) : '';

        // Tampilkan modal
        const modal = document.getElementById('modalPembayaranKandidat');
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        document.body.classList.add('overflow-hidden');
    }

    function closeModalPembayaranKandidat() {
        const modal = document.getElementById('modalPembayaranKandidat');
        modal.classList.add('hidden');
        modal.style.display = 'none';
        document.body.classList.remove('overflow-hidden');
        
        const form = document.getElementById('formPembayaranManual');
        if (form) form.reset();
    }

    // ==========================================
    // ✅ FUNGSI MODAL PEMBAYARAN REGISTRASI (peserta pendaftaran)
    // ==========================================
    function openModalPembayaranRegistrasi(pesertaId, totalBiaya, sudahDibayar, namaPeserta, listPembayaran = []) {
        document.getElementById('regbayar_peserta_id').value = pesertaId;
        document.getElementById('regbayar_nama_peserta').textContent = namaPeserta;

        const fmt = (num) => 'Rp ' + new Intl.NumberFormat('id-ID').format(num);
        document.getElementById('regbayar_display_total').value = fmt(totalBiaya);
        document.getElementById('regbayar_display_sudah').value = fmt(sudahDibayar);

        const sisa = Math.max(0, totalBiaya - sudahDibayar);
        document.getElementById('regbayar_display_sisa').value = fmt(sisa);

        // Render riwayat pembayaran registrasi (hanya yang sudah settlement)
        const tbody = document.getElementById('riwayat_pembayaran_registrasi_list');
        tbody.innerHTML = '';

        const riwayat = (listPembayaran || []).filter(item => item.status_midtrans === 'settlement');

        if (riwayat.length > 0) {
            riwayat.forEach(item => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-gray-50';

                let tglFormatted = '-';
                if (item.tanggal_bayar) {
                    const d = new Date(item.tanggal_bayar);
                    tglFormatted = d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
                }

                tr.innerHTML = `
                    <td class="px-3 py-2 text-gray-700">${tglFormatted}</td>
                    <td class="px-3 py-2 text-gray-700 uppercase font-medium">${item.metode || 'Manual'}</td>
                    <td class="px-3 py-2 text-right font-semibold text-emerald-600">${fmt(item.jumlah || 0)}</td>
                `;
                tbody.appendChild(tr);
            });
        } else {
            tbody.innerHTML = `<tr><td colspan="3" class="px-3 py-3 text-center text-gray-400 italic">Belum ada riwayat pembayaran.</td></tr>`;
        }

        // Set otomatis jumlah bayar dengan sisa tagihan
        const inputFormatted = document.getElementById('regbayar_jumlah_bayar_formatted');
        const inputRaw = document.getElementById('regbayar_jumlah_bayar_raw');

        inputRaw.value = sisa;
        inputFormatted.value = sisa > 0 ? new Intl.NumberFormat('id-ID').format(sisa) : '';

        const modal = document.getElementById('modalPembayaranRegistrasi');
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        document.body.classList.add('overflow-hidden');
    }

    function closeModalPembayaranRegistrasi() {
        const modal = document.getElementById('modalPembayaranRegistrasi');
        modal.classList.add('hidden');
        modal.style.display = 'none';
        document.body.classList.remove('overflow-hidden');

        const form = document.getElementById('formPembayaranRegistrasi');
        if (form) form.reset();
    }

    // ==========================================
    // NUMBER FORMATTER & REALTIME SISA KALKULATOR + LIMITER
    // ==========================================
    document.addEventListener('DOMContentLoaded', function() {
        const inputFormatted = document.getElementById('bayar_jumlah_bayar_formatted');
        const inputRaw = document.getElementById('bayar_jumlah_bayar_raw');

        if (inputFormatted && inputRaw) {
            inputFormatted.addEventListener('input', function(e) {
                // Ambil hanya angka murni
                let value = this.value.replace(/\D/g, '');
                let numericValue = value ? parseInt(value, 10) : 0;

                // Hitung total biaya dan sisa tagihan saat ini
                const total = parseInt(document.getElementById('bayar_total_biaya').value) || 0;
                const sudahStr = document.getElementById('bayar_display_sudah').value.replace(/\D/g, '');
                const sudah = parseInt(sudahStr) || 0;
                const sisaMaksimal = Math.max(0, total - sudah);

                // 🛑 LIMITER: Jika melebihi sisa pembayaran, batasi ke sisa maksimal dan beri peringatan
                if (numericValue > sisaMaksimal) {
                    numericValue = sisaMaksimal;
                    showToast('Jumlah bayar tidak boleh melebihi sisa tagihan!', 'error');
                }

                // Masukkan ke input hidden untuk dikirim ke backend
                inputRaw.value = numericValue;

                // Format kembali ke tampilan dengan titik ribuan (id-ID)
                this.value = numericValue > 0 ? new Intl.NumberFormat('id-ID').format(numericValue) : '';

                // Update real-time sisa pembayaran pada ringkasan
                const sisaBaru = Math.max(0, sisaMaksimal - numericValue);
                document.getElementById('bayar_display_sisa').value = 'Rp ' + new Intl.NumberFormat('id-ID').format(sisaBaru);
            });
        }
    });

    // ✅ NUMBER FORMATTER & LIMITER — khusus modal Pembayaran Registrasi
    document.addEventListener('DOMContentLoaded', function() {
        const inputFormatted = document.getElementById('regbayar_jumlah_bayar_formatted');
        const inputRaw = document.getElementById('regbayar_jumlah_bayar_raw');

        if (inputFormatted && inputRaw) {
            inputFormatted.addEventListener('input', function(e) {
                let value = this.value.replace(/\D/g, '');
                let numericValue = value ? parseInt(value, 10) : 0;

                const totalStr = document.getElementById('regbayar_display_total').value.replace(/\D/g, '');
                const total = parseInt(totalStr) || 0;
                const sudahStr = document.getElementById('regbayar_display_sudah').value.replace(/\D/g, '');
                const sudah = parseInt(sudahStr) || 0;
                const sisaMaksimal = Math.max(0, total - sudah);

                if (numericValue > sisaMaksimal) {
                    numericValue = sisaMaksimal;
                    showToast('Jumlah bayar tidak boleh melebihi sisa tagihan!', 'error');
                }

                inputRaw.value = numericValue;
                this.value = numericValue > 0 ? new Intl.NumberFormat('id-ID').format(numericValue) : '';

                const sisaBaru = Math.max(0, sisaMaksimal - numericValue);
                document.getElementById('regbayar_display_sisa').value = 'Rp ' + new Intl.NumberFormat('id-ID').format(sisaBaru);
            });
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const clearBtn = document.getElementById('clearSearch');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const keyword = this.value.toLowerCase().trim();

                // Tampilkan atau sembunyikan tombol clear (X)
                if (clearBtn) {
                    if (keyword.length > 0) {
                        clearBtn.classList.remove('hidden');
                    } else {
                        clearBtn.classList.add('hidden');
                    }
                }

                // Ambil panel tab yang sedang aktif saat ini saja
                const activePanel = document.querySelector('.tab-panel:not(.hidden)');
                if (!activePanel) return;

                // Cari semua baris (tr) di dalam tabel pada panel aktif tersebut
                const rows = activePanel.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    // Abaikan baris "kosong/empty state" (misal: "Belum ada peserta...")
                    if (row.querySelector('td[colspan]')) return;

                    const rowText = row.innerText.toLowerCase();
                    if (rowText.includes(keyword)) {
                        row.style.display = ''; // Tampilkan
                    } else {
                        row.style.display = 'none'; // Sembunyikan seketika tanpa refresh
                    }
                });
            });
        }

        // Tombol Clear (X) untuk mereset pencarian instan
        if (clearBtn) {
            clearBtn.addEventListener('click', function() {
                if (searchInput) {
                    searchInput.value = '';
                    searchInput.dispatchEvent(new Event('input')); // Picu event input untuk mereset tampilan baris
                    clearBtn.classList.add('hidden');
                }
            });
        }
    });

    // ==========================================
// MODAL TIDAK LOLOS AUDISI
// ==========================================
function openModalTolak(pesertaId, namaPeserta, catatanLama = '') {
    document.getElementById('tolak_peserta_id').value = pesertaId;
    document.getElementById('tolak_nama_peserta').textContent = namaPeserta;
    document.getElementById('tolak_catatan').value = catatanLama || '';
    
    const form = document.getElementById('formTolakAudisi');
    if (form) {
        form.action = '/admin/peserta/' + pesertaId + '/hasil-audisi';
    }
    
    const modal = document.getElementById('modalTolakAudisi');
    if (modal) {
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        document.body.classList.add('overflow-hidden');
    }
}

function closeModalTolak() {
    const modal = document.getElementById('modalTolakAudisi');
    if (modal) {
        modal.classList.add('hidden');
        modal.style.display = 'none';
        document.body.classList.remove('overflow-hidden');
    }
    
    const form = document.getElementById('formTolakAudisi');
    if (form) form.reset();
}

// Handle submit form tolak via AJAX
document.addEventListener('DOMContentLoaded', function() {
    const formTolak = document.getElementById('formTolakAudisi');
    if (formTolak) {
        formTolak.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const pesertaId = document.getElementById('tolak_peserta_id').value;
            const catatan = document.getElementById('tolak_catatan').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            
            if (!csrfToken) {
                alert('CSRF token tidak ditemukan!');
                return;
            }

            fetch('/admin/peserta/' + pesertaId + '/hasil-audisi', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    hasil_audisi: 'tidak_lolos',
                    catatan_admin: catatan
                })
            })
            .then(response => response.json())
            .then(data => {
            if (data.success) {
                // Ambil nama peserta SEBELUM modal ditutup
                const namaPeserta = (document.getElementById('tolak_nama_peserta')?.textContent || 'Peserta').trim();

                closeModalTolak();

                // ✅ Pakai toast existing dari pwa-head (tanpa elemen baru)
                panggilToast('Peserta ' + namaPeserta + ' ditandai TIDAK LOLOS.', 'error');

                // Tunda reload ±2 detik supaya toast sempat terlihat
                setTimeout(function () {
                    window.location.reload();
                }, 2000);
            } else {
                panggilToast(data.message || 'Gagal menyimpan penolakan.', 'error');
            }
        })
            .catch(error => {
                console.error('❌ Error:', error);
                alert('Terjadi kesalahan sistem.');
            });
        });
    }
});

// Close modal on ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModalTolak();
    }
});

// Close modal on backdrop click
document.getElementById('modalTolakAudisi')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeModalTolak();
    }
});

    // ==========================================
    // MODAL CATATAN ADMIN
    // ==========================================
    function openModalCatatanAdmin(pesertaId, namaPeserta, catatan) {
        console.log(' Opening catatan modal:', { pesertaId, namaPeserta, catatan });
        
        document.getElementById('catatan_nama_peserta').textContent = namaPeserta;
        document.getElementById('catatan_isi').textContent = catatan || '(Tidak ada catatan)';
        
        const modal = document.getElementById('modalCatatanAdmin');
        modal.classList.remove('hidden');
        modal.style.display = 'block';
        
        // ✅ PENTING: Kunci scroll body
        document.body.style.overflow = 'hidden';
        
        console.log('✅ Modal catatan dibuka');
    }

    function closeModalCatatanAdmin() {
        const modal = document.getElementById('modalCatatanAdmin');
        modal.classList.add('hidden');
        modal.style.display = 'none';
        
        // ✅ Buka kembali scroll body
        document.body.style.overflow = '';
        
        console.log('🔴 Modal catatan ditutup');
    }

    // Close modal on ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModalCatatanAdmin();
        }
    });

    // Close modal on backdrop click
    document.getElementById('modalCatatanAdmin')?.addEventListener('click', function(e) {
        if (e.target === this || e.target.classList.contains('backdrop-blur-sm')) {
            closeModalCatatanAdmin();
        }
    });
</script>

    @include('partials.swal')

</body>
</html>