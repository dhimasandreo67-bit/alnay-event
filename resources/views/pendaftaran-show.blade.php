<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pendaftaran — {{ $event->nama }} | ALNAY.ID</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="background:#f4f2ec; color:#0a1526;">
    @include('partials.header')

    <main class="mx-auto max-w-3xl px-4 py-10">

        {{-- Tombol Kembali --}}
        <a href="{{ route('profil-user') }}"
           class="inline-flex items-center gap-2 rounded-full border bg-white px-4 py-2 text-sm font-medium shadow-sm hover:bg-gray-50"
           style="border-color:#e3ded6; color:#0a1526;">
            ← Kembali ke Profil
        </a>

        {{-- Banner Mode Lihat Saja --}}
        <div class="mt-6 rounded-xl px-4 py-3 text-sm"
             style="background:#fdf8ec; border:1px solid rgba(201,154,50,0.4); color:#6b5220;">
            <strong>Mode Lihat Saja.</strong>
            Ini adalah data yang kamu kirim saat mendaftar. Data tidak dapat diubah dari halaman ini.
        </div>

        {{-- Header Event — inline style agar warna PASTI tampil --}}
        <div class="mt-6 rounded-2xl p-6 shadow-lg" style="background:#0d1b2e;">
            <p class="text-xs font-bold uppercase tracking-widest" style="color:#c99a32;">Formulir Pendaftaran</p>
            <h1 class="mt-2 text-2xl font-bold" style="color:#ffffff;">{{ $event->nama }}</h1>
            <p class="mt-2 text-sm" style="color:rgba(255,255,255,0.75);">
                Dikirim pada:
                {{ $peserta->created_at ? \Carbon\Carbon::parse($peserta->created_at)->translatedFormat('d F Y, H:i') : '-' }} WIB
            </p>
        </div>

        {{-- Daftar Jawaban (READ-ONLY) --}}
        <div class="mt-6 rounded-2xl bg-white p-6 shadow-sm" style="border:1px solid #e3ded6;">
            <h2 class="text-lg font-bold" style="color:#0a1526;">Data yang Kamu Kirim</h2>
            <p class="mt-1 text-xs" style="color:#8a8577;">Berikut ringkasan jawaban formulir pendaftaranmu.</p>

            <div class="mt-5 space-y-5">
                @forelse($jawabans as $j)
                    @php
                        $tipe  = strtolower($j->pertanyaan->tipe_input ?? 'text');
                        $nilai = $j->jawaban;
                    @endphp
                    <div class="pb-4" style="border-bottom:1px dashed #e3ded6;">
                        <p class="text-xs font-bold uppercase tracking-wide" style="color:#8a8577;">
                            {{ $j->pertanyaan->label }}
                        </p>

                        {{-- FILE: link lihat/unduh --}}
                        @if($tipe === 'file')
                            @php $paths = is_array($nilai) ? $nilai : [$nilai]; @endphp
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach($paths as $p)
                                    @if($p)
                                        <a href="{{ asset('storage/' . $p) }}" target="_blank"
                                           class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-sm font-medium"
                                           style="background:#fdf8ec; border:1px solid rgba(201,154,50,0.5); color:#b8862a;">
                                            📄 Lihat File
                                        </a>
                                    @endif
                                @endforeach
                            </div>

                        {{-- CHECKBOX: gabung array --}}
                        @elseif($tipe === 'checkbox')
                            <p class="mt-1 text-sm" style="color:#0a1526;">{{ is_array($nilai) ? implode(', ', $nilai) : ($nilai ?: '-') }}</p>

                        {{-- TEKS BIASA --}}
                        @else
                            <p class="mt-1 whitespace-pre-line text-sm" style="color:#0a1526;">{{ is_array($nilai) ? implode(', ', $nilai) : ($nilai ?: '-') }}</p>
                        @endif
                    </div>
                @empty
                    <p class="text-sm" style="color:#8a8577;">Tidak ada data jawaban tersimpan untuk pendaftaran ini.</p>
                @endforelse

                {{-- Pelengkap: email akun --}}
                <div>
                    <p class="text-xs font-bold uppercase tracking-wide" style="color:#8a8577;">Email Akun</p>
                    <p class="mt-1 text-sm" style="color:#0a1526;">{{ $peserta->user->email ?? '-' }}</p>
                </div>
            </div>
        </div>

        <p class="mt-6 text-center text-xs" style="color:#8a8577;">
            Butuh perubahan data? Hubungi panitia event melalui kontak resmi yang terdapat di halaman event.
        </p>
    </main>

    @include('partials.footer')
</body>
</html>
