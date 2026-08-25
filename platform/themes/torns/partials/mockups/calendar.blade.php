@php
    // Unified month calendar app screen with color-coded shifts from
    // several workplaces, used by the [torns-unified-calendar] section.
    $weekdays = explode('|', __('L|M|X|J|V|S|D'));

    // Day number => shift type. Static map so Tailwind classes stay literal.
    $shifts = [
        2 => 'morning', 5 => 'night', 9 => 'evening', 12 => 'morning',
        15 => 'morning', 18 => 'night', 21 => 'evening', 26 => 'morning', 29 => 'night',
    ];
    $chipClasses = [
        'morning' => 'bg-shift-morning',
        'evening' => 'bg-shift-evening',
        'night' => 'bg-shift-night',
    ];
    $letters = [
        'morning' => __('shift_letter_morning'),
        'evening' => __('shift_letter_evening'),
        'night' => __('shift_letter_night'),
    ];
@endphp
<div class="flex flex-1 flex-col overflow-hidden">
    <div class="flex items-center justify-between px-4 pb-2 pt-3">
        <p class="font-display text-base font-bold">{{ __('Marzo') }} 2026</p>
        <span class="flex items-center gap-1 rounded-full bg-torns-primary/10 px-2 py-1 text-[8px] font-bold text-torns-primary">
            <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M21 12a9 9 0 1 1-2.6-6.3M21 3v6h-6"/></svg>
            {{ __('Sincronizado') }}
        </span>
    </div>

    <div class="mx-3 rounded-2xl bg-white p-2.5 shadow-sm">
        <div class="grid grid-cols-7 gap-y-1 text-center">
            @foreach ($weekdays as $day)
                <span class="text-[8px] font-bold uppercase text-torns-ink/40">{{ $day }}</span>
            @endforeach
            @for ($day = 1; $day <= 31; $day++)
                <span class="flex flex-col items-center gap-0.5 py-0.5">
                    <span class="text-[9px] font-medium {{ $day === 15 ? 'flex h-4 w-4 items-center justify-center rounded-full bg-torns-primary font-bold text-white' : 'text-torns-ink/70' }}">{{ $day }}</span>
                    @if (isset($shifts[$day]))
                        <span class="flex h-4 w-4 items-center justify-center rounded {{ $chipClasses[$shifts[$day]] }} text-[7px] font-bold text-white">{{ $letters[$shifts[$day]] }}</span>
                    @else
                        <span class="h-4 w-4"></span>
                    @endif
                </span>
            @endfor
        </div>
    </div>

    <div class="mt-2.5 flex justify-center gap-3 px-4">
        @foreach (['morning' => __('Morning shift'), 'evening' => __('Evening shift'), 'night' => __('Night shift')] as $type => $name)
            <span class="flex items-center gap-1 text-[8px] font-semibold text-torns-ink/60">
                <span class="h-2.5 w-2.5 rounded-sm {{ $chipClasses[$type] }}"></span>{{ $name }}
            </span>
        @endforeach
    </div>

    <div class="mx-3 mt-2.5 flex flex-col gap-1.5">
        <div class="app-card flex items-center gap-2 !p-2.5">
            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-shift-morning/15 text-shift-morning">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M3 21h18M5 21V7l7-4 7 4v14"/></svg>
            </span>
            <span class="min-w-0 flex-1">
                <span class="block text-[10px] font-bold leading-tight">{{ __('Hoy') }} · {{ __('Urgencias') }}</span>
                <span class="block text-[8px] leading-tight text-torns-ink/60">{{ __('Hospital Norte') }} · 07–15</span>
            </span>
            {!! Theme::partial('components.shift-chip', ['type' => 'morning', 'size' => 'sm']) !!}
        </div>
        <span class="phone-fab !bottom-3" aria-hidden="true">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
        </span>
        <div class="app-card flex items-center gap-2 !p-2.5">
            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-shift-night/15 text-shift-night">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M3 21h18M5 21V7l7-4 7 4v14"/></svg>
            </span>
            <span class="min-w-0 flex-1">
                <span class="block text-[10px] font-bold leading-tight">{{ __('Sáb') }} 21 · {{ __('UCI') }}</span>
                <span class="block text-[8px] leading-tight text-torns-ink/60">{{ __('Clínica Este') }} · 23–07</span>
            </span>
            {!! Theme::partial('components.shift-chip', ['type' => 'night', 'size' => 'sm']) !!}
        </div>
    </div>
</div>
