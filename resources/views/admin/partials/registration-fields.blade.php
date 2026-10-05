@forelse($questions as $question)
    @php
        $type = strtolower($question->tipe_input);
        $fieldName = "answers[{$question->id}]";
        $fieldError = $errors->first("answers.{$question->id}");
    @endphp

    @if ($type === 'heading')
        <div class="bg-white border-l-4 border-[#C28E27] rounded-xl p-5">
            <h2 class="text-xl font-bold text-black">{{ $question->label }}</h2>
        </div>
    @elseif($type === 'subheading')
        <div class="bg-white border border-[#CDC5BC]/30 rounded-xl p-5">
            <p class="text-sm text-[#4B463F]">{{ $question->label }}</p>
        </div>
    @else
        <div class="bg-white border border-[#CDC5BC]/30 rounded-xl p-5 {{ $fieldError ? 'border-red-300' : '' }}">
            <label class="block text-sm font-semibold text-black mb-2">
                {{ $question->label }}
                @if ($question->wajib)
                    <span class="text-red-500">*</span>
                @endif
            </label>

            @if ($question->konfigurasi['help'] ?? null)
                <p class="text-xs text-[#7F766C] mb-2">{{ $question->konfigurasi['help'] }}</p>
            @endif

            @php
                $placeholder = $question->konfigurasi['placeholder'] ?? '';
                $options = $question->pilihan_jawaban ?? [];
            @endphp

            @if (in_array($type, ['text', 'teks']))
                <input type="text" name="{{ $fieldName }}" placeholder="{{ $placeholder }}"
                    value="{{ old("answers.{$question->id}") }}"
                    class="w-full border border-[#CDC5BC] rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#C28E27]">
            @elseif(in_array($type, ['textarea', 'teks_panjang']))
                <textarea name="{{ $fieldName }}" rows="4" placeholder="{{ $placeholder }}"
                    class="w-full border border-[#CDC5BC] rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#C28E27]">{{ old("answers.{$question->id}") }}</textarea>
            @elseif($type === 'email')
                <input type="email" name="{{ $fieldName }}" placeholder="{{ $placeholder }}"
                    value="{{ old("answers.{$question->id}") }}"
                    class="w-full border border-[#CDC5BC] rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#C28E27]">
            @elseif(in_array($type, ['number', 'angka']))
                <input type="number" name="{{ $fieldName }}" placeholder="{{ $placeholder }}"
                    value="{{ old("answers.{$question->id}") }}"
                    class="w-full border border-[#CDC5BC] rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#C28E27]">
            @elseif(in_array($type, ['select', 'dropdown']))
                <select name="{{ $fieldName }}"
                    class="w-full border border-[#CDC5BC] rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#C28E27]">
                    <option value="">-- Pilih --</option>
                    @foreach ($options as $opt)
                        <option value="{{ $opt }}" @selected(old("answers.{$question->id}") == $opt)>{{ $opt }}</option>
                    @endforeach
                </select>
            @elseif($type === 'radio')
                <div class="space-y-2">
                    @foreach ($options as $opt)
                        <label class="flex items-center gap-2 text-sm cursor-pointer">
                            <input type="radio" name="{{ $fieldName }}" value="{{ $opt }}"
                                @checked(old("answers.{$question->id}") == $opt) class="accent-[#C28E27]">
                            {{ $opt }}
                        </label>
                    @endforeach
                </div>
            @elseif($type === 'checkbox')
                <div class="space-y-2">
                    @foreach ($options as $opt)
                        <label class="flex items-center gap-2 text-sm cursor-pointer">
                            <input type="checkbox" name="{{ $fieldName }}[]" value="{{ $opt }}"
                                @checked(in_array($opt, old("answers.{$question->id}", []))) class="accent-[#C28E27]">
                            {{ $opt }}
                        </label>
                    @endforeach
                </div>
            @elseif($type === 'file')
                <input type="file" name="{{ $fieldName }}"
                    class="w-full border border-[#CDC5BC] rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#C28E27]">
                <p class="text-xs text-[#7F766C] mt-1">Maks 5 MB</p>
            @else
                <input type="text" name="{{ $fieldName }}" placeholder="{{ $placeholder }}"
                    value="{{ old("answers.{$question->id}") }}"
                    class="w-full border border-[#CDC5BC] rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#C28E27]">
            @endif

            @if ($fieldError)
                <p class="text-xs text-red-600 mt-2">{{ $fieldError }}</p>
            @endif
        </div>
    @endif
@empty
    <div class="bg-white border border-[#CDC5BC]/30 rounded-xl p-8 text-center text-[#4B463F]">
        Formulir pendaftaran belum dikonfigurasi oleh admin.
    </div>
@endforelse