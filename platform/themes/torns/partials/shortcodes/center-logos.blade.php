@php
    $title = $shortcode->title ?: 'Los profesionales de estos centros ya utilizan Torns';

    $logos = [];
    for ($i = 1; $i <= 6; $i++) {
        if ($shortcode->{'logo_' . $i}) {
            $logos[] = RvMedia::getImageUrl($shortcode->{'logo_' . $i});
        }
    }

    if (! $logos) {
        $logos = [
            Theme::asset()->url('images/home/logos/logo-emergencies-mediques.webp'),
            Theme::asset()->url('images/home/logos/logo-center-2.webp'),
            Theme::asset()->url('images/home/logos/logo-vall-hebron.webp'),
            Theme::asset()->url('images/home/logos/logo-center-4.webp'),
        ];
    }
@endphp

<section class="border-y border-torns-ink/5 bg-white py-12">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <p class="text-center text-sm font-medium text-torns-ink/70">{{ $title }}</p>
        <ul class="mt-8 flex flex-wrap items-center justify-center gap-x-12 gap-y-6">
            @foreach ($logos as $logo)
                <li>
                    <img src="{{ $logo }}" alt="" class="h-10 w-auto opacity-70 grayscale transition hover:opacity-100 hover:grayscale-0 sm:h-12" loading="lazy">
                </li>
            @endforeach
        </ul>
    </div>
</section>
