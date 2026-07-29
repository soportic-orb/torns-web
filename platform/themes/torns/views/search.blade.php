@php
    Theme::layout('full-width');
@endphp

<div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
    <header class="mx-auto mb-10 max-w-2xl text-center">
        <h1 class="font-display text-3xl font-bold tracking-tight sm:text-4xl">{{ __('Search results') }}</h1>
        @if (request('q'))
            <p class="mt-4 leading-relaxed text-torns-ink/70">"{{ request('q') }}"</p>
        @endif
    </header>

    {!! Theme::partial('blog.post-list', compact('posts')) !!}
</div>
