@php
    // Messaging app screen: a Torns shift shared into a work group chat,
    // used by the "share by messaging" feature row.
@endphp
<div class="flex flex-1 flex-col overflow-hidden">
    <div class="flex items-center gap-2 border-b border-torns-ink/5 bg-white px-4 pb-2.5 pt-3">
        <span class="app-avatar !h-7 !w-7 bg-shift-evening/15 text-[9px] text-shift-evening">{{ __('shift_letter_evening') }}</span>
        <span class="min-w-0 flex-1">
            <span class="block truncate text-[11px] font-bold leading-tight">{{ __('Grupo del trabajo') }}</span>
            <span class="block text-[8px] leading-tight text-torns-ink/50">{{ __('12 personas') }}</span>
        </span>
    </div>

    <div class="flex flex-1 flex-col gap-2 px-3 pt-3">
        {{-- Shared Torns card (incoming) --}}
        <div class="max-w-[85%] rounded-2xl rounded-tl-md border border-torns-ink/5 bg-white p-2.5 shadow-sm">
            <div class="flex items-center gap-1.5 border-b border-torns-ink/5 pb-2">
                <span class="flex h-5 w-5 items-center justify-center rounded-md bg-torns-primary text-[8px] font-bold text-white">T</span>
                <span class="text-[9px] font-bold text-torns-primary">Torns</span>
            </div>
            <div class="flex items-center gap-2 pt-2">
                {!! Theme::partial('components.shift-chip', ['type' => 'night', 'size' => 'sm', 'time' => '23–07']) !!}
                <span class="min-w-0 flex-1">
                    <span class="block text-[10px] font-bold leading-tight">{{ __('Busco cambio') }}</span>
                    <span class="block text-[8px] leading-tight text-torns-ink/60">{{ __('Vie') }} 13 · {{ __('Urgencias') }}</span>
                </span>
            </div>
            <span class="mt-2 block rounded-full bg-torns-primary/10 py-1 text-center text-[9px] font-bold text-torns-primary">{{ __('Ver en Torns') }}</span>
        </div>

        <div class="max-w-[80%] rounded-2xl rounded-tl-md bg-white p-2.5 text-[10px] leading-snug shadow-sm">
            {{ __('¿Alguien me lo puede cambiar? 🙏') }}
        </div>

        <div class="ml-auto max-w-[80%] rounded-2xl rounded-tr-md bg-torns-primary p-2.5 text-[10px] leading-snug text-white shadow-sm">
            {{ __('Yo puedo, te propongo el cambio en la app ✅') }}
        </div>
    </div>

    <div class="mt-auto flex items-center gap-2 px-3 pb-4 pt-2">
        <span class="flex-1 rounded-full bg-white px-3 py-2 text-[9px] text-torns-ink/40 shadow-sm">{{ __('Escribe un mensaje…') }}</span>
        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-torns-primary text-white">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
        </span>
    </div>
</div>
