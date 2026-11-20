<?php

namespace App\Http\Controllers\Admin;

use App\Support\ShortLinks;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Blog\Models\Post;
use Illuminate\Http\Request;

/**
 * «Generar enllaç curt» in the post editor (docs/short-links.md).
 */
class ShortLinkController extends BaseController
{
    public function generate(Request $request, ShortLinks $links): BaseHttpResponse
    {
        abort_unless($request->user()?->hasAnyPermission(['posts.create', 'posts.edit']), 403);
        $data = $request->validate([
            'post_id' => ['required', 'integer'],
            'locale' => ['required', 'string', 'max:10'],
        ]);

        $post = Post::query()->with('slugable')->findOrFail($data['post_id']);
        $link = $links->forPost($post, $data['locale']);
        if (! $link) {
            return $this->httpResponse()->setError()->setCode(422)->setMessage(trans('torns.short_link_unpublished'));
        }

        return $this->httpResponse()->setData([
            'url' => $links->url($link),
            'label' => $links->label($link),
            'clicks' => $link->clicks,
        ]);
    }
}
