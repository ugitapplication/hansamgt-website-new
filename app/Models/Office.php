<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'address',
        'phone',
        'landline',
        'email',
        'latitude',
        'longitude',
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
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    /**
     * Whether this office has a usable, in-range coordinate pair.
     *
     * A half-filled pair (latitude but no longitude, or vice versa) is treated
     * as "no location" rather than guessed, and coordinates outside the valid
     * ranges are rejected so a typo can never place a marker in the sea.
     */
    public function hasCoordinates(): bool
    {
        if ($this->latitude === null || $this->longitude === null) {
            return false;
        }

        return $this->latitude >= -90.0 && $this->latitude <= 90.0
            && $this->longitude >= -180.0 && $this->longitude <= 180.0;
    }

    /**
     * Scope a query to offices shown on the public site.
     *
     * @param  Builder<Office>  $query
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /**
     * Scope a query to display order.
     *
     * @param  Builder<Office>  $query
     */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort')->orderBy('id');
    }
}
