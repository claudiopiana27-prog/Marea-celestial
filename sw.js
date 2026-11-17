/* Service worker - Marea Celestial (sitio PHP)
   - HTML: network-first (siempre intenta traer la version nueva)
   - Assets: cache-first
   - Nunca cachea /admin/ ni /api/
   - Sin conexion: muestra la ultima copia de la pagina pedida o el inicio */
const CACHE = 'marea-celestial-v3';
const SCOPE = self.registration.scope;
const PRECACHE = [
  'index.php',
  'productos.php',
  'nosotros.php',
  'assets/css/bootstrap.min.css',
  'assets/css/plumage.css',
  'assets/css/marea-home.css',
  'assets/css/marea-tema.css',
  'assets/js/jquery.min.js',
  'assets/js/bootstrap.bundle.min.js',
  'img/logo.jpg',
  'img/favicon-192.png'
].map((r) => SCOPE + r);

const ES_ASSET = /\.(?:css|js|mjs|png|jpe?g|gif|webp|svg|ico|woff2?|ttf|otf|eot|mp4|webm)$/i;

self.addEventListener('install', (e) => {
  e.waitUntil(
    caches.open(CACHE)
      .then((c) => Promise.all(PRECACHE.map((u) => c.add(u).catch(() => {}))))
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
  const req = e.request;
  if (req.method !== 'GET') return;

  const url = new URL(req.url);
  if (url.origin !== self.location.origin) return;
  if (url.pathname.includes('/admin/') || url.pathname.includes('/api/')) return;

  const esNavegacion = req.mode === 'navigate' || req.destination === 'document';

  if (esNavegacion) {
    e.respondWith(
      fetch(req)
        .then((resp) => {
          const copia = resp.clone();
          caches.open(CACHE).then((c) => c.put(req, copia)).catch(() => {});
          return resp;
        })
        .catch(() => caches.match(req).then((hit) => hit || caches.match(SCOPE + 'index.php')))
    );
    return;
  }

  if (ES_ASSET.test(url.pathname)) {
    e.respondWith(
      caches.match(req).then((hit) => {
        if (hit) return hit;
        return fetch(req).then((resp) => {
          if (resp && resp.status === 200) {
            const copia = resp.clone();
            caches.open(CACHE).then((c) => c.put(req, copia)).catch(() => {});
          }
          return resp;
        });
      })
    );
  }
});
