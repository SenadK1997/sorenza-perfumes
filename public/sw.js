/**
 * Sorénza — minimal service worker.
 * Its main job is to make the site "installable" as a PWA.
 * We deliberately keep caching very conservative so admin/Livewire updates
 * are never served stale.
 */
const CACHE = 'sorenza-shell-v1';

// Static shell we're happy to cache
const SHELL = [
  '/favicon.png',
  '/favicon-32x32.png',
  '/apple-touch-icon.png',
  '/android-chrome-192x192.png',
  '/android-chrome-512x512.png',
  '/manifest.json',
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE).then((c) => c.addAll(SHELL)).catch(() => {})
  );
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(keys.filter((k) => k !== CACHE).map((k) => caches.delete(k)))
    )
  );
  self.clients.claim();
});

self.addEventListener('fetch', (event) => {
  const req = event.request;

  // Only GET requests, same-origin
  if (req.method !== 'GET' || new URL(req.url).origin !== self.location.origin) return;

  // Skip admin/seller panels and livewire so nothing there is served stale.
  const path = new URL(req.url).pathname;
  if (path.startsWith('/admin')
      || path.startsWith('/seller')
      || path.startsWith('/livewire')
      || path.startsWith('/api')) return;

  // Cache-first for static assets in build/, images, favicons.
  if (/\.(png|jpe?g|webp|svg|gif|ico|woff2?|ttf|css|js)$/i.test(path)
      || path.startsWith('/build/')
      || path.startsWith('/storage/images/')) {
    event.respondWith(
      caches.match(req).then((cached) =>
        cached || fetch(req).then((res) => {
          if (res && res.status === 200) {
            const clone = res.clone();
            caches.open(CACHE).then((c) => c.put(req, clone)).catch(() => {});
          }
          return res;
        }).catch(() => cached)
      )
    );
    return;
  }

  // For HTML pages: network-first with offline fallback to cached homepage
  if (req.headers.get('accept') && req.headers.get('accept').includes('text/html')) {
    event.respondWith(
      fetch(req).catch(() => caches.match(req).then((r) => r || caches.match('/')))
    );
  }
});
