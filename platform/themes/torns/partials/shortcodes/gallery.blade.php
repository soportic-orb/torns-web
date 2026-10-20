@php
    $title = $shortcode->title ?: __('No querrás salir de la app');
    $subtitle = $shortcode->subtitle ?: __('Diseño sencillo, claro y minimalista, con la lógica de los profesionales de la salud.');
    $screenUrl = fn (string $name, int $width) => Theme::asset()->url("images/home/screens/{$name}-{$width}.webp");

    // Store mockups of the current website, in its order.
    $screens = [
        ['name' => 'screen-2903', 'caption' => __('Calendario integrado')],
        ['name' => 'screen-2896', 'caption' => __('Muro de cambios publicados')],
        ['name' => 'screen-2897', 'caption' => __('Turno de cobertura publicado por el centro')],
        ['name' => 'screen-2894', 'caption' => __('Propuestas de cadenas de turnos')],
        ['name' => 'screen-2895', 'caption' => __('Detalle de la propuesta de cadena de turno')],
        ['name' => 'screen-2898', 'caption' => __('Detalle de la propuesta de cadena de cambios de Copilot')],
        ['name' => 'screen-2899', 'caption' => __('Pantalla Cambios con los turnos publicados y su estado')],
        ['name' => 'screen-2900', 'caption' => __('Detalle de turno publicado por el usuario')],
        ['name' => 'screen-2901', 'caption' => __('Publicación de un turno SOS urgente')],
        ['name' => 'screen-2902', 'caption' => __('Selección de los turnos a visualizar en el calendario')],
        ['name' => 'screen-2904', 'caption' => __('Sincronización de calendarios')],
        ['name' => 'screen-2905', 'caption' => __('Sistema de mensajería interno')],
        ['name' => 'screen-2906', 'caption' => __('Perfil del usuario')],
    ];
@endphp

<section class="overflow-hidden bg-white py-16 lg:py-24" id="pantallas">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        {!! Theme::partial('components.section-heading', ['title' => $title, 'subtitle' => $subtitle]) !!}

        <div class="screens-carousel mt-12" data-carousel tabindex="0" role="region" aria-roledescription="carousel" aria-label="{{ $title }}">
            <ul class="screens-carousel__stage list-none">
                @foreach ($screens as $screen)
                    <li class="screens-carousel__slide" data-carousel-slide data-caption="{{ $screen['caption'] }}" aria-label="{{ $screen['caption'] }}">
                        <img
                            src="{{ $screenUrl($screen['name'], 560) }}"
                            srcset="{{ $screenUrl($screen['name'], 280) }} 280w, {{ $screenUrl($screen['name'], 560) }} 560w"
                            sizes="17rem"
                            alt="{{ $screen['caption'] }}"
                            class="block h-auto w-full drop-shadow-xl"
                            width="560"
                            height="1132"
                            @if ($loop->index > 2 && $loop->remaining > 2) loading="lazy" @endif
                        >
                    </li>
                @endforeach
            </ul>

            <div class="mt-6 flex items-center justify-center gap-5">
                <button type="button" class="inline-flex h-12 w-12 items-center justify-center rounded-full border border-torns-ink/15 bg-white text-torns-ink transition hover:border-torns-primary hover:text-torns-primary" data-carousel-prev>
                    <span class="sr-only">{{ __('Previous') }}</span>
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                </button>
                <p class="min-w-0 max-w-xs text-center text-sm font-semibold sm:max-w-sm" data-carousel-caption aria-live="polite">{{ $screens[0]['caption'] }}</p>
                <button type="button" class="inline-flex h-12 w-12 items-center justify-center rounded-full border border-torns-ink/15 bg-white text-torns-ink transition hover:border-torns-primary hover:text-torns-primary" data-carousel-next>
                    <span class="sr-only">{{ __('Next') }}</span>
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
                </button>
            </div>
        </div>
    </div>
</section>
