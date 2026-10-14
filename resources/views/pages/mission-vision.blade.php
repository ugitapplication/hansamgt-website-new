@extends('layouts.app')

@section('content')
    <div class="page-wrap">
        <div class="page-grid">
            <div>
                <h1 class="page-h1">Mission &amp; Vision — HANSA Management Ltd.</h1>
                <img src="{{ asset('images/pages/banners/mission2.jpg') }}" alt="HANSA Management mission and vision banner"
                     width="1120" height="350" loading="lazy" decoding="async" class="page-banner">
                <div class="mv-grid">
                    <article class="mv-card">
                        <img src="{{ asset('images/pages/icons/mission-1-120x111.png') }}" alt="mission" title="mission"
                             width="120" height="111" loading="lazy" decoding="async">
                        <h2>Mission</h2>
                        <p>To adopt best-in-class service techniques by keeping optimum use of resource to ensure highest level of customer satisfaction.</p>
                    </article>
                    <article class="mv-card">
                        <img src="{{ asset('images/pages/icons/vision-1-111x111.png') }}" alt="vision" title="vision"
                             width="111" height="111" loading="lazy" decoding="async">
                        <h2>Vision</h2>
                        <p>To be the most aspired international standard one-stop Hospitality &amp; Facility Management service provider in Bangladesh.</p>
                    </article>
                </div>
            </div>
            @include('pages.partials._sidebar-about', ['active' => 'mission-vision'])
        </div>
    </div>
    @include('pages.home.sections._slogan')
@endsection
