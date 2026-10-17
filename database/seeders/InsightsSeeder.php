<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use RuntimeException;

/**
 * Seeds the Insights (blog) with the launch article set.
 *
 * Idempotent: articles are matched on their slug, so re-running updates the
 * copy in place rather than creating duplicates. The publish date is only set
 * when an article is first created, so re-seeding does not keep resetting it
 * to "today".
 *
 * Article bodies are kept out of this class, in:
 *   database/seeders/data/insights/<slug>.html
 */
class InsightsSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->articles() as $slug => $article) {
            $post = Post::firstOrNew(['slug' => $slug]);

            if (! $post->exists) {
                $post->published_at = now()->subDays($article['published_days_ago']);
            }

            $attributes = Arr::except($article, ['published_days_ago']);
            $attributes['slug'] = $slug;
            $attributes['body'] = $this->body($slug);

            $post->fill($attributes)->save();
        }
    }

    /**
     * The launch articles. The array key is the URL slug; `published_days_ago`
     * sets the publish date on first insert (0 = newest, shown first).
     *
     * @return array<string, array<string, mixed>>
     */
    private function articles(): array
    {
        return [
            'choosing-a-facility-management-company-bangladesh' => [
                'title' => 'How to Choose a Facility Management Company in Bangladesh: A 10-Point Checklist',
                'excerpt' => 'A practical, no-jargon checklist for selecting a facility management partner in Bangladesh — from scope and staffing to life safety, housekeeping standards, reporting and hand-over.',
                'cover_image' => 'images/pages/gallery/IMG_9572.JPG',
                'cover_image_alt' => 'HANSA Management training session for site staff',
                'is_published' => true,
                'published_days_ago' => 0,
                'meta_title' => 'How to Choose a Facility Management Company in Bangladesh',
                'meta_description' => 'A practical checklist for choosing a facility management partner in Bangladesh: scope, staffing, life safety, housekeeping standards, MEP, reporting and hand-over.',
                'og_image' => null,
            ],

            'fire-life-safety-failures-commercial-buildings' => [
                'title' => 'Fire and Life Safety in Commercial Buildings: Seven Failures That Are Easy to Miss',
                'excerpt' => 'Fire and life safety systems rarely fail all at once. They degrade quietly — a detector out of calibration, a fire door propped open, a drill that never happened. Seven failures worth checking for.',
                'cover_image' => 'images/home/slider/security-guards-uniform-inspection-hansa-management.jpg',
                'cover_image_alt' => 'HANSA security guards in uniform lined up for inspection by two managers',
                'is_published' => true,
                'published_days_ago' => 1,
                'meta_title' => 'Seven Fire and Life Safety Failures to Watch For',
                'meta_description' => 'Seven fire and life safety failures that are easy to miss in commercial buildings — and the maintenance habits that prevent them.',
                'og_image' => null,
            ],

            'five-star-hotel-housekeeping-standards' => [
                'title' => 'Hotel Housekeeping Standards: What Five-Star Properties Should Expect',
                'excerpt' => 'Housekeeping and cleaning are not the same job. One produces a spotless room; the other produces the same room, the same way, every day. What hotel-grade housekeeping actually requires.',
                'cover_image' => 'images/home/slider/hotel-housekeeping-bed-making-hansa-management.jpg',
                'cover_image_alt' => 'HANSA housekeeping staff making a bed in a hotel guest room',
                'is_published' => true,
                'published_days_ago' => 2,
                'meta_title' => 'Hotel Housekeeping Standards: What to Expect',
                'meta_description' => 'What five-star housekeeping standards actually require — cleaning sequence, public-area rhythm, specialist surface care, inspection and supervision.',
                'og_image' => null,
            ],

            'mep-preventive-maintenance-schedule' => [
                'title' => 'Preventive Maintenance for MEP Systems: Building a Schedule That Holds',
                'excerpt' => 'Most MEP failures are not sudden. They are the visible end of a maintenance schedule that was never built — or never followed. A practical way to put one together.',
                'cover_image' => 'images/pages/services/p-32.jpg',
                'cover_image_alt' => 'MEP engineering services',
                'is_published' => true,
                'published_days_ago' => 3,
                'meta_title' => 'MEP Preventive Maintenance: Building a Schedule',
                'meta_description' => 'A practical guide to building a planned preventive maintenance schedule for MEP systems — asset register, criticality, frequencies, records and review.',
                'og_image' => null,
            ],
        ];
    }

    /**
     * Read an article body from database/seeders/data/insights/<slug>.html.
     */
    private function body(string $slug): string
    {
        $path = database_path("seeders/data/insights/{$slug}.html");

        if (! is_file($path)) {
            throw new RuntimeException("Missing article body file: {$path}");
        }

        return trim((string) file_get_contents($path));
    }
}
