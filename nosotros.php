<?php
require_once "config.php";

$mc_titulo      = "Nosotros | Marea Celestial";
$mc_descripcion = "Conocé Marea Celestial: bolsos, carteras y monederos en ecocuero. Calidad, envíos a todo el país y compra fácil por WhatsApp.";
$mc_og_titulo   = "Nosotros | Marea Celestial";
$mc_og_imagen   = "img/og-image.jpg";
$mc_pagina      = "nosotros";

ob_start();
?>
<style>
    .feature-card {
        text-align: center; padding: var(--space-6);
        border: 1px solid var(--border); border-radius: var(--radius-2xl);
        background: var(--bg-card); transition: all var(--dur-base); height: 100%;
    }
    .feature-card:hover { border-color: var(--gold); box-shadow: 0 0 30px var(--gold-glow); transform: translateY(-4px); }
    .acordeon-faq .accordion-button {
        background: var(--bg-card); color: var(--fg); border: 1px solid var(--border);
    }
    .acordeon-faq .accordion-button:not(.collapsed) {
        background: var(--gold-subtle); color: var(--fg);
        box-shadow: inset 0 -1px 0 var(--border);
    }
    .acordeon-faq .accordion-button:focus { box-shadow: none; }
    .acordeon-faq .accordion-body { color: var(--fg-muted); }
    .acordeon-faq .accordion-item { border: 1px solid var(--border); background: var(--bg-elevated); }
