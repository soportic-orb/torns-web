# Instalación en un servidor con CloudPanel

Con SSH y Composer en el servidor no hace falta el paquete zip de
`docs/INSTALL.md` ni el instalador web: se clona el repositorio y se
instala por línea de comandos. Los assets del tema van compilados en el
repositorio (no hace falta Node).

Ejemplo para staging (`nueva.torns.app`, usuario de sitio `torns-nueva`).
Para producción, lo mismo con `www.torns.app` y `APP_ENV=production`.

## 1. En CloudPanel

1. **Sites → Add Site → Create a PHP Site**: plantilla **Laravel** (raíz en
   `public/`), dominio `nueva.torns.app`, PHP **8.4**. Anota el usuario del sitio.
2. **SSL/TLS** del sitio → Let's Encrypt.
3. **Databases → Add Database** (por ejemplo `tornsweb`), con su usuario.
4. **Cron Jobs** del sitio:

   ```
   * * * * * php8.4 /home/torns-nueva/htdocs/nueva.torns.app/artisan schedule:run >> /dev/null 2>&1
   ```

## 2. Código y `.env` (SSH)

Trabaja siempre como el usuario del sitio (`su - torns-nueva`, o
`sudo -u torns-nueva …` desde root). Si algo se crea como root, PHP no podrá
escribir en `storage/`, `bootstrap/cache/` ni en `.env` y la web dará un 500.

```bash
cd ~/htdocs/nueva.torns.app
rm -rf ./* ./.[!.]*                      # la página de ejemplo de CloudPanel
git clone https://github.com/soportic-orb/torns-web.git .
composer install --no-dev --optimize-autoloader
cp .env.example .env
nano .env
```

En `.env`:

- `APP_ENV=staging` (o `production` en www), `APP_URL=https://nueva.torns.app`
- `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`: los del paso 1
- `CMS_ENABLE_INSTALLER=false` (no se usa el instalador web)
- `ADMIN_DIR=<ruta-secreta>`, `MAIL_USERNAME`, `MAIL_PASSWORD`
- `OTA_PUBLIC_KEY=…` (`docs/OTA.md`)

## 3. Instalar

```bash
php artisan key:generate --force
php artisan optimize:clear
php artisan migrate --force
for p in language language-advanced blog contact translation; do php artisan cms:plugin:activate $p; done
php artisan tinker --execute='setting()->forceSet("theme","torns")->save();'
php artisan cms:user:create              # nombre, email, usuario y contraseña del admin
php artisan db:seed --class=TornsContentSeeder --force
php artisan storage:link
php artisan optimize
```

`cms:theme:activate` falla en una base de datos recién creada; por eso el
tema se activa con `tinker`.

Si algo da un 500: `tail -n 30 ~/logs/php/error.log` y
`grep -h '\.ERROR:' storage/logs/laravel-*.log | tail -n 3`.

## 4. Comprobar

- `/`, `/ca`, `/gl`, `/eu`, `/funcionalidades`, `/sitemap.xml`, `/llms.txt`.
- `/robots.txt`: `Disallow: /` en staging; en producción permite y enlaza el sitemap.
- `/<ADMIN_DIR>` → Administración de la plataforma → Sistema → Actualizaciones
  de la web: sin avisos; «Buscar actualizaciones» y aplicar la última versión.
- Ajustes → Otros → IA para el blog, si se va a usar.

## Después

- **Tema y contenido**: con el botón de actualizaciones OTA (`docs/OTA.md`).
- **Código** (`app/`, plugins, migraciones), como usuario del sitio:

  ```bash
  git pull && composer install --no-dev --optimize-autoloader && php artisan migrate --force && php artisan optimize
  ```
