<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Event AL-NAY.ID</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
        }

        body {
            font-family: 'Inter', Arial, sans-serif;
        }

        .font-display {
            font-family: 'Playfair Display', serif;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(24px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes floatPattern {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-12px) rotate(2deg);
            }
        }

        .anim-left {
            animation: slideInLeft 0.9s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        .anim-right {
            animation: slideInRight 0.9s cubic-bezier(0.16, 1, 0.3, 1) 0.15s both;
        }

        .anim-up {
            animation: fadeInUp 0.8s ease-out 0.5s both;
        }

        .anim-float {
            animation: floatPattern 8s ease-in-out infinite;
        }
    </style>
</head>

<body class="min-h-screen bg-[#FBF9F5]">

    <div class="min-h-screen flex items-center justify-center p-4 md:p-8">
        <div
            class="w-full max-w-[1080px] bg-white rounded-xl shadow-2xl overflow-hidden grid grid-cols-1 md:grid-cols-2 md:min-h-[600px]">
            {{-- PANEL KIRI — DARK --}}
            <div
                class="relative bg-[#0A1526] p-6 md:p-12 flex flex-col justify-between overflow-hidden anim-left min-h-[260px] md:min-h-0">

                <div class="absolute -top-20 -right-20 w-64 h-64 rounded-full border border-[#B8862B]/20 anim-float">
                </div>
                <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full border border-[#B8862B]/30 anim-float"
                    style="animation-delay: -2s;"></div>

                <div class="relative z-10">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/alnaylogo.png') }}" alt="ALNAY.ID" class="w-12 h-12 object-contain">
                        <div>
                            <p class="font-display font-bold text-[18px] tracking-[0.1em] text-[#B8862B]">ALNAY.ID</p>
                            <p class="text-[11px] text-[#8a8178] tracking-wide">Jas & gown berbudaya</p>
                        </div>
                    </div>
                </div>

                    {{-- Logo background --}}
                <div class="absolute inset-y-0 left-0 w-[75%] flex items-center justify-start pointer-events-none">
                    {{-- Wrapper: atur posisi + tinggi --}}
                    <div class="h-full" style="transform: translateX(-30%);">
                        {{-- Image: animasi float --}}
                        <img src="{{ asset('images/alnaylogo.png') }}" alt=""
                            class="h-[80%] md:h-[85%] w-auto max-w-none object-contain anim-float"
                            style="
                                opacity: 0.12;
                                filter: blur(4px) saturate(1.1);
                            ">
                    </div>
                </div>

                {{-- Konten atas & bawah di atas logo --}}
                <div class="relative z-10 flex flex-col justify-between h-full">
                    {{-- Konten atas & bawah tetap di sini --}}
                </div>

                <div class="relative z-10 anim-up">
                    <h2 class="font-display font-bold text-[24px] md:text-[54px] leading-tight text-white mb-3">
                        MUDA<br>GAYA<br>BERBUDAYA
                    </h2>
                    <p class="text-[13px] leading-[20px] text-[#8a8178]">
                        Masuk untuk lihat riwayat sewa, transaksi, dan event yang kamu ikuti.
                    </p>
                </div>
            </div>

            {{-- PANEL KANAN — LIGHT --}}
            <div class="p-6 md:p-12 flex flex-col justify-center bg-white anim-right">

                <div class="max-w-[340px] mx-auto w-full">

                    <h1 class="font-display font-bold text-[32px] leading-tight text-[#0C1A30] mb-2">
                        Masuk
                    </h1>
                    <p class="text-[14px] text-[#64748B] mb-8">
                        Masuk dengan akun Google Anda untuk melanjutkan.
                    </p>

                    <a href="{{ url('/auth/google') }}"
                        class="group w-full h-12 flex items-center justify-center gap-3 bg-white border-2 border-[#E2E8F0] rounded-md shadow-sm hover:border-[#B8862B] hover:shadow-md transition-all duration-300">
                        <svg class="w-5 h-5 transition-transform group-hover:scale-110" viewBox="0 0 24 24">
                            <path fill="#4285F4"
                                d="M23.49 12.27c0-.79-.07-1.54-.19-2.27H12v4.51h6.47c-.29 1.48-1.14 2.73-2.4 3.58v3h3.88c2.26-2.09 3.54-5.17 3.54-8.82z" />
                            <path fill="#34A853"
                                d="M12 24c3.24 0 5.95-1.08 7.94-2.91l-3.88-3c-1.08.72-2.45 1.16-4.06 1.16-3.13 0-5.78-2.11-6.73-4.96H1.27v3.09C3.25 21.3 7.31 24 12 24z" />
                            <path fill="#FBBC05"
                                d="M5.27 14.29c-.25-.72-.38-1.49-.38-2.29s.14-1.57.38-2.29V6.62H1.27A11.98 11.98 0 0 0 0 12c0 1.93.46 3.76 1.27 5.38l4-3.09z" />
                            <path fill="#EA4335"
                                d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.44-3.44C17.95 1.19 15.24 0 12 0 7.31 0 3.25 2.7 1.27 6.62l4 3.09C6.22 6.86 8.87 4.75 12 4.75z" />
                        </svg>
                        <span class="text-[14px] font-bold text-[#1E293B] group-hover:text-[#B8862B] transition-colors">
                            Masuk dengan Google
                        </span>
                    </a>

                    <div class="rounded-md bg-[#faf8f2] border border-[#eee5d2] p-4 mt-6">
                        <div class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-[#B8862B] shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-[11px] text-[#7F766C] leading-relaxed">
                                Gunakan akun Google aktif Anda. Dengan masuk, Anda menyetujui
                                <a href="#" class="text-[#B8862B] font-semibold hover:underline">Syarat &amp;
                                    Ketentuan</a>.
                            </p>
                        </div>
                    </div>

                    <p class="text-center text-[12px] text-[#94A3B8] mt-8">
                        © 2026 <span class="font-semibold text-[#B8862B]">ALNAY.ID</span>
                    </p>
                </div>

            </div>

        </div>
    </div>

</body>

</html>
