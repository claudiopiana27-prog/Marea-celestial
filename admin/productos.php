<?php
require_once "../config.php";
requiere_login();

$pdo = conectar();
$mensaje = $_SESSION["flash"] ?? "";
unset($_SESSION["flash"]);

$busqueda = isset($_GET["busqueda"]) ? limpiar($_GET["busqueda"]) : "";
$id_cat = isset($_GET["categoria"]) ? (int)$_GET["categoria"] : 0;

$sql = "SELECT p.*, c.categoria AS categoria_nombre
        FROM productos p
        INNER JOIN categorias c ON c.id = p.id_categoria
        WHERE 1=1";
$params = [];
if ($busqueda !== "") {
    $sql .= " AND p.producto LIKE ?";
    $params[] = "%$busqueda%";
}
if ($id_cat > 0) {
    $sql .= " AND p.id_categoria = ?";
    $params[] = $id_cat;
}
$sql .= " ORDER BY p.id";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$productos = $stmt->fetchAll();

$categorias = $pdo->query("SELECT id, categoria FROM categorias ORDER BY id")->fetchAll();

$titulo = "Productos";
require_once "_header.php";
?>
    <div class="d-flex flex-wrap gap-2 mb-3">
        <a href="producto_form.php" class="btn btn-dark fw-semibold">+ Nuevo producto</a>
        <form class="d-flex gap-2 ms-auto" method="get">
            <select name="categoria" class="form-select w-auto">
                <option value="0">Todas las categorías</option>
                <?php foreach ($categorias as $c): ?>
                    <option value="<?= (int)$c["id"] ?>" <?= $id_cat === (int)$c["id"] ? "selected" : "" ?>><?= htmlspecialchars($c["categoria"]) ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" name="busqueda" class="form-control w-auto" placeholder="Buscar..." value="<?= htmlspecialchars($busqueda) ?>">
            <button class="btn btn-outline-secondary" type="submit">Buscar</button>
        </form>
    </div>

    <?php if ($mensaje !== ""): ?>
        <div class="alert alert-success py-2 small"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Imagen</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th class="text-center">Stock</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (count($productos) === 0): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">No hay productos que coincidan.</td></tr>
                <?php endif; ?>
                <?php foreach ($productos as $p): ?>
                    <tr>
                        <td><?= (int)$p["id"] ?></td>
                        <td><img class="thumb" src="../<?= htmlspecialchars($p["imagen"]) ?>" alt="" onerror="this.style.visibility='hidden'"></td>
                        <td class="fw-semibold">
                            <?= htmlspecialchars($p["producto"]) ?>
                            <a href="../producto.php?id=<?= (int)$p["id"] ?>" target="_blank" class="small ms-1" style="color:#6c757d;text-decoration:none" title="Ver ficha">&rarr;</a>
                        </td>
                        <td><?= htmlspecialchars($p["categoria_nombre"]) ?></td>
                        <td><?= precio_ar($p["precio"]) ?></td>
                        <td class="text-center">
                            <span class="badge rounded-pill <?= (int)$p["stock"] > 0 ? "bg-success" : "bg-danger" ?>">
                                <?= (int)$p["stock"] ?>
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="producto_form.php?id=<?= (int)$p["id"] ?>" class="btn btn-sm btn-outline-warning">Editar</a>
                            <a href="producto_eliminar.php?id=<?= (int)$p["id"] ?>"
                               class="btn btn-sm btn-outline-danger btn-eliminar">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php
function cargar_script_pie(): void { ?>
<script>
$(function () {
    $(".btn-eliminar").on("click", function (e) {
        if (!confirm("¿Seguro que querés eliminar este producto?")) {
            e.preventDefault();
        }
    });
});
</script>
<?php }
require_once "_footer.php";