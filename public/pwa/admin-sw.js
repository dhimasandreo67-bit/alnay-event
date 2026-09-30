const CACHE_NAME = 'alnay-admin-shell-v1';
const OFFLINE_URL = '/pwa/offline.html';

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => cache.add(OFFLINE_URL))
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => Promise.all(
            cacheNames
                .filter((cacheName) => cacheName.startsWith('alnay-admin-shell-') && cacheName !== CACHE_NAME)
                .map((cacheName) => caches.delete(cacheName))
        ))
    );
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    if (event.request.method !== 'GET' || event.request.mode !== 'navigate') {
        return;
    }

    event.respondWith(
        fetch(event.request).catch(() => caches.match(OFFLINE_URL))
    );
});