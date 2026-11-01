<?php
/* Carrito global - Marea Celestial
   Se incluye en todas las paginas publicas.FAB + modal + logica en localStorage. */
$waGlobal = preg_replace("/\D/", "", WHATSAPP);

/* Datos de transferencia.
   IMPORTANTE: son valores de EJEMPLO. Reemplazar por los datos reales
   de la cuenta y poner $mpAliasREAL = true antes de publicar.
   Mientras $mpAliasREAL sea false, la pantalla de transferencia muestra
   un aviso y NO se envian datos bancarios al cliente. */
$mpAlias      = "MCALESTIAL.2026";
$mpTitular    = "Marea Celestial";
$mpBanco      = "Banco de la Nación Argentina";
$mpCBU        = "0000000000000000000000";
$mpAliasREAL  = false;
?>

<!-- FAB global del carrito -->
<button class="cart-fab-global" type="button" data-bs-toggle="modal" data-bs-target="#modalCarrito" aria-label="Abrir mi carrito">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
    <span class="cart-fab-global__count" id="cartCountGlobal" data-count="0">0</span>
</button>

<!-- FAB global de WhatsApp -->
<a class="whatsapp-fab" href="https://wa.me/<?= $waGlobal ?>" target="_blank" rel="noopener" aria-label="Escribinos por WhatsApp">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
</a>

<!-- Modal carrito -->
<div class="modal fade" id="modalCarrito" tabindex="-1" aria-hidden="true" aria-labelledby="modalCarritoLabel">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="background:var(--color-bg-card);border:1px solid var(--color-accent-a22);border-radius:var(--radius-xl);box-shadow:var(--shadow-xl)">
            <div class="modal-header" style="border-bottom:1px solid var(--color-accent-a22)">
                <h5 class="modal-title" id="modalCarritoLabel" style="font-family:var(--font-display);color:var(--color-primary)">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--color-accent)" stroke-width="2" class="me-2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                    Mi carrito
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body" style="padding:var(--space-6)">
                <div id="cartEmpty" class="text-center py-5">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary-a45)" stroke-width="1.5" class="mb-3"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                    <p class="mb-3" style="color:var(--color-primary-a45)">Tu carrito está vacío</p>
                    <a href="productos.php" class="btn btn-primary">Ver catálogo</a>
                </div>
                <div id="cartItems"></div>
                <div class="d-flex justify-content-between align-items-center pt-3 mt-3 border-top" style="border-color:var(--color-accent-a22)!important">
                    <span style="color:var(--color-primary-a70)">Total</span>
                    <span class="fw-bold" style="font-size:1.5rem;color:var(--color-primary)" id="cartTotal">$0</span>
                </div>
            </div>

            <div class="modal-footer" style="border-top:1px solid var(--color-accent-a22);padding:var(--space-5) var(--space-6) var(--space-6)">
                <p class="w-100 mb-3" style="font-size:var(--text-sm);color:var(--color-primary-a70)">
                    Completá tus datos y elegí cómo pagar.
                </p>

                <div class="row g-2 mb-3 w-100">
                    <div class="col-md-5">
                        <input type="text" id="pedNombre" class="form-control" placeholder="Tu nombre" autocomplete="name" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" id="pedTelefono" class="form-control" placeholder="Tu WhatsApp" autocomplete="tel" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" id="pedLocalidad" class="form-control" placeholder="Localidad">
                    </div>
                </div>
                <div class="w-100 mb-3">
                    <textarea id="pedMensaje" class="form-control" rows="2" placeholder="Comentario opcional (color, envío, etc.)" style="resize:none"></textarea>
                </div>

                <!-- Metodos de pago -->
                <div class="w-100 mb-3">
                    <label class="metodo-pago mb-2">
                        <input type="radio" name="metodoPago" value="whatsapp" checked>
                        <span class="metodo-pago__icono" aria-hidden="true">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </span>
                        <span>
                            <strong>Confirmar por WhatsApp</strong>
                            <span class="d-block" style="font-size:var(--text-xs);color:var(--color-primary-a45)">Te escribimos y coordinamos todo</span>
                        </span>
                    </label>

                    <label class="metodo-pago mb-2">
                        <input type="radio" name="metodoPago" value="transferencia">
                        <span class="metodo-pago__icono" aria-hidden="true">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"></rect><path d="M2 10h20"></path></svg>
                        </span>
                        <span>
                            <strong>Transferencia bancaria</strong>
                            <span class="d-block" style="font-size:var(--text-xs);color:var(--color-primary-a45)">Te mostramos los datos al confirmar</span>
                        </span>
                    </label>

                    <label class="metodo-pago" id="filaMercadoPago" style="display:none">
                        <input type="radio" name="metodoPago" value="mercadopago">
                        <span class="metodo-pago__icono" aria-hidden="true">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2L3 14h7l-1 8 10-12h-7l1-8z"></path></svg>
                        </span>
                        <span>
                            <strong>MercadoPago</strong>
                            <span class="d-block" style="font-size:var(--text-xs);color:var(--color-primary-a45)">Tarjeta, débito o dinero en cuenta</span>
                        </span>
                    </label>
                </div>

                <!-- Datos de transferencia (ocultos por defecto) -->
                <div class="w-100 mb-3" id="panelTransferencia" style="display:none">
                    <div class="datos-cuenta">
                        <p class="mb-2"><strong>Banco:</strong> <?= htmlspecialchars($mpBanco) ?></p>
                        <p class="mb-2"><strong>Titular:</strong> <?= htmlspecialchars($mpTitular) ?></p>
                        <p class="mb-0"><strong>Alias:</strong> <span class="datos-cuenta__alias"><?= htmlspecialchars($mpAlias) ?></span></p>
                        <p class="mb-0 mt-2"><strong>CBU:</strong> <span style="font-family:monospace;font-size:var(--text-xs)"><?= htmlspecialchars($mpCBU) ?></span></p>
                        <?php if (!$mpAliasREAL): ?>
                        <p class="mb-0 mt-2" style="color:#b91c1c;font-size:var(--text-xs)">
                            <strong>Aviso:</strong> estos son datos de ejemplo. Hay que reemplazarlos por los reales antes de recibir pedidos.
                        </p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row g-2 w-100">
                    <div class="col-12 col-sm-6">
                        <button class="btn btn-primary w-100" id="btnPedir" data-metodo="whatsapp">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" class="me-2"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            Confirmar pedido
                        </button>
                    </div>
                    <div class="col-12 col-sm-6">
                        <button class="btn btn-outline w-100" id="btnVaciar" type="button" style="border-color:var(--color-accent-a40);color:var(--color-primary)">Vaciar carrito</button>
                    </div>
                </div>
                <div id="pedError" class="small mt-2 w-100" style="display:none;color:#b91c1c"></div>
            </div>
        </div>
    </div>
