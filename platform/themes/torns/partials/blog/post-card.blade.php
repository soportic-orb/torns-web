<article class="group flex h-full flex-col overflow-hidden rounded-2xl border border-torns-ink/5 bg-white transition hover:-translate-y-0.5 hover:shadow-lg hover:shadow-torns-ink/5 motion-reduce:transition-none motion-reduce:hover:translate-y-0">
    <a href="{{ $post->url }}" class="flex h-full flex-col">
        @if ($post->image)
            <img
                src="{{ RvMedia::getImageUrl($post->image, 'medium', false, RvMedia::getDefaultImage()) }}"
                alt="{{ $post->name }}"
                class="aspect-[16/9] w-full object-cover"
                width="600"
                height="338"
                loading="lazy"
            >
        @endif
        <div class="flex flex-1 flex-col p-5">
            <div class="flex items-center gap-3 text-xs font-medium uppercase tracking-wide text-torns-ink/50">
                <time datetime="{{ $post->created_at->toDateString() }}">{{ $post->created_at->translatedFormat('d M, Y') }}</time>
                @if ($post->categories->isNotEmpty())
                    <span class="text-torns-primary">{{ $post->categories->first()->name }}</span>
                @endif
            </div>
            <h3 class="mt-2 font-display text-lg font-semibold leading-snug transition group-hover:text-torns-primary">{{ $post->name }}</h3>
            @if ($post->description)
                <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-torns-ink/70">{{ $post->description }}</p>
            @endif
        </div>
    </a>
</article>
