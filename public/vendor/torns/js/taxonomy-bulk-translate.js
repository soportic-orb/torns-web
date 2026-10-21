/*
 * «Traducir con IA» in the bulk actions of the tag and category lists
 * (App\Ai\TranslateTaxonomyBulkAction, resources/views/admin/ai/taxonomy-bulk-translate.blade.php).
 * Translates the selected rows one at a time into the chosen languages,
 * with a progress bar, then redraws the table.
 */
document.addEventListener('click', (event) => {
    const item = event.target.closest('[data-torns-ai-bulk-translate]')
    if (!item) return
    event.preventDefault()
    const type = item.dataset.type
    const modal = document.querySelector(`[data-torns-ai-bulk-modal="${type}"]`)
    if (!modal) return
    if (!modal.dataset.ready) setupModal(modal, type)
    const table = item.closest('.table-wrapper')?.querySelector('table.table') || document.querySelector('.table-wrapper table.table')
    const ids = [...(table?.querySelectorAll('.checkboxes:checked') || [])].map((box) => Number(box.value))
    const t = JSON.parse(modal.dataset.i18n || '{}')
    if (!ids.length) {
        (window.Botble?.showError || window.alert)(t.selectFirst)
        return
    }
    modal.tornsState = { ids, table }
    modal.querySelector('[data-bulk-count]').textContent = t.count.replace(':count', ids.length)
    modal.querySelector('[data-bulk-progress]').hidden = true
    modal.querySelector('[data-bulk-summary]').textContent = ''
    bootstrap.Modal.getOrCreateInstance(modal).show()
})

function setupModal(modal, type) {
    modal.dataset.ready = '1'
    document.body.appendChild(modal) // out of the dropdown menu
    const t = JSON.parse(modal.dataset.i18n || '{}')
    const $ = (selector) => modal.querySelector(selector)

    $('[data-bulk-run]').addEventListener('click', async () => {
        const { ids, table } = modal.tornsState || { ids: [] }
        const languages = [...modal.querySelectorAll('[data-bulk-language]:checked')].map((box) => box.value)
        const summary = $('[data-bulk-summary]')
        summary.className = 'small mb-0'
        if (!languages.length) {
            summary.classList.add('text-danger')
            summary.textContent = t.none
            return
        }
        const button = $('[data-bulk-run]')
        const label = $('[data-bulk-label]')
        const bar = $('[data-bulk-bar]')
        button.disabled = true
        $('[data-bulk-spinner]').hidden = false
        label.textContent = t.running
        $('[data-bulk-progress]').hidden = false
        bar.style.width = '4%'
        bar.classList.add('progress-bar-animated', 'progress-bar-striped')
        bar.classList.remove('bg-success', 'bg-warning')

        let saved = 0
        let failed = 0
        for (const [index, id] of ids.entries()) {
            const name = table?.querySelector(`.checkboxes[value="${id}"]`)?.closest('tr')?.querySelector('a')?.textContent?.trim() || `#${id}`
            $('[data-bulk-current]').textContent = t.current.replace(':name', name).replace(':done', index + 1).replace(':total', ids.length)
            try {
                const res = await fetch(modal.dataset.url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    },
                    body: JSON.stringify({ type, id, languages, overwrite: $('[data-bulk-overwrite]').checked }),
                })
                const json = await res.json().catch(() => ({}))
                if (!res.ok || json.error) throw new Error(json.message || `Error ${res.status}`)
                saved += (json.data?.results || []).filter((result) => result.status === 'saved').length
            } catch (error) {
                failed++
                console.warn('Torns AI: translation failed', id, error)
            }
            const percent = Math.round(((index + 1) / ids.length) * 100)
            bar.style.width = `${Math.max(percent, 4)}%`
            bar.parentElement.setAttribute('aria-valuenow', String(percent))
        }

        bar.classList.remove('progress-bar-animated', 'progress-bar-striped')
        bar.classList.add(failed ? 'bg-warning' : 'bg-success')
        $('[data-bulk-current]').textContent = ''
        summary.classList.add(failed ? 'text-warning' : 'text-success')
        summary.textContent = t.done.replace(':count', saved) + (failed ? ' ' + t.failed.replace(':count', failed) : '')
        button.disabled = false
        $('[data-bulk-spinner]').hidden = true
        label.textContent = t.run
        if (table?.id && window.LaravelDataTables?.[table.id]) window.LaravelDataTables[table.id].draw(false)
    })
}
