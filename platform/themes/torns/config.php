<?php

use Botble\Shortcode\View\View;
use Botble\Theme\Theme;

return [
    'inherit' => null,

    'events' => [
        'before' => function ($theme): void {
        },

        'beforeRenderTheme' => function (Theme $theme): void {
            // Version by content: assets are served with a ten-year cache, and
            // an OTA update changes the files without changing the CMS version.
            $version = function (string $path): string {
                $file = public_path('themes/torns/' . $path);

                return is_file($file) ? substr(md5_file($file), 0, 12) : get_cms_version();
            };

            $theme->asset()->usePath()->add('style', 'css/style.css', [], [], $version('css/style.css'));
            $theme->asset()->container('footer')->usePath()->add('main', 'js/main.js', [], [], $version('js/main.js'));

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
