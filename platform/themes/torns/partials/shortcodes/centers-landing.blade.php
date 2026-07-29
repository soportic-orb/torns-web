@php
    $problemTitle = $shortcode->problem_title ?: __('¿Cuánto tiempo pierden tus profesionales buscando un cambio de turno?');
    $benefitsTitle = $shortcode->benefits_title ?: __('Ellos ganan. Tu centro gana.');
    $panelTitle = $shortcode->panel_title ?: __('Tu panel de gestión');

    $icon = fn (string $path) => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . $path . '</svg>';

    $benefits = [
        ['title' => __('Mejora del clima laboral'), 'description' => __('Un equipo que puede conciliar mejor trabaja mejor. Los profesionales con herramientas reales para gestionar su vida laboral reportan mayor satisfacción y menor intención de abandono.'), 'icon' => $icon('<path d="M12 21s-7-4.6-9.3-9A5.4 5.4 0 0 1 12 6.6 5.4 5.4 0 0 1 21.3 12C19 16.4 12 21 12 21Z"/>')],
        ['title' => __('Reducción del absentismo'), 'description' => __('Cuando los profesionales pueden reorganizar sus turnos de forma ágil y autónoma, los pequeños imprevistos personales dejan de convertirse en ausencias.'), 'icon' => $icon('<path d="M3 12a9 9 0 1 0 18 0 9 9 0 0 0-18 0Z"/><path d="m8 12 3 3 5-6"/>')],
        ['title' => __('Adiós al grupo de WhatsApp'), 'description' => __('Cada publicación, propuesta, aceptación o rechazo genera una notificación push inmediata. Nadie tiene que estar pendiente de ningún grupo.'), 'icon' => $icon('<path d="M18 8a6 6 0 1 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 0 1-3.4 0"/>')],
        ['title' => __('Cambios en minutos, no en días'), 'description' => __('El Muro muestra en tiempo real los turnos publicados por compañeros del mismo centro, filtrados por unidad, área y tipo de turno.'), 'icon' => $icon('<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/>')],
        ['title' => __('Propuestas sin malentendidos'), 'description' => __('Ambas partes ven todos los detalles: quién propone, qué turno ofrece y qué turno pide. La aceptación es explícita y queda registrada.'), 'icon' => $icon('<path d="M8 12h8M12 8v8"/><rect x="3" y="3" width="18" height="18" rx="4"/>')],
        ['title' => __('Multi-centro con un único perfil'), 'description' => __('Muchos profesionales trabajan en más de un centro. Torns permite gestionarlos todos desde una sola app.'), 'icon' => $icon('<path d="M3 21h18M5 21V7l7-4 7 4v14"/><path d="M9 9h1m4 0h1M9 13h1m4 0h1M9 17h1m4 0h1"/>')],
    ];

    $panelFeatures = [
        ['title' => __('Todos tus centros desde un mismo panel'), 'description' => __('Gestiona tantos centros como necesites, cada uno con su propio equipo de gestión y sus usuarios.')],
        ['title' => __('Recibe los cambios de tus profesionales'), 'description' => __('El panel recibe todas las notificaciones de cambios hechos desde la app. Márcalos como visualizados y los profesionales verán que el gestor los ha aceptado.')],
        ['title' => __('Publica turnos que necesites cubrir'), 'description' => __('Envía coberturas de turno a los usuarios de la app, gestiona las peticiones y asigna los turnos desde el panel.')],
        ['title' => __('Campañas de mailing'), 'description' => __('Diseña comunicados visualmente, selecciona destinatarios y consulta estadísticas de aperturas y clics.')],
        ['title' => __('Panel de gestor'), 'description' => __('Los gestores controlan los profesionales de sus servicios, reciben sus cambios y publican turnos a cubrir.')],
        ['title' => __('Soporte personalizado, del bueno'), 'description' => __('Soporte técnico y comercial integrado mediante tickets, para clientes, gestores y usuarios.')],
    ];
    $accents = ['morning', 'evening', 'night'];
@endphp

