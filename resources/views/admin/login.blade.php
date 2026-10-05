<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('admin.partials.pwa-head')
    <title>Masuk Admin | ALNAY.ID</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #101b2d;
            --gold: #bc8523;
            --gold-light: #d4a853;
            --gold-dark: #8a6015;
            --cream: #f8f6f1;
            --line: #dfe2e6;
            --muted: #788292;
            --success: #10b981;
            --error: #ef4444;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: var(--cream);
            color: var(--ink);
            font-family: "Inter", Arial, sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ============================================
           ANIMATED BACKGROUND PARTICLES
        ============================================ */
        .bg-particles {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(188, 133, 35, 0.15) 0%, transparent 70%);
            animation: floatParticle 20s infinite ease-in-out;
        }

        .particle:nth-child(1) { width: 300px; height: 300px; top: 10%; left: 5%; animation-delay: 0s; }
        .particle:nth-child(2) { width: 200px; height: 200px; top: 60%; left: 80%; animation-delay: -5s; animation-duration: 25s; }
        .particle:nth-child(3) { width: 400px; height: 400px; top: 80%; left: 20%; animation-delay: -10s; animation-duration: 30s; }
        .particle:nth-child(4) { width: 150px; height: 150px; top: 30%; left: 70%; animation-delay: -15s; }

        @keyframes floatParticle {
            0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.3; }
            25% { transform: translate(50px, -80px) scale(1.1); opacity: 0.5; }
            50% { transform: translate(-30px, 60px) scale(0.9); opacity: 0.4; }
            75% { transform: translate(70px, 30px) scale(1.05); opacity: 0.6; }
        }

        /* ============================================
           MAIN LAYOUT
        ============================================ */
        .login-shell {
            height: 100vh; /* ← UBAH dari min-height menjadi height */
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            max-width: 1400px;
            margin: auto;
            padding: 30px 50px; /* ← KURANGI padding dari 40px 60px */
            gap: 60px; /* ← KURANGI gap dari 80px */
            align-items: center;
            position: relative;
            z-index: 1;
            box-sizing: border-box;
        }

        /* ============================================
           LEFT VISUAL PANEL
        ============================================ */
        .visual {
            height: 620px;
            border-radius: 24px;
            overflow: hidden;
            position: relative;
            background: linear-gradient(155deg, #c2943a 0%, #17243a 38%, #081424 100%);
            box-shadow: 
                0 30px 60px rgba(20, 29, 43, 0.25),
                0 0 0 1px rgba(255, 255, 255, 0.1) inset;
            opacity: 0;
            transform: translateX(-50px);
            animation: slideRight 1s ease-out 0.3s forwards;
        }

        @keyframes slideRight {
            to { opacity: 1; transform: translateX(0); }
        }

        .visual::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(160deg, transparent 20%, rgba(3, 12, 26, 0.72) 61%, #071323 88%);
            z-index: 1;
        }

        /* Brand di dalam Visual Card */
        .visual-brand {
            position: absolute;
            top: 30px;
            left: 30px;
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 10;
            opacity: 0;
            animation: fadeInDown 0.8s ease-out 0.5s forwards;
        }

        .visual-brand-logo {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(188, 133, 35, 0.4);
            overflow: hidden;
        }

        .visual-brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .visual-brand-text {
            font-family: "Playfair Display", serif;
            font-size: 24px;
            font-weight: 900;
            color: #fff;
            letter-spacing: -0.5px;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        .visual-brand-text span {
            color: var(--gold);
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Animated Spotlight */
        .spotlight {
            position: absolute;
            width: 280px;
            height: 280px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 220, 143, 0.4) 0%, transparent 70%);
            filter: blur(20px);
            top: 60px;
            left: 50%;
            transform: translateX(-50%);
            animation: pulseSpotlight 4s ease-in-out infinite;
            z-index: 2;
        }

        @keyframes pulseSpotlight {
            0%, 100% { opacity: 0.6; transform: translateX(-50%) scale(1); }
            50% { opacity: 1; transform: translateX(-50%) scale(1.15); }
        }

        /* Animated Stage */
        .stage {
            position: absolute;
            inset: 140px 40px 180px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 50% 50% 8px 8px;
            transform: perspective(500px) rotateX(14deg);
            background: repeating-linear-gradient(90deg, rgba(255, 255, 255, 0.12) 0 2px, transparent 2px 53px);
            z-index: 2;
            overflow: hidden;
        }

        .stage::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        /* Floating Elements */
        .floating-star {
            position: absolute;
            color: rgba(255, 220, 143, 0.6);
            font-size: 20px;
            animation: floatStar 6s ease-in-out infinite;
            z-index: 3;
        }

        .floating-star:nth-child(4) { top: 20%; left: 15%; animation-delay: 0s; }
        .floating-star:nth-child(5) { top: 35%; right: 20%; animation-delay: -2s; animation-duration: 7s; }
        .floating-star:nth-child(6) { bottom: 30%; left: 25%; animation-delay: -4s; animation-duration: 8s; }

        @keyframes floatStar {
            0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0.6; }
            50% { transform: translateY(-20px) rotate(180deg); opacity: 1; }
        }

        .visual-copy {
            position: absolute;
            left: 40px;
            right: 40px;
            bottom: 45px;
            color: #fff;
            z-index: 4;
        }

        .eyebrow {
            display: inline-block;
            padding: 8px 18px;
            border-radius: 20px;
            background: var(--gold);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            box-shadow: 0 4px 15px rgba(188, 133, 35, 0.4);
            animation: fadeInUp 0.8s ease-out 0.6s both;
        }

        .visual h1 {
            font: 700 38px/1.1 "Playfair Display", serif;
            margin: 24px 0 14px;
            max-width: 340px;
            animation: fadeInUp 0.8s ease-out 0.8s both;
        }

        .visual p {
            color: #c5ccd7;
            font-size: 14px;
            line-height: 1.7;
            max-width: 340px;
            margin: 0;
            animation: fadeInUp 0.8s ease-out 1s both;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ============================================
           RIGHT FORM PANEL
        ============================================ */
        .form-panel {
            max-width: 440px;
            width: 100%;
            justify-self: start;
            opacity: 0;
            transform: translateX(50px);
            animation: slideLeft 1s ease-out 0.5s forwards;
        }

        @keyframes slideLeft {
            to { opacity: 1; transform: translateX(0); }
        }

        .form-panel h2 {
            font: 800 42px "Inter", Arial, sans-serif;
            margin: 0 0 10px;
            background: linear-gradient(135deg, var(--ink) 0%, var(--gold-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .subtitle {
            color: var(--muted);
            font-size: 15px;
            margin: 0 0 40px;
            line-height: 1.6;
        }

        /* Error Message */
        .error {
            color: var(--error);
            background: linear-gradient(135deg, #fff0ee 0%, #ffe4e1 100%);
            border-left: 4px solid var(--error);
            border-radius: 8px;
            padding: 14px 16px;
            font-size: 13px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: shakeError 0.5s ease-out;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.1);
        }

        .error::before {
            content: '⚠️';
            font-size: 18px;
        }

        @keyframes shakeError {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-8px); }
            40%, 80% { transform: translateX(8px); }
        }

        /* Form Fields */
        .field {
            margin-bottom: 24px;
            position: relative;
        }

        .field label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--ink);
            transition: color 0.3s;
        }

        .field:focus-within label {
            color: var(--gold);
        }

        .field input {
            width: 100%;
            height: 52px;
            border: 2px solid var(--line);
            border-radius: 10px;
            background: #fff;
            padding: 0 18px;
            font-size: 14px;
            color: var(--ink);
            outline: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: "Inter", sans-serif;
        }

        .field input:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 4px rgba(188, 133, 35, 0.12);
            transform: translateY(-2px);
        }

        .field input::placeholder {
            color: #b0b5bc;
            transition: opacity 0.3s;
        }

        .field input:focus::placeholder {
            opacity: 0.5;
        }

        /* Password Toggle dengan Icon Mata */
        .password-wrap {
            position: relative;
        }

        .password-wrap input {
            padding-right: 55px;
        }

        .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            border: 0;
            background: none;
            color: var(--muted);
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toggle-password:hover {
            background: rgba(188, 133, 35, 0.1);
            color: var(--gold);
            transform: translateY(-50%) scale(1.1);
        }

        .toggle-password svg {
            width: 22px;
            height: 22px;
            transition: all 0.3s;
        }

        .toggle-password .eye-off {
            display: none;
        }

        .toggle-password.active .eye-on {
            display: none;
        }

        .toggle-password.active .eye-off {
            display: block;
        }

        /* Submit Button */
        .submit {
            width: 100%;
            height: 52px;
            border: 0;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
            color: white;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(188, 133, 35, 0.3);
        }

        .submit::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(188, 133, 35, 0.4);
        }

        .submit:hover::before {
            left: 100%;
        }

        .submit:active {
            transform: translateY(-1px);
        }

        .submit.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .submit.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            top: 50%;
            left: 50%;
            margin-top: -10px;
            margin-left: -10px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Footer */
        .credentials {
            margin-top: 30px;
            color: var(--muted);
            font-size: 12px;
            text-align: center;
            line-height: 1.6;
        }

        .credentials strong {
            color: var(--ink);
            font-weight: 600;
        }

        /* ============================================
           RESPONSIVE
        ============================================ */
        @media (max-width: 950px) {
            .login-shell {
                display: block;
                padding: 100px 24px 40px;
                gap: 40px;
            }

            .visual {
                height: 420px;
            }

            .visual-brand {
                top: 20px;
                left: 20px;
            }

            .visual-brand-logo {
                width: 40px;
                height: 40px;
            }

            .visual-brand-text {
                font-size: 20px;
            }

            .visual h1 {
                font-size: 32px;
            }

            .form-panel {
                max-width: none;
            }

            .form-panel h2 {
                font-size: 36px;
            }
        }

        @media (max-height: 700px) {
            .visual {
                height: 580px;
                width: 580px;
            }
            
            .login-shell {
                padding: 20px 110px;
            }
            
            .visual-brand {
                top: 20px;
                left: 20px;
            }
            
            .visual-copy {
                left: 30px;
                right: 30px;
                bottom: 35px;
            }
            
            .visual h1 {
                font-size: 32px;
                margin: 18px 0 10px;
            }
            
            .form-panel h2 {
                font-size: 36px;
            }
        }

        @media (max-width: 480px) {
            .visual {
                height: 350px;
                border-radius: 16px;
                padding: 20px;
            }

            .visual-brand {
                top: 15px;
                left: 15px;
            }

            .visual-brand-logo {
                width: 35px;
                height: 35px;
            }

            .visual-brand-text {
                font-size: 18px;
            }

            .visual h1 {
                font-size: 26px;
            }

            .visual p {
                font-size: 13px;
            }

            .form-panel h2 {
                font-size: 30px;
            }
        }
    </style>
