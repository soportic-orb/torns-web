# Paso a producción: www.torns.app con la nueva web

`www.torns.app` (WordPress) y `nueva.torns.app` (staging de esta web) están en
el mismo servidor de CloudPanel (187.33.149.129), así que **no hay que tocar
el DNS**. El cambio consiste en sustituir el sitio de WordPress por una
instalación nueva de esta web, con el contenido de staging.

- Staging **se queda** como está (`APP_ENV=staging`, no indexable) para
  seguir probando cambios.
- Producción es una instalación aparte (su usuario, su base de datos y su
  `.env` con `APP_ENV=production`), con una copia de la base de datos de
  staging: páginas, publicaciones, traducciones, menús, usuarios y ajustes
  (también las claves de IA, por eso se reutiliza la misma `APP_KEY`).
- CloudPanel no admite dos sitios con el mismo dominio: hay que borrar el
  sitio de WordPress antes de crear el nuevo. Con todo preparado, la web
  está caída unos **10–15 minutos**. Hazlo en una hora de poco tráfico.

Nombres de ejemplo (cámbialos si usas otros):

| | Staging | Producción |
|---|---|---|
| Dominio | `nueva.torns.app` | `www.torns.app` (y `torns.app` → www) |
| Usuario del sitio | `torns-nueva` | `torns-www` |
| Carpeta | `/home/torns-nueva/htdocs/nueva.torns.app` | `/home/torns-www/htdocs/www.torns.app` |
| Base de datos | la de staging (míralo en CloudPanel → Databases) | `tornsweb_prod` |

Las órdenes con `clpctl` se ejecutan como **root**; las de la web, como el
usuario del sitio (`sudo -u <usuario> …`).

## Antes del día del cambio

1. **Staging al día**: aplica la última actualización de la web y deja el
   código en `main` (`docs/CLOUDPANEL.md`, «Después»). Revisa páginas, menús
   y publicaciones: es lo que se copiará a producción.
2. **Usuario del WordPress actual**: anota en CloudPanel → Sites el usuario
   del sitio `www.torns.app` y el nombre de su base de datos.
3. **Bajada del TTL**: no hace falta, el DNS no cambia.

## Día del cambio

### 1. Copia de seguridad de WordPress (sin caída)

```bash
mkdir -p /root/backup-wordpress && cd /root/backup-wordpress
clpctl db:export --databaseName=<bd-wordpress> --file=/root/backup-wordpress/wordpress.sql.gz
tar -czf wordpress-files.tar.gz -C /home/<usuario-wordpress>/htdocs www.torns.app
ls -lh
```

Guarda también una copia fuera del servidor (descárgala con SFTP). Con esto
se puede volver atrás (ver «Volver a WordPress»).

### 2. Copia de la base de datos de staging (sin caída)

```bash
cd /root
clpctl db:export --databaseName=<bd-staging> --file=/root/staging.sql.gz
zcat staging.sql.gz | grep -c 'nueva.torns.app'
```

Si el último número no es 0, hay direcciones absolutas de staging en la base
de datos; se cambian por las de producción:

```bash
zcat staging.sql.gz | sed 's#https://nueva\.torns\.app#https://www.torns.app#g; s#nueva\.torns\.app#www.torns.app#g' | gzip > produccion.sql.gz
```

Si era 0: `cp staging.sql.gz produccion.sql.gz`.

### 3. Borrar el sitio de WordPress (empieza la caída)

CloudPanel → **Sites** → `www.torns.app` → **Delete** (o
`clpctl site:delete --domainName=www.torns.app --force`). Borra también su
base de datos si CloudPanel lo pregunta: ya está en la copia del paso 1.

### 4. Crear el sitio nuevo

1. **Sites → Add Site → Create a PHP Site**
   - Application: **Laravel**
   - Domain Name: **`www.torns.app`**. Después, en **Vhost**, comprueba que
     `torns.app` también aparece y redirige a `https://www.torns.app`; si no,
     añade al principio del vhost un bloque `server` con
     `server_name torns.app;` y `return 301 https://www.torns.app$request_uri;`
     (puertos 80 y 443, con el mismo certificado).
   - PHP Version: **8.4**
   - Site User: **`torns-www`** y una contraseña
2. En el sitio, **SSL/TLS → Actions → New Let's Encrypt Certificate**, con los
   dominios `www.torns.app` y `torns.app`.
3. **Databases → Add Database**: `tornsweb_prod`, con su usuario y contraseña
   (anótalos).
4. **Cron Jobs → Add Cron Job**:

   ```
   * * * * * php8.4 /home/torns-www/htdocs/www.torns.app/artisan schedule:run >> /dev/null 2>&1
   ```

### 5. Código y `.env`

```bash
cd /home/torns-www/htdocs/www.torns.app
sudo -u torns-www bash -c 'rm -rf ./* ./.[!.]* && git clone https://github.com/soportic-orb/torns-web.git . && composer install --no-dev --optimize-autoloader'
cp /home/torns-nueva/htdocs/nueva.torns.app/.env .env
chown torns-www:torns-www .env
nano .env
```

