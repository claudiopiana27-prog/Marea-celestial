<?php
require_once dirname(__DIR__) . "/config.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    json_response(["ok" => false, "error" => "Método no permitido"], 405);
}

$entrada = json_decode(file_get_contents("php://input"), true);
if (!is_array($entrada)) {
    json_response(["ok" => false, "error" => "JSON inválido"], 400);
}

$nombre = mb_substr(limpiar($entrada["nombre"] ?? ""), 0, 120);
$telefono = mb_substr(limpiar($entrada["telefono"] ?? ""), 0, 40);
$localidad = mb_substr(limpiar($entrada["localidad"] ?? ""), 0, 120);
$mensaje = mb_substr(limpiar($entrada["mensaje"] ?? ""), 0, 1000);
$items = $entrada["items"] ?? [];
$metodo_pago = limpiar($entrada["metodo_pago"] ?? "whatsapp");

$metodos_validos = ["whatsapp", "transferencia", "mercadopago"];
if (!in_array($metodo_pago, $metodos_validos, true)) {
    $metodo_pago = "whatsapp";
}

if ($nombre === "" || $telefono === "") {
    json_response(["ok" => false, "error" => "Faltan nombre o teléfono"], 400);
}
if (!is_array($items) || count($items) === 0) {
    json_response(["ok" => false, "error" => "El carrito está vacío"], 400);
}
if (count($items) > 30) {
    json_response(["ok" => false, "error" => "El carrito tiene demasiados productos distintos."], 400);
}

/* Normalizar items: agrupar cantidades por producto y validar rangos. */
$pedido_items = [];
foreach ($items as $item) {
    if (!is_array($item)) {
        continue;
    }
    $id_producto = (int)($item["id"] ?? 0);
    $cantidad = (int)($item["cantidad"] ?? 1);
    if ($id_producto <= 0 || $cantidad <= 0) {
        continue;
    }
    if ($cantidad > 99) {
        json_response(["ok" => false, "error" => "La cantidad máxima por producto es 99."], 400);
    }
    if (!isset($pedido_items[$id_producto])) {
        $pedido_items[$id_producto] = 0;
    }
    $pedido_items[$id_producto] += $cantidad;
    if ($pedido_items[$id_producto] > 99) {
        json_response(["ok" => false, "error" => "La cantidad máxima por producto es 99."], 400);
    }
}
if (count($pedido_items) === 0) {
    json_response(["ok" => false, "error" => "El carrito está vacío"], 400);
}

try {
    $pdo = conectar();
    $pdo->beginTransaction();

    $total = 0;
    $detalles = [];
    $sin_stock = [];

    /* Bloqueo las filas para que dos pedidos simultáneos no vendan la misma unidad. */
    $stmt_prod = $pdo->prepare("SELECT id, producto, precio, stock FROM productos WHERE id = ? FOR UPDATE");
    foreach ($pedido_items as $id_producto => $cantidad) {
        $stmt_prod->execute([$id_producto]);
        $prod = $stmt_prod->fetch();

        if (!$prod) {
            $sin_stock[] = "un producto del carrito ya no está disponible";
            continue;
        }

        $stock = (int)$prod["stock"];
        if ($stock <= 0) {
            $sin_stock[] = $prod["producto"] . " (agotado)";
            continue;
        }
        if ($cantidad > $stock) {
            $sin_stock[] = $prod["producto"] . " (solo quedan " . $stock . ")";
            continue;
        }

        $subtotal = (float)$prod["precio"] * $cantidad;
        $total += $subtotal;

        $detalles[] = [
            "id_producto" => (int)$prod["id"],
            "producto_nombre" => $prod["producto"],
            "precio" => (float)$prod["precio"],
            "cantidad" => $cantidad,
            "subtotal" => $subtotal,
        ];
    }

    if (count($detalles) === 0) {
        $pdo->rollBack();
        $motivo = count($sin_stock) > 0
            ? implode(", ", $sin_stock)
            : "los productos del carrito ya no están disponibles";
        json_response([
            "ok" => false,
            "error" => "No pudimos confirmar el pedido: " . $motivo . ".",
        ], 409);
    }

    if (count($sin_stock) > 0) {
        $pdo->rollBack();
        json_response([
            "ok" => false,
            "error" => "Sin stock suficiente para: " . implode(", ", $sin_stock)
                . ". Sacalos del carrito para continuar.",
        ], 409);
    }

    $etiqueta_metodo = [
        "whatsapp" => "Confirmado por WhatsApp",
        "transferencia" => "Transferencia bancaria",
        "mercadopago" => "MercadoPago",
    ][$metodo_pago];

    $mensaje_pedido = "Hola, quiero pedir:%0A";
    foreach ($detalles as $d) {
        $mensaje_pedido .= "- " . $d["producto_nombre"] . " x" . $d["cantidad"]
            . " = " . precio_ar($d["subtotal"]) . "%0A";
    }
    if (WHATSAPP !== "") {
        $mensaje_pedido .= "%0ATotal: " . precio_ar($total);
        $mensaje_pedido .= "%0APago: " . $etiqueta_metodo;
        if ($mensaje !== "") {
            $mensaje_pedido .= "%0A" . $mensaje;
        }
        $mensaje_pedido .= "%0ANombre: " . rawurlencode($nombre) . "%0ATeléfono: " . rawurlencode($telefono);
        if ($localidad !== "") {
            $mensaje_pedido .= "%0ALocalidad: " . rawurlencode($localidad);
        }
    }

    /* El metodo de pago se antepone al mensaje para que quede registrado en la BD. */
    $mensaje_registrado = "[Pago: " . $etiqueta_metodo . "] " . $mensaje;

    $stmt = $pdo->prepare("INSERT INTO pedidos (nombre, telefono, localidad, total, mensaje) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nombre, $telefono, $localidad, $total, $mensaje_registrado]);
    $id_pedido = (int)$pdo->lastInsertId();

    $stmt = $pdo->prepare(
        "INSERT INTO detalle_pedidos (id_pedido, id_producto, producto_nombre, precio, cantidad)
         VALUES (?, ?, ?, ?, ?)"
    );
    foreach ($detalles as $d) {
        $stmt->execute([$id_pedido, $d["id_producto"], $d["producto_nombre"], $d["precio"], $d["cantidad"]]);
    }

    /* Descontar stock solo si sigue alcanzando (doble control dentro de la transaccion). */
    $stmt = $pdo->prepare("UPDATE productos SET stock = stock - ? WHERE id = ? AND stock >= ?");
    foreach ($detalles as $d) {
        $stmt->execute([$d["cantidad"], $d["id_producto"], $d["cantidad"]]);
        if ($stmt->rowCount() === 0) {
            throw new RuntimeException("Stock insuficiente al descontar el producto " . $d["id_producto"]);
        }
    }

    $pdo->commit();

    $link = (WHATSAPP !== "")
        ? "https://wa.me/" . preg_replace("/\D/", "", WHATSAPP) . "?text=" . $mensaje_pedido
        : null;

    json_response([
        "ok" => true,
        "id_pedido" => $id_pedido,
        "total" => $total,
        "total_formateado" => precio_ar($total),
        "metodo_pago" => $metodo_pago,
        "link_whatsapp" => $link,
    ]);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("MC api/pedido: " . $e->getMessage());
    json_response(["ok" => false, "error" => "No se pudo registrar el pedido. Intentá de nuevo."], 500);
}
