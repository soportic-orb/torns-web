@php
    // SOS urgent swap app screen, used by the "SOS shifts" feature row.
@endphp
<div class="flex flex-1 flex-col overflow-hidden">
    <div class="mx-3 mt-2 rounded-2xl bg-gradient-to-br from-shift-evening to-[#d94435] p-3 text-white shadow-md">
        <div class="flex items-center gap-2">
            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white/20">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 9v4m0 4h.01M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z"/></svg>
            </span>
            <span>
                <span class="block font-display text-sm font-bold leading-tight">{{ __('SOS · Cambio urgente') }}</span>
                <span class="block text-[9px] leading-tight text-white/85">{{ __('Se notificará a todo tu centro') }}</span>
            </span>
        </div>
    </div>

    <div class="mx-3 mt-2.5 app-card">
        <div class="flex items-center gap-2">
            {!! Theme::partial('components.shift-chip', ['type' => 'morning', 'size' => 'sm', 'time' => '07–15']) !!}
            <span class="min-w-0 flex-1">
                <span class="block text-[11px] font-bold leading-tight">{{ __('Sáb') }} 21 · {{ __('Urgencias') }}</span>
                <span class="block text-[9px] leading-tight text-torns-ink/60">{{ __('Hospital Norte') }}</span>
            </span>
        </div>
        <p class="mt-2 border-t border-torns-ink/5 pt-2 text-[9px] leading-snug text-torns-ink/60">
            {{ __('Todos los usuarios de tu centro recibirán una notificación pidiendo su colaboración.') }}
        </p>
    </div>

    <div class="mx-3 mt-2.5">
        <span class="flex items-center justify-center gap-1.5 rounded-full bg-shift-evening py-2.5 text-[11px] font-bold text-white shadow-md">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 8a6 6 0 1 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 0 1-3.4 0"/></svg>
            {{ __('Publicar SOS') }}
        </span>
    </div>

    <div class="mx-3 mt-4 flex items-center gap-2.5 rounded-2xl border border-torns-ink/5 bg-white p-3 shadow-lg">
        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-torns-primary/10 text-torns-primary">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 8a6 6 0 1 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 0 1-3.4 0"/></svg>
        </span>
        <span>
            <span class="block text-[10px] font-bold leading-tight">{{ __('24 compañeros notificados') }}</span>
            <span class="block text-[8px] leading-tight text-torns-ink/50">{{ __('hace 2 min') }}</span>
        </span>
    </div>

    <div class="mx-3 mt-2 flex items-center gap-2.5 rounded-2xl border border-torns-ink/5 bg-white/70 p-3">
        <span class="app-avatar !h-8 !w-8 bg-shift-night/15 text-[10px] text-shift-night">MP</span>
        <span>
            <span class="block text-[10px] font-bold leading-tight">Marc P. {{ __('te propone un cambio') }}</span>
            <span class="block text-[8px] leading-tight text-torns-ink/50">{{ __('hace 1 min') }}</span>
        </span>
    </div>
</div>
