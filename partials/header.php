<?php
/* Header compartido - Marea Celestial
   Variables opcionales que puede definir cada pagina antes de incluirlo:
     $mc_titulo, $mc_descripcion, $mc_og_titulo, $mc_og_tipo,
     $mc_og_imagen, $mc_og_url, $mc_pagina, $mc_head_extra, $mc_error_bd
*/
if (!function_exists('url_absoluta')) { require_once __DIR__ . "/../config.php"; }

$mc_titulo      = $mc_titulo      ?? "Marea Celestial";
$mc_descripcion = $mc_descripcion ?? "Bolsos, carteras y monederos en ecocuero de Marea Celestial. Catálogo 2026 con envíos a todo el país vía WhatsApp.";
$mc_og_titulo   = $mc_og_titulo   ?? $mc_titulo;
$mc_og_tipo     = $mc_og_tipo     ?? "website";
$mc_og_imagen   = $mc_og_imagen   ?? "img/og-image.jpg";
$mc_og_url      = $mc_og_url      ?? "";
$mc_pagina      = $mc_pagina      ?? "";
$mc_head_extra  = $mc_head_extra  ?? "";
$mc_error_bd    = $mc_error_bd    ?? "";

$mc_nav = [
    ["inicio",      "Inicio",      "index.php"],
    ["productos",   "Catálogo",    "productos.php"],
    ["colecciones", "Colecciones", "colecciones.php"],
    ["nosotros",    "Nosotros",    "nosotros.php"],
    ["contacto",    "Contacto",    "contacto.php"],
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($mc_titulo) ?></title>
    <meta name="description" content="<?= htmlspecialchars($mc_descripcion) ?>">
    <meta name="theme-color" content="#FFFBF2">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32.png">
    <link rel="icon" type="image/png" sizes="192x192" href="img/favicon-192.png">
    <link rel="apple-touch-icon" href="img/favicon-192.png">
    <link rel="manifest" href="manifest.webmanifest">
    <meta property="og:type" content="<?= htmlspecialchars($mc_og_tipo) ?>">
    <meta property="og:site_name" content="Marea Celestial">
    <meta property="og:title" content="<?= htmlspecialchars($mc_og_titulo) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($mc_descripcion) ?>">
    <meta property="og:image" content="<?= htmlspecialchars(url_absoluta($mc_og_imagen)) ?>">
    <?php if ($mc_og_url !== ""): ?><meta property="og:url" content="<?= htmlspecialchars($mc_og_url) ?>"><?php endif; ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/plumage.css">
    <style>
        .logo-wrap { position: relative; display: inline-block; border-radius: 50%; }
        .logo-wrap::before {
            content: ''; position: absolute; inset: -6px; border-radius: 50%;
            background: conic-gradient(from 0deg, var(--gold-dark), var(--gold-light), var(--turquoise), var(--gold), var(--gold-dark));
            opacity: 0; transition: opacity 0.4s ease; z-index: -1; filter: blur(8px);
        }
        .logo-wrap:hover::before { opacity: 0.75; }
        .logo-header {
            height: 42px; width: 42px; object-fit: cover;
            border-radius: 50%; border: 2px solid var(--gold);
            box-shadow: 0 0 12px var(--gold-glow);
            transition: all var(--dur-base);
        }
        .logo-header:hover { box-shadow: 0 0 24px var(--gold-glow); transform: scale(1.08); }
        .nav-link-modern {
            position: relative; padding: var(--space-2) var(--space-3);
            color: var(--fg-muted); font-weight: 500; font-size: var(--text-sm);
            transition: color var(--dur-fast);
        }
        .nav-link-modern::after {
            content: ''; position: absolute; bottom: 0; left: 50%; width: 0; height: 2px;
            background: var(--gold); transition: all var(--dur-base) var(--ease-out);
            transform: translateX(-50%);
        }
        .nav-link-modern:hover { color: var(--gold); }
        .nav-link-modern:hover::after { width: 100%; }
        .nav-link-modern.active { color: var(--gold); }
        .nav-link-modern.active::after { width: 100%; }
    </style>
    <?= $mc_head_extra ?>
</head>
<body>
    <?php if ($mc_error_bd !== ""): ?>
        <div style="background:rgba(168,180,183,0.16);border-bottom:1px solid rgba(140,154,158,0.3);color:var(--turquoise-dark);padding:10px 20px;font-size:0.85rem;text-align:center">
            No se pudo cargar el catálogo en este momento. <?= htmlspecialchars($mc_error_bd) ?>
        </div>
    <?php endif; ?>
    <div class="atmosphere" aria-hidden="true">
        <div class="atmosphere__orb atmosphere__orb--1"></div>
        <div class="atmosphere__orb atmosphere__orb--2"></div>
        <div class="atmosphere__orb atmosphere__orb--3"></div>
    </div>
    <div class="bg-grid" aria-hidden="true"></div>

    <header class="fixed top-0 left-0 right-0 z-[200] glass" id="mainHeader">
        <nav class="container navbar navbar-expand-md" style="min-height:72px;padding-top:0;padding-bottom:0" aria-label="Navegación principal">
            <a href="index.php" class="navbar-brand d-flex align-items-center gap-3 py-0" aria-label="Marea Celestial - Inicio">
                <img src="img/logo.jpg" alt="Marea Celestial" class="logo-header logo-wrap" onerror="this.style.display='none'">
                <span class="fw-bold text-gradient-gold" style="font-size:1.15rem;letter-spacing:0.04em">MAREA CELESTIAL</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Abrir menú"
                    style="background:transparent;color:var(--fg);padding:6px 8px">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="3" y1="7" x2="21" y2="7"></line><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="17" x2="21" y2="17"></line></svg>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="mainNav">
                <ul class="navbar-nav align-items-md-center gap-1" style="padding-top:12px;padding-bottom:12px">
                    <?php foreach ($mc_nav as [$clave, $texto, $href]): ?>
                    <li class="nav-item">
                        <a href="<?= $href ?>" class="nav-link nav-link-modern<?= $mc_pagina === $clave ? ' active' : '' ?>"<?= $mc_pagina === $clave ? ' aria-current="page"' : '' ?>><?= $texto ?></a>
                    </li>
                    <?php endforeach; ?>
                    <li class="nav-item ms-md-2 mt-2 mt-md-0">
                        <a href="productos.php" class="btn btn-primary">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                            Comprar
                        </a>
                    </li>
                    <li class="nav-item ms-md-1 mt-2 mt-md-0">
                        <button type="button" id="btnInstalar" class="btn btn-outline" style="display:none;border-color:var(--gold);color:var(--gold)">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                            Instalar app
                        </button>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
