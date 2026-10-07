<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event - {{ $event->nama }} | ALNAY.ID</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>

<body class="bg-cream text-ink font-sans text-[13px]">
    <div class="flex min-h-screen w-full">

        <main class="flex-1 min-w-0 ml-[235px] p-6">

            {{-- ============================================ --}}
            {{-- HEADER                                        --}}
            {{-- ============================================ --}}
            <div class="flex items-center justify-between mb-6 max-w-6xl">
                <div>
                    <h1 class="font-bold text-[18px] text-ink">Edit Data Event</h1>
                    <p class="text-[11px] text-[#8a8984]">Perbarui informasi event {{ $event->nama }}</p>
                </div>
                <a href="{{ route('admin.events.index') }}"
                    class="px-4 py-2 border border-[#e5e1db] bg-white rounded-md text-ink font-bold hover:bg-[#f7f5f0] transition">
                    ← Kembali
                </a>
            </div>

            {{-- ============================================ --}}
            {{-- FORM EDIT EVENT                               --}}
            {{-- ============================================ --}}
            <form action="{{ route('admin.events.update', $event->id) }}" method="POST" enctype="multipart/form-data"
                class="max-w-6xl grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_340px] gap-6 items-start">
                @csrf
                @method('PUT')

                {{-- ============================================ --}}
                {{-- KOLOM KIRI — FORM FIELDS                     --}}
                {{-- ============================================ --}}
                <div class="space-y-6 min-w-0">

                    {{-- ===== SECTION 1: STATUS EVENT ===== --}}
                    <div class="bg-white border border-[#e3ded6] rounded-xl p-6 shadow-sm">
                        <div class="mb-4 pb-3 border-b border-[#e5e1db]">
                            <h2 class="font-bold text-slate-800">Status Event</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Pilih status publikasi event saat ini</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3" id="statusContainer">
                            <label data-color="blue"
                                class="status-card cursor-pointer border-2 rounded-lg p-4 flex items-center gap-3 transition {{ old('status', $event->status) === 'akan_datang' ? 'border-blue-500 bg-blue-50' : 'border-[#e5e1db] hover:bg-[#f5f1eb]' }}">
                                <input type="radio" name="status" value="akan_datang"
                                    {{ old('status', $event->status) === 'akan_datang' ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600 border-slate-300 focus:ring-blue-500">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                                <span class="text-sm font-medium">Akan Datang</span>
                            </label>

                            <label data-color="emerald"
                                class="status-card cursor-pointer border-2 rounded-lg p-4 flex items-center gap-3 transition {{ old('status', $event->status) === 'sedang_berlangsung' ? 'border-emerald-500 bg-emerald-50' : 'border-[#e5e1db] hover:bg-[#f5f1eb]' }}">
                                <input type="radio" name="status" value="sedang_berlangsung"
                                    {{ old('status', $event->status) === 'sedang_berlangsung' ? 'checked' : '' }}
                                    class="w-4 h-4 text-emerald-600 border-slate-300 focus:ring-emerald-500">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                <span class="text-sm font-medium">Sedang Berlangsung</span>
                            </label>

                            <label data-color="orange"
                                class="status-card cursor-pointer border-2 rounded-lg p-4 flex items-center gap-3 transition {{ old('status', $event->status) === 'telah_berakhir' ? 'border-orange-500 bg-orange-50' : 'border-[#e5e1db] hover:bg-[#f5f1eb]' }}">
                                <input type="radio" name="status" value="telah_berakhir"
                                    {{ old('status', $event->status) === 'telah_berakhir' ? 'checked' : '' }}
                                    class="w-4 h-4 text-orange-600 border-slate-300 focus:ring-orange-500">
                                <span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span>
                                <span class="text-sm font-medium">Telah Berakhir</span>
                            </label>
                        </div>
                        @error('status')
                            <p class="text-xs text-rose-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ===== SECTION 2: INFORMASI UTAMA ===== --}}
                    <div class="bg-white border border-[#e3ded6] rounded-xl p-6 shadow-sm">
                        <div class="mb-4 pb-3 border-b border-[#e5e1db]">
                            <h2 class="font-bold text-slate-800">Informasi Utama</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Nama, deskripsi, dan banner event</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-[minmax(0,1fr)_240px] gap-5">

                            {{-- KIRI: Nama + Deskripsi + SK --}}
                            <div class="space-y-4 min-w-0">

                                <div>
                                    <label class="block font-bold mb-1.5">Nama Event</label>
                                    <input type="text" name="nama" value="{{ old('nama', $event->nama) }}"
                                        required
                                        class="w-full border border-[#e5e1db] rounded-md p-2.5 bg-[#fcfbf9] focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 transition">
                                    @error('nama')
                                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block font-bold mb-1.5">Deskripsi</label>
                                    <textarea name="deskripsi" rows="3" required
                                        class="w-full border border-[#e5e1db] rounded-md p-2.5 bg-[#fcfbf9] focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 transition">{{ old('deskripsi', $event->deskripsi) }}</textarea>
                                    @error('deskripsi')
                                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block font-bold mb-1.5">Syarat & Ketentuan</label>
                                    <textarea name="syarat_ketentuan" rows="4" required
                                        class="w-full border border-[#e5e1db] rounded-md p-2.5 bg-[#fcfbf9] focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 transition">{{ old('syarat_ketentuan', $event->syarat_ketentuan) }}</textarea>
                                    @error('syarat_ketentuan')
                                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- KANAN: Banner Upload --}}
                            <div>
                                <label class="block font-bold mb-1.5">Banner Event</label>
                                <p class="text-[11px] text-[#8a8984] mb-2">Kosongkan jika tidak diubah</p>

                                <div class="space-y-2">
                                    @if ($event->banner)
                                        <img src="{{ Storage::url($event->banner) }}" alt="{{ $event->nama }}"
                                            class="w-full h-32 object-cover rounded-lg border border-[#e5e1db]">
                                    @else
                                        <div
                                            class="w-full h-32 bg-gray-100 rounded-lg border border-dashed border-[#e5e1db] flex items-center justify-center text-xs text-gray-500">
                                            Belum ada banner
                                        </div>
                                    @endif

                                    <input type="file" name="banner" accept="image/*"
                                        class="w-full border border-[#e5e1db] rounded-md p-2 bg-[#fcfbf9] text-[12px] file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:bg-gold file:text-white file:font-semibold file:cursor-pointer hover:file:bg-[#a3721a]">
                                    @error('banner')
                                        <p class="text-xs text-rose-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ===== SECTION 3: BIAYA & VOTE ===== --}}
                    <div class="bg-white border border-[#e3ded6] rounded-xl p-6 shadow-sm">
                        <div class="mb-4 pb-3 border-b border-[#e5e1db]">
                            <h2 class="font-bold text-slate-800">Biaya & Vote</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Pengaturan biaya pendaftaran dan harga per vote</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold mb-1.5">Biaya Pendaftaran (Rp)</label>
                                <div class="relative">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-sm font-semibold text-slate-500">Rp</span>
                                    <input type="text" name="biaya_pendaftaran" id="biaya_pendaftaran"
                                        value="{{ old('biaya_pendaftaran', $event->biaya_pendaftaran) }}" required
                                        inputmode="numeric" autocomplete="off"
                                        class="w-full border border-[#e5e1db] rounded-md p-2.5 pl-10 bg-[#fcfbf9] focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 transition"
                                        placeholder="0">
                                </div>
                                <p class="text-[11px] text-slate-500 mt-1.5">
                                    Biaya dasar pendaftaran. Setiap peserta akan mendapat kode unik 3 digit ditambahkan
                                    ke nominal ini.
                                </p>

                                {{-- Warning Pending --}}
                                <div id="warning-harga"
                                    class="hidden mt-3 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        <div class="text-sm">
                                            <p class="font-semibold text-amber-800">Perhatian: Ada Tagihan Pending!</p>
                                            <p class="text-amber-700 mt-1">
                                                Terdapat <strong id="count-pending">0</strong> peserta dengan tagihan
                                                <strong>belum lunas</strong> yang menggunakan harga lama (<strong
                                                    id="harga-lama">Rp 0</strong>).
                                            </p>
                                            <p class="text-amber-700 mt-1">
                                                Perubahan harga <strong>tidak akan mengubah</strong> tagihan mereka.
                                            </p>
                                            <p class="text-amber-700 mt-1">
                                                Peserta <strong>baru</strong> akan mendapat harga baru: <strong
                                                    id="harga-baru">Rp 0</strong>.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block font-bold mb-1.5">Harga Per Vote (Rp)</label>
                                <div class="relative">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-sm font-semibold text-slate-500">Rp</span>
                                    <input type="text" name="harga_per_vote" id="harga_per_vote"
                                        value="{{ old('harga_per_vote', $event->harga_per_vote) }}" required
                                        inputmode="numeric" autocomplete="off"
                                        class="w-full border border-[#e5e1db] rounded-md p-2.5 pl-10 bg-[#fcfbf9] focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 transition"
                                        placeholder="0">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ===== SECTION 4: JADWAL & LOKASI ===== --}}
                    <div class="bg-white border border-[#e3ded6] rounded-xl p-6 shadow-sm">
                        <div class="mb-4 pb-3 border-b border-[#e5e1db]">
                            <h2 class="font-bold text-slate-800">Jadwal & Lokasi</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Waktu pelaksanaan dan tempat event</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold mb-1.5">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai"
                                    value="{{ old('tanggal_mulai', $event->tanggal_mulai ? \Carbon\Carbon::parse($event->tanggal_mulai)->format('Y-m-d') : '') }}"
                                    required
                                    class="w-full border border-[#e5e1db] rounded-md p-2.5 bg-[#fcfbf9] focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 transition">
                                @error('tanggal_mulai')
                                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block font-bold mb-1.5">Waktu Mulai</label>
                                <input type="time" name="waktu_mulai"
                                    value="{{ old('waktu_mulai', $event->waktu_mulai ? \Carbon\Carbon::parse($event->waktu_mulai)->format('H:i') : '') }}"
                                    class="w-full border border-[#e5e1db] rounded-md p-2.5 bg-[#fcfbf9] focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 transition">
                                @error('waktu_mulai')
                                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block font-bold mb-1.5">Tanggal Selesai <span
                                        class="text-[#98958f] font-normal">(Opsional)</span></label>
                                <input type="date" name="tanggal_selesai"
                                    value="{{ old('tanggal_selesai', $event->tanggal_selesai ? \Carbon\Carbon::parse($event->tanggal_selesai)->format('Y-m-d') : '') }}"
                                    class="w-full border border-[#e5e1db] rounded-md p-2.5 bg-[#fcfbf9] focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 transition">
                                @error('tanggal_selesai')
                                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block font-bold mb-1.5">Waktu Selesai <span
                                        class="text-[#98958f] font-normal">(Opsional)</span></label>
                                <input type="time" name="waktu_selesai"
                                    value="{{ old('waktu_selesai', $event->waktu_selesai ? \Carbon\Carbon::parse($event->waktu_selesai)->format('H:i') : '') }}"
                                    class="w-full border border-[#e5e1db] rounded-md p-2.5 bg-[#fcfbf9] focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 transition">
                                @error('waktu_selesai')
                                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block font-bold mb-1.5">Lokasi</label>
                                <input type="text" name="lokasi" value="{{ old('lokasi', $event->lokasi) }}"
                                    required
                                    class="w-full border border-[#e5e1db] rounded-md p-2.5 bg-[#fcfbf9] focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 transition">
                                @error('lokasi')
                                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- ===== SECTION 5: PENDAFTARAN ===== --}}
                    <div class="bg-white border border-[#e3ded6] rounded-xl p-6 shadow-sm">
                        <div class="mb-4 pb-3 border-b border-[#e5e1db]">
                            <h2 class="font-bold text-slate-800">Pendaftaran</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Batas waktu dan jumlah slot untuk calon peserta
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold mb-1.5">Pembukaan Pendaftaran (Tanggal)</label>
                                <input type="date" name="pembukaan_pendaftaran_tanggal"
                                    value="{{ old('pembukaan_pendaftaran_tanggal', $event->pembukaan_pendaftaran_tanggal?->format('Y-m-d')) }}"
                                    class="w-full border border-[#e5e1db] rounded-md p-2.5 bg-[#fcfbf9] focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 transition">
                                @error('pembukaan_pendaftaran_tanggal')
                                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block font-bold mb-1.5">Pembukaan Pendaftaran (Waktu)</label>
                                <input type="time" name="pembukaan_pendaftaran_waktu"
                                    value="{{ old('pembukaan_pendaftaran_waktu', $event->pembukaan_pendaftaran_waktu?->format('H:i')) }}"
                                    class="w-full border border-[#e5e1db] rounded-md p-2.5 bg-[#fcfbf9] focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 transition">
                                @error('pembukaan_pendaftaran_waktu')
                                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block font-bold mb-1.5">Batas Pendaftaran (Tanggal)</label>
                                <input type="date" name="batas_pendaftaran_tanggal"
                                    value="{{ old('batas_pendaftaran_tanggal', $event->batas_pendaftaran_tanggal?->format('Y-m-d')) }}"
                                    required
                                    class="w-full border border-[#e5e1db] rounded-md p-2.5 bg-[#fcfbf9] focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 transition">
                                @error('batas_pendaftaran_tanggal')
                                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block font-bold mb-1.5">Batas Pendaftaran (Waktu)</label>
                                <input type="time" name="batas_pendaftaran_waktu"
                                    value="{{ old('batas_pendaftaran_waktu', $event->batas_pendaftaran_waktu?->format('H:i')) }}"
                                    required
                                    class="w-full border border-[#e5e1db] rounded-md p-2.5 bg-[#fcfbf9] focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 transition">
                                @error('batas_pendaftaran_waktu')
                                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block font-bold mb-1.5">Kuota Peserta</label>
                                <div class="relative">
                                    <input type="number" name="kuota_peserta"
                                        value="{{ old('kuota_peserta', $event->kuota_peserta) }}" required
                                        min="1"
                                        class="w-full border border-[#e5e1db] rounded-md p-2.5 bg-[#fcfbf9] pr-16 focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 transition">
                                    <span
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-slate-500">orang</span>
                                </div>
                                @error('kuota_peserta')
                                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div> {{-- END KOLOM KIRI --}}

                {{-- ============================================ --}}
                {{-- KOLOM KANAN — PREVIEW                        --}}
                {{-- ============================================ --}}
                <aside class="lg:sticky lg:top-6 space-y-3">
                    <p class="text-[10px] uppercase tracking-wide text-[#8a8984] font-bold">Pratinjau Halaman Event</p>

                    <div class="bg-white border border-[#e3ded6] rounded-xl overflow-hidden shadow-sm">
                        <div id="previewBanner"
                            class="relative bg-gradient-to-br from-[#c99a4a] to-[#ba8320] h-40 flex items-center justify-center rounded-t-xl overflow-hidden">
                            <span id="previewStatusBadge"
                                class="absolute top-3 left-3 z-10 bg-white/90 text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm">
                                Akan Datang
                            </span>

                            @if ($event->banner)
                                <span id="previewBannerEmpty" class="text-white/80 text-[11px] hidden">Belum ada
                                    banner diunggah</span>
                                <img id="previewBannerImg" src="{{ Storage::url($event->banner) }}"
                                    class="absolute inset-0 w-full h-full object-cover z-0"
                                    alt="{{ $event->nama }}">
                            @else
                                <span id="previewBannerEmpty" class="text-white/80 text-[11px]">Belum ada banner
                                    diunggah</span>
                                <img id="previewBannerImg"
                                    class="hidden absolute inset-0 w-full h-full object-cover z-0" alt="">
                            @endif
                        </div>

                        <div class="p-4">
                            <h3 id="previewNama" class="font-bold text-[15px] mb-2">Nama event akan muncul di sini
                            </h3>
                            <p id="previewDeskripsi" class="text-[11px] text-[#73716c] mb-3 line-clamp-3">
                                Deskripsi event akan muncul di sini.</p>

                            <div class="grid grid-cols-2 gap-2 mb-3">
                                <div class="bg-[#f7f5f0] rounded-lg p-2.5">
                                    <div class="text-[9px] uppercase text-[#98958f] font-bold mb-1">Hari & Jam</div>
                                    <div id="previewJadwal" class="text-[11px] font-semibold">—</div>
                                </div>
                                <div class="bg-[#f7f5f0] rounded-lg p-2.5">
                                    <div class="text-[9px] uppercase text-[#98958f] font-bold mb-1">Tempat</div>
                                    <div id="previewLokasi" class="text-[11px] font-semibold">—</div>
                                </div>
                            </div>

                            <div class="space-y-1.5 border-t border-[#e5e1db] pt-3 text-[11px]">
                                <div class="flex justify-between">
                                    <span class="text-[#73716c]">Biaya Daftar</span>
                                    <strong id="previewBiaya">Gratis</strong>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-[#73716c]">Harga / Vote</span>
                                    <strong id="previewHargaVote">—</strong>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-[#73716c]">Batas Pendaftaran</span>
                                    <strong id="previewBatas">—</strong>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-[#73716c]">Kuota Peserta</span>
                                    <strong id="previewKuota">—</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- ===== TOMBOL SUBMIT — DI BAWAH PREVIEW ===== --}}
                    <div class="pt-3 border-t border-[#e5e1db] flex gap-2.5">
                        <a href="{{ route('admin.events.index') }}"
                            class="flex-1 px-4 py-2.5 border border-[#e5e1db] bg-white rounded-md text-ink font-bold text-center hover:bg-[#f7f5f0] transition text-[13px]">
                            Batal
                        </a>
                        <button type="submit"
                            class="flex-1 px-4 py-2.5 bg-gold text-white font-bold rounded-md hover:bg-[#a3721a] transition shadow-sm text-[13px]">
                            Simpan Perubahan
                        </button>
                    </div>
                </aside>

            </form>

        </main>
    </div>

    @include('admin.partials.sidebar', ['active' => 'events-index', 'event' => $event])

    {{-- Script sama seperti sebelumnya — gak diubah --}}
    <script>
        document.getElementById('biaya_pendaftaran')?.addEventListener('input', function() {
            const hargaBaru = parseInt(this.value.replace(/\D/g, '')) || 0;
            const hargaLama = {{ $event->biaya_pendaftaran ?? 0 }};
            const warningBox = document.getElementById('warning-harga');

            if (hargaBaru !== hargaLama && hargaBaru > 0) {
                fetch(`/admin/api/event/{{ $event->id }}/pending-payments`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.count > 0) {
                            document.getElementById('count-pending').textContent = data.count;
                            document.getElementById('harga-lama').textContent = 'Rp ' + data.harga_lama
                                .toLocaleString('id-ID');
                            document.getElementById('harga-baru').textContent = 'Rp ' + hargaBaru
                                .toLocaleString('id-ID');
                            warningBox.classList.remove('hidden');
                        } else {
                            warningBox.classList.add('hidden');
                        }
                    });
            } else {
                warningBox.classList.add('hidden');
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const statusCards = document.querySelectorAll('.status-card');
            const colorMap = {
                blue: {
                    activeBorder: 'border-blue-500',
                    activeBg: 'bg-blue-50'
                },
                emerald: {
                    activeBorder: 'border-emerald-500',
                    activeBg: 'bg-emerald-50'
                },
                orange: {
                    activeBorder: 'border-orange-500',
                    activeBg: 'bg-orange-50'
                }
            };

            function updateStatusStyles() {
                statusCards.forEach(card => {
                    const radio = card.querySelector('input[type="radio"]');
                    const colorKey = card.getAttribute('data-color');
                    const {
                        activeBorder,
                        activeBg
                    } = colorMap[colorKey];

                    if (radio.checked) {
                        card.classList.remove('border-[#e5e1db]', 'hover:bg-[#f5f1eb]');
                        card.classList.add(activeBorder, activeBg);
                    } else {
                        card.classList.remove(activeBorder, activeBg);
                        card.classList.add('border-[#e5e1db]', 'hover:bg-[#f5f1eb]');
                    }
                });
            }

            statusCards.forEach(card => {
                const radio = card.querySelector('input[type="radio"]');
                radio.addEventListener('change', updateStatusStyles);
            });
            updateStatusStyles();
        });

        document.addEventListener('DOMContentLoaded', function() {
            const namaInput = document.querySelector('input[name="nama"]');
            const deskripsiInput = document.querySelector('textarea[name="deskripsi"]');
            const tglMulaiInput = document.querySelector('input[name="tanggal_mulai"]');
            const wktMulaiInput = document.querySelector('input[name="waktu_mulai"]');
            const tglSelesaiInput = document.querySelector('input[name="tanggal_selesai"]');
            const wktSelesaiInput = document.querySelector('input[name="waktu_selesai"]');
            const lokasiInput = document.querySelector('input[name="lokasi"]');
            const biayaInput = document.querySelector('input[name="biaya_pendaftaran"]');
            const hargaVoteInput = document.querySelector('input[name="harga_per_vote"]');
            const batasTglInput = document.querySelector('input[name="batas_pendaftaran_tanggal"]');
            const batasWktInput = document.querySelector('input[name="batas_pendaftaran_waktu"]');
            const kuotaInput = document.querySelector('input[name="kuota_peserta"]');
            const bannerInput = document.querySelector('input[name="banner"]');
            const statusInputs = document.querySelectorAll('input[name="status"]');

            const previewNama = document.getElementById('previewNama');
            const previewDeskripsi = document.getElementById('previewDeskripsi');
            const previewJadwal = document.getElementById('previewJadwal');
            const previewLokasi = document.getElementById('previewLokasi');
            const previewBiaya = document.getElementById('previewBiaya');
            const previewHargaVote = document.getElementById('previewHargaVote');
            const previewBatas = document.getElementById('previewBatas');
            const previewKuota = document.getElementById('previewKuota');
            const previewStatusBadge = document.getElementById('previewStatusBadge');
            const previewBannerImg = document.getElementById('previewBannerImg');
            const previewBannerEmpty = document.getElementById('previewBannerEmpty');

            function formatRupiah(val) {
                const numeric = String(val).replace(/\D/g, '');
                if (!numeric || numeric === '0') return 'Gratis';
                return 'Rp ' + parseInt(numeric).toLocaleString('id-ID');
            }

            function formatTanggal(strDate) {
                if (!strDate) return '';
                const d = new Date(strDate);
                if (isNaN(d.getTime())) return strDate;
                return d.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });
            }

            function updatePreview() {
                if (previewNama) previewNama.textContent = namaInput?.value || 'Nama event belum diisi';
                if (previewDeskripsi) previewDeskripsi.textContent = deskripsiInput?.value ||
                    'Deskripsi belum diisi';
                if (previewLokasi) previewLokasi.textContent = lokasiInput?.value || '—';
                if (previewBiaya) previewBiaya.textContent = formatRupiah(biayaInput?.value);
                if (previewHargaVote) previewHargaVote.textContent = hargaVoteInput?.value ? formatRupiah(
                    hargaVoteInput.value) : '—';
                if (previewKuota) previewKuota.textContent = kuotaInput?.value ? `${kuotaInput.value} Orang` : '—';

                let jadwalStr = '—';
                if (tglMulaiInput?.value) {
                    jadwalStr = formatTanggal(tglMulaiInput.value);
                    if (wktMulaiInput?.value) jadwalStr += ` ${wktMulaiInput.value}`;
                    if (tglSelesaiInput?.value) {
                        jadwalStr += ` - ${formatTanggal(tglSelesaiInput.value)}`;
                        if (wktSelesaiInput?.value) jadwalStr += ` ${wktSelesaiInput.value}`;
                    }
                }
                if (previewJadwal) previewJadwal.textContent = jadwalStr;

                let batasStr = '—';
                if (batasTglInput?.value) {
                    batasStr = formatTanggal(batasTglInput.value);
                    if (batasWktInput?.value) batasStr += ` ${batasWktInput.value}`;
                }
                if (previewBatas) previewBatas.textContent = batasStr;

                const checkedStatus = document.querySelector('input[name="status"]:checked')?.value;
                if (previewStatusBadge && checkedStatus) {
                    const statusConfig = {
                        'akan_datang': {
                            text: 'Akan Datang',
                            class: 'bg-blue-100 text-blue-700'
                        },
                        'sedang_berlangsung': {
                            text: 'Sedang Berlangsung',
                            class: 'bg-emerald-100 text-emerald-700'
                        },
                        'telah_berakhir': {
                            text: 'Telah Berakhir',
                            class: 'bg-orange-100 text-orange-700'
                        }
                    };
                    const config = statusConfig[checkedStatus] || {
                        text: checkedStatus,
                        class: 'bg-gray-100 text-gray-700'
                    };
                    previewStatusBadge.textContent = config.text;
                    previewStatusBadge.className =
                        `absolute top-3 left-3 z-10 text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm ${config.class}`;
                }
            }

            [namaInput, deskripsiInput, tglMulaiInput, wktMulaiInput, tglSelesaiInput, wktSelesaiInput,
                lokasiInput, biayaInput, hargaVoteInput, batasTglInput, batasWktInput, kuotaInput
            ].forEach(input => {
                if (input) {
                    input.addEventListener('input', updatePreview);
                    input.addEventListener('change', updatePreview);
                }
            });

            statusInputs.forEach(radio => radio.addEventListener('change', updatePreview));

            if (bannerInput) {
                bannerInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            if (previewBannerImg) {
                                previewBannerImg.src = e.target.result;
                                previewBannerImg.classList.remove('hidden');
                            }
                            if (previewBannerEmpty) previewBannerEmpty.classList.add('hidden');
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            updatePreview();
        });

        document.addEventListener('DOMContentLoaded', function() {
            const biayaInput = document.getElementById('biaya_pendaftaran');
            const voteInput = document.getElementById('harga_per_vote');
            const previewBiaya = document.getElementById('previewBiaya');
            const previewHargaVote = document.getElementById('previewHargaVote');

            function getNumericValue(value) {
                return String(value ?? '').replace(/\D/g, '');
            }

            function formatNumber(value) {
                const numeric = getNumericValue(value);
                if (!numeric) return '';
                return new Intl.NumberFormat('id-ID').format(Number(numeric));
            }

            function formatRupiah(value) {
                const numeric = getNumericValue(value);
                if (!numeric || Number(numeric) === 0) return 'Gratis';
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(Number(numeric));
            }

            function updatePreview() {
                if (previewBiaya && biayaInput) {
                    const value = getNumericValue(biayaInput.value);
                    previewBiaya.textContent = value ? formatRupiah(value) : 'Gratis';
                }
                if (previewHargaVote && voteInput) {
                    const value = getNumericValue(voteInput.value);
                    previewHargaVote.textContent = value ? formatRupiah(value) : '—';
                }
            }

            function setupCurrencyInput(input) {
                if (!input) return;
                input.addEventListener('input', function() {
                    const numericValue = getNumericValue(this.value);
                    this.value = formatNumber(numericValue);
                    updatePreview();
                });
                input.addEventListener('blur', function() {
                    const numericValue = getNumericValue(this.value);
                    this.value = formatNumber(numericValue);
                    updatePreview();
                });
            }

            if (biayaInput) biayaInput.value = formatNumber(biayaInput.value);
            if (voteInput) voteInput.value = formatNumber(voteInput.value);

            setupCurrencyInput(biayaInput);
            setupCurrencyInput(voteInput);
            updatePreview();

            const form = biayaInput ? biayaInput.closest('form') : null;
            if (form) {
                form.addEventListener('submit', function() {
                    if (biayaInput) biayaInput.value = getNumericValue(biayaInput.value);
                    if (voteInput) voteInput.value = getNumericValue(voteInput.value);
                });
            }
        });
    </script>

</body>

</html>
