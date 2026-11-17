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
        if (!empty($prod["imagen"]) && str_starts_with($prod["imagen"], "img/")) {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM productos WHERE imagen = ? AND id <> ?");
            $stmt->execute([$prod["imagen"], $id]);
            $en_uso = (int)$stmt->fetchColumn();
            if ($en_uso === 0) {
                $vieja = dirname(__DIR__) . "/" . $prod["imagen"];
                if (is_file($vieja)) { @unlink($vieja); }
            }
        }
        $stmt = $pdo->prepare("DELETE FROM productos WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION["flash"] = "Producto eliminado.";
    }
}
redirigir("productos.php");
