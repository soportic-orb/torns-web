# Publicar «Funcionalidades de Torns» en www.torns.app (WordPress + Divi)

Checklist manual. Lo que se ha detectado de la web actual (12/10/2026):

- **Tema:** Divi.
- **SEO:** Yoast SEO (v28.5). Ya imprime un JSON-LD con `#organization` («Torns»), `#website`, `WebPage`, `SoftwareApplication` y `FAQPage` en la home, y **genera su propio `/llms.txt`**.
- **Idiomas:** TranslatePress, con prefijos `/ca/`, `/gl/`, `/eu/` y `hreflang` automáticos.
- **Servidor:** nginx. **No se ha detectado Cloudflare** (sin cabecera `cf-ray`). Si lo hay delante con otra configuración, aplica el punto 5.
- **robots.txt actual:** `Allow: /` para todos los agentes, sin bloqueos de bots de IA.
- **`/funcionalidades/`** no existe todavía (404).

Ficheros (en `content/features/build/`, se regeneran con `python3 scripts/build_features.py`):

| Fichero | Destino |
|---|---|
| `funcionalidades.es.html` | Contenido de la página `/funcionalidades/` |
| `schema.es.json` | JSON-LD de la página |
| `llms.txt` | `https://www.torns.app/llms.txt` |
| `llms-full.txt` | `https://www.torns.app/llms-full.txt` |
| `funcionalidades.es.md` | `https://www.torns.app/funcionalidades.md` |

---

## 1. Crear la página

- [ ] Páginas › Añadir nueva. Título: «Funcionalidades de Torns». Slug: `funcionalidades`.
- [ ] Plantilla de página sin barra lateral; **sin sliders, pestañas, acordeones ni carga diferida**: todo el texto tiene que estar en el HTML.
- [ ] Con el Divi Builder: una sección › una fila de una columna › **un módulo Código**. Pega el contenido completo de `funcionalidades.es.html`.
  - El HTML ya trae el `<h1>`. Si la plantilla de Divi pinta el título de la página como otro `<h1>`, desactívalo (Ajustes de página › «Mostrar título»: no) para que haya un solo H1.
  - Cada `<h2>` y `<h3>` tiene `id` para enlazar secciones (`#planes-y-precios`, `#preguntas-frecuentes`, `#organizaciones-panel-de-torns`…).
- [ ] Yoast en la página:
  - Frase clave: «funcionalidades Torns».
  - Título SEO: `Funcionalidades de Torns: app de cambios de turno para sanitarios`.
  - Meta descripción: el primer párrafo de la página (≤ 155 caracteres; recórtalo si hace falta).
- [ ] Publica.

## 2. Insertar el JSON-LD

Yoast no tiene un campo de schema personalizado por página en la versión gratuita. Usa **una** de estas dos opciones:

- [ ] **Opción A (recomendada):** en la misma página, añade un segundo módulo Código (al final) con:

  ```html
  <script type="application/ld+json">
  …contenido completo de schema.es.json…
  </script>
  ```

- [ ] **Opción B:** si usas un plugin de fragmentos (WPCode o similar), crea un fragmento «HTML en `<head>`» limitado a la página `funcionalidades` con el mismo `<script>`.

Notas:
- El JSON-LD usa los mismos `@id` que Yoast para la organización (`https://www.torns.app/#organization`), el sitio (`#website`) y la página, con datos compatibles: Google los fusiona en lugar de ver dos organizaciones.
- No dupliques el `FAQPage` en otro bloque de la página.

## 3. Ficheros en la raíz del dominio

- [ ] **Desactiva el `llms.txt` de Yoast antes de subir el nuestro:** Yoast SEO › Ajustes › Funciones del sitio › «llms.txt» › desactivar. Si no, Yoast lo regenera y sobrescribe el fichero.
- [ ] Sube por SFTP (o el gestor de archivos del hosting) a la raíz pública de WordPress, junto a `wp-config.php`:
  - `llms.txt`
  - `llms-full.txt`
  - `funcionalidades.es.md` **renombrado a `funcionalidades.md`**
