<?php

use Illuminate\Support\Str;

if (! function_exists('torns_pricing_plans')) {
    /**
     * Pricing plans, editable from Theme options (Torns → Pricing).
     * Features are stored one per line.
     */
    function torns_pricing_plans(): array
    {
        $plans = [
            'free' => [
                'name' => 'Free',
                'price' => '0 €',
                'period' => '',
                'description' => __('Utiliza gratis las funcionalidades básicas de la app'),
                'features' => __("Gestión de 1 centro de trabajo\nCalendario de turnos integrado\nMuro de turnos publicados\nPublicar cambios ilimitados\nAceptar 2 cambios al mes\nChat individual y grupal\nSincronización con Apple y Google"),
                'badge' => '',
                'cta_label' => __('Empieza gratis'),
                'highlighted' => false,
                'disabled' => false,
            ],
            'monthly' => [
                'name' => __('Pro Mensual'),
                'price' => '2,99 €',
                'period' => __('/mes'),
                'description' => __('Acceso mensual a todas las funcionalidades Pro. Cancela cuando quieras.'),
                'features' => __("Todas las funcionalidades del plan Free\nCopilot: cadenas de cambios a 3 y 4 con IA\nCentros de trabajo ilimitados\nAceptar turnos ilimitados\nPropuestas de cambios a 3\nRecordatorios de turnos\nNotificar cambios al gestor\nPublicar SOS cambio urgente\nEstadísticas de trabajo\nCompartir calendario de turnos"),
                'badge' => '',
                'cta_label' => __('Hazte Pro'),
                'highlighted' => false,
                'disabled' => false,
                'copilot' => true,
            ],
            'annual' => [
                'name' => __('Pro Anual'),
                'price' => '29,90 €',
                'period' => __('/año'),
                'description' => __('Acceso anual a todas las funcionalidades Pro.'),
                'features' => __("Todas las funcionalidades del plan Free\nCopilot: cadenas de cambios a 3 y 4 con IA\nCentros de trabajo ilimitados\nAceptar turnos ilimitados\nPropuestas de cambios a 3\nRecordatorios de turnos\nNotificar cambios al gestor\nPublicar SOS cambio urgente\nEstadísticas de trabajo\nCompartir calendario de turnos"),
                'badge' => __('Ahorras 2 meses'),
                'cta_label' => __('Hazte Pro'),
                'highlighted' => true,
                'disabled' => false,
                'copilot' => true,
            ],
        ];

        foreach ($plans as $key => $defaults) {
            foreach ($defaults as $field => $default) {
                $value = theme_option("pricing_{$key}_{$field}");
                if ($value !== null && $value !== '') {
                    $plans[$key][$field] = in_array($field, ['highlighted', 'disabled'], true)
                        ? in_array(strtolower((string) $value), ['1', 'yes', 'true'], true)
                        : $value;
                }
            }
            $plans[$key]['features'] = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) $plans[$key]['features']))));
        }

        return $plans;
    }
}

if (! function_exists('torns_comparison_data')) {
    /**
     * Comparison table vs other shift apps, editable from Theme options
     * (Torns → Comparison). Cell values: "yes", "no", "-" (no public data)
     * or free text (shown verbatim, e.g. "Pro").
     *
     * Sources for every default cell are documented in
     * docs/comparison-verification.md — update that file when editing here.
     */
    function torns_comparison_data(): array
    {
        $competitors = [
            'torns' => __('Torns'),
            'aturnos' => __('aTurnos'),
            'shiftool' => __('Shiftool'),
            'supershift' => __('Supershift'),
            'turnoclip' => __('TurnoClip'),
        ];

        $defaultRows = [
            ['feature' => __('Enfoque'), 'torns' => __('App individual: cambios + calendario'), 'aturnos' => __('Planificación B2B para empresas'), 'shiftool' => __('App individual de cambios de turno'), 'supershift' => __('Calendario de turnos personal'), 'turnoclip' => __('Calendario personal con tablón de cambios')],
            ['feature' => __('Cambios de turno gestionados entre compañeros'), 'torns' => 'yes', 'aturnos' => __('Si la empresa lo habilita'), 'shiftool' => 'yes', 'supershift' => 'no', 'turnoclip' => 'yes'],
            ['feature' => __('Calendario multi-centro unificado'), 'torns' => 'yes', 'aturnos' => '-', 'shiftool' => 'yes', 'supershift' => 'yes', 'turnoclip' => '-'],
            ['feature' => __('Sin necesidad de que la empresa lo contrate'), 'torns' => 'yes', 'aturnos' => 'no', 'shiftool' => 'yes', 'supershift' => 'yes', 'turnoclip' => 'yes'],
            ['feature' => __('Cadenas de cambios a 3-4 con IA (Copilot)'), 'torns' => 'yes', 'aturnos' => '-', 'shiftool' => '-', 'supershift' => 'no', 'turnoclip' => '-'],
            ['feature' => __('SOS de cambio urgente al centro'), 'torns' => 'yes', 'aturnos' => '-', 'shiftool' => '-', 'supershift' => 'no', 'turnoclip' => '-'],
            ['feature' => __('Notificación automática al gestor de planillas'), 'torns' => 'yes', 'aturnos' => __('Validación por admin'), 'shiftool' => 'yes', 'supershift' => 'no', 'turnoclip' => __('PDF de entrega manual')],
            ['feature' => __('Sincronización Apple/Google Calendar'), 'torns' => 'yes', 'aturnos' => '-', 'shiftool' => '-', 'supershift' => 'Pro', 'turnoclip' => '-'],
            ['feature' => __('Chat individual y grupal'), 'torns' => 'yes', 'aturnos' => '-', 'shiftool' => '-', 'supershift' => 'no', 'turnoclip' => '-'],
            ['feature' => __('Idiomas ES / CA / GL / EU'), 'torns' => 'yes', 'aturnos' => 'no', 'shiftool' => 'no', 'supershift' => 'no', 'turnoclip' => '-'],
        ];

        $rows = $defaultRows;

        $stored = theme_option('comparison_rows');
        if ($stored) {
            $decoded = json_decode((string) $stored, true);
            if (is_array($decoded) && $decoded) {
                $normalized = [];
                foreach ($decoded as $item) {
                    // RepeaterField stores each row as a list of field objects.
                    if (isset($item[0]) && is_array($item[0])) {
                        $row = [];
                        foreach ($item as $field) {
                            if (isset($field['key'])) {
                                $row[$field['key']] = $field['value'] ?? '';
                            }
                        }
                        $item = $row;
                    }
                    if (! empty($item['feature'])) {
                        $normalized[] = $item + ['torns' => '-', 'aturnos' => '-', 'shiftool' => '-', 'supershift' => '-', 'turnoclip' => '-'];
                    }
                }
                if ($normalized) {
                    $rows = $normalized;
                }
            }
        }

        return [
            'competitors' => $competitors,
            'rows' => $rows,
            'reviewed_at' => theme_option('comparison_reviewed_at', '29/07/2026'),
        ];
    }
}

