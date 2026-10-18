<?php

namespace App\Support;

/**
 * Turns the office records into the data the click-to-load Google map needs.
 *
 * One place decides whether an office has a *verified, usable* location, so the
 * Blade component, the admin form and the tests all agree on the same rule:
 *
 *   - both latitude and longitude must be present (a half pair is "no location",
 *     never guessed from the other value or from the address), and
 *   - each value must fall inside its valid range.
 *
 * Nothing here invents a position. An office without a pair is left out, and
 * the published address is never geocoded: the map shows a location only when
 * somebody has entered coordinates that were checked against the real building.
 * If no office passes the check, `enabled()` returns false and the map is not
 * rendered at all — there is deliberately no fallback marker and no arbitrary
 * centre.
 */
class OfficeMap
{
    /**
     * The map markers for the offices that have a verified location.
     *
     * @param  array<int, array<string, mixed>>  $offices
     * @return list<array{name: string, lat: float, lng: float, embed: string, directions: string}>
     */
    public static function markers(array $offices): array
    {
        $endpoint = self::endpoint();

        if ($endpoint === null) {
            return [];
        }

        $markers = [];

        foreach ($offices as $office) {
            $latitude = $office['latitude'] ?? null;
            $longitude = $office['longitude'] ?? null;

            if (! self::isValidPair($latitude, $longitude)) {
                continue;
            }

            $lat = (float) $latitude;
            $lng = (float) $longitude;

            $markers[] = [
                'name' => (string) ($office['title'] ?? ''),
                'lat' => $lat,
                'lng' => $lng,
                'embed' => self::embedUrl($endpoint, $lat, $lng),
                'directions' => self::directionsUrl($lat, $lng),
            ];
        }

        return $markers;
    }

    /**
     * Whether the click-to-load map should be rendered for this set of offices.
     *
     * Requires the feature to be switched on, a usable embed endpoint *and* at
     * least one office with a verified location, so an enabled-but-empty (or
     * misconfigured) installation still renders nothing rather than a map of
     * nowhere.
     *
     * @param  array<int, array<string, mixed>>  $offices
     */
    public static function enabled(array $offices): bool
    {
        return (bool) config('site.maps.enabled', true) && self::markers($offices) !== [];
    }

    /**
     * Whether a coordinate pair is complete and inside the valid ranges.
     */
    public static function isValidPair(mixed $latitude, mixed $longitude): bool
    {
        if (! is_numeric($latitude) || ! is_numeric($longitude)) {
            return false;
        }

        $lat = (float) $latitude;
        $lng = (float) $longitude;

        return $lat >= -90.0 && $lat <= 90.0
            && $lng >= -180.0 && $lng <= 180.0;
    }

    /**
     * A keyless "open directions" external link for one office.
     *
     * Google Maps accepts a raw coordinate pair as the destination, so this
     * needs no API key and no place id. It is always rendered, even when the
     * visitor has not loaded the embed, and it is the documented fallback when
     * the embed itself cannot be displayed.
     */
    public static function directionsUrl(float $lat, float $lng): string
    {
        return 'https://www.google.com/maps/dir/?api=1&destination='
            .self::coordinate($lat).','.self::coordinate($lng);
    }

    /**
     * The keyless Google Maps embed URL for one verified location.
     *
     * This is exactly what Google's own "Share → Embed a map" frame points at,
     * expressed as a URL: no API key, no billing account, no secret in the
     * repository. One request shows one location, which is why the component
     * renders one iframe per verified office.
     */
    protected static function embedUrl(string $endpoint, float $lat, float $lng): string
    {
        /* Built by hand rather than with http_build_query() so the coordinate
           pair keeps the literal comma Google's own embed markup uses. Every
           value here is already constrained (decimals, a clamped integer and a
           letters-and-hyphens language tag), so nothing can break the URL. */
        $query = 'q='.self::coordinate($lat).','.self::coordinate($lng)
            .'&z='.self::zoom()
            .'&hl='.self::language()
            .'&output=embed';

        return $endpoint.'?'.$query;
    }

    /**
     * The absolute embed endpoint from configuration, or null when it is not a
     * usable http(s) URL — in which case no map is rendered.
     */
    protected static function endpoint(): ?string
    {
        $endpoint = rtrim((string) config('site.maps.endpoint'), '?&#/');
        $parts = parse_url($endpoint);

        if (! isset($parts['scheme'], $parts['host'])
            || ! in_array(strtolower($parts['scheme']), ['http', 'https'], true)) {
            return null;
        }

        return $endpoint;
    }

    /**
     * The configured zoom, clamped to the range the embed URL accepts.
     */
    protected static function zoom(): int
    {
        return max(1, min(21, (int) config('site.maps.zoom', 16)));
    }

    /**
     * The configured map language, reduced to the characters a language tag
     * may contain so it can never break out of the query string.
     */
    protected static function language(): string
    {
        $language = preg_replace('/[^A-Za-z-]/', '', (string) config('site.maps.language', 'en'));

        return is_string($language) && $language !== '' ? $language : 'en';
    }

    /**
     * A coordinate as a stable decimal string, without trailing zeroes.
     */
    protected static function coordinate(float $value): string
    {
        $formatted = rtrim(rtrim(number_format($value, 7, '.', ''), '0'), '.');

        return ($formatted === '' || $formatted === '-') ? '0' : $formatted;
    }
}