</div>

<!-- Toast -->
<div class="toast" id="toastAdd" style="position:fixed;bottom:24px;left:50%;transform:translateX(-50%) translateY(140%);z-index:2100;transition:transform 300ms cubic-bezier(0.16,1,0.3,1),opacity 300ms;opacity:0;background:var(--color-primary);color:var(--color-white);border:1px solid var(--color-accent);border-radius:var(--radius-full);padding:12px 22px">
    <span id="toastMsg">Agregado al carrito</span>
</div>

<script>
/* ============ Carrito global (todas las paginas) ============ */
window.MC_CARRITO_KEY = 'marea_celestial_carrito';

function mcLeerCarrito() {
    try { return JSON.parse(localStorage.getItem(window.MC_CARRITO_KEY) || '[]'); }
    catch (e) { return []; }
}
function mcGuardarCarrito(c) {
    localStorage.setItem(window.MC_CARRITO_KEY, JSON.stringify(c));
    mcActualizarCarrito();
}
function mcTotalCantidad(c) { return c.reduce(function (s, i) { return s + i.cantidad; }, 0); }
function mcTotalPrecio(c) {
    return c.reduce(function (s, i) {
        var n = Number(String(i.precio).replace(/[^0-9.,]/g, '').replace(/\./g, '').replace(',', '.'));
        return s + (isNaN(n) ? 0 : n) * i.cantidad;
    }, 0);
}
function mcFormato(n) { return '$' + Number(n).toLocaleString('es-AR'); }

