@extends('layouts.app')

@push('head')
    <link rel="preload" as="image" href="{{ ($heroSlides[0]['src'] ?? null) ?: asset('images/home/slider/hotel-housekeeping-bed-making-hansa-management.jpg') }}" fetchpriority="high">
    <link rel="preload" as="font" type="font/woff" href="{{ asset('fonts/AvenirLTStd-Roman.woff') }}" crossorigin>
    <style>
        #header{position:relative;z-index:50;background:#fff}
        body.has-hero.header_transparent #header{background:rgba(255,255,255,.72)}
        body.header_sticky #header{background:#fff;box-shadow:0 2px 10px rgba(0,46,91,.12)}
        body.has-hero.header_transparent.header_sticky #header{background:#fff}
        @media(min-width:1025px){#header{position:sticky;top:0}#header .header_top{height:105px}html{scroll-padding-top:105px}body.has-hero #main-content{margin-top:-105px}}
        .home-hero{position:relative;height:clamp(560px,92vh,880px);display:flex;flex-direction:column;justify-content:flex-end;overflow:hidden;background:#3a3a3a}
        .home-hero__slides{position:absolute;inset:0}
        .home-hero__slide{position:absolute;inset:0;opacity:0}
        .home-hero__slide.is-active{opacity:1}
        .home-hero__slide img{width:100%;height:100%;object-fit:cover}
        .home-hero__content{position:relative;z-index:2;width:100%;max-width:1200px;margin:0 auto;padding:9rem 15px 3rem}
        .home-hero__actions{display:flex;flex-wrap:nowrap;gap:.85rem;margin-top:1.6rem}
        .home-hero__cta{display:inline-block;flex:0 0 auto;padding:.8rem 2rem;font-weight:700;line-height:1.2;text-decoration:none;white-space:nowrap;color:#002e5b;background:#fcb000;border:2px solid #fcb000}
        .home-hero__cta--ghost{color:#fff;background:rgba(0,46,91,.72);border-color:#fff}
        @media(max-width:1024px){.home-hero{height:min(74vh,620px)}}
        @media(max-width:640px){.home-hero{height:min(62vw,300px)}.home-hero__content{padding:.5rem 15px .75rem}.home-hero__title{font-size:clamp(12px,3.6vw,16px)}.home-hero__actions{margin-top:.5rem;gap:.35rem}.home-hero__cta{flex:1 1 0;text-align:center;padding:.4rem clamp(.5rem,2.2vw,.9rem);font-size:clamp(.68rem,3vw,.8rem)}.home-hero__arrow{top:42%;width:44px;height:44px;font-size:22px}}
        @media(max-width:1024px){body.has-hero.header_transparent #header,body.has-hero.header_transparent.header_sticky #header{position:relative;background:#fff}}
    </style>
@endpush

@section('content')
    @include('pages.home.sections._hero')
    @include('pages.home.sections._slogan')
    @include('pages.home.sections._policy')
    @include('pages.home.sections._services')
    @include('pages.home.sections._numbers')
    @include('pages.home.sections._glance')
    @include('pages.home.sections._why')
    @include('pages.home.sections._clients')
    @include('pages.home.sections._projects')
    @include('pages.home.sections._concerns')
    @include('pages.home.sections._news')
    @include('pages.home.sections._contact')
    @include('pages.home.sections._offices')
    @include('pages.home.sections._rail')
@endsection
