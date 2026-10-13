<footer class="bg-hansa-navy text-slate-200">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 md:grid-cols-2 lg:grid-cols-4">
        <div>
            <a href="{{ url('/') }}" aria-label="HANSA Management Ltd. — home">
                <img src="{{ asset('images/hansa_logo_new.png') }}"
                     alt="HANSA Management Ltd. logo"
                     width="180" height="64"
                     loading="lazy"
                     class="h-14 w-auto rounded bg-white p-1">
            </a>
            <p class="mt-4 text-sm leading-relaxed">
                HANSA Management Ltd. is an integrated hospitality &amp; facility management company in Bangladesh, providing a full range of services.
            </p>
        </div>

        <nav aria-label="Footer services">
            <h2 class="font-heading text-sm font-bold uppercase tracking-wider text-white">Our Services</h2>
            <ul class="mt-4 space-y-2 text-sm">
                <li><a class="hover:text-hansa-gold hover:underline" href="{{ url('hospitality-management') }}">Hospitality Management</a></li>
                <li><a class="hover:text-hansa-gold hover:underline" href="{{ url('security-fire-life-safety') }}">Security Surveillance &amp; Fire Life Safety</a></li>
                <li><a class="hover:text-hansa-gold hover:underline" href="{{ url('cleaning-white-glove-housekeeping') }}">Cleaning &amp; White-Glove Housekeeping</a></li>
                <li><a class="hover:text-hansa-gold hover:underline" href="{{ url('mep-mechanical-electrical-plumbing') }}">Mechanical, Electrical &amp; Plumbing</a></li>
            </ul>
        </nav>

        <nav aria-label="Footer company">
            <h2 class="font-heading text-sm font-bold uppercase tracking-wider text-white">Company</h2>
            <ul class="mt-4 space-y-2 text-sm">
                <li><a class="hover:text-hansa-gold hover:underline" href="{{ url('company-profile') }}">Company Profile</a></li>
                <li><a class="hover:text-hansa-gold hover:underline" href="{{ url('mission-vision') }}">Mission &amp; Vision</a></li>
                <li><a class="hover:text-hansa-gold hover:underline" href="{{ url('charter-of-unique-group') }}">Charter of Unique Group</a></li>
                <li><a class="hover:text-hansa-gold hover:underline" href="{{ url('management') }}">Management</a></li>
                <li><a class="hover:text-hansa-gold hover:underline" href="{{ url('corporate-social-responsibility') }}">CSR</a></li>
                <li><a class="hover:text-hansa-gold hover:underline" href="{{ url('projects') }}">Projects</a></li>
                <li><a class="hover:text-hansa-gold hover:underline" href="{{ url('gallery') }}">Gallery</a></li>
            </ul>
        </nav>

        <div>
            <h2 class="font-heading text-sm font-bold uppercase tracking-wider text-white">Contact</h2>
            <address class="mt-4 space-y-2 text-sm not-italic">
                <p>Borak Mehnur (Level-18), 51/B Kemal Ataturk Avenue, Banani, Dhaka 1213</p>
                <p><a class="hover:text-hansa-gold hover:underline" href="tel:+8801708800870">01708800870</a></p>
                <p><a class="hover:text-hansa-gold hover:underline" href="mailto:info@hansamgt.com">info@hansamgt.com</a></p>
            </address>
            <ul class="mt-4 flex gap-3">
                <li>
                    <a href="https://www.facebook.com/HANSAManagementLtd/" target="_blank" rel="noopener"
                       aria-label="HANSA on Facebook"
                       class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/10 hover:bg-white/20">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M13.5 21v-7h2.4l.4-3h-2.8V9.1c0-.9.3-1.5 1.6-1.5h1.3V4.9c-.3 0-1.1-.1-2-.1-2 0-3.4 1.2-3.4 3.5V11H8.5v3H11v7h2.5z"/></svg>
                    </a>
                </li>
                <li>
                    <a href="https://www.linkedin.com/in/hansa-management-ltd-222838196/" target="_blank" rel="noopener"
                       aria-label="HANSA on LinkedIn"
                       class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/10 hover:bg-white/20">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6.9 8.6H4V20h2.9V8.6zM5.4 4.5a1.7 1.7 0 1 0 0 3.4 1.7 1.7 0 0 0 0-3.4zM20 13.4V20h-2.9v-6c0-1.5-.6-2.4-2-2.4-1.1 0-1.7.7-2 1.4-.1.3-.1.6-.1 1V20h-2.9V8.6h2.9v1.5c.4-.6 1.1-1.5 2.7-1.5 2 0 4.3 1.3 4.3 4.8z"/></svg>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="border-t border-white/15">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-2 px-4 py-4 text-xs sm:px-6">
            <p>Copyright &copy; {{ date('Y') }} HANSA Management Ltd. All rights reserved.</p>
            <p>Developed by Unique Group IT</p>
        </div>
    </div>
</footer>
