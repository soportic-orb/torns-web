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
            // Real exports (backend services/pdf_calendar.py) of a sample calendar: annual, list and monthly.
            'sheets' => 'pdf-exports',
            'sheets_alt' => __('PDF del calendario de Torns en vista anual, de lista y mensual'),
        ],
    ];
@endphp

<section class="py-16 lg:py-24" id="compartir">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        {!! Theme::partial('components.section-heading', ['title' => $title, 'eyebrow' => 'Torns Pro', 'align' => 'left']) !!}
        <div class="mt-10 grid gap-6 lg:grid-cols-2">
            @foreach ($cards as $card)
                <article @class(['grid items-center gap-6 rounded-[2rem] bg-white p-6 sm:p-8', 'sm:grid-cols-[1fr_12rem]' => empty($card['sheets']), 'xl:grid-cols-[1fr_17rem]' => ! empty($card['sheets'])])>
                    <div>
                        <h3 class="font-display text-2xl font-bold leading-snug">{{ $card['title'] }}</h3>
                        <p class="mt-3 leading-relaxed text-torns-ink/70">{{ $card['text'] }}</p>
                        <p class="mt-3 font-semibold text-torns-primary-dark">{{ $card['extra'] }}</p>
                    </div>
                    @if (! empty($card['sheets']))
                        {{-- The exported PDFs, with the export screen in front. --}}
                        <div class="relative mx-auto w-full max-w-[22rem] pb-10 xl:max-w-none">
                            <img
                                src="{{ torns_asset("images/home/{$card['sheets']}-1200.webp") }}"
                                srcset="{{ torns_asset("images/home/{$card['sheets']}-600.webp") }} 600w, {{ torns_asset("images/home/{$card['sheets']}-1200.webp") }} 1200w"
                                sizes="(min-width: 1280px) 17rem, 22rem"
                                alt="{{ $card['sheets_alt'] }}"
                                class="block h-auto w-full"
                                width="1200"
                                height="1174"
                                loading="lazy"
                            >
                            @if ($screenExists($card['screen']))
                                {!! Theme::partial('components.phone', [
                                    'name' => $card['screen'],
                                    'alt' => $card['alt'],
                                    'tilt' => 'right',
                                    'class' => 'absolute -bottom-3 -right-2 w-[40%]',
                                    'sizes' => '8rem',
                                ]) !!}
                            @endif
                        </div>
                    @elseif ($screenExists($card['screen']))
                        {!! Theme::partial('components.phone', [
                            'name' => $card['screen'],
                            'alt' => $card['alt'],
                            'tilt' => $loop->iteration === 2 ? 'right' : 'left',
                            'class' => 'mx-auto w-40 sm:w-[11rem]',
                            'sizes' => '12rem',
                        ]) !!}
                    @endif
                </article>
            @endforeach
        </div>
    </div>
</section>
