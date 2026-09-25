<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Event AL-NAY.ID</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="h-screen overflow-hidden bg-[#FBF9F5]">
    <div class="max-w-[1280px] mx-auto flex flex-col h-full">

        <!-- Header -->
        <header class="flex items-center justify-between px-16 py-6">
            <span class="font-display font-bold text-[26px] tracking-[-0.65px] text-[#0E1828]">
                EVENT ALNAY.ID
            </span>
            <div class="flex items-center gap-3">
                <a href="{{ url('/login') }}"
                   class="px-7 py-2 rounded-md bg-[#B8862B] text-white text-sm font-semibold shadow-sm">
                    Masuk
                </a>
                <a href="{{ url('/login') }}"
                   class="px-7 py-2 rounded-md border border-[#B8862B] text-[#B8862B] text-sm font-semibold">
                    Daftar
                </a>
            </div>
        </header>

        <!-- Main content -->
        <div class="flex-1 flex items-center justify-center px-6 h-full">
            <div class="flex items-stretch gap-16 max-w-[1040px] w-full justify-center">

                <!-- Kartu kiri -->
                <div class="w-[430px] bg-[#0A1526] rounded-2xl shadow-xl flex flex-col justify-end p-8 shrink-0 overflow-auto" style="min-height:480px; max-height:calc(100vh - 160px);">
                    <span class="inline-block w-fit mb-4 px-3.5 py-1 rounded-full bg-[#B8862B] text-[11px] font-bold uppercase tracking-[0.55px] text-white">
                        Duta Budaya 2026
                    </span>
                    <h2 class="font-display font-bold text-[30px] leading-[38px] tracking-[0.75px] text-white mb-4">
                        Dukung Kandidat<br>Favoritmu
                    </h2>
                    <p class="text-[13px] leading-[21px] text-[#D1D5DB]">
                        Masuk untuk memberikan suara dan ikut menentukan siapa yang akan membawa warisan budaya Nusantara ke kancah global.
                    </p>
                </div>

                <!-- Form kanan -->
                <div class="w-[420px] flex flex-col items-center justify-center px-6" style="min-height:480px; max-height:calc(100vh - 160px);">
                    <h1 class="font-bold text-[34px] leading-[36px] tracking-[-0.85px] text-[#0C1A30] mb-2 teZzZxt-center">
                        Masuk
                    </h1>
                    <p class="text-sm text-[#64748B] text-center mb-7">
                    </p>

                    <a href="{{ url('/auth/google') }}"
                       class="w-full h-12 flex items-center justify-center gap-3 bg-white border border-[#E2E8F0] rounded-md shadow-sm hover:bg-gray-50 transition">
                        <svg class="w-4 h-4" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M23.49 12.27c0-.79-.07-1.54-.19-2.27H12v4.51h6.47c-.29 1.48-1.14 2.73-2.4 3.58v3h3.88c2.26-2.09 3.54-5.17 3.54-8.82z"/>
                            <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.94-2.91l-3.88-3c-1.08.72-2.45 1.16-4.06 1.16-3.13 0-5.78-2.11-6.73-4.96H1.27v3.09C3.25 21.3 7.31 24 12 24z"/>
                            <path fill="#FBBC05" d="M5.27 14.29c-.25-.72-.38-1.49-.38-2.29s.14-1.57.38-2.29V6.62H1.27A11.98 11.98 0 0 0 0 12c0 1.93.46 3.76 1.27 5.38l4-3.09z"/>
                            <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.44-3.44C17.95 1.19 15.24 0 12 0 7.31 0 3.25 2.7 1.27 6.62l4 3.09C6.22 6.86 8.87 4.75 12 4.75z"/>
                        </svg>
                        <span class="text-sm font-bold text-[#1E293B]">Masuk dengan Google</span>
                    </a>

                    <p class="text-xs text-[#475569] text-center mt-7">
                        Dengan masuk, Anda menyetujui
                        <a href="#" class="text-[#C08929] font-medium">Syarat &amp; Ketentuan Voting</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>