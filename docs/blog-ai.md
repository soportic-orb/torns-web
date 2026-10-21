# IA para el blog

El editor de publicaciones (Blog → Publicaciones) puede redactar con IA el
cuerpo, el título y el resumen de una noticia, crear su imagen destacada y
traducirla al catalán, gallego y euskera.
Es el mismo planteamiento que el editor de Mailing del Tauler de iSEM.

## Activarla

**Ajustes → Otros → IA para el blog** (solo superadministradores):

- Interruptor «Activar la IA en el editor del blog». Está **apagado** por
  defecto; mientras lo esté, el editor no muestra nada.
- Clave de Anthropic (Claude) y/o de OpenAI (ChatGPT e imágenes). Se guardan
  cifradas con la `APP_KEY`. Vacía = se mantiene la actual; «-» = se borra.
- Modelos (por defecto `claude-opus-5-5`, `gpt-5` y `gpt-image-1`) y el
  servicio de texto por defecto.
- «Probar la conexión» hace una llamada mínima a cada servicio configurado.

También se pueden dar por `.env` (los ajustes del panel tienen prioridad):
`TORNS_AI_ENABLED`, `TORNS_AI_DEFAULT_TEXT`, `TORNS_ANTHROPIC_API_KEY`,
`TORNS_CLAUDE_MODEL`, `TORNS_OPENAI_API_KEY`, `TORNS_OPENAI_TEXT_MODEL`,
`TORNS_OPENAI_IMAGE_MODEL`.

## En el editor

Encima del contenido aparecen hasta cuatro botones:

- **✨ Escribir con IA**: petición libre, servicio (Claude o ChatGPT), qué
  escribir (artículo completo, título o resumen), idioma (por defecto el de
  la versión que se edita: es, ca, gl o eu), tono y extensión. Con «Usar el
  texto que ya hay» reescribe o resume el contenido actual. El resultado se
  previsualiza y se puede **sustituir el contenido**, **añadir al final**,
  usarlo como **título** o **descripción**, o **volver a generar**.
- **✨ Generar SEO con IA**: con el título, la descripción y el contenido que
  hay en el editor, rellena el **título SEO** (60 caracteres como máximo) y
  la **descripción SEO** (155) de la caja «Optimización SEO», en el idioma de
  la versión que se edita. Si el modelo se pasa de largo, se recorta por una
  frase o palabra completa. Hay que guardar la publicación.
- **✨ Crear la imagen destacada con IA**: descripción, formato (horizontal
  1536×1024 o cuadrada) y estilo (fotografía, ilustración o dibujo plano con
  los colores de Torns). La imagen se guarda en Medios → `blog-ia` y, con
  «Usar como imagen destacada», queda puesta en el campo Imagen (hay que
  guardar la publicación).

- **✨ Traducir con IA** (en una publicación ya guardada, desde la versión en
  castellano): idiomas (los activos, salvo el principal), servicio y «Sustituir
  las traducciones que ya existen» (apagado: las que ya hay no se tocan).
  Traduce un idioma tras otro y **guarda** cada traducción en cuanto llega;
  el diálogo indica el estado de cada uno. Se revisan en la pestaña de cada
  idioma del editor.

Escribir y crear la imagen no publica nada solo: la persona que edita revisa,
ajusta y guarda. La traducción sí se guarda, en la versión de cada idioma.

## Traducción

`App\Ai\PostTranslator` parte de la versión **guardada** en el idioma
principal (lo que no se haya guardado no se traduce) y escribe donde lo hace el
plugin language-advanced de Botble:

| Qué | Dónde |
|---|---|
| Título, descripción, contenido | `posts_translations` |
| Título y descripción SEO (y el valor de indexación del original) | meta `<idioma>_seo_meta` |
| URL: el título traducido en formato slug, único dentro de `blog/` | `slugs_translations` |

El contenido conserva su HTML (imágenes, enlaces, códigos cortos); solo se
quitan `<script>`, `<style>`, atributos `on…` y enlaces `javascript:`. Si la
respuesta llega incompleta (texto muy largo), no se guarda nada de ese idioma.
Las publicaciones creadas como publicación aparte en otro idioma (antiguo
sistema de idiomas) no se traducen desde aquí.

