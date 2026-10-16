<link rel="stylesheet" href="{{ asset('css/admin-sidebar.css') }}">
<style>
/* ============================================
   Admin Sidebar — ALNAY.ID
   ============================================ */

@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

.admin-sidebar {
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    width: 16rem; /* 256px */
    background: #1c1917;
    color: #e7e5e4;
    display: flex;
    flex-direction: column;
    z-index: 40;
    font-family: 'Inter', system-ui, sans-serif;
    border-right: 1px solid rgba(255, 255, 255, 0.06);
    overflow: hidden;
}

/* ---- Brand ---- */
.admin-sidebar__brand {
    padding: 1.35rem 1.25rem 1.1rem;
    font-size: 1.15rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    color: #fafaf9;
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    animation: sidebarFadeIn 0.45s ease both;
}

.admin-sidebar__brand span {
    color: #C28E27;
}

/* ---- Section label ---- */
.admin-sidebar__section {
    margin: 1.1rem 1.25rem 0.45rem;
    font-size: 0.65rem;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #78716c;
    animation: sidebarFadeIn 0.4s ease both;
    animation-delay: 0.05s;
}

/* ---- Nav links ---- */
.admin-sidebar__nav {
    display: flex;
    flex-direction: column;
    gap: 2px;
    padding: 0 0.65rem;
}

.admin-sidebar__link {
    position: relative;
    display: flex;
    align-items: center;
    gap: 0.7rem;
    padding: 0.6rem 0.75rem;
    border-radius: 0.55rem;
    font-size: 0.8125rem;
    font-weight: 500;
    color: #a8a29e;
    text-decoration: none;
    transition:
        background 0.2s ease,
        color 0.2s ease,
        transform 0.2s ease,
        box-shadow 0.2s ease;
    animation: sidebarSlideIn 0.4s ease both;
    animation-delay: calc(0.06s * var(--i, 0));
    overflow: hidden;
}

/* Indikator kiri (muncul saat hover / active) */
.admin-sidebar__link::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%) scaleY(0);
    width: 3px;
    height: 60%;
    border-radius: 0 3px 3px 0;
    background: #C28E27;
    transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.admin-sidebar__link:hover {
    background: rgba(255, 255, 255, 0.05);
    color: #fafaf9;
    transform: translateX(2px);
}

.admin-sidebar__link:hover::before {
    transform: translateY(-50%) scaleY(1);
}

.admin-sidebar__link.is-active {
    background: rgba(194, 142, 39, 0.15);
    color: #fafaf9;
    box-shadow: inset 0 0 0 1px rgba(194, 142, 39, 0.25);
}

.admin-sidebar__link.is-active::before {
    transform: translateY(-50%) scaleY(1);
}

.admin-sidebar__link.is-active .admin-sidebar__step {
    background: #C28E27;
    color: #1c1917;
    box-shadow: 0 0 0 3px rgba(194, 142, 39, 0.25);
}

/* ---- Step number ---- */
.admin-sidebar__step {
    flex-shrink: 0;
    width: 1.45rem;
    height: 1.45rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 9999px;
    font-size: 0.7rem;
    font-weight: 700;
    background: rgba(255, 255, 255, 0.08);
    color: #a8a29e;
    transition:
        background 0.2s ease,
        color 0.2s ease,
        box-shadow 0.2s ease,
        transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.admin-sidebar__link:hover .admin-sidebar__step {
    background: rgba(194, 142, 39, 0.25);
    color: #C28E27;
    transform: scale(1.08);
}

.admin-sidebar__label {
    flex: 1;
    line-height: 1.3;
    min-width: 0;
}

/* ---- Badge notifikasi ---- */
.admin-sidebar__badge {
    flex-shrink: 0;
    min-width: 1.25rem;
    height: 1.25rem;
    padding: 0 0.35rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 9999px;
    font-size: 0.65rem;
    font-weight: 700;
    background: #C28E27;
    color: #1c1917;
    animation: badgePulse 2s ease-in-out infinite;
}

/* ---- Spacer ---- */
.admin-sidebar__spacer {
    flex: 1;
}

/* ---- User card ---- */
.admin-sidebar__user {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin: 0.75rem;
    padding: 0.75rem;
    border-radius: 0.75rem;
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.06);
    text-decoration: none;
    color: inherit;
    transition:
        background 0.2s ease,
        border-color 0.2s ease,
        transform 0.2s ease;
    animation: sidebarFadeIn 0.5s ease both;
    animation-delay: 0.35s;
}

