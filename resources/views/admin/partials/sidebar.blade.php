<link rel="stylesheet" href="{{ asset('css/admin-sidebar.css') }}">
<style>
    * {
        font-family: "Inter";
    }
</style>
<aside class="admin-sidebar">
    <div class="admin-sidebar__brand">ALNAY<span>.ID</span></div>

    <nav class="admin-sidebar__nav" aria-label="Event dan peserta">
        <p class="admin-sidebar__section">Event & Peserta</p>
        
        <a class="{{ ($active ?? '') === 'dashboard' ? 'is-active' : '' }}" 
        href="{{ route('admin.dashboard') }}">
            <span class="admin-sidebar__step">1</span>
            <span>Verifikasi Pendaftaran Peserta</span>
            <b class="admin-sidebar__badge">{{ $menungguReview ?? 0 }}</b>
        </a>

        <a class="{{ ($active ?? '') === 'form-builder' ? 'is-active' : '' }}" 
   href="{{ isset($event) ? route('admin.form-builder', $event->id) : route('admin.form-builder') }}">
            <span class="admin-sidebar__step">2</span>
            <span>Form Pendaftaran Event</span>
        </a>

        <a class="{{ in_array($active ?? '', ['events-create', 'events-index']) ? 'is-active' : '' }}"
        href="{{ route('admin.events.index') }}">
            <span class="admin-sidebar__step">3</span>
            <span>Kelola Event</span>
        </a>

        <a class="{{ ($active ?? '') === 'activation' ? 'is-active' : '' }}"
        href="{{ isset($event) ? route('admin.activation', $event->id) : '#' }}">
            <span class="admin-sidebar__step">4</span>
            <span>Aktivasi Akun Peserta</span>
        </a>
    </nav>

    <nav class="admin-sidebar__nav" aria-label="Voting">
        <p class="admin-sidebar__section">Voting</p>

        {{-- Pengaturan Event & Voting --}}
        <a class="{{ ($active ?? '') === 'voting-settings' ? 'is-active' : '' }}"
        href="{{ isset($event) ? route('admin.voting.settings', $event->id) : '#' }}">
            <span class="admin-sidebar__step">1</span>
            <span>Pengaturan Event & Voting</span>
        </a>

        {{-- Daftar Voter --}}
        <a class="{{ ($active ?? '') === 'voters' ? 'is-active' : '' }}"
        href="{{ isset($event) ? route('admin.voters', $event->id) : '#' }}">
            <span class="admin-sidebar__step">2</span>
            <span>Daftar Voter</span>
        </a>

        {{-- Leaderboard Suara Voting --}}
        <a class="{{ ($active ?? '') === 'leaderboard' ? 'is-active' : '' }}"
        href="{{ isset($event) ? route('admin.leaderboard', $event->id) : '#' }}">
            <span class="admin-sidebar__step">3</span>
            <span>Leaderboard Suara Voting</span>
        </a>
    </nav>

    @php
    $adminName = session('admin_name', 'MAS RADEN');

    $initials = collect(explode(' ', trim($adminName)))
        ->filter()
        ->map(fn($word) => strtoupper(substr($word, 0, 1)))
        ->join('');
    @endphp

    <a href="{{ route('admin.profile') }}" class="admin-sidebar__user {{ ($active ?? '') === 'profile' ? 'is-active' : '' }}">
        <span class="admin-sidebar__avatar">{{ $initials }}</span>
        <div>
            <strong>{{ $adminName }}</strong>
            <small>Admin Event</small>
        </div>
    </a>
</aside>
