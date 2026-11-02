# mochcun.com

Sitio público de **SERVICIOS DE AUTOTRANSPORTE MOCHCUN, S.A. DE C.V.**: PHP 8.1+ sin framework, pensado para cPanel. Incluye un blog con panel de administración (SQLite).

## Qué se conservó para no perder el SEO

- **Las mismas URLs** del sitio anterior, con diagonal final (`/gestoria-licencia-federal-cancun/`, etc.).
- **Los mismos títulos y descripciones**, salvo correcciones: acentos, NOM actualizadas y el título de *Nosotros*, que estaba duplicado con el de *Emisiones*.
- **Todo el texto de cada página**, depurado de datos vencidos (avisos de la pandemia) y de errores de captura.
- **Redirecciones 301** desde `/pagina.php`, `/pagina` (sin diagonal), `www.` y `http://` hacia una sola versión: `https://mochcun.com/pagina/`.
- **Las páginas eliminadas** redirigen a la más cercana: `/covid-19/` → `/faqs/` y `/registro/` → `/contacto/`.
- **Mejoras nuevas para Google:** etiqueta canonical, `sitemap.xml` (se genera solo e incluye los artículos), `robots.txt`, y datos estructurados (negocio local, servicios, preguntas frecuentes, migas de pan y artículos).

## Estructura

```
index.php, *.php        Páginas públicas (una por URL)
blog/                   Listado, artículo y RSS del blog
admin/                  Panel para escribir artículos y leer mensajes
app/                    Configuración, base de datos, SEO y plantilla (bloqueado a la web)
assets/                 CSS, JS, fuentes e imágenes optimizadas (WebP)
storage/                Base de datos SQLite (bloqueado a la web; no va en Git)
uploads/blog/           Imágenes de los artículos (no va en Git)
documentos/             PDF de las NOM (viven en el servidor; no van en Git)
tools/dev-router.php    Solo para probar en local
```

Los datos de contacto, el WhatsApp, la dirección y las categorías del blog se editan en `app/config.php`.

## Publicar en cPanel

1. **Respaldo:** descarga una copia completa de `public_html` actual desde el Administrador de archivos.
2. **No borres** `public_html/documentos/`, porque ahí están los PDF de las NOM con los nombres que ya enlaza el sitio. Puedes dejar también `public_html/images/` mientras Google actualiza su índice de imágenes.
3. **Elimina** los archivos viejos que ya no se usan: `inc/`, `css/`, `js/`, `plugins/`, `fonts/`, `covid-19.php`, `registro.php`, `submit.php` e `index - copia.php`. Sube el contenido de este repositorio a `public_html` reemplazando las páginas.
4. **Permisos de escritura:** las carpetas `storage/` y `uploads/blog/` deben poder escribirse (755 suele bastar en cPanel).
5. **Crea tu usuario:** entra a `https://mochcun.com/admin/`. La primera vez te pide crear la cuenta de administrador.
6. **Revisa en el navegador:**
   - Abre `http://www.mochcun.com/contacto.php`: debe terminar en `https://mochcun.com/contacto/`.
   - Abre `https://mochcun.com/sitemap.xml`.
   - Envía un mensaje de prueba desde Contacto.
7. **En Google Search Console:**
   - Envía `https://mochcun.com/sitemap.xml`.
   - Usa *Inspección de URL* en la página de inicio y en las 3 o 4 páginas que más tráfico reciben.
   - Revisa *Páginas* durante 2 a 4 semanas.

> El `.htaccess` ya incluye, al final, los bloques de cPanel con la versión de PHP del servidor actual (ea-php81). Si cambias la versión de PHP desde *MultiPHP Manager*, cPanel los reescribe solo.

## Probar en local

```bash
php -S localhost:8000 tools/dev-router.php
```

## Analítica

Se mantiene Google Tag Manager (`GTM-MNFQFQ7`). El script se carga después de la primera interacción o 2.5 s después de que la página termina de cargar, para no frenarla. Si Google Analytics no está configurado dentro de Tag Manager, escribe el ID de GA4 en `ga4_id` en `app/config.php`.
