<?php
require_once "../config.php";
requiere_login();

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
if ($id > 0) {
    $pdo = conectar();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM productos WHERE id_categoria = ?");
    $stmt->execute([$id]);
    $hay = (int)$stmt->fetchColumn();
    if ($hay === 0) {
        $pdo->prepare("DELETE FROM categorias WHERE id = ?")->execute([$id]);
        $_SESSION["flash"] = "Categoría eliminada.";
    }
}
redirigir("categorias.php");