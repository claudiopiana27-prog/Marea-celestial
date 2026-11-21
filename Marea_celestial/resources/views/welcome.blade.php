<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Marea Celestial | Accesorios Urbanos</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Cormorant+Garamond:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=Montserrat:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            /* ============================================================
               MAREA CELESTIAL — Homepage / manual de marca
               Paleta EXACTA:
                 Navy       #0A1F44
                 Gold       #D4AF37
                 Turquoise  #00B4D8
                 Cream      #F5F1E8
               Tipografía: Cormorant Garamond (títulos) + Montserrat (texto)
               ============================================================ */

            body.mc-home {
                --mc-navy: #0A1F44;
                --mc-gold: #D4AF37;
                --mc-turq: #00B4D8;
                --mc-cream: #F5F1E8;
                --mc-gold-dark: #8A6A1F;

                --color-primary: #0A1F44;
                --color-secondary: #00B4D8;
                --color-accent: #D4AF37;
                --color-bg: #F5F1E8;
                --color-primary-a08: rgba(10,31,68,0.08);
                --color-primary-a12: rgba(10,31,68,0.12);
                --color-primary-a20: rgba(10,31,68,0.20);
                --color-primary-a45: rgba(10,31,68,0.45);
                --color-primary-a70: rgba(10,31,68,0.70);
                --color-secondary-a10: rgba(0,180,216,0.10);
                --color-secondary-a20: rgba(0,180,216,0.20);
                --color-secondary-a35: rgba(0,180,216,0.35);
                --color-accent-a12: rgba(212,175,55,0.12);
                --color-accent-a22: rgba(212,175,55,0.22);
                --color-accent-a40: rgba(212,175,55,0.40);
                --color-accent-a60: rgba(212,175,55,0.60);
                --color-bg-glass: rgba(245,241,232,0.94);
                --color-bg-faint: rgba(245,241,232,0.6);

                --font-display: 'Cormorant Garamond', 'Playfair Display', Georgia, serif;
                --font-body: 'Montserrat', 'Inter', -apple-system, sans-serif;
                --font-sans: var(--font-body);

                background: var(--mc-cream);
                color: var(--mc-navy);
                font-family: var(--font-body);
                padding-top: 0;
            }

            body.mc-home .atmosphere, body.mc-home .bg-grid { display: none; }

            body.mc-home h1, body.mc-home h2, body.mc-home h3, body.mc-home h4 {
                font-family: var(--font-display);
            }

            /* Patrones de marca */
            .mc-moon-pattern {
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='140' height='140'%3E%3Cg fill='%23D4AF37'%3E%3Cpath opacity='.16' d='M34 22a16 16 0 1 0 13 25 18 18 0 1 1-13-25z'/%3E%3Cpath opacity='.1' d='M100 84a12 12 0 1 0 10 19 14 14 0 1 1-10-19z'/%3E%3Ccircle opacity='.18' cx='78' cy='34' r='1.7'/%3E%3Ccircle opacity='.14' cx='24' cy='98' r='1.4'/%3E%3Ccircle opacity='.12' cx='116' cy='20' r='1.2'/%3E%3C/g%3E%3C/svg%3E");
                background-size: 140px 140px;
            }

            .mc-wave-pattern {
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='46'%3E%3Cpath d='M0 30c20-15 40-15 60 0s40 15 60 0 40-15 40 0' fill='none' stroke='%23D4AF37' stroke-width='2.4' opacity='.45'/%3E%3Cpath d='M0 30c20-15 40-15 60 0s40 15 60 0 40-15 40 0' fill='none' stroke='%2300B4D8' stroke-width='1.1' opacity='.8'/%3E%3C/svg%3E");
                background-size: 160px 46px;
                background-repeat: repeat-x;
            }

            /* Header */
            .mc-header {
                position: relative;
                overflow: hidden;
                background: var(--mc-navy);
                color: var(--mc-cream);
                padding: 30px 20px 26px;
                text-align: center;
                border-bottom: 3px solid var(--mc-gold);
            }

            .mc-header__sky {
                position: absolute;
                inset: 0;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='140' height='140'%3E%3Cg fill='%23D4AF37'%3E%3Cpath opacity='.16' d='M34 22a16 16 0 1 0 13 25 18 18 0 1 1-13-25z'/%3E%3Cpath opacity='.1' d='M100 84a12 12 0 1 0 10 19 14 14 0 1 1-10-19z'/%3E%3Ccircle opacity='.18' cx='78' cy='34' r='1.7'/%3E%3Ccircle opacity='.14' cx='24' cy='98' r='1.4'/%3E%3Ccircle opacity='.12' cx='116' cy='20' r='1.2'/%3E%3C/g%3E%3C/svg%3E");
                background-size: 140px 140px;
                opacity: 0.65;
                pointer-events: none;
            }

            .mc-header__waves {
                position: absolute;
                left: 0; right: 0; bottom: 0;
                height: 38px;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='46'%3E%3Cpath d='M0 30c20-15 40-15 60 0s40 15 60 0 40-15 40 0' fill='none' stroke='%23D4AF37' stroke-width='2.4' opacity='.28'/%3E%3Cpath d='M0 30c20-15 40-15 60 0s40 15 60 0 40-15 40 0' fill='none' stroke='%2300B4D8' stroke-width='1.1' opacity='.45'/%3E%3C/svg%3E");
                background-size: 160px 46px;
                background-repeat: repeat-x;
                pointer-events: none;
                opacity: 0.9;
            }

            .mc-header__inner {
                position: relative;
                z-index: 2;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 10px;
            }

            .mc-brand {
                display: inline-flex;
                align-items: center;
                gap: 18px;
                text-decoration: none;
            }

            .mc-brand__logo {
                width: 74px;
                height: 74px;
                border-radius: 50%;
                object-fit: cover;
                border: 2px solid var(--mc-gold);
                box-shadow: 0 0 0 4px rgba(212,175,55,0.14), 0 8px 22px rgba(0,0,0,0.35);
                display: block;
                background: var(--mc-navy);
            }

            .mc-brand__text { text-align: left; }

            .mc-brand__name {
                display: block;
                font-family: var(--font-display);
                font-size: clamp(1.9rem, 4.4vw, 2.5rem);
                font-weight: 600;
                line-height: 1;
                color: var(--mc-cream);
                letter-spacing: 0.02em;
            }

            .mc-brand__sub {
                display: block;
                margin-top: 7px;
                font-family: var(--font-body);
                font-size: 0.58rem;
                font-weight: 500;
                letter-spacing: 0.34em;
                color: var(--mc-gold);
                text-transform: uppercase;
            }

            .mc-header__moons {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                color: var(--mc-gold);
                opacity: 0.55;
                pointer-events: none;
            }
            .mc-header__moons--l { left: clamp(12px, 5vw, 70px); }
            .mc-header__moons--r { right: clamp(12px, 5vw, 70px); transform: translateY(-50%) scaleX(-1); }

            @media (max-width: 640px) {
                .mc-header__moons { display: none; }
                .mc-brand { flex-direction: column; gap: 12px; }
                .mc-brand__text { text-align: center; }
            }

            /* Nav */
            .mc-nav {
                position: sticky;
                top: 0;
                z-index: 60;
                background: var(--mc-cream);
                border-bottom: 1px solid rgba(212,175,55,0.4);
                box-shadow: 0 4px 18px rgba(10,31,68,0.05);
            }

            .mc-nav__list {
                list-style: none;
                margin: 0;
                padding: 15px 20px;
                display: flex;
                justify-content: center;
                align-items: center;
                gap: clamp(14px, 3.4vw, 42px);
                overflow-x: auto;
                scrollbar-width: none;
            }
            .mc-nav__list::-webkit-scrollbar { display: none; }

            .mc-nav__link {
                display: inline-block;
                padding-bottom: 4px;
                font-family: var(--font-body);
                font-size: 0.8rem;
                font-weight: 500;
                letter-spacing: 1.5px;
                text-transform: uppercase;
                color: var(--mc-navy);
                text-decoration: none;
                white-space: nowrap;
                border-bottom: 1px solid transparent;
                transition: color 0.2s ease, border-color 0.2s ease;
            }

            .mc-nav__link:hover, .mc-nav__link:focus-visible { color: var(--mc-gold); border-bottom-color: var(--mc-gold); }
            .mc-nav__link.is-active {
                color: var(--mc-gold);
                font-weight: 700;
                border-bottom-color: var(--mc-gold);
            }

            /* Hero */
            .mc-hero {
                position: relative;
                display: grid;
                grid-template-columns: 460px minmax(0, 1fr);
                min-height: 650px;
                background: var(--mc-cream);
            }

            .mc-hero__left {
                position: relative;
                overflow: hidden;
                background: var(--mc-navy);
                color: var(--mc-cream);
                padding: 58px 44px 130px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                gap: 0;
            }

            .mc-hero__sky {
                position: absolute;
                inset: 0;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='140' height='140'%3E%3Cg fill='%23D4AF37'%3E%3Cpath opacity='.16' d='M34 22a16 16 0 1 0 13 25 18 18 0 1 1-13-25z'/%3E%3Cpath opacity='.1' d='M100 84a12 12 0 1 0 10 19 14 14 0 1 1-10-19z'/%3E%3Ccircle opacity='.18' cx='78' cy='34' r='1.7'/%3E%3Ccircle opacity='.14' cx='24' cy='98' r='1.4'/%3E%3Ccircle opacity='.12' cx='116' cy='20' r='1.2'/%3E%3C/g%3E%3C/svg%3E");
                background-size: 140px 140px;
            }

            .mc-hero__title {
                font-family: var(--font-display);
                font-size: clamp(2.5rem, 8vw, 4.5rem);
                font-weight: 700;
                line-height: 1.1;
                color: var(--mc-cream);
                margin-bottom: 12px;
            }

            .mc-hero__subtitle {
                font-family: var(--font-body);
                font-size: 1.1rem;
                color: var(--mc-cream);
                margin-bottom: 24px;
            }

            .mc-hero__tags {
                display: flex;
                flex-wrap: wrap;
                gap: 10px 14px;
                margin-bottom: 24px;
            }

            .mc-hero__tag {
                font-family: var(--font-body);
                font-size: 0.65rem;
                font-weight: 600;
                letter-spacing: 0.2em;
                text-transform: uppercase;
                color: var(--mc-navy);
                background: var(--mc-turq);
                padding: 6px 12px;
                border-radius: var(--radius-lg, 12px);
            }

            .mc-hero__btn {
                display: inline-block;
                font-family: var(--font-body);
                font-size: 0.75rem;
                font-weight: 600;
                letter-spacing: 0.15em;
                text-transform: uppercase;
                color: var(--mc-navy);
                background: var(--mc-gold);
                padding: 14px 32px;
                border-radius: var(--radius-lg, 12px);
                border: none;
                cursor: pointer;
                transition: background 0.2s ease;
                white-space: nowrap;
            }

            .mc-hero__btn:hover { background: #c9a82c; }

            .mc-hero__right {
                position: relative;
                background: url('https://images.unsplash.com/photo-15844 lost-blur-1') no-repeat center/cover;
                min-height: 500px;
            }

            .mc-hero__wave-bottom {
                position: absolute;
                bottom: 0; left: 0; right: 0;
                height: 50px;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='50'%3E%3Cpath d='M0 25c20-15 40-15 60 0s40 15 60 0 40-15 40 0' fill='none' stroke='%23D4AF37' stroke-width='2.4' opacity='.3'/%3E%3Cpath d='M0 25c20-15 40-15 60 0s40 15 60 0 40-15 40 0' fill='none' stroke='%2300B4D8' stroke-width='1.1' opacity='.5'/%3E%3C/svg%3E");
                background-size: 160px 50px;
                background-repeat: repeat-x;
            }

            @media (max-width: 640px) {
                .mc-hero { grid-template-columns: 1fr; min-height: 500px; }
                .mc-hero__left { padding: 40px 24px 80px; }
                .mc-hero__right { display: none; }
            }

            /* Colección */
            .mc-collection {
                padding: 60px 20px;
                background: var(--mc-cream);
            }

            .mc-collection__title {
                font-family: var(--font-display);
                font-size: clamp(2rem, 5vw, 2.5rem);
                color: var(--mc-navy);
                margin-bottom: 8px;
            }

            .mc-collection__subtitle {
                font-family: var(--font-body);
                font-size: 1rem;
                color: var(--mc-turq);
                margin-bottom: 40px;
                letter-spacing: 0.3px;
            }

            .mc-collection__grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 24px;
            }

            /* Product card wrapper - exactly as specified */
            .mc-prod {
                position: relative;
                border-radius: var(--radius-lg, 12px);
                overflow: hidden;
                background: var(--mc-cream);
                border: 2px solid var(--mc-gold);
                box-shadow: 0 4px 14px rgba(10,31,68,0.12);
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .mc-prod:hover {
                transform: translateY(-4px);
                box-shadow: 0 8px 24px rgba(10,31,68,0.18);
            }

            .mc-prod__img {
                width: 100%;
                height: 280px;
                background: var(--mc-navy);
                overflow: hidden;
            }

            .mc-prod__img img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }

            .mc-prod__badge-nuevo {
                position: absolute;
                top: 10px;
                left: 10px;
                background: var(--mc-gold);
                color: var(--mc-navy);
                font-family: var(--font-body);
                font-size: 0.55rem;
                font-weight: 700;
                letter-spacing: 0.1em;
                text-transform: uppercase;
                padding: 4px 10px;
                border-radius: var(--radius-full, 20px);
                box-shadow: 0 2px 6px rgba(0,0,0,0.15);
                z-index: 2;
                white-space: nowrap;
            }

            .mc-prod__info {
                padding: 16px 14px 20px;
            }

            .mc-prod__name {
                font-family: var(--font-display);
                font-size: 0.9rem;
                font-weight: 600;
                color: var(--mc-navy);
                margin-bottom: 6px;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .mc-prod__price {
                font-family: var(--font-body);
                font-size: 0.85rem;
                font-weight: 700;
                color: var(--mc-navy);
                margin-bottom: 10px;
                display: block;
            }

            .mc-prod__desc {
                font-family: var(--font-body);
                font-size: 0.7rem;
                color: var(--mc-gold-dark);
                line-height: 1.4;
                margin-bottom: 12px;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            /* Botón final */
            .mc-final-btn {
                display: inline-block;
                font-family: var(--font-body);
                font-size: 0.85rem;
                font-weight: 600;
                letter-spacing: 1.5px;
                text-transform: uppercase;
                color: var(--mc-navy);
                background: var(--mc-gold);
                padding: 16px 48px;
                border-radius: var(--radius-lg, 12px);
                border: 2px solid var(--mc-gold);
                cursor: pointer;
                transition: opacity 0.2s ease;
                white-space: nowrap;
                margin-top: 40px;
            }

            .mc-final-btn:hover { opacity: 0.9; }

            /* Reduced motion and accessibility */
            @media (prefers-reduced-motion: reduce) {
                .mc-prod, .mc-final-btn { transition: none; }
            }
        </style>
    @endif
</head>
<body class="mc-home">

<!-- ============================================================
   1. HEADER
   ============================================================ -->
<header class="mc-header">
    <div class="mc-header__sky"></div>
    <div class="mc-header__waves"></div>

    <div class="mc-header__inner">
        <a href="index.php" class="mc-brand">
            <div class="mc-brand__logo"></div>
            <div class="mc-brand__text">
                <div class="mc-brand__name">Marea celestial</div>
                <div class="mc-brand__sub">ACCESORIOS • CUERO URBANO</div>
            </div>
        </a>

        <div class="mc-header__moons mc-header__moons--l"></div>
        <div class="mc-header__moons mc-header__moons--r"></div>
    </div>
</header>

<!-- ============================================================
   2. NAV BAR
   ============================================================ -->
<nav class="mc-nav">
    <ul class="mc-nav__list">
        <li>
            <a href="index.php" class="mc-nav__link is-active">INICIO</a>
        </li>
        <li>
            <a href="index.php#coleccion" class="mc-nav__link">BOLSOS</a>
        </li>
        <li>
            <a href="index.php#coleccion" class="mc-nav__link">CARTERAS</a>
        </li>
        <li>
            <a href="index.php#coleccion" class="mc-nav__link">RIÑONERAS</a>
        </li>
        <li>
            <a href="index.php#novedades" class="mc-nav__link">NOVEDADES</a>
        </li>
        <li>
            <a href="index.php#coleccion" class="mc-nav__link">COLECCIÓN</a>
        </li>
    </ul>
</nav>

<!-- ============================================================
   3. HERO
   ============================================================ -->
<main class="mc-hero">
    <div class="mc-hero__left">
        <div class="mc-hero__sky"></div>

        <h1 class="mc-hero__title">Marea Celestial | Accesorios Urbanos</h1>
        <p class="mc-hero__subtitle">Marroquinería urbana hecha para el día a día</p>

        <div class="mc-hero__tags">
            <span class="mc-hero__tag">Bolsos</span>
            <span class="mc-hero__tag">Carteras</span>
            <span class="mc-hero__tag">Riñoneras</span>
        </div>

        <a href="#coleccion" class="mc-hero__btn">Comprar ahora</a>
    </div>

    <div class="mc-hero__right">
        <!-- Modelo foto - usando una imagen representativa -->
        <div style="width:100%;height:500px;background:url('https://images.unsplash.com/photo-1553671536-222d0e9c0c2e?w=800&h=600&fit=crop') no-repeat center/cover;"></div>

        <div class="mc-hero__wave-bottom"></div>
    </div>
</main>

<!-- ============================================================
   4. COLECCIÓN
   ============================================================ -->
<section class="mc-collection" id="coleccion">
    <h2 class="mc-collection__title">COLECCIÓN URBANA DE CUERO</h2>
    <p class="mc-collection__subtitle">Diseño minimal • Cuero premium • Hecho para la ciudad</p>

    <div class="mc-collection__grid">
        <?php
        // Cargar productos del catálogo
        require_once "config.php";

        try {
            $pdo = conectar();
            $stmt = $pdo->query("SELECT p.*, c.categoria AS cat_nombre FROM productos p INNER JOIN categorias c ON c.id = p.id_categoria ORDER BY p.id_categoria, p.id");
            $productos = $stmt->fetchAll();
        } catch (Throwable $e) {
            $productos = [];
            error_log("Error fetching productos: " . $e->getMessage());
        }

        // Agrupar por categoría para el grid 2 columnas
        $agrupados = [];
        foreach ($productos as $pf) {
            $key = (int)$pf['id_categoria'];
            if (!isset($agrupados[$key])) {
                $agrupados[$key] = [];
            }
            $agrupados[$key][] = $pf;
        }

        // Para grid 2 columnas, alternar lados
        $izquierda = true;
        foreach ($agrupados as $categoriaId => $items) {
            // Título de categoría
            $catNombre = $items[0]['cat_nombre'] ?? 'Productos';

            // Alternar el orden para grid 2 columnas
            $itemsIzq = [];
            $itemsDer = [];
            foreach ($items as $idx => $item) {
                if ($izquierda) {
                    $itemsIzq[] = $item;
                } else {
                    $itemsDer[] = $item;
                }
            }

            // Mostrar pares
            $max = max(count($itemsIzq), count($itemsDer));
            for ($i = 0; $i < $max; $i++) {
                // Ítem izquierdo
                if (isset($itemsIzq[$i])) {
                    $item = $itemsIzq[$i];
                    $precio = "$" . number_format((float)$item['precio'], 0, ",", ".");
                    $stock = (int)$item['stock'];
                    $esNuevo = $stock > 0 && (int)$item['id'] >= 30;
                    $img = htmlspecialchars(imagen_thumb($item['imagen']));
                    $nombre = htmlspecialchars($item['producto']);
                    $desc = htmlspecialchars($item['descripcion'] ?? '');

                    echo '
                    <div class="mc-prod">
                        <div class="mc-prod__img">
                            <img src="' . $img . '" alt="' . $nombre . '" loading="lazy">
                            ' . ($esNuevo ? '<span class="mc-prod__badge-nuevo">NUEVO</span>' : '') . '
                        </div>
                        <div class="mc-prod__info">
                            <h3 class="mc-prod__name">' . $nombre . '</h3>
                            <span class="mc-prod__price">' . $precio . '</span>
                            <p class="mc-prod__desc">' . $desc . '</p>
                        </div>
                    </div>';
                }

                // Ítem derecho
                if (isset($itemsDer[$i])) {
                    $item = $itemsDer[$i];
                    $precio = "$" . number_format((float)$item['precio'], 0, ",", ".");
                    $stock = (int)$item['stock'];
                    $esNuevo = $stock > 0 && (int)$item['id'] >= 30;
                    $img = htmlspecialchars(imagen_thumb($item['imagen']));
                    $nombre = htmlspecialchars($item['producto']);
                    $desc = htmlspecialchars($item['descripcion'] ?? '');

                    echo '
                    <div class="mc-prod">
                        <div class="mc-prod__img">
                            <img src="' . $img . '" alt="' . $nombre . '" loading="lazy">
                            ' . ($esNuevo ? '<span class="mc-prod__badge-nuevo">NUEVO</span>' : '') . '
                        </div>
                        <div class="mc-prod__info">
                            <h3 class="mc-prod__name">' . $nombre . '</h3>
                            <span class="mc-prod__price">' . $precio . '</span>
                            <p class="mc-prod__desc">' . $desc . '</p>
                        </div>
                    </div>';
                }
            }
            $izquierda = !$izquierda;
        }
        ?>
    </div>
</section>

<!-- ============================================================
   5. BOTÓN FINAL
   ============================================================ -->
<div style="text-align: center; margin: 60px 0 20px;">
    <a href="#coleccion" class="mc-final-btn">DESCUBRE TODA LA COLECCIÓN →</a>
</div>

<!-- Footer / copyright -->
<footer class="mc-footer" style="padding: 32px 20px; text-align: center; color: var(--mc-navy); font-size: 0.8rem; border-top: 1px solid rgba(212,175,55,0.3);">
    <p>Marea Celestial | Accesorios Urbanos · Hecho a mano con ♡ en Buenos Aires</p>
</footer>

</body>
</html>