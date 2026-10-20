@php
    $competitors = torns_comparison_competitors();
    $locale = torns_current_locale();
    $prefix = $locale === 'es' ? '' : $locale . '/';
@endphp

<section class="bg-gradient-to-b from-white to-torns-bg">
    <div class="mx-auto max-w-5xl px-4 pb-10 pt-12 sm:px-6 lg:px-8 lg:pb-14 lg:pt-16">
        <p class="text-sm font-semibold uppercase tracking-widest text-torns-primary">{{ __('Comparativas') }}</p>
        <h1 class="mt-3 font-display text-3xl font-bold leading-tight tracking-tight [text-wrap:balance] sm:text-4xl lg:text-5xl">{{ __('Torns frente a otras apps de turnos') }}</h1>
        <p class="mt-5 max-w-3xl text-lg leading-relaxed text-torns-ink/70">{{ __('Comparativas de funcionalidades entre Torns y otras apps de calendario y cambios de turno que se usan en España, con más de 30 puntos cada una y las fuentes públicas de cada dato.') }}</p>
    </div>
</section>

<section class="pb-16 lg:pb-20">
    <div class="mx-auto grid max-w-5xl gap-5 px-4 sm:grid-cols-2 sm:px-6 lg:px-8">
        @foreach ($competitors as $slug => $competitor)
            <a href="{{ url($prefix . 'torns-vs-' . $slug) }}" class="group flex flex-col rounded-2xl border border-torns-ink/10 bg-white p-6 transition hover:border-torns-primary hover:shadow-lg">
                <span class="font-display text-xl font-bold group-hover:text-torns-primary">Torns vs {{ $competitor['name'] }}</span>
                <span class="mt-2 flex-1 text-sm leading-relaxed text-torns-ink/70">{{ torns_comparison_text($competitor['summary'] ?? '') }}</span>
                <span class="mt-4 text-sm font-semibold text-torns-primary">{{ __('Ver la comparativa') }} →</span>
            </a>
        @endforeach
    </div>
</section>
