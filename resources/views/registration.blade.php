<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran — {{ $event->nama }} | ALNAY.ID</title>

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

    @php
        $isPreview = request()->routeIs('admin.preview-form');
    @endphp

    <main class="mx-auto max-w-3xl px-5 pb-10 pt-6 sm:pt-8">

        {{-- ================== TOMBOL KEMBALI ================== --}}
        <button type="button" onclick="{{ $isPreview ? 'closePreviewTab()' : 'goBack()' }}"
            class="inline-flex items-center gap-2 rounded-full border border-[#e5e0d3] bg-white px-4 py-2 text-xs font-semibold text-[#4B463F] shadow-sm transition hover:border-[#c99a32] hover:text-[#c99a32]">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 19l-7-7 7-7" />
            </svg>
            {{ $isPreview ? 'Kembali ke Form Builder' : 'Kembali' }}
        </button>

        {{-- ================== MODE PREVIEW (ADMIN) ================== --}}
        @if ($isPreview)
            <div class="mt-5 flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-4">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-amber-100">
                    <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v2m0 4h.01M5 19h14a2 2 0 001.84-2.75L13.74 4a2 2 0 00-3.5 0L3.16 16.25A2 2 0 005 19z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[13px] font-bold text-amber-800">Mode Preview (Admin)</p>
                    <p class="mt-0.5 text-[12px] leading-relaxed text-amber-700">
                        Ini tampilan form yang akan dilihat user. Tombol <strong>Kirim Pendaftaran
                            dinonaktifkan</strong> karena kamu login sebagai admin.
                    </p>
                </div>
            </div>
        @endif

        {{-- ================== HERO EVENT (dibuat lebih ringkas) ================== --}}
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
                    Formulir Pendaftaran
                </span>

                <h1 class="font-display mt-3 text-xl font-bold leading-tight sm:text-2xl">
                    {{ $event->nama }}
                </h1>

                <p class="mt-1.5 max-w-xl text-[13px] leading-relaxed text-gray-300">
                    Isi formulir di bawah untuk mendaftar event ini. Pastikan data yang kamu masukkan sudah benar.
                </p>

                <div class="mt-4 flex flex-wrap gap-2">
                    @if ($event->batas_pendaftaran_tanggal)
                        <span
                            class="inline-flex items-center gap-1.5 rounded-full border border-white/15 bg-white/5 px-3 py-1 text-[11px] font-medium text-gray-200">
                            <svg class="h-3.5 w-3.5 text-[#e0b254]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Batas daftar {{ $event->batas_pendaftaran_tanggal->translatedFormat('d M Y') }}
                        </span>
                    @endif

                    <span
                        class="inline-flex items-center gap-1.5 rounded-full border border-white/15 bg-white/5 px-3 py-1 text-[11px] font-medium text-gray-200">
                        <svg class="h-3.5 w-3.5 text-[#e0b254]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Biaya
                        {{ ($event->biaya_pendaftaran ?? 0) > 0 ? 'Rp ' . number_format($event->biaya_pendaftaran, 0, ',', '.') : 'Gratis' }}
                    </span>
                </div>
            </div>
        </section>

        {{-- ================== MODE SUKSES ================== --}}
        @if (session('success'))
            <div class="mt-6 rounded-2xl border border-[#ece5d7] bg-white p-8 text-center shadow-sm sm:p-10">
                <div class="relative mx-auto mb-5 flex h-20 w-20 items-center justify-center">
                    <span class="absolute inset-0 rounded-full bg-emerald-100"></span>
                    <span
                        class="relative flex h-14 w-14 items-center justify-center rounded-full bg-emerald-500 text-white shadow-lg shadow-emerald-500/30">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                </div>
                <h2 class="font-display text-xl font-bold text-[#0a1526] sm:text-2xl">Pendaftaran Berhasil Dikirim!</h2>
                <p class="mx-auto mt-2 mb-6 max-w-md text-sm leading-relaxed text-[#6b6b6b]">
                    {{ session('success') }}
                </p>
                <a href="{{ route('profil-user') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#c69220] to-[#b58219] px-6 py-3 text-sm font-semibold text-white shadow-md transition hover:from-[#b58219] hover:to-[#a06f1a]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Lihat Status Pendaftaran
                </a>
            </div>

        {{-- ================== MODE FORM ================== --}}
        @else
            @if (session('error'))
                <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-red-800">
                    <p class="mb-2 text-sm font-bold">Ada kesalahan pada form:</p>
                    <ul class="list-inside list-disc space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

