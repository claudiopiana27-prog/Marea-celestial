<?php
require_once "config.php";

$id_categoria = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$cat_actual = "Ver todos";
$cats = [];
$productos = [];

try {
    $pdo = conectar();
    $cats = $pdo->query("SELECT id, categoria, (SELECT COUNT(*) FROM productos p WHERE p.id_categoria = c.id) AS cant FROM categorias c ORDER BY c.id")->fetchAll();
    if ($id_categoria > 0) {
        $stmt = $pdo->prepare("SELECT p.*, c.categoria AS cat_nombre FROM productos p INNER JOIN categorias c ON c.id = p.id_categoria WHERE p.id_categoria = ? ORDER BY p.id");
        $stmt->execute([$id_categoria]);
    } else {
        $stmt = $pdo->query("SELECT p.*, c.categoria AS cat_nombre FROM productos p INNER JOIN categorias c ON c.id = p.id_categoria ORDER BY p.id_categoria, p.id");
    }
    $productos = $stmt->fetchAll();
} catch (Throwable $e) {
    $mc_error_bd = $e->getMessage();
}

$mc_titulo      = "Catálogo | Marea Celestial";
$mc_descripcion = "Explorá el catálogo de Marea Celestial: bolsos, carteras y monederos en ecocuero. Comprá directo por WhatsApp.";
$mc_og_titulo   = "Catálogo | Marea Celestial";
$mc_og_imagen   = "img/og-image.jpg";
$mc_pagina      = "productos";

