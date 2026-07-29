@php
    $supportedLocales = Language::getSupportedLocales();
    $currentLocale = Language::getCurrentLocale();
    $inline = $inline ?? false;
@endphp

@if ($supportedLocales && count($supportedLocales) > 1)
    @if ($inline)
        <ul class="flex flex-wrap gap-2" aria-label="{{ __('Language') }}">
            @foreach ($supportedLocales as $localeCode => $properties)
                <li>
                    @if ($localeCode === $currentLocale)
                        <span class="inline-flex h-9 min-w-11 items-center justify-center rounded-lg bg-torns-primary px-2 text-sm font-semibold uppercase text-white" aria-current="true">
                            {{ $properties['lang_code'] }}
                        </span>
                    @else
                        <a
                            href="{{ Language::getSwitcherUrl($localeCode, $properties['lang_code']) }}"
                            class="inline-flex h-9 min-w-11 items-center justify-center rounded-lg border border-torns-ink/10 px-2 text-sm font-semibold uppercase text-torns-ink/70 transition hover:border-torns-primary hover:text-torns-primary"
                            lang="{{ $properties['lang_code'] }}"
                            hreflang="{{ $properties['lang_code'] }}"
                        >
                            <span class="sr-only">{{ $properties['lang_name'] }}</span>
                            <span aria-hidden="true">{{ $properties['lang_code'] }}</span>
                        </a>
                    @endif
                </li>
            @endforeach
        </ul>
    @else
        <details class="lang-menu relative" data-lang-menu>
            <summary class="flex h-10 cursor-pointer list-none items-center gap-1.5 rounded-lg px-3 text-sm font-semibold uppercase text-torns-ink/80 transition hover:bg-torns-ink/5 [&::-webkit-details-marker]:hidden">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M3.6 9h16.8M3.6 15h16.8M12 3a15 15 0 0 1 0 18M12 3a15 15 0 0 0 0 18"/></svg>
                {{ Arr::get($supportedLocales, $currentLocale . '.lang_code', $currentLocale) }}
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
            </summary>
            <ul class="absolute right-0 top-full z-50 mt-1 min-w-40 rounded-xl border border-torns-ink/5 bg-white p-1.5 shadow-lg">
                @foreach ($supportedLocales as $localeCode => $properties)
                    <li>
                        @if ($localeCode === $currentLocale)
                            <span class="flex items-center justify-between rounded-lg bg-torns-primary/5 px-3 py-2 text-sm font-semibold text-torns-primary" aria-current="true">
                                {{ $properties['lang_name'] }}
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 13 4 4L19 7"/></svg>
                            </span>
                        @else
                            <a
                                href="{{ Language::getSwitcherUrl($localeCode, $properties['lang_code']) }}"
                                class="block rounded-lg px-3 py-2 text-sm font-medium text-torns-ink/80 transition hover:bg-torns-ink/5"
                                lang="{{ $properties['lang_code'] }}"
                                hreflang="{{ $properties['lang_code'] }}"
                            >
                                {{ $properties['lang_name'] }}
                            </a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </details>
    @endif
@endif
