@php
    // Shift chip — the visual signature of the site. A small card representing
    // a work shift: letter + time range, colored by shift type.
    // Params: $type = morning|evening|night, $label (optional letter override),
    //         $time (optional, e.g. '07:00'), $size = sm|md|lg (default md).
    $type = $type ?? 'morning';
    $size = $size ?? 'md';
    $defaults = [
        'morning' => ['label' => __('shift_letter_morning'), 'name' => __('Morning shift')],
        'evening' => ['label' => __('shift_letter_evening'), 'name' => __('Evening shift')],
        'night' => ['label' => __('shift_letter_night'), 'name' => __('Night shift')],
    ];
    $label = $label ?? $defaults[$type]['label'];
    $name = $defaults[$type]['name'];
@endphp
<span
    class="shift-chip shift-chip--{{ $type }} shift-chip--{{ $size }}"
    role="img"
    aria-label="{{ $name }}@if (! empty($time)), {{ $time }}@endif"
>
    <span class="shift-chip__label" aria-hidden="true">{{ $label }}</span>
    @if (! empty($time))
        <span class="shift-chip__time" aria-hidden="true">{{ $time }}</span>
    @endif
</span>
