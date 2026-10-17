<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SEO defaults
    |--------------------------------------------------------------------------
    |
    | The organisation facts that used to live here — legal name, phone, email,
    | logo, social profiles and the address — are now editable from the admin
    | panel under Settings → Site Settings. Their defaults live in
    | config/site.php and are read through app/Support/Settings.php, so there is
    | a single source of truth for the values that were previously duplicated
    | across the header, the footer, the contact page and this file.
    |
    */

    'default_robots' => 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',

];
