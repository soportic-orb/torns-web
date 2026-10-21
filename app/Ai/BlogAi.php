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
     * @return array{html: string, text: string, provider: string, description?: string}
     */
    public function text(string $provider, string $prompt, array $opts, ?int $userId = null): array
    {
        if (! isset($this->textProviders()[$provider])) {
            throw new RuntimeException(trans('torns.ai_err_not_configured'));
        }
        $prompt = $this->plain($prompt, 4000);
        $format = in_array($opts['format'] ?? '', self::FORMATS, true) ? $opts['format'] : 'article';
        // With the post's own text, the request is optional: summarise it, title it or improve it.
        if ($prompt === '' && $this->blockText($opts['current'] ?? '', 20000) === '') {
            throw new RuntimeException(trans('torns.ai_err_prompt'));
        }
        $system = $this->system($format, $opts);
        $message = $this->userMessage($prompt, $opts);
        $max = $format === 'article' ? 6000 : 400;

        [$raw, $usage] = $provider === 'claude'
            ? $this->claude($system, $message, $max)
            : $this->openaiText($system, $message, $max);

        Log::info('Torns blog AI: text', ['user' => $userId, 'provider' => $provider, 'format' => $format] + $usage);

        $result = $this->shape($raw, $format) + ['provider' => $provider];
        if ($format === 'article') {
            $result['description'] = $this->articleSummary($provider, $result['html'], $opts, $userId);
        }

        return $result;
    }

    /** Summary for the post's Description field, written from the generated article. Empty if it fails. */
    protected function articleSummary(string $provider, string $html, array $opts, ?int $userId): string
    {
        $message = '';
        $title = $this->plain($opts['title'] ?? '', 255);
        if ($title !== '') {
            $message .= "Título de la publicación: {$title}\n\n";
        }
        $message .= "<articulo>\n" . $this->blockText($html, 20000) . "\n</articulo>\n\nEscribe el resumen de este artículo.";
        $system = $this->system('summary', $opts);

        try {
            [$raw, $usage] = $provider === 'claude'
                ? $this->claude($system, $message, 400)
                : $this->openaiText($system, $message, 400);
            Log::info('Torns blog AI: text', ['user' => $userId, 'provider' => $provider, 'format' => 'summary'] + $usage);

            return $this->shape($raw, 'summary')['text'];
        } catch (\Throwable $e) {
            Log::warning('Torns blog AI: article summary failed', ['user' => $userId, 'provider' => $provider, 'error' => $e->getMessage()]);

            return '';
        }
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
        $current = $this->blockText($opts['current'] ?? '', 20000);
        if ($current !== '') {
            $task = match ($opts['format'] ?? '') {
                'summary' => 'Escribe el resumen del texto actual',
                'title' => 'Propón un título para el texto actual',
                default => 'Reescribe el texto actual',
            };
            $out .= "<texto_actual>\n{$current}\n</texto_actual>\n\n" . ($prompt !== '' ? "{$task} siguiendo esta petición: {$prompt}" : "{$task}.");
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
    protected function claude(string $system, string $prompt, int $max, int $timeout = 180): array
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

        $res = Http::withHeaders($headers)->acceptJson()->timeout($timeout)->post(self::ANTHROPIC_URL, $body);
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
    protected function openaiText(string $system, string $prompt, int $max, int $timeout = 180): array
    {
        $model = $this->openaiTextModel();
        $body = ['model' => $model, 'instructions' => $system, 'input' => $prompt, 'max_output_tokens' => $max + 2000];
        if (preg_match('/^(gpt-5|o\d)/', $model)) {
            $body['reasoning'] = ['effort' => 'low'];
        }
        $res = Http::withToken($this->get('torns_ai_openai_key'))->acceptJson()->timeout($timeout)->post(self::OPENAI_URL . '/responses', $body);
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

    // ------------------------------------------------------------------ SEO

    /** Lengths search engines show without cutting. */
    public const SEO_TITLE_MAX = 60;

    public const SEO_DESCRIPTION_MAX = 155;

    /**
     * SEO title and description for a post, from its title, summary and body.
     *
     * @param  array{title?: string, description?: string, content?: string, language?: string}  $post
     * @return array{seo_title: string, seo_description: string}
     */
    public function seo(string $provider, array $post, ?int $userId = null): array
    {
        if (! isset($this->textProviders()[$provider])) {
            throw new RuntimeException(trans('torns.ai_err_not_configured'));
        }
        $title = $this->plain($post['title'] ?? '', 255);
        $content = $this->plain($post['content'] ?? '', 12000);
        if ($title === '' && $content === '') {
            throw new RuntimeException(trans('torns.ai_err_seo_empty'));
        }
        $language = self::LANGUAGES[$post['language'] ?? ''] ?? self::LANGUAGES['es'];

        $system = 'Escribes el título y la descripción para buscadores (Google, Bing) y asistentes de IA de una publicación del blog de Torns (www.torns.app), leído por profesionales sanitarios en España que trabajan a turnos y guardias. '
            . "Escribe en {$language}. "
            . 'Título SEO: de 45 a ' . self::SEO_TITLE_MAX . ' caracteres, con la búsqueda principal al principio, natural (no una lista de palabras clave), sin comillas; si cabe, termina en « | Torns». '
            . 'Descripción SEO: de 120 a ' . self::SEO_DESCRIPTION_MAX . ' caracteres, una o dos frases que resuman lo que aporta la publicación e inviten a leerla, sin comillas ni emojis. '
            . 'Usa solo lo que dice la publicación: no añadas datos, cifras ni funciones de Torns que no estén en ella. '
            . 'Responde solo con <seo_title>…</seo_title> y <seo_description>…</seo_description>.';

        $message = "<title>\n{$title}\n</title>\n\n";
        $summary = $this->plain($post['description'] ?? '', 1000);
        if ($summary !== '') {
            $message .= "<summary>\n{$summary}\n</summary>\n\n";
        }
        $message .= "<content>\n{$content}\n</content>";

        [$raw, $usage] = $provider === 'claude'
            ? $this->claude($system, $message, 400)
            : $this->openaiText($system, $message, 400);

        Log::info('Torns blog AI: seo', ['user' => $userId, 'provider' => $provider] + $usage);

        $out = [];
        foreach (['seo_title' => self::SEO_TITLE_MAX, 'seo_description' => self::SEO_DESCRIPTION_MAX] as $field => $max) {
            if (! preg_match("#<{$field}>\s*(.*?)\s*</{$field}>#s", $raw, $match)) {
                throw new RuntimeException(trans('torns.ai_err_unusable'));
            }
            $value = (string) preg_replace('/^[\s"\'«»“”]+|[\s"\'«»“”]+$/u', '', strip_tags(html_entity_decode($match[1], ENT_QUOTES | ENT_HTML5, 'UTF-8')));
            $out[$field] = $this->fit($value, $max);
        }

        return $out;
    }

    /** Short words a cut text must not end on (es, ca, gl, eu). */
    protected const DANGLING = ['a', 'al', 'amb', 'con', 'da', 'de', 'del', 'do', 'e', 'el', 'els', 'en', 'eta', 'i', 'la', 'las', 'les', 'lo', 'los', 'na', 'no', 'o', 'os', 'para', 'per', 'por', 'que', 'sen', 'sin', 'su', 'sus', 'tu', 'un', 'una', 'y'];

    /**
     * Make a text fit: drop a trailing " | Torns" first, then prefer ending
     * at a full sentence, else at a whole word that is not a connector.
     */
    protected function fit(string $value, int $max): string
    {
        $value = trim(preg_replace('/\s+/', ' ', $value) ?? '');
        if (mb_strlen($value) <= $max) {
            return $value;
        }
        $bare = (string) preg_replace('/\s*\|\s*Torns$/u', '', $value);
        if (mb_strlen($bare) <= $max) {
            return $bare;
        }

        $cut = mb_substr($bare, 0, $max);
        $sentence = mb_strrpos($cut, '. ');
        if ($sentence !== false && $sentence >= $max * 0.6) {
            return mb_substr($cut, 0, $sentence + 1);
        }

        $words = explode(' ', mb_substr($bare, 0, $max + 1));
        array_pop($words);
        while (count($words) > 1 && in_array(mb_strtolower((string) end($words)), self::DANGLING, true)) {
            array_pop($words);
        }

        return (string) preg_replace('/[\s,;:|–-]+$/u', '', implode(' ', $words));
    }

    // ------------------------------------------------------------------ translation

    /** Fields of a post that are translated, in the order the model returns them. */
    public const TRANSLATED_FIELDS = ['name', 'description', 'seo_title', 'seo_description', 'content'];

    /** What translate() is translating: a post, or a category or tag of the blog. */
    public const TRANSLATED_KINDS = [
        'post' => 'las publicaciones del blog',
        'category' => 'el nombre y la descripción de una categoría del blog',
        'tag' => 'el nombre y la descripción de una etiqueta del blog',
    ];

    /**
     * Translate a post (or a category or tag, $kind) from Spanish. Empty
     * source fields come back empty.
     *
     * @param  array<string, string>  $source  keys of TRANSLATED_FIELDS
     * @return array<string, string>
     */
    public function translate(string $provider, array $source, string $language, ?int $userId = null, string $kind = 'post'): array
    {
        if (! isset($this->textProviders()[$provider])) {
            throw new RuntimeException(trans('torns.ai_err_not_configured'));
        }
        if ($language === 'es' || ! isset(self::LANGUAGES[$language])) {
            throw new RuntimeException(trans('torns.ai_err_translate_language'));
        }

        $fields = array_filter(array_map(fn ($value) => trim((string) $value), array_intersect_key($source, array_flip(self::TRANSLATED_FIELDS))), 'strlen');
        if (! isset($fields['name'])) {
            throw new RuntimeException(trans('torns.ai_err_translate_empty'));
        }

        $target = self::LANGUAGES[$language];
        $what = self::TRANSLATED_KINDS[$kind] ?? self::TRANSLATED_KINDS['post'];
        $system = "Traduces del castellano al {$target} {$what} de Torns (www.torns.app), leído por profesionales sanitarios en España que trabajan a turnos y guardias. "
            . ($kind === 'post' ? '' : 'El nombre es un término corto que se muestra en un filtro y en la dirección web: tradúcelo igual de corto, sin artículo ni punto final, como lo diría un hablante nativo. ')
            . "Escribe un {$target} correcto y natural, con el vocabulario sanitario y laboral que se usa en ese idioma; no traduzcas palabra por palabra. "
            . 'Traduce todo el texto y nada más: no añadas, quites ni resumas información, ni comentes la traducción. '
            . 'No traduzcas los nombres Torns, Torns Pro ni Copilot, ni las direcciones web, correos electrónicos o nombres de marcas. '
            . 'En el contenido HTML conserva exactamente las mismas etiquetas, en el mismo orden, con los mismos atributos y valores (href, src, class, id, style); traduce solo el texto visible y los atributos alt y title. '
            . 'Deja intacto lo que vaya entre corchetes como [nombre ...] (códigos cortos), y los marcadores como [dato] o [fuente]. '
            . 'Responde solo con los campos que recibas, cada uno dentro de su misma etiqueta: <name>, <description>, <seo_title>, <seo_description> y <content>.';

        $message = '';
        foreach (self::TRANSLATED_FIELDS as $field) {
            if (isset($fields[$field])) {
                $message .= "<{$field}>\n{$fields[$field]}\n</{$field}>\n\n";
            }
        }
        // Room for the whole content: roughly one token per three characters, plus margin.
        $max = (int) min(32000, 1500 + mb_strlen($message) / 2);

        [$raw, $usage] = $provider === 'claude'
            ? $this->claude($system, trim($message), $max, 600)
            : $this->openaiText($system, trim($message), $max, 600);

        Log::info('Torns blog AI: translation', ['user' => $userId, 'provider' => $provider, 'language' => $language, 'kind' => $kind] + $usage);

        $out = [];
        foreach (self::TRANSLATED_FIELDS as $field) {
            if (! isset($fields[$field])) {
                $out[$field] = '';

                continue;
            }
            if (! preg_match("#<{$field}>\s*(.*?)\s*</{$field}>#s", $raw, $match) || trim($match[1]) === '') {
                throw new RuntimeException(trans('torns.ai_err_translate_incomplete'));
            }
            $out[$field] = $field === 'content'
                ? $this->safeHtml($match[1])
                : trim(strip_tags(html_entity_decode($match[1], ENT_QUOTES | ENT_HTML5, 'UTF-8')));
        }

        return $out;
    }

    /**
     * Translated post body: keep its markup (images, embeds, shortcodes),
     * drop only what could run code.
     */
    protected function safeHtml(string $html): string
    {
        $html = (string) preg_replace('#<(script|style)\b[^>]*>.*?</\1\s*>#is', '', $html);
        $html = (string) preg_replace('#\s+on[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)#i', '', $html);

        return trim((string) preg_replace('#(href|src)\s*=\s*(["\']?)\s*javascript:[^"\'>\s]*\2#i', '$1="#"', $html));
    }

    // ------------------------------------------------------------------ images

    /**
     * Create a featured image and store it in the media library ("Blog IA" folder).
     *
     * @return array{path: string, url: string, thumb: string}
     */
    /**
     * @param  string  $prompt  the editor's own indications (optional when the post has a title or text)
     * @param  array{title?: string, description?: string, content?: string}  $post  the post as it is in the editor
     */
    public function image(string $prompt, string $shape, string $style, ?int $userId = null, array $post = []): array
    {
        if (! $this->imageReady()) {
            throw new RuntimeException(trans('torns.ai_err_image_key'));
        }
        $prompt = $this->plain($prompt, 3000);
        $post = [
            'title' => $this->plain($post['title'] ?? '', 255),
            'description' => $this->plain($post['description'] ?? '', 1000),
            'content' => $this->blockText($post['content'] ?? '', 20000),
        ];
        if ($prompt === '' && implode('', $post) === '') {
            throw new RuntimeException(trans('torns.ai_err_image_prompt'));
        }
        $scene = implode('', $post) !== '' ? $this->imageScene($post, $prompt, $style, $userId) : '';
        $size = self::SHAPES[$shape] ?? self::SHAPES['landscape'];
        $full = ($scene !== '' ? $scene : trim($post['title'] . "\n" . $prompt))
            . ($prompt !== '' ? "\n\nThe editor's indications, which must be followed: {$prompt}" : '')
            . "\n\n" . (self::STYLES[$style] ?? self::STYLES['photo'])
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

    /** Plain text of an HTML body, one line per block so headings do not run into the next paragraph. */
    protected function blockText(?string $html, int $max): string
    {
        $text = (string) preg_replace('#</(h\d|p|li|blockquote|div)>|<br\s*/?>#i', "$0\n", (string) $html);

        $text = str_replace("\u{00A0}", ' ', $this->plain($text, $max));

        return trim((string) preg_replace('/\n\s*\n\s*\n/', "\n\n", $text));
    }

    /**
     * The scene for the featured image, written by the text model from the whole post
     * so the image fits what the article is about. Empty if no text model answers.
     */
    protected function imageScene(array $post, string $prompt, string $style, ?int $userId): string
    {
        $provider = $this->defaultTextProvider();
        if (! $provider) {
            return '';
        }
        $system = 'You write prompts for an image model that creates the featured image of articles on the blog of Torns, '
            . 'a shift and on-call management app for healthcare workers in Spain (nurses, doctors, technicians). '
            . 'From the article you receive, describe one concrete scene that conveys its main idea: who appears, what they are doing, where, the light and the mood. '
            . 'Healthcare professionals at work or in their personal life, respectful and positive; no blood, injuries, identifiable patients or brand names. '
            . 'No text, letters, numbers, logos or screens with legible content in the image. '
            . 'If the editor gives indications, follow them: they take precedence over the article. '
            . 'Write in English, in at most 90 words, as one paragraph. Reply only with the description.';
        $message = '';
        foreach (['title' => 'Title', 'description' => 'Summary'] as $key => $label) {
            if ($post[$key] !== '') {
                $message .= "{$label}: {$post[$key]}\n";
            }
        }
        if ($post['content'] !== '') {
            $message .= "\n<article>\n" . mb_substr($post['content'], 0, 12000) . "\n</article>\n";
        }
        $message .= "\nStyle of the image: " . (self::STYLES[$style] ?? self::STYLES['photo']);
        if ($prompt !== '') {
            $message .= "\nEditor's indications: {$prompt}";
        }

        try {
            [$raw, $usage] = $provider === 'claude'
                ? $this->claude($system, $message, 400)
                : $this->openaiText($system, $message, 400);
            Log::info('Torns blog AI: image scene', ['user' => $userId, 'provider' => $provider] + $usage);

            return $this->plain($raw, 1500);
        } catch (\Throwable $e) {
            Log::warning('Torns blog AI: image scene failed', ['user' => $userId, 'provider' => $provider, 'error' => $e->getMessage()]);

            return '';
        }
    }

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
