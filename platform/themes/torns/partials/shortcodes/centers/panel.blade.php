@php
    $asset = fn (string $base, int $width) => Theme::asset()->url("images/home/{$base}-{$width}.webp");
    $withImage = array_values(array_filter($panelFeatures, fn ($feature) => ! empty($feature['image'])));
    $withoutImage = array_values(array_filter($panelFeatures, fn ($feature) => empty($feature['image'])));
@endphp

<section class="bg-torns-ink py-14 text-white lg:py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <p class="mb-3 text-sm font-semibold uppercase tracking-widest text-shift-morning">{{ __('Integrated management') }}</p>
            <h2 class="font-display text-3xl font-bold tracking-tight sm:text-4xl">{{ $panelTitle }}</h2>
            <p class="mt-4 leading-relaxed text-white/70">{{ __('Tú les ofreces una app que mejora sus cambios de turno. Tú obtienes un panel para gestionar tus centros, publicar coberturas y recibir los cambios.') }}</p>
        </div>

        <figure class="mt-12 overflow-hidden rounded-2xl bg-white shadow-2xl shadow-black/40 ring-1 ring-white/10">
            <div class="flex items-center gap-1.5 bg-torns-deep px-4 py-2.5" aria-hidden="true">
                <span class="h-2.5 w-2.5 rounded-full bg-white/30"></span>
                <span class="h-2.5 w-2.5 rounded-full bg-white/30"></span>
                <span class="h-2.5 w-2.5 rounded-full bg-white/30"></span>
            </div>
            <img
                src="{{ $asset('centers-panel', 1600) }}"
                srcset="{{ $asset('centers-panel', 800) }} 800w, {{ $asset('centers-panel', 1600) }} 1600w"
                sizes="(min-width: 1152px) 72rem, 92vw"
                alt="{{ __('Panel de gestión de Torns para centros') }}"
                class="block w-full"
                width="1600"
                height="870"
                loading="lazy"
            >
        </figure>

        <div class="mt-14 grid gap-6 md:grid-cols-2">
            @foreach ($withImage as $feature)
                <article class="overflow-hidden rounded-3xl bg-white/5">
                    <img
                        src="{{ $asset($feature['image'], 800) }}"
                        srcset="{{ $asset($feature['image'], 800) }} 800w, {{ $asset($feature['image'], 1600) }} 1600w"
                        sizes="(min-width: 768px) 34rem, 92vw"
                        alt="{{ $feature['title'] }}"
                        class="block aspect-[16/11] w-full bg-gradient-to-b from-white/10 to-transparent object-contain px-6 pt-6"
                        width="800"
                        height="550"
                        loading="lazy"
                    >
                    <div class="p-6 pt-4">
                        <h3 class="font-display text-lg font-semibold">{{ $feature['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-white/70">{{ $feature['description'] }}</p>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-6 grid gap-6 md:grid-cols-2">
            @foreach ($withoutImage as $feature)
                <div class="rounded-2xl bg-white/5 p-6">
                    <h3 class="font-display text-lg font-semibold">{{ $feature['title'] }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-white/70">{{ $feature['description'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
