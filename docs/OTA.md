# Actualizaciones OTA del tema y del contenido

La plantilla (tema `torns`) y el contenido de la portada, las páginas
estáticas y los menús se actualizan desde el repositorio, sin FTP ni SSH:

1. Se edita en una rama (a mano o desde Claude Code) y se abre una PR.
2. Al fusionarla en `main`, la GitHub Action **OTA release** compila el tema,
   empaqueta las carpetas permitidas, firma el manifiesto y publica una
   release `ota-<versión>`.
3. Cada web (staging y producción) la detecta en menos de una hora y la
   muestra en **Admin → Administración de la plataforma → Sistema →
   Actualizaciones de la web**. La aplica una persona administradora con el
   botón «Aplicar la versión …».

## Qué se actualiza y qué no

| Se actualiza | No se toca nunca |
|---|---|
| `platform/themes/torns` (plantillas, CSS/JS, imágenes, traducciones del tema) | Núcleo de Botble, plugins, `vendor/` |
| `public/themes/torns` (los assets que sirve la web) | `.env`, `storage/`, medios subidos |
| `content/site` → páginas y menús en la base de datos | Noticias y categorías del blog, opciones del tema guardadas en el panel |

Una actualización que contenga cualquier otro fichero se rechaza. Los
cambios de base de datos (migraciones), del núcleo o de plugins siguen
yendo por el paquete de instalación (`docs/INSTALL.md`).

## El contenido como ficheros

```
content/site/pages.json                 slug, plantilla, nombre y SEO de cada página, por idioma
content/site/pages/<clave>.<idioma>.html  contenido (shortcodes del tema o HTML)
content/site/menus.json                 menús, con una etiqueta por idioma
```

Al aplicar una OTA se escriben en la base de datos con estas reglas:

- Página o menú igual al del repositorio: no se hace nada.
- Cambiado en el repositorio y **no** editado en /admin desde la última
  OTA: se actualiza.
- **Editado en /admin** desde la última OTA: se respeta la edición y se
  avisa en la pantalla de actualizaciones («No tocadas porque se editaron en
  /admin»). El botón «Usar la versión del repositorio» las sobrescribe
  (la edición queda en la copia de seguridad).

La huella de lo último escrito se guarda en el ajuste
`torns_site_content_state`. En una web instalada antes de existir la OTA
no hay huella: la primera OTA adopta las páginas tal como vienen del
repositorio.

Para que lo editado en /admin pase al repositorio, hay que copiarlo a su
fichero en `content/site/pages/` en una PR.

## Seguridad

- El manifiesto (`ota-manifest.json`, con el sha256 del paquete) va
  firmado con Ed25519. La web solo acepta paquetes firmados con la clave
  privada que está en el secreto `OTA_SIGNING_KEY` del repositorio y cuyo
  hash coincide.
- Antes de aplicar se guarda una copia de las carpetas y del contenido de
  las páginas en `storage/app/ota/backups` (se conservan las 3 últimas).
  «Deshacer la última actualización» la restaura.
- Cada carpeta se sustituye con un par de renombrados: si algo falla a
  medias, se deshace lo ya cambiado.
- Solo los superadministradores ven la pantalla y pueden aplicar.

## Puesta en marcha (una vez)

1. **Claves.** En local:

   ```bash
   bash scripts/ota-keygen.sh /ruta/segura
   ```

   - Pega el contenido de `ota-signing-key.pem` en GitHub → repositorio
     `torns-web` → Settings → Secrets and variables → Actions → New
     repository secret, con el nombre `OTA_SIGNING_KEY`. Después borra el
     fichero.
   - Añade la línea `OTA_PUBLIC_KEY=…` que imprime el script al `.env` de
     staging y de producción.

2. **`.env` de cada web** (opcionales):

   ```
   OTA_REPOSITORY=soportic-orb/torns-web
   OTA_GITHUB_TOKEN=   # solo si el repositorio pasa a ser privado: token fine-grained con permiso de lectura de Contents
   ```

3. **Cron** del scheduler (`docs/INSTALL.md`, paso 8): comprueba cada hora
   si hay versión nueva. Sin cron, el botón «Buscar actualizaciones» hace
   lo mismo.

4. **Primera OTA.** El paquete de instalación de esta versión ya trae el
   código de la OTA. A partir de ahí, cualquier PR que toque el tema o
   `content/site` genera una release al fusionarse.

La pantalla de actualizaciones avisa si falta algo (extensiones `zip` o
`sodium` de PHP, la clave pública, o permisos de escritura).

## Desde la línea de comandos

```bash
php artisan torns:ota:check          # busca una versión nueva
php artisan torns:ota:apply          # la aplica (pide confirmación)
php artisan torns:ota:rollback       # vuelve a la copia anterior
php artisan torns:content:sync       # escribe content/site en la base de datos, respetando /admin
php artisan torns:content:sync --force  # … sobrescribiendo lo editado en /admin
```
