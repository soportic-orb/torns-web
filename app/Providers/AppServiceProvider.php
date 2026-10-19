<?php

namespace App\Providers;

use Botble\Base\Facades\PanelSectionManager;
use Botble\Base\PanelSections\PanelSectionItem;
use Botble\Base\PanelSections\System\SystemPanelSection;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Site updates (OTA) page: Admin → Platform → System.
        $this->loadRoutesFrom(base_path('routes/admin.php'));

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
    }
}
