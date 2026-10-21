@php
    $title = $shortcode->title ?: '';
    $content1 = $shortcode->content_1 ?: '';
    $content2 = $shortcode->content_2 ?: '';
    $side = $shortcode->side === 'right' ? 'right' : 'left';
    $imageSrcset = null;
    [$imageWidth, $imageHeight] = [896, 896];
    if ($shortcode->image) {
        $image = RvMedia::getImageUrl($shortcode->image);
    } elseif ($shortcode->image_asset) {
        $assetBase = 'images/home/' . preg_replace('/\.(png|jpe?g|webp)$/', '', $shortcode->image_asset);
        $image = torns_asset($assetBase . '-896.webp');
        $imageSrcset = torns_asset($assetBase . '-480.webp') . ' 480w, ' . $image . ' 896w';
        $size = @getimagesize(public_path('themes/torns/' . $assetBase . '-896.webp'));
        if ($size) {
            [$imageWidth, $imageHeight] = $size;
        }
    } else {
        $image = null;
    }
    // Portrait images (the watch) are capped by height so the row stays short.
    $portrait = $imageHeight > $imageWidth;
@endphp

<section class="py-12 lg:py-16">
    <div class="mx-auto grid max-w-6xl items-center gap-10 px-4 sm:px-6 lg:grid-cols-2 lg:gap-16 lg:px-8">
        <div @class(['lg:order-2' => $side === 'right'])>
            @if ($image)
                <img src="{{ $image }}" @if ($imageSrcset) srcset="{{ $imageSrcset }}" sizes="{{ $portrait ? '13rem' : '(min-width: 1024px) 28rem, 90vw' }}" @endif alt="{{ $title }}" @class(['mx-auto', 'h-auto max-h-64 w-auto lg:max-h-80' => $portrait, 'w-full max-w-md' => ! $portrait]) width="{{ $imageWidth }}" height="{{ $imageHeight }}" loading="lazy">
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
