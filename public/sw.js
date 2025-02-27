var cacheName = 'hello-pwa-v2';
var filesToCache = [
  '/',
  '/css/all.css',  // Đường dẫn đã tối ưu CSS
  '/js/all.js',    // Đường dẫn đã tối ưu JS
];

self.addEventListener('install', function(e) {
  e.waitUntil(
    caches.open(cacheName).then(function(cache) {
      return cache.addAll(filesToCache);
    })
  );
});

self.addEventListener('fetch', function(event) {
  event.respondWith(
    caches.match(event.request).then(function(response) {
      return response || fetch(event.request).then(function(networkResponse) {
        let responseClone = networkResponse.clone();
        caches.open('hello-pwa-v2').then(function(cache) {
          cache.put(event.request, responseClone);
        });
        return networkResponse;
      });
    })
  );
});

