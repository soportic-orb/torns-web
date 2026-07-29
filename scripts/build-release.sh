#!/usr/bin/env bash
#
# Builds dist/torns-web-install.zip: a ready-to-upload package for shared
# hosting (includes vendor/ and compiled theme assets; excludes .git,
# node_modules, .env and local runtime files).
#
# Usage (from the repository root):
#   bash scripts/build-release.sh [--skip-build]
#
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
DIST_DIR="$ROOT/dist"
ZIP_PATH="$DIST_DIR/torns-web-install.zip"
SKIP_BUILD="${1:-}"

cd "$ROOT"

if [[ "$SKIP_BUILD" != "--skip-build" ]]; then
    echo "==> composer install (production)"
    composer install --no-dev --optimize-autoloader --no-interaction

    echo "==> building torns theme assets"
    if [[ ! -d node_modules ]]; then
        npm ci --no-audit --no-fund
    fi
    npm run build:torns
fi

if [[ ! -d vendor ]]; then
    echo "ERROR: vendor/ is missing. Run composer install first." >&2
    exit 1
fi

if [[ ! -f public/themes/torns/css/style.css ]]; then
    echo "ERROR: compiled theme assets are missing. Run npm run build:torns first." >&2
    exit 1
fi

echo "==> packaging"
mkdir -p "$DIST_DIR"
rm -f "$ZIP_PATH"

zip -r -q "$ZIP_PATH" . \
    -x ".git/*" \
    -x ".github/*" \
    -x "node_modules/*" \
    -x "dist/*" \
    -x ".env" \
    -x ".env.local" \
    -x ".env.*.local" \
    -x "storage/logs/*" \
    -x "storage/framework/cache/data/*" \
    -x "storage/framework/sessions/*" \
    -x "storage/framework/views/*" \
    -x "storage/app/public/*" \
    -x "storage/installed-content-seed.lock" \
    -x "storage/installed" \
    -x "storage/installing" \
    -x "bootstrap/cache/*.php" \
    -x "tests/*" \
    -x ".phpunit.result.cache" \
    -x "*.DS_Store"

echo "==> done: $ZIP_PATH ($(du -h "$ZIP_PATH" | cut -f1))"
