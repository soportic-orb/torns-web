<?php

namespace Database\Seeders;

use Botble\ACL\Models\User;
use Botble\Base\Facades\MetaBox;
use Botble\Blog\Models\Category;
use Botble\Contact\Models\CustomField;
use Botble\Language\Models\Language;
use Botble\Language\Models\LanguageMeta;
use Botble\Menu\Models\Menu;
use Botble\Menu\Models\MenuLocation;
use Botble\Menu\Models\MenuNode;
use Botble\Page\Models\Page;
use Botble\Slug\Models\Slug;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

/**
 * Initial content for the Torns website in the four site languages.
 *
 * Idempotent: every record is keyed by a stable identifier (slug + language,
 * or a lang_meta_origin hash), so the seeder can be re-run without duplicating
 * content. Existing rows are updated in place.
 *
 * Run with: php artisan db:seed --class=TornsContentSeeder
 */
class TornsContentSeeder extends Seeder
{
    protected const LOCALES = [
        'es' => ['name' => 'Español', 'flag' => 'es', 'default' => true],
        'ca' => ['name' => 'Català', 'flag' => 'es', 'default' => false],
        'gl' => ['name' => 'Galego', 'flag' => 'es', 'default' => false],
        'eu' => ['name' => 'Euskara', 'flag' => 'es', 'default' => false],
    ];

    public function run(): void
    {
        $this->seedLanguages();
        $this->seedSettings();
        $this->seedPages();
        $this->seedMenus();
        $this->seedBlogCategories();
        $this->seedContactCustomField();

        // Theme translations may be cached per locale during the run.
        cache()->flush();

        $this->command?->info('Torns content seeded for the four site languages.');
    }

    /**
     * Translate a theme string into a specific locale, falling back to the
     * Spanish source text (keys are the Spanish literals).
     */
    protected function t(string $key, string $locale): string
    {
        if ($locale === 'es') {
            return $key;
        }

        $line = Lang::get($key, [], $locale);

        return is_string($line) ? $line : $key;
    }

    protected function seedLanguages(): void
    {
        $order = 0;
        foreach (self::LOCALES as $code => $meta) {
            Language::query()->updateOrCreate(['lang_locale' => $code], [
                'lang_name' => $meta['name'],
                'lang_code' => $code,
                'lang_flag' => $meta['flag'],
                'lang_is_default' => $meta['default'],
                'lang_order' => $order++,
                'lang_is_rtl' => false,
            ]);
        }

        setting()->forceSet('language_hide_default', '1')->save();
    }

    protected function seedSettings(): void
    {
        $prefix = fn (string $model) => 'permalink-' . Str::slug(str_replace('\\', '_', $model));

        setting()
            ->forceSet('admin_title', 'Torns')
            ->forceSet('theme-torns-site_title', 'Torns')
            ->forceSet('theme-torns-seo_title', 'App de cambios de turno y calendario sanitario | Torns')
            ->forceSet('theme-torns-seo_description', 'Cambia turnos con tus compañeros del mismo centro y gestiona el calendario de todos tus centros con sincronización Apple y Google Calendar. Gratis, sin tarjeta.')
            ->forceSet($prefix(\Botble\Blog\Models\Post::class), 'blog')
            ->forceSet($prefix(Category::class), 'blog')
            ->forceSet($prefix(\Botble\Blog\Models\Tag::class), 'blog/tag')
            ->save();
    }

