@php
    // The management panel of an organization, in screenshots of the real
    // panel (panel.torns.app). Shared by the centres and the associations
    // pages: $mainImage is the large screenshot, $panelFeatures the cards
    // (with an image or text only) and $mobile, when given, the block about
    // the panel installed on the phone.
    $asset = fn (string $base, int $width) => Theme::asset()->url("images/home/{$base}-{$width}.webp");
    $mainImage ??= 'centers-panel';
    $mainAlt ??= __('Panel de gestión de Torns para centros');
    $panelSubtitle ??= __('Tú les ofreces una app que mejora sus cambios de turno. Tú obtienes un panel para gestionar tus centros, publicar turnos a cubrir y recibir los cambios, también desde el móvil.');
    $mobile ??= null;
    $withImage = array_values(array_filter($panelFeatures, fn ($feature) => ! empty($feature['image'])));
    $withoutImage = array_values(array_filter($panelFeatures, fn ($feature) => empty($feature['image'])));
@endphp

<section class="bg-torns-ink py-14 text-white lg:py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <p class="mb-3 text-sm font-semibold uppercase tracking-widest text-shift-morning">{{ __('Integrated management') }}</p>
            <h2 class="font-display text-3xl font-bold tracking-tight sm:text-4xl">{{ $panelTitle }}</h2>
            <p class="mt-4 leading-relaxed text-white/70">{{ $panelSubtitle }}</p>
        </div>

        <figure class="mt-12 overflow-hidden rounded-2xl bg-white shadow-2xl shadow-black/40 ring-1 ring-white/10">
            <div class="flex items-center gap-1.5 bg-torns-deep px-4 py-2.5" aria-hidden="true">
                <span class="h-2.5 w-2.5 rounded-full bg-white/30"></span>
                <span class="h-2.5 w-2.5 rounded-full bg-white/30"></span>
                <span class="h-2.5 w-2.5 rounded-full bg-white/30"></span>
                <span class="ml-3 rounded-md bg-white/10 px-3 py-0.5 text-xs text-white/60">panel.torns.app</span>
            </div>
            <img
                src="{{ $asset($mainImage, 1600) }}"
                srcset="{{ $asset($mainImage, 800) }} 800w, {{ $asset($mainImage, 1600) }} 1600w"
                sizes="(min-width: 1152px) 72rem, 92vw"
                alt="{{ $mainAlt }}"
                class="block w-full"
                width="1600"
                height="870"
                loading="lazy"
            >
        </figure>

        <div class="mt-6 grid gap-6 md:grid-cols-2">
            @foreach ($withImage as $feature)
                <article class="overflow-hidden rounded-3xl bg-white/5 ring-1 ring-white/10">
                    <img
                        src="{{ $asset($feature['image'], 800) }}"
                        srcset="{{ $asset($feature['image'], 800) }} 800w, {{ $asset($feature['image'], 1600) }} 1600w"
                        sizes="(min-width: 768px) 34rem, 92vw"
                        alt="{{ $feature['title'] }}"
                        class="block aspect-[16/11] w-full object-contain px-5 pt-5"
                        width="800"
                        height="550"
                        loading="lazy"
                    >
                    <div class="p-6 pt-5">
                        <h3 class="font-display text-lg font-semibold">{{ $feature['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-white/70">{{ $feature['description'] }}</p>
                    </div>
                </article>
            @endforeach
        </div>

        @if ($mobile)
            <div class="mt-6 grid items-center gap-8 rounded-3xl bg-white/5 p-6 ring-1 ring-white/10 sm:p-10 lg:grid-cols-2 lg:gap-12 lg:p-12">
                <div>
                    <p class="mb-3 text-sm font-semibold uppercase tracking-widest text-shift-morning">{{ $mobile['eyebrow'] }}</p>
                    <h3 class="font-display text-2xl font-bold tracking-tight sm:text-3xl">{{ $mobile['title'] }}</h3>
                    <p class="mt-4 leading-relaxed text-white/70">{{ $mobile['text'] }}</p>
                    <ul class="mt-6 flex flex-col gap-3">
                        @foreach ($mobile['points'] as $point)
                            <li class="flex items-start gap-3 text-sm leading-relaxed text-white/80">
                                <svg class="mt-0.5 h-5 w-5 shrink-0 text-shift-morning" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12 5 5L20 7"/></svg>
                                <span>{{ $point }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <img
                    src="{{ $asset($mobile['image'], 1200) }}"
                    srcset="{{ $asset($mobile['image'], 600) }} 600w, {{ $asset($mobile['image'], 1200) }} 1200w"
                    sizes="(min-width: 1024px) 32rem, 88vw"
                    alt="{{ $mobile['title'] }}"
                    class="mx-auto block w-full max-w-md"
                    width="1200"
                    height="946"
                    loading="lazy"
                >
            </div>
        @endif

        @if ($withoutImage)
            <div @class(['mt-6 grid gap-6', 'md:grid-cols-3' => count($withoutImage) === 3, 'md:grid-cols-2' => count($withoutImage) !== 3])>
                @foreach ($withoutImage as $feature)
                    <div class="rounded-2xl bg-white/5 p-6 ring-1 ring-white/10">
                        <h3 class="font-display text-lg font-semibold">{{ $feature['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-white/70">{{ $feature['description'] }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
