@php
    $title = $shortcode->title ?: __('Planes y precios de la app de cambios de turno');
    $subtitle = $shortcode->subtitle ?: __('Los planes de suscripción son tan sencillos como la propia aplicación. Tú decides: paga mes a mes sin ataduras o elige el pago anual y ahorra.');
    $plans = torns_pricing_plans();
    $appStoreUrl = theme_option('app_store_url', 'https://apps.apple.com/es/app/torns/id6760354618');

    // Pro Monthly and Pro Annual share the same features: they render as a
    // single "Pro" card whose price follows the billing toggle above the grid.
    $renderCard = fn (array $plan, ?string $displayName = null) => Theme::partial('components.pricing-card', [
        'name' => $displayName ?? $plan['name'],
        'price' => $plan['price'],
        'period' => $plan['period'],
        'features' => $plan['features'],
        'badge' => $plan['badge'],
        'highlighted' => $plan['highlighted'],
        'disabled' => $plan['disabled'],
        'copilot' => ! empty($plan['copilot']),
        'cta_label' => $plan['cta_label'],
        'cta_url' => $appStoreUrl,
        'note' => $plan['description'],
    ]);
@endphp

<section class="bg-white py-16 lg:py-24" id="precios">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        {!! Theme::partial('components.section-heading', ['title' => $title, 'subtitle' => $subtitle, 'eyebrow' => __('Precios')]) !!}

        <input type="radio" id="billing-monthly" name="billing-period" class="billing-radio">
        <input type="radio" id="billing-annual" name="billing-period" class="billing-radio" checked>

        <div class="billing-toggle">
            <label for="billing-monthly">{{ __('Pago mensual') }}</label>
            <label for="billing-annual">
                {{ __('Pago anual') }}
                <span class="billing-toggle__chip">{{ __('Ahorras 2 meses') }}</span>
            </label>
        </div>

        <div class="pricing-grid mx-auto mt-10 grid max-w-3xl items-stretch gap-6 md:grid-cols-2">
            <div class="h-full">{!! $renderCard($plans['free']) !!}</div>
            {{-- Both billing variants render as the highlighted "Pro" card. --}}
            <div class="billing-monthly-card h-full">{!! $renderCard(array_merge($plans['monthly'], ['highlighted' => true]), 'Pro') !!}</div>
            {{-- The toggle already shows the savings chip, so the annual card badge is dropped. --}}
            <div class="billing-annual-card h-full">{!! $renderCard(array_merge($plans['annual'], ['badge' => '']), 'Pro') !!}</div>
        </div>

        <p class="mx-auto mt-8 max-w-2xl text-center text-sm text-torns-ink/70">
            {{ __('Copilot, el asistente de IA para cambios de turno, está incluido en los planes Pro.') }}
        </p>
    </div>
</section>