if (! function_exists('torns_default_faq_items')) {
    /**
     * Default FAQ entries, shared by the [torns-faq] shortcode and the
     * FAQPage JSON-LD schema.
     */
    function torns_default_faq_items(): array
    {
        return [
            ['question' => __('¿Qué es Torns?'), 'answer' => __('Torns es una aplicación móvil para iOS y Android que permite a los profesionales sanitarios gestionar cambios de turno con sus compañeros del mismo centro de trabajo y controlar su calendario de guardias y turnos de todos sus centros.')],
            ['question' => __('¿Cómo funciona la app para cambios de turno?'), 'answer' => __('Publicas el turno que necesitas cambiar, tus compañeros reciben una notificación, te proponen un intercambio compatible con su disponibilidad y, al aceptar, se notifica automáticamente al gestor de planillas.')],
            ['question' => __('¿Puedo gestionar mi calendario de turnos en Torns?'), 'answer' => __('Sí. Torns no solo te permite gestionar cambios de guardia con tus compañeros: es una completa herramienta de gestión de tu calendario laboral. Puedes introducir tus turnos, sincronizarlos con Apple Calendar o Google Calendar y mantener tu agenda totalmente al día.')],
            ['question' => __('¿Cómo me puedo registrar en Torns?'), 'answer' => __('Puedes registrarte con tu e-mail personal, con tus cuentas de Google o Apple, o con la cuenta corporativa de Microsoft de tu centro.')],
            ['question' => __('¿Cuánto cuesta Torns?'), 'answer' => __('Puedes crear una cuenta gratis sin tarjeta de crédito y usar el plan Free. El plan Pro cuesta 2,99 €/mes o 29,90 €/año (con el pago anual ahorras 2 meses) e incluye Copilot, el asistente de IA para cambios de turno.')],
            ['question' => __('¿Para qué profesionales sanitarios sirve Torns?'), 'answer' => __('Torns está pensada para enfermeras, médicos, TCAE, auxiliares, celadores, matronas, fisioterapeutas, administrativos sanitarios, técnicos en emergencias sanitarias (TES) y cualquier profesional sanitario que trabaje por turnos o guardias.')],
            ['question' => __('¿En qué idiomas está disponible Torns?'), 'answer' => __('Torns está disponible en castellano, català, galego y euskara.')],
            ['question' => __('¿En qué se diferencia Torns de otras apps de turnos como aTurnos, Shiftool, Supershift o TurnoClip?'), 'answer' => __('Torns combina en una sola app los cambios de turno gestionados entre compañeros del mismo centro y un calendario unificado multi-centro con sincronización Apple/Google Calendar, sin que la empresa tenga que contratar nada. aTurnos es un software de planificación que contrata la empresa; Supershift es un calendario personal sin intercambio de turnos entre compañeros; y a ello Torns añade el SOS de cambios urgentes, la notificación automática al gestor de planillas, chat integrado y los 4 idiomas ES/CA/GL/EU. Puedes ver la comparativa objetiva completa más arriba.')],
        ];
    }
}

if (! function_exists('torns_comparison_cell')) {
    /**
     * Render a comparison cell value as accessible HTML.
     */
    function torns_comparison_cell(string $value, bool $isTorns = false): string
    {
        $value = trim($value);
        $normalized = Str::lower($value);

        if (in_array($normalized, ['yes', 'si', 'sí', '1', 'true'], true)) {
            $color = $isTorns ? 'text-torns-primary' : 'text-torns-ink/70';

            return '<span class="inline-flex items-center justify-center ' . $color . '" role="img" aria-label="' . e(__('Yes')) . '">'
                . '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 13 4 4L19 7"/></svg>'
                . '</span>';
        }

        if (in_array($normalized, ['no', '0', 'false'], true)) {
            return '<span class="inline-flex items-center justify-center text-torns-ink/30" role="img" aria-label="' . e(__('No')) . '">'
                . '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>'
                . '</span>';
        }

        if (in_array($normalized, ['-', '—', ''], true)) {
            return '<span class="text-torns-ink/70" aria-label="' . e(__('No public data')) . '">—</span>';
        }

        return '<span class="text-xs font-medium leading-snug text-torns-ink/70">' . e($value) . '</span>';
    }
}
