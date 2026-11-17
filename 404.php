<?php
require_once "config.php";
http_response_code(404);

$mc_titulo = "Página no encontrada | Marea Celestial";
$mc_descripcion = "La página que buscás no existe o cambió de dirección. Volvé al catálogo de Marea Celestial.";
$mc_pagina = "";

require_once "partials/header.php";
?>
<main class="container py-5">
    <div class="row justify-content-center text-center">
        <div class="col-lg-7 py-5">
            <p class="display-1 fw-bold mb-0" style="color:var(--gold-dark);letter-spacing:-2px">404</p>
            <h1 class="display-section mb-3" style="color:var(--fg)">No encontramos esta página</h1>
            <p class="lead mb-4" style="color:var(--fg-muted)">
                Puede que el enlace esté roto o que la página haya cambiado de lugar.
                Te dejamos el camino de vuelta:
            </p>
            <div class="d-flex flex-wrap gap-2 justify-content-center">
                <a href="index.php" class="btn btn-dark px-4 py-2 fw-semibold">Ir al inicio</a>
                <a href="productos.php" class="btn btn-outline-dark px-4 py-2 fw-semibold">Ver el catálogo</a>
                <a href="contacto.php" class="btn btn-outline-dark px-4 py-2 fw-semibold">Contactarnos</a>
            </div>
        </div>
    </div>
</main>
<?php require_once "partials/footer.php"; ?>
