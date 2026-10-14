@extends('layouts.app')

@section('content')
    <div class="page-wrap">
        <div class="page-grid">
            <div>
                <img src="{{ asset('images/pages/banners/sb-2.jpg') }}" alt="Security surveillance and fire life safety services banner"
                     width="1120" height="350" loading="lazy" decoding="async" class="page-banner">
                <h1 class="page-h1 page-h1--sm">Security Surveillance, Fire Life Safety &amp; Risk Management</h1>
                <div class="page-body">
                    <p>HML Security Surveillance, Fire Life Safety and Risk Management Services Include</p>
                </div>
                <div class="svc-split">
                    <img src="{{ asset('images/pages/services/p-30.jpg') }}" alt="Security and surveillance training" title="SoftServiceTraining"
                         width="300" height="190" loading="lazy" decoding="async">
                    <div class="page-body">
                        <h2 class="page-h2">Fire Life Safety</h2>
                        <ul class="svc-list">
                            <li>Fire Alarm System Monitoring</li>
                            <li>Fire Evacuation System &amp; Management</li>
                        </ul>
                        <h2 class="page-h2">Risk Management</h2>
                        <ul class="svc-list">
                            <li>Developing Loss Prevention Procedures</li>
                            <li>Emergency/Crisis Management &amp; Recovery Plan</li>
                            <li>First Aid Program</li>
                            <li>Weapons Handling</li>
                            <li>Automated External Defibrillator (AED) Program</li>
                        </ul>
                        <h2 class="page-h2">Investigations</h2>
                        <ul class="svc-list">
                            <li>Covert Surveillance</li>
                            <li>Intelligence Gathering</li>
                            <li>Law Enforcement Agency Liaison</li>
                            <li>Workplace Violence</li>
                        </ul>
                        <h2 class="page-h2">Operations</h2>
                        <ul class="svc-list">
                            <li>Parking &amp; Traffic Management</li>
                            <li>Key Control</li>
                            <li>Associate &amp; Guest Entrance &amp; Exit Controlling</li>
                            <li>Biohazardous &amp; Infectious Materials Handling</li>
                        </ul>
                    </div>
                </div>
            </div>
            @include('pages.partials._sidebar-services', ['active' => 'security-fire-life-safety'])
        </div>
    </div>
    @include('pages.home.sections._slogan')
@endsection
