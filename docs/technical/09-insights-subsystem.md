# 09 — Insights Subsystem

> **Purpose:** Document the article system end to end: model, publish rules, public routes, pagination, related posts, RSS, structured data, admin editing, and its trust boundaries.
> **Audience:** Developers and content editors.
> **Last reviewed:** 2026-10-19
> **Evidence basis:** `app/Http/Controllers/InsightController.php`, `app/Models/Post.php`, `app/Filament/Resources/Posts/PostResource.php`, `resources/views/pages/{insights,insight,insights-feed}.blade.php`, `database/seeders/InsightsSeeder.php`, `database/migrations/2026_10_17_000001_create_posts_table.php`.

**Verification legend:** ✅ source-verified · 🟡 runtime-unverified · ⚠️ partial · ⛔ missing · ❓ unknown

---

## 1. Overview

| Aspect | Value |
|---|---|
| Public index | `/insights` |
| Detail | `/insights/{slug}` |
| Feed | `/insights/feed` (RSS 2.0) |
| Admin | `/admin/posts` (label "Insights") |
| Table | `posts` |
| Model | `App\Models\Post` |
| Pagination | 9 per page (`InsightController::PER_PAGE`, line 14) |
| Feed limit | 20 |
| Related limit | 3 |
| Default ordering | `published_at DESC, id DESC` |
| Detail lookup key | `slug` (`getRouteKeyName`, `Post.php:50-53`) |

There is **no category, tag, author, or comment system**. `articleSection` is the literal string `'Insights'` (`InsightController.php:159`). ⛔

---

## 2. Publication rules

`Post::scopePublished` (`Post.php:60-67`):

```php
$query->where('is_published', true)
    ->where(function (Builder $query): void {
        $query->whereNull('published_at')
            ->orWhere('published_at', '<=', now());
    });
```

| `is_published` | `published_at` | Visible? |
|---|---|---|
| false | any | ⛔ no |
| true | null | ✅ yes |
| true | past | ✅ yes |
| true | **future** | ⛔ **no** — scheduled publishing without a scheduler |

**Scheduled publishing works passively:** nothing runs at the publish time; the article simply starts matching `published_at <= now()` on the next request. There is no cron requirement. ✅

`scopeOrdered` (74-77): `orderByDesc('published_at')->orderByDesc('id')`. Because `published_at` can be null, nulls sort last on SQLite/MySQL DESC. ⚠️

Display date (`publishedDate` accessor, 83-86): `published_at ?? created_at`, so an article published without a date still shows the creation date.

---

## 3. Public index — `/insights`

`InsightController::index` (21-41):

```php
try {
    $posts = Post::query()->published()->ordered()->paginate(self::PER_PAGE);
} catch (\Throwable) {
    $posts = new LengthAwarePaginator([], 0, self::PER_PAGE);
}
```

- On DB failure: an **empty but valid** paginator, so the page returns 200 with the empty state.
- SEO: title, description, canonical, `feed` link, and `ItemList` schema (33-39).
- View `pages/insights.blade.php`:
  - `<h1>Insights</h1>` (line 5)
  - Empty state (10-18)
  - Grid of `insight-card` articles (20-40)
  - Cover image `600×400`, `loading="lazy"`, `decoding="async"` (25-27)
  - Date `<time datetime="YYYY-MM-DD">` (31-33)
  - `<h2>` title link (34-36)
  - 150-char summary (37)
  - "Read article" link (38)
  - Pagination links

### 3.1 ItemList schema

`itemListSchema` (122-141):

```json
{
  "@type": "ItemList",
  "@id": "https://hansamgt.com/insights#article-list",
  "name": "Insights",
  "itemListElement": [
    { "@type": "ListItem", "position": 1, "url": "…", "name": "…" }
  ]
}
```

Positions restart at 1 on every page, because the schema is built from the current page's items only. ⚠️ Paginated `ItemList` positions would ideally be offset; they are not.

---

## 4. Detail — `/insights/{slug}`

`InsightController::show` (46-78):

