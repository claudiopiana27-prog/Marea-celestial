<?php
require_once "../config.php";
requiere_login();

$pdo = conectar();
$raiz = dirname(__DIR__);
$dir_respaldos = __DIR__ . "/_backups";

function normalizar_texto(string $texto): string
{
    $texto = strtolower(trim($texto));
    $texto = preg_replace("/[^a-z0-9]+/u", " ", $texto);
    return trim((string)$texto);
}

function extraer_sku(string $texto): string
{
    if (preg_match("/\b([A-Za-z]{1,3})\s*[-_]\s*(\d{1,3})\b/", $texto, $m)) {
        return strtoupper($m[1]) . "-" . $m[2];
    }
    return "";
}

function parsear_precio(string $texto): ?float
{
    $n = preg_replace("/[^0-9.,]/", "", $texto);
    if ($n === "") {
        return null;
    }
    $coma = strrpos($n, ",");
    $punto = strrpos($n, ".");
    if ($coma !== false && $punto !== false) {
        if ($coma > $punto) {
            $n = str_replace(".", "", $n);
            $n = str_replace(",", ".", $n);
        } else {
            $n = str_replace(",", "", $n);
        }
    } elseif ($coma !== false) {
        $n = (strlen($n) - $coma - 1) === 2 ? str_replace(",", ".", $n) : str_replace(",", "", $n);
    } elseif ($punto !== false) {
        $n = (strlen($n) - $punto - 1) === 2 ? $n : str_replace(".", "", $n);
    }
    return is_numeric($n) ? round((float)$n, 2) : null;
}

function parsear_stock(string $texto): ?int
{
    $t = strtolower(trim($texto));
    if ($t === "") {
        return null;
    }
    if (preg_match("/agotad|sin\s*stock|ultimas?\s*(unidades|piezas)|consultar|sin\s*existencias/", $t)) {
        return 0;
    }
    if (preg_match("/(?:stock|existencias|unidades|uds)\s*[:=]?\s*(\d{1,4})/u", $t, $m)) {
        return (int)$m[1];
    }
    if (preg_match("/\b(\d{1,4})\s*(?:unidades|uds|u)\b/u", $t, $m)) {
        return (int)$m[1];
    }
    if (preg_match("/^\s*(\d{1,4})\s*$/", $t, $m)) {
        return (int)$m[1];
    }
    return null;
}

function resolver_imagen(string $token, string $raiz, string $sku): array
{
    $avisos = [];
    $token = trim(str_replace("\\", "/", $token));
    $token = ltrim($token, "/");
    if ($token === "") {
        return [null, $avisos];
    }

    $candidatas = [];
    if (stripos($token, "img/") === 0) {
        $candidatas[] = $token;
    } else {
        $candidatas[] = "img/catalogo/" . $token;
        $candidatas[] = "img/fuentes/" . $token;
        $candidatas[] = "img/" . $token;
    }

    $base = pathinfo($token, PATHINFO_FILENAME);
    $ext = strtolower(pathinfo($token, PATHINFO_EXTENSION));
    if ($ext === "" || strlen($ext) > 4) {
        foreach (["jpg", "jpeg", "png", "webp"] as $e) {
            $candidatas[] = "img/catalogo/" . $base . "." . $e;
            $candidatas[] = "img/fuentes/" . $base . "." . $e;
        }
    }

    foreach ($candidatas as $c) {
        if (is_file($raiz . "/" . $c)) {
            return [$c, $avisos];
        }
    }

    $aguja = str_replace("-", "", strtolower($sku));
    if ($aguja !== "" && is_dir($raiz . "/img/catalogo")) {
        $archivos = glob($raiz . "/img/catalogo/*") ?: [];
        foreach ($archivos as $archivo) {
            if (is_file($archivo) && str_contains(str_replace("-", "", strtolower(basename($archivo))), $aguja)) {
                return ["img/catalogo/" . basename($archivo), $avisos];
            }
        }
    }

    $avisos[] = "No se encontro la imagen \"" . $token . "\" en img/catalogo ni img/fuentes.";
    return [null, $avisos];
}

