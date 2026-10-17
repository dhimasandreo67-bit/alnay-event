<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Peserta Tampil | ALNAY.ID</title>
    @vite(['resources/css/app.css'])
    @include('admin.partials.pwa-head')
</head>

<body class="bg-[#f7f6f3] text-[#24211d] font-sans">
    <div class="flex min-h-screen">
        @include('admin.partials.sidebar', ['active' => 'peserta-tampil', 'event' => $event])

        <main class="flex-1 min-w-0 ml-[235px] mt-[80px]">
            @include('admin.partials.header', ['variant' => 'dashboard', 'event' => $event])

            <div class="p-6 md:p-8 space-y-6">

                {{-- HEADER --}}
                <div class="bg-white rounded-xl border border-[#e7e2da] p-6">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                        <div class="min-w-0">
                            <h1 class="text-2xl font-bold text-[#24211d]">Kelola Peserta Tampil</h1>
                            <p class="text-sm text-[#8c8982] mt-1">
                                Atur peserta mana yang tampil di halaman publik & bisa divote.
                            </p>
                            @if ($event)
                                <p class="text-xs text-[#8c8982] mt-2">
                                    Event: <strong>{{ $event->nama }}</strong>
                                </p>
                            @endif
                        </div>

                        @if ($event)
                            <div class="flex items-center gap-2 flex-wrap shrink-0">

                                <form action="{{ route('admin.peserta-tampil.toggle-semua', $event->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="tampil" value="1">
                                    <button type="submit"
                                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-lg transition whitespace-nowrap">
                                        Tampilkan Semua
                                    </button>
                                </form>
                                <form action="{{ route('admin.peserta-tampil.toggle-semua', $event->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="tampil" value="0">
                                    <button type="submit"
                                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-rose-500 hover:bg-rose-600 text-white font-semibold text-sm rounded-lg transition whitespace-nowrap">
                                        Sembunyikan Semua
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- FLASH --}}
                @if (session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- LIST PESERTA --}}
                @if ($event)
                    <div class="bg-white rounded-xl border border-[#e7e2da] overflow-hidden">
                        <div class="p-4 border-b border-[#e7e2da]">
                            <h3 class="text-sm font-semibold text-[#24211d]">
                                Daftar Peserta ({{ $pesertas->count() }})
                            </h3>
                        </div>

                        @if ($pesertas->count() > 0)
                            <div class="divide-y divide-[#e7e2da]">
                                @foreach ($pesertas as $p)
                                    @php
                                        $fotoUrl = $p->user?->foto_custom
                                            ? asset('storage/' . $p->user->foto_custom)
                                            : ($p->user?->foto_url ?? null);
                                    @endphp

                                    {{-- ✅ Baris peserta (dibungkus dengan flex yang benar) --}}
                                    <div class="p-4 flex items-start gap-3">

                                        {{-- Avatar --}}
                                        <div class="w-12 h-12 rounded-full overflow-hidden bg-[#C28E27]/10 text-[#C28E27] flex items-center justify-center font-bold text-sm shrink-0 border border-[#e7e2da] {{ $fotoUrl ? 'cursor-pointer ring-offset-1 hover:ring-2 hover:ring-[#C28E27]/50 transition' : '' }}"
                                            id="avatar-{{ $p->id }}"
                                            @if ($fotoUrl)
                                                onclick="openImageModal({{ json_encode($fotoUrl) }})"
                                                title="Lihat gambar penuh"
                                            @endif>
                                            @if ($fotoUrl)
                                                <img src="{{ $fotoUrl }}" alt="{{ $p->namaTampil() }}"
                                                    class="w-full h-full object-cover pointer-events-none"
                                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <span class="hidden w-full h-full items-center justify-center">{{ strtoupper(substr($p->namaTampil(), 0, 1)) }}</span>
                                            @else
                                                <span>{{ strtoupper(substr($p->namaTampil(), 0, 1)) }}</span>
                                            @endif
                                        </div>

                                        {{-- Info --}}
                                        <div class="min-w-0 flex-1">
                                            <p class="font-semibold text-[#24211d] truncate">{{ $p->namaTampil() }}</p>
                                            <p class="text-xs text-[#8c8982] truncate">{{ $p->emailTampil() }}</p>
                                            <p class="text-xs text-[#8c8982] mt-0.5 flex items-center gap-1.5 flex-wrap">
                                                <span>Pembayaran:
                                                    <span class="font-semibold
                                                        {{ $p->status_bayar_tampil === 'lunas' ? 'text-emerald-600' : ($p->status_bayar_tampil === 'dp' ? 'text-amber-600' : 'text-rose-500') }}">
                                                        {{ $p->status_bayar_tampil === 'dp' ? 'DP' : ucfirst($p->status_bayar_tampil ?? 'belum') }}
                                                    </span>
                                                </span>
                                                @if ($p->kandidat_voting)
                                                    <span class="px-1.5 py-0.5 rounded bg-indigo-50 text-indigo-600 text-[10px] font-bold">KANDIDAT VOTING</span>
                                                @endif
                                            </p>
                                        </div>

                                        {{-- Aksi --}}
                                        <div class="flex items-center gap-2 shrink-0 flex-wrap justify-end">
                                            <button type="button"
                                                onclick="openModalGambar({{ $p->id }}, {{ json_encode($p->namaTampil()) }}, {{ json_encode($fotoUrl) }})"
                                                class="px-3 py-2 text-xs font-bold rounded-lg transition border border-[#e7e2da] bg-white text-[#24211d] hover:bg-[#faf8f4] hover:border-[#C28E27] whitespace-nowrap">
                                                {{ $fotoUrl ? 'Ubah Gambar' : 'Tambah Gambar' }}
                                            </button>

                                            <form action="{{ route('admin.peserta-tampil.toggle', $p->id) }}"
                                                method="POST" class="toggle-tampil-form">
                                                @csrf
                                                <button type="submit"
                                                    class="toggle-tampil-btn px-4 py-2 text-xs font-bold rounded-lg transition
                                                        {{ $p->tampil_di_publik
                                                            ? 'bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200'
                                                            : 'bg-gray-100 text-gray-600 hover:bg-gray-200 border border-gray-200' }}">
                                                    {{ $p->tampil_di_publik ? 'Tampil di Publik' : 'Disembunyikan' }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-12 text-center text-[#8c8982]">
                                <p class="text-sm">Belum ada peserta diterima di event ini.</p>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="bg-white rounded-xl border border-[#e7e2da] p-8 text-center text-[#8c8982]">
                        Pilih event dulu di halaman lain.
                    </div>
                @endif

            </div>
        </main>
    </div>

    {{-- =====================================================
         MODAL TAMBAH PESERTA
    ====================================================== --}}
    <div id="modalTambah" class="hidden fixed inset-0 z-[999] bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-[#e7e2da] relative">
            <div class="flex items-center justify-between pb-4 border-b border-[#e7e2da] mb-4">
                <div>
                    <h3 class="text-base font-bold text-[#24211d]">Tambah Peserta Manual</h3>
                    <p class="text-[11px] text-[#8c8982] mt-0.5">Peserta akan langsung tampil &amp; bisa divote</p>
                </div>
                <button type="button" onclick="closeModalTambah()"
                    class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="formTambahPeserta" onsubmit="submitTambah(event)">
                @csrf

                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-bold mb-1.5 text-[#24211d]">
                            Nama <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" required maxlength="100"
                            placeholder="Contoh: John Doe"
                            class="w-full border border-[#e5e1db] rounded-md p-2.5 bg-[#fcfbf9] text-sm focus:outline-none focus:border-[#C28E27] focus:ring-2 focus:ring-[#C28E27]/20 transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold mb-1.5 text-[#24211d]">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" required maxlength="255"
                            placeholder="Contoh: johndoe@example.com"
                            class="w-full border border-[#e5e1db] rounded-md p-2.5 bg-[#fcfbf9] text-sm focus:outline-none focus:border-[#C28E27] focus:ring-2 focus:ring-[#C28E27]/20 transition">
                    </div>
                </div>

                <div id="modalError" class="hidden mt-3 rounded-lg bg-rose-50 border border-rose-200 px-3 py-2 text-xs text-rose-700"></div>

                <div class="flex gap-2 mt-5">
                    <button type="button" onclick="closeModalTambah()"
                        class="flex-1 px-4 py-2.5 border border-[#e5e1db] bg-white rounded-md text-[#24211d] font-bold text-sm hover:bg-[#f7f5f0] transition">
                        Batal
                    </button>
                    <button type="submit" id="btnSubmitTambah"
                        class="flex-1 px-4 py-2.5 bg-[#C28E27] hover:bg-[#a3721f] text-white font-bold text-sm rounded-md transition">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- =====================================================
         ✅ MODAL GAMBAR (sebelumnya HILANG — sekarang ditambahkan)
    ====================================================== --}}
    <div id="modalGambar" class="hidden fixed inset-0 z-[999] bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-[#e7e2da] relative">
            <div class="flex items-center justify-between pb-4 border-b border-[#e7e2da] mb-4">
                <div>
                    <h3 class="text-base font-bold text-[#24211d]" id="modalGambarTitle">Ubah Gambar</h3>
                    <p class="text-[11px] text-[#8c8982] mt-0.5">
                        Peserta: <span id="modalGambarNama" class="font-semibold">-</span>
                    </p>
                </div>
                <button type="button" onclick="closeModalGambar()"
                    class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="formGambar" onsubmit="submitGambar(event)" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="gambar_peserta_id" name="peserta_id" value="">

                {{-- Preview --}}
                <div class="mb-4 flex flex-col items-center gap-2.5">
                    <div id="gambarPreviewWrap"
                        class="w-32 h-32 rounded-full overflow-hidden bg-[#faf8f4] border border-[#e7e2da] flex items-center justify-center transition">
                        <img id="gambarPreviewImg" src="" alt="Preview"
                            class="hidden w-full h-full object-cover pointer-events-none">
                        <span id="gambarPreviewPlaceholder" class="text-xs text-[#8c8982] text-center px-2">
                            Belum ada foto
                        </span>
                    </div>

                    <button type="button" id="btnLihatFull" onclick="lihatFullGambar()"
                        class="hidden items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-lg border border-[#e7e2da] bg-white text-[#24211d] hover:bg-[#faf8f4] hover:border-[#C28E27] transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/>
                        </svg>
                        Lihat Full Gambar
                    </button>
                </div>

                <div>
                    <label class="block text-xs font-bold mb-1.5 text-[#24211d]">
                        Pilih File Gambar <span class="text-red-500">*</span>
                    </label>
                    <input type="file" id="gambar_file" name="foto" accept="image/*" required
                        class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#C28E27] file:text-white hover:file:bg-[#a3721f] border border-[#e7e2da] rounded-lg p-1.5 bg-gray-50 cursor-pointer">
                    <p class="text-[11px] text-[#8c8982] mt-1">Format: JPG, PNG, WEBP. Maks 2 MB.</p>
                </div>

                <div id="modalGambarError" class="hidden mt-3 rounded-lg bg-rose-50 border border-rose-200 px-3 py-2 text-xs text-rose-700"></div>

                <div class="flex gap-2 mt-5">
                    <button type="button" onclick="closeModalGambar()"
                        class="flex-1 px-4 py-2.5 border border-[#e5e1db] bg-white rounded-md text-[#24211d] font-bold text-sm hover:bg-[#f7f5f0] transition">
                        Batal
                    </button>
                    <button type="submit" id="btnSubmitGambar"
                        class="flex-1 px-4 py-2.5 bg-[#C28E27] hover:bg-[#a3721f] text-white font-bold text-sm rounded-md transition">
                        Unggah
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- =====================================================
         IMAGE PREVIEW MODAL (zoom + pan)
    ====================================================== --}}
    <div id="imageModal" class="hidden fixed inset-0 z-[9999] bg-slate-950/90 backdrop-blur-sm" aria-hidden="true">

        {{-- Top bar --}}
        <div class="absolute top-0 left-0 right-0 z-10 flex items-center justify-between px-4 sm:px-6 py-4 bg-gradient-to-b from-black/60 to-transparent">
            <div class="flex items-center gap-2">
                <button type="button" onclick="zoomOut()"
                    class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition cursor-pointer"
                    title="Zoom Out">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                    </svg>
                </button>

                <button type="button" onclick="resetZoom()"
                    class="h-10 px-3 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-semibold transition cursor-pointer">
                    Reset
                </button>

                <button type="button" onclick="zoomIn()"
                    class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition cursor-pointer"
                    title="Zoom In">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </button>
            </div>

            <button type="button" onclick="closeImageModal()"
                class="w-10 h-10 rounded-xl bg-white/10 hover:bg-rose-500 text-white flex items-center justify-center transition cursor-pointer"
                title="Tutup">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Image container --}}
        <div id="imageModalBackdrop"
            class="w-full h-full flex items-center justify-center overflow-hidden p-5 sm:p-10"
            onclick="handleBackdropClick(event)">
            <img id="previewImage" src="" alt="Preview gambar"
                class="max-w-full max-h-full object-contain rounded-lg shadow-2xl cursor-zoom-in"
                style="transition: transform 0.2s ease; transform-origin: center center; user-select: none; -webkit-user-drag: none;"
                onclick="event.stopPropagation()"
                ondblclick="toggleZoom()">
        </div>

        {{-- Bottom hint --}}
        <div class="absolute bottom-5 left-1/2 -translate-x-1/2 rounded-full bg-black/50 px-4 py-2 text-[11px] text-white/70 backdrop-blur">
            Klik dua kali gambar untuk zoom · Scroll untuk perbesar/perkecil · Esc untuk tutup
        </div>
    </div>

    {{-- =====================================================
         SCRIPT
    ====================================================== --}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {

        // ========== TOGGLE TAMPIL — AJAX ==========
        document.querySelectorAll('.toggle-tampil-form').forEach(form => {
            form.addEventListener('submit', async function (e) {
                e.preventDefault();
                const btn = form.querySelector('.toggle-tampil-btn');
                if (!btn) return;

                const originalHTML = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = 'Memproses...';

                try {
                    const token = form.querySelector('input[name="_token"]').value;
                    const res = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: new FormData(form),
                    });

                    const data = await res.json();
                    if (!res.ok || !data.success) throw new Error(data.message || 'Gagal.');

                    btn.style.background = '';
                    btn.style.color = '';
                    btn.style.borderColor = '';

                    if (data.tampilDiPublik) {
                        btn.style.background = '#eff6ff';
                        btn.style.color = '#1d4ed8';
                        btn.style.borderColor = '#bfdbfe';
                        btn.innerHTML = 'Tampil di Publik';
                    } else {
                        btn.style.background = '#f3f4f6';
                        btn.style.color = '#4b5563';
                        btn.style.borderColor = '#e5e7eb';
                        btn.innerHTML = 'Disembunyikan';
                    }
                } catch (err) {
                    btn.innerHTML = originalHTML;
                    alert('Gagal: ' + err.message);
                } finally {
                    btn.disabled = false;
                }
            });
        });

        // ========== Tutup modal via backdrop ==========
        document.getElementById('modalTambah')?.addEventListener('click', function (e) {
            if (e.target === this) closeModalTambah();
        });
        document.getElementById('modalGambar')?.addEventListener('click', function (e) {
            if (e.target === this) closeModalGambar();
        });

        // ========== Preview file gambar saat dipilih ==========
        document.getElementById('gambar_file')?.addEventListener('change', function () {
            const file = this.files?.[0];
            const img  = document.getElementById('gambarPreviewImg');
            const ph   = document.getElementById('gambarPreviewPlaceholder');
            if (!file || !img || !ph) return;

            if (!file.type.startsWith('image/')) {
                alert('File harus berupa gambar.');
                this.value = '';
                return;
            }
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran maksimal 2 MB.');
                this.value = '';
                return;
            }

            const url = URL.createObjectURL(file);
            img.src = url;
            img.classList.remove('hidden');
            ph.classList.add('hidden');

            // ✅ Perbarui URL aktif supaya tombol "Lihat Full" bisa preview file baru juga
            currentGambarUrl = url;
            updateBtnLihatFull();
        });

        // ========== Image Preview — zoom ==========
        let currentZoom = 1;
        const minZoom = 0.5;
        const maxZoom = 4;
        const zoomStep = 0.25;

        const imageModal = document.getElementById('imageModal');
        const previewImage = document.getElementById('previewImage');

        window.openImageModal = function (imageUrl) {
            if (!imageUrl || !imageModal || !previewImage) return;
            previewImage.src = imageUrl;
            currentZoom = 1;
            applyZoom();
            imageModal.classList.remove('hidden');
            imageModal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('overflow-hidden');
        };

        window.closeImageModal = function () {
            if (!imageModal || !previewImage) return;
            imageModal.classList.add('hidden');
            imageModal.setAttribute('aria-hidden', 'true');
            previewImage.src = '';
            currentZoom = 1;

            // ✅ Kalau modal gambar masih terbuka, biarkan scroll tetap terkunci
            const modalGambarEl = document.getElementById('modalGambar');
            const masihAdaModal = modalGambarEl && !modalGambarEl.classList.contains('hidden');

            if (!masihAdaModal) {
                document.body.classList.remove('overflow-hidden');
                document.body.style.overflow = '';
            }
        };

        function applyZoom() {
            if (!previewImage) return;
            previewImage.style.transform = `scale(${currentZoom})`;
            previewImage.style.cursor = currentZoom > 1 ? 'zoom-out' : 'zoom-in';
        }

        window.zoomIn = function () {
            currentZoom = Math.min(currentZoom + zoomStep, maxZoom);
            applyZoom();
        };

        window.zoomOut = function () {
            currentZoom = Math.max(currentZoom - zoomStep, minZoom);
            applyZoom();
        };

        window.resetZoom = function () {
            currentZoom = 1;
            applyZoom();
        };

        window.toggleZoom = function () {
            if (currentZoom > 1) resetZoom();
            else { currentZoom = 2; applyZoom(); }
        };

        window.handleBackdropClick = function (event) {
            if (event.target === event.currentTarget) closeImageModal();
        };

        if (previewImage) {
            previewImage.addEventListener('wheel', function (event) {
                if (!imageModal || imageModal.classList.contains('hidden')) return;
                event.preventDefault();
                if (event.deltaY < 0) zoomIn();
                else zoomOut();
            }, { passive: false });
        }

        document.addEventListener('keydown', function (event) {
            if (!imageModal || imageModal.classList.contains('hidden')) return;
            if (event.key === 'Escape') closeImageModal();
            if (event.key === '+' || event.key === '=') zoomIn();
            if (event.key === '-') zoomOut();
        });
    });

    // ========== MODAL TAMBAH PESERTA ==========
    function openModalTambah() {
        const modal = document.getElementById('modalTambah');
        const err   = document.getElementById('modalError');
        if (!modal) return;
        modal.classList.remove('hidden');
        if (err) err.classList.add('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModalTambah() {
        const modal = document.getElementById('modalTambah');
        const form  = document.getElementById('formTambahPeserta');
        if (modal) modal.classList.add('hidden');
        if (form) form.reset();
        document.body.style.overflow = '';
    }

    async function submitTambah(e) {
        e.preventDefault();
        const form = e.target;
        const btn = document.getElementById('btnSubmitTambah');
        const errorEl = document.getElementById('modalError');
        if (!btn || !errorEl) return;

        const originalText = btn.textContent;
        btn.disabled = true;
        btn.textContent = 'Menyimpan...';
        errorEl.classList.add('hidden');

        try {
            const res = await fetch(@json(route('admin.peserta-tampil.store', $event?->id)), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    nama: form.nama.value,
                    email: form.email.value,
                }),
            });

            const data = await res.json();
            if (!res.ok || !data.success) throw new Error(data.message || 'Gagal menyimpan.');

            window.location.reload();
        } catch (err) {
            errorEl.textContent = err.message;
            errorEl.classList.remove('hidden');
            btn.disabled = false;
            btn.textContent = originalText;
        }
    }

    // ========== MODAL GAMBAR ==========
    let currentGambarUrl = null;

    function openModalGambar(pesertaId, nama, fotoUrl) {
        const idEl     = document.getElementById('gambar_peserta_id');
        const namaEl   = document.getElementById('modalGambarNama');
        const titleEl  = document.getElementById('modalGambarTitle');
        const errEl    = document.getElementById('modalGambarError');
        const form     = document.getElementById('formGambar');
        const img      = document.getElementById('gambarPreviewImg');
        const ph       = document.getElementById('gambarPreviewPlaceholder');
        const modal    = document.getElementById('modalGambar');

        if (!modal) return;

        if (idEl)   idEl.value = pesertaId;
        if (namaEl) namaEl.textContent = nama;
        if (titleEl) titleEl.textContent = fotoUrl ? 'Ubah Gambar' : 'Tambah Gambar';
        if (errEl)  errEl.classList.add('hidden');
        if (form)   form.reset();
        if (idEl)   idEl.value = pesertaId;

        currentGambarUrl = fotoUrl || null;

        if (fotoUrl && img && ph) {
            img.src = fotoUrl;
            img.classList.remove('hidden');
            ph.classList.add('hidden');
        } else if (img && ph) {
            img.src = '';
            img.classList.add('hidden');
            ph.classList.remove('hidden');
            ph.textContent = 'Belum ada foto';
        }

        updateBtnLihatFull();
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    // ✅ Tampilkan / sembunyikan tombol "Lihat Full Gambar"
    function updateBtnLihatFull() {
        const btn = document.getElementById('btnLihatFull');
        const wrap = document.getElementById('gambarPreviewWrap');
        if (!btn) return;

        if (currentGambarUrl) {
            btn.classList.remove('hidden');
            btn.classList.add('inline-flex');
            if (wrap) {
                wrap.classList.add('cursor-pointer', 'hover:ring-2', 'hover:ring-[#C28E27]/50', 'ring-offset-1');
                wrap.onclick = lihatFullGambar;
                wrap.title = 'Klik untuk lihat full';
            }
        } else {
            btn.classList.add('hidden');
            btn.classList.remove('inline-flex');
            if (wrap) {
                wrap.classList.remove('cursor-pointer', 'hover:ring-2', 'hover:ring-[#C28E27]/50', 'ring-offset-1');
                wrap.onclick = null;
                wrap.title = '';
            }
        }
    }

    // ✅ Buka imageModal (zoom/preview) dari dalam modal gambar
    function lihatFullGambar() {
        if (currentGambarUrl && typeof openImageModal === 'function') {
            openImageModal(currentGambarUrl);
        }
    }

    function closeModalGambar() {
        const modal = document.getElementById('modalGambar');
        const form  = document.getElementById('formGambar');
        if (modal) modal.classList.add('hidden');
        if (form) form.reset();
        currentGambarUrl = null;
        document.body.style.overflow = '';
    }

    async function submitGambar(e) {
        e.preventDefault();
        const form      = e.target;
        const btn       = document.getElementById('btnSubmitGambar');
        const errorEl   = document.getElementById('modalGambarError');
        const idEl      = document.getElementById('gambar_peserta_id');
        const fileInput = document.getElementById('gambar_file');

        if (!btn || !errorEl || !idEl || !fileInput) return;

        const pesertaId = idEl.value;

        if (!fileInput.files?.length) {
            errorEl.textContent = 'Pilih file gambar terlebih dahulu.';
            errorEl.classList.remove('hidden');
            return;
        }

        const originalText = btn.textContent;
        btn.disabled = true;
        btn.textContent = 'Mengunggah...';
        errorEl.classList.add('hidden');

        const fd = new FormData();
        const tokenInput = form.querySelector('input[name="_token"]');
        if (tokenInput) fd.append('_token', tokenInput.value);
        fd.append('foto', fileInput.files[0]);

        try {
            const res = await fetch(`/admin/peserta-tampil/${pesertaId}/foto`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: fd,
            });

            const data = await res.json();
            if (!res.ok || !data.success) throw new Error(data.message || 'Gagal mengunggah gambar.');

            const avatar = document.getElementById('avatar-' + pesertaId);
            if (avatar && data.foto_url) {
                avatar.innerHTML = `<img src="${data.foto_url}?t=${Date.now()}" alt="" class="w-full h-full object-cover">`;
            }

            const rowBtn = document.querySelector(`button[onclick*="openModalGambar(${pesertaId},"]`);
            if (rowBtn) rowBtn.textContent = 'Ubah Gambar';

            closeModalGambar();
        } catch (err) {
            errorEl.textContent = err.message;
            errorEl.classList.remove('hidden');
        } finally {
            btn.disabled = false;
            btn.textContent = originalText;
        }
    }
    </script>
</body>

</html>