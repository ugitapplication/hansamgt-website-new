<?php

namespace App\Support;

/**
 * Answers "is there anything on this site that needs the visitor's consent?"
 *
 * Used by the layout, the banner, the footer link and the <head> so that the
 * Google Analytics tag, the consent banner and the "Cookie settings" control
 * all switch on and off together.
 *
 * The values come from the settings table (admin panel → Settings → Site
 * Settings) with the defaults in config/site.php and config/analytics.php.
 */
class Consent
{
    public static function analyticsEnabled(): bool
    {
        // Environment-level kill switch. GA4_ENABLED=false removes the tag, the
        // banner and the footer control entirely, and overrides the panel.
        if (! config('analytics.ga4.enabled')) {
            return false;
        }

        return Settings::bool('ga4_enabled') && filled(Settings::get('ga4_id'));
    }

    /**
     * The GA4 measurement ID, or null when analytics is switched off.
     */
    public static function measurementId(): ?string
    {
        return self::analyticsEnabled() ? Settings::get('ga4_id') : null;
    }

    /**
     * The consent record version. Bumped from the panel whenever the cookies or
     * the purposes change, so returning visitors are asked again instead of
     * being covered by an out-of-date decision.
     */
    public static function version(): string
    {
        return (string) Settings::get('consent_version', '1');
    }

    /**
     * The localStorage key that holds the visitor's decision.
     */
    public static function storageKey(): string
    {
        return (string) config('analytics.consent_storage_key', 'hansa.consent');
    }
}
