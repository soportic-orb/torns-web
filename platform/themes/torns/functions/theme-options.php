<?php

use Botble\Theme\Events\RenderingThemeOptionSettings;
use Botble\Theme\ThemeOption\Fields\RepeaterField;
use Botble\Theme\ThemeOption\Fields\TextField;
use Botble\Theme\ThemeOption\Fields\TextareaField;
use Botble\Theme\ThemeOption\Fields\ToggleField;
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
                    TextField::make()
                        ->name('ga4_measurement_id')
                        ->label(__('GA4 Measurement ID'))
                        ->helperText(__('E.g.: G-XXXXXXXXXX. The tracking snippet is only rendered in production when this is filled in.')),
                ])
        );

    // ------------------------------------------------------------------
    // Pricing plans (used by the [torns-pricing] shortcode)
    // ------------------------------------------------------------------
    $pricingFields = [];
    $plans = [
        'free' => __('Free plan'),
        'monthly' => __('Pro Monthly plan'),
        'annual' => __('Pro Annual plan'),
    ];

    foreach ($plans as $key => $label) {
        $pricingFields[] = TextField::make()
            ->name("pricing_{$key}_name")
            ->label($label . ' — ' . __('Name'));
        $pricingFields[] = TextField::make()
            ->name("pricing_{$key}_price")
            ->label($label . ' — ' . __('Price'))
            ->helperText(__('E.g.: 2,99 €'));
        $pricingFields[] = TextField::make()
            ->name("pricing_{$key}_period")
            ->label($label . ' — ' . __('Period'))
            ->helperText(__('E.g.: /month, /year. Leave empty for none.'));
        $pricingFields[] = TextareaField::make()
            ->name("pricing_{$key}_description")
            ->label($label . ' — ' . __('Description'));
        $pricingFields[] = TextareaField::make()
            ->name("pricing_{$key}_features")
            ->label($label . ' — ' . __('Features (one per line)'))
            ->rows(6);
        $pricingFields[] = TextField::make()
            ->name("pricing_{$key}_badge")
            ->label($label . ' — ' . __('Badge'))
            ->helperText(__('E.g.: You save 2 months'));
        $pricingFields[] = TextField::make()
            ->name("pricing_{$key}_cta_label")
            ->label($label . ' — ' . __('Button label'));
        $pricingFields[] = ToggleField::make()
            ->name("pricing_{$key}_highlighted")
            ->label($label . ' — ' . __('Highlighted'));
    }

    theme_option()->setSection(
        ThemeOptionSection::make('opt-text-subsection-torns-pricing')
            ->title(__('Torns: Pricing'))
            ->icon('ti ti-coin-euro')
            ->fields($pricingFields)
    );

    // ------------------------------------------------------------------
    // Comparison table (used by the [torns-comparison] shortcode)
    // ------------------------------------------------------------------
    theme_option()->setSection(
        ThemeOptionSection::make('opt-text-subsection-torns-comparison')
            ->title(__('Torns: Comparison'))
            ->icon('ti ti-table')
            ->fields([
                TextField::make()
                    ->name('comparison_reviewed_at')
                    ->label(__('Last review date'))
                    ->defaultValue('29/07/2026')
                    ->helperText(__('Shown under the table. Update it whenever you verify the data again.')),
                RepeaterField::make()
                    ->name('comparison_rows')
                    ->label(__('Comparison rows'))
                    ->helperText(__('Cell values: "yes", "no", "-" (no public data) or free text (e.g. "Pro"). Sources for each cell are documented in docs/comparison-verification.md. If empty, the theme defaults are used.'))
                    ->fields([
                        TextField::make()->name('feature')->label(__('Feature')),
                        TextField::make()->name('torns')->label('Torns'),
                        TextField::make()->name('aturnos')->label('aTurnos'),
                        TextField::make()->name('shiftool')->label('Shiftool'),
                        TextField::make()->name('supershift')->label('Supershift'),
                        TextField::make()->name('turnoclip')->label('TurnoClip'),
                    ]),
            ])
    );
});
