<?php

namespace App\Providers;

use App\Ai\BlogAi;
use App\Http\Middleware\NoIndexOutsideProduction;
use App\Listeners\RepairEnvAfterInstaller;
use Botble\Base\Facades\PanelSectionManager;
use Botble\Base\Forms\FieldOptions\HtmlFieldOption;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\FormAbstract;
use Botble\Base\PanelSections\PanelSectionItem;
use Botble\Base\PanelSections\System\SystemPanelSection;
use Botble\Blog\Forms\PostForm;
use Botble\Blog\Models\Post;
use Botble\Language\Facades\Language;
use Botble\Language\Models\LanguageMeta;
use Botble\Setting\PanelSections\SettingOthersPanelSection;
use Botble\Installer\Events\EnvironmentSaved;
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
                    ->setTitle('Actualizaciones de la web')
                    ->withIcon('ti ti-cloud-download')
                    ->withDescription('Aplica las nuevas versiones del tema y del contenido publicadas desde el repositorio.')
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
                    ->setTitle('IA para el blog')
                    ->withIcon('ti ti-sparkles')
                    ->withDescription('Claude y ChatGPT para redactar las publicaciones, y OpenAI para sus imágenes destacadas.')
                    ->withPriority(150)
                    ->withRoute('torns-ai.settings')
            );
        });

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

            return $form->addBefore('content', 'torns_ai_panel', HtmlField::class, HtmlFieldOption::make()->content(view('admin.ai.post-panel', [
                'providers' => $ai->textProviders(),
                'defaultProvider' => $ai->defaultTextProvider(),
                'imageReady' => $ai->imageReady(),
                'language' => array_key_exists($language, BlogAi::LANGUAGES) ? $language : 'es',
                'formats' => BlogAi::FORMATS,
                'tones' => BlogAi::TONES,
                'lengths' => BlogAi::LENGTHS,
                'languages' => BlogAi::LANGUAGES,
                'shapes' => BlogAi::SHAPES,
                'styles' => BlogAi::STYLES,
            ])->render())->toArray());
        }, 120, 2);
    }
}
