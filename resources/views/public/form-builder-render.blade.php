<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pendaftaran {{ $event->nama ?? 'Event' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-100 min-h-screen py-10">
    <div class="max-w-2xl mx-auto space-y-4 px-4">
        
        <form action="{{ route('public.registration.store', $event) }}" method="POST" class="space-y-4">
            @csrf

            @forelse($fields as $index => $field)
                @php
                    // Ambil tipe, label, dan placeholder dengan fallback aman
                    $type = strtolower($field['type'] ?? $field['fieldType'] ?? 'text');
                    $label = $field['label'] ?? $field['title'] ?? 'Field ' . ($index + 1);
                    $placeholder = $field['placeholder'] ?? '';
                    $required = !empty($field['required']);
                @endphp

                <div class="p-6 bg-white rounded-xl shadow-sm border border-stone-200">
                    
                    {{-- Judul / Header Section --}}
                    @if(in_array($type, ['header', 'title', 'judul']))
                        <h2 class="text-2xl font-bold text-stone-900">{{ $label }}</h2>
                    
                    {{-- Sub Judul / Paragraph --}}
                    @elseif(in_array($type, ['subtitle', 'sub_judul', 'paragraph', 'deskripsi']))
                        <p class="text-stone-600 text-sm">{{ $label }}</p>

                    {{-- Textarea / Teks Panjang --}}
                    @elseif(in_array($type, ['textarea', 'teks_panjang']))
                        <label class="block font-medium text-stone-800 mb-1">
                            {{ $label }} @if($required) <span class="text-red-500">*</span> @endif
                        </label>
                        <textarea name="answers[{{ $field['id'] ?? $index }}]" rows="4"
                                  placeholder="{{ $placeholder }}"
                                  {{ $required ? 'required' : '' }}
                                  class="w-full px-4 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none"></textarea>

                    {{-- Dropdown / Select Option --}}
                    @elseif(in_array($type, ['select', 'dropdown']))
                        <label class="block font-medium text-stone-800 mb-1">
                            {{ $label }} @if($required) <span class="text-red-500">*</span> @endif
                        </label>
                        <select name="answers[{{ $field['id'] ?? $index }}]" 
                                {{ $required ? 'required' : '' }}
                                class="w-full px-4 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none">
                            <option value="">-- Pilih --</option>
                            @foreach($field['options'] ?? [] as $opt)
                                <option value="{{ is_array($opt) ? ($opt['value'] ?? $opt['label']) : $opt }}">
                                    {{ is_array($opt) ? ($opt['label'] ?? $opt['value']) : $opt }}
                                </option>
                            @endforeach
                        </select>

                    {{-- Standard Input (Text, Email, Number, dll) --}}
                    @else
                        <label class="block font-medium text-stone-800 mb-1">
                            {{ $label }} @if($required) <span class="text-red-500">*</span> @endif
                        </label>
                        <input type="{{ in_array($type, ['email', 'number']) ? $type : 'text' }}" 
                               name="answers[{{ $field['id'] ?? $index }}]" 
                               placeholder="{{ $placeholder }}"
                               {{ $required ? 'required' : '' }}
                               class="w-full px-4 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none">
                    @endif

                </div>
            @empty
                <div class="p-8 text-center bg-white rounded-xl text-stone-500 border border-stone-200 shadow-sm">
                    Formulir pendaftaran belum dikonfigurasi.
                </div>
            @endforelse

            @if(count($fields) > 0)
                <button type="submit" 
                        class="w-full py-3 bg-[#ba8320] hover:bg-[#a06f1a] text-white font-bold rounded-xl transition shadow">
                    Daftar Sekarang
                </button>
            @endif

        </form>
    </div>
</body>
</html>