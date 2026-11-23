<?php
require_once "../config.php";
requiere_login();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirigir("productos.php");
}
csrf_requerir();

$id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;
if ($id > 0) {
    $pdo = conectar();
    $stmt = $pdo->prepare("SELECT imagen FROM productos WHERE id = ?");
    $stmt->execute([$id]);
    $prod = $stmt->fetch();
    if ($prod) {
        $imagen = $prod["imagen"] ?? "";
        $en_uso = 0;
        if (!empty($imagen) && str_starts_with($imagen, "img/")) {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM productos WHERE imagen = ? AND id <> ?");
            $stmt->execute([$imagen, $id]);
            $en_uso = (int)$stmt->fetchColumn();
        }

        /* Primero el registro; la imagen se borra solo si el DELETE terminó bien. */
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("DELETE FROM productos WHERE id = ?");
            $stmt->execute([$id]);
            $pdo->commit();
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log("MC eliminar producto: " . $e->getMessage());
            $_SESSION["flash"] = "No se pudo eliminar el producto.";
            redirigir("productos.php");
        }

        if ($en_uso === 0 && !empty($imagen) && str_starts_with($imagen, "img/")) {
            $vieja = dirname(__DIR__) . "/" . $imagen;
            if (is_file($vieja)) { @unlink($vieja); }
        }
        $_SESSION["flash"] = "Producto eliminado.";
    }
}
redirigir("productos.php");
