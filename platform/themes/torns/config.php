<?php

use Botble\Shortcode\View\View;
use Botble\Theme\Theme;

return [
    'inherit' => null,

    'events' => [
        'before' => function ($theme): void {
        },

        'beforeRenderTheme' => function (Theme $theme): void {
            // Cache-busting for browsers/CDNs: bump this on every release that
            // changes the compiled theme assets (css/js).
            $themeVersion = '1.1.1';
            $version = get_cms_version() . '-' . $themeVersion;

            $theme->asset()->usePath()->add('style', 'css/style.css', [], [], $version);
            $theme->asset()->container('footer')->usePath()->add('main', 'js/main.js', [], [], $version);

            if (function_exists('shortcode')) {
                $theme->composer(['page', 'post'], function (View $view): void {
                    $view->withShortcodes();
                });
            }
        },

        'beforeRenderLayout' => [
            'default' => function ($theme): void {
            },
        ],
    ],
];
