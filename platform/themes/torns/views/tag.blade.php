@php
    Theme::layout('full-width');
    Theme::set('blogTaxonomy', $tag);
    torns_blog_taxonomy_seo($tag, $posts->total());
@endphp

<div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
    <div class="mb-6">
        <a href="{{ torns_blog_url() }}" class="inline-flex min-h-11 items-center gap-2 rounded-full px-1 text-sm font-semibold text-torns-primary transition hover:text-torns-primary-dark focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-torns-primary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            {{ __('Volver al blog') }}
        </a>
    </div>
    <header class="mx-auto mb-10 max-w-2xl text-center">
        <p class="mb-3 text-sm font-semibold uppercase tracking-widest text-torns-primary">{{ __('Tag') }}</p>
        <h1 class="font-display text-3xl font-bold tracking-tight sm:text-4xl">#{{ $tag->name }}</h1>
        @if ($tag->description)
            <p class="mt-4 leading-relaxed text-torns-ink/70">{{ $tag->description }}</p>
        @endif
    </header>

    {!! Theme::partial('blog.post-list', compact('posts')) !!}
</div>
