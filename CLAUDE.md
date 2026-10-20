# torns-web — working notes for AI assistants

Marketing website of Torns (www.torns.app; staging nueva.torns.app) on
Botble CMS (Laravel). Theme `torns` in `platform/themes/torns`, site code in
`app/`, pages and menus as files in `content/site`. Code, commits and docs
for developers in English; site texts in Spanish (es) with ca, gl and eu;
conversation with Octavi in Spanish.

Before Botble-specific work, follow Botble's own guide for AI assistants:
https://docs.botble.com/cms/ai-assistant-guide.html

## Rules that are not negotiable

- Never push to `main`. Work on a branch and open a PR. Every merge to
  `main` that touches the theme or `content/site` publishes an OTA release
  (`.github/workflows/ota-release.yml`).
- Never invent features, prices or limits of the Torns app. The source of
  truth is the app repository (soportic-orb/Torns) and what Octavi
  confirms; the verified list is `content/site/pages/funcionalidades.*`.
  No comparative claims about other apps outside the verified comparison
  table (`docs/comparison-verification.md`).
- Every user-facing theme string goes through `__()` with its Catalan,
  Galician and Basque translation in `platform/themes/torns/lang/{ca,gl,eu}.json`
  (the Spanish text is the key). Admin strings use `trans('torns.*')` in
  `lang/{es,en}/torns.php`.
- A site that is not www.torns.app must never be indexed: keep `APP_ENV=staging`
  there. Go-live (`docs/PRODUCTION.md`) turns the nueva.torns.app install into
  www.torns.app and redirects nueva.torns.app to it; after that there is no staging.
- Do not touch Botble core (`platform/core`, `platform/packages`) or the
  bundled plugins; extend them with hooks, filters and the theme.

## How changes reach the sites

| Change | Ships with |
|---|---|
| Theme (`platform/themes/torns`, `public/themes/torns`) | OTA: merge → release → "Apply" in Admin → System → Site updates |
| Pages and menus (`content/site`) | OTA (edits made in /admin are kept unless "Use the repository version") |
| `app/`, `config/`, `routes/`, `lang/`, `public/vendor/torns`, migrations | `git pull` on the server (`docs/CLOUDPANEL.md`) |

Theme assets are compiled and committed: after editing `assets/` or Blade
classes run `npm run build:torns` and commit `platform/themes/torns/public`
and `public/themes/torns`.
Admin assets in `public/vendor/torns` go through `App\Support\VersionedAsset::path()`
(content hash in the URL): public files have a ten-year cache.

## Where things live

- Home and pages: shortcodes in `platform/themes/torns/functions/shortcodes.php`,
  views in `partials/shortcodes/`; page content in `content/site/pages/<key>.<lang>.html`,
  definitions (slug, template, SEO) in `content/site/pages.json`, menus in `content/site/menus.json`.
- SEO and AI readability: `functions/seo.php` (JSON-LD graph),
  `app/Support/SiteIndex.php` (sitemap, llms.txt, llms-full.txt, `.md` URLs),
  `app/Support/IndexNow.php` (pings Bing & co. when a post is saved) — `docs/seo-geo.md`.
- OTA: `app/Ota`, `app/Support/SiteContent.php` — `docs/OTA.md`.
- AI in the blog editor: `app/Ai/BlogAi.php` (its `FACTS` must match the app), translation of posts in `app/Ai/PostTranslator.php` — `docs/blog-ai.md`.
- Install: `docs/CLOUDPANEL.md` (SSH) or `docs/INSTALL.md` (shared hosting).
- Going live on www.torns.app (the nueva.torns.app install takes over the domain,
  WordPress is removed): `docs/PRODUCTION.md`; old WordPress URLs are
  redirected in `config/legacy-redirects.php`.

## Checks before a PR

```bash
php -l <changed php files>
php pint.phar --test <changed php files>      # pint.json follows Botble's style
npm run build:torns                           # if the theme changed
```

Render the change locally (SQLite + `php artisan db:seed --class=TornsContentSeeder`)
at 1440 px and 390 px when it is visible, and check the four languages.
