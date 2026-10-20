<x-core::card class="mt-3">
    <x-core::card.header>
        <x-core::card.title>{{ trans('torns.ota_history') }}</x-core::card.title>
    </x-core::card.header>
    @if ($history)
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>{{ trans('torns.ota_col_date') }}</th>
                        <th>{{ trans('torns.ota_col_action') }}</th>
                        <th>{{ trans('torns.ota_col_version') }}</th>
                        <th>{{ trans('torns.ota_col_by') }}</th>
                        <th>{{ trans('torns.ota_col_content') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($history as $entry)
                        <tr>
                            <td class="text-nowrap">{{ $entry['at'] ?? '' }}</td>
                            <td>{{ trans(($entry['action'] ?? '') === 'rollback' ? 'torns.ota_action_rollback' : 'torns.ota_action_apply') }}</td>
                            <td>
                                <code>{{ $entry['version'] ?? '—' }}</code>
                                @if (! empty($entry['from']))
                                    <span class="text-secondary">({{ trans('torns.ota_before', ['version' => $entry['from']]) }})</span>
                                @endif
                            </td>
                            <td>{{ $entry['by'] ?? '' }}</td>
                            <td class="small">
                                @if (! empty($entry['updated']) || ! empty($entry['created']))
                                    <div>{{ trans('torns.ota_updated_items', ['items' => implode(', ', array_merge($entry['created'] ?? [], $entry['updated'] ?? []))]) }}</div>
                                @endif
                                @if (! empty($entry['conflicts']))
                                    <div class="text-warning">{{ trans('torns.ota_kept_items', ['items' => implode(', ', $entry['conflicts'])]) }}</div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <x-core::card.body>
            <p class="text-secondary mb-0">{{ trans('torns.ota_history_empty') }}</p>
        </x-core::card.body>
    @endif
</x-core::card>
