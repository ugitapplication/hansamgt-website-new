<?php

namespace App\Models;

use App\Models\Concerns\ResolvesImageUrl;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use ResolvesImageUrl;

    /**
     * The two states the public filter offers.
     *
     * @var array<string, string>
     */
    public const CATEGORIES = [
        'ongoing' => 'Ongoing',
        'upcoming' => 'Upcoming',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'location',
        'category',
        'image',
        'image_alt',
        'is_featured',
        'homepage_title',
        'homepage_location',
        'homepage_alt',
        'homepage_sort',
        'sort',
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
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort' => 'integer',
            'homepage_sort' => 'integer',
        ];
    }

    /**
     * Scope a query to projects shown on the public site.
     *
     * @param  Builder<Project>  $query
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /**
     * Scope a query to projects shown in the homepage "Service Projects" band.
     *
     * @param  Builder<Project>  $query
     */
    public function scopeFeatured(Builder $query): void
    {
        $query->where('is_featured', true);
    }

    /**
     * Scope a query to display order.
     *
     * @param  Builder<Project>  $query
     */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort')->orderBy('id');
    }

    /**
     * Scope a query to the homepage band order, which is not the same as the
     * Projects page order.
     *
     * @param  Builder<Project>  $query
     */
    public function scopeHomepageOrdered(Builder $query): void
    {
        $query->orderBy('homepage_sort')->orderBy('sort')->orderBy('id');
    }

    /**
     * The homepage card text, falling back to the main text. The homepage used
     * to carry its own, longer wording for the same four projects.
     */
    public function homepageTitle(): string
    {
        return (string) ($this->homepage_title ?: $this->title);
    }

    public function homepageLocation(): string
    {
        return (string) ($this->homepage_location ?: $this->location);
    }

    public function homepageAlt(): string
    {
        return (string) ($this->homepage_alt ?: $this->image_alt ?: $this->title);
    }

    /**
     * The human label for the category, for the admin table and the card badge.
     */
    protected function categoryLabel(): Attribute
    {
        return Attribute::get(fn (): string => self::CATEGORIES[$this->category] ?? (string) $this->category);
    }

    /**
     * Admin preview URL for the image (used by the Filament table).
     */
    protected function previewUrl(): Attribute
    {
        return Attribute::get(fn (): string => $this->imageUrl());
    }
}
