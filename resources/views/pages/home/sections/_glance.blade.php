{{-- Services at-a-glance (legacy: ServiceDelivery.php vc-hoverbox flip cards) --
     Managed under Homepage Management → Homepage Sections. The built-in cards
     below are the fallback so the band still renders before the table is
     seeded. --}}
@php
    $glanceCards = ($glanceCards ?? []);

    if (empty($glanceCards)) {
        $glanceCards = [
            ['src' => asset('images/home/glance/p-13.jpg'), 'title' => 'Hospitality', 'text' => 'We are the only integrated hotel & facility management company in the country that provides experienced associates in 5-STAR international luxury hotels.'],
            ['src' => asset('images/home/glance/p-5.jpg'), 'title' => 'MEP', 'text' => 'Our engineering teams create safe, productive environments and ensure your assets function optimally throughout their lifecycle from building services to energy management.'],
            ['src' => asset('images/home/glance/p-6.jpg'), 'title' => 'Cleaning', 'text' => 'Everyday our custom cleaning services keep people healthy and safe, nurture wellbeing and shape strong and reputable workplaces and expertly trained people to grant you peace of mind.'],
            ['src' => asset('images/home/glance/p-29.jpg'), 'title' => 'Façade Cleaning', 'text' => 'We have highly trained and experienced workforce with all necessary machine tools and safety equipment’s for façade cleaning.'],
            ['src' => asset('images/home/glance/p-10.jpg'), 'title' => 'Security', 'text' => 'Our security services are key to creating a welcoming and productive environment with intelligent solutions that meet today’s high standards, we ensure that people and places are safe.'],
            ['src' => asset('images/home/glance/p-25.jpg'), 'title' => 'Pest Control', 'text' => 'Our pest control service includes bed bugs, moths, crickets, fleas, hornets, scorpions, silverfish, carpenter ants, roaches, mice, spiders, centipedes, earwigs, ticks, etc.'],
            ['src' => asset('images/home/glance/p-2.jpg'), 'title' => 'Parking Management', 'text' => 'Parking management includes self-ticketing, enforcement, parking planning, maintenance, parking equipment, supplies, line painting services, letting spaces and garage maintenance.'],
            ['src' => asset('images/home/glance/p-16.jpg'), 'title' => 'Front Office', 'text' => 'HANSA provides experienced male/female executives to manage front office operations by welcoming the guests, providing information, operating PABX & maintaining log books.'],
        ];
    }
@endphp
<section id="emr_highlights" class="home-section" aria-labelledby="glance-heading">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <h2 id="glance-heading" class="home-heading">Services at-a-glance</h2>
        <div class="mt-8 grid grid-cols-1 gap-4 md:grid-cols-4">
            @foreach ($glanceCards as $card)
                <div class="vc-hoverbox-wrapper vc-hoverbox-shape--square vc-hoverbox-align--center vc-hoverbox-direction--default vc-hoverbox-width--100" tabindex="0">
                    <div class="vc-hoverbox">
                        <div class="vc-hoverbox-inner">
                            <div class="vc-hoverbox-block vc-hoverbox-front" style="background-image: url('{{ $card['src'] }}');" role="img" aria-label="{{ $card['title'] }} service image">
                                <div class="vc-hoverbox-block-inner vc-hoverbox-front-inner"></div>
                            </div>
                            <div class="vc-hoverbox-block vc-hoverbox-back" style="background-color: #f2f2f2;">
                                <div class="vc-hoverbox-block-inner vc-hoverbox-back-inner">
                                    <h3 class="project-hover-heading">{{ $card['title'] }}</h3>
                                    <p class="project-hover-text">{{ $card['text'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
