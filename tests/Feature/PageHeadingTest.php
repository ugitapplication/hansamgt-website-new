<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Every public page must expose exactly one <h1> — the single topic heading for
 * the page. Both search engines and assistive technology rely on there being
 * one, and only one, first-level heading.
 *
 * The routes below mirror the public GET routes registered in routes/web.php.
 * Dynamic detail pages (/insights/{slug}) are not covered here because they
 * need a Post fixture.
 */
class PageHeadingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The public pages rendered directly from a controller action.
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
    public function test_a_public_page_renders_with_exactly_one_h1(string $path): void
    {
        $response = $this->get($path);

        $response->assertOk();

        // Count real opening <h1> tags only — `<h1>` and `<h1 class="...">` — so
        // escaped text such as "&lt;h1" or prose mentioning "h1" cannot affect
        // the count.
        $count = preg_match_all('/<h1[\s>]/i', (string) $response->getContent());

        $this->assertSame(
            1,
            $count,
            sprintf('Expected exactly one <h1> opening tag on [%s], found %d.', $path, $count),
        );
    }
}