function resolver_categoria(string $nombre, array $categorias): array
{
    $n = normalizar_texto($nombre);
    if ($n === "") {
        return [0, null];
    }
    foreach ($categorias as $c) {
        if (normalizar_texto($c["categoria"]) === $n) {
            return [(int)$c["id"], null];
        }
    }
    foreach ($categorias as $c) {
        $cn = normalizar_texto($c["categoria"]);
        if ($cn !== "" && (str_contains($n, $cn) || str_contains($cn, $n))) {
            return [(int)$c["id"], null];
        }
    }
    return [0, ucwords(strtolower(trim($nombre)))];
}

function detectar_separador(string $linea): string
{
    $opciones = ["|" => substr_count($linea, "|"), ";" => substr_count($linea, ";"), "\t" => substr_count($linea, "\t")];
    arsort($opciones);
    $ganador = array_key_first($opciones);
    return $opciones[$ganador] > 0 ? $ganador : "";
}

function parsear_linea(string $linea, int $num, array $categorias, string $raiz): ?array
{
    $original = trim($linea);
    if ($original === "" || str_starts_with($original, "#")) {
        return null;
    }

    $fila = [
        "linea" => $num,
        "original" => $original,
        "sku" => "",
        "nombre" => null,
        "id_categoria" => null,
        "categoria_nueva" => null,
        "precio" => null,
        "stock" => null,
        "descripcion" => null,
        "imagen" => null,
        "errores" => [],
        "avisos" => [],
    ];

    $sep = detectar_separador($original);
    $campos = $sep !== "" ? array_map("trim", explode($sep, $original)) : [$original];

    if ($sep !== "") {
        $fila["sku"] = extraer_sku($campos[0]);
        if (count($campos) > 1 && $campos[1] !== "") {
            $fila["nombre"] = $campos[1];
        }
        if (count($campos) > 2 && $campos[2] !== "" && !is_numeric($campos[2])) {
            [$id, $nueva] = resolver_categoria($campos[2], $categorias);
            $fila["id_categoria"] = $id > 0 ? $id : null;
            $fila["categoria_nueva"] = $nueva;
        } elseif (count($campos) > 2 && is_numeric($campos[2])) {
            $fila["avisos"][] = "El campo 3 se tomo como precio y no como categoria. Usa 7 campos si querés cambiar la categoria.";
            $fila["precio"] = parsear_precio($campos[2]);
        }
        if (count($campos) > 3 && $campos[3] !== "") {
            $fila["precio"] = parsear_precio($campos[3]);
        }
        if (count($campos) > 4 && $campos[4] !== "") {
            $fila["stock"] = parsear_stock($campos[4]);
        }
        if (count($campos) > 5 && $campos[5] !== "") {
            $fila["descripcion"] = $campos[5];
        }
        if (count($campos) > 6 && $campos[6] !== "") {
            $previa = $campos[6];
        }
    } else {
        $fila["sku"] = extraer_sku($original);
        $resto = $original;

        if (preg_match("/[$]\s*([0-9][0-9.,]*)/u", $original, $m)) {
            $fila["precio"] = parsear_precio($m[1]);
            $resto = str_replace($m[0], " ", $resto);
        } elseif (preg_match("/(?:precio|valor)\s*[:=]?\s*([0-9][0-9.,]*)/ui", $original, $m)) {
            $fila["precio"] = parsear_precio($m[1]);
            $resto = str_replace($m[0], " ", $resto);
        }

        if (preg_match("/\b(\d{1,4})\s*(?:unidades|uds)\b/iu", $original, $m)) {
            $fila["stock"] = (int)$m[1];
            $resto = str_replace($m[0], " ", $resto);
        } elseif (preg_match("/(?:stock|existencias)\s*[:=]?\s*(\d{1,4}|agotad\w*)/iu", $original, $m)) {
            $fila["stock"] = is_numeric($m[1]) ? (int)$m[1] : 0;
            $resto = str_replace($m[0], " ", $resto);
        } elseif (preg_match("/\b(agotad\w*|sin\s*stock)\b/iu", $original, $m)) {
            $fila["stock"] = 0;
            $resto = str_replace($m[0], " ", $resto);
        }

        if (preg_match("/(?:categoria|categoría)\s*[:=]?\s*([\p{L}]+(?:\s+[\p{L}]+)?)/iu", $resto, $m)) {
            [$id, $nueva] = resolver_categoria(trim($m[1]), $categorias);
            $fila["id_categoria"] = $id > 0 ? $id : null;
            $fila["categoria_nueva"] = $nueva;
            $resto = str_replace($m[0], " ", $resto);
        }

        if (preg_match("/(?:descripción|desc)\s*[:=]\s*(.+)$/iu", $resto, $m)) {
            $fila["descripcion"] = trim($m[1]);
            $resto = str_replace($m[0], " ", $resto);
        }

        if (preg_match("/\S+\.(?:jpe?g|png|webp|gif)\b/i", $resto, $m)) {
            $previa = $m[0];
            $resto = str_replace($m[0], " ", $resto);
        }

        $resto = trim(preg_replace("/\b[A-Za-z]{1,3}\s*[-_]\s*\d{1,3}\b/", " ", $resto));
        $resto = preg_replace("/\s*(?:[-–—|:,]+\s*)+$/u", "", trim($resto));
        $resto = preg_replace("/^(?:[-–—|:,]+\s*)+/u", "", trim($resto));
        $resto = trim(preg_replace("/\s{2,}/", " ", $resto));
        if ($resto !== "") {
            $fila["nombre"] = $resto;
        }
    }

    if ($fila["sku"] === "") {
        return null;
    }

    $sin_codigo = trim(preg_replace("/\b" . preg_quote($fila["sku"], "/") . "\b/iu", " ", (string)$fila["nombre"]));
    $sin_codigo = trim(preg_replace("/\s{2,}/", " ", $sin_codigo));
    $sin_codigo = trim(preg_replace("/\s*(?:[-–—|:,]+\s*)+$/u", "", $sin_codigo));
    $fila["nombre"] = $sin_codigo === "" ? $fila["sku"] : $fila["sku"] . " " . $sin_codigo;

    if ($sin_codigo === "") {
        $fila["avisos"][] = "Sin nombre: se usara solo el codigo " . $fila["sku"] . ".";
    }

    if (isset($previa) && $previa !== "") {
        [$ruta, $avisos] = resolver_imagen($previa, $raiz, $fila["sku"]);
        $fila["imagen"] = $ruta;
        $fila["avisos"] = array_merge($fila["avisos"], $avisos);
    }

    return $fila;
}

