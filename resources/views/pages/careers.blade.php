@extends('layouts.app')

@section('content')
    <div class="page-wrap">
        <div class="page-grid">
            <div>
                <h1 class="page-h1">Careers — HANSA Management Ltd.</h1>
                <img src="{{ asset('images/home/slider/hansa-management-team-group-photo.jpg') }}"
                     alt="HANSA Management team members together"
                     loading="lazy" decoding="async" class="page-banner">
                <div class="page-body">
                    <p>HANSA Management Ltd. is one of Bangladesh&rsquo;s leading integrated hospitality and facility management companies and a concern of Unique Group. With more than 1,000 employees delivering service round the clock, we are always looking for energetic, service-minded people who take pride in looking after the places where people live, work and stay.</p>
                </div>

                <h2 class="page-h2">Why build your career at HANSA</h2>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="border-l-4 border-hansa-gold bg-gray-50 p-4">
                        <h3 class="font-heading text-base font-bold text-hansa-navy">A leading integrated provider</h3>
                        <p class="mt-1 text-sm leading-relaxed text-slate-600">Join the country&rsquo;s leading integrated hospitality &amp; facility management company, part of Unique Group.</p>
                    </div>
                    <div class="border-l-4 border-hansa-gold bg-gray-50 p-4">
                        <h3 class="font-heading text-base font-bold text-hansa-navy">Work with world-class clients</h3>
                        <p class="mt-1 text-sm leading-relaxed text-slate-600">Our people serve international five-star hotels including The Westin Dhaka and Sheraton Dhaka, and HANSA &ndash; A Premium Residence.</p>
                    </div>
                    <div class="border-l-4 border-hansa-gold bg-gray-50 p-4">
                        <h3 class="font-heading text-base font-bold text-hansa-navy">Training centre with expert trainers</h3>
                        <p class="mt-1 text-sm leading-relaxed text-slate-600">We invest in our people through a well-equipped training centre with expert trainers.</p>
                    </div>
                    <div class="border-l-4 border-hansa-gold bg-gray-50 p-4">
                        <h3 class="font-heading text-base font-bold text-hansa-navy">Many career paths</h3>
                        <p class="mt-1 text-sm leading-relaxed text-slate-600">From hospitality and front office to security, cleaning and engineering, our one-stop service offers a wide range of roles.</p>
                    </div>
                    <div class="border-l-4 border-hansa-gold bg-gray-50 p-4">
                        <h3 class="font-heading text-base font-bold text-hansa-navy">24/7 operations across Dhaka</h3>
                        <p class="mt-1 text-sm leading-relaxed text-slate-600">With a corporate office in Banani and service booths across the city, opportunities are close to home.</p>
                    </div>
                    <div class="border-l-4 border-hansa-gold bg-gray-50 p-4">
                        <h3 class="font-heading text-base font-bold text-hansa-navy">Safety-first culture</h3>
                        <p class="mt-1 text-sm leading-relaxed text-slate-600">We apply an absolute safety policy to all employees in every activity and project.</p>
                    </div>
                </div>

                <h2 class="page-h2">Areas we recruit for</h2>
                <div class="svc-cols">
                    <ul class="svc-list">
                        <li><strong>Hospitality Management</strong> — front office, food &amp; beverage service, culinary, housekeeping, laundry and landscaping.</li>
                        <li><strong>Security Surveillance, Fire Life Safety &amp; Risk Management</strong> — security officers, fire &amp; life safety, investigations, and parking &amp; traffic management.</li>
                        <li><strong>Cleaning &amp; White-Glove Housekeeping</strong> — floor care, marble crystallization, carpet care, fa&ccedil;ade cleaning, pest control and public-area cleaning.</li>
                    </ul>
                    <ul class="svc-list">
                        <li><strong>MEP (Mechanical, Electrical &amp; Plumbing)</strong> — air conditioning, water supply &amp; drainage, firefighting, elevator, CCTV/BMS and water treatment.</li>
                        <li><strong>Corporate &amp; support</strong> — administration, human resources, finance, procurement and client relations at our corporate office.</li>
                    </ul>
                </div>

                <h2 class="page-h2">How to apply</h2>
                <div class="page-body">
                    <p>We continuously recruit for our operations across Dhaka. To apply, email your CV to <a class="text-hansa-navy hover:underline" href="mailto:{{ \App\Support\Settings::get('email') }}">{{ \App\Support\Settings::get('email') }}</a> stating the position or department you are interested in, or call our information desk on <a class="text-hansa-navy hover:underline" href="{{ \App\Support\Settings::phoneHref() }}">{{ \App\Support\Settings::phoneDisplay() }}</a>. Shortlisted candidates will be contacted for an interview.</p>
                </div>
            </div>
            <aside aria-label="Apply to HANSA">
                <div class="side-cta">
                    <h2>Join our team</h2>
                    <p>Send your CV to <a href="mailto:{{ \App\Support\Settings::get('email') }}">{{ \App\Support\Settings::get('email') }}</a> or call <a href="{{ \App\Support\Settings::phoneHref() }}">{{ \App\Support\Settings::phoneDisplay() }}</a>.</p>
                    <p><a class="home-submit" href="mailto:{{ \App\Support\Settings::get('email') }}">Email your CV</a></p>
                </div>
            </aside>
        </div>
    </div>
    @include('pages.home.sections._slogan')
@endsection