- [ ] Comprueba que se sirven como texto:
  - `https://www.torns.app/llms.txt` → 200, `text/plain`
  - `https://www.torns.app/llms-full.txt` → 200, `text/plain`
  - `https://www.torns.app/funcionalidades.md` → 200, `text/markdown` o `text/plain`, con codificación UTF-8 (los acentos deben verse bien).
  - Si nginx sirve `.md` como descarga (`application/octet-stream`), añade en el bloque `server`: `types { text/markdown md; } charset utf-8;` o pide al hosting que lo haga.

## 4. robots.txt

El robots.txt actual ya permite todo. Si se edita (Yoast › Herramientas › Editor de archivos), mantén permitidos los rastreadores de búsqueda y recuperación:

```
User-agent: Googlebot
Allow: /

User-agent: Bingbot
Allow: /

User-agent: OAI-SearchBot
Allow: /

User-agent: ChatGPT-User
Allow: /

User-agent: Claude-SearchBot
Allow: /

User-agent: Claude-User
Allow: /

User-agent: PerplexityBot
Allow: /
```

- [ ] **Decisión tuya (entrenamiento de modelos):** `GPTBot`, `ClaudeBot` y `Google-Extended` hoy están permitidos por el `Allow: /` general. Si no quieres que el contenido se use para entrenar modelos, añade para cada uno `Disallow: /`. No afecta a aparecer en las respuestas con búsqueda (eso lo hacen los agentes de la lista anterior).

## 5. Cloudflare (solo si está delante del dominio)

No se ha detectado. Si se activa en el futuro:

- [ ] Security › Bots › **AI Crawl Control** (o «Block AI bots»): no bloquear Googlebot, Bingbot, OAI-SearchBot, ChatGPT-User, Claude-SearchBot, Claude-User ni PerplexityBot. Los de entrenamiento (GPTBot, ClaudeBot, Google-Extended), según tu decisión del punto 4.
- [ ] **Managed robots.txt:** desactivado, o revisado para que no añada `Disallow` a los agentes de búsqueda.
- [ ] Bot Fight Mode: comprobar que no devuelve desafíos a esos agentes.

## 6. Caché y minificación

- [ ] Si un plugin de caché u optimización (o la configuración de Divi › Opciones del tema › Rendimiento) minifica el HTML en línea, comprueba que el `<script type="application/ld+json">` llega intacto (ver código fuente de la página publicada). Si se rompe, excluye la página `/funcionalidades/` de la minificación de HTML/JS en línea.
- [ ] Purga la caché (Divi › Opciones del tema › Constructor › Avanzado › «Static CSS File Generation: Clear», y la del plugin o del hosting si la hay).

## 7. Enlazar e indexar

- [ ] Menú principal o pie: enlace «Funcionalidades» a `/funcionalidades/`.
- [ ] Home: un enlace interno en el texto (por ejemplo, «Ver todas las funcionalidades») a `/funcionalidades/`.
- [ ] Comprueba que aparece en `https://www.torns.app/page-sitemap.xml` (Yoast la añade sola al publicarla; si no, Yoast › Ajustes › Tipos de contenido › Páginas › mostrar en resultados).
- [ ] Google Search Console › Inspección de URLs › `https://www.torns.app/funcionalidades/` › «Solicitar indexación».
- [ ] Bing Webmaster Tools › Envío de URL (también alimenta a ChatGPT y Copilot de Microsoft).

## 8. Validar

- [ ] [Rich Results Test](https://search.google.com/test/rich-results) con la URL publicada: debe detectar «Preguntas frecuentes» y «Aplicación de software» sin errores.
- [ ] [Schema Markup Validator](https://validator.schema.org/) con la URL: sin errores; avisos de propiedades recomendadas aceptables.
- [ ] Ver código fuente: un solo `<h1>`, el JSON-LD íntegro, y todo el texto de la página presente en el HTML.

## 9. Mantener al día

Cada vez que cambie una funcionalidad, un precio o un límite: edita `content/features/funcionalidades.es.md`, ejecuta `python3 scripts/build_features.py`, y vuelve a pegar el HTML y el JSON-LD y a subir los tres ficheros de texto. `dateModified` se actualiza solo con la fecha de la compilación; actualiza también «Última actualización» en «Datos clave».