<section class="bg-white py-14 lg:py-20">
    <div class="mx-auto grid max-w-6xl items-start gap-10 px-4 sm:px-6 lg:grid-cols-2 lg:gap-16 lg:px-8">
        <div>
            <h2 class="font-display text-2xl font-bold tracking-tight sm:text-3xl">{{ $problemTitle }}</h2>
            <p class="mt-4 leading-relaxed text-torns-ink/70">{{ __('La gestión de cambios de turno en los centros sanitarios sigue siendo un proceso informal y caótico: grupos de WhatsApp saturados, llamadas fuera de horario, mensajes perdidos, malentendidos…') }}</p>
            <p class="mt-3 leading-relaxed text-torns-ink/70">{{ __('El resultado lo conoces bien: profesionales estresados, coordinadores desbordados y una conciliación familiar que se resiente semana tras semana.') }}</p>
        </div>
        <div class="rounded-2xl bg-torns-bg p-6 lg:p-8">
            <p class="leading-relaxed text-torns-ink/80">{{ __('Torns nació para resolver exactamente esto. Es la plataforma especializada en cambios de turno para profesionales sanitarios — enfermería, medicina, técnicos, auxiliares — que digitaliza y ordena todo el proceso.') }}</p>
            <p class="mt-3 font-medium leading-relaxed">{{ __('Cuando tu centro adquiere licencias para sus trabajadores, toda la plantilla accede de forma gratuita y el caos se convierte en un proceso transparente, trazable y sin fricciones.') }}</p>
        </div>
    </div>
</section>

<section class="py-14 lg:py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        {!! Theme::partial('components.section-heading', [
            'eyebrow' => __('Advantages for your center'),
            'title' => $benefitsTitle,
            'subtitle' => __('Una herramienta ágil, sencilla y compatible con tus sistemas de gestión actuales.'),
        ]) !!}
        <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($benefits as $index => $benefit)
                {!! Theme::partial('components.feature-card', [
                    'title' => $benefit['title'],
                    'description' => $benefit['description'],
                    'icon' => $benefit['icon'],
                    'accent' => $accents[$index % 3],
                ]) !!}
            @endforeach
        </div>
    </div>
</section>

<section class="bg-torns-ink py-14 text-white lg:py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <p class="mb-3 text-sm font-semibold uppercase tracking-widest text-shift-morning">{{ __('Integrated management') }}</p>
            <h2 class="font-display text-3xl font-bold tracking-tight sm:text-4xl">{{ $panelTitle }}</h2>
            <p class="mt-4 leading-relaxed text-white/70">{{ __('Tú les ofreces una app que mejora sus cambios de turno. Tú obtienes un panel para gestionar tus centros, publicar coberturas y recibir los cambios.') }}</p>
        </div>
        <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($panelFeatures as $feature)
                <div class="rounded-2xl bg-white/5 p-6">
                    <h3 class="font-display text-lg font-semibold">{{ $feature['title'] }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-white/70">{{ $feature['description'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-14 lg:py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
            <div>
                <h2 class="font-display text-2xl font-bold tracking-tight sm:text-3xl">{{ __('How does the center code work?') }}</h2>
                <ol class="mt-6 flex flex-col gap-4">
                    @foreach ([
                        __('Tu centro recibe un código único al darse de alta en Torns.'),
                        __('Compártelo con tus profesionales por el canal que prefieras.'),
                        __('Cada profesional lo introduce en la pantalla de inicio de la app y se une al centro en segundos, sin configuraciones.'),
                        __('Desde ese momento ven el Muro del centro y pueden intercambiar turnos entre ellos.'),
                    ] as $index => $step)
                        <li class="flex items-start gap-4">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-torns-primary font-display text-sm font-bold text-white" aria-hidden="true">{{ $index + 1 }}</span>
                            <span class="leading-relaxed text-torns-ink/80">{{ $step }}</span>
                        </li>
                    @endforeach
                </ol>
            </div>
            <div class="rounded-3xl bg-torns-primary p-8 text-center text-white lg:p-12">
                <h2 class="font-display text-2xl font-bold sm:text-3xl">{{ __('Shall we talk?') }}</h2>
                <p class="mx-auto mt-3 max-w-sm leading-relaxed text-white">{{ __('Cuéntanos cómo es tu centro y te explicamos cómo funcionan las licencias y la prueba de 30 días sin tarjeta de crédito.') }}</p>
                <a href="{{ url('contacta') }}" class="mt-8 inline-flex items-center gap-2 rounded-xl bg-white px-6 py-3 text-sm font-semibold text-torns-primary transition hover:bg-torns-bg">
                    {{ __('Contact us') }}
                </a>
            </div>
        </div>
    </div>
</section>
