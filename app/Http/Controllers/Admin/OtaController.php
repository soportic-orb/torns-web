<?php

namespace App\Http\Controllers\Admin;

use App\Ota\OtaUpdater;
use App\Support\SiteContent;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * Admin → Platform → System → Site updates: shows the installed and the
 * available OTA version, and applies or rolls back updates on demand.
 * Super administrators only.
 */
class OtaController extends BaseController
{
    public function __construct(protected OtaUpdater $ota)
    {
    }

    public function index(Request $request): View
    {
        $this->authorizeSuperUser($request);
        $this->pageTitle('Actualizaciones de la web');

        return view('admin.ota', [
            'current' => $this->ota->currentVersion(),
            'available' => $this->ota->available(),
            'lastCheck' => $this->ota->lastCheck(),
            'history' => $this->ota->history(),
            'problems' => $this->ota->requirements(),
            'hasBackup' => $this->ota->hasBackup(),
            'repository' => config('torns-ota.repository'),
            'conflicts' => $this->ota->history()[0]['conflicts'] ?? [],
        ]);
    }

    public function check(Request $request): RedirectResponse
    {
        $this->authorizeSuperUser($request);

        return $this->run(fn () => $this->ota->check()
            ? 'Hay una versión nueva lista para aplicar.'
            : 'La web está al día.');
    }

    public function apply(Request $request): RedirectResponse
    {
        $this->authorizeSuperUser($request);

        return $this->run(function () use ($request) {
            $result = $this->ota->apply($request->user()->name ?? (string) $request->user()->getKey());
            $kept = count($result['report']['conflicts']);

            return "Versión {$result['version']} aplicada."
                . ($kept ? " {$kept} página(s) o menú(s) editados en /admin no se han tocado: revísalos abajo." : '');
        });
    }

    public function rollback(Request $request): RedirectResponse
    {
        $this->authorizeSuperUser($request);

        return $this->run(function () use ($request) {
            $version = $this->ota->rollback($request->user()->name ?? (string) $request->user()->getKey());

            return 'Restaurada la versión anterior' . ($version ? " ({$version})" : '') . '.';
        });
    }

    /** Overwrite the pages and menus kept because of /admin edits with the repository version. */
    public function useRepositoryContent(Request $request, SiteContent $content): RedirectResponse
    {
        $this->authorizeSuperUser($request);

        return $this->run(function () use ($content) {
            $report = $content->sync(force: true);

            return 'Contenido del repositorio aplicado: ' . (count($report['updated']) + count($report['created'])) . ' página(s) o menú(s) actualizados.';
        });
    }

    protected function run(callable $action): RedirectResponse
    {
        try {
            $message = $action();

            return redirect()->route('torns-ota.index')->with('ota_ok', $message);
        } catch (\Throwable $e) {
            Log::error('Torns OTA failed', ['error' => $e->getMessage()]);

            return redirect()->route('torns-ota.index')->with('ota_error', $e->getMessage());
        }
    }

    protected function authorizeSuperUser(Request $request): void
    {
        abort_unless($request->user()?->isSuperUser(), 403);
    }
}
