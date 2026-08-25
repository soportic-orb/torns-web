@php
    // CSS phone mockup with an in-browser recreation of an app screen.
    // Params: $screen = mockup view name under partials/mockups/ (wall, share,
    //         sos, languages, copilot, calendar), $label = accessible
    //         description announced instead of the decorative screen content.
@endphp
<div class="phone-frame" role="img" aria-label="{{ $label ?? __('Pantalla de la app Torns') }}">
    <span class="phone-island" aria-hidden="true"></span>
    <div class="phone-screen" aria-hidden="true">
        <div class="phone-statusbar">
            <span>9:41</span>
            <span class="flex items-center gap-1">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M2 17h3v4H2zM8 13h3v8H8zM14 9h3v12h-3zM20 5h3v16h-3z"/></svg>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M12 18.5a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3ZM12 14c-2 0-3.8.8-5.1 2.1l1.8 1.8a4.5 4.5 0 0 1 6.6 0l1.8-1.8A7.2 7.2 0 0 0 12 14ZM12 9c-3.4 0-6.4 1.4-8.6 3.6l1.8 1.8A9.7 9.7 0 0 1 12 11.5c2.7 0 5.1 1.1 6.8 2.9l1.8-1.8A12.2 12.2 0 0 0 12 9Z"/></svg>
                <svg width="18" height="10" viewBox="0 0 28 14" fill="none"><rect x="1" y="1" width="22" height="12" rx="3.5" stroke="currentColor" stroke-opacity=".4"/><rect x="3" y="3" width="14" height="8" rx="2" fill="currentColor"/><path d="M25.5 5v4a2 2 0 0 0 0-4Z" fill="currentColor" fill-opacity=".4"/></svg>
            </span>
        </div>
        {!! Theme::partial('mockups.' . $screen) !!}
    </div>
</div>
