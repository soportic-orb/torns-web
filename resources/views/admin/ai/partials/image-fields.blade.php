<div data-ai-mode="image" hidden>
    <label class="form-label" for="torns-ai-image-prompt">{{ trans('torns.ai_image_prompt_label') }}</label>
    <textarea class="form-control mb-3" id="torns-ai-image-prompt" rows="3" placeholder="{{ trans('torns.ai_image_prompt_placeholder') }}"></textarea>
    <div class="row g-2 mb-3">
        <div class="col-6">
            <label class="form-label" for="torns-ai-shape">{{ trans('torns.ai_shape') }}</label>
            <select class="form-select" id="torns-ai-shape">
                @foreach ($shapes as $key)
                    <option value="{{ $key }}">{{ trans('torns.ai_shape_' . $key) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6">
            <label class="form-label" for="torns-ai-style">{{ trans('torns.ai_style') }}</label>
            <select class="form-select" id="torns-ai-style">
                @foreach ($styles as $key)
                    <option value="{{ $key }}">{{ trans('torns.ai_style_' . $key) }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <p class="form-hint mb-3">{{ trans('torns.ai_image_hint') }}</p>
</div>