El sitemap, `llms.txt`, `llms-full.txt` y las URL `.md` incluyen las
traducciones (`App\Support\SiteIndex`).

Para traducir muchas a la vez, por SSH (sin límite de tiempo de la petición):

```bash
php artisan torns:blog:translate              # todas, a todos los idiomas, sin tocar las ya traducidas
php artisan torns:blog:translate 12 15 --lang=ca --provider=openai --overwrite
```

## Categorías y etiquetas

Cada categoría y cada etiqueta es **una sola fila**, en castellano, con sus
traducciones (nombre, descripción y URL) en las tablas de language-advanced
(`categories_translations`, `tags_translations`, `slugs_translations`), igual
que las publicaciones.

- **Automático**: al guardar una categoría o una etiqueta en castellano, y al
  guardar una publicación (para sus etiquetas nuevas), se traducen con IA las
  que faltan, después de responder (el editor no espera).
  Al traducir una publicación con «Traducir con IA», también se traducen sus
  categorías y etiquetas a ese idioma.
- **Nunca se sustituye una traducción escrita a mano**: solo se rellenan los
  idiomas sin traducción o con la copia del castellano que Botble crea al
  guardar (`App\Ai\TaxonomyTranslator::missing()`). Las que la IA ya tradujo
  quedan anotadas (meta `torns_ai_translated`), así que una palabra igual en
  los dos idiomas («Funcionalidades» en gallego) no se vuelve a traducir.
- **En el formulario** de la categoría o la etiqueta, el bloque «Traducciones»
  muestra cada idioma (Traducida / Pendiente) y el botón «Traducir con IA»;
  con «Volver a traducir todas» también sustituye las escritas a mano.
- **En el listado** de etiquetas (Blog → Etiquetas) y de categorías: marca las
  filas (o todas con la casilla de la cabecera) y elige Acciones → «Traducir
  con IA». En el diálogo se eligen los idiomas; se traducen una a una con una
  barra de progreso y, como en el resto, solo las pendientes salvo que se
  marque «Volver a traducir todas» (`App\Ai\TranslateTaxonomyBulkAction`,
  `public/vendor/torns/js/taxonomy-bulk-translate.js`).
- **URL**: se genera del nombre traducido (`/ca/blog/sanitat`) y es única en
  su prefijo (`App\Support\TranslatedSlug`). Si se escribe igual que en
  castellano, se usa la del castellano.
- **Comando** para las que ya existen:
  `php artisan torns:blog:translate-taxonomy [--type=category|tag] [--lang=ca] [--overwrite]`.

### Pasar las categorías antiguas a una sola fila

Las primeras categorías (Novedades, Funcionalidades, Sanidad) se crearon como
una fila por idioma. Para unirlas, una vez desplegado:

```bash
php artisan torns:blog:taxonomy-merge          # solo muestra qué haría
php artisan torns:blog:taxonomy-merge --apply  # copia de la base de datos antes
php artisan torns:blog:translate-taxonomy      # traduce con IA lo que falte
```

Por cada grupo se queda la fila en castellano. De cada una de las otras:
sus publicaciones pasan a la de castellano (ninguna pierde su categoría), sus
subcategorías también, su nombre y descripción pasan a ser la traducción de
ese idioma (salvo que ya haya una escrita a mano), su URL pasa a ser la URL
traducida y después se borra. Cada grupo va en una transacción. Volver a
ejecutarlo no hace nada.

## Qué sabe la IA de Torns

El prompt de sistema (`App\Ai\BlogAi::FACTS`) lleva una ficha con lo que se
puede afirmar de la app (funciones, planes, precios, idiomas) y prohíbe
atribuirle cualquier otra cosa, inventar cifras, fechas o normas (pone
`[dato]` o `[fuente]`), comparar Torns con otras apps por su nombre y dar
consejos médicos. **Mantén la ficha al día** cuando cambien funciones o
precios (las mismas fuentes que `content/site`).

## Límites y registro

- 20 textos, 20 SEO, 20 traducciones (una por idioma) y 10 imágenes por minuto y persona.
- Pueden usarla quienes tienen permiso para crear o editar publicaciones
  (traducir: editar).
- Cada llamada se anota en el log de Laravel (`Torns blog AI`) con la
  persona, el servicio, el modelo y los tokens de entrada y salida.
