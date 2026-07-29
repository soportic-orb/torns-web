# INSTALL.md — Instalación en hosting compartido

Guía paso a paso para instalar la web de Torns (Botble CMS) en un hosting
compartido Linux **sin Composer ni Node en el servidor**. Todo lo que necesita
compilación viaja ya compilado en el paquete.

## 1. Requisitos del hosting

- **PHP 8.3 u 8.4** (esta versión de Botble requiere mínimo 8.3) con las
  extensiones: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO MySQL,
  Tokenizer, XML, cURL, Zip, GD, exif.
- **MySQL 5.7+ o MariaDB 10.3+**.
- Apache con `mod_rewrite` (o equivalente) y **gzip/brotli activo**
  (mod_deflate; imprescindible para el rendimiento medido).
- ~500 MB de espacio en disco.

## 2. Preparar el paquete en local

En tu máquina (con Composer 2 y Node 20+):

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build:torns
bash scripts/build-release.sh   # genera dist/torns-web-install.zip
```

El zip contiene el proyecto completo: `vendor/`, assets compilados del tema,
traducciones y contenido del seeder. Excluye `.git`, `node_modules` y `.env`.

## 3. Subir y desplegar

1. Sube `dist/torns-web-install.zip` por FTP/SFTP (o por el gestor de archivos
   del panel) y descomprímelo en el directorio de la web.
2. Elige cómo servir la aplicación:
   - **Opción A (preferida):** en el panel del hosting, apunta el *document
     root* del dominio al subdirectorio `public/`.
   - **Opción B:** si el hosting no permite cambiar el document root, deja el
     proyecto tal cual en la raíz web: el `.htaccess` de la raíz reescribe
     todas las peticiones hacia `public/` automáticamente.
3. Da permisos de escritura (755/775 según hosting) a `storage/` y
   `bootstrap/cache/` de forma recursiva.

## 4. Base de datos

En el panel del hosting crea:

1. Una base de datos MySQL (p. ej. `torns_web`) con cotejamiento
   `utf8mb4_unicode_ci`.
2. Un usuario con todos los privilegios sobre esa base de datos.

## 5. Configurar `.env`

Copia `.env.example` a `.env` y rellena:

- `APP_URL` — `https://www.torns.app` (o `https://nueva.torns.app` en pruebas,
  ver `docs/MIGRATION.md`).
- `APP_ENV` — `production` solo en la web definitiva (controla GA4);
  usa `staging` en `nueva.torns.app`.
- `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` — los del paso 4.
- `MAIL_USERNAME`, `MAIL_PASSWORD` — credenciales API de Mailjet
  (host `in-v3.mailjet.com:587` ya configurado; remitente `noreply@torns.app`).

## 6. Instalador web de Botble

1. Visita `https://tu-dominio/install` y sigue el asistente (genera la
   `APP_KEY`, ejecuta migraciones y crea el usuario administrador).
2. Entra en `/admin` y comprueba que el tema **torns** está activo
   (Apariencia → Temas); actívalo si no lo está.
3. Verifica en Admin → Plugins que están activos: Blog, Language,
   Language Advanced, Contact Form y Translation.

## 7. Contenido inicial (seeder)

El seeder crea idiomas, páginas en 4 idiomas, menús, categorías del blog,
metas SEO y opciones del tema. Es idempotente: re-ejecutarlo no duplica nada.

- **Con SSH:**

  ```bash
  php artisan db:seed --class=TornsContentSeeder
  ```

- **Sin SSH** (ruta protegida de un solo uso):
  1. Añade a `.env`: `INSTALL_SEED_TOKEN=<cadena-aleatoria-larga>`.
  2. Visita `https://tu-dominio/install-seed/<esa-cadena>`.
  3. La ruta se autodesactiva tras ejecutarse (crea
     `storage/installed-content-seed.lock`). Borra `INSTALL_SEED_TOKEN`
     del `.env`.

## 8. Cron del scheduler (opcional, recomendado)

Para publicaciones de blog programadas, añade en el panel del hosting:

```
* * * * * php /ruta/al/proyecto/artisan schedule:run >> /dev/null 2>&1
```

## 9. Checklist post-instalación

- [ ] HTTPS forzado (redirección http→https y no-www→www en el panel o `.htaccess`).
- [ ] `.env`: `APP_DEBUG=false` y `APP_ENV=production`.
- [ ] Cambiar la URL del panel: en `.env`, `ADMIN_DIR=<ruta-secreta>`
      (p. ej. `ADMIN_DIR=gestion-torns`) — soportado por Botble.
- [ ] Contraseña fuerte para el usuario administrador.
- [ ] Probar el formulario de `/contacta` (llega a la bandeja del panel y a
      `hola@torns.app` vía Mailjet).
- [ ] Verificar los 4 idiomas: `/`, `/ca`, `/gl`, `/eu` y alguna interior
      (`/ca/centros`).
- [ ] **GA4**: introducir el ID de medición en Admin → Apariencia →
      Opciones del tema → Torns → "GA4 Measurement ID". El snippet solo se
      emite con `APP_ENV=production`.
- [ ] `https://tu-dominio/sitemap.xml` responde y lista los 4 idiomas.
- [ ] Optimizar: Admin → Plataforma → Optimize (o `php artisan optimize`).
