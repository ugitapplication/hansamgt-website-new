<?php

namespace Tests\Feature;

use App\Models\Office;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The office map component on the homepage and the contact page.
 *
 * The coordinates used here are synthetic fixtures — nothing in this file
 * asserts where a real HANSA office is. The map is only expected to appear when
 * an office carries a complete, in-range pair.
 */
class OfficeMapPageTest extends TestCase
{
    use RefreshDatabase;

    private const VERIFIED = [
        'title' => 'Verified Booth',
        'address' => 'Test address, Dhaka',
        'latitude' => 12.5,
        'longitude' => 45.25,
    ];

    public function test_homepage_and_contact_page_render_without_errors(): void
    {
        Office::query()->create(['title' => 'Booth', 'address' => 'Test address']);

        $this->get('/')->assertOk();
        $this->get('/contact')->assertOk();
    }

    public function test_the_map_is_not_rendered_when_no_office_has_a_verified_location(): void
    {
        Office::query()->create(['title' => 'Booth', 'address' => 'Test address']);

        foreach (['/', '/contact'] as $url) {
            $response = $this->get($url)->assertOk();

            $response->assertDontSee('data-office-map', false);
            $response->assertDontSee('Load Google Map');
            $response->assertDontSee('google.com/maps');
        }
    }

    public function test_the_map_renders_only_offices_with_a_verified_location(): void
    {
        Office::query()->create(self::VERIFIED);
        Office::query()->create(['title' => 'Unmapped Booth', 'address' => 'Test address']);

        $response = $this->get('/contact')->assertOk();

        $response->assertSee('data-office-map', false);
        $response->assertSee('Load Google Map');
        $response->assertSee('connects to Google');
        $response->assertSee('Google map of Verified Booth', false);

        // The unverified office still has its normal card, but no embed.
        $response->assertSee('Unmapped Booth');
        $response->assertDontSee('Google map of Unmapped Booth', false);

        $this->assertCount(1, $this->iframes($response));
    }

    public function test_the_homepage_renders_the_same_component(): void
    {
        Office::query()->create(self::VERIFIED);

        $response = $this->get('/')->assertOk();

        $response->assertSee('data-office-map', false);
        $this->assertCount(1, $this->iframes($response));
    }

    public function test_each_verified_office_gets_its_own_map(): void
    {
        Office::query()->create(self::VERIFIED);
        Office::query()->create([
            'title' => 'Second Booth',
            'address' => 'Another address',
            'latitude' => 23.75,
            'longitude' => 90.38,
        ]);

        $response = $this->get('/contact')->assertOk();

        $frames = $this->iframes($response);

        $this->assertCount(2, $frames);
        $this->assertStringContainsString('data-src="https://www.google.com/maps?q=12.5,45.25', $frames[0]);
        $this->assertStringContainsString('title="Google map of Verified Booth"', $frames[0]);
        $this->assertStringContainsString('data-src="https://www.google.com/maps?q=23.75,90.38', $frames[1]);
        $this->assertStringContainsString('title="Google map of Second Booth"', $frames[1]);
    }

    public function test_no_request_to_google_is_emitted_before_the_visitor_activates_it(): void
    {
        Office::query()->create(self::VERIFIED);

        $response = $this->get('/contact')->assertOk();
        $frames = $this->iframes($response);

        $this->assertCount(1, $frames);

        // The embed URL is present, but only in data-src: an iframe with no src
        // makes no network request, so nothing reaches Google until the button
        // is pressed.
        $this->assertStringContainsString('data-src=', $frames[0]);
        $this->assertDoesNotMatchRegularExpression('/\ssrc\s*=/', $frames[0]);
        $this->assertStringContainsString('hidden', $frames[0]);

        // Nothing else in the page points the browser at Google either.
        $html = (string) $response->getContent();
        $this->assertStringNotContainsString('<script src="https://www.google.com', $html);
        $this->assertStringNotContainsString('maps.googleapis.com', $html);
        $this->assertStringNotContainsString('maps.gstatic.com', $html);
    }

    public function test_every_map_keeps_a_visible_directions_link_that_needs_no_javascript(): void
    {
        Office::query()->create(self::VERIFIED);

        $response = $this->get('/contact')->assertOk();

        $response->assertSee(
            'href="https://www.google.com/maps/dir/?api=1&amp;destination=12.5,45.25"',
            false,
        );
        $response->assertSee('aria-label="Get directions to Verified Booth"', false);
    }

