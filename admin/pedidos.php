<?php
require_once "../config.php";
requiere_login();

$pdo = conectar();

$id_pedido = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$detalle = null;
$items_pedido = [];

// Cambiar estado
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id_pedido"], $_POST["estado"])) {
    csrf_requerir();
    $estados = ["pendiente", "confirmado", "enviado", "entregado", "cancelado"];
    $nuevo_estado = limpiar($_POST["estado"]);
    $id_objetivo = (int)$_POST["id_pedido"];

    if (in_array($nuevo_estado, $estados, true) && $id_objetivo > 0) {
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("SELECT estado FROM pedidos WHERE id = ? FOR UPDATE");
            $stmt->execute([$id_objetivo]);
            $actual = $stmt->fetchColumn();

            if ($actual !== false && $actual !== $nuevo_estado) {
                $stmt = $pdo->prepare("UPDATE pedidos SET estado = ? WHERE id = ?");
                $stmt->execute([$nuevo_estado, $id_objetivo]);

                /* Cancelar repone el stock; reactivar un pedido cancelado lo vuelve a descontar. */
                if ($nuevo_estado === "cancelado") {
                    $pdo->prepare(
                        "UPDATE productos p
                         INNER JOIN (
                             SELECT id_producto, SUM(cantidad) AS cant
                             FROM detalle_pedidos WHERE id_pedido = ? GROUP BY id_producto
                         ) d ON d.id_producto = p.id
                         SET p.stock = p.stock + d.cant"
                    )->execute([$id_objetivo]);
                } elseif ($actual === "cancelado") {
                    $pdo->prepare(
                        "UPDATE productos p
                         INNER JOIN (
                             SELECT id_producto, SUM(cantidad) AS cant
                             FROM detalle_pedidos WHERE id_pedido = ? GROUP BY id_producto
                         ) d ON d.id_producto = p.id
                         SET p.stock = GREATEST(0, p.stock - d.cant)"
                    )->execute([$id_objetivo]);
                }
            }

            $pdo->commit();
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log("MC pedidos estado: " . $e->getMessage());
            $_SESSION["flash"] = "No se pudo actualizar el estado del pedido.";
        }
    }
    redirigir("pedidos.php?id=" . $id_objetivo);
}

if ($id_pedido > 0) {
    $stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id = ?");
    $stmt->execute([$id_pedido]);
    $detalle = $stmt->fetch();
    if ($detalle) {
        $stmt = $pdo->prepare("SELECT * FROM detalle_pedidos WHERE id_pedido = ?");
        $stmt->execute([$id_pedido]);
        $items_pedido = $stmt->fetchAll();
    }
}

$sql = "SELECT p.*,
               (SELECT COUNT(*) FROM detalle_pedidos d WHERE d.id_pedido = p.id) AS cant_items
        FROM pedidos p ORDER BY p.fecha DESC, p.id DESC";
$pedidos = $pdo->query($sql)->fetchAll();

$titulo = $detalle ? "Pedido #" . $detalle["id"] : "Pedidos";

$estado_activo = isset($_GET["estado"]) ? limpiar($_GET["estado"]) : "";
if (in_array($estado_activo, ["pendiente", "confirmado", "enviado", "entregado", "cancelado"], true)) {
    $filtro_estado = $estado_activo;
} else {
    $filtro_estado = "";
}
if ($filtro_estado !== "") {
    $sql_filtro = $pdo->prepare("SELECT p.*, (SELECT COUNT(*) FROM detalle_pedidos d WHERE d.id_pedido = p.id) AS cant_items FROM pedidos p WHERE p.estado = ? ORDER BY p.fecha DESC, p.id DESC");
    $sql_filtro->execute([$filtro_estado]);
    $pedidos = $sql_filtro->fetchAll();
}

