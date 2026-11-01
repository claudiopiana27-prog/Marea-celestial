<?php
require_once "../config.php";
requiere_login();

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
if ($id > 0) {
    $pdo = conectar();
    $stmt = $pdo->prepare("SELECT imagen FROM productos WHERE id = ?");
    $stmt->execute([$id]);
    $prod = $stmt->fetch();
    if ($prod) {
        if (!empty($prod["imagen"]) && str_starts_with($prod["imagen"], "img/")) {
            $vieja = dirname(__DIR__) . "/" . $prod["imagen"];
            if (is_file($vieja)) { @unlink($vieja); }
        }
        $stmt = $pdo->prepare("DELETE FROM productos WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION["flash"] = "Producto eliminado.";
    }
}
redirigir("productos.php");