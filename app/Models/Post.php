<?php

namespace App\Models;

use App\Models\Concerns\ResolvesImageUrl;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use ResolvesImageUrl;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'body',
        'cover_image',
        'cover_image_alt',
        'is_published',
        'published_at',
        'meta_title',
        'meta_description',
        'og_image',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    /**
     * Articles are addressed by slug in public URLs.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Scope a query to articles that are live on the public site.
     *
     * @param  Builder<Post>  $query
     */
    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true)
            ->where(function (Builder $query): void {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    /**
     * Scope a query to newest-first display order.
     *
     * @param  Builder<Post>  $query
     */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderByDesc('published_at')->orderByDesc('id');
    }

    /**
     * The date shown to readers: the explicit publish date, or the creation
     * date when an article was published without one.
     */
    protected function publishedDate(): Attribute
    {
        return Attribute::get(fn (): ?\Illuminate\Support\Carbon => $this->published_at ?? $this->created_at);
    }

    /**
     * The public path for the article.
     */
    public function path(): string
    {
        return '/insights/'.$this->slug;
    }

    /**
     * The absolute cover image URL (empty string when no cover is set).
     */
    public function coverImageUrl(): string
    {
        return $this->imageUrl('cover_image');
    }

    /**
     * The absolute social-share image URL, falling back to the cover image.
     */
    public function socialImageUrl(): string
    {
        return $this->imageUrl('og_image') ?: $this->coverImageUrl();
    }

    /**
     * A plain-text summary for meta descriptions, index cards and feeds.
     * Uses the hand-written excerpt when present, otherwise the article body.
     */
    public function summary(int $limit = 160): string
    {
        $text = $this->excerpt ?: strip_tags((string) $this->body);
        $text = trim(preg_replace('/\s+/', ' ', html_entity_decode($text)) ?? '');

        return Str::limit($text, $limit);
    }

    /**
     * Admin preview URL for the cover image (used by the Filament table).
     */
    protected function previewUrl(): Attribute
    {
        return Attribute::get(fn (): string => $this->coverImageUrl());
    }
}
