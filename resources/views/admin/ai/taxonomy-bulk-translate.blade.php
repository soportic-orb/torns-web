{{-- Bulk action «Traducir con IA» (App\Ai\TranslateTaxonomyBulkAction): menu item + its dialog.
     The behaviour is in public/vendor/torns/js/taxonomy-bulk-translate.js: this markup reaches the
     page through the table's JavaScript, where an inline <script> would not run. --}}
<x-core::dropdown.item :label="$label" href="#" data-torns-ai-bulk-translate data-type="{{ $type }}" />

<div class="modal fade" tabindex="-1" aria-hidden="true" data-torns-ai-bulk-modal="{{ $type }}"
    data-url="{{ route('torns-ai.translate-taxonomy') }}"
    data-i18n="{{ json_encode([
        'count' => trans('torns.ai_bulk_translate_count'),
        'none' => trans('torns.ai_translate_none'),
        'selectFirst' => trans('torns.ai_bulk_translate_select'),
        'current' => trans('torns.ai_bulk_translate_current'),
        'done' => trans('torns.ai_bulk_translate_done'),
        'failed' => trans('torns.ai_bulk_translate_failed'),
        'running' => trans('torns.ai_translate_running'),
        'run' => trans('torns.ai_translate_run'),
    ]) }}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">✨ {{ $label }}</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ trans('torns.ai_close') }}"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted" data-bulk-count></p>
                <fieldset class="mb-3">
                    <legend class="form-label">{{ trans('torns.ai_translate_languages') }}</legend>
                    @foreach ($languages as $language)
                        <label class="form-check">
                            <input class="form-check-input" type="checkbox" value="{{ $language }}" checked data-bulk-language>
                            <span class="form-check-label">{{ trans('torns.ai_lang_' . $language) }}</span>
                        </label>
                    @endforeach
                </fieldset>
                <label class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" data-bulk-overwrite>
                    <span class="form-check-label">{{ trans('torns.ai_taxonomy_overwrite') }}</span>
                </label>
                <p class="small text-muted mb-3">{{ trans('torns.ai_bulk_translate_hint') }}</p>
                <div hidden data-bulk-progress>
                    <div class="progress mb-2" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 4%" data-bulk-bar></div>
                    </div>
                    <p class="small mb-1" data-bulk-current></p>
                </div>
                <p class="small mb-0" role="status" data-bulk-summary></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-bs-dismiss="modal">{{ trans('torns.ai_close') }}</button>
                <button type="button" class="btn btn-primary" data-bulk-run>
                    <span class="spinner-border spinner-border-sm me-1" role="status" hidden data-bulk-spinner></span>
                    <span data-bulk-label>{{ trans('torns.ai_translate_run') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>
