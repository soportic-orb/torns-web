{{--
    Home-page announcement: 30 days of Pro on signing up. Hidden in the
    markup; main.js opens it a moment after the page loads, at most once
    every 10 days per browser (localStorage), and never for a visitor
    without JavaScript, so it can never trap anyone.
--}}
<div
    class="promo-pro fixed inset-0 z-[60] flex items-center justify-center bg-torns-ink/40 px-4 backdrop-blur-sm"
    role="dialog"
    aria-modal="true"
    aria-labelledby="promo-pro-title"
    aria-describedby="promo-pro-text"
    data-promo-pro
    hidden
>
    <div class="relative w-full max-w-md rounded-2xl bg-white p-8 text-center shadow-2xl sm:p-10" data-promo-pro-card>
        <button
            type="button"
            class="absolute right-3 top-3 inline-flex h-11 w-11 items-center justify-center rounded-full text-torns-ink/60 transition hover:bg-torns-ink/5 hover:text-torns-ink"
            aria-label="{{ __('Cerrar') }}"
            data-promo-pro-close
        >
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
        </button>
        <p class="text-6xl" aria-hidden="true">🤙</p>
        <h2 class="mt-5 font-display text-2xl font-bold leading-tight tracking-tight [text-wrap:balance] sm:text-3xl" id="promo-pro-title" tabindex="-1" style="outline: none">{{ __('Regístrate ahora y disfruta del plan Pro gratis durante 30 días') }}</h2>
        <p class="mt-4 leading-relaxed text-torns-ink/70 [text-wrap:pretty]" id="promo-pro-text">{{ __('Prueba todas las funcionalidades de Torns como centros de trabajo ilimitados, el Copilot, las alertas personalizadas, los cambios a 3 o los cambios urgentes SOS.') }}</p>
        <div class="mt-7">
            {!! Theme::partial('components.store-badges', ['center' => true, 'size' => 'md']) !!}
        </div>
    </div>
</div>
