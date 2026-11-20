<x-core::card>
    <x-core::card.header>
        <x-core::card.title>{{ trans('torns.ota_version_title') }}</x-core::card.title>
    </x-core::card.header>
    <x-core::card.body>
        <dl class="row mb-0">
            <dt class="col-5">{{ trans('torns.ota_installed') }}</dt>
            <dd class="col-7">{{ $current ?: trans('torns.ota_manual_install') }}</dd>
            <dt class="col-5">{{ trans('torns.ota_last_check') }}</dt>
            <dd class="col-7">{{ $lastCheck ?: trans('torns.ota_never') }}</dd>
            <dt class="col-5">{{ trans('torns.ota_source') }}</dt>
            <dd class="col-7"><code>{{ $repository }}</code>, {{ trans('torns.ota_source_releases') }}</dd>
        </dl>
    </x-core::card.body>
    <x-core::card.footer class="d-flex gap-2 flex-wrap">
        <form method="POST" action="{{ route('torns-ota.check') }}">
            @csrf
            <x-core::button type="submit" icon="ti ti-refresh">{{ trans('torns.ota_check') }}</x-core::button>
        </form>
        @if ($hasBackup)
            <form method="POST" action="{{ route('torns-ota.rollback') }}" data-confirm="{{ trans('torns.ota_confirm_rollback') }}">
                @csrf
                <x-core::button type="submit" color="secondary" icon="ti ti-arrow-back-up">{{ trans('torns.ota_rollback') }}</x-core::button>
            </form>
        @endif
    </x-core::card.footer>
</x-core::card>
