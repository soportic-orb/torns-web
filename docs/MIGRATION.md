# MIGRATION.md — Plan de corte desde WordPress

Plan para sustituir el WordPress actual de `www.torns.app` por la nueva web
Botble sin pérdida de SEO ni tiempo de caída perceptible.

## Fase A — Instalación de pruebas en `nueva.torns.app`

1. Crea el subdominio `nueva.torns.app` en el mismo hosting compartido y sigue
   `docs/INSTALL.md` con:
   - `APP_URL=https://nueva.torns.app`
   - `APP_ENV=staging` *(GA4 nunca se emite fuera de production)*

2. **Mientras esté en pruebas, bloquea la indexación** (las tres cosas):

   | Protección | Cómo activarla | Cómo desactivarla en el corte |
   |---|---|---|
   | `robots.txt` con `Disallow: /` | Admin → Configuración → General → contenido de robots.txt: `User-agent: *` + `Disallow: /` | Restaurar el robots.txt por defecto (permitir todo + sitemap) |
   | Meta `noindex` global | Admin → Configuración → General → desmarcar "index" del sitio (SEO) | Volver a marcar "index" |
   | Autenticación HTTP básica | En el panel del hosting (protección de directorios) o `.htaccess` de la raíz con `AuthType Basic` + `.htpasswd` | Eliminar las directivas Auth del `.htaccess` |

3. **Validación completa en `nueva.torns.app`:**
   - [ ] Los 4 idiomas: `/`, `/ca`, `/gl`, `/eu` (home, centros, contacta,
         terminos, privacidad, blog).
   - [ ] Formulario de contacto: entrada en el panel + email a `hola@torns.app`.
   - [ ] Panel `/admin`: edición de páginas, theme options (precios y
         comparativa), menús, blog (crear y despublicar una entrada de prueba).
   - [ ] Rendimiento: PageSpeed Insights móvil ≥ 90 (con gzip activo).
   - [ ] `sitemap.xml`, `hreflang` en el `<head>`, JSON-LD (validar con
         https://search.google.com/test/rich-results).

## Fase B — Corte

Idealmente en franja de poco tráfico:

1. Copia de seguridad completa del WordPress (archivos + BD).
2. En el panel del hosting, **cambia el document root de `www.torns.app`** a la
   instalación validada (o mueve la instalación al directorio del dominio).
3. En `.env`: `APP_URL=https://www.torns.app` y `APP_ENV=production`; luego
   `php artisan optimize:clear` (o borrar `bootstrap/cache/*.php` por FTP).
4. **Retira las tres protecciones** de la tabla anterior (robots, noindex, HTTP auth).
5. Añade las reglas 301 de restos de WordPress al `.htaccess` de la raíz
   (tabla en `docs/redirects.md`).
6. Introduce el **ID de GA4** en Opciones del tema (queda activo desde el
   día 1 al estar en production).

## Fase C — Verificación post-corte

- [ ] `curl -I https://www.torns.app/centros/` → `301` → `/centros` → `200`.
- [ ] `curl -I http://torns.app/` → `301` → `https://www.torns.app/`.
- [ ] Las 4 homes por idioma responden `200` y el `<head>` contiene los
      `hreflang` correctos con las URLs definitivas.
- [ ] `https://www.torns.app/sitemap.xml` correcto → **reenviar el sitemap en
      Google Search Console**.
- [ ] GA4 registra visitas (Tiempo real).
- [ ] Formulario de contacto en producción.

## Fase D — Respaldo

- Mantén la copia del WordPress (archivos + BD) en **almacenamiento privado
  durante 30 días** (no accesible por web).
- Pasados 30 días sin incidencias, elimínala.
- Vigila Search Console las primeras 2 semanas (cobertura + páginas 404) y
  añade redirecciones puntuales si aparece alguna URL antigua relevante.
