<footer class="bg-black text-gray-400">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 md:grid-cols-2 lg:grid-cols-4">
        <div>
            <a href="{{ url('/') }}" aria-label="{{ \App\Support\Settings::get('legal_name') }} — home">
                <img src="{{ \App\Support\Settings::imageUrl('footer_logo') }}"
                     alt="{{ \App\Support\Settings::get('legal_name') }} logo"
                     width="180" height="64"
                     loading="lazy"
                     class="h-14 w-auto">
            </a>
            <p class="mt-4 text-sm leading-relaxed">
                {{ \App\Support\Settings::get('footer_about') }}
            </p>
        </div>

        <nav aria-label="Footer services">
            <h2 class="font-heading text-lg font-bold tracking-wide text-white">Our Services Link</h2>
            <ul class="mt-4 space-y-2 text-sm">
                <li><a class="text-hansa-gold hover:text-white hover:underline" href="{{ url('hospitality-management') }}">Hospitality Management</a></li>
                <li><a class="text-hansa-gold hover:text-white hover:underline" href="{{ url('security-fire-life-safety') }}">Security Surveillance &amp; Fire Life Safety</a></li>
                <li><a class="text-hansa-gold hover:text-white hover:underline" href="{{ url('cleaning-white-glove-housekeeping') }}">Cleaning &amp; White-Glove Housekeeping</a></li>
                <li><a class="text-hansa-gold hover:text-white hover:underline" href="{{ url('mep-mechanical-electrical-plumbing') }}">Mechanical, Electrical &amp; Plumbing</a></li>
            </ul>
        </nav>

        <nav aria-label="Footer company">
            <h2 class="font-heading text-lg font-bold tracking-wide text-white">Company</h2>
            <ul class="mt-4 space-y-2 text-sm">
                <li><a class="text-hansa-gold hover:text-white hover:underline" href="{{ url('company-profile') }}">Company Profile</a></li>
                <li><a class="text-hansa-gold hover:text-white hover:underline" href="{{ url('mission-vision') }}">Mission &amp; Vision</a></li>
                <li><a class="text-hansa-gold hover:text-white hover:underline" href="{{ url('charter-of-unique-group') }}">Charter of Unique Group</a></li>
                <li><a class="text-hansa-gold hover:text-white hover:underline" href="{{ url('management') }}">Management</a></li>
                <li><a class="text-hansa-gold hover:text-white hover:underline" href="{{ url('corporate-social-responsibility') }}">CSR</a></li>
                <li><a class="text-hansa-gold hover:text-white hover:underline" href="{{ url('careers') }}">Careers</a></li>
                <li><a class="text-hansa-gold hover:text-white hover:underline" href="{{ url('projects') }}">Projects</a></li>
                <li><a class="text-hansa-gold hover:text-white hover:underline" href="{{ url('gallery') }}">Gallery</a></li>
                <li><a class="text-hansa-gold hover:text-white hover:underline" href="{{ url('insights') }}">Insights</a></li>
            </ul>
        </nav>

        <div>
            <h2 class="font-heading text-lg font-bold tracking-wide text-white">Contact</h2>
            @php
                $pin = '<svg class="mt-0.5 h-4 w-4 shrink-0 text-hansa-gold" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>';
                $phone = '<svg class="mt-0.5 h-4 w-4 shrink-0 text-hansa-gold" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>';
                $mail = '<svg class="mt-0.5 h-4 w-4 shrink-0 text-hansa-gold" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 6l-10 7L2 6"/></svg>';
            @endphp
            <address class="mt-4 space-y-2 text-sm not-italic">
                <p class="flex items-start gap-2">{!! $pin !!}<span>{{ \App\Support\Settings::get('postal_address') }}</span></p>
                <p class="flex items-start gap-2">{!! $phone !!}<span><a class="text-hansa-gold hover:text-white hover:underline" href="{{ \App\Support\Settings::phoneHref() }}">{{ \App\Support\Settings::phoneDisplay() }}</a></span></p>
                <p class="flex items-start gap-2">{!! $mail !!}<span><a class="text-hansa-gold hover:text-white hover:underline" href="mailto:{{ \App\Support\Settings::get('email') }}">{{ \App\Support\Settings::get('email') }}</a></span></p>
            </address>
            @include('partials.social-links', ['style' => 'footer'])
        </div>
    </div>

    <div class="border-t border-white/25">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-x-6 gap-y-2 px-4 py-4 text-xs sm:px-6">
            <p>Copyright &copy; {{ date('Y') }} {{ \App\Support\Settings::get('legal_name') }}. All rights reserved.</p>
            <nav aria-label="Legal" class="flex flex-wrap items-center gap-x-4 gap-y-2">
                <a class="hover:text-white hover:underline" href="{{ url('privacy-policy') }}">Privacy Policy</a>
                @if (\App\Support\Consent::analyticsEnabled())
                    <button type="button" class="cursor-pointer hover:text-white hover:underline" data-consent-open>Cookie settings</button>
                @endif
            </nav>
            <p>Developed by Unique Group IT</p>
        </div>
    </div>
</footer>
