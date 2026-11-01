<?php
require_once "../config.php";
requiere_login();

$pdo = conectar();
$mensaje = $_SESSION["flash"] ?? "";
unset($_SESSION["flash"]);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = limpiar($_POST["categoria"] ?? "");
    if ($nombre !== "") {
        $existe = $pdo->prepare("SELECT COUNT(*) FROM categorias WHERE categoria = ?");
        $existe->execute([$nombre]);
        if ((int)$existe->fetchColumn() > 0) {
            $_SESSION["flash"] = "Esa categoría ya existe.";
        } else {
            $pdo->prepare("INSERT INTO categorias (categoria) VALUES (?)")->execute([$nombre]);
            $_SESSION["flash"] = "Categoría creada.";
        }
        redirigir("categorias.php");
    }
}

$categorias = $pdo->query(
    "SELECT c.id, c.categoria,
            (SELECT COUNT(*) FROM productos p WHERE p.id_categoria = c.id) AS cant
     FROM categorias c ORDER BY c.id"
)->fetchAll();

$titulo = "Categorías";
require_once "_header.php";
?>
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <?php if ($mensaje !== ""): ?>
                <div class="alert alert-<?= $mensaje === "Esa categoría ya existe." ? "warning" : "success" ?> py-2 small"><?= htmlspecialchars($mensaje) ?></div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <form method="post" class="d-flex gap-2">
                        <input type="text" name="categoria" class="form-control" placeholder="Nueva categoría..." required>
                        <button class="btn btn-dark fw-semibold px-4">Agregar</button>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr><th>ID</th><th>Categoría</th><th>Productos</th><th class="text-end">Acciones</th></tr>
                        </thead>
                        <tbody>
                        <?php foreach ($categorias as $c): ?>
                            <tr>
                                <td><?= (int)$c["id"] ?></td>
                                <td class="fw-semibold"><?= htmlspecialchars($c["categoria"]) ?></td>
                                <td><?= (int)$c["cant"] ?></td>
                                <td class="text-end">
                                    <a href="../productos.php?id=<?= (int)$c["id"] ?>" class="btn btn-sm btn-outline-secondary" target="_blank">Ver</a>
                                    <?php if ((int)$c["cant"] === 0 && (int)$c["id"] > 3): ?>
                                        <a href="categoria_eliminar.php?id=<?= (int)$c["id"] ?>"
                                           class="btn btn-sm btn-outline-danger btn-eliminar">Eliminar</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php
function cargar_script_pie(): void { ?>
<script>
$(function () {
    $(".btn-eliminar").on("click", function (e) {
        if (!confirm("¿Seguro que querés eliminar esta categoría?")) { e.preventDefault(); }
    });
});
</script>
<?php }
require_once "_footer.php";