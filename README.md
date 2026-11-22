# Marea-celestial
Marroquinería urbana hecha para el día a día ✨

## Cómo abrir el sitio

El sitio real corre con Apache + MySQL de XAMPP (no usa `php artisan serve`).

1. Abrir el panel de XAMPP y arrancar **Apache** y **MySQL**.
2. Doble clic en `abrir-sitio.bat`.

- Sitio real: <http://localhost/Marea_celestial/>
- Versión estática exportada: `abrir-sitio.bat estatico` → <http://localhost/Marea_celestial/html/>

Si Apache no está corriendo, el `.bat` avisa y no abre el navegador.

## Estructura del repo

- **Raíz:** aplicación PHP real (`index.php`, `productos.php`, `producto.php`, etc.).
- `admin/`: panel de administración.
- `api/`: endpoints JSON (catálogo y pedidos).
- `partials/`: header, footer y carrito compartidos.
- `assets/`: CSS y JS del sitio.
- `img/`: imágenes del catálogo, promociones y fuentes para importar.
- `catalogo.sql`: esquema y datos iniciales de MySQL.
- `html/`: exportación HTML estática (se abre con `abrir-sitio.bat estatico`).
- `maquetas/`: HTML originales de diseño; **no se sirven** desde Apache.

## Configuración

La conexión a MySQL y el número de WhatsApp se leen desde variables de entorno.

1. Copiar `.env.example` como `.env`.
2. Ajustar `DB_HOST`, `DB_USER`, `DB_PASSWORD`, `DB_NAME` y `WHATSAPP`.

Si no existe `.env`, `config.php` usa los valores por defecto de XAMPP (`root`, sin password, base `catalogo`), así que el sitio sigue funcionando igual.

No subir credenciales reales: `.env` y `.env.*` están ignorados por Git. Apache también bloquea el acceso directo a `.env`.
