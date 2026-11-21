<?php
/* Ficha de producto con cantidad, zoom, compartir WhatsApp, metadatos y carrito global */
require_once "config.php";

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$producto = null;
$imagen_full = "img/logo.jpg";

try {
    $pdo = conectar();
    $stmt = $pdo->prepare("SELECT p.*, c.categoria AS cat_nombre FROM productos p INNER JOIN categorias c ON c.id = p.id_categoria WHERE p.id = ?");
    $stmt->execute([$id]);
    $producto = $stmt->fetch();
} catch (Throwable $e) {
    $mc_error_bd = $e->getMessage();
}

if (!$producto) {
    http_response_code(404);
    require __DIR__ . "/404.php";
    exit;
}

$precio = "$" . number_format((float)$producto["precio"], 0, ",", ".");
$hay_stock = (int)$producto["stock"] > 0;
$imagen_full = imagen_producto($producto["imagen"] ?? "");

$titulo = $producto["producto"] . " | Marea Celestial";
$desc = trim((string)($producto["descripcion"] ?? "")) ?: "Bolso, cartera o monedero de Marea Celestial.";
$url_actual = url_absoluta("producto.php?id=" . $producto["id"]);

$mc_titulo      = $titulo;
$mc_descripcion = $desc;
$mc_og_titulo   = $titulo;
$mc_og_tipo     = "product";
$mc_og_imagen   = $imagen_full;
$mc_og_url      = $url_actual;
$mc_canonical   = $url_actual;
$mc_pagina      = "productos";

ob_start();
?>
<style>
    body { padding-top: 72px; }
    .product-hero {
        position: relative; border-radius: var(--radius-lg); overflow: hidden;
        border: 1.5px solid var(--border); background: var(--bg-card);
        box-shadow: 0 20px 40px rgba(90, 74, 58, 0.16);
    }
    .product-hero img {
        width: 100%; height: 100%; object-fit: cover; display: block;
        cursor: zoom-in; transition: transform var(--dur-base) var(--ease-out);
    }
    .qty-wrap {
        display: inline-flex; align-items: center; border: 1.5px solid var(--border);
        border-radius: var(--radius-full); background: var(--bg-elevated);
        padding: 2px; box-shadow: var(--shadow-soft);
    }
    .qty-btn {
        width: 40px; height: 40px; border-radius: var(--radius-full); border: none;
        background: transparent; color: var(--fg); display: inline-flex; align-items: center;
        justify-content: center; transition: all var(--dur-base); font-size: 1.25rem;
    }
    .qty-btn:hover { background: var(--gold-subtle); color: var(--gold-dark); }
    .qty-btn:disabled { opacity: 0.4; }
    .qty-input {
        width: 64px; height: 40px; border: none; background: transparent; text-align: center;
        font-weight: 600; font-size: var(--text-base); color: var(--fg);
    }
    .lightbox {
        position: fixed; inset: 0; background: rgba(0,0,0,0.9); display: none; align-items: center;
        justify-content: center; z-index: 9999; padding: 16px;
    }
    .lightbox.active { display: flex; }
    .lightbox img { max-width: 95vw; max-height: 95vh; border-radius: var(--radius-md); }
    .lightbox .close {
        position: absolute; top: 16px; right: 16px; width: 44px; height: 44px; border-radius: 50%;
        border: 1px solid rgba(255,255,255,0.2); background: rgba(255,255,255,0.1);
        color: #fff; display: flex; align-items: center; justify-content: center;
    }
    .share-btns { display: flex; gap: 8px; flex-wrap: wrap; }
    .btn-share {
        height: 38px; padding: 0 14px; border-radius: var(--radius-full); border: 1.5px solid var(--border);
        background: var(--bg-elevated); color: var(--fg); display: inline-flex; align-items: center; gap: 8px;
        text-decoration: none; transition: all var(--dur-base);
    }
    .btn-share:hover { border-color: var(--gold); color: var(--gold-dark); background: var(--gold-subtle); }
