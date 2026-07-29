# Torns — Marketing Website

Marketing website for [Torns](https://www.torns.app), the shift-swapping app for healthcare professionals in Spain. Built on **Botble CMS** (Laravel) with a fully custom theme (`torns`), a blog manageable from the admin panel, and 4 languages: Spanish (default, no URL prefix), Catalan (`/ca/`), Galician (`/gl/`) and Basque (`/eu/`).

## Requirements

- PHP 8.3 or 8.4 with extensions: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO MySQL, Tokenizer, XML, cURL, Zip, GD, exif
- MySQL 5.7+ / MariaDB 10.3+
- Composer 2 (local development / build only)
- Node.js 20+ (local asset compilation only — **not required on the server**)

## Local development

```bash
cp .env.example .env
# edit .env with your local DB credentials, then:
php artisan key:generate
php artisan migrate
php artisan cms:publish:assets
php artisan cms:user:create   # create the admin user
php artisan serve             # http://localhost:8000 — admin panel at /admin
```

Active plugins: Blog, Language, Language Advanced, Contact Form, Translation. All other bundled plugins are kept inactive.

## Deployment on shared hosting

No Composer or Node is needed on the server: `vendor/` is uploaded via FTP/SFTP and compiled theme assets are versioned in the repository. Two options to serve the app depending on what the hosting panel allows:

### Option A — document root pointing to `public/` (preferred)

Set the domain's document root to the `public/` directory in the hosting panel. This is the standard and most secure Laravel setup: only `public/` is web-accessible.

### Option B — `.htaccess` fallback in the project root

If the hosting does not allow changing the document root, upload the project to the web root as-is. The `.htaccess` file at the project root rewrites every request to `public/`, so URLs work exactly the same. Slightly less strict than option A (project files live under the web root), but Botble ships protections for sensitive paths and `.env` is never web-served thanks to the rewrite rules.

Full guides:

- [`docs/INSTALL.md`](docs/INSTALL.md) — step-by-step shared hosting installation (including the one-shot browser seeder for hosts without SSH).
- [`docs/MIGRATION.md`](docs/MIGRATION.md) — WordPress cut-over plan (staging on `nueva.torns.app`, go-live, verification).
- [`docs/redirects.md`](docs/redirects.md) — URL preservation and 301 rules.
- [`docs/comparison-verification.md`](docs/comparison-verification.md) — sources for every cell of the comparison table.

To build the upload package: `bash scripts/build-release.sh` → `dist/torns-web-install.zip`.

## Languages

| Language | Locale | URL prefix |
|----------|--------|------------|
| Español (default) | `es` | — (no prefix) |
| Català | `ca` | `/ca/` |
| Galego | `gl` | `/gl/` |
| Euskara | `eu` | `/eu/` |
