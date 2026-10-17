{{-- Slogan band below the slider (legacy: Slider.php CTA + particlesJS) --}}
<section class="home-slogan" aria-label="A concern of Unique Group">
    <canvas id="slogan-particles" aria-hidden="true"></canvas>
    <div class="home-slogan__inner">
        <p class="home-slogan__concern"><span>a concern of</span> Unique Group</p>
        <p class="home-slogan__phone"><a href="{{ \App\Support\Settings::phoneHref() }}">&#9742; {{ \App\Support\Settings::phoneDisplay() }}</a></p>
    </div>
</section>
