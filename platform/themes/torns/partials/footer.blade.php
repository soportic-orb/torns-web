        </main>

        <footer class="border-t border-torns-ink/5 bg-white">
            <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
                <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-4">
                    <div class="lg:col-span-2">
                        <img src="{{ Theme::asset()->url('images/logo.png') }}" alt="Torns" class="h-8 w-auto" width="152" height="32">
                        <p class="mt-4 max-w-sm text-sm leading-relaxed text-torns-ink/70">
                            {{ __('Swap shifts with your colleagues and manage the calendar of all your workplaces from a single app.') }}
                        </p>
                        <div class="mt-6">
                            {!! Theme::partial('components.store-badges', ['size' => 'sm']) !!}
                        </div>
                    </div>

                    <nav aria-label="{{ __('Footer navigation') }}">
                        <p class="mb-4 text-sm font-semibold">{{ __('Explore') }}</p>
                        {!!
                            Menu::renderMenuLocation('footer-menu', [
                                'view' => 'menu-footer',
                                'options' => ['class' => 'flex flex-col gap-2.5'],
                            ])
                        !!}
                    </nav>

                    <div>
                        <p class="mb-4 text-sm font-semibold">{{ __('Contact') }}</p>
                        <ul class="flex flex-col gap-2.5 text-sm">
                            <li>
                                <a class="footer-link" href="mailto:{{ theme_option('contact_email', 'hola@torns.app') }}">
                                    {{ theme_option('contact_email', 'hola@torns.app') }}
                                </a>
                            </li>
                            <li>
                                <a class="footer-link" href="{{ theme_option('support_url', 'https://soporte.torns.app') }}" rel="noopener">
                                    {{ __('Support center') }}
                                </a>
                            </li>
                        </ul>

                        @if (is_plugin_active('language'))
                            <p class="mb-3 mt-8 text-sm font-semibold">{{ __('Language') }}</p>
                            {!! Theme::partial('language-switcher', ['inline' => true]) !!}
                        @endif
                    </div>
                </div>

                <div class="mt-12 flex flex-col gap-4 border-t border-torns-ink/5 pt-6 text-sm text-torns-ink/60 sm:flex-row sm:items-center sm:justify-between">
                    <p>{!! Theme::getSiteCopyright() ?: '&copy; ' . date('Y') . ' Torns' !!}</p>
                    {!!
                        Menu::renderMenuLocation('footer-legal-menu', [
                            'view' => 'menu-footer',
                            'options' => ['class' => 'flex flex-wrap gap-x-6 gap-y-2'],
                        ])
                    !!}
                </div>
            </div>
        </footer>

        {!! Theme::footer() !!}
    </body>
</html>
