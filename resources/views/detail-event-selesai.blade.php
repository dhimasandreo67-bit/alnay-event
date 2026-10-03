<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $event->nama }} - Hasil Akhir | ALNAY.ID</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
    </style>
</head>

<body class="bg-[#faf9f6] text-[#15233d]">

    @php
        // =====================================================
        // DATA DARI CONTROLLER
        // $event, $pesertas, $pemenang, $peringkatLain, $totalSuara
        // =====================================================

        // Format angka — 15.400 → 15.4K
        $formatAngka = function ($angka) {
            if ($angka >= 1000000) return round($angka / 1000000, 1) . 'M';
            if ($angka >= 1000)    return round($angka / 1000, 1) . 'K';
            return number_format($angka, 0, ',', '.');
        };

        // Helper inisial — "Raden Roro Ayu" → "RR"
        $inisial = fn ($nama) => strtoupper(implode('', array_map(
            fn ($kata) => mb_substr($kata, 0, 1),
            array_slice(preg_split('/\s+/', trim($nama)), 0, 2)
        )));

        // Ubah $pesertas jadi array kandidat
        $kandidat = $pesertas->map(function ($p, $i) use ($totalSuara) {
            return [
                'id'     => $p->id,
                'nomor'  => str_pad($i + 1, 2, '0', STR_PAD_LEFT),
                'nama'   => $p->namaTampil(),
                'asal'   => '-', // nanti kalau ada jawaban provinsi
                'foto'   => $p->user?->foto_custom
                ? asset('storage/' . $p->user->foto_custom)
                : $p->user?->foto_url,
                'persen' => $totalSuara > 0 ? round(($p->total_vote / $totalSuara) * 100, 2) : 0,
                'vote'   => $p->total_vote,
            ];
        })->toArray();

        // Pemenang — top 3
        $pemenangArr = [];
        foreach (array_slice($kandidat, 0, 3) as $i => $k) {
            $k['juara'] = $i + 1;
            $pemenangArr[] = $k;
        }

        // Peringkat 4-6
        $peringkatLainArr = [];
        foreach (array_slice($kandidat, 3, 3) as $i => $k) {
            $k['peringkat'] = sprintf('%02d', $i + 4);
            $k['inisial']   = $inisial($k['nama']);
            $peringkatLainArr[] = $k;
        }

        $jumlahKandidat = count($kandidat);
    @endphp

    {{-- HEADER --}}
    <header class="sticky top-0 z-50 border-b border-[#e9e9e9] bg-white/95 backdrop-blur">
        <div class="mx-auto flex h-[56px] max-w-[1200px] items-center justify-between px-5 md:px-8">
            <a href="{{ route('dashboard-user') }}" class="font-display text-[16px] font-bold tracking-tight text-[#13223a]">
                EVENTALNAY.ID
            </a>

            @auth('pelanggan')
                @php
                    $u = auth('pelanggan')->user();
                    $fotoHeader = $u->foto_custom ? asset('storage/' . $u->foto_custom) : $u->foto_url;
                    $inisialHeader = strtoupper(substr(trim($u->nama_custom ?: $u->nama), 0, 1));
                @endphp
                <a href="{{ route('profil-user') }}" aria-label="Profil pengguna"
                    class="flex h-9 w-9 items-center justify-center rounded-full border border-[#d9d9d9] bg-white text-[#333] transition hover:bg-[#f7f7f7] overflow-hidden">
                    @if ($fotoHeader)
                        <img src="{{ $fotoHeader }}" alt="{{ $u->nama_custom ?: $u->nama }}"
                            class="w-full h-full object-cover">
                    @else
                        <span class="text-[11px] font-bold">{{ $inisialHeader }}</span>
                    @endif
                </a>
            @else
                <a href="{{ route('auth.google') }}"
                    class="rounded-full bg-[#cf9b32] px-4 py-2 text-[11px] font-semibold text-white transition hover:bg-[#b88625]">
                    Login
                </a>
            @endauth
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-5 pt-0 pb-6">

        {{-- EVENT BANNER --}}
        <section class="relative overflow-hidden rounded-xl mx-0 md:-mx-10 mt-4">
            @if ($event->banner)
                <img src="{{ asset('storage/' . $event->banner) }}"
                    alt="{{ $event->nama }}"
                    class="w-full h-[220px] sm:h-[300px] md:h-[380px] object-cover object-center">
            @else
                <div class="w-full h-[220px] sm:h-[300px] md:h-[380px] bg-gradient-to-br from-[#0a1526] to-[#1a2942] flex items-center justify-center">
                    <p class="font-display text-3xl font-bold text-[#c99a32] tracking-wider">{{ $event->nama }}</p>
                </div>
            @endif
        </section>

        {{-- EVENT INFORMATION --}}
        <section class="relative -mt-5 mx-4 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-4 md:p-5">
                <div class="grid grid-cols-1 lg:grid-cols-[1fr_200px] gap-5">

                    <div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-[#c99a32] text-white text-[10px] font-semibold uppercase tracking-wider">
                            Telah Berakhir
                        </span>

                        <h1 class="font-display text-xl md:text-2xl font-bold text-[#111827] mt-2.5">
                            {{ $event->nama }}
                        </h1>

                        <p class="text-gray-500 text-xs md:text-sm leading-relaxed mt-2.5 max-w-3xl">
                            {{ $event->deskripsi ?: 'Deskripsi belum diisi.' }}
                        </p>
                    </div>

                    <div class="bg-[#faf8f2] border border-[#eee5d2] rounded-lg p-3.5 flex items-center">
                        <div class="grid grid-cols-2 gap-4 w-full text-center">
                            <div>
                                <p class="text-lg font-bold">{{ $jumlahKandidat }}</p>
                                <p class="text-xs text-gray-500 mt-1">Kandidat</p>
                            </div>
                            <div>
                                <p class="text-lg font-bold">{{ $formatAngka($totalSuara) }}</p>
                                <p class="text-xs text-gray-500 mt-1">Total Vote</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        {{-- PEMENANG & HASIL AKHIR --}}
        <section class="mt-8">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-2">
                <div>
                    <h2 class="font-display text-lg md:text-xl font-bold text-[#15233d]">Pemenang &amp; Hasil Akhir</h2>
                    <p class="text-gray-500 text-xs md:text-sm mt-1">
                        Tiga besar dengan perolehan suara tertinggi, disusul peringkat 4–6.
                    </p>
                </div>
                <p class="text-xs text-gray-500 whitespace-nowrap">
                    Total {{ number_format($totalSuara, 0, ',', '.') }} suara dari {{ $jumlahKandidat }} kandidat
                </p>
            </div>

            @if (count($pemenangArr) > 0)
                {{-- KARTU TIGA BESAR --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 mt-4 items-end">
                    @foreach ($pemenangArr as $p)
                        <div class="bg-white rounded-lg overflow-hidden shadow-sm
                            {{ $p['juara'] == 1 ? 'border-2 border-[#c99a32] shadow-md sm:order-2' : 'border border-gray-100 ' . ($p['juara'] == 2 ? 'sm:order-1' : 'sm:order-3') }}">

                            <div class="relative overflow-hidden {{ $p['juara'] == 1 ? 'h-[230px] sm:h-[270px]' : 'h-[190px] sm:h-[210px]' }}">
                                @if ($p['foto'])
                                    <img src="{{ $p['foto'] }}" alt="{{ $p['nama'] }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-[#fbf6e9] flex items-center justify-center">
                                        <span class="font-display text-4xl font-bold text-[#c99a32]">{{ $inisial($p['nama']) }}</span>
                                    </div>
                                @endif

                                <span class="absolute top-2.5 left-2.5 bg-white px-2.5 py-1 rounded-md text-xs font-semibold text-[#15233d] shadow-sm">
                                    {{ $p['nomor'] }}
                                </span>

                                <span class="absolute top-2.5 right-2.5 bg-[#c99a32] text-white text-[10px] font-semibold px-2.5 py-1 rounded-md uppercase tracking-wide">
                                    Juara {{ $p['juara'] }}
                                </span>
                            </div>

                            <div class="p-3">
                                <div class="flex items-center justify-between gap-2">
                                    <h3 class="font-semibold text-sm text-[#15233d]">{{ $p['nama'] }}</h3>
                                    <span class="text-[10px] font-semibold text-[#c99a32] bg-[#fbf6e9] px-2 py-1 rounded">
                                        {{ $p['asal'] }}
                                    </span>
                                </div>

                                <p class="text-base font-bold text-[#15233d] mt-1.5">
                                    {{ $p['persen'] }}% <span class="text-xs font-normal text-gray-400">Vote</span>
                                </p>

                                <div class="w-full h-1.5 bg-gray-100 rounded-full mt-2">
                                    <div class="h-1.5 bg-[#c99a32] rounded-full" style="width: {{ $p['persen'] }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if (count($peringkatLainArr) > 0)
                    {{-- PERINGKAT 4-6 --}}
                    <div class="bg-white rounded-lg border border-gray-100 shadow-sm mt-4 divide-y divide-gray-100">
                        @foreach ($peringkatLainArr as $r)
                            <div class="flex items-center gap-3 px-4 py-3">
                                <span class="text-gray-300 font-semibold text-sm w-5">{{ $r['peringkat'] }}</span>

                                <div class="w-8 h-8 rounded-full bg-[#fbf6e9] text-[#c99a32] text-[11px] font-bold flex items-center justify-center shrink-0">
                                    {{ $r['inisial'] }}
                                </div>

                                <p class="text-sm font-semibold text-[#15233d] w-40 truncate">{{ $r['nama'] }}</p>

                                <span class="text-[10px] font-semibold text-[#c99a32] bg-[#fbf6e9] px-2 py-1 rounded whitespace-nowrap">
                                    {{ $r['asal'] }}
                                </span>

                                <div class="flex-1 h-1.5 bg-gray-100 rounded-full mx-2">
                                    <div class="h-1.5 bg-[#c99a32] rounded-full" style="width: {{ $r['persen'] }}%"></div>
                                </div>

                                <span class="text-sm font-semibold text-[#15233d] whitespace-nowrap">{{ $r['persen'] }}%</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            @else
                <div class="bg-white rounded-lg border border-dashed border-gray-200 p-10 text-center mt-4">
                    <p class="text-sm font-semibold text-[#15233d]">Belum ada peserta</p>
                    <p class="mt-1 text-xs text-gray-400">Event ini belum memiliki peserta yang diterima.</p>
                </div>
            @endif
        </section>

        {{-- DAFTAR KANDIDAT --}}
        <section class="mt-8">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-2 mb-4">
                <h2 class="font-display text-lg md:text-xl font-bold text-[#15233d]">Daftar Kandidat</h2>
            </div>

            @if (count($kandidat) > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3.5">
                    @foreach ($kandidat as $k)
                        <div class="bg-white rounded-lg overflow-hidden border border-gray-100 shadow-sm">
                            <div class="relative h-[180px] md:h-[200px] overflow-hidden bg-[#fbf6e9]">
                                @if ($k['foto'])
                                    <img src="{{ $k['foto'] }}" alt="{{ $k['nama'] }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="font-display text-4xl font-bold text-[#c99a32]">{{ $inisial($k['nama']) }}</span>
                                    </div>
                                @endif

                                <span class="absolute top-2.5 left-2.5 bg-white px-2 py-0.5 rounded text-[10px] font-semibold text-[#15233d] shadow-sm">
                                    {{ $k['nomor'] }}
                                </span>
                            </div>

                            <div class="p-3">
                                <h3 class="text-sm font-semibold text-[#15233d] truncate">{{ $k['nama'] }}</h3>
                                <p class="text-[10px] text-gray-400 mt-0.5">{{ $k['asal'] }}</p>

                                <p class="text-xs font-bold text-[#c99a32] mt-1.5">
                                    {{ $k['persen'] }}% Vote
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-lg border border-dashed border-gray-200 p-10 text-center">
                    <p class="text-sm font-semibold text-[#15233d]">Belum ada kandidat</p>
                </div>
            @endif
        </section>

    </main>

        @include('partials.footer')

</body>
</html>