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
    $ogImage = isset($seo['image']) ? asset($seo['image']) : asset(config('seo.default_image'));

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
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-NJDKQTDFB5"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-NJDKQTDFB5');
</script>

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

<script type="application/ld+json">
{!! $jsonLd !!}
</script>

<link rel="icon" href="{{ asset('images/hansa_logo_new.png') }}" type="image/png">
<link rel="apple-touch-icon" href="{{ asset('images/hansa_logo_new.png') }}">
