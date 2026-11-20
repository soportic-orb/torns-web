@php
    $title = $shortcode->title ?: __('Preguntas frecuentes');

    $items = [];
    for ($i = 1; $i <= 10; $i++) {
        if ($shortcode->{'question_' . $i} && $shortcode->{'answer_' . $i}) {
            $items[] = ['question' => $shortcode->{'question_' . $i}, 'answer' => $shortcode->{'answer_' . $i}];
        }
    }

    if (! $items) {
        $items = torns_default_faq_items();
    }
@endphp

<section class="py-16 lg:py-24" id="faq">
    <div class="mx-auto max-w-3xl px-4 sm:px-6">
        {!! Theme::partial('components.section-heading', ['title' => $title]) !!}

        <div class="mt-10 flex flex-col gap-3">
            @foreach ($items as $index => $item)
                {!! Theme::partial('components.faq-item', [
                    'question' => $item['question'],
                    'answer' => e($item['answer']),
                    'open' => $index === 0,
                ]) !!}
            @endforeach
        </div>
    </div>
</section>
