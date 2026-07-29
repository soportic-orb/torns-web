<?php

use Botble\Theme\Events\RenderingThemeOptionSettings;
use Botble\Theme\ThemeOption\Fields\TextField;
use Botble\Theme\ThemeOption\ThemeOptionSection;

app('events')->listen(RenderingThemeOptionSettings::class, function (): void {
    theme_option()
        ->setSection(
            ThemeOptionSection::make('opt-text-subsection-torns')
                ->title(__('Torns'))
                ->icon('ti ti-calendar-repeat')
                ->fields([
                    TextField::make()
                        ->name('app_store_url')
                        ->label(__('App Store URL'))
                        ->defaultValue('https://apps.apple.com/es/app/torns/id6760354618'),
                    TextField::make()
                        ->name('play_store_url')
                        ->label(__('Play Store URL'))
                        ->defaultValue('https://play.google.com/store/apps/details?id=com.tornsapp.android'),
                    TextField::make()
                        ->name('contact_email')
                        ->label(__('Contact email'))
                        ->defaultValue('hola@torns.app'),
                    TextField::make()
                        ->name('support_url')
                        ->label(__('Support site URL'))
                        ->defaultValue('https://soporte.torns.app'),
                ])
        );
});
