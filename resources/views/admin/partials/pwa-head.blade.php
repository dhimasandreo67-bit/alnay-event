{{-- Meta tag untuk PWA & Mobile-Friendly --}}
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes, viewport-fit=cover">
<meta name="theme-color" content="#ba8320">
<meta name="msapplication-TileColor" content="#ba8320">

{{-- Mobile Web App Capabilities --}}
<meta name="mobile-web-app-capable" content="yes">
<meta name="application-name" content="ALNAY.ID Admin">

{{-- iOS Specific --}}
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="ALNAY Admin">

{{-- Manifest & Icons --}}
<link rel="manifest" href="{{ asset('site.webmanifest') }}">
<link rel="icon" href="{{ asset('icons/admin-app.svg') }}" type="image/svg+xml">
<link rel="apple-touch-icon" href="{{ asset('icons/admin-app.svg') }}">
<meta name="format-detection" content="telephone=no">

{{-- Toast Notification CSS & JS --}}
<link rel="stylesheet" href="{{ asset('css/admin-toast.css') }}">
<script src="{{ asset('js/admin-toast.js') }}" defer></script>

{{-- Custom Modal CSS & JS --}}
<link rel="stylesheet" href="{{ asset('css/admin-modal.css') }}">
<script src="{{ asset('js/admin-modal.js') }}" defer></script>

{{-- Flash data dari Laravel session --}}
@if (session('success') || session('error') || session('warning') || session('info'))
    <script id="flash-data" type="application/json">
        {
            "success": {!! json_encode(session('success')) !!},
            "error": {!! json_encode(session('error')) !!},
            "warning": {!! json_encode(session('warning')) !!},
            "info": {!! json_encode(session('info')) !!}
        }
    </script>
@endif

{{-- Service Worker Registration --}}
<script>
if ('serviceWorker' in navigator && window.isSecureContext) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register("{{ asset('pwa/admin-sw.js') }}")
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
</style>