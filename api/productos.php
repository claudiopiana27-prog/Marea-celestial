<?php
require_once dirname(__DIR__) . "/config.php";

try {
    $pdo = conectar();

    $cat = isset($_GET["categoria"]) ? (int)$_GET["categoria"] : 0;
    $busqueda = isset($_GET["busqueda"]) ? limpiar($_GET["busqueda"]) : "";

    $sql = "SELECT p.id, p.id_categoria, c.categoria AS categoria_nombre,
                   p.producto, p.precio, p.imagen, p.descripcion
            FROM productos p
            INNER JOIN categorias c ON c.id = p.id_categoria
            WHERE 1=1";
    $params = [];

    if ($cat > 0) {
        $sql .= " AND p.id_categoria = ?";
        $params[] = $cat;
    }
    if ($busqueda !== "") {
        $sql .= " AND (p.producto LIKE ? OR p.descripcion LIKE ?)";
        $params[] = "%$busqueda%";
        $params[] = "%$busqueda%";
    }
    $sql .= " ORDER BY p.id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $productos = $stmt->fetchAll();

    foreach ($productos as &$p) {
        $p["precio"] = (float)$p["precio"];
        $p["precio_formateado"] = precio_ar($p["precio"]);
    }
    unset($p);

    json_response([
        "ok" => true,
        "total" => count($productos),
        "productos" => $productos,
    ]);
} catch (Throwable $e) {
    json_response(["ok" => false, "error" => $e->getMessage()], 500);
}