<?php
require_once "../config.php";
requiere_login();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirigir("categorias.php");
}
csrf_requerir();

$id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;

/* Las categorías base (1–3) no se pueden borrar nunca. */
if ($id > 3) {
    $pdo = conectar();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM productos WHERE id_categoria = ?");
    $stmt->execute([$id]);
    $hay = (int)$stmt->fetchColumn();
    if ($hay === 0) {
        $pdo->prepare("DELETE FROM categorias WHERE id = ?")->execute([$id]);
        $_SESSION["flash"] = "Categoría eliminada.";
    } else {
        $_SESSION["flash"] = "No se puede eliminar: la categoría todavía tiene productos.";
    }
}
redirigir("categorias.php");
