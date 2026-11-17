<?php
require_once "config.php";

$colecciones = [];
try {
    $pdo = conectar();
    $cats = $pdo->query("
        SELECT c.id, c.categoria,
               (SELECT COUNT(*) FROM productos p WHERE p.id_categoria = c.id) AS total
        FROM categorias c
        ORDER BY c.id
    ")->fetchAll();

    $stmtProd = $pdo->prepare("SELECT p.* FROM productos p WHERE p.id_categoria = ? ORDER BY (p.stock > 0) DESC, p.id LIMIT 4");
    foreach ($cats as $cat) {
        $stmtProd->execute([(int)$cat["id"]]);
        $cat["productos"] = $stmtProd->fetchAll();
        if ($cat["productos"]) $colecciones[] = $cat;
    }
} catch (Throwable $e) {
    $mc_error_bd = $e->getMessage();
}

$acentos = [
    "Bolsos"    => ["icono" => "👜", "lema" => "Amplios, cómodos y con actitud para tu día a día."],
    "Carteras"  => ["icono" => "👛", "lema" => "La pieza esencial que combina con todo."],
    "Monederos" => ["icono" => "👝", "lema" => "Pequeños y prácticos, con el brillo justo."],
];

$mc_titulo      = "Colecciones | Marea Celestial";
$mc_descripcion = "Descubrí las colecciones de Marea Celestial: bolsos, carteras y monederos en ecocuero, con envíos a todo el país.";
$mc_og_titulo   = "Colecciones | Marea Celestial";
$mc_og_imagen   = "img/og-image.jpg";
$mc_pagina      = "colecciones";

ob_start();
?>
<style>
    .col-hero { text-align: center; padding: var(--space-10) var(--space-4) var(--space-6); }
    .col-hero__titulo { font-family: var(--font-display); font-weight: 700; color: var(--color-primary); font-size: clamp(1.8rem, 4vw, 2.8rem); margin: 0; }
    .col-panel {
        border: 1.5px solid var(--border); border-radius: var(--radius-2xl);
        background: var(--bg-card); overflow: hidden; margin-bottom: var(--space-8);
        box-shadow: var(--shadow-lg);
    }
    .col-panel__media { position: relative; min-height: 100%; background: var(--bg-elevated); }
    .col-panel__media img { width: 100%; height: 100%; min-height: 260px; object-fit: cover; display: block; }
    .col-panel__media::after { content: ''; position: absolute; inset: 0; background: linear-gradient(180deg, transparent 40%, rgba(10,25,49,0.35)); }
    .col-panel__body { padding: var(--space-8); display: flex; flex-direction: column; justify-content: center; height: 100%; }
    .col-panel__chip {
        display: inline-flex; align-items: center; gap: 8px; padding: 6px 16px; border-radius: var(--radius-full);
        font-size: var(--text-xs); font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; width: fit-content;
    }
    .col-panel__titulo { font-family: var(--font-display); font-weight: 700; color: var(--color-primary); font-size: clamp(1.5rem, 3vw, 2.2rem); margin: var(--space-3) 0 var(--space-2); }
    .col-mini { display: flex; gap: var(--space-3); margin: var(--space-4) 0; }
    .col-mini a { width: 74px; height: 74px; border-radius: var(--radius-lg); overflow: hidden; border: 1.5px solid var(--border); transition: border-color var(--dur-base); background: var(--bg-elevated); }
    .col-mini a:hover { border-color: var(--gold); }
    .col-mini img { width: 100%; height: 100%; object-fit: cover; display: block; }

    @media (max-width: 575px) {
        .col-panel__body { padding: 24px 18px; }
        .col-panel__media img { min-height: 210px; }
        .col-mini a { width: 60px; height: 60px; }
    }
</style>
<?php
$mc_head_extra = ob_get_clean();
require_once "partials/header.php";
?>

    <main>
    <section class="col-hero">
        <span class="badge badge-gold mb-3 d-inline-block">✦ Colecciones curadas ✦</span>
        <h1 class="col-hero__titulo"><span class="text-gradient-celestial">Nuestras colecciones</span></h1>
        <p class="text-fg-muted text-lg max-w-2xl mx-auto mt-3">
            Cada línea tiene su personalidad. Elegí la tuya y descubrí todas las piezas disponibles.
        </p>
    </section>

    <div class="container pb-12">
        <?php if ($colecciones): ?>
            <?php foreach ($colecciones as $i => $col):
                $nombre = $col["categoria"];
                $acc = $acentos[$nombre] ?? ["icono" => "✦", "lema" => "Diseños pensados para acompañarte."];
                $portada = $col["productos"][0]["imagen"] ?? "";
                $invertido = ($i % 2 === 1);
            ?>
            <article class="col-panel">
                <div class="row g-0 align-items-stretch <?= $invertido ? 'flex-lg-row-reverse' : '' ?>">
                    <div class="col-12 col-lg-5">
                        <div class="col-panel__media">
                            <img src="<?= htmlspecialchars(imagen_producto($portada)) ?>"
                                 alt="<?= htmlspecialchars($nombre) ?>" loading="lazy" onerror="this.style.display='none'">
                        </div>
                    </div>
                    <div class="col-12 col-lg-7">
                        <div class="col-panel__body">
                            <span class="col-panel__chip">
                                <span aria-hidden="true"><?= $acc['icono'] ?></span> Colección
                            </span>
                            <h2 class="col-panel__titulo"><?= htmlspecialchars($nombre) ?></h2>
                            <p class="text-fg-muted mb-0"><?= htmlspecialchars($acc['lema']) ?></p>

                            <?php if (!empty($col["productos"])): ?>
                            <div class="col-mini">
                                <?php foreach (array_slice($col["productos"], 0, 4) as $mini): ?>
                                    <a href="producto.php?id=<?= (int)$mini["id"] ?>" aria-label="<?= htmlspecialchars($mini["producto"]) ?>">
                                        <img src="<?= htmlspecialchars(imagen_thumb($mini["imagen"])) ?>"
                                             alt="<?= htmlspecialchars($mini["producto"]) ?>" loading="lazy" onerror="this.style.display='none'">
                                    </a>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>

                            <div class="d-flex flex-wrap align-items-center gap-3 mt-2">
                                <a href="productos.php?id=<?= (int)$col["id"] ?>" class="btn btn-primary">
                                    Ver <?= (int)$col["total"] ?> <?= (int)$col["total"] === 1 ? 'producto' : 'productos' ?>
                                </a>
                                <a href="contacto.php" class="btn btn-outline">Consultar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-fg-muted py-10">No hay colecciones disponibles por el momento.</p>
        <?php endif; ?>
    </div>
    </main>

<?php require_once "partials/footer.php"; ?>