/* Agrega un producto al carrito. Expuesto para que producto.php lo use. */
function mcAgregarAlCarrito(p) {
    var c = mcLeerCarrito();
    var ex = null;
    for (var i = 0; i < c.length; i++) { if (c[i].id === p.id) { ex = c[i]; break; } }
    if (ex) { ex.cantidad += (p.cantidad || 1); }
    else {
        c.push({
            id: p.id, nombre: p.nombre, precio: p.precio,
            precioForm: p.precioForm, imagen: p.imagen,
            cantidad: p.cantidad || 1
        });
    }
    mcGuardarCarrito(c);
    return c;
}

function mcActualizarCarrito() {
    var c = mcLeerCarrito();
    var total = mcTotalCantidad(c);

    var badge = document.getElementById('cartCountGlobal');
    if (badge) { badge.textContent = total; badge.setAttribute('data-count', total); }

    var legacy = document.getElementById('cartCount');
    if (legacy) { legacy.textContent = total; legacy.style.display = total > 0 ? 'flex' : 'none'; }

    var vacio = document.getElementById('cartEmpty');
    var items = document.getElementById('cartItems');
    var totalEl = document.getElementById('cartTotal');
    if (!items) return;

    if (!total) {
        if (vacio) vacio.style.display = '';
        items.innerHTML = '';
        if (totalEl) totalEl.textContent = '$0';
        return;
    }
    if (vacio) vacio.style.display = 'none';

    var html = '';
    c.forEach(function (i) {
        var sub = mcTotalPrecio([i]);
        var img = String(i.imagen || '').replace(/"/g, '');
        var nom = String(i.nombre || '').replace(/</g, '&lt;');
        html += '<div class="d-flex align-items-center gap-3 pb-3 mb-3 border-bottom" style="border-color:var(--color-accent-a22)!important">'
            + (img ? '<img src="' + img + '" alt="" style="width:56px;height:56px;object-fit:cover;border-radius:var(--radius-sm);border:1px solid var(--color-accent-a22);flex:0 0 56px">'
                   : '<div style="width:56px;height:56px;border-radius:var(--radius-sm);border:1px solid var(--color-accent-a22);flex:0 0 56px;background:var(--color-bg-elevated)"></div>')
            + '<div class="flex-grow-1" style="min-width:0">'
            + '<div class="fw-semibold small" style="color:var(--color-primary)">' + nom + '</div>'
            + '<div class="small" style="color:var(--color-primary-a45)">' + mcFormato(mcTotalPrecio([{ precio: i.precio, cantidad: 1 }])) + ' c/u</div></div>'
            + '<div class="d-flex align-items-center gap-2">'
            + '<button type="button" class="cantidad-caja" style="width:32px;height:32px" data-mc-accion="menos" data-id="' + i.id + '" aria-label="Restar">−</button>'
            + '<span class="fw-bold" style="min-width:20px;text-align:center">' + i.cantidad + '</span>'
            + '<button type="button" class="cantidad-caja" style="width:32px;height:32px" data-mc-accion="mas" data-id="' + i.id + '" aria-label="Sumar">+</button></div>'
            + '<div class="fw-bold small" style="min-width:76px;text-align:right">' + mcFormato(sub) + '</div>'
            + '<button type="button" data-mc-accion="borrar" data-id="' + i.id + '" aria-label="Quitar" style="border:none;background:transparent;color:var(--color-primary-a45);font-size:1.3rem;line-height:1;cursor:pointer;padding:0 4px">&times;</button>'
            + '</div>';
    });
    items.innerHTML = html;
    if (totalEl) totalEl.textContent = mcFormato(mcTotalPrecio(c));
}

function mcToast(msg) {
    var t = document.getElementById('toastAdd');
    var m = document.getElementById('toastMsg');
    if (!t || !m) return;
    m.textContent = msg;
    t.style.transform = 'translateX(-50%) translateY(0)';
    t.style.opacity = '1';
    clearTimeout(window._mcToastT);
    window._mcToastT = setTimeout(function () {
        t.style.transform = 'translateX(-50%) translateY(140%)';
        t.style.opacity = '0';
    }, 2600);
}

function mcAbrirCarrito() {
    if (window.bootstrap) {
        bootstrap.Modal.getOrCreateInstance(document.getElementById('modalCarrito')).show();
    }
}

/* Sincroniza pestanas abiertas */
window.addEventListener('storage', function (e) {
    if (e.key === window.MC_CARRITO_KEY) mcActualizarCarrito();
});

document.addEventListener('DOMContentLoaded', function () {
    mcActualizarCarrito();

    /* +/- / borrar */
    document.getElementById('cartItems')?.addEventListener('click', function (ev) {
        var btn = ev.target.closest('[data-mc-accion]');
        if (!btn) return;
        var accion = btn.getAttribute('data-mc-accion');
        var id = btn.getAttribute('data-id');
        var c = mcLeerCarrito();
        var idx = -1;
        for (var i = 0; i < c.length; i++) { if (String(c[i].id) === String(id)) { idx = i; break; } }
        if (idx === -1) return;

        if (accion === 'mas') { c[idx].cantidad++; }
        else if (accion === 'menos') {
            if (c[idx].cantidad > 1) c[idx].cantidad--;
            else c.splice(idx, 1);
        }
        else if (accion === 'borrar') { c.splice(idx, 1); }
        mcGuardarCarrito(c);
    });

    /* Metodo de pago -> muestra panel de transferencia */
    document.querySelectorAll('input[name="metodoPago"]').forEach(function (r) {
        r.addEventListener('change', function () {
            var panel = document.getElementById('panelTransferencia');
            var btn = document.getElementById('btnPedir');
            if (panel) panel.style.display = r.value === 'transferencia' ? '' : 'none';
            if (btn) {
                btn.setAttribute('data-metodo', r.value);
                btn.classList.toggle('btn-mp', r.value === 'mercadopago');
            }
        });
    });

    /* Vaciar */
    document.getElementById('btnVaciar')?.addEventListener('click', function () {
        if (!mcLeerCarrito().length) return;
        if (confirm('¿Vaciar el carrito?')) {
            localStorage.removeItem(window.MC_CARRITO_KEY);
            mcActualizarCarrito();
            mcToast('Carrito vaciado');
        }
    });

    /* Confirmar pedido */
    document.getElementById('btnPedir')?.addEventListener('click', function () {
        var btn = this;
        var carrito = mcLeerCarrito();
        var nombre = document.getElementById('pedNombre').value.trim();
        var telefono = document.getElementById('pedTelefono').value.trim();
        var localidad = document.getElementById('pedLocalidad').value.trim();
        var mensaje = document.getElementById('pedMensaje').value.trim();
        var metodo = btn.getAttribute('data-metodo') || 'whatsapp';
        var err = document.getElementById('pedError');

        function fallo(m) { err.textContent = m; err.style.display = ''; }

        if (!carrito.length) return fallo('El carrito está vacío.');
        if (!nombre) return fallo('Completá tu nombre.');
        if (!telefono) return fallo('Completá tu WhatsApp.');

        var total = mcTotalPrecio(carrito);
        err.style.display = 'none';
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Procesando...';

        fetch('api/pedido.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ nombre: nombre, telefono: telefono, localidad: localidad, mensaje: mensaje, metodo_pago: metodo, items: carrito })
        })
            .then(function (r) { return r.json(); })
            .then(function (r) {
                if (r.ok) {
                    localStorage.removeItem(window.MC_CARRITO_KEY);
                    mcActualizarCarrito();
                    var modal = bootstrap.Modal.getInstance(document.getElementById('modalCarrito'));
                    if (modal) modal.hide();
                    if (r.link_whatsapp) window.open(r.link_whatsapp, '_blank', 'noopener');
                    mcToast('¡Pedido #' + r.id_pedido + ' confirmado!');
                } else {
                    fallo(r.error || 'Error al enviar el pedido.');
                }
            })
            .catch(function () { fallo('No se pudo conectar. Revisá tu internet.'); })
            .finally(function () {
                btn.disabled = false;
                btn.innerHTML = 'Confirmar pedido';
            });
    });
});
</script>
