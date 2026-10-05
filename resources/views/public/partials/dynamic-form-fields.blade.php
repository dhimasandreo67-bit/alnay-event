{{--
    Partial: Dynamic Form Fields (Form Builder)
    Dipakai di registration.blade.php
    Data: $fields (Collection of Pertanyaan)
--}}

@php
    $isPreview = request()->routeIs('admin.preview-form');
@endphp

{{-- ===== FORM DINAMIS DARI FORM BUILDER ===== --}}
@foreach ($fields as $field)
    @php
        $tipe = strtolower($field->tipe_input);
        $konfig = $field->konfigurasi ?? [];
        $help = $konfig['help'] ?? null;
        $placeholder = $konfig['placeholder'] ?? '';
        $options = $field->pilihan_jawaban ?? [];
        if (is_string($options)) {
            $options = json_decode($options, true) ?? [];
        }
        $fieldName = "answers[{$field->id}]";
        $oldValue = old($fieldName);
    @endphp

    {{-- Heading --}}
    @if ($tipe === 'heading')
        <div class="bg-white border border-[#CDC5BC]/30 rounded-xl p-6">
            <h2 class="text-2xl font-bold text-black">{{ $field->label }}</h2>
        </div>
        @continue
    @endif

    {{-- Subheading --}}
    @if ($tipe === 'subheading')
        <div class="bg-white border border-[#CDC5BC]/30 rounded-xl p-6">
            <h3 class="text-lg font-bold text-[#4B463F]">{{ $field->label }}</h3>
        </div>
        @continue
    @endif

    {{-- Field Input --}}
    <div class="bg-white border border-[#CDC5BC]/30 rounded-xl p-6 space-y-2">
        <label class="block text-sm font-semibold mb-1.5">
            {{ $field->label }}
            @if ($field->wajib)
                <span class="text-red-500">*</span>
            @endif
        </label>

        @if ($help)
            <p class="text-xs text-[#7A756D] -mt-1 mb-2">{{ $help }}</p>
        @endif

        {{-- TEXT / EMAIL / NUMBER / TEL --}}
        @if (in_array($tipe, ['text', 'teks']))
            <input type="text" name="{{ $fieldName }}" value="{{ $oldValue }}"
                placeholder="{{ $placeholder }}" @if ($field->wajib) required @endif
                class="w-full border border-[#CDC5BC]/40 rounded-lg px-4 py-2.5 focus:border-[#C28E27] focus:ring-2 focus:ring-[#C28E27]/20 outline-none transition">
        @elseif ($tipe === 'email')
            <input type="email" name="{{ $fieldName }}" value="{{ $oldValue }}"
                placeholder="{{ $placeholder ?: 'nama@email.com' }}" @if ($field->wajib) required @endif
                class="w-full border border-[#CDC5BC]/40 rounded-lg px-4 py-2.5 focus:border-[#C28E27] focus:ring-2 focus:ring-[#C28E27]/20 outline-none transition">
        @elseif (in_array($tipe, ['number', 'angka']))
            <input type="number" name="{{ $fieldName }}" value="{{ $oldValue }}"
                placeholder="{{ $placeholder }}" @if ($field->wajib) required @endif
                class="w-full border border-[#CDC5BC]/40 rounded-lg px-4 py-2.5 focus:border-[#C28E27] focus:ring-2 focus:ring-[#C28E27]/20 outline-none transition">
        @elseif ($tipe === 'textarea' || $tipe === 'teks_panjang')
            <textarea name="{{ $fieldName }}" rows="4" placeholder="{{ $placeholder }}"
                @if ($field->wajib) required @endif
                class="w-full border border-[#CDC5BC]/40 rounded-lg px-4 py-2.5 focus:border-[#C28E27] focus:ring-2 focus:ring-[#C28E27]/20 outline-none transition">{{ $oldValue }}</textarea>

            {{-- DROPDOWN --}}
        @elseif (in_array($tipe, ['select', 'dropdown']))
            <select name="{{ $fieldName }}" @if ($field->wajib) required @endif
                class="w-full border border-[#CDC5BC]/40 rounded-lg px-4 py-2.5 focus:border-[#C28E27] focus:ring-2 focus:ring-[#C28E27]/20 outline-none transition bg-white">
                <option value="">— Pilih —</option>
                @foreach ($options as $opt)
                    <option value="{{ $opt }}" {{ $oldValue == $opt ? 'selected' : '' }}>{{ $opt }}
                    </option>
                @endforeach
            </select>

            {{-- RADIO --}}
        @elseif ($tipe === 'radio')
            <div class="space-y-2">
                @foreach ($options as $opt)
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="{{ $fieldName }}" value="{{ $opt }}"
                            {{ $oldValue == $opt ? 'checked' : '' }} @if ($field->wajib) required @endif
                            class="text-[#C28E27] focus:ring-[#C28E27]">
                        <span class="text-sm">{{ $opt }}</span>
                    </label>
                @endforeach
            </div>

            {{-- CHECKBOX --}}
        @elseif ($tipe === 'checkbox')
            <div class="space-y-2">
                @foreach ($options as $opt)
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="{{ $fieldName }}[]" value="{{ $opt }}"
                            {{ is_array($oldValue) && in_array($opt, $oldValue) ? 'checked' : '' }}
                            class="rounded text-[#C28E27] focus:ring-[#C28E27]">
                        <span class="text-sm">{{ $opt }}</span>
                    </label>
                @endforeach
            </div>

            {{-- FILE --}}
        @elseif ($tipe === 'file')
            <input type="file" name="{{ $fieldName }}" @if ($field->wajib) required @endif
                class="w-full border border-[#CDC5BC]/40 rounded-lg px-4 py-2.5 focus:border-[#C28E27] focus:ring-2 focus:ring-[#C28E27]/20 outline-none transition file:mr-3 file:rounded file:border-0 file:bg-[#C28E27] file:px-3 file:py-1.5 file:text-white file:text-xs file:font-bold">

            {{-- DATE --}}
        @elseif ($tipe === 'date')
            <input type="date" name="{{ $fieldName }}" value="{{ $oldValue }}"
                @if ($field->wajib) required @endif
                class="w-full border border-[#CDC5BC]/40 rounded-lg px-4 py-2.5 focus:border-[#C28E27] focus:ring-2 focus:ring-[#C28E27]/20 outline-none transition">

            {{-- FALLBACK --}}
        @else
            <input type="text" name="{{ $fieldName }}" value="{{ $oldValue }}"
                placeholder="{{ $placeholder }}" @if ($field->wajib) required @endif
                class="w-full border border-[#CDC5BC]/40 rounded-lg px-4 py-2.5 focus:border-[#C28E27] focus:ring-2 focus:ring-[#C28E27]/20 outline-none transition">
        @endif

        @error($fieldName)
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
@endforeach

{{-- ===== TOMBOL SUBMIT ===== --}}
@if ($isPreview)
    <div class="bg-amber-50 border-2 border-dashed border-amber-300 rounded-xl p-4 text-center">
        <p class="text-sm font-semibold text-amber-800">Mode Preview — Tombol Submit Dinonaktifkan</p>
        <p class="text-xs text-amber-700 mt-1">Ini hanya tampilan. User akan melihat tombol aktif di halaman publik.</p>
    </div>
@else
    <button type="submit"
        class="w-full bg-[#C28E27] hover:bg-[#a97b1f] text-white font-bold py-3.5 rounded-lg transition">
        Kirim Pendaftaran
    </button>
@endif
