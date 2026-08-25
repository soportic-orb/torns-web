@php
    $title = $shortcode->title ?: '';
    $content1 = $shortcode->content_1 ?: '';
    $content2 = $shortcode->content_2 ?: '';
    $side = $shortcode->side === 'right' ? 'right' : 'left';

    // Known app-screen assets render as live CSS mockups; an admin-uploaded
    // image or an unknown asset falls back to a plain <img>.
    $mockups = [
        'row-share-messaging' => ['screen' => 'share', 'blob' => 'bg-shift-evening/10'],
        'row-sos' => ['screen' => 'sos', 'blob' => 'bg-shift-evening/15'],
        'row-languages' => ['screen' => 'languages', 'blob' => 'bg-torns-primary/10'],
        'row-copilot' => ['screen' => 'copilot', 'blob' => 'bg-shift-night/10'],
    ];

    $assetKey = $shortcode->image_asset ? preg_replace('/\.(png|jpe?g|webp)$/', '', $shortcode->image_asset) : null;
    $mockup = (! $shortcode->image && $assetKey && isset($mockups[$assetKey])) ? $mockups[$assetKey] : null;

    $image = null;
    $imageSrcset = null;
    if ($shortcode->image) {
        $image = RvMedia::getImageUrl($shortcode->image);
    } elseif (! $mockup && $assetKey) {
        $assetBase = 'images/home/' . $assetKey;
        $image = Theme::asset()->url($assetBase . '-896.webp');
        $imageSrcset = Theme::asset()->url($assetBase . '-480.webp') . ' 480w, ' . Theme::asset()->url($assetBase . '-896.webp') . ' 896w';
    }
@endphp

<section class="py-12 lg:py-16">
    <div class="mx-auto grid max-w-6xl items-center gap-10 px-4 sm:px-6 lg:grid-cols-2 lg:gap-16 lg:px-8">
        <div @class(['lg:order-2' => $side === 'right'])>
            @if ($mockup)
                <div class="relative mx-auto w-64 sm:w-72">
                    <div class="absolute -inset-10 rounded-full {{ $mockup['blob'] }} blur-2xl" aria-hidden="true"></div>
                    <div class="relative">
                        {!! Theme::partial('components.phone-mockup', ['screen' => $mockup['screen'], 'label' => $title]) !!}
                    </div>
                </div>
            @elseif ($image)
                <img src="{{ $image }}" @if ($imageSrcset) srcset="{{ $imageSrcset }}" sizes="(min-width: 1024px) 28rem, 90vw" @endif alt="{{ $title }}" class="mx-auto w-full max-w-md" width="896" height="896" loading="lazy">
            @endif
        </div>
        <div @class(['lg:order-1' => $side === 'right'])>
            <h2 class="font-display text-2xl font-bold tracking-tight sm:text-3xl">{{ $title }}</h2>
            @if ($content1)
                <p class="mt-4 leading-relaxed text-torns-ink/70">{{ $content1 }}</p>
            @endif
            @if ($content2)
                <p class="mt-3 leading-relaxed text-torns-ink/70">{{ $content2 }}</p>
            @endif
        </div>
    </div>
</section>
