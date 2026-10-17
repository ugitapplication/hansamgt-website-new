<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Google Analytics 4
    |--------------------------------------------------------------------------
    |
    | The tag is never requested until a visitor accepts analytics in the cookie
    | banner (see resources/js/consent.js). Setting GA4_ENABLED=false, or
    | emptying the measurement ID, removes the tag *and* the consent banner
    | entirely — nothing is collected, so there is nothing to consent to.
    |
    */

    'ga4' => [
        'enabled' => env('GA4_ENABLED', true),
        'measurement_id' => env('GA4_MEASUREMENT_ID', 'G-NJDKQTDFB5'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Consent record
    |--------------------------------------------------------------------------
    |
    | The visitor's choice is remembered in localStorage under the key below.
    | Bump `consent_version` whenever the cookies or the purposes change, so
    | that returning visitors are asked again instead of being covered by an
    | out-of-date decision.
    |
    */

    'consent_version' => 1,

    'consent_storage_key' => 'hansa.consent',

];