    /**
     * Pages in the four languages, linked as translations of each other via a
     * shared lang_meta_origin. Slugs are identical across languages (the
     * language prefix in the URL disambiguates), preserving current-site URLs.
     */
    protected function seedPages(): void
    {
        $userId = User::query()->value('id') ?? 1;

        $pages = [
            'home' => [
                'slug' => '',
                'template' => 'full-width',
                'names' => ['es' => 'Inicio', 'ca' => 'Inici', 'gl' => 'Inicio', 'eu' => 'Hasiera'],
                'content' => fn (string $locale) => $this->homeContent($locale),
                'seo_title' => [
                    'es' => 'App de cambios de turno y calendario sanitario | Torns',
                    'ca' => "App de canvis de torn i calendari sanitari | Torns",
                    'gl' => 'App de cambios de quenda e calendario sanitario | Torns',
                    'eu' => 'Txanda-aldaketen eta osasun-egutegiaren appa | Torns',
                ],
                'seo_description' => [
                    'es' => 'Cambia turnos con tus compañeros del mismo centro y gestiona el calendario de todos tus centros con sincronización Apple y Google Calendar. Gratis, sin tarjeta.',
                    'ca' => "Canvia torns amb els teus companys del mateix centre i gestiona el calendari de tots els teus centres amb sincronització Apple i Google Calendar. Gratis, sense targeta.",
                    'gl' => 'Cambia quendas cos teus compañeiros do mesmo centro e xestiona o calendario de todos os teus centros con sincronización Apple e Google Calendar. Gratis, sen tarxeta.',
                    'eu' => 'Aldatu txandak zentro bereko lankideekin eta kudeatu zure zentro guztietako egutegia Apple eta Google Calendar sinkronizazioarekin. Doan, txartelik gabe.',
                ],
            ],
            'centros' => [
                'slug' => 'centros',
                'template' => 'full-width',
                'names' => ['es' => 'Torns para centros', 'ca' => 'Torns per a centres', 'gl' => 'Torns para centros', 'eu' => 'Torns zentroentzat'],
                'content' => fn (string $locale) => $this->centersContent($locale),
                'seo_title' => [
                    'es' => 'Torns para centros sanitarios y hospitales',
                    'ca' => 'Torns per a centres sanitaris i hospitals',
                    'gl' => 'Torns para centros sanitarios e hospitais',
                    'eu' => 'Torns osasun-zentro eta ospitaleentzat',
                ],
                'seo_description' => [
                    'es' => 'Ofrece a tus profesionales una app para gestionar sus cambios de turno y mejora el clima laboral, con panel de gestión para tu centro. Prueba de 30 días.',
                    'ca' => 'Ofereix als teus professionals una app per gestionar els seus canvis de torn i millora el clima laboral, amb panell de gestió per al teu centre. Prova de 30 dies.',
                    'gl' => 'Ofrece aos teus profesionais unha app para xestionar os seus cambios de quenda e mellora o clima laboral, con panel de xestión para o teu centro. Proba de 30 días.',
                    'eu' => 'Eskaini zure profesionalei txanda-aldaketak kudeatzeko app bat eta hobetu lan-giroa, zure zentrorako kudeaketa-panelarekin. 30 eguneko proba.',
                ],
            ],
            'contacta' => [
                'slug' => 'contacta',
                'template' => 'full-width',
                'names' => ['es' => 'Contacta', 'ca' => 'Contacta', 'gl' => 'Contacta', 'eu' => 'Kontaktua'],
                'content' => fn () => '[torns-contact][/torns-contact]',
                'seo_title' => [
                    'es' => 'Contacta con el equipo de Torns',
                    'ca' => "Contacta amb l'equip de Torns",
                    'gl' => 'Contacta co equipo de Torns',
                    'eu' => 'Jarri harremanetan Torns taldearekin',
                ],
                'seo_description' => [
                    'es' => 'Escríbenos y te ayudamos: dudas sobre la app de cambios de turno, licencias para centros sanitarios o soporte. Respondemos en hola@torns.app.',
                    'ca' => "Escriu-nos i t'ajudem: dubtes sobre l'app de canvis de torn, llicències per a centres sanitaris o suport. Responem a hola@torns.app.",
                    'gl' => 'Escríbenos e axudámosche: dúbidas sobre a app de cambios de quenda, licenzas para centros sanitarios ou soporte. Respondemos en hola@torns.app.',
                    'eu' => 'Idatzi eta lagunduko dizugu: txanda-aldaketen appari, osasun-zentroetarako lizentziei edo laguntzari buruzko zalantzak. hola@torns.app helbidean erantzuten dugu.',
                ],
            ],
            'terminos' => [
                'slug' => 'terminos',
                'template' => 'default',
                'names' => [
                    'es' => 'Términos y condiciones de uso',
                    'ca' => "Termes i condicions d'ús",
                    'gl' => 'Termos e condicións de uso',
                    'eu' => 'Erabilera-baldintzak',
                ],
                'content' => fn (string $locale) => $this->legalContent('terms', $locale),
                'seo_title' => [
                    'es' => 'Términos y condiciones de uso | Torns',
                    'ca' => "Termes i condicions d'ús | Torns",
                    'gl' => 'Termos e condicións de uso | Torns',
                    'eu' => 'Erabilera-baldintzak | Torns',
                ],
                'seo_description' => [
                    'es' => 'Términos y condiciones de uso de la aplicación y los servicios de Torns.',
                    'ca' => "Termes i condicions d'ús de l'aplicació i els serveis de Torns.",
                    'gl' => 'Termos e condicións de uso da aplicación e os servizos de Torns.',
                    'eu' => 'Torns aplikazioaren eta zerbitzuen erabilera-baldintzak.',
                ],
            ],
            'privacidad' => [
                'slug' => 'privacidad',
                'template' => 'default',
                'names' => [
                    'es' => 'Política de Privacidad',
                    'ca' => 'Política de Privacitat',
                    'gl' => 'Política de Privacidade',
                    'eu' => 'Pribatutasun Politika',
                ],
                'content' => fn (string $locale) => $this->legalContent('privacy', $locale),
                'seo_title' => [
                    'es' => 'Política de privacidad | Torns',
                    'ca' => 'Política de privacitat | Torns',
                    'gl' => 'Política de privacidade | Torns',
                    'eu' => 'Pribatutasun-politika | Torns',
                ],
                'seo_description' => [
                    'es' => 'Política de privacidad de Torns: qué datos tratamos, con qué finalidad y cómo ejercer tus derechos.',
                    'ca' => 'Política de privacitat de Torns: quines dades tractem, amb quina finalitat i com exercir els teus drets.',
                    'gl' => 'Política de privacidade de Torns: que datos tratamos, con que finalidade e como exercer os teus dereitos.',
                    'eu' => 'Torns-en pribatutasun-politika: zein datu tratatzen ditugun, zein helbururekin eta nola erabili zure eskubideak.',
                ],
            ],
            'blog' => [
                'slug' => 'blog',
                'template' => 'full-width',
                'names' => ['es' => 'Blog', 'ca' => 'Blog', 'gl' => 'Blog', 'eu' => 'Bloga'],
                'content' => fn () => '',
                'seo_title' => [
                    'es' => 'Blog de Torns — turnos y sanidad',
                    'ca' => 'Blog de Torns — torns i sanitat',
                    'gl' => 'Blog de Torns — quendas e sanidade',
                    'eu' => 'Torns bloga — txandak eta osasuna',
                ],
                'seo_description' => [
                    'es' => 'Novedades de Torns y consejos para profesionales sanitarios que trabajan a turnos: cambios de turno, calendario laboral y conciliación.',
                    'ca' => 'Novetats de Torns i consells per a professionals sanitaris que treballen a torns: canvis de torn, calendari laboral i conciliació.',
                    'gl' => 'Novidades de Torns e consellos para profesionais sanitarios que traballan a quendas: cambios de quenda, calendario laboral e conciliación.',
                    'eu' => 'Torns-en berriak eta txandaka lan egiten duten osasun-profesionalentzako aholkuak: txanda-aldaketak, lan-egutegia eta kontziliazioa.',
                ],
            ],
        ];

        foreach ($pages as $key => $definition) {
            $origin = md5('torns-page-' . $key);

            foreach (array_keys(self::LOCALES) as $locale) {
                $existingMeta = LanguageMeta::query()
                    ->where('lang_meta_origin', $origin)
                    ->where('lang_meta_code', $locale)
                    ->where('reference_type', Page::class)
                    ->first();

                $attributes = [
                    'name' => $definition['names'][$locale],
                    'content' => ($definition['content'])($locale),
                    'template' => $definition['template'],
                    'status' => 'published',
                    'user_id' => $userId,
                ];

                $page = $existingMeta
                    ? tap(Page::query()->find($existingMeta->reference_id) ?? new Page(), fn (Page $model) => $model->forceFill($attributes)->save())
                    : Page::query()->create($attributes);

                LanguageMeta::query()->updateOrCreate(
                    ['reference_id' => $page->getKey(), 'reference_type' => Page::class],
                    ['lang_meta_code' => $locale, 'lang_meta_origin' => $origin]
                );

                if ($definition['slug'] !== '') {
                    Slug::query()->updateOrCreate(
                        ['reference_id' => $page->getKey(), 'reference_type' => Page::class],
                        ['key' => $definition['slug'], 'prefix' => '']
                    );
                }

                MetaBox::saveMetaBoxData($page, 'seo_meta', [
                    'seo_title' => $definition['seo_title'][$locale],
                    'seo_description' => $definition['seo_description'][$locale],
                    'index' => 'index',
                ]);

                // Theme options are language-aware: the default locale uses the
                // bare key, other locales get the locale segment in the key.
                $optionInfix = $locale === 'es' ? '' : "{$locale}-";

                if ($key === 'home') {
                    setting()->forceSet("theme-torns-{$optionInfix}homepage_id", (string) $page->getKey());
                }

                if ($key === 'blog') {
                    setting()->forceSet("theme-torns-{$optionInfix}blog_page_id", (string) $page->getKey());
                    if ($locale === 'es') {
                        setting()->forceSet('blog_page_id', (string) $page->getKey());
                    }
                }
            }
        }

        setting()->save();
    }

