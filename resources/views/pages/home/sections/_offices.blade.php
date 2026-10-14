{{-- Office addresses (legacy: Address.php content; Map.php Google Maps embed intentionally
     not ported — no API key in the new site. All NAP content preserved below.) --}}
@php
    $offices = [
        [
            'title' => 'Corporate Office',
            'address' => 'Borak Mehnur (Level-18), 51/B Kemal Ataturk Avenue, Banani, Dhaka-1213, Bangladesh',
            'landline' => '88 02 222285116-123',
        ],
        [
            'title' => 'Service Booth (Midtown)',
            'address' => 'Unique Heights, 117 Kazi Nazrul Islam Avenue, Eskaton, Dhaka, Bangladesh',
            'landline' => '88 02 48322812',
        ],
        [
            'title' => 'Service Booth (Uttara)',
            'address' => 'HANSA- A Premium Residence, House # 3 & 5, Road # 10/A , Sector # 09, Uttara, Dhaka - 1230, Bangladesh',
            'landline' => null,
        ],
        [
            'title' => 'Service Booth (Dhanmondi)',
            'address' => 'Unique Trade Centre , 8 Panthapath, Kawranbazar, Dhaka-1215, Bangladesh',
            'landline' => null,
        ],
    ];
@endphp
<section class="home-section home-section--dark" aria-labelledby="offices-heading">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <h2 id="offices-heading" class="home-heading home-heading--light">Our Offices</h2>
        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($offices as $office)
                <address class="office-card">
                    <h3>{{ $office['title'] }}</h3>
                    <p>{{ $office['address'] }}</p>
                    <p><a href="tel:+8801708800870">01708800870</a></p>
                    @if ($office['landline'])
                        <p>{{ $office['landline'] }}</p>
                    @endif
                    <p><a href="mailto:info@hansamgt.com">info@hansamgt.com</a></p>
                </address>
            @endforeach
        </div>
    </div>
</section>
