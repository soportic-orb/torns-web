<?php

namespace App\Ai;

use Botble\Media\Facades\RvMedia;
use DOMDocument;
use DOMElement;
use DOMNode;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * AI writing in the blog post editor: post body, title and summary with
 * Claude (Anthropic) or OpenAI, and the featured image with OpenAI.
 *
 * Same approach as the iSEM Tauler mailing editor (MailingAi): settings in
 * Botble's settings table with the API keys encrypted (APP_KEY), .env as
 * fallback, no SDKs. Off until an administrator enables it.
 */
class BlogAi
{
    public const ANTHROPIC_URL = 'https://api.anthropic.com/v1/messages';

    public const OPENAI_URL = 'https://api.openai.com/v1';

    /** Labels: torns.ai_format_<key>. */
    public const FORMATS = ['article', 'title', 'summary'];

    /** Tone as written in the prompt; labels: torns.ai_tone_<key>. */
    public const TONES = [
        'cercano' => 'Cercano',
        'informativo' => 'Informativo',
        'profesional' => 'Profesional',
        'divulgativo' => 'Divulgativo',
    ];

    public const LENGTHS = [
        'corto' => 'Unas 400 palabras, con 2 o 3 apartados.',
        'medio' => 'Unas 800 palabras, con 3 o 4 apartados.',
        'largo' => 'Unas 1.300 palabras, con 4 a 6 apartados.',
    ];

    /** Language as named in the prompt; labels: torns.ai_lang_<key>. */
    public const LANGUAGES = [
        'es' => 'castellano',
        'ca' => 'catalán',
        'gl' => 'gallego',
        'eu' => 'euskera',
    ];

    public const SHAPES = [
        'landscape' => '1536x1024',
        'square' => '1024x1024',
    ];

    public const STYLES = [
        'photo' => 'Realistic editorial photograph, natural light, clean composition, Spanish healthcare setting.',
        'illustration' => 'Modern editorial illustration, soft colours with touches of teal #227A96 and warm yellow #F5A623.',
        'flat' => 'Flat minimalist illustration, simple shapes, light background, teal #227A96, navy #0F2430 and warm yellow #F5A623.',
    ];

    /** Setting key => fallback in config/torns-ai.php (.env). */
    public const SETTINGS = [
        'torns_ai_enabled' => 'enabled',
        'torns_ai_default_text' => 'default_text',
        'torns_ai_anthropic_key' => 'anthropic_key',
        'torns_ai_claude_model' => 'claude_model',
        'torns_ai_openai_key' => 'openai_key',
        'torns_ai_openai_text_model' => 'openai_text_model',
        'torns_ai_openai_image_model' => 'openai_image_model',
    ];

    public const SECRETS = ['torns_ai_anthropic_key', 'torns_ai_openai_key'];

    /**
     * What the model may say about Torns. Anything else about the product
     * must come from the request; keep in sync with content/site.
     */
    protected const FACTS = <<<'TXT'
        Torns es una app (iOS, Android, Apple Watch, widgets y web app.torns.app) para profesionales sanitarios en España: enfermeras, médicos, TCAE, TES, celadores, matronas, fisioterapeutas y otros que trabajan a turnos o guardias.
        - Calendario laboral unificado con los turnos y guardias de todos los centros donde trabajas; vistas de mes, día, lista y año; vacaciones y días no disponibles; sincronización con Apple Calendar y Google Calendar; funciona sin conexión.
        - Cambios de turno entre compañeros del mismo centro y categoría: se publica el turno en el Muro, los compañeros proponen uno suyo y, al aceptar, se actualizan los calendarios. Chat individual y de grupo.
        - Importar el cuadrante: a partir de una foto o un documento, la IA encuentra la fila de la persona y propone sus turnos para revisarlos; el archivo no se guarda.
        - Torns Pro: Copilot (asistente con IA que busca cambios compatibles y cadenas de 3 o 4 compañeros), consultor legal (responde sobre permisos, jornada y descansos citando el convenio o la norma), modo SOS para cambios urgentes, aviso por e-mail al gestor del cuadrante, recordatorios, rotaciones, estadísticas de horas, compartir e imprimir el calendario.
        - Planes: Free (1 centro, 3 cambios aceptados al mes); Pro 2,99 €/mes o 29,90 €/año; 30 días de prueba de Pro al registrarse, sin tarjeta.
        - Para centros: panel de organización con licencias desde 0,39 € por usuario activo y mes (sin IVA). Asociaciones y colegios pueden patrocinar Torns Pro a sus socios.
        - Idiomas: castellano, català, galego y euskara.
        TXT;

