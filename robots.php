<?php
require_once "config.php";
header("Content-Type: text/plain; charset=utf-8");
echo "User-agent: *\n";
echo "Allow: /\n";
echo "\n";
echo "Sitemap: " . rtrim(url_base(), "/") . "/sitemap.xml\n";