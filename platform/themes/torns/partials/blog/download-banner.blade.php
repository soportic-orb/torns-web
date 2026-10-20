{{-- End-of-post call to action: try the app, with the store badges. --}}
<aside class="not-prose mt-12 overflow-hidden rounded-3xl bg-gradient-to-br from-torns-primary to-torns-primary-dark text-white" aria-labelledby="post-download-title">
    <div class="grid items-center gap-6 px-6 py-8 sm:grid-cols-[3fr_2fr] sm:px-10 sm:py-10">
        <div class="text-center sm:text-left">
            <p class="text-sm font-semibold uppercase tracking-widest text-white/80">{{ __('Prueba Torns') }}</p>
            <h2 class="mt-2 font-display text-2xl font-bold leading-tight [text-wrap:balance] sm:text-3xl" id="post-download-title">{{ __('Tus turnos y guardias, en un solo calendario') }}</h2>
            <p class="mt-3 leading-relaxed text-white/90">{{ __('Organiza los turnos de todos tus centros y cambia turnos con tus compañeros. Gratis para empezar, con 30 días de Torns Pro sin tarjeta.') }}</p>
            <div class="mt-6 flex justify-center sm:justify-start">
                {!! Theme::partial('components.store-badges', ['size' => 'md']) !!}
            </div>
        </div>
        <img
            src="{{ Theme::asset()->url('images/home/hero-phones-640.webp') }}"
            alt=""
            class="mx-auto hidden w-full max-w-[15rem] drop-shadow-xl sm:block"
            width="640"
            height="555"
            loading="lazy"
        >
    </div>
</aside>
