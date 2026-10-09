<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Leaderboard Suara Voting | ALNAY.ID</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >
    @vite(['resources/css/app.css'])
</head>

<body class="bg-cream font-['Inter'] text-[13px] text-[#22201d]">

<div class="flex min-h-screen">

    @include('admin.partials.sidebar', [
        'active' => 'leaderboard',
        'event' => $event
    ])

    <main class="flex-1 min-w-0 p-6 ml-[235px]">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-1">

            <div class="flex items-center gap-3">

                <h1 class="text-2xl font-bold m-0">
                    Leaderboard Suara Voting
                </h1>

                <span class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-500 text-[11px] font-bold px-2.5 py-1 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                    Live
                </span>
            </div>
        </div>

        <p class="text-[#73716c] text-[12px] mb-5">
            {{ $event?->nama ?? 'Belum ada event' }}
            — peringkat diperbarui otomatis secara real-time
        </p>


        @if (!$event)

            {{-- Tidak ada event --}}
            <div class="bg-white border border-dashed border-[#e3ded6] rounded-xl p-8 text-center text-[#98958f]">
                Belum ada event.
            </div>

        @else

            {{-- ===================================================== --}}
            {{-- STAT CARDS --}}
            {{-- ===================================================== --}}

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">

                {{-- Total suara --}}
                <div class="bg-white rounded-xl border border-[#e3ded6] p-4">

                    <div class="text-3xl font-bold">
                        {{ number_format($totalSuara, 0, ',', '.') }}
                    </div>

                    <div class="text-[11px] text-[#73716c] mt-1">
                        Total Suara Masuk
                    </div>

                </div>


                {{-- Peserta --}}
                <div class="bg-white rounded-xl border border-[#e3ded6] p-4">

                    <div class="text-3xl font-bold">
                        {{ $pesertaCount }}
                    </div>

                    <div class="text-[11px] text-[#73716c] mt-1">
                        Peserta Berkompetisi
                    </div>

                </div>


                {{-- Peringkat 1 --}}
                <div class="bg-white rounded-xl border border-[#e3ded6] p-4">

                    <div class="text-center">
                        <h5>{{ $peringkat1?->nama ?? $peringkat1?->nama_lengkap ?? '-' }}</h5>
                        <p>{{ $peringkat1?->voting_masuk_count ?? 0 }} Suara</p>
                        <small>{{ $persenPeringkat1 }}% dari total suara</small>
                    </div>

                    <div class="text-[11px] text-[#73716c] mt-1">
                        Peringkat 1 Sementara
                    </div>

                    @if ($peringkat1)

                        <div class="text-[10px] text-[#98958f]">
                            {{ number_format($peringkat1['total_vote'], 0, ',', '.') }}
                            suara
                        </div>

                    @endif

                </div>


                {{-- Persentase --}}
                <div class="bg-white rounded-xl border border-[#e3ded6] p-4">

                    <div class="text-2xl font-bold font-mono">
                        {{ $peringkat1['persentase'] ?? 0 }}%
                    </div>

                    <div class="text-[11px] text-[#73716c] mt-1">
                        Persentase Peringkat 1
                    </div>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- TOP 3 --}}
            {{-- ===================================================== --}}

            @if ($pesertas->count() > 0)

                <div class="grid grid-cols-3 gap-4 mb-5">

                    {{-- Peringkat 2 --}}
                    @if ($pesertas[1] ?? null)

                        <div class="bg-white rounded-xl border border-[#e3ded6] p-5 text-center">

                            <div class="text-[10px] uppercase font-bold text-[#98958f] mb-3">
                                Peringkat 2
                            </div>

                            <div class="w-16 h-16 mx-auto rounded-full bg-[#edf3fb] text-[#42658c] flex items-center justify-center text-xl font-bold border border-[#d8e5f4]">
                                {{ $pesertas[1]['inisial'] }}
                            </div>

                            <h3 class="font-bold mt-3">
                                {{ $pesertas[1]['nama'] }}
                            </h3>

                            <div class="text-3xl font-bold mt-2">
                                {{ number_format($pesertas[1]['total_vote'], 0, ',', '.') }}
                            </div>

                            <div class="text-[11px] text-[#73716c]">
                                {{ $pesertas[1]['persentase'] }}% dari total suara
                            </div>

                        </div>

                    @else

                        <div class="bg-white rounded-xl border border-dashed border-[#e3ded6] p-5 text-center text-[#98958f]">
                            -
                        </div>

                    @endif


                    {{-- Peringkat 1 --}}
                    @if ($peringkat1)

                    <div class="flex justify-center">
                        <div class="w-full max-w-sm bg-[#fbf6e9] rounded-xl border border-[#d9bd79] p-5 text-center">

                            <div class="text-[10px] uppercase font-bold text-[#ba8320] mb-3">
                                Peringkat 1
                            </div>

                            <div class="w-20 h-20 mx-auto rounded-full bg-[#f0e6cf] text-[#8a6d1f] flex items-center justify-center text-2xl font-bold border-2 border-[#d9bd79]">
                                {{ $peringkat1['inisial'] }}
                            </div>

                            <h3 class="font-bold mt-3 text-lg">
                                {{ $peringkat1['nama'] }}
                            </h3>

                            <div class="text-4xl font-bold text-[#ba8320] mt-2">
                                {{ number_format($peringkat1['total_vote'], 0, ',', '.') }}
                            </div>

                            <div class="text-[11px] text-[#73716c]">
                                {{ $peringkat1['persentase'] }}% dari total suara
                            </div>

                        </div>
                    </div>

                    @else

                        <div class="bg-white rounded-xl border border-dashed border-[#e3ded6] p-5 text-center text-[#98958f]">
                            -
                        </div>

                    @endif


                    {{-- Peringkat 3 --}}
                    @if ($pesertas[2] ?? null)

                        <div class="bg-white rounded-xl border border-[#e3ded6] p-5 text-center">

                            <div class="text-[10px] uppercase font-bold text-[#98958f] mb-3">
                                Peringkat 3
                            </div>

                            <div class="w-16 h-16 mx-auto rounded-full bg-[#fff1e8] text-[#a96335] flex items-center justify-center text-xl font-bold border border-[#f1d5c4]">
                                {{ $pesertas[2]['inisial'] }}
                            </div>

                            <h3 class="font-bold mt-3">
                                {{ $pesertas[2]['nama'] }}
                            </h3>

                            <div class="text-3xl font-bold mt-2">
                                {{ number_format($pesertas[2]['total_vote'], 0, ',', '.') }}
                            </div>

                            <div class="text-[11px] text-[#73716c]">
                                {{ $pesertas[2]['persentase'] }}% dari total suara
                            </div>

                        </div>

                    @else

                        <div class="bg-white rounded-xl border border-dashed border-[#e3ded6] p-5 text-center text-[#98958f]">
                            -
                        </div>

                    @endif

                </div>

            @endif


            {{-- ===================================================== --}}
            {{-- PERINGKAT LENGKAP --}}
            {{-- ===================================================== --}}

            <div class="bg-white rounded-xl border border-[#e3ded6] overflow-hidden">

                <div class="p-4 border-b border-[#e3ded6] flex items-center justify-between">

                    <div>

                        <h3 class="font-bold">
                            Peringkat Lengkap
                        </h3>

                        <p class="text-[11px] text-[#98958f] mt-0.5">
                            Urutan peserta berdasarkan jumlah suara
                        </p>

                    </div>

                    <div class="text-[11px] text-[#98958f]">
                        {{ $pesertas->count() }} peserta
                    </div>

                </div>


                <div class="divide-y divide-[#f0ede6]">

                    @forelse ($pesertas as $index => $peserta)

                        <div class="p-4 hover:bg-[#faf8f4] transition flex items-center gap-4">

                            {{-- Rank --}}
                            <div class="w-10 text-center shrink-0">

                                @if ($index === 0)

                                    <span class="text-lg font-bold text-[#ba8320]">
                                        #1
                                    </span>

                                @elseif ($index === 1)

                                    <span class="text-lg font-bold text-[#42658c]">
                                        #2
                                    </span>

                                @elseif ($index === 2)

                                    <span class="text-lg font-bold text-[#a96335]">
                                        #3
                                    </span>

                                @else

                                    <span class="text-sm font-bold text-[#73716c]">
                                        #{{ $index + 1 }}
                                    </span>

                                @endif

                            </div>


                            {{-- Avatar --}}
                            <div class="w-10 h-10 rounded-full {{ $peserta['warna'] }} flex items-center justify-center text-[11px] font-bold border shrink-0">
                                {{ $peserta['inisial'] }}
                            </div>


                            {{-- Nama --}}
                            <div class="flex-1 min-w-0">

                                <h4 class="font-bold truncate">
                                    {{ $peserta['nama'] }}
                                </h4>

                                <div class="text-[10px] text-[#98958f] mt-0.5">
                                    {{ $peserta['persentase'] }}% dari total suara
                                </div>

                            </div>


                            {{-- Progress --}}
                            <div class="w-40 hidden md:block">

                                <div class="h-1.5 bg-[#f0ede6] rounded-full overflow-hidden">

                                    <div
                                        class="h-full bg-[#ba8320] rounded-full transition-all duration-500"
                                        style="width: {{ min((float) $peserta['persentase'], 100) }}%"
                                    ></div>

                                </div>

                            </div>


                            {{-- Jumlah suara --}}
                            <div class="text-right w-28 shrink-0">

                                <div class="font-bold">
                                    {{ number_format($peserta['total_vote'], 0, ',', '.') }}
                                </div>

                                <div class="text-[10px] text-[#73716c]">
                                    suara
                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="p-6 text-center text-[#98958f]">
                            Belum ada suara masuk untuk event ini.
                        </div>

                    @endforelse

                </div>

            </div>

        @endif

    </main>

</div>

</body>
</html>