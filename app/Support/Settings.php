<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

/**
 * Reads the editable site settings held in the `settings` table.
 *
 * Every key has a default in config/site.php, so the site behaves exactly as it
 * did before the settings module existed whenever the table is empty, not yet
 * migrated, or unreachable. Nothing here ever throws into a page render.
 */
class Settings
{
    /**
     * Keys whose default is a real value but which the panel may legitimately
     * empty.
     *
     * For these, an empty stored value means "intentionally blank" rather than
     * "fall back to the default" — otherwise clearing the field in the panel
     * would silently restore the very value it was meant to remove (the social
     * profiles and the fax number both have a real default).
     *
     * Image settings are deliberately *not* here: their default is the bundled
     * HANSA logo, and clearing an upload should go back to it.
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
     * Every stored value, keyed by setting name.
     *
     * @return array<string, string|null>
     */
    public static function all(): array
    {
        if (self::$cache === null) {
            try {
                /** @var array<string, string|null> $values */
                $values = Setting::query()->pluck('value', 'key')->all();
                self::$cache = $values;
            } catch (\Throwable) {
                // Table not migrated yet, or the database is unreachable. The
                // defaults in config/site.php take over.
                self::$cache = [];
            }
        }

        return self::$cache;
    }

    /**
     * A single setting: the stored value, otherwise the configured default.
     *
     * A key that has been cleared in the panel resolves to null for the
     * clearable keys (see CLEARABLE_KEYS), and to its default for everything
     * else.
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

    /**
     * Drop the per-request cache (called after any write).
     */
    public static function flush(): void
    {
        self::$cache = null;
        self::$urlCache = [];
    }

    /**
     * A web-accessible URL for an image setting.
     *
     * Uploads made from the panel live on the "public" disk
     * (storage/app/public); images shipped with the application live under
     * public/ directly, e.g. "images/hansa_logo_new.png". Both resolve here.
     */
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
     * (no country code), derived from the single stored value.
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

    /**
     * The social profiles that actually have a URL, in display order.
     *
     * Profiles with no URL are omitted entirely, so a placeholder link can never
     * be published by accident.
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
