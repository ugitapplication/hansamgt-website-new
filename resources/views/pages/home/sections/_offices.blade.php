{{-- Office addresses, read from the `offices` table (admin → Settings → Offices).
     There is one list now: the contact page and this section used to carry
     separate copies, and both repeated the corporate mobile and email on every
     card. A per-office number is either real or absent. --}}
<section class="home-section home-section--dark" aria-labelledby="offices-heading">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <h2 id="offices-heading" class="home-heading home-heading--light">Our Offices</h2>
        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach (($offices ?? []) as $office)
                <address class="office-card">
                    <h3>{{ $office['title'] }}</h3>
                    <p>{{ $office['address'] }}</p>
                    @if ($office['phone'])
                        <p><a href="tel:{{ preg_replace('/[^\d+]/', '', $office['phone']) }}">{{ preg_replace('/^\+?88\s*/', '', $office['phone']) }}</a></p>
                    @endif
                    @if ($office['landline'])
                        <p>{{ $office['landline'] }}</p>
                    @endif
                    @if ($office['email'])
                        <p><a href="mailto:{{ $office['email'] }}">{{ $office['email'] }}</a></p>
                    @endif
                </address>
            @endforeach
        </div>
    </div>
</section>
