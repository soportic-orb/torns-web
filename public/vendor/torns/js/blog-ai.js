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
        $('[data-ai-heading]').textContent = { image: t.image, translate: t.translate }[mode] || t.write
        dialog.querySelectorAll('[data-ai-common]').forEach((el) => {
            el.hidden = mode === 'translate'
        })
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

    const post = async (url, body) => {
        const res = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify(body),
        })
        const json = await res.json().catch(() => ({}))
        if (!res.ok || json.error) {
            const firstError = json.errors ? Object.values(json.errors)[0]?.[0] : null
            const timeout = [502, 503, 504, 524].includes(res.status) && t.translateTimeout
            throw new Error(firstError || json.message || timeout || `Error ${res.status}`)
        }
        return json.data
    }

    // One request per language: each translation is saved as soon as it is ready.
    async function translate() {
        const button = $('[data-ai-translate]')
        const summary = $('[data-ai-translate-summary]')
        const progress = $('[data-ai-translate-progress]')
        const bar = $('[data-ai-translate-bar]')
        const languages = [...dialog.querySelectorAll('[data-ai-translate-language]:checked')].map((el) => el.value)
        const labelOf = (code) => dialog.querySelector(`label[for="torns-ai-tr-${code}"]`)?.firstChild?.textContent.trim() || code
        const badge = (code, text, tone) => {
            const el = $(`[data-ai-translate-status="${code}"]`)
            el.hidden = false
            el.className = `badge ms-2 bg-${tone}-lt text-${tone}`
            el.textContent = text
        }
        const setProgress = (done) => {
            const percent = Math.round((done / languages.length) * 100)
            bar.style.width = `${Math.max(percent, 4)}%`
            bar.parentElement.setAttribute('aria-valuenow', String(percent))
            $('[data-ai-translate-count]').textContent = t.translateCount.replace(':done', done).replace(':total', languages.length)
        }

        summary.className = 'small'
        if (!languages.length) {
            summary.classList.add('text-danger')
            summary.textContent = t.translateNone
            return
        }

        button.disabled = true
        $('[data-ai-translate-spinner]').hidden = false
        $('[data-ai-translate-label]').textContent = t.translateRunning
        summary.textContent = ''
        dialog.querySelectorAll('[data-ai-translate-status]').forEach((el) => { el.hidden = true })
        languages.forEach((code) => badge(code, t.translatePending, 'secondary'))
        progress.hidden = false
        bar.classList.add('progress-bar-animated', 'progress-bar-striped')
        bar.classList.remove('bg-success', 'bg-warning')
        $('[data-ai-translate-wait]').hidden = false
        setProgress(0)

        let saved = 0
        let failed = 0
        for (const [index, language] of languages.entries()) {
            $('[data-ai-translate-current]').textContent = t.translateCurrent.replace(':language', labelOf(language))
            badge(language, t.translating, 'blue')
            try {
                const result = await post(root.dataset.translateUrl, {
                    post_id: root.dataset.postId,
                    language,
                    provider: $('#torns-ai-tr-provider').value,
                    overwrite: $('#torns-ai-tr-overwrite').checked,
                })
                if (result.status === 'saved') {
                    saved++
                    badge(language, t.translated, 'green')
                } else {
                    badge(language, t.skipped, 'secondary')
                }
            } catch (error) {
                failed++
                badge(language, error.message, 'red')
            }
            setProgress(index + 1)
        }

        bar.classList.remove('progress-bar-animated', 'progress-bar-striped')
        bar.classList.add(failed ? 'bg-warning' : 'bg-success')
        $('[data-ai-translate-current]').textContent = ''
        $('[data-ai-translate-wait]').hidden = true
        summary.classList.add(failed ? 'text-warning' : 'text-success')
        summary.textContent = t.translateDone.replace(':count', saved) + (failed ? ' ' + t.translateFailed.replace(':count', failed) : '')
        $('[data-ai-translate-spinner]').hidden = true
        $('[data-ai-translate-label]').textContent = t.translateRun
        button.disabled = false
    }

    async function generate() {
        const status = $('[data-ai-status]')
        const button = $('[data-ai-generate]')
        const { url, body } = request()
        button.disabled = true
        status.classList.remove('text-danger')
        status.textContent = mode === 'image' ? t.creating : t.writing
        try {
            const data = await post(url, body)
            status.textContent = ''
            $('[data-ai-result]').hidden = false
            mode === 'image' ? showImage(data) : showText(body.format, data)
        } catch (error) {
            status.textContent = error.message
            status.classList.add('text-danger')
        } finally {
            button.disabled = false
        }
    }

    // SEO title and description from the post as it is in the editor.
    async function generateSeo() {
        const button = root.querySelector('[data-ai-seo]')
        const label = root.querySelector('[data-ai-seo-label]')
        const original = label.textContent
        button.disabled = true
        root.querySelector('[data-ai-seo-spinner]').hidden = false
        label.textContent = t.seoRunning
        try {
            const data = await post(root.dataset.seoUrl, {
                provider: root.dataset.provider,
                title: field('name')?.value || '',
                description: field('description')?.value || '',
                content: editor.get(),
                language: root.dataset.language,
            })
            // The SEO box starts folded; open it so the new values are visible.
            const section = document.querySelector('.seo-edit-section')
            if (section && getComputedStyle(section).display === 'none') {
                document.querySelector('.btn-trigger-show-seo-detail')?.click()
            }
            setField('seo_meta[seo_title]', data.seo_title)
            setField('seo_meta[seo_description]', data.seo_description)
            // Search preview above the fields (Botble only refreshes it for #seo_title).
            const previewTitle = document.querySelector('.page-title-seo')
            const previewDescription = document.querySelector('.page-description-seo')
            if (previewTitle) previewTitle.textContent = data.seo_title
            if (previewDescription) previewDescription.textContent = data.seo_description
            document.querySelector('.default-seo-description')?.classList.add('hidden')
            document.querySelector('.existed-seo-meta')?.classList.remove('hidden')
            window.Botble?.showSuccess?.(t.seoDone)
        } catch (error) {
            if (window.Botble?.showError) window.Botble.showError(error.message)
            else window.alert(error.message)
        } finally {
            label.textContent = original
            root.querySelector('[data-ai-seo-spinner]').hidden = true
            button.disabled = false
        }
    }

    root.querySelector('[data-ai-seo]')?.addEventListener('click', generateSeo)
    root.querySelectorAll('[data-ai-open]').forEach((button) => button.addEventListener('click', () => {
        setMode(button.dataset.aiOpen)
        syncFormat()
        dialog.showModal()
    }))
    $('#torns-ai-format').addEventListener('change', syncFormat)
    $('[data-ai-translate]')?.addEventListener('click', translate)
    $('[data-ai-close]').addEventListener('click', () => dialog.close())
    $('[data-ai-generate]').addEventListener('click', generate)
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initTornsAi)
} else {
    initTornsAi()
}
