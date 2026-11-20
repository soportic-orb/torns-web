@php
    $title = $shortcode->title ?: '';
    $subtitle = $shortcode->subtitle ?: '';
    $centered = $shortcode->align !== 'left';
    $image = $shortcode->image_asset ? 'images/home/' . preg_replace('/[^a-z0-9-]/', '', $shortcode->image_asset) : null;
@endphp

<section class="bg-gradient-to-b from-white to-torns-bg">
    @if ($image)
        <div class="mx-auto grid max-w-6xl items-center gap-10 px-4 pb-12 pt-12 sm:px-6 lg:grid-cols-2 lg:gap-12 lg:px-8 lg:pb-16 lg:pt-16">
            <div>
                <h1 class="font-display text-3xl font-bold leading-tight tracking-tight [text-wrap:balance] sm:text-4xl lg:text-[2.6rem]">{{ $title }}</h1>
                @if ($subtitle)
                    <p class="mt-5 max-w-xl text-lg leading-relaxed text-torns-ink/70">{{ $subtitle }}</p>
                @endif
            </div>
            <img
                src="{{ Theme::asset()->url($image . '-1600.webp') }}"
                srcset="{{ Theme::asset()->url($image . '-800.webp') }} 800w, {{ Theme::asset()->url($image . '-1600.webp') }} 1600w"
                sizes="(min-width: 1024px) 40rem, 92vw"
                alt="{{ $title }}"
                class="w-full drop-shadow-2xl"
                width="1600"
                height="892"
                fetchpriority="high"
            >
        </div>
    @else
        <div @class(['mx-auto max-w-4xl px-4 pb-12 pt-14 sm:px-6 lg:pb-16 lg:pt-20', 'text-center' => $centered])>
            <h1 class="font-display text-3xl font-bold tracking-tight sm:text-4xl lg:text-5xl">{{ $title }}</h1>
            @if ($subtitle)
                <p @class(['mt-5 text-lg leading-relaxed text-torns-ink/70', 'mx-auto max-w-2xl' => $centered, 'max-w-2xl' => ! $centered])>{{ $subtitle }}</p>
            @endif
        </div>
    @endif
</section>
