@php Theme::layout('full-width'); @endphp

<div class="mx-auto flex max-w-2xl flex-col items-center px-4 py-24 text-center sm:px-6">
    <div class="flex items-center gap-3" aria-hidden="true">
        {!! Theme::partial('components.shift-chip', ['type' => 'morning', 'label' => '4', 'size' => 'lg']) !!}
        {!! Theme::partial('components.shift-chip', ['type' => 'evening', 'label' => '0', 'size' => 'lg']) !!}
        {!! Theme::partial('components.shift-chip', ['type' => 'night', 'label' => '4', 'size' => 'lg']) !!}
    </div>
    <h1 class="mt-8 font-display text-3xl font-bold tracking-tight">{{ __('Page not found') }}</h1>
    <p class="mt-3 text-torns-ink/70">{{ __('The page you are looking for does not exist or has been moved.') }}</p>
    <a href="{{ BaseHelper::getHomepageUrl() }}" class="btn-primary mt-8">{{ __('Back to home') }}</a>
</div>
