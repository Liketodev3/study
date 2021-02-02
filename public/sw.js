importScripts('config.js');

self.addEventListener('install', (e) => {
  e.waitUntil(
    caches.open(cacheName + cacheVersion).then((cache) => {
      return cache.addAll(contentToCache);
    })
  );
});

self.addEventListener('activate', function (event) {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(thisCacheName => {
          if (thisCacheName !== cacheName + cacheVersion) {
            return caches.delete(thisCacheName);
          }
        })
      );
    })
  )
});


self.addEventListener('fetch', (e) => {
  e.respondWith(
    caches.match(e.request).then((r) => {
      console.log('[Service Worker] Fetching resource: ' + e.request.url);
      return r || fetch(e.request).then((response) => {
        return caches.open(cacheName+cacheVersion).then((cache) => {
          console.log('[Service Worker] Caching new resource: ' + e.request.url);
          if ((e.request.method == 'GET') && (e.request.mode === 'navigate')) {
            cache.put(e.request, response.clone());
          }
          return response;
        });
      });
    })
  );
});
