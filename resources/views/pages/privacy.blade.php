@extends('layouts.app')

@section('content')
    @php
        // Values below are business decisions, not facts scraped from the code.
        // Change them here and the wording follows. Confirm each one:
        //  - $lastUpdated: bump whenever the policy changes.
        //  - $enquiryRetention: how long you actually keep enquiry records.
        //  - $analyticsRetention: must match the retention period set in the
        //    Google Analytics 4 property, or this policy will be inaccurate.
        $lastUpdated = '17 October 2026';
        $enquiryRetention = '24 months';
        $analyticsRetention = '14 months';

        $contents = [
            'who-we-are' => 'Who we are',
            'what-we-collect' => 'What we collect',
            'why-we-use-it' => 'Why we use it',
            'lawful-basis' => 'Our lawful basis',
            'cookies' => 'Cookies and analytics',
            'sharing' => 'Who we share it with',
            'transfers' => 'Where your data goes',
            'retention' => 'How long we keep it',
            'your-rights' => 'Your rights',
            'security' => 'Security',
            'children' => 'Children',
            'changes' => 'Changes to this policy',
            'contact-us' => 'How to contact us',
        ];
    @endphp

    <div class="page-wrap">
        <div class="page-grid">
            <div class="legal-doc">
                <h1 class="page-h1">Privacy Policy</h1>
                <p class="privacy-updated">
                    Last updated: <time datetime="2026-10-17">{{ $lastUpdated }}</time>
                </p>

                <div class="callout">
                    <h2 class="callout__title">In brief</h2>
                    <ul>
                        <li>We collect personal data in two ways: the enquiry form you fill in, and analytics cookies — and only if you accept them.</li>
                        <li>We use what you send us to reply to you and to provide our services. Nothing else.</li>
                        <li>Google Analytics is switched off until you accept it. If you decline, nothing is sent to Google at all.</li>
                        <li>We do not sell your personal data, and we do not share it for anyone else&rsquo;s marketing.</li>
                        <li>You can ask to see, correct or delete your data, or withdraw your consent, at any time.</li>
                    </ul>
                </div>

                <h2 class="page-h2" id="who-we-are">Who we are</h2>
                <div class="page-body">
                    <p>HANSA Management Ltd. (&ldquo;HANSA&rdquo;, &ldquo;we&rdquo;, &ldquo;us&rdquo;) is an integrated hospitality and facility management company in Bangladesh and a concern of Unique Group. Our office is at Borak Mehnur (Level-18), 51/B Kemal Ataturk Avenue, Banani, Dhaka 1213.</p>
                    <p>For personal data collected through this website, HANSA Management Ltd. is the data controller. That means we decide what is collected and why, and we are responsible for looking after it.</p>
                    <p>This policy covers <strong>www.hansamgt.com</strong> and the enquiry forms on it. It does not cover other websites, including those of other Unique Group concerns or our social media pages.</p>
                </div>

                <h2 class="page-h2" id="what-we-collect">What we collect</h2>
                <div class="page-body">
                    <h3>Information you give us</h3>
                    <p>When you use the enquiry form on our home page or contact page, we collect:</p>
                    <ul>
                        <li>your name</li>
                        <li>your email address</li>
                        <li>your phone number</li>
                        <li>the message you write</li>
                    </ul>
                    <p>The form cannot be submitted without these fields. If you email or telephone us instead, we receive whatever you choose to share.</p>

                    <h3>Information collected automatically</h3>
                    <p>If &mdash; and only if &mdash; you accept analytics, Google Analytics 4 collects:</p>
                    <ul>
                        <li>a randomly generated identifier stored in a cookie, which distinguishes one browser from another</li>
                        <li>your IP address, which indicates your approximate location</li>
                        <li>your device, operating system and browser type</li>
                        <li>the pages you visit, how long you spend and how you arrived here</li>
                    </ul>
                    <p>We use this only in aggregate, to see which services and pages are useful. We do not attempt to identify individual visitors.</p>

                    <h3>What we ask you not to send</h3>
                    <p>Please do not send sensitive personal data through the enquiry form or by email &mdash; for example health or medical information, biometric data, financial or bank details, or identity documents. We do not need any of that to answer an enquiry about our services.</p>
                </div>

                <h2 class="page-h2" id="why-we-use-it">Why we use it</h2>
                <div class="page-body">
                    <ul>
                        <li>to reply to your enquiry, and to pass it to the colleague who can best answer it</li>
                        <li>to prepare a quotation, or to discuss a contract or tender with you</li>
                        <li>to operate, secure and maintain this website</li>
                        <li>to understand which pages and services visitors find useful, so we can improve them</li>
                        <li>to meet our legal, accounting and record-keeping obligations</li>
                    </ul>
                    <p>We do not use your enquiry details for automated decision-making or profiling, we do not build advertising profiles from them, and we never sell them.</p>
                </div>

                <h2 class="page-h2" id="lawful-basis">Our lawful basis</h2>
                <div class="page-body">
                    <p>Bangladesh&rsquo;s Personal Data Protection Act, 2026 requires that personal data be processed on a lawful basis, and that any consent be <strong>voluntary, specific, clear and revocable</strong>.</p>
                    <ul>
                        <li><strong>Enquiries.</strong> We process what you send us because you have asked us to respond, and with your consent, which you give by choosing to complete and submit the form after reading the notice beside it.</li>
                        <li><strong>Analytics.</strong> We ask separately, in the cookie banner. Analytics is off until you accept, and accepting is entirely your choice. Declining has no effect on your ability to use this website or to contact us.</li>
                        <li><strong>Legal obligations.</strong> We keep certain records where the law requires it.</li>
                    </ul>
                </div>

                <h2 class="page-h2" id="cookies">Cookies and analytics</h2>
                <div class="page-body">
                    <p>A cookie is a small file a website asks your browser to store. Some are essential for a site to work; others are used for measurement. This website sets these:</p>
                </div>

                <div class="privacy-table-wrap">
                    <table class="privacy-table">
                        <caption class="sr-only">Cookies set by this website</caption>
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Set by</th>
                                <th scope="col">Purpose</th>
                                <th scope="col">Expires</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code>hansa-management-session</code></td>
                                <td>This website</td>
                                <td>Keeps your session while you move between pages. It is what lets the enquiry form work and shows you the confirmation message after you submit. It holds a random session identifier, not your personal details.</td>
                                <td>After 120 minutes of inactivity</td>
                            </tr>
                            <tr>
                                <td><code>XSRF-TOKEN</code></td>
                                <td>This website</td>
                                <td>A security token that protects the enquiry form against cross-site request forgery. It is not used to track you.</td>
                                <td>After 120 minutes of inactivity</td>
                            </tr>
                            <tr>
                                <td><code>_ga</code><br><code>_ga_&lt;container-id&gt;</code></td>
                                <td>Google</td>
                                <td>Google Analytics 4. Distinguishes visitors and remembers a session so we can see how the site is used. <strong>Only set after you accept analytics.</strong></td>
                                <td>Up to 2 years, per Google&rsquo;s default</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="page-body">
                    <h3>Strictly necessary cookies</h3>
                    <p>The two cookies set by this website are required for it to function &mdash; without them the enquiry form cannot be submitted securely. They do not track you across other sites, and they are not used for advertising.</p>

                    <h3>How we ask for your consent</h3>
                    <p>We ask before setting anything that is not strictly necessary. Until you accept:</p>
                    <ul>
                        <li>the Google Analytics library is never requested from Google&rsquo;s servers</li>
                        <li>no Google Analytics cookie is set, and</li>
                        <li>Google&rsquo;s consent state is set to &ldquo;denied&rdquo;, so no measurement can begin early.</li>
                    </ul>
                    <p>If you decline, nothing at all is sent to Google. Declining does not limit this website in any way.</p>

                    <h3>Changing your mind</h3>
                    <p>Your choice is your own and it is revocable. Every page on this site has a <strong>Cookie settings</strong> link at the bottom, which brings the choice back at any time. If you choose <em>Decline</em> after having accepted, analytics stops immediately, the Google cookies are cleared from your browser, and the page reloads without the tag.</p>
                    <p>Your decision itself is stored in your browser&rsquo;s local storage under the key <code>hansa.consent</code>, not in a cookie. It records only whether you accepted or declined, and it is never sent to us. Clearing your browser storage removes it, and we will ask again. If the cookies or the purposes ever change, we will ask again too.</p>
                </div>

                <h2 class="page-h2" id="sharing">Who we share it with</h2>
                <div class="page-body">
                    <p>We do not sell your personal data and we do not share it so that anyone else can market to you. We share it only with:</p>
                    <ul>
                        <li><strong>Google</strong> &mdash; if you accept analytics, your usage data is processed by Google as our analytics provider.</li>
                        <li><strong>Our website host and email provider</strong> &mdash; the service providers that run this site and deliver our email, who process data on our instructions.</li>
                        <li><strong>Our group</strong> &mdash; HANSA is a concern of Unique Group, so an enquiry may be shared internally with the team best placed to answer you.</li>
                        <li><strong>Professional advisers and authorities</strong> &mdash; where we are legally required to disclose, or need to establish, exercise or defend legal rights.</li>
                    </ul>
                </div>

                <h2 class="page-h2" id="transfers">Where your data goes</h2>
                <div class="page-body">
                    <p>Our office, our staff and our records are in Bangladesh. Some of the providers listed above &mdash; notably Google &mdash; process data on servers outside Bangladesh, including in the United States and the European Union.</p>
                    <p>Where personal data is transferred outside Bangladesh, we expect those providers to protect it to a standard consistent with this policy and with the law that applies to us.</p>
                </div>

                <h2 class="page-h2" id="retention">How long we keep it</h2>
                <div class="page-body">
                    <ul>
                        <li><strong>Enquiries:</strong> kept for up to {{ $enquiryRetention }} from our last contact with you, then deleted. If we go on to work together, contract and accounting records are kept for as long as the law requires.</li>
                        <li><strong>Analytics data:</strong> retained for {{ $analyticsRetention }} in our Google Analytics property.</li>
                        <li><strong>Your consent choice:</strong> kept in your browser until you clear it, or until we publish a new version of this policy and ask again.</li>
                    </ul>
                    <p>We keep data only as long as we need it for the purpose we collected it for, or as long as the law requires.</p>
                </div>

                <h2 class="page-h2" id="your-rights">Your rights</h2>
                <div class="page-body">
                    <p>Under the Personal Data Protection Act, 2026 you have the right to:</p>
                    <ul>
                        <li>be told whether we hold personal data about you, and what we do with it</li>
                        <li>access a copy of it</li>
                        <li>have inaccurate or incomplete data corrected or completed</li>
                        <li>receive your data in a portable form</li>
                        <li>withdraw your consent and ask us to stop processing</li>
                        <li>object to processing, and ask us to erase data we no longer need</li>
                        <li>complain to the competent authority</li>
                    </ul>
                    <p>Withdrawing consent for analytics takes one click, using <strong>Cookie settings</strong> at the bottom of any page. For everything else, contact us using the details below.</p>
                </div>

                <h2 class="page-h2" id="security">Security</h2>
                <div class="page-body">
                    <p>We take appropriate technical and organisational measures to protect personal data: access controls on our systems, an encrypted (HTTPS) connection to this website, and limiting which colleagues can see enquiries.</p>
                    <p>No system can be guaranteed perfectly secure, so we cannot promise absolute security. If a breach ever affects your rights, we will tell you and the relevant authorities as required by law.</p>
                </div>

                <h2 class="page-h2" id="children">Children</h2>
                <div class="page-body">
                    <p>This website and our services are aimed at businesses and adults. We do not knowingly collect personal data from children. If you believe a child has sent us information, email us and we will delete it.</p>
                </div>

                <h2 class="page-h2" id="changes">Changes to this policy</h2>
                <div class="page-body">
                    <p>We may update this policy &mdash; for example, if we adopt a new tool that uses cookies, or if the law changes. The date at the top of this page always shows the current version, and past versions are available from us on request.</p>
                    <p>If a change significantly affects how we use analytics, we will ask for your consent again rather than relying on your earlier choice.</p>
                </div>

                <h2 class="page-h2" id="contact-us">How to contact us</h2>
                <div class="page-body">
                    <p>To ask about this policy, to exercise any of your rights, or to complain about how we have handled your data, contact us:</p>
                    <ul>
                        <li>Email: <a href="mailto:info@hansamgt.com">info@hansamgt.com</a></li>
                        <li>Telephone: <a href="tel:+8801708800870">01708800870</a></li>
                        <li>Post: HANSA Management Ltd., Borak Mehnur (Level-18), 51/B Kemal Ataturk Avenue, Banani, Dhaka 1213, Bangladesh</li>
                    </ul>
                    <p>Please tell us what you would like us to do, and enough about your enquiry for us to find it &mdash; the name and email address you used, and roughly when you got in touch. We will respond within a reasonable period and we will not charge you for exercising your rights.</p>
                    <p>This policy is published in English. If you would like help understanding it in Bangla, contact us and we will assist you.</p>
                </div>
            </div>

            <aside aria-label="On this page">
                <nav class="side-nav" aria-labelledby="privacy-contents">
                    <h2 class="side-nav__title" id="privacy-contents">On this page</h2>
                    <ul class="side-nav__list">
                        @foreach ($contents as $anchor => $label)
                            <li><a href="#{{ $anchor }}">{{ $label }}</a></li>
                        @endforeach
                    </ul>
                </nav>

                <div class="side-cta">
                    <h2>Questions about your data?</h2>
                    <p>Email <a href="mailto:info@hansamgt.com">info@hansamgt.com</a> or call <a href="tel:+8801708800870">01708800870</a>.</p>
                    <p><a class="home-submit" href="{{ url('contact') }}">Contact us</a></p>
                </div>
            </aside>
        </div>
    </div>
    @include('pages.home.sections._slogan')
@endsection
