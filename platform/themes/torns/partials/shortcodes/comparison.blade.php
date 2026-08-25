@php
    $title = $shortcode->title ?: __('¿Por qué Torns?');
    $subtitle = $shortcode->subtitle ?: __('Comparativa objetiva de características con otras apps de gestión de turnos, según la documentación pública de cada aplicación.');
    $data = torns_comparison_data();
    $competitors = $data['competitors'];
    $rows = $data['rows'];
    $others = array_diff_key($competitors, ['torns' => true]);
@endphp

<section class="py-16 lg:py-24" id="comparativa">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        {!! Theme::partial('components.section-heading', ['title' => $title, 'subtitle' => $subtitle, 'eyebrow' => __('Comparativa')]) !!}

        {{-- Desktop / tablet: table with sticky first columns --}}
        <div class="mt-12 hidden overflow-x-auto rounded-2xl border border-torns-ink/10 bg-white md:block">
            <table class="w-full min-w-[860px] border-collapse text-sm">
                <caption class="sr-only">{{ $title }}</caption>
                <thead>
                    <tr class="border-b border-torns-ink/10">
                        <th scope="col" class="sticky left-0 z-10 bg-white p-4 text-left font-semibold text-torns-ink/70">{{ __('Feature') }}</th>
                        @foreach ($competitors as $key => $name)
                            <th scope="col" @class(['p-4 text-center font-display', 'bg-torns-primary/5 text-torns-primary text-base font-bold' => $key === 'torns', 'font-semibold' => $key !== 'torns'])>
                                {{ $name }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rows as $row)
                        <tr class="border-b border-torns-ink/5 last:border-0">
                            <th scope="row" class="sticky left-0 z-10 max-w-56 bg-white p-4 text-left font-medium">{{ $row['feature'] }}</th>
                            @foreach ($competitors as $key => $name)
                                <td @class(['p-4 text-center align-middle', 'bg-torns-primary/5' => $key === 'torns'])>
                                    {!! torns_comparison_cell((string) ($row[$key] ?? '-'), $key === 'torns') !!}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile: one stacked card per competitor, Torns column always visible --}}
        <div class="mt-10 flex flex-col gap-4 md:hidden">
            @foreach ($others as $key => $name)
                <details class="group rounded-2xl border border-torns-ink/10 bg-white">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-3 px-5 py-4 font-display font-semibold [&::-webkit-details-marker]:hidden">
                        <span>Torns <span class="text-torns-ink/70">vs</span> {{ $name }}</span>
                        <svg class="h-5 w-5 shrink-0 text-torns-ink/40 transition-transform duration-200 group-open:rotate-180 motion-reduce:transition-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                    </summary>
                    <div class="border-t border-torns-ink/5 px-5 py-2">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-xs uppercase tracking-wide text-torns-ink/70">
                                    <th scope="col" class="py-2 text-left font-semibold">{{ __('Feature') }}</th>
                                    <th scope="col" class="w-16 py-2 text-center font-bold text-torns-primary">Torns</th>
                                    <th scope="col" class="w-16 py-2 text-center font-semibold">{{ $name }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rows as $row)
                                    <tr class="border-t border-torns-ink/5">
                                        <th scope="row" class="py-2.5 pr-2 text-left text-xs font-medium leading-snug">{{ $row['feature'] }}</th>
                                        <td class="py-2.5 text-center">{!! torns_comparison_cell((string) ($row['torns'] ?? '-'), true) !!}</td>
                                        <td class="py-2.5 text-center">{!! torns_comparison_cell((string) ($row[$key] ?? '-')) !!}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </details>
            @endforeach
        </div>

        <p class="mt-6 text-center text-xs leading-relaxed text-torns-ink/70">
            {{ __('Last review') }}: {{ $data['reviewed_at'] }} ·
            {{ __('Information based on the public documentation of each application at the review date.') }}
        </p>
    </div>
</section>
