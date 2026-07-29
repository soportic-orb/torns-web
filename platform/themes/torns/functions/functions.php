<?php

use Botble\Menu\Facades\Menu;
use Botble\Theme\Supports\ThemeSupport;

app()->booted(function (): void {
    ThemeSupport::registerSiteLogoHeight();
    ThemeSupport::registerSiteCopyright();

    register_page_template([
        'default' => __('Default'),
        'full-width' => __('Full width'),
    ]);

    Menu::addMenuLocation('main-menu', __('Main Navigation'));
    Menu::addMenuLocation('footer-menu', __('Footer Navigation'));
    Menu::addMenuLocation('footer-legal-menu', __('Footer Legal Navigation'));
});
