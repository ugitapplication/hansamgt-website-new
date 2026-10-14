@extends('layouts.app')

@section('content')
    <div class="page-wrap">
        <div class="page-grid">
            <div>
                <img src="{{ asset('images/pages/banners/sb-1.jpg') }}" alt="Hospitality management services banner"
                     width="1120" height="350" loading="lazy" decoding="async" class="page-banner">
                <h1 class="page-h1 page-h1--sm">Hospitality Management Services</h1>
                <div class="page-body">
                    <p>We HANSA Management Ltd. (HML) is the only Integrated Facility Management Company in Bangladesh that provides services in international five-star standard hotels. Our hospitality partners are The Westin Dhaka, The Sheraton Dhaka and HANSA-A Premium Residence. We HML, provide experienced professional to the following streams in the hospitality management.</p>
                </div>
                <div class="svc-split">
                    <img src="{{ asset('images/pages/services/p-13.jpg') }}" alt="Hospitality Management" title="Hospitality Management"
                         width="300" height="190" loading="lazy" decoding="async">
                    <div class="svc-cols">
                        <ul>
                            <li>Risk Management &amp; Loss Prevention (Safety &amp; Security Management)</li>
                            <li>Front Office &amp; Secretarial Operations</li>
                            <li>Fitness &amp; Recreation</li>
                            <li>Spa &amp; Salon</li>
                            <li>Transport</li>
                            <li>Food &amp; Beverage Service</li>
                            <li>Culinary</li>
                        </ul>
                        <ul>
                            <li>Engineering &amp; Maintenance</li>
                            <li>Cleaning &amp; White-glove Housekeeping</li>
                            <li>Laundry &amp; Dry Cleaning</li>
                            <li>Landscaping/Gardening</li>
                            <li>Pest Control</li>
                            <li>Sales &amp; Marketing</li>
                            <li>Human Resources</li>
                        </ul>
                    </div>
                </div>
            </div>
            @include('pages.partials._sidebar-services', ['active' => 'hospitality-management'])
        </div>
    </div>
    @include('pages.home.sections._slogan')
@endsection
