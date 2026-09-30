<meta name="theme-color" content="#171512">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<link rel="manifest" href="{{ asset('site.webmanifest') }}">
<link rel="icon" href="{{ asset('icons/admin-app.svg') }}" type="image/svg+xml">
<link rel="apple-touch-icon" href="{{ asset('icons/admin-app.svg') }}">
<script>
    if ('serviceWorker' in navigator && window.isSecureContext) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register(@json(asset('admin/admin-sw.js')))
                .catch((error) => console.warn('Admin service worker registration failed:', error));
        });
    }
</script>