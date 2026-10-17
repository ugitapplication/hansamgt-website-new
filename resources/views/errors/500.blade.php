@php
    $seo = [
        'title' => 'Something went wrong (500) | HANSA Management Ltd.',
        'description' => 'An unexpected error occurred. Please try again or contact HANSA Management Ltd.',
        'canonical' => url()->current(),
        'robots' => 'noindex, nofollow',
    ];
@endphp
@extends('layouts.app')

@section('content')
    <div class="page-wrap">
        <div class="error-page">
            <p class="error-page__code">500</p>
            <h1 class="page-h1">Something went wrong</h1>
            <div class="page-body">
                <p>Sorry, we hit an unexpected problem while loading this page.</p>
                <p>
                    Please try again in a moment, or <a href="{{ url('contact') }}">contact our Information Desk</a>
                    @php($phone = \App\Support\Settings::phoneDisplay())@if ($phone)({{ $phone }})@endif You can also <a href="{{ url('/') }}">return to the homepage</a>.
                </p>
            </div>
        </div>
    </div>
@endsection
