<?php

use Botble\Theme\Facades\Theme;

if (! is_plugin_active('contact')) {
    return;
}

/**
 * Honeypot antispam: an off-screen text input real users never fill in.
 * Bots that stuff every field fail the max:0 validation rule below.
 */
add_filter('pre_contact_form', function (?string $html): string {
    return ($html ?? '') . '
        <div class="torns-hp" aria-hidden="true">
            <label for="contact_website">' . __('Leave this field empty') . '</label>
            <input type="text" id="contact_website" name="website" tabindex="-1" autocomplete="off" value="">
        </div>';
}, 120);

add_filter('contact_request_rules', function (array $rules): array {
    $rules['website'] = ['nullable', 'max:0'];

    return $rules;
}, 120);

/**
 * The contact plugin queues jQuery + an AJAX helper. The theme is jQuery-free:
 * without them the form degrades gracefully to a standard POST + redirect.
 */
app('events')->listen(\Botble\Theme\Events\ThemeRoutingBeforeEachRouteEvent::class, function (): void {
    Theme::asset()->remove('contact-css');
    Theme::asset()->container('footer')->remove('contact-public-js');
});
