<?php

namespace App\Models;

use App\Models\Concerns\ResolvesImageUrl;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    use ResolvesImageUrl;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'label',
        'image',
        'alt',
        'sort_order',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope a query to only active slides.
     *
     * @param  Builder<HeroSlide>  $query
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /**
     * Scope a query to front-end display order.
     *
     * @param  Builder<HeroSlide>  $query
     */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Admin preview URL for the slide image (used by the Filament table).
     * Returned as an absolute URL so ImageColumn renders it directly.
     */
    protected function previewUrl(): Attribute
    {
        return Attribute::get(fn (): string => $this->imageUrl());
    }
}
