<link rel="stylesheet" href="<?php echo e(asset('css/admin-sidebar.css')); ?>">
<style>
    * {
        font-family: "Inter";
    }
</style>
<aside class="admin-sidebar hidden lg:block lg:w-64 flex-shrink-0">
    <div class="admin-sidebar__brand">ALNAY<span>.ID</span></div>

    <nav class="admin-sidebar__nav" aria-label="Event dan peserta">
        <p class="admin-sidebar__section">Event & Peserta</p>
        
        <a class="<?php echo e(($active ?? '') === 'dashboard' ? 'is-active' : ''); ?>" 
        href="<?php echo e(route('admin.dashboard', $event?->id)); ?>">
            <span class="admin-sidebar__step">1</span>
            <span>Verifikasi Pendaftaran Peserta</span>
            
            
            <?php if(!empty($menungguReview) && $menungguReview > 0): ?>
                <b class="admin-sidebar__badge"><?php echo e($menungguReview); ?></b>
            <?php endif; ?>
        </a>

        <a class="<?php echo e(($active ?? '') === 'form-builder' ? 'is-active' : ''); ?>" 
   href="<?php echo e(isset($event) ? route('admin.form-builder', $event->id) : route('admin.form-builder')); ?>">
            <span class="admin-sidebar__step">2</span>
            <span>Form Pendaftaran Event</span>
        </a>

        <a class="<?php echo e(in_array($active ?? '', ['events-create', 'events-index']) ? 'is-active' : ''); ?>"
        href="<?php echo e(route('admin.events.index')); ?>">
            <span class="admin-sidebar__step">3</span>
            <span>Kelola Event</span>
        </a>

        <a class="<?php echo e(($active ?? '') === 'activation' ? 'is-active' : ''); ?>"
        href="<?php echo e(isset($event) ? route('admin.activation', $event->id) : '#'); ?>">
            <span class="admin-sidebar__step">4</span>
            <span>Aktivasi Akun Peserta</span>
        </a>
    </nav>

    <nav class="admin-sidebar__nav" aria-label="Voting">
        <p class="admin-sidebar__section">Voting</p>

        
        <a class="<?php echo e(($active ?? '') === 'voting-settings' ? 'is-active' : ''); ?>"
        href="<?php echo e(isset($event) ? route('admin.voting.settings', $event->id) : '#'); ?>">
            <span class="admin-sidebar__step">4</span>
            <span>Pengaturan Event & Voting</span>
        </a>

        
        <a class="<?php echo e(($active ?? '') === 'voters' ? 'is-active' : ''); ?>"
        href="<?php echo e(isset($event) ? route('admin.voters', $event->id) : '#'); ?>">
            <span class="admin-sidebar__step">5</span>
            <span>Daftar Voter</span>
        </a>

        
        <a class="<?php echo e(($active ?? '') === 'leaderboard' ? 'is-active' : ''); ?>"
        href="<?php echo e(isset($event) ? route('admin.leaderboard', $event->id) : '#'); ?>">
            <span class="admin-sidebar__step">6</span>
            <span>Leaderboard Suara Voting</span>
        </a>

        <a class="<?php echo e(($active ?? '') === 'profile' ? 'is-active' : ''); ?>"
        href="<?php echo e(route('admin.profile')); ?>">
            <span class="admin-sidebar__step">4</span>
            <span>Profile</span>
        </a>
    </nav>

    
    <?php
        $adminName = session('admin_name', 'MAS RADEN');
        $initials = collect(explode(' ', trim($adminName)))
            ->filter()
            ->map(fn($word) => strtoupper(substr($word, 0, 1)))
            ->join('');
    ?>
    <a href="<?php echo e(route('admin.profile')); ?>" class="admin-sidebar__user <?php echo e(($active ?? '') === 'profile' ? 'is-active' : ''); ?>">
        <span class="admin-sidebar__avatar"><?php echo e($initials); ?></span>
        <div>
            <strong><?php echo e($adminName); ?></strong>
            <small>Admin Event</small>
        </div>
    </a>
</aside>
<?php /**PATH D:\alnayevent\resources\views/admin/partials/sidebar.blade.php ENDPATH**/ ?>