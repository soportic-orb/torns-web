#!/usr/bin/env bash
#
# Creates the Ed25519 key pair that signs OTA releases (docs/OTA.md).
#
#   - The private key goes to the GitHub secret OTA_SIGNING_KEY of the
#     repository (Settings → Secrets and variables → Actions). Never commit it.
#   - The public key goes to OTA_PUBLIC_KEY in the .env of every site that
#     should accept the updates (staging and production).
#
# Usage: bash scripts/ota-keygen.sh [output-dir]
set -euo pipefail

OUT="${1:-.}"
KEY="$OUT/ota-signing-key.pem"

if [[ -e "$KEY" ]]; then
    echo "$KEY already exists; refusing to overwrite it." >&2
    exit 1
fi

openssl genpkey -algorithm ed25519 -out "$KEY"
chmod 600 "$KEY"
PUBLIC="$(openssl pkey -in "$KEY" -pubout -outform DER | tail -c 32 | base64 -w0)"

echo "Private key: $KEY"
echo "  → paste its whole content into the GitHub secret OTA_SIGNING_KEY, then delete the file."
echo
echo "Public key (add to .env on each site):"
echo "OTA_PUBLIC_KEY=$PUBLIC"
