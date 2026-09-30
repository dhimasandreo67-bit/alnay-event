<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran — {{ $event->nama }} | ALNAY.ID</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FFF8F1] text-[#1D1B17] min-h-screen">

    {{-- Header --}}
    <header class="border-b border-[#CDC5BC]/30 bg-[#FBF9F4]/95 backdrop-blur">
        <div class="max-w-3xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="/beranda" class="font-bold text-xl text-[#0A1526]">EVENT ALNAY.ID</a>
            @auth('pelanggan')
                <div class="flex items-center gap-3 text-xs text-[#4B463F]">
                    <span>{{ auth('pelanggan')->user()->nama }}</span>
                    <form action="{{ route('auth.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-red-600 hover:underline">Keluar</button>
                    </form>
                </div>
            @endauth
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-6 py-10">

        {{-- Breadcrumb --}}
        <nav class="text-xs font-semibold uppercase tracking-wide text-[#4B463F] flex items-center gap-2 mb-6">
            <a href="/beranda" class="hover:text-black">&larr; Kembali ke Beranda</a>
            <span class="text-[#CDC5BC]">/</span>
            <span class="text-black">{{ $event->nama }}</span>
        </nav>

        {{-- Judul --}}
        <div class="bg-white border border-[#CDC5BC]/30 rounded-xl p-6 mb-6">
            <h1 class="text-2xl font-bold text-black mb-1">{{ $event->nama }}</h1>
            <p class="text-sm text-[#4B463F]">Isi formulir di bawah untuk mendaftar event ini.</p>
        </div>

        {{-- Flash message --}}
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 mb-6 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 mb-6 text-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- Error validasi --}}
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 mb-6">
                <p class="font-bold mb-2">Ada kesalahan pada form:</p>
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- BELUM LOGIN: tampilkan tombol login --}}
        @guest('pelanggan')
            <div class="bg-white border border-[#CDC5BC]/30 rounded-xl p-10 text-center">
                <div class="w-16 h-16 rounded-full bg-[#FAF3E7] flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-[#7F5700]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="8" r="4"/>
                        <path d="M4 21c0-4 3.6-7 8-7s8 3 8 7"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-black mb-2">Login Dulu untuk Mendaftar</h2>
                <p class="text-sm text-[#4B463F] mb-6">
                    Kami perlu tahu siapa kamu. Login pakai akun Google — prosesnya cuma 1 detik.
                </p>
                <a href="{{ route('auth.google', ['redirect_to' => url()->current()]) }}"
                   class="inline-flex items-center gap-2 bg-[#C28E27] hover:bg-[#a97b1f] text-white font-semibold px-6 py-3 rounded-lg transition">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M21.35 11.1h-9.17v2.73h6.51c-.33 3.81-3.5 5.44-6.5 5.44C8.36 19.27 5 16.25 5 12c0-4.1 3.2-7.27 7.2-7.27 3.09 0 4.9 1.97 4.9 1.97L19 4.72S16.56 2 12.1 2C6.42 2 2.03 6.8 2.03 12c0 5.05 4.13 10 10.22 10 5.35 0 9.25-3.67 9.25-9.09 0-1.15-.15-1.81-.15-1.81z"/>
                    </svg>
                    Login dengan Google
                </a>
            </div>
        @endguest

        {{-- SUDAH LOGIN: tampilkan form --}}
        @auth('pelanggan')
            <form action="{{ route('public.registration.store', $event) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="space-y-5">
                @csrf

                {{-- Nama & Email dari Google --}}
                <div class="bg-white border border-[#CDC5BC]/30 rounded-xl p-5">
                    <p class="text-xs font-semibold uppercase tracking-wide text-[#4B463F] mb-3">Data Akun (dari Google)</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-[#4B463F] mb-1">Nama</label>
                            <input type="text" value="{{ auth('pelanggan')->user()->nama }}" readonly
                                   class="w-full bg-[#FAF3E7] border border-[#CDC5BC]/40 rounded-lg px-3 py-2.5 text-sm text-[#4B463F]">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#4B463F] mb-1">Email</label>
                            <input type="email" value="{{ auth('pelanggan')->user()->email }}" readonly
                                   class="w-full bg-[#FAF3E7] border border-[#CDC5BC]/40 rounded-lg px-3 py-2.5 text-sm text-[#4B463F]">
                        </div>
                    </div>
                </div>

                {{-- Pertanyaan Dinamis --}}
                @forelse($questions as $question)
                    @php
                        $type = strtolower($question->tipe_input);
                        $fieldName = "answers[{$question->id}]";
                        $fieldError = $errors->first("answers.{$question->id}");
                    @endphp

                    {{-- Heading / Subheading --}}
                    @if($type === 'heading')
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
                                @if($question->wajib) <span class="text-red-500">*</span> @endif
                            </label>

                            @if($question->konfigurasi['help'] ?? null)
                                <p class="text-xs text-[#7F766C] mb-2">{{ $question->konfigurasi['help'] }}</p>
                            @endif

                            @php
                                $placeholder = $question->konfigurasi['placeholder'] ?? '';
                                $options = $question->pilihan_jawaban ?? [];
                            @endphp

                            {{-- Render berdasarkan tipe --}}
                            @if(in_array($type, ['text', 'teks']))
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
                                    @foreach($options as $opt)
                                        <option value="{{ $opt }}" @selected(old("answers.{$question->id}") == $opt)>{{ $opt }}</option>
                                    @endforeach
                                </select>

                            @elseif($type === 'radio')
                                <div class="space-y-2">
                                    @foreach($options as $opt)
                                        <label class="flex items-center gap-2 text-sm cursor-pointer">
                                            <input type="radio" name="{{ $fieldName }}" value="{{ $opt }}"
                                                   @checked(old("answers.{$question->id}") == $opt)
                                                   class="accent-[#C28E27]">
                                            {{ $opt }}
                                        </label>
                                    @endforeach
                                </div>

                            @elseif($type === 'checkbox')
                                <div class="space-y-2">
                                    @foreach($options as $opt)
                                        <label class="flex items-center gap-2 text-sm cursor-pointer">
                                            <input type="checkbox" name="{{ $fieldName }}[]" value="{{ $opt }}"
                                                   @checked(in_array($opt, old("answers.{$question->id}", [])))
                                                   class="accent-[#C28E27]">
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

                            @if($fieldError)
                                <p class="text-xs text-red-600 mt-2">{{ $fieldError }}</p>
                            @endif
                        </div>
                    @endif
                @empty
                    <div class="bg-white border border-[#CDC5BC]/30 rounded-xl p-8 text-center text-[#4B463F]">
                        Formulir pendaftaran belum dikonfigurasi oleh admin.
                    </div>
                @endforelse

                {{-- Tombol Submit --}}
                @if($questions->count() > 0)
                    <button type="submit"
                            class="w-full bg-[#C28E27] hover:bg-[#a97b1f] text-white font-bold py-4 rounded-xl transition">
                        Kirim Pendaftaran
                    </button>
                @endif
            </form>
        @endauth

    </main>

</body>
</html>