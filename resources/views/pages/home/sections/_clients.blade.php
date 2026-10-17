{{-- Our Valued Clients (legacy: Clients.php; OwlCarousel replaced with scroll-snap carousel).
     Logos come from the admin panel when active rows exist; otherwise the
     original built-in set below is used so the homepage never breaks. --}}
@php
    $clients = ($clients ?? []);
    if (empty($clients)) {
        $clients = [
            ['src' => asset('images/home/clients/westin-dhaka-logo.png'), 'alt' => 'The Westin Dhaka'],
            ['src' => asset('images/home/clients/sheraton-dhaka-logo.png'), 'alt' => 'Sheraton Dhaka'],
            ['src' => asset('images/home/clients/hansa-hotel.png'), 'alt' => 'HANSA – A Premium Residence'],
            ['src' => asset('images/home/clients/regus.png'), 'alt' => 'Regus Dhaka'],
            ['src' => asset('images/home/clients/uae-xchange.png'), 'alt' => 'UAE Xchange'],
            ['src' => asset('images/home/clients/unique-meghnaghat.png'), 'alt' => 'Unique MeghnaGhat'],
            ['src' => asset('images/home/clients/sure-cash.png'), 'alt' => 'Sure Cash'],
            ['src' => asset('images/home/clients/nipro-jmi-pharma.png'), 'alt' => 'NIPRO JMI PHARMA'],
            ['src' => asset('images/home/clients/electricity-generation-company.png'), 'alt' => 'Electricity Generation Company of Bangladesh'],
            ['src' => asset('images/home/clients/lidl.jpeg'), 'alt' => 'Lidl'],
            ['src' => asset('images/home/clients/rupayancity.jpeg'), 'alt' => 'Rupayan City'],
            ['src' => asset('images/home/clients/table.jpeg'), 'alt' => 'Table'],
            ['src' => asset('images/home/clients/ucc.jpeg'), 'alt' => 'UCC'],
            ['src' => asset('images/home/clients/vf.jpeg'), 'alt' => 'VF'],
            ['src' => asset('images/home/clients/united-hostipital.jpg'), 'alt' => 'United Hospital'],
            ['src' => asset('images/home/clients/hyatt-place.jpg'), 'alt' => 'Hyatt Place'],
            ['src' => asset('images/home/clients/in.jpg'), 'alt' => 'IN'],
            ['src' => asset('images/home/clients/foodpanda.jpg'), 'alt' => 'foodpanda'],
        ];
    }
@endphp
<section id="emr_clients" class="home-section home-section--tint" aria-labelledby="clients-heading">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <h2 id="clients-heading" class="home-heading">Our Valued Clients</h2>
        <div class="logo-carousel" data-carousel data-autoplay="3000">
            <button type="button" class="logo-carousel__btn" data-carousel-prev aria-label="Scroll clients backward">&#8249;</button>
            <div class="logo-carousel__track">
                @foreach ($clients as $client)
                    <div class="logo-carousel__item">
                        <img src="{{ $client['src'] }}"
                             alt="{{ $client['alt'] }}" title="{{ $client['alt'] }}"
                             width="200" height="140" loading="lazy" decoding="async">
                    </div>
                @endforeach
            </div>
            <button type="button" class="logo-carousel__btn" data-carousel-next aria-label="Scroll clients forward">&#8250;</button>
        </div>
    </div>
</section>
