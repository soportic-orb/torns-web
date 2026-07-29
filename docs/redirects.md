# 301 redirects — WordPress → Botble migration

Last review: 29/07/2026. To be applied at cut-over (see `docs/MIGRATION.md`).

## Routes that keep their URL (no redirect needed)

The information architecture was designed to preserve every indexed URL:

| Current WordPress URL | New URL | Status |
|---|---|---|
| `/` | `/` | unchanged |
| `/centros/` | `/centros` | unchanged (trailing slash normalized, see below) |
| `/contacta/` | `/contacta` | unchanged |
| `/terminos/` | `/terminos` | unchanged |
| `/privacidad/` | `/privacidad` | unchanged |
| `/ca/`, `/gl/`, `/eu/` + the pages above | same | unchanged |
| `/blog` | `/blog` | new section (no previous URL) |

## Normalizations handled globally

- **Trailing slashes**: the root `.htaccess` issues a single `301` from `/pagina/`
  to `/pagina` for any non-directory URL. WordPress used trailing slashes, so
  every old deep link resolves with exactly one redirect hop.
- **HTTPS + host**: force `https://www.torns.app` (non-www → www, http → https)
  at the hosting panel or with the rules below.

## WordPress-specific leftovers to redirect

Add these rules to the **root `.htaccess`** at cut-over (before the generic
rewrite to `public/`):

```apache
# --- WordPress leftovers (301) ---
RewriteRule ^feed/?$ /blog [R=301,L]
RewriteRule ^comments/feed/?$ /blog [R=301,L]
RewriteRule ^(ca|gl|eu)/feed/?$ /$1 [R=301,L]
RewriteRule ^wp-login\.php$ / [R=301,L]
RewriteRule ^wp-admin/?.*$ / [R=301,L]
RewriteRule ^author/.*$ / [R=301,L]
RewriteRule ^wp-json(/.*)?$ / [R=301,L]
```

Media files under `/wp-content/uploads/...` were re-hosted inside the theme
(`/themes/torns/images/...`). Old image URLs will 404 after cut-over; they are
not indexed as pages, so no redirects are added. If Search Console reports
valuable image URLs later, add specific rules for them.

## Verification checklist (post cut-over)

1. `curl -I https://www.torns.app/centros/` → `301` → `/centros` → `200`.
2. `curl -I http://torns.app/` → `301` → `https://www.torns.app/`.
3. All four language homepages return `200` and cross-link via `hreflang`.
4. `https://www.torns.app/sitemap.xml` lists the four per-language sitemaps.
5. Re-submit the sitemap in Search Console.
