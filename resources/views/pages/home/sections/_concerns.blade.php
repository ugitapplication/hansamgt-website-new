{{-- Business Concerns & Associates (legacy: CustomerTrust.php; OwlCarousel replaced) --}}
@php
    $concerns = [
        ['file' => 'unique-eastern.jpg', 'alt' => 'Unique Eastern'],
        ['file' => 'gulshan-clinic.jpg', 'alt' => 'Gulshan Clinic'],
        ['file' => 'canadian-university.jpg', 'alt' => 'Canadian University of Bangladesh'],
        ['file' => 'update-college.jpg', 'alt' => 'Update College'],
        ['file' => 'update-institute.jpg', 'alt' => 'Update Institute'],
        ['file' => 'arial-dairy.jpg', 'alt' => 'Arial Dairy and Agro'],
        ['file' => 'unique-share-mgt.jpg', 'alt' => 'Unique Share Management'],
        ['file' => 'borak-readymix.jpg', 'alt' => 'Borak Ready Mix Concrete'],
        ['file' => 'amader-somoy.jpg', 'alt' => 'Amader Somoy'],
        ['file' => 'business-post.jpg', 'alt' => 'The Business Post'],
        ['file' => 'unique-vocational-training-center-logo.jpg', 'alt' => 'Unique Vocational Training Center'],
        ['file' => 'borak-polytechnic-logo.jpg', 'alt' => 'Borak Polytechnic Institute'],
        ['file' => 'my-sis-tech-logo.jpg', 'alt' => 'My Sys Technology'],
    ];
@endphp
<section id="emr_concern" class="home-section home-section--tint" aria-labelledby="concerns-heading">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <h2 id="concerns-heading" class="home-heading">Business Concerns &amp; Associates</h2>
        <div class="logo-carousel" data-carousel data-autoplay="950">
            <button type="button" class="logo-carousel__btn" data-carousel-prev aria-label="Scroll associates backward">&#8249;</button>
            <div class="logo-carousel__track">
                @foreach ($concerns as $concern)
                    <div class="logo-carousel__item">
                        <img src="{{ asset('images/home/concerns/' . $concern['file']) }}"
                             alt="{{ $concern['alt'] }}" title="{{ $concern['alt'] }}"
                             width="200" height="140" loading="lazy" decoding="async">
                    </div>
                @endforeach
            </div>
            <button type="button" class="logo-carousel__btn" data-carousel-next aria-label="Scroll associates forward">&#8250;</button>
        </div>
    </div>
</section>
