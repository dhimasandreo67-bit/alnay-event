<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pendaftaran — {{ $event->nama }} | ALNAY.ID</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
    </style>
</head>

<body class="min-h-screen bg-[#faf9f6] text-[#15233d] antialiased">

    @include('partials.header')

    <main class="mx-auto max-w-3xl px-5 pb-10 pt-6 sm:pt-8">

        {{-- ================== TOMBOL KEMBALI ================== --}}
        <a href="{{ route('profil-user') }}"
            class="inline-flex items-center gap-2 rounded-full border border-[#e5e0d3] bg-white px-4 py-2 text-xs font-semibold text-[#4B463F] shadow-sm transition hover:border-[#c99a32] hover:text-[#c99a32]">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 19l-7-7 7-7" />
            </svg>
            Kembali ke Profil
        </a>

        {{-- ================== HERO EVENT ================== --}}
        <section
            class="relative mt-5 overflow-hidden rounded-2xl bg-gradient-to-br from-[#0a1526] via-[#11223f] to-[#0a1526] p-5 text-white shadow-lg sm:p-6">
            <div class="pointer-events-none absolute -right-12 -top-12 h-44 w-44 rounded-full bg-[#c99a32]/20 blur-3xl">
            </div>
            <div class="pointer-events-none absolute -bottom-16 -left-10 h-40 w-40 rounded-full bg-[#c99a32]/10 blur-3xl">
            </div>

            <div class="relative">
                <span
                    class="inline-flex items-center gap-2 rounded-full border border-[#c99a32]/40 bg-[#c99a32]/10 px-3 py-1 text-[10px] font-bold uppercase tracking-[.2em] text-[#e0b254]">
                    <span class="h-1.5 w-1.5 rounded-full bg-[#c99a32]"></span>
                    Data Pendaftaran
                </span>

                <h1 class="font-display mt-3 text-xl font-bold leading-tight sm:text-2xl">
                    {{ $event->nama }}
                </h1>

                <div class="mt-4 flex flex-wrap gap-2">
                    <span
                        class="inline-flex items-center gap-1.5 rounded-full border border-white/15 bg-white/5 px-3 py-1 text-[11px] font-medium text-gray-200">
                        <svg class="h-3.5 w-3.5 text-[#e0b254]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Dikirim
                        {{ $peserta->created_at ? \Carbon\Carbon::parse($peserta->created_at)->translatedFormat('d M Y, H:i') . ' WIB' : '-' }}
                    </span>
                </div>
            </div>
        </section>

        {{-- ================== BANNER MODE LIHAT SAJA ================== --}}
        <div class="mt-4 flex items-start gap-2.5 rounded-xl border border-[#c99a32]/40 bg-[#fdf8ec] px-4 py-3">
            <svg class="mt-0.5 h-4 w-4 shrink-0 text-[#b8862a]" fill="none" stroke="currentColor"
                viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <p class="text-[12px] leading-relaxed text-[#6b5220]">
                <strong>Mode Lihat Saja.</strong>
                Ini adalah data yang kamu kirim saat mendaftar dan tidak dapat diubah dari halaman ini.
            </p>
        </div>

        {{-- ================== DATA JAWABAN (satu kartu, 2 kolom) ================== --}}
        <div class="mt-4 rounded-2xl border border-[#ece5d7] bg-white p-5 shadow-sm sm:p-6">
            <div class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">

                @forelse ($jawabans as $j)
                    @php
                        $tipe = strtolower(trim($j->pertanyaan->tipe_input ?? 'text'));
                        $label = $j->pertanyaan->label ?? '-';
                        $nilai = $j->jawaban;

                        // Judul & sub judul (kalau ikut tersimpan) — tampil sebagai pemisah
                        $isHeading = $tipe === 'heading';
                        $isSub = $tipe === 'subheading';

                        // Field panjang -> selebar penuh. Sisanya setengah lebar.
                        $isWide = in_array($tipe, ['textarea', 'radio', 'checkbox', 'file'], true);

                        // Nilai selalu dijadikan array agar mudah ditampilkan
                        $daftarNilai = collect(is_array($nilai) ? $nilai : [$nilai])
                            ->filter(fn($v) => $v !== null && $v !== '')
                            ->values();

                        $boxClass =
                            'w-full rounded-lg border border-[#e3ded6] bg-[#fdfcf9] px-3 py-2 text-sm text-[#0a1526] break-words';
                    @endphp

                    @if ($isHeading)
                        <div class="sm:col-span-2 {{ $loop->first ? '' : 'mt-3 border-t border-[#f0ece2] pt-5' }}">
                            <h2 class="font-display text-lg font-bold text-[#0a1526]">{{ $label }}</h2>
                        </div>
                        @continue
                    @endif

                    @if ($isSub)
                        <div class="-mt-2 sm:col-span-2">
                            <p class="text-[13px] leading-relaxed text-[#6b6b6b]">{{ $label }}</p>
                        </div>
                        @continue
                    @endif

                    <div class="min-w-0 {{ $isWide ? 'sm:col-span-2' : '' }}">
                        <p class="mb-1 text-[13px] font-semibold leading-snug text-[#0a1526]">{{ $label }}</p>

                        {{-- ========== FILE ========== --}}
                        @if ($tipe === 'file')
                            @if ($daftarNilai->isEmpty())
                                <div class="{{ $boxClass }} text-gray-400">-</div>
                            @else
                                <div class="space-y-2">
                                    @foreach ($daftarNilai as $p)
                                        <a href="{{ asset('storage/' . $p) }}" target="_blank" rel="noopener"
                                            class="flex items-center gap-3 rounded-lg border border-dashed border-[#d9cfb8] bg-[#fdfcf9] px-3 py-2.5 transition hover:border-[#c99a32] hover:bg-[#fdf8ec]">
                                            <span
                                                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#f4ede0] text-[#c99a32]">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                            </span>
                                            <span class="min-w-0 flex-1 leading-tight">
                                                <span
                                                    class="block truncate text-[13px] font-semibold text-[#0a1526]">{{ basename($p) }}</span>
                                                <span class="block text-[11px] text-[#9a968a]">Klik untuk melihat /
                                                    mengunduh</span>
                                            </span>
                                            <svg class="h-4 w-4 shrink-0 text-[#c99a32]" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    @endforeach
                                </div>
                            @endif

                        {{-- ========== CHECKBOX: tampil sebagai chip ========== --}}
                        @elseif ($tipe === 'checkbox')
                            @if ($daftarNilai->isEmpty())
                                <div class="{{ $boxClass }} text-gray-400">-</div>
                            @else
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($daftarNilai as $v)
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-lg border border-[#c99a32] bg-[#fdf8ec] px-3 py-1.5 text-sm font-semibold text-[#0a1526]">
                                            <svg class="h-3 w-3 text-[#c99a32]" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" stroke-width="3.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                            {{ $v }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                        {{-- ========== TEXTAREA / TEKS PANJANG ========== --}}
                        @elseif ($tipe === 'textarea')
                            <div class="{{ $boxClass }} whitespace-pre-line">{{ $daftarNilai->implode(', ') ?: '-' }}</div>

                        {{-- ========== TEKS BIASA / RADIO / SELECT / ANGKA / DLL ========== --}}
                        @else
                            <div class="{{ $boxClass }}">{{ $daftarNilai->implode(', ') ?: '-' }}</div>
                        @endif
                    </div>
                @empty
                    <p class="py-6 text-center text-sm text-[#8a8577] sm:col-span-2">
                        Tidak ada data jawaban tersimpan untuk pendaftaran ini.
                    </p>
                @endforelse

                {{-- ========== EMAIL AKUN (pelengkap) ========== --}}
                <div class="min-w-0">
                    <p class="mb-1 text-[13px] font-semibold leading-snug text-[#0a1526]">Email Akun</p>
                    <div
                        class="w-full break-words rounded-lg border border-[#e3ded6] bg-[#fdfcf9] px-3 py-2 text-sm text-[#0a1526]">
                        {{ $peserta->user->email ?? '-' }}
                    </div>
                </div>

            </div>
        </div>

        <p class="mt-5 text-center text-xs text-[#8a8577]">
            Butuh perubahan data? Hubungi panitia event melalui kontak resmi yang terdapat di halaman event.
        </p>
    </main>

    @include('partials.footer')
</body>

</html>
