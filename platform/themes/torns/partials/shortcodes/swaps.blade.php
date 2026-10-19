@php
    $title = $shortcode->title ?: __('Y cuando necesitas cambiar un turno…');

    // Each row: text + one or two store screenshots (images/home/screens).
    $rows = [
        [
            'eyebrow' => __('Muro de turnos'), 'accent' => 'text-torns-primary',
            'title' => __('Publica el turno que quieres cambiar'),
            'text' => __('Lo ven tus compañeros de centro y de tu misma categoría. El Muro se filtra por centro, unidad y fecha, y los turnos publicados también aparecen en tu calendario.'),
            'extra' => __('Compártelo por WhatsApp como imagen, aunque tu compañero aún no use Torns.'),
            'screens' => [['screen-2896', __('Muro de cambios publicados')], ['screen-2900', __('Detalle de turno publicado por el usuario')]],
        ],
        [
            'eyebrow' => __('Propuestas y cadenas'), 'accent' => 'text-[#5C2FBD]',
            'title' => __('Te proponen un cambio. Y si no hay a dos, a tres'),
            'text' => __('Quien puede cubrirte te ofrece uno de sus turnos. Si no hay un cambio directo, Copilot encuentra cadenas entre 3 o 4 compañeros en las que todos salís ganando.'),
            'extra' => __('Al aceptar, cada turno pasa al calendario de su nuevo titular.'),
            'screens' => [['screen-2894', __('Propuestas de cadenas de turnos')], ['screen-2895', __('Detalle de la propuesta de cadena de turno')]],
        ],
        [
            'eyebrow' => __('Modo SOS'), 'accent' => 'text-[#C2185B]',
            'title' => __('Un turno urgente, arriba del todo'),
            'text' => __('Activa el modo SOS y Torns pide ayuda a los compañeros de tu centro. Tu publicación queda destacada en el Muro.'),
            'extra' => __('Para cuando un imprevisto no puede esperar.'),
            'screens' => [['screen-2901', __('Publicación de un turno SOS urgente')], ['screen-2899', __('Pantalla Cambios con los turnos publicados y su estado')]],
        ],
        [
            'eyebrow' => __('Chat y aviso al gestor'), 'accent' => 'text-[#3F6E2A]',
            'title' => __('Habláis por Torns y el gestor se entera'),
            'text' => __('Mensajería individual y de grupo, sin dar tu teléfono. Cuando se acepta un cambio, quien hace el cuadrante recibe un e-mail con todos los datos.'),
            'extra' => __('Guarda una vez el contacto de tu gestor de planillas.'),
            'screens' => [['screen-2905', __('Sistema de mensajería interno')], ['screen-2897', __('Turno de cobertura publicado por el centro')]],
        ],
    ];
@endphp

<section class="py-16 lg:py-24" id="funcionalidades">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <p class="mb-3 text-sm font-semibold uppercase tracking-widest text-torns-primary">{{ __('Cambios de turno') }}</p>
            <h2 class="font-display text-3xl font-bold leading-tight tracking-tight sm:text-[2.6rem]">{{ $title }}</h2>
        </div>

        <div class="mt-10 flex flex-col gap-6">
            @foreach ($rows as $i => $row)
                <article class="grid items-center gap-8 overflow-hidden rounded-[2rem] bg-white p-6 sm:p-10 lg:grid-cols-2 lg:gap-14 lg:p-12">
                    <div @class(['lg:order-2' => $i % 2 === 1])>
                        <p class="mb-3 text-sm font-bold uppercase tracking-widest {{ $row['accent'] }}">{{ $row['eyebrow'] }}</p>
                        <h3 class="font-display text-2xl font-bold leading-snug sm:text-3xl">{{ $row['title'] }}</h3>
                        <p class="mt-4 text-lg leading-relaxed text-torns-ink/70">{{ $row['text'] }}</p>
                        <p class="mt-4 font-semibold text-torns-primary-dark">{{ $row['extra'] }}</p>
                    </div>
                    <div @class(['flex justify-center gap-4 sm:gap-6', 'lg:order-1' => $i % 2 === 1])>
                        @foreach ($row['screens'] as $j => [$name, $alt])
                            {!! Theme::partial('components.phone', [
                                'name' => $name,
                                'alt' => $alt,
                                'class' => 'w-40 sm:w-52' . ($j === 1 ? ' mt-12' : ''),
                                'sizes' => '(min-width: 640px) 13rem, 10rem',
                            ]) !!}
                        @endforeach
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
