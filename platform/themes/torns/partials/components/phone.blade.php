@php
    // App screenshot, without a phone frame: rounded corners and a soft shadow.
    // Params: $name = file base in images/home/screens, $alt, $class, $sizes,
    // $eager (bool), $tilt = 'left' | 'right' | null (a slight turn, for one
    // screen of a pair). The store mockups (screen-<n>) are served from their
    // extracted screens (screen-<n>-flat-240/480); the other screenshots at
    // $small and twice that.
    $flat = (bool) preg_match('/^screen-\d+$/', $name);
    $small = $flat ? 240 : ($small ?? 300);
    $large = $small * 2;
    $ratio = $flat ? 1054 / 482 : 2000 / 920;
    $base = 'images/home/screens/' . $name . ($flat ? '-flat' : '');
    $src = torns_asset("{$base}-{$large}.webp");
    $srcset = torns_asset("{$base}-{$small}.webp") . " {$small}w, {$src} {$large}w";
    $sizes = $sizes ?? '(min-width: 1024px) 16rem, 60vw';
    $eager = $eager ?? false;
    $tilt = $tilt ?? null;
@endphp
<div @class(['app-screen', 'app-screen--tilt-left' => $tilt === 'left', 'app-screen--tilt-right' => $tilt === 'right', $class ?? ''])>
    <img
        src="{{ $src }}"
        srcset="{{ $srcset }}"
        sizes="{{ $sizes }}"
        alt="{{ $alt ?? '' }}"
        width="{{ $large }}"
        height="{{ (int) round($large * $ratio) }}"
        @if ($eager) fetchpriority="high" @else loading="lazy" @endif
        class="block h-auto w-full"
    >
</div>
