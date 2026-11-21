<?php
require_once "../config.php";

if (!empty($_SESSION["admin_id"])) {
    redirigir("index.php");
}

$error = "";
$intentos = $_SESSION["login_intentos"] ?? ["n" => 0, "ts" => 0];
$bloqueado = $intentos["n"] >= 5 && (time() - (int)$intentos["ts"]) < 900;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    csrf_requerir();

    if ($bloqueado) {
        $error = "Demasiados intentos fallidos. Esperá 15 minutos e intentá de nuevo.";
    } else {
        $usuario = limpiar($_POST["usuario"] ?? "");
        $password = $_POST["password"] ?? "";

        if ($usuario !== "" && $password !== "") {
            try {
                $pdo = conectar();
                $stmt = $pdo->prepare("SELECT id, usuario, password FROM usuarios WHERE usuario = ?");
                $stmt->execute([$usuario]);
                $admin = $stmt->fetch();

                if ($admin && password_verify($password, $admin["password"])) {
                    unset($_SESSION["login_intentos"]);
                    session_regenerate_id(true);
                    $_SESSION["admin_id"] = (int)$admin["id"];
                    $_SESSION["admin_usuario"] = $admin["usuario"];
                    redirigir("index.php");
                } else {
                    usleep(400000);
                    $_SESSION["login_intentos"] = ["n" => (int)$intentos["n"] + 1, "ts" => time()];
                    $restantes = max(0, 5 - ((int)$intentos["n"] + 1));
                    $error = "Usuario o contraseña incorrectos."
                        . ($restantes > 0 ? " Te quedan $restantes intento(s)." : " Cuenta bloqueada por 15 minutos.");
                }
            } catch (Throwable $e) {
                error_log("MC login: " . $e->getMessage());
                $error = "No se pudo iniciar sesión en este momento. Intentá de nuevo.";
            }
        } else {
            $error = "Completá ambos campos.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresar | Panel Marea Celestial</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0C2D48 0%, #081C30 50%, #0A1628 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 380px;
            background: #FDF8F0;
            border-radius: 16px;
            padding: 36px 30px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3), 0 0 60px rgba(201,162,39,0.15);
        }
        .login-card h1 {
            font-size: 1.4rem;
            font-weight: 800;
            color: #0C2D48;
            letter-spacing: 1px;
        }
        .login-card .logo-img {
            height: 52px;
            width: auto;
            display: block;
            margin: 0 auto 14px;
        }
        .login-card .subtitle {
            color: #5A7A94;
        }
        .form-control:focus {
            border-color: #C9A227;
            box-shadow: 0 0 0 0.2rem rgba(201,162,39,0.25);
        }
        .btn-dark {
            background: linear-gradient(135deg, #0C2D48, #081C30);
            border: none;
        }
        .btn-dark:hover {
            background: linear-gradient(135deg, #1A4A6E, #0C2D48);
        }
    </style>
</head>
<body>
    <div class="login-card">
        <img class="logo-img" src="../img/logo.jpg" alt="Marea Celestial" onerror="this.style.display='none'" style="border:3px solid #C9A227;box-shadow:0 0 20px rgba(201,162,39,0.4);border-radius:50%">
        <h1 class="text-center mb-1">Panel de administración</h1>
        <p class="subtitle text-center small mb-4">Ingresá para gestionar tu catálogo</p>

        <?php if ($error !== ""): ?>
            <div class="alert alert-danger py-2 small"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" action="login.php" autocomplete="off">
            <?= csrf_campo() ?>
            <div class="mb-3">
                <label class="form-label small fw-semibold" for="usuario">Usuario</label>
                <input type="text" name="usuario" id="usuario" class="form-control" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold" for="password">Contraseña</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-dark w-100 fw-semibold mt-2">Ingresar</button>
        </form>

        <p class="text-center small mt-4 mb-0">
            <a href="../index.php" class="text-decoration-none" style="color:#0C2D48">&larr; Volver al catálogo</a>
        </p>
    </div>
</body>
</html>
