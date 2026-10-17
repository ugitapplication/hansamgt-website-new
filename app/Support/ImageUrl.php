<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

/**
 * Resolve a web-accessible URL for a managed image path.
 *
 * Uploads made in the admin panel live on the "public" disk
 * (storage/app/public) while the images that shipped with the site live under
 * public/ directly. One field value can be either, so the resolution is shared
 * by the models, the admin panel and the settings accessor.
 */
class ImageUrl
{
    public static function resolve(?string $path): string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return '';
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (Storage::disk('public')->exists($path)) {
            return url(Storage::disk('public')->url($path));
        }

        return asset(ltrim($path, '/'));
    }
}
