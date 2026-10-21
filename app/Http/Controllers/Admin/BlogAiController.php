<?php

namespace App\Http\Controllers\Admin;

use App\Ai\BlogAi;
use App\Ai\PostTranslator;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Blog\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use RuntimeException;

/**
 * AI writing for blog posts: the endpoints the post editor calls, and the
 * settings page (super administrators only).
 */
class BlogAiController extends BaseController
{
    public function __construct(protected BlogAi $ai)
    {
    }

    public function text(Request $request): BaseHttpResponse
    {
        $this->authorizeEditor($request);
        $data = $request->validate([
            'provider' => ['required', 'string'],
            'prompt' => ['nullable', 'string', 'max:4000'],
            'format' => ['nullable', 'string'],
            'tone' => ['nullable', 'string'],
            'length' => ['nullable', 'string'],
            'language' => ['nullable', 'string'],
            'title' => ['nullable', 'string', 'max:255'],
            'current' => ['nullable', 'string', 'max:60000'],
        ]);

        return $this->respond(fn () => $this->ai->text($data['provider'], (string) ($data['prompt'] ?? ''), Arr::except($data, ['provider', 'prompt']), $request->user()->getKey()));
    }

    public function image(Request $request): BaseHttpResponse
    {
        $this->authorizeEditor($request);
        $data = $request->validate([
            'prompt' => ['required', 'string', 'max:3000'],
            'shape' => ['nullable', 'string'],
            'style' => ['nullable', 'string'],
        ]);

        return $this->respond(fn () => $this->ai->image($data['prompt'], (string) ($data['shape'] ?? 'landscape'), (string) ($data['style'] ?? 'photo'), $request->user()->getKey()));
    }

    public function seo(Request $request): BaseHttpResponse
    {
        $this->authorizeEditor($request);
        $data = $request->validate([
            'provider' => ['required', 'string'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'content' => ['nullable', 'string', 'max:200000'],
            'language' => ['nullable', 'string'],
        ]);

        return $this->respond(fn () => $this->ai->seo($data['provider'], Arr::except($data, ['provider']), $request->user()->getKey()));
    }

    public function translate(Request $request, PostTranslator $translator): BaseHttpResponse
    {
        abort_unless($request->user()?->hasPermission('posts.edit'), 403);
        $data = $request->validate([
            'post_id' => ['required', 'integer'],
            'language' => ['required', 'string'],
            'provider' => ['required', 'string'],
            'overwrite' => ['nullable', 'boolean'],
        ]);
        $post = Post::query()->findOrFail($data['post_id']);
        // A long post can take a few minutes to translate.
        @set_time_limit(600);

        return $this->respond(fn () => $translator->translate($post, $data['language'], $data['provider'], (bool) ($data['overwrite'] ?? false), $request->user()->getKey()));
    }

    public function settings(Request $request): View
    {
        $this->authorizeSuperUser($request);
        $this->pageTitle(trans('torns.ai_settings_title'));

        return view('admin.ai.settings', [
            'ai' => $this->ai,
            'tests' => session('ai_tests', []),
        ]);
    }

    public function saveSettings(Request $request): BaseHttpResponse
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

        return $this->httpResponse()->setNextRoute('torns-ai.settings')->withUpdatedSuccessMessage();
    }

    public function test(Request $request): BaseHttpResponse
    {
        $this->authorizeSuperUser($request);

        return $this->httpResponse()
            ->setNextRoute('torns-ai.settings')
            ->with(['ai_tests' => $this->ai->test($request->user()->getKey()) ?: [
                'none' => ['ok' => false, 'message' => trans('torns.ai_test_none')],
            ]]);
    }

    protected function respond(callable $action): BaseHttpResponse
    {
        try {
            return $this->httpResponse()->setData($action());
        } catch (RuntimeException $e) {
            return $this->httpResponse()->setError()->setCode(422)->setMessage($e->getMessage());
        }
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