    protected function homeContent(string $locale): string
    {
        $t = fn (string $key) => str_replace('"', '”', $this->t($key, $locale));

        $rows = [
            ['title' => 'Comparte los turnos por mensajería', 'image' => 'row-share-messaging', 'side' => 'left', 'p1' => 'Comparte el turno que has publicado en Torns a través de WhatsApp o tu app de mensajería favorita. Tus contactos o el grupo del trabajo recibirán una imagen con toda la información del turno que necesitas cambiar.', 'p2' => 'Así tus compañeros, aunque aún no estén registrados en la app, también sabrán que necesitas cambiar un turno y todos sus detalles.'],
            ['title' => 'Turnos SOS: cambios de turno urgentes', 'image' => 'row-sos', 'side' => 'right', 'p1' => '¿Necesitas cambiar un turno con urgencia? Activa el modo SOS y todos los usuarios de tu centro recibirán una notificación solicitando su colaboración para ayudarte con el cambio.', 'p2' => 'Una de esas funcionalidades que te van a encantar.'],
            ['title' => 'En tu propio idioma', 'image' => 'row-languages', 'side' => 'left', 'p1' => 'Creemos en la diversidad y la inclusión. Torns está disponible en distintos idiomas: castellano, català, galego y euskara.', 'p2' => 'Configura la app en tu idioma y todas las funcionalidades, mensajes, notificaciones y correos se ajustarán a tus preferencias.'],
            ['title' => 'Copilot: asistente IA para cambios de turno', 'image' => 'row-copilot', 'side' => 'right', 'p1' => 'Copilot es el asistente de inteligencia artificial que te ayudará a encontrar cambio de turno más rápido. Utiliza la disponibilidad de tu calendario, los turnos que necesitas cambiar y los de tus compañeros para hacer match.', 'p2' => 'Te propondrá los cambios que mejor se adapten a tu agenda y a los lugares donde prefieres trabajar. Copilot aprende de tus rutinas.'],
        ];

        $rowShortcodes = '';
        foreach ($rows as $row) {
            $rowShortcodes .= sprintf(
                '[torns-feature-row title="%s" image_asset="%s" side="%s" content_1="%s" content_2="%s"][/torns-feature-row]' . "\n",
                $t($row['title']),
                $row['image'],
                $row['side'],
                $t($row['p1']),
                $t($row['p2'])
            );
        }

        return <<<HTML
[torns-hero][/torns-hero]
[torns-video][/torns-video]
[torns-about][/torns-about]
[torns-unified-calendar][/torns-unified-calendar]
[torns-features][/torns-features]
{$rowShortcodes}[torns-gallery][/torns-gallery]
[torns-comparison][/torns-comparison]
[torns-center-code][/torns-center-code]
[torns-pricing][/torns-pricing]
[torns-register-cta][/torns-register-cta]
[torns-centers-teaser][/torns-centers-teaser]
[torns-faq][/torns-faq]
[torns-center-logos][/torns-center-logos]
[torns-blog-posts][/torns-blog-posts]
HTML;
    }

