@extends('layouts.app')

@section('content')
    <div class="page-wrap">
        <div class="page-grid">
            <div>
                <img src="{{ asset('images/pages/banners/sb-3.jpg') }}" alt="Cleaning and white-glove housekeeping services banner"
                     width="1120" height="350" loading="lazy" decoding="async" class="page-banner">
                <h1 class="page-h1 page-h1--sm">Cleaning &amp; White-Glove Housekeeping Services</h1>
                <div class="page-body">
                    <p>HML Cleaning &amp; White-glove Housekeeping Services Include:</p>
                </div>
                <div class="svc-split">
                    <img src="{{ asset('images/pages/services/p-31.jpg') }}" alt="Cleaning and housekeeping services"
                         width="300" height="190" loading="lazy" decoding="async">
                    <div class="svc-cols">
                        <ul>
                            <li>Floor Care
                                <ul>
                                    <li>Hardwood Flooring</li>
                                    <li>Vinyl or Linoleum</li>
                                    <li>Porcelain or Ceramic Tile Flooring</li>
                                    <li>Natural Stone Tile Flooring</li>
                                    <li>Spot &amp; Spill Cleaning</li>
                                </ul>
                            </li>
                            <li>Marble Crystallization</li>
                            <li>Carpet Shampooing</li>
                            <li>Upholstery Shampooing</li>
                        </ul>
                        <ul>
                            <li>Facade Cleaning</li>
                            <li>Pest Control</li>
                            <li>Odor Control</li>
                            <li>Public Area Routine Cleaning</li>
                            <li>Guest Room Routine Service</li>
                            <li>Guestroom &amp; Public Area General Cleaning</li>
                            <li>Guestroom Nightly Refresh/Turn-down Service</li>
                            <li>Disinfection &amp; Sanitization</li>
                        </ul>
                    </div>
                </div>
            </div>
            @include('pages.partials._sidebar-services', ['active' => 'cleaning-white-glove-housekeeping'])
        </div>
    </div>
    @include('pages.home.sections._slogan')
@endsection
