{{-- Company sidebar (legacy: AboutRightMenu.php) --}}
@php
    $aboutLinks = [
        ['url' => 'company-profile', 'label' => 'Company Profile'],
        ['url' => 'charter-of-unique-group', 'label' => 'Charter of Unique Group'],
        ['url' => 'management', 'label' => 'Management'],
        ['url' => 'mission-vision', 'label' => 'Vision & Mission'],
        ['url' => 'corporate-social-responsibility', 'label' => 'CSR'],
    ];
    $aboutActive = $active ?? '';
@endphp
<aside class="side-menu" aria-label="About HANSA">
    @foreach ($aboutLinks as $link)
        <a href="{{ url($link['url']) }}" class="{{ $aboutActive === $link['url'] ? 'is-active' : '' }}">{{ $link['label'] }}</a>
    @endforeach
</aside>
