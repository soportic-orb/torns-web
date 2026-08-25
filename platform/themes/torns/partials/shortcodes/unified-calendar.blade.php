@php
    $title = $shortcode->title ?: __('Calendario unificado de turnos de trabajo');
    $subtitle = $shortcode->subtitle ?: __('Gestiona tus turnos de trabajo y consulta los turnos disponibles de todos tus centros desde un único lugar.');
    $closing = $shortcode->closing ?: __('¡Ya no necesitas otras apps para la gestión de tus turnos!');
    $image = $shortcode->image ? RvMedia::getImageUrl($shortcode->image) : null;

    $benefitsLeft = [
        ['icon' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>', 'text' => __('El calendario de todos tus centros en un solo lugar')],
        ['icon' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3l4 4-4 4M21 7H7M7 21l-4-4 4-4M3 17h14"/></svg>', 'text' => __('Los turnos publicados en el Muro, visibles en tu calendario')],
    ];
    $benefitsRight = [
        ['icon' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12m0 0 4-4m-4 4-4-4"/><path d="M3 17v2a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-2"/></svg>', 'text' => __('Importa los turnos que ya tengas en tu calendario local')],
        ['icon' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-2.6-6.3M21 3v6h-6"/></svg>', 'text' => __('Sincronización con Apple Calendar y Google Calendar')],
    ];
    $accentClasses = [
        'bg-shift-morning/20 text-shift-morning',
        'bg-shift-evening/20 text-shift-evening',
        'bg-shift-night/20 text-shift-night',
        'bg-shift-morning/20 text-shift-morning',
    ];
@endphp

<section class="relative overflow-hidden bg-gradient-to-br from-torns-ink via-[#1a2f3f] to-torns-primary-dark py-16 text-white lg:py-24" id="calendario">
    <div class="pointer-events-none absolute inset-0" aria-hidden="true">
        <div class="absolute -right-24 top-0 h-80 w-80 rounded-full bg-torns-primary/20 blur-3xl"></div>
        <div class="absolute -left-24 bottom-0 h-72 w-72 rounded-full bg-shift-night/20 blur-3xl"></div>
    </div>
    <div class="relative mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <p class="mb-4">
                <span class="inline-flex items-center rounded-full border border-white/10 bg-white/10 px-3.5 py-1.5 text-xs font-bold uppercase tracking-wider text-shift-morning">{{ __('Unified calendar') }}</span>
            </p>
            <h2 class="font-display text-3xl font-bold tracking-tight sm:text-4xl">{{ $title }}</h2>
            <p class="mt-4 text-base leading-relaxed text-white/70 sm:text-lg">{{ $subtitle }}</p>
        </div>

        <div class="mt-14 grid items-center gap-10 lg:grid-cols-[1fr_auto_1fr] lg:gap-8">
            <ul class="flex flex-col gap-6">
                @foreach ($benefitsLeft as $i => $benefit)
                    <li class="flex items-start gap-4 rounded-2xl border border-white/10 bg-white/10 p-5 backdrop-blur lg:flex-row-reverse lg:text-right">
                        <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $accentClasses[$i] }}" aria-hidden="true">{!! $benefit['icon'] !!}</span>
                        <span class="text-sm leading-relaxed text-white/85 sm:text-base">{{ $benefit['text'] }}</span>
                    </li>
                @endforeach
            </ul>

            <div class="order-first mx-auto w-60 sm:w-64 lg:order-none">
                @if ($image)
                    <img src="{{ $image }}" alt="{{ __('Unified calendar in the Torns app') }}" class="w-full drop-shadow-2xl" width="512" height="963" loading="lazy">
                @else
                    {!! Theme::partial('components.phone-mockup', ['screen' => 'calendar', 'label' => __('Unified calendar in the Torns app')]) !!}
                @endif
            </div>

            <ul class="flex flex-col gap-6">
                @foreach ($benefitsRight as $i => $benefit)
                    <li class="flex items-start gap-4 rounded-2xl border border-white/10 bg-white/10 p-5 backdrop-blur">
                        <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $accentClasses[$i + 2] }}" aria-hidden="true">{!! $benefit['icon'] !!}</span>
                        <span class="text-sm leading-relaxed text-white/85 sm:text-base">{{ $benefit['text'] }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <p class="mx-auto mt-12 max-w-xl text-center font-display text-xl font-bold text-shift-morning sm:text-2xl">{{ $closing }}</p>
    </div>
</section>
