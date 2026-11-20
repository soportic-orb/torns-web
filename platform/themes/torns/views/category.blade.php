@php
    Theme::layout('full-width');
    ['featured' => $featured, 'posts' => $posts] = torns_blog_listing($category);
    Theme::set('blogTaxonomy', $category);
    torns_blog_taxonomy_seo($category, $posts->total() + ($featured ? 1 : 0));
@endphp

<div class="mx-auto flex max-w-6xl flex-col gap-8 px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
    {!! Theme::partial('blog.header', compact('category')) !!}

    @if ($featured && $posts->onFirstPage())
        {!! Theme::partial('blog.featured', ['post' => $featured]) !!}
    @endif

    {{-- With only the featured post, no "coming soon" under it. --}}
    @unless ($featured && $posts->isEmpty())
        {!! Theme::partial('blog.post-list', compact('posts')) !!}
    @endunless
</div>
