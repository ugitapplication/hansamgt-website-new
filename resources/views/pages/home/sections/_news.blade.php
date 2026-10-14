{{-- Latest News (legacy: Award.php; Slick carousel replaced with static grid) --}}
<section id="emr_awards" class="home-section" aria-labelledby="news-heading">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <h2 id="news-heading" class="home-heading">Latest News</h2>
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
    </div>
</section>
