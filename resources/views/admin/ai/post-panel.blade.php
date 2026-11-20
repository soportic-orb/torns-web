<div
    class="torns-ai mb-3"
    id="torns-ai-panel"
    data-text-url="{{ route('torns-ai.text') }}"
    data-image-url="{{ route('torns-ai.image') }}"
    data-seo-url="{{ route('torns-ai.seo') }}"
    data-language="{{ $language }}"
    data-provider="{{ $defaultProvider }}"
    @if ($translate)
        data-translate-url="{{ route('torns-ai.translate') }}"
        data-post-id="{{ $translate['post_id'] }}"
    @endif
    data-i18n="{{ json_encode([
        'write' => trans('torns.ai_write'),
        'image' => trans('torns.ai_image'),
        'writing' => trans('torns.ai_writing'),
        'creating' => trans('torns.ai_creating'),
        'replace' => trans('torns.ai_replace'),
        'append' => trans('torns.ai_append'),
        'useTitle' => trans('torns.ai_use_title'),
        'useDescription' => trans('torns.ai_use_description'),
        'useImage' => trans('torns.ai_use_image'),
        'again' => trans('torns.ai_again'),
        'replaced' => trans('torns.ai_done_replaced'),
        'appended' => trans('torns.ai_done_appended'),
        'titleSet' => trans('torns.ai_done_title'),
        'descriptionSet' => trans('torns.ai_done_description'),
        'descriptionLabel' => trans('torns.ai_description_label'),
        'imageSet' => trans('torns.ai_done_image'),
        'translate' => trans('torns.ai_translate'),
        'translating' => trans('torns.ai_translating'),
        'translated' => trans('torns.ai_translate_saved'),
        'skipped' => trans('torns.ai_translate_skipped'),
        'translateDone' => trans('torns.ai_translate_done'),
        'translateNone' => trans('torns.ai_translate_none'),
        'translateRun' => trans('torns.ai_translate_run'),
        'seo' => trans('torns.ai_seo'),
        'seoRunning' => trans('torns.ai_seo_running'),
        'seoDone' => trans('torns.ai_seo_done'),
        'translateRunning' => trans('torns.ai_translate_running'),
        'translateCurrent' => trans('torns.ai_translate_current', ['language' => ':language']),
        'translateCount' => trans('torns.ai_translate_count', ['done' => ':done', 'total' => ':total']),
        'translateFailed' => trans('torns.ai_translate_failed', ['count' => ':count']),
        'translatePending' => trans('torns.ai_translate_pending'),
        'translateTimeout' => trans('torns.ai_err_translate_timeout'),
    ]) }}"
>
    <div class="d-flex flex-wrap gap-2">
        @if ($providers)
            <button type="button" class="btn btn-outline-primary" data-ai-open="text">✨ {{ trans('torns.ai_write') }}</button>
        @endif
        @if ($providers)
            <button type="button" class="btn btn-outline-primary" data-ai-seo>
                <span class="spinner-border spinner-border-sm me-1" aria-hidden="true" data-ai-seo-spinner hidden></span>
                <span data-ai-seo-label>✨ {{ trans('torns.ai_seo') }}</span>
            </button>
        @endif
        @if ($imageReady)
            <button type="button" class="btn btn-outline-primary" data-ai-open="image">✨ {{ trans('torns.ai_image') }}</button>
        @endif
        @if ($translate)
            <button type="button" class="btn btn-outline-primary" data-ai-open="translate">✨ {{ trans('torns.ai_translate') }}</button>
        @endif
    </div>

    <dialog class="torns-ai__dialog" aria-labelledby="torns-ai-title">
        <div class="torns-ai__box">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h3 class="m-0" id="torns-ai-title" data-ai-heading>{{ trans('torns.ai_write') }}</h3>
                <button type="button" class="btn-close" aria-label="{{ trans('torns.ai_close') }}" data-ai-close></button>
            </div>

            @include('admin.ai.partials.text-fields')
            @include('admin.ai.partials.image-fields')
            @if ($translate)
                @include('admin.ai.partials.translate-fields')
            @endif

            <div class="d-flex gap-2 align-items-center mb-3" data-ai-common>
                <button type="button" class="btn btn-primary" data-ai-generate>{{ trans('torns.ai_generate') }}</button>
                <span class="text-secondary small" data-ai-status role="status"></span>
            </div>

            <div data-ai-result data-ai-common hidden>
                <div class="torns-ai__preview mb-3" data-ai-preview></div>
                <div class="d-flex flex-wrap gap-2" data-ai-actions></div>
            </div>
        </div>
    </dialog>
</div>
