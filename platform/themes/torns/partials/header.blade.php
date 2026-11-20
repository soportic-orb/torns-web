<!DOCTYPE html>
<html {!! Theme::htmlAttributes() !!}>
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1" name="viewport">

        <link rel="preload" href="{{ Theme::asset()->url('fonts/poppins-700-latin.woff2') }}" as="font" type="font/woff2" crossorigin>
        <link rel="preload" href="{{ Theme::asset()->url('fonts/instrument-sans-normal-latin.woff2') }}" as="font" type="font/woff2" crossorigin>

        {!! torns_social_meta(torns_strip_default_schemas(Theme::header())) !!}
    </head>
    <body {!! Theme::bodyAttributes() !!} class="bg-torns-bg font-sans text-torns-ink antialiased">
        {!! apply_filters(THEME_FRONT_BODY, null) !!}

        <a href="#main-content" class="skip-link">{{ __('Skip to content') }}</a>

        @php
            $localePrefix = torns_current_locale() === 'es' ? '' : torns_current_locale() . '/';
            $centersUrl = url($localePrefix . 'centros');
            $associationsUrl = url($localePrefix . 'asociaciones-profesionales');
        @endphp

        <header class="site-header sticky top-0 z-40 border-b border-torns-ink/5 bg-torns-bg/90 backdrop-blur transition-colors duration-200" data-header>
            <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:h-20 lg:px-8">
                <a href="{{ BaseHelper::getHomepageUrl() }}" class="flex shrink-0 items-center" aria-label="Torns">
                    <img src="{{ Theme::asset()->url('images/logo.webp') }}" alt="Torns" class="h-8 w-auto lg:h-9" width="152" height="32">
                </a>

                <nav class="hidden xl:block" aria-label="{{ __('Main navigation') }}">
                    {!!
                        Menu::renderMenuLocation('main-menu', [
                            'view' => 'menu',
                            'options' => ['class' => 'flex items-center'],
                        ])
                    !!}
                </nav>

                <div class="flex items-center gap-2 sm:gap-3">
                    @if (is_plugin_active('language'))
                        <div class="hidden xl:block">
                            {!! Theme::partial('language-switcher') !!}
                        </div>
                    @endif

                    {{-- «Para organizaciones»: centres and professional associations. --}}
                    <details class="org-menu relative hidden sm:block" data-org-menu>
                        <summary class="btn-dark cursor-pointer list-none whitespace-nowrap xl:px-4 [&::-webkit-details-marker]:hidden">
                            {{ __('For organizations') }}
                            <svg class="ml-1 h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                        </summary>
                        <div class="absolute right-0 z-50 mt-2 w-64 rounded-2xl bg-white p-2 shadow-xl ring-1 ring-torns-ink/5">
                            <a href="{{ $centersUrl }}" class="block rounded-xl px-4 py-3 transition hover:bg-torns-bg">
                                <span class="block text-sm font-semibold text-torns-ink">{{ __('Healthcare centres') }}</span>
                                <span class="mt-0.5 block text-xs text-torns-ink/60">{{ __('Hospitals, health centres and emergency services') }}</span>
                            </a>
                            <a href="{{ $associationsUrl }}" class="block rounded-xl px-4 py-3 transition hover:bg-torns-bg">
                                <span class="block text-sm font-semibold text-torns-ink">{{ __('Professional associations') }}</span>
                                <span class="mt-0.5 block text-xs text-torns-ink/60">{{ __('Unions, professional colleges and scientific societies') }}</span>
                            </a>
                        </div>
                    </details>

                    <a
                        href="{{ theme_option('app_store_url', 'https://apps.apple.com/es/app/torns/id6760354618') }}"
                        class="btn-primary hidden whitespace-nowrap sm:inline-flex xl:px-4"
                        data-download-cta
                    >
                        {{ __('Download the app') }}
                    </a>

                    <button
                        type="button"
                        class="inline-flex h-11 w-11 items-center justify-center rounded-lg text-torns-ink transition hover:bg-torns-ink/5 xl:hidden"
                        data-drawer-open
                        aria-controls="mobile-drawer"
                        aria-expanded="false"
                    >
                        <span class="sr-only">{{ __('Open menu') }}</span>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 6h16" />
                            <path d="M7 12h13" />
                            <path d="M10 18h10" />
                        </svg>
                    </button>
                </div>
            </div>
        </header>

        <div class="drawer-backdrop fixed inset-0 z-40 bg-torns-ink/40 opacity-0 transition-opacity" data-drawer-backdrop hidden></div>
        <aside
            id="mobile-drawer"
            class="drawer fixed inset-y-0 right-0 z-50 flex w-80 max-w-[85vw] translate-x-full flex-col bg-white shadow-xl transition-transform xl:hidden"
            data-drawer
            aria-label="{{ __('Main navigation') }}"
            hidden
        >
            {{-- No logo here: the bar behind the drawer already shows it. --}}
            <div class="flex h-16 items-center justify-end border-b border-torns-ink/5 px-4">
                <button
                    type="button"
                    class="inline-flex h-11 w-11 items-center justify-center text-torns-ink transition hover:text-torns-primary"
                    data-drawer-close
                >
                    <span class="sr-only">{{ __('Close menu') }}</span>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M18 6l-12 12" />
                        <path d="M6 6l12 12" />
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
                        <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-torns-ink/70">{{ __('Language') }}</p>
                        {!! Theme::partial('language-switcher', ['inline' => true]) !!}
                    </div>
                @endif
            </div>
            <div class="flex flex-col gap-2 border-t border-torns-ink/5 p-4">
                <a href="{{ $centersUrl }}" class="btn-dark w-full justify-center">
                    {{ __('For centers') }}
                </a>
                <a href="{{ $associationsUrl }}" class="btn-dark w-full justify-center">
                    {{ __('For professional associations') }}
                </a>
                <a href="{{ theme_option('app_store_url', 'https://apps.apple.com/es/app/torns/id6760354618') }}" class="btn-primary w-full justify-center">
                    {{ __('Download the app') }}
                </a>
            </div>
        </aside>

        <main id="main-content">
            @php
                // The contact page shows its own confirmation screen instead.
                $successFlash = session()->has('success_msg') && ! request()->is('contacta', '*/contacta');
            @endphp
            @if ($successFlash || session()->has('error_msg') || $errors->getBag('contact')->any())
                <div class="px-4 sm:px-6">
                    @if ($successFlash)
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
