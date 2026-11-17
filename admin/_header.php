<?php
if (!function_exists("requiere_login")) {
    require_once __DIR__ . "/../config.php";
}
requiere_login();
$titulo = $titulo ?? "Panel de administración";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo) ?> | Marea Celestial</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #F5F0E8;
            min-height: 100vh;
        }
        .sidebar {
            background: linear-gradient(180deg, #0C2D48 0%, #081C30 100%);
            min-height: 100vh;
        }
        .sidebar .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 18px;
            border-bottom: 1px solid rgba(201,162,39,0.2);
            color: #F5F0E8;
            text-decoration: none;
        }
        .sidebar .brand img { height: 38px; width: auto; }
        .sidebar .brand span {
            font-weight: 800; letter-spacing: 1px;
            background: linear-gradient(135deg, #D4A843, #C9A227);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .sidebar a.nav-link, .sidebar button.nav-link {
            color: #A8C4D8;
            padding: 12px 18px;
            font-weight: 600;
            border-left: 3px solid transparent;
            transition: all 0.2s;
        }
        .sidebar a.nav-link:hover, .sidebar a.nav-link.active,
        .sidebar button.nav-link:hover {
            color: #F5F0E8;
            background: rgba(201,162,39,0.1);
            border-left-color: #C9A227;
        }
        .admin-topbar {
            background: #fff;
            padding: 14px 22px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .stat-card {
            background: #fff;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.06);
        }
        .stat-card .num { font-size: 1.8rem; font-weight: 800; color: #0C2D48; }
        .stat-card .lbl { color: #5A7A94; font-size: 0.9rem; }
        .table img.thumb { width: 48px; height: 48px; object-fit: cover; border-radius: 8px; }
    </style>
</head>
<body>
<div class="container-fluid p-0">
    <div class="row g-0">
        <div class="col-auto sidebar">
            <div class="d-flex flex-column">
                <a class="brand" href="index.php">
                    <img src="../img/logo.jpg" alt="Marea Celestial" style="width:36px;height:36px;object-fit:cover;border-radius:50%;border:2px solid #C9A227;box-shadow:0 0 8px rgba(201,162,39,0.4)">
                    <span>MAREA CELESTIAL</span>
                </a>
                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>" href="index.php">Inicio</a>
                <a class="nav-link <?= str_starts_with(basename($_SERVER['PHP_SELF']), 'producto') ? 'active' : '' ?>" href="productos.php">Productos</a>
                <a class="nav-link <?= str_starts_with(basename($_SERVER['PHP_SELF']), 'categoria') ? 'active' : '' ?>" href="categorias.php">Categorías</a>
                <a class="nav-link <?= str_starts_with(basename($_SERVER['PHP_SELF']), 'importar') ? 'active' : '' ?>" href="importar.php">Importar</a>
                <a class="nav-link <?= str_starts_with(basename($_SERVER['PHP_SELF']), 'pedido') ? 'active' : '' ?>" href="pedidos.php">Pedidos</a>
                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'cuenta.php' ? 'active' : '' ?>" href="cuenta.php">Mi cuenta</a>
                <form method="post" action="logout.php" class="m-0">
                    <?= csrf_campo() ?>
                    <button type="submit" class="nav-link text-danger border-0 bg-transparent w-100 text-start">Salir</button>
                </form>
            </div>
        </div>
        <div class="col">
            <div class="admin-topbar sticky-top">
                <h1 class="h5 mb-0 fw-bold"><?= htmlspecialchars($titulo) ?></h1>
                <a href="../index.php" class="btn btn-sm btn-outline-secondary" target="_blank">Ver tienda &nearr;</a>
            </div>
            <div class="p-3 p-md-4">
