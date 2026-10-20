/*
 * "Write with AI" panel on the blog post form (resources/views/admin/ai/post-panel.blade.php).
 * Calls the BlogAiController endpoints and puts the result into the post editor.
 */
const initTornsAi = () => {
    const root = document.getElementById('torns-ai-panel')
    if (!root) return

    const dialog = root.querySelector('dialog')
    const $ = (selector) => dialog.querySelector(selector)
    const t = JSON.parse(root.dataset.i18n || '{}')
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content
    const field = (name) => document.querySelector(`[name="${name}"]`)
    let mode = 'text'

    // Post body: CKEditor (Botble default), TinyMCE or the plain textarea.
    const editor = {
        get() {
            const ck = window.EDITOR?.CKEDITOR?.content
            if (ck) return ck.getData()
            if (window.tinymce?.get('content')) return window.tinymce.get('content').getContent()
            return document.getElementById('content')?.value || ''
        },
        set(html) {
            const ck = window.EDITOR?.CKEDITOR?.content
            if (ck) return ck.setData(html)
            if (window.tinymce?.get('content')) return window.tinymce.get('content').setContent(html)
            const textarea = document.getElementById('content')
            if (textarea) textarea.value = html
        },
    }

    const setField = (name, value) => {
        const el = field(name)
        if (!el) return
        el.value = value
        el.dispatchEvent(new Event('input', { bubbles: true }))
    }

    const setMode = (next) => {
        mode = next
        dialog.querySelectorAll('[data-ai-mode]').forEach((el) => {
            el.hidden = el.dataset.aiMode !== mode
        })
        $('[data-ai-heading]').textContent = mode === 'image' ? t.image : t.write
        $('[data-ai-result]').hidden = true
        $('[data-ai-status]').textContent = ''
        const title = field('name')?.value?.trim()
        if (mode === 'image' && title && !$('#torns-ai-image-prompt').value) {
            $('#torns-ai-image-prompt').value = t.imagePrompt.replace(':title', title)
        }
    }

    const syncFormat = () => {
        const article = $('#torns-ai-format').value === 'article'
        dialog.querySelectorAll('[data-ai-article-only]').forEach((el) => {
            el.hidden = !article
        })
    }

    const done = (message) => {
        dialog.close()
        window.Botble?.showSuccess?.(message)
    }

    const action = (label, handler, primary = false) => {
        const button = document.createElement('button')
        button.type = 'button'
        button.className = primary ? 'btn btn-primary' : 'btn btn-outline-secondary'
        button.textContent = label
        button.addEventListener('click', handler)
        $('[data-ai-actions]').append(button)
    }

    const showText = (format, result) => {
        const preview = $('[data-ai-preview]')
        if (format === 'article') {
            preview.innerHTML = result.html
        } else {
            preview.textContent = result.text
        }
        $('[data-ai-actions]').replaceChildren()
        if (format === 'article') {
            action(t.replace, () => { editor.set(result.html); done(t.replaced) }, true)
            action(t.append, () => { editor.set(editor.get() + result.html); done(t.appended) })
        } else if (format === 'title') {
            action(t.useTitle, () => { setField('name', result.text); done(t.titleSet) }, true)
        } else {
            action(t.useDescription, () => { setField('description', result.text); done(t.descriptionSet) }, true)
        }
        action(t.again, generate)
    }

    const showImage = (result) => {
        const img = document.createElement('img')
        img.src = result.url
        img.alt = ''
        $('[data-ai-preview]').replaceChildren(img)
        $('[data-ai-actions]').replaceChildren()
        action(t.useImage, () => {
            const box = document.querySelector('.image-box-image')
            if (box) {
                const input = box.querySelector('.image-data')
                input.value = result.path
                input.dispatchEvent(new Event('change', { bubbles: true }))
                const preview = box.querySelector('.preview-image')
                if (preview) {
                    preview.src = result.thumb || result.url
                    preview.classList.remove('default-image')
                }
                const remove = box.querySelector('.image-picker-remove-button')
                if (remove) remove.style.display = ''
            }
            done(t.imageSet)
        }, true)
        action(t.again, generate)
    }

    const request = () => mode === 'image'
        ? {
            url: root.dataset.imageUrl,
            body: { prompt: $('#torns-ai-image-prompt').value, shape: $('#torns-ai-shape').value, style: $('#torns-ai-style').value },
        }
        : {
            url: root.dataset.textUrl,
            body: {
                provider: $('#torns-ai-provider').value,
                prompt: $('#torns-ai-prompt').value,
                format: $('#torns-ai-format').value,
                tone: $('#torns-ai-tone').value,
                length: $('#torns-ai-length').value,
                language: $('#torns-ai-language').value,
                title: field('name')?.value || '',
                current: $('#torns-ai-rewrite').checked ? editor.get() : '',
            },
        }

    async function generate() {
        const status = $('[data-ai-status]')
        const button = $('[data-ai-generate]')
        const { url, body } = request()
        button.disabled = true
        status.classList.remove('text-danger')
        status.textContent = mode === 'image' ? t.creating : t.writing
        try {
            const res = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify(body),
            })
            const json = await res.json().catch(() => ({}))
            if (!res.ok || json.error) {
                const firstError = json.errors ? Object.values(json.errors)[0]?.[0] : null
                throw new Error(firstError || json.message || `Error ${res.status}`)
            }
            status.textContent = ''
            $('[data-ai-result]').hidden = false
            mode === 'image' ? showImage(json.data) : showText(body.format, json.data)
        } catch (error) {
            status.textContent = error.message
            status.classList.add('text-danger')
        } finally {
            button.disabled = false
        }
    }

    root.querySelectorAll('[data-ai-open]').forEach((button) => button.addEventListener('click', () => {
        setMode(button.dataset.aiOpen)
        syncFormat()
        dialog.showModal()
    }))
    $('#torns-ai-format').addEventListener('change', syncFormat)
    $('[data-ai-close]').addEventListener('click', () => dialog.close())
    $('[data-ai-generate]').addEventListener('click', generate)
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initTornsAi)
} else {
    initTornsAi()
}
