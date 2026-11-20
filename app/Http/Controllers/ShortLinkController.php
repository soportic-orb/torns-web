<?php

namespace App\Http\Controllers;

use App\Support\ShortLinks;
use Botble\Blog\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * The short link of a published post, made the first time a reader presses
 * a share button on it (docs/short-links.md).
 */
class ShortLinkController extends Controller
{
    public function store(Request $request, ShortLinks $links): JsonResponse
    {
        $data = $request->validate([
            'post' => ['required', 'integer'],
            'locale' => ['required', 'string', 'max:10'],
        ]);

        $post = Post::query()->with('slugable')->find($data['post']);
        $link = $post ? $links->forPost($post, $data['locale']) : null;
        if (! $link) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json(['url' => $links->url($link)]);
    }
}