</head>

<body>
    <!-- Animated Background Particles -->
    <div class="bg-particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <main class="login-shell">
        <!-- Left Visual Panel -->
        <section class="visual" aria-label="Alnay Event">
            <!-- Brand di dalam Visual Card -->
            <div class="visual-brand">
                <div class="visual-brand-logo">
                    <img src="{{ asset('storage/assets/logo-alnay.png') }}" alt="ALNAY.ID Logo" onerror="this.style.display='none'">
                </div>
                <div class="visual-brand-text">ALNAY<span>.ID</span></div>
            </div>

            <div class="spotlight"></div>
            <div class="stage"></div>
            
            <!-- Floating Stars -->
            <div class="floating-star">✦</div>
            <div class="floating-star">✧</div>
            <div class="floating-star">✦</div>

            <div class="visual-copy">
                <span class="eyebrow">Admin Portal</span>
                <h1>Kelola event, rayakan talenta.</h1>
                <p>Satu ruang kerja untuk mengatur pendaftaran, peserta, dan voting event ALNAY.ID.</p>
            </div>
        </section>

        <!-- Right Form Panel -->
        <section class="form-panel">
            <h2>Masuk</h2>
            <p class="subtitle">Selamat datang kembali, Admin. Silakan masuk untuk melanjutkan.</p>

            @if ($errors->any())
                <div class="error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('admin.login.store') }}" id="loginForm">
                @csrf

                <div class="field">
                    <label for="email">Email</label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        placeholder="Masukkan email admin" 
                        required 
                        autofocus
                        autocomplete="email"
                    >
                </div>

                <div class="field">
                    <label for="password">Kata Sandi</label>
                    <div class="password-wrap">
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            placeholder="Masukkan kata sandi" 
                            required
                            autocomplete="current-password"
                        >
                        <button 
                            class="toggle-password" 
                            type="button" 
                            aria-label="Tampilkan kata sandi"
                            onclick="togglePassword()"
                        >
                            <!-- Eye Open Icon -->
                            <svg class="eye-on" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <!-- Eye Closed Icon -->
                            <svg class="eye-off" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                <button class="submit" type="submit" id="submitBtn">
                    Masuk ke Dashboard
                </button>
            </form>

            <div class="credentials">
                <strong>ALNAY.ID</strong> — Platform Manajemen Event<br>
                © 2026 All rights reserved.
            </div>
        </section>
    </main>

    <script>
        // Toggle Password dengan Icon Mata
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleBtn = document.querySelector('.toggle-password');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleBtn.classList.add('active');
                toggleBtn.setAttribute('aria-label', 'Sembunyikan kata sandi');
            } else {
                passwordInput.type = 'password';
                toggleBtn.classList.remove('active');
                toggleBtn.setAttribute('aria-label', 'Tampilkan kata sandi');
            }
        }

        // Loading Animation saat Submit
        const loginForm = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');

        if (loginForm) {
            loginForm.addEventListener('submit', function() {
                submitBtn.classList.add('loading');
                submitBtn.textContent = 'Memproses...';
            });
        }

        // Input Focus Animation
        const inputs = document.querySelectorAll('.field input');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
            });
        });

        // Parallax Effect pada Visual Panel
        const visual = document.querySelector('.visual');
        if (visual && window.innerWidth > 950) {
            document.addEventListener('mousemove', (e) => {
                const x = (e.clientX / window.innerWidth - 0.5) * 20;
                const y = (e.clientY / window.innerHeight - 0.5) * 20;
                visual.style.transform = `perspective(1000px) rotateY(${x * 0.3}deg) rotateX(${-y * 0.3}deg)`;
            });
        }
    </script>
</body>

</html>