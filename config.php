<?php
/* En producción no mostrar errores al visitante; en local sí, para depurar. */
$mc_host_actual = strtolower((string)($_SERVER["HTTP_HOST"] ?? ""));
$mc_host_actual = trim((string)preg_replace('/:\d+$/', '', $mc_host_actual), "[]");
if ($mc_host_actual !== "" && !in_array($mc_host_actual, ["localhost", "127.0.0.1", "::1"], true)) {
    ini_set("display_errors", "0");
    ini_set("display_startup_errors", "0");
    ini_set("log_errors", "1");
}

/* ---------- Configuración desde .env (con valores por defecto para XAMPP) ---------- */
function mc_cargar_env(string $archivo): void
{
    if (!is_file($archivo) || !is_readable($archivo)) {
        return;
    }
    $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lineas === false) {
        return;
    }
    foreach ($lineas as $linea) {
        $linea = trim($linea);
        if ($linea === "" || str_starts_with($linea, "#")) {
            continue;
        }
        if (stripos($linea, "export ") === 0) {
            $linea = trim(substr($linea, 7));
        }
        $partes = explode("=", $linea, 2);
        if (count($partes) !== 2) {
            continue;
        }
        $clave = trim($partes[0]);
        $valor = trim($partes[1]);
        if ($clave === "" || !preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $clave)) {
            continue;
        }
        if (isset($_ENV[$clave]) || isset($_SERVER[$clave]) || getenv($clave) !== false) {
            continue;
        }
        if (strlen($valor) >= 2
            && (($valor[0] === '"' && str_ends_with($valor, '"'))
                || ($valor[0] === "'" && str_ends_with($valor, "'")))) {
            $valor = substr($valor, 1, -1);
        }
        $_ENV[$clave] = $valor;
    }
}

function mc_env(string $clave, string $por_defecto = ""): string
{
    if (isset($_ENV[$clave]) && is_scalar($_ENV[$clave])) {
        return (string)$_ENV[$clave];
    }
    if (isset($_SERVER[$clave]) && is_scalar($_SERVER[$clave])) {
        return (string)$_SERVER[$clave];
    }
    $valor = getenv($clave);
    return $valor === false ? $por_defecto : (string)$valor;
}

mc_cargar_env(__DIR__ . "/.env");

/* Sesión con cookies endurecidas (HttpOnly + SameSite). */
if (session_status() === PHP_SESSION_NONE) {
    $mc_https = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off")
        || (($_SERVER["HTTP_X_FORWARDED_PROTO"] ?? "") === "https");
    session_set_cookie_params([
        "httponly" => true,
        "samesite" => "Lax",
        "secure"   => $mc_https,
        "path"     => "/",
    ]);
    session_start();
}

define("DB_SERVIDOR", mc_env("DB_HOST", "localhost"));
define("DB_USUARIO", mc_env("DB_USER", "root"));
define("DB_PASSWORD", mc_env("DB_PASSWORD", ""));
define("DB_NOMBRE", mc_env("DB_NAME", "catalogo"));

define("WHATSAPP", mc_env("WHATSAPP", "5492235970665")); // numero con prefijo pais, ej: 5491100000000

// Utilidades de URL para el deploy
function url_base(): string
{
    $https = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off")
        || (($_SERVER["HTTP_X_FORWARDED_PROTO"] ?? "") === "https");
    $host = $_SERVER["HTTP_HOST"] ?? "localhost:8080";
    $script = $_SERVER["SCRIPT_NAME"] ?? "/Marea_celestial/index.php";
    $dir = rtrim(str_replace("\\", "/", dirname($script)), "/");
    if (str_ends_with($dir, "/index.php")) { $dir = substr($dir, 0, -9); }
    return ($https ? "https" : "http") . "://" . $host . ($dir === "/" ? "" : $dir);
}
function url_absoluta(string $ruta): string
{
    return url_base() . "/" . ltrim($ruta, "/");
}

