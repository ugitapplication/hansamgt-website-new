<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * The floating quick-link rail is shared chrome, not a homepage section.
 *
 * The legacy wpfm menu appeared on every page and linked to the homepage
 * anchors. The migration originally scoped the rail to the homepage, so these
 * tests lock down the restored site-wide behaviour.
 */
class QuickRailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The public pages rendered from a controller action. The RSS feed is
     * excluded because it is XML and does not extend the public layout.
     *
     * @return array<string, array{string}>
     */
    public static function publicPages(): array
    {
        return [
            'home' => ['/'],
            'company profile' => ['/company-profile'],
            'mission & vision' => ['/mission-vision'],
            'charter of unique group' => ['/charter-of-unique-group'],
            'management' => ['/management'],
            'corporate social responsibility' => ['/corporate-social-responsibility'],
            'contact' => ['/contact'],
            'hospitality management' => ['/hospitality-management'],
            'security, fire & life safety' => ['/security-fire-life-safety'],
            'cleaning & white-glove housekeeping' => ['/cleaning-white-glove-housekeeping'],
            'mep' => ['/mep-mechanical-electrical-plumbing'],
            'projects' => ['/projects'],
            'gallery' => ['/gallery'],
            'careers' => ['/careers'],
            'privacy policy' => ['/privacy-policy'],
            'insights' => ['/insights'],
        ];
    }

    #[DataProvider('publicPages')]
    public function test_the_quick_rail_is_rendered_once_on_every_public_page(string $path): void
    {
        $response = $this->get($path);

        $response->assertOk();
        $response->assertSee('class="home-rail"', false);
        $response->assertSee('aria-label="Quick links"', false);

        $this->assertSame(
            1,
            substr_count((string) $response->getContent(), 'class="home-rail"'),
            sprintf('Expected exactly one quick rail on [%s].', $path),
        );
    }

    public function test_the_quick_rail_has_the_seven_legacy_shortcuts(): void
    {
        $response = $this->get('/');
        $response->assertOk();

        $content = (string) $response->getContent();

        $this->assertSame(
            7,
            substr_count($content, 'home-rail__label'),
            'The rail should contain exactly seven shortcuts.',
        );

        $links = [
            'what_we_do' => 'Our Services',
            'ServiceExcellence' => 'HANSA In Numbers',
            'emr_highlights' => 'Services at-a-glance',
            'why_choos_us' => 'Why to Choose HANSA',
            'emr_news' => 'Service Projects',
            'emr_awards' => 'News',
            'emr_contact' => 'Contact Us',
        ];

        foreach ($links as $anchor => $label) {
            $response->assertSee('href="'.url('/').'#'.$anchor.'"', false);
            $response->assertSee('aria-label="'.$label.'"', false);
        }
    }
}
