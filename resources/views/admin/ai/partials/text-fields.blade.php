<div data-ai-mode="text">
    <label class="form-label" for="torns-ai-prompt">{{ trans('torns.ai_prompt_label') }}</label>
    <textarea class="form-control mb-3" id="torns-ai-prompt" rows="4" placeholder="{{ trans('torns.ai_prompt_placeholder') }}"></textarea>

    <div class="row g-2 mb-3">
        @if (count($providers) > 1)
            <div class="col-6 col-md-4">
                <label class="form-label" for="torns-ai-provider">{{ trans('torns.ai_provider') }}</label>
                <select class="form-select" id="torns-ai-provider">
                    @foreach ($providers as $key => $label)
                        <option value="{{ $key }}" @selected($key === $defaultProvider)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        @else
            <input type="hidden" id="torns-ai-provider" value="{{ $defaultProvider }}">
        @endif
        <div class="col-6 col-md-4">
            <label class="form-label" for="torns-ai-format">{{ trans('torns.ai_format') }}</label>
            <select class="form-select" id="torns-ai-format">
                @foreach ($formats as $key)
                    <option value="{{ $key }}">{{ trans('torns.ai_format_' . $key) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-4">
            <label class="form-label" for="torns-ai-language">{{ trans('torns.ai_language') }}</label>
            <select class="form-select" id="torns-ai-language">
                @foreach ($languages as $key)
                    <option value="{{ $key }}" @selected($key === $language)>{{ trans('torns.ai_lang_' . $key) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-4" data-ai-article-only>
            <label class="form-label" for="torns-ai-tone">{{ trans('torns.ai_tone') }}</label>
            <select class="form-select" id="torns-ai-tone">
                @foreach ($tones as $key)
                    <option value="{{ $key }}">{{ trans('torns.ai_tone_' . $key) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-4" data-ai-article-only>
            <label class="form-label" for="torns-ai-length">{{ trans('torns.ai_length') }}</label>
            <select class="form-select" id="torns-ai-length">
                @foreach ($lengths as $key)
                    <option value="{{ $key }}" @selected($key === 'medio')>{{ trans('torns.ai_length_' . $key) }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <label class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="torns-ai-rewrite">
        <span class="form-check-label">{{ trans('torns.ai_rewrite') }}</span>
    </label>
</div>
