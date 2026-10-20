@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    @foreach ($tests as $test)
        <x-core::alert :type="$test['ok'] ? 'success' : 'danger'">{{ $test['message'] }}</x-core::alert>
    @endforeach

    <form method="POST" action="{{ route('torns-ai.settings.save') }}">
        @csrf
        <x-core::card>
            <x-core::card.header>
                <x-core::card.title>{{ trans('torns.ai_settings_title') }}</x-core::card.title>
            </x-core::card.header>
            <x-core::card.body>
                <p class="text-secondary">{{ trans('torns.ai_settings_intro') }}</p>

                <label class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" name="torns_ai_enabled" value="1" @checked($ai->enabled())>
                    <span class="form-check-label">{{ trans('torns.ai_settings_enable') }}</span>
                </label>

                <div class="row g-4">
                    <div class="col-md-6">
                        <h4>Claude (Anthropic)</h4>
                        @include('admin.ai.partials.key-field', ['name' => 'torns_ai_anthropic_key', 'placeholder' => 'sk-ant-…', 'hint' => trans('torns.ai_settings_key_hint')])
                        @include('admin.ai.partials.model-field', ['name' => 'torns_ai_claude_model', 'label' => trans('torns.ai_settings_model'), 'default' => $ai->claudeModel()])
                    </div>
                    <div class="col-md-6">
                        <h4>{{ trans('torns.ai_settings_openai') }}</h4>
                        @include('admin.ai.partials.key-field', ['name' => 'torns_ai_openai_key', 'placeholder' => 'sk-…', 'hint' => trans('torns.ai_settings_key_hint') . ' ' . trans('torns.ai_settings_openai_hint')])
                        @include('admin.ai.partials.model-field', ['name' => 'torns_ai_openai_text_model', 'label' => trans('torns.ai_settings_text_model'), 'default' => $ai->openaiTextModel()])
                        @include('admin.ai.partials.model-field', ['name' => 'torns_ai_openai_image_model', 'label' => trans('torns.ai_settings_image_model'), 'default' => $ai->openaiImageModel()])
                    </div>
                </div>

                <div class="mb-0 col-md-4 px-0">
                    <label class="form-label" for="torns_ai_default_text">{{ trans('torns.ai_settings_default') }}</label>
                    <select class="form-select" id="torns_ai_default_text" name="torns_ai_default_text">
                        <option value="claude" @selected($ai->get('torns_ai_default_text') !== 'openai')>Claude</option>
                        <option value="openai" @selected($ai->get('torns_ai_default_text') === 'openai')>ChatGPT</option>
                    </select>
                </div>
            </x-core::card.body>
            <x-core::card.footer class="d-flex gap-2">
                <x-core::button type="submit" color="primary" icon="ti ti-device-floppy">{{ trans('torns.ai_settings_save') }}</x-core::button>
                <x-core::button type="submit" formaction="{{ route('torns-ai.settings.test') }}" icon="ti ti-plug-connected">{{ trans('torns.ai_settings_test') }}</x-core::button>
            </x-core::card.footer>
        </x-core::card>
    </form>
@endsection