    // ------------------------------------------------------------------ settings

    public function get(string $key): string
    {
        $value = null;
        $raw = setting($key);
        if (is_string($raw) && $raw !== '') {
            $value = in_array($key, self::SECRETS, true) ? $this->decrypt($raw) : $raw;
        }

        return trim((string) ($value ?? config('torns-ai.' . self::SETTINGS[$key], '')));
    }

    /** Save settings (keys encrypted). An empty key keeps the stored one; "-" deletes it. */
    public function save(array $values): void
    {
        foreach (self::SETTINGS as $key => $_) {
            if (! array_key_exists($key, $values)) {
                continue;
            }
            $value = trim((string) $values[$key]);
            if (in_array($key, self::SECRETS, true)) {
                if ($value === '') {
                    continue;
                }
                $value = $value === '-' ? '' : Crypt::encryptString($value);
            }
            setting()->forceSet($key, $value);
        }
        setting()->save();
    }

    protected function decrypt(string $raw): ?string
    {
        try {
            return Crypt::decryptString($raw);
        } catch (\Throwable) {
            return null;
        }
    }

    public function enabled(): bool
    {
        return $this->get('torns_ai_enabled') === '1';
    }

    public function claudeReady(): bool
    {
        return $this->get('torns_ai_anthropic_key') !== '';
    }

    public function openaiReady(): bool
    {
        return $this->get('torns_ai_openai_key') !== '';
    }

    /** @return array<string, string> */
    public function textProviders(): array
    {
        if (! $this->enabled()) {
            return [];
        }

        return array_filter([
            'claude' => $this->claudeReady() ? 'Claude' : null,
            'openai' => $this->openaiReady() ? 'ChatGPT' : null,
        ]);
    }

    /** Images: OpenAI only (Claude does not generate images). */
    public function imageReady(): bool
    {
        return $this->enabled() && $this->openaiReady();
    }

    public function defaultTextProvider(): ?string
    {
        $providers = $this->textProviders();
        $wanted = $this->get('torns_ai_default_text');

        return isset($providers[$wanted]) ? $wanted : (array_key_first($providers) ?: null);
    }

    public function claudeModel(): string
    {
        return $this->get('torns_ai_claude_model') ?: 'claude-opus-5-5';
    }

    public function openaiTextModel(): string
    {
        return $this->get('torns_ai_openai_text_model') ?: 'gpt-5';
    }

    public function openaiImageModel(): string
    {
        return $this->get('torns_ai_openai_image_model') ?: 'gpt-image-1';
    }

    /** "sk-…abcd", to show that a key is stored without showing it. */
    public function masked(string $key): string
    {
        $value = $this->get($key);

        return $value === '' ? '' : mb_substr($value, 0, 3) . '…' . mb_substr($value, -4);
    }

    // ------------------------------------------------------------------ text

    /**
     * @param  array{format?: string, tone?: string, length?: string, language?: string, title?: string, current?: string}  $opts
     * @return array{html: string, text: string, provider: string}
     */
    public function text(string $provider, string $prompt, array $opts, ?int $userId = null): array
    {
        if (! isset($this->textProviders()[$provider])) {
            throw new RuntimeException(trans('torns.ai_err_not_configured'));
        }
        $prompt = $this->plain($prompt, 4000);
        if ($prompt === '') {
            throw new RuntimeException(trans('torns.ai_err_prompt'));
        }
        $format = in_array($opts['format'] ?? '', self::FORMATS, true) ? $opts['format'] : 'article';
        $system = $this->system($format, $opts);
        $message = $this->userMessage($prompt, $opts);
        $max = $format === 'article' ? 6000 : 400;

        [$raw, $usage] = $provider === 'claude'
            ? $this->claude($system, $message, $max)
            : $this->openaiText($system, $message, $max);

        Log::info('Torns blog AI: text', ['user' => $userId, 'provider' => $provider, 'format' => $format] + $usage);

        return $this->shape($raw, $format) + ['provider' => $provider];
    }

