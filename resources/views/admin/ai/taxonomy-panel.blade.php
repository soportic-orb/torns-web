{{-- Category / tag form: its translations and «Traducir con IA» (AppServiceProvider). --}}
<div class="card mb-3" id="torns-ai-taxonomy" data-url="{{ route('torns-ai.translate-taxonomy') }}" data-type="{{ $type }}" data-id="{{ $id }}">
    <div class="card-body">
        <h4 class="card-title mb-2">✨ {{ trans('torns.ai_taxonomy_title') }}</h4>
        <p class="text-muted small mb-3">{{ trans('torns.ai_taxonomy_intro') }}</p>
        <ul class="list-unstyled d-flex flex-wrap gap-2 mb-3" data-ai-taxonomy-status>
            @foreach ($languages as $language)
                <li>
                    <span @class(['badge', 'bg-green-lt text-green' => ! in_array($language, $missing, true), 'bg-secondary-lt text-secondary' => in_array($language, $missing, true)]) data-lang="{{ $language }}">
                        {{ trans('torns.ai_lang_' . $language) }} ·
                        <span data-label>{{ in_array($language, $missing, true) ? trans('torns.ai_taxonomy_missing') : trans('torns.ai_taxonomy_done') }}</span>
                    </span>
                </li>
            @endforeach
        </ul>
        <div class="d-flex flex-wrap align-items-center gap-3">
            <button type="button" class="btn btn-outline-primary" data-ai-taxonomy-run>
                <span class="spinner-border spinner-border-sm me-1" role="status" hidden data-ai-taxonomy-spinner></span>
                {{ trans('torns.ai_taxonomy_run') }}
            </button>
            <label class="form-check mb-0">
                <input class="form-check-input" type="checkbox" data-ai-taxonomy-overwrite>
                <span class="form-check-label small">{{ trans('torns.ai_taxonomy_overwrite') }}</span>
            </label>
        </div>
        <p class="small mt-2 mb-0" role="status" data-ai-taxonomy-message></p>
    </div>
</div>
<script>
    (() => {
        const root = document.getElementById('torns-ai-taxonomy')
        if (!root || root.dataset.ready) return
        root.dataset.ready = '1'
        const button = root.querySelector('[data-ai-taxonomy-run]')
        const message = root.querySelector('[data-ai-taxonomy-message]')
        const labels = { done: @json(trans('torns.ai_taxonomy_done')), missing: @json(trans('torns.ai_taxonomy_missing')), saved: @json(trans('torns.ai_taxonomy_saved')) }
        button.addEventListener('click', async () => {
            button.disabled = true
            root.querySelector('[data-ai-taxonomy-spinner]').hidden = false
            message.className = 'small mt-2 mb-0'
            message.textContent = ''
            try {
                const res = await fetch(root.dataset.url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content },
                    body: JSON.stringify({ type: root.dataset.type, id: Number(root.dataset.id), overwrite: root.querySelector('[data-ai-taxonomy-overwrite]').checked }),
                })
                const json = await res.json().catch(() => ({}))
                if (!res.ok || json.error) throw new Error(json.message || `Error ${res.status}`)
                const missing = json.data?.missing || []
                root.querySelectorAll('[data-lang]').forEach((badge) => {
                    const isMissing = missing.includes(badge.dataset.lang)
                    badge.className = `badge ${isMissing ? 'bg-secondary-lt text-secondary' : 'bg-green-lt text-green'}`
                    badge.querySelector('[data-label]').textContent = isMissing ? labels.missing : labels.done
                })
                message.classList.add('text-success')
                message.textContent = labels.saved
            } catch (error) {
                message.classList.add('text-danger')
                message.textContent = error.message
            } finally {
                button.disabled = false
                root.querySelector('[data-ai-taxonomy-spinner]').hidden = true
            }
        })
    })()
</script>