@php
    $sekarang = now();
    $bukaDaftar = $event->pembukaan_pendaftaran_tanggal;
    $batasDaftar = $event->batas_pendaftaran_tanggal;

    $pendaftaranDitutup = false;
    $alasanTutup = null;

    // NOTE: guard harus 'pelanggan', sama seperti di PendaftaranController@storePublic
    $currentUser = \Illuminate\Support\Facades\Auth::guard('pelanggan')->user();

    $sudahTerdaftar = false;

    if (!$isPreview && $currentUser) {
        $sudahTerdaftar = \App\Models\Peserta::where('event_id', $event->id)
        ->where('user_id', $currentUser->id)
        ->whereNotIn('status', ['gugur', 'ditolak'])
        ->exists();
    }

        if ($isPreview) {
        if (!($event->pendaftaran_aktif ?? true)) {
            $pendaftaranDitutup = true;
            $alasanTutup = 'Pendaftaran peserta tidak diaktifkan untuk event ini.';
        } elseif (!$event->pendaftaran_dibuka) {
            $pendaftaranDitutup = true;
            $alasanTutup = 'Pendaftaran untuk event ini sedang ditutup (Toggle Pendaftaran Nonaktif).';
        }
    } else {
        if ($sudahTerdaftar) {
            $pendaftaranDitutup = true;
            $alasanTutup = 'Kamu sudah terdaftar dengan akun ini pada event yang sama. Satu akun hanya dapat mendaftar sekali.';
        } elseif (in_array($event->status, ['sedang_berlangsung', 'telah_berakhir'])) {
            $pendaftaranDitutup = true;
            $alasanTutup = 'Event ini sedang berlangsung / telah berakhir — pendaftaran peserta baru sudah ditutup.';
        } elseif (!($event->pendaftaran_aktif ?? true)) {
            $pendaftaranDitutup = true;
            $alasanTutup = 'Pendaftaran peserta tidak diaktifkan untuk event ini.';
        } elseif (!$event->pendaftaran_dibuka) {
            $pendaftaranDitutup = true;
            $alasanTutup = 'Pendaftaran untuk event ini sedang ditutup oleh admin.';
        } elseif ($bukaDaftar && $sekarang->lt($bukaDaftar)) {
            $pendaftaranDitutup = true;
            $alasanTutup = 'Pendaftaran untuk event ini belum dibuka. Buka pada ' . $bukaDaftar->translatedFormat('d F Y') . ' pukul ' . ($event->pembukaan_pendaftaran_waktu ?? '00:00') . ' WIB.';
        } elseif ($batasDaftar && $sekarang->gt($batasDaftar)) {
            $pendaftaranDitutup = true;
            $alasanTutup = 'Pendaftaran untuk event ini sudah ditutup pada ' . $batasDaftar->translatedFormat('d F Y') . '.';
        }
    }
