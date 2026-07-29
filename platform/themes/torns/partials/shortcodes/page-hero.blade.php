@php
    $title = $shortcode->title ?: '';
    $subtitle = $shortcode->subtitle ?: '';
    $centered = $shortcode->align !== 'left';
@endphp

<section class="bg-gradient-to-b from-white to-torns-bg">
    <div @class(['mx-auto max-w-4xl px-4 pb-12 pt-14 sm:px-6 lg:pb-16 lg:pt-20', 'text-center' => $centered])>
        <h1 class="font-display text-3xl font-bold tracking-tight sm:text-4xl lg:text-5xl">{{ $title }}</h1>
        @if ($subtitle)
            <p @class(['mt-5 text-lg leading-relaxed text-torns-ink/70', 'mx-auto max-w-2xl' => $centered, 'max-w-2xl' => ! $centered])>{{ $subtitle }}</p>
        @endif
    </div>
</section>
