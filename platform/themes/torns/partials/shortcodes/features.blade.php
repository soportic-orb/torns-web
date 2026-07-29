@php
    $title = $shortcode->title ?: __('Funcionalidades para gestionar tus turnos y tus cambios');
    $subtitle = $shortcode->subtitle ?: __('Todo lo que necesitas para tus cambios de turno y tu calendario, en una sola app.');

    $icon = fn (string $path) => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . $path . '</svg>';

    $features = [
        ['title' => __('Tus turnos'), 'description' => __('Crea los turnos o guardias que necesites cambiar: centro, unidad, tipo de turno, fecha y horario. Tus compañeros verán toda la información.'), 'icon' => $icon('<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>')],
        ['title' => __('Tu disponibilidad'), 'description' => __('Introduce los días en que puedes trabajar (mañana, tarde, noche, 24h…) o importa automáticamente los huecos de tu calendario.'), 'icon' => $icon('<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/>')],
        ['title' => __('Muro de publicaciones'), 'description' => __('Ve todos los turnos publicados por tus compañeros que buscan un cambio. Filtra por centro, unidad o fecha, y prueba la vista calendario.'), 'icon' => $icon('<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/>')],
        ['title' => __('Lugares habituales'), 'description' => __('Guarda tus puestos de trabajo habituales en tu perfil para crear un cambio de turno en segundos.'), 'icon' => $icon('<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="3"/>')],
        ['title' => __('Gestor de planillas'), 'description' => __('Al aceptar un cambio, la app envía un e-mail al gestor de planillas y a los trabajadores con todos los datos del cambio.'), 'icon' => $icon('<path d="M4 4h16v16H4z"/><path d="m4 7 8 6 8-6"/>')],
        ['title' => __('Notificaciones'), 'description' => __('Recibe avisos cuando se publica un cambio, te llega una propuesta o se acepta la tuya. Ya no tendrás que estar pendiente del WhatsApp.'), 'icon' => $icon('<path d="M18 8a6 6 0 1 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 0 1-3.4 0"/>')],
        ['title' => __('Calendario de trabajo'), 'description' => __('Controla los turnos de todos tus centros en tu calendario personal y visualiza también los turnos publicados en el Muro.'), 'icon' => $icon('<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01"/>')],
        ['title' => __('Cambios a 3'), 'description' => __('Copilot te propondrá cadenas de cambios entre 3 o 4 personas cuando detecte la posibilidad. Todos salís ganando.'), 'icon' => $icon('<circle cx="6" cy="6" r="3"/><circle cx="18" cy="6" r="3"/><circle cx="12" cy="18" r="3"/><path d="M8.5 8 11 15m4.5-7L13 15M9 6h6"/>')],
        ['title' => __('Recordatorios'), 'description' => __('Recibe un recordatorio unas horas antes de cada turno. No olvidarás ir a trabajar, ni siquiera en turnos recién cambiados.'), 'icon' => $icon('<circle cx="12" cy="13" r="8"/><path d="M12 9v4l2.5 2.5M5 3 3 5m18 0-2-2"/>')],
        ['title' => __('Multi-centro'), 'description' => __('Únete a todos los centros donde hagas turnos o guardias y gestiona los cambios con tus compañeros desde un único lugar.'), 'icon' => $icon('<path d="M3 21h18M5 21V7l7-4 7 4v14"/><path d="M9 9h1m4 0h1M9 13h1m4 0h1M9 17h1m4 0h1"/>')],
    ];
    $accents = ['morning', 'evening', 'night'];
@endphp

<section class="py-16 lg:py-24" id="funcionalidades">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        {!! Theme::partial('components.section-heading', ['title' => $title, 'subtitle' => $subtitle]) !!}

        <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($features as $index => $feature)
                {!! Theme::partial('components.feature-card', [
                    'title' => $feature['title'],
                    'description' => $feature['description'],
                    'icon' => $feature['icon'],
                    'accent' => $accents[$index % 3],
                ]) !!}
            @endforeach
        </div>
    </div>
</section>
