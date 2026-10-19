@php
    $title = $shortcode->title ?: __('Tus turnos y guardias de todos tus centros, en un solo calendario');
    $subtitle = $shortcode->subtitle ?: __('Torns es tu calendario laboral: el hospital, el centro de salud y la ambulancia, juntos. Y cuando necesitas cambiar un turno, tus compañeros lo ven y te proponen uno suyo.');

    // One tag per work centre around the phone: the calendar is multi-centre.
    $tags = [
        ['color' => 'bg-[#1B2A4A]', 'shift' => __('Noche'), 'center' => __('Hospital'), 'time' => '22:00 – 08:00', 'position' => '-left-6 top-24 sm:-left-24'],
        ['color' => 'bg-[#C2185B]', 'shift' => __('Guardia 24 h'), 'center' => __('SEM'), 'time' => __('Sábado'), 'position' => '-right-6 top-1/2 sm:-right-24'],
        ['color' => 'bg-shift-morning', 'shift' => __('Mañana'), 'center' => __('Centro de salud'), 'time' => '08:00 – 15:00', 'position' => '-left-4 bottom-16 sm:-left-20'],
    ];
@endphp

<section class="overflow-hidden bg-gradient-to-b from-white to-torns-bg">
    <div class="mx-auto grid max-w-6xl items-center gap-14 px-4 pb-16 pt-12 sm:px-6 lg:grid-cols-[7fr_5fr] lg:gap-10 lg:px-8 lg:pb-24 lg:pt-16">
        <div class="text-center lg:text-left">
            <p class="mb-5 inline-flex rounded-full bg-torns-mist px-3 py-1.5 text-xs font-bold uppercase tracking-widest text-torns-primary-dark">{{ __('Para enfermeras, médicos, TCAE, TES y más') }}</p>
            <h1 class="font-display text-4xl font-bold leading-[1.1] tracking-tight [text-wrap:balance] sm:text-5xl lg:text-[3.4rem]">{{ $title }}</h1>
            <p class="mx-auto mt-6 max-w-xl text-lg leading-relaxed text-torns-ink/70 lg:mx-0">{{ $subtitle }}</p>

            <div class="mt-8 flex flex-col items-center gap-4 lg:items-start">
                {!! Theme::partial('components.store-badges', ['center' => true, 'size' => 'md']) !!}
                <a href="https://app.torns.app" rel="noopener" class="btn-secondary">{{ __('Abrir en el navegador') }}</a>
            </div>

            <p class="mt-6 text-sm text-torns-ink/70">{{ __('Gratis para empezar · Pro 2,99 €/mes · 30 días de prueba con todo incluido · sin tarjeta') }}</p>
        </div>

        <div class="relative mx-auto w-56 sm:w-64">
            <div class="absolute -inset-10 rounded-full bg-torns-primary/10 blur-3xl" aria-hidden="true"></div>
            {!! Theme::partial('components.phone', [
                'name' => 'calendar-month',
                'framed' => true,
                'alt' => __('Calendario mensual de Torns con turnos de varios centros y vacaciones'),
                'sizes' => '(min-width: 640px) 16rem, 14rem',
                'eager' => true,
            ]) !!}
            @foreach ($tags as $i => $tag)
                <span class="center-tag center-tag--{{ $i + 1 }} {{ $tag['position'] }}" aria-hidden="true">
                    <span class="center-tag__dot {{ $tag['color'] }}"></span>
                    <span><b>{{ $tag['shift'] }}</b> · {{ $tag['center'] }}<br><span class="text-torns-ink/60">{{ $tag['time'] }}</span></span>
                </span>
            @endforeach
        </div>
    </div>
</section>
