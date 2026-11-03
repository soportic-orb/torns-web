# Enlaces cortos de las publicaciones

Cada publicación del blog puede tener un enlace corto por idioma:
`torns.app/wy89` lleva a `www.torns.app/blog/…` y `torns.app/k3m7` a
`www.torns.app/ca/blog/…`. Solo publicaciones, no páginas.

## Cómo se crean

- **Desde el editor** (Admin › Blog › Publicaciones › editar): el recuadro
  «Enlace corto» muestra el enlace del idioma que se está editando con un
  botón para copiarlo y sus visitas, o el botón «Generar enlace corto».
  La publicación tiene que estar guardada y publicada en ese idioma.
- **Al compartir en la web**: si la publicación aún no tiene enlace en ese
  idioma, al pulsar cualquier botón de compartir (WhatsApp, X, Facebook,
  LinkedIn, Email) la página lo pide (`POST /short-link`) y comparte el
  enlace corto. Espera 1,5 s como mucho; si no llega, comparte el enlace
  largo. Cuando ya existe, la página sale con él en los botones.

## Cómo funcionan

- Código de 4 caracteres exactos al azar, con minúsculas y números sin los
  que se confunden (`0 o 1 l i`): 31⁴ ≈ 920.000 combinaciones.
- Nunca coincide con un código existente, un idioma (`ca`, `gl`, `eu`), la
  dirección de una página o de otro contenido sin prefijo, una ruta ni las
  palabras de `ShortLinks::RESERVED`.
- `torns.app/wy89` redirige a `www.torns.app/wy89` (servidor) y de ahí, con
  un **302**, a la dirección actual de la publicación en su idioma: si
  cambia el slug, el enlace sigue funcionando. Si la publicación se borra o
  deja de estar publicada, lleva al blog de ese idioma.
- La redirección es un middleware del grupo `web` (`RedirectShortLinks`):
  solo actúa si el código existe; cualquier otra dirección de 4 caracteres
  sigue a Botble como siempre.
- Cada visita cuenta (`clicks`, `last_clicked_at`), salvo las peticiones
  HEAD y las de los robots de vista previa (WhatsApp, Facebook, LinkedIn,
  X, Telegram, Slack…).
- La vista previa al compartir (imagen y título) sale igual: las redes
  siguen la redirección hasta la publicación.

## Dónde está

| Qué | Dónde |
|---|---|
| Tabla `torns_short_links` (código, publicación, idioma, visitas) | `database/migrations/2026_11_03_000000_create_torns_short_links_table.php` |
| Crear, resolver y contar | `app/Support/ShortLinks.php`, `app/Models/ShortLink.php` |
| URL de una publicación en un idioma | `SiteIndex::postUrl()` |
| Redirección | `app/Http/Middleware/RedirectShortLinks.php` |
| `POST /short-link` (web, sin sesión, 30 por minuto) | `app/Http/Controllers/ShortLinkController.php`, `routes/seo-early.php` |
| Botón del editor | `app/Http/Controllers/Admin/ShortLinkController.php`, `resources/views/admin/short-link/panel.blade.php`, `public/vendor/torns/js/short-link.js`, `AppServiceProvider` |
| Botones de compartir | `platform/themes/torns/views/post.blade.php`, `assets/js/main.js` |

## Despliegue

El tema (botones de compartir) viaja por OTA; el resto, con la
actualización del servidor (`docs/CLOUDPANEL.md`): `git fetch`,
`git reset --hard origin/main` y `php artisan migrate --force`. Mientras
falte la tabla, los botones comparten el enlace largo y el recuadro del
editor no aparece.
