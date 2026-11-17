<?php
require_once "../config.php";
requiere_login();

$pdo = conectar();
$errores = [];
$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

// Datos actuales del producto (si edito)
$producto_actual = null;
if ($id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->execute([$id]);
    $producto_actual = $stmt->fetch();
    if (!$producto_actual) {
        redirigir("productos.php");
    }
}

$nombre = $producto_actual["producto"] ?? "";
$precio = $producto_actual["precio"] ?? "";
$descripcion = $producto_actual["descripcion"] ?? "";
$id_categoria = $producto_actual["id_categoria"] ?? 0;
$imagen_actual = $producto_actual["imagen"] ?? "";
$stock = (int)($producto_actual["stock"] ?? 0);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    csrf_requerir();
    $nombre = limpiar($_POST["producto"] ?? "");
    $precio_input = trim($_POST["precio"] ?? "");
    $descripcion = limpiar($_POST["descripcion"] ?? "");
    $id_categoria = (int)($_POST["id_categoria"] ?? 0);
    $stock = max(0, (int)($_POST["stock"] ?? 0));
    $subir = $_FILES["imagen"] ?? null;

    if ($nombre === "") { $errores[] = "El nombre es obligatorio."; }
    if ($precio_input === "" || !is_numeric(str_replace(",", ".", $precio_input))) {
        $errores[] = "El precio debe ser un número.";
    }
    if ($id_categoria <= 0) { $errores[] = "Elegí una categoría."; }

    $nueva_ruta = $imagen_actual;

    if ($subir && !empty($subir["name"])) {
        if ($subir["error"] !== UPLOAD_ERR_OK) {
            $errores[] = "Error al subir la imagen.";
        } elseif ((int)$subir["size"] > 4 * 1024 * 1024) {
            $errores[] = "La imagen supera los 4 MB. Comprimila antes de subirla.";
        } else {
            $ext = strtolower(pathinfo($subir["name"], PATHINFO_EXTENSION));
            $permitidas = ["jpg", "jpeg", "png", "webp", "gif"];
            $info = @getimagesize($subir["tmp_name"]);
            $mime_ok = $info !== false && in_array($info["mime"] ?? "", ["image/jpeg", "image/png", "image/webp", "image/gif"], true);
            if (!in_array($ext, $permitidas) || !$mime_ok) {
                $errores[] = "El archivo no es una imagen válida (usá jpg, png, webp o gif).";
            } else {
                $dir_dest = dirname(__DIR__) . "/img";
                if (!is_dir($dir_dest)) { mkdir($dir_dest, 0777, true); }
                $archivo = "producto_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $ext;
                if (move_uploaded_file($subir["tmp_name"], $dir_dest . "/" . $archivo)) {
                    if ($imagen_actual !== "" && str_starts_with($imagen_actual, "img/")) {
                        $stmt_img = $pdo->prepare("SELECT COUNT(*) FROM productos WHERE imagen = ? AND id <> ?");
                        $stmt_img->execute([$imagen_actual, $id]);
                        if ((int)$stmt_img->fetchColumn() === 0) {
                            $vieja = dirname(__DIR__) . "/" . $imagen_actual;
                            if (is_file($vieja)) { @unlink($vieja); }
                        }
                    }
                    $nueva_ruta = "img/" . $archivo;
                } else {
                    $errores[] = "No se pudo guardar la imagen.";
                }
            }
        }
    }

    if (count($errores) === 0) {
        $precio_numerico = (float)str_replace(",", ".", $precio_input);
        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE productos SET id_categoria=?, producto=?, precio=?, descripcion=?, imagen=?, stock=? WHERE id=?");
            $stmt->execute([$id_categoria, $nombre, $precio_numerico, $descripcion, $nueva_ruta, $stock, $id]);
            $msg = "Producto actualizado correctamente.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO productos (id_categoria, producto, precio, descripcion, imagen, stock) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$id_categoria, $nombre, $precio_numerico, $descripcion, $nueva_ruta, $stock]);
            $msg = "Producto creado correctamente.";
        }
        $_SESSION["flash"] = $msg;
        redirigir("productos.php");
    }
}

$categorias = $pdo->query("SELECT id, categoria FROM categorias ORDER BY id")->fetchAll();

$titulo = $id > 0 ? "Editar producto" : "Nuevo producto";
require_once "_header.php";
?>
    <div class="row justify-content-center">
        <div class="col-lg-7 col-xl-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <?php if (count($errores) > 0): ?>
                        <div class="alert alert-danger py-2 small">
                            <?php foreach ($errores as $e): ?><div>&bull; <?= htmlspecialchars($e) ?></div><?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="producto_form.php<?= $id > 0 ? "?id=$id" : "" ?>" enctype="multipart/form-data">
                        <?= csrf_campo() ?>
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Nombre del producto</label>
                            <input type="text" name="producto" class="form-control" required value="<?= htmlspecialchars($nombre) ?>">
                        </div>
                        <div class="row g-3">
                            <div class="col-7">
                                <label class="form-label small fw-semibold">Categoría</label>
                                <select name="id_categoria" class="form-select" required>
                                    <option value="">Seleccionar...</option>
                                    <?php foreach ($categorias as $c): ?>
                                        <option value="<?= (int)$c["id"] ?>" <?= $id_categoria === (int)$c["id"] ? "selected" : "" ?>><?= htmlspecialchars($c["categoria"]) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-5">
                                <label class="form-label small fw-semibold">Precio ($)</label>
                                <input type="text" name="precio" class="form-control" required placeholder="13990" value="<?= htmlspecialchars($precio) ?>">
                            </div>
                            <div class="col-5">
                                <label class="form-label small fw-semibold">Stock</label>
                                <input type="number" name="stock" class="form-control" min="0" value="<?= (int)$stock ?>">
                            </div>
                        </div>
                        <div class="mb-3 mt-3">
                            <label class="form-label small fw-semibold">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="3"><?= htmlspecialchars($descripcion) ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Imagen</label>
                            <?php if ($imagen_actual !== ""): ?>
                                <div class="mb-2">
                                    <img src="../<?= htmlspecialchars($imagen_actual) ?>" alt="" style="max-height:90px;border-radius:8px" onerror="this.style.display='none'">
                                </div>
                            <?php endif; ?>
                            <input type="file" name="imagen" class="form-control" accept="image/*">
                            <div class="form-text"><?= $id > 0 ? "Dejalo vacío para mantener la imagen actual." : "JPG, PNG, WEBP o GIF." ?></div>
                        </div>
                        <div class="d-flex gap-2 mt-4">
                            <button class="btn btn-dark fw-semibold px-4"><?= $id > 0 ? "Guardar cambios" : "Crear producto" ?></button>
                            <a href="productos.php" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php require_once "_footer.php"; ?>