@php
    $title = $shortcode->title ?: __('¿Qué es Torns?');
    $description = $shortcode->description ?: __('Torns es la app para profesionales sanitarios que une en un solo lugar los dos pilares de tu vida laboral: los cambios de turno con tus compañeros y el calendario unificado de todos tus centros de trabajo. Publica el turno que necesitas cambiar, recibe propuestas compatibles con tu disponibilidad y controla toda tu actividad sin salir de la app.');
    $bullets = [];
    for ($i = 1; $i <= 4; $i++) {
        $key = 'bullet_' . $i;
        if ($shortcode->{$key}) {
            $bullets[] = $shortcode->{$key};
        }
    }
    if (! $bullets) {
        $bullets = [
            __('Cambios de turno gestionados entre compañeros del mismo centro'),
            __('Calendario unificado con los turnos de todos tus centros'),
            __('Notificaciones al publicar, recibir o aceptar una propuesta de cambio'),
            __('Sincronización con Apple Calendar y Google Calendar'),
        ];
    }
    $accents = ['morning', 'evening', 'night'];
@endphp

<section class="bg-white py-16 lg:py-24">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-10 lg:grid-cols-2">
            <div>
                {!! Theme::partial('components.section-heading', ['title' => $title, 'align' => 'left']) !!}
                <p class="mt-5 max-w-xl text-base leading-relaxed text-torns-ink/70 sm:text-lg">{{ $description }}</p>
                <p class="mt-4 font-display text-lg font-semibold text-torns-primary">
                    {{ __('With Torns you no longer need one app for your calendar and another one for your swaps.') }}
                </p>
            </div>
            <ul class="flex flex-col gap-4">
                @foreach ($bullets as $index => $bullet)
                    <li class="flex items-start gap-4 rounded-2xl border border-torns-ink/5 bg-torns-bg p-4">
                        <span class="mt-0.5" aria-hidden="true">
                            {!! Theme::partial('components.shift-chip', ['type' => $accents[$index % 3], 'size' => 'sm']) !!}
                        </span>
                        <span class="text-sm font-medium leading-relaxed sm:text-base">{{ $bullet }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>
