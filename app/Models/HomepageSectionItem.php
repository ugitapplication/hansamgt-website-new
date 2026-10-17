<?php

namespace App\Models;

use App\Models\Concerns\ResolvesImageUrl;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class HomepageSectionItem extends Model
{
    use ResolvesImageUrl;

    /**
     * The sections these items belong to.
     *
     * @var array<string, string>
     */
    public const SECTIONS = [
        'commitments' => 'Our commitments (3 cards)',
        'numbers' => 'HANSA in Numbers (5 statistics)',
        'glance' => 'Services at-a-glance (8 cards)',
        'why' => 'Why to choose HANSA (bullet points)',
    ];

    /**
     * The attribute that tells each card which icon to draw.
     *
     * The three commitment icons were traced from the legacy icon font and stay
     * in the Blade partial; the item only stores which one to use.
     *
     * @var array<string, string>
     */
    public const ICONS = [
        'polygon' => 'Hexagons (operational standards)',
        'safety' => 'Safety helmet',
        'bulb' => 'Bulb with a lightning bolt',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'section',
        'title',
        'body',
        'value',
        'value_from_year',
        'image',
        'image_alt',
        'icon',
        'group',
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
            'is_active' => 'boolean',
            'sort' => 'integer',
            'value_from_year' => 'integer',
        ];
    }

    /**
     * Scope a query to items of one section, shown on the public site.
     *
     * @param  Builder<HomepageSectionItem>  $query
     */
    public function scopeSection(Builder $query, string $section): void
    {
        $query->where('section', $section);
    }

    /**
     * Scope a query to items shown on the public site.
     *
     * @param  Builder<HomepageSectionItem>  $query
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /**
     * Scope a query to display order.
     *
     * @param  Builder<HomepageSectionItem>  $query
     */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort')->orderBy('id');
    }

    /**
     * The big number shown on a "HANSA in Numbers" item.
     *
     * "Years in operation" was always derived from the founding year so it
     * cannot go stale: set `value_from_year` and the number counts up by
     * itself, or set a fixed `value` for everything else.
     */
    protected function displayValue(): Attribute
    {
        return Attribute::get(function (): string {
            if ($this->value_from_year) {
                return max(1, (int) now()->year - (int) $this->value_from_year).'+';
            }

            return (string) $this->value;
        });
    }

    /**
     * Admin preview URL for the card image (used by the Filament table).
     */
    protected function previewUrl(): Attribute
    {
        return Attribute::get(fn (): string => $this->imageUrl());
    }
}
