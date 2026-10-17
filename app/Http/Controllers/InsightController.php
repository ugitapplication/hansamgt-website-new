<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class InsightController extends Controller
{
    private const PER_PAGE = 9;

    private const ORG_ID = 'https://hansamgt.com/#organization';

    /**
     * The public Insights index (paginated list of published articles).
     */
    public function index(): View
    {
        // Any failure (table not yet migrated, DB unreachable) renders an empty
        // index instead of breaking the page.
        try {
            $posts = Post::query()->published()->ordered()->paginate(self::PER_PAGE);
        } catch (\Throwable) {
            $posts = new LengthAwarePaginator([], 0, self::PER_PAGE);
        }

        return view('pages.insights', [
            'posts' => $posts,
            'seo' => [
                'title' => 'Insights | HANSA Management Ltd.',
                'description' => 'Guides, updates and practical notes on hospitality and facility management in Bangladesh, from HANSA Management Ltd.',
                'canonical' => 'https://hansamgt.com/insights',
                'feed' => 'https://hansamgt.com/insights/feed',
                'schema' => [$this->itemListSchema($posts)],
            ],
        ]);
    }

    /**
     * A single published article.
     */
    public function show(string $slug): View
    {
        try {
            $post = Post::query()->published()->where('slug', $slug)->first();
        } catch (\Throwable) {
            $post = null;
        }

        abort_if($post === null, 404);

        $canonical = 'https://hansamgt.com'.$post->path();
        $image = $post->socialImageUrl();

        return view('pages.insight', [
            'post' => $post,
            'related' => $this->relatedPosts($post),
            'breadcrumbs' => [
                ['label' => 'Home', 'url' => url('/')],
                ['label' => 'Insights', 'url' => url('insights')],
                ['label' => Str::limit($post->title, 60), 'url' => null],
            ],
            'seo' => [
                'title' => $post->meta_title ?: $post->title.' | HANSA Management Ltd.',
                'description' => $post->meta_description ?: $post->summary(160),
                'canonical' => $canonical,
                'type' => 'article',
                'image' => $image ?: null,
                'published_time' => $post->published_date?->toIso8601String(),
                'modified_time' => $post->updated_at?->toIso8601String(),
                'schema' => [$this->articleSchema($post, $canonical, $image)],
            ],
        ]);
    }

    /**
     * RSS 2.0 feed of the latest articles.
     */
    public function feed(): SymfonyResponse
    {
        try {
            $posts = Post::query()->published()->ordered()->limit(20)->get();
        } catch (\Throwable) {
            $posts = collect();
        }

        return response()
            ->view('pages.insights-feed', ['posts' => $posts])
            ->header('Content-Type', 'application/rss+xml; charset=UTF-8');
    }

    /**
     * The newest other articles, for the "More insights" sidebar on an article.
     *
     * The current article is excluded so the sidebar never self-links, and the
     * query is guarded like the index/show queries so a missing table or an
     * unreachable database degrades to "no sidebar" instead of a 500.
     *
     * @return Collection<int, Post>
     */
    private function relatedPosts(Post $post, int $limit = 3): Collection
    {
        try {
            return Post::query()
                ->published()
                ->ordered()
                ->where('id', '!=', $post->getKey())
                ->limit($limit)
                ->get();
        } catch (\Throwable) {
            return collect();
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function itemListSchema(LengthAwarePaginator $posts): array
    {
        $items = [];

        foreach (array_values($posts->items()) as $index => $post) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'url' => url($post->path()),
                'name' => $post->title,
            ];
        }

        return [
            '@type' => 'ItemList',
            '@id' => 'https://hansamgt.com/insights#article-list',
            'name' => 'Insights',
            'itemListElement' => $items,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function articleSchema(Post $post, string $canonical, string $image): array
    {
        $schema = [
            '@type' => 'BlogPosting',
            '@id' => $canonical.'#article',
            'headline' => Str::limit($post->title, 110, ''),
            'description' => $post->meta_description ?: $post->summary(160),
            'datePublished' => $post->published_date?->toIso8601String(),
            'dateModified' => $post->updated_at?->toIso8601String(),
            'inLanguage' => 'en',
            'author' => ['@id' => self::ORG_ID],
            'publisher' => ['@id' => self::ORG_ID],
            'mainEntityOfPage' => ['@id' => $canonical.'#webpage'],
            'articleSection' => 'Insights',
            'isPartOf' => ['@id' => 'https://hansamgt.com/insights#article-list'],
        ];

        if ($image !== '') {
            $schema['image'] = $image;
        }

        if ($post->excerpt) {
            $schema['abstract'] = $post->excerpt;
        }

        return $schema;
    }
}
