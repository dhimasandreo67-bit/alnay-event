<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran — {{ $event->nama }} | ALNAY.ID</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#FFF8F1] text-[#1D1B17] min-h-screen">

    @php
        $isPreview = request()->routeIs('admin.preview-form');
    @endphp

    @include('partials.header')

    <main class="max-w-3xl mx-auto px-6 py-8">

        {{-- ================== TOMBOL KEMBALI ================== --}}
        <div class="mb-6">
            @php
                $backLabel = $isPreview ? 'Kembali ke Form Builder' : 'Kembali';
            @endphp

            @if ($isPreview)
                <button type="button" onclick="closePreviewTab()" aria-label="{{ $backLabel }}"
                    class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white border border-[#CDC5BC]/40 hover:bg-[#FBF9F4] hover:border-[#C28E27] transition text-[#4B463F] hover:text-[#C28E27]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H5M12 19l-7-7 7-7" />
                    </svg>
                </button>
            @else
                <button type="button" onclick="goBack()" aria-label="{{ $backLabel }}"
                    class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white border border-[#CDC5BC]/40 hover:bg-[#FBF9F4] hover:border-[#C28E27] transition text-[#4B463F] hover:text-[#C28E27]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H5M12 19l-7-7 7-7" />
                    </svg>
                </button>
            @endif
        </div>

        {{-- ================== MODE PREVIEW (ADMIN) ================== --}}
        @if ($isPreview)
            <div class="mb-6 bg-amber-50 border-2 border-amber-300 rounded-xl p-4 flex items-start gap-3">
                <div class="w-9 h-9 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v2m0 4h.01M5 19h14a2 2 0 001.84-2.75L13.74 4a2 2 0 00-3.5 0L3.16 16.25A2 2 0 005 19z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[13px] font-bold text-amber-800">Mode Preview (Admin)</p>
                    <p class="text-[12px] text-amber-700 mt-0.5">
                        Ini tampilan form yang akan dilihat user. Tombol <strong>Kirim Pendaftaran
                            dinonaktifkan</strong> karena kamu login sebagai admin.
                    </p>
                </div>
            </div>
        @endif

        {{-- ================== JUDUL EVENT ================== --}}
        <div class="bg-white border border-[#CDC5BC]/30 rounded-xl p-6 mb-6">
            <h1 class="text-2xl font-bold text-black mb-1">{{ $event->nama }}</h1>
            <p class="text-sm text-[#4B463F]">Isi formulir di bawah untuk mendaftar event ini.</p>
        </div>

        {{-- ================== MODE SUKSES ================== --}}
        @if (session('success'))
            <div class="bg-white border-2 border-green-200 rounded-2xl p-8 text-center mb-6 shadow-sm">
                <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-9 h-9 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-black mb-2">Pendaftaran Berhasil Dikirim!</h2>
                <p class="text-sm text-[#4B463F] mb-6 max-w-md mx-auto">
                    {{ session('success') }}
                </p>
                <a href="{{ route('profil-user') }}"
                    class="inline-flex items-center justify-center gap-2 bg-[#C28E27] hover:bg-[#a97b1f] text-white font-semibold px-6 py-3 rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Lihat Status Pendaftaran
                </a>
            </div>

        {{-- ================== MODE FORM ================== --}}
        @else
            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 mb-6 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 mb-6">
                    <p class="font-bold mb-2">Ada kesalahan pada form:</p>
                    <ul class="list-disc list-inside text-sm space-y-1">
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

                if (in_array($event->status, ['sedang_berlangsung', 'telah_berakhir'])) {
                    $pendaftaranDitutup = true;
                    $alasanTutup =
                        'Event ini sedang berlangsung / telah berakhir — pendaftaran peserta baru sudah ditutup.';
                } elseif (!$event->pendaftaran_dibuka) {
                    $pendaftaranDitutup = true;
                    $alasanTutup = 'Pendaftaran untuk event ini sedang ditutup oleh admin.';
                } elseif ($bukaDaftar && $sekarang->lt($bukaDaftar)) {
                    $pendaftaranDitutup = true;
                    $alasanTutup =
                        'Pendaftaran untuk event ini belum dibuka. Buka pada ' .
                        $bukaDaftar->translatedFormat('d F Y') .
                        ' pukul ' .
                        ($event->pembukaan_pendaftaran_waktu ?? '00:00') .
                        ' WIB.';
                } elseif ($batasDaftar && $sekarang->gt($batasDaftar)) {
                    $pendaftaranDitutup = true;
                    $alasanTutup =
                        'Pendaftaran untuk event ini sudah ditutup pada ' .
                        $batasDaftar->translatedFormat('d F Y') .
                        '.';
                } else {
                    $pendaftaranDitutup = false;
                    $alasanTutup = null;
                }
            @endphp

            @if ($pendaftaranDitutup)
                {{-- ================== PENDAFTARAN DITUTUP ================== --}}
                <div class="bg-white border border-[#CDC5BC]/30 rounded-xl p-8 text-center">
                    <div class="w-16 h-16 rounded-full bg-[#FAF3E7] flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-[#7F5700]" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <rect x="4" y="10" width="16" height="11" rx="2" />
                            <path d="M8 10V7a4 4 0 018 0v3" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-black mb-2">Pendaftaran Ditutup</h2>
                    <p class="text-sm text-[#4B463F]">{{ $alasanTutup }}</p>
                </div>
            @else
                {{-- ================== FORM PENDAFTARAN ================== --}}
                <form method="POST" action="{{ route('public.registration.store', $event) }}"
                    enctype="multipart/form-data">
                    @csrf

                    @php
                        $pertanyaans = $questions ?? $event->pertanyaans()->orderBy('urutan')->get();
                    @endphp

                    @forelse ($pertanyaans as $q)
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
                            <div class="mt-6 mb-3">
                                <h2 class="font-display text-2xl font-bold text-[#15233d]">
                                    {{ $q->label }}
                                </h2>
                                @if ($help)
                                    <p class="mt-1 text-sm text-gray-500">{{ $help }}</p>
                                @endif
                            </div>
                            @continue
                        @endif

                        {{-- ========== SUBHEADING ========== --}}
                        @if ($tipe === 'subheading')
                            <div class="mt-5 mb-2">
                                <p class="text-sm text-gray-600">
                                    {{ $q->label }}
                                </p>
                                @if ($help)
                                    <p class="mt-1 text-xs text-gray-400">{{ $help }}</p>
                                @endif
                            </div>
                            @continue
                        @endif

                        <div class="mb-5 rounded-xl border border-gray-100 bg-white p-4">
                            <label for="q-{{ $q->id }}" class="mb-2 block text-sm font-semibold text-[#15233d]">
                                {{ $q->label }}
                                @if ($isRequired)
                                    <span class="text-red-500">*</span>
                                @endif
                            </label>

                            @if ($help)
                                <p class="mb-2 text-xs text-gray-500">{{ $help }}</p>
                            @endif

                            @php
                                // ============================================
                                // LOGIKA TYPE INPUT — WHITELIST + PAKSA NAMA TEXT
                                // ============================================

                                // 1. Deteksi field "nama" — paksa jadi text
                                $labelLower = strtolower(trim($q->label));
                                $namaKeywords = ['nama', 'nama lengkap', 'nama pendaftaran', 'nama tampilan', 'name'];
                                $isNamaField = in_array($labelLower, $namaKeywords, true);

                                // 2. Whitelist type HTML yang diizinkan
                                $allowedTypes = ['text', 'email', 'number', 'tel', 'url', 'date', 'time'];

                                // 3. Tentukan input type final
                                $inputType = $isNamaField
                                    ? 'text'
                                    : (in_array($tipe, $allowedTypes, true) ? $tipe : 'text');

                                // 4. Flag — apakah field ini input single (bukan textarea/select/file/dll)
                                $isSingleInput =
                                    in_array($tipe, ['text', 'email', 'number', 'tel', 'url', 'date', 'time'], true) ||
                                    $isNamaField;
                            @endphp

                            {{-- ========== SINGLE INPUT (text / email / number / tel / url / date / time) ========== --}}
                            @if ($isSingleInput)
                                <input type="{{ $inputType }}"
                                    id="q-{{ $q->id }}"
                                    name="answers[{{ $q->id }}]"
                                    value="{{ old("answers.{$q->id}") }}"
                                    placeholder="{{ $placeholder ?: '' }}"
                                    @if ($isRequired) required @endif
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-[#c99a32] focus:outline-none focus:ring-1 focus:ring-[#c99a32]">

                            {{-- ========== TEXTAREA ========== --}}
                            @elseif ($tipe === 'textarea')
                                <textarea id="q-{{ $q->id }}"
                                    name="answers[{{ $q->id }}]"
                                    rows="4"
                                    placeholder="{{ $placeholder ?: '' }}"
                                    @if ($isRequired) required @endif
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-[#c99a32] focus:outline-none focus:ring-1 focus:ring-[#c99a32]">{{ old("answers.{$q->id}") }}</textarea>

                            {{-- ========== SELECT / DROPDOWN ========== --}}
                            @elseif ($tipe === 'select' || $tipe === 'dropdown')
                                <select id="q-{{ $q->id }}"
                                    name="answers[{{ $q->id }}]"
                                    @if ($isRequired) required @endif
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-[#c99a32] focus:outline-none focus:ring-1 focus:ring-[#c99a32]">
                                    <option value="">— Pilih —</option>
                                    @foreach ((array) $options as $opt)
                                        <option value="{{ $opt }}"
                                            @selected(old("answers.{$q->id}") == $opt)>
                                            {{ $opt }}
                                        </option>
                                    @endforeach
                                </select>

                            {{-- ========== RADIO ========== --}}
                            @elseif ($tipe === 'radio')
                                <div class="space-y-2">
                                    @foreach ((array) $options as $opt)
                                        <label class="flex items-center gap-2 text-sm">
                                            <input type="radio"
                                                name="answers[{{ $q->id }}]"
                                                value="{{ $opt }}"
                                                @checked(old("answers.{$q->id}") == $opt)
                                                @if ($isRequired) required @endif
                                                class="text-[#c99a32] focus:ring-[#c99a32]">
                                            {{ $opt }}
                                        </label>
                                    @endforeach
                                </div>

                            {{-- ========== CHECKBOX ========== --}}
                            @elseif ($tipe === 'checkbox')
                                <div class="space-y-2">
                                    @foreach ((array) $options as $opt)
                                        <label class="flex items-center gap-2 text-sm">
                                            <input type="checkbox"
                                                name="answers[{{ $q->id }}][]"
                                                value="{{ $opt }}"
                                                @checked(in_array($opt, (array) old("answers.{$q->id}", [])))
                                                class="rounded text-[#c99a32] focus:ring-[#c99a32]">
                                            {{ $opt }}
                                        </label>
                                    @endforeach
                                </div>

                            {{-- ========== FILE ========== --}}
                            @elseif ($tipe === 'file')
                                <input type="file"
                                    id="q-{{ $q->id }}"
                                    name="answers[{{ $q->id }}]"
                                    @if ($isRequired) required @endif
                                    class="w-full rounded-lg border border-dashed border-gray-300 px-3 py-2.5 text-sm focus:border-[#c99a32] focus:outline-none">

                            {{-- ========== FALLBACK — apapun yang gak dikenal → text ========== --}}
                            @else
                                <input type="text"
                                    id="q-{{ $q->id }}"
                                    name="answers[{{ $q->id }}]"
                                    value="{{ old("answers.{$q->id}") }}"
                                    placeholder="{{ $placeholder ?: '' }}"
                                    @if ($isRequired) required @endif
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-[#c99a32] focus:outline-none focus:ring-1 focus:ring-[#c99a32]">
                            @endif

                            @error("answers.{$q->id}")
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    @empty
                        <div class="rounded-xl border border-dashed border-gray-200 bg-white p-8 text-center">
                            <p class="text-sm text-gray-500">
                                Belum ada pertanyaan untuk event ini.
                            </p>
                        </div>
                    @endforelse

                    {{-- ========== TOMBOL SUBMIT ========== --}}
                    <div class="mt-6 flex justify-end">
                        <button type="submit"
                            @if ($isPreview) disabled @endif
                            class="rounded-lg bg-gradient-to-r from-[#c69220] to-[#b58219] px-6 py-3 text-sm font-semibold text-white shadow-sm hover:from-[#b58219] hover:to-[#a06f1a] disabled:opacity-50 disabled:cursor-not-allowed">
                            Kirim Pendaftaran
                        </button>
                    </div>
                </form>
            @endif
        @endif

    </main>

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