@php
    $title = $shortcode->title ?: '¿Te han dado un código de centro?';
    $description = $shortcode->description ?: 'Si tu centro de trabajo, tu colegio profesional o tus compañeros te han dado un código de centro, introdúcelo en la pantalla de inicio de la app: te registrarás gratis en segundos y te unirás directamente a tus compañeros.';
    $image = $shortcode->image ? RvMedia::getImageUrl($shortcode->image) : Theme::asset()->url('images/home/center-code-mockup-416.webp');
@endphp

<section class="py-16 lg:py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-10 overflow-hidden rounded-3xl bg-torns-primary lg:grid-cols-[1fr_auto]">
            <div class="p-8 text-white sm:p-12">
                <h2 class="font-display text-2xl font-bold tracking-tight sm:text-3xl">{{ $title }}</h2>
                <p class="mt-4 max-w-xl leading-relaxed text-white">{{ $description }}</p>
                <div class="mt-8">
                    {!! Theme::partial('components.store-badges', ['size' => 'sm']) !!}
                </div>
            </div>
            <div class="hidden items-end pr-12 lg:flex">
                <img src="{{ $image }}" alt="{{ __('Center code screen in the Torns app') }}" class="-mb-16 w-52 drop-shadow-2xl" width="416" height="905" loading="lazy">
            </div>
        </div>
    </div>
</section>
