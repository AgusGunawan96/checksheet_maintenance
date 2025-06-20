const CACHE_NAME = 'checksheet-cache-v1';
const urlsToCache = [
    '/',
    '/checksheet_maintenance/login',
    '/assets/css/app.css',
    '/assets/js/pace.min.js',
    '/assets/images/favicon-32x32.png',
    '/assets/images/logo.jpg',
    '/assets/images/login-images/login-front-img.png'
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                console.log('Opened cache');
                return cache.addAll(urlsToCache);
            })
    );
});

self.addEventListener('fetch', (event) => {
    event.respondWith(
        caches.match(event.request)
            .then((response) => {
                return response || fetch(event.request);
            })
    );
});

self.addEventListener('activate', (event) => {
    const cacheWhitelist = [CACHE_NAME];
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheWhitelist.indexOf(cacheName) === -1) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});
