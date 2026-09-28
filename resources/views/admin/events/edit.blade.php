<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event - {{ $event->nama }} | ALNAY.ID</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { gold: '#ba8320', cream: '#f7f5f0', ink: '#22201d' },
                    fontFamily: { sans: ['Inter', 'Arial', 'sans-serif'] },
                },
            },
        };
    </script>
</head>
<body class="bg-cream text-ink font-sans text-[13px]">
    <div class="flex min-h-screen w-full">

        <main class="flex-1 min-w-0 ml-[235px] p-6">
            {{-- HEADER --}}
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="font-bold text-[18px] text-ink">Edit Data Event</h1>
                    <p class="text-[11px] text-[#8a8984]">Perbarui informasi event {{ $event->nama }}</p>
                </div>
                <a href="{{ route('admin.events.index') }}" class="px-4 py-2 border border-[#e5e1db] bg-white rounded-md text-ink font-bold hover:bg-[#f7f5f0]">
                    ← Kembali
                </a>
            </div>

            {{-- FORM EDIT EVENT --}}
            <div class="bg-white border border-[#e3ded6] rounded-xl p-6 shadow-sm max-w-4xl">
                <form action="{{ route('admin.events.update', $event->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Nama Event --}}
                    <div>
                        <label class="block font-bold mb-1">Nama Event</label>
                        <input type="text" name="nama" value="{{ old('nama', $event->nama) }}" required 
                               class="w-full border border-[#e5e1db] rounded-md p-2.5 bg-[#fcfbf9] focus:outline-none focus:border-gold">
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label class="block font-bold mb-1">Deskripsi</label>
                        <textarea name="deskripsi" rows="3" required 
                                  class="w-full border border-[#e5e1db] rounded-md p-2.5 bg-[#fcfbf9] focus:outline-none focus:border-gold">{{ old('deskripsi', $event->deskripsi) }}</textarea>
                    </div>

                    {{-- Syarat & Ketentuan --}}
                    <div>
                        <label class="block font-bold mb-1">Syarat & Ketentuan</label>
                        <textarea name="syarat_ketentuan" rows="3" required 
                                  class="w-full border border-[#e5e1db] rounded-md p-2.5 bg-[#fcfbf9] focus:outline-none focus:border-gold">{{ old('syarat_ketentuan', $event->syarat_ketentuan) }}</textarea>
                    </div>

                    {{-- Banner Upload --}}
                    <div>
                        <label class="block font-bold mb-1">Banner Event (Kosongkan jika tidak diubah)</label>
                        @if($event->banner)
                            <img src="{{ Storage::url($event->banner) }}" alt="{{ $event->nama }}" class="w-12 h-12 object-cover rounded-lg">
                        @else
                            <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center text-xs text-gray-500">No Image</div>
                        @endif
                        <input type="file" name="banner" accept="image/*" 
                               class="w-full border border-[#e5e1db] rounded-md p-2 bg-[#fcfbf9]">
                    </div>

                    {{-- Grid Biaya --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold mb-1">Biaya Pendaftaran (Rp)</label>
                            <input type="number" name="biaya_pendaftaran" value="{{ old('biaya_pendaftaran', $event->biaya_pendaftaran) }}" required 
                                   class="w-full border border-[#e5e1db] rounded-md p-2.5 bg-[#fcfbf9]">
                        </div>
                        <div>
                            <label class="block font-bold mb-1">Harga Per Vote (Rp)</label>
                            <input type="number" name="harga_per_vote" value="{{ old('harga_per_vote', $event->harga_per_vote) }}" required 
                                   class="w-full border border-[#e5e1db] rounded-md p-2.5 bg-[#fcfbf9]">
                        </div>
                    </div>

                    {{-- Grid Tanggal & Waktu Mulai --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold mb-1">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', \Carbon\Carbon::parse($event->tanggal_mulai)->format('Y-m-d')) }}" required 
                                   class="w-full border border-[#e5e1db] rounded-md p-2.5 bg-[#fcfbf9]">
                        </div>
                        {{-- Waktu Mulai --}}
                        <div>
                            <label class="block font-bold mb-1">Waktu Mulai</label>
                            <input type="time" name="waktu_mulai" 
                                value="{{ old('waktu_mulai', $event->waktu_mulai ? \Carbon\Carbon::parse($event->waktu_mulai)->format('H:i') : '') }}" 
                                required 
                                class="w-full border border-[#e5e1db] rounded-md p-2.5 bg-[#fcfbf9]">
                        </div>
                    </div>

                    {{-- Lokasi & Kuota --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold mb-1">Lokasi</label>
                            <input type="text" name="lokasi" value="{{ old('lokasi', $event->lokasi) }}" required 
                                   class="w-full border border-[#e5e1db] rounded-md p-2.5 bg-[#fcfbf9]">
                        </div>
                        <div>
                            <label class="block font-bold mb-1">Kuota Peserta</label>
                            <input type="number" name="kuota_peserta" value="{{ old('kuota_peserta', $event->kuota_peserta) }}" required 
                                   class="w-full border border-[#e5e1db] rounded-md p-2.5 bg-[#fcfbf9]">
                        </div>
                    </div>

                    {{-- Batas Pendaftaran --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold mb-1">Batas Pendaftaran (Tanggal)</label>
                            <input type="date" name="batas_pendaftaran_tanggal" value="{{ old('batas_pendaftaran_tanggal', \Carbon\Carbon::parse($event->batas_pendaftaran_tanggal)->format('Y-m-d')) }}" required 
                                   class="w-full border border-[#e5e1db] rounded-md p-2.5 bg-[#fcfbf9]">
                        </div>
                        {{-- Batas Pendaftaran (Waktu) --}}
                        <div>
                            <label class="block font-bold mb-1">Batas Pendaftaran (Waktu)</label>
                            <input type="time" name="batas_pendaftaran_waktu" 
                                value="{{ old('batas_pendaftaran_waktu', $event->batas_pendaftaran_waktu ? \Carbon\Carbon::parse($event->batas_pendaftaran_waktu)->format('H:i') : '') }}" 
                                required 
                                class="w-full border border-[#e5e1db] rounded-md p-2.5 bg-[#fcfbf9]">
                        </div>
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="pt-4 border-t border-[#e5e1db] flex justify-end gap-3">
                        <a href="{{ route('admin.events.index') }}" class="px-4 py-2 border border-[#e5e1db] rounded-md text-ink font-bold hover:bg-[#f7f5f0]">Batal</a>
                        <button type="submit" class="px-6 py-2 bg-gold text-white font-bold rounded-md hover:bg-[#a3721a]">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    @include('admin.partials.sidebar', ['active' => 'events-index', 'event' => $event])
</body>
</html>