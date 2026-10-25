const CACHE = 'marea-celestial-v2';
// El scope del worker apunta a la raíz real (funciona en local y en hosting)
const SCOPE = self.registration.scope;
const RUTAS = [
  'index.php',
  'productos.php',
  'nosotros.php',
  'assets/css/bootstrap.min.css',
  'assets/css/plumage.css',
  'assets/js/jquery.min.js',
  'assets/js/bootstrap.bundle.min.js',
  'img/logo.jpg',
  'img/favicon-192.png'
].map(r => SCOPE + r);

self.addEventListener('install', (e) => {
  e.waitUntil(
    caches.open(CACHE)
      .then((c) => c.addAll(RUTAS))
      .then(() => self.skipWaiting())
  );
});

self.addEventListener('activate', (e) => {
  e.waitUntil(
    caches.keys()
      .then((keys) => Promise.all(keys.filter((k) => k !== CACHE).map((k) => caches.delete(k))))
      .then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', (e) => {
  if (e.request.method !== 'GET') return;
  e.respondWith(
    caches.match(e.request).then((hit) => {
      if (hit) return hit;
      return fetch(e.request)
        .then((resp) => {
          const clon = resp.clone();
          if (resp.ok && e.request.url.startsWith(SCOPE)) {
            caches.open(CACHE).then((c) => c.put(e.request, clon));
          }
          return resp;
        })
        .catch(() => caches.match(SCOPE + 'index.php'));
    })
  );
});