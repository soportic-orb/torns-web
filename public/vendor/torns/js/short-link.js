/*
 * «Generar enllaç curt» in the post editor
 * (resources/views/admin/short-link/panel.blade.php, docs/short-links.md):
 * makes the post's short link in the language being edited and copies it.
 */
document.addEventListener('click', async (event) => {
    const box = event.target.closest('#torns-short-link')
    if (!box) return
    const t = JSON.parse(box.dataset.i18n || '{}')
    const message = box.querySelector('[data-short-link-message]')

    if (event.target.closest('[data-short-link-copy]')) {
        const url = box.querySelector('[data-short-link-label]').href
        try {
            await navigator.clipboard.writeText(url)
            message.textContent = t.copied
        } catch {
            window.prompt('', url)
        }
        return
    }

    const button = event.target.closest('[data-short-link-generate]')
    if (!button || button.disabled) return
    const spinner = box.querySelector('[data-short-link-spinner]')
    button.disabled = true
    spinner.hidden = false
    message.textContent = ''
    try {
        const res = await fetch(box.dataset.url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
            },
            body: JSON.stringify({ post_id: Number(box.dataset.postId), locale: box.dataset.locale }),
        })
        const json = await res.json().catch(() => ({}))
        if (!res.ok || json.error) throw new Error(json.message || t.failed)
        const label = box.querySelector('[data-short-link-label]')
        label.href = json.data.url
        label.textContent = json.data.label
        box.querySelector('[data-short-link-clicks]').textContent = t.clicks.replace(':count', json.data.clicks)
        box.querySelector('[data-short-link-result]').hidden = false
        button.hidden = true
    } catch (error) {
        message.textContent = error.message || t.failed
    } finally {
        button.disabled = false
        spinner.hidden = true
    }
})
