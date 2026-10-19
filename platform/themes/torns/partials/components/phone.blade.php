@php
    // App screenshot. Params: $name = file base in images/home/screens
    // (served at two widths: $small and twice that), $alt, $class,
    // $framed (bool, wrap a raw screenshot in a CSS phone frame; the store
    // mockups already include their frame), $sizes, $eager (bool).
    $framed = $framed ?? false;
    $small = $small ?? ($framed ? 300 : 280);
    $large = $small * 2;
    $ratio = $framed ? 2000 / 920 : 1132 / 560;
    $base = 'images/home/screens/' . $name;
    $src = Theme::asset()->url("{$base}-{$large}.webp");
    $srcset = Theme::asset()->url("{$base}-{$small}.webp") . " {$small}w, {$src} {$large}w";
    $sizes = $sizes ?? '(min-width: 1024px) 16rem, 60vw';
    $eager = $eager ?? false;
@endphp
<div @class([$framed ? 'phone-frame' : 'relative', $class ?? ''])>
    <img
        src="{{ $src }}"
        srcset="{{ $srcset }}"
        sizes="{{ $sizes }}"
        alt="{{ $alt ?? '' }}"
        width="{{ $large }}"
        height="{{ (int) round($large * $ratio) }}"
        @if ($eager) fetchpriority="high" @else loading="lazy" @endif
        @class(['block h-auto w-full', 'drop-shadow-xl' => ! $framed])
    >
</div>
