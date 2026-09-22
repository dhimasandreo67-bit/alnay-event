<link rel="stylesheet" href="{{ asset('css/admin-header.css') }}">
<header class="admin-header admin-header--{{ $variant ?? 'module' }}">
    @if(($variant ?? 'module') === 'dashboard')
        <div class="admin-header__context">Dashboard Admin<strong>{{ $event?->nama ?? 'Event Alnay' }}</strong></div>
        <div class="admin-header__actions"><a class="admin-header__button admin-header__button--gold" href="{{ route('admin.events.create') }}">＋ Tambah Event Baru</a><form method="POST" action="{{ route('admin.logout.web') }}">@csrf<button class="admin-header__button" type="submit">Keluar</button></form></div>
    @elseif(($variant ?? 'module') === 'builder')
        <div class="admin-header__builder-context"><span class="admin-header__eyebrow">Atelier Event › {{ $event?->nama ?? 'Pilih Event' }} › Atur Formulir Pendaftaran</span><strong>Formulir Pendaftaran — {{ $event?->nama ?? 'Event Baru' }}</strong><small>Atur data & persyaratan yang wajib diisi peserta fashion show busana adat</small></div>
        <div class="admin-header__actions"><a class="admin-header__button" href="{{ route('admin.dashboard') }}">← Dashboard</a><button class="admin-header__button admin-header__button--gold" type="button" id="saveFormHeader">▣ Simpan Formulir</button></div>
    @else
        <div class="admin-header__context">Admin Portal<strong>{{ $event?->nama ?? 'Event Alnay' }}</strong></div>
        <a class="admin-header__button" href="{{ route('admin.dashboard') }}">← Dashboard</a>
    @endif
</header>