ob_start();
?>
<style>
    body { padding-top: 72px; }

    .chip {
        text-decoration: none; white-space: nowrap;
        height: 38px; display: inline-flex; align-items: center;
        padding: 0 18px; border-radius: var(--radius-full);
        border: 1.5px solid var(--border); background: transparent;
        color: var(--fg-muted); font-size: var(--text-sm); font-weight: 600;
        transition: all var(--dur-base) var(--ease-out);
    }
    .chip:hover { border-color: var(--gold); color: var(--gold); background: var(--gold-subtle); }
    .chip.active {
        background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        border-color: transparent; color: var(--navy-dark);
        box-shadow: 0 4px 16px var(--gold-glow);
    }

    /* Product cards 3D */
    .product-card {
        position: relative; border-radius: var(--radius-lg); overflow: hidden;
        background: var(--bg-card); border: 1.5px solid var(--border);
        transition: all var(--dur-slow) var(--ease-out);
        transform-style: preserve-3d; perspective: 800px;
        cursor: pointer;
    }
    .product-card:hover {
        border-color: var(--gold);
        transform: translateY(-8px) rotateX(3deg);
        box-shadow: 0 20px 40px rgba(90, 74, 58, 0.16);
    }
    .product-card .img-wrap {
        width: 100%; aspect-ratio: 1/1; overflow: hidden;
        background: var(--bg-elevated); position: relative;
    }
    .product-card .img-wrap::after {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(180deg, transparent 60%, rgba(0,0,0,0.3) 100%);
        opacity: 0; transition: opacity var(--dur-base);
    }
    .product-card:hover .img-wrap::after { opacity: 1; }
    .product-card img {
        width: 100%; height: 100%; object-fit: cover; display: block;
        transition: transform var(--dur-slower) var(--ease-out);
    }
    .product-card:hover img { transform: scale(1.06); }
    .product-card .info { padding: var(--space-3) var(--space-3) var(--space-4); }
    .product-card h3 {
        font-family: var(--font-sans);
        font-size: var(--text-sm); font-weight: 600; color: var(--fg);
        margin-bottom: 2px; line-height: 1.3;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }
    .product-card .desc {
        font-size: var(--text-xs); color: var(--fg-subtle); margin-bottom: 6px;
        display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;
    }
    .product-card .price { font-weight: 700; font-size: var(--text-base); color: var(--gold-dark); }
    .product-card .shine {
        position: absolute; inset: 0; pointer-events: none;
        background: linear-gradient(135deg, rgba(201,162,39,0.08) 0%, transparent 50%);
        opacity: 0; transition: opacity var(--dur-base); border-radius: inherit;
    }
    .product-card:hover .shine { opacity: 1; }
    .badge-stock-card {
        position: absolute; top: 10px; left: 10px; z-index: 3;
        background: rgba(239,68,68,0.92); color: #fff;
        font-size: var(--text-xs); font-weight: 700; letter-spacing: 0.02em;
        padding: 5px 12px; border-radius: var(--radius-full);
        box-shadow: 0 4px 12px rgba(90, 74, 58, 0.22);
    }
    .product-card.sin-stock { opacity: 0.75; }
    .badge-stock {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 5px 14px; border-radius: var(--radius-full); font-size: var(--text-xs); font-weight: 700;
    }
    .badge-stock.ok { background: rgba(34,197,94,0.13); color: #15803d; border: 1px solid rgba(34,197,94,0.35); }
    .badge-stock.no { background: rgba(239,68,68,0.12); color: #b91c1c; border: 1px solid rgba(239,68,68,0.32); }

    .qty-btn {
        width: 30px; height: 30px; border-radius: 50%;
        border: 1px solid var(--border); background: transparent;
        color: var(--fg); font-weight: 700; font-size: 1rem;
        display: inline-flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all var(--dur-fast);
    }
    .qty-btn:hover { border-color: var(--gold); color: var(--gold); background: var(--gold-subtle); }
    #catsBar { position: sticky; top: 72px; }
</style>
<?php
$mc_head_extra = ob_get_clean();
require_once "partials/header.php";
?>

    <!-- Chips de categoría + buscador -->
    <div class="z-[150] w-100 glass" id="catsBar" style="box-shadow:0 4px 12px rgba(90, 74, 58, 0.10)">
        <div class="d-flex align-items-center gap-2 py-2 px-3">
            <div class="position-relative flex-grow-1" style="min-width:0">
                <input type="search" id="buscador" class="form-control" placeholder="Buscar producto (ej: cartera, billetera, tote)"
                       aria-label="Buscar producto" autocomplete="off"
                       style="border-radius:var(--radius-full);border:1.5px solid var(--border);background:rgba(255,255,255,0.04);color:var(--fg);font-size:var(--text-sm);padding-left:36px">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     style="position:absolute;left:12px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:var(--fg-subtle)"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </div>
        </div>
        <div class="d-flex gap-2 overflow-auto py-2 px-3" style="scrollbar-width:none">
            <a href="productos.php" class="chip <?= $id_categoria === 0 ? 'active' : '' ?>">Ver todos</a>
            <?php foreach ($cats as $cat):
                $activo = ($id_categoria === (int)$cat["id"]) ? "active" : "";
                if ($id_categoria === (int)$cat["id"]) { $cat_actual = $cat["categoria"]; }
            ?>
                <a href="productos.php?id=<?= (int)$cat["id"] ?>" class="chip <?= $activo ?>"><?= htmlspecialchars($cat["categoria"]) ?></a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Productos -->
    <div class="container" style="padding-top:24px;padding-bottom:100px">
        <nav aria-label="breadcrumb" class="mb-1" style="font-size:var(--text-sm);color:var(--fg-subtle)">
            <a href="index.php" style="color:var(--gold-dark);text-decoration:none">Inicio</a>
            <span style="color:var(--fg-subtle)"> &rarr; </span>
            <a href="productos.php" style="color:var(--gold-dark);text-decoration:none">Catálogo</a>
            <?php if ($id_categoria > 0): ?>
                <span style="color:var(--fg-subtle)"> &rarr; </span>
                <span style="color:var(--fg)"><?= htmlspecialchars($cat_actual) ?></span>
            <?php endif; ?>
        </nav>
        <div class="accent-band" aria-hidden="true"></div>
        <div class="d-flex align-items-center gap-2 mb-2" id="resultadoBusqueda" style="display:none;color:var(--fg-subtle);font-size:var(--text-sm)"></div>
        <div class="row g-3" id="gridProductos">
        <?php
        $grupo_actual = null;
        $conteo_cat = [];
        foreach ($productos as $pf) $conteo_cat[(int)$pf["id_categoria"]] = ($conteo_cat[(int)$pf["id_categoria"]] ?? 0) + 1;
        foreach ($productos as $fila):
            $precio = "$" . number_format((float)$fila["precio"], 0, ",", ".");
            if ($id_categoria === 0 && $grupo_actual !== $fila["id_categoria"]):
                $grupo_actual = $fila["id_categoria"];
        ?>
            <div class="col-12" data-search-encabezado="1" data-encabezado="<?= htmlspecialchars($fila['cat_nombre']) ?>">
                <div class="d-flex align-items-center gap-3 mt-4 mb-1">
                    <h2 class="display-card mb-0" style="color:var(--fg)"><?= htmlspecialchars($fila['cat_nombre']) ?></h2>
                    <span class="badge badge-gold"><?= (int)$conteo_cat[$grupo_actual] ?> productos</span>
                    <div class="flex-grow-1" style="height:1px;background:linear-gradient(90deg,var(--border-strong),transparent)"></div>
                </div>
            </div>
        <?php endif; ?>
            <div class="col-6 col-md-4 col-lg-3" data-search-tarjeta="1" data-encabezado="<?= htmlspecialchars($fila['cat_nombre']) ?>" data-nombre="<?= htmlspecialchars($fila['producto'], ENT_QUOTES) ?>" data-desc="<?= htmlspecialchars($fila['descripcion'] ?? '', ENT_QUOTES) ?>">
                <div class="product-card"
                     onclick="abrirProducto(this)"
                     role="button" tabindex="0"
                     onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();abrirProducto(this);}"
                     data-id="<?= (int)$fila['id'] ?>"
                     data-img="<?= htmlspecialchars(imagen_producto($fila['imagen'])) ?>"
                     data-thumb="<?= htmlspecialchars(imagen_thumb($fila['imagen'])) ?>"
                     data-nombre="<?= htmlspecialchars($fila['producto'], ENT_QUOTES) ?>"
                     data-desc="<?= htmlspecialchars($fila['descripcion'] ?? '', ENT_QUOTES) ?>"
                     data-stock="<?= (int)$fila['stock'] ?>"
                     data-precio="<?= htmlspecialchars($precio) ?>">
                    <div class="img-wrap">
                        <img src="<?= htmlspecialchars(imagen_thumb($fila['imagen'])) ?>"
                             onerror="this.style.display='none'"
                             alt="<?= htmlspecialchars($fila['producto']) ?>"
                             loading="lazy">
                        <?php if ((int)$fila['stock'] <= 0): ?>
                            <span class="badge-stock-card">Agotado</span>
                        <?php elseif ((int)$fila['id'] >= 30): ?>
                            <span class="badge-nuevo badge-nuevo--pos">Nuevo</span>
                        <?php endif; ?>
                    </div>
                    <div class="shine"></div>
                    <div class="info">
                        <h3><?= htmlspecialchars($fila['producto']) ?></h3>
                        <?php if (!empty($fila['descripcion'])): ?>
                            <p class="desc"><?= htmlspecialchars($fila['descripcion']) ?></p>
                        <?php endif; ?>
                        <span class="price"><?= htmlspecialchars($precio) ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    </div>

    <!-- Modal de producto -->
    <div class="modal fade" id="modalProducto" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-2xl)">
                <div class="modal-header" style="border-color:var(--border)">
                    <h5 class="modal-title" id="modalLabel" style="font-family:var(--font-display);color:var(--fg)"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4 align-items-start">
                        <div class="col-12 col-md-6">
                            <img id="modalImg" src="" alt="" style="width:100%;aspect-ratio:1/1;object-fit:cover;border-radius:var(--radius-lg);border:1px solid var(--border)">
                        </div>
                        <div class="col-12 col-md-6">
                            <span class="badge-stock" id="modalStock" style="display:none"></span>
                            <p id="modalDesc" class="text-fg-muted small mt-3 mb-2"></p>
                            <p class="fw-bold mb-3" id="modalPrecio" style="font-size:1.5rem;color:var(--gold-dark)"></p>
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <span class="text-fg-muted small">Cantidad</span>
                                <div class="d-flex align-items-center gap-2">
                                    <button class="qty-btn" id="cantMenos" type="button" aria-label="Quitar uno">−</button>
                                    <span id="cantValor" class="fw-bold" style="min-width:24px;text-align:center">1</span>
                                    <button class="qty-btn" id="cantMas" type="button" aria-label="Agregar uno">+</button>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <button class="btn btn-primary" id="btnAgregar" type="button">Agregar al carrito</button>
                                <a class="btn btn-outline" id="modalFicha" href="#">Ver ficha completa</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
