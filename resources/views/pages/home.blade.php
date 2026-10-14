@extends('layouts.app')

@push('head')
    <link rel="preload" as="image" href="{{ asset('images/home/slider/hotel-housekeeping-bed-making-hansa-management.jpg') }}" fetchpriority="high">
    <link rel="preload" as="font" type="font/woff" href="{{ asset('fonts/AvenirLTStd-Roman.woff') }}" crossorigin>
    {{-- Critical first-paint CSS (mirrors resources/css/app.css): prevents hero
         content flashing under the absolute header before the bundle arrives. --}}
    <style>
        #header{position:relative;z-index:50;background:#fff}
        body.has-hero.header_transparent #header{position:absolute;top:0;left:0;right:0;background:rgba(255,255,255,.72)}
        body.header_sticky #header,body.has-hero.header_transparent.header_sticky #header{position:fixed;left:0;top:0;right:0;width:100%;background:#fff}
        .home-hero{position:relative;height:clamp(560px,92vh,880px);display:flex;flex-direction:column;justify-content:flex-end;overflow:hidden;background:#3a3a3a}
        .home-hero__slides{position:absolute;inset:0}
        .home-hero__slide{position:absolute;inset:0;opacity:0}
        .home-hero__slide.is-active{opacity:1}
        .home-hero__slide img{width:100%;height:100%;object-fit:cover}
        .home-hero__content{position:relative;z-index:2;width:100%;max-width:1200px;margin:0 auto;padding:9rem 15px 3rem}
        @media(max-width:1024px){.home-hero{height:min(74vh,620px)}}
        @media(max-width:640px){.home-hero{height:min(62vw,300px)}.home-hero__content{padding:1rem 15px 1.25rem}}
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
