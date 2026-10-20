@php
    $title = $shortcode->title ?: __('Tu calendario, también fuera de la app');
    $screenExists = fn (string $name) => is_file(public_path("themes/torns/images/home/screens/{$name}-600.webp"));

    // Facts from content/site/pages/funcionalidades.* (Compartir calendario, Imprimir y exportar a PDF).
    $cards = [
        [
            'screen' => 'share-calendar',
            'title' => __('Comparte tu calendario'),
            'text' => __('Torns genera un enlace público de solo lectura a tu calendario de turnos, con contraseña si quieres, para que tu familia o tu pareja vean cuándo trabajas sin instalar nada.'),
            'extra' => __('También puedes exportarlo en formato ICS.'),
            'alt' => __('Calendario público compartido desde Torns'),
        ],
        [
            'screen' => 'export-pdf',
            'title' => __('Imprímelo o expórtalo en PDF'),
            'text' => __('Elige la vista (lista, mensual o anual), el periodo, los tipos de turno y los centros, y exporta tu calendario en PDF para imprimirlo o enviarlo.'),
            'extra' => __('Perfecto para colgarlo en la nevera o pasarlo a quien hace el cuadrante.'),
            'alt' => __('Pantalla de imprimir y exportar el calendario a PDF en Torns'),
        ],
    ];
@endphp

<section class="py-16 lg:py-24" id="compartir">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        {!! Theme::partial('components.section-heading', ['title' => $title, 'eyebrow' => 'Torns Pro', 'align' => 'left']) !!}
        <div class="mt-10 grid gap-6 lg:grid-cols-2">
            @foreach ($cards as $card)
                <article class="grid items-center gap-6 rounded-[2rem] bg-white p-6 sm:grid-cols-[1fr_12rem] sm:p-8">
                    <div>
                        <h3 class="font-display text-2xl font-bold leading-snug">{{ $card['title'] }}</h3>
                        <p class="mt-3 leading-relaxed text-torns-ink/70">{{ $card['text'] }}</p>
                        <p class="mt-3 font-semibold text-torns-primary-dark">{{ $card['extra'] }}</p>
                    </div>
                    @if ($screenExists($card['screen']))
                        {!! Theme::partial('components.phone', [
                            'name' => $card['screen'],
                            'framed' => true,
                            'alt' => $card['alt'],
                            'class' => 'mx-auto w-44 sm:w-full',
                            'sizes' => '12rem',
                        ]) !!}
                    @endif
                </article>
            @endforeach
        </div>
    </div>
</section>
