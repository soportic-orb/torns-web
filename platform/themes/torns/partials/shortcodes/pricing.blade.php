@php
    $title = $shortcode->title ?: 'Planes y precios de la app de cambios de turno';
    $subtitle = $shortcode->subtitle ?: 'Los planes de suscripción son tan sencillos como la propia aplicación. Tú decides: paga mes a mes sin ataduras o elige el pago anual y ahorra.';
    $plans = torns_pricing_plans();
    $appStoreUrl = theme_option('app_store_url', 'https://apps.apple.com/es/app/torns/id6760354618');
@endphp

<section class="bg-white py-16 lg:py-24" id="precios">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        {!! Theme::partial('components.section-heading', ['title' => $title, 'subtitle' => $subtitle]) !!}

        <div class="mt-14 grid items-stretch gap-6 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($plans as $plan)
                {!! Theme::partial('components.pricing-card', [
                    'name' => $plan['name'],
                    'price' => $plan['price'],
                    'period' => $plan['period'],
                    'features' => $plan['features'],
                    'badge' => $plan['badge'],
                    'highlighted' => $plan['highlighted'],
                    'disabled' => $plan['disabled'],
                    'cta_label' => $plan['cta_label'],
                    'cta_url' => $appStoreUrl,
                    'note' => $plan['description'],
                ]) !!}
            @endforeach
        </div>
    </div>
</section>
