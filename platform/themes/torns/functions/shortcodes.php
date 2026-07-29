<?php

use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Shortcode\Forms\ShortcodeForm;
use Botble\Theme\Facades\Theme;
use Illuminate\Routing\Events\RouteMatched;

app('events')->listen(RouteMatched::class, function (): void {
    $textField = fn (string $label) => [
        'label' => $label,
        'attr' => ['placeholder' => $label],
    ];

    $textareaField = fn (string $label) => [
        'label' => $label,
        'attr' => ['placeholder' => $label, 'rows' => 3],
    ];

    // ------------------------------------------------------------------
    // 1. Hero
    // ------------------------------------------------------------------
    add_shortcode('torns-hero', __('Torns: Hero'), __('Main hero with dual value proposition, store badges and swap animation'), function ($shortcode) {
        return Theme::partial('shortcodes.hero', ['shortcode' => $shortcode]);
    });

    shortcode()->setAdminConfig('torns-hero', function (array $attributes) use ($textField, $textareaField) {
        return ShortcodeForm::createFromArray($attributes)
            ->add('title', TextField::class, $textField(__('Title (H1)')))
            ->add('subtitle', TextareaField::class, $textareaField(__('Subtitle')))
            ->add('image', MediaImageField::class, ['label' => __('Phone mockup image')]);
    });

    // ------------------------------------------------------------------
    // 2. Presentation video
    // ------------------------------------------------------------------
    add_shortcode('torns-video', __('Torns: Video'), __('Presentation video with poster and lazy load'), function ($shortcode) {
        return Theme::partial('shortcodes.video', ['shortcode' => $shortcode]);
    });

    shortcode()->setAdminConfig('torns-video', function (array $attributes) use ($textField) {
        return ShortcodeForm::createFromArray($attributes)
            ->add('title', TextField::class, $textField(__('Title')))
            ->add('video_url', TextField::class, $textField(__('Video URL (mp4)')))
            ->add('poster', MediaImageField::class, ['label' => __('Poster image')]);
    });

    // ------------------------------------------------------------------
    // 3. What is Torns
    // ------------------------------------------------------------------
    add_shortcode('torns-about', __('Torns: What is Torns'), __('Introduction to the two pillars: shift swaps + unified calendar'), function ($shortcode) {
        return Theme::partial('shortcodes.about', ['shortcode' => $shortcode]);
    });

    shortcode()->setAdminConfig('torns-about', function (array $attributes) use ($textField, $textareaField) {
        $form = ShortcodeForm::createFromArray($attributes)
            ->add('title', TextField::class, $textField(__('Title')))
            ->add('description', TextareaField::class, $textareaField(__('Description')));

        for ($i = 1; $i <= 4; $i++) {
            $form->add('bullet_' . $i, TextField::class, $textField(__('Bullet :number', ['number' => $i])));
        }

        return $form;
    });

    // ------------------------------------------------------------------
    // 4. Unified calendar spotlight
    // ------------------------------------------------------------------
    add_shortcode('torns-unified-calendar', __('Torns: Unified calendar'), __('Calendar spotlight with central mockup and benefits around'), function ($shortcode) {
        return Theme::partial('shortcodes.unified-calendar', ['shortcode' => $shortcode]);
    });

    shortcode()->setAdminConfig('torns-unified-calendar', function (array $attributes) use ($textField, $textareaField) {
        return ShortcodeForm::createFromArray($attributes)
            ->add('title', TextField::class, $textField(__('Title')))
            ->add('subtitle', TextareaField::class, $textareaField(__('Subtitle')))
            ->add('closing', TextField::class, $textField(__('Closing highlight')))
            ->add('image', MediaImageField::class, ['label' => __('Calendar mockup image')]);
    });

    // ------------------------------------------------------------------
    // 5. Features grid (10 cards)
    // ------------------------------------------------------------------
    add_shortcode('torns-features', __('Torns: Features grid'), __('Grid with the 10 main features'), function ($shortcode) {
        return Theme::partial('shortcodes.features', ['shortcode' => $shortcode]);
    });

    shortcode()->setAdminConfig('torns-features', function (array $attributes) use ($textField, $textareaField) {
        return ShortcodeForm::createFromArray($attributes)
            ->add('title', TextField::class, $textField(__('Title')))
            ->add('subtitle', TextareaField::class, $textareaField(__('Subtitle')));
    });

    // ------------------------------------------------------------------
    // 6. Alternating feature row (used once per row)
    // ------------------------------------------------------------------
    add_shortcode('torns-feature-row', __('Torns: Feature row'), __('Alternating image/text row (messaging, SOS, languages, Copilot)'), function ($shortcode) {
        return Theme::partial('shortcodes.feature-row', ['shortcode' => $shortcode]);
    });

    shortcode()->setAdminConfig('torns-feature-row', function (array $attributes) use ($textField, $textareaField) {
        return ShortcodeForm::createFromArray($attributes)
            ->add('title', TextField::class, $textField(__('Title')))
            ->add('content_1', TextareaField::class, $textareaField(__('First paragraph')))
            ->add('content_2', TextareaField::class, $textareaField(__('Second paragraph')))
            ->add('image', MediaImageField::class, ['label' => __('Image')])
            ->add(
                'side',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Image side'))
                    ->choices(['left' => __('Left'), 'right' => __('Right')])
                    ->defaultValue('left')
            );
    });

    // ------------------------------------------------------------------
    // 7. Screens gallery (13 screenshots)
    // ------------------------------------------------------------------
    add_shortcode('torns-gallery', __('Torns: Screens gallery'), __('App screenshots: horizontal scroll on mobile, grid + lightbox on desktop'), function ($shortcode) {
        return Theme::partial('shortcodes.gallery', ['shortcode' => $shortcode]);
    });

    shortcode()->setAdminConfig('torns-gallery', function (array $attributes) use ($textField, $textareaField) {
        return ShortcodeForm::createFromArray($attributes)
            ->add('title', TextField::class, $textField(__('Title')))
            ->add('subtitle', TextareaField::class, $textareaField(__('Subtitle')));
    });

    // ------------------------------------------------------------------
    // 8. Comparison table (reads rows from theme options)
    // ------------------------------------------------------------------
    add_shortcode('torns-comparison', __('Torns: Comparison table'), __('Objective comparison vs other shift apps. Content managed in Theme options'), function ($shortcode) {
        return Theme::partial('shortcodes.comparison', ['shortcode' => $shortcode]);
    });

    shortcode()->setAdminConfig('torns-comparison', function (array $attributes) use ($textField, $textareaField) {
        return ShortcodeForm::createFromArray($attributes)
            ->add('title', TextField::class, $textField(__('Title')))
            ->add('subtitle', TextareaField::class, $textareaField(__('Subtitle')));
    });

    // ------------------------------------------------------------------
    // 9. Center code CTA
    // ------------------------------------------------------------------
    add_shortcode('torns-center-code', __('Torns: Center code'), __('CTA for users with a workplace center code'), function ($shortcode) {
        return Theme::partial('shortcodes.center-code', ['shortcode' => $shortcode]);
    });

    shortcode()->setAdminConfig('torns-center-code', function (array $attributes) use ($textField, $textareaField) {
        return ShortcodeForm::createFromArray($attributes)
            ->add('title', TextField::class, $textField(__('Title')))
            ->add('description', TextareaField::class, $textareaField(__('Description')))
            ->add('image', MediaImageField::class, ['label' => __('Image')]);
    });

    // ------------------------------------------------------------------
    // 10. Pricing (reads plans from theme options)
    // ------------------------------------------------------------------
    add_shortcode('torns-pricing', __('Torns: Pricing'), __('Pricing cards. Plans and features managed in Theme options'), function ($shortcode) {
        return Theme::partial('shortcodes.pricing', ['shortcode' => $shortcode]);
    });

    shortcode()->setAdminConfig('torns-pricing', function (array $attributes) use ($textField, $textareaField) {
        return ShortcodeForm::createFromArray($attributes)
            ->add('title', TextField::class, $textField(__('Title')))
            ->add('subtitle', TextareaField::class, $textareaField(__('Subtitle')));
    });

    // ------------------------------------------------------------------
    // 11. Register CTA
    // ------------------------------------------------------------------
    add_shortcode('torns-register-cta', __('Torns: Register CTA'), __('Create account call to action with store badges'), function ($shortcode) {
        return Theme::partial('shortcodes.register-cta', ['shortcode' => $shortcode]);
    });

    shortcode()->setAdminConfig('torns-register-cta', function (array $attributes) use ($textField, $textareaField) {
        return ShortcodeForm::createFromArray($attributes)
            ->add('title', TextField::class, $textField(__('Title')))
            ->add('subtitle', TextareaField::class, $textareaField(__('Subtitle')));
    });

    // ------------------------------------------------------------------
    // 12. Centers teaser (B2B)
    // ------------------------------------------------------------------
    add_shortcode('torns-centers-teaser', __('Torns: Centers teaser'), __('B2B teaser linking to the centers landing page'), function ($shortcode) {
        return Theme::partial('shortcodes.centers-teaser', ['shortcode' => $shortcode]);
    });

    shortcode()->setAdminConfig('torns-centers-teaser', function (array $attributes) use ($textField, $textareaField) {
        return ShortcodeForm::createFromArray($attributes)
            ->add('title', TextField::class, $textField(__('Title')))
            ->add('description', TextareaField::class, $textareaField(__('Description')))
            ->add('url', TextField::class, $textField(__('Landing URL')))
            ->add('image', MediaImageField::class, ['label' => __('Image')]);
    });

    // ------------------------------------------------------------------
    // 13. FAQ
    // ------------------------------------------------------------------
    add_shortcode('torns-faq', __('Torns: FAQ'), __('Frequently asked questions accordion'), function ($shortcode) {
        return Theme::partial('shortcodes.faq', ['shortcode' => $shortcode]);
    });

    shortcode()->setAdminConfig('torns-faq', function (array $attributes) use ($textField) {
        $form = ShortcodeForm::createFromArray($attributes)
            ->add('title', TextField::class, $textField(__('Title')));

        for ($i = 1; $i <= 10; $i++) {
            $form
                ->add('question_' . $i, TextField::class, $textField(__('Question :number', ['number' => $i])))
                ->add('answer_' . $i, TextareaField::class, [
                    'label' => __('Answer :number', ['number' => $i]),
                    'attr' => ['rows' => 2],
                ]);
        }

        return $form;
    });

    // ------------------------------------------------------------------
    // 14. Center logos
    // ------------------------------------------------------------------
    add_shortcode('torns-center-logos', __('Torns: Center logos'), __('Row of logos of centers already using Torns'), function ($shortcode) {
        return Theme::partial('shortcodes.center-logos', ['shortcode' => $shortcode]);
    });

    shortcode()->setAdminConfig('torns-center-logos', function (array $attributes) use ($textField) {
        $form = ShortcodeForm::createFromArray($attributes)
            ->add('title', TextField::class, $textField(__('Title')));

        for ($i = 1; $i <= 6; $i++) {
            $form->add('logo_' . $i, MediaImageField::class, ['label' => __('Logo :number', ['number' => $i])]);
        }

        return $form;
    });

    // ------------------------------------------------------------------
    // Generic page hero (interior pages)
    // ------------------------------------------------------------------
    add_shortcode('torns-page-hero', __('Torns: Page hero'), __('Generic hero for interior pages'), function ($shortcode) {
        return Theme::partial('shortcodes.page-hero', ['shortcode' => $shortcode]);
    });

    shortcode()->setAdminConfig('torns-page-hero', function (array $attributes) use ($textField, $textareaField) {
        return ShortcodeForm::createFromArray($attributes)
            ->add('title', TextField::class, $textField(__('Title (H1)')))
            ->add('subtitle', TextareaField::class, $textareaField(__('Subtitle')))
            ->add(
                'align',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Alignment'))
                    ->choices(['center' => __('Centered'), 'left' => __('Left')])
                    ->defaultValue('center')
            );
    });

    // ------------------------------------------------------------------
    // Centers landing (B2B)
    // ------------------------------------------------------------------
    add_shortcode('torns-centers-landing', __('Torns: Centers landing'), __('Full B2B landing content for the centers page'), function ($shortcode) {
        return Theme::partial('shortcodes.centers-landing', ['shortcode' => $shortcode]);
    });

    shortcode()->setAdminConfig('torns-centers-landing', function (array $attributes) use ($textField) {
        return ShortcodeForm::createFromArray($attributes)
            ->add('problem_title', TextField::class, $textField(__('Problem section title')))
            ->add('benefits_title', TextField::class, $textField(__('Benefits section title')))
            ->add('panel_title', TextField::class, $textField(__('Panel section title')));
    });

    // ------------------------------------------------------------------
    // Contact section (info + form with honeypot)
    // ------------------------------------------------------------------
    if (is_plugin_active('contact')) {
        add_shortcode('torns-contact', __('Torns: Contact section'), __('Contact info and form'), function ($shortcode) {
            return Theme::partial('shortcodes.contact-section', ['shortcode' => $shortcode]);
        });

        shortcode()->setAdminConfig('torns-contact', function (array $attributes) use ($textField, $textareaField) {
            return ShortcodeForm::createFromArray($attributes)
                ->add('title', TextField::class, $textField(__('Title')))
                ->add('subtitle', TextareaField::class, $textareaField(__('Subtitle')));
        });
    }

    // ------------------------------------------------------------------
    // 15. Latest blog posts
    // ------------------------------------------------------------------
    add_shortcode('torns-blog-posts', __('Torns: Blog posts'), __('Latest blog posts with an elegant empty state'), function ($shortcode) {
        $posts = collect();

        if (is_plugin_active('blog')) {
            $posts = get_latest_posts((int) ($shortcode->limit ?: 3), [], ['slugable', 'categories']);
        }

        return Theme::partial('shortcodes.blog-posts', ['shortcode' => $shortcode, 'posts' => $posts]);
    });

    shortcode()->setAdminConfig('torns-blog-posts', function (array $attributes) use ($textField) {
        return ShortcodeForm::createFromArray($attributes)
            ->add('title', TextField::class, $textField(__('Title')))
            ->add('limit', NumberField::class, [
                'label' => __('Number of posts'),
                'default_value' => 3,
            ]);
    });
});
