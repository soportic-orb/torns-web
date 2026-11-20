@php
    $title = $shortcode->title ?: __('Crea una cuenta en menos de 1 minuto');
    $subtitle = $shortcode->subtitle ?: __('Da de alta tu centro en el panel de gestión de Torns. Crea la cuenta ahora, gratis.');
    $url = $shortcode->url ?: 'https://panel.torns.app/register';
    $image = fn (int $width) => Theme::asset()->url("images/home/centers-signup-{$width}.webp");
@endphp

<section class="pb-16 lg:pb-24" id="crear-cuenta">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-8 overflow-hidden rounded-3xl bg-torns-mist px-6 py-10 sm:px-10 lg:grid-cols-[5fr_4fr] lg:gap-12 lg:px-14 lg:py-14">
            <div class="text-center lg:text-left">
                <h2 class="font-display text-3xl font-bold tracking-tight [text-wrap:balance] sm:text-4xl">{{ $title }}</h2>
                <p class="mx-auto mt-4 max-w-xl text-lg leading-relaxed text-torns-ink/75 lg:mx-0">{{ $subtitle }}</p>
                <ul class="mt-6 flex list-none flex-wrap justify-center gap-x-6 gap-y-2 lg:justify-start">
                    @foreach ([__('30 días de prueba'), __('Sin tarjeta de crédito')] as $point)
                        <li class="inline-flex items-center gap-2 font-semibold text-torns-primary-dark">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12 5 5L20 7"/></svg>
                            {{ $point }}
                        </li>
                    @endforeach
                </ul>
                <a href="{{ $url }}" rel="noopener" class="btn-primary mt-8 px-7 py-3 text-base">{{ __('¡Crea tu cuenta!') }}</a>
            </div>
            <img
                src="{{ $image(1120) }}"
                srcset="{{ $image(560) }} 560w, {{ $image(1120) }} 1120w"
                sizes="(min-width: 1024px) 26rem, 90vw"
                alt=""
                class="mx-auto w-full max-w-sm lg:max-w-none"
                width="1120"
                height="724"
                loading="lazy"
            >
        </div>
    </div>
</section>