1. `Post::published()->where('slug', $slug)->first()` inside `try/catch`.
2. `abort_if($post === null, 404)` (54).
3. `$canonical = 'https://hansamgt.com'.$post->path()` (56) — `path()` returns `/insights/{slug}` (91-94).
4. `$image = $post->socialImageUrl()` — `og_image` then `cover_image` (107-110).
5. Related posts, breadcrumbs, SEO array.

View `pages/insight.blade.php`:

| Element | Lines |
|---|---|
| `<h1 class="page-h1 page-h1--sm">` | 8 |
| date `<time>` | 10-14 |
| cover image `1120×630` | 16-21 |
| excerpt lede | 23-25 |
| **unescaped body** `{!! $post->body !!}` | 27-29 |
| back link | 32-34 |
| related sidebar (only if `>= 2`) | 39+ |

### 4.1 SEO

| Field | Source |
|---|---|
| `title` | `meta_title` or `"{title} \| HANSA Management Ltd."` (68) |
| `description` | `meta_description` or `summary(160)` (69) |
| `canonical` | `https://hansamgt.com/insights/{slug}` |
| `type` | `article` |
| `image` | social image or null |
| `published_time` | `published_date->toIso8601String()` |
| `modified_time` | `updated_at->toIso8601String()` |

### 4.2 BlogPosting schema

`articleSchema` (146-172):

```json
{
  "@type": "BlogPosting",
  "@id": "{canonical}#article",
  "headline": "…",
  "description": "…",
  "datePublished": "…",
  "dateModified": "…",
  "inLanguage": "en",
  "author": { "@id": "https://hansamgt.com/#organization" },
  "publisher": { "@id": "https://hansamgt.com/#organization" },
  "mainEntityOfPage": { "@id": "{canonical}#webpage" },
  "articleSection": "Insights",
  "isPartOf": { "@id": "https://hansamgt.com/insights#article-list" },
  "image": "…",        // only when present
  "abstract": "…"      // only when excerpt present
}
```

The `author` is the **organisation**, not a person — correct, since there is no author field. ✅

### 4.3 Related posts

`relatedPosts` (105-117):

```php
Post::query()->published()->ordered()
    ->where('id', '!=', $post->getKey())
    ->limit($limit)->get();
```

- "Related" means **newest other articles**, not topical similarity. ⚠️ Misleading label if a future reader expects relevance.
- Guarded by `try/catch`, returning `collect()`.
- The view hides the sidebar when fewer than 2 results (line 39), so a one-item sidebar never appears.

---

## 5. RSS feed — `/insights/feed`

`InsightController::feed` (83-94):

- Latest 20 published posts.
- `response()->view(...)->header('Content-Type', 'application/rss+xml; charset=UTF-8')`.
- No explicit `Cache-Control`.

`pages/insights-feed.blade.php`:

| Element | Line |
|---|---|
| XML declaration via raw PHP echo | 1 |
| `<rss version="2.0" xmlns:atom=…>` | 2 |
| `<channel>` title/link/description/language | 4-7 |
| `atom:link` self | 8 |
| `<item>` title/link/guid/pubDate/description | 10-16 |

`pubDate` uses `($post->published_date ?? now())->toRfc2822String()` (14). `description` is `summary(300)` — plain text, HTML stripped.

⚠️ `summary()` strips tags but does **not** re-escape `&`, `<`, `>` after `html_entity_decode`. Blade's `{{ }}` escaping then handles XML special characters, so the output is safe. ✅

The feed link is advertised in `<head>` only on the index page (`seo.blade.php:199-201`, driven by `$seo['feed']`), not on article pages. ⚠️

---

## 6. Admin editing — `/admin/posts`

`app/Filament/Resources/Posts/PostResource.php`.

| Section | Fields | Validation |
|---|---|---|
| Article | `title` | required, max 255, live slug generation on create |
| | `slug` | required, max 255, **unique** (ignore current record) |
| | `excerpt` | max 300 |
| | `body` | **required**, RichEditor |
| Cover image | `cover_image` | image, public disk, `posts/`, jpeg/png/webp, max 3 MB |
| | `cover_image_alt` | max 255 |
| Publishing | `is_published` | toggle, default false |
| | `published_at` | DateTimePicker, no seconds, helper explains future-date hiding |
| SEO | `meta_title` | max 70 |
| | `meta_description` | max 180 |
| | `og_image` | image, `posts/og`, max 3 MB |

