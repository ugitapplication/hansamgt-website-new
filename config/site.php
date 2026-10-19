<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Editable site settings — defaults
    |--------------------------------------------------------------------------
    |
    | Every value below can be overridden from the admin panel under
    | Settings → Site Settings (app/Filament/Pages/ManageSettings.php). These
    | defaults are used when the `settings` table is empty or has no value for a
    | key, so the public site keeps working before the panel is populated.
    |
    | Values here are public by definition — telephone numbers, social profiles,
    | a Google Analytics id. Never add a secret: mail credentials, APP_KEY and
    | database credentials belong in .env. They are not editable from the panel
    | on purpose, because a database row is plaintext, readable by every admin,
    | and stolen along with any database leak.
    |
    | Social profiles left as null are simply not rendered, which is why the two
    | placeholder links the old footer/contact page carried (`#`) no longer
    | appear anywhere.
    |
    */

    'defaults' => [

        // ---- Branding -----------------------------------------------------
        'logo' => 'images/hansa_logo_new.png',
        'footer_logo' => 'images/hansa_logo_new.png',
        'favicon' => 'images/hansa_logo_new.png',
        'og_image' => 'images/hansa_logo_new.png',

        // ---- Organisation (also feeds the schema.org Organization) --------
        'legal_name' => 'HANSA Management Ltd.',
        'site_url' => 'https://hansamgt.com/',
        'description' => 'HANSA Management Ltd. is an integrated hospitality and facility management company in Bangladesh, a concern of Unique Group.',
        'footer_about' => 'HANSA Management Ltd. is an integrated hospitality & facility management company in Bangladesh, providing a full range of services.',
        'parent_name' => 'Unique Group',
        'parent_url' => 'https://www.uniquegroupbd.com/',
        'founding_year' => '2015',
        'latitude' => null,
        'longitude' => null,
        'opening_hours' => null,

        // ---- Contact ------------------------------------------------------
        'phone_primary' => '+8801708800870',
        'phone_emergency' => null,
        'whatsapp' => null,
        // The floating click-to-chat button (partials/whatsapp-float.blade.php).
        // The number above is the real gate: with it empty nothing is ever
        // rendered, so this can safely default to on for the day a number is
        // entered, and the panel switch turns the button off without having to
        // delete the number.
        'whatsapp_enabled' => true,
        'fax' => '88-02-8823392',
        'email' => 'info@hansamgt.com',
        'enquiry_recipient' => 'info@hansamgt.com',
        'postal_address' => 'Borak Mehnur (Level-18), 51/B Kemal Ataturk Avenue, Banani, Dhaka 1213',

        // The address broken into the parts schema.org expects.
        'street_address' => 'Borak Mehnur (Level-18), 51/B Kemal Ataturk Avenue, Banani',
        'address_locality' => 'Dhaka',
        'postal_code' => '1213',
        'address_country' => 'BD',

        // ---- Social profiles (leave empty to hide the icon) ---------------
        'facebook' => 'https://www.facebook.com/HANSAManagementLtd/',
        'linkedin' => 'https://www.linkedin.com/in/hansa-management-ltd-222838196/',
        'youtube' => null,
        'twitter' => null,
        'instagram' => null,

        // ---- Analytics & consent -----------------------------------------
        // GA4_ENABLED=false in .env is a hard switch: it removes the tag, the
        // banner and the footer control, and overrides whatever the panel says.
        'ga4_enabled' => env('GA4_ENABLED', true) ? '1' : '0',
        'ga4_id' => env('GA4_MEASUREMENT_ID', 'G-NJDKQTDFB5'),

        // Bump when the cookies or the purposes change, so returning visitors
        // are asked again instead of being covered by an out-of-date decision.
        'consent_version' => (string) env('CONSENT_VERSION', 1),

        // ---- Legal --------------------------------------------------------
        'privacy_updated' => '2026-10-17',

    ],

    /*
    |--------------------------------------------------------------------------
    | Office map (Google Maps embed)
    |--------------------------------------------------------------------------
    |
    | The map is only rendered when at least one office has a verified
    | latitude/longitude pair (admin → Offices). With no verified coordinates
    | nothing is output at all, so the page can never show an invented or
    | approximate location. The legacy site's coordinates were placeholders and
    | are deliberately not reused; see docs/maps-audit-and-plan.md.
    |
    | OFFICE_MAP_ENABLED=false switches the whole feature off and removes the
    | Google iframe origin from the Content-Security-Policy.
    |
    | 'endpoint' is the keyless Google Maps embed URL — the same one Google's
    | own "Share → Embed a map" produces. It needs no API key, no billing
    | account and no secret in this repository, and no JavaScript library: the
    | map is an ordinary iframe, and the address bar of that iframe is built by
    | App\Support\OfficeMap. Its origin is the only frame-src source the
    | middleware adds.
    |
    | 'zoom' is clamped to 1…21 by OfficeMap; 'language' is stripped to
    | letters and hyphens, and both are only ever emitted as query parameters.
    |
    */

    'maps' => [
        'enabled' => env('OFFICE_MAP_ENABLED', true),
        'endpoint' => env('OFFICE_MAP_ENDPOINT', 'https://www.google.com/maps'),
        'zoom' => (int) env('OFFICE_MAP_ZOOM', 16),
        'language' => (string) env('OFFICE_MAP_LANGUAGE', 'en'),
    ],

];
