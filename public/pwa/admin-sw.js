// public/pwa/admin-sw.js
const CACHE_NAME = 'alnay-admin-v1';
const OFFLINE_URL = '/pwa/offline.html';

// Aset statis yang wajib di-cache saat install (app shell)
const STATIC_ASSETS = [
    '/',
    '/admin',
    '/admin/login',
    OFFLINE_URL,
    // Tambahkan path CSS/JS utama jika ada yang di-compile
    // '/css/app.css',
    // '/js/app.js',
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
                console.warn('[SW] Pre-cache failed:', err);
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

    // Abaikan request ke Laravel Debugbar / Boost
    if (url.pathname.startsWith('/_debugbar') || 
        url.pathname.startsWith('/_boost') ||
        url.pathname.startsWith('/livewire')) {
        return;
    }

    // Strategi berbeda berdasarkan tipe request
    if (request.destination === 'document') {
        // HTML: Network First, fallback ke cache (penting untuk data dinamis)
        event.respondWith(
            fetch(request)
                .then((response) => {
                    // Clone & simpan ke cache untuk offline
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
                    // Fallback: placeholder untuk gambar
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