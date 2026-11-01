<?php
require_once "../config.php";
requiere_login();

$pdo = conectar();
$total_productos = (int)$pdo->query("SELECT COUNT(*) FROM productos")->fetchColumn();
$total_categorias = (int)$pdo->query("SELECT COUNT(*) FROM categorias")->fetchColumn();
$total_pedidos = (int)$pdo->query("SELECT COUNT(*) FROM pedidos")->fetchColumn();
$total_ventas = (float)$pdo->query("SELECT COALESCE(SUM(total),0) FROM pedidos")->fetchColumn();

// Pedidos por estado
$por_estado = [];
foreach ($pdo->query("SELECT estado, COUNT(*) AS n, COALESCE(SUM(total),0) AS monto FROM pedidos GROUP BY estado") as $fila) {
    $por_estado[$fila["estado"]] = ["n" => (int)$fila["n"], "monto" => (float)$fila["monto"]];
}

// Ventas últimos 6 meses
$mes = $pdo->query("SELECT DATE_FORMAT(fecha, '%Y-%m') AS ym, COUNT(*) AS n, COALESCE(SUM(total),0) AS monto FROM pedidos WHERE fecha >= DATE_SUB(NOW(), INTERVAL 6 MONTH) GROUP BY ym ORDER BY ym DESC")->fetchAll();

// Productos más vendidos
$mas_vendidos = $pdo->query("SELECT producto_nombre, SUM(cantidad) AS cant, SUM(cantidad*precio) AS monto FROM detalle_pedidos GROUP BY producto_nombre ORDER BY cant DESC LIMIT 5")->fetchAll();

// Stock bajo
$stock_bajo = $pdo->query("SELECT producto, stock FROM productos WHERE stock <= 2 ORDER BY stock ASC, producto")->fetchAll();

$ultimos_pedidos = $pdo->query("SELECT * FROM pedidos ORDER BY fecha DESC, id DESC LIMIT 5")->fetchAll();

$titulo = "Inicio";
require_once "_header.php";
?>
    <div class="row g-3">
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="num"><?= $total_productos ?></div>
                <div class="lbl">Productos</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="num"><?= $total_categorias ?></div>
                <div class="lbl">Categorías</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="num"><?= $total_pedidos ?></div>
                <div class="lbl">Pedidos</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="num"><?= precio_ar($total_ventas) ?></div>
                <div class="lbl">Total vendido</div>
            </div>
        </div>
    </div>

    <div class="card mt-4 border-0 shadow-sm">
        <div class="card-header bg-white fw-bold">Pedidos por estado</div>
        <div class="card-body">
            <?php
            $colores = ["pendiente" => "warning", "confirmado" => "info", "enviado" => "primary", "entregado" => "success", "cancelado" => "danger"];
            $nombres = ["pendiente" => "Pendiente", "confirmado" => "Confirmado", "enviado" => "Enviado", "entregado" => "Entregado", "cancelado" => "Cancelado"];
            foreach ($colores as $est => $color) {
                $cant = $por_estado[$est]["n"] ?? 0;
                $monto = $por_estado[$est]["monto"] ?? 0;
            ?>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge rounded-pill bg-<?= $color ?> text-dark" style="min-width:110px"><?= $nombres[$est] ?></span>
                    <span class="fw-bold"><?= $cant ?> pedido(s)</span>
                    <span class="text-muted small"><?= $monto > 0 ? precio_ar($monto) : "—" ?></span>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold">Productos más vendidos</div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="table-light"><tr><th>Producto</th><th>Cant.</th><th class="text-end">Importe</th></tr></thead>
                        <tbody>
                        <?php if (!$mas_vendidos): ?>
                            <tr><td colspan="3" class="text-center text-muted py-3">Sin ventas todavía.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($mas_vendidos as $mv): ?>
                            <tr>
                                <td class="fw-semibold small"><?= htmlspecialchars($mv["producto_nombre"]) ?></td>
                                <td><?= (int)$mv["cant"] ?></td>
                                <td class="text-end fw-bold"><?= precio_ar($mv["monto"]) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold">Stock bajo (≤ 2)</div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="table-light"><tr><th>Producto</th><th class="text-end">Stock</th></tr></thead>
                        <tbody>
                        <?php if (!$stock_bajo): ?>
                            <tr><td colspan="2" class="text-center text-muted py-3">Todo con stock suficiente. ;)</td></tr>
                        <?php endif; ?>
                        <?php foreach ($stock_bajo as $sb): ?>
                            <tr>
                                <td class="fw-semibold small"><?= htmlspecialchars($sb["producto"]) ?></td>
                                <td class="text-end <?= (int)$sb["stock"] === 0 ? "text-danger fw-bold" : "" ?>"><?= (int)$sb["stock"] ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4 border-0 shadow-sm">
        <div class="card-header bg-white fw-bold">Últimos 6 meses</div>
        <div class="card-body">
            <?php foreach ($mes as $m): ?>
                <div class="d-flex align-items-center gap-3 mb-2">
                    <span class="text-muted small" style="min-width:70px"><strong><?= htmlspecialchars($m["ym"]) ?></strong></span>
                    <span class="text-muted small"><?= (int)$m["n"] ?> pedido(s)</span>
                    <span class="fw-bold small"><?= precio_ar($m["monto"]) ?></span>
                </div>
            <?php endforeach; ?>
            <?php if (!$mes): ?><p class="text-muted small">Sin ventas en los últimos 6 meses.</p><?php endif; ?>
        </div>
    </div>

    <div class="card mt-4 border-0 shadow-sm">
        <div class="card-header bg-white fw-bold">Últimos pedidos</div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Teléfono</th>
                        <th>Localidad</th>
                        <th>Total</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (count($ultimos_pedidos) === 0): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Todavía no hay pedidos.</td></tr>
                <?php endif; ?>
                <?php foreach ($ultimos_pedidos as $p): ?>
                    <tr>
                        <td>#<?= (int)$p["id"] ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($p["nombre"]) ?></td>
                        <td><?= htmlspecialchars($p["telefono"]) ?></td>
                        <td><?= htmlspecialchars($p["localidad"]) ?></td>
                        <td class="fw-bold"><?= precio_ar($p["total"]) ?></td>
                        <td class="text-muted small"><?= formato_fecha($p["fecha"]) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php require_once "_footer.php"; ?>