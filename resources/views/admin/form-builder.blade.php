<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Form Builder | ALNAY.ID</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin-sidebar.css') }}">
    <style>
        :root {
            --ink: #22201d;
            --dark: #171512;
            --gold: #c38b22;
            --cream: #f7f6f3;
            --line: #e5e1db;
            --muted: #8a8984;
            --green: #089b73
        }

        * {
            box-sizing: border-box;
            font-family: "Inter", Arial, sans-serif
        }

        body {
            margin: 0;
            background: var(--cream);
            color: var(--ink);
            font: 15px "Inter", Arial, sans-serif
        }

        .shell {
            display: flex;
            min-height: 100vh
        }

        .side {
            width: 220px;
            background: var(--dark);
            color: #aaa59c;
            padding: 18px 10px;
            flex: none
        }

        .brand {
            font: 700 22px "Inter", Arial, sans-serif;
            color: #fff;
            padding: 4px 12px 30px
        }

        .brand span {
            color: #cc962f
        }

        .section {
            font-size: 10px;
            letter-spacing: .7px;
            text-transform: uppercase;
            color: #777169;
            margin: 0 12px 12px
        }

        .nav a {
            display: flex;
            gap: 11px;
            align-items: center;
            padding: 12px 11px;
            border-radius: 8px;
            color: #a7a29a;
            text-decoration: none;
            line-height: 1.3
        }

        .nav a.active {
            background: #302b25;
            color: white
        }

        .step {
            width: 21px;
            height: 21px;
            border-radius: 50%;
            background: #332e28;
            display: grid;
            place-items: center;
            font-size: 11px;
            flex: none
        }

        .active .step {
            background: var(--gold);
            color: white
        }

        .badge {
            margin-left: auto;
            background: #d94d43;
            color: white;
            border-radius: 12px;
            padding: 3px 7px;
            font-size: 10px
        }

        .user {
            position: absolute;
            bottom: 20px;
            left: 10px;
            width: 200px;
            background: #29251f;
            border-radius: 9px;
            padding: 11px;
            display: flex;
            gap: 11px;
            align-items: center;
            color: #fff;
            font-size: 12px
        }

        .avatar {
            width: 33px;
            height: 33px;
            border-radius: 50%;
            background: #f4f0e7;
            color: #4d4435;
            display: grid;
            place-items: center;
            font-weight: bold
        }

        .user small {
            display: block;
            color: #aaa59e;
            font-size: 10px;
            margin-top: 4px
        }

        .workspace {
            min-width: 0;
            flex: 1
        }

        .top {
            height: 115px;
            background: #201f1d;
            color: #fff;
            padding: 16px 22px 14px;
            position: relative
        }

        .crumb {
            color: #aaa59e;
            font-size: 11px
        }

        .crumb b {
            color: #d7a43c;
            font-weight: normal
        }

        .title-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 9px
        }

        .title-icon {
            width: 33px;
            height: 33px;
            background: #f3eee3;
            color: #37322b;
            border-radius: 7px;
            display: grid;
            place-items: center;
            font-weight: bold
        }

        .top h1 {
            font-size: 17px;
            margin: 0
        }

        .top p {
            margin: 3px 0 0;
            color: #b6b1aa;
            font-size: 11px
        }

        .top-actions {
            display: flex;
            gap: 10px;
            margin-top: 12px
        }

        .top-actions a,
        .top-actions button {
            border: 1px solid #57534e;
            border-radius: 5px;
            padding: 9px 14px;
            background: #2d2b28;
            color: #fff;
            font-size: 11px;
            text-decoration: none
        }

        .top-actions .save {
            background: var(--gold);
            border-color: var(--gold)
        }

        .builder {
            display: grid;
            grid-template-columns: 220px minmax(420px, 1fr);
            min-height: calc(100vh - 115px)
        }

        .library {
            background: #faf9f7;
            border-right: 1px solid var(--line);
            padding: 18px 12px
        }

        .pane-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-transform: uppercase;
            letter-spacing: .8px;
            font-size: 10px;
            color: #797670;
            margin: 0 6px 14px
        }

        .count {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 11px;
            padding: 4px 9px;
            color: var(--gold);
            font-size: 10px;
            text-transform: none;
            letter-spacing: 0
        }

        .field-item {
            background: #fff;
            border: 1px solid #e1ddd6;
            border-radius: 9px;
            padding: 13px 11px;
            margin-bottom: 9px;
            cursor: grab;
            box-shadow: 0 1px 2px #00000005
        }

        .field-item:hover {
            border-color: #d0a047;
            transform: translateX(2px)
        }

        .drag {
            color: #aaa;
            font-size: 17px;
            margin-right: 9px
        }

        .field-item strong {
            font-size: 12px
        }

        .field-item small {
            display: block;
            color: #98958f;
            font-size: 10px;
            margin: 5px 0 0 26px
        }

        .canvas {
            padding: 60px 30px 70px;
            overflow-y: auto;
            overflow-x: hidden;
            background: #f5f4f1;
            min-width: 0;
        }

        .canvas-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #8e8b84;
            font-size: 11px;
            margin-bottom: 18px
        }

        .question {
            background: white;
            border: 1px solid #e3ded6;
            border-radius: 12px;
            box-shadow: 0 3px 10px #3a302211;
            padding: 18px;
            margin-bottom: 13px;
            position: relative
        }

        .question.selected {
            border-color: #c99532;
            box-shadow: 0 0 0 2px #dcb96d55
        }

        .question-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
        }

        .question-label {
            font-size: 14px;
            font-weight: bold;
            outline: none;
            cursor: text
        }

        .question-head > div:first-child {
            min-width: 0;
            flex: 1;
        }

        .question-label:focus {
            background: #fffdf6;
            border-radius: 5px;
            box-shadow: 0 0 0 2px #ecd9a6
        }

        .question-label:empty:before {
            content: 'Tulis pertanyaan...';
            color: #b7b3ac
        }

        .question[data-required="true"] .question-label::after {
            content: ' *';
            color: #e26855
        }

        .question-meta {
            font-size: 10px;
            color: #a09d96;
            margin-top: 6px;
            outline: none;
            cursor: text
        }

        .question-meta:empty:before {
            content: 'Tambahkan keterangan (opsional)';
            color: #c7c3ba
        }

        .grip {
            color: #b2afa9;
            font-size: 18px;
            cursor: grab
        }

        .preview {
            margin-top: 14px;
            border: 1px solid #e4e0da;
            border-radius: 7px;
            padding: 13px;
            color: #aaa;
            font-size: 11px;
            background: #fcfcfb
        }

        .dropzone {
            height: 56px;
            border: 1px dashed #d5cbbd;
            border-radius: 7px;
            display: grid;
            place-items: center;
            color: #aaa;
            font-size: 11px;
            margin-top: 14px
        }

        .dropzone.dragging {
            background: #fff8e9;
            border-color: var(--gold)
        }

        .empty {
            border: 1px dashed #d8d0c4;
            border-radius: 12px;
            padding: 54px;
            text-align: center;
            color: #999;
            background: #fff
        }

        .draft-modal-backdrop {
            position: fixed;
            inset: 0;
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: #17151299
        }

        .draft-modal-backdrop.show {
            display: flex
        }

        .draft-modal {
            width: min(420px, 100%);
            border: 1px solid #e5e1db;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 18px 50px #0004;
            padding: 24px
        }

        .draft-modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            margin-top: 22px
        }

        .draft-modal-actions button {
            border: 1px solid #dedbd4;
            border-radius: 5px;
            background: #fff;
            color: #56524c;
            cursor: pointer;
            padding: 9px 13px;
            font-size: 11px
        }

        .draft-modal-actions .draft-restore {
            border-color: #ba8320;
            background: #ba8320;
            color: #fff;
            font-weight: 700
        }

        .question.heading .question-label {
            font: 700 30px/1.15 "Inter", Arial, sans-serif;
            color: #211e1a
        }

        .question.subheading .question-label {
            font: 500 16px/1.5 Inter, Arial, sans-serif;
            color: #6f6b64
        }

        .question.heading .question-meta,
        .question.subheading .question-meta,
        .question.heading .preview,
        .question.subheading .preview {
            display: none
        }

        .question-actions {
            display: flex;
            align-items: center;
            gap: 12px
        }

        .remove {
            width: 33px;
            height: 33px;
            border: 1px solid #eadbd0;
            border-radius: 6px;
            background: #fff8f5;
            color: #c15b45;
            cursor: pointer;
            font-size: 19px;
            line-height: 1
        }

        .remove:hover {
            background: #c15b45;
            color: #fff
        }

        .choice-option {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #5b5852;
            font-size: 11px
        }

        .file-preview {
            height: 76px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 1px dashed #d5cbbd;
            border-radius: 7px;
            background: #fcfbf9;
            color: #8d8981
        }

        .file-icon {
            color: var(--gold);
            font-size: 19px;
            margin-bottom: 5px
        }

        .preview-input {
            outline: none;
            cursor: text;
            min-height: 18px
        }

        .preview-input:focus {
            background: #fffdf6;
            border-radius: 5px
        }

        .preview-input:empty:before {
            content: 'Ketik placeholder...';
            color: #aaa
        }

        .option-text {
            outline: none;
            cursor: text;
            flex: 1
        }

        .option-remove {
            border: 0;
            background: none;
            color: #bbb;
            cursor: pointer;
            font-size: 16px;
            line-height: 1
        }

        .option-remove:hover {
            color: #c15b45
        }

        .add-option {
            border: 0;
            background: none;
            color: var(--gold);
            cursor: pointer;
            font-size: 11px;
            padding: 5px 0;
            text-align: left;
            display: block
        }

        .add-option:hover {
            text-decoration: underline
        }

        .file-drop {
            display: block;
            cursor: pointer;
        }

        .file-clear {
            border: 0;
            background: none;
            color: #bbb;
            cursor: pointer;
            font-size: 13px;
            margin-top: 6px
        }

        .required-switch {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            user-select: none;
            font-size: 9px;
            color: #98958f;
            white-space: nowrap;
        }

        .required-switch-track {
            width: 30px;
            height: 17px;
            border-radius: 999px;
            background: #e5e1db;
            position: relative;
            transition: background .15s ease;
            flex: none;
        }

        .required-switch-thumb {
            position: absolute;
            top: 2px;
            left: 2px;
            width: 13px;
            height: 13px;
            border-radius: 50%;
            background: white;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .25);
            transition: left .15s ease;
        }

        .required-switch.active .required-switch-track {
            background: #e26855;
        }

        .required-switch.active .required-switch-thumb {
            left: 15px;
        }

        .required-switch.active .required-switch-label {
            color: #c1442f;
            font-weight: bold;
        }
    </style>
