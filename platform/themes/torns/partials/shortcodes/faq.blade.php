@php
    $title = $shortcode->title ?: 'Preguntas frecuentes';

    $items = [];
    for ($i = 1; $i <= 10; $i++) {
        if ($shortcode->{'question_' . $i} && $shortcode->{'answer_' . $i}) {
            $items[] = ['question' => $shortcode->{'question_' . $i}, 'answer' => $shortcode->{'answer_' . $i}];
        }
    }

    if (! $items) {
        $items = [
            ['question' => '¿Qué es Torns?', 'answer' => 'Torns es una aplicación móvil para iOS y Android que permite a los profesionales sanitarios gestionar cambios de turno con sus compañeros del mismo centro de trabajo y controlar su calendario de guardias y turnos de todos sus centros.'],
            ['question' => '¿Cómo funciona la app para cambios de turno?', 'answer' => 'Publicas el turno que necesitas cambiar, tus compañeros reciben una notificación, te proponen un intercambio compatible con su disponibilidad y, al aceptar, se notifica automáticamente al gestor de planillas.'],
            ['question' => '¿Puedo gestionar mi calendario de turnos en Torns?', 'answer' => 'Sí. Torns no solo te permite gestionar cambios de guardia con tus compañeros: es una completa herramienta de gestión de tu calendario laboral. Puedes introducir tus turnos, sincronizarlos con Apple Calendar o Google Calendar y mantener tu agenda totalmente al día.'],
            ['question' => '¿Cómo me puedo registrar en Torns?', 'answer' => 'Puedes registrarte con tu e-mail personal, con tus cuentas de Google o Apple, o con la cuenta corporativa de Microsoft de tu centro.'],
            ['question' => '¿Cuánto cuesta Torns?', 'answer' => 'Puedes crear una cuenta gratis sin tarjeta de crédito y usar el plan Free. El plan Pro cuesta 2,99 €/mes o 29,90 €/año (con el pago anual ahorras 2 meses).'],
            ['question' => '¿Para qué profesionales sanitarios sirve Torns?', 'answer' => 'Torns está pensada para enfermeras, médicos, TCAE, auxiliares, celadores, matronas, fisioterapeutas, administrativos sanitarios, técnicos en emergencias sanitarias (TES) y cualquier profesional sanitario que trabaje por turnos o guardias.'],
            ['question' => '¿En qué idiomas está disponible Torns?', 'answer' => 'Torns está disponible en castellano, català, galego y euskara.'],
            ['question' => '¿En qué se diferencia Torns de otras apps de turnos como aTurnos, Shiftool, Supershift o TurnoClip?', 'answer' => 'Torns combina en una sola app los cambios de turno gestionados entre compañeros del mismo centro y un calendario unificado multi-centro con sincronización Apple/Google Calendar, sin que la empresa tenga que contratar nada. aTurnos es un software de planificación que contrata la empresa; Supershift es un calendario personal sin intercambio de turnos entre compañeros; y a ello Torns añade el SOS de cambios urgentes, la notificación automática al gestor de planillas, chat integrado y los 4 idiomas ES/CA/GL/EU. Puedes ver la comparativa objetiva completa más arriba.'],
        ];
    }
@endphp

<section class="py-16 lg:py-24" id="faq">
    <div class="mx-auto max-w-3xl px-4 sm:px-6">
        {!! Theme::partial('components.section-heading', ['title' => $title]) !!}

        <div class="mt-10 flex flex-col gap-3">
            @foreach ($items as $index => $item)
                {!! Theme::partial('components.faq-item', [
                    'question' => $item['question'],
                    'answer' => e($item['answer']),
                    'open' => $index === 0,
                ]) !!}
            @endforeach
        </div>
    </div>
</section>
