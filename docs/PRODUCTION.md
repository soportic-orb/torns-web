# Paso a producción: www.torns.app con la nueva web

La web nueva ya está instalada en CloudPanel como el sitio `nueva.torns.app`
(usuario `torns-nueva`), en el mismo servidor que el WordPress de
`www.torns.app` (187.33.149.129), así que **no hay que tocar el DNS**. El
cambio consiste en que esa misma instalación responda a `www.torns.app`:

- `www.torns.app` → la web nueva (dominio principal).
- `torns.app` y `nueva.torns.app` → redirección 301 a `https://www.torns.app`,
  para que Google no vea dos copias de la web.
- La instalación pasa a `APP_ENV=production` (indexable). Desde ese momento
  **no hay staging**: lo que se edita en /admin se publica.
- El sitio de WordPress se borra (después de hacer copia), porque dos sitios
  no pueden tener el mismo dominio en nginx.

La web está caída solo entre el borrado de WordPress y el certificado nuevo:
unos **5 minutos**. Hazlo en una hora de poco tráfico.

Las órdenes con `clpctl` se ejecutan como **root**; las de la web, como el
usuario del sitio (`sudo -u torns-nueva …`). Cambia `<usuario-wordpress>` y
`<bd-wordpress>` por los del WordPress actual (CloudPanel → Sites y
Databases).

## Antes del día del cambio

1. **Código al día** en `nueva.torns.app`, con las redirecciones de las URL de
   WordPress (`config/legacy-redirects.php`):

   ```bash
   cd /home/torns-nueva/htdocs/nueva.torns.app
   sudo -u torns-nueva git fetch origin main
   sudo -u torns-nueva git reset --hard origin/main
   sudo -u torns-nueva php artisan migrate --force
   sudo -u torns-nueva php artisan optimize:clear
   ```

   Y la última actualización de la web aplicada desde el panel.
2. **Contenido revisado**: páginas, menús, publicaciones y traducciones de
   `nueva.torns.app` son los que se publicarán.

## Día del cambio

### 1. Copia de seguridad de WordPress (sin caída)

```bash
mkdir -p /root/backup-wordpress && cd /root/backup-wordpress
clpctl db:export --databaseName=<bd-wordpress> --file=/root/backup-wordpress/wordpress.sql.gz
tar -czf wordpress-files.tar.gz -C /home/<usuario-wordpress>/htdocs www.torns.app
cp /etc/nginx/sites-enabled/www.torns.app.conf vhost-wordpress.conf 2>/dev/null
ls -lh
```

Descarga también una copia fuera del servidor (SFTP).

### 2. Imágenes antiguas de WordPress (sin caída, recomendado)

Las imágenes de la web anterior (`/wp-content/uploads/…`) pueden estar
enlazadas desde Google Imágenes, redes sociales o correos ya enviados. Se
copian a la web nueva tal cual (nginx las sirve como ficheros; git no las
sigue y las actualizaciones no las tocan):

```bash
cd /home/torns-nueva/htdocs/nueva.torns.app
mkdir -p public/wp-content
cp -a /home/<usuario-wordpress>/htdocs/www.torns.app/wp-content/uploads public/wp-content/
chown -R torns-nueva:torns-nueva public/wp-content
```

### 3. Borrar el sitio de WordPress (empieza la caída)

CloudPanel → **Sites** → `www.torns.app` → **Delete**. Su base de datos puede
quedarse de momento (ya está en la copia del paso 1).

### 4. Vhost de nueva.torns.app

CloudPanel → **Sites** → `nueva.torns.app` → **Vhost**.

1. En el bloque `server { … }` principal (el que tiene `root` y las reglas de
   PHP), cambia la línea `server_name`:

   ```nginx
   server_name www.torns.app;
   ```

2. Añade **al principio** del vhost, antes de ese bloque, este otro bloque
   para los dominios que redirigen:

   ```nginx
   server {
     listen 80;
     listen [::]:80;
     listen 443 ssl;
     listen [::]:443 ssl;
     http2 on;
     {{ssl_certificate_key}}
     {{ssl_certificate}}
     server_name torns.app nueva.torns.app;
     location /.well-known/acme-challenge/ {
       root {{root}};
     }
     location / {
       return 301 https://www.torns.app$request_uri;
     }
   }
   ```

   `{{ssl_certificate}}`, `{{ssl_certificate_key}}` y `{{root}}` se escriben
   tal cual: CloudPanel los sustituye. Si las líneas `listen` del bloque
   principal son distintas (por ejemplo `listen 443 ssl http2;` sin
   `http2 on;`), copia las suyas en lugar de estas. La excepción de
   `/.well-known/acme-challenge/` permite renovar el certificado de los tres
   dominios.

