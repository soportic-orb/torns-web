@php
    // App language settings screen, used by the "in your own language"
    // feature row. Language names and samples are endonyms — never
    // translated. The active row follows the visitor's current locale.
    $currentLocale = in_array(app()->getLocale(), ['es', 'ca', 'gl', 'eu'], true) ? app()->getLocale() : 'es';
    $languages = [
        ['locale' => 'es', 'name' => 'Castellano', 'sample' => 'Cambia turnos con tus compañeros'],
        ['locale' => 'ca', 'name' => 'Català', 'sample' => 'Canvia torns amb els teus companys'],
        ['locale' => 'gl', 'name' => 'Galego', 'sample' => 'Cambia quendas cos teus compañeiros'],
        ['locale' => 'eu', 'name' => 'Euskara', 'sample' => 'Aldatu txandak lankideekin'],
    ];
    $languages = array_map(function ($language) use ($currentLocale) {
        $language['active'] = $language['locale'] === $currentLocale;

        return $language;
    }, $languages);
@endphp
<div class="flex flex-1 flex-col overflow-hidden">
    <div class="flex items-center gap-2 px-4 pb-2 pt-3">
        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-white text-torns-ink/60 shadow-sm">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="m15 18-6-6 6-6"/></svg>
        </span>
        <p class="font-display text-base font-bold">{{ __('Idioma de la app') }}</p>
    </div>

    <div class="mx-3 flex flex-col gap-1.5">
        @foreach ($languages as $language)
            <div class="app-card flex items-center gap-2.5 {{ $language['active'] ? 'ring-2 ring-torns-primary' : '' }}">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full {{ $language['active'] ? 'bg-torns-primary/10 text-torns-primary' : 'bg-torns-ink/5 text-torns-ink/50' }}">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a15 15 0 0 1 0 18 15 15 0 0 1 0-18Z"/></svg>
                </span>
                <span class="min-w-0 flex-1">
                    <span class="block text-[11px] font-bold leading-tight">{{ $language['name'] }}</span>
                    <span class="block truncate text-[8px] leading-tight text-torns-ink/50">{{ $language['sample'] }}</span>
                </span>
                @if ($language['active'])
                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-torns-primary text-white">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m5 13 4 4L19 7"/></svg>
                    </span>
                @else
                    <span class="h-5 w-5 rounded-full border-2 border-torns-ink/15"></span>
                @endif
            </div>
        @endforeach
    </div>

    <div class="mx-3 mb-4 mt-auto rounded-2xl bg-torns-primary/5 p-3">
        <p class="text-[9px] leading-snug text-torns-ink/60">
            {{ __('Funcionalidades, mensajes, notificaciones y correos se ajustan a tu idioma.') }}
        </p>
    </div>
</div>
