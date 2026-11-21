<?php
require_once "config.php";

$productos = [];
$destacado = null;
$imagenesHero = [];

try {
    $pdo = conectar();
    $productos = $pdo->query("
        SELECT p.*, c.categoria AS cat_nombre
        FROM productos p
        INNER JOIN categorias c ON c.id = p.id_categoria
        ORDER BY p.id_categoria, p.id
    ")->fetchAll();

    /* Producto destacado: una cartera con stock; si no hay, el primer producto con stock. */
    foreach ($productos as $p) {
        if ((int)$p["stock"] > 0 && $p["imagen"] !== "" && (int)$p["id_categoria"] === 2) { $destacado = $p; break; }
    }
    if (!$destacado) {
        foreach ($productos as $p) {
            if ((int)$p["stock"] > 0 && $p["imagen"] !== "") { $destacado = $p; break; }
        }
    }

    /* Hasta 3 imagenes reales para los destacados del hero. */
    foreach ($productos as $p) {
        if ($p["imagen"] !== "" && is_file(__DIR__ . "/" . ltrim($p["imagen"], "/"))) {
            $imagenesHero[] = $p["imagen"];
        }
        if (count($imagenesHero) >= 3) break;
    }
} catch (Throwable $e) {
    $mc_error_bd = $e->getMessage();
}

$wa = preg_replace("/\D/", "", WHATSAPP);
if (!defined("MC_INSTAGRAM")) define("MC_INSTAGRAM", "marea_celestial");
$ig = MC_INSTAGRAM;

$mc_titulo      = "Marea Celestial | Accesorios que te acompañan a donde brilles";
$mc_descripcion = "Marea Celestial: bolsos, carteras y monederos en ecocuero. Diseño único, resistente y versátil. Comprá directo por WhatsApp con envío a todo el país.";
$mc_og_titulo   = "Marea Celestial | Accesorios que te acompañan";
$mc_og_imagen   = "img/og-image.jpg";
$mc_pagina      = "inicio";

ob_start();
?>
<style>
    /* ===== HERO ===== */
    .mr-hero { background: var(--color-bg); padding-top: 72px; }
    .mr-hero__row { display: flex; min-height: 560px; }
    .mr-hero__izq {
        width: 50%; background: #fff8ec;
        padding: var(--space-10) var(--space-8);
        display: flex; flex-direction: column; justify-content: center;
    }
    .mr-hero__logo {
        width: 96px; height: 96px; border-radius: 50%; object-fit: cover;
        border: 3px solid var(--color-accent); box-shadow: 0 0 30px var(--color-accent-a40);
        margin-bottom: var(--space-5);
    }
    .mr-hero__titulo { font-family: var(--font-display); font-weight: 700; color: var(--color-primary); font-size: clamp(2rem, 4vw, 3rem); margin: 0; }
    .mr-hero__sub { letter-spacing: 0.28em; font-size: 0.72rem; color: var(--color-primary-a70); margin-top: var(--space-2); }
    .mr-hero__claim { font-family: var(--font-display); font-style: italic; font-size: clamp(1.3rem, 2.4vw, 1.9rem); color: var(--color-primary); margin-top: var(--space-5); }
    .mr-feats { display: flex; gap: var(--space-5); margin-top: var(--space-6); flex-wrap: wrap; }
    .mr-feat { text-align: center; max-width: 96px; }
    .mr-feat__circ { width: 64px; height: 64px; border-radius: 50%; overflow: hidden; background: #fff; border: 1px solid var(--color-accent-a22); margin: 0 auto; }
    .mr-feat__circ img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .mr-feat__txt { font-size: 0.6rem; letter-spacing: 0.06em; margin-top: var(--space-2); color: var(--color-primary-a70); }
    .mr-btn-wa {
        margin-top: var(--space-6); display: inline-flex; align-items: center; gap: 8px;
        background: #25D366; color: #fff; font-weight: 700; padding: 12px 24px;
        border-radius: var(--radius-full); text-decoration: none; width: fit-content;
        box-shadow: 0 8px 24px rgba(37, 211, 102, 0.35);
        transition: transform var(--dur-base) var(--ease-out);
    }
    .mr-btn-wa:hover { transform: translateY(-2px); color: #fff; }
    .mr-hero__der { width: 50%; position: relative; overflow: hidden; background: linear-gradient(180deg, var(--color-secondary-a20), var(--color-primary)); }
    .mr-hero__der img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .mr-hero__der::after { content: ''; position: absolute; inset: auto 0 0 0; height: 96px; background: linear-gradient(0deg, rgba(10, 35, 66, 0.3), transparent); }
    @media (max-width: 991px) {
        .mr-hero__row { flex-direction: column; }
        .mr-hero__izq, .mr-hero__der { width: 100%; }
        .mr-hero__der { height: 340px; }
    }

    /* ===== DESTACADO ===== */
    .mr-destacado { background: var(--color-primary); color: var(--color-white); }
    .mr-destacado__row { display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-8); align-items: center; padding: var(--space-12) var(--space-8); }
    .mr-destacado__img { width: 80%; margin: 0 auto; border-radius: var(--radius-lg); display: block; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4); }
    .mr-destacado__titulo { font-family: var(--font-display); font-weight: 700; font-size: clamp(2rem, 4vw, 3rem); color: var(--color-white); margin: 0; }
    .mr-destacado__sub { color: var(--color-accent); letter-spacing: 0.2em; margin-top: var(--space-2); font-size: 0.78rem; }
    .mr-lista { list-style: none; padding: 0; margin: var(--space-6) 0 0; }
    .mr-lista li { display: flex; align-items: center; gap: var(--space-3); margin-bottom: var(--space-4); font-size: var(--text-sm); letter-spacing: 0.04em; }
    .mr-lista__icon { width: 34px; height: 34px; border-radius: 50%; border: 1px solid var(--color-accent); color: var(--color-accent); display: inline-flex; align-items: center; justify-content: center; flex: 0 0 34px; }
    .mr-cita { margin-top: var(--space-6); font-style: italic; color: var(--color-accent); }
    .mr-btn-gold { margin-top: var(--space-6); display: inline-flex; background: var(--color-accent); color: var(--color-primary); font-weight: 700; padding: 12px 30px; border-radius: var(--radius-full); text-decoration: none; transition: transform var(--dur-base) var(--ease-out); }
    .mr-btn-gold:hover { transform: translateY(-2px); color: var(--color-primary); }
    @media (max-width: 767px) { .mr-destacado__row { grid-template-columns: 1fr; padding: var(--space-10) var(--space-5); } }

    /* ===== CATALOGO ===== */
    .mr-catalogo { background: #fff; padding: var(--space-12) var(--space-4); }
    .mr-catalogo__titulo { text-align: center; font-family: var(--font-display); font-weight: 600; color: var(--color-primary); font-size: clamp(1.6rem, 3vw, 2.1rem); }
    .mr-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: var(--space-6); margin-top: var(--space-10); }
    .mr-card { border: 1px solid #e8dcc0; border-radius: var(--radius-lg); padding: var(--space-4); background: #fff; display: flex; flex-direction: column; transition: box-shadow var(--dur-base) var(--ease-out), transform var(--dur-base) var(--ease-out); }
    .mr-card:hover { box-shadow: 0 20px 40px rgba(90, 74, 58, 0.14); transform: translateY(-4px); }
    .mr-card__media { position: relative; height: 260px; background: var(--color-bg); border-radius: var(--radius-md); overflow: hidden; display: block; }
    .mr-card__media img { width: 100%; height: 100%; object-fit: contain; display: block; transition: transform var(--dur-slow) var(--ease-out); }
    .mr-card:hover .mr-card__media img { transform: scale(1.05); }
    .mr-card__badge { position: absolute; top: 10px; left: 10px; background: #111; color: #f5e0a0; font-size: 0.68rem; padding: 5px 12px; border-radius: var(--radius-full); }
    .mr-card__nombre { font-weight: 700; color: var(--color-primary); margin-top: var(--space-3); font-size: var(--text-base); text-decoration: none; }
    .mr-card__nombre:hover { color: var(--color-secondary); }
    .mr-card__desc { font-size: var(--text-xs); color: var(--color-primary-a70); margin: 2px 0 0; flex-grow: 1; }
    .mr-card__pie { display: flex; justify-content: space-between; align-items: center; margin-top: var(--space-3); gap: var(--space-3); }
    .mr-card__precio { font-weight: 800; font-size: var(--text-lg); color: var(--color-primary); }
    .mr-card__cta { background: var(--color-primary); color: #fff; font-size: var(--text-xs); padding: 8px 16px; border-radius: var(--radius-full); text-decoration: none; white-space: nowrap; transition: background var(--dur-base); }
    .mr-card__cta:hover { color: #fff; background: var(--color-secondary); }
    @media (max-width: 991px) { .mr-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 575px) { .mr-grid { grid-template-columns: 1fr; } }

    .mr-benef {
        margin-top: var(--space-12); background: var(--color-bg);
        border: 1px solid var(--color-accent-a22); border-radius: var(--radius-lg);
        padding: var(--space-6); display: flex; justify-content: space-around;
        gap: var(--space-4); text-align: center; flex-wrap: wrap;
        font-size: 0.78rem; letter-spacing: 0.04em; color: var(--color-primary);
    }
    .mr-contacto { display: flex; justify-content: center; gap: var(--space-5); margin-top: var(--space-8); flex-wrap: wrap; }
    .mr-contacto a, .mr-contacto span { display: inline-flex; align-items: center; gap: 8px; padding: 10px 24px; border-radius: var(--radius-full); text-decoration: none; font-weight: 600; font-size: var(--text-sm); }
    .mr-contacto .mr-wa { background: #25D366; color: #fff; }
    .mr-contacto .mr-ig { border: 1px solid #d6336c; color: #d6336c; }
    .mr-contacto .mr-env { border: 1px solid var(--color-accent); color: var(--color-primary); }
</style>
<?php
$mc_head_extra = ob_get_clean();
require_once "partials/header.php";
?>

    <!-- HERO -->
    <section class="mr-hero">
        <div class="mr-hero__row">
            <div class="mr-hero__izq">
                <img src="img/logo.jpg" alt="Marea Celestial" class="mr-hero__logo" onerror="this.style.display='none'">
                <h1 class="mr-hero__titulo">Marea Celestial</h1>
                <p class="mr-hero__sub">ACCESORIOS QUE TE ACOMPAÑAN A DONDE BRILLES</p>
                <h2 class="mr-hero__claim">Tu estilo en movimiento ♡</h2>

                <?php if (!empty($imagenesHero)): ?>
                <div class="mr-feats">
                    <div class="mr-feat">
                        <div class="mr-feat__circ"><img src="<?= htmlspecialchars($imagenesHero[0]) ?>" alt="" loading="lazy"></div>
                        <p class="mr-feat__txt">DISEÑO ÚNICO</p>
                    </div>
                    <div class="mr-feat">
                        <div class="mr-feat__circ"><img src="<?= htmlspecialchars($imagenesHero[min(1, count($imagenesHero) - 1)]) ?>" alt="" loading="lazy"></div>
                        <p class="mr-feat__txt">CIERRES RESISTENTES</p>
                    </div>
                    <div class="mr-feat">
                        <div class="mr-feat__circ"><img src="<?= htmlspecialchars($imagenesHero[min(2, count($imagenesHero) - 1)]) ?>" alt="" loading="lazy"></div>
                        <p class="mr-feat__txt">CORREA REGULABLE</p>
                    </div>
                </div>
                <?php endif; ?>

                <a href="https://wa.me/<?= $wa ?>" target="_blank" rel="noopener" class="mr-btn-wa">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Comprar por WhatsApp
                </a>
            </div>

            <div class="mr-hero__der">
                <?php if ($destacado): ?>
                    <img src="<?= htmlspecialchars(imagen_producto($destacado["imagen"])) ?>" alt="<?= htmlspecialchars($destacado["producto"]) ?>" onerror="this.style.display='none'">
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- DESTACADO -->
    <?php if ($destacado): ?>
    <section class="mr-destacado">
        <div class="mr-destacado__row">
            <img src="<?= htmlspecialchars(imagen_producto($destacado["imagen"])) ?>" alt="<?= htmlspecialchars($destacado["producto"]) ?>" class="mr-destacado__img" onerror="this.style.display='none'">
            <div>
                <h2 class="mr-destacado__titulo">Marea Celestial</h2>
                <p class="mr-destacado__sub">TU ESTILO, EN ARMONÍA CON EL UNIVERSO</p>
                <p class="mt-4 mb-0" style="font-size:var(--text-lg)"><?= htmlspecialchars($destacado["producto"]) ?></p>
                <?php if (!empty($destacado["descripcion"])): ?>
                    <p class="mt-1 mb-0" style="color:var(--color-primary-a45)"><?= htmlspecialchars($destacado["descripcion"]) ?></p>
                <?php endif; ?>
                <ul class="mr-lista">
                    <li><span class="mr-lista__icon">✦</span> DISEÑO EXCLUSIVO</li>
                    <li><span class="mr-lista__icon">🛡</span> RESISTENTE Y DURADERA</li>
                    <li><span class="mr-lista__icon">👜</span> PRÁCTICA Y VERSÁTIL</li>
                </ul>
                <p class="mr-cita">"Llevá contigo tu propia marea"</p>
                <a href="producto.php?id=<?= (int)$destacado["id"] ?>" class="mr-btn-gold">VER PRODUCTO — <?= precio_ar($destacado["precio"]) ?></a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- CATALOGO -->
    <section class="mr-catalogo">
        <h2 class="mr-catalogo__titulo">Carteras que cuentan tu historia ♡</h2>

        <?php if ($productos): ?>
        <div class="mr-grid">
            <?php foreach ($productos as $p):
                $agotado = (int)$p["stock"] <= 0;
                $textoWa = rawurlencode("Hola! Me interesa " . $p["producto"]);
            ?>
            <article class="mr-card">
                <a href="producto.php?id=<?= (int)$p["id"] ?>" class="mr-card__media">
                    <img src="<?= htmlspecialchars(imagen_thumb($p["imagen"])) ?>"
                         onerror="this.style.display='none'"
                         alt="<?= htmlspecialchars($p["producto"]) ?>"
                         loading="lazy">
                    <?php if ($agotado): ?>
                        <span class="mr-card__badge" style="background:#b91c1c;color:#fff">AGOTADO</span>
                    <?php elseif ((int)$p["id"] >= 30): ?>
                        <span class="mr-card__badge">NUEVO</span>
                    <?php endif; ?>
                </a>
                <a href="producto.php?id=<?= (int)$p["id"] ?>" class="mr-card__nombre"><?= htmlspecialchars($p["producto"]) ?></a>
                <?php if (!empty($p["descripcion"])): ?>
                    <p class="mr-card__desc"><?= htmlspecialchars($p["descripcion"]) ?></p>
                <?php endif; ?>
                <div class="mr-card__pie">
                    <span class="mr-card__precio"><?= precio_ar($p["precio"]) ?></span>
                    <a href="https://wa.me/<?= $wa ?>?text=<?= $textoWa ?>" target="_blank" rel="noopener" class="mr-card__cta">Consultar</a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
            <p class="text-center mt-5" style="color:var(--color-primary-a45)">No hay productos disponibles por el momento.</p>
        <?php endif; ?>

        <div class="mr-benef">
            <div>💎 MATERIALES DE CALIDAD</div>
            <div>✓ IDEAL PARA TU DÍA A DÍA</div>
            <div>👜 COMODIDAD Y ESTILO</div>
            <div>🎁 VARIOS MODELOS</div>
        </div>

        <div class="mr-contacto">
            <a href="https://wa.me/<?= $wa ?>" target="_blank" rel="noopener" class="mr-wa">📱 <?= htmlspecialchars(WHATSAPP) ?></a>
            <a href="https://www.instagram.com/<?= urlencode($ig) ?>" target="_blank" rel="noopener" class="mr-ig">📷 <?= htmlspecialchars($ig) ?></a>
            <span class="mr-env">🚚 ENVÍOS A TODO EL PAÍS</span>
        </div>
    </section>

<?php
ob_start();
?>
<script>
    if (typeof mcActualizarCarrito === 'function') mcActualizarCarrito();
</script>
<?php
$mc_scripts = ob_get_clean();
require_once "partials/footer.php";
?>
