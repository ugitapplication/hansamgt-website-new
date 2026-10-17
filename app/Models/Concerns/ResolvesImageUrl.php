<?php

namespace App\Models\Concerns;

use App\Support\ImageUrl;

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
        return ImageUrl::resolve($this->{$field} ?? null);
    }
}
