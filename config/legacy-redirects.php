<?php

/*
| Permanent (301) redirects from URLs of the previous WordPress website
| (www.torns.app until October 2026) that have no page with the same path
| here. Paths without a leading slash; a trailing slash on the request
| matches too. Registered in routes/seo-early.php.
*/

return [
    // Blog post that was not moved: the closest page.
    'digitalizacion-cambios-turno-hospital' => '/centros',
    'ca/digitalitzacio-canvis-torn-hospital' => '/ca/centros',
    'gl/digitalizacion-cambios-turno-hospital' => '/gl/centros',
    'eu/digitalizacion-cambios-turno-hospital' => '/eu/centros',

    // Thank-you page of the old contact form.
    'gracias' => '/',
    'ca/gracias' => '/ca',
    'gl/grazas' => '/gl',
    'eu/eskerrik-asko' => '/eu',

    // Pages whose translated slug changed.
    'eu/kontaktua' => '/eu/contacta',
    'ca/centres' => '/ca/centros',
    'eu/zentroak' => '/eu/centros',

    // Comparison page: now a section of the home page.
    'torns-vs-aturnos' => '/#comparativa',
    'ca/torns-vs-aturnos' => '/ca#comparativa',
    'gl/torns-vs-aturnos' => '/gl#comparativa',
    'eu/torns-vs-aturnos' => '/eu#comparativa',

    // Rules of a past giveaway.
    'bases-sorteo-smartbox' => '/',
    'ca/bases-del-sorteig-smartbox' => '/ca',
    'gl/bases-sorteo-smartbox' => '/gl',
    'eu/smartbox-zozketaren-oinarriak-eta-baldintzak' => '/eu',

    // Blog category and feeds.
    'category/general' => '/blog',
    'ca/category/general' => '/ca/blog',
    'gl/categoria/xeral' => '/gl/blog',
    'eu/kategoria/orokorra' => '/eu/blog',
    'feed' => '/blog',
    'comments/feed' => '/blog',

    // Sitemaps of the old website (Yoast), still known to search engines.
    'sitemap_index.xml' => '/sitemap.xml',
    'post-sitemap.xml' => '/sitemap.xml',
    'page-sitemap.xml' => '/sitemap.xml',
    'category-sitemap.xml' => '/sitemap.xml',
];
