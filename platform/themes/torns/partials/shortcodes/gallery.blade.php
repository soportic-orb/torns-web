@php
    $title = $shortcode->title ?: __('No querrás salir de la app');
    $subtitle = $shortcode->subtitle ?: __('Diseño sencillo, claro y minimalista, con la lógica de los profesionales de la salud.');

    // Screenshot order and captions match the current website.
    $screens = [
        ['file' => 'screen-2903-thumb.webp', 'caption' => __('Calendario integrado')],
        ['file' => 'screen-2896-thumb.webp', 'caption' => __('Muro de cambios publicados')],
        ['file' => 'screen-2897-thumb.webp', 'caption' => __('Turno de cobertura publicado por el centro')],
        ['file' => 'screen-2894-thumb.webp', 'caption' => __('Propuestas de cadenas de turnos')],
        ['file' => 'screen-2895-thumb.webp', 'caption' => __('Detalle de la propuesta de cadena de turno')],
        ['file' => 'screen-2898-thumb.webp', 'caption' => __('Detalle de la propuesta de cadena de cambios de Copilot')],
        ['file' => 'screen-2899-thumb.webp', 'caption' => __('Pantalla Cambios con los turnos publicados y su estado')],
        ['file' => 'screen-2900-thumb.webp', 'caption' => __('Detalle de turno publicado por el usuario')],
        ['file' => 'screen-2901-thumb.webp', 'caption' => __('Publicación de un turno SOS urgente')],
        ['file' => 'screen-2902-thumb.webp', 'caption' => __('Selección de los turnos a visualizar en el calendario')],
        ['file' => 'screen-2904-thumb.webp', 'caption' => __('Sincronización de calendarios')],
        ['file' => 'screen-2905-thumb.webp', 'caption' => __('Sistema de mensajería interno')],
        ['file' => 'screen-2906-thumb.webp', 'caption' => __('Perfil del usuario')],
    ];
@endphp

<section class="bg-white py-16 lg:py-24">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        {!! Theme::partial('components.section-heading', ['title' => $title, 'subtitle' => $subtitle]) !!}
    </div>

    {{-- Mobile: horizontal scroll with snap. Desktop: grid + vanilla lightbox. --}}
    <div class="mt-10 lg:hidden">
        <ul class="scrollbar-none flex snap-x snap-mandatory gap-4 overflow-x-auto px-4 pb-4 sm:px-6">
            @foreach ($screens as $screen)
                <li class="w-56 shrink-0 snap-center">
                    <figure>
                        <img
                            src="{{ Theme::asset()->url('images/home/gallery/' . $screen['file']) }}"
                            alt="{{ $screen['caption'] }}"
                            class="w-full rounded-2xl border border-torns-ink/10 shadow-sm"
                            width="400"
                            height="516"
                            loading="lazy"
                        >
                        <figcaption class="mt-2 px-1 text-center text-xs text-torns-ink/70">{{ $screen['caption'] }}</figcaption>
                    </figure>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="mx-auto mt-10 hidden max-w-6xl grid-cols-4 gap-5 px-4 sm:px-6 lg:grid lg:px-8 xl:grid-cols-5" data-gallery>
        @foreach ($screens as $screen)
            <figure>
                <button
                    type="button"
                    class="group block w-full overflow-hidden rounded-2xl border border-torns-ink/10 transition hover:shadow-lg focus-visible:shadow-lg"
                    data-gallery-item
                    data-caption="{{ $screen['caption'] }}"
                    data-full="{{ Theme::asset()->url('images/home/gallery/' . $screen['file']) }}"
                >
                    <img
                        src="{{ Theme::asset()->url('images/home/gallery/' . $screen['file']) }}"
                        alt="{{ $screen['caption'] }}"
                        class="w-full transition duration-200 group-hover:scale-[1.02] motion-reduce:transition-none"
                        width="400"
                        height="516"
                        loading="lazy"
                    >
                </button>
                <figcaption class="mt-2 px-1 text-center text-xs text-torns-ink/70">{{ $screen['caption'] }}</figcaption>
            </figure>
        @endforeach
    </div>

    {{-- Lightbox (vanilla JS, wired in main.js) --}}
    <div class="lightbox fixed inset-0 z-[90] hidden items-center justify-center bg-torns-ink/90 p-6" data-lightbox role="dialog" aria-modal="true" aria-label="{{ __('Screenshot viewer') }}">
        <button type="button" class="absolute right-5 top-5 inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/20" data-lightbox-close>
            <span class="sr-only">{{ __('Close') }}</span>
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
        </button>
        <button type="button" class="absolute left-4 top-1/2 hidden h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/20 sm:inline-flex" data-lightbox-prev>
            <span class="sr-only">{{ __('Previous') }}</span>
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
        </button>
        <button type="button" class="absolute right-4 top-1/2 hidden h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/20 sm:inline-flex" data-lightbox-next>
            <span class="sr-only">{{ __('Next') }}</span>
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
        </button>
        <figure class="flex max-h-full flex-col items-center">
            <img src="" alt="" class="max-h-[80vh] w-auto rounded-2xl" data-lightbox-image>
            <figcaption class="mt-4 text-sm text-white/80" data-lightbox-caption></figcaption>
        </figure>
    </div>
</section>
