<?php
require_once "config.php";
header("Content-Type: application/xml; charset=utf-8");
$base = rtrim(url_base(), "/");
$urls = [];
$urls[] = ["$base/index.php", "daily", 1.0];
$urls[] = ["$base/productos.php", "daily", 0.9];
$urls[] = ["$base/nosotros.php", "monthly", 0.6];
try {
    $pdo = conectar();
    foreach ($pdo->query("SELECT p.id, p.id_categoria FROM productos p INNER JOIN categorias c ON c.id = p.id_categoria ORDER BY p.id") as $pr) {
        $urls[] = ["$base/producto.php?id=" . (int)$pr["id"], "weekly", 0.7];
        $urls[] = ["$base/productos.php?id=" . (int)$pr["id_categoria"], "weekly", 0.7];
    }
} catch (Throwable $e) {}
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($urls as $u) {
    echo "  <url>\n";
    echo "    <loc>" . htmlspecialchars($u[0]) . "</loc>\n";
    echo "    <changefreq>" . $u[1] . "</changefreq>\n";
    echo "    <priority>" . $u[2] . "</priority>\n";
    echo "  </url>\n";
}
echo '</urlset>' . "\n";