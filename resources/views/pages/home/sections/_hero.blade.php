{{-- Hero slider (legacy: Slider.php, RevSlider replaced with lightweight rotator).
     Slides come from the admin panel (HeroSlider) when active rows exist;
     otherwise the original built-in set below is used so the homepage never breaks. --}}
@php
    $heroSlides = ($heroSlides ?? []);
    if (empty($heroSlides)) {
        $heroSlides = [
            ['src' => asset('images/home/slider/hotel-housekeeping-bed-making-hansa-management.jpg'), 'alt' => 'HANSA housekeeping staff making a bed in a hotel guest room'],
            ['src' => asset('images/home/slider/hansa-management-team-group-photo.jpg'), 'alt' => 'HANSA Management team group photo in a hotel restaurant'],
            ['src' => asset('images/home/slider/security-guards-uniform-inspection-hansa-management.jpg'), 'alt' => 'HANSA security guards in uniform lined up for inspection by two managers'],
            ['src' => asset('images/home/slider/cleaning-building-hansa-management.jpg'), 'alt' => 'HANSA staff in hard hats during cleaning building hose training'],
            ['src' => asset('images/home/slider/floor-cleaning-machine-terrace-hansa-management.jpg'), 'alt' => 'Two HANSA cleaning staff polishing a stone terrace floor with a scrubbing machine and wet vacuum'],
            ['src' => asset('images/home/slider/hansa-employee-training.jpg'), 'alt' => 'HANSA Management Ltd. hospitality and facility management services'],
            ['src' => asset('images/home/slider/front-office-reception-desk-hansa-management.jpg'), 'alt' => 'HANSA front office executive at a reception desk with telephone and microphone'],
        ];
    }
@endphp
<section class="home-hero" aria-label="HANSA Management highlights">
    <div class="home-hero__slides" id="home-hero-slides">
        @foreach ($heroSlides as $slide)
            <div class="home-hero__slide{{ $loop->first ? ' is-active' : '' }}"{{ $loop->first ? '' : ' aria-hidden="true"' }}>
                <img src="{{ $slide['src'] }}"
                     alt="{{ $slide['alt'] }}"
                     @if ($loop->first) fetchpriority="high" @else loading="lazy" @endif>
            </div>
        @endforeach
    </div>
    <div class="home-hero__content">
        <h1 class="home-hero__title">
            <span class="text-hansa-gold">HANSA Management Ltd.</span><br>
            <span class="text-white">Hospitality and Facility Management Services</span>
        </h1>
        <div class="home-hero__actions">
            <a class="home-hero__cta" href="#emr_contact" data-rail-link>Request a Quote</a>
            <a class="home-hero__cta home-hero__cta--ghost" href="{{ \App\Support\Settings::phoneHref() }}">Call {{ \App\Support\Settings::phoneDisplay() }}</a>
        </div>
    </div>
    <button type="button" class="home-hero__arrow home-hero__arrow--prev" data-hero-prev aria-label="Previous slide">&#8249;</button>
    <button type="button" class="home-hero__arrow home-hero__arrow--next" data-hero-next aria-label="Next slide">&#8250;</button>
    <div class="home-hero__progress" aria-hidden="true"><span id="home-hero-progress"></span></div>
</section>
