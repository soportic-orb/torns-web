{{-- The latest featured post, full width with the title over the image. --}}
@php
    $category = $post->categories->first();
@endphp
<article class="group relative isolate overflow-hidden rounded-3xl bg-torns-deep">
    <a href="{{ $post->url }}" class="block aspect-[4/5] sm:aspect-[16/9] lg:aspect-[21/9]">
        @if ($post->image)
            <img src="{{ RvMedia::getImageUrl($post->image, null, false, RvMedia::getDefaultImage()) }}" alt="" class="absolute inset-0 -z-10 h-full w-full object-cover transition duration-700 group-hover:scale-[1.02] motion-reduce:transition-none motion-reduce:group-hover:scale-100" width="1536" height="1024" fetchpriority="high">
        @endif
        <span class="absolute inset-0 -z-10 bg-gradient-to-t from-black/60 via-black/15 to-transparent" aria-hidden="true"></span>
        <span class="absolute inset-x-5 bottom-6 flex max-w-4xl flex-col gap-3 sm:inset-x-10 sm:bottom-10 lg:gap-4">
            <span class="flex flex-wrap items-center gap-2.5">
                <span class="rounded-full bg-white px-2.5 py-1 text-xs font-bold uppercase tracking-wider text-torns-ink">{{ __('Destacada') }}</span>
                <span class="text-sm font-semibold text-white [text-shadow:0_1px_3px_rgba(0,0,0,0.8)]">
                    @if ($category){{ $category->name }} · @endif<time datetime="{{ $post->created_at->toDateString() }}">{{ $post->created_at->translatedFormat('d M, Y') }}</time>
                </span>
            </span>
            <h2 class="font-display text-2xl font-bold leading-tight tracking-tight text-white [text-shadow:0_2px_4px_rgba(0,0,0,0.85),0_4px_18px_rgba(0,0,0,0.6)] [text-wrap:balance] sm:text-4xl lg:text-5xl">{{ $post->name }}</h2>
            @if ($post->description)
                <p class="hidden max-w-2xl text-lg leading-relaxed text-white [text-shadow:0_1px_3px_rgba(0,0,0,0.85)] sm:line-clamp-2">{{ $post->description }}</p>
            @endif
        </span>
    </a>
</article>