@endphp
            @if ($pendaftaranDitutup)
                {{-- ================== PENDAFTARAN DITUTUP ================== --}}
                <div class="mt-6 rounded-2xl border border-[#ece5d7] bg-white p-8 text-center shadow-sm sm:p-10">
                    <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-[#f4ede0]">
                        <svg class="h-8 w-8 text-[#c99a32]" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <rect x="4" y="10" width="16" height="11" rx="2" />
                            <path d="M8 10V7a4 4 0 018 0v3" />
                        </svg>
                    </div>
                    <h2 class="font-display text-xl font-bold text-[#0a1526] sm:text-2xl">Pendaftaran Ditutup</h2>
                    <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-[#6b6b6b]">{{ $alasanTutup }}</p>

                    @unless ($isPreview)
                        <a href="{{ route('dashboard-user') }}"
                            class="mt-6 inline-flex items-center justify-center rounded-xl border border-[#e5e0d3] bg-white px-5 py-2.5 text-xs font-semibold text-[#4B463F] transition hover:border-[#c99a32] hover:text-[#c99a32]">
                            Kembali ke Dashboard
                        </a>
                    @endunless
                </div>
            @else
                {{-- ================== FORM PENDAFTARAN ================== --}}
                <form method="POST" action="{{ route('public.registration.store', $event) }}"
                    enctype="multipart/form-data" id="formPendaftaran" class="mt-5">
                    @csrf

                    @php
                        $pertanyaans = $questions ?? $event->pertanyaans()->orderBy('urutan')->get();

                        // Jumlah pertanyaan yang benar-benar diisi (judul & sub judul tidak dihitung)
                        $totalPertanyaan = collect($pertanyaans)
                            ->filter(
                                fn($item) => !in_array(strtolower(trim($item->tipe_input ?? 'text')), ['heading', 'subheading'], true),
                            )
                            ->count();
                    @endphp

                    @if ($pertanyaans->isEmpty())
                        <div class="rounded-2xl border border-dashed border-[#ddd8cc] bg-white p-10 text-center">
                            <p class="text-sm font-semibold text-[#15233d]">Belum ada pertanyaan untuk event ini.</p>
                            <p class="mt-1 text-xs text-gray-400">Formulir akan muncul di sini begitu admin
                                menyusunnya.</p>
                        </div>
                    @else
                        {{-- SATU KARTU UNTUK SEMUA FIELD.
                             Field pendek (text/email/number/tel/select/dll) = 2 kolom berdampingan di layar >= sm.
                             Field panjang (textarea/radio/checkbox/file) & judul = selebar penuh. --}}
                        <div class="rounded-2xl border border-[#ece5d7] bg-white p-5 shadow-sm sm:p-6">
                            <div class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">

                                @foreach ($pertanyaans as $q)
                                    @php
                                        $tipe = strtolower(trim($q->tipe_input ?? 'text'));
                                        $config = is_string($q->konfigurasi)
                                            ? json_decode($q->konfigurasi, true) ?? []
                                            : $q->konfigurasi ?? [];
                                        $options = is_string($q->pilihan_jawaban)
                                            ? json_decode($q->pilihan_jawaban, true) ?? []
                                            : $q->pilihan_jawaban ?? [];
                                        $help = $config['help'] ?? '';
                                        $placeholder = $config['placeholder'] ?? '';
                                        $isRequired = $q->wajib ?? false;
                                    @endphp

                                    {{-- ========== HEADING ========== --}}
                                    @if ($tipe === 'heading')
                                        <div class="sm:col-span-2 {{ $loop->first ? '' : 'mt-3 border-t border-[#f0ece2] pt-5' }}">
                                            <h2 class="font-display text-lg font-bold text-[#0a1526]">{{ $q->label }}</h2>
                                            @if ($help)
                                                <p class="mt-0.5 text-xs text-[#8a8178]">{{ $help }}</p>
                                            @endif
                                        </div>
                                        @continue
                                    @endif

                                    {{-- ========== SUBHEADING ========== --}}
                                    @if ($tipe === 'subheading')
                                        <div class="-mt-2 sm:col-span-2">
                                            <p class="text-[13px] leading-relaxed text-[#6b6b6b]">{{ $q->label }}</p>
                                            @if ($help)
                                                <p class="mt-0.5 text-xs text-[#9a968a]">{{ $help }}</p>
                                            @endif
                                        </div>
                                        @continue
                                    @endif

                                    @php
                                        // Paksa field "nama" jadi text
                                        $labelLower = strtolower(trim($q->label));
                                        $namaKeywords = ['nama', 'nama lengkap', 'nama pendaftaran', 'nama tampilan', 'name'];
                                        $isNamaField = in_array($labelLower, $namaKeywords, true);

                                        $allowedTypes = ['text', 'email', 'number', 'tel', 'url', 'date', 'time'];
                                        $inputType = $isNamaField ? 'text' : (in_array($tipe, $allowedTypes, true) ? $tipe : 'text');

                                        $isSingleInput = $isNamaField || in_array($tipe, $allowedTypes, true);

                                        // Field panjang -> selebar penuh. Sisanya setengah lebar.
                                        $isWide = in_array($tipe, ['textarea', 'radio', 'checkbox', 'file'], true);

                                        $hasError = $errors->has("answers.{$q->id}");
                                        $borderClass = $hasError ? 'border-red-300' : 'border-[#e3ded6]';

                                        // Input ramping: py-2, rounded-lg
                                        $inputClass =
                                            'w-full rounded-lg border bg-[#fdfcf9] px-3 py-2 text-sm text-[#0a1526] placeholder:text-gray-400 transition focus:border-[#c99a32] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c99a32]/20 ' .
                                            $borderClass;
                                        $optionClass =
                                            'block rounded-lg border bg-[#fdfcf9] py-2 pl-10 pr-3 text-sm text-[#4B463F] transition hover:border-[#c99a32]/60 peer-checked:border-[#c99a32] peer-checked:bg-[#fdf8ec] peer-checked:font-semibold peer-checked:text-[#0a1526] peer-focus-visible:ring-2 peer-focus-visible:ring-[#c99a32]/20 ' .
                                            $borderClass;
                                    @endphp

                                    <div data-field class="min-w-0 {{ $isWide ? 'sm:col-span-2' : '' }}">

                                        <label for="q-{{ $q->id }}"
                                            class="mb-1 block text-[13px] font-semibold leading-snug text-[#0a1526]">
                                            {{ $q->label }}
                                            @if ($isRequired)
                                                <span class="text-red-500">*</span>
                                            @endif
                                        </label>

                                        @if ($help)
                                            <p class="mb-1.5 text-[11px] leading-relaxed text-[#8a8178]">{{ $help }}</p>
                                        @endif

                                        {{-- ========== SINGLE INPUT ========== --}}
                                        @if ($isSingleInput)
                                            <input type="{{ $inputType }}" id="q-{{ $q->id }}"
                                                name="answers[{{ $q->id }}]"
                                                value="{{ old("answers.{$q->id}") }}"
                                                placeholder="{{ $placeholder ?: '' }}"
                                                @if ($isRequired) required @endif
                                                class="{{ $inputClass }}">

                                        {{-- ========== TEXTAREA ========== --}}
                                        @elseif ($tipe === 'textarea')
                                            <textarea id="q-{{ $q->id }}" name="answers[{{ $q->id }}]" rows="3"
                                                placeholder="{{ $placeholder ?: '' }}"
                                                @if ($isRequired) required @endif
                                                class="{{ $inputClass }} resize-y">{{ old("answers.{$q->id}") }}</textarea>

                                        {{-- ========== SELECT / DROPDOWN ========== --}}
                                        @elseif ($tipe === 'select' || $tipe === 'dropdown')
                                            <div class="relative">
                                                <select id="q-{{ $q->id }}" name="answers[{{ $q->id }}]"
                                                    @if ($isRequired) required @endif
                                                    class="{{ $inputClass }} appearance-none pr-9">
                                                    <option value="">— Pilih —</option>
                                                    @foreach ((array) $options as $opt)
                                                        <option value="{{ $opt }}"
                                                            @selected(old("answers.{$q->id}") == $opt)>
                                                            {{ $opt }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-[#8a8178]"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                    stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </div>

                                        {{-- ========== RADIO ========== --}}
                                        @elseif ($tipe === 'radio')
                                            <div class="grid gap-2 sm:grid-cols-2">
                                                @foreach ((array) $options as $opt)
                                                    <label class="relative block cursor-pointer">
                                                        <input type="radio" name="answers[{{ $q->id }}]"
                                                            value="{{ $opt }}"
                                                            @checked(old("answers.{$q->id}") == $opt)
                                                            @if ($isRequired) required @endif
                                                            class="peer sr-only">
                                                        <span class="{{ $optionClass }}">{{ $opt }}</span>
                                                        <span
                                                            class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 rounded-full border-2 border-[#cfc8ba] bg-white transition peer-checked:border-[#c99a32] peer-checked:bg-[#c99a32] peer-checked:shadow-[inset_0_0_0_2px_white]"></span>
                                                    </label>
                                                @endforeach
                                            </div>

                                        {{-- ========== CHECKBOX ========== --}}
                                        @elseif ($tipe === 'checkbox')
                                            <div class="grid gap-2 sm:grid-cols-2">
                                                @foreach ((array) $options as $opt)
                                                    <label class="relative block cursor-pointer">
                                                        <input type="checkbox" name="answers[{{ $q->id }}][]"
                                                            value="{{ $opt }}"
                                                            @checked(in_array($opt, (array) old("answers.{$q->id}", [])))
                                                            class="peer sr-only">
                                                        <span class="{{ $optionClass }}">{{ $opt }}</span>
                                                        <span
                                                            class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 rounded border-2 border-[#cfc8ba] bg-white transition peer-checked:border-[#c99a32] peer-checked:bg-[#c99a32]"></span>
                                                        <svg class="pointer-events-none absolute left-[14px] top-1/2 h-2.5 w-2.5 -translate-y-1/2 text-white opacity-0 transition peer-checked:opacity-100"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                            stroke-width="3.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </label>
                                                @endforeach
                                            </div>

                                        {{-- ========== FILE (kompak: satu baris, bukan kotak tinggi) ========== --}}
                                        @elseif ($tipe === 'file')
                                            <label data-dropzone
                                                class="relative flex cursor-pointer items-center gap-3 rounded-lg border border-dashed bg-[#fdfcf9] px-3 py-2.5 transition hover:border-[#c99a32] hover:bg-[#fdf8ec] {{ $hasError ? 'border-red-300' : 'border-[#d9cfb8]' }}">
                                                <span
                                                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#f4ede0] text-[#c99a32]">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M16 8l-4-4m0 0L8 8m4-4v12" />
                                                    </svg>
                                                </span>
                                                <span class="min-w-0 flex-1 leading-tight">
                                                    <span data-file-text
                                                        class="block truncate text-[13px] font-semibold text-[#0a1526]">Klik untuk memilih file</span>
                                                    <span data-file-hint
                                                        class="block text-[11px] text-[#9a968a]">atau tarik &amp; lepas file ke sini</span>
                                                </span>

                                                <input type="file" id="q-{{ $q->id }}"
                                                    name="answers[{{ $q->id }}]"
                                                    @if ($isRequired) required @endif
                                                    class="absolute inset-0 h-full w-full cursor-pointer opacity-0">
                                            </label>

                                        {{-- ========== FALLBACK ========== --}}
                                        @else
                                            <input type="text" id="q-{{ $q->id }}"
                                                name="answers[{{ $q->id }}]"
                                                value="{{ old("answers.{$q->id}") }}"
                                                placeholder="{{ $placeholder ?: '' }}"
                                                @if ($isRequired) required @endif
                                                class="{{ $inputClass }}">
                                        @endif

                                        @error("answers.{$q->id}")
                                            <p class="mt-1.5 flex items-center gap-1.5 text-xs font-medium text-red-500">
                                                <svg class="h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    @endif

                    {{-- ========== BAR SUBMIT (menempel di bawah layar) ========== --}}
                    <div class="sticky bottom-4 z-30 mt-6">
                        <div
                            class="flex items-center justify-between gap-4 rounded-2xl border border-[#ece5d7] bg-white/95 px-4 py-2.5 shadow-xl backdrop-blur sm:px-5">

                            @if ($totalPertanyaan > 0)
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between text-[11px] font-semibold text-[#8a8178]">
                                        <span>Kelengkapan formulir</span>
                                        <span><span id="progressCount">0</span> / {{ $totalPertanyaan }}</span>
                                    </div>
                                    <div class="mt-1.5 h-1.5 w-full overflow-hidden rounded-full bg-[#f0ece2]">
                                        <div id="progressBar"
                                            class="h-full w-0 rounded-full bg-gradient-to-r from-[#c99a32] to-[#e0b254] transition-all duration-300">
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <button type="submit" @if ($isPreview) disabled @endif
                                class="ml-auto inline-flex shrink-0 items-center gap-2 whitespace-nowrap rounded-xl bg-gradient-to-r from-[#c69220] to-[#b58219] px-5 py-2.5 text-sm font-semibold text-white shadow-md transition hover:from-[#b58219] hover:to-[#a06f1a] disabled:cursor-not-allowed disabled:opacity-50 sm:px-6">
                                Kirim Pendaftaran
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </form>
            @endif
        @endif

    </main>

    {{-- ========== SCRIPT FORM: PROGRES & NAMA FILE ========== --}}
    <script>
        (function() {
            var form = document.getElementById('formPendaftaran');
            if (!form) return;

            var cards = form.querySelectorAll('[data-field]');
            var bar = document.getElementById('progressBar');
            var count = document.getElementById('progressCount');

            function isFilled(card) {
                var inputs = card.querySelectorAll('input, select, textarea');
                for (var i = 0; i < inputs.length; i++) {
                    var el = inputs[i];
                    if (el.type === 'radio' || el.type === 'checkbox') {
                        if (el.checked) return true;
                    } else if (el.type === 'file') {
                        if (el.files && el.files.length) return true;
                    } else if (el.value && el.value.trim() !== '') {
                        return true;
                    }
                }
                return false;
            }

            function updateProgress() {
                var filled = 0;
                cards.forEach(function(card) {
                    if (isFilled(card)) filled++;
                });
                if (count) count.textContent = filled;
                if (bar) bar.style.width = (cards.length ? (filled / cards.length) * 100 : 0) + '%';
            }

            function formatSize(bytes) {
                if (bytes >= 1048576) return (bytes / 1048576).toFixed(1) + ' MB';
                return Math.max(1, Math.round(bytes / 1024)) + ' KB';
            }

            form.querySelectorAll('[data-dropzone] input[type="file"]').forEach(function(input) {
                input.addEventListener('change', function() {
                    var zone = input.closest('[data-dropzone]');
                    var text = zone.querySelector('[data-file-text]');
                    var hint = zone.querySelector('[data-file-hint]');

                    if (input.files && input.files.length) {
                        var file = input.files[0];
                        text.textContent = file.name;
                        hint.textContent = formatSize(file.size) + ' · klik untuk mengganti';
                        zone.classList.add('border-[#c99a32]', 'bg-[#fdf8ec]');
                    } else {
                        text.textContent = 'Klik untuk memilih file';
                        hint.textContent = 'atau tarik & lepas file ke sini';
                        zone.classList.remove('border-[#c99a32]', 'bg-[#fdf8ec]');
                    }
                });
            });

            form.addEventListener('input', updateProgress);
            form.addEventListener('change', updateProgress);
            updateProgress();
        })();
    </script>

    {{-- ========== SCRIPT PREVIEW ADMIN ========== --}}
    @if ($isPreview)
        <script>
            function closePreviewTab() {
                window.close();

                setTimeout(function() {
                    if (!window.closed) {
                        window.location.href = '{{ route('admin.form-builder', $event->id) }}';
                    }
                }, 300);
            }
        </script>
    @endif

    {{-- ========== SCRIPT GO BACK (USER) ========== --}}
    @unless ($isPreview)
        <script>
            function goBack() {
                if (window.history.length > 1 && document.referrer) {
                    window.history.back();
                } else {
                    window.location.href = '{{ route('dashboard-user') }}';
                }
            }
        </script>
    @endunless

</body>

</html>