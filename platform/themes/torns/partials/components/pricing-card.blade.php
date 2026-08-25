@php
    // Pricing card. Params: $name, $price, $period, $features (array of strings),
    // $highlighted (bool), $badge, $cta_label, $cta_url, $note, $disabled (bool),
    // $copilot (bool — shows the "Copilot included" AI chip).
    $highlighted = ! empty($highlighted);
    $disabled = ! empty($disabled);
    $copilot = ! empty($copilot);
    $features = $features ?? [];
@endphp
<article @class([
    'pricing-card relative flex h-full flex-col rounded-3xl p-6 transition duration-200 lg:p-8',
    'border-2 border-torns-primary bg-gradient-to-b from-torns-primary/[0.06] to-white shadow-xl shadow-torns-primary/15' => $highlighted,
    'border border-torns-ink/10 bg-white hover:border-torns-primary/30 hover:shadow-lg hover:shadow-torns-ink/5' => ! $highlighted,
])>
    @if (! empty($badge))
        <span class="absolute -top-3.5 left-1/2 inline-flex -translate-x-1/2 whitespace-nowrap rounded-full bg-torns-primary px-3.5 py-1 text-xs font-bold uppercase tracking-wide text-white shadow-md shadow-torns-primary/30">
            {{ $badge }}
        </span>
    @endif

    <h3 class="font-display text-lg font-semibold">{{ $name ?? '' }}</h3>

    <p class="mt-4 flex items-baseline gap-1.5">
        <span class="font-display text-4xl font-bold tracking-tight">{{ $price ?? '' }}</span>
        @if (! empty($period))
            <span class="text-sm font-medium text-torns-ink/70">{{ $period }}</span>
        @endif
    </p>

    @if ($copilot)
        <p class="mt-4">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-gradient-to-r from-shift-night/15 to-torns-primary/15 px-3 py-1.5 text-xs font-bold text-shift-night">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2 9.5 9.5 2 12l7.5 2.5L12 22l2.5-7.5L22 12l-7.5-2.5L12 2Z"/></svg>
                {{ __('Copilot IA incluido') }}
            </span>
        </p>
    @endif

    @if (! empty($features))
        <ul class="mt-6 flex flex-1 flex-col gap-3">
            @foreach ($features as $feature)
                <li class="flex items-start gap-2.5 text-sm leading-snug text-torns-ink/80">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 {{ $highlighted ? 'text-torns-primary' : 'text-torns-ink/40' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 13 4 4L19 7"/></svg>
                    {{ $feature }}
                </li>
            @endforeach
        </ul>
    @endif

    @if (! empty($cta_label))
        <div class="mt-8">
            @if ($disabled)
                <span class="inline-flex w-full cursor-default items-center justify-center rounded-full border border-torns-ink/10 px-5 py-3 text-sm font-semibold text-torns-ink/70">
                    {{ $cta_label }}
                </span>
            @else
                <a href="{{ $cta_url ?? '#' }}" @class([
                    'inline-flex w-full items-center justify-center rounded-full px-5 py-3 text-sm font-semibold transition',
                    'bg-torns-primary text-white shadow-md shadow-torns-primary/25 hover:bg-torns-primary-dark' => $highlighted,
                    'border border-torns-primary text-torns-primary hover:bg-torns-primary/5' => ! $highlighted,
                ])>
                    {{ $cta_label }}
                </a>
            @endif
        </div>
    @endif

    @if (! empty($note))
        <p class="mt-3 text-center text-xs text-torns-ink/70">{{ $note }}</p>
    @endif
</article>
