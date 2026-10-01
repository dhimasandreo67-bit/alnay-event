<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('admin.partials.pwa-head')
    <title>Daftar Event | ALNAY.ID</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/css/admin-sidebar.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { gold: '#ba8320', cream: '#f7f5f0', ink: '#22201d' },
                    fontFamily: { sans: ['Inter', 'Arial', 'sans-serif'] },
                },
            },
        };
    </script>
</head>
<body class="bg-cream text-ink font-sans text-[13px]">
    <div class="flex min-h-screen w-full">
        <main class="flex-1 min-w-0 ml-[235px]">
            {{-- HEADER --}}
            <header class="flex items-center justify-between px-6 py-4 border-b border-[#e5e1db] bg-white">
                <div>
                    <h1 class="font-bold text-[16px] m-0">Kelola Data Event</h1>
                    <p class="text-[10px] text-[#8a8984] m-0">ALNAY.ID — Daftar seluruh event terdaftar</p>
                </div>
                <a href="{{ route('admin.events.create') }}" 
                   class="inline-flex items-center gap-2 rounded-md bg-gold border border-gold px-4 py-2 text-white text-[11px] font-bold">
                    + Buat Event Baru
                </a>
            </header>

            <div class="p-6">

            {{-- ✅ DROPDOWN PEMILIHAN EVENT AKTIF --}}
            <div class="mb-6 bg-white rounded-xl border border-slate-200 p-4">
                <div class="flex items-center justify-between gap-4 flex-wrap">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <label for="select-event" class="text-sm font-semibold text-slate-700 whitespace-nowrap">
                            Event Aktif:
                        </label>
                        <select id="select-event"
                            onchange="window.location.href='{{ route('admin.events.index') }}?event=' + this.value"
                            class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-amber-300 focus:border-amber-400">
                        @foreach ($eventList as $e)
                            <option value="{{ $e->id }}"
                                    {{ ($activeEvent?->id ?? 0) === $e->id ? 'selected' : '' }}>
                                {{ $e->nama }} (ID: {{ $e->id }})
                            </option>
                        @endforeach
                    </select>
                    </div>

                    @if ($activeEvent)
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.events.edit', $activeEvent->id) }}" 
                            class="px-3 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 text-sm font-medium rounded-lg border border-amber-200 transition">
                                Edit Event Ini
                            </a>
                            <a href="{{ route('admin.activation', $activeEvent->id) }}" 
                            class="px-3 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-sm font-medium rounded-lg border border-emerald-200 transition">
                                Aktivasi Peserta
                            </a>
                            <a href="{{ route('admin.voting.settings', $activeEvent->id) }}" 
                            class="px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 text-sm font-medium rounded-lg border border-blue-200 transition">
                                Pengaturan Voting
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Info Event Aktif --}}
                @if ($activeEvent)
                    <div class="mt-3 pt-3 border-t border-slate-100 grid grid-cols-2 md:grid-cols-4 gap-3 text-xs">
                        <div>
                            <span class="text-slate-500">Status:</span>
                            @php
                                $statusClass = match($activeEvent->status) {
                                    'akan_datang' => 'bg-blue-100 text-blue-700 border-blue-200',
                                    'berlangsung' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                    'selesai' => 'bg-orange-100 text-orange-700 border-orange-200',
                                    default => 'bg-slate-100 text-slate-700 border-slate-200'
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold border ml-1 {{ $statusClass }}">
                                {{ $activeEvent->status_label }}
                            </span>
                        </div>
                        <div>
                            <span class="text-slate-500">Peserta:</span>
                            <span class="font-semibold text-slate-800 ml-1">
                                {{ $activeEvent->pesertas()->count() }} / {{ $activeEvent->kuota_peserta ?? '-' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-slate-500">Biaya Daftar:</span>
                            <span class="font-semibold text-slate-800 ml-1">
                                Rp {{ number_format($activeEvent->biaya_pendaftaran ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                        <div>
                            <span class="text-slate-500">Lokasi:</span>
                            <span class="font-semibold text-slate-800 ml-1 truncate block">
                                {{ $activeEvent->lokasi ?? '-' }}
                            </span>
                        </div>
                    </div>
                @endif
            </div>

                @if (session('success'))
                    <div class="mb-4 rounded-lg bg-emerald-50 text-emerald-700 px-4 py-3 text-[12px] border border-emerald-200">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- TABEL --}}
                <div class="bg-white border border-[#e3ded6] rounded-xl overflow-hidden shadow-sm">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#fcfbf9] border-b border-[#e3ded6] text-[11px] text-[#8a8984] uppercase tracking-wider">
                                <th class="py-3.5 px-4 font-bold">Nama Event & Lokasi</th>
                                <th class="py-3.5 px-4 font-bold">Status</th>
                                <th class="py-3.5 px-4 font-bold">Pelaksanaan</th>
                                <th class="py-3.5 px-4 font-bold">Tarif</th>
                                <th class="py-3.5 px-4 font-bold">Kuota</th>
                                <th class="py-3.5 px-4 font-bold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e3ded6]">
                            @forelse ($events as $item)
                                <tr class="hover:bg-slate-50 transition {{ ($activeEvent?->id ?? 0) === $item->id ? 'bg-amber-50/50 border-l-4 border-l-amber-400' : '' }}">
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $item->banner ? asset('storage/' . $item->banner) : 'https://via.placeholder.com/150' }}" 
                                                 class="w-14 h-10 object-cover rounded-md border border-[#e5e1db]">
                                            <div>
                                                <div class="font-bold text-ink text-[13px] line-clamp-1">
                                                    {{ $item->nama }}
                                                </div>
                                                <span class="text-[10px] text-[#8a8984] block mt-0.5">
                                                    Lokasi: {{ $item->lokasi }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- KOLOM STATUS EVENT --}}
                                    <td class="py-3 px-4 text-[11px]">
                                        @if ($item->status === 'akan_datang')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200 whitespace-nowrap">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 shrink-0"></span>
                                                Akan Datang
                                            </span>
                                        @elseif ($item->status === 'sedang_berlangsung')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 whitespace-nowrap">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>
                                                Sedang Berlangsung
                                            </span>
                                        @elseif ($item->status === 'telah_berakhir')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-orange-50 text-orange-700 border border-orange-200 whitespace-nowrap">
                                                <span class="w-1.5 h-1.5 rounded-full bg-orange-500 shrink-0"></span>
                                                Telah Berakhir
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-gray-50 text-gray-600 border border-gray-200 whitespace-nowrap">
                                                {{ $item->status_label ?? 'Tidak Diketahui' }}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="py-3 px-4 text-[11px]">
                                    {{-- Tanggal & Waktu Mulai --}}
                                    <div class="font-semibold text-ink flex items-center gap-1">
                                        <span>{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}</span>
                                        @if ($item->waktu_mulai)
                                            <span class="text-slate-500 font-normal"> - {{ \Carbon\Carbon::parse($item->waktu_mulai)->format('H:i') }} WIB </span>
                                        @endif
                                    </div>
                                    
                                    {{-- Tanggal & Waktu Selesai --}}
                                    <div class="font-semibold text-ink flex items-center gap-1">
                                        <span>{{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}</span>
                                        @if ($item->waktu_selesai)
                                            <span class="text-slate-500 font-normal"> - {{ \Carbon\Carbon::parse($item->waktu_selesai)->format('H:i') }} WIB</span>
                                        @endif
                                    </div>
                                </td>

                                    <td class="py-3 px-4 text-[11px]">
                                        <div>Daftar: <strong class="text-ink">{{ $item->biaya_pendaftaran > 0 ? 'Rp ' . number_format($item->biaya_pendaftaran, 0, ',', '.') : 'Gratis' }}</strong></div>
                                        <div class="text-[#8a8984]">Vote: {{ $item->harga_per_vote > 0 ? 'Rp ' . number_format($item->harga_per_vote, 0, ',', '.') : '—' }}</div>
                                    </td>

                                    <td class="py-3 px-4 text-[11px]">
                                        <span class="inline-block bg-[#f7f5f0] border border-[#e5e1db] rounded px-2 py-1 font-semibold text-ink">
                                            {{ $item->kuota_peserta }} Peserta
                                        </span>
                                    </td>

                                    <td class="py-3 px-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.events.edit', $item->id) }}" 
                                               class="px-2.5 py-1.5 rounded-md border border-[#e5e1db] bg-white text-ink text-[11px] font-bold hover:bg-[#f7f5f0]">
                                                Edit
                                            </a>

                                            <form action="{{ route('admin.events.destroy', $item->id) }}" method="POST" 
                                                  onsubmit="return confirm('Yakin ingin menghapus event ini?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="px-2.5 py-1.5 rounded-md border border-red-200 bg-red-50 text-red-600 text-[11px] font-bold hover:bg-red-100">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-[#8a8984]">
                                        Belum ada data event. <a href="{{ route('admin.events.create') }}" class="text-gold underline font-bold">Buat Event Pertama</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if ($events->hasPages())
                        <div class="p-4 border-t border-[#e3ded6]">
                            {{ $events->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    @include('admin.partials.sidebar', ['active' => 'events-index'])
</body>
</html>