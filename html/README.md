# Marea Celestial — versión HTML estática

Sitio público exportado a HTML puro, sin PHP ni base de datos.

## Páginas

- `index.html` — inicio
- `productos.html` — catálogo completo (filtros por categoría con `?id=1`, `?id=2`, `?id=3`)
- `producto-<id>.html` — ficha de cada producto
- `colecciones.html`
- `nosotros.html`
- `contacto.html`
- `404.html` — página de error (configurala como 404 en tu hosting)

## Carrito y pedidos

El carrito usa `localStorage` y funciona sin servidor. Al confirmar un pedido se abre
WhatsApp con el detalle, en lugar de enviarlo a `api/pedido.php`.

## Publicación

Subí **todo el contenido de esta carpeta** (`html/`) a la raíz de tu hosting.
Los estilos (`assets/`) y las imágenes (`img/`) ya están incluidos.

## Antes de publicar

- Cambiá `https://tudominio.com` en `robots.txt` y `sitemap.xml` por tu dominio real.
- Los datos de transferencia del carrito siguen siendo de ejemplo; editalos en el HTML
  de cada página (buscar `Banco de la Nación Argentina`).
- Para regenerar el sitio después de cambiar productos en la base de datos, volvé a
  ejecutar el exportador.