    protected function system(string $format, array $opts): string
    {
        $language = self::LANGUAGES[$opts['language'] ?? ''] ?? self::LANGUAGES['es'];
        $tone = self::TONES[$opts['tone'] ?? ''] ?? self::TONES['cercano'];
        $length = self::LENGTHS[$opts['length'] ?? ''] ?? self::LENGTHS['medio'];

        $base = 'Escribes para el blog de Torns (www.torns.app), leído por profesionales sanitarios en España que trabajan a turnos y guardias. '
            . "Escribe en {$language} correcto y natural, aunque la petición esté en otro idioma. Tono: {$tone}. "
            . "Esto es lo que se puede decir de Torns; no le atribuyas ninguna otra funcionalidad, precio ni dato:\n" . self::FACTS . "\n"
            . 'No inventes datos concretos que no estén en la petición (cifras, fechas, estudios, normas, artículos de convenios, nombres de personas o centros): si hacen falta, pon un marcador entre corchetes, como [dato] o [fuente]. '
            . 'No compares Torns con otras aplicaciones por su nombre. No des consejos médicos ni hables de pacientes concretos. '
            . 'Responde solo con el contenido pedido, sin explicaciones ni comentarios sobre lo que has hecho.';

        return $base . ' ' . match ($format) {
            'title' => 'Formato: un solo título en texto plano, de 50 a 70 caracteres, sin comillas ni punto final.',
            'summary' => 'Formato: un resumen en texto plano de una o dos frases (máximo 160 caracteres), para la descripción y los buscadores.',
            default => 'Formato: el cuerpo del artículo en HTML, sin el título principal. Apartados con <h2> (y <h3> si hace falta), párrafos <p>, listas <ul>/<ol> con <li>, <strong>, <em>, <blockquote> y enlaces <a href="https://…"> solo si la petición da la dirección. Ninguna otra etiqueta ni estilos. Termina con un párrafo que invite a probar Torns de forma natural, sin exagerar. ' . $length,
        };
    }

    protected function userMessage(string $prompt, array $opts): string
    {
        $out = '';
        $title = $this->plain($opts['title'] ?? '', 255);
        if ($title !== '') {
            $out .= "Título actual de la publicación: {$title}\n\n";
        }
        $current = $this->plain($opts['current'] ?? '', 20000);
        if ($current !== '') {
            $out .= "<texto_actual>\n{$current}\n</texto_actual>\n\nReescribe el texto actual siguiendo esta petición: {$prompt}";
        } else {
            $out .= "Petición: {$prompt}";
        }

        return $out;
    }

    /** @return array{html: string, text: string} */
    protected function shape(string $raw, string $format): array
    {
        $raw = trim((string) preg_replace('/^```[a-z]*\s*|\s*```$/i', '', trim($raw)));
        if ($raw === '') {
            throw new RuntimeException(trans('torns.ai_err_empty'));
        }
        if ($format !== 'article') {
            $quotes = " \t\"'«»“”*#";
            $line = trim(strip_tags(html_entity_decode(preg_split('/\R/', $raw)[0], ENT_QUOTES | ENT_HTML5, 'UTF-8')), $quotes);
            if ($format === 'title') {
                $line = trim(rtrim($line, '.'), $quotes);
            }

            return ['html' => '', 'text' => mb_substr($line, 0, $format === 'title' ? 120 : 300)];
        }
        if (! preg_match('/<(p|h2|h3|ul|ol|li|br)\b/i', $raw)) {
            $raw = implode('', array_map(fn ($p) => '<p>' . nl2br(e(trim($p)), false) . '</p>', preg_split('/\R{2,}/', $raw)));
        }
        $html = $this->clean($raw);
        if ($html === '') {
            throw new RuntimeException(trans('torns.ai_err_unusable'));
        }

        return ['html' => $html, 'text' => trim(strip_tags($html))];
    }

