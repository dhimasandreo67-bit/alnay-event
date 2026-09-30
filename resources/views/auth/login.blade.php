<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Event AL-NAY.ID</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html, body { height: 100%; }
        body { font-family: 'Inter', Arial, sans-serif; }
        .font-display { font-family: 'Inter', Arial, sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-[#FBF9F5]">
    <div class="max-w-[1280px] mx-auto flex flex-col h-full">

        <!-- Header -->
        <header class="flex items-center px-6 md:px-16 py-4 shrink-0">
            <a href="{{ url('/') }}" class="font-display font-bold text-[22px] tracking-[-0.65px] text-[#0E1828]">
                EVENT ALNAY.ID
            </a>
        </header>

        <!-- Main content -->
        <div class="flex-1 flex items-center justify-center px-6 py-6">
            <div class="flex flex-col md:flex-row items-stretch gap-6 md:gap-14 max-w-[1040px] w-full md:h-[clamp(420px,75vh,520px)] justify-center">

                <!-- Kartu kiri -->
                <div class="relative overflow-hidden w-full md:w-[420px] bg-[#0A1526] rounded-2xl shadow-xl flex flex-col justify-end p-6 md:p-8 md:shrink-0 min-h-[220px] md:h-full">
                    <!-- Hiasan -->
                    <div class="absolute -top-16 -right-16 w-56 h-56 rounded-full border border-[#B8862B]/30"></div>
                    <div class="absolute -top-8 -right-8 w-36 h-36 rounded-full border border-[#B8862B]/40"></div>

                    <span class="relative inline-block w-fit mb-3 px-3.5 py-1 rounded-full bg-[#B8862B] text-[11px] font-bold uppercase tracking-[0.55px] text-white">
                        Duta Budaya 2026
                    </span>
                    <h2 class="relative font-display font-bold text-[26px] leading-[32px] tracking-[0.5px] text-white mb-3">
                        Dukung Kandidat<br>Favoritmu
                    </h2>
                    <p class="relative text-[13px] leading-[20px] text-[#D1D5DB]">
                        Masuk untuk memberikan suara dan ikut menentukan siapa yang akan membawa warisan budaya Nusantara ke kancah global.
                    </p>
                </div>

                <!-- Form kanan -->
                <div class="w-full md:w-[400px] flex flex-col items-center justify-center px-2 md:px-6 md:h-full">
                    <h1 class="font-display font-bold text-[30px] leading-[34px] tracking-[-0.7px] text-[#0C1A30] mb-2 text-center">
                        Masuk
                    </h1>
                    <p class="text-sm text-[#64748B] text-center mb-6">
                        Masuk dengan dengan akun google anda
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

                    <p class="text-xs text-[#475569] text-center mt-6">
                        Dengan masuk, Anda menyetujui
                        <a href="#" class="text-[#C08929] font-medium">Syarat &amp; Ketentuan Voting</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>