function leer_categorias(PDO $pdo): array
{
    return $pdo->query("SELECT id, categoria FROM categorias ORDER BY id")->fetchAll();
}

function indexar_existentes(PDO $pdo): array
{
    $mapa = [];
    foreach ($pdo->query("SELECT * FROM productos ORDER BY id")->fetchAll() as $p) {
        $sku = extraer_sku($p["producto"]);
        if ($sku !== "") {
            $mapa[$sku] = $p;
        }
    }
    return $mapa;
}

function planificar(array $filas, array $existentes, array $categorias): array
{
    $plan = [];
    $vistos = [];

    foreach ($filas as $fila) {
        $sku = $fila["sku"];
        if (isset($vistos[$sku])) {
            $fila["avisos"][] = "El codigo " . $sku . " ya aparecio antes (linea " . $vistos[$sku] . "). Se usa la ultima.";
        }
        $vistos[$sku] = $fila["linea"];

        $actual = $existentes[$sku] ?? null;
        $fila["existe"] = $actual !== null;
        $fila["id_actual"] = $actual ? (int)$actual["id"] : 0;
        $fila["antes"] = $actual;
        $fila["cambios"] = [];
        $fila["accion"] = "crear";

        if ($actual) {
            $fila["accion"] = "actualizar";
            if ($fila["nombre"] !== null && $fila["nombre"] !== $actual["producto"]) {
                $fila["cambios"]["producto"] = $actual["producto"];
            }
            if ($fila["precio"] !== null && (float)$fila["precio"] !== (float)$actual["precio"]) {
                $fila["cambios"]["precio"] = (float)$actual["precio"];
            }
            if ($fila["id_categoria"] !== null && (int)$fila["id_categoria"] !== (int)$actual["id_categoria"]) {
                $fila["cambios"]["id_categoria"] = (int)$actual["id_categoria"];
            }
            if ($fila["stock"] !== null && (int)$fila["stock"] !== (int)$actual["stock"]) {
                $fila["cambios"]["stock"] = (int)$actual["stock"];
            }
            if ($fila["descripcion"] !== null && $fila["descripcion"] !== (string)$actual["descripcion"]) {
                $fila["cambios"]["descripcion"] = (string)$actual["descripcion"];
            }
            if ($fila["imagen"] !== null && $fila["imagen"] !== $actual["imagen"]) {
                $fila["cambios"]["imagen"] = $actual["imagen"];
            }

            if (count($fila["cambios"]) === 0) {
                $fila["accion"] = "sin_cambios";
            }
        } else {
            if ($fila["precio"] === null) {
                $fila["errores"][] = "Producto nuevo sin precio: indicá el precio para poder crearlo.";
            }
            if ($fila["id_categoria"] === null && $fila["categoria_nueva"] === null) {
                $fila["errores"][] = "Producto nuevo sin categoria: indicá una existente o el nombre de la nueva.";
            }
            if ($fila["imagen"] === null) {
                $fila["errores"][] = "Producto nuevo sin imagen: subi el archivo a img/catalogo/ o indicá el nombre exacto en el ultimo campo.";
            }
        }

        $plan[] = $fila;
    }

    return $plan;
}