Table columns: cover preview, title (searchable), slug (hidden by default), published toggle, publish date, updated (hidden). Default sort `published_at DESC`. Actions: edit, delete, bulk delete.

Pages: `ListPosts`, `CreatePost`, `EditPost` (`getPages`, 204-211). All three are thin subclasses with `CreateAction` on the list page (`ListPosts.php:13-18`).

### 6.1 Upload handling detail

Each `FileUpload` uses:

```php
->disk('public')
->directory('posts')          // or posts/og
->visibility('public')
->acceptedFileTypes(['image/jpeg','image/png','image/webp'])
->maxSize(3072)
->fetchFileInformation(false)
->getUploadedFileUsing(fn (string $file) => [
    'name' => basename($file),
    'size' => 0,
    'type' => null,
    'url'  => ImageUrl::resolve($file),
])
```

`fetchFileInformation(false)` plus a custom `getUploadedFileUsing` avoids Filament stat-ing a file that may live under `public/` rather than the storage disk. This is the pattern across all image resources. See [14 — Media](14-media-and-image-pipeline.md).

⚠️ `size => 0` and `type => null` mean the admin UI cannot show real file sizes or MIME types for existing images.

### 6.2 Slug behaviour

`title` is `->live(onBlur: true)` and on **create only** sets `slug` via `Str::slug()` (`PostResource.php:59-64`). Editing a title does **not** change the slug — correct for URL stability. ✅

---

## 7. Seeded content

`database/seeders/InsightsSeeder.php` defines 4 articles by slug (49-85):

| Slug |
|---|
| `choosing-a-facility-management-company-bangladesh` |
| `fire-life-safety-failures-commercial-buildings` |
| `five-star-hotel-housekeeping-standards` |
| `mep-preventive-maintenance-schedule` |

Body HTML lives in `database/seeders/data/insights/*.html`. The seeder uses relative `published_days_ago` values, so dates stay recent. 🟡

A parallel Markdown copy of the same four articles exists under `docs/content/insights/` — **documentation drafts, not the source of truth**. The database is authoritative once seeded.

---

## 8. Failure modes

| Failure | Observed behaviour |
|---|---|
| `posts` table missing | Index shows empty state (200); detail returns 404 |
| DB unreachable | Same as above — **no 500** |
| Slug not found | 404 with custom error page |
| Unpublished article requested by slug | 404 (not 403) — correct, avoids leaking existence |
| Future-dated article | 404 until `published_at` passes |
| Cover image missing from disk | `ImageUrl::resolve` falls back to `asset($path)` → likely 404 image |
| `body` contains script | Rendered unescaped and executed (CSP allows inline) ⚠️ |

---

## 9. Testing coverage

| Test | Covers insights? |
|---|---|
| `PageHeadingTest` | ✅ index only (`/insights`) |
| `ExampleTest` | ⛔ |
| `SettingsCacheTest` | ⛔ |
| `WhatsappFloatTest` | ✅ includes `/insights` in its page list |
| `OfficeMapPageTest` | ⛔ |
| `ImageUrlTest` | ⚠️ image resolution used by covers |
| `OfficeMapCoordinatesTest` | ⛔ |

⛔ **No test** covers: detail page rendering, 404 for unpublished, pagination, RSS output, `ItemList`/`BlogPosting` schema, slug uniqueness, or scheduled publishing.

---

## Known limitations

- Pagination and feed output were not executed.
- The exact seeded publish dates are relative and were not resolved to absolute dates.
- `RichEditor` output sanitisation depends on Filament's editor configuration; the stored HTML is trusted and rendered raw.
- Whether the production site currently has any published posts is unknown (no DB access).

---

## Related chapters

- [06 — Routing & URL Map](06-routing-and-url-map.md)
- [07 — Public Pages Reference](07-public-pages-reference.md)
- [13 — Database & Data Dictionary](13-database-and-data-dictionary.md#31-posts)
- [15 — SEO & Structured Data](15-seo-and-structured-data.md)
- [17 — Security & Hardening](17-security-and-hardening.md#6-xss-trust-boundaries)
- [19 — Testing & QA](19-testing-and-qa.md)
