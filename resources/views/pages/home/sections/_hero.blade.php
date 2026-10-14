{{-- Hero slider (legacy: Slider.php, RevSlider replaced with lightweight rotator) --}}
@php
    $heroSlides = [
        ['file' => 'hotel-housekeeping-bed-making-hansa-management.jpg', 'alt' => 'HANSA housekeeping staff making a bed in a hotel guest room'],
        ['file' => 'hansa-management-team-group-photo.jpg', 'alt' => 'HANSA Management team group photo in a hotel restaurant'],
        ['file' => 'security-guards-uniform-inspection-hansa-management.jpg', 'alt' => 'HANSA security guards in uniform lined up for inspection by two managers'],
        ['file' => 'cleaning-building-hansa-management.jpg', 'alt' => 'HANSA staff in hard hats during cleaning building hose training'],
        ['file' => 'floor-cleaning-machine-terrace-hansa-management.jpg', 'alt' => 'Two HANSA cleaning staff polishing a stone terrace floor with a scrubbing machine and wet vacuum'],
        ['file' => 'hansa-employee-training.jpg', 'alt' => 'HANSA Management Ltd. hospitality and facility management services'],
        ['file' => 'front-office-reception-desk-hansa-management.jpg', 'alt' => 'HANSA front office executive at a reception desk with telephone and microphone'],
    ];
@endphp
<section class="home-hero" aria-label="HANSA Management highlights">
    <div class="home-hero__slides" id="home-hero-slides">
        @foreach ($heroSlides as $slide)
            <div class="home-hero__slide{{ $loop->first ? ' is-active' : '' }}"{{ $loop->first ? '' : ' aria-hidden="true"' }}>
                <img src="{{ asset('images/home/slider/' . $slide['file']) }}"
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
    </div>
    <button type="button" class="home-hero__arrow home-hero__arrow--prev" data-hero-prev aria-label="Previous slide">&#8249;</button>
    <button type="button" class="home-hero__arrow home-hero__arrow--next" data-hero-next aria-label="Next slide">&#8250;</button>
    <div class="home-hero__progress" aria-hidden="true"><span id="home-hero-progress"></span></div>
</section>
