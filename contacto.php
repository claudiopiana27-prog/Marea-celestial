<?php
require_once "config.php";

$wa = preg_replace("/\D/", "", WHATSAPP);
if (!defined("MC_INSTAGRAM")) define("MC_INSTAGRAM", "marea_celestial");
$ig = MC_INSTAGRAM;

$mc_titulo      = "Contacto | Marea Celestial";
$mc_descripcion = "Contactate con Marea Celestial por WhatsApp o Instagram. Consultas, pedidos, envíos y ventas por mayor.";
$mc_og_titulo   = "Contacto | Marea Celestial";
$mc_og_imagen   = "img/og-image.jpg";
$mc_pagina      = "contacto";

ob_start();
?>
<style>
    .ct-hero { text-align: center; padding: var(--space-10) var(--space-4) var(--space-6); }
    .ct-hero__titulo {
        font-family: var(--font-display); font-weight: 700;
        color: var(--color-primary); font-size: clamp(1.8rem, 4vw, 2.8rem); margin: 0;
    }

    .ct-panel {
        border: 1.5px solid var(--border); border-radius: var(--radius-2xl);
        background: var(--bg-card); padding: var(--space-6);
        box-shadow: var(--shadow-lg); height: 100%;
    }

    .ct-channel {
        display: flex; align-items: center; gap: var(--space-3);
        text-decoration: none; color: inherit;
        border: 1px solid var(--border); border-radius: var(--radius-lg);
        background: var(--bg-elevated); padding: var(--space-3) var(--space-4);
        transition: border-color var(--dur-base) var(--ease-out), transform var(--dur-base) var(--ease-out);
    }
    .ct-channel + .ct-channel { margin-top: var(--space-3); }
    .ct-channel:hover { border-color: var(--gold); transform: translateY(-2px); color: inherit; }
    .ct-channel__icono {
        flex: 0 0 44px; width: 44px; height: 44px; border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        background: var(--gold-subtle); color: var(--gold-dark);
    }
    .ct-channel__label {
        display: block; margin: 0;
        font-size: var(--text-xs); letter-spacing: 0.08em; text-transform: uppercase;
        color: var(--fg-subtle);
    }
    .ct-channel__valor {
        display: block; margin-top: 2px;
        font-size: var(--text-sm); font-weight: 600; color: var(--fg);
        word-break: break-word;
    }

    .ct-label { font-size: var(--text-sm); font-weight: 600; color: var(--fg); margin-bottom: 6px; }
    .ct-ayuda { font-size: var(--text-xs); color: var(--fg-subtle); margin-top: 6px; }

    @media (max-width: 575px) {
        .ct-hero { padding: 28px 0 20px; }
        .ct-panel { padding: 20px 16px; }
    }
