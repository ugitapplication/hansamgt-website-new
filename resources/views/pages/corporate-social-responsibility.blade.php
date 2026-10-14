@extends('layouts.app')

@section('content')
    <div class="page-wrap">
        <div class="page-grid">
            <div>
                <h1 class="page-h1">Corporate Social Responsibility — HANSA Management Ltd.</h1>
                <img src="{{ asset('images/pages/banners/CSR2.jpg') }}" alt="HANSA environmental commitment banner"
                     width="1120" height="350" loading="lazy" decoding="async" class="page-banner">
                <div class="page-body">
                    <p>The environment is our life long shelter and HANSA will never act anything harmful to the nation and earth. We are committed to serve you through ensuring green environment. HANSA is trying its level best to protect the interest of yours and the environment as well. HANSA is created for helping others and our philosophy will be remained as long as we can.</p>
                </div>
            </div>
            @include('pages.partials._sidebar-about', ['active' => 'corporate-social-responsibility'])
        </div>
    </div>
    @include('pages.home.sections._slogan')
@endsection
