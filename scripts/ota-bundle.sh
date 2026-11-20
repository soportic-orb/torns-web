#!/usr/bin/env bash
#
# Packs an OTA release (docs/OTA.md) from a checkout whose theme assets are
# already built (npm run build:torns):
#
#   dist/ota/ota-bundle.zip       the allowed roots, nothing else
#   dist/ota/ota-manifest.json    version, commit, sha256 of the bundle
#   dist/ota/ota-manifest.json.sig  Ed25519 signature (only with a key)
#
# Usage: bash scripts/ota-bundle.sh <version> [signing-key.pem]
set -euo pipefail

VERSION="${1:?usage: ota-bundle.sh <version> [signing-key.pem]}"
KEY="${2:-}"
ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
OUT="$ROOT/dist/ota"
# Must match config/torns-ota.php.
ROOTS=(platform/themes/torns public/themes/torns content/site)

cd "$ROOT"
rm -rf "$OUT"
mkdir -p "$OUT"

for dir in "${ROOTS[@]}"; do
    [[ -d "$dir" ]] || { echo "Missing $dir" >&2; exit 1; }
done

# No directory entries, no node_modules or OS junk.
zip -qrDX "$OUT/ota-bundle.zip" "${ROOTS[@]}" -x '*/node_modules/*' '*.DS_Store' '*/.git*'

SHA="$(sha256sum "$OUT/ota-bundle.zip" | cut -d' ' -f1)"
COMMIT="$(git rev-parse HEAD 2>/dev/null || echo unknown)"
FILES="$(unzip -Z1 "$OUT/ota-bundle.zip" | wc -l | tr -d ' ')"

cat > "$OUT/ota-manifest.json" <<JSON
{
    "format": 1,
    "version": "$VERSION",
    "commit": "$COMMIT",
    "created_at": "$(date -u +%Y-%m-%dT%H:%M:%SZ)",
    "sha256": "$SHA",
    "files": $FILES,
    "roots": ["platform/themes/torns", "public/themes/torns", "content/site"]
}
JSON

if [[ -n "$KEY" ]]; then
    openssl pkeyutl -sign -inkey "$KEY" -rawin -in "$OUT/ota-manifest.json" | base64 | tr -d '\n' > "$OUT/ota-manifest.json.sig"
fi

echo "OTA $VERSION: $FILES files, sha256 $SHA"
