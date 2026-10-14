{{-- Our Valued Clients (legacy: Clients.php; OwlCarousel replaced with scroll-snap carousel) --}}
@php
    $clients = [
        ['file' => 'westin-dhaka-logo.png', 'alt' => 'The Westin Dhaka'],
        ['file' => 'sheraton-dhaka-logo.png', 'alt' => 'Sheraton Dhaka'],
        ['file' => 'hansa-hotel.png', 'alt' => 'HANSA – A Premium Residence'],
        ['file' => 'regus.png', 'alt' => 'Regus Dhaka'],
        ['file' => 'uae-xchange.png', 'alt' => 'UAE Xchange'],
        ['file' => 'unique-meghnaghat.png', 'alt' => 'Unique MeghnaGhat'],
        ['file' => 'sure-cash.png', 'alt' => 'Sure Cash'],
        ['file' => 'nipro-jmi-pharma.png', 'alt' => 'NIPRO JMI PHARMA'],
        ['file' => 'electricity-generation-company.png', 'alt' => 'Electricity Generation Company of Bangladesh'],
        ['file' => 'lidl.jpeg', 'alt' => 'Lidl'],
        ['file' => 'rupayancity.jpeg', 'alt' => 'Rupayan City'],
        ['file' => 'table.jpeg', 'alt' => 'Table'],
        ['file' => 'ucc.jpeg', 'alt' => 'UCC'],
        ['file' => 'vf.jpeg', 'alt' => 'VF'],
        ['file' => 'united-hostipital.jpg', 'alt' => 'United Hospital'],
        ['file' => 'hyatt-place.jpg', 'alt' => 'Hyatt Place'],
        ['file' => 'in.jpg', 'alt' => 'IN'],
        ['file' => 'foodpanda.jpg', 'alt' => 'foodpanda'],
    ];
@endphp
<section id="emr_clients" class="home-section home-section--tint" aria-labelledby="clients-heading">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <h2 id="clients-heading" class="home-heading">Our Valued Clients</h2>
        <div class="logo-carousel" data-carousel data-autoplay="3000">
            <button type="button" class="logo-carousel__btn" data-carousel-prev aria-label="Scroll clients backward">&#8249;</button>
            <div class="logo-carousel__track">
                @foreach ($clients as $client)
                    <div class="logo-carousel__item">
                        <img src="{{ asset('images/home/clients/' . $client['file']) }}"
                             alt="{{ $client['alt'] }}" title="{{ $client['alt'] }}"
                             width="200" height="140" loading="lazy" decoding="async">
                    </div>
                @endforeach
            </div>
            <button type="button" class="logo-carousel__btn" data-carousel-next aria-label="Scroll clients forward">&#8250;</button>
        </div>
    </div>
</section>
