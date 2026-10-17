@props(['seo' => []])

@php
    $seoTitle = $seo['title'] ?? 'HANSA Management Ltd. | Hospitality and Facility Management Services in Dhaka';
    $seoDescription = $seo['description'] ?? 'HANSA Management Ltd., a concern of Unique Group. Hospitality and facility management services in Dhaka. Information Desk: 01708800870.';
    $seoCanonical = $seo['canonical'] ?? url('/');
    $ogImage = asset('images/hansa_logo_new.png');
@endphp
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
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
<meta property="og:type" content="website">
<meta property="og:site_name" content="HANSA Management Ltd.">
<meta property="og:url" content="{{ $seoCanonical }}">
<meta property="og:title" content="{{ $seoTitle }}">
<meta property="og:description" content="{{ $seoDescription }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:image:alt" content="HANSA Management Ltd. logo">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seoTitle }}">
<meta name="twitter:description" content="{{ $seoDescription }}">
<meta name="twitter:image" content="{{ $ogImage }}">

<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@type": "Organization",
    "name": "HANSA Management Ltd.",
    "url": "https://hansamgt.com/",
    "logo": "{{ $ogImage }}",
    "description": "HANSA Management Ltd. is an integrated hospitality and facility management company in Bangladesh, a concern of Unique Group.",
    "telephone": "+8801708800870",
    "email": "info@hansamgt.com",
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "Borak Mehnur (Level-18), 51/B Kemal Ataturk Avenue, Banani",
        "addressLocality": "Dhaka",
        "postalCode": "1213",
        "addressCountry": "BD"
    },
    "parentOrganization": {
        "@type": "Organization",
        "name": "Unique Group",
        "url": "https://www.uniquegroupbd.com/"
    },
    "sameAs": [
        "https://www.facebook.com/HANSAManagementLtd/",
        "https://www.linkedin.com/in/hansa-management-ltd-222838196/"
    ]
}
</script>

<link rel="icon" href="{{ asset('images/hansa_logo_new.png') }}" type="image/png">
<link rel="apple-touch-icon" href="{{ asset('images/hansa_logo_new.png') }}">

<!-- Live brand font: Avenir self-hosted (declared in app.css @font-face, same file the live site uses) -->
