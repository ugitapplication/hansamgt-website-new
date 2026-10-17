<footer class="bg-black text-gray-400">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 md:grid-cols-2 lg:grid-cols-4">
        <div>
            <a href="{{ url('/') }}" aria-label="HANSA Management Ltd. — home">
                <img src="{{ asset('images/hansa_logo_new.png') }}"
                     alt="HANSA Management Ltd. logo"
                     width="180" height="64"
                     loading="lazy"
                     class="h-14 w-auto">
            </a>
            <p class="mt-4 text-sm leading-relaxed">
                HANSA Management Ltd. is an integrated hospitality &amp; facility management company in Bangladesh, providing a full range of services.
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
            <address class="mt-4 space-y-2 text-sm not-italic">
                <p>Borak Mehnur (Level-18), 51/B Kemal Ataturk Avenue, Banani, Dhaka 1213</p>
                <p><a class="text-hansa-gold hover:text-white hover:underline" href="tel:+8801708800870">01708800870</a></p>
                <p><a class="text-hansa-gold hover:text-white hover:underline" href="mailto:info@hansamgt.com">info@hansamgt.com</a></p>
            </address>
            <ul class="mt-4 flex gap-3">
                <li>
                    <a href="https://www.facebook.com/HANSAManagementLtd/" target="_blank" rel="noopener"
                       aria-label="HANSA on Facebook" title="Facebook"
                       class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white text-black transition-colors duration-300 hover:bg-[#1877F2] hover:text-white">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                </li>
                <li>
                    <a href="https://www.linkedin.com/in/hansa-management-ltd-222838196/" target="_blank" rel="noopener"
                       aria-label="HANSA on LinkedIn" title="LinkedIn"
                       class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white text-black transition-colors duration-300 hover:bg-[#0A66C2] hover:text-white">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    </a>
                </li>
                <li>
                    <a href="#" target="_blank" rel="noopener"
                       aria-label="HANSA on YouTube" title="YouTube"
                       class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white text-black transition-colors duration-300 hover:bg-[#FF0000] hover:text-white">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="border-t border-white/25">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-x-6 gap-y-2 px-4 py-4 text-xs sm:px-6">
            <p>Copyright &copy; {{ date('Y') }} HANSA Management Ltd. All rights reserved.</p>
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
