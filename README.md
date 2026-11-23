# Marea-celestial
Marroquinería urbana hecha para el día a día ✨

## Cómo abrir el sitio

El sitio real corre con Apache + MySQL de XAMPP (no usa `php artisan serve`).

1. Abrir el panel de XAMPP y arrancar **Apache** y **MySQL**.
2. Importar `catalogo.sql` en phpMyAdmin (una sola vez).
3. Abrir la URL que corresponda:

- Sitio real: <http://localhost/Marea_celestial/>
- Versión estática exportada: <http://localhost/Marea_celestial/html/>

Si Apache no está corriendo, las URL no cargan.

## Estructura del repo

- **Raíz:** aplicación PHP real (`index.php`, `productos.php`, `producto.php`, etc.).
- `admin/`: panel de administración.
- `api/`: endpoints JSON (catálogo y pedidos).
- `partials/`: header, footer y carrito compartidos.
- `assets/`: CSS y JS del sitio.
- `img/`: imágenes del catálogo, promociones y fuentes para importar.
- `catalogo.sql`: esquema y datos iniciales de MySQL.
- `html/`: exportación HTML estática (<http://localhost/Marea_celestial/html/>).
- `maquetas/`: HTML originales de diseño; **no se sirven** desde Apache.
- `docs/`: documentación y GitHub Pages; `docs/index.html` es el mapa completo por carpeta y archivo.

## Mapa técnico y GitHub Pages

`docs/index.html` es la versión pública de la documentación: resume el propósito de Marea Celestial, el rol de cada carpeta y qué hace cada archivo conservado, con sus dependencias y reglas de mantenimiento. `docs/resumen-proyecto.html` es la misma guía para consulta local.

- URL pública: <https://claudiopiana27-prog.github.io/Marea-celestial/>
- Fuente a seleccionar en **Settings > Pages**: rama `main`, carpeta `/docs`.
- `docs/.nojekyll` evita que GitHub intente compilar el sitio con Jekyll.

Para verlo en local, abrir `docs/index.html` en el navegador.

## Configuración

La conexión a MySQL y el número de WhatsApp se leen desde variables de entorno.

1. Copiar `.env.example` como `.env`.
2. Ajustar `DB_HOST`, `DB_USER`, `DB_PASSWORD`, `DB_NAME` y `WHATSAPP`.

Si no existe `.env`, `config.php` usa los valores por defecto de XAMPP (`root`, sin password, base `catalogo`), así que el sitio sigue funcionando igual.

No subir credenciales reales: `.env` y `.env.*` están ignorados por Git. Apache también bloquea el acceso directo a `.env`.
