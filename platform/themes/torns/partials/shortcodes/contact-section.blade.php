@php
    $title = $shortcode->title ?: __('Contacta con nosotros');
    $subtitle = $shortcode->subtitle ?: __('El equipo de Torns está a tu disposición. Utiliza el formulario para iniciar una conversación con nosotros.');
    $email = theme_option('contact_email', 'hola@torns.app');
    $supportUrl = theme_option('support_url', 'https://soporte.torns.app');
    // Without JavaScript the form posts and comes back with the success message.
    $sent = session()->has('success_msg');
    $home = torns_current_locale() === 'es' ? url('/') : url(torns_current_locale());
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

{{-- Confirmation screen: shown by main.js when the form is sent (contact-form.submitted). --}}
<div
    class="contact-sent fixed inset-0 z-[60] flex items-center justify-center bg-white/95 px-6 backdrop-blur-sm"
    role="dialog"
    aria-modal="true"
    aria-labelledby="contact-sent-title"
    data-contact-sent
    @unless ($sent) hidden @endunless
>
    <canvas class="pointer-events-none fixed inset-0 h-full w-full" data-confetti aria-hidden="true"></canvas>
    <div class="relative mx-auto max-w-2xl text-center">
        <p class="contact-sent__emoji text-7xl sm:text-8xl" aria-hidden="true">🎉</p>
        <h2 class="mt-6 font-display text-3xl font-bold leading-tight tracking-tight [text-wrap:balance] sm:text-4xl lg:text-5xl" id="contact-sent-title" tabindex="-1" style="outline: none">{{ __('¡Genial! Hemos recibido tu mensaje y contactaremos contigo en breve.') }}</h2>
        <div class="mt-10 flex flex-wrap items-center justify-center gap-3">
            <a href="{{ $home }}" class="btn-primary px-6 py-3 text-base">{{ __('Volver al inicio') }}</a>
            <button type="button" class="btn-secondary px-6 py-3 text-base" data-contact-sent-close>{{ __('Cerrar') }}</button>
        </div>
    </div>
</div>
