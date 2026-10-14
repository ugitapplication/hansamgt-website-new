{{-- Services sidebar (legacy: ServiceRightMenu.php + InnerContact.php) --}}
@php
    $serviceLinks = [
        ['url' => 'hospitality-management', 'label' => 'Hospitality Management'],
        ['url' => 'security-fire-life-safety', 'label' => 'Security Surveillance & Fire Life Safety'],
        ['url' => 'cleaning-white-glove-housekeeping', 'label' => 'Cleaning & White-Glove Housekeeping'],
        ['url' => 'mep-mechanical-electrical-plumbing', 'label' => 'Mechanical, Electrical & Plumbing'],
    ];
    $serviceActive = $active ?? '';
@endphp
<aside aria-label="Our services">
    <nav class="side-menu" aria-label="Services">
        @foreach ($serviceLinks as $link)
            <a href="{{ url($link['url']) }}" class="{{ $serviceActive === $link['url'] ? 'is-active' : '' }}">{{ $link['label'] }}</a>
        @endforeach
    </nav>
    <div class="side-cta">
        <h2>How can we help?</h2>
        <p>Contact at <a href="tel:+8801708800870">01708800870</a> or submit an inquiry online.</p>
        <p><a class="home-submit" href="{{ url('contact') }}">Contact Us</a></p>
    </div>
</aside>
