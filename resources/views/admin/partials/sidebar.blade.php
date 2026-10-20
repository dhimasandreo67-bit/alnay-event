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
    overflow-y: auto;
    overflow-x: hidden;
}

/* Scrollbar tipis, menyatu dengan tema gelap sidebar */
.admin-sidebar {
    scrollbar-width: thin;
    scrollbar-color: rgba(194, 142, 39, 0.35) transparent;
}

.admin-sidebar::-webkit-scrollbar {
    width: 6px;
}

.admin-sidebar::-webkit-scrollbar-track {
    background: transparent;
}

.admin-sidebar::-webkit-scrollbar-thumb {
    background: rgba(194, 142, 39, 0.35);
    border-radius: 9999px;
}

.admin-sidebar::-webkit-scrollbar-thumb:hover {
    background: rgba(194, 142, 39, 0.55);
}

/* ---- Brand ---- */
.admin-sidebar__brand {
    padding: 1.05rem 1.1rem 0.85rem;
    font-size: 1.05rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    color: #fafaf9;
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    animation: sidebarFadeIn 0.45s ease both;
    flex-shrink: 0;
}

.admin-sidebar__brand span {
    color: #C28E27;
}

/* ---- Section label ---- */
.admin-sidebar__section {
    margin: 0.85rem 1.1rem 0.35rem;
    font-size: 0.6rem;
    font-weight: 600;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #78716c;
    animation: sidebarFadeIn 0.4s ease both;
    animation-delay: 0.05s;
}

/* ---- Nav links ---- */
.admin-sidebar__nav {
    display: flex;
    flex-direction: column;
    gap: 1px;
    padding: 0 0.55rem;
    flex-shrink: 0;
}

