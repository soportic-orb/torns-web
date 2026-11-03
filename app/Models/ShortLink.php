<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Short link of a blog post in one language: torns.app/<code> (docs/short-links.md).
 *
 * @property string $code
 * @property int $post_id
 * @property string $locale
 * @property int $clicks
 */
class ShortLink extends Model
{
    protected $table = 'torns_short_links';

    protected $fillable = ['code', 'post_id', 'locale'];

    protected $attributes = ['clicks' => 0];

    protected $casts = [
        'post_id' => 'int',
        'clicks' => 'int',
        'last_clicked_at' => 'datetime',
    ];
}
