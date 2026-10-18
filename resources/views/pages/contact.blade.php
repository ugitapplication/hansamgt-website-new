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
                    <p><a href="{{ \App\Support\Settings::phoneHref() }}">{{ \App\Support\Settings::phoneDisplay() }}</a></p>
                </div>
            </div>
            @if (\App\Support\Settings::filled('fax'))
                <div class="contact-strip__item">
                    <span class="contact-strip__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg></span>
                    <div>
                        <h2>Fax</h2>
                        <p>{{ \App\Support\Settings::get('fax') }}</p>
                    </div>
                </div>
            @endif
            <div class="contact-strip__item">
                <span class="contact-strip__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 6l-10 7L2 6"/></svg></span>
                <div>
                    <h2>Email</h2>
                    <p><a href="mailto:{{ \App\Support\Settings::get('email') }}">{{ \App\Support\Settings::get('email') }}</a></p>
                </div>
            </div>
        </div>
    </div>
    <div class="page-wrap">
        @php
            $pin = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>';
            $phone = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>';
            $mail = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 6l-10 7L2 6"/></svg>';
        @endphp
        <div class="contact-cards">
            @foreach (($offices ?? []) as $office)
                <article class="contact-card">
                    <h2>{{ $office['title'] }}</h2>
                    <p class="contact-card__row">{!! $pin !!}<span>{{ $office['address'] }}</span></p>
                    {{-- Only shown when the office actually has its own number or
                         address; the cards used to repeat the corporate mobile and
                         email on all four, which made the booths look like they had
                         their own line. --}}
                    @if ($office['phone'])
                        <p class="contact-card__row">{!! $phone !!}<span><a href="tel:{{ preg_replace('/[^\d+]/', '', $office['phone']) }}">{{ preg_replace('/^\+?88\s*/', '', $office['phone']) }}</a></span></p>
                    @endif
                    @if ($office['landline'])
                        <p class="contact-card__row">{!! $phone !!}<span>{{ $office['landline'] }}</span></p>
                    @endif
                    @if ($office['email'])
                        <p class="contact-card__row contact-card__row--mail">{!! $mail !!}<span><a href="mailto:{{ $office['email'] }}">{{ $office['email'] }}</a></span></p>
                    @endif
                    @include('partials.social-links', ['style' => 'card'])
                </article>
            @endforeach
        </div>
        @include('partials.office-map', ['offices' => $offices ?? []])
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
                <p class="form-privacy form-privacy--panel">
                    We use the details you send here only to respond to your enquiry and provide our services.
                    See our <a href="{{ url('privacy-policy') }}">Privacy Policy</a>.
                </p>
                <p style="margin-top:1rem;"><button type="submit" class="contact-submit">Submit <span class="chev">›</span></button></p>
            </form>
        </div>
    </div>
    @include('pages.home.sections._slogan')
@endsection
