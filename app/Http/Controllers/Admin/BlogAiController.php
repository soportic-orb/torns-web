<?php

namespace App\Http\Controllers\Admin;

use App\Ai\BlogAi;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\View\View;

/**
 * AI writing for blog posts: the endpoints the post editor calls, and the
 * settings page (super administrators only).
 */
class BlogAiController extends BaseController
{
    public function __construct(protected BlogAi $ai)
    {
    }

    public function text(Request $request): JsonResponse
    {
        $this->authorizeEditor($request);
        $data = $request->validate([
            'provider' => ['required', 'string'],
            'prompt' => ['required', 'string', 'max:4000'],
            'format' => ['nullable', 'string'],
            'tone' => ['nullable', 'string'],
            'length' => ['nullable', 'string'],
            'language' => ['nullable', 'string'],
            'title' => ['nullable', 'string', 'max:255'],
            'current' => ['nullable', 'string', 'max:60000'],
        ]);

        try {
            $result = $this->ai->text($data['provider'], $data['prompt'], Arr::except($data, ['provider', 'prompt']), $request->user()->getKey());
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json($result);
    }

    public function image(Request $request): JsonResponse
    {
        $this->authorizeEditor($request);
        $data = $request->validate([
            'prompt' => ['required', 'string', 'max:3000'],
            'shape' => ['nullable', 'string'],
            'style' => ['nullable', 'string'],
        ]);

        try {
            $result = $this->ai->image($data['prompt'], (string) ($data['shape'] ?? 'landscape'), (string) ($data['style'] ?? 'photo'), $request->user()->getKey());
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json($result);
    }

    public function settings(Request $request): View
    {
        $this->authorizeSuperUser($request);
        $this->pageTitle('IA para el blog');

        return view('admin.ai.settings', [
            'ai' => $this->ai,
            'tests' => session('ai_tests', []),
        ]);
    }

    public function saveSettings(Request $request): RedirectResponse
    {
        $this->authorizeSuperUser($request);
        $data = $request->validate([
            'torns_ai_enabled' => ['nullable', 'in:0,1'],
            'torns_ai_default_text' => ['nullable', 'in:claude,openai'],
            'torns_ai_anthropic_key' => ['nullable', 'string', 'max:500'],
            'torns_ai_claude_model' => ['nullable', 'string', 'max:100'],
            'torns_ai_openai_key' => ['nullable', 'string', 'max:500'],
            'torns_ai_openai_text_model' => ['nullable', 'string', 'max:100'],
            'torns_ai_openai_image_model' => ['nullable', 'string', 'max:100'],
        ]);
        $data['torns_ai_enabled'] = $request->boolean('torns_ai_enabled') ? '1' : '0';
        $this->ai->save($data);

        return redirect()->route('torns-ai.settings')->with('ai_ok', 'Ajustes guardados.');
    }

    public function test(Request $request): RedirectResponse
    {
        $this->authorizeSuperUser($request);

        return redirect()->route('torns-ai.settings')->with('ai_tests', $this->ai->test($request->user()->getKey()) ?: [
            'none' => ['ok' => false, 'message' => 'No hay ningún servicio activo: activa la IA y añade al menos una clave.'],
        ]);
    }

    protected function authorizeEditor(Request $request): void
    {
        abort_unless($request->user()?->hasAnyPermission(['posts.create', 'posts.edit']), 403);
    }

    protected function authorizeSuperUser(Request $request): void
    {
        abort_unless($request->user()?->isSuperUser(), 403);
    }
}
