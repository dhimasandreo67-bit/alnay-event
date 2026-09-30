<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('admin.partials.pwa-head')

    <title>Dashboard Admin | ALNAY.ID</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },

                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#c38b22',
                            600: '#c38b22',
                            700: '#c38b22',
                        },
                    },

                    boxShadow: {
                        soft: '0 10px 30px rgba(15, 23, 42, 0.06)',
                        card: '0 4px 20px rgba(15, 23, 42, 0.05)',
                    },
                },
            },
        };
    </script>

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
        }

        /* Prevent selecting image while zooming */
        #previewImage {
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

/* Label pertanyaan */
.question-label {
    font-size: 14px;
    font-weight: 600;
    color: #51483f;
    line-height: 1.6;
    margin-bottom: 10px;
}

/* Container jawaban */
.jawaban-container {
    width: 100%;
}

/* Jawaban teks */
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

/* Preview gambar */
.file-preview {
    display: inline-block;
    max-width: 100%;
    background: #faf9f7;
    border: 1px solid #e7e2dc;
    border-radius: 10px;
    overflow: hidden;
}

.file-preview > a {
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

/* Info gambar */
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

/* Dokumen */
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

/* Mobile */
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
        $statusMeta = [
            'menunggu_review' => [
                'label' => 'Menunggu Review',
                'class' => 'bg-amber-50 text-amber-700 border-amber-200',
                'dot' => 'bg-amber-500',
            ],

            // 'revisi' => [
            //     'label' => 'Perlu Revisi Data',
            //     'class' => 'bg-rose-50 text-rose-700 border-rose-200',
            //     'dot' => 'bg-rose-500',
            // ],

            'diterima' => [
                'label' => 'Diterima',
                'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'dot' => 'bg-emerald-500',
            ],

            'ditolak' => [
                'label' => 'Ditolak',
                'class' => 'bg-slate-100 text-slate-600 border-slate-200',
                'dot' => 'bg-slate-500',
            ],
        ];

        $currentStatus = $statusMeta[$peserta->status] ?? [
            'label' => $peserta->status ?? '-',
            'class' => 'bg-slate-100 text-slate-600 border-slate-200',
            'dot' => 'bg-slate-400',
        ];
    @endphp


    {{-- =====================================================
        MAIN CONTENT
    ====================================================== --}}
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-10">

        {{-- =================================================
            BREADCRUMB
        ================================================== --}}
        <div class="flex items-center gap-2 text-xs text-slate-400 mb-5">

            <a
                href="{{ url('/admin') }}"
                class="hover:text-blue-600 transition"
            >

                Peserta
            </a>

            <svg
                class="w-3.5 h-3.5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 5l7 7-7 7"
                />
            </svg>

            <span class="text-slate-500">
                Detail Pendaftaran
            </span>

        </div>


        {{-- =================================================
            HEADER CARD
        ================================================== --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-soft overflow-hidden mb-6">

            {{-- Decorative top --}}
            <div class="h-1.5 bg-gradient-to-r from-orange-600 via-orange-500 to-orange-500"></div>

            <div class="p-5 sm:p-6 lg:p-7">

                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                    {{-- Title --}}
                    <div class="flex items-start gap-4">

                        {{-- Avatar --}}
                        <div
                            class="hidden sm:flex shrink-0 w-14 h-14 rounded-2xl bg-gradient-to-br from-orange-500 to-red-600 text-white items-center justify-center shadow-lg shadow-orange-500/20"
                        >
                            <span class="text-lg font-bold">
                                {{ strtoupper(substr($peserta->user->nama ?? 'P', 0, 1)) }}
                            </span>
                        </div>

                        <div>

                            <div class="flex flex-wrap items-center gap-2 mb-1">

                                <h1 class="text-xl sm:text-2xl font-bold text-slate-900">
                                    Detail Pendaftaran
                                </h1>

                                {{-- Status --}}
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full border text-[11px] font-semibold {{ $currentStatus['class'] }}"
                                >
                                    <span
                                        class="w-1.5 h-1.5 rounded-full {{ $currentStatus['dot'] }}"
                                    ></span>

                                    {{ $currentStatus['label'] }}
                                </span>

                            </div>

                            <p class="text-sm text-slate-500">
                                Review data peserta sebelum menentukan status pendaftaran.
                            </p>

                        </div>

                    </div>


                    {{-- Participant identity --}}
                    <div class="flex items-center gap-3">

                        <div class="text-right hidden sm:block">

                            <p class="text-xs text-slate-400">
                                Peserta
                            </p>

                            <p class="text-sm font-semibold text-slate-700">
                                {{ $peserta->user->nama ?? '-' }}
                            </p>

                        </div>

                        <div
                            class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500"
                        >
                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                />
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

            <div
                class="mb-6 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-emerald-700"
            >
                <svg
                    class="w-5 h-5 shrink-0 mt-0.5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 13l4 4L19 7"
                    />
                </svg>

                <p class="text-sm">
                    {{ session('success') }}
                </p>
            </div>

        @endif


        @if (session('error'))

            <div
                class="mb-6 flex items-start gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3.5 text-rose-700"
            >
                <svg
                    class="w-5 h-5 shrink-0 mt-0.5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>

                <p class="text-sm">
                    {{ session('error') }}
                </p>
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
                <section
                    class="bg-white rounded-2xl border border-slate-200 shadow-card overflow-hidden"
                >

                    <div class="px-5 sm:px-6 py-4 border-b border-slate-100">

                        <div class="flex items-center gap-3">

                            <div
                                class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center"
                            >
                                <svg
                                    class="w-5 h-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.8"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                    />
                                </svg>
                            </div>

                            <div>

                                <h2 class="text-sm font-bold text-slate-800">
                                    Informasi Peserta
                                </h2>

                                <p class="text-xs text-slate-400">
                                    Data dasar peserta pendaftaran
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="p-5 sm:p-6">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- Nama --}}
                            <div
                                class="group rounded-xl border border-slate-100 bg-slate-50/70 p-4 hover:bg-blue-50/50 hover:border-blue-100 transition"
                            >

                                <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400 mb-1.5">
                                    Nama Lengkap
                                </p>

                                <p class="text-sm font-semibold text-slate-800 break-words">
                                    {{ $peserta->user->nama ?? '-' }}
                                </p>

                            </div>


                            {{-- Email --}}
                            <div
                                class="group rounded-xl border border-slate-100 bg-slate-50/70 p-4 hover:bg-blue-50/50 hover:border-blue-100 transition"
                            >

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
                <section
                    class="bg-white rounded-2xl border border-slate-200 shadow-card overflow-hidden"
                >

                    {{-- Header --}}
                    <div class="px-5 sm:px-6 py-4 border-b border-slate-100">

                        <div class="flex items-center justify-between gap-4">

                            <div class="flex items-center gap-3">

                                <div
                                    class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center"
                                >
                                    <svg
                                        class="w-5 h-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.8"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z"
                                        />
                                    </svg>
                                </div>

                                <div>

                                    <h2 class="text-sm font-bold text-slate-800">
                                        Jawaban Formulir
                                    </h2>

                                    <p class="text-xs text-slate-400">
                                        Data yang diisi oleh peserta
                                    </p>

                                </div>

                            </div>

                            <span
                                class="hidden sm:inline-flex px-2.5 py-1 rounded-lg bg-slate-100 text-[11px] font-semibold text-slate-500"
                            >
                                {{ $peserta->jawabans->count() }} Jawaban
                            </span>

                        </div>

                    </div>


                    {{-- Answers --}}
                    <div class="p-5 sm:p-6">

                        <div class="space-y-3">

                            @forelse ($peserta->jawabans as $jawaban)

                            @continue(
                                in_array(
                                    $jawaban->pertanyaan?->tipe_input,
                                    ['heading', 'subheading'],
                                    true
                                )
                            )

                            @php
                                $tipeInput = strtolower($jawaban->pertanyaan?->tipe_input ?? '');
                                $filePath = is_array($jawaban->jawaban) ? ($jawaban->jawaban[0] ?? '') : (string) $jawaban->jawaban;
                                
                                // Bersihkan query string jika ada, lalu ambil ekstensi file
                                $cleanPath = parse_url($filePath, PHP_URL_PATH) ?? '';
                                $extension = strtolower(pathinfo($cleanPath, PATHINFO_EXTENSION));
                                
                                // Cek apakah tipe inputnya gambar ATAU ekstensi filenya adalah gambar
                                $isImage = in_array($tipeInput, ['upload gambar', 'upload_gambar', 'image', 'gambar'], true) 
                                            || in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true);
                            @endphp

                                <div
                                    class="group rounded-xl border border-slate-100 bg-white hover:border-slate-200 hover:shadow-sm transition overflow-hidden"
                                >

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
        // File ada di disk public → akses langsung via symlink storage
        $imageSrc = asset('storage/' . $filePath);
    @endphp

    <div class="mt-2 relative group rounded-xl overflow-hidden border border-slate-200 bg-white cursor-pointer"
         onclick="openImageModal('{{ $imageSrc }}')">

        <img src="{{ $imageSrc }}"
             alt="Lampiran {{ $jawaban->pertanyaan?->label ?? 'Upload Gambar' }}"
             class="w-full h-48 object-cover"
             loading="lazy"
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

                                                    <a
                                                        href="{{ route('admin.jawaban.file', $jawaban) }}"
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                        class="inline-flex items-center gap-2 rounded-lg bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 hover:bg-blue-100 transition"
                                                    >

                                                        <svg
                                                            class="w-4 h-4"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            viewBox="0 0 24 24"
                                                        >
                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                stroke-width="1.8"
                                                                d="M12 16V4m0 0L8 8m4-4l4 4M5 20h14"
                                                            />
                                                        </svg>

                                                        Lihat / Unduh Berkas

                                                    </a>

                                                {{-- TEXT --}}
                                                @else

                                                    <div
                                                        class="rounded-lg bg-slate-50 border border-slate-100 px-3 py-2.5"
                                                    >

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

                                <div
                                    class="rounded-xl border border-dashed border-slate-200 bg-slate-50 px-5 py-10 text-center"
                                >

                                    <div
                                        class="mx-auto mb-3 w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400"
                                    >
                                        <svg
                                            class="w-5 h-5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.8"
                                                d="M9 12h6m-6 4h4m-8 4h14a2 2 0 002-2V6a2 2 0 00-2-2h-5l-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"
                                            />
                                        </svg>
                                    </div>

                                    <p class="text-sm font-medium text-slate-500">
                                        Belum ada jawaban
                                    </p>

                                    <p class="text-xs text-slate-400 mt-1">
                                        Peserta belum mengisi formulir.
                                    </p>

                                </div>

                            @endforelse

                        </div>

                    </div>

                </section>


                {{-- =============================================
                    CATATAN ADMIN
                ============================================== --}}
                @if ($peserta->catatan_admin)

                    <section
                        class="rounded-2xl border border-amber-200 bg-gradient-to-br from-amber-50 to-orange-50 p-5 shadow-sm"
                    >

                        <div class="flex items-start gap-3">

                            <div
                                class="w-9 h-9 shrink-0 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center"
                            >
                                <svg
                                    class="w-5 h-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.8"
                                        d="M11 5h2m-1-3v2m0 13v4m-4-4h8M5 9h14M6 21h12a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v12a2 2 0 002 2z"
                                    />
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
                    PAYMENT
                ============================================== --}}
                @if ($peserta->pembayaran)

                    <div class="bg-white rounded-xl border border-slate-200 p-5">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h3 class="text-sm font-semibold text-slate-800">Pembayaran</h3>
                        </div>
                        <p class="text-xs text-slate-500 mb-4">Status transaksi peserta</p>

                        @php
                            $paymentIsPaid = $peserta->pembayaran->status_midtrans === 'settlement';
                            $pembayaran = $peserta->pembayaran;
                        @endphp

                        {{-- Status Badge --}}
                        <div class="mb-4">
                            <span class="text-xs text-slate-500">Status</span>
                            <div class="mt-1">
                                @if ($paymentIsPaid)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Lunas
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        {{ ucfirst($pembayaran->status_midtrans) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- NOMINAL UNIK (Highlight) --}}
                        <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-lg p-4 mb-4">
                            <span class="text-xs text-amber-700 font-medium">Nominal Transfer Unik</span>
                            <div class="text-2xl font-bold text-amber-900 mt-1">
                                Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                            </div>
                            <div class="text-xs text-amber-700 mt-2 space-y-1">
                                <div class="flex justify-between">
                                    <span>Tarif Dasar:</span>
                                    <span class="font-medium">Rp {{ number_format($pembayaran->tarif_dasar ?? 0, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Kode Unik:</span>
                                    <span class="font-mono font-bold bg-amber-200 px-1.5 py-0.5 rounded">
                                        + {{ $pembayaran->kode_unik }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Order ID --}}
                        <div class="flex justify-between items-center py-2 border-t border-slate-100">
                            <span class="text-xs text-slate-500">Order ID</span>
                            <span class="text-xs font-mono text-slate-700">{{ $pembayaran->midtrans_order_id }}</span>
                        </div>

                        {{-- Metode --}}
                        <div class="flex justify-between items-center py-2 border-t border-slate-100">
                            <span class="text-xs text-slate-500">Metode</span>
                            <span class="text-xs text-slate-700">{{ ucfirst($pembayaran->metode ?? '-') }}</span>
                        </div>

                        @if ($pembayaran->tanggal_bayar)
                        <div class="flex justify-between items-center py-2 border-t border-slate-100">
                            <span class="text-xs text-slate-500">Tanggal Bayar</span>
                            <span class="text-xs text-slate-700">{{ $pembayaran->tanggal_bayar->format('d M Y, H:i') }} WIB</span>
                        </div>
                        @endif
                    </div>

                @endif


                {{-- =============================================
                    ACTION
                ============================================== --}}
                <section
                    class="bg-white rounded-2xl border border-slate-200 shadow-card overflow-hidden"
                >

                    <div class="px-5 py-4 border-b border-slate-100">

                        <h2 class="text-sm font-bold text-slate-800">
                            Tindakan Review
                        </h2>

                        <p class="text-xs text-slate-400 mt-1">
                            Tentukan hasil review pendaftaran.
                        </p>

                    </div>


                    <form
                        action="{{ route('admin.peserta.update-status', $peserta) }}"
                        method="POST"
                        class="p-5"
                    >

                        @csrf

                        {{-- Buttons --}}
                        <div class="space-y-2.5">

                            {{-- Terima --}}
                            <button
                                type="submit"
                                name="status"
                                value="diterima"
                                class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 px-4 py-3 text-sm font-semibold text-white shadow-sm shadow-emerald-600/20 transition active:scale-[0.98]"
                            >

                                <svg
                                    class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>

                                Terima Pendaftaran

                            </button>


                            {{-- Revisi --}}
                            {{-- <button
                                type="submit"
                                name="status"
                                value="revisi"
                                class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-amber-500 hover:bg-amber-600 px-4 py-3 text-sm font-semibold text-white shadow-sm shadow-amber-500/20 transition active:scale-[0.98]"
                            >

                                <svg
                                    class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.5-6.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 8.5-8.5z"
                                    />
                                </svg>

                                Minta Revisi

                            </button> --}}


                            {{-- Tolak --}}
                            <button
                                type="submit"
                                name="status"
                                value="ditolak"
                                class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-white hover:bg-rose-50 border border-rose-200 px-4 py-3 text-sm font-semibold text-rose-600 transition active:scale-[0.98]"
                            >

                                <svg
                                    class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>

                                Tolak Pendaftaran

                            </button>

                        </div>

                    </form>


                    {{-- Delete --}}
                    <div class="px-5 pb-5">

                        <form action="{{ route('admin.peserta.destroy', $peserta->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data peserta ini? Tindakan ini tidak bisa dibatalkan.')">

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-xs font-semibold text-slate-500 hover:text-red-600 hover:border-red-200 hover:bg-red-50 transition"
                            >

                                <svg
                                    class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.8"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-9 0h14"
                                    />
                                </svg>

                                Hapus Data Peserta

                            </button>

                        </form>

                    </div>

                </section>


                {{-- =============================================
                    STATUS INFO
                ============================================== --}}
                <div class="rounded-2xl bg-gradient-to-br from-slate-800 to-slate-900 p-5 text-white shadow-lg">

                    <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold">
                        Status Saat Ini
                    </p>

                    <div class="flex items-center gap-3 mt-3">

                        <div
                            class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center"
                        >
                            <span
                                class="w-3 h-3 rounded-full {{ $currentStatus['dot'] }}"
                            ></span>
                        </div>

                        <div>

                            <p class="font-semibold">
                                {{ $currentStatus['label'] }}
                            </p>

                            <p class="text-xs text-slate-400 mt-0.5">
                                Status pendaftaran peserta
                            </p>

                        </div>

                    </div>

                </div>

            </aside>

        </div>

    </main>


    {{-- =====================================================
        IMAGE PREVIEW MODAL
    ====================================================== --}}
    <div
        id="imageModal"
        class="hidden fixed inset-0 z-[9999] bg-slate-950/90 backdrop-blur-sm"
        aria-hidden="true"
    >

        {{-- Top bar --}}
        <div
            class="absolute top-0 left-0 right-0 z-10 flex items-center justify-between px-4 sm:px-6 py-4 bg-gradient-to-b from-black/60 to-transparent"
        >

            <div class="flex items-center gap-2">

                <button
                    type="button"
                    onclick="zoomOut()"
                    class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition"
                    title="Zoom Out"
                >
                    <svg
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M20 12H4"
                        />
                    </svg>
                </button>


                <button
                    type="button"
                    onclick="resetZoom()"
                    class="h-10 px-3 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-semibold transition"
                >
                    Reset
                </button>


                <button
                    type="button"
                    onclick="zoomIn()"
                    class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition"
                    title="Zoom In"
                >
                    <svg
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 4v16m8-8H4"
                        />
                    </svg>
                </button>

            </div>


            {{-- Close --}}
            <button
                type="button"
                onclick="closeImageModal()"
                class="w-10 h-10 rounded-xl bg-white/10 hover:bg-rose-500 text-white flex items-center justify-center transition"
                title="Tutup"
            >
                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>

        </div>


        {{-- Image container --}}
        <div
            id="imageModalBackdrop"
            class="w-full h-full flex items-center justify-center overflow-hidden p-5 sm:p-10"
            onclick="handleBackdropClick(event)"
        >

            <img
                id="previewImage"
                src=""
                alt="Preview gambar"
                class="max-w-full max-h-full object-contain rounded-lg shadow-2xl cursor-zoom-in"
                onclick="event.stopPropagation()"
                ondblclick="toggleZoom()"
            >

        </div>


        {{-- Bottom hint --}}
        <div
            class="absolute bottom-5 left-1/2 -translate-x-1/2 rounded-full bg-black/50 px-4 py-2 text-[11px] text-white/70 backdrop-blur"
        >
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


        /**
         * Open image modal
         */
        function openImageModal(imageUrl) {

            previewImage.src = imageUrl;

            currentZoom = 1;
            applyZoom();

            imageModal.classList.remove('hidden');
            imageModal.setAttribute('aria-hidden', 'false');

            document.body.classList.add('overflow-hidden');
        }


        /**
         * Close image modal
         */
        function closeImageModal() {

            imageModal.classList.add('hidden');
            imageModal.setAttribute('aria-hidden', 'true');

            previewImage.src = '';

            document.body.classList.remove('overflow-hidden');

            currentZoom = 1;
        }


        /**
         * Apply zoom
         */
        function applyZoom() {

            previewImage.style.transform =
                `scale(${currentZoom})`;

            previewImage.style.cursor =
                currentZoom > 1
                    ? 'zoom-out'
                    : 'zoom-in';
        }


        /**
         * Zoom in
         */
        function zoomIn() {

            currentZoom = Math.min(
                currentZoom + zoomStep,
                maxZoom
            );

            applyZoom();
        }


        /**
         * Zoom out
         */
        function zoomOut() {

            currentZoom = Math.max(
                currentZoom - zoomStep,
                minZoom
            );

            applyZoom();
        }


        /**
         * Reset zoom
         */
        function resetZoom() {

            currentZoom = 1;

            applyZoom();
        }


        /**
         * Double click zoom
         */
        function toggleZoom() {

            if (currentZoom > 1) {
                resetZoom();
            } else {
                currentZoom = 2;

                applyZoom();
            }
        }


        /**
         * Close when clicking backdrop
         */
        function handleBackdropClick(event) {

            if (event.target === event.currentTarget) {
                closeImageModal();
            }
        }


        /**
         * ESC to close
         */
        document.addEventListener('keydown', function (event) {

            if (event.key === 'Escape') {
                closeImageModal();
            }

            if (
                event.key === '+' ||
                event.key === '='
            ) {
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


        /**
         * Mouse wheel zoom
         */
        previewImage.addEventListener('wheel', function (event) {

            if (
                imageModal.classList.contains('hidden')
            ) {
                return;
            }

            event.preventDefault();

            if (event.deltaY < 0) {
                zoomIn();
            } else {
                zoomOut();
            }

        }, {
            passive: false
        });

    </script>

</body>

</html>
