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
            box-sizing: border-box
        }

        body {
            margin: 0;
            background: var(--cream);
            color: var(--ink);
            font: 12px Arial, sans-serif
        }

        .shell {
            display: flex;
            min-height: 100vh
        }

        .side {
            width: 183px;
            background: var(--dark);
            color: #aaa59c;
            padding: 15px 8px;
            flex: none
        }

        .brand {
            font: bold 18px Georgia, serif;
            color: #fff;
            padding: 3px 10px 27px
        }

        .brand span {
            color: #cc962f
        }

        .section {
            font-size: 8px;
            letter-spacing: .7px;
            text-transform: uppercase;
            color: #777169;
            margin: 0 10px 10px
        }

        .nav {
            margin-bottom: 22px
        }

        .nav a {
            display: flex;
            gap: 9px;
            align-items: center;
            padding: 10px 9px;
            border-radius: 7px;
            color: #a7a29a;
            text-decoration: none;
            line-height: 1.3
        }

        .nav a.active {
            background: #302b25;
            color: white
        }

        .step {
            width: 17px;
            height: 17px;
            border-radius: 50%;
            background: #332e28;
            display: grid;
            place-items: center;
            font-size: 9px;
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
            border-radius: 10px;
            padding: 2px 5px;
            font-size: 8px
        }

        .user {
            position: absolute;
            bottom: 16px;
            left: 8px;
            width: 167px;
            background: #29251f;
            border-radius: 8px;
            padding: 9px;
            display: flex;
            gap: 9px;
            align-items: center;
            color: #fff;
            font-size: 10px
        }

        .avatar {
            width: 27px;
            height: 27px;
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
            font-size: 8px;
            margin-top: 3px
        }

        .workspace {
            min-width: 0;
            flex: 1
        }

        .top {
            height: 96px;
            background: #201f1d;
            color: #fff;
            padding: 13px 18px 12px;
            position: relative
        }

        .crumb {
            color: #aaa59e;
            font-size: 9px
        }

        .crumb b {
            color: #d7a43c;
            font-weight: normal
        }

        .title-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 7px
        }

        .title-icon {
            width: 27px;
            height: 27px;
            background: #f3eee3;
            color: #37322b;
            border-radius: 6px;
            display: grid;
            place-items: center;
            font-weight: bold
        }

        .top h1 {
            font-size: 14px;
            margin: 0
        }

        .top p {
            margin: 2px 0 0;
            color: #b6b1aa;
            font-size: 9px
        }

        .top-actions {
            display: flex;
            gap: 8px;
            margin-top: 10px
        }

        .top-actions a,
        .top-actions button {
            border: 1px solid #57534e;
            border-radius: 4px;
            padding: 7px 11px;
            background: #2d2b28;
            color: #fff;
            font-size: 9px;
            text-decoration: none
        }

        .top-actions .save {
            background: var(--gold);
            border-color: var(--gold)
        }

        .builder {
            display: grid;
            grid-template-columns: 184px minmax(400px, 1fr) 255px;
            min-height: calc(100vh - 96px)
        }

        .library,
        .inspector {
            background: #faf9f7
        }

        .library {
            border-right: 1px solid var(--line);
            padding: 15px 10px
        }

        .pane-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-transform: uppercase;
            letter-spacing: .8px;
            font-size: 8px;
            color: #797670;
            margin: 0 5px 12px
        }

        .count {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 9px;
            padding: 3px 7px;
            color: var(--gold);
            font-size: 8px;
            text-transform: none;
            letter-spacing: 0
        }

        .field-item {
            background: #fff;
            border: 1px solid #e1ddd6;
            border-radius: 8px;
            padding: 10px 9px;
            margin-bottom: 7px;
            cursor: grab;
            box-shadow: 0 1px 2px #00000005
        }

        .field-item:hover {
            border-color: #d0a047;
            transform: translateX(2px)
        }

        .drag {
            color: #aaa;
            font-size: 14px;
            margin-right: 7px
        }

        .field-item strong {
            font-size: 10px
        }

        .field-item small {
            display: block;
            color: #98958f;
            font-size: 8px;
            margin: 4px 0 0 22px
        }

        .canvas {
            padding: 22px 26px 65px;
            overflow: auto;
            background: #f5f4f1
        }

        .canvas-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #8e8b84;
            font-size: 9px;
            margin-bottom: 15px
        }

        .zoom {
            border: 1px solid var(--line);
            background: white;
            border-radius: 4px;
            padding: 5px 8px
        }

        .form-canvas {
            max-width: 680px;
            margin: auto
        }

        .form-header,
        .question {
            background: white;
            border: 1px solid #e3ded6;
            border-radius: 10px;
            box-shadow: 0 3px 10px #3a302211
        }

        .form-header {
            padding: 20px 16px;
            margin-bottom: 11px;
            border-top: 3px solid var(--gold)
        }

        .form-header h2 {
            font: 700 20px Georgia, serif;
            margin: 0 0 6px
        }

        .form-header p {
            margin: 0;
            color: #73716c;
            font-size: 10px
        }

        .question {
            padding: 15px;
            margin-bottom: 10px;
            position: relative
        }

        .question.selected {
            border-color: #c99532;
            box-shadow: 0 0 0 2px #dcb96d55
        }

        .question-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between
        }

        .question-label {
            font-size: 11px;
            font-weight: bold
        }

        .question-label em {
            color: #e26855;
            font-style: normal;
            font-size: 9px;
            margin-left: 5px
        }

        .question-meta {
            font-size: 8px;
            color: #a09d96;
            margin-top: 5px
        }

        .grip {
            color: #b2afa9;
            font-size: 15px;
            cursor: grab
        }

        .preview {
            margin-top: 12px;
            border: 1px solid #e4e0da;
            border-radius: 6px;
            padding: 10px;
            color: #aaa;
            font-size: 9px;
            background: #fcfcfb
        }

        .preview.area {
            height: 47px
        }

        .preview.select:after {
            content: '⌄';
            float: right;
            color: #999
        }

        .dropzone {
            height: 47px;
            border: 1px dashed #d5cbbd;
            border-radius: 6px;
            display: grid;
            place-items: center;
            color: #aaa;
            font-size: 9px;
            margin-top: 12px
        }

        .dropzone.dragging {
            background: #fff8e9;
            border-color: var(--gold)
        }

        .remove {
            border: 0;
            background: none;
            color: #bab5ad;
            cursor: pointer;
            font-size: 14px
        }

        .inspector {
            border-left: 1px solid var(--line);
            padding: 15px
        }

        .inspector h3 {
            font-size: 11px;
            margin: 0 0 5px
        }

        .inspector>p {
            font-size: 9px;
            color: #999;
            margin: 0 0 16px;
            line-height: 1.5
        }

        .control {
            margin-bottom: 14px
        }

        .control label {
            display: block;
            font-size: 9px;
            font-weight: bold;
            margin-bottom: 6px;
            color: #59564f
        }

        .control input,
        .control textarea,
        .control select {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 5px;
            background: white;
            padding: 8px;
            font-size: 10px;
            font-family: inherit
        }

        .control textarea {
            height: 57px;
            resize: vertical
        }

        .toggle {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid var(--line);
            padding-top: 13px
        }

        .toggle input {
            accent-color: var(--gold);
            width: 15px;
            height: 15px
        }

        .hint {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 11px 18px;
            background: #fff;
            border-top: 1px solid var(--line);
            font-size: 9px;
            color: #8d8981
        }

        .empty {
            border: 1px dashed #d8d0c4;
            border-radius: 10px;
            padding: 45px;
            text-align: center;
            color: #999;
            background: #fff
        }

        .toast {
            position: fixed;
            right: 20px;
            bottom: 20px;
            background: #182b26;
            color: #e7fff7;
            padding: 11px 15px;
            border-radius: 7px;
            box-shadow: 0 5px 18px #0003;
            display: none;
            font-size: 11px
        }

        .toast.show {
            display: block
        }

        @media(max-width:980px) {
            .builder {
                grid-template-columns: 170px minmax(350px, 1fr)
            }

            .inspector {
                display: none
            }
        }

        @media(max-width:650px) {
            .side {
                width: 58px
            }

            .brand {
                font-size: 0;
                padding-left: 10px
            }

            .brand:before {
                content: 'A';
                font: bold 22px Georgia;
                color: #ca922b
            }

            .section,
            .nav a span:not(.step),
            .badge,
            .user div {
                display: none
            }

            .nav a {
                justify-content: center
            }

            .user {
                width: 42px;
                justify-content: center
            }

            .builder {
                display: block
            }

            .library {
                display: flex;
                gap: 7px;
                overflow: auto;
                padding: 9px
            }

            .library .pane-title {
                display: none
            }

            .field-item {
                min-width: 130px;
                margin: 0;
                padding: 8px
            }

            .field-item small {
                display: none
            }

            .canvas {
                padding: 15px 10px
            }

            .top {
                height: auto
            }

            .top-actions {
                overflow: auto
            }
        }

        <style>@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: Inter, Arial, sans-serif
        }

        .question.heading,
        .question.subheading {
            padding: 20px 16px
        }

        .question.heading .question-label {
            font: 700 25px/1.15 Georgia, serif;
            color: #211e1a
        }

        .question.subheading .question-label {
            font: 500 13px/1.5 Inter, Arial, sans-serif;
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
            gap: 10px
        }

        .remove {
            width: 27px;
            height: 27px;
            border: 1px solid #eadbd0;
            border-radius: 5px;
            background: #fff8f5;
            color: #c15b45;
            cursor: pointer;
            font-size: 16px;
            line-height: 1
        }

        .remove:hover {
            background: #c15b45;
            color: #fff
        }

        .grip {
            cursor: grab
        }

        .question.text-field .question-label {
            font-size: 10px
        }

        .question.email-field .preview {
            font-size: 0
        }

        .question.email-field .preview::after {
            content: 'Masukkan alamat email (contoh: nama@email.com)';
            font-size: 9px;
            color: #aaa
        }

        .question.choice-field .preview {
            display: flex;
            flex-direction: column;
            gap: 8px;
            background: transparent;
            border: 0;
            padding: 0;
            color: #5b5852
        }

        .choice-option {
            display: flex;
            align-items: center;
            gap: 8px
        }

        .choice-option input {
            accent-color: var(--gold)
        }

        .question.file-field .preview {
            height: 76px;
            display: grid;
            place-items: center;
            border: 1px dashed #d5cbbd;
            background: #fcfbf9;
            color: #8d8981
        }

        .question.file-field .preview::before {
            content: '▣';
            display: block;
            color: var(--gold);
            font-size: 16px;
            margin-bottom: 3px
        }

        .trash-icon {
            font-size: 15px
        }
    </style>
