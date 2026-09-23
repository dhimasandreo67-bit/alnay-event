<link rel="stylesheet" href="{{ asset('css/admin-sidebar.css') }}">
<aside class="admin-sidebar">
    <div class="admin-sidebar__brand">ALNAY<span>.ID</span></div>

    <nav class="admin-sidebar__nav" aria-label="Event dan peserta">
        <p class="admin-sidebar__section">Event & Peserta</p>
        <a class="{{ ($active ?? '') === 'dashboard' ? 'is-active' : '' }}" href="{{ route('admin.dashboard') }}"><span class="admin-sidebar__step">1</span><span>Verifikasi Pendaftaran Peserta</span><b class="admin-sidebar__badge">{{ $menungguReview ?? 0 }}</b></a>
        <a class="{{ ($active ?? '') === 'form-builder' ? 'is-active' : '' }}" href="{{ route('admin.form-builder', $event?->id) }}"><span class="admin-sidebar__step">2</span><span>Buat Form Pendaftaran Event Baru</span></a>
        <a class="{{ ($active ?? '') === 'registrations' ? 'is-active' : '' }}" href="{{ route('admin.registrations', $event?->id) }}"><span class="admin-sidebar__step">3</span><span>Buat Event Baru</span></a>
        <a class="{{ ($active ?? '') === 'activation' ? 'is-active' : '' }}" href="{{ route('admin.activation', $event?->id) }}"><span class="admin-sidebar__step">4</span><span>Aktivasi Akun Peserta</span></a>
    </nav>

    <nav class="admin-sidebar__nav" aria-label="Voting">
        <p class="admin-sidebar__section">Voting</p>
        <a class="{{ ($active ?? '') === 'voting-settings' ? 'is-active' : '' }}" href="{{ route('admin.voting.settings', $event?->id) }}"><span class="admin-sidebar__step">4</span><span>Pengaturan Event & Voting</span></a>
        <a class="{{ ($active ?? '') === 'voters' ? 'is-active' : '' }}" href="{{ route('admin.voters', $event?->id) }}"><span class="admin-sidebar__step">5</span><span>Daftar Voter</span><b class="admin-sidebar__live">Live</b></a>
        <a class="{{ ($active ?? '') === 'leaderboard' ? 'is-active' : '' }}" href="{{ route('admin.leaderboard', $event?->id) }}"><span class="admin-sidebar__step">6</span><span>Leaderboard Suara Voting</span></a>
    </nav>

    <div class="admin-sidebar__user"><span class="admin-sidebar__avatar">SA</span><div><strong>{{ session('admin_name', 'Siti Aminah') }}</strong><small>Admin Event</small></div></div>
</aside>
