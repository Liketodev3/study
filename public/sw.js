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
    fetch(e.request).then((response) => {
      return caches.open(cacheName + cacheVersion).then((cache) => {
        if ((e.request.method == 'GET') && (e.request.url.startsWith('http') || e.request.url.startsWith('https'))) {
          cache.put(e.request, response.clone());
        }
        return response;
      });
    }).catch(async function () {

      if (e.request.method == 'GET') {
        return caches.match(e.request).then(function (res) {
          if (res === undefined) {
            if(e.request.mode === 'navigate'){
              return caches.match(webRootUrl + 'offline.html');
            }
            return;
          }
          return res;
        });
      }
    })
  );
});
