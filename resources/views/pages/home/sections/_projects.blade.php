{{-- Service Projects (legacy: News.php) --}}
@php
    $projects = [
        ['img' => '150304_DCC_B.RGB_color.jpg', 'alt' => 'Sheraton Dhaka hotel project, Banani', 'title' => '5-Star International Chain Hotel Sheraton Dhaka', 'location' => 'Banani, Dhaka'],
        ['img' => 'BORAK_mehnur.jpg', 'alt' => 'Borak Mehnur commercial complex, Banani', 'title' => 'Multi-Storied Commercial Complex Borak Mehnur', 'location' => 'Banani, Dhaka'],
        ['img' => 'park_valy.jpg', 'alt' => 'Borak Park Valley residential complex, Baridhara', 'title' => 'High-Rise Modern Residential Complex Borak Park Valley', 'location' => 'Baridhara Diplomatic Zone, Dhaka'],
        ['img' => 'Heights.jpg', 'alt' => 'Unique Heights complex, Paribagh', 'title' => 'Multi-Storied Commercial Cum Residential Complex Unique Heights', 'location' => 'Paribagh, Dhaka'],
    ];
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
                            <img src="{{ asset('images/home/projects/' . $project['img']) }}" alt="{{ $project['alt'] }}"
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
