<?php

/*
|--------------------------------------------------------------------------
| OTA updates of the Torns theme and site content (docs/OTA.md)
|--------------------------------------------------------------------------
*/

return [
    // GitHub repository that publishes the "ota-*" releases.
    'repository' => env('OTA_REPOSITORY', 'soportic-orb/torns-web'),

    // Only needed if the repository is private (fine-grained token, read-only
    // "Contents" permission). Public repositories work without it.
    'github_token' => env('OTA_GITHUB_TOKEN'),

    // Ed25519 public key (base64, 32 bytes) printed by scripts/ota-keygen.sh.
    // Without it no update can be applied.
    'public_key' => env('OTA_PUBLIC_KEY'),

    // The only paths an update may replace. Everything else (core, plugins,
    // vendor, .env, storage, uploads, blog posts) is never touched.
    'roots' => [
        'platform/themes/torns',
        'public/themes/torns',
        'content/site',
    ],

    // Backups kept in storage/app/ota/backups (one per applied update).
    'keep_backups' => 3,
];
