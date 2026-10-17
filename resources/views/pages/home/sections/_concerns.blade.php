{{-- Business Concerns & Associates (legacy: CustomerTrust.php; OwlCarousel replaced).
     Logos come from the admin panel when active rows exist; otherwise the
     original built-in set below is used so the homepage never breaks. --}}
@php
    $concerns = ($concerns ?? []);
    if (empty($concerns)) {
        $concerns = [
            ['src' => asset('images/home/concerns/unique-eastern.jpg'), 'alt' => 'Unique Eastern'],
            ['src' => asset('images/home/concerns/gulshan-clinic.jpg'), 'alt' => 'Gulshan Clinic'],
            ['src' => asset('images/home/concerns/canadian-university.jpg'), 'alt' => 'Canadian University of Bangladesh'],
            ['src' => asset('images/home/concerns/update-college.jpg'), 'alt' => 'Update College'],
            ['src' => asset('images/home/concerns/update-institute.jpg'), 'alt' => 'Update Institute'],
            ['src' => asset('images/home/concerns/arial-dairy.jpg'), 'alt' => 'Arial Dairy and Agro'],
            ['src' => asset('images/home/concerns/unique-share-mgt.jpg'), 'alt' => 'Unique Share Management'],
            ['src' => asset('images/home/concerns/borak-readymix.jpg'), 'alt' => 'Borak Ready Mix Concrete'],
            ['src' => asset('images/home/concerns/amader-somoy.jpg'), 'alt' => 'Amader Somoy'],
            ['src' => asset('images/home/concerns/business-post.jpg'), 'alt' => 'The Business Post'],
            ['src' => asset('images/home/concerns/unique-vocational-training-center-logo.jpg'), 'alt' => 'Unique Vocational Training Center'],
            ['src' => asset('images/home/concerns/borak-polytechnic-logo.jpg'), 'alt' => 'Borak Polytechnic Institute'],
            ['src' => asset('images/home/concerns/my-sis-tech-logo.jpg'), 'alt' => 'My Sys Technology'],
        ];
    }
@endphp
<section id="emr_concern" class="home-section home-section--tint" aria-labelledby="concerns-heading">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <h2 id="concerns-heading" class="home-heading">Business Concerns &amp; Associates</h2>
        <div class="logo-carousel" data-carousel data-autoplay="950">
            <button type="button" class="logo-carousel__btn" data-carousel-prev aria-label="Scroll associates backward">&#8249;</button>
            <div class="logo-carousel__track">
                @foreach ($concerns as $concern)
                    <div class="logo-carousel__item">
                        <img src="{{ $concern['src'] }}"
                             alt="{{ $concern['alt'] }}" title="{{ $concern['alt'] }}"
                             width="200" height="140" loading="lazy" decoding="async">
                    </div>
                @endforeach
            </div>
            <button type="button" class="logo-carousel__btn" data-carousel-next aria-label="Scroll associates forward">&#8250;</button>
        </div>
    </div>
</section>
