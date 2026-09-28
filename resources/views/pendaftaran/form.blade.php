<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Daftar — {{ $event->nama }} | ALNAY.ID</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --gold:#c38b22; --cream:#f7f6f3; --ink:#22201d; --line:#e5e1db }
        * { box-sizing: border-box }
        body { margin:0; background:var(--cream); color:var(--ink); font:15px Inter, Arial, sans-serif }
        .wrap { max-width: 640px; margin: 0 auto; padding: 30px 20px 80px }
        .header { background:white; border:1px solid var(--line); border-top:4px solid var(--gold); border-radius:12px; padding:24px 20px; margin-bottom:16px; box-shadow:0 3px 10px #3a302211 }
        .header h1 { font:700 22px Georgia, serif; margin:0 0 8px }
        .header p { margin:0; color:#73716c; font-size:12px }
        .field { background:white; border:1px solid var(--line); border-radius:12px; padding:18px; margin-bottom:12px }
        .field label { display:block; font-weight:bold; font-size:14px; margin-bottom:4px }
        .field label .req { color:#e26855 }
        .field .help { font-size:10px; color:#a09d96; margin-bottom:10px }
        .field input[type=text], .field input[type=email], .field input[type=number],
        .field input[type=file], .field textarea, .field select {
            width:100%; border:1px solid var(--line); border-radius:6px; padding:10px; font:inherit; font-size:12px
        }
        .field textarea { min-height:80px; resize:vertical }
        .choice-row { display:flex; align-items:center; gap:8px; font-size:12px; margin-bottom:6px }
        .field .error { color:#c1442f; font-size:10px; margin-top:6px; display:none }
        .field.has-error { border-color:#e0938a }
        .field.has-error .error { display:block }

        .login-card { background:white; border:1px solid var(--line); border-radius:12px; padding:30px 20px; text-align:center }
        .login-card p { color:#73716c; font-size:12px; margin-bottom:16px }
        .btn-google { display:inline-flex; align-items:center; gap:10px; border:1px solid var(--line); border-radius:6px; padding:10px 18px; background:white; text-decoration:none; color:var(--ink); font-size:12px; font-weight:600 }

        .btn-submit { width:100%; border:0; border-radius:6px; padding:13px; background:var(--gold); color:white; font-weight:bold; font-size:13px; cursor:pointer }
        .btn-submit:disabled { opacity:.6; cursor:wait }

        .banner { padding:12px 16px; border-radius:8px; font-size:12px; margin-bottom:16px }
        .banner.success { background:#e5f6ee; color:#0a6b4f }
        .banner.error { background:#fdeceb; color:#a3382a }
        .hidden { display:none }
        .userbar { display:flex; align-items:center; justify-content:space-between; background:white; border:1px solid var(--line); border-radius:10px; padding:10px 14px; margin-bottom:16px; font-size:11px; color:#73716c }
        .userbar button { border:0; background:none; color:#c1442f; cursor:pointer; font-size:11px }
    </style>
</head>
<body>
    <div class="wrap">


        <div id="banner" class="banner hidden"></div>

        {{-- Ditampilkan kalau BELUM login --}}
        <div id="loginCard" class="login-card hidden">
            <p>Login dengan Google untuk melanjutkan pendaftaran.</p>
            <a href="#" id="btnGoogleLogin" class="btn-google">🔒 Masuk dengan Google</a>
        </div>

        {{-- Ditampilkan kalau SUDAH login --}}
        <div id="userBar" class="userbar hidden">
            <span id="userName"></span>
            <button type="button" id="btnLogout">Keluar</button>
        </div>

        <form id="formPendaftaran" class="hidden">
            <div id="fieldsContainer"></div>
            <button type="submit" class="btn-submit" id="btnSubmit">Daftar Sekarang</button>
        </form>

    </div>

    <script>
        const eventId = {{ $event->id }};
        const API_BASE = ''; // isi kalau API di domain/port beda, misal 'http://localhost:8000'
        const TOKEN_KEY = 'pelangganToken';
        const banner = document.getElementById('banner');
        const loginCard = document.getElementById('loginCard');
        const userBar = document.getElementById('userBar');
        const userName = document.getElementById('userName');
        const form = document.getElementById('formPendaftaran');
        const fieldsContainer = document.getElementById('fieldsContainer');
        const btnSubmit = document.getElementById('btnSubmit');

        form.noValidate = true;

        let pertanyaanList = [];

        function showBanner(message, type = 'error') {
            banner.textContent = message;
            banner.className = 'banner ' + type;
        }

        function getToken() {
            return localStorage.getItem(TOKEN_KEY);
        }

        function setToken(token) {
            localStorage.setItem(TOKEN_KEY, token);
        }

        function clearToken() {
            localStorage.removeItem(TOKEN_KEY);
        }

        /*
        |--------------------------------------------------------------------------
        | Tangkap token dari redirect Google OAuth (?token=xxx di URL)
        |--------------------------------------------------------------------------
        */
        (function captureTokenFromUrl() {
            const params = new URLSearchParams(window.location.search);
            const token = params.get('token');
            if (token) {
                setToken(token);
                params.delete('token');
                const cleanUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
                window.history.replaceState({}, '', cleanUrl);
            }
        })();

        /*
        |--------------------------------------------------------------------------
        | Render field sesuai tipe_input
        |--------------------------------------------------------------------------
        */
        function renderFields(list) {
            fieldsContainer.innerHTML = '';

            list.forEach(item => {
                if (item.tipe_input === 'heading') {
                    fieldsContainer.insertAdjacentHTML('beforeend', `
                        <div class="field" style="border-top:3px solid var(--gold)">
                            <h2 style="margin:0;font:700 20px Georgia, serif">${escapeHTML(item.label)}</h2>
                        </div>
                    `);
                    return;
                }

                if (item.tipe_input === 'subheading') {
                    fieldsContainer.insertAdjacentHTML('beforeend', `
                        <div class="field"><p style="margin:0;color:#6f6b64">${escapeHTML(item.label)}</p></div>
                    `);
                    return;
                }

                const wajib = item.wajib ? '<span class="req"> *</span>' : '';
                const help = item.konfigurasi?.help
                    ? `<div class="help">${escapeHTML(item.konfigurasi.help)}</div>` : '';
                const placeholder = escapeHTML(item.konfigurasi?.placeholder || item.placeholder || '');

                // PERBAIKAN: Ambil pilihan jawaban dari 'options' ATAU 'pilihan_jawaban'
                const rawOptions = item.options || item.pilihan_jawaban || item.konfigurasi?.options || [];
                const choices = Array.isArray(rawOptions) ? rawOptions : (typeof rawOptions === 'string' ? JSON.parse(rawOptions) : []);

                let inputHtml = '';

                // PERBAIKAN: Gunakan format name="answers[ID]" agar sesuai dengan Request/Validation Controller
                const fieldName = `answers[${item.id}]`; 

                if (['text', 'email', 'number'].includes(item.tipe_input)) {
                    inputHtml = `<input type="${item.tipe_input}" name="${fieldName}" placeholder="${placeholder}">`;
                } else if (item.tipe_input === 'textarea') {
                    inputHtml = `<textarea name="${fieldName}" placeholder="${placeholder}"></textarea>`;
                } 
                // PERBAIKAN: Cek 'dropdown' DAN 'select'
                else if (['select', 'dropdown'].includes(item.tipe_input)) {
                    const opts = choices
                        .map(o => `<option value="${escapeAttribute(o)}">${escapeHTML(o)}</option>`)
                        .join('');
                    inputHtml = `<select name="${fieldName}"><option value="">Pilih salah satu</option>${opts}</select>`;
                } else if (item.tipe_input === 'radio') {
                    inputHtml = choices.map((o) => `
                        <label class="choice-row">
                            <input type="radio"
                                name="${fieldName}"
                                value="${escapeAttribute(o)}">
                            ${escapeHTML(o)}
                        </label>
                    `).join('');
                } else if (item.tipe_input === 'checkbox') {
                    inputHtml = choices.map((o) => `
                        <label class="choice-row">
                            <input type="checkbox" name="${fieldName}[]" value="${escapeAttribute(o)}">
                            ${escapeHTML(o)}
                        </label>
                    `).join('');
                } else if (item.tipe_input === 'file') {
                    inputHtml = `<input type="file" name="${fieldName}">`;
                } else {
                    inputHtml = `<input type="text" name="${fieldName}" placeholder="${placeholder}">`;
                }

                fieldsContainer.insertAdjacentHTML('beforeend', `
                    <div class="field" data-pertanyaan-id="${item.id}" data-wajib="${item.wajib ? 'true' : 'false'}">
                        <label>${escapeHTML(item.label)}${wajib}</label>
                        ${help}
                        ${inputHtml}
                        <div class="error">Pertanyaan ini wajib diisi.</div>
                    </div>
                `);
            });
        }

        function escapeHTML(value) {
            const div = document.createElement('div');
            div.textContent = value ?? '';
            return div.innerHTML;
        }

        function escapeAttribute(value) {
            return escapeHTML(value ?? '').replace(/"/g, '&quot;');
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil daftar pertanyaan (public, gak butuh login)
        |--------------------------------------------------------------------------
        */
        async function loadPertanyaan() {
            try {
                const res = await fetch(`${API_BASE}/event/${eventId}/pertanyaan`, {
                    headers: { Accept: 'application/json' }
                });
                if (!res.ok) throw new Error('Gagal memuat form pendaftaran.');
                pertanyaanList = await res.json();
                renderFields(pertanyaanList);
            } catch (error) {
                showBanner(error.message, 'error');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Cek status login (GET /auth/me)
        |--------------------------------------------------------------------------
        */
        async function refreshAuthState() {
            const token = getToken();

            if (!token) {
                loginCard.classList.remove('hidden');
                userBar.classList.add('hidden');
                form.classList.add('hidden');
                return;
            }

            try {
                const res = await fetch(`${API_BASE}/auth/me`, {
                    headers: { Accept: 'application/json', Authorization: `Bearer ${token}` }
                });

                if (!res.ok) throw new Error('Sesi login habis, silakan login lagi.');

                const user = await res.json();
                userName.textContent = `Masuk sebagai ${user.nama}`;
                loginCard.classList.add('hidden');
                userBar.classList.remove('hidden');
                form.classList.remove('hidden');

            } catch (error) {
                clearToken();
                loginCard.classList.remove('hidden');
                userBar.classList.add('hidden');
                form.classList.add('hidden');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Login Google — redirect balik ke halaman ini setelah sukses
        |--------------------------------------------------------------------------
        */
        document.getElementById('btnGoogleLogin').addEventListener('click', function (event) {
            event.preventDefault();
            // Backend GoogleAuthController::callback redirect ke
            // services.google.frontend_redirect + '?token=...'. Pastikan
            // GOOGLE_FRONTEND_REDIRECT di .env diarahkan ke halaman ini,
            // atau tempel redirect_to di sini kalau backend mendukungnya.
            window.location.href = `${API_BASE}/auth/google`;
        });

        document.getElementById('btnLogout').addEventListener('click', async function () {
            const token = getToken();
            clearToken();
            try {
                await fetch(`${API_BASE}/auth/logout`, {
                    method: 'POST',
                    headers: { Accept: 'application/json', Authorization: `Bearer ${token}` }
                });
            } catch (error) {
                // Diamkan — token sudah dihapus di sisi client, cukup.
            }
            refreshAuthState();
        });

        /*
        |--------------------------------------------------------------------------
        | Submit pendaftaran
        |--------------------------------------------------------------------------
        */
        /*
        |--------------------------------------------------------------------------
        | Submit pendaftaran (Diperbaiki)
        |--------------------------------------------------------------------------
        */
        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            form.noValidate = true;

            const token = getToken();
            if (!token) {
                showBanner('Silakan login dulu sebelum mendaftar.', 'error');
                return;
            }

            let valid = true;
            const formData = new FormData();

            fieldsContainer.querySelectorAll('.field[data-pertanyaan-id]').forEach(fieldEl => {
                const pertanyaanId = fieldEl.dataset.pertanyaanId;
                const wajib = fieldEl.dataset.wajib === 'true';
                const errorEl = fieldEl.querySelector('.error');

                let value = null;
                const checkboxes = fieldEl.querySelectorAll('input[type=checkbox]:checked');
                const radio = fieldEl.querySelector('input[type=radio]:checked');
                const fileInput = fieldEl.querySelector('input[type=file]');
                const normalInput = fieldEl.querySelector('input[type=text], input[type=email], input[type=number], textarea, select');

                if (checkboxes.length) {
                    value = [...checkboxes].map(c => c.value).join(', ');
                    formData.append(`answers[${pertanyaanId}]`, value);
                } else if (radio) {
                    value = radio.value;
                    formData.append(`answers[${pertanyaanId}]`, value);
                } else if (fileInput) {
                    if (fileInput.files[0]) {
                        value = fileInput.files[0].name;
                        formData.append(`answers[${pertanyaanId}]`, fileInput.files[0]);
                    }
                } else if (normalInput) {
                    value = normalInput.value.trim();
                    formData.append(`answers[${pertanyaanId}]`, value);
                }

                if (wajib && (!value || value === '')) {
                    fieldEl.classList.add('has-error');
                    if (errorEl) {
                        errorEl.textContent = 'Pilih atau isi pertanyaan ini.';
                    }
                    valid = false;
                } else {
                    fieldEl.classList.remove('has-error');
                    if (errorEl) {
                        errorEl.textContent = 'Pertanyaan ini wajib diisi.';
                    }
                }
            });

            if (!valid) {
                showBanner('Ada pertanyaan wajib yang belum diisi.', 'error');
                return;
            }

            btnSubmit.disabled = true;
            btnSubmit.textContent = 'Mendaftar...';

            try {
                const res = await fetch(`${API_BASE}/event/${eventId}/daftar`, {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        Authorization: `Bearer ${token}`
                    },
                    body: formData
                });

                const data = await res.json().catch(() => ({}));

                if (!res.ok) {
                    throw new Error(data.message || 'Pendaftaran gagal, coba lagi.');
                }

                showBanner('Pendaftaran berhasil! Data Anda telah dikirim untuk direview.', 'success');
                form.reset();

            } catch (error) {
                showBanner(error.message, 'error');
            } finally {
                btnSubmit.disabled = false;
                btnSubmit.textContent = 'Daftar Sekarang';
            }
        });

        loadPertanyaan();
        refreshAuthState();
    </script>
</body>
</html>