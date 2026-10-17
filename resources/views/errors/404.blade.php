@php
    $seo = [
        'title' => 'Page not found (404) | HANSA Management Ltd.',
        'description' => 'The page you requested could not be found. Use the menu above or contact HANSA Management Ltd.',
        'canonical' => url()->current(),
        'robots' => 'noindex, nofollow',
    ];
@endphp
@extends('layouts.app')

@section('content')
    <div class="page-wrap">
        <div class="error-page">
            <p class="error-page__code">404</p>
            <h1 class="page-h1">Page not found</h1>
            <div class="page-body">
                <p>Sorry, the page you are looking for doesn&rsquo;t exist or has been moved.</p>
                <p>
                    Please <a href="{{ url('/') }}">return to the homepage</a> or
                    <a href="{{ url('contact') }}">contact our Information Desk</a> (01708800870).
                </p>
            </div>
        </div>
    </div>
@endsection
