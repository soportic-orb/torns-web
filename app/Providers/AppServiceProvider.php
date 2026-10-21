<?php

namespace App\Providers;

use App\Ai\BlogAi;
use App\Ai\PostTranslator;
use App\Ai\TaxonomyTranslator;
use App\Ai\TranslateTaxonomyBulkAction;
use App\Http\Middleware\NoIndexOutsideProduction;
use App\Listeners\RepairEnvAfterInstaller;
use App\Support\IndexNow;
use App\Support\VersionedAsset;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Facades\Assets;
use Botble\Base\Facades\PanelSectionManager;
use Botble\Base\Forms\FieldOptions\HtmlFieldOption;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\FormAbstract;
use Botble\Base\PanelSections\PanelSectionItem;
use Botble\Base\PanelSections\System\SystemPanelSection;
use Botble\Blog\Forms\CategoryForm;
use Botble\Blog\Forms\PostForm;
use Botble\Blog\Forms\TagForm;
use Botble\Blog\Models\Category;
use Botble\Blog\Models\Post;
use Botble\Blog\Models\Tag;
use Botble\Blog\Tables\CategoryTable;
use Botble\Blog\Tables\TagTable;
use Botble\Installer\Events\EnvironmentSaved;
use Botble\Language\Facades\Language;
use Botble\Language\Models\LanguageMeta;
use Botble\Setting\PanelSections\SettingOthersPanelSection;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Before any provider boots, so Botble's catch-all page route does not
        // take robots.txt, llms-full.txt or the /x.md versions.
        $this->app->booting(function (): void {
            require base_path('routes/seo-early.php');
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Botble's web installer rewrites .env without APP_KEY and our own values.
        if (class_exists(EnvironmentSaved::class)) {
            Event::listen(EnvironmentSaved::class, RepairEnvAfterInstaller::class);
        }

        $this->app['router']->pushMiddlewareToGroup('web', NoIndexOutsideProduction::class);

        // IndexNow: tell search engines when a published post is saved or translated.
        Event::listen([CreatedContentEvent::class, UpdatedContentEvent::class], function (CreatedContentEvent|UpdatedContentEvent $event): void {
            if ($event->data instanceof Post) {
                $this->app->make(IndexNow::class)->submitPostLater($event->data);
            }
        });

        // Categories and tags: translated with AI after they are saved in the
        // default language, and those of a post when the post is saved, so a
        // tag typed in the post form gets its translations too. After the
        // response: the editor does not wait for it. Translations written by
        // hand are never replaced (TaxonomyTranslator::missing).
        Event::listen([CreatedContentEvent::class, UpdatedContentEvent::class], function (CreatedContentEvent|UpdatedContentEvent $event): void {
            $model = $event->data;
            if (! ($model instanceof Category || $model instanceof Tag || $model instanceof Post) || ! is_in_admin()) {
                return;
            }
            if (request('ref_lang') && class_exists(Language::class) && request('ref_lang') !== Language::getDefaultLocaleCode()) {
                return; // editing a translation by hand
            }
            if (! $this->app->make(BlogAi::class)->defaultTextProvider()) {
                return;
            }
            $this->app->terminating(function () use ($model): void {
                @set_time_limit(300);
                $translator = $this->app->make(TaxonomyTranslator::class);
                $model instanceof Post
                    ? $translator->translatePostTaxonomy($model->fresh() ?? $model)
                    : $translator->translateMissing($model->fresh() ?? $model);
            });
        });

        // Site updates (OTA) page: Admin → Platform → System.
        $this->loadRoutesFrom(base_path('routes/admin.php'));

        // After Botble's routes, so these replace its sitemap and llms.txt.
        $this->app->booted(function (): void {
            require base_path('routes/seo.php');
        });

        PanelSectionManager::group('system')->beforeRendering(function (): void {
            if (! auth()->user()?->isSuperUser()) {
                return;
            }

            PanelSectionManager::registerItem(
                SystemPanelSection::class,
                fn () => PanelSectionItem::make('torns-ota')
                    ->setTitle(trans('torns.ota_title'))
                    ->withIcon('ti ti-cloud-download')
                    ->withDescription(trans('torns.ota_panel_description'))
                    ->withPriority(5)
                    ->withRoute('torns-ota.index')
            );
        });

        // AI writing for blog posts: Settings → Others, and a panel on the post form.
        PanelSectionManager::default()->beforeRendering(function (): void {
            if (! auth()->user()?->isSuperUser()) {
                return;
            }

            PanelSectionManager::registerItem(
                SettingOthersPanelSection::class,
                fn () => PanelSectionItem::make('torns-ai')
                    ->setTitle(trans('torns.ai_settings_title'))
                    ->withIcon('ti ti-sparkles')
                    ->withDescription(trans('torns.ai_panel_description'))
                    ->withPriority(150)
                    ->withRoute('torns-ai.settings')
            );
        });

        // Tag and category lists: «Traducir con IA» for the selected rows.
        foreach ([TagTable::class => ['tag', 'tags.edit'], CategoryTable::class => ['category', 'categories.edit']] as $tableClass => [$type, $permission]) {
            $tableClass::extend(function ($table) use ($type, $permission): void {
                if (! $this->app->make(TaxonomyTranslator::class)->languages() || ! $this->app->make(BlogAi::class)->textProviders()) {
                    return;
                }
                Assets::addScriptsDirectly(VersionedAsset::path('vendor/torns/js/taxonomy-bulk-translate.js'));
                $table->addBulkAction(
                    TranslateTaxonomyBulkAction::make()->type($type)->label(trans('torns.ai_taxonomy_run'))->permission($permission)->confirmation(false)
                );
            });
        }

        // Category and tag forms: their translations and «Traducir con IA».
        add_filter(BASE_FILTER_BEFORE_RENDER_FORM, function (FormAbstract $form, $model) {
            if (! ($form instanceof CategoryForm || $form instanceof TagForm) || ! ($model instanceof Category || $model instanceof Tag) || ! $model->getKey()) {
                return $form;
            }
            if (request('ref_lang') && class_exists(Language::class) && request('ref_lang') !== Language::getDefaultLocaleCode()) {
                return $form;
            }
            $translator = $this->app->make(TaxonomyTranslator::class);
            if (! $translator->languages() || ! $this->app->make(BlogAi::class)->textProviders()) {
                return $form;
            }

            return $form->addAfter('description', 'torns_ai_taxonomy', HtmlField::class, HtmlFieldOption::make()->content(view('admin.ai.taxonomy-panel', [
                'type' => $model instanceof Category ? 'category' : 'tag',
                'id' => $model->getKey(),
                'languages' => $translator->languages(),
                'missing' => $translator->missing($model),
            ])->render())->toArray());
        }, 121, 2);

        add_filter(BASE_FILTER_BEFORE_RENDER_FORM, function (FormAbstract $form, $model) {
            if (! $form instanceof PostForm || ! auth()->user()?->hasAnyPermission(['posts.create', 'posts.edit'])) {
                return $form;
            }

            $ai = $this->app->make(BlogAi::class);
            if (! $ai->textProviders() && ! $ai->imageReady()) {
                return $form;
            }

            $language = request('ref_lang')
                ?: ($model instanceof Post && $model->getKey() && class_exists(LanguageMeta::class)
                    ? LanguageMeta::query()->where('reference_id', $model->getKey())->where('reference_type', Post::class)->value('lang_meta_code')
                    : null)
                ?: (class_exists(Language::class) ? Language::getCurrentAdminLocaleCode() : null)
                ?: 'es';

            // Translation: from the saved post in the default language, when there is a text service.
            $translate = null;
            $translator = $this->app->make(PostTranslator::class);
            if ($ai->textProviders() && $model instanceof Post && $model->getKey() && $translator->languages()
                && (! request('ref_lang') || request('ref_lang') === Language::getDefaultLocaleCode())) {
                $translate = ['post_id' => $model->getKey(), 'languages' => $translator->languages(), 'translated' => $translator->translated($model)];
            }

            Assets::addStylesDirectly(VersionedAsset::path('vendor/torns/css/blog-ai.css'))
                ->addScriptsDirectly(VersionedAsset::path('vendor/torns/js/blog-ai.js'));

            return $form->addBefore('content', 'torns_ai_panel', HtmlField::class, HtmlFieldOption::make()->content(view('admin.ai.post-panel', [
                'providers' => $ai->textProviders(),
                'defaultProvider' => $ai->defaultTextProvider(),
                'imageReady' => $ai->imageReady(),
                'language' => array_key_exists($language, BlogAi::LANGUAGES) ? $language : 'es',
                'formats' => BlogAi::FORMATS,
                'tones' => array_keys(BlogAi::TONES),
                'lengths' => array_keys(BlogAi::LENGTHS),
                'languages' => array_keys(BlogAi::LANGUAGES),
                'shapes' => array_keys(BlogAi::SHAPES),
                'styles' => array_keys(BlogAi::STYLES),
                'translate' => $translate,
            ])->render())->toArray());
        }, 120, 2);
    }
}