    protected function centersContent(string $locale): string
    {
        $title = str_replace('"', '”', $this->t('La app de cambios de turno y calendario laboral que tus profesionales adorarán', $locale));
        $subtitle = str_replace('"', '”', $this->t('Mejora la conciliación laboral y personal de tus profesionales con una aplicación que transforma la gestión de su calendario laboral y sus cambios de turno.', $locale));

        return <<<HTML
[torns-page-hero title="{$title}" subtitle="{$subtitle}"][/torns-page-hero]
[torns-centers-landing][/torns-centers-landing]
HTML;
    }

    protected function legalContent(string $document, string $locale): string
    {
        $path = database_path("seeders/data/{$document}-{$locale}.html");

        if (! is_file($path)) {
            $path = database_path("seeders/data/{$document}-es.html");
        }

        return is_file($path) ? (string) file_get_contents($path) : '';
    }

    protected function seedMenus(): void
    {
        $menus = [
            'main-menu' => [
                'location' => 'main-menu',
                'name' => 'Main menu',
                'items' => [
                    ['label' => 'Inicio', 'url' => '/'],
                    ['label' => 'Torns para centros', 'url' => '/centros'],
                    ['label' => 'Blog', 'url' => '/blog'],
                    ['label' => 'Contacta', 'url' => '/contacta'],
                ],
            ],
            'footer-menu' => [
                'location' => 'footer-menu',
                'name' => 'Footer menu',
                'items' => [
                    ['label' => 'Inicio', 'url' => '/'],
                    ['label' => 'Torns para centros', 'url' => '/centros'],
                    ['label' => 'Blog', 'url' => '/blog'],
                    ['label' => 'Contacta', 'url' => '/contacta'],
                ],
            ],
            'footer-legal' => [
                'location' => 'footer-legal-menu',
                'name' => 'Footer legal',
                'items' => [
                    ['label' => 'Términos y condiciones', 'url' => '/terminos'],
                    ['label' => 'Política de privacidad', 'url' => '/privacidad'],
                ],
            ],
        ];

        foreach ($menus as $slug => $definition) {
            $origin = md5('torns-menu-' . $slug);

            foreach (array_keys(self::LOCALES) as $locale) {
                $localizedSlug = $locale === 'es' ? $slug : "{$slug}-{$locale}";

                $menu = Menu::query()->updateOrCreate(
                    ['slug' => $localizedSlug],
                    ['name' => $definition['name'] . ($locale === 'es' ? '' : ' (' . strtoupper($locale) . ')'), 'status' => 'published']
                );

                MenuLocation::query()->updateOrCreate(['menu_id' => $menu->getKey(), 'location' => $definition['location']]);

                LanguageMeta::query()->updateOrCreate(
                    ['reference_id' => $menu->getKey(), 'reference_type' => Menu::class],
                    ['lang_meta_code' => $locale, 'lang_meta_origin' => $origin]
                );

                MenuNode::query()->where('menu_id', $menu->getKey())->delete();
                $prefix = $locale === 'es' ? '' : '/' . $locale;
                foreach ($definition['items'] as $position => $item) {
                    $url = $item['url'] === '/' ? ($prefix ?: '/') : $prefix . $item['url'];
                    MenuNode::query()->create([
                        'menu_id' => $menu->getKey(),
                        'parent_id' => 0,
                        'title' => $this->t($item['label'], $locale),
                        'url' => $url,
                        'position' => $position,
                        'target' => '_self',
                    ]);
                }
            }
        }
    }

