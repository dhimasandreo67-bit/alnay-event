<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    @include('admin.partials.pwa-head')
    <title>Profil Admin | ALNAY.ID</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { gold: '#ba8320', cream: '#f7f5f0' } } } };
    </script>
</head>
<body class="bg-cream font-['Inter'] text-[13px] text-[#22201d]">
<div class="flex min-h-screen">
    @include('admin.partials.sidebar', ['active' => 'profile', 'event' => $event])

    <main class="flex-1 min-w-0 p-6 max-w-2xl ml-[235px]">
        <h1 class="text-2xl font-bold m-0">Profil Admin</h1>
        <p class="text-[#73716c] text-[12px] mt-1 mb-5">Kelola informasi akun dan password login kamu.</p>

        @if (session('success'))
            <div class="mb-4 rounded-lg bg-emerald-50 text-emerald-700 px-4 py-3 text-sm">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-rose-50 text-rose-700 px-4 py-3 text-sm">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        {{-- Info akun --}}
        <div class="bg-white border border-[#e3ded6] rounded-xl p-5 mb-5">
            <div class="flex items-center gap-3 mb-5">
                <span class="w-14 h-14 rounded-full bg-gold text-white text-xl font-bold grid place-items-center">
                    {{ strtoupper(substr($admin->nama, 0, 2)) }}
                </span>
                <div>
                    <div class="font-bold text-[15px]">{{ $admin->nama }}</div>
                    <div class="text-[11px] text-[#73716c]">Admin Event</div>
                </div>
            </div>

            <form action="{{ route('admin.profile.update') }}" method="POST">
                @csrf
                <label class="block text-[11px] font-bold text-[#73716c] mb-1">Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama', $admin->nama) }}"
                       class="w-full border border-[#e5e1db] rounded-lg px-3 py-2.5 text-[12px] mb-3">

                <label class="block text-[11px] font-bold text-[#73716c] mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $admin->email) }}"
                       class="w-full border border-[#e5e1db] rounded-lg px-3 py-2.5 text-[12px] mb-4">

                <button type="submit" class="px-4 py-2.5 text-[12px] font-bold bg-gold text-white rounded-lg">
                    Simpan Perubahan
                </button>
            </form>
        </div>

        {{-- Ubah password --}}
        <div class="bg-white border border-[#e3ded6] rounded-xl p-5">
            <h2 class="font-bold text-[14px] mb-4">Ubah Password</h2>

            <form action="{{ route('admin.profile.password') }}" method="POST">
                @csrf
                <label class="block text-[11px] font-bold text-[#73716c] mb-1">Password Saat Ini</label>
                <input type="password" name="current_password"
                       class="w-full border border-[#e5e1db] rounded-lg px-3 py-2.5 text-[12px] mb-3">

                <label class="block text-[11px] font-bold text-[#73716c] mb-1">Password Baru</label>
                <input type="password" name="password"
                       class="w-full border border-[#e5e1db] rounded-lg px-3 py-2.5 text-[12px] mb-3">

                <label class="block text-[11px] font-bold text-[#73716c] mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation"
                       class="w-full border border-[#e5e1db] rounded-lg px-3 py-2.5 text-[12px] mb-4">

                <button type="submit" class="px-4 py-2.5 text-[12px] font-bold border border-[#e3ded6] rounded-lg">
                    Ubah Password
                </button>
            </form>
        </div>
    </main>
</div>
</body>
</html>