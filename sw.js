const CACHE_NAME = 'lms-unsiq-v1';
const ASSETS = [
    '/project_lms/',
    '/project_lms/public/assets/css/style.css',
    '/project_lms/manifest.json'
];

self.addEventListener('install', (e) => {
    e.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(ASSETS);
        }).catch(err => console.log('SW Install Error', err))
    );
});

self.addEventListener('fetch', (e) => {
    // Hanya proses GET requests
    if (e.request.method !== 'GET') return;
    
    e.respondWith(
        fetch(e.request).catch(() => {
            return caches.match(e.request);
        })
    );
});
