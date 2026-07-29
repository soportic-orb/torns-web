@php
    // Section heading. Params: $title, $subtitle, $eyebrow, $align = center|left,
    // $level = heading level tag (default h2).
    $align = $align ?? 'center';
    $level = $level ?? 'h2';
@endphp
<div @class([
    'max-w-2xl',
    'mx-auto text-center' => $align === 'center',
])>
    @if (! empty($eyebrow))
        <p class="mb-3 text-sm font-semibold uppercase tracking-widest text-torns-primary">{{ $eyebrow }}</p>
    @endif
    <{{ $level }} class="font-display text-3xl font-bold tracking-tight sm:text-4xl">{{ $title ?? '' }}</{{ $level }}>
    @if (! empty($subtitle))
        <p class="mt-4 text-base leading-relaxed text-torns-ink/70 sm:text-lg">{{ $subtitle }}</p>
    @endif
</div>
