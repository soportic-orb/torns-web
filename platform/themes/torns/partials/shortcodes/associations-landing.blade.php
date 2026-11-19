@php
    // Torns for professional associations: unions, professional colleges and
    // scientific societies that sponsor Torns Pro for their members. Facts
    // from content/site/markdown/funcionalidades.*.md («Asociaciones
    // profesionales») and the association panel (panel.torns.app/asociacion).
    $check = '<svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12 5 5L20 7"/></svg>';

    $forEntity = [
        [__('Un beneficio que se usa cada semana'), __('Da valor a la colegiación, la afiliación o la cuota más allá del carné.')],
        [__('Atrae y fideliza'), __('Un argumento concreto para nuevas altas, sobre todo entre los profesionales más jóvenes.')],
        [__('Tú decides cuánto cubres'), __('Patrocinio total, con Torns Pro sin coste para el asociado, o parcial.')],
        [__('Coste cerrado'), __('Un contrato anual por número de plazas, con un presupuesto a medida.')],
        [__('Sin trámites'), __('Un código para tus asociados o vuestra lista de correos: Torns los reconoce al registrarse.')],
        [__('Tu propio panel'), __('Altas, plazas, códigos, equipo y contrato, siempre a la vista.')],
    ];
    $forMembers = [
        [__('Torns Pro, sin coste o con descuento'), __('Según el patrocinio que acordéis con Torns.')],
        [__('Todos sus turnos y guardias en un calendario'), __('De todos sus centros, con el cuadrante importado con una foto.')],
        [__('Cambios de turno ilimitados'), __('Con compañeros de su centro y su categoría, cadenas de cambios y modo SOS.')],
        [__('Consultor legal'), __('Respuestas a sus dudas laborales basadas en documentos legales revisados, con la fuente citada.')],
        [__('Sus horas, contadas'), __('Estadísticas de trabajo, horas anuales y el calendario en PDF.')],
        [__('Copilot'), __('Le pide a Torns lo que necesita, como a un compañero.')],
    ];

    $panelFeatures = [
        ['title' => __('Códigos y registro de correos'), 'description' => __('Vuestros códigos con su patrocinio y sus usos. Sube un CSV con los correos de tus asociados: quien ya tiene cuenta recibe un aviso con la oferta y el resto la verá al registrarse.'), 'image' => 'associations-panel-codes'],
        ['title' => __('Tu equipo, con su rol'), 'description' => __('Invita como propietario o gestor a las personas que llevan el acuerdo, y retira el acceso cuando haga falta.'), 'image' => 'associations-panel-contacts'],
        ['title' => __('Plazas de un vistazo'), 'description' => __('Socios activos, plazas contratadas y plazas libres, y los datos de facturación de la entidad.'), 'image' => 'associations-panel-overview'],
        ['title' => __('Vuestro contrato'), 'description' => __('Plazas, importe y periodo. Se renueva solo y podéis no renovarlo hasta 30 días antes.')],
        ['title' => __('Soporte personalizado, del bueno'), 'description' => __('Soporte técnico y comercial integrado mediante tickets, para clientes, gestores y usuarios.')],
    ];

    $steps = [
        __('Acordamos el patrocinio: el número de plazas y si lo cubrís entero o en parte, con un contrato anual.'),
        __('Torns crea vuestros códigos y os da acceso al panel de la asociación.'),
        __('Lo comunicáis a vuestros asociados con el código, o subís vuestra lista de correos.'),
        __('Se registran en la app y ya tienen Torns Pro. Vosotros veis las altas y las plazas en el panel.'),
    ];
    $contactUrl = url(torns_current_locale() === 'es' ? 'contacta' : torns_current_locale() . '/contacta');
@endphp

