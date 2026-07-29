@php
    $title = $shortcode->title ?: 'Crea una cuenta en menos de 1 minuto';
    $subtitle = $shortcode->subtitle ?: 'Crea tu cuenta ahora gratis. Sin tarjeta de crédito.';
@endphp

<section class="py-16 lg:py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl bg-gradient-to-br from-torns-primary to-torns-primary-dark px-8 py-12 text-center text-white sm:px-12 lg:py-16">
            <h2 class="font-display text-3xl font-bold tracking-tight sm:text-4xl">{{ $title }}</h2>
            <p class="mx-auto mt-4 max-w-xl text-lg text-white">{{ $subtitle }}</p>
            <div class="mt-8">
                {!! Theme::partial('components.store-badges', ['center' => true]) !!}
            </div>
        </div>
    </div>
</section>
