<?php

namespace App\Http\Middleware;

use App\Support\ShortLinks;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * torns.app/wy89 → the post it was made for (docs/short-links.md). Only a
 * four-character path that is a known code: anything else, a page with a
 * four-letter slug included, goes on to Botble as usual.
 */
class RedirectShortLinks
{
    /** Link previews of chats and networks: they do not count as clicks. */
    protected const BOTS = '/bot|crawl|spider|slurp|preview|facebookexternalhit|whatsapp|telegram|slack|discord|skype|embedly|vkshare|pinterest|linkedin|twitter|google-?inspection/i';

    public function __construct(protected ShortLinks $links)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->path();
        if (! $request->isMethod('GET') && ! $request->isMethod('HEAD')) {
            return $next($request);
        }
        if (! preg_match(ShortLinks::pattern(), $path) || ! ($link = $this->links->resolve($path))) {
            return $next($request);
        }

        if ($request->isMethod('GET') && ! preg_match(self::BOTS, (string) $request->userAgent())) {
            $this->links->countClick($link);
        }

        // 302: the post's URL may change, and every visit is counted.
        return redirect()->away($this->links->target($link), 302)
            ->header('X-Robots-Tag', 'noindex')
            ->header('Cache-Control', 'no-store');
    }
}
