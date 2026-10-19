@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    @if (session('ota_ok'))
        <x-core::alert type="success">{{ session('ota_ok') }}</x-core::alert>
    @endif
    @if (session('ota_error'))
        <x-core::alert type="danger" title="No se ha podido completar">{{ session('ota_error') }}</x-core::alert>
    @endif
    @if ($problems)
        <x-core::alert type="warning" title="Este servidor aún no puede aplicar actualizaciones">
            <ul class="mb-0">
                @foreach ($problems as $problem)
                    <li>{{ $problem }}</li>
                @endforeach
            </ul>
        </x-core::alert>
    @endif

    <div class="row row-cards">
        <div class="col-lg-7">
            <x-core::card>
                <x-core::card.header>
                    <x-core::card.title>Versión de la web</x-core::card.title>
                </x-core::card.header>
                <x-core::card.body>
                    <dl class="row mb-0">
                        <dt class="col-5">Instalada</dt>
                        <dd class="col-7">{{ $current ?: 'Instalación manual (sin OTA todavía)' }}</dd>
                        <dt class="col-5">Última comprobación</dt>
                        <dd class="col-7">{{ $lastCheck ?: 'Nunca' }}</dd>
                        <dt class="col-5">Origen</dt>
                        <dd class="col-7"><code>{{ $repository }}</code>, releases «ota-…»</dd>
                    </dl>
                </x-core::card.body>
                <x-core::card.footer class="d-flex gap-2 flex-wrap">
                    <form method="POST" action="{{ route('torns-ota.check') }}">
                        @csrf
                        <x-core::button type="submit" icon="ti ti-refresh">Buscar actualizaciones</x-core::button>
                    </form>
                    @if ($hasBackup)
                        <form method="POST" action="{{ route('torns-ota.rollback') }}" onsubmit="return confirm('¿Volver a los archivos y páginas guardados antes de la última actualización?')">
                            @csrf
                            <x-core::button type="submit" color="secondary" icon="ti ti-arrow-back-up">Deshacer la última actualización</x-core::button>
                        </form>
                    @endif
                </x-core::card.footer>
            </x-core::card>
        </div>

        <div class="col-lg-5">
            <x-core::card>
                <x-core::card.header>
                    <x-core::card.title>
                        @if ($available)
                            Nueva versión: {{ $available['version'] }}
                        @else
                            Sin actualizaciones pendientes
                        @endif
                    </x-core::card.title>
                </x-core::card.header>
                <x-core::card.body>
                    @if ($available)
                        <p class="text-secondary mb-2">Publicada el {{ \Illuminate\Support\Carbon::parse($available['published_at'])->timezone(config('app.timezone'))->format('d/m/Y H:i') }}.
                            @if ($available['url'])
                                <a href="{{ $available['url'] }}" target="_blank" rel="noopener">Ver en GitHub</a>
                            @endif
                        </p>
                        @if ($available['notes'])
                            <pre class="small bg-light p-2 rounded" style="white-space: pre-wrap; max-height: 16rem; overflow: auto">{{ $available['notes'] }}</pre>
                        @endif
                        <p class="small text-secondary mb-0">Se guarda una copia de seguridad antes de aplicar. Las páginas y los menús que hayas editado aquí, en /admin, no se sobrescriben. Las noticias del blog no se tocan nunca.</p>
                    @else
                        <p class="text-secondary mb-0">Cuando se fusione un cambio del tema o del contenido en el repositorio, aparecerá aquí en menos de una hora (o al pulsar «Buscar actualizaciones»).</p>
                    @endif
                </x-core::card.body>
                @if ($available)
                    <x-core::card.footer>
                        <form method="POST" action="{{ route('torns-ota.apply') }}" onsubmit="return confirm('¿Aplicar la versión {{ $available['version'] }}?')">
                            @csrf
                            <x-core::button type="submit" color="primary" icon="ti ti-cloud-download" :disabled="(bool) $problems">Aplicar la versión {{ $available['version'] }}</x-core::button>
                        </form>
                    </x-core::card.footer>
                @endif
            </x-core::card>
        </div>
    </div>

    @if ($conflicts)
        <x-core::alert type="warning" class="mt-3" title="Contenido editado en /admin que la última actualización no ha tocado">
            <p>{{ implode(', ', $conflicts) }}. Se mantiene tu edición. Si prefieres la versión del repositorio, puedes aplicarla (tu edición queda en la copia de seguridad).</p>
            <form method="POST" action="{{ route('torns-ota.use-repository-content') }}" onsubmit="return confirm('Se sobrescribirán las páginas y los menús editados en /admin con la versión del repositorio. ¿Continuar?')">
                @csrf
                <x-core::button type="submit" color="warning">Usar la versión del repositorio</x-core::button>
            </form>
        </x-core::alert>
    @endif

    <x-core::card class="mt-3">
        <x-core::card.header>
            <x-core::card.title>Historial</x-core::card.title>
        </x-core::card.header>
        @if ($history)
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Acción</th>
                            <th>Versión</th>
                            <th>Por</th>
                            <th>Páginas y menús</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($history as $entry)
                            <tr>
                                <td class="text-nowrap">{{ $entry['at'] ?? '' }}</td>
                                <td>{{ ($entry['action'] ?? '') === 'rollback' ? 'Deshecha' : 'Aplicada' }}</td>
                                <td><code>{{ $entry['version'] ?? '—' }}</code>@if (! empty($entry['from'])) <span class="text-secondary">(antes {{ $entry['from'] }})</span>@endif</td>
                                <td>{{ $entry['by'] ?? '' }}</td>
                                <td class="small">
                                    @if (! empty($entry['updated']) || ! empty($entry['created']))
                                        <div>Actualizadas: {{ implode(', ', array_merge($entry['created'] ?? [], $entry['updated'] ?? [])) }}</div>
                                    @endif
                                    @if (! empty($entry['conflicts']))
                                        <div class="text-warning">No tocadas porque se editaron en /admin: {{ implode(', ', $entry['conflicts']) }}</div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <x-core::card.body>
                <p class="text-secondary mb-0">Todavía no se ha aplicado ninguna actualización.</p>
            </x-core::card.body>
        @endif
    </x-core::card>
@endsection