</style>
<?php
$mc_head_extra = ob_get_clean();
require_once "partials/header.php";
?>

    <main class="container" style="padding-bottom:100px">
        <nav aria-label="breadcrumb" class="text-sm mb-2" style="color:var(--fg-subtle);padding-top:20px">
            <a href="index.php" style="color:var(--gold-dark);text-decoration:none">Inicio</a>
            <span style="color:var(--fg-subtle)"> &rsaquo; </span>
            <span style="color:var(--fg)">Contacto</span>
        </nav>

        <section class="ct-hero">
            <span class="badge badge-gold mb-3 d-inline-block">✦ Estamos para ayudarte ✦</span>
            <h1 class="ct-hero__titulo"><span class="text-gradient-celestial">Hablemos</span></h1>
            <p class="text-fg-muted text-lg max-w-2xl mx-auto mt-3 mb-0">
                Consultas por modelos, colores, stock, envíos o ventas por mayor.
                Te respondemos por WhatsApp de lunes a sábado de 10 a 19 h.
            </p>
        </section>

        <div class="row g-4 align-items-stretch">
            <!-- Canales directos -->
            <div class="col-12 col-lg-5">
                <div class="ct-panel">
                    <h2 class="display-card mb-3" style="color:var(--fg)">Canales directos</h2>

                    <a class="ct-channel" href="https://wa.me/<?= $wa ?>" target="_blank" rel="noopener">
                        <span class="ct-channel__icono" aria-hidden="true">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </span>
                        <span style="min-width:0">
                            <span class="ct-channel__label">WhatsApp</span>
                            <span class="ct-channel__valor"><?= htmlspecialchars(WHATSAPP) ?></span>
                        </span>
                    </a>

                    <a class="ct-channel" href="https://www.instagram.com/<?= urlencode($ig) ?>" target="_blank" rel="noopener">
                        <span class="ct-channel__icono" aria-hidden="true">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                        </span>
                        <span style="min-width:0">
                            <span class="ct-channel__label">Instagram</span>
                            <span class="ct-channel__valor">@<?= htmlspecialchars($ig) ?></span>
                        </span>
                    </a>

                    <a class="ct-channel" href="nosotros.php#envios">
                        <span class="ct-channel__icono" aria-hidden="true">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                        </span>
                        <span style="min-width:0">
                            <span class="ct-channel__label">Envíos y pagos</span>
                            <span class="ct-channel__valor">Todo el país · Ver detalles</span>
                        </span>
                    </a>

                    <a class="ct-channel" href="productos.php">
                        <span class="ct-channel__icono" aria-hidden="true">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                        </span>
                        <span style="min-width:0">
                            <span class="ct-channel__label">Catálogo</span>
                            <span class="ct-channel__valor">Ver todos los productos</span>
                        </span>
                    </a>

                    <p class="ct-ayuda mt-4 mb-0">
                        Horario de atención: lunes a sábado, 10 a 19 h.
                    </p>
                </div>
            </div>

            <!-- Formulario -->
            <div class="col-12 col-lg-7">
                <div class="ct-panel">
                    <h2 class="display-card mb-3" style="color:var(--fg)">Escribinos</h2>
                    <p class="text-fg-muted small mb-4">
                        Completá el formulario y se abrirá WhatsApp con tu mensaje listo para enviar.
                    </p>

                    <form id="formContacto" novalidate>
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="ct-label" for="ctNombre">Nombre</label>
                                <input class="form-control" type="text" id="ctNombre" name="nombre"
                                       autocomplete="name" required>
                                <p class="ct-ayuda" id="errNombre" style="display:none;color:#b91c1c">Completá tu nombre.</p>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="ct-label" for="ctTelefono">Teléfono (opcional)</label>
                                <input class="form-control" type="tel" id="ctTelefono" name="telefono"
                                       autocomplete="tel">
                            </div>
                            <div class="col-12">
                                <label class="ct-label" for="ctMensaje">Mensaje</label>
                                <textarea class="form-control" id="ctMensaje" name="mensaje" rows="5" required
                                          placeholder="Contame qué producto te interesa, talle, color o consulta."
                                          style="resize:vertical"></textarea>
                                <p class="ct-ayuda" id="errMensaje" style="display:none;color:#b91c1c">Escribí tu consulta.</p>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mt-4">
                            <button class="btn btn-primary" type="submit">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" class="me-1" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                Enviar por WhatsApp
                            </button>
                            <a class="btn btn-outline" href="productos.php">Ver catálogo</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

<?php
ob_start();
?>
<script>
    (function () {
        var form = document.getElementById('formContacto');
        if (!form) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            var nombre  = document.getElementById('ctNombre');
            var tel     = document.getElementById('ctTelefono');
            var mensaje = document.getElementById('ctMensaje');
            var errNom  = document.getElementById('errNombre');
            var errMsg  = document.getElementById('errMensaje');
            var ok = true;

            errNom.style.display = 'none';
            errMsg.style.display = 'none';

            if (!nombre.value.trim()) { errNom.style.display = ''; nombre.focus(); ok = false; }
            if (!mensaje.value.trim()) { errMsg.style.display = ''; if (ok) mensaje.focus(); ok = false; }
            if (!ok) return;

            var texto = 'Hola Marea Celestial! Soy ' + nombre.value.trim() + '.\n' + mensaje.value.trim();
            if (tel.value.trim()) texto += '\nTeléfono: ' + tel.value.trim();

            window.open('https://wa.me/<?= $wa ?>?text=' + encodeURIComponent(texto), '_blank', 'noopener');
        });
    })();
</script>
<?php
$mc_scripts = ob_get_clean();
require_once "partials/footer.php";
?>
