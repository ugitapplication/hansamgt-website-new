<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;


class Settings
{
    /**
     *
     * @var list<string>
     */
    protected const CLEARABLE_KEYS = [
        'facebook',
        'linkedin',
        'youtube',
        'twitter',
        'instagram',
        'whatsapp',
        'phone_emergency',
        'fax',
        'latitude',
        'longitude',
        'opening_hours',
    ];

    /**
     * The shared-cache key for the whole settings table.
     */
    private const CACHE_KEY = 'site-settings:v1';

    /**
     * Safety-net lifetime, in seconds.
     */
    private const CACHE_TTL = 86400;

    /**
     * Raw stored values, read once per request.
     *
     * @var array<string, string|null>|null
     */
    private static ?array $cache = null;

    /**
     * Resolved image URLs, memoised per request so a page render does not stat
     * the filesystem once per image.
     *
     * @var array<string, string>
     */
    private static array $urlCache = [];

    /**
     * @return array<string, string|null>
     */
    public static function all(): array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        $cached = self::readCached();

        if ($cached !== null) {
            return self::$cache = $cached;
        }

        try {
            /** @var array<string, string|null> $values */
            $values = Setting::query()->pluck('value', 'key')->all();
        } catch (\Throwable) {

            return self::$cache = [];
        }

        self::writeCached($values);

        return self::$cache = $values;
    }

    private static function readCached(): ?array
    {
        try {
            $cached = Cache::get(self::CACHE_KEY);
        } catch (\Throwable) {
            return null;
        }

        return is_array($cached) ? $cached : null;
    }

    /**
     * @param  array<string, string|null>  $values
     */
    private static function writeCached(array $values): void
    {
        try {
            Cache::put(self::CACHE_KEY, $values, self::CACHE_TTL);
        } catch (\Throwable) {
            // Ignored: the next request reads the database again instead.
        }
    }

    /**
     * A single setting: the stored value, otherwise the configured default.
     */
    public static function get(string $key, ?string $default = null): ?string
    {
        $stored = self::all();

        if (array_key_exists($key, $stored)) {
            $value = $stored[$key];

            if ($value === null || $value === '') {
                if (in_array($key, self::CLEARABLE_KEYS, true)) {
                    return null;
                }

                return $default ?? self::default($key);
            }

            return (string) $value;
        }

        return $default ?? self::default($key);
    }

    /**
     * Whether a setting has a usable value.
     */
    public static function filled(string $key): bool
    {
        return filled(self::get($key));
    }

    /**
     * A boolean setting stored as "1" / "0".
     */
    public static function bool(string $key, bool $default = false): bool
    {
        $value = self::all()[$key] ?? null;

        if ($value === null || $value === '') {
            $fallback = self::default($key);

            return $fallback === null ? $default : filter_var($fallback, FILTER_VALIDATE_BOOL);
        }

        return filter_var($value, FILTER_VALIDATE_BOOL);
    }

    /**
     * The value for a setting, honouring the config/site.php default.
     */
    protected static function default(string $key): ?string
    {
        $value = config('site.defaults.'.$key);

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        return $value === null ? null : (string) $value;
    }

    /**
     * Store one setting.
     */
    public static function set(string $key, ?string $value): void
    {
        Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]);

        self::flush();
    }

    /**
     * Store several settings at once; booleans are written as "1" / "0".
     *
     * @param  array<string, mixed>  $values
     */
    public static function setMany(array $values): void
    {
        foreach ($values as $key => $value) {
            if (is_bool($value)) {
                $value = $value ? '1' : '0';
            }

            Setting::query()->updateOrCreate(
                ['key' => (string) $key],
                ['value' => ($value === null || $value === '') ? null : (string) $value],
            );
        }

        self::flush();
    }

    public static function flush(): void
    {
        self::$cache = null;
        self::$urlCache = [];

        try {
            Cache::forget(self::CACHE_KEY);
        } catch (\Throwable) {
            //
        }
    }

    public static function imageUrl(string $key): string
    {
        $path = trim((string) self::get($key));

        if ($path === '') {
            return '';
        }

        if (isset(self::$urlCache[$path])) {
            return self::$urlCache[$path];
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return self::$urlCache[$path] = $path;
        }

        if (Storage::disk('public')->exists($path)) {
            return self::$urlCache[$path] = url(Storage::disk('public')->url($path));
        }

        return self::$urlCache[$path] = asset(ltrim($path, '/'));
    }

    /**
     * The primary phone number in the local display format used in the markup
     */
    public static function phoneDisplay(): ?string
    {
        $phone = self::get('phone_primary');

        if ($phone === null) {
            return null;
        }

        return preg_replace('/^\+?88\s*/', '', trim($phone)) ?: $phone;
    }

    /**
     * A tel: href for the primary phone number.
     */
    public static function phoneHref(): ?string
    {
        $phone = self::get('phone_primary');

        return $phone === null ? null : 'tel:'.preg_replace('/[^\d+]/', '', $phone);
    }

    public static function whatsappHref(): ?string
    {
        $number = self::get('whatsapp');

        if ($number === null) {
            return null;
        }

        $digits = (string) preg_replace('/[^0-9]/', '', $number);
        $digits = (string) preg_replace('/^00/', '', $digits);

        if (strlen($digits) < 8) {
            return null;
        }

        return 'https://wa.me/'.$digits;
    }

    /**
     *
     * @return array<int, array{key: string, label: string, url: string}>
     */
    public static function socialLinks(): array
    {
        $definitions = [
            'facebook' => 'Facebook',
            'linkedin' => 'LinkedIn',
            'youtube' => 'YouTube',
            'twitter' => 'X (Twitter)',
            'instagram' => 'Instagram',
        ];

        $links = [];

        foreach ($definitions as $key => $label) {
            $url = self::get($key);

            if (filled($url)) {
                $links[] = ['key' => $key, 'label' => $label, 'url' => $url];
            }
        }

        return $links;
    }

    /**
     * The `sameAs` list for the Organization structured data.
     *
     * @return array<int, string>
     */
    public static function sameAs(): array
    {
        return array_map(
            static fn (array $link): string => $link['url'],
            self::socialLinks(),
        );
    }
}
