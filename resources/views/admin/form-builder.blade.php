<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Form Builder | ALNAY.ID</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @include('admin.partials.pwa-head')
    <link rel="stylesheet" href="{{ asset('css/admin-sidebar.css') }}">
    <style>
        /* =====================================================================
           DESIGN TOKENS & MODERN POLISH
        ===================================================================== */
        :root {
            --ink: #1f1d1a;
            --dark: #12100e;
            --gold: #c38b22;
            --gold-light: #fdf8ee;
            --cream: #faf9f6;
            --line: #e2ded8;
            --muted: #82807a;
            --green: #089b73;

            --admin-side: 235px;
            --hdr: 76px;
            --lib-w: clamp(210px, 21vw, 250px);
            --radius: 12px;

            --pad-x: clamp(16px, 3.5vw, 36px);
            --pad-y-t: clamp(20px, 4vw, 40px);
            --pad-y-b: clamp(40px, 6vw, 80px);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html,
        body {
            max-width: 100%;
            overflow-x: clip;
        }

        body {
            margin: 0;
            background: var(--cream);
            color: var(--ink);
            font: 15px/1.5 "Inter", Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
            text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        /* =====================================================================
           SHELL
        ===================================================================== */
        .shell {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            min-width: 0;
            margin-left: var(--admin-side);
            width: calc(100% - var(--admin-side));
            max-width: 100%;
            padding-top: var(--hdr);
        }

        .workspace {
            min-width: 0;
            flex: 1 1 auto;
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        /* LEGACY */
        .side { position: relative; width: var(--side-w, 220px); flex: 0 0 var(--side-w, 220px); background: var(--dark); color: #aaa59c; padding: 18px 10px 96px; }
        .brand { font: 700 22px/1 "Inter", Arial, sans-serif; color: #fff; padding: 4px 12px 30px; }
        .brand span { color: #cc962f; }
        .section { font-size: 10px; letter-spacing: .7px; text-transform: uppercase; color: #777169; margin: 0 12px 12px; }
        .nav a { display: flex; gap: 11px; align-items: center; padding: 12px 11px; border-radius: 8px; color: #a7a29a; text-decoration: none; line-height: 1.3; transition: background .15s ease, color .15s ease; }
        .nav a:hover { background: #26221d; color: #fff; }
        .nav a.active { background: #302b25; color: #fff; }
        .step { width: 21px; height: 21px; border-radius: 50%; background: #332e28; display: grid; place-items: center; font-size: 11px; flex: none; }
        .active .step { background: var(--gold); color: #fff; }
        .badge { margin-left: auto; background: #d94d43; color: #fff; border-radius: 12px; padding: 3px 7px; font-size: 10px; }
        .user { position: absolute; left: 10px; right: 10px; bottom: 20px; background: #29251f; border-radius: 9px; padding: 11px; display: flex; gap: 11px; align-items: center; color: #fff; font-size: 12px; }
        .avatar { width: 33px; height: 33px; border-radius: 50%; background: #f4f0e7; color: #4d4435; display: grid; place-items: center; font-weight: 700; flex: none; }
        .user small { display: block; color: #aaa59e; font-size: 10px; margin-top: 4px; }
        .preview-link { display: block; margin: 18px 8px 0; padding: 10px 14px; background: #fff; border: 1px solid var(--line); border-radius: 8px; color: #444; text-align: center; font-size: 12px; font-weight: 500; text-decoration: none; transition: background .15s ease; }
        .preview-link:hover { background: #f3f1ed; }
        .top { background: #201f1d; color: #fff; padding: 16px 22px 14px; position: relative; }
        .crumb { color: #aaa59e; font-size: 11px; }
        .crumb b { color: #d7a43c; font-weight: normal; }
        .title-row { display: flex; align-items: center; gap: 12px; margin-top: 9px; flex-wrap: wrap; }
        .title-icon { width: 33px; height: 33px; background: #f3eee3; color: #37322b; border-radius: 7px; display: grid; place-items: center; font-weight: 700; }
        .top h1 { font-size: 17px; margin: 0; }
        .top p { margin: 3px 0 0; color: #b6b1aa; font-size: 11px; }
        .top-actions { display: flex; gap: 10px; margin-top: 12px; flex-wrap: wrap; }
        .top-actions a, .top-actions button { border: 1px solid #57534e; border-radius: 5px; padding: 9px 14px; background: #2d2b28; color: #fff; font-size: 11px; text-decoration: none; cursor: pointer; }
        .top-actions .save { background: var(--gold); border-color: var(--gold); }

        /* =====================================================================
           BUILDER GRID
        ===================================================================== */
        .builder {
            display: grid;
            grid-template-columns: var(--lib-w) minmax(0, 1fr);
            min-height: calc(100vh - var(--hdr));
            min-width: 0;
            flex: 1 1 auto;
            align-items: stretch;
        }

        /* =====================================================================
           LIBRARY SIDEBAR (Refined Styling)
        ===================================================================== */
        .library {
            background: #ffffff;
            border-right: 1px solid var(--line);
            min-width: 0;
        }

        .library-scroll {
            position: sticky;
            top: var(--hdr);
            max-height: calc(100vh - var(--hdr));
            overflow-y: auto;
            overscroll-behavior: contain;
            padding: 20px 14px;
            scrollbar-width: thin;
            scrollbar-color: #d8d3ca transparent;
        }

        .library-scroll::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        .library-scroll::-webkit-scrollbar-thumb {
            background: #d8d3ca;
            border-radius: 4px;
        }

        .pane-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-transform: uppercase;
            letter-spacing: .9px;
            font-size: 11px;
            font-weight: 700;
            color: #6e6b65;
            margin: 0 6px 16px;
        }

        .count {
            background: var(--gold-light);
            border: 1px solid #ecd8ab;
            border-radius: 12px;
            padding: 3px 10px;
            color: var(--gold);
            font-size: 10px;
            font-weight: 600;
            text-transform: none;
            letter-spacing: 0;
        }

        .field-item {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 12px 14px;
            margin-bottom: 10px;
            cursor: grab;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
            transition: all .2s cubic-bezier(0.16, 1, 0.3, 1);
            min-width: 0;
        }

        .field-item:hover {
            border-color: var(--gold);
            background: #fffcf7;
            box-shadow: 0 4px 12px rgba(195, 139, 34, 0.1);
            transform: translateY(-1px);
        }

        .field-item * {
            pointer-events: none;
        }

        .drag {
            color: #b5b1a9;
            font-size: 16px;
            margin-right: 10px;
        }

        .field-item strong {
            font-size: 12.5px;
            color: var(--ink);
            font-weight: 600;
            word-break: break-word;
        }

        .field-item small {
            display: block;
            color: var(--muted);
            font-size: 11px;
            margin: 4px 0 0 26px;
            word-break: break-word;
        }

        /* =====================================================================
           CANVAS (Refined Layout & Cards)
        ===================================================================== */
        .canvas {
            padding: var(--pad-y-t) var(--pad-x) var(--pad-y-b);
            background: var(--cream);
            min-width: 0;
            max-width: 100%;
            overflow-x: clip;
        }

        .canvas-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--muted);
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.8px;
            margin-bottom: 20px;
            gap: 12px;
            flex-wrap: wrap;
        }

        .question {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 14px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
            padding: clamp(16px, 2.5vw, 22px);
            margin-bottom: 16px;
            position: relative;
            min-width: 0;
            max-width: 100%;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .question.selected {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(195, 139, 34, 0.15), 0 6px 20px rgba(0, 0, 0, 0.05);
        }

        .question-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: clamp(10px, 2vw, 18px);
            min-width: 0;
        }

        .question-head>div:first-child {
            min-width: 0;
            flex: 1 1 auto;
        }

        .question-label {
            font-size: 14.5px;
            font-weight: 700;
            color: var(--ink);
            outline: none;
            cursor: text;
            word-break: break-word;
            overflow-wrap: anywhere;
        }

        .question-label:focus {
            background: var(--gold-light);
            border-radius: 6px;
            padding: 2px 4px;
            box-shadow: 0 0 0 2px #ecd9a6;
        }

        .question-label:empty:before {
            content: 'Tulis pertanyaan...';
            color: #b7b3ac;
        }

        .question[data-required="true"] .question-label::after {
            content: ' *';
            color: #d94d43;
        }

        .question-meta {
            font-size: 11.5px;
            color: var(--muted);
            margin-top: 6px;
            outline: none;
            cursor: text;
            word-break: break-word;
            overflow-wrap: anywhere;
        }

        .question-meta:empty:before {
            content: 'Tambahkan keterangan (opsional)';
            color: #c7c3ba;
        }

        .grip {
            color: #c4c0b6;
            font-size: 18px;
            cursor: grab;
            user-select: none;
            padding: 2px 4px;
            border-radius: 4px;
            transition: color 0.15s;
        }
        .grip:hover { color: var(--ink); }

        .preview {
            margin-top: 16px;
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 14px;
            color: #777;
            font-size: 12px;
            background: #fcfcfb;
            word-break: break-word;
            overflow-wrap: anywhere;
            min-width: 0;
        }

        .dropzone {
            min-height: 64px;
            border: 2px dashed #d5cbbd;
            border-radius: var(--radius);
            display: grid;
            place-items: center;
            color: var(--muted);
            font-size: 12px;
            font-weight: 500;
            margin-top: 18px;
            text-align: center;
            padding: 16px;
            word-break: break-word;
            background: rgba(255, 255, 255, 0.5);
            transition: all 0.2s ease;
        }

        .dropzone.dragging {
            background: var(--gold-light);
            border-color: var(--gold);
            color: var(--gold);
        }

        /* ✅ Garis indikator posisi saat menyisipkan field baru di tengah kanvas */
        .drop-indicator {
            height: 3px;
            background: var(--gold);
            border-radius: 3px;
            margin: 6px 2px;
            box-shadow: 0 0 0 4px rgba(195, 139, 34, 0.15);
            pointer-events: none;
        }

        .empty {
            border: 2px dashed #d8d0c4;
            border-radius: var(--radius);
            padding: clamp(32px, 6vw, 60px) clamp(16px, 4vw, 40px);
            text-align: center;
            color: var(--muted);
            background: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
        }

        /* =====================================================================
           QUESTION ACTIONS & COMPONENTS
        ===================================================================== */
        .question-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: none;
        }

        .remove {
            width: 34px;
            height: 34px;
            border: 1px solid #f2dcd6;
            border-radius: 8px;
            background: #fff8f6;
            color: #d94d43;
            cursor: pointer;
            font-size: 15px;
            display: grid;
            place-items: center;
            flex: none;
            transition: all 0.15s ease;
        }

        .remove:hover {
            background: #d94d43;
            color: #fff;
            border-color: #d94d43;
        }

        .choice-option {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #4b4843;
            font-size: 12px;
            min-width: 0;
            margin-bottom: 8px;
        }
        .choice-option:last-child { margin-bottom: 0; }

        .file-preview {
            min-height: 80px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 1px dashed #d5cbbd;
            border-radius: 8px;
            background: #fcfbf9;
            color: var(--muted);
            padding: 16px;
            text-align: center;
        }

        .file-icon {
            color: var(--gold);
            font-size: 24px;
            margin-bottom: 6px;
        }

        .preview-input {
            outline: none;
            cursor: text;
            min-height: 20px;
            word-break: break-word;
            overflow-wrap: anywhere;
        }

        .preview-input:focus {
            background: var(--gold-light);
            border-radius: 4px;
            padding: 2px 4px;
        }

        .preview-input:empty:before {
            content: 'Ketik placeholder...';
            color: #aaa;
        }

        .option-text {
            outline: none;
            cursor: text;
            flex: 1 1 auto;
            min-width: 0;
            word-break: break-word;
            overflow-wrap: anywhere;
            padding: 2px 4px;
            border-radius: 4px;
        }
        .option-text:focus {
            background: var(--gold-light);
        }

        .option-remove {
            border: 0;
            background: none;
            color: #bbb;
            cursor: pointer;
            font-size: 16px;
            line-height: 1;
            flex: none;
            padding: 4px;
            border-radius: 4px;
            transition: color 0.15s;
        }

        .option-remove:hover {
            color: #d94d43;
            background: #fff2f0;
        }

        .add-option {
            border: 0;
            background: none;
            color: var(--gold);
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            padding: 6px 0;
            text-align: left;
            display: block;
            margin-top: 8px;
            transition: opacity 0.15s;
        }

        .add-option:hover {
            opacity: 0.8;
            text-decoration: underline;
        }

        /* =====================================================================
           REQUIRED SWITCH
        ===================================================================== */
        .required-switch {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            user-select: none;
            font-size: 11px;
            color: var(--muted);
            white-space: nowrap;
            flex: none;
            font-weight: 500;
        }

        .required-switch-track {
            width: 32px;
            height: 18px;
            border-radius: 999px;
            background: #dcd7ce;
            position: relative;
            transition: background .2s ease;
            flex: none;
        }

        .required-switch-thumb {
            position: absolute;
            top: 2px;
            left: 2px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
            transition: left .2s ease;
        }

        .required-switch.active .required-switch-track {
            background: #d94d43;
        }

        .required-switch.active .required-switch-thumb {
            left: 16px;
        }

        .required-switch.active .required-switch-label {
            color: #d94d43;
            font-weight: 700;
        }

        /* =====================================================================
           HEADINGS / SUBHEADINGS
        ===================================================================== */
        .question.heading .question-label {
            font: 700 clamp(20px, 3.2vw, 28px)/1.2 "Inter", Arial, sans-serif;
            color: var(--ink);
        }

        .question.subheading .question-label {
            font: 500 clamp(14px, 2vw, 16px)/1.5 "Inter", Arial, sans-serif;
            color: #615e58;
        }

        .question.heading .question-meta,
        .question.subheading .question-meta,
        .question.heading .preview,
        .question.subheading .preview {
            display: none;
        }

        /* =====================================================================
           MODAL
        ===================================================================== */
        .draft-modal-backdrop {
            position: fixed;
            inset: 0;
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: rgba(18, 16, 14, 0.6);
            backdrop-filter: blur(4px);
        }

        .draft-modal-backdrop.show {
            display: flex;
        }

        .draft-modal {
            width: min(440px, 100%);
            border: 1px solid var(--line);
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            padding: clamp(20px, 3.5vw, 28px);
        }

        .draft-modal h2 {
            font-size: 18px;
            margin-top: 0;
            color: var(--ink);
        }

        .draft-modal p {
            font-size: 13.5px;
            color: #555;
            line-height: 1.5;
        }

        .draft-modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 24px;
            flex-wrap: wrap;
        }

        .draft-modal-actions button {
            border: 1px solid #d5d1c8;
            border-radius: 8px;
            background: #fff;
            color: #4b4843;
            cursor: pointer;
            padding: 10px 16px;
            font-size: 12.5px;
            font-weight: 500;
            transition: background 0.15s;
        }

        .draft-modal-actions button:hover {
            background: #f5f3ef;
        }

        .draft-modal-actions .draft-restore {
            border-color: var(--gold);
            background: var(--gold);
            color: #fff;
            font-weight: 600;
        }
        .draft-modal-actions .draft-restore:hover {
            background: #b07c1e;
        }

        /* =====================================================================
           RESPONSIVE BREAKPOINTS
        ===================================================================== */
        @media (max-width: 1280px) {
            :root {
                --pad-x: clamp(14px, 2.5vw, 28px);
            }
        }

        @media (max-width: 1024px) {
            .shell {
                margin-left: 0;
                width: 100%;
            }
            body > header {
                left: 0 !important;
            }
            :root {
                --lib-w: clamp(190px, 23vw, 230px);
            }
        }

        @media (max-width: 900px) {
            .builder {
                grid-template-columns: minmax(0, 1fr);
                min-height: 0;
            }
            .library {
                border-right: 0;
                border-bottom: 1px solid var(--line);
                position: relative;
                z-index: 2;
                background: #faf8f5;
            }
            .library-scroll {
                position: relative;
                top: auto;
                z-index: 2;
                max-height: none;
                height: auto;
                display: flex;
                gap: 10px;
                padding: 14px 16px;
                overflow-x: auto;
                overflow-y: hidden;
                -webkit-overflow-scrolling: touch;
                border-bottom: 1px solid var(--line);
            }
            .field-item {
                flex: 0 0 auto;
                min-width: 150px;
                max-width: 200px;
                padding: 10px 12px;
                margin-bottom: 0;
            }
            .field-item:hover {
                transform: none;
            }
            .pane-title {
                display: none;
            }
        }

        @media (max-width: 768px) {
            :root {
                --pad-y-t: 16px;
                --pad-y-b: 48px;
                --pad-x: 14px;
            }
            .canvas-bar {
                margin-bottom: 14px;
            }
            .question-label {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            :root {
                --pad-y-t: 12px;
                --pad-y-b: 40px;
                --pad-x: 10px;
            }
            .question {
                padding: 14px;
                border-radius: 12px;
            }
            .question-head {
                flex-wrap: wrap;
            }
            .question-actions {
                gap: 8px;
                flex-wrap: wrap;
                justify-content: flex-end;
                width: 100%;
                margin-top: 4px;
                padding-top: 10px;
                border-top: 1px solid #f0ece6;
            }
            .required-switch-label {
                display: none;
            }
            .remove {
                width: 30px;
                height: 30px;
                font-size: 13px;
            }
            .field-item {
                min-width: 130px;
                max-width: 170px;
                padding: 8px 10px;
            }
            .field-item small {
                display: none;
            }
            .drag {
                font-size: 13px;
                margin-right: 6px;
            }
            .draft-modal-actions {
                justify-content: stretch;
            }
            .draft-modal-actions button {
                flex: 1 1 auto;
            }
        }

        @media (hover: none) {
            .field-item:hover {
                transform: none;
            }
        }
    </style>
</head>

<body>
    {{-- Header HANYA SATU KALI, di luar .shell. --}}
    @include('admin.partials.header', ['variant' => 'builder', 'event' => $event])

    <div class="shell">
        <main class="workspace">
            <div class="builder">
                <aside class="library">
                    <div class="library-scroll">
                        <p class="pane-title">Fields <span class="count">10 Komponen</span></p>

                        @foreach ([['text', 'Judul', 'Header / Nama Section'], ['textarea', 'Sub Judul', 'Deskripsi teks panduan'], ['text', 'Text', 'Input teks satu baris'], ['text', 'Email', 'Validasi alamat email'], ['number', 'Angka', 'WhatsApp, Usia, Ukuran'], ['textarea', 'Teks Panjang', 'Textarea / Catatan Khusus'], ['select', 'Dropdown', 'Pilihan Kategori Lomba'], ['radio', 'Radio Option', 'Pilihan tunggal opsional'], ['file', 'Upload Gambar', 'Foto Peserta / Kostum'], ['file', 'Upload Dokumen', 'KTP / KK / Portofolio (PDF)']] as $item)
                            <div class="field-item" draggable="true" data-type="{{ $item[0] }}" data-label="{{ $item[1] }}" data-hint="{{ $item[2] }}">
                                <span class="drag">⠿</span><strong>{{ $item[1] }}</strong><small>{{ $item[2] }}</small>
                            </div>
                        @endforeach
                    </div>
                </aside>

                <section class="canvas">
                    <div class="canvas-bar"><span>CANVAS</span></div>
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

    {{-- =====================================================================
         SCRIPT — Terjaga sepenuhnya, fungsi operasional dipertahankan 100%[cite: 21]
    ====================================================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            const list = document.getElementById('questionList');
            const dropzone = document.getElementById('dropzone');
            const saveButton = document.getElementById('saveForm');

            let selected = null;
            let draggedQuestion = null;
            let draggedLibraryItem = null;
            let pendingInsertTarget = null; // ✅ posisi sisip untuk field baru dari library

            // ✅ Garis indikator posisi sisip (dipindah-pindah sesuai posisi kursor saat drag)
            const dropIndicator = document.createElement('div');
            dropIndicator.className = 'drop-indicator';

            function showDropIndicatorAt(afterElement) {
                if (afterElement && afterElement.parentNode === list) {
                    list.insertBefore(dropIndicator, afterElement);
                } else {
                    list.appendChild(dropIndicator);
                }
            }

            function hideDropIndicator() {
                if (dropIndicator.parentNode) {
                    dropIndicator.parentNode.removeChild(dropIndicator);
                }
            }

            // ✅ Cari elemen .question yang ada TEPAT DI BAWAH posisi kursor (y),
            // dipakai bersama untuk reorder elemen lama maupun sisip elemen baru.
            // Return null artinya kursor berada di bawah semua elemen (taruh di akhir).
            function getQuestionAfterPoint(y) {
                const questions = [...list.querySelectorAll('.question')].filter(q => q !== draggedQuestion);
                let closest = { offset: Number.NEGATIVE_INFINITY, element: null };

                for (const q of questions) {
                    const rect = q.getBoundingClientRect();
                    const offset = y - (rect.top + rect.height / 2);
                    if (offset < 0 && offset > closest.offset) {
                        closest = { offset, element: q };
                    }
                }

                return closest.element;
            }

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

                element.classList.remove('heading', 'subheading', 'text-field', 'email-field', 'choice-field',
                    'file-field');

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

                if (type === 'heading' || type === 'subheading') {
                    preview.innerHTML = '';
                    return;
                }

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

                if (type === 'select') {
                    if (force || !preview.contains(document.activeElement)) {
                        const optionsHtml = options.length ?
                            options.map((option, index) => `
                            <div class="choice-option">
                                <span class="option-text" contenteditable="true" data-role="option" data-index="${index}">${escapeHTML(option)}</span>
                                <button type="button" class="option-remove" data-role="remove-option" data-index="${index}" title="Hapus opsi">×</button>
                            </div>
                        `).join('') : '<div class="choice-option" style="color:#aaa">Belum ada pilihan</div>';

                        preview.innerHTML = optionsHtml +
                            `<button type="button" class="add-option" data-role="add-option">+ Tambah opsi</button>`;
                    }
                    return;
                }

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

            function selectQuestion(questionEl) {
                if (selected) {
                    selected.classList.remove('selected');
                }
                selected = questionEl;
                if (selected) {
                    selected.classList.add('selected');
                }
            }

            let isAddingField = false;

            function addField(type, label, insertBeforeEl = null) {
                if (!type) return;
                if (isAddingField) return;
                isAddingField = true;

                try {
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
                    element.dataset.config = JSON.stringify({
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

                    // ✅ Sisipkan di posisi yang diminta (hasil drag atau elemen terpilih);
                    // kalau tidak ada target yang valid, tetap taruh di akhir seperti biasa.
                    if (insertBeforeEl && insertBeforeEl.parentNode === list) {
                        list.insertBefore(element, insertBeforeEl);
                    } else {
                        list.appendChild(element);
                    }

                    bindQuestion(element);
                    renderField(element);
                    selectQuestion(element);

                    saveDraftNow();
                } finally {
                    isAddingField = false;
                }
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

                        if (!list.querySelector('.question') && !document.getElementById(
                                'emptyState')) {
                            const emptyElement = document.createElement('div');
                            emptyElement.id = 'emptyState';
                            emptyElement.className = 'empty';
                            emptyElement.innerHTML =
                                'Tarik komponen dari panel kiri ke sini<br><small>atau klik komponen untuk menambahkannya</small>';
                            list.prepend(emptyElement);
                        }
                    });
                }
            }

            document.querySelectorAll('.field-item').forEach(item => {
                if (item.dataset.label === 'Email') item.dataset.type = 'email';
                if (item.dataset.label === 'Judul') item.dataset.type = 'heading';
                if (item.dataset.label === 'Sub Judul') item.dataset.type = 'subheading';
                if (item.dataset.label.includes('Upload')) item.dataset.type = 'file';

                item.addEventListener('click', function() {
                    // ✅ Kalau ada elemen yang sedang dipilih, sisipkan field baru tepat
                    // setelahnya — bukan selalu di paling bawah.
                    const insertBeforeEl = selected ? selected.nextElementSibling : null;
                    addField(item.dataset.type, item.dataset.label, insertBeforeEl);
                });

                item.addEventListener('dragstart', function(event) {
                    draggedLibraryItem = item;
                    event.dataTransfer.effectAllowed = 'copy';
                    event.dataTransfer.setData('text/field-type', item.dataset.type);
                    event.dataTransfer.setData('text/field-label', item.dataset.label);
                });

                item.addEventListener('dragend', function() {
                    draggedLibraryItem = null;
                    pendingInsertTarget = null;
                    hideDropIndicator();
                });
            });

            list.addEventListener('dragover', function(event) {
                event.preventDefault();

                if (draggedQuestion) {
                    const afterElement = getQuestionAfterPoint(event.clientY);
                    if (afterElement) {
                        list.insertBefore(draggedQuestion, afterElement);
                    } else {
                        list.appendChild(draggedQuestion);
                    }
                    return;
                }

                // ✅ Menyeret field baru dari library — tampilkan garis indikator
                // persis di posisi tempat field akan disisipkan.
                if (draggedLibraryItem) {
                    pendingInsertTarget = getQuestionAfterPoint(event.clientY);
                    showDropIndicatorAt(pendingInsertTarget);
                }
            });

            list.addEventListener('dragleave', function(event) {
                if (draggedLibraryItem && !list.contains(event.relatedTarget)) {
                    hideDropIndicator();
                }
            });

            const canvas = document.querySelector('.canvas');
            if (canvas) {
                canvas.addEventListener('dragover', function(event) {
                    event.preventDefault();
                    event.dataTransfer.dropEffect = 'copy';
                });

                canvas.addEventListener('drop', function(event) {
                    event.preventDefault();
                    const target = event.target;
                    if (target.closest('#questionList') || target.closest('#dropzone')) return;

                    const type = event.dataTransfer.getData('text/field-type');
                    const label = event.dataTransfer.getData('text/field-label');
                    if (type && label && !draggedQuestion) {
                        addField(type, label, pendingInsertTarget);
                        saveDraftNow();
                    }
                    pendingInsertTarget = null;
                    hideDropIndicator();
                });

                list.addEventListener('drop', function(event) {
                    event.preventDefault();
                    event.stopPropagation();
                    const type = event.dataTransfer.getData('text/field-type');
                    const label = event.dataTransfer.getData('text/field-label');

                    if (type && label && !draggedQuestion) {
                        addField(type, label, pendingInsertTarget);
                        saveDraftNow();
                    }
                    pendingInsertTarget = null;
                    hideDropIndicator();
                });

                if (dropzone) {
                    dropzone.addEventListener('dragover', function(event) {
                        event.preventDefault();
                        event.dataTransfer.dropEffect = 'copy';
                        dropzone.classList.add('dragging');
                    });

                    dropzone.addEventListener('dragleave', function() {
                        dropzone.classList.remove('dragging');
                    });

                    dropzone.addEventListener('drop', function(event) {
                        event.preventDefault();
                        event.stopPropagation();
                        dropzone.classList.remove('dragging');
                        const type = event.dataTransfer.getData('text/field-type');
                        const label = event.dataTransfer.getData('text/field-label');
                        if (type && label && !draggedQuestion) {
                            addField(type, label, pendingInsertTarget);
                            saveDraftNow();
                        }
                        pendingInsertTarget = null;
                        hideDropIndicator();
                    });
                }
            }

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

            list.addEventListener('input', function(event) {
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

            const btnHeader = document.getElementById('saveFormHeader');
            const btnMain = document.getElementById('saveForm');
            const allSaveButtons = [btnHeader, btnMain].filter(Boolean);

            allSaveButtons.forEach(btn => {
                btn.addEventListener('click', async function(e) {
                    e.preventDefault();
                    if (!eventId) return toast('Pilih event terlebih dahulu.', 'warning');

                    const fields = getFieldsFromCanvas();
                    if (!fields.length) return toast('Tambahkan minimal satu field.',
                        'warning');

                    allSaveButtons.forEach(b => {
                        b.disabled = true;
                        b.innerHTML = 'Menyimpan...';
                    });

                    try {
                        const response = await fetch(
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
                            });

                        const data = await response.json();
                        if (!response.ok) throw new Error(data.message ||
                            'Gagal menyimpan.');

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

        function collectFormData() {
            const questions = [];
            document.querySelectorAll('.field-item').forEach((el, index) => {
                const helpInput = el.querySelector('.input-keterangan, [name="help"], .help-text');
                const helpValue = helpInput ? helpInput.value.trim() : '';

                const optionInputs = el.querySelectorAll('.option-input, .opsi-item input');
                const options = Array.from(optionInputs)
                    .map(opt => opt.value.trim())
                    .filter(opt => opt !== '');

                questions.push({
                    id: el.dataset.id || null,
                    label: el.querySelector('.label-input').value,
                    tipe_input: el.dataset.tipe,
                    wajib: el.querySelector('.switch-wajib').checked,
                    konfigurasi: {
                        help: helpValue,
                        placeholder: el.querySelector('.placeholder-input')?.value || ''
                    },
                    pilihan_jawaban: options
                });
            });
            return questions;
        }
    </script>

    @include('admin.partials.sidebar', ['active' => 'form-builder', 'event' => $event])
    @include('admin.partials.pwa-head')
</body>

</html>