    /** @return array{0: string, 1: array<string, int>} text and token usage */
    protected function claude(string $system, string $prompt, int $max): array
    {
        $model = $this->claudeModel();
        $body = ['model' => $model, 'max_tokens' => $max, 'system' => $system, 'messages' => [['role' => 'user', 'content' => $prompt]]];
        $headers = ['x-api-key' => $this->get('torns_ai_anthropic_key'), 'anthropic-version' => '2023-06-01'];
        // Routine writing: low effort, on the models that support it.
        if (preg_match('/^claude-(opus-(4-[5-9]|5)|sonnet-(4-6|5)|fable|haiku-5)/', $model)) {
            $body['output_config'] = ['effort' => 'low'];
        }
        // If the safety filters refuse it, the server retries with another model.
        if (preg_match('/^claude-(opus-5|sonnet-5-5|fable)/', $model)) {
            $body['fallbacks'] = 'default';
            $headers['anthropic-beta'] = 'server-side-fallback-2026-07-01';
        }

        $res = Http::withHeaders($headers)->acceptJson()->timeout(180)->post(self::ANTHROPIC_URL, $body);
        if ($res->failed()) {
            throw new RuntimeException(trans('torns.ai_err_claude', ['error' => $res->json('error.message') ?: 'error ' . $res->status()]));
        }
        if ($res->json('stop_reason') === 'refusal') {
            throw new RuntimeException(trans('torns.ai_err_claude_refusal'));
        }
        $text = '';
        foreach ((array) $res->json('content', []) as $block) {
            if (($block['type'] ?? null) === 'text') {
                $text .= $block['text'];
            }
        }

        return [$text, ['model' => (string) $res->json('model', $model), 'input_tokens' => (int) $res->json('usage.input_tokens'), 'output_tokens' => (int) $res->json('usage.output_tokens')]];
    }

    /** OpenAI Responses API. @return array{0: string, 1: array<string, int>} */
    protected function openaiText(string $system, string $prompt, int $max): array
    {
        $model = $this->openaiTextModel();
        $body = ['model' => $model, 'instructions' => $system, 'input' => $prompt, 'max_output_tokens' => $max + 2000];
        if (preg_match('/^(gpt-5|o\d)/', $model)) {
            $body['reasoning'] = ['effort' => 'low'];
        }
        $res = Http::withToken($this->get('torns_ai_openai_key'))->acceptJson()->timeout(180)->post(self::OPENAI_URL . '/responses', $body);
        if ($res->failed()) {
            throw new RuntimeException(trans('torns.ai_err_openai', ['error' => $res->json('error.message') ?: 'error ' . $res->status()]));
        }
        $text = (string) ($res->json('output_text') ?? '');
        if ($text === '') {
            foreach ((array) $res->json('output', []) as $item) {
                foreach ((array) ($item['content'] ?? []) as $content) {
                    if (($content['type'] ?? '') === 'output_text') {
                        $text .= (string) ($content['text'] ?? '');
                    } elseif (($content['type'] ?? '') === 'refusal') {
                        throw new RuntimeException(trans('torns.ai_err_openai_refusal'));
                    }
                }
            }
        }

        return [$text, ['model' => $model, 'input_tokens' => (int) $res->json('usage.input_tokens'), 'output_tokens' => (int) $res->json('usage.output_tokens')]];
    }

    // ------------------------------------------------------------------ images

    /**
     * Create a featured image and store it in the media library ("Blog IA" folder).
     *
     * @return array{path: string, url: string, thumb: string}
     */
    public function image(string $prompt, string $shape, string $style, ?int $userId = null): array
    {
        if (! $this->imageReady()) {
            throw new RuntimeException(trans('torns.ai_err_image_key'));
        }
        $prompt = $this->plain($prompt, 3000);
        if ($prompt === '') {
            throw new RuntimeException(trans('torns.ai_err_image_prompt'));
        }
        $size = self::SHAPES[$shape] ?? self::SHAPES['landscape'];
        $full = $prompt . "\n\n" . (self::STYLES[$style] ?? self::STYLES['photo'])
            . ' Featured image for an article on a blog for healthcare workers in Spain who work shifts: respectful and positive,'
            . ' no blood, no injuries, no identifiable patients, no hospital brand names.'
            . ' No text, letters, logos or watermarks in the image.';

        $model = $this->openaiImageModel();
        $body = ['model' => $model, 'prompt' => $full, 'n' => 1];
        if (str_starts_with($model, 'gpt-image')) {
            $body += ['size' => $size, 'quality' => 'medium', 'output_format' => 'jpeg'];
        } else {
            // dall-e-3: its own sizes and base64 response.
            $body += ['size' => $shape === 'square' ? '1024x1024' : '1792x1024', 'response_format' => 'b64_json'];
        }

        $res = Http::withToken($this->get('torns_ai_openai_key'))->acceptJson()->timeout(240)->post(self::OPENAI_URL . '/images/generations', $body);
        if ($res->failed()) {
            $message = (string) ($res->json('error.message') ?: 'error ' . $res->status());
            throw new RuntimeException(str_contains(strtolower($message), 'safety') || $res->json('error.code') === 'moderation_blocked'
                ? trans('torns.ai_err_image_moderation')
                : trans('torns.ai_err_image_failed', ['error' => $message]));
        }
        $bytes = base64_decode((string) $res->json('data.0.b64_json'), true);
        if (! $bytes) {
            throw new RuntimeException(trans('torns.ai_err_image_empty'));
        }

        $tmp = tempnam(sys_get_temp_dir(), 'ai') . '.jpg';
        file_put_contents($tmp, $bytes);
        try {
            $result = RvMedia::uploadFromBlob(new UploadedFile($tmp, 'imagen-ia.jpg', 'image/jpeg', null, true), 'imagen-ia-' . now()->format('Ymd-His') . '.jpg', 0, 'blog-ia');
        } finally {
            @unlink($tmp);
        }
        if (! empty($result['error']) || empty($result['data'])) {
            throw new RuntimeException(trans('torns.ai_err_image_save', ['error' => $result['message'] ?? '']));
        }
        $path = (string) $result['data']->url;
        Log::info('Torns blog AI: image', ['user' => $userId, 'model' => $model, 'path' => $path]);

        return ['path' => $path, 'url' => RvMedia::url($path), 'thumb' => RvMedia::getImageUrl($path, 'thumb')];
    }

