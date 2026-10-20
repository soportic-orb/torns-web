<?php

namespace App\Http\Controllers\Admin;

use App\Ota\OtaUpdater;
use App\Support\SiteContent;
use Botble\Base\Facades\Assets;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
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
        $this->pageTitle(trans('torns.ota_title'));
        Assets::addStylesDirectly('vendor/torns/css/ota.css')
            ->addScriptsDirectly('vendor/torns/js/confirm-forms.js');

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

    public function check(Request $request): BaseHttpResponse
    {
        $this->authorizeSuperUser($request);

        return $this->run(fn () => trans($this->ota->check() ? 'torns.ota_msg_available' : 'torns.ota_msg_up_to_date'));
    }

    public function apply(Request $request): BaseHttpResponse
    {
        $this->authorizeSuperUser($request);

        return $this->run(function () use ($request) {
            $result = $this->ota->apply($this->userName($request));
            $kept = count($result['report']['conflicts']);

            return trans('torns.ota_msg_applied', ['version' => $result['version']])
                . ($kept ? ' ' . trans('torns.ota_msg_kept', ['count' => $kept]) : '');
        });
    }

    public function rollback(Request $request): BaseHttpResponse
    {
        $this->authorizeSuperUser($request);

        return $this->run(function () use ($request) {
            $version = $this->ota->rollback($this->userName($request));

            return trans('torns.ota_msg_rolled_back', ['version' => $version ?: trans('torns.ota_manual_install')]);
        });
    }

    /** Overwrite the pages and menus kept because of /admin edits with the repository version. */
    public function useRepositoryContent(Request $request, SiteContent $content): BaseHttpResponse
    {
        $this->authorizeSuperUser($request);

        return $this->run(function () use ($content) {
            $report = $content->sync(force: true);

            return trans('torns.ota_msg_repository_content', ['count' => count($report['updated']) + count($report['created'])]);
        });
    }

    protected function run(callable $action): BaseHttpResponse
    {
        $response = $this->httpResponse()->setNextRoute('torns-ota.index');

        try {
            return $response->setMessage($action());
        } catch (\Throwable $e) {
            Log::error('Torns OTA failed', ['error' => $e->getMessage()]);

            return $response->setError()->setMessage($e->getMessage());
        }
    }

    protected function userName(Request $request): string
    {
        return $request->user()->name ?? (string) $request->user()->getKey();
    }

    protected function authorizeSuperUser(Request $request): void
    {
        abort_unless($request->user()?->isSuperUser(), 403);
    }
}
