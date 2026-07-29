@php
    Theme::layout('full-width');
    Theme::set('post', $post);
    $relatedPosts = get_related_posts($post->id, 3);
    $shareUrl = $post->url;
    $shareTitle = $post->name;
@endphp

<article class="py-12 lg:py-16">
    <header class="mx-auto max-w-3xl px-4 sm:px-6">
        @if ($post->categories->isNotEmpty())
            <div class="flex flex-wrap justify-center gap-2">
                @foreach ($post->categories as $category)
                    <a href="{{ $category->url }}" class="rounded-full bg-torns-primary/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-torns-primary transition hover:bg-torns-primary/20">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        @endif
        <h1 class="mt-4 text-center font-display text-3xl font-bold tracking-tight sm:text-4xl">{{ $post->name }}</h1>
        <div class="mt-4 flex items-center justify-center gap-3 text-sm text-torns-ink/70">
            <time datetime="{{ $post->created_at->toDateString() }}">{{ $post->created_at->translatedFormat('d F, Y') }}</time>
            @if ($post->author?->name)
                <span aria-hidden="true">·</span>
                <span>{{ $post->author->name }}</span>
            @endif
        </div>
    </header>

    @if ($post->image)
        <div class="mx-auto mt-10 max-w-4xl px-4 sm:px-6">
            <img
                src="{{ RvMedia::getImageUrl($post->image) }}"
                alt="{{ $post->name }}"
                class="w-full rounded-2xl"
                width="1200"
                height="675"
                fetchpriority="high"
            >
        </div>
    @endif

    {{-- Reading column: prose-torns caps line length at ~70ch --}}
    <div class="mx-auto mt-10 max-w-3xl px-4 sm:px-6">
        <div class="prose-torns mx-auto text-[1.0625rem] leading-relaxed">
            {!! BaseHelper::clean($post->content) !!}
        </div>

        @if ($post->tags->isNotEmpty())
            <ul class="mt-10 flex flex-wrap gap-2">
                @foreach ($post->tags as $tag)
                    <li>
                        <a href="{{ $tag->url }}" class="rounded-full border border-torns-ink/10 px-3 py-1 text-xs font-medium text-torns-ink/70 transition hover:border-torns-primary hover:text-torns-primary">
                            #{{ $tag->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif

        {{-- Native share links, no third-party SDKs --}}
        <div class="mt-10 border-t border-torns-ink/5 pt-6">
            <p class="mb-3 text-sm font-semibold">{{ __('Share') }}</p>
            <ul class="flex flex-wrap gap-2">
                <li>
                    <a class="share-link" rel="noopener nofollow" target="_blank" href="https://wa.me/?text={{ urlencode($shareTitle . ' ' . $shareUrl) }}">WhatsApp</a>
                </li>
                <li>
                    <a class="share-link" rel="noopener nofollow" target="_blank" href="https://x.com/intent/post?url={{ urlencode($shareUrl) }}&text={{ urlencode($shareTitle) }}">X</a>
                </li>
                <li>
                    <a class="share-link" rel="noopener nofollow" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}">Facebook</a>
                </li>
                <li>
                    <a class="share-link" rel="noopener nofollow" target="_blank" href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($shareUrl) }}">LinkedIn</a>
                </li>
                <li>
                    <a class="share-link" href="mailto:?subject={{ rawurlencode($shareTitle) }}&body={{ rawurlencode($shareUrl) }}">Email</a>
                </li>
            </ul>
        </div>
    </div>

    @if ($relatedPosts->isNotEmpty())
        <aside class="mx-auto mt-16 max-w-6xl px-4 sm:px-6 lg:px-8" aria-label="{{ __('Related posts') }}">
            <h2 class="mb-6 text-center font-display text-2xl font-semibold">{{ __('Related posts') }}</h2>
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($relatedPosts as $relatedPost)
                    {!! Theme::partial('blog.post-card', ['post' => $relatedPost]) !!}
                @endforeach
            </div>
        </aside>
    @endif
</article>