</style>
<?php
$mc_head_extra = ob_get_clean();
require_once "partials/header.php";
?>

    <main class="container" style="padding-top:140px;padding-bottom:100px">
        <nav aria-label="breadcrumb" class="text-sm mb-4" style="color:var(--fg-subtle)">
            <a href="index.php" style="color:var(--gold-dark);text-decoration:none">Inicio</a>
            <span style="color:var(--fg-subtle)"> › </span>
            <span style="color:var(--fg)">Nosotros</span>
        </nav>

        <!-- HERO NOSOTROS -->
        <section class="text-center mb-5">
            <span class="badge badge-gold mb-3 d-inline-block">✦ Tu estilo también brilla ✦</span>
            <h1 class="display-section mb-3"><span class="text-gradient-celestial">Sobre Marea Celestial</span></h1>
            <p class="text-fg-muted text-lg max-w-2xl mx-auto" style="line-height:1.8">
                Somos un emprendimiento de accesorios e indumentaria. Trabajamos con
                <strong class="text-gold">ecocuero premium importado</strong>, seleccionamos cada modelo
                para que combine estilo, calidad y precio justo.
            </p>
        </section>

        <!-- VALORES -->
        <section class="row g-4 mb-5">
            <div class="col-12 col-md-6 col-lg-3">
                <div class="feature-card">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="mb-3" style="color:var(--gold)"><path d="M20 7L9 18l-5-5"></path></svg>
                    <h3 class="display-card mb-2" style="color:var(--fg)">Calidad real</h3>
                    <p class="small text-fg-muted mb-0">Ecocuero grueso, costuras reforzadas y herrajes que duran en el tiempo.</p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="feature-card">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="mb-3" style="color:var(--gold)"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    <h3 class="display-card mb-2" style="color:var(--fg)">Atención personal</h3>
                    <p class="small text-fg-muted mb-0">Te respondemos por WhatsApp para asesorarte con tu pedido.</p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="feature-card">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="mb-3" style="color:var(--gold)"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                    <h3 class="display-card mb-2" style="color:var(--fg)">Envíos al país</h3>
                    <p class="small text-fg-muted mb-0">Despachamos a todo el territorio nacional con seguimiento.</p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="feature-card">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="mb-3" style="color:var(--gold)"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    <h3 class="display-card mb-2" style="color:var(--fg)">Compra segura</h3>
                    <p class="small text-fg-muted mb-0">Pedís en 2 minutos y coordinamos todo por WhatsApp.</p>
                </div>
            </div>
        </section>

        <!-- NUESTRA HISTORIA -->
        <section class="glass-strong rounded-3xl p-5 p-md-6 mb-5" style="border-color:var(--gold-glow)">
            <div class="row g-4 align-items-center">
                <div class="col-12 col-lg-5">
                    <span class="badge badge-turquoise mb-2 d-inline-block">✦ Nuestra historia</span>
                    <h2 class="display-card mb-3" style="color:var(--fg)">Desde 2021, en Valparaíso</h2>
                    <p class="text-fg-muted small" style="line-height:1.8">
                        Marea Celestial nació en 2021 como un pequeño taller de accesorios.
                        Lo que empezó cosiendo a mano para amigas se transformó en una marca
                        que hoy llega a todo el país. Creemos que un accesorio no es solo un
                        objeto: es parte de tu historia.
                    </p>
                </div>
                <div class="col-12 col-lg-7">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="feature-card text-start">
                                <h3 class="display-card mb-1" style="color:var(--gold)">Mariana Rojas</h3>
                                <p class="small text-fg-muted mb-0">Diseñadora. Cuida cada detalle, color y terminación de las piezas.</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="feature-card text-start">
                                <h3 class="display-card mb-1" style="color:var(--gold)">Diego Morales</h3>
                                <p class="small text-fg-muted mb-0">Director creativo. Piensa cada colección y la experiencia de compra.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- PAGOS Y ENVÍOS -->
        <section class="glass-strong rounded-3xl p-5 p-md-6 mb-5" style="border-color:var(--gold-glow)" id="pagos">
            <div class="row g-4 align-items-center">
                <div class="col-12 col-lg-5">
                    <span class="badge badge-turquoise mb-2 d-inline-block">✦ Medios de pago</span>
                    <h2 class="display-card mb-3" style="color:var(--fg)">¿Cómo pagás?</h2>
                    <p class="text-fg-muted small" style="line-height:1.7">Aceptamos los medios más cómodos del mercado:</p>
                    <ul class="list-unstyled text-sm text-fg-muted mb-0" style="line-height:2.2">
                        <li>• Efectivo (retiro o contra entrega coordinado)</li>
                        <li>• Transferencia bancaria / alias CVU</li>
                        <li>• Mercado Pago (próximamente disponible online)</li>
                    </ul>
                </div>
                <div class="col-12 col-lg-7" id="envios">
                    <span class="badge badge-gold mb-2 d-inline-block">✦ Envíos</span>
                    <h2 class="display-card mb-3" style="color:var(--fg)">¿Cómo llega tu pedido?</h2>
                    <div class="row g-3 text-sm text-fg-muted">
                        <div class="col-6"><div class="rounded-3 p-4 h-100" style="border:1px solid var(--border);background:var(--bg-elevated)"><strong class="text-gold d-block mb-1">Envío a domicilio</strong>Correo argentino / OCA con seguimiento, o coordinas a tu cargo el medio que prefieras.</div></div>
                        <div class="col-6"><div class="rounded-3 p-4 h-100" style="border:1px solid var(--border);background:var(--bg-elevated)"><strong class="text-gold d-block mb-1">Retiro en mano</strong>Podés retirar en nuestra zona después de coordinar por WhatsApp.</div></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section id="faq">
            <header class="text-center mb-4">
                <span class="badge badge-gold mb-2 d-inline-block">✦ Preguntas frecuentes</span>
                <h2 class="display-section mb-2"><span class="text-gradient-celestial">¿Dudas?</span></h2>
            </header>
            <div class="accordion acordeon-faq" id="faqAcc">
                <div class="accordion-item">
                    <h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#f1">¿Cómo hago un pedido?</button></h2>
                    <div id="f1" class="accordion-collapse collapse show" data-bs-parent="#faqAcc">
                        <div class="accordion-body">Elegís los productos, tocás "Enviar pedido por WhatsApp" y el mensaje se arma solo con tu carrito. Nosotros te confirmamos medio de pago y envío.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#f2">¿En cuánto tiempo recibo mi pedido?</button></h2>
                    <div id="f2" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
                        <div class="accordion-body">Una vez confirmado el pago, despachamos en 24-48 h. El plazo de entrega depende del destino y del correo elegido (lo coordinamos contigo).</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#f3">¿Hacen envíos a todo el país?</button></h2>
                    <div id="f3" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
                        <div class="accordion-body">Sí, enviamos a todo el territorio argentino. Consultanos el costo según tu localidad y preferencia.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#f4">¿Puedo cambiar o devolver un producto?</button></h2>
                    <div id="f4" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
                        <div class="accordion-body">Aceptamos cambios por falla de fábrica dentro de los primeros días de recibido el producto, siempre que conserve su estado original. Coordinalo por WhatsApp.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#f5">¿Qué es el ecocuero?</button></h2>
                    <div id="f5" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
                        <div class="accordion-body">Es una tela recubierta tipo cuero vegano, resistente y fácil de limpiar. Es nuestra elección por ser amigable con los animales y el ambiente.</div>
                    </div>
                </div>
            </div>
        </section>
    </main>

<?php require_once "partials/footer.php"; ?>
