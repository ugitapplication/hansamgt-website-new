@extends('layouts.app')

@section('content')
    <div class="page-wrap">
        <h1 class="page-h1">Contact HANSA Management Ltd.</h1>
    </div>
    <div class="contact-strip">
        <div class="contact-strip__inner">
            <div class="contact-strip__item">
                <span class="contact-strip__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg></span>
                <div>
                    <h2>Information Desk</h2>
                    <p><a href="tel:+8801708800870">01708800870</a></p>
                </div>
            </div>
            <div class="contact-strip__item">
                <span class="contact-strip__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg></span>
                <div>
                    <h2>Fax</h2>
                    <p>88-02-8823392</p>
                </div>
            </div>
            <div class="contact-strip__item">
                <span class="contact-strip__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 6l-10 7L2 6"/></svg></span>
                <div>
                    <h2>Email</h2>
                    <p><a href="mailto:info@hansamgt.com">info@hansamgt.com</a></p>
                </div>
            </div>
        </div>
    </div>
    <div class="page-wrap">
        @php
            $pin = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>';
            $phone = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>';
            $mail = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 6l-10 7L2 6"/></svg>';
            $fb = '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>';
            $tw = '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/></svg>';
            $li = '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>';
            $offices = [
                ['title' => 'Corporate Office', 'address' => 'Borak Mehnur (Level-18), 51/B Kemal Ataturk Avenue, Banani, Dhaka-1213, Bangladesh', 'landline' => '88 02 222285116-123'],
                ['title' => 'Service booth (Midtown)', 'address' => 'Unique Heights, 117 Kazi Nazrul Islam Avenue, Eskaton, Dhaka, Bangladesh', 'landline' => '88 02 48322812'],
                ['title' => 'Service booth (Uttara)', 'address' => 'HANSA- A Premium Residence, House # 3 & 5, Road # 10/A , Sector # 09, Uttara, Dhaka - 1230, Bangladesh', 'landline' => null],
                ['title' => 'Service booth (Dhanmondi)', 'address' => 'Unique Trade Centre , 8 Panthapath, Kawranbazar, Dhaka-1215, Bangladesh', 'landline' => null],
            ];
        @endphp
        <div class="contact-cards">
            @foreach ($offices as $office)
                <article class="contact-card">
                    <h2>{{ $office['title'] }}</h2>
                    <p class="contact-card__row">{!! $pin !!}<span>{{ $office['address'] }}</span></p>
                    <p class="contact-card__row">{!! $phone !!}<span><a href="tel:+8801708800870">01708800870</a></span></p>
                    @if ($office['landline'])
                        <p class="contact-card__row">{!! $phone !!}<span>{{ $office['landline'] }}</span></p>
                    @endif
                    <p class="contact-card__row contact-card__row--mail">{!! $mail !!}<span><a href="mailto:info@hansamgt.com">info@hansamgt.com</a></span></p>
                    <ul class="contact-card__socials">
                        <li><a href="https://www.facebook.com/HANSAManagementLtd/" target="_blank" rel="noopener" aria-label="HANSA on Facebook">{!! $fb !!}</a></li>
                        <li><a href="#" target="_blank" rel="noopener" aria-label="HANSA on Twitter">{!! $tw !!}</a></li>
                        <li><a href="https://www.linkedin.com/in/hansa-management-ltd-222838196/" target="_blank" rel="noopener" aria-label="HANSA on LinkedIn">{!! $li !!}</a></li>
                    </ul>
                </article>
            @endforeach
        </div>
        <div class="contact-formwrap">
            <h2>Contact Us</h2>
            @if (session('contact_success'))
                <p class="form-note" role="status" style="color: #1a7f37; font-weight: 600;">{{ session('contact_success') }}</p>
            @endif
            <form method="post" action="{{ url('contact') }}" aria-label="Contact HANSA">
                @csrf
                <div class="contact-form">
                    <div class="contact-form__col">
                        <div>
                            <label class="sr-only" for="contact-page-name">Full Name</label>
                            <input id="contact-page-name" type="text" name="name" required autocomplete="name"
                                   placeholder="Full Name" class="contact-input" value="{{ old('name') }}">
                            @error('name')
                                <small class="form-note" role="alert" style="color: #b3261e;">{{ $message }}</small>
                            @enderror
                        </div>
                        <div>
                            <label class="sr-only" for="contact-page-phone">Phone Number</label>
                            <input id="contact-page-phone" type="tel" name="phone" required autocomplete="tel"
                                   placeholder="Phone Number" class="contact-input" value="{{ old('phone') }}">
                            @error('phone')
                                <small class="form-note" role="alert" style="color: #b3261e;">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="contact-form__col">
                        <div>
                            <label class="sr-only" for="contact-page-email">Email</label>
                            <input id="contact-page-email" type="email" name="email" required autocomplete="email"
                                   placeholder="Email" class="contact-input" value="{{ old('email') }}">
                            @error('email')
                                <small class="form-note" role="alert" style="color: #b3261e;">{{ $message }}</small>
                            @enderror
                        </div>
                        <div style="display:flex;flex:1;">
                            <label class="sr-only" for="contact-page-message">Your Message</label>
                            <textarea id="contact-page-message" name="message" rows="6" required
                                      placeholder="Your Message *" class="contact-input">{{ old('message') }}</textarea>
                        </div>
                        @error('message')
                            <small class="form-note" role="alert" style="color: #b3261e;">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <p style="margin-top:1.5rem;"><button type="submit" class="contact-submit">Submit <span class="chev">›</span></button></p>
            </form>
        </div>
    </div>
    @include('pages.home.sections._slogan')
@endsection
