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
        :root {
            --gold: #c38b22;
            --cream: #f7f6f3;
            --ink: #22201d;
            --line: #e5e1db
        }

        * {
            box-sizing: border-box
        }

        body {
            margin: 0;
            background: var(--cream);
            color: var(--ink);
            font: 15px Inter, Arial, sans-serif
        }

        .wrap {
            max-width: 640px;
            margin: 0 auto;
            padding: 30px 20px 80px
        }

        .header {
            background: white;
            border: 1px solid var(--line);
            border-top: 4px solid var(--gold);
            border-radius: 12px;
            padding: 24px 20px;
            margin-bottom: 16px;
            box-shadow: 0 3px 10px #3a302211
        }

        .header h1 {
            font: 700 22px Georgia, serif;
            margin: 0 0 8px
        }

        .header p {
            margin: 0;
            color: #73716c;
            font-size: 12px
        }

        .field {
            background: white;
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 18px;
            margin-bottom: 12px
        }

        .field label {
            display: block;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 4px
        }

        .field label .req {
            color: #e26855
        }

        .field .help {
            font-size: 10px;
            color: #a09d96;
            margin-bottom: 10px
        }

        .field input[type=text],
        .field input[type=email],
        .field input[type=number],
        .field input[type=file],
        .field textarea,
        .field select {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 6px;
            padding: 10px;
            font: inherit;
            font-size: 12px
        }

        .field textarea {
            min-height: 80px;
            resize: vertical
        }

        .choice-row {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            margin-bottom: 6px
        }

        .field .error {
            color: #c1442f;
            font-size: 10px;
            margin-top: 6px
        }

        .field.has-error {
            border-color: #e0938a
        }

        .btn-submit {
            width: 100%;
            border: 0;
            border-radius: 6px;
            padding: 13px;
            background: var(--gold);
            color: white;
            font-weight: bold;
            font-size: 13px;
            cursor: pointer
        }

        .banner {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 12px;
            margin-bottom: 16px
        }

        .banner.success {
            background: #e5f6ee;
            color: #0a6b4f
        }

        .banner.error {
            background: #fdeceb;
            color: #a3382a
        }

        .btn-clear {
            width: 35%;
            border: 1px solid var(--line);
            border-radius: 6px;
            padding: 13px;
            background: #f0ede6;
            color: #555;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-clear:hover {
            background: #e2ded5;
            color: #222;
        }
    </style>
</head>

