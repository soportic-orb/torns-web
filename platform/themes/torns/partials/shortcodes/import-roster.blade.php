@php
    $title = $shortcode->title ?: __('Una foto del cuadrante y tu mes está hecho');
    $content1 = $shortcode->content_1 ?: __('Copilot lee la foto o el documento de tu cuadrante, encuentra tu fila y te propone los turnos para que los revises antes de guardarlos. El archivo no se guarda.');
    $content2 = $shortcode->content_2 ?: __('¿Ya los tienes en Google o Apple Calendar? Torns también los importa desde ahí.');

    // Illustration only: a paper roster with the user's row highlighted,
    // turning into reviewed shifts. Letters follow the app (M / T / N).
    $roster = [
        ['name' => 'Laura', 'days' => ['m', 'm', '', 'e', 'e', '', 'n']],
        ['name' => __('Tú'), 'days' => ['e', '', 'n', 'n', '', 'm', ''], 'me' => true],
        ['name' => 'Jordi', 'days' => ['', 'n', 'n', '', 'm', 'm', '']],
        ['name' => 'Marta', 'days' => ['n', '', 'm', 'm', 'e', '', '']],
    ];
    $letters = ['m' => __('shift_letter_morning'), 'e' => __('shift_letter_evening'), 'n' => __('shift_letter_night')];
    $letterClass = ['m' => 'text-[#9A6A00]', 'e' => 'text-[#B23A2E]', 'n' => 'text-[#3A49AD]'];
    $review = [
        ['type' => 'evening', 'day' => __('Lun 5'), 'time' => '15:00 – 22:00'],
        ['type' => 'night', 'day' => __('Mié 7'), 'time' => '22:00 – 08:00'],
        ['type' => 'night', 'day' => __('Jue 8'), 'time' => '22:00 – 08:00'],
        ['type' => 'morning', 'day' => __('Sáb 10'), 'time' => '08:00 – 15:00'],
    ];
@endphp

<section class="py-16 lg:py-24" id="importar">
    <div class="mx-auto grid max-w-6xl items-center gap-12 px-4 sm:px-6 lg:grid-cols-2 lg:gap-16 lg:px-8">
        <div class="flex flex-col items-center gap-6 sm:flex-row sm:items-center sm:justify-center" aria-hidden="true">
            <div class="w-64 -rotate-3 rounded-xl border border-torns-ink/10 bg-white p-4 font-mono text-xs shadow-lg shadow-torns-ink/5">
                <p class="mb-3 font-sans text-[0.7rem] font-bold uppercase tracking-widest text-torns-ink/50">{{ __('Cuadrante · octubre') }}</p>
                <table class="w-full border-collapse">
                    @foreach ($roster as $row)
                        <tr @class(['bg-shift-morning/25' => ! empty($row['me'])])>
                            <td class="py-1.5 pr-2 font-sans font-semibold">{{ $row['name'] }}</td>
                            @foreach ($row['days'] as $letter)
                                <td class="w-6 py-1.5 text-center font-bold {{ $letterClass[$letter] ?? '' }}">{{ $letters[$letter] ?? '·' }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </table>
            </div>
            <svg class="h-8 w-8 shrink-0 rotate-90 text-torns-primary sm:rotate-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14m-6-6 6 6-6 6"/></svg>
            <ul class="flex w-60 list-none flex-col gap-2.5 rounded-3xl bg-white p-4 shadow-xl shadow-torns-ink/10">
                @foreach ($review as $shift)
                    <li class="flex items-center gap-3">
                        {!! Theme::partial('components.shift-chip', ['type' => $shift['type'], 'size' => 'sm']) !!}
                        <span class="whitespace-nowrap text-sm leading-tight"><b>{{ $shift['day'] }}</b><br><span class="text-torns-ink/60">{{ $shift['time'] }}</span></span>
                        <svg class="ml-auto h-5 w-5 text-torns-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m5 13 4 4L19 7"/></svg>
                    </li>
                @endforeach
            </ul>
        </div>
        <div>
            <p class="mb-3 text-sm font-semibold uppercase tracking-widest text-torns-primary">{{ __('Importa tu cuadrante') }}</p>
            <h2 class="font-display text-3xl font-bold leading-tight tracking-tight sm:text-4xl">{{ $title }}</h2>
            <p class="mt-5 text-lg leading-relaxed text-torns-ink/70">{{ $content1 }}</p>
            <p class="mt-3 text-lg leading-relaxed text-torns-ink/70">{{ $content2 }}</p>
        </div>
    </div>
</section>
