@php
    $title = $shortcode->title ?: 'Torns para centros sanitarios y hospitales';
    $description = $shortcode->description ?: 'Si eres responsable de un centro sanitario, ofrece Torns a las personas que trabajan en él: facilitarás el intercambio de turnos, mejorarás la conciliación y los cambios se comunicarán automáticamente a quien gestione las planillas.';
    $url = $shortcode->url ?: url('centros');
    $image = $shortcode->image ? RvMedia::getImageUrl($shortcode->image) : Theme::asset()->url('images/home/centers-teaser.png');
@endphp

<section class="bg-white py-16 lg:py-24" id="centros">
    <div class="mx-auto grid max-w-6xl items-center gap-10 px-4 sm:px-6 lg:grid-cols-2 lg:gap-16 lg:px-8">
        <div>
            <p class="mb-3 text-sm font-semibold uppercase tracking-widest text-torns-primary">{{ __('For centers') }}</p>
            <h2 class="font-display text-3xl font-bold tracking-tight sm:text-4xl">{{ $title }}</h2>
            <p class="mt-4 max-w-xl leading-relaxed text-torns-ink/70">{{ $description }}</p>
            <a href="{{ $url }}" class="btn-primary mt-8">{{ __('Discover Torns for centers') }}</a>
        </div>
        <div>
            <img src="{{ $image }}" alt="{{ $title }}" class="mx-auto w-full max-w-md" width="640" height="687" loading="lazy">
        </div>
    </div>
</section>
