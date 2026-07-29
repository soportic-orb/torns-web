@php
    $title = $shortcode->title ?: 'Cambia turnos con tus compañeros y gestiona el calendario de todos tus centros';
    $subtitle = $shortcode->subtitle ?: 'Torns es la app de cambios de turno para profesionales sanitarios: publica el turno que no puedes hacer, intercámbialo con compañeros de tu centro y controla todos tus turnos en un calendario unificado sincronizado con Apple y Google Calendar.';
    $image = $shortcode->image ? RvMedia::getImageUrl($shortcode->image) : Theme::asset()->url('images/home/hero-mockup-896.webp');
    $imageSrcset = $shortcode->image ? null : Theme::asset()->url('images/home/hero-mockup-480.webp') . ' 480w, ' . Theme::asset()->url('images/home/hero-mockup-896.webp') . ' 896w';
@endphp

<section class="overflow-hidden bg-gradient-to-b from-white to-torns-bg">
    <div class="mx-auto grid max-w-6xl items-center gap-12 px-4 pb-16 pt-12 sm:px-6 lg:grid-cols-2 lg:gap-8 lg:px-8 lg:pb-24 lg:pt-20">
        <div class="text-center lg:text-left">
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

        <div class="relative mx-auto w-full max-w-sm lg:max-w-md">
            <div class="absolute -inset-8 rounded-full bg-torns-primary/5 blur-2xl" aria-hidden="true"></div>
            <img
                src="{{ $image }}"
                @if ($imageSrcset) srcset="{{ $imageSrcset }}" sizes="(min-width: 1024px) 28rem, 90vw" @endif
                alt="{{ __('Torns app on an iPhone') }}"
                class="relative w-full"
                width="896"
                height="1041"
                fetchpriority="high"
            >
        </div>
    </div>
</section>
