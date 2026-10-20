<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('admin.partials.pwa-head')
    <title>Profil Admin | ALNAY.ID</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-cream font-['Inter'] text-[13px] text-[#22201d]">

@php
    // Dipakai untuk menentukan apakah admin yang sedang login boleh mengubah password.
    // Hanya Super Admin yang boleh mengubah password (punya dirinya sendiri lewat halaman ini).
    $isSuperAdmin = (bool) ($admin->isSuperAdmin() ?? false);
@endphp

{{-- Outer Layout Container --}}
<div class="flex min-h-screen w-full">
    @include('admin.partials.sidebar', ['active' => 'profile', 'event' => $event])

    {{-- Main Content: Mengisi sisa ruang (flex-1) dan full width --}}
    <main class="flex-1 min-w-0 p-6 md:p-8 ml-[235px]">
    {{-- Header: judul kiri, tombol logout kanan --}}
    <div class="flex flex-wrap items-start justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold m-0">Profil Admin</h1>
            <p class="text-[#73716c] text-[12px] mt-1">Kelola informasi akun dan password login kamu.</p>
        </div>

        {{-- Hidden logout form --}}
        <form id="logoutForm" action="{{ route('admin.logout.web') }}" method="POST" class="hidden">
            @csrf
        </form>

        {{-- Tombol logout — klik = langsung logout --}}
        <button type="button" id="btnLogoutAdmin"
            class="mt-6 inline-flex items-center justify-center gap-2 rounded-lg border border-rose-200 bg-rose-50 px-4 py-2.5 text-xs font-semibold text-rose-700 transition hover:bg-rose-600 hover:text-white hover:border-rose-600 cursor-pointer">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
            </svg>
            Keluar dari Akun
        </button>
    </div>

        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-rose-50 text-rose-700 px-4 py-3 text-sm">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        {{-- Info akun: Grid 2 kolom yang melebar penuh (w-full) --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 w-full">

            {{-- Card Profil --}}
            <div class="bg-white border border-[#e3ded6] rounded-xl p-6 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-4 mb-6">
                        <span class="w-14 h-14 rounded-full bg-gold text-white text-xl font-bold grid place-items-center flex-shrink-0">
                            {{ strtoupper(substr($admin->nama ?? 'AD', 0, 2)) }}
                        </span>
                        <div>
                            <div class="font-bold text-[16px]">{{ $admin->nama ?? 'Admin' }}</div>
                            <div class="text-[12px] text-[#73716c]">{{ $isSuperAdmin ? 'Super Admin' : 'Admin Event' }}</div>
                        </div>
                    </div>

                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-[11px] font-bold text-[#73716c] mb-1.5">
                                Nama Lengkap
                            </label>
                            <input type="text" name="nama" value="{{ old('nama', $admin->nama ?? '') }}"
                                   class="w-full border border-[#e5e1db] rounded-lg px-3.5 py-2.5 text-[12px] focus:outline-none focus:border-gold">
                        </div>

                        <div class="mb-6">
                            <label class="block text-[11px] font-bold text-[#73716c] mb-1.5">
                                Email
                            </label>
                            <input type="email" name="email" value="{{ old('email', $admin->email ?? '') }}"
                                   class="w-full border border-[#e5e1db] rounded-lg px-3.5 py-2.5 text-[12px] focus:outline-none focus:border-gold">
                        </div>

                        <button type="submit"
                                class="px-5 py-2.5 text-[12px] font-bold bg-gold hover:bg-[#a3721a] text-white rounded-lg transition">
                            Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>

            @if ($isSuperAdmin)
                {{-- Card Ubah Password — hanya Super Admin yang boleh mengubah password --}}
                <div class="bg-white border border-[#e3ded6] rounded-xl p-6 shadow-sm">
                    <h2 class="font-bold text-[15px] mb-5">Ubah Password</h2>

                    <form action="{{ route('admin.profile.password') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-[11px] font-bold text-[#73716c] mb-1.5">
                                Password Saat Ini
                            </label>
                            <input type="password" name="current_password"
                                   class="w-full border border-[#e5e1db] rounded-lg px-3.5 py-2.5 text-[12px] focus:outline-none focus:border-gold">
                        </div>

                        <div class="mb-4">
                            <label class="block text-[11px] font-bold text-[#73716c] mb-1.5">
                                Password Baru
                            </label>
                            <input type="password" name="password"
                                   class="w-full border border-[#e5e1db] rounded-lg px-3.5 py-2.5 text-[12px] focus:outline-none focus:border-gold">
                        </div>

                        <div class="mb-6">
                            <label class="block text-[11px] font-bold text-[#73716c] mb-1.5">
                                Konfirmasi Password Baru
                            </label>
                            <input type="password" name="password_confirmation"
                                   class="w-full border border-[#e5e1db] rounded-lg px-3.5 py-2.5 text-[12px] focus:outline-none focus:border-gold">
                        </div>

                        <button type="submit"
                                class="px-5 py-2.5 text-[12px] font-bold border border-[#e3ded6] hover:bg-gray-50 rounded-lg transition">
                            Ubah Password
                        </button>
                    </form>
                </div>
            @else
                {{-- Non-Super Admin: tidak diizinkan mengubah password sendiri --}}
                <div class="bg-white border border-[#e3ded6] rounded-xl p-6 shadow-sm flex flex-col justify-center gap-2">
                    <h2 class="font-bold text-[15px]">Ubah Password</h2>
                    <p class="text-[12px] text-[#73716c] leading-relaxed">
                        Untuk keamanan akun, perubahan password hanya bisa dilakukan oleh
                        <strong>Super Admin</strong>. Hubungi Super Admin kalau kamu perlu
                        mengganti password akun ini.
                    </p>
                </div>
            @endif
        </div>
    </main>
</div>

<script>
(function () {
    var btn  = document.getElementById('btnLogoutAdmin');
    var form = document.getElementById('logoutForm');
    if (!btn || !form) return;

    btn.addEventListener('click', function (e) {
        e.preventDefault();
        form.submit();   // langsung logout, tanpa konfirmasi
    });
})();
</script>

</body>
</html>