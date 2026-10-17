<?php

namespace App\Models;

use App\Models\Concerns\ResolvesImageUrl;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use ResolvesImageUrl;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'slug',
        'name',
        'role',
        'modal_name',
        'teaser',
        'bio',
        'image',
        'image_alt',
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
        ];
    }

    /**
     * Scope a query to members shown on the public site.
     *
     * @param  Builder<TeamMember>  $query
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /**
     * Scope a query to display order.
     *
     * @param  Builder<TeamMember>  $query
     */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort')->orderBy('id');
    }

    /**
     * The name shown in the "read more" dialog heading.
     */
    public function displayName(): string
    {
        return (string) ($this->modal_name ?: $this->name);
    }

    /**
     * The image alt text, falling back to a description built from the role.
     */
    public function imageAlt(): string
    {
        return (string) ($this->image_alt ?: $this->name.', '.$this->role);
    }

    /**
     * Admin preview URL for the portrait (used by the Filament table).
     */
    protected function previewUrl(): Attribute
    {
        return Attribute::get(fn (): string => $this->imageUrl());
    }
}
