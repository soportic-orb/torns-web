@php
    $title = $shortcode->title ?: __('IA que trabaja para ti');
    $subtitle = $shortcode->subtitle ?: __('Incluida en Torns Pro y en los 30 días de prueba.');
    $asset = fn (string $base) => [
        'src' => Theme::asset()->url("images/home/{$base}-896.webp"),
        'srcset' => Theme::asset()->url("images/home/{$base}-480.webp") . ' 480w, ' . Theme::asset()->url("images/home/{$base}-896.webp") . ' 896w',
    ];
    $cards = [
        [
            'eyebrow' => 'Copilot', 'tone' => 'bg-torns-mist', 'accent' => 'text-torns-primary-dark',
            'title' => __('Tu asistente de turnos'),
            'text' => __('Cruza tu calendario, tu disponibilidad y los turnos publicados por tus compañeros para encontrar el cambio que te encaja. Aprende tus rutinas y mantiene tus calendarios sincronizados.'),
            'image' => $asset('row-copilot-phone'), 'alt' => __('Copilot en la app Torns'), 'height' => 1639,
        ],
        [
            'eyebrow' => __('Consultor legal'), 'tone' => 'bg-[#E6EFE0]', 'accent' => 'text-[#3F6E2A]',
            'title' => __('Tu convenio, con el artículo exacto'),
            'text' => __('Permisos, jornada y descansos: busca la respuesta en el convenio o la normativa que te aplica y te dice de qué artículo sale.'),
            'image' => $asset('row-legal'), 'alt' => __('Consultor legal en la app Torns'), 'height' => 1645,
        ],
    ];
@endphp

<section class="pb-16 lg:pb-24" id="ia">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        {!! Theme::partial('components.section-heading', ['title' => $title, 'subtitle' => $subtitle, 'align' => 'left']) !!}
        <div class="mt-10 grid gap-6 lg:grid-cols-2">
            @foreach ($cards as $card)
                <article class="grid items-center gap-6 overflow-hidden rounded-[2rem] {{ $card['tone'] }} p-8 sm:grid-cols-[1fr_11rem] sm:p-10">
                    <div>
                        <p class="mb-3 text-sm font-bold uppercase tracking-widest {{ $card['accent'] }}">{{ $card['eyebrow'] }} · Pro</p>
                        <h3 class="font-display text-2xl font-bold leading-snug">{{ $card['title'] }}</h3>
                        <p class="mt-3 leading-relaxed text-torns-ink/75">{{ $card['text'] }}</p>
                    </div>
                    <img src="{{ $card['image']['src'] }}" srcset="{{ $card['image']['srcset'] }}" sizes="(min-width: 640px) 11rem, 60vw" alt="{{ $card['alt'] }}" class="mx-auto -mb-16 w-44 drop-shadow-xl sm:mb-0 sm:w-full" width="896" height="{{ $card['height'] }}" loading="lazy">
                </article>
            @endforeach
        </div>
    </div>
</section>
