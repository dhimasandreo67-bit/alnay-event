@php
    $event = $event ?? null;
    $menungguReview = $menungguReview ?? 0;
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Buat Event Baru | ALNAY.ID</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin-sidebar.css') }}">

    @vite(['resources/css/app.css'])
</head>

<body class="bg-cream text-ink font-sans text-[13px]">
    <div class="flex min-h-screen w-full">

        <main class="flex-1 min-w-0 ml-[235px]">

            {{-- HEADER --}}
            <header class="flex items-center justify-between px-6 py-4 border-b border-[#e5e1db] bg-white">
                <div class="flex items-center gap-3">
                    <button type="button" onclick="window.history.back()"
                        class="p-2 text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </button>
                    <div class="w-9 h-9 rounded-lg bg-[#f3eee3] text-[#37322b] grid place-items-center font-bold">A
                    </div>
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
                <div class="mx-6 mt-4 p-4 bg-rose-50 border border-rose-200 rounded-lg">
                    <p class="text-sm font-semibold text-rose-700 mb-2">Ada isian yang belum sesuai:</p>
                    <ul class="list-disc list-inside text-sm text-rose-600 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="formEvent" method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data"
                class="grid grid-cols-1 md:grid-cols-[minmax(0,1fr)_340px] gap-5 p-6 items-start">
                @csrf

                <div class="space-y-5 min-w-0">

                    {{-- ===== BANNER & INFORMASI UTAMA ===== --}}
                    <div class="bg-white border border-[#e3ded6] rounded-xl p-6 shadow-sm">
                        <h2 class="font-bold text-[16px] mb-1">Banner & Informasi Utama</h2>
                        <p class="text-[11px] text-[#73716c] mb-4">Gambar dan deskripsi ini yang akan dilihat pertama
                            kali oleh calon peserta.</p>

                        <label class="block text-[12px] font-bold mb-2">Gambar Banner Event</label>
                        <label id="dropZone"
                            class="flex flex-col items-center justify-center gap-2 border-2 border-dashed border-[#d5cbbd] rounded-xl py-8 cursor-pointer hover:border-gold bg-[#fcfbf9] mb-5 overflow-hidden">
                            <input type="file" name="banner" id="bannerInput" accept="image/*" class="hidden">
                            <input type="hidden" name="banner_base64" id="bannerBase64" value="{{ old('banner_base64') }}">
                            <div id="dropZoneEmpty" class="flex flex-col items-center gap-2">
                                <span class="text-[12px]">Tarik & lepas gambar di sini, atau <span
                                        class="text-gold underline">jelajahi file</span></span>
                                <span class="text-[10px] text-[#98958f]">Rasio 16:9 disarankan • JPG, PNG, atau WEBP
                                    hingga 5 MB</span>
                            </div>
                            <img id="bannerPreviewImg" class="hidden w-full h-40 object-cover rounded-lg"
                                alt="">
                        </label>
                        @error('banner')
                            <div class="text-red-600 text-[10px] -mt-3 mb-4">{{ $message }}</div>
                        @enderror

                        <label class="block text-[12px] font-bold mb-2">Nama Event</label>
                        <input type="text" name="nama" id="inputNama" value="{{ old('nama') }}"
                            class="w-full border border-[#e5e1db] rounded-lg px-3 py-2.5 text-[12px] mb-4"
                            placeholder="Contoh: Pendaftaran Putra Putri ALNAY.ID 2026">
                        @error('nama')
                            <div class="text-red-600 text-[10px] -mt-3 mb-4">{{ $message }}</div>
                        @enderror

                        <label class="block text-[12px] font-bold mb-2">Deskripsi Event</label>
                        <textarea name="deskripsi" id="inputDeskripsi" rows="3"
                            class="w-full border border-[#e5e1db] rounded-lg px-3 py-2.5 text-[12px] mb-4"
                            placeholder="Jelaskan mengenai detail event ini...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="text-red-600 text-[10px] -mt-3 mb-4">{{ $message }}</div>
                        @enderror

                        <label class="block text-[12px] font-bold mb-2">Syarat & Ketentuan</label>
                        <textarea name="syarat_ketentuan" id="inputSyaratKetentuan" rows="4"
                            class="w-full border border-[#e5e1db] rounded-lg px-3 py-2.5 text-[12px] mb-4"
                            placeholder="Masukkan syarat dan ketentuan event (pisahkan tiap baris dengan enter)...">{{ old('syarat_ketentuan') }}</textarea>
                        @error('syarat_ketentuan')
                            <div class="text-red-600 text-[10px] -mt-3 mb-4">{{ $message }}</div>
                        @enderror

                        {{-- Status Event --}}
                        <div class="bg-white rounded-xl border border-[#e5e1db] p-5">
                            <h3 class="font-bold text-slate-800 mb-3">Status Event</h3>
                            <div class="grid grid-cols-3 gap-3">
                                <label
                                    class="status-option cursor-pointer border-2 rounded-lg p-3 flex items-center justify-center gap-2 transition-all hover:shadow-md border-[#e5e1db]"
                                    data-color="blue" onclick="selectStatus(this, 'akan_datang')">
                                    <input type="radio" name="status" value="akan_datang"
                                        {{ old('status', 'akan_datang') === 'akan_datang' ? 'checked' : '' }}
                                        class="sr-only">
                                    <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                                    <span class="text-sm font-medium">Akan Datang</span>
                                </label>
                                <label
                                    class="status-option cursor-pointer border-2 rounded-lg p-3 flex items-center justify-center gap-2 transition-all hover:shadow-md border-[#e5e1db]"
                                    data-color="emerald" onclick="selectStatus(this, 'sedang_berlangsung')">
                                    <input type="radio" name="status" value="sedang_berlangsung"
                                        {{ old('status') === 'sedang_berlangsung' ? 'checked' : '' }} class="sr-only">
                                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                                    <span class="text-sm font-medium">Sedang Berlangsung</span>
                                </label>
                                <label
                                    class="status-option cursor-pointer border-2 rounded-lg p-3 flex items-center justify-center gap-2 transition-all hover:shadow-md border-[#e5e1db]"
                                    data-color="orange" onclick="selectStatus(this, 'telah_berakhir')">
                                    <input type="radio" name="status" value="telah_berakhir"
                                        {{ old('status') === 'telah_berakhir' ? 'checked' : '' }} class="sr-only">
                                    <span class="w-3 h-3 rounded-full bg-orange-500"></span>
                                    <span class="text-sm font-medium">Telah Berakhir</span>
                                </label>
                            </div>
                            @error('status')
                                <p class="text-xs text-rose-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- ===== ALUR EVENT ===== --}}
                    <div class="bg-white border border-[#e3ded6] rounded-xl p-6 shadow-sm">
                        <div class="mb-4 pb-3 border-b border-[#e5e1db]">
                            <h2 class="font-bold text-slate-800">Alur Event</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Centang tahapan yang dilalui peserta — ringkasan di bawah update otomatis</p>
                        </div>

                        {{-- SAKLAR ALUR --}}
                        <div class="space-y-3">
                            <p class="text-[11px] font-bold text-[#73716c] uppercase mb-2">Saklar Alur</p>

                            {{-- Pendaftaran Publik --}}
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="hidden" name="pendaftaran_aktif" value="0">
                                <input type="checkbox" name="pendaftaran_aktif" value="1" id="toggle-pendaftaran"
                                    {{ old('pendaftaran_aktif', true) ? 'checked' : '' }}
                                    class="mt-0.5 w-4 h-4 text-[#C28E27] border-slate-300 rounded focus:ring-[#C28E27]">
                                <span>
                                    <strong class="block text-[12px] text-slate-800">Pendaftaran Publik</strong>
                                    <span class="text-[11px] text-[#73716c]">Publik bisa daftar sendiri lewat form. Kalau dimatikan, admin yang input peserta manual.</span>
                                </span>
                            </label>

                            {{-- Butuh Audisi --}}
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="hidden" name="butuh_audisi" value="0">
                                <input type="checkbox" name="butuh_audisi" value="1" id="toggle-audisi"
                                    {{ old('butuh_audisi', true) ? 'checked' : '' }}
                                    class="mt-0.5 w-4 h-4 text-[#C28E27] border-slate-300 rounded focus:ring-[#C28E27]">
                                <span>
                                    <strong class="block text-[12px] text-slate-800">Butuh Audisi</strong>
                                    <span class="text-[11px] text-[#73716c]">Peserta yang sudah bayar harus diseleksi admin (Terima/Tolak)</span>
                                </span>
                            </label>

                            {{-- Butuh Registrasi --}}
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="hidden" name="butuh_registrasi" value="0">
                                <input type="checkbox" name="butuh_registrasi" value="1" id="toggle-registrasi"
                                    {{ old('butuh_registrasi', true) ? 'checked' : '' }}
                                    class="mt-0.5 w-4 h-4 text-[#C28E27] border-slate-300 rounded focus:ring-[#C28E27]">
                                <span>
                                    <strong class="block text-[12px] text-slate-800">Butuh Registrasi</strong>
                                    <span class="text-[11px] text-[#73716c]">Peserta harus bayar biaya registrasi setelah lolos audisi</span>
                                </span>
                            </label>
                        </div>

                        {{-- DETAIL AUDISI — muncul kalau Butuh Audisi checked --}}
                        <div id="detail-audisi" class="mt-4 pt-4 border-t border-[#e5e1db] hidden">
                            <p class="text-[11px] font-bold text-[#73716c] uppercase mb-3">Detail Audisi</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-bold mb-1.5 text-[12px]">Tanggal Audisi <span class="text-[#98958f] font-normal">(Opsional)</span></label>
                                    <input type="date" name="tanggal_audisi"
                                        value="{{ old('tanggal_audisi') }}"
                                        class="w-full border border-[#e5e1db] rounded-md p-2.5 bg-[#fcfbf9] focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 transition">
                                    <p class="text-[11px] text-slate-500 mt-1">Biarkan kosong jika belum ditentukan.</p>
                                </div>
                                <div>
                                    <label class="block font-bold mb-1.5 text-[12px]">Lokasi Audisi <span class="text-[#98958f] font-normal">(Opsional)</span></label>
                                    <input type="text" name="audisi_lokasi"
                                        value="{{ old('audisi_lokasi') }}"
                                        placeholder="Contoh: Gedung Serbaguna"
                                        class="w-full border border-[#e5e1db] rounded-md p-2.5 bg-[#fcfbf9] focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 transition">
                                </div>
                            </div>
                        </div>

                        {{-- RINGKASAN --}}
                        <div class="mt-4 pt-4 border-t border-[#e5e1db]">
                            <p class="text-[11px] font-bold text-[#73716c] uppercase mb-2">Ringkasan Alur</p>
                            <div id="alur-ringkasan" class="text-[12px] text-slate-700 bg-[#faf8f2] rounded-lg px-3 py-2.5 border border-[#e5e1db]">
                                Daftar → Bayar → Audisi → Registrasi → Tampil → Vote
                            </div>
                        </div>
                    </div>

                    {{-- ===== REGISTRASI ===== --}}
                    <div id="field-registrasi" class="bg-white border border-[#e3ded6] rounded-xl p-6 shadow-sm">
                        <div class="mb-4 pb-3 border-b border-[#e5e1db]">
                            <h2 class="font-bold text-slate-800">Registrasi</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Biaya registrasi peserta (bisa lunas atau cicil)</p>
                        </div>

                        {{-- Toggle Registrasi Aktif --}}
                        <input type="hidden" name="registrasi_aktif" value="0">
                        <div class="mb-4 flex items-center justify-between rounded-lg border border-[#e7e2da] bg-[#faf8f4] px-4 py-3">
                            <div>
                                <p class="text-xs font-semibold text-gray-700">Registrasi Aktif</p>
                                <p class="mt-0.5 text-[11px] text-[#8c8982]">Matikan jika event ini tidak memerlukan pembayaran registrasi.</p>
                            </div>
                            <label class="relative inline-flex cursor-pointer items-center">
                                <input type="checkbox" name="registrasi_aktif" value="1" class="peer sr-only"
                                       @checked(old('registrasi_aktif', true))
                                       onchange="document.getElementById('areaHargaRegistrasi').classList.toggle('hidden', !this.checked)">
                                <div class="h-6 w-11 rounded-full bg-gray-300 after:absolute after:left-[2px] after:top-0.5 after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-[#C28E27] peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:ring-4 peer-focus:ring-[#C28E27]/20"></div>
                            </label>
                        </div>

                        <div id="areaHargaRegistrasi"
                             class="grid grid-cols-1 md:grid-cols-2 gap-4 {{ old('registrasi_aktif', true) ? '' : 'hidden' }}">
                            <div>
                                <label class="block font-bold mb-1.5">Harga Registrasi (Lunas)</label>
                                <div class="relative">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-sm font-semibold text-slate-500">Rp</span>
                                    <input type="text" name="harga_registrasi" id="harga_registrasi"
                                        value="{{ old('harga_registrasi') }}" inputmode="numeric" autocomplete="off"
                                        class="w-full border border-[#e5e1db] rounded-md p-2.5 pl-10 bg-[#fcfbf9] focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 transition"
                                        placeholder="0">
                                </div>
                                <p class="text-[11px] text-slate-500 mt-1.5">Biaya registrasi penuh jika peserta
                                    memilih bayar lunas.</p>
                            </div>

                            <div>
                                <label class="block font-bold mb-1.5">Harga Registrasi (DP)</label>
                                <div class="relative">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-sm font-semibold text-slate-500">Rp</span>
                                    <input type="text" name="harga_registrasi_dp" id="harga_registrasi_dp"
                                        value="{{ old('harga_registrasi_dp') }}" inputmode="numeric"
                                        autocomplete="off"
                                        class="w-full border border-[#e5e1db] rounded-md p-2.5 pl-10 bg-[#fcfbf9] focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 transition"
                                        placeholder="0">
                                </div>
                                <p class="text-[11px] text-slate-500 mt-1.5">Biaya DP gelombang pertama. Pelunasan
                                    (DP2) = harga lunas − harga DP.</p>
                            </div>
                        </div>
                    </div>

                    {{-- ===== JADWAL & LOKASI ===== --}}
                    <div class="bg-white border border-[#e3ded6] rounded-xl p-6 shadow-sm">
                        <h2 class="font-bold text-[16px] mb-1">Jadwal & Lokasi Event</h2>
                        <p class="text-[11px] text-[#73716c] mb-4">Atur waktu dan tempat pelaksanaan event. Tanggal &
                            waktu selesai bersifat opsional.</p>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-[12px] font-bold mb-2">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" id="inputTanggalMulai"
                                    value="{{ old('tanggal_mulai') }}"
                                    class="w-full border border-[#e5e1db] rounded-lg px-3 py-2.5 text-[12px]">
                                @error('tanggal_mulai')
                                    <div class="text-red-600 text-[10px] mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-[12px] font-bold mb-2">Waktu Mulai</label>
                                <input type="time" name="waktu_mulai" id="inputWaktuMulai"
                                    value="{{ old('waktu_mulai') }}"
                                    class="w-full border border-[#e5e1db] rounded-lg px-3 py-2.5 text-[12px]">
                                @error('waktu_mulai')
                                    <div class="text-red-600 text-[10px] mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-[12px] font-bold mb-2">Tanggal Selesai <span
                                        class="text-[#98958f] font-normal">(Opsional)</span></label>
                                <input type="date" name="tanggal_selesai" id="inputTanggalSelesai"
                                    value="{{ old('tanggal_selesai') }}"
                                    class="w-full border border-[#e5e1db] rounded-lg px-3 py-2.5 text-[12px]">
                                @error('tanggal_selesai')
                                    <div class="text-red-600 text-[10px] mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-[12px] font-bold mb-2">Waktu Selesai <span
                                        class="text-[#98958f] font-normal">(Opsional)</span></label>
                                <input type="time" name="waktu_selesai" id="inputWaktuSelesai"
                                    value="{{ old('waktu_selesai') }}"
                                    class="w-full border border-[#e5e1db] rounded-lg px-3 py-2.5 text-[12px]">
                                @error('waktu_selesai')
                                    <div class="text-red-600 text-[10px] mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <label class="block text-[12px] font-bold mb-2">Lokasi</label>
                        <input type="text" name="lokasi" id="inputLokasi" value="{{ old('lokasi') }}"
                            class="w-full border border-[#e5e1db] rounded-lg px-3 py-2.5 text-[12px]"
                            placeholder="Contoh: Citra II, Universitas Amikom Yogyakarta">
                        @error('lokasi')
                            <div class="text-red-600 text-[10px] mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- ===== BIAYA & VOTING ===== --}}
                    <div class="bg-white border border-[#e3ded6] rounded-xl p-6 shadow-sm">
                        <h2 class="font-bold text-[16px] mb-1">Biaya & Voting</h2>
                        <p class="text-[11px] text-[#73716c] mb-4">Pengaturan tarif pendaftaran dan sistem harga vote
                            event.</p>

                        <div class="grid grid-cols-2 gap-4">
                            <div id="field-biaya-pendaftaran">
                                <label class="block text-[12px] font-bold mb-2">Biaya Pendaftaran</label>
                                <div class="relative">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-[11px] text-[#98958f] font-bold">Rp</span>
                                    <input type="text" name="biaya_pendaftaran" id="inputBiayaPendaftaran"
                                        value="{{ old('biaya_pendaftaran') }}" inputmode="numeric"
                                        autocomplete="off"
                                        class="w-full border border-[#e5e1db] rounded-lg pl-9 pr-3 py-2.5 text-[12px]"
                                        placeholder="0">
                                </div>
                                <p class="text-[10px] text-[#98958f] mt-1">Isi 0 jika pendaftaran gratis.</p>
                                @error('biaya_pendaftaran')
                                    <div class="text-red-600 text-[10px] mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-[12px] font-bold mb-2">Harga Per Vote</label>
                                <div class="relative">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-[11px] text-[#98958f] font-bold">Rp</span>
                                    <input type="text" name="harga_per_vote" id="inputHargaPerVote"
                                        value="{{ old('harga_per_vote') }}" inputmode="numeric" autocomplete="off"
                                        class="w-full border border-[#e5e1db] rounded-lg pl-9 pr-3 py-2.5 text-[12px]"
                                        placeholder="0">
                                </div>
                                <p class="text-[10px] text-[#98958f] mt-1">Tarif nominal yang ditagih per 1 kali vote.
                                </p>
                                @error('harga_per_vote')
                                    <div class="text-red-600 text-[10px] mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- ===== PENDAFTARAN ===== --}}
                    <div class="bg-white rounded-xl border border-[#e5e1db] p-6">
                        <div class="mb-5 pb-3 border-b border-[#e5e1db]">
                            <h3 class="font-bold text-slate-800">Pendaftaran</h3>
                            <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                                <strong class="text-slate-700">Opsional</strong> — isi tanggal jika ingin buka/tutup
                                otomatis. Kosongkan jika atur manual dari halaman Kelola Event.
                            </p>
                        </div>

                        {{-- Baris 1: Pembukaan --}}
                        <div class="mb-4">
                            <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400 mb-2">Pembukaan
                                Pendaftaran</p>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label
                                        class="block text-[11px] font-semibold text-slate-600 mb-1.5">Tanggal</label>
                                    <input type="date" name="pembukaan_pendaftaran_tanggal"
                                        value="{{ old('pembukaan_pendaftaran_tanggal') }}"
                                        class="w-full border border-[#e5e1db] rounded-md px-3 py-2.5 bg-[#fcfbf9] text-[12px] focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 transition">
                                    @error('pembukaan_pendaftaran_tanggal')
                                        <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1.5">Waktu</label>
                                    <input type="time" name="pembukaan_pendaftaran_waktu"
                                        value="{{ old('pembukaan_pendaftaran_waktu') }}"
                                        class="w-full border border-[#e5e1db] rounded-md px-3 py-2.5 bg-[#fcfbf9] text-[12px] focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 transition">
                                    @error('pembukaan_pendaftaran_waktu')
                                        <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Baris 2: Batas --}}
                        <div class="mb-4">
                            <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400 mb-2">Batas
                                Pendaftaran</p>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label
                                        class="block text-[11px] font-semibold text-slate-600 mb-1.5">Tanggal</label>
                                    <input type="date" name="batas_pendaftaran_tanggal" id="inputBatasTanggal"
                                        value="{{ old('batas_pendaftaran_tanggal') }}"
                                        class="w-full border border-[#e5e1db] rounded-md px-3 py-2.5 bg-[#fcfbf9] text-[12px] focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 transition">
                                    @error('batas_pendaftaran_tanggal')
                                        <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1.5">Waktu</label>
                                    <input type="time" name="batas_pendaftaran_waktu" id="inputBatasWaktu"
                                        value="{{ old('batas_pendaftaran_waktu') }}"
                                        class="w-full border border-[#e5e1db] rounded-md px-3 py-2.5 bg-[#fcfbf9] text-[12px] focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 transition">
                                    @error('batas_pendaftaran_waktu')
                                        <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Baris 3: Kuota --}}
                        <div>
                            <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400 mb-2">Kuota</p>
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1.5">Kuota
                                    Peserta</label>
                                <div class="relative">
                                    <input type="number" name="kuota_peserta" id="inputKuota"
                                        value="{{ old('kuota_peserta') }}" min="1" placeholder="Contoh: 100"
                                        class="w-full border border-[#e5e1db] rounded-md px-3 py-2.5 bg-[#fcfbf9] text-[12px] pr-16 focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 transition">
                                    <span
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-[11px] font-semibold text-slate-500">orang</span>
                                </div>
                                @error('kuota_peserta')
                                    <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full py-3 bg-[#ba8320] hover:bg-[#a06f1a] text-white font-bold rounded-lg transition-colors inline-flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M20 6L9 17l-5-5" />
                        </svg>
                        Publikasikan Event
                    </button>

                </div>

                {{-- ===== PREVIEW ===== --}}
                <div class="bg-white rounded-xl border border-[#e5e1db] p-5 sticky top-6">
                    <h3 class="font-bold text-slate-800 mb-3">Pratinjau Halaman Event</h3>

                    <div
                        class="relative bg-gradient-to-br from-[#c99a4a] to-[#ba8320] rounded-lg overflow-hidden h-48">
                        <img id="previewBannerImg" src="" alt=""
                            class="absolute inset-0 w-full h-full object-cover z-0 hidden">
                        <span id="previewBannerEmpty"
                            class="absolute inset-0 flex items-center justify-center text-white/80 text-sm z-10">
                            Belum ada banner diunggah
                        </span>
                        <span id="previewStatusBadge"
                            class="absolute top-3 left-3 z-20 px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200">
                            Akan Datang
                        </span>
                    </div>

                    <div class="mt-4 space-y-2">
                        <h4 id="previewNama" class="font-bold text-slate-800 text-lg">Nama event akan muncul di sini
                        </h4>
                        <p id="previewDeskripsi" class="text-sm text-slate-600">Deskripsi event akan muncul di sini.
                        </p>

                        <div class="grid grid-cols-2 gap-2 mt-3">
                            <div class="bg-slate-50 rounded-lg p-3">
                                <p class="text-xs text-slate-500 font-semibold">HARI & JAM</p>
                                <p id="previewJadwal" class="text-sm font-medium text-slate-800">—</p>
                            </div>
                            <div class="bg-slate-50 rounded-lg p-3">
                                <p class="text-xs text-slate-500 font-semibold">TEMPAT</p>
                                <p id="previewLokasi" class="text-sm font-medium text-slate-800">—</p>
                            </div>
                        </div>

                        <div class="space-y-1 text-sm mt-3">
                            <div class="flex justify-between">
                                <span class="text-slate-600">Biaya Daftar</span>
                                <span id="previewBiaya" class="font-medium">Gratis</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Harga / Vote</span>
                                <span id="previewHargaVote" class="font-medium">—</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Batas Pendaftaran</span>
                                <span id="previewBatas" class="font-medium">Manual oleh Admin</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Kuota Peserta</span>
                                <span id="previewKuota" class="font-medium">—</span>
                            </div>
                        </div>

                        {{-- RINGKASAN ALUR DI PREVIEW --}}
                        <div class="mt-3 pt-3 border-t border-slate-200">
                            <p class="text-xs text-slate-500 font-semibold mb-1">ALUR EVENT</p>
                            <p id="previewAlur" class="text-sm font-medium text-slate-800">—</p>
                        </div>
                    </div>
                </div>
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

        // ===== ALUR EVENT — Show/Hide & Ringkasan =====
        function updateAlur() {
            const tP = document.getElementById('toggle-pendaftaran')?.checked;
            const tA = document.getElementById('toggle-audisi')?.checked;
            const tR = document.getElementById('toggle-registrasi')?.checked;
            const el = document.getElementById('alur-ringkasan');
            const previewAlur = document.getElementById('previewAlur');

            // Show/hide Detail Audisi
            const detailAudisi = document.getElementById('detail-audisi');
            if (detailAudisi) {
                detailAudisi.classList.toggle('hidden', !tA);
            }

            // Show/hide field Biaya Pendaftaran
            const fieldBiaya = document.getElementById('field-biaya-pendaftaran');
            if (fieldBiaya) {
                fieldBiaya.classList.toggle('hidden', !tP);
            }

            // Show/hide field Registrasi
            const fieldReg = document.getElementById('field-registrasi');
            if (fieldReg) {
                fieldReg.classList.toggle('hidden', !tR);
            }

            // Ringkasan
            let steps = [];
            if (tP) {
                steps.push('Daftar', 'Bayar');
            } else {
                steps.push('Input Manual Admin');
            }
            if (tA) steps.push('Audisi');
            if (tR) steps.push('Registrasi');
            steps.push('Tampil', 'Vote');

            const text = steps.join(' → ');
            if (el) el.textContent = text;
            if (previewAlur) previewAlur.textContent = text;
        }

        document.querySelectorAll('#toggle-pendaftaran, #toggle-audisi, #toggle-registrasi').forEach(el => {
            el.addEventListener('change', updateAlur);
        });

        function updatePreview() {
            if ($('previewNama') && inputNama) $('previewNama').textContent = inputNama.value.trim() ||
                'Nama event akan muncul di sini';
            if ($('previewDeskripsi') && inputDeskripsi) $('previewDeskripsi').textContent = inputDeskripsi.value.trim() ||
                'Deskripsi event akan muncul di sini.';
            if ($('previewLokasi') && inputLokasi) $('previewLokasi').textContent = inputLokasi.value.trim() || '—';

            if ($('previewBiaya') && inputBiayaPendaftaran) $('previewBiaya').textContent = formatRupiah(
                inputBiayaPendaftaran.value);
            if ($('previewHargaVote') && inputHargaPerVote) $('previewHargaVote').textContent = inputHargaPerVote.value >
                0 ? formatRupiah(inputHargaPerVote.value) : '—';

            if ($('previewJadwal') && inputTanggalMulai) {
                const tglMulai = formatTanggalIndo(inputTanggalMulai.value);
                const jamMulai = inputWaktuMulai?.value;
                const jamSelesai = inputWaktuSelesai?.value;

                if (tglMulai) {
                    let jadwal = tglMulai;
                    if (jamMulai) jadwal += `\n${jamMulai}` + (jamSelesai ? ` - ${jamSelesai} WIB` : ' WIB');
                    $('previewJadwal').innerText = jadwal;
                } else {
                    $('previewJadwal').textContent = '—';
                }
            }

            if ($('previewBatas') && inputBatasTanggal) {
                const batasTgl = formatTanggalIndo(inputBatasTanggal.value);
                if (batasTgl) {
                    $('previewBatas').textContent =
                        `${batasTgl}${inputBatasWaktu?.value ? ', ' + inputBatasWaktu.value + ' WIB' : ''}`;
                } else {
                    $('previewBatas').textContent = 'Manual oleh Admin';
                }
            }

            if ($('previewKuota') && inputKuota) $('previewKuota').textContent = inputKuota.value ?
                `${inputKuota.value} orang` : '—';
        }

        [inputNama, inputDeskripsi, inputSyaratKetentuan, inputBiayaPendaftaran, inputHargaPerVote,
            inputTanggalMulai, inputWaktuMulai, inputTanggalSelesai, inputWaktuSelesai,
            inputLokasi, inputBatasTanggal, inputBatasWaktu, inputKuota
        ].forEach(el => el && el.addEventListener('input', updatePreview));

        // Banner preview + simpan base64
        if (bannerInput) {
            bannerInput.addEventListener('change', function() {
                const file = this.files[0];
                if (!file) return;

                if (file.size > 5 * 1024 * 1024) {
                    alert('File terlalu besar. Maksimal 5MB.');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    if ($('dropZoneEmpty')) $('dropZoneEmpty').classList.add('hidden');
                    const previewImg = $('bannerPreviewImg');
                    if (previewImg) {
                        previewImg.src = e.target.result;
                        previewImg.classList.remove('hidden');
                    }

                    if ($('previewBannerEmpty')) $('previewBannerEmpty').classList.add('hidden');
                    const bigPreview = $('previewBannerImg');
                    if (bigPreview) {
                        bigPreview.src = e.target.result;
                        bigPreview.classList.remove('hidden');
                    }

                    const base64Input = $('bannerBase64');
                    if (base64Input) {
                        base64Input.value = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            });
        }

        // Restore banner dari base64
        document.addEventListener('DOMContentLoaded', function() {
            const base64Input = document.getElementById('bannerBase64');
            const base64Value = base64Input?.value;

            if (base64Value && base64Value.startsWith('data:image')) {
                const dropZoneEmpty = document.getElementById('dropZoneEmpty');
                if (dropZoneEmpty) dropZoneEmpty.classList.add('hidden');

                const previewImg = document.getElementById('bannerPreviewImg');
                if (previewImg) {
                    previewImg.src = base64Value;
                    previewImg.classList.remove('hidden');
                }

                const previewBannerEmpty = document.getElementById('previewBannerEmpty');
                if (previewBannerEmpty) previewBannerEmpty.classList.add('hidden');

                const bigPreview = document.getElementById('previewBannerImg');
                if (bigPreview) {
                    bigPreview.src = base64Value;
                    bigPreview.classList.remove('hidden');
                }
            }
        });

        // Drag & drop
        const dropZone = $('dropZone');
        if (dropZone) {
            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.classList.add('border-gold');
            });
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

        // ===== SELECT STATUS =====
        function selectStatus(element, value) {
            document.querySelectorAll('.status-option').forEach(opt => {
                opt.classList.remove(
                    'border-blue-500', 'bg-blue-50',
                    'border-emerald-500', 'bg-emerald-50',
                    'border-orange-500', 'bg-orange-50'
                );
                opt.classList.add('border-[#e5e1db]');
                opt.querySelector('input').checked = false;
            });

            const color = element.dataset.color;
            element.classList.remove('border-[#e5e1db]');
            element.classList.add(`border-${color}-500`, `bg-${color}-50`);
            element.querySelector('input').checked = true;

            updatePreviewBadge(value);
        }

        function updatePreviewBadge(status) {
            const badge = document.getElementById('previewStatusBadge');
            if (!badge) return;

            const badges = {
                'akan_datang': {
                    text: 'Akan Datang',
                    bg: 'bg-blue-100',
                    text_color: 'text-blue-700',
                    border: 'border-blue-200'
                },
                'sedang_berlangsung': {
                    text: 'Sedang Berlangsung',
                    bg: 'bg-emerald-100',
                    text_color: 'text-emerald-700',
                    border: 'border-emerald-200'
                },
                'telah_berakhir': {
                    text: 'Telah Berakhir',
                    bg: 'bg-orange-100',
                    text_color: 'text-orange-700',
                    border: 'border-orange-200'
                }
            };

            const config = badges[status] || badges['akan_datang'];
            badge.textContent = config.text;
            badge.className =
                `absolute top-3 left-3 z-20 px-3 py-1 rounded-full text-xs font-bold ${config.bg} ${config.text_color} ${config.border}`;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const checkedRadio = document.querySelector('input[name="status"]:checked');
            if (checkedRadio) {
                const label = checkedRadio.closest('.status-option');
                if (label) {
                    const color = label.dataset.color;
                    label.classList.remove('border-[#e5e1db]');
                    label.classList.add(`border-${color}-500`, `bg-${color}-50`);
                    updatePreviewBadge(checkedRadio.value);
                }
            }
            updatePreview();
            updateAlur(); // init alur event
        });

        // ===== FORMAT RUPIAH =====
        function getNumericValue(value) {
            return String(value || '').replace(/\D/g, '');
        }

        function formatRupiahInput(input) {
            const numericValue = getNumericValue(input.value);
            if (numericValue === '') {
                input.value = '';
                return;
            }
            input.value = new Intl.NumberFormat('id-ID').format(Number(numericValue));
        }

        document.addEventListener('DOMContentLoaded', function() {
            const inputBiaya = document.getElementById('inputBiayaPendaftaran');
            const inputHargaVote = document.getElementById('inputHargaPerVote');
            const inputHargaReg = document.getElementById('harga_registrasi');
            const inputHargaRegDp = document.getElementById('harga_registrasi_dp');
            const form = document.getElementById('formEvent');

            [inputBiaya, inputHargaVote, inputHargaReg, inputHargaRegDp].forEach(input => {
                if (!input) return;
                input.addEventListener('input', function() {
                    formatRupiahInput(this);
                    updatePreview();
                });
            });

            [inputBiaya, inputHargaVote, inputHargaReg, inputHargaRegDp].forEach(input => {
                if (!input || !input.value) return;
                const numericValue = getNumericValue(input.value);
                if (numericValue) {
                    input.value = new Intl.NumberFormat('id-ID').format(Number(numericValue));
                }
            });

            if (form) {
                form.addEventListener('submit', function() {
                    [inputBiaya, inputHargaVote, inputHargaReg, inputHargaRegDp].forEach(input => {
                        if (input) input.value = getNumericValue(input.value);
                    });
                });
            }
        });
    </script>

    @include('admin.partials.sidebar', [
        'active' => 'events-create',
        'event' => $event,
        'menungguReview' => $menungguReview,
    ])
</body>

</html>