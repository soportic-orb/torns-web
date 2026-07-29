<?php

use Botble\Menu\Facades\Menu;
use Botble\Theme\Facades\Theme;
use Botble\Theme\Supports\ThemeSupport;
use Botble\Theme\Typography\TypographyItem;

app()->booted(function (): void {
    ThemeSupport::registerSiteLogoHeight();
    ThemeSupport::registerSiteCopyright();

    // Local fonts shipped as woff2 inside the theme — mark them as non-Google
    // so the typography system never injects remote font-face CSS.
    Theme::typography()->registerFontFamilies([
        new TypographyItem('primary', __('Primary'), 'Instrument Sans', [400, 500, 600, 700], false),
        new TypographyItem('display', __('Display'), 'Bricolage Grotesque', [400, 600, 700, 800], false),
    ]);

    register_page_template([
        'default' => __('Default'),
        'full-width' => __('Full width'),
    ]);

    Menu::addMenuLocation('main-menu', __('Main Navigation'));
    Menu::addMenuLocation('footer-menu', __('Footer Navigation'));
    Menu::addMenuLocation('footer-legal-menu', __('Footer Legal Navigation'));
});