3. **Save**. Si CloudPanel da un error de nginx, no guarda nada: revisa que
   las llaves `{ }` estén completas.

### 5. Certificado

`nueva.torns.app` → **SSL/TLS** → **Actions** → **New Let's Encrypt
Certificate**, con los dominios `www.torns.app`, `torns.app` y
`nueva.torns.app` → **Create and Install**.

### 6. La web en modo producción (termina la caída)

```bash
cd /home/torns-nueva/htdocs/nueva.torns.app
sudo -u torns-nueva nano .env
```

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://www.torns.app
```

```bash
sudo -u torns-nueva php artisan optimize:clear
sudo -u torns-nueva php artisan optimize
```

¿Hay direcciones de staging guardadas en la base de datos (en ajustes o en el
contenido)? Compruébalo:

```bash
clpctl db:export --databaseName=<bd-de-nueva> --file=/root/nueva.sql.gz
zcat /root/nueva.sql.gz | grep -o 'https\?://nueva\.torns\.app[^"<> ]*' | sort | uniq -c | sort -rn | head
```

Si sale algo, redirige igualmente a www, pero conviene cambiarlo desde /admin
(o pásamelo y lo vemos).

### 7. Comprobar

- `https://www.torns.app`, `/ca`, `/gl`, `/eu`, `/centros`, `/funcionalidades`,
  `/blog`, `/privacidad`, `/terminos`.
- `https://torns.app/centros`, `http://www.torns.app` y
  `https://nueva.torns.app/ca` redirigen a la misma ruta en
  `https://www.torns.app`.
- `https://www.torns.app/robots.txt` **permite** el rastreo y enlaza el
  sitemap (si dice `Disallow: /`, `APP_ENV` no es `production`).
- Direcciones antiguas: `/centros/`, `/ca/centres/`, `/torns-vs-aturnos/`,
  `/digitalizacion-cambios-turno-hospital/`, `/sitemap_index.xml` redirigen a
  la nueva (lista completa en `config/legacy-redirects.php`).
- `https://www.torns.app/wp-content/uploads/2026/03/tornsapp-logo.png` se ve.
- `https://www.torns.app/<ADMIN_DIR>`: entra (la sesión de nueva.torns.app no
  vale, hay que volver a iniciarla); Sistema → Actualizaciones de la web sin
  avisos; Ajustes → Otros → IA para el blog → «Probar la conexión».
- El formulario de contacto envía el correo.
- Si algo da un 500: `tail -n 30 /home/torns-nueva/logs/php/error.log` y
  `grep -h '\.ERROR:' storage/logs/laravel-*.log | tail -n 3`.

## Después del cambio

- **Google Search Console** (propiedad `https://www.torns.app/`): Sitemaps →
  quita `sitemap_index.xml` y envía `sitemap.xml`. Revisa «Páginas» las
  semanas siguientes por si salen 404 de direcciones antiguas que falten en
  `config/legacy-redirects.php`.
- **Actualizaciones**: igual que hasta ahora, en el mismo sitio. Las de la web
  (tema y contenido) se aplican directamente en producción; si quieres
  probarlas antes, hará falta montar otro staging.
- Guarda la copia de WordPress unas semanas; después borra su base de datos y
  `/root/backup-wordpress`.

## Volver a WordPress

1. Vhost de `nueva.torns.app`: deja solo el bloque principal con
   `server_name nueva.torns.app;` y vuelve a `APP_ENV=staging` en `.env`
   (`php artisan optimize`).
2. CloudPanel → **Add Site** → **WordPress** (o PHP), dominio `www.torns.app`,
   el usuario anterior; certificado Let's Encrypt para `www.torns.app` y
   `torns.app`.
3. Restaura ficheros y base de datos:

   ```bash
   tar -xzf /root/backup-wordpress/wordpress-files.tar.gz -C /home/<usuario-wordpress>/htdocs
   chown -R <usuario-wordpress>:<usuario-wordpress> /home/<usuario-wordpress>/htdocs/www.torns.app
   clpctl db:import --databaseName=<bd-wordpress> --file=/root/backup-wordpress/wordpress.sql.gz
   ```

   Si borraste la base de datos, créala antes en CloudPanel → Databases con el
   nombre, usuario y contraseña de `wp-config.php` (está en la copia).
