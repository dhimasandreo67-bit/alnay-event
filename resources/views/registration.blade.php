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

    <header class="border-b border-[#CDC5BC]/30 bg-[#FBF9F4]/95 backdrop-blur">
        <div class="max-w-3xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="{{ $isPreview ? route('admin.dashboard') : '/beranda' }}"
                class="font-bold text-xl text-[#0A1526]">EVENT ALNAY.ID</a>

            @if ($isPreview)
                <span
                    class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 border border-amber-300 px-3 py-1.5 text-[11px] font-semibold text-amber-800">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Mode Preview (Admin)
                </span>
            @endif
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-6 py-8">

        <div class="mb-6">
            @php
                // Tombol back — admin: ke form builder. User: history back + fallback.
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

        <div class="bg-white border border-[#CDC5BC]/30 rounded-xl p-6 mb-6">
            <h1 class="text-2xl font-bold text-black mb-1">{{ $event->nama }}</h1>
            <p class="text-sm text-[#4B463F]">Isi formulir di bawah untuk mendaftar event ini.</p>
        </div>

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
        @endif

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

            // Status event dulu
            if (in_array($event->status, ['sedang_berlangsung', 'telah_berakhir'])) {
                $pendaftaranDitutup = true;
                $alasanTutup =
                    'Event ini sedang berlangsung / telah berakhir — pendaftaran peserta baru sudah ditutup.';
            }
            // Flag manual admin
            elseif (!$event->pendaftaran_dibuka) {
                $pendaftaranDitutup = true;
                $alasanTutup = 'Pendaftaran untuk event ini sedang ditutup oleh admin.';
            }
            // Waktu belum masuk
            elseif ($bukaDaftar && $sekarang->lt($bukaDaftar)) {
                $pendaftaranDitutup = true;
                $alasanTutup =
                    'Pendaftaran untuk event ini belum dibuka. Buka pada ' .
                    $bukaDaftar->translatedFormat('d F Y') .
                    ' pukul ' .
                    ($event->pembukaan_pendaftaran_waktu ?? '00:00') .
                    ' WIB.';
            }
            // Waktu udah lewat
            elseif ($batasDaftar && $sekarang->gt($batasDaftar)) {
                $pendaftaranDitutup = true;
                $alasanTutup =
                    'Pendaftaran untuk event ini sudah ditutup pada ' . $batasDaftar->translatedFormat('d F Y') . '.';
            } else {
                $pendaftaranDitutup = false;
                $alasanTutup = null;
            }
        @endphp

        @if ($pendaftaranDitutup)
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
            {{-- Form Pendaftaran — ini yang muncul kalau pendaftaran dibuka --}}
            <form method="POST" action="{{ route('public.registration.store', $event) }}"
                enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- Cek apakah form builder sudah dibuat — pakai relasi pertanyaans --}}
                @php
                    $formFields = $event->pertanyaans()->orderBy('urutan', 'asc')->get();
                @endphp

                @if ($formFields->count() > 0)
                    @include('public.partials.dynamic-form-fields', [
                        'fields' => $formFields,
                    ])
                @else
                    {{-- Form builder belum dibuat — tampilkan notice --}}
                    <div class="bg-white border border-[#CDC5BC]/30 rounded-xl p-8 text-center">
                        <div class="w-16 h-16 rounded-full bg-amber-50 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v2m0 4h.01M5 19h14a2 2 0 001.84-2.75L13.74 4a2 2 0 00-3.5 0L3.16 16.25A2 2 0 005 19z" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold text-black mb-2">Formulir Belum Tersedia</h2>
                        <p class="text-sm text-[#4B463F]">
                            @if ($isPreview)
                                Admin belum membuat formulir pendaftaran untuk event ini.
                                Buka <strong>Form Builder</strong> untuk mulai mengatur form.
                            @else
                                Formulir pendaftaran untuk event ini belum tersedia. Silakan cek kembali nanti.
                            @endif
                        </p>
                    </div>
                @endif
            </form>
        @endif

    </main>

    {{-- Script untuk mode preview admin — tutup tab --}}
    @if ($isPreview)
        <script>
            function closePreviewTab() {
                // Coba tutup tab preview
                window.close();

                // Fallback — kalau browser blokir window.close()
                setTimeout(function() {
                    if (!window.closed) {
                        // Redirect ke form builder di tab yang sama
                        window.location.href = '{{ route('admin.form-builder', $event->id) }}';
                    }
                }, 300);
            }
        </script>
    @endif

    {{-- Script goBack — history back + fallback ke beranda (untuk user) --}}
    @unless ($isPreview)
        <script>
            function goBack() {
                // Kalau ada history (user datang dari halaman lain), balik ke sana
                if (window.history.length > 1 && document.referrer) {
                    window.history.back();
                } else {
                    // Fallback: kalau user buka langsung / refresh, balik ke beranda
                    window.location.href = '{{ route('dashboard-user') }}';
                }
            }
        </script>
    @endunless

</body>

</html>