function aplicar_plan(PDO $pdo, array $plan, array $categorias, string $raiz): array
{
    $resumen = ["creados" => 0, "actualizados" => 0, "sin_cambios" => 0, "omitidos" => 0, "categorias_nuevas" => [], "errores" => []];

    foreach ($plan as $fila) {
        if (count($fila["errores"]) > 0) {
            $resumen["omitidos"]++;
            $resumen["errores"][] = $fila["sku"] . " (linea " . $fila["linea"] . "): " . implode(" ", $fila["errores"]);
            continue;
        }

        $id_categoria = $fila["id_categoria"];

        if ($fila["accion"] === "sin_cambios") {
            $resumen["sin_cambios"]++;
            continue;
        }

        if ($id_categoria === null && $fila["categoria_nueva"] !== null) {
            $stmt = $pdo->prepare("INSERT INTO categorias (categoria) VALUES (?)");
            $stmt->execute([$fila["categoria_nueva"]]);
            $id_categoria = (int)$pdo->lastInsertId();
            $categorias[] = ["id" => $id_categoria, "categoria" => $fila["categoria_nueva"]];
            $resumen["categorias_nuevas"][] = $fila["categoria_nueva"];
        } elseif ($id_categoria === null) {
            $id_categoria = (int)($fila["antes"]["id_categoria"] ?? 0);
        }

        if ($fila["accion"] === "actualizar") {
            $actual = $fila["antes"];
            $set = [];
            $params = [];
            if (isset($fila["cambios"]["producto"])) { $set[] = "producto=?"; $params[] = $fila["nombre"]; }
            if (isset($fila["cambios"]["precio"])) { $set[] = "precio=?"; $params[] = $fila["precio"]; }
            if (isset($fila["cambios"]["id_categoria"])) { $set[] = "id_categoria=?"; $params[] = $id_categoria; }
            if (isset($fila["cambios"]["stock"])) { $set[] = "stock=?"; $params[] = $fila["stock"]; }
            if (isset($fila["cambios"]["descripcion"])) { $set[] = "descripcion=?"; $params[] = $fila["descripcion"]; }
            if (isset($fila["cambios"]["imagen"])) { $set[] = "imagen=?"; $params[] = $fila["imagen"]; }
            if (count($set) === 0) { $resumen["sin_cambios"]++; continue; }
            $params[] = $fila["id_actual"];
            $stmt = $pdo->prepare("UPDATE productos SET " . implode(", ", $set) . " WHERE id=?");
            $stmt->execute($params);
            $resumen["actualizados"]++;
        } else {
            $stmt = $pdo->prepare("INSERT INTO productos (id_categoria, producto, precio, descripcion, imagen, stock) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $id_categoria,
                $fila["nombre"],
                $fila["precio"] ?? 0,
                $fila["descripcion"] ?? "",
                $fila["imagen"] ?? "",
                $fila["stock"] ?? 0,
            ]);
            $resumen["creados"]++;
        }
    }

    return $resumen;
}

