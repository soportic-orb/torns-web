{!! Theme::partial('header') !!}

<div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:py-16">
    @if (Theme::get('pageId') && ! BaseHelper::isHomepage(Theme::get('pageId')))
        <header class="mb-10">
            <h1 class="font-display text-3xl font-bold tracking-tight sm:text-4xl">{{ Theme::get('title') ?: SeoHelper::getTitle() }}</h1>
            @if (Theme::get('subtitle'))
                <p class="mt-3 text-lg text-torns-ink/70">{!! Theme::get('subtitle') !!}</p>
            @endif
        </header>
    @endif

    <div class="prose-torns">
        {!! Theme::content() !!}
    </div>
</div>

{!! Theme::partial('footer') !!}