</head>

<body>
    @include('admin.partials.header', ['variant' => 'builder', 'event' => $event])
    <div class="shell">
        <aside class="side">
            <div class="brand">ALNAY<span>.ID</span></div>
            <div class="nav">
                <p class="section">Event & Peserta</p>
                <a href="{{ route('admin.dashboard') }}"><span class="step">1</span><span>Buat Event Baru</span></a>
                <a class="active" href="{{ route('admin.form-builder', $event?->id) }}"><span class="step">2</span><span>Buat Form Pendaftaran Event</span></a>
                <a href="#"><span class="step">3</span><span>Verifikasi Pendaftaran Peserta</span><b class="badge">6</b></a>
                <a href="#"><span class="step">4</span><span>Aktivasi Akun Peserta</span></a>
            </div>
            <div class="user">
                <span class="avatar">SA</span>
                <div><strong>{{ session('admin_name', 'Siti Aminah') }}</strong><small>Admin Event</small></div>
            </div>

            <a href="{{ $event ? route('admin.preview-form', $event->id) : '#' }}" target="_blank"
                class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                Lihat Form Publik
            </a>
        </aside>

        <main class="workspace">
            <div class="builder">
                <aside class="library" style="padding-top: 50px">
                    <p class="pane-title">Fields <span class="count">10 Komponen</span></p>

                    @foreach ([['text', 'Judul', 'Header / Nama Section'], ['textarea', 'Sub Judul', 'Deskripsi teks panduan'], ['text', 'Text', 'Input teks satu baris'], ['text', 'Email', 'Validasi alamat email'], ['number', 'Angka', 'WhatsApp, Usia, Ukuran'], ['textarea', 'Teks Panjang', 'Textarea / Catatan Khusus'], ['select', 'Dropdown', 'Pilihan Kategori Lomba'], ['radio', 'Radio Option', 'Pilihan tunggal opsional'], ['file', 'Upload Gambar', 'Foto Peserta / Kostum'], ['file', 'Upload Dokumen', 'KTP / KK / Portofolio (PDF)']] as $item)
                        <div class="field-item" draggable="true" data-type="{{ $item[0] }}" data-label="{{ $item[1] }}" data-hint="{{ $item[2] }}">
                            <span class="drag">⠿</span><strong>{{ $item[1] }}</strong><small>{{ $item[2] }}</small>
                        </div>
                    @endforeach
                </aside>

                <section class="canvas">
                    <div class="canvas-bar"><span>CANVAS &nbsp;</span></div>
                    <div id="questionList">
                        @forelse($questions as $question)
                            <article class="question" draggable="true" data-id="{{ $question->id }}"
                                data-type="{{ $question->tipe_input }}" data-label="{{ $question->label }}"
                                data-required="{{ $question->wajib ? 'true' : 'false' }}"
                                data-options='@json($question->pilihan_jawaban ?? [])'
                                data-config='@json($question->konfigurasi ?? [])'>
                                <div class="question-head">
                                    <div>
                                        <div class="question-label" contenteditable="true" data-role="label" spellcheck="false"></div>
                                        <div class="question-meta" contenteditable="true" data-role="help" spellcheck="false"></div>
                                    </div>
                                    <div class="question-actions">
                                        <label class="required-switch" data-role="toggle-required" title="Wajib diisi">
                                            <span class="required-switch-track"><span class="required-switch-thumb"></span></span>
                                            <span class="required-switch-label">Wajib</span>
                                        </label>
                                        <button class="remove" type="button" title="Hapus elemen" aria-label="Hapus elemen">
                                            <span class="trash-icon">🗑</span>
                                        </button>
                                        <span class="grip">⠿</span>
                                    </div>
                                </div>
                                <div class="preview"></div>
                            </article>
                        @empty
                            <div class="empty" id="emptyState">Tarik komponen dari panel kiri ke sini<br><small>atau klik komponen untuk menambahkannya</small></div>
                        @endforelse
                    </div>
                    <div class="dropzone" id="dropzone">+ Tarik field ke sini untuk menambah</div>
                </section>
            </div>
        </main>
    </div>

    <div class="draft-modal-backdrop" id="draftModal" role="dialog" aria-modal="true">
        <div class="draft-modal">
            <h2>Perubahan formulir ditemukan</h2>
            <p id="draftModalMessage"></p>
            <div class="draft-modal-actions">
                <button type="button" id="discardDraft">Hapus draft</button>
                <button type="button" class="draft-restore" id="restoreDraft">Muat perubahan</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            const list = document.getElementById('questionList');
            const dropzone = document.getElementById('dropzone');
            const saveButton = document.getElementById('saveForm');

            let selected = null;
            let draggedQuestion = null;
            let draggedLibraryItem = null;

            function resolveEventId() {
                const fromUrl = window.location.pathname.match(/form-builder\/(\d+)/);
                if (fromUrl) return fromUrl[1];
                const fromBlade = '{{ $event?->id }}';
                if (fromBlade) return fromBlade;
                return '';
            }

            const eventId = resolveEventId();
            const draftKey = `formBuilderDraft:${eventId || 'new'}`;

            function getFieldsFromCanvas() {
                const questions = [...document.querySelectorAll('#questionList .question')];
                return questions.map((element, index) => ({
                    id: element.dataset.id || null,
                    urutan: index + 1,
                    label: element.dataset.label || '',
                    tipe_input: element.dataset.type,
                    pilihan_jawaban: getOptions(element),
                    konfigurasi: getConfig(element),
                    wajib: element.dataset.required === 'true'
                }));
            }

            function saveDraftNow() {
                if (!draftKey) return;
                try {
                    localStorage.setItem(draftKey, JSON.stringify({
                        savedAt: Date.now(),
                        fields: getFieldsFromCanvas()
                    }));
                } catch (error) {
                    console.error('[FormBuilder] Gagal simpan draft:', error);
                }
            }

            function clearDraft() {
                if (!draftKey) return;
                localStorage.removeItem(draftKey);
            }

            function parseJSON(value, fallback = {}) {
                try {
                    return JSON.parse(value || JSON.stringify(fallback));
                } catch (error) {
                    return fallback;
                }
            }

            function getOptions(element) {
                const options = parseJSON(element.dataset.options, []);
                return Array.isArray(options) ? options : [];
            }

            function getConfig(element) {
                const config = parseJSON(element.dataset.config, {});
                return config && typeof config === 'object' ? config : {};
            }

            function escapeHTML(value) {
                const div = document.createElement('div');
                div.textContent = value ?? '';
                return div.innerHTML;
            }

            function renderField(element, force = false) {
            const type = element.dataset.type;
            const options = getOptions(element);
            const config = getConfig(element);

            const preview = element.querySelector('.preview');
            const labelElement = element.querySelector('.question-label');

            if (!preview || !labelElement) return;

            element.classList.remove('heading', 'subheading', 'text-field', 'email-field', 'choice-field', 'file-field');

            if (type === 'heading') element.classList.add('heading');
            if (type === 'subheading') element.classList.add('subheading');
            if (['radio', 'checkbox', 'select'].includes(type)) element.classList.add('choice-field');
            if (type === 'file') element.classList.add('file-field');

            if (document.activeElement !== labelElement) {
                labelElement.textContent = element.dataset.label || '';
            }

            const metaElement = element.querySelector('.question-meta');
            if (metaElement && document.activeElement !== metaElement) {
                metaElement.textContent = config.help || '';
            }

            const requiredToggle = element.querySelector('[data-role="toggle-required"]');
            if (requiredToggle) {
                requiredToggle.classList.toggle('active', element.dataset.required === 'true');
            }

            // 1. Heading / Subheading
            if (type === 'heading' || type === 'subheading') {
                preview.innerHTML = '';
                return;
            }

            // 2. Radio & Checkbox
            if (type === 'radio' || type === 'checkbox') {
                if (force || !preview.contains(document.activeElement)) {
                    const optionsHtml = options.map((option, index) => `
                        <label class="choice-option">
                            <input type="${type}" disabled>
                            <span class="option-text" contenteditable="true" data-role="option" data-index="${index}">${escapeHTML(option)}</span>
                            <button type="button" class="option-remove" data-role="remove-option" data-index="${index}" title="Hapus opsi">×</button>
                        </label>
                    `).join('');

                    preview.innerHTML = optionsHtml + `<button type="button" class="add-option" data-role="add-option">+ Tambah opsi</button>`;
                }
                return;
            }

            // 3. Dropdown Select
            if (type === 'select') {
                if (force || !preview.contains(document.activeElement)) {
                    const optionsHtml = options.length ?
                        options.map((option, index) => `
                            <div class="choice-option">
                                <span class="option-text" contenteditable="true" data-role="option" data-index="${index}">${escapeHTML(option)}</span>
                                <button type="button" class="option-remove" data-role="remove-option" data-index="${index}" title="Hapus opsi">×</button>
                            </div>
                        `).join('') : '<div class="choice-option" style="color:#aaa">Belum ada pilihan</div>';

                    preview.innerHTML = optionsHtml + `<button type="button" class="add-option" data-role="add-option">+ Tambah opsi</button>`;
                }
                return;
            }

            // 4. File Upload (PERBAIKAN TAMPILAN UPLOAD)
            if (type === 'file') {
                preview.innerHTML = `
                    <div class="file-preview" style="padding: 16px; border: 2px dashed #d5cbbd; border-radius: 8px; background: #fcfbf9; text-align: center;">
                        <div class="file-icon" style="font-size: 24px; color: #c38b22; margin-bottom: 6px;">📁</div>
                        <div class="file-drop-text" style="font-size: 13px; font-weight: 500; color: #555;">
                            Pilih File
                        </div>
                    </div>
                `;
                return;
            }

            // 5. Input Text / Number / Email / Textarea Biasa
            if (force || !preview.contains(document.activeElement)) {
                const defaultText = {
                    email: 'Masukkan alamat email (contoh: nama@email.com)',
                    number: 'Masukkan angka',
                    textarea: 'Masukkan jawaban peserta',
                }[type] || 'Masukkan jawaban peserta';

                preview.innerHTML = `<div class="preview-input" contenteditable="true" data-role="placeholder">${escapeHTML(config.placeholder || defaultText)}</div>`;
            }
        }

            function selectQuestion(questionEl) {
                if (selected) {
                    selected.classList.remove('selected');
                }
                selected = questionEl;
                if (selected) {
                    selected.classList.add('selected');
                }
            }

            function addField(type, label) {
                if (!type) return;

                document.getElementById('emptyState')?.remove();

                let finalLabel = label;
                if (type === 'heading') finalLabel = 'Pendaftaran Event Alnay.ID';
                if (type === 'subheading') finalLabel = 'Kompetisi & Pergelaran Busana Adat Nusantara';

                let options = [];
                if (['radio', 'checkbox', 'select'].includes(type)) {
                    options = ['Pilihan 1', 'Pilihan 2'];
                }

                const element = document.createElement('article');
                element.className = 'question';
                element.draggable = true;
                element.dataset.id = '';
                element.dataset.type = type;
                element.dataset.label = finalLabel;
                element.dataset.required = 'false';
                element.dataset.options = JSON.stringify(options);
                element.dataset.config = JSON.stringify({ help: '', placeholder: '' });

                element.innerHTML = `
                    <div class="question-head">
                        <div>
                            <div class="question-label" contenteditable="true" data-role="label" spellcheck="false"></div>
                            <div class="question-meta" contenteditable="true" data-role="help" spellcheck="false"></div>
                        </div>
                        <div class="question-actions">
                            <label class="required-switch" data-role="toggle-required" title="Wajib diisi">
                                <span class="required-switch-track"><span class="required-switch-thumb"></span></span>
                                <span class="required-switch-label">Wajib</span>
                            </label>
                            <button class="remove" type="button" title="Hapus elemen" aria-label="Hapus elemen">
                                <span class="trash-icon">🗑</span>
                            </button>
                            <span class="grip">⠿</span>
                        </div>
                    </div>
                    <div class="preview"></div>
                `;

                list.appendChild(element);

                bindQuestion(element);
                renderField(element);
                selectQuestion(element);

                saveDraftNow();
            }

            function bindQuestion(element) {
                element.addEventListener('click', function(event) {
                    if (event.target.closest('.remove')) return;
                    selectQuestion(element);
                });

                element.addEventListener('dragstart', function(event) {
                    draggedQuestion = element;
                    element.classList.add('dragging-question');
                    event.dataTransfer.effectAllowed = 'move';
                });

                element.addEventListener('dragend', function() {
                    element.classList.remove('dragging-question');
                    draggedQuestion = null;
                    saveDraftNow();
                });

                const removeButton = element.querySelector('.remove');
                if (removeButton) {
                    removeButton.addEventListener('click', function(event) {
                        event.stopPropagation();
                        element.remove();
                        saveDraftNow();

                        if (!list.querySelector('.question') && !document.getElementById('emptyState')) {
                            const emptyElement = document.createElement('div');
                            emptyElement.id = 'emptyState';
                            emptyElement.className = 'empty';
                            emptyElement.innerHTML = 'Tarik komponen dari panel kiri ke sini<br><small>atau klik komponen untuk menambahkannya</small>';
                            list.prepend(emptyElement);
                        }
                    });
                }
            }

            document.querySelectorAll('.field-item').forEach(item => {
                // Sinkronisasi data-type spesifik berdasarkan label
                if (item.dataset.label === 'Email') item.dataset.type = 'email';
                if (item.dataset.label === 'Judul') item.dataset.type = 'heading';
                if (item.dataset.label === 'Sub Judul') item.dataset.type = 'subheading';
                if (item.dataset.label.includes('Upload')) item.dataset.type = 'file';

                item.addEventListener('click', function() {
                    addField(item.dataset.type, item.dataset.label);
                });

                item.addEventListener('dragstart', function(event) {
                    draggedLibraryItem = item;
                    event.dataTransfer.effectAllowed = 'copy';
                    event.dataTransfer.setData('text/field-type', item.dataset.type);
                    event.dataTransfer.setData('text/field-label', item.dataset.label);
                });

                item.addEventListener('dragend', function() {
                    draggedLibraryItem = null;
                });
            });

            list.addEventListener('dragover', function(event) {
                event.preventDefault();
                if (draggedQuestion) {
                    const target = event.target.closest('.question');
                    if (!target || target === draggedQuestion) return;
                    const rect = target.getBoundingClientRect();
                    const before = event.clientY < rect.top + rect.height / 2;
                    if (before) {
                        list.insertBefore(draggedQuestion, target);
                    } else {
                        list.insertBefore(draggedQuestion, target.nextSibling);
                    }
                }
            });

            list.addEventListener('drop', function(event) {
                event.preventDefault();
                const type = event.dataTransfer.getData('text/field-type');
                const label = event.dataTransfer.getData('text/field-label');

                if (type && label && !draggedQuestion) {
                    addField(type, label);
                }
                saveDraftNow();
            });

            list.addEventListener('click', function(event) {
                const addBtn = event.target.closest('[data-role="add-option"]');
                if (addBtn) {
                    const question = addBtn.closest('.question');
                    const options = getOptions(question);
                    options.push('Opsi ' + (options.length + 1));
                    question.dataset.options = JSON.stringify(options);
                    renderField(question, true);
                    saveDraftNow();
                    return;
                }

                const toggleRequiredBtn = event.target.closest('[data-role="toggle-required"]');
                if (toggleRequiredBtn) {
                    const question = toggleRequiredBtn.closest('.question');
                    question.dataset.required = question.dataset.required === 'true' ? 'false' : 'true';
                    renderField(question, true);
                    saveDraftNow();
                    return;
                }

                const removeBtn = event.target.closest('[data-role="remove-option"]');
                if (removeBtn) {
                    const question = removeBtn.closest('.question');
                    const options = getOptions(question);
                    options.splice(Number(removeBtn.dataset.index), 1);
                    question.dataset.options = JSON.stringify(options);
                    renderField(question, true);
                    saveDraftNow();
                    return;
                }
            });

            // PENTING: listener ini yang menangkap SEMUA ketikan langsung di
            // canvas (label, keterangan tambahan, placeholder, teks opsi)
            // lalu menyimpannya ke dataset element - tanpa ini, isian di
            // canvas cuma tampil sementara tapi tidak pernah ke-simpan.
            list.addEventListener('input', function (event) {
                const target = event.target;
                const question = target.closest('.question');
                if (!question) return;

                if (target.dataset.role === 'label') {
                    question.dataset.label = target.textContent.trim();
                } else if (target.dataset.role === 'help') {
                    const config = getConfig(question);
                    config.help = target.textContent.trim();
                    question.dataset.config = JSON.stringify(config);
                } else if (target.dataset.role === 'placeholder') {
                    const config = getConfig(question);
                    config.placeholder = target.textContent.trim();
                    question.dataset.config = JSON.stringify(config);
                } else if (target.dataset.role === 'option') {
                    const options = getOptions(question);
                    const index = Number(target.dataset.index);
                    options[index] = target.textContent.trim();
                    question.dataset.options = JSON.stringify(options);
                } else {
                    return;
                }

                saveDraftNow();
            });

            document.querySelectorAll('.question').forEach(question => {
                bindQuestion(question);
                renderField(question);
            });

            // Tombol Simpan Form
            const btnHeader = document.getElementById('saveFormHeader');
            const btnMain = document.getElementById('saveForm');
            const allSaveButtons = [btnHeader, btnMain].filter(Boolean);

            allSaveButtons.forEach(btn => {
                btn.addEventListener('click', async function(e) {
                    e.preventDefault();
                    if (!eventId) return toast('Pilih event terlebih dahulu.', 'warning');

                    const fields = getFieldsFromCanvas();
                    if (!fields.length) return toast('Tambahkan minimal satu field.', 'warning');

                    allSaveButtons.forEach(b => {
                        b.disabled = true;
                        b.innerHTML = 'Menyimpan...';
                    });

                    try {
                        const response = await fetch('{{ $event ? route("admin.form-builder.save", $event) : "#" }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ fields: fields })
                        });

                        const data = await response.json();
                        if (!response.ok) throw new Error(data.message || 'Gagal menyimpan.');

                        clearDraft();
                        toast(data.message || 'Formulir berhasil disimpan.', 'success');
                    } catch (error) {
                        swalAlert(error.message, 'error');
                    } finally {
                        allSaveButtons.forEach(b => {
                            b.disabled = false;
                            b.innerHTML = 'Simpan Formulir';
                        });
                    }
                });
            });
        });

        // Contoh fungsi pengumpul data dari DOM sebelum dikirim via AJAX:
