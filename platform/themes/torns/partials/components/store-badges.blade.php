@php
    // Official App Store / Google Play badges linking to the store listings.
    // Params: $size = sm|md (default md), $center (bool). Both images are cropped
    // to the badge itself, so the same height gives the same visual size.
    $size = $size ?? 'md';
    $height = $size === 'sm' ? 'h-10' : 'h-12';
    $appStoreUrl = theme_option('app_store_url', 'https://apps.apple.com/es/app/torns/id6760354618');
    $playStoreUrl = theme_option('play_store_url', 'https://play.google.com/store/apps/details?id=com.tornsapp.android');
@endphp
<div class="flex flex-wrap items-center gap-3 {{ ! empty($center) ? 'justify-center' : '' }}">
    <a href="{{ $appStoreUrl }}" rel="noopener" class="inline-flex shrink-0 rounded-lg transition hover:opacity-80">
        <img
            src="{{ torns_asset('images/badge-appstore-es.svg') }}"
            alt="{{ __('Download on the App Store') }}"
            class="{{ $height }} w-auto"
            width="120"
            height="40"
            loading="lazy"
        >
    </a>
    <a href="{{ $playStoreUrl }}" rel="noopener" class="inline-flex shrink-0 rounded-lg transition hover:opacity-80">
        <img
            src="{{ torns_asset('images/badge-playstore-es.png') }}"
            alt="{{ __('Get it on Google Play') }}"
            class="{{ $height }} w-auto"
            width="135"
            height="40"
            loading="lazy"
        >
    </a>
</div>
