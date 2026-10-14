@extends('layouts.app')

@section('content')
    <div class="page-wrap">
        <h1 class="page-h1 page-h1--center">Our Projects — Buildings &amp; Hotels Managed in Dhaka</h1>
        @php
            $projects = [
                ['img' => 'westin.jpg', 'alt' => 'Westin Dhaka, Gulshan-2, Dhaka', 'title' => 'WESTIN DHAKA', 'location' => 'Gulshan-2, Dhaka', 'cat' => 'ongoing'],
                ['img' => 'Heights.jpg', 'alt' => 'Unique Heights, Eskaton, Dhaka', 'title' => 'UNIQUE HEIGHTS', 'location' => 'Kazi Nazrul Islam Avenue, Eskaton, Dhaka', 'cat' => 'ongoing', 'home' => true],
                ['img' => 'RCBT.jpg', 'alt' => 'Red Crescent Borak Tower, Old Elephant Road, Dhaka', 'title' => 'RED CRESCENT BORAK TOWER', 'location' => 'Old Elephant Road, Dhaka', 'cat' => 'ongoing'],
                ['img' => 'DSC_8353.jpg', 'alt' => 'Unique Oval, Kemal Ataturk Avenue, Banani, Dhaka', 'title' => 'UNIQUE OVAL', 'location' => 'Kemal Ataturk Avenue, Banani, Dhaka', 'cat' => 'ongoing'],
                ['img' => 'utc.jpg', 'alt' => 'Unique Trade Center, Panthapath, Kawran Bazar, Dhaka', 'title' => 'UNIQUE TRADE CENTER', 'location' => 'Panthapath, Kawran Bazar, Dhaka', 'cat' => 'ongoing'],
                ['img' => '150304_DCC_B.RGB_color.jpg', 'alt' => 'Sheraton Dhaka, Kemal Ataturk Avenue, Banani, Dhaka', 'title' => 'SHERATON DHAKA', 'location' => 'Kemal Ataturk Avenue, Banani, Dhaka', 'cat' => 'upcoming', 'home' => true],
                ['img' => 'FINAL70DilkushaPic.jpg', 'alt' => 'Borak Biz Center, Dilkusha, Motijheel, Dhaka', 'title' => 'BORAK BIZ CENTER', 'location' => 'Dilkusha, Motijheel, Dhaka', 'cat' => 'upcoming'],
                ['img' => 'park_valy.jpg', 'alt' => 'Borak Park Valley, Parkroad, Baridhara, Dhaka', 'title' => 'BORAK PARK VALLEY', 'location' => 'Parkroad, Baridhara, Dhaka', 'cat' => 'upcoming', 'home' => true],
                ['img' => 'BORAK_mehnur.jpg', 'alt' => 'Borak Mehnur, Banani, Dhaka', 'title' => 'BORAK MEHNUR', 'location' => 'Banani, Dhaka', 'cat' => 'upcoming', 'home' => true],
                ['img' => 'ZOHIR TOWER_02b.jpg', 'alt' => 'Borak Johir Tower, Panthapath, Kawran Bazar, Dhaka', 'title' => 'BORAK JOHIR TOWER', 'location' => 'Panthapath, Kawran Bazar, Dhaka', 'cat' => 'upcoming'],
                ['img' => 'rendering.jpg', 'alt' => 'Borak Acropolis, Gulshan-2, Dhaka', 'title' => 'BORAK ACROPOLIS', 'location' => 'Gulshan-2, Dhaka', 'cat' => 'upcoming'],
            ];
        @endphp
        <div class="filter-bar" role="group" aria-label="Filter projects">
            <button type="button" data-filter="all" class="is-active">Projects</button>
            <button type="button" data-filter="ongoing">Ongoing</button>
            <button type="button" data-filter="upcoming">Upcoming</button>
        </div>
        <div class="works-grid" id="works-grid">
            @foreach ($projects as $project)
                <article class="work-card" data-cat="{{ $project['cat'] }}">
                    <img src="{{ asset('images/' . (isset($project['home']) ? 'home/projects/' : 'pages/projects/') . $project['img']) }}" alt="{{ $project['alt'] }}"
                         width="350" height="195" loading="lazy" decoding="async">
                    <p class="work-card__cat">{{ $project['cat'] === 'ongoing' ? 'Ongoing' : 'Upcoming' }}</p>
                    <h2 class="work-card__title">{{ $project['title'] }}</h2>
                    <p class="work-card__loc">{{ $project['location'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
    @include('pages.home.sections._slogan')
@endsection

@push('scripts')
<script>
document.querySelectorAll('.filter-bar [data-filter]').forEach(function (btn) {
    btn.addEventListener('click', function () {
        document.querySelectorAll('.filter-bar [data-filter]').forEach(function (b) {
            b.classList.toggle('is-active', b === btn);
        });
        var f = btn.getAttribute('data-filter');
        document.querySelectorAll('#works-grid .work-card').forEach(function (card) {
            card.hidden = f !== 'all' && card.getAttribute('data-cat') !== f;
        });
    });
});
</script>
@endpush
