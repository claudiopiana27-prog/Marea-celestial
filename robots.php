<?php
require_once "config.php";
header("Content-Type: text/plain; charset=utf-8");
echo "User-agent: *\n";
echo "Allow: /\n";
$prefijo = rtrim((string)parse_url(url_base(), PHP_URL_PATH), "/");
echo "Disallow: {$prefijo}/admin/\n";
echo "Disallow: {$prefijo}/api/\n";
echo "\n";
echo "Sitemap: " . rtrim(url_base(), "/") . "/sitemap.xml\n";