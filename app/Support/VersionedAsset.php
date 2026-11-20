<?php

namespace App\Support;

/**
 * Public files are served with a ten-year cache, and Botble versions the
 * ones added with Assets::add*Directly() by the CMS version, which our
 * updates do not change. A content hash in the URL makes browsers fetch a
 * file again when it changes (Botble adds no version when there is a "?").
 */
class VersionedAsset
{
    public static function path(string $path): string
    {
        $file = public_path($path);

        return is_file($file) ? $path . '?v=' . substr(md5_file($file), 0, 12) : $path;
    }
}
