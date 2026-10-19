@php
    // "What is Torns" in three cards: calendar first, then swaps, then the assistant.
    $title = $shortcode->title ?: __('¿Qué es Torns?');
    $pillars = [
        ['title' => $shortcode->bullet_1 ?: __('Tu calendario laboral'), 'text' => __('Turnos, guardias, vacaciones y festivos de todos los centros donde trabajas, con horas y estadísticas.'), 'href' => '#calendario'],
        ['title' => $shortcode->bullet_2 ?: __('Cambios sin perseguir a nadie'), 'text' => __('Publicas el turno, tus compañeros te proponen uno suyo y, al aceptar, los dos calendarios se actualizan.'), 'href' => '#funcionalidades'],
        ['title' => $shortcode->bullet_3 ?: __('Un asistente que te ayuda'), 'text' => __('Copilot importa tu cuadrante desde una foto y busca el cambio que te encaja, también a 3 o 4.'), 'href' => '#ia'],
    ];
@endphp

<section class="pb-16 lg:pb-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <h2 class="sr-only">{{ $title }}</h2>
        <div class="grid gap-5 md:grid-cols-3">
            @foreach ($pillars as $pillar)
                <a href="{{ $pillar['href'] }}" class="group rounded-3xl bg-white p-7 transition hover:shadow-lg hover:shadow-torns-ink/5">
                    <h3 class="font-display text-xl font-bold">{{ $pillar['title'] }}</h3>
                    <p class="mt-3 leading-relaxed text-torns-ink/70">{{ $pillar['text'] }}</p>
                    <span class="mt-4 inline-flex text-sm font-semibold text-torns-primary group-hover:text-torns-primary-dark" aria-hidden="true">→</span>
                </a>
            @endforeach
        </div>
    </div>
</section>
