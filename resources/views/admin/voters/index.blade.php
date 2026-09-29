<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Daftar Voter | ALNAY.ID</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { gold: '#ba8320', cream: '#f7f5f0' } } } };
    </script>
</head>
<body class="bg-cream font-['Inter'] text-[13px] text-[#22201d]">
<div class="flex min-h-screen">
    @include('admin.partials.sidebar', ['active' => 'voters', 'event' => $event])

    <main class="flex-1 min-w-0 p-6 ml-[235px]">
        <div class="flex items-center gap-3 mb-1">
            <h1 class="text-2xl font-bold m-0">Daftar Voter</h1>
            <span class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-500 text-[11px] font-bold px-2.5 py-1 rounded-full">
                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span> Live
            </span>
        </div>
        <p class="text-[#73716c] text-[12px] mb-5">
            {{ $event?->nama ?? 'Belum ada event' }}
        </p>

        @if (!$event)
            <div class="bg-white border border-dashed border-[#e3ded6] rounded-xl p-8 text-center text-[#98958f]">
                Belum ada event.
            </div>
        @else
            {{-- Stat cards --}}
            <div class="grid grid-cols-4 gap-4 mb-5">
                <div class="bg-white rounded-xl border border-[#e3ded6] p-4">
                    <div class="text-3xl font-bold">{{ number_format($totalSuara, 0, ',', '.') }}</div>
                    <div class="text-[11px] text-[#73716c] mt-1">Total Suara Masuk</div>
                </div>
                <div class="bg-white rounded-xl border border-[#e3ded6] p-4">
                    <div class="text-3xl font-bold">{{ $voterUnik }}</div>
                    <div class="text-[11px] text-[#73716c] mt-1">Voter Unik (Akun Berbeda)</div>
                </div>
                <div class="bg-white rounded-xl border border-[#e3ded6] p-4">
                    <div class="text-lg font-bold text-emerald-600 truncate">{{ $terpopuler?->peserta?->user?->nama ?? '-' }}</div>
                    <div class="text-[11px] text-[#73716c] mt-1">Sementara Terpopuler</div>
                    <div class="text-[10px] text-[#98958f]">{{ $terpopuler->total ?? 0 }} suara</div>
                </div>
                <div class="bg-white rounded-xl border border-[#e3ded6] p-4">
                    <div class="text-2xl font-bold font-mono">{{ $suaraTerakhir ? \Carbon\Carbon::parse($suaraTerakhir->tanggal_vote)->format('H.i.s') : '-' }}</div>
                    <div class="text-[11px] text-[#73716c] mt-1">Suara Terakhir Masuk</div>
                </div>
            </div>

            {{-- Filter --}}
            <div class="flex items-center gap-3 mb-4">
                <select id="filterPeserta" class="border border-[#e3ded6] rounded-lg px-3 py-2.5 text-[12px] bg-white">
                    <option value="">Semua Peserta</option>
                    @foreach ($pesertaList as $p)
                        <option value="{{ $p->id }}">{{ $p->user->nama ?? '-' }}</option>
                    @endforeach
                </select>
                <input id="searchVoter" type="text" placeholder="Cari nama atau email voter..."
                       class="flex-1 border border-[#e3ded6] rounded-lg px-3 py-2.5 text-[12px]">
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-xl border border-[#e3ded6] overflow-hidden">
                <table class="w-full text-[12px]">
                    <thead>
                        <tr class="border-b border-[#e3ded6] text-left text-[10px] uppercase text-[#98958f]">
                            <th class="p-3 font-bold">Waktu Vote</th>
                            <th class="p-3 font-bold">Nama Voter</th>
                            <th class="p-3 font-bold">Email</th>
                            <th class="p-3 font-bold">Peserta yang Dipilih</th>
                            <th class="p-3 font-bold text-right">Jumlah Vote</th>
                        </tr>
                    </thead>
                    <tbody id="voterRows">
                        @forelse ($votings as $v)
                            @php
                                $namaPeserta = $v->peserta?->user?->nama ?? '-';
                                $inisial = collect(explode(' ', $namaPeserta))->map(fn($w) => strtoupper($w[0] ?? ''))->take(2)->implode('');
                            @endphp
                            <tr class="border-b border-[#f0ede6]"
                                data-peserta-id="{{ $v->peserta_id }}"
                                data-search="{{ strtolower(($v->voter->nama ?? '').' '.($v->voter->email ?? '')) }}">
                                <td class="p-3 font-mono text-[#73716c]">{{ \Carbon\Carbon::parse($v->tanggal_vote)->format('H.i.s') }}</td>
                                <td class="p-3 font-semibold">{{ $v->voter->nama ?? '-' }}</td>
                                <td class="p-3 text-[#73716c]">{{ $v->voter->email ?? '-' }}</td>
                                <td class="p-3">
                                    <span class="inline-flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-full bg-[#f0e6cf] text-[#8a6d1f] text-[10px] font-bold grid place-items-center">{{ $inisial }}</span>
                                        {{ $namaPeserta }}
                                    </span>
                                </td>
                                <td class="p-3 text-right">
                                    <span class="bg-[#f7f2e4] text-[#8a6d1f] text-[11px] font-semibold px-2.5 py-1 rounded-full">{{ $v->jumlah_vote }} suara</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="p-6 text-center text-[#98958f]">Belum ada suara masuk.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </main>
</div>

<script>
    const rows = document.querySelectorAll('#voterRows tr[data-peserta-id]');
    const filterPeserta = document.getElementById('filterPeserta');
    const searchVoter = document.getElementById('searchVoter');

    function applyFilters() {
        const pesertaId = filterPeserta?.value || '';
        const q = (searchVoter?.value || '').toLowerCase();

        rows.forEach(row => {
            const matchPeserta = !pesertaId || row.dataset.pesertaId === pesertaId;
            const matchSearch = !q || row.dataset.search.includes(q);
            row.style.display = (matchPeserta && matchSearch) ? '' : 'none';
        });
    }

    filterPeserta?.addEventListener('change', applyFilters);
    searchVoter?.addEventListener('input', applyFilters);

    // "Live" - refresh data berkala. Ini reload halaman penuh (paling
    // sederhana); kalau butuh update tanpa flicker, bisa diganti fetch AJAX.
    setTimeout(() => location.reload(), 20000);
</script>
</body>
</html>