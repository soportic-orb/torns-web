@php
    $title = $shortcode->title ?: __('Crea una cuenta en menos de 1 minuto');
    $subtitle = $shortcode->subtitle ?: __('Crea tu cuenta ahora gratis. Sin tarjeta de crédito.');
@endphp

<section class="py-16 lg:py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-torns-primary to-torns-primary-dark px-8 py-12 text-center text-white sm:px-12 lg:py-16">
            <div class="pointer-events-none absolute inset-0" aria-hidden="true">
                <div class="absolute -left-16 -top-16 h-56 w-56 rounded-full bg-white/10 blur-2xl"></div>
                <div class="absolute -bottom-20 -right-12 h-64 w-64 rounded-full bg-shift-morning/20 blur-3xl"></div>
                <div class="absolute right-16 top-8 h-16 w-16 rounded-2xl border border-white/15"></div>
                <div class="absolute bottom-10 left-14 h-10 w-10 rounded-full border border-white/15"></div>
            </div>
            <div class="relative">
                <h2 class="font-display text-3xl font-bold tracking-tight sm:text-4xl">{{ $title }}</h2>
                <p class="mx-auto mt-4 max-w-xl text-lg text-white">{{ $subtitle }}</p>
                <div class="mt-8">
                    {!! Theme::partial('components.store-badges', ['center' => true]) !!}
                </div>
            </div>
        </div>
    </div>
</section>
