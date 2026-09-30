<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran - {{ $event->nama }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: #f7f7f5; font-family: 'Inter', sans-serif; color: #18202c; margin: 0; padding: 40px 20px; }
        .form-container { max-width: 680px; margin: 0 auto; }
        .card { background: #fff; border-radius: 12px; padding: 24px; margin-bottom: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
        .form-header { border-top: 6px solid #ba8320; }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-weight: 600; margin-bottom: 8px; }
        .form-input { width: 100%; padding: 12px; border: 1px solid #e8e8e5; border-radius: 8px; box-sizing: border-box; font-size: 14px; }
        .btn-submit { width: 100%; padding: 14px; background: #ba8320; color: #fff; font-weight: 600; border: none; border-radius: 8px; cursor: pointer; font-size: 15px; }
        .btn-submit:hover { background: #a06e18; }
    </style>
</head>
<body>

<div class="form-container">
    <form action="{{ route('public.registration.store', $event) }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Loop Render Komponen Dinamis dari Form Builder --}}
        @foreach($formFields as $field)
            
            {{-- Header / Judul --}}
            @if(($field['type'] ?? '') === 'header' || ($field['type'] ?? '') === 'judul')
                <div class="card form-header">
                    <h1 style="margin: 0 0 8px 0; font-size: 24px;">{{ $field['label'] ?? $event->nama }}</h1>
                    @if(!empty($field['description']))
                        <p style="margin: 0; color: #666;">{{ $field['description'] }}</p>
                    @endif
                </div>

            {{-- Sub Judul --}}
            @elseif(($field['type'] ?? '') === 'subheader' || ($field['type'] ?? '') === 'sub_judul')
                <div class="card">
                    <h3 style="margin: 0;">{{ $field['label'] }}</h3>
                </div>

            {{-- Input Teks / Email / Angka / Teks Panjang --}}
            @elseif(in_array($field['type'] ?? '', ['text', 'email', 'number', 'textarea', 'teks_panjang', 'angka']))
                <div class="card">
                    <div class="form-group">
                        <label class="form-label">
                            {{ $field['label'] }}
                            @if(!empty($field['required'])) <span style="color: red;">*</span> @endif
                        </label>
                        
                        @if(($field['type'] ?? '') === 'textarea' || ($field['type'] ?? '') === 'teks_panjang')
                            <textarea name="answers[{{ $field['name'] ?? $loop->index }}]" class="form-input" rows="4" placeholder="{{ $field['placeholder'] ?? '' }}" {{ !empty($field['required']) ? 'required' : '' }}></textarea>
                        @else
                            <input type="{{ $field['type'] === 'angka' ? 'number' : $field['type'] }}" 
                                   name="answers[{{ $field['name'] ?? $loop->index }}]" 
                                   class="form-input" 
                                   placeholder="{{ $field['placeholder'] ?? 'Masukkan jawaban...' }}"
                                   {{ !empty($field['required']) ? 'required' : '' }}>
                        @endif
                    </div>
                </div>

            {{-- Dropdown / Pilihan --}}
            @elseif(($field['type'] ?? '') === 'select' || ($field['type'] ?? '') === 'dropdown')
                <div class="card">
                    <div class="form-group">
                        <label class="form-label">
                            {{ $field['label'] }}
                            @if(!empty($field['required'])) <span style="color: red;">*</span> @endif
                        </label>
                        <select name="answers[{{ $field['name'] ?? $loop->index }}]" class="form-input" {{ !empty($field['required']) ? 'required' : '' }}>
                            <option value="">-- Pilih --</option>
                            @foreach($field['options'] ?? [] as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

        @endforeach

        <button type="submit" class="btn-submit">Daftar Sekarang</button>
    </form>
</div>

</body>
</html>