</head>
:root{--ink:#22201d;--dark:#171512;--gold:#c38b22;--cream:#f7f6f3;--line:#e5e1db;--muted:#8a8984;--green:#089b73}*{box-sizing:border-box}body{margin:0;background:var(--cream);color:var(--ink);font:12px
Arial,sans-serif}
@include('admin.partials.header', ['variant' => 'builder', 'event' => $event])
<div class="shell">
    <aside class="side">
        <div class="brand">ALNAY<span>.ID</span></div>
        <div class="nav">
            <p class="section">Event & Peserta</p><a href="{{ route('admin.dashboard') }}"><span
                    class="step">1</span><span>Buat Event Baru</span></a><a class="active"
                href="{{ route('admin.form-builder', $event?->id) }}"><span class="step">2</span><span>Buat Form
                    Pendaftaran Event</span></a><a href="#"><span class="step">3</span><span>Verifikasi
                    Pendaftaran Peserta</span><b class="badge">6</b></a><a href="#"><span
                    class="step">4</span><span>Aktivasi Akun Peserta</span></a>
        </div>
        <div class="nav">
            <p class="section">Voting</p><a href="#"><span class="step">4</span><span>Pengaturan Event &
                    Voting</span></a><a href="#"><span class="step">5</span><span>Daftar Voter</span></a><a
                href="#"><span class="step">6</span><span>Leaderboard Suara Voting</span></a>
        </div>
        <div class="user"><span class="avatar">SA</span>
            <div><strong>{{ session('admin_name', 'Siti Aminah') }}</strong><small>Admin Event</small></div>
        </div>
    </aside>
    <main class="workspace">
        <header class="top">
            <div class="crumb">Atelier Event &nbsp;›&nbsp; {{ $event?->nama ?? 'Pilih Event' }} &nbsp;›&nbsp; <b>Atur
                    Formulir Pendaftaran</b></div>
            <div class="title-row"><span class="title-icon">A</span>
                <div>
                    <h1>Formulir Pendaftaran — {{ $event?->nama ?? 'Event Baru' }}</h1>
                    <p>Atur data & persyaratan yang wajib diisi peserta fashion show busana adat</p>
                </div>
            </div>
            <div class="top-actions"><span
                    style="padding:7px 10px;border:1px solid #45423e;border-radius:4px;color:#aaa;font-size:9px">●
                    alnay.id/daftar/{{ $event?->id ?? 'event' }}</span><a href="#">◉ &nbsp; Lihat
                    Publik</a><button class="save" id="saveForm">▣ &nbsp; Simpan Formulir</button><select
                    id="eventPicker"
                    style="background:#2d2b28;color:#fff;border:1px solid #57534e;border-radius:4px;padding:6px;font-size:9px">
                    <option value="">Atur Pendaftaran</option>
                    @foreach ($events as $item)
                        <option value="{{ $item->id }}" @selected($event?->id === $item->id)>{{ $item->nama }}</option>
                    @endforeach
                </select>
            </div>
        </header>
        <div class="builder">
            <aside class="library">
                <p class="pane-title">Fields <span class="count">10 Komponen</span></p>
                @foreach ([['text', 'Judul', 'Header / Nama Section'], ['textarea', 'Sub Judul', 'Deskripsi teks panduan'], ['text', 'Text', 'Input teks satu baris'], ['text', 'Email', 'Validasi alamat email'], ['number', 'Angka', 'WhatsApp, Usia, Ukuran'], ['textarea', 'Teks Panjang', 'Textarea / Catatan Khusus'], ['select', 'Dropdown', 'Pilihan Kategori Lomba'], ['radio', 'Radio Option', 'Pilihan tunggal opsional'], ['file', 'Upload Gambar / Banner', 'Foto Peserta / Kostum'], ['file', 'Upload Dokumen', 'KTP / KK / Portofolio (PDF)']] as $item)
                    <div class="field-item" draggable="true" data-type="{{ $item[0] }}"
                        data-label="{{ $item[1] }}" data-hint="{{ $item[2] }}"><span
                            class="drag">⠿</span><strong>{{ $item[1] }}</strong><small>{{ $item[2] }}</small>
                    </div>
                @endforeach
            </aside>
            <section class="canvas">
                <div class="canvas-bar"><span>CANVAS &nbsp;|&nbsp; Mode Desain Interaktif</span><span
                        class="zoom">100% &nbsp; ◧ ◫</span></div>
                <div class="form-canvas">
                    <div class="form-header">
                        <h2 id="formTitle">{{ $event?->nama ?? 'Pendaftaran Event Alnay.ID' }}</h2>
                        <p id="formDescription">Kompetisi & Pergelaran Busana Adat Nusantara</p>
                    </div>
                    <div id="questionList">
                        @forelse($questions as $question)
                            <article class="question" draggable="true" data-id="{{ $question->id }}"
                                data-type="{{ $question->tipe_input }}" data-label="{{ $question->label }}"
                                data-required="{{ $question->wajib ? 'true' : 'false' }}"
                                data-options='@json($question->pilihan_jawaban ?? [])' data-config='@json($question->konfigurasi ?? [])'>
                                <div class="question-head">
                                    <div>
                                        <div class="question-label">{{ $question->label }} @if ($question->wajib)
                                                <em>Wajib</em>
                                            @endif
                                        </div>
                                        <div class="question-meta">{{ ucfirst($question->tipe_input) }}</div>
                                    </div>
                                    <div><button class="remove" type="button">⋮</button><span class="grip">⠿</span>
                                    </div>
                                </div>
                                <div
                                    class="preview {{ in_array($question->tipe_input, ['textarea', 'file']) ? 'area' : '' }} {{ in_array($question->tipe_input, ['select', 'radio']) ? 'select' : '' }}">
                                    {{ $question->tipe_input === 'file' ? 'Tarik berkas foto ke sini atau Pilih File' : 'Masukkan jawaban peserta' }}
                                </div>
                        </article>@empty<div class="empty" id="emptyState">Tarik komponen dari panel kiri ke
                                sini<br><small>atau klik komponen untuk menambahkannya</small></div>
                        @endforelse
                    </div>
                    <div class="dropzone" id="dropzone">+ Tarik field ke sini untuk menambah</div>
                </div>
            </section>
            <aside class="inspector">
                <h3>Pengaturan Field</h3>
                <p>Pilih elemen di canvas untuk mengatur detail dan validasinya.</p>
                <div id="inspectorEmpty" class="empty" style="padding:22px 10px;font-size:10px">Belum ada field
                    dipilih.</div>
                <div id="inspectorForm" style="display:none">
                    <div class="control"><label>Label pertanyaan</label><input id="editLabel"></div>
                    <div class="control"><label>Deskripsi / petunjuk</label>
                        <textarea id="editHelp"></textarea>
                    </div>
                    <div class="control"><label>Placeholder</label><input id="editPlaceholder"></div>
                    <div class="control"><label>Opsi pilihan (satu per baris)</label>
                        <textarea id="editOptions"></textarea>
                    </div>
                    <div class="toggle"><label>Wajib diisi</label><input id="editRequired" type="checkbox"></div>
                </div>
            </aside>
        </div>
    </main>
