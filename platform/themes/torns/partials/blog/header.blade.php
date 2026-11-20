@php
    // Blog header with the category filter, shared by the blog page and the category pages.
    $blogUrl = torns_blog_url();
    $categories = torns_blog_categories();
    $activeId = isset($category) ? (int) $category->getKey() : null;
    $activeName = $activeId ? (collect($categories)->firstWhere('id', $activeId)['name'] ?? $category->name) : null;
    $pill = 'inline-flex min-h-11 items-center whitespace-nowrap rounded-full border px-4 text-sm font-semibold transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-torns-primary';
@endphp

<header class="flex flex-col gap-6">
    <div>
        <p class="text-sm font-semibold uppercase tracking-widest text-torns-primary">{{ __('Blog') }}</p>
        <h1 class="mt-2 font-display text-3xl font-bold leading-tight tracking-tight [text-wrap:balance] sm:text-4xl lg:text-5xl">{{ $activeName ?: __('Noticias y consejos para quien trabaja a turnos') }}</h1>
        {{-- A category without a description still says what it holds: the
             same sentence as its SEO description. --}}
        @if ($activeId)
            <p class="mt-4 max-w-2xl leading-relaxed text-torns-ink/70">{{ $category->description ?: __('Publicaciones del blog de Torns sobre :name, para profesionales sanitarios que trabajan a turnos y guardias.', ['name' => $activeName]) }}</p>
        @endif
    </div>

    @if ($categories)
        <nav aria-label="{{ __('Categorías') }}" class="-mx-4 overflow-x-auto px-4 pb-1 sm:mx-0 sm:px-0">
            <ul class="flex list-none gap-2 sm:flex-wrap">
                <li>
                    <a href="{{ $blogUrl }}" @if (! $activeId) aria-current="page" @endif @class([$pill, 'border-torns-ink bg-torns-ink text-white' => ! $activeId, 'border-torns-ink/15 bg-white text-torns-ink/80 hover:border-torns-primary hover:text-torns-primary' => $activeId])>{{ __('Todas las publicaciones') }}</a>
                </li>
                @foreach ($categories as $item)
                    <li>
                        <a href="{{ $item['url'] }}" @if ($activeId === $item['id']) aria-current="page" @endif @class([$pill, 'border-torns-ink bg-torns-ink text-white' => $activeId === $item['id'], 'border-torns-ink/15 bg-white text-torns-ink/80 hover:border-torns-primary hover:text-torns-primary' => $activeId !== $item['id']])>{{ $item['name'] }}</a>
                    </li>
                @endforeach
            </ul>
        </nav>
    @endif
</header>
