<?php
/* Header compartido - Marea Celestial
   Diseño único para TODAS las páginas (home + internas): header navy con logo,
   nav crema sticky y marco de olas. Mismo esquema que el flyer de marca.

   Variables opcionales que puede definir cada página antes de incluirlo:
     $mc_titulo, $mc_descripcion, $mc_og_titulo, $mc_og_tipo,
     $mc_og_imagen, $mc_og_url, $mc_canonical, $mc_pagina, $mc_head_extra,
     $mc_error_bd, $mc_body_class
*/
if (!function_exists('url_absoluta')) { require_once __DIR__ . "/../config.php"; }

$mc_titulo      = $mc_titulo      ?? "Marea Celestial";
$mc_descripcion = $mc_descripcion ?? "Bolsos, carteras y monederos en ecocuero de Marea Celestial. Catálogo 2026 con envíos a todo el país vía WhatsApp.";
$mc_og_titulo   = $mc_og_titulo   ?? $mc_titulo;
$mc_og_tipo     = $mc_og_tipo     ?? "website";
$mc_og_imagen   = $mc_og_imagen   ?? "img/og-image.jpg";
$mc_canonical   = $mc_canonical   ?? url_canonica();
$mc_og_url      = $mc_og_url      ?? $mc_canonical;
$mc_pagina      = $mc_pagina      ?? "";
$mc_head_extra  = $mc_head_extra  ?? "";
$mc_error_bd    = $mc_error_bd    ?? "";
$mc_body_class  = $mc_body_class  ?? "";

/* clave de página, texto, href */
$mc_nav = [
    ["inicio",      "Inicio",    "index.php"],
    ["productos",   "Catálogo",  "productos.php"],
    ["",            "Bolsos",    "productos.php?id=1"],
    ["",            "Carteras",  "productos.php?id=2"],
    ["colecciones", "Colección", "colecciones.php"],
    ["nosotros",    "Nosotros",  "nosotros.php"],
    ["contacto",    "Contacto",  "contacto.php"],
];
$mc_ver = (string)max(
    (int)(@filemtime(__DIR__ . "/../assets/css/marea-home.css") ?: 0),
    (int)(@filemtime(__DIR__ . "/../assets/css/marea-tema.css") ?: 0)
);
if ($mc_ver === "0") { $mc_ver = "1"; }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($mc_titulo) ?></title>
    <meta name="description" content="<?= htmlspecialchars($mc_descripcion) ?>">
    <meta name="theme-color" content="#0A1F44">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32.png">
    <link rel="icon" type="image/png" sizes="192x192" href="img/favicon-192.png">
    <link rel="apple-touch-icon" href="img/favicon-192.png">
    <link rel="manifest" href="manifest.webmanifest">
    <link rel="canonical" href="<?= htmlspecialchars($mc_canonical) ?>">
    <meta property="og:type" content="<?= htmlspecialchars($mc_og_tipo) ?>">
    <meta property="og:site_name" content="Marea Celestial">
    <meta property="og:title" content="<?= htmlspecialchars($mc_og_titulo) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($mc_descripcion) ?>">
    <meta property="og:image" content="<?= htmlspecialchars(url_absoluta($mc_og_imagen)) ?>">
    <?php if ($mc_og_url !== ""): ?><meta property="og:url" content="<?= htmlspecialchars($mc_og_url) ?>"><?php endif; ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,500&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/plumage.css">
    <link rel="stylesheet" href="assets/css/marea-home.css?v=<?= $mc_ver ?>">
    <link rel="stylesheet" href="assets/css/marea-tema.css?v=<?= $mc_ver ?>">
    <?= $mc_head_extra ?>
</head>
<body class="mc-page<?= $mc_body_class !== "" ? " " . htmlspecialchars($mc_body_class) : "" ?>">
    <div class="mc-page-frame" aria-hidden="true"></div>

    <?php if ($mc_error_bd !== ""): ?>
        <div style="background:rgba(0,180,216,0.12);border-bottom:1px solid rgba(0,180,216,0.35);color:#0A6E86;padding:10px 20px;font-size:0.85rem;text-align:center">
            No se pudo cargar el catálogo en este momento. Probá de nuevo en unos minutos.
        </div>
    <?php endif; ?>

    <!-- HEADER -->
    <header class="mc-header">
        <div class="mc-header__sky" aria-hidden="true"></div>
        <div class="mc-header__waves" aria-hidden="true"></div>

        <svg class="mc-header__moons mc-header__moons--l" width="46" height="46" viewBox="0 0 46 46" fill="none" aria-hidden="true">
            <path d="M30 5a17 17 0 1 0 11 22A14 14 0 1 1 30 5z" fill="currentColor" opacity=".8"/>
            <path d="M34 8l1.6 4.4L40 14l-4.4 1.6L34 20l-1.6-4.4L28 14l4.4-1.6z" fill="currentColor" opacity=".65"/>
        </svg>
        <svg class="mc-header__moons mc-header__moons--r" width="46" height="46" viewBox="0 0 46 46" fill="none" aria-hidden="true">
            <path d="M30 5a17 17 0 1 0 11 22A14 14 0 1 1 30 5z" fill="currentColor" opacity=".8"/>
            <path d="M34 8l1.6 4.4L40 14l-4.4 1.6L34 20l-1.6-4.4L28 14l4.4-1.6z" fill="currentColor" opacity=".65"/>
        </svg>

        <div class="mc-header__inner">
            <a class="mc-brand" href="index.php">
                <img src="img/logo.jpg" alt="Marea Celestial" class="mc-brand__logo" width="74" height="74" onerror="this.style.display='none'">
                <span class="mc-brand__text">
                    <span class="mc-brand__name">Marea celestial</span>
                    <span class="mc-brand__sub">Accesorios &bull; Cuero urbano</span>
                </span>
            </a>
        </div>
    </header>

    <!-- NAV -->
    <nav class="mc-nav" aria-label="Navegación principal">
        <ul class="mc-nav__list">
            <?php foreach ($mc_nav as [$clave, $texto, $href]): ?>
            <li>
                <a class="mc-nav__link<?= ($clave !== "" && $mc_pagina === $clave) ? ' is-active' : '' ?>"
                   href="<?= htmlspecialchars($href) ?>"<?= ($clave !== "" && $mc_pagina === $clave) ? ' aria-current="page"' : '' ?>><?= htmlspecialchars($texto) ?></a>
            </li>
            <?php endforeach; ?>
            <li>
                <button type="button" id="btnInstalar" class="mc-nav__install">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Instalar app
                </button>
            </li>
            <li>
                <a class="mc-nav__cta" href="productos.php">Comprar</a>
            </li>
        </ul>
    </nav>
