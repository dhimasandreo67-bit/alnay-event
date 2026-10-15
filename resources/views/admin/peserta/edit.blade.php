<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Data Peserta | ALNAY.ID</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Tailwind CSS --}}
    @vite(['resources/css/app.css'])

    {{-- Custom Admin CSS --}}
    <link rel="stylesheet" href="{{ asset('css/admin-sidebar.css') }}">
    @include('admin.partials.pwa-head')

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 7px;
            height: 7px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Image modal */
        #imageModal {
            transition: opacity 0.2s ease;
        }

        #imageModal.hidden {
            pointer-events: none;
        }

        #previewImage {
            transition: transform 0.2s ease;
            transform-origin: center center;
            user-select: none;
            -webkit-user-drag: none;
        }
    </style>
</head>

<body class="bg-slate-100 text-slate-800 min-h-screen">

    @php
        // Karena status otomatis 'diterima', kita hardcode meta untuk tampilan
        $currentStatus = [
            'label' => 'Diterima',
            'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'dot' => 'bg-emerald-500',
        ];
    @endphp

    {{-- MAIN CONTENT --}}
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-10">

        {{-- BREADCRUMB --}}
        <div class="flex items-center gap-2 text-xs text-slate-400 mb-5">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition">Peserta</a>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <a href="{{ route('admin.peserta.show', $peserta->id) }}" class="hover:text-blue-600 transition">Detail Pendaftaran</a>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-slate-500">Edit Data</span>
        </div>

        {{-- HEADER CARD --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-soft overflow-hidden mb-6">
            <div class="h-1.5 bg-gradient-to-r from-emerald-600 via-emerald-500 to-emerald-500"></div>
            <div class="p-5 sm:p-6 lg:p-7">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                    <div class="flex items-start gap-4">
                        <div class="hidden sm:flex shrink-0 w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white items-center justify-center shadow-lg shadow-emerald-500/20">
                            <span class="text-lg font-bold">
                                {{ strtoupper(substr($peserta->user->nama ?? 'P', 0, 1)) }}
                            </span>
                        </div>
                        <div>
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <h1 class="text-xl sm:text-2xl font-bold text-slate-900">
                                    Edit Data Peserta
                                </h1>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full border text-[11px] font-semibold {{ $currentStatus['class'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $currentStatus['dot'] }}"></span>
                                    {{ $currentStatus['label'] }}
                                </span>
                            </div>
                            <p class="text-sm text-slate-500">
                                Ubah data pendaftaran & jawaban formulir peserta. Status dikelola otomatis.
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs text-slate-400">Peserta</p>
                            <p class="text-sm font-semibold text-slate-700">{{ $peserta->namaTampil() }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- FLASH MESSAGES --}}
        @if (session('success'))
            <div class="mb-6 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-emerald-700">
                <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 flex items-start gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3.5 text-rose-700">
                <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <p class="text-sm">{{ session('error') }}</p>
            </div>
        @endif

        {{-- FORM START --}}
        <form method="POST" action="{{ route('admin.peserta.update', $peserta->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- LEFT CONTENT (2/3) --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- INFORMASI PESERTA --}}
                    <section class="bg-white rounded-2xl border border-slate-200 shadow-card overflow-hidden">
                        <div class="px-5 sm:px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-slate-800">Informasi Peserta</h2>
                                <p class="text-xs text-slate-400">Data dasar akun peserta</p>
                            </div>
                        </div>

                        <div class="p-5 sm:p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                        Nama Pendaftaran <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nama" value="{{ old('nama', $peserta->namaTampil()) }}"
                                        class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                                    @error('nama') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                        Email <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" name="email" value="{{ old('email', $peserta->user?->email) }}"
                                        class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                                    @error('email') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- JAWABAN FORMULIR --}}
                    <section class="bg-white rounded-2xl border border-slate-200 shadow-card overflow-hidden">
                        <div class="px-5 sm:px-6 py-4 border-b border-slate-100 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-sm font-bold text-slate-800">Jawaban Formulir</h2>
                                    <p class="text-xs text-slate-400">Seluruh isian peserta saat mendaftar — silakan ubah seperlunya.</p>
                                </div>
                            </div>
                            <span class="hidden sm:inline-flex px-2.5 py-1 rounded-lg bg-slate-100 text-[11px] font-semibold text-slate-500">
                                {{ $peserta->jawabans->count() }} Jawaban
                            </span>
                        </div>

                        <div class="p-5 sm:p-6 space-y-3">
                            @php
                                $filteredJawabans = $peserta->jawabans->reject(function ($jawaban) {
                                    return in_array(strtolower($jawaban->pertanyaan?->tipe_input ?? ''), ['heading', 'subheading'], true);
                                });
                            @endphp

                            @if($filteredJawabans->count() > 0)
                                @foreach ($filteredJawabans as $jawaban)
                                    @php
                                        $pertanyaan = $jawaban->pertanyaan;
                                        $tipe = strtolower($pertanyaan?->tipe_input ?? 'text');

                                        $raw = $jawaban->jawaban;
                                        if (is_string($raw)) {
                                            $dec = json_decode($raw, true);
                                            $arr = is_array($dec) ? $dec : [$raw];
                                        } else {
                                            $arr = is_array($raw) ? $raw : [$raw];
                                        }
                                        $arr = array_map(fn($v) => is_scalar($v) ? (string) $v : '', $arr);
                                        $nilaiText = implode(', ', $arr);
                                        $oldKey = 'answers.' . $jawaban->id;

                                        $filePath  = $arr[0] ?? '';
                                        $cleanPath = parse_url($filePath, PHP_URL_PATH) ?? $filePath;
                                        $extension = strtolower(pathinfo($cleanPath, PATHINFO_EXTENSION));
                                        $isImage   = in_array($tipe, ['upload_gambar', 'upload gambar', 'image', 'gambar'], true)
                                            || in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true);

                                        $options = $pertanyaan?->pilihan_jawaban ?? [];
                                        if (is_string($options)) { $options = json_decode($options, true) ?? []; }
                                    @endphp

                                    <div class="group rounded-xl border border-slate-100 bg-white hover:border-slate-200 hover:shadow-sm transition overflow-hidden">
                                        <div class="p-4">
                                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-5">
                                                <div>
                                                    <p class="text-[11px] uppercase tracking-wide font-semibold text-slate-400 mb-1">Pertanyaan</p>
                                                    <p class="text-xs sm:text-sm font-medium text-slate-600">
                                                        {{ $pertanyaan?->label ?? 'Pertanyaan dihapus' }}
                                                    </p>
                                                </div>
                                                <div class="sm:col-span-2">
                                                    <p class="text-[11px] uppercase tracking-wide font-semibold text-slate-400 mb-2">Jawaban</p>

                                                    {{-- TEXT / EMAIL / NUMBER / DATE --}}
                                                    @if (in_array($tipe, ['text', 'teks', 'email', 'number', 'angka', 'date']))
                                                        <input type="{{ $tipe === 'angka' ? 'number' : ($tipe === 'teks' ? 'text' : $tipe) }}"
                                                            name="answers[{{ $jawaban->id }}]"
                                                            value="{{ old($oldKey, $nilaiText) }}"
                                                            class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">

                                                    {{-- TEXTAREA --}}
                                                    @elseif (in_array($tipe, ['textarea', 'teks_panjang']))
                                                        <textarea name="answers[{{ $jawaban->id }}]" rows="4"
                                                            class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">{{ old($oldKey, $nilaiText) }}</textarea>

                                                    {{-- SELECT / DROPDOWN --}}
                                                    @elseif (in_array($tipe, ['select', 'dropdown']))
                                                        <select name="answers[{{ $jawaban->id }}]"
                                                            class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition bg-white">
                                                            <option value="">— Pilih —</option>
                                                            @foreach ($options as $opt)
                                                                <option value="{{ $opt }}" @selected(old($oldKey, $nilaiText) == $opt)>{{ $opt }}</option>
                                                            @endforeach
                                                        </select>

                                                    {{-- RADIO --}}
                                                    @elseif ($tipe === 'radio')
                                                        <div class="space-y-2 pt-1">
                                                            @foreach ($options as $opt)
                                                                <label class="flex items-center gap-2 cursor-pointer text-sm">
                                                                    <input type="radio" name="answers[{{ $jawaban->id }}]" value="{{ $opt }}"
                                                                        @checked(old($oldKey, $nilaiText) == $opt)
                                                                        class="text-blue-600 focus:ring-blue-500">
                                                                    {{ $opt }}
                                                                </label>
                                                            @endforeach
                                                        </div>

                                                    {{-- CHECKBOX --}}
                                                    @elseif ($tipe === 'checkbox')
                                                        <div class="space-y-2 pt-1">
                                                            @foreach ($options as $opt)
                                                                <label class="flex items-center gap-2 cursor-pointer text-sm">
                                                                    <input type="checkbox" name="answers[{{ $jawaban->id }}][]" value="{{ $opt }}"
                                                                        @checked(in_array($opt, (array) old($oldKey, $arr)))
                                                                        class="rounded text-blue-600 focus:ring-blue-500">
                                                                    {{ $opt }}
                                                                </label>
                                                            @endforeach
                                                        </div>

                                                    {{-- FILE / GAMBAR --}}
                                                    @elseif (in_array($tipe, ['file', 'upload_gambar', 'upload gambar', 'image', 'gambar']))
                                                        @if ($filePath)
                                                            <div class="mb-3">
                                                                @if ($isImage && \Illuminate\Support\Facades\Storage::disk('public')->exists($filePath))
                                                                    @php
                                                                        $imageSrc = asset('storage/' . $filePath);
                                                                    @endphp
                                                                    <div class="relative group rounded-xl overflow-hidden border border-slate-200 bg-white cursor-pointer w-32 h-32"
                                                                        onclick="openImageModal('{{ $imageSrc }}')">
                                                                        <img src="{{ $imageSrc }}" alt="File lama" class="w-full h-full object-cover">
                                                                    </div>
                                                                @else
                                                                    <a href="{{ route('admin.jawaban.file', $jawaban) }}" target="_blank"
                                                                        class="inline-flex items-center gap-2 rounded-lg bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 hover:bg-blue-100 transition">
                                                                        Lihat / unduh berkas lama
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        @else
                                                            <p class="text-xs text-slate-400 mb-2">Belum ada file terunggah.</p>
                                                        @endif

                                                        <input type="file" name="answers[{{ $jawaban->id }}]"
                                                            class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition cursor-pointer border border-slate-200 rounded-lg p-1.5">
                                                        <p class="text-[11px] text-slate-400 mt-1">Kosongkan jika tidak ingin mengganti file lama.</p>

                                                    {{-- FALLBACK --}}
                                                    @else
                                                        <input type="text" name="answers[{{ $jawaban->id }}]"
                                                            value="{{ old($oldKey, $nilaiText) }}"
                                                            class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 px-5 py-10 text-center">
                                    <p class="text-sm font-medium text-slate-500">Belum ada jawaban</p>
                                    <p class="text-xs text-slate-400 mt-1">Peserta belum mengisi formulir.</p>
                                </div>
                            @endif
                        </div>
                    </section>

                    {{-- CATATAN ADMIN --}}
                    <section class="bg-white rounded-2xl border border-slate-200 shadow-card overflow-hidden p-5 sm:p-6">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5h2m-1-3v2m0 13v4m-4-4h8M5 9h14M6 21h12a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-slate-800">Catatan Admin</h2>
                                <p class="text-xs text-slate-400">Catatan internal (opsional)</p>
                            </div>
                        </div>
                        <textarea name="catatan_admin" rows="3"
                            class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition resize-none"
                            placeholder="Tulis catatan admin...">{{ old('catatan_admin', $peserta->catatan_admin) }}</textarea>
                    </section>
                </div>

                {{-- RIGHT SIDEBAR (1/3) --}}
                <div class="space-y-6">

                    {{-- STATUS INFO --}}
                    <div class="rounded-2xl bg-gradient-to-br from-emerald-600 to-teal-700 p-5 text-white shadow-lg">
                        <p class="text-[10px] uppercase tracking-widest text-emerald-200 font-bold">Status Saat Ini</p>
                        <div class="flex items-center gap-3 mt-3">
                            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold">Diterima Otomatis</p>
                                <p class="text-xs text-emerald-100 mt-0.5">Peserta langsung dapat membayar</p>
                            </div>
                        </div>
                    </div>
                    
                    <section class="bg-white rounded-2xl border border-slate-200 shadow-card overflow-hidden p-5 sm:p-6 space-y-3 sticky top-6">
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 px-4 py-3 text-sm font-semibold text-white shadow-sm transition active:scale-[0.98]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.peserta.show', $peserta->id) }}"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                            Batal
                        </a>
                        <p class="text-[11px] text-slate-400 text-center m-0">
                            Perubahan tersimpan ke data peserta & akun user.
                        </p>
                    </section>
                </div>
            </div>
        </form>
    </main>

    {{-- IMAGE PREVIEW MODAL --}}
    <div id="imageModal" class="hidden fixed inset-0 z-[9999] bg-slate-950/90 backdrop-blur-sm" aria-hidden="true">
        <div class="absolute top-0 left-0 right-0 z-10 flex items-center justify-between px-4 sm:px-6 py-4 bg-gradient-to-b from-black/60 to-transparent">
            <div class="flex items-center gap-2">
                <button type="button" onclick="zoomOut()" class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition" title="Zoom Out">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                </button>
                <button type="button" onclick="resetZoom()" class="h-10 px-3 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-semibold transition">Reset</button>
                <button type="button" onclick="zoomIn()" class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition" title="Zoom In">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </button>
            </div>
            <button type="button" onclick="closeImageModal()" class="w-10 h-10 rounded-xl bg-white/10 hover:bg-rose-500 text-white flex items-center justify-center transition" title="Tutup">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div id="imageModalBackdrop" class="w-full h-full flex items-center justify-center overflow-hidden p-5 sm:p-10" onclick="handleBackdropClick(event)">
            <img id="previewImage" src="" alt="Preview gambar" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl cursor-zoom-in" onclick="event.stopPropagation()" ondblclick="toggleZoom()">
        </div>
        <div class="absolute bottom-5 left-1/2 -translate-x-1/2 rounded-full bg-black/50 px-4 py-2 text-[11px] text-white/70 backdrop-blur">
            Klik dua kali gambar untuk zoom
        </div>
    </div>

    {{-- JAVASCRIPT IMAGE VIEWER --}}
    <script>
        let currentZoom = 1;
        const minZoom = 0.5, maxZoom = 4, zoomStep = 0.25;
        const imageModal = document.getElementById('imageModal');
        const previewImage = document.getElementById('previewImage');

        function openImageModal(imageUrl) {
            previewImage.src = imageUrl;
            currentZoom = 1;
            applyZoom();
            imageModal.classList.remove('hidden');
            imageModal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('overflow-hidden');
        }

        function closeImageModal() {
            imageModal.classList.add('hidden');
            imageModal.setAttribute('aria-hidden', 'true');
            previewImage.src = '';
            document.body.classList.remove('overflow-hidden');
            currentZoom = 1;
        }

        function applyZoom() {
            previewImage.style.transform = `scale(${currentZoom})`;
            previewImage.style.cursor = currentZoom > 1 ? 'zoom-out' : 'zoom-in';
        }

        function zoomIn() { currentZoom = Math.min(currentZoom + zoomStep, maxZoom); applyZoom(); }
        function zoomOut() { currentZoom = Math.max(currentZoom - zoomStep, minZoom); applyZoom(); }
        function resetZoom() { currentZoom = 1; applyZoom(); }
        function toggleZoom() { currentZoom = currentZoom > 1 ? 1 : 2; applyZoom(); }
        function handleBackdropClick(event) { if (event.target === event.currentTarget) closeImageModal(); }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') closeImageModal();
            if (event.key === '=' || event.key === '+') { if (!imageModal.classList.contains('hidden')) zoomIn(); }
            if (event.key === '-') { if (!imageModal.classList.contains('hidden')) zoomOut(); }
        });

        previewImage.addEventListener('wheel', function(event) {
            if (imageModal.classList.contains('hidden')) return;
            event.preventDefault();
            if (event.deltaY < 0) zoomIn(); else zoomOut();
        }, { passive: false });
    </script>

    @include('partials.swal')

</body>

</html>
