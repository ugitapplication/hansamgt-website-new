<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Storage;

/**
 * Resolve a web-accessible URL for a managed image field.
 *
 * Managed uploads live on the "public" disk (storage/app/public).
 * Seeded legacy images live under public/ directly. Both resolve here
 * so the homepage never breaks regardless of image origin.
 */
trait ResolvesImageUrl
{
    public function imageUrl(string $field = 'image'): string
    {
        $path = trim((string) ($this->{$field} ?? ''));

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
