@php
    Theme::layout('full-width');
@endphp

<div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
    <header class="mx-auto mb-10 max-w-2xl text-center">
        <p class="mb-3 text-sm font-semibold uppercase tracking-widest text-torns-primary">{{ __('Category') }}</p>
        <h1 class="font-display text-3xl font-bold tracking-tight sm:text-4xl">{{ $category->name }}</h1>
        @if ($category->description)
            <p class="mt-4 leading-relaxed text-torns-ink/70">{{ $category->description }}</p>
        @endif
    </header>

    {!! Theme::partial('blog.post-list', compact('posts')) !!}
</div>
