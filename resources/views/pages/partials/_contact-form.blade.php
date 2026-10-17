{{-- Contact form fields (legacy: Contact.php / ContactPage.php, UI only) --}}
@csrf
<p class="font-semibold">I would like to discuss:</p>
<div class="mt-4 grid gap-4 sm:grid-cols-2">
    <div>
        <label class="sr-only" for="{{ $prefix }}-name">Full Name</label>
        <input id="{{ $prefix }}-name" type="text" name="name" required autocomplete="name"
               placeholder="Full Name" class="home-input">
    </div>
    <div>
        <label class="sr-only" for="{{ $prefix }}-email">Email</label>
        <input id="{{ $prefix }}-email" type="email" name="email" required autocomplete="email"
               placeholder="Email" class="home-input">
    </div>
    <div>
        <label class="sr-only" for="{{ $prefix }}-phone">Phone Number</label>
        <input id="{{ $prefix }}-phone" type="tel" name="phone" required autocomplete="tel"
               placeholder="Phone Number" class="home-input">
    </div>
    <div>
        <label class="sr-only" for="{{ $prefix }}-message">Your Message</label>
        <textarea id="{{ $prefix }}-message" name="message" rows="4" required
                  placeholder="Your Message *" class="home-input"></textarea>
    </div>
</div>
<p class="form-privacy form-privacy--panel">
    We use the details you send here only to respond to your enquiry and provide our services.
    See our <a href="{{ url('privacy-policy') }}">Privacy Policy</a>.
</p>
<button type="submit" class="home-submit">Submit &#8250;</button>
