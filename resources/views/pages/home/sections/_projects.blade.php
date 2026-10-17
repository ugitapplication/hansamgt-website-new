{{-- Service Projects (legacy: News.php) — the Projects rows flagged "Show on the
     homepage", in their own order. The built-in list below is the fallback so
     the band still renders before the table is seeded. --}}
@php
    $projects = ($projects ?? []);

    if (empty($projects)) {
        $projects = [
            ['src' => asset('images/home/projects/150304_DCC_B.RGB_color.jpg'), 'alt' => 'Sheraton Dhaka hotel project, Banani', 'title' => '5-Star International Chain Hotel Sheraton Dhaka', 'location' => 'Banani, Dhaka'],
            ['src' => asset('images/home/projects/BORAK_mehnur.jpg'), 'alt' => 'Borak Mehnur commercial complex, Banani', 'title' => 'Multi-Storied Commercial Complex Borak Mehnur', 'location' => 'Banani, Dhaka'],
            ['src' => asset('images/home/projects/park_valy.jpg'), 'alt' => 'Borak Park Valley residential complex, Baridhara', 'title' => 'High-Rise Modern Residential Complex Borak Park Valley', 'location' => 'Baridhara Diplomatic Zone, Dhaka'],
            ['src' => asset('images/home/projects/Heights.jpg'), 'alt' => 'Unique Heights complex, Paribagh', 'title' => 'Multi-Storied Commercial Cum Residential Complex Unique Heights', 'location' => 'Paribagh, Dhaka'],
        ];
    }
@endphp
<section id="emr_news" class="home-section" aria-labelledby="projects-heading">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <h2 id="projects-heading" class="home-heading">Service Projects</h2>
        <p class="mt-2 text-left"><a class="home-viewall" href="{{ url('projects') }}">+ view all</a></p>
        <div class="mt-8 grid grid-cols-1 gap-[30px] sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($projects as $project)
                <article class="project-card">
                    <div class="project-card__media">
                        <a href="{{ url('projects') }}" aria-label="{{ $project['title'] }}">
                            <img src="{{ $project['src'] }}" alt="{{ $project['alt'] }}"
                                 width="400" height="353" loading="lazy" decoding="async">
                        </a>
                    </div>
                    <div class="project-card__body">
                        <h5><a href="{{ url('projects') }}">{{ $project['title'] }}</a></h5>
                        <p>{{ $project['location'] }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
