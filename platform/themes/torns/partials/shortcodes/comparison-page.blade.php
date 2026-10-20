@php
    $data = torns_comparison((string) $shortcode->competitor);
@endphp

@if ($data)
    @php
        $name = $data['name'];
        $checked = $data['checked_on'] ? \Illuminate\Support\Carbon::parse($data['checked_on'])->format('d/m/Y') : '';
        $rows = collect($data['groups'])->pluck('rows')->flatten(1);
        // What only one of the two documents publicly, and what both offer.
        $onlyTorns = $rows->filter(fn ($row) => $row['torns']['has'] && ! $row['other']['has'])
            ->reject(fn ($row) => in_array($row['key'], ['focus', 'price', 'platforms', 'data_privacy'], true));
        $onlyOther = $rows->filter(fn ($row) => $row['other']['has'] && ! $row['torns']['has']);
        $both = $rows->filter(fn ($row) => $row['torns']['has'] && $row['other']['has'])
            ->reject(fn ($row) => in_array($row['key'], ['focus', 'price', 'platforms', 'languages', 'data_privacy'], true));
        $others = collect(torns_comparison_competitors())->except($data['slug']);
        $locale = torns_current_locale();
        $prefix = $locale === 'es' ? '' : $locale . '/';
    @endphp

    <section class="bg-gradient-to-b from-white to-torns-bg">
        <div class="mx-auto max-w-5xl px-4 pb-10 pt-12 sm:px-6 lg:px-8 lg:pb-14 lg:pt-16">
            <p class="text-sm font-semibold uppercase tracking-widest text-torns-primary">{{ __('Comparativa') }}</p>
            <h1 class="mt-3 font-display text-3xl font-bold leading-tight tracking-tight [text-wrap:balance] sm:text-4xl lg:text-5xl">{{ __('Torns vs :name: comparativa de funcionalidades', ['name' => $name]) }}</h1>
            <div class="mt-8 grid gap-4 md:grid-cols-2">
                <div class="rounded-2xl border border-torns-primary/20 bg-white p-5">
                    <p class="font-display text-lg font-bold text-torns-primary-dark">Torns</p>
                    <p class="mt-2 text-sm leading-relaxed text-torns-ink/75">{{ $data['groups']['general']['rows'][0]['torns']['detail'] ?? '' }}</p>
                </div>
                <div class="rounded-2xl border border-torns-ink/10 bg-white p-5">
                    <p class="font-display text-lg font-bold">{{ $name }}</p>
                    <p class="mt-2 text-sm leading-relaxed text-torns-ink/75">{{ $data['summary'] }}</p>
                </div>
            </div>
            @if ($checked)
                <p class="mt-5 text-sm text-torns-ink/60">{{ __('Datos de las fuentes públicas de cada app, revisados el :date. Lista de fuentes al final.', ['date' => $checked]) }}</p>
            @endif
        </div>
    </section>

    <section class="py-10 lg:py-14">
        <div class="mx-auto grid max-w-5xl gap-6 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
            @if ($onlyTorns->isNotEmpty())
                <div class="rounded-2xl bg-torns-primary/5 p-6">
                    <h2 class="font-display text-xl font-bold">{{ __('Lo que ofrece Torns y no consta en :name', ['name' => $name]) }}</h2>
                    <ul class="mt-4 flex list-none flex-col gap-2.5">
                        @foreach ($onlyTorns->take(10) as $row)
                            <li class="flex gap-2 text-sm leading-relaxed text-torns-ink/80">
                                <svg class="mt-0.5 shrink-0 text-torns-primary" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 13 4 4L19 7"/></svg>
                                <span><b class="font-semibold text-torns-ink">{{ $row['label'] }}</b>{{ $row['torns']['value'] === 'Pro' ? ' (Pro)' : '' }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="rounded-2xl bg-white p-6 ring-1 ring-torns-ink/10">
                @if ($both->isNotEmpty())
                    <h2 class="font-display text-xl font-bold">{{ __('Lo que ofrecen las dos') }}</h2>
                    <ul class="mt-4 flex list-none flex-wrap gap-2">
                        @foreach ($both as $row)
                            <li class="rounded-full bg-torns-bg px-3 py-1 text-sm text-torns-ink/80">{{ $row['label'] }}</li>
                        @endforeach
                    </ul>
                @endif
                @if ($onlyOther->isNotEmpty())
                    <h2 class="mt-6 font-display text-xl font-bold">{{ __('Lo que ofrece :name y no Torns', ['name' => $name]) }}</h2>
                    <ul class="mt-4 flex list-none flex-wrap gap-2">
                        @foreach ($onlyOther as $row)
                            <li class="rounded-full bg-torns-bg px-3 py-1 text-sm text-torns-ink/80">{{ $row['label'] }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </section>

    <section class="pb-12 lg:pb-16" id="tabla">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <h2 class="font-display text-2xl font-bold tracking-tight sm:text-3xl">{{ __('Comparativa completa: Torns y :name', ['name' => $name]) }}</h2>
            <p class="mt-3 text-sm text-torns-ink/65">
                {!! torns_comparison_cell('yes') !!} {{ __('Sí, según su documentación') }} ·
                {!! torns_comparison_cell('no') !!} {{ __('No') }} ·
                <span class="font-semibold">—</span> {{ __('No consta en sus fuentes públicas') }}
            </p>

            @foreach ($data['groups'] as $group)
                <div class="mt-8 overflow-hidden rounded-2xl border border-torns-ink/10 bg-white">
                    <table class="block w-full border-collapse text-left text-sm md:table">
                        <caption class="block border-b border-torns-ink/10 bg-torns-bg px-5 py-3 text-left font-display text-base font-bold md:table-caption">{{ $group['label'] }}</caption>
                        <thead class="sr-only">
                            <tr>
                                <th scope="col">{{ __('Funcionalidad') }}</th>
                                <th scope="col">Torns</th>
                                <th scope="col">{{ $name }}</th>
                            </tr>
                        </thead>
                        <tbody class="block md:table-row-group">
                            @foreach ($group['rows'] as $row)
                                <tr class="block border-t border-torns-ink/5 align-top first:border-t-0 md:table-row">
                                    <th scope="row" class="block px-5 pb-1 pt-4 font-semibold md:table-cell md:w-[28%] md:py-4">{{ $row['label'] }}</th>
                                    @foreach (['torns' => 'Torns', 'other' => $name] as $side => $label)
                                        <td class="block px-5 py-2 last:pb-4 md:table-cell md:w-[36%] md:py-4">
                                            <span class="mb-1 block text-xs font-semibold uppercase tracking-wide text-torns-ink/45">{{ $label }}</span>
                                            <span class="flex items-start gap-2">
                                                <span class="shrink-0">{!! torns_comparison_cell($row[$side]['value'], $side === 'torns') !!}</span>
                                            </span>
                                            @if ($row[$side]['detail'])
                                                <span class="mt-1 block leading-relaxed text-torns-ink/70">{{ $row[$side]['detail'] }}</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    </section>

    @php
        // Same questions and answers as the page's FAQPage data (scripts/build-comparisons.py).
        $faqFile = base_path("content/site/schema/vs-{$data['slug']}.{$locale}.json");
        $faq = is_file($faqFile) ? (json_decode((string) file_get_contents($faqFile), true)['mainEntity'] ?? []) : [];
    @endphp
    @if ($faq)
        <section class="pb-12 lg:pb-16" id="preguntas">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                <h2 class="font-display text-2xl font-bold tracking-tight sm:text-3xl">{{ __('Preguntas frecuentes') }}</h2>
                <div class="mt-6 divide-y divide-torns-ink/10 rounded-2xl border border-torns-ink/10 bg-white">
                    @foreach ($faq as $item)
                        <details class="group px-5 py-4" @if ($loop->first) open @endif>
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold">
                                {{ $item['name'] }}
                                <svg class="shrink-0 transition group-open:rotate-180" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                            </summary>
                            <p class="mt-3 text-sm leading-relaxed text-torns-ink/75">{{ $item['acceptedAnswer']['text'] ?? '' }}</p>
                        </details>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="pb-12 lg:pb-16">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            {!! Theme::partial('blog.download-banner') !!}
        </div>
    </section>

    <section class="pb-16 lg:pb-20">
        <div class="mx-auto grid max-w-5xl gap-10 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
            <div>
                <h2 class="font-display text-xl font-bold">{{ __('Fuentes') }}</h2>
                <ul class="mt-4 flex list-none flex-col gap-2 text-sm">
                    @if ($data['torns_source'])
                        <li><a class="footer-link break-words" href="{{ url($prefix . 'funcionalidades') }}">{{ __('Torns: funcionalidades, planes y límites') }}</a></li>
                    @endif
                    @foreach ($data['sources'] as $source)
                        <li><a class="footer-link break-words" href="{{ $source['url'] }}" rel="nofollow noopener" target="_blank">{{ $source['title'] ?? $source['url'] }}</a></li>
                    @endforeach
                </ul>
                <p class="mt-5 text-sm leading-relaxed text-torns-ink/60">{{ __('Comparativa basada solo en lo que publica cada app (web, tiendas de aplicaciones y centro de ayuda) a fecha de la revisión. Las apps cambian: si ves un dato desactualizado, escríbenos a hola@torns.app y lo corregimos. :name es una marca de sus titulares.', ['name' => $name]) }}</p>
            </div>
            @if ($others->isNotEmpty())
                <div>
                    <h2 class="font-display text-xl font-bold">{{ __('Otras comparativas') }}</h2>
                    <ul class="mt-4 flex list-none flex-col gap-2">
                        @foreach ($others as $slug => $other)
                            <li><a class="font-semibold text-torns-primary underline-offset-4 hover:underline" href="{{ url($prefix . 'torns-vs-' . $slug) }}">Torns vs {{ $other['name'] }}</a></li>
                        @endforeach
                        <li><a class="font-semibold text-torns-primary underline-offset-4 hover:underline" href="{{ url($prefix . 'comparativas') }}">{{ __('Todas las comparativas') }}</a></li>
                    </ul>
                </div>
            @endif
        </div>
    </section>
@endif