</style>
<?php
$mc_head_extra = ob_get_clean();
require_once "partials/header.php";
?>
    <main class="container" style="padding-top:32px;padding-bottom:100px">
        <nav aria-label="breadcrumb" class="breadcrumb-pill mb-4">
            <a href="index.php">Inicio</a>
            <span style="color:var(--fg-subtle)">&rarr;</span>
            <a href="productos.php">Catálogo</a>
            <span style="color:var(--fg-subtle)">&rarr;</span>
            <a href="productos.php?id=<?= (int)$producto["id_categoria"] ?>"><?= htmlspecialchars($producto["cat_nombre"] ?? "") ?></a>
            <span style="color:var(--fg-subtle)">&rarr;</span>
            <span style="color:var(--fg);font-weight:600"><?= htmlspecialchars($producto["producto"]) ?></span>
        </nav>
        <div class="row g-4 g-lg-5 align-items-start">
            <div class="col-12 col-lg-6">
                <div class="product-hero" style="aspect-ratio:1/1">
                    <img class="img-zoom" src="<?= htmlspecialchars($imagen_full) ?>" alt="<?= htmlspecialchars($producto["producto"]) ?>">
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <span class="badge badge-gold mb-3"><?= htmlspecialchars($producto["cat_nombre"] ?? "") ?></span>
                <h1 class="display-section mb-2" style="color:var(--fg)"><?= htmlspecialchars($producto["producto"]) ?></h1>
                <div class="mb-4 d-flex align-items-center flex-wrap gap-2">
                    <span class="fs-3 fw-bold" style="color:var(--gold-dark)"><?= $precio ?></span>
                    <span class="badge-stock <?= $hay_stock ? 'ok' : 'no' ?>" id="badgeStock">
                        <?= $hay_stock ? '✔ En stock' : '✖ Agotado' ?>
                    </span>
                </div>
                <?php if (!empty($producto["descripcion"])): ?>
                    <p class="text-fg-muted text-lg mb-4" style="line-height:1.7"><?= htmlspecialchars($producto["descripcion"]) ?></p>
                <?php endif; ?>
                <div class="d-flex align-items-center gap-3 flex-wrap mb-3" id="qtyControl" style="<?= $hay_stock ? '' : 'display:none!important' ?>">
                    <label class="text-sm fw-semibold" for="cantidad" style="color:var(--fg)">Cantidad</label>
                    <div class="qty-wrap">
                        <button type="button" class="qty-btn" data-action="restar" aria-label="Disminuir cantidad">−</button>
                        <input type="number" id="cantidad" class="qty-input" min="1" max="<?= max(1, (int)$producto['stock']) ?>" value="1" readonly>
                        <button type="button" class="qty-btn" data-action="sumar" aria-label="Aumentar cantidad">+</button>
                    </div>
                    <small class="text-fg-subtle" id="stockDisp">Quedan <?= (int)$producto['stock'] ?> en stock</small>
                </div>
                <div class="feature-check">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <span class="text-sm">Pedí con 3 clics por WhatsApp, sin registrarte</span>
                </div>
                <div class="feature-check">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                    <span class="text-sm">Envíos a todo el país vía correo o envío coordinado</span>
                </div>
                <div class="feature-check">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    <span class="text-sm">Material ecocuero premium, importado</span>
                </div>
                <div class="d-flex flex-wrap gap-3 mt-4">
                    <button type="button" class="btn btn-gold btn-lg" id="btnAgregarCarrito" <?= $hay_stock ? '' : 'disabled' ?> data-id="<?= (int)$producto['id'] ?>" data-nombre="<?= htmlspecialchars($producto['producto'], ENT_QUOTES) ?>" data-precio="<?= htmlspecialchars($precio) ?>" data-img="<?= htmlspecialchars($imagen_full) ?>">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:8px"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                        Agregar al carrito
                    </button>
                    <a href="#" class="btn btn-outline btn-lg" id="btnWpDirecto" target="_blank" rel="noopener noreferrer">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" style="margin-right:8px"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413"/></svg>
                        Consultar por WhatsApp
                    </a>
                </div>
                <div class="mt-4">
                    <div class="share-btns">
                        <button type="button" class="btn-share" id="shareWp">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413"/></svg>
                            Compartir por WhatsApp
                        </button>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($url_actual) ?>" target="_blank" rel="noopener noreferrer" class="btn-share">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.99 3.66 9.13 8.44 9.88v-6.99H7.9v-2.89h2.54V9.41c0-2.5 1.49-3.89 3.78-3.89 1.09 0 2.23.2 2.23.2v2.45h-1.26c-1.24 0-1.63.77-1.63 1.56v1.87h2.78l-.44 2.89h-2.34v6.99C18.34 21.13 22 16.99 22 12z"/></svg>
                            Facebook
                        </a>
                        <button type="button" class="btn-share" id="copyLink">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                            Copiar link
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- También te puede gustar -->
        <?php
        try {
            $stmt_rel = $pdo->prepare("SELECT id, producto, precio, imagen FROM productos WHERE id_categoria = ? AND id <> ? ORDER BY id LIMIT 4");
            $stmt_rel->execute([(int)$producto["id_categoria"], (int)$producto["id"]]);
            $relacionados = $stmt_rel->fetchAll();
        } catch (Throwable $e) {
            $relacionados = [];
        }
        ?>
        <?php if (!empty($relacionados)): ?>
        <section class="mt-5 pt-4" style="border-top:1px solid var(--border)">
            <div class="d-flex align-items-center gap-3 mb-4">
                <h2 class="display-section mb-0" style="color:var(--fg);font-size:clamp(1.4rem,3vw,2rem)">También te puede gustar</h2>
                <div class="flex-grow-1" style="height:1px;background:linear-gradient(90deg,var(--border-strong),transparent)"></div>
            </div>
            <div class="row g-3">
                <?php foreach ($relacionados as $rel):
                    $precio_rel = "$" . number_format((float)$rel["precio"], 0, ",", ".");
                ?>
                    <div class="col-6 col-md-3">
                        <a href="producto.php?id=<?= (int)$rel['id'] ?>" class="text-decoration-none">
                            <div class="rounded-3xl overflow-hidden" style="border:1.5px solid var(--border);background:var(--bg-card);transition:all var(--dur-base) var(--ease-out)">
                                <div style="aspect-ratio:1/1;overflow:hidden;background:var(--bg-elevated)">
                                    <img src="<?= htmlspecialchars(imagen_thumb($rel["imagen"])) ?>"
                                         onerror="this.style.display='none'"
                                         alt="<?= htmlspecialchars($rel["producto"]) ?>" loading="lazy"
                                         style="width:100%;height:100%;object-fit:cover;display:block">
                                </div>
                                <div style="padding:12px 14px 16px">
                                    <h3 style="font-family:var(--font-sans);font-size:var(--text-sm);font-weight:600;color:var(--fg);margin:0 0 4px;line-height:1.3"><?= htmlspecialchars($rel["producto"]) ?></h3>
                                    <span style="font-weight:700;color:var(--gold-dark)"><?= htmlspecialchars($precio_rel) ?></span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
    </main>

    <div class="lightbox" id="lightbox">
        <button class="close" id="lbClose" aria-label="Cerrar zoom">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
        <img id="lbImg" src="" alt="">
    </div>

