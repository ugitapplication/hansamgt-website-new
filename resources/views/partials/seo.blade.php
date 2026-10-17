@php
    $seo = $seo ?? [];
    $breadcrumbs = $breadcrumbs ?? [];

    $org = config('seo.organization');
    $siteUrl = rtrim($org['url'], '/');
    $lang = str_replace('_', '-', app()->getLocale());

    $seoTitle = $seo['title'] ?? 'HANSA Management Ltd. | Hospitality and Facility Management Services in Dhaka';
    $seoDescription = $seo['description'] ?? 'HANSA Management Ltd., a concern of Unique Group. Hospitality and facility management services in Dhaka. Information Desk: 01708800870.';
    $seoCanonical = $seo['canonical'] ?? url('/');
    $seoType = $seo['type'] ?? 'website';
    $ogImage = ! empty($seo['image'])
        ? (str_starts_with($seo['image'], 'http') ? $seo['image'] : asset($seo['image']))
        : asset(config('seo.default_image'));

    // Local and staging environments must never be indexed, no matter what a
    // page asks for. Production honours the per-page value.
    $robots = app()->isProduction()
        ? ($seo['robots'] ?? config('seo.default_robots'))
        : 'noindex, nofollow';

    // ---- Structured data (schema.org @graph) ------------------------------

    $organization = [
        '@type' => ['Organization', 'LocalBusiness'],
        '@id' => $siteUrl.'/#organization',
        'name' => $org['name'],
        'url' => $org['url'],
        'logo' => asset($org['logo']),
        'image' => asset($org['logo']),
        'description' => $org['description'],
        'telephone' => $org['telephone'],
        'email' => $org['email'],
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => $org['street_address'],
            'addressLocality' => $org['address_locality'],
            'postalCode' => $org['postal_code'],
            'addressCountry' => $org['address_country'],
        ],
        'parentOrganization' => [
            '@type' => 'Organization',
            'name' => $org['parent']['name'],
            'url' => $org['parent']['url'],
        ],
        'sameAs' => $org['same_as'],
    ];

    $graph = [
        $organization,
        [
            '@type' => 'WebSite',
            '@id' => $siteUrl.'/#website',
            'url' => $org['url'],
            'name' => $org['name'],
            'publisher' => ['@id' => $siteUrl.'/#organization'],
            'inLanguage' => $lang,
        ],
        [
            '@type' => 'WebPage',
            '@id' => $seoCanonical.'#webpage',
            'url' => $seoCanonical,
            'name' => $seoTitle,
            'description' => $seoDescription,
            'isPartOf' => ['@id' => $siteUrl.'/#website'],
            'about' => ['@id' => $siteUrl.'/#organization'],
            'inLanguage' => $lang,
        ],
    ];

    if (count($breadcrumbs) > 1) {
        $breadcrumbItems = [];
        foreach ($breadcrumbs as $index => $crumb) {
            $item = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $crumb['label'],
            ];
            if (! empty($crumb['url'])) {
                $item['item'] = $crumb['url'];
            }
            $breadcrumbItems[] = $item;
        }
        $graph[] = [
            '@type' => 'BreadcrumbList',
            '@id' => $seoCanonical.'#breadcrumb',
            'itemListElement' => $breadcrumbItems,
        ];
    }

    if (! empty($seo['service'])) {
        $service = $seo['service'];
        $graph[] = [
            '@type' => 'Service',
            '@id' => $seoCanonical.'#service',
            'name' => $service['name'],
            'serviceType' => $service['type'] ?? $service['name'],
            'description' => $service['description'] ?? $seoDescription,
            'url' => $seoCanonical,
            'provider' => ['@id' => $siteUrl.'/#organization'],
            'areaServed' => ['@type' => 'Country', 'name' => 'Bangladesh'],
        ];
    }

    foreach (($seo['schema'] ?? []) as $extra) {
        $graph[] = $extra;
    }

    $jsonLd = json_encode(
        ['@context' => 'https://schema.org', '@graph' => $graph],
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG
    );
@endphp
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="{{ $robots }}">
{{-- Google Analytics 4 — consent-gated.
     The consent defaults are declared before anything else, and the gtag.js
     library itself is only requested once the visitor accepts analytics
     (resources/js/consent.js). Until then nothing is loaded from Google and
     nothing is sent to Google — not even a cookieless ping. --}}
@if (\App\Support\Consent::analyticsEnabled())
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('consent', 'default', {
        'ad_storage': 'denied',
        'ad_user_data': 'denied',
        'ad_personalization': 'denied',
        'analytics_storage': 'denied',
        'wait_for_update': 500
    });
    gtag('set', 'ads_data_redaction', true);
    window.hansaAnalytics = { id: @json(\App\Support\Consent::measurementId()) };
</script>
@endif

<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ $seoTitle }}</title>
<meta name="description" content="{{ $seoDescription }}">
<link rel="canonical" href="{{ $seoCanonical }}">

<meta property="og:locale" content="en_US">
<meta property="og:type" content="{{ $seoType }}">
<meta property="og:site_name" content="{{ $org['name'] }}">
<meta property="og:url" content="{{ $seoCanonical }}">
<meta property="og:title" content="{{ $seoTitle }}">
<meta property="og:description" content="{{ $seoDescription }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:image:alt" content="{{ $org['name'] }} logo">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seoTitle }}">
<meta name="twitter:description" content="{{ $seoDescription }}">
<meta name="twitter:image" content="{{ $ogImage }}">
@if (! empty($seo['published_time']))
<meta property="article:published_time" content="{{ $seo['published_time'] }}">
@endif
@if (! empty($seo['modified_time']))
<meta property="article:modified_time" content="{{ $seo['modified_time'] }}">
@endif
@if (! empty($seo['feed']))
<link rel="alternate" type="application/rss+xml" title="{{ $seoTitle }}" href="{{ $seo['feed'] }}">
@endif

<script type="application/ld+json">
{!! $jsonLd !!}
</script>

<link rel="icon" href="{{ asset('images/hansa_logo_new.png') }}" type="image/png">
<link rel="apple-touch-icon" href="{{ asset('images/hansa_logo_new.png') }}">
