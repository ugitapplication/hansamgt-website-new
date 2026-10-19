<?php

namespace App\Support;

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
     */
    public static function directionsUrl(float $lat, float $lng): string
    {
        return 'https://www.google.com/maps/dir/?api=1&destination='
            .self::coordinate($lat).','.self::coordinate($lng);
    }

    /**
     * The keyless Google Maps embed URL for one verified location.
     */
    protected static function embedUrl(string $endpoint, float $lat, float $lng): string
    {
        $query = 'q='.self::coordinate($lat).','.self::coordinate($lng)
            .'&z='.self::zoom()
            .'&hl='.self::language()
            .'&output=embed';

        return $endpoint.'?'.$query;
    }

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
