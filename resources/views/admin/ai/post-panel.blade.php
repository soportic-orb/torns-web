{{--
    "Write with AI" panel on the blog post form (injected by AppServiceProvider).
    Params: $providers (key => label), $defaultProvider, $imageReady, $language,
    plus the option lists from App\Ai\BlogAi.
--}}
<div class="torns-ai mb-3" id="torns-ai-panel" data-text-url="{{ route('torns-ai.text') }}" data-image-url="{{ route('torns-ai.image') }}">
    <div class="d-flex flex-wrap gap-2">
        @if ($providers)
            <button type="button" class="btn btn-outline-primary" data-ai-open="text">✨ Escribir con IA</button>
        @endif
        @if ($imageReady)
            <button type="button" class="btn btn-outline-primary" data-ai-open="image">✨ Crear la imagen destacada con IA</button>
        @endif
    </div>

    <dialog class="torns-ai__dialog" aria-labelledby="torns-ai-title" style="border: 0; border-radius: 12px; padding: 0; width: min(46rem, calc(100vw - 2rem))">
        <div class="torns-ai__box" style="padding: 1.25rem 1.5rem; max-height: calc(100vh - 4rem); overflow: auto">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h3 class="m-0" id="torns-ai-title" data-ai-heading>Escribir con IA</h3>
                <button type="button" class="btn-close" aria-label="Cerrar" data-ai-close></button>
            </div>

            {{-- Text --}}
            <div data-ai-mode="text">
                <label class="form-label" for="torns-ai-prompt">¿Sobre qué escribimos?</label>
                <textarea class="form-control mb-3" id="torns-ai-prompt" rows="4" placeholder="Ej.: cómo organizar un cambio de turno a tres en Navidad, con consejos prácticos para enfermeras de hospital"></textarea>

                <div class="row g-2 mb-3">
                    @if (count($providers) > 1)
                        <div class="col-6 col-md-4">
                            <label class="form-label" for="torns-ai-provider">Servicio</label>
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
                        <label class="form-label" for="torns-ai-format">Qué escribir</label>
                        <select class="form-select" id="torns-ai-format">
                            @foreach ($formats as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-4">
                        <label class="form-label" for="torns-ai-language">Idioma</label>
                        <select class="form-select" id="torns-ai-language">
                            @foreach ($languages as $key => $label)
                                <option value="{{ $key }}" @selected($key === $language)>{{ ucfirst($label) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-4" data-ai-article-only>
                        <label class="form-label" for="torns-ai-tone">Tono</label>
                        <select class="form-select" id="torns-ai-tone">
                            @foreach ($tones as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-4" data-ai-article-only>
                        <label class="form-label" for="torns-ai-length">Extensión</label>
                        <select class="form-select" id="torns-ai-length">
                            @foreach ($lengths as $key => $length)
                                <option value="{{ $key }}" @selected($key === 'medio')>{{ $length['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <label class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="torns-ai-rewrite">
                    <span class="form-check-label">Usar el texto que ya hay en la publicación (para reescribirlo o resumirlo)</span>
                </label>
            </div>

            {{-- Image --}}
            <div data-ai-mode="image" hidden>
                <label class="form-label" for="torns-ai-image-prompt">Describe la imagen</label>
                <textarea class="form-control mb-3" id="torns-ai-image-prompt" rows="3" placeholder="Ej.: una enfermera mira su calendario de turnos en el móvil en la sala de descanso, al amanecer"></textarea>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label" for="torns-ai-shape">Formato</label>
                        <select class="form-select" id="torns-ai-shape">
                            @foreach ($shapes as $key => $shape)
                                <option value="{{ $key }}">{{ $shape['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label" for="torns-ai-style">Estilo</label>
                        <select class="form-select" id="torns-ai-style">
                            @foreach ($styles as $key => $style)
                                <option value="{{ $key }}">{{ $style['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <p class="form-hint mb-3">Se guarda en Medios → blog-ia. Sin texto ni logotipos.</p>
            </div>

            <div class="d-flex gap-2 align-items-center mb-3">
                <button type="button" class="btn btn-primary" data-ai-generate>Generar</button>
                <span class="text-secondary small" data-ai-status role="status"></span>
            </div>

            <div data-ai-result hidden>
                <div class="torns-ai__preview mb-3" data-ai-preview></div>
                <div class="d-flex flex-wrap gap-2" data-ai-actions></div>
            </div>
        </div>
    </dialog>
</div>

<style>
    .torns-ai__dialog { box-shadow: 0 20px 60px rgb(0 0 0 / 0.25); }
    .torns-ai__dialog::backdrop { background: rgb(15 36 48 / 0.45); }
    .torns-ai__preview { border: 1px solid var(--bb-border-color, #dce5e8); border-radius: 8px; padding: 1rem; max-height: 22rem; overflow: auto; }
    .torns-ai__preview img { max-width: 100%; height: auto; border-radius: 6px; display: block; }
</style>

<script>
    (() => {
        const root = document.getElementById('torns-ai-panel')
        const dialog = root.querySelector('dialog')
        const $ = (selector) => dialog.querySelector(selector)
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content
        let mode = 'text'

        // Rich editor helpers: CKEditor (Botble default), TinyMCE or the plain textarea.
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
        const field = (name) => document.querySelector(`[name="${name}"]`)

        const setMode = (next) => {
            mode = next
            dialog.querySelectorAll('[data-ai-mode]').forEach((el) => { el.hidden = el.dataset.aiMode !== mode })
            $('[data-ai-heading]').textContent = mode === 'image' ? 'Crear la imagen destacada con IA' : 'Escribir con IA'
            $('[data-ai-result]').hidden = true
            $('[data-ai-status]').textContent = ''
            if (mode === 'image' && !$('#torns-ai-image-prompt').value) {
                const title = field('name')?.value?.trim()
                if (title) $('#torns-ai-image-prompt').value = `Imagen para el artículo «${title}»`
            }
        }
        const syncFormat = () => {
            const article = $('#torns-ai-format').value === 'article'
            dialog.querySelectorAll('[data-ai-article-only]').forEach((el) => { el.hidden = !article })
        }

        root.querySelectorAll('[data-ai-open]').forEach((button) => button.addEventListener('click', () => {
            setMode(button.dataset.aiOpen)
            syncFormat()
            dialog.showModal()
        }))
        $('#torns-ai-format').addEventListener('change', syncFormat)
        $('[data-ai-close]').addEventListener('click', () => dialog.close())

        const action = (label, handler, primary = false) => {
            const button = document.createElement('button')
            button.type = 'button'
            button.className = primary ? 'btn btn-primary' : 'btn btn-outline-secondary'
            button.textContent = label
            button.addEventListener('click', handler)
            $('[data-ai-actions]').append(button)
        }
        const done = (message) => {
            dialog.close()
            if (window.Botble?.showSuccess) window.Botble.showSuccess(message)
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
                action('Sustituir el contenido', () => { editor.set(result.html); done('Contenido sustituido.') }, true)
                action('Añadir al final', () => { editor.set(editor.get() + result.html); done('Texto añadido al final.') })
            } else if (format === 'title') {
                action('Usar como título', () => { const el = field('name'); el.value = result.text; el.dispatchEvent(new Event('input', { bubbles: true })); done('Título actualizado.') }, true)
            } else {
                action('Usar como descripción', () => { const el = field('description'); el.value = result.text; el.dispatchEvent(new Event('input', { bubbles: true })); done('Descripción actualizada.') }, true)
            }
            action('Volver a generar', generate)
        }

        const showImage = (result) => {
            const img = document.createElement('img')
            img.src = result.url
            img.alt = ''
            $('[data-ai-preview]').replaceChildren(img)
            $('[data-ai-actions]').replaceChildren()
            action('Usar como imagen destacada', () => {
                const box = document.querySelector('.image-box-image')
                if (box) {
                    const input = box.querySelector('.image-data')
                    input.value = result.path
                    input.dispatchEvent(new Event('change', { bubbles: true }))
                    const preview = box.querySelector('.preview-image')
                    if (preview) { preview.src = result.thumb || result.url; preview.classList.remove('default-image') }
                    const remove = box.querySelector('.image-picker-remove-button')
                    if (remove) remove.style.display = ''
                }
                done('Imagen destacada actualizada. Guarda la publicación para conservarla.')
            }, true)
            action('Volver a generar', generate)
        }

        async function generate() {
            const status = $('[data-ai-status]')
            const button = $('[data-ai-generate]')
            let url, body
            if (mode === 'image') {
                url = root.dataset.imageUrl
                body = { prompt: $('#torns-ai-image-prompt').value, shape: $('#torns-ai-shape').value, style: $('#torns-ai-style').value }
            } else {
                url = root.dataset.textUrl
                body = {
                    provider: $('#torns-ai-provider').value,
                    prompt: $('#torns-ai-prompt').value,
                    format: $('#torns-ai-format').value,
                    tone: $('#torns-ai-tone').value,
                    length: $('#torns-ai-length').value,
                    language: $('#torns-ai-language').value,
                    title: field('name')?.value || '',
                    current: $('#torns-ai-rewrite').checked ? editor.get() : '',
                }
            }
            button.disabled = true
            status.classList.remove('text-danger')
            status.textContent = mode === 'image' ? 'Creando la imagen… (hasta un minuto)' : 'Escribiendo… (hasta un minuto)'
            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': csrf },
                    body: JSON.stringify(body),
                })
                const data = await res.json().catch(() => ({}))
                if (!res.ok) {
                    const firstError = data.errors ? Object.values(data.errors)[0]?.[0] : null
                    throw new Error(data.message && !firstError ? data.message : firstError || data.message || `Error ${res.status}`)
                }
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
        $('[data-ai-generate]').addEventListener('click', generate)
    })()
</script>
