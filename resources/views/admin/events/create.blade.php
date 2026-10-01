<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Buat Event Baru | ALNAY.ID</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Georgia&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/css/admin-sidebar.css') }}">
    
    <style>
        body {
            font-family: 'Inter', 'Arial', sans-serif;
        }
    </style>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold: '#ba8320',
                        cream: '#f7f5f0',
                        ink: '#22201d',
                    },
                    fontFamily: {
                        sans: ['Inter', 'Arial', 'sans-serif'],
                        serif: ['Georgia', 'serif'],
                    },
                },
            },
        };
    </script>
</head>
<body class="bg-cream text-ink font-sans text-[13px]">
    <div class="flex min-h-screen w-full">

        {{-- ===================== MAIN ===================== --}}
        <main class="flex-1 min-w-0 ml-[235px]">

            {{-- HEADER --}}
            <header class="flex items-center justify-between px-6 py-4 border-b border-[#e5e1db] bg-white">
                <div class="flex items-center gap-3">
                    <button type="button" onclick="window.history.back()" class="p-2 text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </button>
                    <div class="w-9 h-9 rounded-lg bg-[#f3eee3] text-[#37322b] grid place-items-center font-bold">A</div>
                    <div>
                        <h1 class="font-bold text-[16px] m-0">Buat Event Baru</h1>
                        <p class="text-[10px] text-[#8a8984] m-0">ALNAY.ID — Admin Backoffice</p>
                    </div>
                </div>
            </header>

            @if (session('success'))
                <div class="mx-6 mt-4 rounded-lg bg-emerald-50 text-emerald-700 px-4 py-3 text-[12px]">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-4 bg-rose-50 border border-rose-200 rounded-lg">
                    <p class="text-sm font-semibold text-rose-700 mb-2">Ada isian yang belum sesuai:</p>
                    <ul class="list-disc list-inside text-sm text-rose-600 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                id="formEvent"
                method="POST"
                action="{{ route('admin.events.store') }}"
                enctype="multipart/form-data"
                class="grid grid-cols-1 md:grid-cols-[minmax(0,1fr)_340px] gap-5 p-6 items-start">
                @csrf

                

                {{-- ===== KOLOM FORM ===== --}}
                <div class="space-y-5 min-w-0">

                    {{-- Banner & Informasi Utama --}}
                    <div class="bg-white border border-[#e3ded6] rounded-xl p-6 shadow-sm">
                        <h2 class="font-bold text-[16px] mb-1">Banner & Informasi Utama</h2>
                        <p class="text-[11px] text-[#73716c] mb-4">Gambar dan deskripsi ini yang akan dilihat pertama kali oleh calon peserta.</p>

                        <label class="block text-[12px] font-bold mb-2">Gambar Banner Event</label>
                        <label id="dropZone" class="flex flex-col items-center justify-center gap-2 border-2 border-dashed border-[#d5cbbd] rounded-xl py-8 cursor-pointer hover:border-gold bg-[#fcfbf9] mb-5 overflow-hidden">
                            <input type="file" name="banner" id="bannerInput" accept="image/*" class="hidden">
                            <div id="dropZoneEmpty" class="flex flex-col items-center gap-2">
                                <span class="text-[12px]">Tarik & lepas gambar di sini, atau <span class="text-gold underline">jelajahi file</span></span>
                                <span class="text-[10px] text-[#98958f]">Rasio 16:9 disarankan • JPG, PNG, atau WEBP hingga 5 MB</span>
                            </div>
                            <img id="bannerPreviewImg" class="hidden w-full h-40 object-cover rounded-lg" alt="">
                        </label>
                        @error('banner')<div class="text-red-600 text-[10px] -mt-3 mb-4">{{ $message }}</div>@enderror

                        <label class="block text-[12px] font-bold mb-2">Nama Event</label>
                        <input type="text" name="nama" id="inputNama" value="{{ old('nama') }}"
                               class="w-full border border-[#e5e1db] rounded-lg px-3 py-2.5 text-[12px] mb-4" placeholder="Contoh: Pendaftaran Putra Putri ALNAY.ID 2026">
                        @error('nama')<div class="text-red-600 text-[10px] -mt-3 mb-4">{{ $message }}</div>@enderror

                        <label class="block text-[12px] font-bold mb-2">Deskripsi Event</label>
                        <textarea name="deskripsi" id="inputDeskripsi" rows="3"
                                  class="w-full border border-[#e5e1db] rounded-lg px-3 py-2.5 text-[12px] mb-4" placeholder="Jelaskan mengenai detail event ini...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')<div class="text-red-600 text-[10px] -mt-3 mb-4">{{ $message }}</div>@enderror

                        <label class="block text-[12px] font-bold mb-2">Syarat & Ketentuan</label>
                        <textarea name="syarat_ketentuan" id="inputSyaratKetentuan" rows="4"
                                  class="w-full border border-[#e5e1db] rounded-lg px-3 py-2.5 text-[12px] mb-4" placeholder="Masukkan syarat dan ketentuan event (pisahkan tiap baris dengan enter)...">{{ old('syarat_ketentuan') }}</textarea>
                        @error('syarat_ketentuan')<div class="text-red-600 text-[10px] -mt-3 mb-4">{{ $message }}</div>@enderror

                        {{-- Status Event --}}
                        <div>
                            <label class="block font-bold mb-2">Status Event</label>
                            <div class="grid grid-cols-3 gap-3">
                                {{-- Akan Datang (default checked) --}}
                                <label class="cursor-pointer border-2 rounded-lg p-3 flex items-center gap-2 transition {{ old('status', 'akan_datang') === 'akan_datang' ? 'border-blue-500 bg-blue-50' : 'border-[#e5e1db] hover:bg-[#f5f1eb]' }}">
                                    <input type="radio" name="status" value="akan_datang"
                                        {{ old('status', 'akan_datang') === 'akan_datang' ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600">
                                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                                    <span class="text-sm font-medium">Akan Datang</span>
                                </label>

                                {{-- Sedang Berlangsung --}}
                                <label class="cursor-pointer border-2 rounded-lg p-3 flex items-center gap-2 transition {{ old('status') === 'sedang_berlangsung' ? 'border-emerald-500 bg-emerald-50' : 'border-[#e5e1db] hover:bg-[#f5f1eb]' }}">
                                    <input type="radio" name="status" value="sedang_berlangsung"
                                        {{ old('status') === 'sedang_berlangsung' ? 'checked' : '' }}
                                        class="w-4 h-4 text-emerald-600">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                    <span class="text-sm font-medium">Sedang Berlangsung</span>
                                </label>

                                {{-- Telah Berakhir --}}
                                <label class="cursor-pointer border-2 rounded-lg p-3 flex items-center gap-2 transition {{ old('status') === 'telah_berakhir' ? 'border-orange-500 bg-orange-50' : 'border-[#e5e1db] hover:bg-[#f5f1eb]' }}">
                                    <input type="radio" name="status" value="telah_berakhir"
                                        {{ old('status') === 'telah_berakhir' ? 'checked' : '' }}
                                        class="w-4 h-4 text-orange-600">
                                    <span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span>
                                    <span class="text-sm font-medium">Telah Berakhir</span>
                                </label>
                            </div>
                            @error('status') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Jadwal & Lokasi Event --}}
                    <div class="bg-white border border-[#e3ded6] rounded-xl p-6 shadow-sm">
                        <h2 class="font-bold text-[16px] mb-1">Jadwal & Lokasi Event</h2>
                        <p class="text-[11px] text-[#73716c] mb-4">Atur waktu dan tempat pelaksanaan event. Tanggal &amp; waktu selesai bersifat opsional.</p>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-[12px] font-bold mb-2">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" id="inputTanggalMulai" value="{{ old('tanggal_mulai') }}"
                                       class="w-full border border-[#e5e1db] rounded-lg px-3 py-2.5 text-[12px]">
                                @error('tanggal_mulai')<div class="text-red-600 text-[10px] mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="block text-[12px] font-bold mb-2">Waktu Mulai</label>
                                <input type="time" name="waktu_mulai" id="inputWaktuMulai" value="{{ old('waktu_mulai') }}"
                                       class="w-full border border-[#e5e1db] rounded-lg px-3 py-2.5 text-[12px]">
                                @error('waktu_mulai')<div class="text-red-600 text-[10px] mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="block text-[12px] font-bold mb-2">Tanggal Selesai <span class="text-[#98958f] font-normal">(Opsional)</span></label>
                                <input type="date" name="tanggal_selesai" id="inputTanggalSelesai" value="{{ old('tanggal_selesai') }}"
                                       class="w-full border border-[#e5e1db] rounded-lg px-3 py-2.5 text-[12px]">
                                @error('tanggal_selesai')<div class="text-red-600 text-[10px] mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="block text-[12px] font-bold mb-2">Waktu Selesai <span class="text-[#98958f] font-normal">(Opsional)</span></label>
                                <input type="time" name="waktu_selesai" id="inputWaktuSelesai" value="{{ old('waktu_selesai') }}"
                                       class="w-full border border-[#e5e1db] rounded-lg px-3 py-2.5 text-[12px]">
                                @error('waktu_selesai')<div class="text-red-600 text-[10px] mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <label class="block text-[12px] font-bold mb-2">Lokasi</label>
                        <input type="text" name="lokasi" id="inputLokasi" value="{{ old('lokasi') }}"
                               class="w-full border border-[#e5e1db] rounded-lg px-3 py-2.5 text-[12px]" placeholder="Contoh: Citra II, Universitas Amikom Yogyakarta">
                        @error('lokasi')<div class="text-red-600 text-[10px] mt-1">{{ $message }}</div>@enderror
                    </div>

                    {{-- Biaya & Voting --}}
                    <div class="bg-white border border-[#e3ded6] rounded-xl p-6 shadow-sm">
                        <h2 class="font-bold text-[16px] mb-1">Biaya & Voting</h2>
                        <p class="text-[11px] text-[#73716c] mb-4">Pengaturan tarif pendaftaran dan sistem harga vote event.</p>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[12px] font-bold mb-2">Biaya Pendaftaran</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[11px] text-[#98958f] font-bold">Rp</span>
                                    <input type="number" name="biaya_pendaftaran" id="inputBiayaPendaftaran" min="0" value="{{ old('biaya_pendaftaran', 0) }}"
                                           class="w-full border border-[#e5e1db] rounded-lg pl-9 pr-3 py-2.5 text-[12px]" placeholder="0">
                                </div>
                                <p class="text-[10px] text-[#98958f] mt-1">Isi 0 jika pendaftaran gratis.</p>
                                @error('biaya_pendaftaran')<div class="text-red-600 text-[10px] mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div>
                                <label class="block text-[12px] font-bold mb-2">Harga Per Vote</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[11px] text-[#98958f] font-bold">Rp</span>
                                    <input type="number" name="harga_per_vote" id="inputHargaPerVote" min="0" value="{{ old('harga_per_vote', 0) }}"
                                           class="w-full border border-[#e5e1db] rounded-lg pl-9 pr-3 py-2.5 text-[12px]" placeholder="0">
                                </div>
                                <p class="text-[10px] text-[#98958f] mt-1">Tarif nominal yang ditagih per 1 kali vote.</p>
                                @error('harga_per_vote')<div class="text-red-600 text-[10px] mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- Pendaftaran --}}
                    <div class="bg-white rounded-xl border border-[#e5e1db] p-6">
                        <h3 class="font-bold text-slate-800 mb-1">Pendaftaran</h3>
                        <p class="text-xs text-slate-500 mb-4">Batas waktu dan jumlah slot yang tersedia untuk calon peserta.</p>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            {{-- ✅ PEMBUKAAN PENDAFTARAN (BARU) --}}
                            <div>
                                <label class="block font-bold mb-1">Pembukaan Pendaftaran (Tanggal)</label>
                                <input type="date" name="pembukaan_pendaftaran_tanggal" 
                                    value="{{ old('pembukaan_pendaftaran_tanggal') }}" 
                                    class="w-full border border-[#e5e1db] rounded-md p-2.5 bg-[#fcfbf9]">
                                @error('pembukaan_pendaftaran_tanggal')
                                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block font-bold mb-1">Pembukaan Pendaftaran (Waktu)</label>
                                <input type="time" name="pembukaan_pendaftaran_waktu" 
                                    value="{{ old('pembukaan_pendaftaran_waktu') }}" 
                                    class="w-full border border-[#e5e1db] rounded-md p-2.5 bg-[#fcfbf9]">
                                @error('pembukaan_pendaftaran_waktu')
                                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            {{-- Batas Pendaftaran --}}
                            <div>
                                <label class="block font-bold mb-1">Batas Pendaftaran (Tanggal)</label>
                                <input type="date" name="batas_pendaftaran_tanggal" 
                                    id="inputBatasTanggal"
                                    value="{{ old('batas_pendaftaran_tanggal') }}" 
                                    required
                                    class="w-full border border-[#e5e1db] rounded-md p-2.5 bg-[#fcfbf9]">
                                @error('batas_pendaftaran_tanggal')
                                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block font-bold mb-1">Batas Pendaftaran (Waktu)</label>
                                <input type="time" name="batas_pendaftaran_waktu" 
                                    id="inputBatasWaktu"
                                    value="{{ old('batas_pendaftaran_waktu') }}" 
                                    required
                                    class="w-full border border-[#e5e1db] rounded-md p-2.5 bg-[#fcfbf9]">
                                @error('batas_pendaftaran_waktu')
                                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold mb-1">Kuota Peserta</label>
                            <div class="relative">
                                <input type="number" name="kuota_peserta" 
                                    id="inputKuota"
                                    value="{{ old('kuota_peserta') }}" 
                                    min="1"
                                    placeholder="Contoh: 100"
                                    class="w-full border border-[#e5e1db] rounded-md p-2.5 bg-[#fcfbf9] pr-16">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-slate-500">orang</span>
                            </div>
                            @error('kuota_peserta')
                                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3 bg-[#ba8320] hover:bg-[#a06f1a] text-white font-bold rounded-lg transition-colors">
                        ✓ Publikasikan Event
                    </button>
                </div>

                {{-- ===== PREVIEW ===== --}}
                <aside class="md:sticky md:top-6 space-y-3">
                    <p class="text-[10px] uppercase tracking-wide text-[#8a8984] font-bold">Pratinjau Halaman Event</p>

                    <div class="bg-white border border-[#e3ded6] rounded-xl overflow-hidden shadow-sm">
                        <div id="previewBanner" class="relative bg-gradient-to-br from-[#c99a4a] to-[#ba8320] h-40 flex items-center justify-center">
                            <span id="previewStatusBadge" class="absolute top-3 left-3 bg-white/90 text-[10px] font-bold px-2.5 py-1 rounded-full">Akan Datang</span>
                            <span id="previewBannerEmpty" class="text-white/80 text-[11px]">Belum ada banner diunggah</span>
                            <img id="previewBannerImg" class="hidden absolute inset-0 w-full h-full object-cover" alt="">
                        </div>
                        <div class="p-4">
                            <h3 id="previewNama" class="font-bold text-[15px] mb-2">Nama event akan muncul di sini</h3>
                            <p id="previewDeskripsi" class="text-[11px] text-[#73716c] mb-3 line-clamp-3">Deskripsi event akan muncul di sini.</p>

                            <div class="grid grid-cols-2 gap-2 mb-3">
                                <div class="bg-[#f7f5f0] rounded-lg p-2.5">
                                    <div class="text-[9px] uppercase text-[#98958f] font-bold mb-1">Hari &amp; Jam</div>
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
                </aside>
            </form>
        </main>
    </div>

    <script>
        const $ = (id) => document.getElementById(id);

    const inputNama = $('inputNama');
    const inputDeskripsi = $('inputDeskripsi');
    const inputSyaratKetentuan = $('inputSyaratKetentuan');
    const inputBiayaPendaftaran = $('inputBiayaPendaftaran');
    const inputHargaPerVote = $('inputHargaPerVote');
    const inputTanggalMulai = $('inputTanggalMulai');
    const inputWaktuMulai = $('inputWaktuMulai');
    const inputTanggalSelesai = $('inputTanggalSelesai');
    const inputWaktuSelesai = $('inputWaktuSelesai');
    const inputLokasi = $('inputLokasi');
    const inputBatasTanggal = $('inputBatasTanggal');
    const inputBatasWaktu = $('inputBatasWaktu');
    const inputKuota = $('inputKuota');
    const bannerInput = $('bannerInput');

    const HARI = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const BULAN = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

    function formatRupiah(number) {
        if (!number || number <= 0) return 'Gratis';
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
    }

    function formatTanggalIndo(dateStr) {
        if (!dateStr) return null;
        const d = new Date(dateStr + 'T00:00:00');
        if (isNaN(d)) return null;
        return `${HARI[d.getDay()]}, ${d.getDate()} ${BULAN[d.getMonth()]} ${d.getFullYear()}`;
    }

    function formatJam(t) {
        return t ? t : null;
    }

    /*
    |--------------------------------------------------------------------------
    | Live preview text (Sudah diperbaiki)
    |--------------------------------------------------------------------------
    */
    function updatePreview() {

        document.querySelectorAll('input[name="event_status"]').forEach(radio => {
            radio.addEventListener('change', updatePreview);
        });

        if ($('previewNama') && inputNama)$('previewNama').textContent = inputNama.value.trim() || 'Nama event akan muncul di sini';
        if ($('previewDeskripsi') && inputDeskripsi)$('previewDeskripsi').textContent = inputDeskripsi.value.trim() || 'Deskripsi event akan muncul di sini.';
        if ($('previewLokasi') && inputLokasi)$('previewLokasi').textContent = inputLokasi.value.trim() || '—';

        // Biaya & Vote
        if ($('previewBiaya') && inputBiayaPendaftaran)$('previewBiaya').textContent = formatRupiah(inputBiayaPendaftaran.value);
        if ($('previewHargaVote') && inputHargaPerVote)$('previewHargaVote').textContent = inputHargaPerVote.value > 0 ? formatRupiah(inputHargaPerVote.value) : '—';

        // Jadwal Event
        if ($('previewJadwal') && inputTanggalMulai) {
            const tglMulai = formatTanggalIndo(inputTanggalMulai.value);
            const jamMulai = formatJam(inputWaktuMulai?.value);
            const jamSelesai = formatJam(inputWaktuSelesai?.value);

            if (tglMulai) {
                let jadwal = tglMulai;
                if (jamMulai) jadwal += `\n${jamMulai}` + (jamSelesai ? ` - ${jamSelesai} WIB` : ' WIB');
                $('previewJadwal').innerText = jadwal;
            } else {
                $('previewJadwal').textContent = '—';
            }
        }

        // Batas Pendaftaran
        if ($('previewBatas') && inputBatasTanggal) {
            const batasTgl = formatTanggalIndo(inputBatasTanggal.value);
            $('previewBatas').textContent = batasTgl
                ? `${batasTgl}${inputBatasWaktu?.value ? ', ' + inputBatasWaktu.value + ' WIB' : ''}`
                : '—';
        }

        if ($('previewKuota') && inputKuota)$('previewKuota').textContent = inputKuota.value ? `${inputKuota.value} orang` : '—';

        updateStatus();
    }

    /*
    |--------------------------------------------------------------------------
    | Status dihitung otomatis dari tanggal+waktu
    |--------------------------------------------------------------------------
    */
    function updateStatus() {
        const checkedRadio = document.querySelector('input[name="event_status"]:checked');
        const previewBadge = document.getElementById('previewStatusBadge');

        if (!checkedRadio || !previewBadge) return;

        const statusConfig = {
            'akan_datang': {
                label: 'Akan Datang',
                classes: 'bg-white/90 text-blue-700'
            },
            'berlangsung': {
                label: 'Sedang Berlangsung',
                classes: 'bg-white/90 text-emerald-700'
            },
            'selesai': {
                label: 'Telah Berakhir',
                classes: 'bg-white/90 text-orange-700'
            }
        };

        const config = statusConfig[checkedRadio.value];
        if (config) {
            previewBadge.textContent = config.label;
            previewBadge.className = `absolute top-3 left-3 text-[10px] font-bold px-2.5 py-1 rounded-full ${config.classes}`;
        }
    }

    // Event Listener untuk Text Input
    [inputNama, inputDeskripsi, inputSyaratKetentuan, inputBiayaPendaftaran, inputHargaPerVote,
     inputTanggalMulai, inputWaktuMulai, inputTanggalSelesai, inputWaktuSelesai, 
     inputLokasi, inputBatasTanggal, inputBatasWaktu, inputKuota]
        .forEach(el => el && el.addEventListener('input', updatePreview));

    /*
    |--------------------------------------------------------------------------
    | Preview gambar banner
    |--------------------------------------------------------------------------
    */
    if (bannerInput) {
        bannerInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                if ($('dropZoneEmpty'))$('dropZoneEmpty').classList.add('hidden');
                
                const previewImg = $('bannerPreviewImg');
                if (previewImg) {
                    previewImg.src = e.target.result;
                    previewImg.classList.remove('hidden');
                }

                if ($('previewBannerEmpty'))$('previewBannerEmpty').classList.add('hidden');
                
                const bigPreview = $('previewBannerImg');
                if (bigPreview) {
                    bigPreview.src = e.target.result;
                    bigPreview.classList.remove('hidden');
                }
            };
            reader.readAsDataURL(file);
        });
    }

    // Drag & drop file ke dropzone
    const dropZone = $('dropZone');
    if (dropZone) {
        dropZone.addEventListener('dragover', (e) => { e.preventDefault(); dropZone.classList.add('border-gold'); });
        dropZone.addEventListener('dragleave', () => dropZone.classList.remove('border-gold'));
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-gold');
            if (e.dataTransfer.files[0] && bannerInput) {
                bannerInput.files = e.dataTransfer.files;
                bannerInput.dispatchEvent(new Event('change'));
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('formEvent');
        const DRAFT_KEY = 'draft_event_create';
        const draftNotice = document.getElementById('draftNotice');
        const clearDraftBtn = document.getElementById('clearDraftBtn');

        // 1. Fungsi Simpan Draft ke LocalStorage
        // 1. Fungsi Simpan Draft ke LocalStorage (Sudah Diperbaiki)
        function saveDraft() {
            if (!form) return;
            const formData = {};
            const elements = form.querySelectorAll('input, textarea, select');

            elements.forEach(el => {
                if (el.name && el.name !== '_token' && el.type !== 'file') {
                    // Khusus Radio Button: Hanya simpan jika statusnya checked
                    if (el.type === 'radio') {
                        if (el.checked) {
                            formData[el.name] = el.value;
                        }
                    } else {
                        formData[el.name] = el.value;
                    }
                }
            });

            localStorage.setItem(DRAFT_KEY, JSON.stringify(formData));
        }

        // 2. Fungsi Load Draft dari LocalStorage
        function loadDraft() {
            if (!form) return;
            const savedData = localStorage.getItem(DRAFT_KEY);
            if (!savedData) return;

            try {
                const formData = JSON.parse(savedData);
                let hasContent = false;

                Object.keys(formData).forEach(name => {
                    const inputs = form.querySelectorAll(`[name="${name}"]`);
                    if (inputs.length > 0 && formData[name] !== undefined) {
                        if (inputs[0].type === 'radio') {
                            // Cari radio button yang value-nya sama lalu centang
                            inputs.forEach(radio => {
                                radio.checked = (radio.value === formData[name]);
                            });
                        } else {
                            inputs[0].value = formData[name];
                        }
                        hasContent = true;
                    }
                });

                if (hasContent) {
                    if (draftNotice) draftNotice.style.display = 'flex';
                    updatePreview();
                }
            } catch (e) {
                console.error('Gagal membaca draft:', e);
            }
        }

        if (form) {
            // 3. Listener perubahan untuk Autosave
            form.addEventListener('input', saveDraft);
            form.addEventListener('change', saveDraft);

            // 4. Hapus Draft saat Form Berhasil Disubmit
            form.addEventListener('submit', function () {
                localStorage.removeItem(DRAFT_KEY);
            });
        }

        // 5. Fitur Hapus Draft Manual
        if (clearDraftBtn) {
            clearDraftBtn.addEventListener('click', function () {
                localStorage.removeItem(DRAFT_KEY);
                if (form) form.reset();
                if (draftNotice) draftNotice.style.display = 'none';
                updatePreview();
            });
        }

        // Load draft saat pertama kali dibuka
        loadDraft();
        updatePreview();
    });

        document.addEventListener('DOMContentLoaded', () => {
        const statusConfig = {
            'akan_datang': {
                label: 'Akan Datang',
                classes: 'bg-white/90 text-blue-700'
            },
            'berlangsung': {
                label: 'Sedang Berlangsung',
                classes: 'bg-white/90 text-emerald-700'
            },
            'selesai': {
                label: 'Telah Berakhir',
                classes: 'bg-white/90 text-orange-700'
            }
        };

        const previewBadge = document.getElementById('previewStatusBadge');

        function updatePreviewBadge() {
            const checkedRadio = document.querySelector('input[name="event_status"]:checked');
            
            if (checkedRadio && previewBadge) {
                const selectedStatus = checkedRadio.value;
                const config = statusConfig[selectedStatus];

                if (config) {
                    previewBadge.textContent = config.label;
                    previewBadge.className = `absolute top-3 left-3 text-[10px] font-bold px-2.5 py-1 rounded-full ${config.classes}`;
                }
            }
        }

        // Tangkap perubahan klik pada SEMUA radio button event_status
        document.querySelectorAll('input[name="event_status"]').forEach(radio => {
            radio.addEventListener('change', updatePreviewBadge);
        });

        // Jalankan sekali saat pertama kali halaman dimuat
        updatePreviewBadge();
    });
    </script>

    {{-- SIDEBAR UTAMA --}}
    @include('admin.partials.sidebar', [
        'active' => 'events-create',
        'event' => $event ?? null,
        'menungguReview' => $menungguReview ?? 0
    ])
</body>
</html>