function marcar_ausentes(PDO $pdo, array $skus_importados): array
{
    $marcados = [];
    foreach ($pdo->query("SELECT id, producto, stock FROM productos")->fetchAll() as $p) {
        if (in_array(extraer_sku($p["producto"]), $skus_importados, true)) {
            continue;
        }
        if ((int)$p["stock"] !== 0) {
            $stmt = $pdo->prepare("UPDATE productos SET stock=0 WHERE id=?");
            $stmt->execute([(int)$p["id"]]);
            $marcados[] = $p["producto"];
        }
    }
    return $marcados;
}

function crear_respaldo(PDO $pdo, string $dir): string
{
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    $archivo = $dir . "/productos_" . date("Ymd_His") . ".json";
    $datos = [
        "generado" => date("c"),
        "categorias" => $pdo->query("SELECT * FROM categorias ORDER BY id")->fetchAll(),
        "productos" => $pdo->query("SELECT * FROM productos ORDER BY id")->fetchAll(),
    ];
    file_put_contents($archivo, json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    return basename($archivo);
}

function restaurar_respaldo(PDO $pdo, string $dir, string $archivo): array
{
    $ruta = $dir . "/" . basename($archivo);
    if (!is_file($ruta)) {
        return ["ok" => false, "mensaje" => "No se encontro el respaldo " . basename($archivo) . "."];
    }
    $datos = json_decode(file_get_contents($ruta), true);
    if (!is_array($datos) || !isset($datos["productos"])) {
        return ["ok" => false, "mensaje" => "El respaldo esta corrupto o tiene otro formato."];
    }

    crear_respaldo($pdo, $dir);

    /* Restauración atómica: si algo falla, la base queda como estaba. */
    try {
        $pdo->beginTransaction();
        $pdo->exec("DELETE FROM productos");
        $stmt = $pdo->prepare("INSERT INTO productos (id, id_categoria, producto, precio, imagen, descripcion, stock) VALUES (?, ?, ?, ?, ?, ?, ?)");
        foreach ($datos["productos"] as $p) {
            $stmt->execute([
                (int)$p["id"],
                (int)$p["id_categoria"],
                $p["producto"],
                $p["precio"],
                $p["imagen"],
                $p["descripcion"],
                (int)$p["stock"],
            ]);
        }
        $pdo->commit();
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("MC restaurar respaldo: " . $e->getMessage());
        return ["ok" => false, "mensaje" => "No se pudo restaurar el respaldo. La base quedó sin cambios."];
    }

    return ["ok" => true, "mensaje" => "Se restauraron " . count($datos["productos"]) . " productos desde " . basename($archivo) . "."];
}

$categorias = leer_categorias($pdo);
$errores = [];
$avisos = [];
$resumen = null;
$plan = [];
$contenido = "";
$agotar_ausentes = false;
$token = csrf_token();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!csrf_valido()) {
        $errores[] = "La sesion expiro. Volve a cargar la pagina e intenta de nuevo.";
    } else {
        $accion = $_POST["accion"] ?? "previsualizar";

        if ($accion === "restaurar") {
            $r = restaurar_respaldo($pdo, $dir_respaldos, (string)($_POST["respaldo"] ?? ""));
            $_SESSION["flash"] = $r["ok"] ? $r["mensaje"] : "Error: " . $r["mensaje"];
            redirigir("importar.php");
        }

        $contenido = (string)($_POST["contenido"] ?? "");
        $agotar_ausentes = !empty($_POST["agotar_ausentes"]);

        $lineas = preg_split("/\R/u", $contenido) ?: [];
        $filas = [];
        foreach ($lineas as $i => $texto) {
            $fila = parsear_linea($texto, $i + 1, $categorias, $raiz);
            if ($fila !== null) {
                $filas[] = $fila;
            }
        }

        if (count($filas) === 0) {
            $errores[] = "No se detecto ningun producto. Cada linea necesita un codigo, por ejemplo A-001, B-10 o C-04.";
        }

        $existentes = indexar_existentes($pdo);
        $plan = planificar($filas, $existentes, $categorias);

        if ($accion === "aplicar" && count($errores) === 0) {
            $archivo_respaldo = crear_respaldo($pdo, $dir_respaldos);
            $resumen = aplicar_plan($pdo, $plan, $categorias, $raiz);

            $agotados = 0;
            if ($agotar_ausentes) {
                $marcados = marcar_ausentes($pdo, array_column($filas, "sku"));
                $agotados = count($marcados);
            }

            $partes = [
                $resumen["creados"] . " nuevos",
                $resumen["actualizados"] . " actualizados",
                $resumen["sin_cambios"] . " sin cambios",
                $resumen["omitidos"] . " omitidos",
            ];
            if ($agotados > 0) {
                $partes[] = $agotados . " puestos en agotado";
            }
            if (count($resumen["categorias_nuevas"]) > 0) {
                $partes[] = "categorias creadas: " . implode(", ", array_unique($resumen["categorias_nuevas"]));
            }
            if (count($resumen["errores"]) > 0) {
                $partes[] = "Omitidos: " . implode(" | ", $resumen["errores"]);
            }

            $_SESSION["flash"] = "Importacion aplicada: " . implode(". ", $partes) . ". Respaldo: " . $archivo_respaldo;
            redirigir("importar.php");
        }
    }
}

