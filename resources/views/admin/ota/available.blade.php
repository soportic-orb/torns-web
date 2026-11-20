<x-core::card>
    <x-core::card.header>
        <x-core::card.title>
            {{ $available ? trans('torns.ota_new_version', ['version' => $available['version']]) : trans('torns.ota_no_updates') }}
        </x-core::card.title>
    </x-core::card.header>
    <x-core::card.body>
        @if ($available)
            <p class="text-secondary mb-2">
                {{ trans('torns.ota_published_on', ['date' => \Illuminate\Support\Carbon::parse($available['published_at'])->timezone(config('app.timezone'))->format('d/m/Y H:i')]) }}
                @if ($available['url'])
                    <a href="{{ $available['url'] }}" target="_blank" rel="noopener">{{ trans('torns.ota_view_on_github') }}</a>
                @endif
            </p>
            @if ($available['notes'])
                <pre class="small p-2 rounded torns-ota-notes">{{ $available['notes'] }}</pre>
            @endif
            <p class="small text-secondary mb-0">{{ trans('torns.ota_apply_hint') }}</p>
        @else
            <p class="text-secondary mb-0">{{ trans('torns.ota_no_updates_hint') }}</p>
        @endif
    </x-core::card.body>
    @if ($available)
        <x-core::card.footer>
            <form method="POST" action="{{ route('torns-ota.apply') }}" data-confirm="{{ trans('torns.ota_confirm_apply', ['version' => $available['version']]) }}">
                @csrf
                <x-core::button type="submit" color="primary" icon="ti ti-cloud-download" :disabled="(bool) $problems">
                    {{ trans('torns.ota_apply', ['version' => $available['version']]) }}
                </x-core::button>
            </form>
        </x-core::card.footer>
    @endif
</x-core::card>
