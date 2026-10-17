<?php

namespace App\Support;

/**
 * Answers "is there anything on this site that needs the visitor's consent?"
 *
 * Used by the layout, the banner, the footer link and the <head> so that the
 * Google Analytics tag, the consent banner and the "Cookie settings" control
 * all switch on and off together.
 */
class Consent
{
    public static function analyticsEnabled(): bool
    {
        return (bool) config('analytics.ga4.enabled')
            && filled(config('analytics.ga4.measurement_id'));
    }

    /**
     * The GA4 measurement ID, or null when analytics is switched off.
     */
    public static function measurementId(): ?string
    {
        return self::analyticsEnabled()
            ? (string) config('analytics.ga4.measurement_id')
            : null;
    }
}
