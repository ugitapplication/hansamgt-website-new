{{-- Floating quick-link rail (legacy: wpfm floating menu in Footer.php; homepage only) --}}
@php
    $railLinks = [
        ['href' => '#what_we_do', 'label' => 'Our Services', 'pill' => 'OUR SERVICES', 'icon' => '<circle cx="12" cy="8" r="3.2"/><path d="M5.5 19c1.2-3.2 3.6-4.8 6.5-4.8s5.3 1.6 6.5 4.8"/>'],
        ['href' => '#ServiceExcellence', 'label' => 'HANSA In Numbers', 'pill' => 'HANSA IN NUMBERS', 'icon' => '<circle cx="12" cy="12" r="8.5"/><path d="M8.5 12.2l2.4 2.4 4.6-5"/>'],
        ['href' => '#emr_highlights', 'label' => 'Services at-a-glance', 'pill' => 'SERVICES AT-A-GLANCE', 'icon' => '<rect x="4" y="4" width="7" height="7" rx="1"/><rect x="13" y="4" width="7" height="7" rx="1"/><rect x="4" y="13" width="7" height="7" rx="1"/><rect x="13" y="13" width="7" height="7" rx="1"/>'],
        ['href' => '#why_choos_us', 'label' => 'Why to Choose HANSA', 'pill' => 'WHY TO CHOOSE HANSA', 'icon' => '<circle cx="9" cy="9" r="2.6"/><circle cx="15.5" cy="10" r="2.2"/><path d="M4 19c.9-2.6 2.7-4 5-4s4.1 1.4 5 4"/><path d="M14.5 15.4c2.3.2 4 1.5 4.9 3.6"/>'],
        ['href' => '#emr_news', 'label' => 'Service Projects', 'pill' => 'SERVICE PROJECTS', 'icon' => '<path d="M4 20V9l8-5 8 5v11"/><path d="M9 20v-6h6v6"/>'],
        ['href' => '#emr_awards', 'label' => 'News', 'pill' => 'NEWS', 'icon' => '<path d="M6 3h9l4 4v14H6z"/><path d="M9 12h7M9 16h7"/>'],
        ['href' => '#emr_contact', 'label' => 'Contact Us', 'pill' => 'CONTACT US', 'icon' => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2"/>'],
    ];
@endphp
<nav class="home-rail" aria-label="Homepage sections">
    @foreach ($railLinks as $link)
        <a href="{{ url('/') . $link['href'] }}" title="{{ $link['label'] }}" aria-label="{{ $link['label'] }}" data-rail-link>
            <span class="rail-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $link['icon'] !!}</svg></span>
            <span class="home-rail__label">{{ $link['pill'] }}</span>
        </a>
    @endforeach
</nav>