.admin-sidebar__user:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(194, 142, 39, 0.35);
    transform: translateY(-1px);
}

.admin-sidebar__user.is-active {
    background: rgba(194, 142, 39, 0.12);
    border-color: rgba(194, 142, 39, 0.4);
}

.admin-sidebar__avatar {
    flex-shrink: 0;
    width: 2.25rem;
    height: 2.25rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 9999px;
    background: linear-gradient(135deg, #C28E27, #a06f1a);
    color: #1c1917;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.02em;
    transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.admin-sidebar__user:hover .admin-sidebar__avatar {
    transform: scale(1.08);
}

.admin-sidebar__user-info {
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.admin-sidebar__user-info strong {
    font-size: 0.8125rem;
    font-weight: 600;
    color: #fafaf9;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.admin-sidebar__user-info small {
    font-size: 0.7rem;
    color: #78716c;
    margin-top: 1px;
}

/* ---- Animations ---- */
@keyframes sidebarSlideIn {
    from {
        opacity: 0;
        transform: translateX(-12px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes sidebarFadeIn {
    from { opacity: 0; }
    to   { opacity: 1; }
}

@keyframes badgePulse {
    0%, 100% {
        box-shadow: 0 0 0 0 rgba(194, 142, 39, 0.45);
    }
    50% {
        box-shadow: 0 0 0 5px rgba(194, 142, 39, 0);
    }
}

/* Reduced motion */
@media (prefers-reduced-motion: reduce) {
    .admin-sidebar__link,
    .admin-sidebar__brand,
    .admin-sidebar__section,
    .admin-sidebar__user,
    .admin-sidebar__badge {
        animation: none !important;
        transition: none !important;
    }
}
</style>
<link rel="stylesheet" href="{{ asset('css/admin-sidebar.css') }}">

<aside class="admin-sidebar hidden lg:flex lg:flex-col lg:w-64 flex-shrink-0">
    {{-- Brand --}}
    <div class="admin-sidebar__brand">
        ALNAY<span>.ID</span>
    </div>

    {{-- Nav: Event & Peserta --}}
    <nav class="admin-sidebar__nav" aria-label="Event dan peserta">
                <p class="admin-sidebar__section">Event & Peserta</p>

        {{-- 1. Verifikasi Pendaftaran Peserta — SELALU tampil --}}
    <a class="admin-sidebar__link {{ ($active ?? '') === 'dashboard' ? 'is-active' : '' }}"
    href="{{ route('admin.dashboard', $event?->id) }}"
    style="--i: 0">
        <span class="admin-sidebar__step">1</span>
        <span class="admin-sidebar__label">Verifikasi Pendaftaran Peserta</span>

        @if(!empty($menungguReview) && $menungguReview > 0)
            <b class="admin-sidebar__badge">{{ $menungguReview }}</b>
        @endif
    </a>

    {{-- 2. Form Pendaftaran Event — SELALU tampil --}}
    <a class="admin-sidebar__link {{ ($active ?? '') === 'form-builder' ? 'is-active' : '' }}"
    href="{{ isset($event) ? route('admin.form-builder', $event->id) : route('admin.form-builder') }}"
    style="--i: 1">
        <span class="admin-sidebar__step">2</span>
        <span class="admin-sidebar__label">Form Pendaftaran Event</span>
    </a>

    {{-- 3. Kelola Event — SELALU tampil --}}
    <a class="admin-sidebar__link {{ in_array($active ?? '', ['events-create', 'events-index']) ? 'is-active' : '' }}"
    href="{{ route('admin.events.index') }}"
    style="--i: 2">
        <span class="admin-sidebar__step">3</span>
        <span class="admin-sidebar__label">Kelola Event</span>
    </a>

    {{-- 4. Aktivasi Akun Peserta — SELALU tampil --}}
    <a class="admin-sidebar__link {{ ($active ?? '') === 'activation' ? 'is-active' : '' }}"
    href="{{ isset($event) ? route('admin.activation', $event->id) : '#' }}"
    style="--i: 3">
        <span class="admin-sidebar__step">4</span>
        <span class="admin-sidebar__label">Aktivasi Akun Peserta</span>
    </a>

    {{-- 5. Kelola Peserta Tampil — SELALU tampil --}}
    <a class="admin-sidebar__link {{ ($active ?? '') === 'peserta-tampil' ? 'is-active' : '' }}"
    href="{{ isset($event) ? route('admin.peserta-tampil', $event->id) : '#' }}"
    style="--i: 4">
                <span class="admin-sidebar__step">5</span>
        <span class="admin-sidebar__label">Kelola Peserta Tampil</span>
    </a>
    </nav>

    {{-- Nav: Voting --}}
    <nav class="admin-sidebar__nav" aria-label="Voting">
        <p class="admin-sidebar__section">Voting</p>

        {{-- 6. Pengaturan Event & Voting --}}
        <a class="admin-sidebar__link {{ ($active ?? '') === 'voting-settings' ? 'is-active' : '' }}"
           href="{{ isset($event) ? route('admin.voting.settings', $event->id) : '#' }}"
           style="--i: 5">
            <span class="admin-sidebar__step">6</span>
            <span class="admin-sidebar__label">Pengaturan Event &amp; Voting</span>
        </a>

        {{-- 7. Daftar Voter --}}
        <a class="admin-sidebar__link {{ ($active ?? '') === 'voters' ? 'is-active' : '' }}"
           href="{{ isset($event) ? route('admin.voters', $event->id) : '#' }}"
           style="--i: 6">
            <span class="admin-sidebar__step">7</span>
            <span class="admin-sidebar__label">Daftar Voter</span>
        </a>

        {{-- 8. Leaderboard Suara Voting --}}
        <a class="admin-sidebar__link {{ ($active ?? '') === 'leaderboard' ? 'is-active' : '' }}"
           href="{{ isset($event) ? route('admin.leaderboard', $event->id) : '#' }}"
           style="--i: 7">
            <span class="admin-sidebar__step">8</span>
            <span class="admin-sidebar__label">Leaderboard Suara Voting</span>
        </a>

        {{-- 9. Profile --}}
        <a class="admin-sidebar__link {{ ($active ?? '') === 'profile' ? 'is-active' : '' }}"
           href="{{ route('admin.profile') }}"
           style="--i: 8">
            <span class="admin-sidebar__step">9</span>
            <span class="admin-sidebar__label">Profile</span>
        </a>
    </nav>

    {{-- Spacer supaya user card nempel di bawah --}}
    <div class="admin-sidebar__spacer"></div>
    {{-- User Card --}}
    @php
        $adminName = session('admin_name', 'MAS RADEN');
        $initials = collect(explode(' ', trim($adminName)))
            ->filter()
            ->map(fn ($word) => strtoupper(substr($word, 0, 1)))
            ->join('');
    @endphp
    <a href="{{ route('admin.profile') }}"
       class="admin-sidebar__user {{ ($active ?? '') === 'profile' ? 'is-active' : '' }}">
        <span class="admin-sidebar__avatar">{{ $initials }}</span>
        <div class="admin-sidebar__user-info">
            <strong>{{ $adminName }}</strong>
            <small>Admin Event</small>
        </div>
    </a>
</aside>