<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

/**
 * Resolve a web-accessible URL for a managed image path.
 */
class ImageUrl
{
    /**
     *
     * @var array<string, string>
     */
    private static array $memo = [];

    public static function resolve(?string $path): string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return '';
        }

        if (isset(self::$memo[$path])) {
            return self::$memo[$path];
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return self::$memo[$path] = $path;
        }

        if (Storage::disk('public')->exists($path)) {
            return self::$memo[$path] = url(Storage::disk('public')->url($path));
        }

        return self::$memo[$path] = asset(ltrim($path, '/'));
    }

    /**
     * Forget every resolved URL.
     */
    public static function flush(): void
    {
        self::$memo = [];
    }
}
