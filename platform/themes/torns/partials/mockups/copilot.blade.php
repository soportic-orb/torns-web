@php
    // Copilot AI chain-swap suggestion screen, used by the Copilot feature row.
    $chain = [
        ['initials' => __('shift_letter_morning'), 'name' => __('Tú'), 'unit' => __('Urgencias'), 'type' => 'morning', 'time' => '07–15', 'day' => __('Vie') . ' 13', 'tint' => 'bg-torns-primary/15 text-torns-primary'],
        ['initials' => 'LG', 'name' => 'Laura G.', 'unit' => __('UCI'), 'type' => 'evening', 'time' => '15–23', 'day' => __('Sáb') . ' 14', 'tint' => 'bg-shift-evening/15 text-shift-evening'],
        ['initials' => 'MP', 'name' => 'Marc P.', 'unit' => __('Planta 3'), 'type' => 'night', 'time' => '23–07', 'day' => __('Vie') . ' 13', 'tint' => 'bg-shift-night/15 text-shift-night'],
    ];
@endphp
<div class="flex flex-1 flex-col overflow-hidden">
    <div class="flex items-center gap-2 px-4 pb-2 pt-3">
        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-gradient-to-br from-shift-night to-torns-primary text-white shadow-sm">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2 9.5 9.5 2 12l7.5 2.5L12 22l2.5-7.5L22 12l-7.5-2.5L12 2Z"/></svg>
        </span>
        <p class="font-display text-base font-bold">Copilot</p>
        <span class="rounded-full bg-shift-night/10 px-2 py-0.5 text-[8px] font-bold uppercase tracking-wide text-shift-night">{{ __('IA') }}</span>
    </div>

    <div class="mx-3 max-w-[90%] rounded-2xl rounded-tl-md bg-white p-2.5 text-[10px] leading-snug shadow-sm">
        {{ __('He encontrado una cadena de cambios que encaja con tu disponibilidad ✨') }}
    </div>

    <div class="mx-3 mt-2.5 app-card">
        <p class="border-b border-torns-ink/5 pb-2 text-[9px] font-bold uppercase tracking-wide text-torns-ink/50">{{ __('Cadena de cambio a 3') }}</p>
        <div class="flex flex-col pt-2">
            @foreach ($chain as $i => $person)
                <div class="flex items-center gap-2">
                    <span class="app-avatar {{ $person['tint'] }}">{{ $person['initials'] }}</span>
                    <span class="min-w-0 flex-1">
                        <span class="block text-[10px] font-bold leading-tight">{{ $person['name'] }}</span>
                        <span class="block text-[8px] leading-tight text-torns-ink/60">{{ $person['unit'] }} · {{ $person['day'] }}</span>
                    </span>
                    {!! Theme::partial('components.shift-chip', ['type' => $person['type'], 'size' => 'sm', 'time' => $person['time']]) !!}
                </div>
                @if ($i < count($chain) - 1)
                    <span class="my-0.5 ml-3.5 flex h-4 items-center text-torns-primary">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14m0 0 5-5m-5 5-5-5"/></svg>
                    </span>
                @endif
            @endforeach
        </div>
    </div>

    <div class="mx-3 mt-2.5 flex items-center gap-1.5 rounded-2xl bg-torns-primary/5 p-2.5">
        <svg class="shrink-0 text-torns-primary" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m5 13 4 4L19 7"/></svg>
        <p class="text-[9px] font-semibold leading-snug text-torns-ink/70">{{ __('Todos los turnos encajan. Todos salís ganando.') }}</p>
    </div>

    <div class="mx-3 mt-2.5">
        <span class="flex items-center justify-center gap-1.5 rounded-full bg-gradient-to-r from-shift-night to-torns-primary py-2.5 text-[11px] font-bold text-white shadow-md">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2 9.5 9.5 2 12l7.5 2.5L12 22l2.5-7.5L22 12l-7.5-2.5L12 2Z"/></svg>
            {{ __('Aceptar cadena') }}
        </span>
    </div>
</div>
