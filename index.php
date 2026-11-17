<?php
require_once "config.php";

$mc_titulo      = "Marea Celestial | Accesorios Urbanos";
$mc_descripcion = "Marea Celestial: marroquinería urbana hecha para el día a día. Bolsos, carteras y riñoneras en cuero premium, con envíos a todo el país.";
$mc_og_titulo   = "Marea Celestial | Accesorios Urbanos";
$mc_og_imagen   = "img/og-image.jpg";
$mc_pagina      = "inicio";
$mc_body_class  = "mc-home";

/* Productos: se conservan las consultas y el markup de las cards existentes. */
$mcProductos  = [];
$mcHeroBolsos = [];
try {
    $pdo = conectar();
    $mcProductos = $pdo->query("
        SELECT p.*, c.categoria AS cat_nombre
        FROM productos p
        INNER JOIN categorias c ON c.id = p.id_categoria
        WHERE p.stock > 0
        ORDER BY p.id DESC
        LIMIT 6
    ")->fetchAll();

    $mcHeroBolsos = $pdo->query("
        SELECT p.id, p.producto, p.imagen
        FROM productos p
        WHERE p.stock > 0 AND p.imagen <> ''
        ORDER BY p.id_categoria, p.id
        LIMIT 3
    ")->fetchAll();
} catch (Throwable $e) {
    $mc_error_bd = $e->getMessage();
}

/* Header + nav compartidos: mismo diseño en todas las páginas. */
require_once "partials/header.php";
?>

    <main>
        <!-- 3. HERO SPLIT -->
        <section class="mc-hero" aria-labelledby="heroTitulo">
            <div class="mc-hero__left">
                <div class="mc-hero__sky" aria-hidden="true"></div>

                <svg class="mc-hero__moon" viewBox="0 0 200 200" aria-hidden="true">
                    <defs>
                        <linearGradient id="mcLuna" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0" stop-color="#EBD489"/>
                            <stop offset=".45" stop-color="#D4AF37"/>
                            <stop offset="1" stop-color="#A8811F"/>
                        </linearGradient>
                    </defs>
                    <path fill="url(#mcLuna)" d="M150 12a86 86 0 1 0 42 128 68 68 0 1 1-42-128z"/>
                    <g fill="url(#mcLuna)">
                        <path d="M146 44l4.6 12.4L163 61l-12.4 4.6L146 78l-4.6-12.4L129 61l12.4-4.6z"/>
                        <circle cx="118" cy="30" r="2"/>
                        <circle cx="172" cy="86" r="1.6"/>
                    </g>
                </svg>

                <div class="mc-hero__content">
                    <p class="mc-hero__eyebrow">
                        <span aria-hidden="true">✦</span> Nueva colección
                    </p>
                    <h1 id="heroTitulo" class="mc-hero__title">
                        Marea Celestial
                        <span>| Accesorios Urbanos</span>
                    </h1>
                    <p class="mc-hero__lead">Marroquinería urbana hecha para el día a día.</p>

                    <ul class="mc-tags" aria-label="Categorías">
                        <li>Bolsos</li>
                        <li>Carteras</li>
                        <li>Riñoneras</li>
                    </ul>

                    <a class="mc-hero__cta" href="productos.php">
                        Comprar ahora
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            <div class="mc-hero__right">
                <div class="mc-hero__sky" aria-hidden="true"></div>
                <figure class="mc-hero__figure">
                    <img class="mc-hero__photo"
                         src="img/og-image.jpg"
                         alt="Marea Celestial — accesorios de cuero urbano"
                         width="1200" height="1200"
                         fetchpriority="high">
                </figure>

                <?php if ($mcHeroBolsos): ?>
                <div class="mc-hero__bags" aria-label="Bolsos, carteras y riñoneras">
                    <?php
                    $mcPuntos = ["#0A1F44", "#00B4D8", "#D4AF37"];
                    foreach ($mcHeroBolsos as $i => $mb):
                    ?>
                    <span class="mc-hero__bag" style="--dot:<?= $mcPuntos[$i % 3] ?>">
                        <img src="<?= htmlspecialchars(imagen_thumb($mb["imagen"])) ?>"
                             alt="<?= htmlspecialchars($mb["producto"]) ?>"
                             loading="lazy"
                             onerror="this.style.display='none'">
                    </span>
                    <?php endforeach; ?>
                    <span class="mc-hero__bags-copy">
                        <strong>Tres esenciales</strong>
                        <span>Navy &middot; Turquesa &middot; Crema</span>
                    </span>
                </div>
                <?php endif; ?>
            </div>

            <svg class="mc-hero__wave" viewBox="0 0 1440 110" preserveAspectRatio="none" aria-hidden="true">
                <path d="M0,64 C220,16 420,96 720,58 C980,24 1200,92 1440,48 L1440,110 L0,110 Z" fill="#00B4D8"/>
                <path d="M0,64 C220,16 420,96 720,58 C980,24 1200,92 1440,48" fill="none" stroke="#D4AF37" stroke-width="2.5"/>
                <path d="M0,84 C240,46 460,104 740,74 C1000,46 1210,100 1440,66" fill="none" stroke="#D4AF37" stroke-width="1" opacity=".5"/>
            </svg>
        </section>

        <!-- 4. COLECCIÓN -->
        <section class="mc-coleccion" id="coleccion" aria-labelledby="coleccionTitulo">
            <div class="mc-frame">
                <span class="mc-corner mc-corner--tl" aria-hidden="true">
                    <svg viewBox="0 0 26 26" fill="currentColor"><path d="M19 2a11 11 0 1 0 6 15A9 9 0 1 1 19 2z"/></svg>
                </span>
                <span class="mc-corner mc-corner--tr" aria-hidden="true">
                    <svg viewBox="0 0 26 26" fill="currentColor"><path d="M19 2a11 11 0 1 0 6 15A9 9 0 1 1 19 2z"/></svg>
                </span>
                <span class="mc-corner mc-corner--bl" aria-hidden="true">
                    <svg viewBox="0 0 26 26" fill="currentColor"><path d="M19 2a11 11 0 1 0 6 15A9 9 0 1 1 19 2z"/></svg>
                </span>
                <span class="mc-corner mc-corner--br" aria-hidden="true">
                    <svg viewBox="0 0 26 26" fill="currentColor"><path d="M19 2a11 11 0 1 0 6 15A9 9 0 1 1 19 2z"/></svg>
                </span>

                <header class="mc-coleccion__head">
                    <h2 id="coleccionTitulo" class="mc-coleccion__title">Colección Urbana de Cuero</h2>
                    <p class="mc-coleccion__sub">
                        <span class="mc-moon" aria-hidden="true">☾</span>
                        Diseño minimal &bull; Cuero premium &bull; Hecho para la ciudad
                        <span class="mc-moon" aria-hidden="true">☽</span>
                    </p>
                </header>

                <?php if ($mcProductos): ?>
                <div class="mc-grid">
                    <?php foreach ($mcProductos as $p): ?>
                    <div class="mc-prod">
                        <span class="badge-nuevo">Nuevo</span>
                        <article class="card-producto h-100">
                            <a href="producto.php?id=<?= (int)$p["id"] ?>" class="d-block position-relative">
                                <img src="<?= htmlspecialchars(imagen_thumb($p["imagen"])) ?>"
                                     alt="<?= htmlspecialchars($p["producto"]) ?>"
                                     class="card-producto__img"
                                     loading="lazy"
                                     onerror="this.style.display='none'">
                            </a>
                            <div class="card-producto__body">
                                <h3 class="card-producto__titulo">
                                    <a href="producto.php?id=<?= (int)$p["id"] ?>"><?= htmlspecialchars($p["producto"]) ?></a>
                                </h3>
                                <?php if (!empty($p["descripcion"])): ?>
                                <p class="card-producto__desc"><?= htmlspecialchars($p["descripcion"]) ?></p>
                                <?php endif; ?>
                                <p class="card-producto__precio"><?= precio_ar($p["precio"]) ?></p>
                                <a href="producto.php?id=<?= (int)$p["id"] ?>" class="btn btn-outline btn-sm w-100">Ver producto</a>
                            </div>
                        </article>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <p class="text-center" style="margin-top:32px;color:var(--mc-gold-dark);">
                    No hay productos disponibles por el momento.
                </p>
                <?php endif; ?>

                <!-- 5. BOTÓN FINAL -->
                <div class="mc-coleccion__foot">
                    <a class="mc-btn-outline" href="colecciones.php">
                        Descubre toda la colección
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </section>
    </main>

<?php require_once "partials/footer.php"; ?>
