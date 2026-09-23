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

        .nav {
            margin-bottom: 26px
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
            grid-template-columns: 220px minmax(420px, 1fr) 300px;
            min-height: calc(100vh - 115px)
        }

        .library,
        .inspector {
            background: #faf9f7
        }

        .library {
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
            padding: 26px 30px 70px;
            overflow: auto;
            background: #f5f4f1
        }

        .canvas-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #8e8b84;
            font-size: 11px;
            margin-bottom: 18px
        }

        .zoom {
            border: 1px solid var(--line);
            background: white;
            border-radius: 5px;
            padding: 6px 10px
        }

        .form-canvas {
            max-width: 760px;
            margin: auto
        }

        .form-header,
        .question {
            background: white;
            border: 1px solid #e3ded6;
            border-radius: 12px;
            box-shadow: 0 3px 10px #3a302211
        }

        .form-header {
            padding: 24px 20px;
            margin-bottom: 14px;
            border-top: 4px solid var(--gold)
        }

        .form-header h2 {
            font: 700 24px "Inter", Arial, sans-serif;
            margin: 0 0 8px
        }

        .form-header p {
            margin: 0;
            color: #73716c;
            font-size: 12px
        }

        .question {
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
            justify-content: space-between
        }

        .question-label {
            font-size: 14px;
            font-weight: bold;
            outline: none;
            cursor: text
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
        .question-meta:focus {
            background: #fffdf6;
            border-radius: 4px
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

        .preview.area {
            height: 56px
        }

        .preview.select:after {
            content: '⌄';
            float: right;
            color: #999
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

        .inspector {
            border-left: 1px solid var(--line);
            padding: 18px
        }

        .inspector h3 {
            font-size: 13px;
            margin: 0 0 6px
        }

        .inspector>p {
            font-size: 11px;
            color: #999;
            margin: 0 0 19px;
            line-height: 1.5
        }

        .control {
            margin-bottom: 17px
        }

        .control label {
            display: block;
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 7px;
            color: #59564f
        }

        .control input,
        .control textarea,
        .control select {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 6px;
            background: white;
            padding: 10px;
            font-size: 12px;
            font-family: inherit
        }

        .control textarea {
            height: 68px;
            resize: vertical
        }

        .toggle {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid var(--line);
            padding-top: 16px
        }

        .toggle input {
            accent-color: var(--gold);
            width: 18px;
            height: 18px
        }

        .hint {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 13px 22px;
            background: #fff;
            border-top: 1px solid var(--line);
            font-size: 11px;
            color: #8d8981
        }

        .empty {
            border: 1px dashed #d8d0c4;
            border-radius: 12px;
            padding: 54px;
            text-align: center;
            color: #999;
            background: #fff
        }

        .toast {
            position: fixed;
            right: 24px;
            bottom: 24px;
            background: #182b26;
            color: #e7fff7;
            padding: 13px 18px;
            border-radius: 8px;
            box-shadow: 0 5px 18px #0003;
            display: none;
            font-size: 13px
        }

        .toast.show {
            display: block
        }

        /* ===== Elemen pertanyaan per-tipe ===== */
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

        .question.text-field .question-label {
            font-size: 12px
        }

        .question.email-field .preview {
            font-size: 0
        }

        .question.email-field .preview::after {
            content: 'Masukkan alamat email (contoh: nama@email.com)';
            font-size: 11px;
            color: #aaa
        }

        .choice-field .preview {
            display: flex;
            flex-direction: column;
            gap: 10px;
            background: transparent;
            border: 0;
            padding: 0
        }

        .choice-option {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #5b5852;
            font-size: 11px
        }

        .choice-option input {
            accent-color: var(--gold)
        }

        .file-preview {
            height: 91px;
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

        .trash-icon {
            font-size: 18px
        }

        .fake-select {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #aaa
        }

        .question.dragging-question {
            opacity: .45
        }

        #questionList.drag-target {
            outline: 2px dashed var(--gold);
            outline-offset: 6px
        }

        #saveForm:disabled {
            opacity: .65;
            cursor: wait
        }

        /* ===== Inline edit di canvas ===== */
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

        .option-text:focus {
            background: #fffdf6;
            border-radius: 4px
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

        /* ===== Responsive ===== */
        @media(max-width:980px) {
            .builder {
                grid-template-columns: 200px minmax(380px, 1fr)
            }

            .inspector {
                display: none
            }
        }

        @media(max-width:650px) {
            .side {
                width: 70px
            }

            .brand {
                font-size: 0;
                padding-left: 12px
            }

            .brand:before {
                content: 'A';
                font: 700 26px "Inter", Arial, sans-serif;
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
                width: 50px;
                justify-content: center
            }

            .builder {
                display: block
            }

            .library {
                display: flex;
                gap: 9px;
                overflow: auto;
                padding: 11px
            }

            .library .pane-title {
                display: none
            }

            .field-item {
                min-width: 150px;
                margin: 0;
                padding: 10px
            }

            .field-item small {
                display: none
            }

            .canvas {
                padding: 18px 12px
            }

            .top {
                height: auto
            }

            .top-actions {
                overflow: auto
            }

        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

            body {
                font-family: Inter, Arial, sans-serif
            }


            .question.heading,
            .question.subheading {
                padding: 20px 16px
            }

            .question.heading .question-label {
                font: 700 25px/1.15 "Inter", Arial, sans-serif;
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

            .fake-select {
                display: flex;
                justify-content: space-between;
                align-items: center;
                color: #aaa;
            }

            .choice-field .preview {
                display: flex;
                flex-direction: column;
                gap: 8px;
                background: transparent;
                border: 0;
                padding: 0;
            }

            .choice-option {
                display: flex;
                align-items: center;
                gap: 8px;
                color: #5b5852;
                font-size: 9px;
            }

            .choice-option input {
                accent-color: var(--gold);
            }

            .file-preview {
                height: 76px;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                border: 1px dashed #d5cbbd;
                border-radius: 6px;
                background: #fcfbf9;
                color: #8d8981;
            }

            .file-icon {
                color: var(--gold);
                font-size: 16px;
                margin-bottom: 4px;
            }

            .question.dragging-question {
                opacity: .45;
            }

            #questionList.drag-target {
                outline: 2px dashed var(--gold);
                outline-offset: 5px;
            }

            #saveForm:disabled {
                opacity: .65;
                cursor: wait;
            }

            /* Label jadi bisa diklik & diketik langsung, kayak Google Form */
            .question-label {
                outline: none;
                cursor: text;
            }

            .question-label:focus {
                background: #fffdf6;
                border-radius: 4px;
                box-shadow: 0 0 0 2px #ecd9a6;
            }

            .question-label:empty:before {
                content: 'Tulis pertanyaan...';
                color: #b7b3ac;
            }

            /* Badge "Wajib" sekarang otomatis dari CSS, bukan elemen <em> lagi */
            .question[data-required="true"] .question-label::after {
                content: ' *';
                color: #e26855;
            }

            /* Placeholder text-field / textarea / number / email, ikut bisa diklik */
            .preview-input {
                outline: none;
                cursor: text;
                min-height: 16px;
            }

            .preview-input:focus {
                background: #fffdf6;
                border-radius: 4px;
            }

            .preview-input:empty:before {
                content: 'Ketik placeholder...';
                color: #aaa;
            }

            /* Opsi radio/checkbox/dropdown, tiap opsi bisa diklik + ada tombol hapus/tambah */
            .option-text {
                outline: none;
                cursor: text;
                flex: 1;
            }

            .option-text:focus {
                background: #fffdf6;
                border-radius: 3px;
            }

            .option-remove {
                border: 0;
                background: none;
                color: #bbb;
                cursor: pointer;
                font-size: 13px;
                line-height: 1;
            }

            .option-remove:hover {
                color: #c15b45;
            }

            .add-option {
                border: 0;
                background: none;
                color: var(--gold);
                cursor: pointer;
                font-size: 9px;
                padding: 4px 0;
                text-align: left;
                display: block;
            }

            .add-option:hover {
                text-decoration: underline;
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
                <div id="questionList">
                    @forelse($questions as $question)
                        <article class="question" draggable="true" data-id="{{ $question->id }}"
                            data-type="{{ $question->tipe_input }}" data-label="{{ $question->label }}"
                            data-required="{{ $question->wajib ? 'true' : 'false' }}"
                            data-options='@json($question->pilihan_jawaban ?? [])' data-config='@json($question->konfigurasi ?? [])'>
                            <div class="question-head">
                                <div class="question-label" contenteditable="true" data-role="label" spellcheck="false"></div>
                                <div class="question-meta" contenteditable="true" data-role="help" spellcheck="false"></div>
                                <div class="question-actions">
                                    <button class="remove" type="button" title="Hapus elemen"
                                        aria-label="Hapus elemen">
                                        <span class="trash-icon">🗑</span>
                                    </button>
                                    <span class="grip">⠿</span>
                                </div>
                            </div>
                            <div class="preview"></div>
                        </article>
                    @empty
                        <div class="empty" id="emptyState">Tarik komponen dari panel kiri ke sini<br><small>atau klik
                                komponen untuk menambahkannya</small></div>
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
<div class="toast" id="toast">Formulir berhasil disimpan.</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const list = document.getElementById('questionList');
        const dropzone = document.getElementById('dropzone');
        const empty = document.getElementById('emptyState');

        const inspectorEmpty = document.getElementById('inspectorEmpty');
        const inspectorForm = document.getElementById('inspectorForm');

        const editLabel = document.getElementById('editLabel');
        const editHelp = document.getElementById('editHelp');
        const editPlaceholder = document.getElementById('editPlaceholder');
        const editOptions = document.getElementById('editOptions');
        const editRequired = document.getElementById('editRequired');

        const saveButton = document.getElementById('saveForm');
        const eventPicker = document.getElementById('eventPicker');
        const toast = document.getElementById('toast');

        let selected = null;
        let draggedQuestion = null;
        let draggedLibraryItem = null;

        /*
        |--------------------------------------------------------------------------
        | Ambil eventId — JANGAN cuma andalkan Blade `{{ $event?->id }}`,
        | karena kalau variabel di controller berbeda nama / null, draft
        | jadi diam-diam gak pernah kesimpan tanpa ada error apapun.
        |
        | Urutan prioritas:
        | 1. Dari URL halaman (/admin/form-builder/3 -> "3")
        | 2. Dari Blade (fallback)
        | 3. Dari option yang lagi keselect di dropdown eventPicker
        |--------------------------------------------------------------------------
        */
        function resolveEventId() {
            const fromUrl = window.location.pathname.match(/form-builder\/(\d+)/);
            if (fromUrl) return fromUrl[1];

            const fromBlade = '{{ $event?->id }}';
            if (fromBlade) return fromBlade;

            const picker = document.getElementById('eventPicker');
            if (picker && picker.value) return picker.value;

            return '';
        }

        const eventId = resolveEventId();
        const draftKey = `formBuilderDraft:${eventId || 'new'}`;

        // Debug permanen — buka tab Console, harus muncul otomatis begitu halaman dimuat.
        console.log('[FormBuilder] eventId =', JSON.stringify(eventId), '| draftKey =', draftKey);

        if (!eventId) {
            console.warn('[FormBuilder] Belum ada Event yang dipilih/tersimpan — draft disimpan ' +
                'sementara di bawah key "formBuilderDraft:new" dan BARU akan pindah ke key per-event');
        }

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
                showDraftIndicator();
            } catch (error) {
                console.error('[FormBuilder] Gagal simpan draft:', error);
            }
        }

        // saveDraft() langsung save, tanpa debounce, supaya gampang dites.
        function saveDraft() {
            saveDraftNow();
        }

        function clearDraft() {
            if (!draftKey) return;
            localStorage.removeItem(draftKey);
        }

        function showDraftIndicator() {
            let indicator = document.getElementById('draftIndicator');
            if (!indicator) {
                indicator = document.createElement('div');
                indicator.id = 'draftIndicator';
                indicator.style = 'position:fixed;left:20px;bottom:20px;background:#182b26;color:#9ee6c8;padding:6px 12px;border-radius:6px;font-size:10px;opacity:0;transition:opacity .2s;z-index:999';
                indicator.textContent = '● Draft tersimpan otomatis';
                document.body.appendChild(indicator);
            }
            indicator.style.opacity = '1';
            clearTimeout(indicator._hideTimer);
            indicator._hideTimer = setTimeout(() => indicator.style.opacity = '0', 1200);
        }

        const fieldTypes = {
            text: 'Text',
            email: 'Email',
            number: 'Angka',
            textarea: 'Teks Panjang',
            select: 'Dropdown',
            radio: 'Radio Option',
            checkbox: 'Checkbox',
            file: 'Upload File',
            heading: 'Judul',
            subheading: 'Sub Judul'
        };

        /*
        |--------------------------------------------------------------------------
        | Helper
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | Render field
        |--------------------------------------------------------------------------
        */

        function renderField(element, force = false) {

            const type = element.dataset.type;
            const options = getOptions(element);
            const config = getConfig(element);

            const preview = element.querySelector('.preview');
            const labelElement = element.querySelector('.question-label');
            const metaElement = element.querySelector('.question-meta');

            if (!preview || !labelElement) {
                return;
            }

            element.classList.remove('heading', 'subheading', 'text-field', 'email-field', 'choice-field',
                'file-field');

            if (type === 'heading') element.classList.add('heading');
            if (type === 'subheading') element.classList.add('subheading');
            if (type === 'text') element.classList.add('text-field');
            if (type === 'email') element.classList.add('email-field');
            if (['radio', 'checkbox', 'select'].includes(type)) element.classList.add('choice-field');
            if (type === 'file') element.classList.add('file-field');

            /*
            | LABEL — jangan timpa isi kalau sedang lagi diketik user (activeElement),
            | supaya kursor gak lompat ke awal tiap kali render ulang.
            */
            if (document.activeElement !== labelElement) {
                labelElement.textContent = element.dataset.label || '';
            }

            if (metaElement && document.activeElement !== metaElement) {
                metaElement.textContent = config.help || fieldTypes[type] || type;
            }

            if (type === 'heading' || type === 'subheading') {
                preview.innerHTML = '';
                return;
            }

            /*
            | RADIO / CHECKBOX — opsi bisa diklik & diedit langsung + tombol hapus/tambah
            */
            if (type === 'radio' || type === 'checkbox') {
                if (force || !preview.contains(document.activeElement)) {
                    const optionsHtml = options.map((option, index) => `
                <label class="choice-option">
                    <input type="${type}" disabled>
                    <span class="option-text" contenteditable="true" data-role="option" data-index="${index}">${escapeHTML(option)}</span>
                    <button type="button" class="option-remove" data-role="remove-option" data-index="${index}" title="Hapus opsi">×</button>
                </label>
            `).join('');

                    preview.innerHTML = optionsHtml +
                        `<button type="button" class="add-option" data-role="add-option">+ Tambah opsi</button>`;
                }
                return;
            }

            /*
            | DROPDOWN — sama seperti radio, tapi tampilannya list opsi
            */
            if (type === 'select') {
                if (force || !preview.contains(document.activeElement)) {
                    const optionsHtml = options.length ?
                        options.map((option, index) => `
                    <div class="choice-option">
                        <span class="option-text" contenteditable="true" data-role="option" data-index="${index}">${escapeHTML(option)}</span>
                        <button type="button" class="option-remove" data-role="remove-option" data-index="${index}" title="Hapus opsi">×</button>
                    </div>
                `).join('') :
                        '<div class="choice-option" style="color:#aaa">Belum ada pilihan</div>';

                    preview.innerHTML = optionsHtml +
                        `<button type="button" class="add-option" data-role="add-option">+ Tambah opsi</button>`;
                }
                return;
            }

            /*
            | FILE — tidak diedit langsung, cukup visual
            */
            if (type === 'file') {
                preview.innerHTML = `
            <div class="file-preview">
                <div class="file-icon">▣</div>
                <div>Tarik berkas ke sini atau pilih file</div>
            </div>
        `;
                return;
            }

            /*
            | TEXT / EMAIL / NUMBER / TEXTAREA — placeholder-nya bisa diklik & diketik langsung
            */
            if (force || !preview.contains(document.activeElement)) {
                const defaultText = {
                    email: 'Masukkan alamat email (contoh: nama@email.com)',
                    number: 'Masukkan angka',
                    textarea: 'Masukkan jawaban peserta',
                } [type] || 'Masukkan jawaban peserta';

                preview.innerHTML =
                    `<div class="preview-input" contenteditable="true" data-role="placeholder">${escapeHTML(config.placeholder || defaultText)}</div>`;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Select Question
        |--------------------------------------------------------------------------
        */

        function selectQuestion(element) {

            document.querySelectorAll('.question').forEach(question => {
                question.classList.remove('selected');
            });

            selected = element;

            if (!selected) {
                inspectorForm.style.display = 'none';
                inspectorEmpty.style.display = 'block';
                return;
            }

            selected.classList.add('selected');

            inspectorEmpty.style.display = 'none';
            inspectorForm.style.display = 'block';

            const config = getConfig(selected);
            const options = getOptions(selected);

            editLabel.value = selected.dataset.label || '';
            editHelp.value = config.help || '';
            editPlaceholder.value = config.placeholder || '';
            editOptions.value = options.join('\n');
            editRequired.checked =
                selected.dataset.required === 'true';

            /*
            | Hide options when field does not need choices
            */

            const optionsControl =
                editOptions.closest('.control');

            if (optionsControl) {

                optionsControl.style.display = ['select', 'radio', 'checkbox']
                    .includes(selected.dataset.type) ?
                    'block' :
                    'none';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Update Selected Field
        |--------------------------------------------------------------------------
        */

        function updateSelected() {

            if (!selected) {
                return;
            }

            const type = selected.dataset.type;

            selected.dataset.label = editLabel.value.trim();

            selected.dataset.required =
                editRequired.checked ? 'true' : 'false';

            const options =
                editOptions.value
                .split('\n')
                .map(item => item.trim())
                .filter(item => item !== '');

            selected.dataset.options =
                JSON.stringify(
                    ['select', 'radio', 'checkbox'].includes(type) ?
                    options : []
                );

            selected.dataset.config =
                JSON.stringify({
                    help: editHelp.value.trim(),
                    placeholder: editPlaceholder.value.trim()
                });

            renderField(selected);

            saveDraft();
        }

        /*
        |--------------------------------------------------------------------------
        | Create Field
        |--------------------------------------------------------------------------
        */

        function addField(type, label) {

            if (!type) {
                return;
            }

            if (empty) {
                empty.remove();
            }

            let finalLabel = label;

            if (type === 'heading') {
                finalLabel = 'Pendaftaran Event Alnay.ID';
            }

            if (type === 'subheading') {
                finalLabel =
                    'Kompetisi & Pergelaran Busana Adat Nusantara';
            }

            let options = [];

            if (['radio', 'checkbox', 'select'].includes(type)) {
                options = [
                    'Pilihan 1',
                    'Pilihan 2'
                ];
            }

            const element =
                document.createElement('article');

            element.className = 'question';
            element.draggable = true;

            element.dataset.id = '';
            element.dataset.type = type;
            element.dataset.label = finalLabel;
            element.dataset.required = 'false';
            element.dataset.options =
                JSON.stringify(options);

            element.dataset.config =
                JSON.stringify({
                    help: '',
                    placeholder: ''
                });

            element.innerHTML = `
                <div class="question-head">
                    <div>
                        <div class="question-label" contenteditable="true" data-role="label" spellcheck="false"></div>
                        <div class="question-meta" contenteditable="true" data-role="help" spellcheck="false"></div>
                    </div>
                    <div class="question-actions">
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

            saveDraft();
        }

        /*
        |--------------------------------------------------------------------------
        | Bind Question
        |--------------------------------------------------------------------------
        */

        function bindQuestion(element) {

            /*
            | Click
            */

            element.addEventListener('click', function(event) {

                if (
                    event.target.closest('.remove')
                ) {
                    return;
                }

                selectQuestion(element);
            });

            /*
            | Drag start
            */

            element.addEventListener('dragstart', function(event) {

                draggedQuestion = element;

                element.classList.add('dragging-question');

                event.dataTransfer.effectAllowed = 'move';
                event.dataTransfer.setData(
                    'text/question-id',
                    element.dataset.id || ''
                );
            });

            /*
            | Drag end
            */

            element.addEventListener('dragend', function() {

                element.classList.remove(
                    'dragging-question'
                );

                draggedQuestion = null;

                document
                    .querySelectorAll('.question')
                    .forEach(question => {
                        question.classList.remove(
                            'drag-over'
                        );
                    });

                saveDraftNow();
            });

            /*
            | Remove
            */

            const removeButton =
                element.querySelector('.remove');

            if (removeButton) {

                removeButton.addEventListener(
                    'click',
                    function(event) {

                        event.stopPropagation();

                        const wasSelected =
                            selected === element;

                        element.remove();

                        saveDraft();

                        if (wasSelected) {
                            selected = null;

                            inspectorForm.style.display =
                                'none';

                            inspectorEmpty.style.display =
                                'block';
                        }

                        if (
                            !list.querySelector('.question')
                        ) {

                            if (
                                !document.getElementById(
                                    'emptyState'
                                )
                            ) {

                                const emptyElement =
                                    document.createElement('div');

                                emptyElement.id =
                                    'emptyState';

                                emptyElement.className =
                                    'empty';

                                emptyElement.innerHTML =
                                    'Tarik komponen dari panel kiri ke sini<br>' +
                                    '<small>atau klik komponen untuk menambahkannya</small>';

                                list.prepend(emptyElement);
                            }
                        }
                    }
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Library Field
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll('.field-item')
            .forEach(item => {

                /*
                | Email harus benar-benar bertipe email
                */

                if (
                    item.dataset.label === 'Email'
                ) {
                    item.dataset.type = 'email';
                }

                /*
                | Click
                */

                item.addEventListener(
                    'click',
                    function() {

                        let type =
                            item.dataset.type;

                        let label =
                            item.dataset.label;

                        if (label === 'Judul') {
                            type = 'heading';
                        }

                        if (label === 'Sub Judul') {
                            type = 'subheading';
                        }

                        addField(type, label);
                    }
                );

                /*
                | Drag start
                */

                item.addEventListener(
                    'dragstart',
                    function(event) {

                        draggedLibraryItem = item;

                        event.dataTransfer.effectAllowed =
                            'copy';

                        event.dataTransfer.setData(
                            'text/field-type',
                            item.dataset.type
                        );

                        event.dataTransfer.setData(
                            'text/field-label',
                            item.dataset.label
                        );
                    }
                );

                item.addEventListener(
                    'dragend',
                    function() {
                        draggedLibraryItem = null;
                    }
                );
            });

        /*
        |--------------------------------------------------------------------------
        | Drop Field ke Canvas
        |--------------------------------------------------------------------------
        */

        list.addEventListener(
            'dragover',
            function(event) {

                event.preventDefault();

                /*
                | Jika yang sedang di-drag adalah question,
                | berarti reorder.
                */

                if (draggedQuestion) {

                    const target =
                        event.target.closest('.question');

                    if (
                        !target ||
                        target === draggedQuestion
                    ) {
                        return;
                    }

                    const rect =
                        target.getBoundingClientRect();

                    const before =
                        event.clientY <
                        rect.top + rect.height / 2;

                    if (before) {
                        list.insertBefore(
                            draggedQuestion,
                            target
                        );
                    } else {
                        list.insertBefore(
                            draggedQuestion,
                            target.nextSibling
                        );
                    }

                    return;
                }

                /*
                | Library field
                */

                if (draggedLibraryItem) {

                    list.classList.add(
                        'drag-target'
                    );
                }
            }
        );

        list.addEventListener(
            'dragleave',
            function() {

                list.classList.remove(
                    'drag-target'
                );
            }
        );

        list.addEventListener(
            'drop',
            function(event) {

                event.preventDefault();

                list.classList.remove(
                    'drag-target'
                );

                /*
                | Tambah field dari library
                */

                const type =
                    event.dataTransfer.getData(
                        'text/field-type'
                    );

                const label =
                    event.dataTransfer.getData(
                        'text/field-label'
                    );

                if (
                    type &&
                    label &&
                    !draggedQuestion
                ) {

                    addField(type, label);
                }

                saveDraft();
            }
        );

        list.addEventListener('input', function (event) {
            const target = event.target;
            const question = target.closest('.question');
            if (!question) return;

            if (target.dataset.role === 'label') {
                question.dataset.label = target.textContent.trim();
                if (selected === question) editLabel.value = question.dataset.label;
            } else if (target.dataset.role === 'help') {
                const config = getConfig(question);
                config.help = target.textContent.trim();
                question.dataset.config = JSON.stringify(config);
                if (selected === question) editHelp.value = config.help;
            } else if (target.dataset.role === 'placeholder') {
                const config = getConfig(question);
                config.placeholder = target.textContent.trim();
                question.dataset.config = JSON.stringify(config);
                if (selected === question) editPlaceholder.value = config.placeholder;
            } else if (target.dataset.role === 'option') {
                const options = getOptions(question);
                const index = Number(target.dataset.index);
                options[index] = target.textContent.trim();
                question.dataset.options = JSON.stringify(options);
                if (selected === question) editOptions.value = options.join('\n');
            } else {
                return;
            }

            saveDraft();
        });

        list.addEventListener('click', function(event) {
            const addBtn = event.target.closest('[data-role="add-option"]');
            if (addBtn) {
                const question = addBtn.closest('.question');
                const options = getOptions(question);
                options.push('Opsi ' + (options.length + 1));
                question.dataset.options = JSON.stringify(options);
                renderField(question, true);

                saveDraft();

                if (selected === question) editOptions.value = options.join('\n');

                const allOptions = question.querySelectorAll('[data-role="option"]');
                allOptions[allOptions.length - 1]?.focus();
                return;
            }

            const removeBtn = event.target.closest('[data-role="remove-option"]');
            if (removeBtn) {
                const question = removeBtn.closest('.question');
                const options = getOptions(question);
                options.splice(Number(removeBtn.dataset.index), 1);
                question.dataset.options = JSON.stringify(options);
                renderField(question, true);

                saveDraft();

                if (selected === question) editOptions.value = options.join('\n');
                return;
            }
        });

        list.addEventListener('focusout', function (event) {
            if (event.target.dataset.role !== 'help') return;

            const question = event.target.closest('.question');
            const config = getConfig(question);

            if (!config.help) {
                event.target.textContent = fieldTypes[question.dataset.type] || question.dataset.type;
            }
        });

        /*
        |--------------------------------------------------------------------------
        | Dropzone
        |--------------------------------------------------------------------------
        */

        dropzone.addEventListener(
            'dragover',
            function(event) {

                event.preventDefault();

                dropzone.classList.add(
                    'dragging'
                );
            }
        );

        dropzone.addEventListener(
            'dragleave',
            function() {

                dropzone.classList.remove(
                    'dragging'
                );
            }
        );

        dropzone.addEventListener(
            'drop',
            function(event) {

                event.preventDefault();

                dropzone.classList.remove(
                    'dragging'
                );

                const type =
                    event.dataTransfer.getData(
                        'text/field-type'
                    );

                const label =
                    event.dataTransfer.getData(
                        'text/field-label'
                    );

                if (type && label) {
                    addField(type, label);
                }
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Existing Questions
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll('.question')
            .forEach(question => {

                bindQuestion(question);
                renderField(question);
            });

        function restoreDraft() {
            if (!draftKey) {
                console.log('[FormBuilder] restoreDraft dilewati karena draftKey kosong.');
                return;
            }

            let draft;
            try {
                draft = JSON.parse(localStorage.getItem(draftKey) || 'null');
            } catch (error) {
                console.warn('[FormBuilder] Draft rusak, dihapus:', error);
                localStorage.removeItem(draftKey);
                return;
            }

            if (!draft || !Array.isArray(draft.fields) || !draft.fields.length) {
                console.log('[FormBuilder] Tidak ada draft tersimpan untuk', draftKey);
                return;
            }

            const menitLalu = Math.max(1, Math.round((Date.now() - draft.savedAt) / 60000));
            const mauRestore = confirm(
                `Ditemukan perubahan formulir yang belum tersimpan (± ${menitLalu} menit lalu). Muat perubahan itu kembali?`
            );

            if (!mauRestore) {
                clearDraft();
                return;
            }

            list.querySelectorAll('.question').forEach(question => question.remove());
            empty?.remove();

            draft.fields.forEach(field => {
                const element = document.createElement('article');
                element.className = 'question';
                element.draggable = true;
                element.dataset.id = field.id || '';
                element.dataset.type = field.tipe_input || 'text';
                element.dataset.label = field.label || '';
                element.dataset.required = field.wajib ? 'true' : 'false';
                element.dataset.options = JSON.stringify(Array.isArray(field.pilihan_jawaban) ? field.pilihan_jawaban : []);
                element.dataset.config = JSON.stringify(field.konfigurasi || {});
                element.innerHTML = `
                    <div class="question-head">
                        <div>
                            <div class="question-label" contenteditable="true" data-role="label" spellcheck="false"></div>
                            <div class="question-meta" contenteditable="true" data-role="help" spellcheck="false"></div>
                        </div>
                        <div class="question-actions">
                            <button class="remove" type="button" title="Hapus elemen" aria-label="Hapus elemen"><span class="trash-icon">🗑</span></button>
                            <span class="grip">⠿</span>
                        </div>
                    </div>
                    <div class="preview"></div>`;
                list.appendChild(element);
                bindQuestion(element);
                renderField(element, true);
            });

            console.log('[FormBuilder] Draft berhasil di-restore,', draft.fields.length, 'field.');
        }

        restoreDraft();

        window.addEventListener('beforeunload', saveDraftNow);
        window.addEventListener('pagehide', saveDraftNow);

        /*
        |--------------------------------------------------------------------------
        | Inspector Events
        |--------------------------------------------------------------------------
        */

        [
            editLabel,
            editHelp,
            editPlaceholder,
            editOptions,
            editRequired
        ].forEach(input => {

            if (!input) {
                return;
            }

            input.addEventListener(
                'input',
                updateSelected
            );

            input.addEventListener(
                'change',
                updateSelected
            );
        });

        /*
        |--------------------------------------------------------------------------
        | Event Picker
        |--------------------------------------------------------------------------
        */

        if (eventPicker) {

            eventPicker.addEventListener(
                'change',
                function(event) {

                    const newEventId =
                        event.target.value;

                    if (!newEventId) {
                        return;
                    }

                    window.location.href =
                        '/admin/form-builder/' +
                        newEventId;
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Save Form
        |--------------------------------------------------------------------------
        */

        if (saveButton) {

            saveButton.addEventListener(
                'click',
                async function() {

                    if (!eventId) {

                        alert(
                            'Pilih event terlebih dahulu.'
                        );

                        return;
                    }

                    const questions = [
                        ...document.querySelectorAll(
                            '#questionList .question'
                        )
                    ];

                    if (!questions.length) {

                        alert(
                            'Tambahkan minimal satu field.'
                        );

                        return;
                    }

                    const fields = getFieldsFromCanvas();

                    /*
                    | Disable button selama proses save
                    */

                    const originalText =
                        saveButton.innerHTML;

                    saveButton.disabled = true;

                    saveButton.innerHTML =
                        'Menyimpan...';

                    try {

                        const response =
                            await fetch(
                                '{{ $event ? route('admin.form-builder.save', $event) : '#' }}', {
                                    method: 'POST',

                                    headers: {
                                        'Content-Type': 'application/json',

                                        'Accept': 'application/json',

                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },

                                    body: JSON.stringify({
                                        fields: fields
                                    })
                                }
                            );

                        const data =
                            await response.json()
                            .catch(() => ({}));

                        if (!response.ok) {

                            console.error(
                                'Form save error:',
                                data
                            );

                            throw new Error(
                                data.message ||
                                'Form gagal disimpan.'
                            );
                        }

                        clearDraft();

                        toast.textContent =
                            data.message ||
                            'Formulir berhasil disimpan.';

                        toast.classList.add(
                            'show'
                        );

                        setTimeout(
                            function() {
                                toast.classList.remove(
                                    'show'
                                );
                            },
                            2500
                        );

                    } catch (error) {

                        console.error(error);

                        alert(
                            error.message ||
                            'Terjadi kesalahan saat menyimpan formulir.'
                        );

                    } finally {

                        saveButton.disabled =
                            false;

                        saveButton.innerHTML =
                            originalText;
                    }
                }
            );
        }

    });
</script>

@include('admin.partials.sidebar', ['active' => 'form-builder', 'event' => $event])</body>

</html>
