<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Support\Settings;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Regenerate public/sitemap.xml from the site\'s public pages and published articles';

    /**
     * Static, always-present public pages — the single source of truth for the
     * sitemap's fixed entries.
     *
     * @return array<int, string>
     */
    protected function paths(): array
    {
        return [
            '/',
            '/hospitality-management',
            '/security-fire-life-safety',
            '/cleaning-white-glove-housekeeping',
            '/mep-mechanical-electrical-plumbing',
            '/company-profile',
            '/mission-vision',
            '/charter-of-unique-group',
            '/management',
            '/corporate-social-responsibility',
            '/projects',
            '/gallery',
            '/careers',
            '/insights',
            '/contact',
            '/privacy-policy',
        ];
    }

    public function handle(): int
    {
        $base = rtrim(Settings::get('site_url') ?? (string) config('app.url'), '/');
        $today = now()->toDateString();

        $entries = [];

        foreach ($this->paths() as $path) {
            $entries[] = ['path' => $path, 'lastmod' => $today];
        }

        // Published Insights articles. Any failure (table not migrated yet,
        // DB unreachable) is ignored so the static pages still get a sitemap.
        try {
            foreach (Post::query()->published()->ordered()->get() as $post) {
                $entries[] = [
                    'path' => $post->path(),
                    'lastmod' => ($post->updated_at ?? now())->toDateString(),
                ];
            }
        } catch (\Throwable) {
            $this->warn('Posts table unavailable — Insights articles were skipped.');
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach ($entries as $entry) {
            $loc = $entry['path'] === '/' ? $base.'/' : $base.$entry['path'];
            $xml .= "  <url>\n";
            $xml .= '    <loc>'.e($loc)."</loc>\n";
            $xml .= '    <lastmod>'.$entry['lastmod']."</lastmod>\n";
            $xml .= "  </url>\n";
        }

        $xml .= "</urlset>\n";

        File::put(public_path('sitemap.xml'), $xml);

        $this->info('Sitemap written to public/sitemap.xml ('.count($entries).' URLs, lastmod '.$today.').');

        return self::SUCCESS;
    }
}
