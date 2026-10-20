@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    @if (session('ai_ok'))
        <x-core::alert type="success">{{ session('ai_ok') }}</x-core::alert>
    @endif
    @foreach ($tests as $test)
        <x-core::alert :type="$test['ok'] ? 'success' : 'danger'">{{ $test['message'] }}</x-core::alert>
    @endforeach

    <form method="POST" action="{{ route('torns-ai.settings.save') }}">
        @csrf
        <x-core::card>
            <x-core::card.header>
                <x-core::card.title>IA para el blog</x-core::card.title>
            </x-core::card.header>
            <x-core::card.body>
                <p class="text-secondary">Con la IA activada, el editor de publicaciones muestra «✨ Escribir con IA»: redacta el cuerpo, el título o el resumen con Claude o ChatGPT, y crea la imagen destacada con OpenAI. Las claves se guardan cifradas.</p>

                <label class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" name="torns_ai_enabled" value="1" @checked($ai->enabled())>
                    <span class="form-check-label">Activar la IA en el editor del blog</span>
                </label>

                <div class="row g-4">
                    <div class="col-md-6">
                        <h4>Claude (Anthropic)</h4>
                        <div class="mb-3">
                            <label class="form-label" for="torns_ai_anthropic_key">Clave de API</label>
                            <input class="form-control" type="password" id="torns_ai_anthropic_key" name="torns_ai_anthropic_key" autocomplete="off" placeholder="{{ $ai->masked('torns_ai_anthropic_key') ?: 'sk-ant-…' }}">
                            <div class="form-hint">Déjala vacía para mantener la actual; escribe «-» para borrarla.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="torns_ai_claude_model">Modelo</label>
                            <input class="form-control" id="torns_ai_claude_model" name="torns_ai_claude_model" value="{{ setting('torns_ai_claude_model') }}" placeholder="{{ $ai->claudeModel() }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h4>OpenAI (ChatGPT e imágenes)</h4>
                        <div class="mb-3">
                            <label class="form-label" for="torns_ai_openai_key">Clave de API</label>
                            <input class="form-control" type="password" id="torns_ai_openai_key" name="torns_ai_openai_key" autocomplete="off" placeholder="{{ $ai->masked('torns_ai_openai_key') ?: 'sk-…' }}">
                            <div class="form-hint">Déjala vacía para mantener la actual; escribe «-» para borrarla. Sin esta clave no se pueden crear imágenes.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="torns_ai_openai_text_model">Modelo de texto</label>
                            <input class="form-control" id="torns_ai_openai_text_model" name="torns_ai_openai_text_model" value="{{ setting('torns_ai_openai_text_model') }}" placeholder="{{ $ai->openaiTextModel() }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="torns_ai_openai_image_model">Modelo de imagen</label>
                            <input class="form-control" id="torns_ai_openai_image_model" name="torns_ai_openai_image_model" value="{{ setting('torns_ai_openai_image_model') }}" placeholder="{{ $ai->openaiImageModel() }}">
                        </div>
                    </div>
                </div>

                <div class="mb-0" style="max-width: 20rem">
                    <label class="form-label" for="torns_ai_default_text">Servicio de texto por defecto</label>
                    <select class="form-select" id="torns_ai_default_text" name="torns_ai_default_text">
                        <option value="claude" @selected($ai->get('torns_ai_default_text') !== 'openai')>Claude</option>
                        <option value="openai" @selected($ai->get('torns_ai_default_text') === 'openai')>ChatGPT</option>
                    </select>
                </div>
            </x-core::card.body>
            <x-core::card.footer class="d-flex gap-2">
                <x-core::button type="submit" color="primary" icon="ti ti-device-floppy">Guardar</x-core::button>
                <x-core::button type="submit" formaction="{{ route('torns-ai.settings.test') }}" icon="ti ti-plug-connected">Probar la conexión</x-core::button>
            </x-core::card.footer>
        </x-core::card>
    </form>
@endsection
