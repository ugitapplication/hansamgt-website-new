<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Regenerate public/sitemap.xml from the site\'s public pages';

    /**
     * Public, indexable pages — the single source of truth for the sitemap.
     * When CMS-driven content (e.g. News/Insights) is added, append their URLs
     * here (or replace this with a query) and re-run the command.
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
            '/contact',
        ];
    }

    public function handle(): int
    {
        $base = rtrim(config('seo.organization.url'), '/');
        $lastmod = now()->toDateString();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach ($this->paths() as $path) {
            $loc = $path === '/' ? $base.'/' : $base.$path;
            $xml .= "  <url>\n";
            $xml .= '    <loc>'.e($loc)."</loc>\n";
            $xml .= '    <lastmod>'.$lastmod."</lastmod>\n";
            $xml .= "  </url>\n";
        }

        $xml .= "</urlset>\n";

        File::put(public_path('sitemap.xml'), $xml);

        $this->info('Sitemap written to public/sitemap.xml ('.count($this->paths()).' URLs, lastmod '.$lastmod.').');

        return self::SUCCESS;
    }
}
