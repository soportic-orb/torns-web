@php
    $title = $shortcode->title ?: 'Últimas novedades del blog';
@endphp

<section class="py-16 lg:py-24" id="blog">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        {!! Theme::partial('components.section-heading', ['title' => $title]) !!}

        @if ($posts->isNotEmpty())
            <div class="mt-12 grid gap-6 md:grid-cols-3">
                @foreach ($posts as $post)
                    <article class="group overflow-hidden rounded-2xl border border-torns-ink/5 bg-white transition hover:-translate-y-0.5 hover:shadow-lg hover:shadow-torns-ink/5 motion-reduce:transition-none motion-reduce:hover:translate-y-0">
                        <a href="{{ $post->url }}" class="block">
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
                            <div class="p-5">
                                <time datetime="{{ $post->created_at->toDateString() }}" class="text-xs font-medium uppercase tracking-wide text-torns-ink/70">
                                    {{ $post->created_at->translatedFormat('d M, Y') }}
                                </time>
                                <h3 class="mt-2 font-display text-lg font-semibold leading-snug transition group-hover:text-torns-primary">{{ $post->name }}</h3>
                                @if ($post->description)
                                    <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-torns-ink/70">{{ $post->description }}</p>
                                @endif
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>
            <div class="mt-10 text-center">
                <a href="{{ route('public.index') . '/blog' }}" class="btn-secondary">{{ __('Visit the blog') }}</a>
            </div>
        @else
            {{-- Elegant empty state until the first posts are published --}}
            <div class="mx-auto mt-12 max-w-lg rounded-2xl border border-dashed border-torns-ink/15 bg-white p-10 text-center">
                <span class="mx-auto inline-flex h-12 w-12 items-center justify-center rounded-xl bg-torns-primary/10 text-torns-primary" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9M16.4 3.6a2 2 0 0 1 2.8 2.8L7 18.6 3 20l1.4-4Z"/></svg>
                </span>
                <h3 class="mt-4 font-display text-lg font-semibold">{{ __('Coming soon') }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-torns-ink/70">{{ __('We will soon publish news, tips and stories for shift-working healthcare professionals here.') }}</p>
            </div>
        @endif
    </div>
</section>
