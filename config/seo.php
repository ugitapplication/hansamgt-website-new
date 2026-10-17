<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Organisation / Local business
    |--------------------------------------------------------------------------
    |
    | Single source of truth for the schema.org entity used across the site
    | (Organization + LocalBusiness). Only verified facts live here — never
    | invent geo coordinates, opening hours, ratings or certifications.
    |
    */

    'organization' => [
        'name' => 'HANSA Management Ltd.',
        'url' => 'https://hansamgt.com/',
        'logo' => 'images/hansa_logo_new.png',
        'description' => 'HANSA Management Ltd. is an integrated hospitality and facility management company in Bangladesh, a concern of Unique Group.',
        'telephone' => '+8801708800870',
        'email' => 'info@hansamgt.com',
        'street_address' => 'Borak Mehnur (Level-18), 51/B Kemal Ataturk Avenue, Banani',
        'address_locality' => 'Dhaka',
        'postal_code' => '1213',
        'address_country' => 'BD',
        'parent' => [
            'name' => 'Unique Group',
            'url' => 'https://www.uniquegroupbd.com/',
        ],
        'same_as' => [
            'https://www.facebook.com/HANSAManagementLtd/',
            'https://www.linkedin.com/in/hansa-management-ltd-222838196/',
        ],
        // Pending from the client (intentionally omitted until supplied):
        // 'latitude', 'longitude', 'opening_hours' — needed for richer
        // LocalBusiness rich results.
    ],

    /*
    |--------------------------------------------------------------------------
    | Defaults
    |--------------------------------------------------------------------------
    */

    'default_image' => 'images/hansa_logo_new.png',

    'default_robots' => 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',

];