<body>
    <div class="wrap">

        <button type="button" onclick="window.location.href='/admin'"
            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:text-gray-900 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali</span>
        </button>

        @if (session('success'))
            <div class="banner success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="banner error">
                Ada beberapa isian yang belum sesuai, cek lagi di bawah.
            </div>
        @endif
        <form action="{{ route('public.registration.store', $event) }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf

            {{-- Pastikan tag form utama di luar loop memiliki: enctype="multipart/form-data" --}}

            @foreach ($questions as $question)
                @php
                    $fieldName = "answers.{$question->id}";
                    $oldValue = old($fieldName);

                    // Dekode pilihan_jawaban sekali saja di awal untuk select, radio, & checkbox
                    $options = [];
                    if (!empty($question->pilihan_jawaban)) {
                        $options = is_string($question->pilihan_jawaban)
                            ? json_decode($question->pilihan_jawaban, true)
                            : $question->pilihan_jawaban;
                    }
                @endphp

                {{-- HEADING --}}
                @if ($question->tipe_input === 'heading')
                    <div class="field field-heading"
                        style="border-top:3px solid var(--gold, #d4af37); padding-top: 15px; margin-top: 25px;">
                        <h2 style="margin:0; font:700 20px Georgia, serif">{{ $question->label }}</h2>
                    </div>
                    @continue
                @endif

                {{-- SUBHEADING --}}
                @if ($question->tipe_input === 'subheading')
                    <div class="field field-subheading" style="margin-bottom: 15px;">
                        <p style="margin:0; color:#6f6b64; font-size: 0.95rem;">{{ $question->label }}</p>
                    </div>
                    @continue
                @endif

                {{-- REGULAR FORM FIELD --}}
                <div class="field @error($fieldName) has-error @enderror" style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px;">
                        {{ $question->label }}
                        @if ($question->wajib)
                            <span class="req" style="color: red;"> *</span>
                        @endif
                    </label>

                    @if ($question->konfigurasi['help'] ?? null)
                        <div class="help" style="font-size: 0.85rem; color: #777; margin-bottom: 6px;">
                            {{ $question->konfigurasi['help'] }}
                        </div>
                    @endif

                    @switch(strtolower($question->tipe_input))
                        {{-- TEKS SATU BARIS & ANGKA --}}
                        @case('text')
                        @case('teks')

                        @case('email')
                        @case('number')

                        @case('angka')
                            <input
                                type="{{ $question->tipe_input === 'angka' ? 'number' : ($question->tipe_input === 'teks' ? 'text' : $question->tipe_input) }}"
                                name="answers[{{ $question->id }}]" value="{{ $oldValue }}"
                                placeholder="{{ $question->konfigurasi['placeholder'] ?? '' }}" class="form-control">
                        @break

                        {{-- TEKS PANJANG --}}
                        @case('textarea')
                        @case('teks_panjang')
                            <textarea name="answers[{{ $question->id }}]" rows="4"
                                placeholder="{{ $question->konfigurasi['placeholder'] ?? '' }}" class="form-control">{{ $oldValue }}</textarea>
                        @break

                        {{-- DROPDOWN / SELECT --}}
                        @case('select')
                        @case('dropdown')
                            <select name="answers[{{ $question->id }}]" class="form-control">
                                <option value="">Pilih salah satu</option>
                                @foreach ($options as $opsi)
                                    <option value="{{ $opsi }}" @selected($oldValue === $opsi)>{{ $opsi }}
                                    </option>
                                @endforeach
                            </select>
                        @break

                        {{-- RADIO BUTTON --}}
                        @case('radio')
                            <div class="radio-group">
                                @foreach ($options as $opsi)
                                    <label class="choice-row" style="display: block; margin-bottom: 4px; cursor: pointer;">
                                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $opsi }}"
                                            @checked($oldValue === $opsi)>
                                        {{ $opsi }}
                                    </label>
                                @endforeach
                            </div>
                        @break

                        {{-- CHECKBOX --}}
                        @case('checkbox')
                            <div data-checkbox-group @if ($question->wajib) data-wajib="true" @endif
                                class="checkbox-group">
                                @foreach ($options as $opsi)
                                    <label class="choice-row" style="display: block; margin-bottom: 4px; cursor: pointer;">
                                        <input type="checkbox" name="answers[{{ $question->id }}][]"
                                            value="{{ $opsi }}" @checked(is_array($oldValue) && in_array($opsi, $oldValue, true))>
                                        {{ $opsi }}
                                    </label>
                                @endforeach
                            </div>
                            <div class="error checkbox-min-error"
                                style="display:none; color: red; font-size: 0.85rem; margin-top: 4px;">
                                Pilih minimal satu opsi.
                            </div>
                        @break

                        {{-- FILE UPLOAD --}}
                        @case('file')
                            <input type="file" name="answers[{{ $question->id }}]" class="form-control-file">
                        @break

                        {{-- DEFAULT FALLBACK --}}

                        @default
                            <input type="text" name="answers[{{ $question->id }}]" value="{{ $oldValue }}"
                                class="form-control">
                    @endswitch

                    {{-- ERROR HANDLING DARI BACKEND (LARAVEL) --}}
                    @error($fieldName)
                        <div class="error" style="color: red; font-size: 0.85rem; margin-top: 4px;">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            @endforeach

            <button type="submit" class="btn-submit">Daftar Sekarang juga</button>
        </form>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');

            if (form) {
                form.noValidate = true;

                form.querySelectorAll('input, select, textarea').forEach(el => {
                    el.setCustomValidity('');
                });

                form.addEventListener('submit', function(event) {
                    let isValid = true;

                    const checkboxGroups = document.querySelectorAll(
                        '[data-checkbox-group][data-wajib="true"]');

                    checkboxGroups.forEach(group => {
                        const checked = group.querySelectorAll('input[type="checkbox"]:checked');
                        const errorDiv = group.nextElementSibling;

                        if (checked.length === 0) {
                            isValid = false;
                            if (errorDiv && errorDiv.classList.contains('checkbox-min-error')) {
                                errorDiv.style.display = 'block';
                            }
                        } else {
                            if (errorDiv && errorDiv.classList.contains('checkbox-min-error')) {
                                errorDiv.style.display = 'none';
                            }
                        }
                    });

                    if (!isValid) {
                        event.preventDefault();
                    }
                });
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const clearBtn = document.getElementById('btn-clear-form');
            const form = document.querySelector('form');

            if (clearBtn && form) {
                clearBtn.addEventListener('click', function () {
                    // Konfirmasi ke pengguna agar tidak sengaja tertekan
                    if (confirm('Apakah Anda yakin ingin mengosongkan semua isian form?')) {
                        // Reset form standar (text, email, number, select, textarea)
                        form.reset();

                        // Bersihkan pesan error visual (jika ada)
                        document.querySelectorAll('.error').forEach(err => {
                            if (err.classList.contains('checkbox-min-error')) {
                                err.style.display = 'none';
                            }
                        });

                        document.querySelectorAll('.has-error').forEach(field => {
                            field.classList.remove('has-error');
                        });

                        // Scroll halus kembali ke bagian atas form
                        form.scrollIntoView({ behavior: 'smooth' });
                    }
                });
            }
        });
    </script>
</body>

</html>
