@php
    ['featured' => $featured, 'posts' => $posts] = torns_blog_listing();
@endphp

<div class="mx-auto flex max-w-6xl flex-col gap-8 px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
    {!! Theme::partial('blog.header') !!}

    @if ($featured && $posts->onFirstPage())
        {!! Theme::partial('blog.featured', ['post' => $featured]) !!}
    @endif

    {{-- With only the featured post, no "coming soon" under it. --}}
    @unless ($featured && $posts->isEmpty())
        {!! Theme::partial('blog.post-list', compact('posts')) !!}
    @endunless
</div>
