@if ($posts->isNotEmpty())
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($posts as $post)
            {!! Theme::partial('blog.post-card', compact('post')) !!}
        @endforeach
    </div>

    <div class="torns-pagination mt-10">
        {!! $posts->withQueryString()->links() !!}
    </div>
@else
    <div class="mx-auto max-w-lg rounded-2xl border border-dashed border-torns-ink/15 bg-white p-10 text-center">
        <span class="mx-auto inline-flex h-12 w-12 items-center justify-center rounded-xl bg-torns-primary/10 text-torns-primary" aria-hidden="true">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9M16.4 3.6a2 2 0 0 1 2.8 2.8L7 18.6 3 20l1.4-4Z"/></svg>
        </span>
        <h2 class="mt-4 font-display text-lg font-semibold">{{ __('Coming soon') }}</h2>
        <p class="mt-2 text-sm leading-relaxed text-torns-ink/60">{{ __('We will soon publish news, tips and stories for shift-working healthcare professionals here.') }}</p>
    </div>
@endif
