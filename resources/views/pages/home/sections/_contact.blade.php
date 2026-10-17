{{-- Let's Talk (legacy: Contact.php; UI only — backend submission arrives with the contact page) --}}
<section id="emr_contact" class="home-contact" aria-labelledby="contact-heading">
    <div class="mx-auto grid max-w-7xl gap-8 px-4 py-14 sm:px-6 md:grid-cols-3">
        <div>
            <h2 id="contact-heading" class="text-3xl font-bold text-white">Let&rsquo;s Talk</h2>
            <p class="mt-3 text-sm leading-relaxed text-white/90">Would you like to speak with our customer service agent to know about our hospitality &amp; facility management services? Just submit your details and we&rsquo;ll be in touch shortly.</p>
        </div>
        <form method="post" action="{{ url('contact') }}" class="md:col-span-2" aria-label="Contact HANSA">
            @csrf
            @if (session('contact_success'))
                <p class="font-semibold text-white" role="status">{{ session('contact_success') }}</p>
            @endif
            <p class="font-semibold text-white">I would like to discuss:</p>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="sr-only" for="home-contact-name">Full Name</label>
                    <input id="home-contact-name" type="text" name="name" required autocomplete="name"
                           placeholder="Full Name" class="home-input" value="{{ old('name') }}">
                    @error('name')
                        <small class="mt-1 block text-sm text-red-200" role="alert">{{ $message }}</small>
                    @enderror
                </div>
                <div>
                    <label class="sr-only" for="home-contact-email">Email</label>
                    <input id="home-contact-email" type="email" name="email" required autocomplete="email"
                           placeholder="Email" class="home-input" value="{{ old('email') }}">
                    @error('email')
                        <small class="mt-1 block text-sm text-red-200" role="alert">{{ $message }}</small>
                    @enderror
                </div>
                <div>
                    <label class="sr-only" for="home-contact-phone">Phone Number</label>
                    <input id="home-contact-phone" type="tel" name="phone" required autocomplete="tel"
                           placeholder="Phone Number" class="home-input" value="{{ old('phone') }}">
                    @error('phone')
                        <small class="mt-1 block text-sm text-red-200" role="alert">{{ $message }}</small>
                    @enderror
                </div>
                <div>
                    <label class="sr-only" for="home-contact-message">Your Message</label>
                    <textarea id="home-contact-message" name="message" rows="4" required
                              placeholder="Your Message *" class="home-input">{{ old('message') }}</textarea>
                    @error('message')
                        <small class="mt-1 block text-sm text-red-200" role="alert">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <button type="submit" class="home-submit">Submit &#8250;</button>
        </form>
    </div>
</section>
