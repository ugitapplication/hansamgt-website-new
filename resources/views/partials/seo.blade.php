@props(['seo' => []])

@php
    $seoTitle = $seo['title'] ?? 'HANSA Management Ltd. | Hospitality and Facility Management Services in Dhaka';
    $seoDescription = $seo['description'] ?? 'HANSA Management Ltd., a concern of Unique Group. Hospitality and facility management services in Dhaka. Information Desk: 01708800870.';
    $seoCanonical = $seo['canonical'] ?? url('/');
    $ogImage = asset('images/hansa_logo_new.png');
@endphp
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
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

<link rel="icon" href="{{ asset('images/hansa_logo_new.png') }}" type="image/png">
<link rel="apple-touch-icon" href="{{ asset('images/hansa_logo_new.png') }}">

<!-- Live brand font: Avenir self-hosted (declared in app.css @font-face, same file the live site uses) -->
