<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Admin — ALNAY.ID</title>
    {{-- TODO: samakan dengan baris @include di admin/staf/index.blade.php --}}
    @include('admin.partials.sidebar', ['active' => 'staf'])
    @include('admin.staf._styles')
</head>
<body>
    <main class="admin-main">
        <div class="page-header">
            <div>
                <h1>Tambah Admin</h1>
                <p>Buat akun admin baru dan atur hak aksesnya.</p>
            </div>
            <a href="{{ route('admin.staf.index') }}" class="btn btn-ghost">&larr; Kembali</a>
        </div>

        @if ($errors->any())
            <div class="alert alert-error">
                <strong>Periksa lagi isian form:</strong>
                <ul style="margin:0.4rem 0 0 1.1rem; padding:0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card form-card">
            <form method="POST" action="{{ route('admin.staf.store') }}">
                @csrf
                @include('admin.staf._form', ['staf' => null])

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Simpan Admin</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