function collectFormData() {
    const questions = [];
    document.querySelectorAll('.field-item').forEach((el, index) => {
        // Ambil nilai help/keterangan tambahan
        const helpInput = el.querySelector('.input-keterangan, [name="help"], .help-text');
        const helpValue = helpInput ? helpInput.value.trim() : '';

        // Ambil opsi-opsi jawaban (untuk Dropdown/Radio/Checkbox)
        const optionInputs = el.querySelectorAll('.option-input, .opsi-item input');
        const options = Array.from(optionInputs)
            .map(opt => opt.value.trim())
            .filter(opt => opt !== ''); // buang yang kosong

        questions.push({
            id: el.dataset.id || null,
            label: el.querySelector('.label-input').value,
            tipe_input: el.dataset.tipe,
            wajib: el.querySelector('.switch-wajib').checked,
            // PERBAIKAN: Masukkan help ke dalam konfigurasi
            konfigurasi: {
                help: helpValue,
                placeholder: el.querySelector('.placeholder-input')?.value || ''
            },
            // PERBAIKAN: Masukkan opsi jawaban ke pilihan_jawaban
            pilihan_jawaban: options
        });
    });
    return questions;
    }
    </script>

    @include('admin.partials.sidebar', ['active' => 'form-builder', 'event' => $event])
    @include('partials.swal')
</body>

</html>