    // ------------------------------------------------------------------ test

    /** @return array<string, array{ok: bool, message: string}> */
    public function test(?int $userId = null): array
    {
        $out = [];
        foreach ($this->textProviders() as $provider => $label) {
            try {
                $result = $this->text($provider, 'Escribe solo la palabra «Correcto».', ['format' => 'title'], $userId);
                $model = $provider === 'claude' ? $this->claudeModel() : $this->openaiTextModel();
                $out[$provider] = ['ok' => true, 'message' => trans('torns.ai_test_ok', ['service' => $label, 'model' => $model, 'answer' => $result['text']])
                    . ($provider === 'openai' ? ' ' . trans('torns.ai_test_images', ['model' => $this->openaiImageModel()]) : '')];
            } catch (\Throwable $e) {
                $out[$provider] = ['ok' => false, 'message' => $e->getMessage()];
            }
        }

        return $out;
    }

    // ------------------------------------------------------------------ helpers

    protected function plain(?string $value, int $max): string
    {
        $value = trim(preg_replace('/[ \t]+/', ' ', strip_tags(html_entity_decode((string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8'))) ?? '');

        return mb_substr($value, 0, $max);
    }

    /** Keep only the tags the article format allows; links only with http(s) href. */
    protected function clean(string $html): string
    {
        $allowed = ['p', 'h2', 'h3', 'h4', 'ul', 'ol', 'li', 'strong', 'em', 'b', 'i', 'a', 'blockquote', 'br'];
        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="utf-8"?><div id="root">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        $root = $doc->getElementById('root');
        if (! $root) {
            return '';
        }

        $walk = function (DOMNode $node) use (&$walk, $allowed): void {
            foreach (iterator_to_array($node->childNodes) as $child) {
                if ($child instanceof DOMElement) {
                    $walk($child);
                    $tag = strtolower($child->tagName);
                    if (! in_array($tag, $allowed, true)) {
                        // Unwrap: keep the text, drop the tag (and scripts entirely).
                        if (in_array($tag, ['script', 'style', 'iframe', 'object'], true)) {
                            $node->removeChild($child);

                            continue;
                        }
                        while ($child->firstChild) {
                            $node->insertBefore($child->firstChild, $child);
                        }
                        $node->removeChild($child);

                        continue;
                    }
                    $href = $tag === 'a' ? $child->getAttribute('href') : null;
                    foreach (iterator_to_array($child->attributes) as $attribute) {
                        $child->removeAttribute($attribute->nodeName);
                    }
                    if ($tag === 'a') {
                        if ($href && preg_match('#^https?://#i', $href)) {
                            $child->setAttribute('href', $href);
                        } else {
                            while ($child->firstChild) {
                                $node->insertBefore($child->firstChild, $child);
                            }
                            $node->removeChild($child);
                        }
                    }
                } elseif ($child->nodeType === XML_COMMENT_NODE) {
                    $node->removeChild($child);
                }
            }
        };
        $walk($root);

        $out = '';
        foreach ($root->childNodes as $child) {
            $out .= $doc->saveHTML($child);
        }

        return trim($out);
    }
}