</div>
<div class="hint">◉ &nbsp; Klik atau tarik komponen ke canvas &nbsp; • &nbsp; Urutkan field dengan drag and drop
</div>
<div class="toast" id="toast">Formulir berhasil disimpan.</div>
<script>
    const list = document.getElementById('questionList'),
        dropzone = document.getElementById('dropzone'),
        empty = document.getElementById('emptyState'),
        inspectorEmpty = document.getElementById('inspectorEmpty'),
        inspectorForm = document.getElementById('inspectorForm');
    let selected = null;
    let dragSource = null;
    document.querySelectorAll('.remove').forEach(button => button.textContent = '×');
    document.querySelectorAll('.question').forEach(question => {
        const label = question.dataset.label;
        if (label === 'Judul' || label === 'Pendaftaran Event Alnay.ID') question.classList.add('heading');
        if (label === 'Sub Judul' || label === 'Kompetisi & Pergelaran Busana Adat Nusantara') question
            .classList.add('subheading');
    });
    document.querySelectorAll('.field-item').forEach(item => {
        const replacement = item.cloneNode(true);
        item.replaceWith(replacement);
        replacement.addEventListener('click', event => {
            event.stopImmediatePropagation();
            let type = replacement.dataset.type;
            let label = replacement.dataset.label;
            if (label === 'Judul') {
                type = 'heading';
                label = 'Pendaftaran Event Alnay.ID';
            }
            if (label === 'Sub Judul') {
                type = 'subheading';
                label = 'Kompetisi & Pergelaran Busana Adat Nusantara';
            }
            addField(type, label);
            const added = list.lastElementChild;
            added.classList.add(type);
        });
        replacement.addEventListener('dragstart', event => {
            event.stopImmediatePropagation();
            let type = replacement.dataset.type;
            let label = replacement.dataset.label;
            if (label === 'Judul') {
                type = 'heading';
                label = 'Pendaftaran Event Alnay.ID';
            }
            if (label === 'Sub Judul') {
                type = 'subheading';
                label = 'Kompetisi & Pergelaran Busana Adat Nusantara';
            }
            event.dataTransfer.setData('type', type);
            event.dataTransfer.setData('label', label);
        });
    });
    const types = {
        text: 'Text',
        textarea: 'Teks Panjang',
        number: 'Angka',
        select: 'Dropdown',
        radio: 'Radio Option',
        checkbox: 'Checkbox',
        file: 'Upload File'
    };

    function selectQuestion(el) {
        document.querySelectorAll('.question').forEach(q => q.classList.remove('selected'));
        selected = el;
        el.classList.add('selected');
        inspectorEmpty.style.display = 'none';
        inspectorForm.style.display = 'block';
        editLabel.value = el.dataset.label;
        editHelp.value = JSON.parse(el.dataset.config || '{}').help || '';
        editPlaceholder.value = JSON.parse(el.dataset.config || '{}').placeholder || '';
        editOptions.value = JSON.parse(el.dataset.options || '[]').join('\n');
        editRequired.checked = el.dataset.required === 'true'
    }

    function bind(el) {
        el.addEventListener('click', () => selectQuestion(el));
        el.addEventListener('dragstart', e => {
            dragSource = el;
            e.dataTransfer.effectAllowed = 'move'
        });
        el.addEventListener('dragover', e => {
            e.preventDefault();
            const r = el.getBoundingClientRect();
            if (e.clientY < r.top + r.height / 2) list.insertBefore(dragSource, el);
            else list.insertBefore(dragSource, el.nextSibling)
        });
        el.querySelector('.remove').addEventListener('click', e => {
            e.stopPropagation();
            el.remove();
            selected = null;
            inspectorForm.style.display = 'none';
            inspectorEmpty.style.display = 'block';
        })
    }

    function addField(type, label) {
        empty?.remove();
        const el = document.createElement('article');
        el.className = 'question';
        el.draggable = true;
        el.dataset.type = type;
        el.dataset.label = label;
        el.dataset.required = 'false';
        el.dataset.options = JSON.stringify(type === 'select' || type === 'radio' ? ['Pilihan 1', 'Pilihan 2'] : []);
        el.dataset.config = JSON.stringify({});
        el.innerHTML =
            '<div class="question-head"><div><div class="question-label"></div><div class="question-meta"></div></div><div><button class="remove" type="button">⋮</button><span class="grip">⠿</span></div></div><div class="preview"></div>';
        el.querySelector('.question-label').textContent = label;
        el.querySelector('.question-meta').textContent = types[type] || type;
        el.querySelector('.preview').textContent = type === 'file' ? 'Tarik berkas foto ke sini atau Pilih File' :
            'Masukkan jawaban peserta';
        list.appendChild(el);
        bind(el);
        selectQuestion(el)
    }
    document.querySelectorAll('.field-item').forEach(item => {
        item.addEventListener('click', () => addField(item.dataset.type, item.dataset.label));
        item.addEventListener('dragstart', e => {
            e.dataTransfer.setData('type', item.dataset.type);
            e.dataTransfer.setData('label', item.dataset.label)
        })
    });
    dropzone.addEventListener('dragover', e => {
        e.preventDefault();
        dropzone.classList.add('dragging')
    });
    dropzone.addEventListener('dragleave', () => dropzone.classList.remove('dragging'));
    dropzone.addEventListener('drop', e => {
        e.preventDefault();
        dropzone.classList.remove('dragging');
        if (e.dataTransfer.getData('type')) addField(e.dataTransfer.getData('type'), e.dataTransfer.getData(
            'label'))
    });
    document.querySelectorAll('.question').forEach(bind);

    function updateSelected() {
        if (!selected) return;
        selected.dataset.label = editLabel.value;
        selected.querySelector('.question-label').innerHTML = '';
        selected.querySelector('.question-label').append(document.createTextNode(editLabel.value + ' '));
        if (editRequired.checked) {
            const em = document.createElement('em');
            em.textContent = 'Wajib';
            selected.querySelector('.question-label').append(em)
        }
        selected.dataset.required = editRequired.checked ? 'true' : 'false';
        selected.dataset.options = JSON.stringify(editOptions.value.split('\n').map(x => x.trim()).filter(Boolean));
        selected.dataset.config = JSON.stringify({
            help: editHelp.value,
            placeholder: editPlaceholder.value
        })
    } ['editLabel', 'editHelp', 'editPlaceholder', 'editOptions', 'editRequired'].forEach(id => document.getElementById(
        id).addEventListener('input', updateSelected));
    document.getElementById('eventPicker').addEventListener('change', e => {
        if (e.target.value) location.href = '/admin/form-builder/' + e.target.value
    });
    document.getElementById('saveForm').addEventListener('click', async () => {
        const eventId = '{{ $event?->id }}';
        if (!eventId) {
            alert('Pilih event terlebih dahulu.');
            return
        }
        const fields = [...document.querySelectorAll('.question')].map(el => ({
            id: el.dataset.id || null,
            label: el.dataset.label,
            tipe_input: el.dataset.type,
            pilihan_jawaban: JSON.parse(el.dataset.options || '[]'),
            konfigurasi: JSON.parse(el.dataset.config || '{}'),
            wajib: el.dataset.required === 'true'
        }));
        const res = await fetch('{{ $event ? route('admin.form-builder.save', $event) : '#' }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                fields
            })
        });
        if (res.ok) {
            const t = document.getElementById('toast');
            t.classList.add('show');
            setTimeout(() => t.classList.remove('show'), 2500)
        } else alert('Form gagal disimpan. Periksa kembali data field.')
    });