    public function test_the_control_is_a_real_button_described_by_the_privacy_note(): void
    {
        Office::query()->create(self::VERIFIED);

        $html = (string) $this->get('/contact')->assertOk()->getContent();

        $this->assertMatchesRegularExpression(
            '/<button type="button" class="office-map__load" data-office-map-load\s+aria-describedby="office-map-[a-z0-9]+-1-note">\s*Load Google Map\s*<\/button>/',
            $html,
        );
        $this->assertStringContainsString('Nothing is requested until you choose to load it.', $html);
    }

    public function test_an_office_with_only_one_coordinate_is_treated_as_unavailable(): void
    {
        Office::query()->create([
            'title' => 'Half Booth',
            'address' => 'Test address',
            'latitude' => 12.5,
            'longitude' => null,
        ]);

        $this->get('/contact')->assertOk()->assertDontSee('data-office-map', false);
    }

    public function test_out_of_range_coordinates_are_rejected(): void
    {
        Office::query()->create([
            'title' => 'Bad Booth',
            'address' => 'Test address',
            'latitude' => 91.0,
            'longitude' => 45.25,
        ]);

        $this->get('/contact')->assertOk()->assertDontSee('data-office-map', false);
    }

    public function test_the_map_is_not_rendered_when_the_feature_is_switched_off(): void
    {
        config()->set('site.maps.enabled', false);

        Office::query()->create(self::VERIFIED);

        $this->get('/contact')->assertOk()->assertDontSee('data-office-map', false);
    }

    public function test_two_component_instances_get_unique_ids(): void
    {
        $offices = [[...self::VERIFIED]];

        $html = view('partials.office-map', ['offices' => $offices])->render()
            .view('partials.office-map', ['offices' => $offices])->render();

        // Only the section id — not an item's "-note" id.
        preg_match_all('/id="(office-map-[^"-]+)"/', $html, $matches);

        $this->assertCount(2, $matches[1]);
        $this->assertNotSame($matches[1][0], $matches[1][1]);
    }

    public function test_the_component_contains_no_leaflet_or_openstreetmap_leftovers(): void
    {
        Office::query()->create(self::VERIFIED);

        $html = strtolower((string) $this->get('/contact')->assertOk()->getContent());

        $this->assertStringNotContainsString('leaflet', $html);
        $this->assertStringNotContainsString('openstreetmap', $html);
        $this->assertStringNotContainsString('tile.openstreetmap', $html);
    }

    public function test_csp_frames_the_configured_google_origin_without_a_wildcard(): void
    {
        $csp = (string) $this->get('/')->headers->get('Content-Security-Policy');

        $this->assertStringContainsString("frame-src 'self' https://www.google.com", $csp);
        $this->assertStringContainsString("script-src 'self'", $csp);

        // No wildcards, no CDN, and no leftover tile host.
        $this->assertStringNotContainsString('*', $csp);
        $this->assertStringNotContainsString('unpkg', $csp);
        $this->assertStringNotContainsString('openstreetmap', $csp);

        // The public site keeps the stricter policy.
        $this->assertStringNotContainsString("'unsafe-eval'", $csp);
    }

    public function test_csp_frame_origin_follows_the_configured_endpoint(): void
    {
        config()->set('site.maps.endpoint', 'https://maps.example.test/maps');

        $csp = (string) $this->get('/')->headers->get('Content-Security-Policy');

        $this->assertStringContainsString('frame-src \'self\' https://maps.example.test', $csp);
        $this->assertStringNotContainsString('https://www.google.com', $csp);
    }

    public function test_csp_drops_the_google_frame_origin_when_the_map_is_switched_off(): void
    {
        config()->set('site.maps.enabled', false);

        $csp = (string) $this->get('/')->headers->get('Content-Security-Policy');

        $this->assertStringContainsString("frame-src 'self'", $csp);
        $this->assertStringNotContainsString('https://www.google.com', $csp);
    }

    /**
     * Every <iframe> opening tag in the rendered page.
     *
     * @return list<string>
     */
    private function iframes(\Illuminate\Testing\TestResponse $response): array
    {
        preg_match_all('/<iframe\b[^>]*>/', (string) $response->getContent(), $matches);

        return $matches[0];
    }
}
