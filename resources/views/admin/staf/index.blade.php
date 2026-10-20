<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Admin — ALNAY.ID</title>
    @include('admin.partials.sidebar', ['active' => 'staf'])
    {{-- admin.staf._styles sudah dihapus sesuai permintaan --}}
    @include('admin.partials.pwa-head')
    @vite(['resources/css/app.css'])
    
    <style>
        /* Mengatur agar konten utama bergeser ke kanan dan tidak tertutup sidebar */
        .admin-main {
            background-color: #f8fafc !important;
            min-height: 100vh;
            padding: 2rem !important;
            margin-left: 245px !important; /* Sesuaikan lebar ini jika sidebar Anda lebih besar/kecil (misal: 280px) */
            width: calc(100% - 260px) !important;
            box-sizing: border-box;
        }

        /* Responsif untuk HP / layar kecil (sidebar biasanya disembunyikan/di-toggle) */
        @media (max-width: 768px) {
            .admin-main {
                margin-left: 0 !important;
                width: 100% !important;
                padding: 1rem !important;
            }
        }

        /* Override Header Title & Subtitle */
        .page-header-title {
            color: #0f172a !important;
            font-size: 1.5rem !important;
            font-weight: 800 !important;
            line-height: 1.2;
        }

        .page-header-subtitle {
            color: #475569 !important;
            font-size: 0.875rem !important;
            margin-top: 0.25rem;
        }

        /* Modal Overlay & Animation */
        .confirm-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(6px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        
        .confirm-overlay.is-active {
            opacity: 1;
            pointer-events: auto;
        }

        .confirm-box {
            background: #ffffff !important;
            border-radius: 1.25rem;
            padding: 1.75rem;
            max-width: 380px;
            width: 90%;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border: 1px solid #e2e8f0;
            transform: scale(0.95) translateY(10px);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .confirm-overlay.is-active .confirm-box {
            transform: scale(1) translateY(0);
            opacity: 1;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased w-full m-0 p-0">
    <main class="admin-main">
        
        <!-- Header Halaman -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 rounded-2xl bg-white p-6 shadow-sm border border-slate-200/80 w-full">
            <div>
                <h1 class="page-header-title">Kelola Admin</h1>
                <p class="page-header-subtitle">Tambah admin baru, atur halaman &amp; event yang boleh mereka akses, atau ganti password mereka.</p>
            </div>
            <a href="{{ route('admin.staf.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-amber-500 hover:bg-amber-600 px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-amber-500/20 active:scale-95 transition-all shrink-0">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Tambah Admin
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-sm font-semibold text-emerald-800 shadow-sm flex items-center gap-3 w-full">
                <svg class="h-5 w-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 rounded-xl bg-rose-50 border border-rose-200 p-4 text-sm font-semibold text-rose-800 shadow-sm flex items-center gap-3 w-full">
                <svg class="h-5 w-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- Tabel Data Admin -->
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden w-full">
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                            <th class="py-4 px-6">Admin</th>
                            <th class="py-4 px-6">Role</th>
                            <th class="py-4 px-6">Akses Halaman</th>
                            <th class="py-4 px-6">Akses Event</th>
                            <th class="py-4 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($stafList as $item)
                            <tr class="transition-colors hover:bg-slate-50/80">
                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-900 text-sm">{{ $item->nama }}</div>
                                    <div class="text-xs text-slate-500 mt-0.5 font-medium">{{ $item->email }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    @if ($item->isSuperAdmin())
                                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-900 border border-amber-300/60">
                                            Super Admin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700 border border-slate-200">
                                            Admin
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-xs">
                                    @if ($item->isSuperAdmin())
                                        <span class="text-slate-400 font-medium">Semua halaman</span>
                                    @else
                                        <span class="rounded bg-slate-100 px-2.5 py-1 font-bold text-slate-700 border border-slate-200/60">{{ count($item->allowed_pages ?? []) }} halaman</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-xs">
                                    @if ($item->isSuperAdmin())
                                        <span class="text-slate-400 font-medium">Semua event</span>
                                    @else
                                        <span class="rounded bg-slate-100 px-2.5 py-1 font-bold text-slate-700 border border-slate-200/60">{{ $item->allowed_events_count }} event</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="inline-flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.staf.edit', $item->id) }}" class="rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-50 active:scale-95 transition-all shadow-xs">
                                            Edit
                                        </a>

                                        @if ($item->id !== session('admin_id'))
                                            <form method="POST" action="{{ route('admin.staf.destroy', $item->id) }}" class="js-delete-staf-form inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="rounded-lg bg-rose-50 border border-rose-200 px-3 py-1.5 text-xs font-bold text-rose-600 hover:bg-rose-100 active:scale-95 transition-all js-delete-staf-trigger"
                                                        data-confirm-message="Hapus admin {{ $item->nama }}? Tindakan ini tidak bisa dibatalkan.">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400 text-sm font-medium">Belum ada data admin.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    {{-- Modal Konfirmasi Hapus --}}
    <div id="deleteConfirmOverlay" class="confirm-overlay">
        <div class="confirm-box text-center" role="dialog" aria-modal="true" aria-labelledby="deleteConfirmText">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-100 text-rose-600 mb-4 shadow-inner">
                <svg class="h-6 w-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>

            <h3 class="text-base font-bold text-slate-900 mb-1">Konfirmasi Penghapusan</h3>
            <p id="deleteConfirmText" class="text-xs text-slate-500 mb-6 leading-relaxed">Yakin mau lanjut?</p>
            
            <div class="grid grid-cols-2 gap-3">
                <button type="button" id="deleteConfirmCancel" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 active:scale-95 transition-all">
                    Batal
                </button>
                <button type="button" id="deleteConfirmOk" class="w-full rounded-xl bg-rose-600 hover:bg-rose-700 px-4 py-2 text-xs font-bold text-white shadow-md shadow-rose-600/20 active:scale-95 transition-all">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>

    <script>
    (function () {
        var overlay    = document.getElementById('deleteConfirmOverlay');
        var textEl     = document.getElementById('deleteConfirmText');
        var btnCancel  = document.getElementById('deleteConfirmCancel');
        var btnOk      = document.getElementById('deleteConfirmOk');
        var pendingForm = null;

        if (!overlay || !btnCancel || !btnOk) return;

        function openModal(form, customMessage) {
            pendingForm = form;
            if (customMessage && textEl) {
                textEl.textContent = customMessage;
            }
            overlay.classList.add('is-active');
        }

        function closeModal() {
            overlay.classList.remove('is-active');
            pendingForm = null;
        }

        document.querySelectorAll('.js-delete-staf-trigger').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var form = btn.closest('form.js-delete-staf-form');
                if (!form) return;
                openModal(form, btn.getAttribute('data-confirm-message'));
            });
        });

        btnCancel.addEventListener('click', closeModal);

        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) closeModal();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && overlay.classList.contains('is-active')) {
                closeModal();
            }
        });

        btnOk.addEventListener('click', function () {
            var form = pendingForm;
            closeModal();
            if (form) form.submit();
        });
    })();
    </script>
</body>
</html>