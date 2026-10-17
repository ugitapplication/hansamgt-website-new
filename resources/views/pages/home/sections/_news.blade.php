{{-- Latest News — CMS-driven from Insights. Falls back to the legacy static cards
     when no articles are published yet (or before the posts table exists). --}}
@php($latest = collect($latestPosts ?? [])->take(2))
<section id="emr_awards" class="home-section" aria-labelledby="news-heading">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <h2 id="news-heading" class="home-heading">Latest News</h2>

        @if ($latest->isNotEmpty())
            <div class="mt-8 grid gap-6 md:grid-cols-2">
                @foreach ($latest as $post)
                    <article class="news-card">
                        <a href="{{ route('insights.show', ['slug' => $post->slug]) }}" aria-label="{{ $post->title }}">
                            @if ($post->coverImageUrl() !== '')
                                <img src="{{ $post->coverImageUrl() }}"
                                     alt="{{ $post->cover_image_alt ?: $post->title }}"
                                     width="450" height="300" loading="lazy" decoding="async">
                            @endif
                        </a>
                        <div class="news-card__body">
                            <h3><a href="{{ route('insights.show', ['slug' => $post->slug]) }}">{{ $post->title }}</a></h3>
                            <p>{{ $post->summary(110) }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
            <p class="mt-6">
                <a class="font-semibold text-hansa-navy underline decoration-hansa-gold decoration-2 underline-offset-4 hover:text-hansa-gold" href="{{ url('insights') }}">All insights</a>
            </p>
        @else
            <div class="mt-8 grid gap-6 md:grid-cols-2">
                <article class="news-card">
                    <a href="{{ url('cleaning-white-glove-housekeeping') }}" aria-label="General Cleaning Training">
                        <img src="{{ asset('images/home/news/news3.jpg') }}" alt="General cleaning training session"
                             width="450" height="300" loading="lazy" decoding="async">
                    </a>
                    <div class="news-card__body">
                        <h3><a href="{{ url('cleaning-white-glove-housekeeping') }}">General Cleaning Training</a></h3>
                        <p>Conduct General Cleaning Training to HML Housekeeping Associates</p>
                    </div>
                </article>
                <article class="news-card">
                    <a href="{{ url('security-fire-life-safety') }}" aria-label="Fire Fighting Training">
                        <img src="{{ asset('images/home/news/news2.jpg') }}" alt="Hansa Highly commended fire fighting training"
                             width="450" height="300" loading="lazy" decoding="async">
                    </a>
                    <div class="news-card__body">
                        <h3><a href="{{ url('security-fire-life-safety') }}">Fire Fighting Training</a></h3>
                        <p>Conduct Fire Fighting Training to Security Team of HANSA Management Ltd.</p>
                    </div>
                </article>
            </div>
        @endif
    </div>
</section>
