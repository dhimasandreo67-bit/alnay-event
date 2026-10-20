<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Admin — ALNAY.ID</title>
    @include('admin.partials.sidebar', ['active' => 'staf'])
    @include('admin.staf._styles')
    @include('admin.partials.pwa-head')
</head>
<body>
    @php
        // Halaman ini seharusnya sudah dibatasi ke Super Admin lewat middleware route
        // (grup yang sama dengan admin.staf.index). $isSuperAdmin di sini dipakai sebagai
        // lapisan pertahanan tambahan khusus untuk form reset password di bawah.
        $currentStaf  = \App\Models\AkunStaf::find(session('admin_id'));
        $isSuperAdmin = (bool) $currentStaf?->isSuperAdmin();
    @endphp

    <main class="admin-main">
        <div class="page-header">
            <div>
                <h1>Edit Admin</h1>
                <p>{{ $staf->nama }} &middot; {{ $staf->email }}</p>
            </div>
            <a href="{{ route('admin.staf.index') }}" class="btn btn-ghost">&larr; Kembali</a>
        </div>

        @if ($errors->any() && !$errors->hasBag('resetPassword'))
            <div class="alert alert-error">
                <strong>Periksa lagi isian form:</strong>
                <ul style="margin:0.4rem 0 0 1.1rem; padding:0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card form-card" style="margin-bottom:1.5rem;">
            <form method="POST" action="{{ route('admin.staf.update', $staf->id) }}">
                @csrf
                @method('PUT')
                @include('admin.staf._form', ['staf' => $staf])

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>

        @if ($isSuperAdmin)
            {{-- Ganti password admin lain — hanya Super Admin yang boleh melakukan ini.
                 Tidak perlu password lama, karena ini aksi reset oleh Super Admin,
                 bukan admin yang bersangkutan mengganti password sendiri. --}}
            <div class="card form-card">
                <h3 style="margin-top:0; font-size:0.95rem; color:#fafaf9;">Ganti Password</h3>
                <p class="field-hint" style="margin-bottom:1rem;">Dipakai kalau admin ini lupa password, atau kamu mau reset password-nya langsung.</p>

                <form method="POST" action="{{ route('admin.staf.password', $staf->id) }}">
                    @csrf
                    <div class="form-grid">
                        <div class="field">
                            <label for="password">Password Baru</label>
                            <input type="password" id="password" name="password" required minlength="8">
                        </div>
                        <div class="field">
                            <label for="password_confirmation">Konfirmasi Password Baru</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8">
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Ganti Password</button>
                    </div>
                </form>
            </div>
        @endif
    </main>
</body>
</html>