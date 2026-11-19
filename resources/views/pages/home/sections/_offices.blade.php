
<section class="home-section home-section--dark" aria-labelledby="offices-heading">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <h2 id="offices-heading" class="home-heading home-heading--light">Our Offices</h2>
        @php
            $pin = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>';
            $phone = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>';
            $mail = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 6l-10 7L2 6"/></svg>';
        @endphp
        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach (($offices ?? []) as $office)
                <address class="office-card">
                    <h3>{{ $office['title'] }}</h3>
                    <p class="office-card__row">{!! $pin !!}<span>{{ $office['address'] }}</span></p>
                    @if ($office['phone'])
                        <p class="office-card__row">{!! $phone !!}<span><a href="tel:{{ preg_replace('/[^\d+]/', '', $office['phone']) }}">{{ preg_replace('/^\+?88\s*/', '', $office['phone']) }}</a></span></p>
                    @endif
                    @if ($office['landline'])
                        <p class="office-card__row">{!! $phone !!}<span>{{ $office['landline'] }}</span></p>
                    @endif
                    @if ($office['email'])
                        <p class="office-card__row">{!! $mail !!}<span><a href="mailto:{{ $office['email'] }}">{{ $office['email'] }}</a></span></p>
                    @endif
                </address>
            @endforeach
        </div>
    </div>
</section>
