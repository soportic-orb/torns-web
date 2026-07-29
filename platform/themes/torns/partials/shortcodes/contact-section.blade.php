@php
    $title = $shortcode->title ?: 'Contacta con nosotros';
    $subtitle = $shortcode->subtitle ?: 'El equipo de Torns está a tu disposición. Utiliza el formulario para iniciar una conversación con nosotros.';
    $email = theme_option('contact_email', 'hola@torns.app');
    $supportUrl = theme_option('support_url', 'https://soporte.torns.app');
@endphp

<section class="py-14 lg:py-20">
    <div class="mx-auto grid max-w-6xl gap-12 px-4 sm:px-6 lg:grid-cols-[1fr_1.4fr] lg:gap-16 lg:px-8">
        <div>
            <h1 class="font-display text-3xl font-bold tracking-tight sm:text-4xl">{{ $title }}</h1>
            <p class="mt-4 leading-relaxed text-torns-ink/70">{{ $subtitle }}</p>

            <dl class="mt-8 flex flex-col gap-5">
                <div class="flex items-start gap-4">
                    <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-torns-primary/10 text-torns-primary" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v16H4z"/><path d="m4 7 8 6 8-6"/></svg>
                    </span>
                    <div>
                        <dt class="text-sm font-semibold">{{ __('Email') }}</dt>
                        <dd><a class="footer-link text-sm" href="mailto:{{ $email }}">{{ $email }}</a></dd>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-torns-primary/10 text-torns-primary" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 8v4M12 16h.01"/></svg>
                    </span>
                    <div>
                        <dt class="text-sm font-semibold">{{ __('Support center') }}</dt>
                        <dd><a class="footer-link text-sm" href="{{ $supportUrl }}" rel="noopener">{{ str_replace(['https://', 'http://'], '', $supportUrl) }}</a></dd>
                    </div>
                </div>
            </dl>
        </div>

        <div class="torns-contact-form rounded-2xl border border-torns-ink/10 bg-white p-6 sm:p-8">
            {!!
                \Botble\Contact\Forms\Fronts\ContactForm::createFromArray([
                    'display_fields' => 'email',
                    'mandatory_fields' => 'email',
                ])->renderForm()
            !!}
        </div>
    </div>
</section>
