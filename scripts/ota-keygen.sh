#!/usr/bin/env bash
#
# Creates the Ed25519 key pair that signs OTA releases (docs/OTA.md).
#
#   - The private key goes to the GitHub secret OTA_SIGNING_KEY of the
#     repository (Settings → Secrets and variables → Actions). Never commit it.
#   - The public key goes to OTA_PUBLIC_KEY in the .env of every site that
#     should accept the updates (staging and production).
#
# Usage (from the repository root): bash scripts/ota-keygen.sh [output-dir]
set -euo pipefail

OUT="${1:-.}"
KEY="$OUT/ota-signing-key.pem"

if [[ -e "$KEY" ]]; then
    echo "$KEY already exists; refusing to overwrite it." >&2
    exit 1
fi

# macOS ships LibreSSL, which has no Ed25519: fall back to PHP's sodium
# (the same library the site uses to verify), writing the same PKCS#8 PEM.
if [[ -z "${OTA_KEYGEN_FORCE_PHP:-}" ]] && openssl genpkey -algorithm ed25519 -out "$KEY" 2>/dev/null; then
    PUBLIC="$(openssl pkey -in "$KEY" -pubout -outform DER | tail -c 32 | base64 | tr -d '\n')"
elif command -v php >/dev/null && php -r 'exit(function_exists("sodium_crypto_sign_keypair") ? 0 : 1);'; then
    rm -f "$KEY"
    PUBLIC="$(php -r '
        $pair = sodium_crypto_sign_keypair();
        $seed = substr(sodium_crypto_sign_secretkey($pair), 0, 32);
        $der = hex2bin("302e020100300506032b657004220420") . $seed;
        file_put_contents($argv[1], "-----BEGIN PRIVATE KEY-----\n" . chunk_split(base64_encode($der), 64, "\n") . "-----END PRIVATE KEY-----\n");
        echo base64_encode(sodium_crypto_sign_publickey($pair));
    ' "$KEY")"
else
    echo "Needs OpenSSL 3 (brew install openssl@3) or PHP with the sodium extension." >&2
    exit 1
fi
chmod 600 "$KEY"

echo "Private key: $KEY"
echo "  → paste its whole content into the GitHub secret OTA_SIGNING_KEY, then delete the file."
echo
echo "Public key (add to .env on each site):"
echo "OTA_PUBLIC_KEY=$PUBLIC"
