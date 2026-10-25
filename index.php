<?php
require_once "config.php";

$mc_titulo      = "Marea Celestial | Bolsos, Carteras y Monederos";
$mc_descripcion = "Marea Celestial: bolsos, carteras y monederos en ecocuero. Catálogo 2026 con envíos a todo el país vía WhatsApp.";
$mc_og_titulo   = "Marea Celestial | Bolsos, Carteras y Monederos";
$mc_og_imagen   = "img/og-image.jpg";
$mc_pagina      = "inicio";

ob_start();
?>
<style>
    .scroll-hint {
        width: 26px; height: 42px; border-radius: 9999px;
        border: 1px solid var(--border-strong);
        display: flex; justify-content: center; padding-top: 8px;
        background: var(--bg-glass);
    }
    .scroll-hint__dot {
        width: 6px; height: 6px; border-radius: 50%;
        background: var(--gold); animation: scroll-bounce 1.5s ease-in-out infinite;
    }
    @keyframes scroll-bounce {
        0%, 100% { transform: translateY(0); opacity: 1; }
        50% { transform: translateY(14px); opacity: 0.4; }
    }

    .divider-celestial {
        width: 120px; height: 2px;
        background: linear-gradient(90deg, transparent, var(--gold), var(--turquoise), var(--gold), transparent);
        margin: 0 auto;
        border-radius: 2px;
    }

    .category-card { position: relative; overflow: hidden; border: 2px solid transparent; transition: all var(--dur-slow) var(--ease-out); }
    .category-card::before {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(180deg, transparent 35%, rgba(43, 36, 30, 0.55) 100%);
        z-index: 1; transition: opacity var(--dur-base);
    }
    .category-card:hover { border-color: var(--gold); transform: translateY(-8px) scale(1.02); box-shadow: 0 18px 40px rgba(90, 74, 58, 0.18); }
    .category-card:hover::before { opacity: 0.95; }
    .category-card img { transition: transform var(--dur-slower) var(--ease-out); }
    .category-card:hover img { transform: scale(1.08); }
    .category-card .content { position: relative; z-index: 2; }

    .hero-stars {
        position: absolute; inset: 0; pointer-events: none; overflow: hidden;
    }
    .hero-star {
        position: absolute; color: var(--gold); font-size: 1.2rem;
        animation: star-twinkle 2s ease-in-out infinite;
    }
    .hero-star:nth-child(1) { top: 15%; left: 10%; animation-delay: 0s; }
    .hero-star:nth-child(2) { top: 25%; right: 15%; animation-delay: 0.4s; font-size: 0.9rem; }
    .hero-star:nth-child(3) { top: 60%; left: 8%; animation-delay: 0.8s; font-size: 1.5rem; }
    .hero-star:nth-child(4) { top: 70%; right: 12%; animation-delay: 1.2s; }
    .hero-star:nth-child(5) { top: 40%; left: 20%; animation-delay: 1.6s; font-size: 0.8rem; }
    .hero-star:nth-child(6) { top: 20%; left: 50%; animation-delay: 0.2s; font-size: 1rem; }

    .review-card {
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: var(--radius-2xl); padding: var(--space-6);
        height: 100%; box-shadow: var(--shadow-lg);
        transition: all var(--dur-base);
    }
    .review-card:hover { border-color: var(--gold); box-shadow: 0 0 30px var(--gold-glow); transform: translateY(-4px); }

    /* ===== Portada Marea Celestial (noche) ===== */
    .mh-hero {
        position: relative; width: 100%; min-height: 560px;
        overflow: hidden; background: var(--color-primary); padding-top: 72px;
    }
    .mh-hero__fondo { position: absolute; inset: 0; }
    .mh-hero__fondo img { width: 100%; height: 100%; object-fit: cover; opacity: 0.9; display: block; }
    .mh-hero__fondo::after {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(180deg, rgba(10, 25, 49, 0.45) 0%, transparent 55%, rgba(10, 25, 49, 0.25) 100%);
    }
    .mh-luna {
        position: absolute; top: calc(72px + 20px); right: 9%; z-index: 2;
        font-size: clamp(64px, 9vw, 110px); line-height: 1;
        filter: drop-shadow(0 0 20px rgba(253, 230, 138, 0.8));
    }
    .mh-hero__contenido {
        position: relative; z-index: 2; display: flex; flex-direction: column;
        align-items: center; text-align: center; padding: var(--space-8) var(--space-4) 0;
    }
    .mh-eyebrow { display: flex; align-items: center; gap: var(--space-4); color: #fde68a; font-weight: 600; letter-spacing: 3px; font-size: var(--text-sm); }
    .mh-eyebrow__linea { height: 1px; width: clamp(64px, 10vw, 96px); }
    .mh-eyebrow__linea--r { background: linear-gradient(90deg, transparent, #fde68a); }
    .mh-eyebrow__linea--l { background: linear-gradient(270deg, transparent, #fde68a); }
    .mh-hero__titulo {
        font-family: var(--font-display); font-weight: 600; color: #fff;
        font-size: clamp(1.7rem, 4.4vw, 2.75rem); line-height: 1.15; margin: var(--space-3) 0 0;
        text-shadow: 0 4px 20px rgba(10, 25, 49, 0.5);
    }
    .mh-hero__texto { color: rgba(255, 255, 255, 0.9); font-size: var(--text-sm); margin-top: var(--space-2); }
    .mh-hero__cta {
        margin-top: var(--space-5); background: #fde68a; color: var(--color-primary);
        font-weight: 700; font-size: var(--text-sm); letter-spacing: 0.04em;
        padding: 10px 32px; border-radius: var(--radius-sm); text-decoration: none;
        transition: filter var(--dur-base);
    }
    .mh-hero__cta:hover { color: var(--color-primary); filter: brightness(0.95); }
    .mh-hero__bolsos {
        position: absolute; left: 0; right: 0; bottom: 6%; z-index: 2;
        display: flex; justify-content: center; align-items: flex-end;
        gap: clamp(1rem, 4vw, 6rem); padding: 0 var(--space-4);
    }
    .mh-bolso { width: clamp(110px, 22vw, 240px); }
    .mh-bolso img {
        width: 100%; aspect-ratio: 1 / 1; object-fit: cover; display: block;
        border-radius: var(--radius-md); box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
    }
    .mh-bolso--1 { animation: mh-float1 4s ease-in-out infinite; }
    .mh-bolso--2 { animation: mh-float2 5s ease-in-out infinite 0.3s; margin-bottom: -1rem; }
    .mh-bolso--3 { animation: mh-float1 4.5s ease-in-out infinite 0.6s; }
    @keyframes mh-float1 { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-15px); } }
    @keyframes mh-float2 { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-20px); } }
    @media (max-width: 767px) {
        .mh-hero { min-height: 500px; }
        .mh-bolso--3 { display: none; }
    }
    @media (prefers-reduced-motion: reduce) {
        .mh-bolso--1, .mh-bolso--2, .mh-bolso--3 { animation: none; }
    }

    .pv-categorias { background: var(--color-bg); padding: var(--space-10) 0; text-align: center; }
    .pv-categorias__titulo {
        font-family: var(--font-display); font-weight: 600; color: var(--color-primary);
        font-size: clamp(1.4rem, 3vw, 2rem); line-height: 1.2; margin: 0;
    }
    .pv-divisor { display: flex; align-items: center; justify-content: center; gap: var(--space-4); margin-top: var(--space-4); }
    .pv-divisor__linea { height: 1px; width: min(200px, 38vw); }
    .pv-divisor__linea--r { background: linear-gradient(90deg, transparent, var(--color-accent)); }
    .pv-divisor__linea--l { background: linear-gradient(270deg, transparent, var(--color-accent)); }
    .pv-divisor__icono { color: var(--color-accent); }
    .pv-cat-grid { display: flex; flex-wrap: wrap; justify-content: center; gap: clamp(1.5rem, 6vw, 5rem); margin-top: var(--space-8); }
    .pv-cat { text-decoration: none; display: block; }
    .pv-cat__circulo {
        display: flex; align-items: center; justify-content: center;
        width: clamp(104px, 12vw, 130px); aspect-ratio: 1; border-radius: 50%;
        background: var(--color-bg-card);
        border: 1px solid var(--color-accent);
        box-shadow: 0 0 0 6px var(--color-bg-card), 0 0 0 7px var(--color-accent-a22);
        transition: box-shadow var(--dur-base) var(--ease-out), transform var(--dur-base) var(--ease-out);
    }
    .pv-cat__circulo-interno {
        width: 88%; height: 88%; border-radius: 50%;
        background: var(--color-secondary);
        display: flex; align-items: center; justify-content: center;
        font-size: 2.2rem; line-height: 1;
    }
    .pv-cat:hover .pv-cat__circulo { box-shadow: 0 12px 30px var(--color-accent-a40); transform: translateY(-4px); }
    .pv-cat__nombre { display: block; margin-top: var(--space-4); color: var(--color-primary); font-family: var(--font-display); font-size: var(--text-lg); }
</style>
<?php
$mc_head_extra = ob_get_clean();
require_once "partials/header.php";
?>

    <!-- PORTADA MAREA CELESTIAL (noche) -->
    <?php
    /* 3 bolsos flotando con imagenes reales de productos con stock.
       Si la BD no responde o no hay imagenes validas, no se muestran. */
    $mhBolsos = [];
    try {
        $pdoMH = conectar();
        $filasMH = $pdoMH->query("SELECT producto, imagen FROM productos WHERE stock > 0 AND imagen <> '' ORDER BY RAND() LIMIT 3")->fetchAll();
        foreach ($filasMH as $fmh) {
            $rutaMH = trim((string)$fmh["imagen"]);
            if ($rutaMH !== "" && is_file(__DIR__ . "/" . ltrim($rutaMH, "/"))) {
                $mhBolsos[] = ["producto" => $fmh["producto"], "imagen" => $rutaMH];
            }
        }
    } catch (Throwable $e) { }
    ?>
    <section class="mh-hero" aria-labelledby="heroTitulo">
        <div class="mh-hero__fondo" aria-hidden="true">
            <img src="https://images.unsplash.com/photo-1530549387789-4c1017266635?w=1920&q=80" alt="">
        </div>
        <div class="mh-luna" aria-hidden="true">🌙</div>

        <div class="mh-hero__contenido">
            <div class="mh-eyebrow">
                <span class="mh-eyebrow__linea mh-eyebrow__linea--r"></span>
                NUEVA COLECCIÓN
                <span class="mh-eyebrow__linea mh-eyebrow__linea--l"></span>
            </div>
            <h1 id="heroTitulo" class="mh-hero__titulo">Inspirada por la marea y el cielo</h1>
            <p class="mh-hero__texto">Diseños exclusivos que capturan la esencia del mar y la luna</p>
            <a href="#mas-vendidos" class="mh-hero__cta">COMPRAR AHORA</a>
        </div>

        <?php if ($mhBolsos): ?>
        <div class="mh-hero__bolsos" aria-label="Productos destacados">
            <?php foreach ($mhBolsos as $i => $mb): ?>
                <div class="mh-bolso mh-bolso--<?= $i + 1 ?>">
                    <img src="<?= htmlspecialchars(imagen_producto($mb["imagen"])) ?>"
                         alt="<?= htmlspecialchars($mb["producto"]) ?>"
                         loading="eager"
                         onerror="this.style.display='none'">
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </section>

    <!-- TÍTULO + CATEGORÍAS -->
    <section id="colecciones" class="pv-categorias" aria-labelledby="portadaTitulo">
        <div class="container">
            <h2 id="portadaTitulo" class="pv-categorias__titulo">Colección Verano Celestial 2026 — Brilla como la luna sobre el mar</h2>

            <div class="pv-divisor" aria-hidden="true">
                <span class="pv-divisor__linea pv-divisor__linea--r"></span>
                <span class="pv-divisor__icono">✦</span>
                <span class="pv-divisor__linea pv-divisor__linea--l"></span>
            </div>

            <div class="pv-cat-grid">
            <?php
            $iconosCat = ["Bolsos" => "👜", "Carteras" => "👛", "Monederos" => "👝"];
            try {
                $pdoCat = conectar();
                $cats = $pdoCat->query("
                    SELECT c.id, c.categoria,
                           (SELECT COUNT(*) FROM productos p WHERE p.id_categoria = c.id) AS total
                    FROM categorias c
                    ORDER BY c.id
                ")->fetchAll();
            } catch (Throwable $e) { $cats = []; }

            if ($cats):
                foreach ($cats as $cat):
                    $nombreCat = $cat["categoria"];
                    $iconoCat = $iconosCat[$nombreCat] ?? "✦";
            ?>
                <a class="pv-cat" href="productos.php?id=<?= (int)$cat["id"] ?>" aria-label="Ver <?= htmlspecialchars($nombreCat) ?> (<?= (int)$cat["total"] ?> productos)">
                    <span class="pv-cat__circulo" aria-hidden="true">
                        <span class="pv-cat__circulo-interno"><?= $iconoCat ?></span>
                    </span>
                    <span class="pv-cat__nombre"><?= htmlspecialchars($nombreCat) ?></span>
                </a>
            <?php
                endforeach;
            else:
            ?>
                <p class="text-fg-muted">No hay categorías disponibles por el momento.</p>
            <?php endif; ?>
            </div>

            <div class="mt-8">
                <a href="colecciones.php" class="btn btn-outline">Ver colecciones curadas ✦</a>
            </div>
        </div>
    </section>

    <div class="accent-band" aria-hidden="true"></div>

    <!-- BENEFICIOS -->
    <section id="beneficios" class="beneficios py-12 relative z-10" aria-labelledby="beneficiosTitulo">
        <div class="container">
            <header class="text-center mb-14 animate-fade-up">
                <span class="badge badge-turquoise mb-3 d-inline-block">✦ Tu experiencia de compra ✦</span>
                <h2 id="beneficiosTitulo" class="display-section mb-3">
                    <span class="text-gradient-celestial">Por qué comprar con nosotras</span>
                </h2>
            </header>
            <div class="row g-4">
                <div class="col-12 col-md-4">
                    <div class="beneficio animate-fade-up delay-1">
                        <div class="beneficio__icono" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="13.5" r="2.5"></circle><circle cx="18.5" cy="13.5" r="2.5"></circle></svg>
                        </div>
                        <h3 class="beneficio__titulo">Envíos a todo el país</h3>
                        <p class="beneficio__texto">Despachamos por correo y entrega a domicilio. Seguimiento online de tu pedido.</p>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="beneficio animate-fade-up delay-2">
                        <div class="beneficio__icono" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 4 1 10 7 10"></polyline><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path></svg>
                        </div>
                        <h3 class="beneficio__titulo">Cambios y devoluciones</h3>
                        <p class="beneficio__texto">Tenés 30 días para cambiar tu producto. Sin vueltas, te lo resolvemos por WhatsApp.</p>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="beneficio animate-fade-up delay-3">
                        <div class="beneficio__icono" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        </div>
                        <h3 class="beneficio__titulo">Pago seguro</h3>
                        <p class="beneficio__texto">Confirmá por WhatsApp o por transferencia. Tus datos siempre protegidos.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Ola turquesa separadora -->
    <div class="ola-separadora ola-separadora--turquesa-tenue" aria-hidden="true">
        <svg viewBox="0 0 1440 100" preserveAspectRatio="none">
            <path d="M0,100 L1440,100 L1440,58 C1200,14 900,42 660,26 C420,10 180,46 0,12 Z"></path>
        </svg>
    </div>

    <!-- MAS VENDIDOS -->
    <section id="mas-vendidos" class="mas-vendidos py-12 relative z-10" aria-labelledby="masVendidosTitulo">
        <div class="container">
            <header class="text-center mb-14 animate-fade-up">
                <span class="badge badge-gold mb-3 d-inline-block">✦ Los más elegidos ✦</span>
                <h2 id="masVendidosTitulo" class="display-section mb-3">
                    <span class="text-gradient-celestial">Más vendidos</span>
                </h2>
                <p class="text-fg-muted text-lg">Los favoritos de nuestras clientas</p>
            </header>

            <?php
            try {
                $pdoMV = conectar();
                $masVendidos = $pdoMV->query("
                    SELECT p.*, c.categoria AS cat_nombre
                    FROM productos p
                    INNER JOIN categorias c ON c.id = p.id_categoria
                    WHERE p.stock > 0
                    ORDER BY (p.stock * 1000) + (p.id * 7) DESC
                    LIMIT 8
                ")->fetchAll();
            } catch (Throwable $e) { $masVendidos = []; }
            ?>

            <?php if ($masVendidos): ?>
            <div id="carouselMV" class="carousel slide" data-bs-ride="carousel" data-bs-interval="6000">
                <div class="carousel-inner">
                    <?php foreach (array_chunk($masVendidos, 4) as $idxGrupo => $grupo): ?>
                    <div class="carousel-item <?= $idxGrupo === 0 ? 'active' : '' ?>">
                        <div class="row g-4">
                            <?php foreach ($grupo as $mv): ?>
                            <div class="col-6 col-lg-3">
                                <article class="card-producto h-100">
                                    <a href="producto.php?id=<?= (int)$mv["id"] ?>" class="d-block position-relative">
                                        <img src="<?= htmlspecialchars(imagen_thumb($mv["imagen"])) ?>"
                                             alt="<?= htmlspecialchars($mv["producto"]) ?>"
                                             class="card-producto__img"
                                             loading="lazy"
                                             onerror="this.style.display='none'">
                                        <?php if ((int)$mv["id"] >= 30): ?>
                                            <span class="badge-nuevo badge-nuevo--pos">Nuevo</span>
                                        <?php endif; ?>
                                    </a>
                                    <div class="card-producto__body">
                                        <h3 class="card-producto__titulo">
                                            <a href="producto.php?id=<?= (int)$mv["id"] ?>"><?= htmlspecialchars($mv["producto"]) ?></a>
                                        </h3>
                                        <p class="card-producto__precio"><?= precio_ar($mv["precio"]) ?></p>
                                        <a href="producto.php?id=<?= (int)$mv["id"] ?>" class="btn btn-outline btn-sm w-100">Ver producto</a>
                                    </div>
                                </article>
                            </div>
                            <?php endforeach; ?>
                            <?php for ($k = count($grupo); $k < 4; $k++): ?>
                            <div class="col-6 col-lg-3"><div></div></div>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php if (count($masVendidos) > 4): ?>
                <div class="d-flex justify-content-center gap-3 mt-5">
                    <button class="btn-circled" type="button" data-bs-target="#carouselMV" data-bs-slide="prev" aria-label="Anterior">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    </button>
                    <button class="btn-circled" type="button" data-bs-target="#carouselMV" data-bs-slide="next" aria-label="Siguiente">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </button>
                </div>
                <?php endif; ?>
            </div>
            <?php else: ?>
            <p class="text-center text-fg-muted">No hay productos disponibles por el momento.</p>
            <?php endif; ?>

            <div class="text-center mt-5">
                <a href="productos.php" class="btn btn-primary btn-lg">Ver todo el catálogo</a>
            </div>
        </div>
    </section>

    <!-- CTA SECTION -->
    <section class="py-20 relative z-10">
        <div class="container">
            <div class="glass-strong rounded-3xl p-8 p-md-14 text-center animate-fade-up" style="border-color:var(--gold-glow)">
                <div class="d-inline-flex align-items-center gap-2 rounded-full px-4 py-2 mb-4" style="background:var(--gold-subtle);border:1px solid var(--border)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-gold"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                    <span class="fw-semibold text-sm" style="color:var(--gold-dark)">Envíos a todo el país</span>
                </div>
                <h3 class="display-section mb-3" style="color:var(--fg)">¿Lista para brillar?</h3>
                <p class="text-fg-muted text-lg mb-6 max-w-xl mx-auto">Elegí tu favorito y pedí por WhatsApp. Nosotros nos encargamos del resto.</p>
                <a href="productos.php" class="btn btn-primary btn-lg pulse-glow">
                    Comenzar mi pedido
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>
    </section>

    <!-- TESTIMONIOS -->
    <section class="py-20 relative z-10">
        <div class="container">
            <header class="text-center mb-4">
                <span class="badge badge-turquoise mb-3 d-inline-block">✦ Lo que dicen nuestros clientes ✦</span>
                <h2 class="display-section mb-3"><span class="text-gradient-celestial">Reseñas reales</span></h2>
            </header>
            <div class="row g-4">
                <div class="col-12 col-md-4">
                    <div class="review-card">
                        <div class="text-gold fs-5 mb-2" aria-label="5 estrellas">★★★★★</div>
                        <p class="text-fg-muted small mb-3">"La cartera es hermosa y llegó rapidísimo. Superó lo que esperaba, se nota la calidad del material."</p>
                        <div class="fw-semibold small" style="color:var(--fg)">María F.</div>
                        <div class="small" style="color:var(--fg-subtle)">Cartera A-016</div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="review-card">
                        <div class="text-gold fs-5 mb-2" aria-label="5 estrellas">★★★★★</div>
                        <p class="text-fg-muted small mb-3">"Compré por WhatsApp y la atención fue excelente. Me asesoraron y quedó hermoso el pedido."</p>
                        <div class="fw-semibold small" style="color:var(--fg)">Romina G.</div>
                        <div class="small" style="color:var(--fg-subtle)">Billetera B-10</div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="review-card">
                        <div class="text-gold fs-5 mb-2" aria-label="5 estrellas">★★★★★</div>
                        <p class="text-fg-muted small mb-3">"El tote bag es de 10. Lo uso todos los días, es cómodo y elegante. Recomendadisimo."</p>
                        <div class="fw-semibold small" style="color:var(--fg)">Agustina P.</div>
                        <div class="small" style="color:var(--fg-subtle)">Tote bag A-01</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php require_once "partials/footer.php"; ?>
