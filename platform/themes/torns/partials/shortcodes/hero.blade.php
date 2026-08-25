@php
    $title = $shortcode->title ?: __('Cambia turnos con tus compañeros y gestiona el calendario de todos tus centros');
    $subtitle = $shortcode->subtitle ?: __('Torns es la app de cambios de turno para profesionales sanitarios: publica el turno que no puedes hacer, intercámbialo con compañeros de tu centro y controla todos tus turnos en un calendario unificado sincronizado con Apple y Google Calendar.');
    $image = $shortcode->image ? RvMedia::getImageUrl($shortcode->image) : null;
@endphp

<section class="relative overflow-hidden bg-gradient-to-b from-white to-torns-bg">
    {{-- Decorative background blobs --}}
    <div class="pointer-events-none absolute inset-0" aria-hidden="true">
        <div class="absolute -left-24 top-16 h-72 w-72 rounded-full bg-torns-primary/10 blur-3xl"></div>
        <div class="absolute -right-16 top-40 h-80 w-80 rounded-full bg-shift-night/10 blur-3xl"></div>
        <div class="absolute bottom-0 left-1/3 h-64 w-64 rounded-full bg-shift-morning/10 blur-3xl"></div>
    </div>

    <div class="relative mx-auto grid max-w-6xl items-center gap-14 px-4 pb-16 pt-12 sm:px-6 lg:grid-cols-2 lg:gap-8 lg:px-8 lg:pb-24 lg:pt-20">
        <div class="text-center lg:text-left">
            <p class="mb-5">
                <span class="inline-flex items-center gap-1.5 rounded-full border border-torns-primary/20 bg-white px-4 py-1.5 text-xs font-bold text-torns-primary shadow-sm">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 21s-7.5-4.7-9.8-9.4C.5 8 2.6 4.5 6.2 4.5c2 0 3.7 1.1 4.6 2.7l1.2 2 1.2-2c.9-1.6 2.6-2.7 4.6-2.7 3.6 0 5.7 3.5 4 7.1C19.5 16.3 12 21 12 21Z"/></svg>
                    {{ __('La app de turnos para profesionales sanitarios') }}
                </span>
            </p>
            <h1 class="font-display text-4xl font-bold tracking-tight sm:text-5xl">{{ $title }}</h1>
            <p class="mx-auto mt-5 max-w-xl text-lg leading-relaxed text-torns-ink/70 lg:mx-0">{{ $subtitle }}</p>

            <div class="mt-8">
                {!! Theme::partial('components.store-badges', ['center' => true, 'size' => 'md']) !!}
            </div>

            {{-- Signature animation: two shift chips swapping between two avatars --}}
            <div class="swap-demo mt-12" aria-label="{{ __('Two shifts being swapped between two colleagues') }}">
                <div class="swap-demo__row">
                    <span class="swap-demo__avatar" aria-hidden="true">L</span>
                    <span class="swap-demo__chip swap-demo__chip--a">
                        {!! Theme::partial('components.shift-chip', ['type' => 'morning', 'time' => '07–15']) !!}
                    </span>
                    <span class="swap-demo__arrows" aria-hidden="true">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3l4 4-4 4M21 7H7M7 21l-4-4 4-4M3 17h14"/></svg>
                    </span>
                    <span class="swap-demo__chip swap-demo__chip--b">
                        {!! Theme::partial('components.shift-chip', ['type' => 'night', 'time' => '23–07']) !!}
                    </span>
                    <span class="swap-demo__avatar swap-demo__avatar--b" aria-hidden="true">M</span>
                </div>
            </div>
        </div>

        <div class="relative mx-auto w-full max-w-[19rem] sm:max-w-[21rem]">
            <div class="absolute -inset-10 rounded-full bg-torns-primary/10 blur-2xl" aria-hidden="true"></div>

            @if ($image)
                <img src="{{ $image }}" alt="{{ __('Torns app on an iPhone') }}" class="relative w-full" width="896" height="1041" fetchpriority="high">
            @else
                {!! Theme::partial('components.phone-mockup', ['screen' => 'wall', 'label' => __('Muro de turnos publicados en la app Torns')]) !!}

                {{-- Floating UI cards --}}
                <div class="float-card float-card--a -left-8 top-36 sm:-left-14" aria-hidden="true">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-torns-primary/10 text-torns-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 8a6 6 0 1 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 0 1-3.4 0"/></svg>
                    </span>
                    <span>
                        <span class="block text-xs font-bold leading-tight">{{ __('Nueva propuesta de cambio') }}</span>
                        <span class="block text-[10px] leading-tight text-torns-ink/60">Laura G. · {{ __('Vie') }} 13 · 07–15</span>
                    </span>
                </div>
                <div class="float-card float-card--b -right-6 bottom-24 sm:-right-12" aria-hidden="true">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m5 13 4 4L19 7"/></svg>
                    </span>
                    <span>
                        <span class="block text-xs font-bold leading-tight">{{ __('¡Cambio aceptado!') }}</span>
                        <span class="block text-[10px] leading-tight text-torns-ink/60">{{ __('Gestor de planillas avisado') }}</span>
                    </span>
                </div>
            @endif
        </div>
    </div>
</section>
