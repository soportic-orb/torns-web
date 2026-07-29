@php
    $title = $shortcode->title ?: '';
    $content1 = $shortcode->content_1 ?: '';
    $content2 = $shortcode->content_2 ?: '';
    $side = $shortcode->side === 'right' ? 'right' : 'left';
    $image = $shortcode->image ? RvMedia::getImageUrl($shortcode->image) : ($shortcode->image_asset ? Theme::asset()->url('images/home/' . $shortcode->image_asset) : null);
@endphp

<section class="py-12 lg:py-16">
    <div class="mx-auto grid max-w-6xl items-center gap-10 px-4 sm:px-6 lg:grid-cols-2 lg:gap-16 lg:px-8">
        <div @class(['lg:order-2' => $side === 'right'])>
            @if ($image)
                <img src="{{ $image }}" alt="{{ $title }}" class="mx-auto w-full max-w-md" width="640" height="640" loading="lazy">
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
