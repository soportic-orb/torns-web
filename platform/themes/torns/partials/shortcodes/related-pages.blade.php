@php
    // Cards linking to other pages of the site: pages="key,key" (keys of
    // content/site/pages.json), each with its name and SEO description in
    // the current language.
    $locale = torns_current_locale();
    $prefix = $locale === 'es' ? '' : $locale . '/';
    $definitions = rescue(fn () => app(\App\Support\SiteContent::class)->definitions()['pages'], [], false);

    $cards = [];
    foreach (array_filter(array_map('trim', explode(',', (string) $shortcode->pages))) as $key) {
        if (! isset($definitions[$key])) {
            continue;
        }
        $definition = $definitions[$key];
        $cards[] = [
            'url' => url($prefix . $definition['slug']),
            'name' => $definition['name'][$locale] ?? $definition['name']['es'] ?? $key,
            'text' => $definition['seo_description'][$locale] ?? $definition['seo_description']['es'] ?? '',
        ];
    }
@endphp

@if ($cards)
    <section class="py-12 lg:py-16">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            {!! Theme::partial('components.section-heading', ['title' => $shortcode->title ?: __('Sigue leyendo')]) !!}

            <div @class(['mt-10 grid gap-5', 'sm:grid-cols-2' => count($cards) === 2 || count($cards) === 4, 'sm:grid-cols-2 lg:grid-cols-3' => count($cards) === 3])>
                @foreach ($cards as $card)
                    <a href="{{ $card['url'] }}" class="group flex flex-col rounded-2xl border border-torns-ink/10 bg-white p-6 transition hover:border-torns-primary hover:shadow-lg">
                        <span class="font-display text-lg font-bold group-hover:text-torns-primary">{{ $card['name'] }}</span>
                        <span class="mt-2 flex-1 text-sm leading-relaxed text-torns-ink/70">{{ $card['text'] }}</span>
                        <span class="mt-4 text-sm font-semibold text-torns-primary">{{ __('Leer más') }} →</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif
