@php
    $title = $shortcode->title ?: __('Planes y precios de la app de cambios de turno');
    $subtitle = $shortcode->subtitle ?: __('Los planes de suscripción son tan sencillos como la propia aplicación. Tú decides: paga mes a mes sin ataduras o elige el pago anual y ahorra.');
    $plans = torns_pricing_plans();
    $appStoreUrl = theme_option('app_store_url', 'https://apps.apple.com/es/app/torns/id6760354618');
@endphp

<section class="bg-white py-16 lg:py-24" id="precios">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        {!! Theme::partial('components.section-heading', ['title' => $title, 'subtitle' => $subtitle, 'eyebrow' => __('Precios')]) !!}

        <div class="mx-auto mt-14 grid max-w-5xl items-stretch gap-6 md:grid-cols-3">
            @foreach ($plans as $plan)
                {!! Theme::partial('components.pricing-card', [
                    'name' => $plan['name'],
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
                ]) !!}
            @endforeach
        </div>

        <p class="mx-auto mt-8 max-w-2xl text-center text-sm text-torns-ink/70">
            {{ __('Copilot, el asistente de IA para cambios de turno, está incluido en los planes Pro.') }}
        </p>
    </div>
</section>
