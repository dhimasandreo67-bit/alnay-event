// public/pwa/admin-sw.js
const CACHE_NAME = 'alnay-admin-v2'; // ← Upgrade versi agar cache lama terhapus
const OFFLINE_URL = '/pwa/offline.html';

// Hanya cache aset yang TIDAK butuh autentikasi
const STATIC_ASSETS = [
    OFFLINE_URL,
    '/css/admin-sidebar.css',
    '/css/admin-toast.css',
    '/css/admin-modal.css',
    '/js/admin-toast.js',
    '/js/admin-modal.js',
];

// ==========================================
// INSTALL: Pre-cache app shell
// ==========================================
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                console.log('[SW] Pre-caching app shell');
                return cache.addAll(STATIC_ASSETS);
            })
            .catch((err) => {
                console.warn('[SW] Pre-cache failed (non-critical):', err);
            })
    );
    self.skipWaiting();
});

// ==========================================
// ACTIVATE: Hapus cache lama
// ==========================================
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames
                    .filter((name) => name.startsWith('alnay-admin-') && name !== CACHE_NAME)
                    .map((name) => {
                        console.log('[SW] Deleting old cache:', name);
                        return caches.delete(name);
                    })
            );
        })
    );
    self.clients.claim();
});

// ==========================================
// FETCH: Strategi caching hybrid
// ==========================================
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Abaikan request non-GET dan request ke domain lain
    if (request.method !== 'GET') return;
    if (url.origin !== location.origin) return;

    // Abaikan request ke Laravel Debugbar / Boost / Livewire
    if (url.pathname.startsWith('/_debugbar') ||
        url.pathname.startsWith('/_boost') ||
        url.pathname.startsWith('/livewire')) {
        return;
    }

    // Strategi berbeda berdasarkan tipe request
    if (request.destination === 'document') {
        // HTML: Network First, fallback ke cache
        event.respondWith(
            fetch(request)
                .then((response) => {
                    if (response && response.status === 200) {
                        const responseClone = response.clone();
                        caches.open(CACHE_NAME).then((cache) => {
                            cache.put(request, responseClone);
                        });
                    }
                    return response;
                })
                .catch(() => {
                    return caches.match(request).then((cached) => {
                        return cached || caches.match(OFFLINE_URL);
                    });
                })
        );
    } else if (
        request.destination === 'image' ||
        request.destination === 'style' ||
        request.destination === 'script' ||
        request.destination === 'font'
    ) {
        // Aset statis: Cache First (cepat & hemat bandwidth)
        event.respondWith(
            caches.match(request).then((cached) => {
                if (cached) return cached;

                return fetch(request).then((response) => {
                    if (response && response.status === 200) {
                        const responseClone = response.clone();
                        caches.open(CACHE_NAME).then((cache) => {
                            cache.put(request, responseClone);
                        });
                    }
                    return response;
                }).catch(() => {
                    if (request.destination === 'image') {
                        return new Response('', {
                            status: 404,
                            statusText: 'Image not found',
                            headers: { 'Content-Type': 'text/plain' }
                        });
                    }
                });
            })
        );
    } else {
        // Request lain: Network First
        event.respondWith(
            fetch(request)
                .catch(() => caches.match(request))
        );
    }
});

// ==========================================
// MESSAGE: Trigger manual cache update
// ==========================================
self.addEventListener('message', (event) => {
    if (event.data === 'SKIP_WAITING') {
        self.skipWaiting();
    }
});