@extends('layouts.app')

@section('content')
    <div class="page-wrap">
        <div class="page-grid">
            <div>
                <img src="{{ asset('images/pages/banners/sb-4.jpg') }}" alt="MEP engineering services banner"
                     width="1120" height="350" loading="lazy" decoding="async" class="page-banner">
                <h1 class="page-h1 page-h1--sm">MEP — Mechanical, Electrical &amp; Plumbing Services</h1>
                <div class="page-body">
                    <p>HML MEP Services Include:</p>
                </div>
                <div class="svc-split">
                    <img src="{{ asset('images/pages/services/p-32.jpg') }}" alt="MEP engineering services"
                         width="300" height="190" loading="lazy" decoding="async">
                    <div class="svc-cols">
                        <ul>
                            <li>Installing Air Conditioning Systems</li>
                            <li>Water Supply &amp; Drainage Systems</li>
                            <li>Firefighting System</li>
                            <li>Transformer Substations &amp; Emergency Power Generators</li>
                            <li>Elevator</li>
                            <li>Fire Protection &amp; Alarm Systems</li>
                            <li>IT Server</li>
                            <li>PABX</li>
                            <li>Security Control Room (CCTV)</li>
                            <li>Building Management System (BMS)</li>
                            <li>Fire Pump</li>
                        </ul>
                        <ul>
                            <li>Water Treatment Plant including Reverse Osmosis System</li>
                            <li>Sound Transmission Class (STP)</li>
                            <li>Effluent Treatment Plant (ETP)</li>
                            <li>Voice &amp; Data Systems</li>
                            <li>UPS</li>
                            <li>Steam Boiler</li>
                            <li>Hot Water Boiler</li>
                            <li>Pool Plant</li>
                            <li>Booster Pump Set</li>
                            <li>Cooling Tower</li>
                            <li>Chiller</li>
                        </ul>
                    </div>
                </div>
            </div>
            @include('pages.partials._sidebar-services', ['active' => 'mep-mechanical-electrical-plumbing'])
        </div>
    </div>
    @include('pages.home.sections._slogan')
@endsection