.admin-sidebar__link {
    position: relative;
    display: flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.44rem 0.65rem;
    border-radius: 0.45rem;
    font-size: 0.76rem;
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

.admin-sidebar__label {
    flex: 1;
    line-height: 1.25;
    min-width: 0;
}

/* ---- Badge notifikasi ---- */
.admin-sidebar__badge {
    flex-shrink: 0;
    min-width: 1.1rem;
    height: 1.1rem;
    padding: 0 0.32rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 9999px;
    font-size: 0.62rem;
    font-weight: 700;
    background: #C28E27;
    color: #1c1917;
    animation: badgePulse 2s ease-in-out infinite;
}

/* ---- Spacer ---- */
.admin-sidebar__spacer {
    flex: 1;
    min-height: 0.5rem;
}

/* ---- User card ---- */
.admin-sidebar__user {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    margin: 0.6rem;
    padding: 0.6rem;
    border-radius: 0.65rem;
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
    flex-shrink: 0;
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
    width: 2rem;
    height: 2rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 9999px;
    background: linear-gradient(135deg, #C28E27, #a06f1a);
    color: #1c1917;
    font-size: 0.7rem;
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
    font-size: 0.78rem;
    font-weight: 600;
    color: #fafaf9;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.admin-sidebar__user-info small {
    font-size: 0.66rem;
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
    @php
        $currentStaf = \App\Models\AkunStaf::find(session('admin_id'));
        $isSuperAdmin = (bool) $currentStaf?->isSuperAdmin();

        // 1. Cek jika controller mengirim $event, pastikan admin ini PUNYA akses ke event tersebut
        if (isset($event) && $event !== null) {
            if (!$currentStaf->hasEventAccess($event->id)) {
                $event = null; // Jika tidak punya akses, batalkan $event tersebut
            }
        } else {
            // Pastikan $event selalu terdefinisi walau controller tidak mengirimkannya
            $event = null;
        }

        // 2. Jika $event kosong atau tidak valid, cari event yang benar-benar diizinkan
        if (!$event) {
            if (session()->has('active_event_id') && $currentStaf?->hasEventAccess(session('active_event_id'))) {
                $event = \App\Models\Event::find(session('active_event_id'));
            } elseif ($currentStaf?->last_active_event_id && $currentStaf->hasEventAccess($currentStaf->last_active_event_id)) {
                $event = $currentStaf->lastActiveEvent;
            } elseif (!$isSuperAdmin) {
                $event = $currentStaf?->allowedEvents()->first();
            } else {
                $event = \App\Models\Event::first();
            }

            // Simpan ke session agar konsisten
            if ($event) {
                session(['active_event_id' => $event->id]);
            }
        }
    @endphp

    {{-- Brand --}}
    <div class="admin-sidebar__brand">
        ALNAY<span>.ID</span>
    </div>

    {{-- Nav: Event & Peserta --}}
    <nav class="admin-sidebar__nav" aria-label="Event dan peserta">
        <p class="admin-sidebar__section">Event & Peserta</p>

        {{-- Verifikasi Pendaftaran Peserta --}}
        @if ($currentStaf?->hasPageAccess('dashboard'))
        <a class="admin-sidebar__link {{ ($active ?? '') === 'dashboard' ? 'is-active' : '' }}"
           href="{{ isset($event) ? route('admin.dashboard', $event->id) : route('admin.events.index') }}"
           style="--i: 0">
            <span class="admin-sidebar__label">Verifikasi Pendaftaran Peserta</span>

            @if(!empty($menungguReview) && $menungguReview > 0)
                <b class="admin-sidebar__badge">{{ $menungguReview }}</b>
            @endif
        </a>
        @endif

        {{-- Form Pendaftaran Event --}}
        @if ($currentStaf?->hasPageAccess('form-builder'))
        <a class="admin-sidebar__link {{ ($active ?? '') === 'form-builder' ? 'is-active' : '' }}"
           href="{{ isset($event) ? route('admin.form-builder', $event->id) : route('admin.form-builder') }}"
           style="--i: 1">
            <span class="admin-sidebar__label">Form Pendaftaran Event</span>
        </a>
        @endif

        {{-- Kelola Event --}}
        @if ($currentStaf?->hasPageAccess('events-index'))
        <a class="admin-sidebar__link {{ in_array($active ?? '', ['events-create', 'events-index']) ? 'is-active' : '' }}"
           href="{{ route('admin.events.index') }}"
           style="--i: 2">
            <span class="admin-sidebar__label">Kelola Event</span>
        </a>
        @endif

        {{-- Aktivasi Akun Peserta --}}
        @if ($currentStaf?->hasPageAccess('activation'))
        <a class="admin-sidebar__link {{ ($active ?? '') === 'activation' ? 'is-active' : '' }}"
           href="{{ isset($event) ? route('admin.activation', $event->id) : route('admin.events.index') }}"
           style="--i: 3">
            <span class="admin-sidebar__label">Aktivasi Akun Peserta</span>
        </a>
        @endif

        {{-- Kelola Peserta Tampil --}}
        @if ($currentStaf?->hasPageAccess('peserta-tampil'))
        <a class="admin-sidebar__link {{ ($active ?? '') === 'peserta-tampil' ? 'is-active' : '' }}"
           href="{{ isset($event) ? route('admin.peserta-tampil', $event->id) : route('admin.events.index') }}"
           style="--i: 4">
            <span class="admin-sidebar__label">Kelola Peserta Tampil</span>
        </a>
        @endif
    </nav>

    {{-- Nav: Voting --}}
    <nav class="admin-sidebar__nav" aria-label="Voting">
        <p class="admin-sidebar__section">Voting</p>

        {{-- Pengaturan Event & Voting --}}
        @if ($currentStaf?->hasPageAccess('voting-settings'))
        <a class="admin-sidebar__link {{ ($active ?? '') === 'voting-settings' ? 'is-active' : '' }}"
           href="{{ isset($event) ? route('admin.voting.settings', $event->id) : route('admin.events.index') }}"
           style="--i: 5">
            <span class="admin-sidebar__label">Pengaturan Event &amp; Voting</span>
        </a>
        @endif

        {{-- Daftar Voter --}}
        @if ($currentStaf?->hasPageAccess('voters'))
        <a class="admin-sidebar__link {{ ($active ?? '') === 'voters' ? 'is-active' : '' }}"
           href="{{ isset($event) ? route('admin.voters', $event->id) : route('admin.events.index') }}"
           style="--i: 6">
            <span class="admin-sidebar__label">Daftar Voter</span>
        </a>
        @endif

        {{-- Leaderboard Suara Voting --}}
        @if ($currentStaf?->hasPageAccess('leaderboard'))
        <a class="admin-sidebar__link {{ ($active ?? '') === 'leaderboard' ? 'is-active' : '' }}"
           href="{{ isset($event) ? route('admin.leaderboard', $event->id) : route('admin.events.index') }}"
           style="--i: 7">
            <span class="admin-sidebar__label">Leaderboard Suara Voting</span>
        </a>
        @endif

        {{-- Profile — selalu tampil, semua admin boleh urus profil sendiri --}}
    </nav>

    {{-- Nav: Administrasi — khusus Super Admin --}}
    @if ($isSuperAdmin)
    <nav class="admin-sidebar__nav" aria-label="Administrasi">
        <p class="admin-sidebar__section">Administrasi</p>

        <a class="admin-sidebar__link {{ ($active ?? '') === 'staf' ? 'is-active' : '' }}"
           href="{{ route('admin.staf.index') }}"
           style="--i: 9">
            <span class="admin-sidebar__label">Kelola Admin</span>
        </a>
    </nav>
    @endif

    {{-- Spacer supaya user card nempel di bawah selama kontennya belum penuh --}}
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
            <small>{{ $isSuperAdmin ? 'Super Admin' : 'Admin Event' }}</small>
        </div>
    </a>
</aside>