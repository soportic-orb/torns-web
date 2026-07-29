@php
    $title = $shortcode->title ?: __('Descubre Torns en 30 segundos');
    $videoUrl = $shortcode->video_url ?: Theme::asset()->url('videos/presentation-es.mp4');
    $poster = $shortcode->poster ? RvMedia::getImageUrl($shortcode->poster) : Theme::asset()->url('images/home/video-poster.jpg');
@endphp

<section class="py-16 lg:py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        {!! Theme::partial('components.section-heading', ['title' => $title]) !!}

        <div class="mx-auto mt-10 max-w-xs overflow-hidden rounded-3xl border border-torns-ink/10 bg-torns-ink shadow-xl sm:max-w-sm">
            {{-- preload=none + poster: video bytes are only fetched on play --}}
            <video
                controls
                preload="none"
                playsinline
                poster="{{ $poster }}"
                class="aspect-[9/16] w-full"
            >
                <source src="{{ $videoUrl }}" type="video/mp4">
                {{ __('Your browser does not support the video tag.') }}
            </video>
        </div>
    </div>
</section>