ob_start();
?>
<script>
    function abrirProducto(el) {
        const d = $(el).data();
        const stock = parseInt(d.stock || 0, 10);
        window._prodActual = {
            id: d.id, nombre: d.nombre, desc: d.desc, imagen: d.img,
            precio: d.precio, precioNum: parseFloat(String(d.precio).replace(/[^0-9]/g, ''))
        };
        window._cantidad = 1;
        $('#modalLabel').text(d.nombre);
        $('#modalImg').attr('src', d.img);
        $('#modalDesc').text(d.desc);
        $('#modalPrecio').text(d.precio);
        $('#cantValor').text(1);
        $('#modalFicha').attr('href', 'producto.php?id=' + d.id);
        const $stock = $('#modalStock');
        const $btn = $('#btnAgregar');
        if (stock <= 0) {
            $stock.show().removeClass('ok').addClass('no').text('Agotado');
            $btn.prop('disabled', true).text('Producto agotado');
        } else {
            $stock.show().removeClass('no').addClass('ok').text('En stock');
            $btn.prop('disabled', false).html('<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg> Agregar al carrito');
        }
        new bootstrap.Modal(document.getElementById('modalProducto')).show();
    }

    $(function () {
        $('#cantMas').on('click', function () { window._cantidad = (window._cantidad || 1) + 1; $('#cantValor').text(window._cantidad); });
        $('#cantMenos').on('click', function () { if ((window._cantidad || 1) > 1) { window._cantidad--; $('#cantValor').text(window._cantidad); } });

        $('#btnAgregar').on('click', function () {
            const p = window._prodActual; if (!p) return;
            mcAgregarAlCarrito({
                id: p.id, nombre: p.nombre, precio: p.precioNum,
                precioForm: p.precio, imagen: p.imagen,
                cantidad: window._cantidad || 1
            });
            bootstrap.Modal.getInstance(document.getElementById('modalProducto')).hide();
            mcToast('"' + p.nombre + '" agregado al carrito');
            setTimeout(mcAbrirCarrito, 400);
        });

        $('#buscador').on('input', function () {
            const term = ($(this).val() || '').trim().toLowerCase();
            let visiblesTotal = 0;
            $('#gridProductos').children('[data-search-tarjeta="1"]').each(function () {
                const nombre = ($(this).data('nombre') || '').toLowerCase();
                const desc = ($(this).data('desc') || '').toLowerCase();
                const coincide = !term || (nombre + ' ' + desc).indexOf(term) !== -1;
                $(this).toggleClass('d-none', !coincide);
                if (coincide) visiblesTotal++;
            });
            $('#gridProductos').children('[data-search-encabezado="1"]').each(function () {
                const nombre = $(this).data('encabezado'); let visibles = 0;
                $('#gridProductos').children('[data-search-tarjeta="1"]').not('.d-none').each(function () {
                    if ($(this).data('encabezado') === nombre) visibles++;
                });
                $(this).toggleClass('d-none', visibles === 0);
            });
            $('#resultadoBusqueda').toggle(!!term).text(term ? visiblesTotal + ' resultado(s)' : '');
        });

        if (typeof mcActualizarCarrito === 'function') mcActualizarCarrito();
    });
</script>
<?php
$mc_scripts = ob_get_clean();
require_once "partials/footer.php";
?>