</script>
<script>
    function renderField(el) {
        const type = el.dataset.type;
        const label = el.dataset.label;
        const options = JSON.parse(el.dataset.options || '[]');
        el.classList.toggle('heading', type === 'heading');
        el.classList.toggle('subheading', type === 'subheading');
        el.classList.toggle('text-field', type === 'text');
        el.classList.toggle('email-field', type === 'email');
        el.classList.toggle('choice-field', type === 'radio' || type === 'checkbox');
        el.classList.toggle('file-field', type === 'file');
        const preview = el.querySelector('.preview');
        if (type === 'heading' || type === 'subheading') {
            preview.innerHTML = '';
            return;
        }
        if (type === 'radio' || type === 'checkbox') {
            preview.innerHTML = options.map(option =>
                `<label class="choice-option"><input type="${type}" disabled> ${option}</label>`).join('');
            return;
        }
        if (type === 'file') {
            preview.textContent = 'Tarik berkas ke sini atau pilih file';
            return;
        }
        preview.textContent = type === 'email' ? 'Masukkan alamat email (contoh: nama@email.com)' :
            'Masukkan jawaban peserta';
    }

    function addField(type, label) {
        empty?.remove();
        const defaults = type === 'heading' ? 'Pendaftaran Event Alnay.ID' : type === 'subheading' ?
            'Kompetisi & Pergelaran Busana Adat Nusantara' : label;
        const el = document.createElement('article');
        el.className = 'question';
        el.draggable = true;
        el.dataset.type = type;
        el.dataset.label = defaults;
        el.dataset.required = 'false';
        el.dataset.options = JSON.stringify(type === 'radio' || type === 'checkbox' ? ['Pilihan 1', 'Pilihan 2'] : []);
        el.dataset.config = '{}';
        el.innerHTML =
            `<div class="question-head"><div><div class="question-label"></div><div class="question-meta"></div></div><div class="question-actions"><button class="remove" type="button" title="Hapus elemen" aria-label="Hapus elemen"><span class="trash-icon">&#128465;</span></button><span class="grip">⠿</span></div></div><div class="preview"></div>`;
        el.querySelector('.question-label').textContent = defaults;
        el.querySelector('.question-meta').textContent = types[type] || type;
        list.appendChild(el);
        bind(el);
        renderField(el);
        selectQuestion(el);
    }

    document.querySelectorAll('.question').forEach(question => {
        question.querySelector('.remove').innerHTML = '<span class="trash-icon">&#128465;</span>';
        renderField(question);
    });
    document.querySelectorAll('.field-item').forEach(item => {
        if (item.dataset.label === 'Email') item.dataset.type = 'email';
    });
    document.querySelectorAll('.field-item').forEach(item => {
        item.addEventListener('click', event => {
            event.stopImmediatePropagation();
            let type = item.dataset.type;
            let label = item.dataset.label;
            if (label === 'Judul') {
                type = 'heading';
                label = 'Pendaftaran Event Alnay.ID';
            }
            if (label === 'Sub Judul') {
                type = 'subheading';
                label = 'Kompetisi & Pergelaran Busana Adat Nusantara';
            }
            addField(type, label);
        }, true);
    });
    if (!document.querySelector('.field-item[data-type="checkbox"]')) {
        const checkbox = document.createElement('div');
        checkbox.className = 'field-item';
        checkbox.draggable = true;
        checkbox.dataset.type = 'checkbox';
        checkbox.dataset.label = 'Checkbox';
        checkbox.innerHTML =
            '<span class="drag">⠿</span><strong>Checkbox</strong><small>Pilihan lebih dari satu</small>';
        document.querySelector('.library').appendChild(checkbox);
        checkbox.addEventListener('click', () => addField('checkbox', 'Checkbox'));
        checkbox.addEventListener('dragstart', event => {
            event.dataTransfer.setData('type', 'checkbox');
            event.dataTransfer.setData('label', 'Checkbox');
        });
    }
</script>@include('admin.partials.sidebar', ['active' => 'form-builder', 'event' => $event])</body>

</html>
