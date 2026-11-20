{{-- Post form: the post's short link in the language being edited (AppServiceProvider, docs/short-links.md). --}}
<div
    class="card mb-3"
    id="torns-short-link"
    data-url="{{ route('torns-short-links.generate') }}"
    data-post-id="{{ $postId }}"
    data-locale="{{ $locale }}"
    data-i18n="{{ json_encode([
        'copied' => trans('torns.short_link_copied'),
        'clicks' => trans('torns.short_link_clicks'),
        'failed' => trans('torns.short_link_failed'),
    ]) }}"
>
    <div class="card-body">
        <h4 class="card-title mb-2">🔗 {{ trans('torns.short_link_title') }}</h4>
        @if (! $postId)
            <p class="text-muted small mb-0">{{ trans('torns.short_link_save_first') }}</p>
        @else
            <p class="text-muted small mb-3">{{ trans('torns.short_link_intro', ['language' => trans('torns.ai_lang_' . $locale)]) }}</p>
            <div class="d-flex flex-wrap align-items-center gap-2" data-short-link-result @if (! $url) hidden @endif>
                <a href="{{ $url }}" target="_blank" rel="noopener" class="fw-semibold" data-short-link-label>{{ $label }}</a>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-short-link-copy>{{ trans('torns.short_link_copy') }}</button>
                <span class="text-muted small" data-short-link-clicks>{{ $url ? trans('torns.short_link_clicks', ['count' => $clicks]) : '' }}</span>
            </div>
            <button type="button" class="btn btn-outline-primary" data-short-link-generate @if ($url) hidden @endif>
                <span class="spinner-border spinner-border-sm me-1" role="status" hidden data-short-link-spinner></span>
                {{ trans('torns.short_link_generate') }}
            </button>
            <p class="small mt-2 mb-0" role="status" data-short-link-message></p>
        @endif
    </div>
</div>