<?php
ob_start();
?>
<script>
    const PROD_ID = <?= (int)$producto["id"] ?>;
    const PROD_NOMBRE = <?= json_encode($producto["producto"], JSON_UNESCAPED_UNICODE) ?>;
    const PROD_PRECIO = <?= json_encode($precio, JSON_UNESCAPED_UNICODE) ?>;
    const PROD_IMG = <?= json_encode($imagen_full, JSON_UNESCAPED_UNICODE) ?>;
    const MAX_STOCK = <?= max(0, (int)$producto["stock"]) ?>;
    const WHATSAPP = <?= json_encode(WHATSAPP, JSON_UNESCAPED_UNICODE) ?>;
    const URL_ACTUAL = <?= json_encode($url_actual, JSON_UNESCAPED_UNICODE) ?>;

    let cant = 1;
    function actualizarQty() {
        const $btnR = $('[data-action="restar"]');
        const $btnS = $('[data-action="sumar"]');
        const $inp = $('#cantidad');
        if (MAX_STOCK <= 0) { cant = 0; }
        $inp.val(cant);
        $btnR.prop('disabled', cant <= 1);
        $btnS.prop('disabled', MAX_STOCK > 0 && cant >= MAX_STOCK);
    }
    $('[data-action="restar"]').on('click', function () { if (cant > 1) { cant--; actualizarQty(); } });
    $('[data-action="sumar"]').on('click', function () { if (MAX_STOCK > 0 && cant < MAX_STOCK) { cant++; actualizarQty(); } });
    actualizarQty();

    $('#btnAgregarCarrito').on('click', function () {
        if (MAX_STOCK <= 0) return;
        const qty = Math.max(1, Math.min(cant, MAX_STOCK));
        if (window.mcAgregarAlCarrito) {
            mcAgregarAlCarrito({ id: PROD_ID, nombre: PROD_NOMBRE, precio: PROD_PRECIO, imagen: PROD_IMG, cantidad: qty });
        }
    });

    function waDirecto(qty) {
        const q = Math.max(1, Math.min(qty, MAX_STOCK));
        const txt = `Hola, quiero pedir:%0A- ${encodeURIComponent(PROD_NOMBRE)} x${q} = ${encodeURIComponent(PROD_PRECIO)}%0ATotal: ${encodeURIComponent(PROD_PRECIO)}%0A(Desde ${encodeURIComponent('Marea Celestial')})`;
        const url = 'https://wa.me/' + WHATSAPP.replace(/\D/g, '') + '?text=' + txt;
        $('#btnWpDirecto').attr('href', url);
    }
    waDirecto(1);
    $(document).on('click', '[data-action="restar"], [data-action="sumar"]', function () { waDirecto(cant); });

    $('#shareWp').on('click', function () {
        const txt = `Mirá este producto: ${encodeURIComponent(PROD_NOMBRE)}%0A${encodeURIComponent(URL_ACTUAL)}%0A%0APedido por WhatsApp: https://wa.me/${WHATSAPP.replace(/\D/g, '')}`;
        window.open('https://wa.me/?text=' + txt, '_blank', 'noopener');
    });
    $('#copyLink').on('click', function () {
        navigator.clipboard.writeText(URL_ACTUAL).then(() => {
            const $b = $(this);
            const t = $b.text();
            $b.text('¡Copiado!');
            setTimeout(() => $b.text(t), 1600);
        });
    });

    const $lb = $('#lightbox');
    const $lbImg = $('#lbImg');
    $('.img-zoom').on('click', function () { $lbImg.attr('src', $(this).attr('src')); $lb.addClass('active'); });
    $('#lbClose, #lightbox').on('click', function (e) {
        if (e.target === this || $(e.target).closest('#lbClose').length) { $lb.removeClass('active'); $lbImg.attr('src', ''); }
    });
    $(document).on('keydown', function (e) { if (e.key === 'Escape') $lb.removeClass('active'); });
</script>
<?php
$mc_scripts = ob_get_clean();
require_once "partials/footer.php";
?>
