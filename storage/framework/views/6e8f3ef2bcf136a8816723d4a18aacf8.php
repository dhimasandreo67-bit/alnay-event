
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes, viewport-fit=cover">
<meta name="theme-color" content="#ba8320">
<meta name="msapplication-TileColor" content="#ba8320">


<meta name="mobile-web-app-capable" content="yes">
<meta name="application-name" content="ALNAY.ID Admin">


<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="ALNAY Admin">


<link rel="manifest" href="<?php echo e(asset('site.webmanifest')); ?>">
<link rel="icon" href="<?php echo e(asset('icons/admin-app.svg')); ?>" type="image/svg+xml">
<link rel="apple-touch-icon" href="<?php echo e(asset('icons/admin-app.svg')); ?>">
<meta name="format-detection" content="telephone=no">


<link rel="stylesheet" href="<?php echo e(asset('css/admin-toast.css')); ?>">


<link rel="stylesheet" href="<?php echo e(asset('css/admin-modal.css')); ?>">


<?php if(session('success') || session('error') || session('warning') || session('info')): ?>
    <script id="flash-data" type="application/json">
    {
        "success": <?php echo json_encode(session('success')); ?>,
        "error": <?php echo json_encode(session('error')); ?>,
        "warning": <?php echo json_encode(session('warning')); ?>,
        "info": <?php echo json_encode(session('info')); ?>

    }
    </script>
<?php endif; ?>


<script src="<?php echo e(asset('js/admin-toast.js')); ?>" defer></script>


<script src="<?php echo e(asset('js/admin-modal.js')); ?>" defer></script>


<script>
if ('serviceWorker' in navigator && window.isSecureContext) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register("<?php echo e(asset('pwa/admin-sw.js')); ?>")
            .then((registration) => {
                console.log('ALNAY Admin SW registered:', registration.scope);
            })
            .catch((error) => {
                console.warn('Admin service worker registration failed:', error);
            });
    });
}
</script>

<style>
:root {
    --sat: env(safe-area-inset-top);
    --sab: env(safe-area-inset-bottom);
    --sal: env(safe-area-inset-left);
    --sar: env(safe-area-inset-right);
}

body {
    padding-top: var(--sat);
    padding-bottom: var(--sab);
    padding-left: var(--sal);
    padding-right: var(--sar);
}
</style><?php /**PATH D:\alnayevent\resources\views/admin/partials/pwa-head.blade.php ENDPATH**/ ?>