<?php

/*
| IndexNow (https://www.indexnow.org): tells Bing, Yandex, Seznam, Naver…
| right away when a page is published or changed. Bing's index also feeds
| ChatGPT search and Copilot. Only sent in production. docs/seo-geo.md.
*/

return [
    'enabled' => env('INDEXNOW_ENABLED', true),
    'endpoint' => env('INDEXNOW_ENDPOINT', 'https://api.indexnow.org/indexnow'),
];
