@php Theme::layout('full-width'); @endphp

{{-- Design system preview — non-production route for visual QA of theme components. --}}
<div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
    <header class="mb-12">
        <h1 class="font-display text-4xl font-bold tracking-tight">Torns — Design system</h1>
        <p class="mt-2 text-torns-ink/60">Componentes del tema. Ruta solo disponible fuera de producción.</p>
    </header>

    <section class="mb-14">
        <h2 class="mb-6 font-display text-2xl font-semibold">Colores</h2>
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 lg:grid-cols-7">
            @foreach ([
                'torns-primary' => '#0E7E8A',
                'torns-primary-dark' => '#0A5A63',
                'torns-bg' => '#FAFBFB',
                'torns-ink' => '#16232A',
                'shift-morning' => '#F5A623',
                'shift-evening' => '#F0685B',
                'shift-night' => '#4A5BC7',
            ] as $name => $hex)
                <div class="overflow-hidden rounded-xl border border-torns-ink/10">
                    <div class="h-16" style="background: {{ $hex }}"></div>
                    <div class="bg-white p-2.5">
                        <p class="text-xs font-semibold">{{ $name }}</p>
                        <p class="text-xs text-torns-ink/50">{{ $hex }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="mb-14">
        <h2 class="mb-6 font-display text-2xl font-semibold">Tipografías</h2>
        <div class="grid gap-6 rounded-2xl border border-torns-ink/10 bg-white p-8 lg:grid-cols-2">
            <div>
                <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-torns-ink/50">Bricolage Grotesque — display</p>
                <p class="font-display text-4xl font-bold">Cambia turnos con tus compañeros</p>
                <p class="mt-2 font-display text-2xl font-semibold">Y gestiona el calendario de todos tus centros</p>
            </div>
            <div>
                <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-torns-ink/50">Instrument Sans — cuerpo</p>
                <p class="text-base leading-relaxed">Con Torns no necesitas una app para tu calendario y otra para tus cambios. Publica el turno que no puedes hacer y deja que tus compañeros del mismo centro te propongan un cambio.</p>
                <p class="mt-2 text-sm leading-relaxed text-torns-ink/70">Texto secundario en tamaño pequeño con color atenuado para descripciones y notas.</p>
            </div>
        </div>
    </section>

    <section class="mb-14">
        <h2 class="mb-6 font-display text-2xl font-semibold">Shift chips (M / T / N)</h2>
        <div class="rounded-2xl border border-torns-ink/10 bg-white p-8">
            <div class="flex flex-wrap items-end gap-6">
                <div class="flex items-center gap-3">
                    {!! Theme::partial('components.shift-chip', ['type' => 'morning', 'size' => 'sm']) !!}
                    {!! Theme::partial('components.shift-chip', ['type' => 'evening', 'size' => 'sm']) !!}
                    {!! Theme::partial('components.shift-chip', ['type' => 'night', 'size' => 'sm']) !!}
                </div>
                <div class="flex items-center gap-3">
                    {!! Theme::partial('components.shift-chip', ['type' => 'morning', 'time' => '07–15']) !!}
                    {!! Theme::partial('components.shift-chip', ['type' => 'evening', 'time' => '15–23']) !!}
                    {!! Theme::partial('components.shift-chip', ['type' => 'night', 'time' => '23–07']) !!}
                </div>
                <div class="flex items-center gap-3">
                    {!! Theme::partial('components.shift-chip', ['type' => 'morning', 'time' => '07:00 – 15:00', 'size' => 'lg']) !!}
                    {!! Theme::partial('components.shift-chip', ['type' => 'evening', 'time' => '15:00 – 23:00', 'size' => 'lg']) !!}
                    {!! Theme::partial('components.shift-chip', ['type' => 'night', 'time' => '23:00 – 07:00', 'size' => 'lg']) !!}
                </div>
            </div>

            <div class="mt-10 border-t border-torns-ink/5 pt-8">
                <p class="mb-4 text-xs font-semibold uppercase tracking-widest text-torns-ink/50">Animación de intercambio (hero)</p>
                <div class="swap-demo mx-auto max-w-sm" aria-label="Dos turnos intercambiándose entre dos personas">
                    <div class="swap-demo__row">
                        <span class="swap-demo__avatar" aria-hidden="true">A</span>
                        <span class="swap-demo__chip swap-demo__chip--a">
                            {!! Theme::partial('components.shift-chip', ['type' => 'morning', 'time' => '07–15']) !!}
                        </span>
                        <span class="swap-demo__arrows" aria-hidden="true">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3l4 4-4 4M21 7H7M7 21l-4-4 4-4M3 17h14"/></svg>
                        </span>
                        <span class="swap-demo__chip swap-demo__chip--b">
                            {!! Theme::partial('components.shift-chip', ['type' => 'night', 'time' => '23–07']) !!}
                        </span>
                        <span class="swap-demo__avatar swap-demo__avatar--b" aria-hidden="true">B</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mb-14">
        <h2 class="mb-6 font-display text-2xl font-semibold">Section heading</h2>
        <div class="rounded-2xl border border-torns-ink/10 bg-white p-8">
            {!! Theme::partial('components.section-heading', [
                'eyebrow' => 'Funcionalidades',
                'title' => 'Todo lo que necesitas para tus turnos',
                'subtitle' => 'Cambios de turno con tus compañeros y un calendario unificado de todos tus centros, en una sola app.',
            ]) !!}
        </div>
    </section>

    <section class="mb-14">
        <h2 class="mb-6 font-display text-2xl font-semibold">Feature cards</h2>
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            {!! Theme::partial('components.feature-card', [
                'accent' => 'morning',
                'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>',
                'title' => 'Tus turnos',
                'description' => 'Registra tus turnos en segundos y tenlos siempre a mano, estés donde estés.',
            ]) !!}
            {!! Theme::partial('components.feature-card', [
                'accent' => 'evening',
                'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3l4 4-4 4M21 7H7M7 21l-4-4 4-4M3 17h14"/></svg>',
                'title' => 'Muro de publicaciones',
                'description' => 'Publica el turno que no puedes hacer y deja que tus compañeros te propongan un cambio.',
            ]) !!}
            {!! Theme::partial('components.feature-card', [
                'accent' => 'night',
                'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22a10 10 0 1 1 10-10c0 .5 0 1-.1 1.4a5.5 5.5 0 0 1-9.9 4.4A10 10 0 0 1 12 22Z"/></svg>',
                'title' => 'Multi-centro',
                'description' => 'Trabajas en varios centros: gestiona los turnos de todos desde un único calendario.',
            ]) !!}
        </div>
    </section>

    <section class="mb-14">
        <h2 class="mb-6 font-display text-2xl font-semibold">Pricing cards</h2>
        <div class="grid gap-6 pt-4 sm:grid-cols-2 lg:grid-cols-3">
            {!! Theme::partial('components.pricing-card', [
                'name' => 'Free',
                'price' => '0 €',
                'period' => 'para siempre',
                'features' => ['Tus turnos y disponibilidad', 'Cambios en el Muro', 'Un centro de trabajo'],
                'cta_label' => 'Empieza gratis',
                'cta_url' => '#',
            ]) !!}
            {!! Theme::partial('components.pricing-card', [
                'name' => 'Pro Anual',
                'price' => '29,90 €',
                'period' => '/año',
                'badge' => 'Ahorras 2 meses',
                'highlighted' => true,
                'features' => ['Todo lo de Free', 'Multi-centro ilimitado', 'Sincronización Apple/Google Calendar', 'Notificaciones avanzadas'],
                'cta_label' => 'Hazte Pro',
                'cta_url' => '#',
                'note' => 'Sin permanencia. Cancela cuando quieras.',
            ]) !!}
            {!! Theme::partial('components.pricing-card', [
                'name' => 'Copilot',
                'price' => '—',
                'features' => ['Cadenas de cambios a 3 y 4 con IA', 'Sugerencias automáticas'],
                'cta_label' => 'Próximamente',
                'disabled' => true,
            ]) !!}
        </div>
    </section>

    <section class="mb-14">
        <h2 class="mb-6 font-display text-2xl font-semibold">Store badges</h2>
        <div class="rounded-2xl border border-torns-ink/10 bg-white p-8">
            {!! Theme::partial('components.store-badges') !!}
        </div>
    </section>

    <section class="mb-14">
        <h2 class="mb-6 font-display text-2xl font-semibold">FAQ items</h2>
        <div class="flex max-w-2xl flex-col gap-3">
            {!! Theme::partial('components.faq-item', [
                'question' => '¿Qué es Torns?',
                'answer' => 'Torns es la app que permite a los profesionales sanitarios cambiar turnos con sus compañeros y gestionar el calendario de todos sus centros desde un único lugar.',
                'open' => true,
            ]) !!}
            {!! Theme::partial('components.faq-item', [
                'question' => '¿Cuánto cuesta?',
                'answer' => 'Puedes usar Torns gratis. El plan Pro cuesta 2,99 €/mes o 29,90 €/año.',
            ]) !!}
            {!! Theme::partial('components.faq-item', [
                'question' => '¿Necesito que mi centro contrate nada?',
                'answer' => 'No. Torns funciona entre compañeros sin que la empresa tenga que contratar ningún software.',
            ]) !!}
        </div>
    </section>

    <section>
        <h2 class="mb-6 font-display text-2xl font-semibold">Botones</h2>
        <div class="flex flex-wrap items-center gap-4 rounded-2xl border border-torns-ink/10 bg-white p-8">
            <a href="#" class="btn-primary">Descarga la app</a>
            <a href="#" class="btn-secondary">Saber más</a>
            <a href="#" class="btn-ghost">Enlace ghost</a>
        </div>
    </section>
</div>