$respaldos = [];
if (is_dir($dir_respaldos)) {
    $archivos = glob($dir_respaldos . "/productos_*.json") ?: [];
    rsort($archivos);
    foreach ($archivos as $a) {
        $respaldos[] = ["archivo" => basename($a), "fecha" => date("d/m/Y H:i", filemtime($a)), "bytes" => filesize($a)];
    }
}

$resumen_calc = ["crear" => 0, "actualizar" => 0, "sin_cambios" => 0, "error" => 0];
foreach ($plan as $f) {
    if (count($f["errores"]) > 0) {
        $resumen_calc["error"]++;
    } else {
        $resumen_calc[$f["accion"]]++;
    }
}
$hay_cambios = ($resumen_calc["crear"] + $resumen_calc["actualizar"]) > 0;

$ejemplo = "A-001 | Cartera c/ Pañuelo | Carteras | 13990 | 5 | Cartera 40 x 20 cm. Importada, con pañuelo. | mc-27-a-001-cartera-c-pa-uelo.jpg\nA-01 Tote Bag 28 x 26 cm - \$13.990 - 3 uds\nA-004 Cartera Blanca - \$13.999 - agotado";

$titulo = "Importar productos";
require_once "_header.php";

$mensaje = $_SESSION["flash"] ?? "";
unset($_SESSION["flash"]);
?>
    <?php if ($mensaje !== ""): ?>
        <div class="alert alert-success py-2 small"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <div class="row g-3">
        <div class="col-xl-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="h6 fw-bold mb-1">Listado de Instagram</h2>
                    <p class="small text-muted mb-3">Pega el listado copiado de Instagram. Se matchean los productos por su codigo (A-001, B-10, C-04), asi que podes volver a pegar el mismo listado las veces que quieras sin duplicar nada.</p>

                    <form method="post" id="formImportar">
                        <input type="hidden" name="csrf" value="<?= htmlspecialchars($token) ?>">
                        <input type="hidden" name="accion" id="accion" value="previsualizar">

                        <textarea name="contenido" id="contenido" rows="14" class="form-control font-monospace small" placeholder="<?= htmlspecialchars($ejemplo) ?>"><?= htmlspecialchars($contenido) ?></textarea>

                        <div class="form-text mt-2">
                            <strong>Formato con separadores</strong> (recomendado):
                            <code>codigo | nombre | categoria | precio | stock | descripcion | imagen</code>.
                            Solo el c&oacute;digo es obligatorio: los campos que dejes vac&iacute;os conservan el valor actual.
                        </div>
                        <div class="form-text">
                            <strong>Formato libre</strong>: <code>A-001 Cartera con pañuelo $13.990 stock 5</code>. Tambien acepta <code>agotado</code> en vez de stock, y <code>desc:</code> para la descripci&oacute;n.
                        </div>
                        <div class="form-text">
                            Precios: se aceptan <code>13990</code>, <code>$13.990</code> y <code>$13.990,50</code>. La imagen se busca en <code>img/catalogo</code>, <code>img/fuentes</code> o por el c&oacute;digo dentro del nombre del archivo.
                        </div>

                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" name="agotar_ausentes" id="agotarAusentes" value="1" <?= $agotar_ausentes ? "checked" : "" ?>>
                            <label class="form-check-label small" for="agotarAusentes">
                                Poner en <strong>agotado</strong> los productos de la base que no aparecen en el listado
                            </label>
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-outline-dark fw-semibold">Previsualizar</button>
                            <button type="submit" class="btn btn-dark fw-semibold d-none" id="btnAplicar">Aplicar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-5">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body p-4">
                    <h2 class="h6 fw-bold mb-3">Resumen</h2>
                    <?php if (count($plan) === 0): ?>
                        <p class="small text-muted mb-0">Aguardando el listado.</p>
                    <?php else: ?>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge rounded-pill bg-success"><?= $resumen_calc["crear"] ?> nuevos</span>
                            <span class="badge rounded-pill bg-warning text-dark"><?= $resumen_calc["actualizar"] ?> a actualizar</span>
                            <span class="badge rounded-pill bg-secondary"><?= $resumen_calc["sin_cambios"] ?> sin cambios</span>
                            <?php if ($resumen_calc["error"] > 0): ?>
                                <span class="badge rounded-pill bg-danger"><?= $resumen_calc["error"] ?> con error</span>
                            <?php endif; ?>
                        </div>

                        <?php if (count($errores) > 0): ?>
                            <div class="alert alert-danger py-2 small mb-3">
                                <?php foreach ($errores as $e): ?><div>&bull; <?= htmlspecialchars($e) ?></div><?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($resumen_calc["crear"] + $resumen_calc["actualizar"] > 0): ?>
                            <div class="alert alert-warning py-2 small mb-0">
                                Se guarda un respaldo automatico antes de tocar la base, por si algo sale mal.
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="h6 fw-bold mb-2">Respaldos</h2>
                    <?php if (count($respaldos) === 0): ?>
                        <p class="small text-muted mb-0">Todavia no hay respaldos.</p>
                    <?php else: ?>
                        <p class="small text-muted">Ultimos <?= count($respaldos) ?> respaldos del catalogo completo.</p>
                        <div class="list-group list-group-flush">
                            <?php foreach (array_slice($respaldos, 0, 8) as $r): ?>
                                <div class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <div class="small fw-semibold"><?= htmlspecialchars($r["fecha"]) ?></div>
                                        <div class="small text-muted" style="font-size:.75rem"><?= htmlspecialchars($r["archivo"]) ?></div>
                                    </div>
                                    <form method="post" class="btn-restaurar">
                                        <input type="hidden" name="csrf" value="<?= htmlspecialchars($token) ?>">
                                        <input type="hidden" name="accion" value="restaurar">
                                        <input type="hidden" name="respaldo" value="<?= htmlspecialchars($r["archivo"]) ?>">
                                        <button class="btn btn-sm btn-outline-warning">Restaurar</button>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php if (count($plan) > 0): ?>
        <div class="card border-0 shadow-sm mt-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Linea</th>
                            <th>Accion</th>
                            <th>Codigo</th>
                            <th>Producto</th>
                            <th>Categoria</th>
                            <th>Precio</th>
                            <th class="text-center">Stock</th>
                            <th>Imagen</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($plan as $f): ?>
                        <?php
                        $etiqueta = ["crear" => ["Nuevo", "bg-success"], "actualizar" => ["Actualizar", "bg-warning text-dark"], "sin_cambios" => ["Igual", "bg-secondary"]];
                        [$txt, $color] = count($f["errores"]) > 0 ? ["Error", "bg-danger"] : $etiqueta[$f["accion"]];
                        $cat_actual = "";
                        foreach ($categorias as $c) {
                            if ((int)$c["id"] === (int)($f["antes"]["id_categoria"] ?? 0)) { $cat_actual = $c["categoria"]; }
                        }
                        ?>
                        <tr class="<?= count($f["errores"]) > 0 ? "table-danger" : "" ?>">
                            <td class="small text-muted"><?= (int)$f["linea"] ?></td>
                            <td><span class="badge rounded-pill <?= $color ?>"><?= $txt ?></span></td>
                            <td class="fw-semibold small"><?= htmlspecialchars($f["sku"]) ?></td>
                            <td class="small">
                                <?php if (isset($f["cambios"]["producto"])): ?>
                                    <div class="text-muted text-decoration-line-through"><?= htmlspecialchars($f["cambios"]["producto"]) ?></div>
                                <?php endif; ?>
                                <?= htmlspecialchars($f["nombre"]) ?>
                            </td>
                            <td class="small">
                                <?php if ($f["id_categoria"] !== null): ?>
                                    <?php
                                    $nueva_cat = "";
                                    foreach ($categorias as $c) {
                                        if ((int)$c["id"] === (int)$f["id_categoria"]) { $nueva_cat = $c["categoria"]; }
                                    }
                                    ?>
                                    <?php if (isset($f["cambios"]["id_categoria"])): ?>
                                        <div class="text-muted text-decoration-line-through"><?= htmlspecialchars($cat_actual) ?></div>
                                    <?php endif; ?>
                                    <?= htmlspecialchars($nueva_cat) ?>
                                <?php elseif ($f["categoria_nueva"] !== null): ?>
                                    <span class="text-danger"><?= htmlspecialchars($f["categoria_nueva"]) ?> <em>(nueva)</em></span>
                                <?php else: ?>
                                    <span class="text-muted"><?= htmlspecialchars($cat_actual) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="small">
                                <?php if (isset($f["cambios"]["precio"])): ?>
                                    <div class="text-muted text-decoration-line-through"><?= precio_ar($f["cambios"]["precio"]) ?></div>
                                <?php endif; ?>
                                <?= $f["precio"] !== null ? precio_ar($f["precio"]) : '<span class="text-muted">sin cambio</span>' ?>
                            </td>
                            <td class="text-center small">
                                <?php if (isset($f["cambios"]["stock"])): ?>
                                    <div class="text-muted text-decoration-line-through"><?= (int)$f["cambios"]["stock"] ?></div>
                                <?php endif; ?>
                                <?php if ($f["stock"] !== null): ?>
                                    <span class="badge rounded-pill <?= (int)$f["stock"] > 0 ? "bg-success" : "bg-danger" ?>"><?= (int)$f["stock"] ?></span>
                                <?php else: ?>
                                    <span class="text-muted">sin cambio</span>
                                <?php endif; ?>
                            </td>
                            <td class="small" style="max-width:220px">
                                <?php if ($f["imagen"] !== null): ?>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="../<?= htmlspecialchars($f["imagen"]) ?>" alt="" style="width:34px;height:34px;object-fit:cover;border-radius:6px" onerror="this.style.visibility='hidden'">
                                        <span class="text-muted" style="font-size:.7rem"><?= htmlspecialchars(basename($f["imagen"])) ?></span>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted"><?= count($f["errores"]) > 0 ? "falta" : "sin cambio" ?></span>
                                <?php endif; ?>
                                <?php foreach ($f["errores"] as $e): ?>
                                    <div class="text-danger" style="font-size:.7rem">&bull; <?= htmlspecialchars($e) ?></div>
                                <?php endforeach; ?>
                                <?php foreach ($f["avisos"] as $a): ?>
                                    <div class="text-warning" style="font-size:.7rem">&bull; <?= htmlspecialchars($a) ?></div>
                                <?php endforeach; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

<?php
function cargar_script_pie(): void {
    global $hay_cambios; ?>
<script>
    $(function () {
        if (<?= $hay_cambios ? "true" : "false" ?>) {
            $("#btnAplicar").removeClass("d-none");
        }
        $("#formImportar").on("submit", function (e) {
            if (this.submitter && this.submitter.id === "btnAplicar") {
                $("#accion").val("aplicar");
                if (!confirm("Se van a guardar los cambios en el catalogo. Confirmar?")) {
                    e.preventDefault();
                    $("#accion").val("previsualizar");
                }
            } else {
                $("#accion").val("previsualizar");
            }
        });
        $("form.btn-restaurar").on("submit", function (e) {
            if (!confirm("Se reemplazara el catalogo actual por el contenido del respaldo. Confirmar?")) {
                e.preventDefault();
            }
        });
    });
</script>
<?php }
require_once "_footer.php";
