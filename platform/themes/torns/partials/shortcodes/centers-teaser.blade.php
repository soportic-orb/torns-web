@php
    $title = $shortcode->title ?: __('Torns para centros sanitarios y hospitales');
    $description = $shortcode->description ?: __('Si eres responsable de un centro sanitario, ofrece Torns a las personas que trabajan en él: facilitarás el intercambio de turnos, mejorarás la conciliación y los cambios se comunicarán automáticamente a quien gestione las planillas.');
    $url = $shortcode->url ?: url('centros');
    $image = $shortcode->image ? RvMedia::getImageUrl($shortcode->image) : Theme::asset()->url('images/home/centers-hero-1600.webp');
    $imageSrcset = $shortcode->image ? null : Theme::asset()->url('images/home/centers-hero-800.webp') . ' 800w, ' . Theme::asset()->url('images/home/centers-hero-1600.webp') . ' 1600w';
@endphp

<section class="bg-white py-16 lg:py-24" id="centros">
    <div class="mx-auto grid max-w-6xl items-center gap-10 px-4 sm:px-6 lg:grid-cols-2 lg:gap-16 lg:px-8">
        <div>
            <p class="mb-3 text-sm font-semibold uppercase tracking-widest text-torns-primary">{{ __('For centers') }}</p>
            <h2 class="font-display text-3xl font-bold tracking-tight sm:text-4xl">{{ $title }}</h2>
            <p class="mt-4 max-w-xl leading-relaxed text-torns-ink/70">{{ $description }}</p>
            <p class="mt-4 font-semibold text-torns-primary-dark">{{ __('Desde 0,39 € por usuario activo y mes (sin IVA), con 30 días de prueba.') }}</p>
            <a href="{{ $url }}" class="btn-primary mt-8">{{ __('Discover Torns for centers') }}</a>

            <div class="mt-10 rounded-3xl bg-[#EDE6FA] p-6">
                <p class="text-sm font-bold uppercase tracking-widest text-[#4A2399]">{{ __('Colegios profesionales, sindicatos y sociedades') }}</p>
                <h3 class="mt-2 font-display text-xl font-bold text-[#24124F]">{{ __('Regala Torns Pro a tus socios') }}</h3>
                <p class="mt-2 leading-relaxed text-[#3B2A60]">{{ __('Patrocina Torns Pro para tus socios, de forma total o parcial. Precio bajo presupuesto.') }}</p>
                <a href="{{ url('contacta') }}" class="mt-4 inline-flex font-semibold text-[#4A2399] underline underline-offset-4 hover:text-[#24124F]">{{ __('Pide un presupuesto') }}</a>
            </div>
        </div>
        <div>
            <img src="{{ $image }}" @if ($imageSrcset) srcset="{{ $imageSrcset }}" sizes="(min-width: 1024px) 34rem, 92vw" @endif alt="{{ $title }}" class="w-full" width="1600" height="892" loading="lazy">
        </div>
    </div>
</section>
