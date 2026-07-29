<!DOCTYPE html>
<html {!! Theme::htmlAttributes() !!}>
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1" name="viewport">

        {!! Theme::header() !!}
    </head>
    <body {!! Theme::bodyAttributes() !!} class="bg-torns-bg font-sans text-torns-ink antialiased">
        {!! apply_filters(THEME_FRONT_BODY, null) !!}

        <a href="#main-content" class="skip-link">{{ __('Skip to content') }}</a>

        <header class="site-header sticky top-0 z-40 border-b border-torns-ink/5 bg-torns-bg/90 backdrop-blur" data-header>
            <div class="mx-auto flex h-16 max-w-6xl items-center justify-between gap-4 px-4 sm:px-6 lg:h-20 lg:px-8">
                <a href="{{ BaseHelper::getHomepageUrl() }}" class="flex shrink-0 items-center" aria-label="Torns">
                    <img src="{{ Theme::asset()->url('images/logo.png') }}" alt="Torns" class="h-8 w-auto lg:h-9" width="152" height="32">
                </a>

                <nav class="hidden lg:block" aria-label="{{ __('Main navigation') }}">
                    {!!
                        Menu::renderMenuLocation('main-menu', [
                            'view' => 'menu',
                            'options' => ['class' => 'flex items-center gap-1'],
                        ])
                    !!}
                </nav>

                <div class="flex items-center gap-2 sm:gap-3">
                    @if (is_plugin_active('language'))
                        <div class="hidden lg:block">
                            {!! Theme::partial('language-switcher') !!}
                        </div>
                    @endif

                    <a
                        href="{{ theme_option('app_store_url', 'https://apps.apple.com/es/app/torns/id6760354618') }}"
                        class="btn-primary hidden sm:inline-flex"
                        data-download-cta
                    >
                        {{ __('Download the app') }}
                    </a>

                    <button
                        type="button"
                        class="inline-flex h-11 w-11 items-center justify-center rounded-lg text-torns-ink transition hover:bg-torns-ink/5 lg:hidden"
                        data-drawer-open
                        aria-controls="mobile-drawer"
                        aria-expanded="false"
                    >
                        <span class="sr-only">{{ __('Open menu') }}</span>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                            <path d="M4 7h16M4 12h16M4 17h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </header>

        <div class="drawer-backdrop fixed inset-0 z-40 bg-torns-ink/40 opacity-0 transition-opacity" data-drawer-backdrop hidden></div>
        <aside
            id="mobile-drawer"
            class="drawer fixed inset-y-0 right-0 z-50 flex w-80 max-w-[85vw] translate-x-full flex-col bg-white shadow-xl transition-transform lg:hidden"
            data-drawer
            aria-label="{{ __('Main navigation') }}"
            hidden
        >
            <div class="flex h-16 items-center justify-between border-b border-torns-ink/5 px-4">
                <img src="{{ Theme::asset()->url('images/logo.png') }}" alt="Torns" class="h-7 w-auto" width="133" height="28">
                <button
                    type="button"
                    class="inline-flex h-11 w-11 items-center justify-center rounded-lg text-torns-ink transition hover:bg-torns-ink/5"
                    data-drawer-close
                >
                    <span class="sr-only">{{ __('Close menu') }}</span>
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                        <path d="m6 6 12 12M18 6 6 18" />
                    </svg>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto px-4 py-6">
                {!!
                    Menu::renderMenuLocation('main-menu', [
                        'view' => 'menu-mobile',
                        'options' => ['class' => 'flex flex-col gap-1'],
                    ])
                !!}

                @if (is_plugin_active('language'))
                    <div class="mt-6 border-t border-torns-ink/5 pt-6">
                        <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-torns-ink/50">{{ __('Language') }}</p>
                        {!! Theme::partial('language-switcher', ['inline' => true]) !!}
                    </div>
                @endif
            </div>
            <div class="border-t border-torns-ink/5 p-4">
                <a href="{{ theme_option('app_store_url', 'https://apps.apple.com/es/app/torns/id6760354618') }}" class="btn-primary w-full justify-center">
                    {{ __('Download the app') }}
                </a>
            </div>
        </aside>

        <main id="main-content">
            @if (session()->has('success_msg') || session()->has('error_msg') || $errors->getBag('contact')->any())
                <div class="px-4 sm:px-6">
                    @if (session()->has('success_msg'))
                        <p class="flash-message flash-message--success" role="status">{{ session('success_msg') }}</p>
                    @endif
                    @if (session()->has('error_msg'))
                        <p class="flash-message flash-message--error" role="alert">{{ session('error_msg') }}</p>
                    @endif
                    @foreach ($errors->getBag('contact')->all() as $error)
                        <p class="flash-message flash-message--error" role="alert">{{ $error }}</p>
                    @endforeach
                </div>
            @endif
