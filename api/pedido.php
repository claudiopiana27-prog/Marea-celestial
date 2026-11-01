<?php
require_once dirname(__DIR__) . "/config.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    json_response(["ok" => false, "error" => "Método no permitido"], 405);
}

$entrada = json_decode(file_get_contents("php://input"), true);
if (!is_array($entrada)) {
    json_response(["ok" => false, "error" => "JSON inválido"], 400);
}

$nombre = limpiar($entrada["nombre"] ?? "");
$telefono = limpiar($entrada["telefono"] ?? "");
$localidad = limpiar($entrada["localidad"] ?? "");
$mensaje = limpiar($entrada["mensaje"] ?? "");
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

try {
    $pdo = conectar();

    $total = 0;
    $detalles = [];
    $mensaje_pedido = "Hola, quiero pedir:%0A";
    $sin_stock = [];

    foreach ($items as $item) {
        $id_producto = (int)($item["id"] ?? 0);
        $cantidad = max(1, (int)($item["cantidad"] ?? 1));

        $stmt = $pdo->prepare("SELECT id, producto, precio, stock FROM productos WHERE id = ?");
        $stmt->execute([$id_producto]);
        $prod = $stmt->fetch();

        if (!$prod) {
            continue;
        }

        /* Control de stock: no se puede pedir mas de lo disponible. */
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
            "id_producto" => $id_producto,
            "producto_nombre" => $prod["producto"],
            "precio" => (float)$prod["precio"],
            "cantidad" => $cantidad,
        ];

        $mensaje_pedido .= "- " . $prod["producto"] . " x" . $cantidad . " = " . precio_ar($subtotal) . "%0A";
    }

    if (count($detalles) === 0) {
        json_response([
            "ok" => false,
            "error" => "No hay stock disponible para: " . implode(", ", $sin_stock),
        ], 409);
    }

    if (count($sin_stock) > 0) {
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

    // Guardar pedido en BD
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

    /* Descontar stock de lo pedido. */
    $stmt = $pdo->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");
    foreach ($detalles as $d) {
        $stmt->execute([$d["cantidad"], $d["id_producto"]]);
    }

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
    json_response(["ok" => false, "error" => $e->getMessage()], 500);
}