    /**
     * Base blog categories in the four languages. No sample posts are created:
     * content will be written from the admin panel.
     */
    protected function seedBlogCategories(): void
    {
        $userId = User::query()->value('id') ?? 1;

        $categories = [
            'novedades' => 'Novedades',
            'funcionalidades' => 'Funcionalidades',
            'sanidad' => 'Sanidad',
        ];

        foreach ($categories as $slug => $label) {
            $origin = md5('torns-category-' . $slug);

            foreach (array_keys(self::LOCALES) as $locale) {
                $existingMeta = LanguageMeta::query()
                    ->where('lang_meta_origin', $origin)
                    ->where('lang_meta_code', $locale)
                    ->where('reference_type', Category::class)
                    ->first();

                $attributes = [
                    'name' => $this->t($label, $locale),
                    'status' => 'published',
                    'author_id' => $userId,
                    'author_type' => User::class,
                ];

                $category = $existingMeta
                    ? tap(Category::query()->find($existingMeta->reference_id) ?? new Category(), fn (Category $model) => $model->forceFill($attributes)->save())
                    : Category::query()->create($attributes);

                LanguageMeta::query()->updateOrCreate(
                    ['reference_id' => $category->getKey(), 'reference_type' => Category::class],
                    ['lang_meta_code' => $locale, 'lang_meta_origin' => $origin]
                );

                Slug::query()->updateOrCreate(
                    ['reference_id' => $category->getKey(), 'reference_type' => Category::class],
                    ['key' => $slug, 'prefix' => 'blog']
                );
            }
        }
    }

    protected function seedContactCustomField(): void
    {
        if (! class_exists(CustomField::class)) {
            return;
        }

        CustomField::query()->updateOrCreate(
            ['name' => 'Centro de trabajo'],
            ['type' => 'text', 'required' => false, 'placeholder' => 'Nombre de tu centro (opcional)', 'order' => 0, 'status' => 'published']
        );
    }
}
