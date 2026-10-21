@php
    $title = $shortcode->title ?: __('Deja la libreta, la foto del cuadrante y las tres apps');
    $subtitle = $shortcode->subtitle ?: __('Un solo calendario para toda tu actividad laboral, aunque trabajes en varios centros con horarios distintos.');
    $closing = $shortcode->closing ?: __('¡Ya no necesitas otras apps para la gestión de tus turnos!');

    $screens = [
        ['name' => 'calendar-month', 'title' => __('Todo tu mes de un vistazo'), 'text' => __('Cada centro y cada tipo de turno con su color, y tus vacaciones marcadas.')],
        ['name' => 'calendar-day', 'title' => __('El detalle de cada día'), 'text' => __('Horario, centro y el tiempo que hará. Los turnos que tienes en el Muro, señalados.')],
        ['name' => 'work-stats', 'title' => __('Tus horas, contadas'), 'text' => __('Turnos, horas, horas extra, domingos, sábados y festivos, por centro y por área.')],
    ];

    // Plan per feature as configured in the app (plans_config): keep in sync.
    $features = [
        ['title' => __('Varios centros'), 'text' => __('El hospital, el centro de salud y la ambulancia en el mismo calendario.'), 'plan' => 'free', 'plan_label' => __('Free: 1 centro · Pro: ilimitados')],
        ['title' => __('Turnos y guardias'), 'text' => __('Mañana, tarde, noche, 12 y 24 horas, con tus horarios reales.'), 'plan' => 'free'],
        ['title' => __('Vacaciones y no disponible'), 'text' => __('Marca tus vacaciones y los días en que no puedes cambiar turnos.'), 'plan' => 'free'],
        ['title' => __('Lugares habituales'), 'text' => __('Guarda centro, unidad y horario para crear turnos con un toque.'), 'plan' => 'free'],
        ['title' => __('Vistas a tu manera'), 'text' => __('Mes, día, lista y año, con el tiempo de cada día.'), 'plan' => 'free'],
        ['title' => __('Sincronizado'), 'text' => __('Con el calendario del móvil, Apple Calendar y Google Calendar.'), 'plan' => 'free'],
        ['title' => __('Sin conexión'), 'text' => __('Consulta tus turnos aunque no tengas cobertura.'), 'plan' => 'free'],
        ['title' => __('Rotaciones'), 'text' => __('Crea tu patrón de turnos y aplícalo a semanas o meses.'), 'plan' => 'pro'],
        ['title' => __('Recordatorios'), 'text' => __('Un aviso unas horas antes de cada turno, también de los recién cambiados.'), 'plan' => 'pro'],
        ['title' => __('Estadísticas de trabajo'), 'text' => __('Horas trabajadas por centro, por área y por tipo de día.'), 'plan' => 'pro'],
        ['title' => __('Compartir'), 'text' => __('Tu calendario en una página web personal para quien tú quieras.'), 'plan' => 'pro'],
        ['title' => __('Imprimir en PDF'), 'text' => __('Elige la vista y qué datos incluir, e imprímelo o envíalo.'), 'plan' => 'pro'],
    ];
@endphp

<section class="bg-torns-ink py-16 text-white lg:py-24" id="calendario">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <p class="mb-3 text-sm font-semibold uppercase tracking-widest text-torns-sky">{{ __('Unified calendar') }}</p>
            <h2 class="font-display text-3xl font-bold leading-tight tracking-tight [text-wrap:balance] sm:text-[2.6rem]">{{ $title }}</h2>
            <p class="mt-4 text-base leading-relaxed text-white/75 sm:text-lg">{{ $subtitle }}</p>
        </div>

        {{-- Mobile: swipe between screens. Desktop: three phones side by side. --}}
        <ul class="scrollbar-none -mx-4 mt-12 list-none flex snap-x snap-mandatory gap-6 overflow-x-auto px-4 pb-2 sm:-mx-6 sm:px-6 lg:mx-0 lg:grid lg:grid-cols-3 lg:gap-10 lg:overflow-visible lg:px-0">
            @foreach ($screens as $screen)
                <li class="w-60 shrink-0 snap-center lg:w-auto">
                    <figure class="flex flex-col items-center">
                        {!! Theme::partial('components.phone', [
                            'name' => $screen['name'],
                            'alt' => $screen['title'],
                            'tilt' => $loop->iteration === 2 ? 'right' : null,
                            'class' => 'w-full max-w-[15rem] ring-1 ring-white/10',
                            'sizes' => '(min-width: 1024px) 16rem, 15rem',
                        ]) !!}
                        <figcaption class="mt-5 text-center">
                            <span class="block font-display text-lg font-semibold">{{ $screen['title'] }}</span>
                            <span class="mt-1 block text-sm leading-relaxed text-white/70">{{ $screen['text'] }}</span>
                        </figcaption>
                    </figure>
                </li>
            @endforeach
        </ul>

        <ul class="mt-14 grid list-none grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4">
            @foreach ($features as $feature)
                <li class="flex flex-col gap-2 rounded-2xl bg-torns-deep p-4 sm:p-5">
                    <h3 class="text-sm font-semibold sm:text-base">{{ $feature['title'] }}</h3>
                    <p class="text-xs leading-relaxed text-white/75 sm:text-sm">{{ $feature['text'] }}</p>
                    <span class="plan-tag plan-tag--{{ $feature['plan'] }} mt-auto">{{ $feature['plan_label'] ?? ($feature['plan'] === 'pro' ? 'Pro' : 'Free') }}</span>
                </li>
            @endforeach
        </ul>

        <p class="mx-auto mt-12 max-w-xl text-center font-display text-xl font-bold text-shift-morning sm:text-2xl">{{ $closing }}</p>
    </div>
</section>
