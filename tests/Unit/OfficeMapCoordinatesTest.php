<?php

namespace Tests\Unit;

use App\Models\Office;
use App\Support\OfficeMap;
use Tests\TestCase;

/**
 * The rule for "this office has a usable location", and the Google Maps embed
 * URL the component hands to the browser.
 *
 * The coordinates below are deliberately synthetic: no test asserts anything
 * about where a real HANSA office is, and the map stays disabled in production
 * until someone enters verified values.
 */
class OfficeMapCoordinatesTest extends TestCase
{
    /** @var array<string, mixed> */
    private const VERIFIED = [
        'title' => 'Verified',
        'address' => 'Somewhere',
        'latitude' => 12.5,
        'longitude' => 45.25,
    ];

    public function test_a_complete_in_range_pair_is_valid(): void
    {
        $this->assertTrue(OfficeMap::isValidPair(12.5, 45.25));
        $this->assertTrue(OfficeMap::isValidPair(0, 0));
        $this->assertTrue(OfficeMap::isValidPair(-90, -180));
        $this->assertTrue(OfficeMap::isValidPair(90, 180));
        $this->assertTrue(OfficeMap::isValidPair('12.5', '45.25'));
    }

    public function test_an_incomplete_or_out_of_range_pair_is_not_valid(): void
    {
        $this->assertFalse(OfficeMap::isValidPair(null, 45.25));
        $this->assertFalse(OfficeMap::isValidPair(12.5, null));
        $this->assertFalse(OfficeMap::isValidPair(null, null));
        $this->assertFalse(OfficeMap::isValidPair('', ''));
        $this->assertFalse(OfficeMap::isValidPair(90.1, 0));
        $this->assertFalse(OfficeMap::isValidPair(-90.1, 0));
        $this->assertFalse(OfficeMap::isValidPair(0, 180.1));
        $this->assertFalse(OfficeMap::isValidPair(0, -180.1));
        $this->assertFalse(OfficeMap::isValidPair('north', 'east'));
    }

    public function test_markers_include_only_offices_with_a_verified_pair(): void
    {
        $markers = OfficeMap::markers([
            self::VERIFIED,
            ['title' => 'No coordinates', 'address' => 'Nowhere', 'latitude' => null, 'longitude' => null],
            ['title' => 'Half pair', 'address' => 'Half', 'latitude' => 12.5, 'longitude' => null],
            ['title' => 'Out of range', 'address' => 'Odd', 'latitude' => 123.0, 'longitude' => 45.25],
        ]);

        $this->assertCount(1, $markers);
        $this->assertSame('Verified', $markers[0]['name']);
        $this->assertSame(12.5, $markers[0]['lat']);
        $this->assertSame(45.25, $markers[0]['lng']);

        // The address is never turned into a position, so an office with an
        // address but no pair produces nothing at all.
        $this->assertArrayNotHasKey('address', $markers[0]);
    }

    public function test_a_marker_carries_a_keyless_google_embed_url(): void
    {
        config()->set('site.maps.endpoint', 'https://www.google.com/maps');
        config()->set('site.maps.zoom', 16);
        config()->set('site.maps.language', 'en');

        $markers = OfficeMap::markers([self::VERIFIED]);

        $this->assertSame(
            'https://www.google.com/maps?q=12.5,45.25&z=16&hl=en&output=embed',
            $markers[0]['embed'],
        );
    }

    public function test_the_embed_url_carries_no_api_key_and_no_third_party_host(): void
    {
        $embed = OfficeMap::markers([self::VERIFIED])[0]['embed'];

        $this->assertStringNotContainsString('key=', $embed);
        $this->assertStringNotContainsString('AIza', $embed);
        $this->assertStringNotContainsString('openstreetmap', strtolower($embed));
        $this->assertStringNotContainsString('leaflet', strtolower($embed));
        $this->assertStringNotContainsString('tile.', strtolower($embed));
    }

    public function test_the_embed_url_uses_the_configured_endpoint_zoom_and_language(): void
    {
        config()->set('site.maps.endpoint', 'https://maps.example.test/maps/');
        config()->set('site.maps.zoom', 12);
        config()->set('site.maps.language', 'bn');

        $embed = OfficeMap::markers([self::VERIFIED])[0]['embed'];

        // The trailing slash of the endpoint is not doubled up.
        $this->assertSame('https://maps.example.test/maps?q=12.5,45.25&z=12&hl=bn&output=embed', $embed);
    }

    public function test_the_zoom_is_clamped_and_the_language_cannot_break_the_url(): void
    {
        config()->set('site.maps.zoom', 9999);
        config()->set('site.maps.language', 'en&output=json<script>');

        $embed = OfficeMap::markers([self::VERIFIED])[0]['embed'];

        $this->assertStringContainsString('&z=21&', $embed);
        $this->assertStringContainsString('&hl=enoutputjsonscript&', $embed);
        $this->assertStringContainsString('&output=embed', $embed);
        $this->assertSame(1, substr_count($embed, '&output='));

        config()->set('site.maps.zoom', -4);
        $this->assertStringContainsString('&z=1&', OfficeMap::markers([self::VERIFIED])[0]['embed']);
    }

    public function test_a_marker_carries_an_always_usable_directions_link(): void
    {
        $markers = OfficeMap::markers([self::VERIFIED]);

        $this->assertSame(
            'https://www.google.com/maps/dir/?api=1&destination=12.5,45.25',
            $markers[0]['directions'],
        );
        $this->assertStringNotContainsString('key=', $markers[0]['directions']);
    }

    public function test_enabled_needs_the_switch_a_usable_endpoint_and_a_verified_marker(): void
    {
        config()->set('site.maps.enabled', true);

        $this->assertFalse(OfficeMap::enabled([]));
        $this->assertFalse(OfficeMap::enabled([
            ['title' => 'No location', 'address' => '', 'latitude' => null, 'longitude' => null],
        ]));
        $this->assertTrue(OfficeMap::enabled([self::VERIFIED]));

        config()->set('site.maps.enabled', false);

        $this->assertFalse(OfficeMap::enabled([self::VERIFIED]));

        config()->set('site.maps.enabled', true);
        config()->set('site.maps.endpoint', 'not-a-url');

        // A broken endpoint must disable the feature rather than emit an iframe
        // pointing at nothing.
        $this->assertFalse(OfficeMap::enabled([self::VERIFIED]));
        $this->assertSame([], OfficeMap::markers([self::VERIFIED]));
    }

    public function test_the_model_reports_its_own_coordinates(): void
    {
        $this->assertTrue((new Office(['latitude' => 12.5, 'longitude' => 45.25]))->hasCoordinates());
        $this->assertFalse((new Office(['latitude' => 12.5, 'longitude' => null]))->hasCoordinates());
        $this->assertFalse((new Office(['latitude' => null, 'longitude' => null]))->hasCoordinates());
        $this->assertFalse((new Office(['latitude' => 91.0, 'longitude' => 45.25]))->hasCoordinates());
    }
}