En `.env` cambia solo esto (el resto, incluida `APP_KEY`, se queda igual que
en staging):

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://www.torns.app
DB_DATABASE=tornsweb_prod
DB_USERNAME=<usuario de la base de datos de producción>
DB_PASSWORD=<contraseña>
```

### 6. Base de datos e imágenes

La versión instalada de las actualizaciones de la web se guarda en la base de
datos, así que viene con la copia; las copias de seguridad de esas
actualizaciones (`storage/app/ota`) no se copian, son de staging.

```bash
clpctl db:import --databaseName=tornsweb_prod --file=/root/produccion.sql.gz

# Imágenes subidas en /admin (Medios)
sudo -u torns-www mkdir -p storage/app
cp -a /home/torns-nueva/htdocs/nueva.torns.app/storage/app/public storage/app/
chown -R torns-www:torns-www storage

sudo -u torns-www php artisan migrate --force
sudo -u torns-www php artisan storage:link
sudo -u torns-www php artisan optimize:clear
sudo -u torns-www php artisan optimize
```

### 7. Imágenes antiguas de WordPress (recomendado)

Las imágenes de la web anterior (`/wp-content/uploads/…`) pueden estar
enlazadas desde Google Imágenes, redes sociales o correos ya enviados. Para
que sigan funcionando, se copian tal cual (nginx las sirve como ficheros):

```bash
cd /home/torns-www/htdocs/www.torns.app
tar -xzf /root/backup-wordpress/wordpress-files.tar.gz -C /tmp www.torns.app/wp-content/uploads
mkdir -p public/wp-content && mv /tmp/www.torns.app/wp-content/uploads public/wp-content/
chown -R torns-www:torns-www public/wp-content
```

Git no las sigue y las actualizaciones no las tocan.

### 8. Comprobar (termina la caída)

- `https://www.torns.app`, `/ca`, `/gl`, `/eu`, `/centros`, `/funcionalidades`,
  `/blog`, `/privacidad`, `/terminos`.
- `https://torns.app` y `http://www.torns.app` redirigen a `https://www.torns.app`.
- `https://www.torns.app/robots.txt` **permite** el rastreo y enlaza el sitemap
  (si dice `Disallow: /`, `APP_ENV` no es `production`: corrige `.env` y
  `php artisan optimize`).
- Direcciones antiguas: `/centros/`, `/ca/centres/`, `/torns-vs-aturnos/`,
  `/digitalizacion-cambios-turno-hospital/`, `/sitemap_index.xml` redirigen a
  la nueva (lista completa en `config/legacy-redirects.php`).
- `/<ADMIN_DIR>`: entra con tu usuario de staging; Sistema → Actualizaciones de
  la web sin avisos; Ajustes → Otros → IA para el blog → «Probar la conexión».
- El formulario de contacto envía el correo.
- Si algo da un 500: `tail -n 30 /home/torns-www/logs/php/error.log` y
  `grep -h '\.ERROR:' storage/logs/laravel-*.log | tail -n 3`.

## Después del cambio

- **Google Search Console** (propiedad `https://www.torns.app/`): Sitemaps →
  quita `sitemap_index.xml` y envía `sitemap.xml`. Revisa «Páginas» las
  semanas siguientes por si aparecen 404 de direcciones antiguas que falten en
  `config/legacy-redirects.php`.
- **Actualizaciones**: igual que en staging. El tema y el contenido, con el
  botón de actualizaciones en el panel de cada sitio (aplícalo primero en
  staging); el código, por SSH en cada carpeta (`docs/CLOUDPANEL.md`).
- **Staging**: a partir de ahora los cambios hechos en /admin de staging **no**
  pasan a producción (son bases de datos distintas). Las publicaciones y las
  páginas se editan en producción.
- Guarda la copia de WordPress unas semanas antes de borrarla.

## Volver a WordPress

Si algo sale mal y no se puede arreglar en el momento:

1. CloudPanel → Sites → borra `www.torns.app` (la nueva).
2. Crea de nuevo el sitio como estaba: **WordPress** (o PHP genérico) con
   dominio `www.torns.app` y el usuario anterior; certificado Let's Encrypt.
3. Restaura los ficheros y la base de datos:

   ```bash
   tar -xzf /root/backup-wordpress/wordpress-files.tar.gz -C /home/<usuario-wordpress>/htdocs
   chown -R <usuario-wordpress>:<usuario-wordpress> /home/<usuario-wordpress>/htdocs/www.torns.app
   clpctl db:add --domainName=www.torns.app --databaseName=<bd-wordpress> --databaseUserName=<usuario-bd> --databaseUserPassword='<contraseña de wp-config.php>'
   clpctl db:import --databaseName=<bd-wordpress> --file=/root/backup-wordpress/wordpress.sql.gz
   ```

   Los datos de la base de datos están en `wp-config.php` dentro de la copia.
