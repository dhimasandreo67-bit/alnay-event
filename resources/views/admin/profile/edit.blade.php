<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('admin.partials.pwa-head')
    <title>Profil Admin | ALNAY.ID</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-cream font-['Inter'] text-[13px] text-[#22201d]">

{{-- Outer Layout Container --}}
<div class="flex min-h-screen w-full">
    @include('admin.partials.sidebar', ['active' => 'profile', 'event' => $event])

    {{-- Main Content: Mengisi sisa ruang (flex-1) dan full width --}}
    <main class="flex-1 min-w-0 p-6 md:p-8 ml-[235px]">
        <h1 class="text-2xl font-bold m-0">Profil Admin</h1>
        <p class="text-[#73716c] text-[12px] mt-1 mb-6">Kelola informasi akun dan password login kamu.</p>

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
                            <div class="text-[12px] text-[#73716c]">Admin Event</div>
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

            {{-- Card Ubah Password --}}
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

        </div>
    </main>
</div>

</body>
</html>