require_once "_header.php";
?>
    <?php if (!empty($_SESSION["flash"])): ?>
        <div class="alert alert-warning py-2 small"><?= htmlspecialchars($_SESSION["flash"]) ?></div>
        <?php unset($_SESSION["flash"]); ?>
    <?php endif; ?>
    <?php if ($detalle): ?>
        <a href="pedidos.php" class="btn btn-outline-secondary btn-sm mb-3">&larr; Volver a pedidos</a>

        <div class="row g-3 mb-4">
            <div class="col-12 col-lg-3">
                <div class="stat-card">
                    <div class="lbl">Cliente</div>
                    <div class="fw-bold fs-5"><?= htmlspecialchars($detalle["nombre"]) ?></div>
                    <div class="text-muted small"><?= htmlspecialchars($detalle["telefono"]) ?></div>
                    <a class="btn btn-success btn-sm mt-2"
                       href="https://wa.me/<?= preg_replace("/\D/", "", $detalle["telefono"]) ?>"
                       target="_blank" rel="noopener">Contactar por WhatsApp</a>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="lbl">Localidad</div>
                    <div class="fw-bold fs-5"><?= htmlspecialchars($detalle["localidad"] ?: "—") ?></div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="lbl">Total · Fecha</div>
                    <div class="fw-bold fs-5"><?= precio_ar($detalle["total"]) ?></div>
                    <div class="text-muted small"><?= formato_fecha($detalle["fecha"]) ?></div>
                </div>
            </div>
            <div class="col-12 col-lg-3">
                <div class="stat-card">
                    <div class="lbl">Estado del pedido</div>
                    <div class="fw-bold fs-6 mb-2" style="text-transform:capitalize;color:<?= $detalle["estado"] === "cancelado" ? "#dc3545" : "#0d6efd" ?>"><?= htmlspecialchars($detalle["estado"]) ?></div>
                    <form method="post" class="d-flex gap-2">
                        <?= csrf_campo() ?>
                        <input type="hidden" name="id_pedido" value="<?= (int)$detalle["id"] ?>">
                        <select name="estado" class="form-select form-select-sm">
                            <?php foreach (["pendiente", "confirmado", "enviado", "entregado", "cancelado"] as $est): ?>
                                <option value="<?= $est ?>" <?= $detalle["estado"] === $est ? "selected" : "" ?>><?= $est ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button class="btn btn-sm btn-primary">Actualizar</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">Detalle de productos</div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr><th>Producto</th><th>Precio</th><th>Cantidad</th><th class="text-end">Subtotal</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($items_pedido as $it): ?>
                        <tr>
                            <td class="fw-semibold"><?= htmlspecialchars($it["producto_nombre"]) ?></td>
                            <td><?= precio_ar($it["precio"]) ?></td>
                            <td>x<?= (int)$it["cantidad"] ?></td>
                            <td class="text-end fw-bold"><?= precio_ar((float)$it["precio"] * (int)$it["cantidad"]) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Total</td>
                            <td class="text-end fw-bold"><?= precio_ar($detalle["total"]) ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <?php if (!empty($detalle["mensaje"])): ?>
                <div class="m-3 p-3 bg-light rounded small"><?= nl2br(htmlspecialchars($detalle["mensaje"])) ?></div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm">
            <div class="d-flex flex-wrap align-items-center gap-2 p-3 border-bottom">
                <a href="pedidos.php" class="btn btn-sm <?= $filtro_estado === "" ? "btn-dark" : "btn-outline-dark" ?>">Todos</a>
                <?php foreach (["pendiente", "confirmado", "enviado", "entregado", "cancelado"] as $est): ?>
                    <a href="pedidos.php?estado=<?= $est ?>" class="btn btn-sm <?= $filtro_estado === $est ? "btn-dark" : "btn-outline-dark" ?>"><?= $est ?></a>
                <?php endforeach; ?>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr><th>#</th><th>Cliente</th><th>Teléfono</th><th>Localidad</th><th>Item</th><th>Total</th><th>Estado</th><th>Fecha</th><th></th></tr>
                    </thead>
                    <tbody>
                    <?php if (count($pedidos) === 0): ?>
                        <tr><td colspan="9" class="text-center text-muted py-4">Todavía no hay pedidos.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($pedidos as $p): ?>
                        <tr>
                            <td>#<?= (int)$p["id"] ?></td>
                            <td class="fw-semibold"><?= htmlspecialchars($p["nombre"]) ?></td>
                            <td><?= htmlspecialchars($p["telefono"]) ?></td>
                            <td><?= htmlspecialchars($p["localidad"] ?: "—") ?></td>
                            <td><?= (int)$p["cant_items"] ?></td>
                            <td class="fw-bold"><?= precio_ar($p["total"]) ?></td>
                            <td>
                                <span class="badge rounded-pill text-dark bg-<?= ["pendiente" => "warning", "confirmado" => "info", "enviado" => "primary", "entregado" => "success", "cancelado" => "danger"][$p["estado"]] ?>">
                                    <?= htmlspecialchars($p["estado"]) ?>
                                </span>
                            </td>
                            <td class="text-muted small"><?= formato_fecha($p["fecha"]) ?></td>
                            <td class="text-end"><a href="pedidos.php?id=<?= (int)$p["id"] ?>" class="btn btn-sm btn-outline-dark">Ver</a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
<?php require_once "_footer.php"; ?>