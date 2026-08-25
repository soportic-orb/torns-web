@php
    // "Muro" (wall of published shifts) app screen, used in the hero mockup.
    $posts = [
        ['initials' => 'LG', 'name' => 'Laura G.', 'unit' => __('Urgencias'), 'day' => __('Vie') . ' 13', 'type' => 'morning', 'time' => '07–15'],
        ['initials' => 'MP', 'name' => 'Marc P.', 'unit' => __('UCI'), 'day' => __('Sáb') . ' 21', 'type' => 'night', 'time' => '23–07'],
        ['initials' => 'AR', 'name' => 'Anna R.', 'unit' => __('Planta 3'), 'day' => __('Dom') . ' 22', 'type' => 'evening', 'time' => '15–23'],
    ];
    $avatarTints = ['bg-shift-morning/15 text-shift-morning', 'bg-shift-night/15 text-shift-night', 'bg-shift-evening/15 text-shift-evening'];
@endphp
<div class="flex flex-1 flex-col overflow-hidden">
    <div class="flex items-center justify-between px-4 pb-2 pt-3">
        <p class="font-display text-base font-bold">{{ __('Muro') }}</p>
        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-white text-torns-ink/60 shadow-sm">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M4 6h16M7 12h10M10 18h4"/></svg>
        </span>
    </div>

    <div class="scrollbar-none flex gap-1.5 overflow-hidden px-4 pb-3">
        <span class="rounded-full bg-torns-primary px-2.5 py-1 text-[9px] font-bold text-white">{{ __('Todos') }}</span>
        <span class="rounded-full bg-white px-2.5 py-1 text-[9px] font-semibold text-torns-ink/60 shadow-sm">{{ __('Urgencias') }}</span>
        <span class="rounded-full bg-white px-2.5 py-1 text-[9px] font-semibold text-torns-ink/60 shadow-sm">{{ __('UCI') }}</span>
    </div>

    <div class="flex flex-1 flex-col gap-2 px-3">
        @foreach ($posts as $i => $post)
            <div class="app-card">
                <div class="flex items-center gap-2">
                    <span class="app-avatar {{ $avatarTints[$i] }}">{{ $post['initials'] }}</span>
                    <span class="min-w-0 flex-1">
                        <span class="block truncate text-[11px] font-bold leading-tight">{{ $post['name'] }}</span>
                        <span class="block truncate text-[9px] leading-tight text-torns-ink/60">{{ $post['unit'] }} · {{ $post['day'] }}</span>
                    </span>
                    {!! Theme::partial('components.shift-chip', ['type' => $post['type'], 'size' => 'sm', 'time' => $post['time']]) !!}
                </div>
                <div class="mt-2 flex items-center justify-between border-t border-torns-ink/5 pt-2">
                    <span class="text-[9px] font-medium text-torns-ink/50">{{ __('Busca cambio') }}</span>
                    <span class="app-btn">{{ __('Proponer') }}</span>
                </div>
            </div>
        @endforeach
    </div>

    <span class="phone-fab" aria-hidden="true">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
    </span>

    <div class="mt-auto flex items-center justify-around border-t border-torns-ink/5 bg-white px-2 pb-4 pt-2">
        @php
            $tabs = [
                ['label' => __('Muro'), 'active' => true, 'icon' => '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/>'],
                ['label' => __('Calendario'), 'active' => false, 'icon' => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>'],
                ['label' => __('Cambios'), 'active' => false, 'icon' => '<path d="M17 3l4 4-4 4M21 7H7M7 21l-4-4 4-4M3 17h14"/>'],
                ['label' => __('Chat'), 'active' => false, 'icon' => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>'],
            ];
        @endphp
        @foreach ($tabs as $tab)
            <span class="flex flex-col items-center gap-0.5 {{ $tab['active'] ? 'text-torns-primary' : 'text-torns-ink/40' }}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $tab['icon'] !!}</svg>
                <span class="text-[7px] font-semibold">{{ $tab['label'] }}</span>
            </span>
        @endforeach
    </div>
</div>
