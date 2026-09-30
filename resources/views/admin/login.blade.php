<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('admin.partials.pwa-head')
    <title>Masuk Admin | ALNAY.ID</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #101b2d;
            --gold: #bc8523;
            --cream: #f8f6f1;
            --line: #dfe2e6;
            --muted: #788292
        }

        * {
            box-sizing: border-box
        }

        body {
            margin: 0;
            background: var(--cream);
            color: var(--ink);
            font-family: "Inter", Arial, sans-serif;
            min-height: 100vh
        }

        .login-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: minmax(420px, 1fr) minmax(420px, 1fr);
            max-width: 1280px;
            margin: auto;
            padding: 28px 54px;
            gap: 90px;
            align-items: center
        }

        .brand {
            position: absolute;
            padding: 0 20px;
            top: 10px;
            left: 72px;
            font-family: "Inter", Arial, sans-serif;
            font-size: 25px;
            font-weight: 700;
            letter-spacing: -1.5px
        }

        .brand span {
            color: var(--gold)
        }

        .visual {
            height: 560px;
            border-radius: 15px;
            overflow: hidden;
            position: relative;
            background: linear-gradient(155deg, #c2943a 0%, #17243a 38%, #081424 100%);
            box-shadow: 0 22px 35px rgba(20, 29, 43, .18)
        }

        .visual:before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(160deg, transparent 20%, rgba(3, 12, 26, .72) 61%, #071323 88%)
        }

        .spotlight {
            position: absolute;
            width: 240px;
            height: 240px;
            border-radius: 50%;
            background: rgba(255, 220, 143, .32);
            filter: blur(5px);
            top: 48px;
            left: 50%;
            transform: translateX(-50%)
        }

        .stage {
            position: absolute;
            inset: 120px 38px 160px;
            border: 1px solid rgba(255, 255, 255, .3);
            border-radius: 50% 50% 8px 8px;
            transform: perspective(500px) rotateX(14deg);
            background: repeating-linear-gradient(90deg, rgba(255, 255, 255, .12) 0 2px, transparent 2px 53px)
        }

        .visual-copy {
            position: absolute;
            left: 34px;
            right: 34px;
            bottom: 35px;
            color: #fff
        }

        .eyebrow {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 20px;
            background: var(--gold);
            font-size: 11px;
            font-weight: bold;
            letter-spacing: .6px;
            text-transform: uppercase
        }

        .visual h1 {
            font: 700 34px/1.04 "Inter", Arial, sans-serif;
            margin: 22px 0 12px;
            max-width: 320px
        }

        .visual p {
            color: #c5ccd7;
            font-size: 14px;
            line-height: 1.65;
            max-width: 330px;
            margin: 0
        }

        .form-panel {
            max-width: 420px;
            width: 100%;
            justify-self: start
        }

        .form-panel h2 {
            font: 700 34px "Inter", Arial, sans-serif;
            margin: 0 0 8px
        }

        .subtitle {
            color: var(--muted);
            font-size: 14px;
            margin: 0 0 34px
        }

        .field {
            margin-bottom: 21px
        }

        .field label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 9px
        }

        .field input {
            width: 100%;
            height: 49px;
            border: 1px solid var(--line);
            border-radius: 6px;
            background: #fff;
            padding: 0 16px;
            font-size: 14px;
            color: var(--ink);
            outline: none
        }

        .field input:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(188, 133, 35, .12)
        }

        .password-wrap {
            position: relative
        }

        .password-wrap input {
            padding-right: 50px
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 14px;
            border: 0;
            background: none;
            color: #748093;
            cursor: pointer;
            font-size: 16px
        }

        .forgot {
            display: block;
            color: var(--gold);
            font-size: 13px;
            text-decoration: none;
            margin: 5px 0 28px
        }

        .submit {
            width: 100%;
            height: 47px;
            border: 0;
            border-radius: 6px;
            background: var(--gold);
            color: white;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: .2s
        }

        .submit:hover {
            background: #a8731d;
            transform: translateY(-1px)
        }

        .error {
            color: #b42318;
            background: #fff0ee;
            border: 1px solid #f2c5bf;
            border-radius: 6px;
            padding: 11px 13px;
            font-size: 13px;
            margin-bottom: 18px
        }

        .credentials {
            margin-top: 25px;
            color: #99a0ab;
            font-size: 12px;
            text-align: center
        }

        .credentials strong {
            color: #5c6471;
            font-weight: 600
        }

        @media(max-width:850px) {
            .login-shell {
                display: block;
                padding: 100px 24px 30px
            }

            .brand {
                left: 24px
            }

            .visual {
                height: 390px;
                margin-bottom: 45px
            }

            .visual h1 {
                font-size: 29px
            }

            .form-panel {
                max-width: none
            }
        }

        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: Inter, Arial, sans-serif
        }
    </style>
</head>

<body>
    <div class="brand">ALNAY<span>.ID</span></div>
    <main class="login-shell">
        <section class="visual" aria-label="Alnay Event">
            <div class="spotlight"></div>
            <div class="stage"></div>
            <div class="visual-copy"><span class="eyebrow">Admin portal</span>
                <h1>Kelola event, rayakan talenta.</h1>
                <p>Satu ruang kerja untuk mengatur pendaftaran, peserta, dan voting event ALNAY.ID.</p>
            </div>
        </section>
        <section class="form-panel">
            <h2>Masuk</h2>
            <p class="subtitle">Selamat datang kembali, Admin.</p>
            @if ($errors->any())
                <div class="error">{{ $errors->first() }}</div>
            @endif
            <form method="POST" action="{{ route('admin.login.store') }}">
                @csrf
                <div class="field"><label for="email">Email</label><input id="email" type="email"
                        name="email" value="{{ old('email') }}" placeholder="Masukkan email admin" required
                        autofocus></div>
                <div class="field"><label for="password">Kata Sandi</label>
                    <div class="password-wrap"><input id="password" type="password" name="password"
                            placeholder="Masukkan kata sandi" required><button class="toggle-password" type="button"
                            aria-label="Tampilkan kata sandi" onclick="togglePassword()">&#9673;</button></div>
                </div>
                <button class="submit" type="submit">Masuk ke Dashboard</button>
            </form>
        </section>
    </main>
    <script>
        function togglePassword() {
            const i = document.getElementById('password');
            i.type = i.type === 'password' ? 'text' : 'password'
        }
    </script>
</body>

</html>
