<?php
/* Footer compartido - Marea Celestial
   Cierra la pagina: ola + footer + carrito flotante + scripts + </body></html>.
   Para JS propio de la pagina, definir $mc_scripts antes de incluir este archivo. */
if (!function_exists('url_absoluta')) { require_once __DIR__ . "/../config.php"; }
$wa = preg_replace("/\D/", "", WHATSAPP);
if (!defined("MC_INSTAGRAM")) define("MC_INSTAGRAM", "marea_celestial");
$ig = MC_INSTAGRAM;
$mc_scripts = $mc_scripts ?? "";
?>
    <!-- Ola dorada que baja al footer -->
    <div class="ola-separadora ola-separadora--dorado" aria-hidden="true">
        <svg viewBox="0 0 1440 100" preserveAspectRatio="none">
            <path d="M0,0 L1440,0 L1440,44 C1180,84 880,52 620,70 C380,86 160,50 0,80 Z"></path>
        </svg>
    </div>

    <footer class="footer-navy relative z-10">
        <div class="container py-12">
            <div class="row g-5">
                <div class="col-12 col-lg-4">
                    <a href="index.php" class="d-inline-flex align-items-center gap-3 mb-3">
                        <img src="img/logo.jpg" alt="Marea Celestial"
                             class="rounded-circle footer-navy__logo"
                             style="width:52px;height:52px;object-fit:cover"
                             onerror="this.style.display='none'">
                        <span class="footer-navy__titulo">MAREA CELESTIAL</span>
                    </a>
                    <p class="mb-4" style="max-width:34ch">
                        Bolsos, carteras y monederos en ecocuero. Diseñados para acompañarte
                        en cada ola, con envío a todo el país.
                    </p>
                    <p class="footer-navy__firma mb-0" style="font-size:0.72rem">✦ TU ESTILO TAMBIÉN BRILLA ✦</p>
                </div>

                <div class="col-6 col-lg-3">
                    <h3 class="footer-navy__titulo mb-3">Navegación</h3>
                    <a class="footer-navy__link" href="index.php">Inicio</a>
                    <a class="footer-navy__link" href="productos.php">Catálogo</a>
                    <a class="footer-navy__link" href="colecciones.php">Colecciones</a>
                    <a class="footer-navy__link" href="nosotros.php">Nosotros</a>
                    <a class="footer-navy__link" href="contacto.php">Contacto</a>
                </div>

                <div class="col-6 col-lg-3">
                    <h3 class="footer-navy__titulo mb-3">Ayuda</h3>
                    <a class="footer-navy__link" href="nosotros.php#faq">Preguntas frecuentes</a>
                    <a class="footer-navy__link" href="nosotros.php#pagos">Cómo pagar</a>
                    <a class="footer-navy__link" href="nosotros.php#envios">Envíos</a>
                    <a class="footer-navy__link" href="nosotros.php#cambios">Cambios y devoluciones</a>
                </div>

                <div class="col-12 col-lg-2">
                    <h3 class="footer-navy__titulo mb-3">Seguinos</h3>
                    <a class="footer-navy__link d-flex align-items-center gap-2" href="https://www.instagram.com/<?= urlencode($ig) ?>" target="_blank" rel="noopener">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                        Instagram
                    </a>
                    <a class="footer-navy__link d-flex align-items-center gap-2" href="https://wa.me/<?= $wa ?>" target="_blank" rel="noopener">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        WhatsApp
                    </a>
                </div>
            </div>

            <hr class="footer-navy__separador my-5">

            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2" style="font-size:0.78rem">
                <span>&copy; <?= date("Y") ?> Marea Celestial. Todos los derechos reservados.</span>
                <span style="opacity:0.75">Hecho con amor en Argentina 🇦🇷</span>
            </div>
        </div>
    </footer>

    <?php require_once __DIR__ . "/carrito.php"; ?>

    <button id="btnVolverArriba" aria-label="Volver arriba"
            style="position:fixed;bottom:24px;left:24px;z-index:999;width:46px;height:46px;border-radius:50%;border:1px solid var(--border-strong);background:var(--bg-card);color:var(--gold-dark);display:none;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 8px 24px rgba(90, 74, 58, 0.18)">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg>
    </button>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script>
        // Service worker (PWA)
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => { navigator.serviceWorker.register('sw.js').catch(() => {}); });
        }
        // Header glass al hacer scroll
        (function () {
            const header = document.getElementById('mainHeader');
            if (!header) return;
            window.addEventListener('scroll', () => {
                header.classList.toggle('glass-strong', window.scrollY > 50);
                header.style.boxShadow = window.scrollY > 50 ? '0 4px 20px rgba(90, 74, 58, 0.12)' : 'none';
            }, { passive: true });
        })();
        // Botón volver arriba
        (function () {
            const arriba = document.getElementById('btnVolverArriba');
            if (!arriba) return;
            window.addEventListener('scroll', () => {
                arriba.style.display = window.scrollY > 600 ? 'flex' : 'none';
            }, { passive: true });
            arriba.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
        })();
        // Instalación PWA
        (function () {
            let promptInstalacion = null;
            const btn = document.getElementById('btnInstalar');
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                promptInstalacion = e;
                if (btn) btn.style.display = 'inline-flex';
            });
            if (btn) btn.addEventListener('click', async () => {
                if (!promptInstalacion) return;
                promptInstalacion.prompt();
                await promptInstalacion.userChoice;
                promptInstalacion = null;
                btn.style.display = 'none';
            });
            window.addEventListener('appinstalled', () => { if (btn) btn.style.display = 'none'; });
        })();
        // Animaciones de entrada
        if ('IntersectionObserver' in window) {
            const obs = new IntersectionObserver((entries) => {
                entries.forEach(e => {
                    if (e.isIntersecting) { e.target.classList.add('anim-visible'); obs.unobserve(e.target); }
                });
            }, { threshold: 0.1, rootMargin: '50px' });
            document.querySelectorAll('.animate-fade-up').forEach(el => obs.observe(el));
        }
    </script>
    <?= $mc_scripts ?>
</body>
</html>
