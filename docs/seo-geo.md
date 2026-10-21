# SEO y lectura por IA (Google, Bing, ChatGPT, Claude, Gemini, Perplexity…)

Qué publica la web para buscadores y asistentes de IA, de dónde sale y qué
hay que mantener.

## Qué hay

| URL | Qué es | Fuente |
|---|---|---|
| `/robots.txt` | En producción: todo permitido, con los rastreadores de IA nombrados (OAI-SearchBot, ChatGPT-User, GPTBot, Claude-SearchBot, Claude-User, ClaudeBot, PerplexityBot, Google-Extended, Applebot-Extended…) y la línea `Sitemap:`. Fuera de producción: `Disallow: /`. | `App\Http\Controllers\SeoController` |
| `/sitemap.xml` | Un solo sitemap con las páginas y las noticias publicadas de los 4 idiomas, `lastmod` y alternativas `hreflang` (+ `x-default`). `/ca/sitemap.xml`, `/gl/…` y `/eu/…` redirigen aquí. | `App\Support\SiteIndex` |
| `/llms.txt` | Resumen para LLM ([llmstxt.org](https://llmstxt.org)): qué es Torns, páginas clave con su descripción, otros idiomas, noticias, legal. | `SiteIndex::llmsTxt()` |
| `/llms-full.txt` | La página de funcionalidades completa y las 30 últimas noticias, en Markdown. | `SiteIndex::llmsFullTxt()` |
| `/funcionalidades.md`, `/ca/funcionalidades.md`… | La página de funcionalidades en Markdown. | `content/site/markdown/` |
| `/blog/<slug>.md`, `/ca/blog/<slug>.md`… | Cada noticia en Markdown (con fecha y URL de origen). | se genera del cuerpo de la noticia |

Las versiones `.md` llevan `Link: rel="canonical"` hacia la página HTML y
`X-Robots-Tag: noindex`, para que el buscador indexe la HTML.

Sustituyen a las de Botble: su sitemap por idioma listaba las páginas de
todos los idiomas bajo cada prefijo, su `llms.txt` enlazaba las versiones
CA/GL/EU a la URL en castellano y su `llms-full.txt` salía vacío.

## En cada página

- `<title>`, `meta description`, canonical y `hreflang` (Botble + SEO de cada
  página en `content/site/pages.json`, o de cada noticia).
- Open Graph con imagen (la destacada de la noticia o `og-banner.jpg`),
  `og:locale` y tarjeta grande de X/Twitter.
- Un solo bloque JSON-LD `@graph` con `@id` estables:
  - `Organization` (`/#organization`): Torns, Soportic, logo, contacto, tiendas.
  - `WebSite` (`/#website`) en los 4 idiomas.
  - `MobileApplication` (`/#app`): descripción, plataformas, funciones (`featureList`), precios, capturas.
  - `WebPage` de la página o noticia, en su idioma.
  - `FAQPage` en la portada (las preguntas del bloque FAQ) y en
    Funcionalidades (`content/site/schema/funcionalidades.<idioma>.json`).
  - `BlogPosting` en cada noticia: titular, descripción, imagen, fechas,
    idioma, categorías, etiquetas y número de palabras.
- Los `WebSite`, `Organization` (sin logo) y `NewsArticle` que Botble añade
  por su cuenta se quitan; las migas de pan (`BreadcrumbList`) se mantienen.
- Fuera de producción todas las respuestas llevan `X-Robots-Tag: noindex,
  nofollow`: staging (`nueva.torns.app`) no debe indexarse nunca.

## Contenido pensado para que lo citen

- **Funcionalidades** (`/funcionalidades`, 4 idiomas) es la página de
  referencia: cada apartado se entiende solo, nombra «Torns», dice cómo se
  usa, en qué plan está y sus límites, con la fecha de última actualización.
  Viene del trabajo de `feature/ai-features-page` (inventario verificado contra
  el código de la app) y vive en `content/site/pages/funcionalidades.*.html`
  y `content/site/markdown/funcionalidades.*.md`.
- La portada y las páginas tienen un solo `<h1>` y apartados `<h2>`.
- Las noticias redactadas con la IA del editor salen con apartados `<h2>`,
  sin datos inventados (marcadores `[dato]`) y sin comparar con otras apps
  (`docs/blog-ai.md`).

## Categorías y etiquetas por idioma

- URL traducida en cada idioma (`/ca/blog/sanitat`, `/eu/blog/osasuna`,
  `/ca/blog/tag/guardies`). La URL de otro idioma o la anterior a la
  traducción (`/ca/blog/sanidad`) redirige con **301** a la buena, también
  en las publicaciones (Botble hacía 302); se conserva `?page=`.
- `hreflang` y `canonical` con las URL traducidas (plugin language).
- Título «Sanitat · Blog de Torns» y descripción en el idioma de la página,
  salvo que la categoría tenga sus propios textos SEO
  (`torns_blog_taxonomy_seo()` en `functions/seo.php`).
- JSON-LD: `CollectionPage` y `BreadcrumbList` (Inicio › Blog › Categoría) en
  categorías y etiquetas, `BreadcrumbList` (Inicio › Blog › Categoría ›
  Publicación) en cada publicación, con los nombres traducidos; el
  `articleSection` y las `keywords` del `BlogPosting` también.
- Las etiquetas con menos de 2 publicaciones (`SiteIndex::TAG_MIN_POSTS`)
  son `noindex, follow` y no van al sitemap: páginas casi vacías.
- Sitemap: categorías con publicaciones y etiquetas con 2 o más, en los 4
  idiomas, con sus alternativas.

## Al publicar una noticia

1. Título claro y **descripción** de una o dos frases: la descripción es el
   resumen de `llms.txt` y lo que citan los asistentes. «✨ Escribir con IA» →
   «Título» o «Resumen» las propone.
2. **SEO** (caja «Optimización SEO»): título de 60 caracteres como máximo y
   descripción de 155, que es lo que muestran Google y Bing sin cortar. Si se
   dejan vacíos se usan el título y la descripción de la noticia, que suelen
   ser más largos. «✨ Generar SEO con IA» los rellena a partir del contenido.
3. Imagen destacada (si no, se usa la imagen general de la web).
4. Categoría y etiquetas.
5. Traducciones con «✨ Traducir con IA», **después** de rellenar el SEO: el
   título y la descripción SEO solo se traducen si existen en castellano. Así
   las versiones quedan enlazadas con `hreflang`.

## IndexNow

Al guardar o traducir una noticia **publicada**, la web avisa a los buscadores
que usan IndexNow (Bing, Yandex, Seznam, Naver…) con su dirección en cada
idioma y la portada del blog. El índice de Bing es el que usan las búsquedas
de ChatGPT y Copilot. Google no usa IndexNow: para Google, el sitemap y
Search Console.

- Solo en producción (`APP_ENV=production`); se apaga con
  `INDEXNOW_ENABLED=false` en `.env`.
- La clave se genera sola la primera vez (ajuste `torns_indexnow_key`) y se
  publica en `https://www.torns.app/<clave>.txt`, como pide el protocolo.
- El aviso se envía después de responder, así que guardar no espera.
- Para enviar todas las direcciones del sitemap (por ejemplo, después del
  cambio de dominio): `php artisan torns:indexnow`. Una o varias:
  `php artisan torns:indexnow https://www.torns.app/blog/…`.
- Queda anotado en el log de Laravel (`Torns IndexNow`).

## Qué mantener

Cuando cambie una funcionalidad, un precio o un límite de la app:

- `content/site/pages/funcionalidades.<idioma>.html` y
  `content/site/markdown/funcionalidades.<idioma>.md` (los 4 idiomas, con
  la fecha de «Última actualización»);
- `content/site/schema/funcionalidades.<idioma>.json` si cambia una pregunta
  frecuente;
- `featureList` y `offers` de `platform/themes/torns/functions/seo.php`;
- la ficha `FACTS` de `app/Ai/BlogAi.php` y las preguntas de
  `torns_default_faq_items()`.

Todo esto viaja por OTA (`docs/OTA.md`), salvo `app/` (paquete de instalación).