/* URL completa de la página actual (para canonical y og:url). */
function url_actual(): string
{
    $https = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off")
        || (($_SERVER["HTTP_X_FORWARDED_PROTO"] ?? "") === "https");
    $host = $_SERVER["HTTP_HOST"] ?? "localhost";
    $uri = $_SERVER["REQUEST_URI"] ?? "/";
    return ($https ? "https" : "http") . "://" . $host . $uri;
}

/* Igual que url_actual pero sin parámetros de tracking; solo conserva ?id=N. */
function url_canonica(): string
{
    $url = url_actual();
    $partes = parse_url($url);
    if ($partes === false) {
        return $url;
    }
    $base = ($partes["scheme"] ?? "http") . "://" . ($partes["host"] ?? "localhost");
    if (!empty($partes["port"])) {
        $base .= ":" . $partes["port"];
    }
    $base .= $partes["path"] ?? "/";

    $params = [];
    if (!empty($partes["query"])) {
        parse_str($partes["query"], $params);
    }
    if (isset($params["id"]) && ctype_digit((string)$params["id"])) {
        $base .= "?id=" . (int)$params["id"];
    }
    return $base;
}

function conectar(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO(
            "mysql:host=" . DB_SERVIDOR . ";dbname=" . DB_NOMBRE . ";charset=utf8mb4",
            DB_USUARIO,
            DB_PASSWORD,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }
    return $pdo;
}

function precio_ar($monto): string
{
    return "$" . number_format((float)$monto, 0, ",", ".");
}

// Devuelve la ruta de imagen valida o un fallback si el archivo no existe.
function imagen_producto(?string $ruta): string
{
    $ruta = trim((string)$ruta);
    if ($ruta === "") return "img/logo.jpg";
    if (is_file(__DIR__ . "/" . ltrim($ruta, "/"))) return $ruta;
    return "img/logo.jpg";
}

// Igual que imagen_producto pero intenta la version reducida de img/catalogo/thumbs/.
function imagen_thumb(?string $ruta): string
{
    $ruta = trim((string)$ruta);
    if ($ruta === "") return "img/logo.jpg";
    $thumb = str_replace("/catalogo/", "/catalogo/thumbs/", $ruta);
    if ($thumb !== $ruta && is_file(__DIR__ . "/" . ltrim($thumb, "/"))) return $thumb;
    if (is_file(__DIR__ . "/" . ltrim($ruta, "/"))) return $ruta;
    return "img/logo.jpg";
}

function formato_fecha($fecha): string
{
    $dt = new DateTime($fecha);
    return $dt->format("d/m/Y H:i");
}

function json_response($datos, int $codigo = 200): void
{
    http_response_code($codigo);
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    exit;
}

function redirigir(string $url): void
{
    header("Location: " . $url);
    exit;
}

function limpiar(mixed $valor): string
{
    if (!is_scalar($valor)) {
        return "";
    }
    return trim(strip_tags((string)$valor));
}

function requiere_login(): void
{
    if (empty($_SESSION["admin_id"])) {
        redirigir("login.php");
    }
}

/* ---------- Protección CSRF (panel de administración) ---------- */
function csrf_token(): string
{
    if (empty($_SESSION["csrf_token"])) {
        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
    }
    return $_SESSION["csrf_token"];
}

function csrf_campo(): string
{
    return '<input type="hidden" name="csrf" value="'
        . htmlspecialchars(csrf_token(), ENT_QUOTES, "UTF-8") . '">';
}

function csrf_valido(): bool
{
    $enviado = $_POST["csrf"] ?? "";
    return is_string($enviado) && $enviado !== "" && hash_equals(csrf_token(), $enviado);
}

function csrf_requerir(): void
{
    if ($_SERVER["REQUEST_METHOD"] === "POST" && !csrf_valido()) {
        http_response_code(403);
        exit("La sesión expiró. Volvé a cargar la página e intentá de nuevo.");
    }
}