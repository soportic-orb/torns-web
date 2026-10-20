<div data-ai-mode="translate" hidden>
    <p class="text-secondary">{{ trans('torns.ai_translate_intro') }}</p>

    <fieldset class="mb-3">
        <legend class="form-label">{{ trans('torns.ai_translate_languages') }}</legend>
        @foreach ($translate['languages'] as $code)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="torns-ai-tr-{{ $code }}" value="{{ $code }}" data-ai-translate-language checked>
                <label class="form-check-label" for="torns-ai-tr-{{ $code }}">
                    {{ trans('torns.ai_lang_' . $code) }}
                    @if (in_array($code, $translate['translated'], true))
                        <span class="text-secondary small">— {{ trans('torns.ai_translate_exists') }}</span>
                    @endif
                </label>
                <span class="small ms-2" data-ai-translate-status="{{ $code }}" role="status"></span>
            </div>
        @endforeach
    </fieldset>

    <div class="row g-2 mb-3 align-items-end">
        @if (count($providers) > 1)
            <div class="col-6 col-md-4">
                <label class="form-label" for="torns-ai-tr-provider">{{ trans('torns.ai_provider') }}</label>
                <select class="form-select" id="torns-ai-tr-provider">
                    @foreach ($providers as $key => $label)
                        <option value="{{ $key }}" @selected($key === $defaultProvider)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        @else
            <input type="hidden" id="torns-ai-tr-provider" value="{{ $defaultProvider }}">
        @endif
    </div>

    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="torns-ai-tr-overwrite">
        <label class="form-check-label" for="torns-ai-tr-overwrite">{{ trans('torns.ai_translate_overwrite') }}</label>
    </div>

    <div class="d-flex gap-2 align-items-center">
        <button type="button" class="btn btn-primary" data-ai-translate>{{ trans('torns.ai_translate_run') }}</button>
        <span class="text-secondary small" data-ai-translate-summary role="status"></span>
    </div>
</div>
