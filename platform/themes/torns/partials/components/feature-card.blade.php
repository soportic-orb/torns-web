@php
    // Feature card. Params: $title, $description, $icon (inline SVG html),
    // $accent = morning|evening|night (icon accent color, rotates in grids).
    $accent = $accent ?? 'morning';
    $accentClasses = [
        'morning' => 'bg-shift-morning/10 text-shift-morning',
        'evening' => 'bg-shift-evening/10 text-shift-evening',
        'night' => 'bg-shift-night/10 text-shift-night',
    ][$accent] ?? 'bg-torns-primary/10 text-torns-primary';
@endphp
<article class="feature-card group rounded-3xl border border-torns-ink/5 bg-white p-6 transition duration-200 hover:-translate-y-1 hover:border-torns-primary/20 hover:shadow-xl hover:shadow-torns-ink/5 motion-reduce:transition-none motion-reduce:hover:translate-y-0">
    @if (! empty($icon))
        <span class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl {{ $accentClasses }}" aria-hidden="true">
            {!! $icon !!}
        </span>
    @endif
    <h3 class="font-display text-lg font-semibold">{{ $title ?? '' }}</h3>
    @if (! empty($description))
        <p class="mt-2 text-sm leading-relaxed text-torns-ink/70">{{ $description }}</p>
    @endif
</article>