<section class="bg-white py-14 lg:py-20">
    <div class="mx-auto grid max-w-6xl items-center gap-10 px-4 sm:px-6 lg:grid-cols-2 lg:gap-16 lg:px-8">
        <div>
            <p class="mb-3 text-sm font-semibold uppercase tracking-widest text-torns-primary">{{ __('Un servicio que se nota') }}</p>
            <h2 class="font-display text-2xl font-bold tracking-tight [text-wrap:balance] sm:text-3xl">{{ __('Lo que más pesa a quien trabaja a turnos, resuelto en el móvil') }}</h2>
            <p class="mt-4 leading-relaxed text-torns-ink/70">{{ __('Cuadrantes en papel, varios centros, cambios de turno por grupos de WhatsApp y dudas sobre descansos o guardias que nadie responde a tiempo. Es el día a día de enfermeras, médicos, TCAE, TES y el resto de profesionales sanitarios.') }}</p>
            <p class="mt-3 leading-relaxed text-torns-ink/70">{{ __('Sindicatos, colegios profesionales y sociedades científicas pueden poner en sus manos una herramienta que usan cada semana: un beneficio visible, con vuestro nombre detrás.') }}</p>
        </div>
        <div class="rounded-2xl bg-torns-bg p-6 lg:p-8">
            <h3 class="font-display text-lg font-semibold">{{ __('Así de simple para tus asociados') }}</h3>
            <ul class="mt-4 flex flex-col gap-3 leading-relaxed text-torns-ink/80">
                <li class="flex gap-3 text-torns-primary">{!! $check !!}<span class="text-torns-ink/80">{{ __('Se registran en Torns con vuestro código o con el correo que tenéis en vuestro registro.') }}</span></li>
                <li class="flex gap-3 text-torns-primary">{!! $check !!}<span class="text-torns-ink/80">{{ __('Con el patrocinio total usan Torns Pro sin coste; con el parcial, con descuento.') }}</span></li>
                <li class="flex gap-3 text-torns-primary">{!! $check !!}<span class="text-torns-ink/80">{{ __('En iPhone, Android, Apple Watch y el navegador.') }}</span></li>
            </ul>
        </div>
    </div>
</section>

<section class="py-14 lg:py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        {!! Theme::partial('components.section-heading', [
            'eyebrow' => __('Ventajas'),
            'title' => __('Ellos ganan. Tu asociación gana.'),
            'subtitle' => __('Un acuerdo sencillo: tus asociados tienen Torns Pro y tú, un servicio diferencial con el coste cerrado.'),
        ]) !!}
        <div class="mt-12 grid gap-5 lg:grid-cols-2">
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-torns-ink/5 sm:p-8">
                <h3 class="font-display text-xl font-bold">{{ __('Para tu asociación') }}</h3>
                <ul class="mt-4 divide-y divide-torns-ink/5">
                    @foreach ($forEntity as [$title, $text])
                        <li class="flex gap-3 py-3.5 text-torns-primary">
                            {!! $check !!}
                            <div><p class="font-semibold text-torns-ink">{{ $title }}</p><p class="mt-0.5 text-sm leading-relaxed text-torns-ink/70">{{ $text }}</p></div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="rounded-3xl bg-torns-primary p-6 text-white sm:p-8">
                <h3 class="font-display text-xl font-bold">{{ __('Para tus colegiados, afiliados y socios') }}</h3>
                <ul class="mt-4 divide-y divide-white/15">
                    @foreach ($forMembers as [$title, $text])
                        <li class="flex gap-3 py-3.5">
                            {!! $check !!}
                            <div><p class="font-semibold">{{ $title }}</p><p class="mt-0.5 text-sm leading-relaxed text-white/85">{{ $text }}</p></div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>

{!! Theme::partial('shortcodes.centers.panel', [
    'panelTitle' => __('Tu panel de gestión'),
    'panelSubtitle' => __('Tu asociación tiene su propio panel en panel.torns.app para ver quién se ha dado de alta, cuántas plazas quedan y quién de tu equipo puede entrar.'),
    'panelFeatures' => $panelFeatures,
    'mainImage' => 'associations-panel',
    'mainAlt' => __('Panel de gestión de Torns para asociaciones profesionales'),
]) !!}

<section class="py-14 lg:py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
            <div>
                <h2 class="font-display text-2xl font-bold tracking-tight sm:text-3xl">{{ __('¿Cómo funciona?') }}</h2>
                <ol class="mt-6 flex flex-col gap-4">
                    @foreach ($steps as $index => $step)
                        <li class="flex items-start gap-4">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-torns-primary font-display text-sm font-bold text-white" aria-hidden="true">{{ $index + 1 }}</span>
                            <span class="leading-relaxed text-torns-ink/80">{{ $step }}</span>
                        </li>
                    @endforeach
                </ol>
            </div>
            <div class="rounded-3xl bg-torns-primary p-8 text-center text-white lg:p-12">
                <h2 class="font-display text-2xl font-bold sm:text-3xl">{{ __('Shall we talk?') }}</h2>
                <p class="mx-auto mt-3 max-w-sm leading-relaxed text-white">{{ __('Cuéntanos cuántos colegiados, afiliados o socios tenéis y te preparamos una propuesta a medida.') }}</p>
                <a href="{{ $contactUrl }}" class="mt-8 inline-flex items-center gap-2 rounded-xl bg-white px-6 py-3 text-sm font-semibold text-torns-primary transition hover:bg-torns-bg">
                    {{ __('Pide una propuesta') }}
                </a>
            </div>
        </div>
    </div>
</section>
