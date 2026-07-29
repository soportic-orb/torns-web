@php
    // FAQ accordion item based on the native <details> element (no JS needed).
    // Params: $question, $answer (HTML allowed), $open (bool).
@endphp
<details class="faq-item group rounded-xl border border-torns-ink/10 bg-white" @if (! empty($open)) open @endif>
    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 rounded-xl px-5 py-4 text-left font-medium transition hover:text-torns-primary [&::-webkit-details-marker]:hidden">
        {{ $question ?? '' }}
        <svg class="h-5 w-5 shrink-0 text-torns-ink/40 transition-transform duration-200 group-open:rotate-180 motion-reduce:transition-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
            <path d="m6 9 6 6 6-6" />
        </svg>
    </summary>
    <div class="px-5 pb-5 text-sm leading-relaxed text-torns-ink/70">
        {!! $answer ?? '' !!}
    </div>
</details>
