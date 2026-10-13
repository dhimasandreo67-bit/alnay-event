<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detail Peserta | ALNAY.ID</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Tailwind CSS --}}
    @vite(['resources/css/app.css'])

    {{-- Custom Admin CSS --}}
    <link rel="stylesheet" href="{{ asset('css/admin-sidebar.css') }}">

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

        .question-card {
            background: #fff;
            border: 1px solid #e8e3dc;
            border-radius: 12px;
            padding: 18px 20px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            transition: all 0.2s ease;
        }

        .question-card:hover {
            border-color: #dcd4ca;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
        }

        .question-label {
            font-size: 14px;
            font-weight: 600;
            color: #51483f;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .jawaban-container {
            width: 100%;
        }

        .text-answer {
            background: #f8f7f5;
            border: 1px solid #ebe7e2;
            border-radius: 8px;
            padding: 11px 14px;
            color: #333;
            font-size: 14px;
            line-height: 1.6;
            min-height: 42px;
            white-space: pre-line;
        }

        .file-preview {
            display: inline-block;
            max-width: 100%;
            background: #faf9f7;
            border: 1px solid #e7e2dc;
            border-radius: 10px;
            overflow: hidden;
        }

        .file-preview>a {
            display: block;
            padding: 8px;
        }

        .file-preview img {
            display: block;
            width: auto;
            max-width: 300px;
            max-height: 220px;
            border-radius: 7px;
            object-fit: cover;
            transition: opacity 0.2s ease;
        }

        .file-preview img:hover {
            opacity: 0.85;
        }

        .file-info {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-top: 1px solid #e7e2dc;
        }

        .file-icon {
            font-size: 20px;
        }

        .file-name {
            color: #403a34;
            font-size: 13px;
            font-weight: 600;
            max-width: 250px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .file-link {
            display: inline-block;
            margin-top: 2px;
            color: #8b6f47;
            font-size: 12px;
            text-decoration: none;
        }

        .file-link:hover {
            text-decoration: underline;
        }

        .document-preview {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            max-width: 500px;
            padding: 12px 14px;
            background: #f8f7f5;
            border: 1px solid #e5e0da;
            border-radius: 9px;
            text-decoration: none;
            color: inherit;
            transition: all 0.2s ease;
        }

        .document-preview:hover {
            background: #f3f0ec;
            border-color: #d8d0c7;
            transform: translateY(-1px);
        }

        .document-icon {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eee8df;
            border-radius: 8px;
            font-size: 20px;
            flex-shrink: 0;
        }

        .document-info {
            flex: 1;
            min-width: 0;
        }

        .document-name {
            color: #3f3933;
            font-size: 13px;
            font-weight: 600;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .document-type {
            margin-top: 3px;
            color: #8a8178;
            font-size: 11px;
        }

        .download-icon {
            color: #8b6f47;
            font-size: 18px;
            flex-shrink: 0;
        }

        @media (max-width: 576px) {
            .question-card {
                padding: 14px;
                border-radius: 10px;
            }

            .question-label {
                font-size: 13px;
            }

            .text-answer {
                font-size: 13px;
                padding: 10px 12px;
            }

            .file-preview img {
                max-width: 100%;
                max-height: 200px;
            }

            .document-preview {
                padding: 10px;
            }

            .document-name {
                max-width: 180px;
            }
        }
    </style>
</head>

<body class="bg-slate-100 text-slate-800 min-h-screen">

    @php
        // Status otomatis "Diterima" - tidak ada pilihan lain
        $currentStatus = [
            'label' => 'Diterima',
            'class' => 'bg-blue-50 text-blue-700 border-blue-200',
            'dot' => 'bg-blue-500',
        ];

        // Ambil pembayaran terbaru
        $pembayaran = $peserta->pembayaran instanceof \Illuminate\Support\Collection
            ? $peserta->pembayaran->sortByDesc('id')->first()
            : $peserta->pembayaran;

        $paymentIsPaid = $pembayaran?->status_midtrans === 'settlement';
    @endphp


    {{-- =====================================================
        MAIN CONTENT
    ====================================================== --}}
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-10">

        {{-- =================================================
            BREADCRUMB
        ================================================== --}}
        <div class="flex items-center gap-2 text-xs text-slate-400 mb-5">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition">
                Peserta
            </a>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-slate-500">
                Detail Pendaftaran
            </span>
        </div>


        {{-- =================================================
            HEADER CARD
        ================================================== --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-soft overflow-hidden mb-6">

            {{-- Decorative top - hijau untuk status diterima --}}
            <div class="h-1.5 bg-gradient-to-r from-blue-600 via-blue-500 to-cyan-500"></div>

            <div class="p-5 sm:p-6 lg:p-7">

                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                    {{-- Title --}}
                    <div class="flex items-start gap-4">

                        {{-- Avatar --}}
                        <div class="hidden sm:flex shrink-0 w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-600 text-white items-center justify-center shadow-lg shadow-blue-500/20">
                            <span class="text-lg font-bold">
                                {{ strtoupper(substr($peserta->user->nama ?? 'P', 0, 1)) }}
                            </span>
                        </div>

                        <div>
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <h1 class="text-xl sm:text-2xl font-bold text-slate-900">
                                    Detail Pendaftaran
                                </h1>

                                {{-- Status Badge --}}
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full border text-[11px] font-semibold {{ $currentStatus['class'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $currentStatus['dot'] }}"></span>
                                    {{ $currentStatus['label'] }}
                                </span>
                            </div>

                            <p class="text-sm text-slate-500">
                                Peserta otomatis diterima saat mendaftar. Data pendaftaran & status pembayaran.
                            </p>
                        </div>
                    </div>


                    {{-- Participant identity --}}
                    <div class="flex items-center gap-3">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs text-slate-400">Peserta</p>
                            <p class="text-sm font-semibold text-slate-700">
                                {{ $peserta->namaTampil() }}
                            </p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- =================================================
            FLASH MESSAGE
        ================================================== --}}
        @if (session('success'))
            <div class="mb-6 flex items-start gap-3 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3.5 text-blue-700">
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


        {{-- =================================================
            CONTENT GRID
        ================================================== --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">


            {{-- =================================================
                LEFT CONTENT
            ================================================== --}}
            <div class="lg:col-span-2 space-y-6">


                {{-- =============================================
                    INFORMASI PESERTA
                ============================================== --}}
                <section class="bg-white rounded-2xl border border-slate-200 shadow-card overflow-hidden">

                    <div class="px-5 sm:px-6 py-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-slate-800">Informasi Peserta</h2>
                                <p class="text-xs text-slate-400">Data dasar peserta pendaftaran</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 sm:p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- Nama Pendaftaran --}}
                            <div class="group rounded-xl border border-slate-100 bg-slate-50/70 p-4 hover:bg-blue-50/50 hover:border-blue-100 transition">
                                <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400 mb-1.5">
                                    Nama Pendaftaran
                                </p>
                                <p class="text-sm font-semibold text-slate-800 break-words">
                                    {{ $peserta->namaTampil() }}
                                </p>
                                @if ($peserta->user && $peserta->namaTampil() !== ($peserta->user->nama_custom ?: $peserta->user->nama))
                                    <p class="text-[10px] text-slate-400 mt-1">
                                        Nama akun: {{ $peserta->user->nama_custom ?: $peserta->user->nama }}
                                    </p>
                                @endif
                            </div>

                            {{-- Email --}}
                            <div class="group rounded-xl border border-slate-100 bg-slate-50/70 p-4 hover:bg-blue-50/50 hover:border-blue-100 transition">
                                <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400 mb-1.5">
                                    Email
                                </p>
                                <p class="text-sm font-semibold text-slate-800 break-all">
                                    {{ $peserta->user->email ?? '-' }}
                                </p>
                            </div>

                        </div>
                    </div>

                </section>


                {{-- =============================================
                    JAWABAN FORMULIR
                ============================================== --}}
                <section class="bg-white rounded-2xl border border-slate-200 shadow-card overflow-hidden">

                    {{-- Header --}}
                    <div class="px-5 sm:px-6 py-4 border-b border-slate-100">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-sm font-bold text-slate-800">Jawaban Formulir</h2>
                                    <p class="text-xs text-slate-400">Data yang diisi oleh peserta</p>
                                </div>
                            </div>
                            <span class="hidden sm:inline-flex px-2.5 py-1 rounded-lg bg-slate-100 text-[11px] font-semibold text-slate-500">
                                {{ $peserta->jawabans->count() }} Jawaban
                            </span>
                        </div>
                    </div>

                    {{-- Answers --}}
                    <div class="p-5 sm:p-6">
                        <div class="space-y-3">

                            @forelse ($peserta->jawabans as $jawaban)
                                @php
                                    // Skip heading / subheading
                                    if (in_array($jawaban->pertanyaan?->tipe_input, ['heading', 'subheading'], true)) {
                                        continue;
                                    }

                                    // Skip field "nama" — sudah tampil di header
                                    $labelLower = strtolower(trim($jawaban->pertanyaan?->label ?? ''));
                                    $namaKeywords = [
                                        'nama',
                                        'nama lengkap',
                                        'nama pendaftaran',
                                        'nama tampilan',
                                        'name',
                                    ];

                                    if (in_array($labelLower, $namaKeywords, true)) {
                                        continue;
                                    }

                                    $tipeInput = strtolower($jawaban->pertanyaan?->tipe_input ?? '');
                                    $filePath = is_array($jawaban->jawaban)
                                        ? $jawaban->jawaban[0] ?? ''
                                        : (string) $jawaban->jawaban;

                                    $cleanPath = parse_url($filePath, PHP_URL_PATH) ?? '';
                                    $extension = strtolower(pathinfo($cleanPath, PATHINFO_EXTENSION));

                                    $isImage =
                                        in_array($tipeInput, ['upload gambar', 'upload_gambar', 'image', 'gambar'], true)
                                        || in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true);
                                @endphp

                                <div class="group rounded-xl border border-slate-100 bg-white hover:border-slate-200 hover:shadow-sm transition overflow-hidden">
                                    <div class="p-4">
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-5">

                                            {{-- Question --}}
                                            <div>
                                                <p class="text-[11px] uppercase tracking-wide font-semibold text-slate-400 mb-1">
                                                    Pertanyaan
                                                </p>
                                                <p class="text-xs sm:text-sm font-medium text-slate-600">
                                                    {{ $jawaban->pertanyaan?->label ?? 'Pertanyaan dihapus' }}
                                                </p>
                                            </div>

                                            {{-- Answer --}}
                                            <div class="sm:col-span-2">
                                                <p class="text-[11px] uppercase tracking-wide font-semibold text-slate-400 mb-2">
                                                    Jawaban
                                                </p>

                                                {{-- IMAGE --}}
                                                @if ($isImage)
                                                    @php
                                                        $imageSrc = asset('storage/' . $filePath);
                                                    @endphp

                                                    <div class="mt-2 relative group rounded-xl overflow-hidden border border-slate-200 bg-white cursor-pointer"
                                                        onclick="openImageModal('{{ $imageSrc }}')">

                                                        <img src="{{ $imageSrc }}"
                                                            alt="Lampiran {{ $jawaban->pertanyaan?->label ?? 'Upload Gambar' }}"
                                                            class="w-full h-48 object-cover" loading="lazy"
                                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                                                        <div class="hidden absolute inset-0 bg-rose-50 items-center justify-center text-rose-600 text-sm text-center px-4">
                                                            Gambar gagal dimuat.<br>
                                                            <code class="text-xs break-all">{{ $filePath }}</code>
                                                        </div>

                                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end justify-center pb-3 pointer-events-none">
                                                            <span class="text-white text-sm font-medium">Klik untuk memperbesar</span>
                                                        </div>
                                                    </div>

                                                {{-- FILE --}}
                                                @elseif ($tipeInput === 'file')
                                                    <a href="{{ route('admin.jawaban.file', $jawaban) }}"
                                                        target="_blank" rel="noopener noreferrer"
                                                        class="inline-flex items-center gap-2 rounded-lg bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 hover:bg-blue-100 transition">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 16V4m0 0L8 8m4-4l4 4M5 20h14" />
                                                        </svg>
                                                        Lihat / Unduh Berkas
                                                    </a>

                                                {{-- TEXT --}}
                                                @else
                                                    <div class="rounded-lg bg-slate-50 border border-slate-100 px-3 py-2.5">
                                                        <p class="text-sm text-slate-700 whitespace-pre-line break-words">
                                                            {{ $jawaban->jawaban ?: '-' }}
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @empty

                                <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 px-5 py-10 text-center">
                                    <div class="mx-auto mb-3 w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                d="M9 12h6m-6 4h4m-8 4h14a2 2 0 002-2V6a2 2 0 00-2-2h-5l-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-slate-500">Belum ada jawaban</p>
                                    <p class="text-xs text-slate-400 mt-1">Peserta belum mengisi formulir.</p>
                                </div>
                            @endforelse

                        </div>
                    </div>

                </section>


                {{-- =============================================
                    CATATAN ADMIN
                ============================================== --}}
                @if ($peserta->catatan_admin)
                    <section class="rounded-2xl border border-amber-200 bg-gradient-to-br from-amber-50 to-orange-50 p-5 shadow-sm">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 shrink-0 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M11 5h2m-1-3v2m0 13v4m-4-4h8M5 9h14M6 21h12a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold uppercase tracking-wide text-amber-800">
                                    Catatan Admin Sebelumnya
                                </h3>
                                <p class="mt-1.5 text-sm text-amber-900/80 whitespace-pre-line">
                                    {{ $peserta->catatan_admin }}
                                </p>
                            </div>
                        </div>
                    </section>
                @endif

            </div>


            {{-- =================================================
                RIGHT SIDEBAR
            ================================================== --}}
            <aside class="space-y-6">

                {{-- =============================================
                    STATUS PENDAFTARAN (AUTO DITERIMA)
                ============================================== --}}
                <div class="rounded-2xl bg-gradient-to-br from-blue-600 to-cyan-700 p-5 text-white shadow-lg">
                    <p class="text-[10px] uppercase tracking-widest text-blue-200 font-bold">
                        Status Pendaftaran
                    </p>
                    <div class="flex items-center gap-3 mt-3">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold">Diterima Otomatis</p>
                            <p class="text-xs text-blue-100 mt-0.5">
                                Peserta langsung dapat membayar
                            </p>
                        </div>
                    </div>
                </div>

                {{-- =============================================
                    AKSI CEPAT
                ============================================== --}}
                <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-2">
                    <h3 class="text-sm font-semibold text-slate-800 mb-3">Aksi Cepat</h3>

                    <a href="{{ route('admin.peserta.edit', $peserta->id) }}"
                        class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 hover:bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Data
                    </a>

                    <a href="{{ route('admin.dashboard') }}"
                        class="w-full inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Dashboard
                    </a>
                </div>

                {{-- =============================================
                    STATUS PEMBAYARAN
                ============================================== --}}
                <div class="bg-white rounded-xl border border-slate-200 p-5">
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="text-sm font-semibold text-slate-800">Pembayaran</h3>
                    </div>
                    <p class="text-xs text-slate-500 mb-4">Status transaksi peserta</p>

                    @if ($pembayaran)
                        {{-- Status Badge --}}
                        <div class="mb-4">
                            <span class="text-xs text-slate-500">Status</span>
                            <div class="mt-1">
                                @if ($paymentIsPaid)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                        Lunas
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        {{ ucfirst($pembayaran->status_midtrans ?? 'pending') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Nominal --}}
                        <div class="flex justify-between items-center py-2 border-t border-slate-100">
                            <span class="text-xs text-slate-500">Nominal</span>
                            <span class="text-sm font-bold text-slate-800">
                                Rp {{ number_format($pembayaran->jumlah ?? 0, 0, ',', '.') }}
                            </span>
                        </div>

                        {{-- Order ID --}}
                        <div class="flex justify-between items-center py-2 border-t border-slate-100">
                            <span class="text-xs text-slate-500">Order ID</span>
                            <span class="text-xs font-mono text-slate-700">
                                {{ $pembayaran->midtrans_order_id ?? '-' }}
                            </span>
                        </div>

                        {{-- Metode --}}
                        <div class="flex justify-between items-center py-2 border-t border-slate-100">
                            <span class="text-xs text-slate-500">Metode</span>
                            <span class="text-xs text-slate-700">
                                {{ ucfirst($pembayaran->metode ?? '-') }}
                            </span>
                        </div>

                        @if ($pembayaran->tanggal_bayar)
                            <div class="flex justify-between items-center py-2 border-t border-slate-100">
                                <span class="text-xs text-slate-500">Tanggal Bayar</span>
                                <span class="text-xs text-slate-700">
                                    {{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d M Y, H:i') }} WIB
                                </span>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-6">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-500 border border-slate-200">
                                Belum ada pembayaran
                            </span>
                        </div>
                    @endif
                </div>
            </aside>
        </div>
    </main>


    {{-- =====================================================
        IMAGE PREVIEW MODAL
    ====================================================== --}}
    <div id="imageModal" class="hidden fixed inset-0 z-[9999] bg-slate-950/90 backdrop-blur-sm" aria-hidden="true">

        {{-- Top bar --}}
        <div class="absolute top-0 left-0 right-0 z-10 flex items-center justify-between px-4 sm:px-6 py-4 bg-gradient-to-b from-black/60 to-transparent">
            <div class="flex items-center gap-2">
                <button type="button" onclick="zoomOut()"
                    class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition"
                    title="Zoom Out">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                    </svg>
                </button>

                <button type="button" onclick="resetZoom()"
                    class="h-10 px-3 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-semibold transition">
                    Reset
                </button>

                <button type="button" onclick="zoomIn()"
                    class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition"
                    title="Zoom In">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </button>
            </div>

            {{-- Close --}}
            <button type="button" onclick="closeImageModal()"
                class="w-10 h-10 rounded-xl bg-white/10 hover:bg-rose-500 text-white flex items-center justify-center transition"
                title="Tutup">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Image container --}}
        <div id="imageModalBackdrop"
            class="w-full h-full flex items-center justify-center overflow-hidden p-5 sm:p-10"
            onclick="handleBackdropClick(event)">
            <img id="previewImage" src="" alt="Preview gambar"
                class="max-w-full max-h-full object-contain rounded-lg shadow-2xl cursor-zoom-in"
                onclick="event.stopPropagation()" ondblclick="toggleZoom()">
        </div>

        {{-- Bottom hint --}}
        <div class="absolute bottom-5 left-1/2 -translate-x-1/2 rounded-full bg-black/50 px-4 py-2 text-[11px] text-white/70 backdrop-blur">
            Klik dua kali gambar untuk zoom
        </div>
    </div>


    {{-- =====================================================
        JAVASCRIPT IMAGE VIEWER
    ====================================================== --}}
    <script>
        let currentZoom = 1;
        const minZoom = 0.5;
        const maxZoom = 4;
        const zoomStep = 0.25;

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

        function zoomIn() {
            currentZoom = Math.min(currentZoom + zoomStep, maxZoom);
            applyZoom();
        }

        function zoomOut() {
            currentZoom = Math.max(currentZoom - zoomStep, minZoom);
            applyZoom();
        }

        function resetZoom() {
            currentZoom = 1;
            applyZoom();
        }

        function toggleZoom() {
            if (currentZoom > 1) {
                resetZoom();
            } else {
                currentZoom = 2;
                applyZoom();
            }
        }

        function handleBackdropClick(event) {
            if (event.target === event.currentTarget) {
                closeImageModal();
            }
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeImageModal();
            }
            if (event.key === '+' || event.key === '=') {
                if (!imageModal.classList.contains('hidden')) {
                    zoomIn();
                }
            }
            if (event.key === '-') {
                if (!imageModal.classList.contains('hidden')) {
                    zoomOut();
                }
            }
        });

        previewImage.addEventListener('wheel', function(event) {
            if (imageModal.classList.contains('hidden')) {
                return;
            }
            event.preventDefault();
            if (event.deltaY < 0) {
                zoomIn();
            } else {
                zoomOut();
            }
        }, { passive: false });
    </script>

    @include('partials.swal')

</body>

</html>