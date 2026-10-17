{{-- Why To Choose HANSA (legacy: WhyChooseUs.php) — managed under Homepage
     Management → Homepage Sections. The bullets below are the fallback so the
     band still renders before the table is seeded. --}}
@php
    $whyPoints = ($whyPoints ?? []);

    if (empty($whyPoints)) {
        $whyPoints = [
            ['text' => 'Experienced in maintaining multistoried complexes', 'group' => '1'],
            ['text' => 'Assure value addition to the property', 'group' => '1'],
            ['text' => 'One-stop service', 'group' => '1'],
            ['text' => 'Cost effective facility management services with no hidden cost', 'group' => '1'],
            ['text' => '100% transparency', 'group' => '1'],
            ['text' => '24/7 or 365 days round the clock', 'group' => '1'],
            ['text' => 'A complete hospitality & facility management service provider', 'group' => '2'],
            ['text' => 'Experienced in maintenance of the world class amenities', 'group' => '2'],
            ['text' => 'Experienced civil and EME engineers services', 'group' => '2'],
            ['text' => 'Instant support in case of emergency', 'group' => '2'],
            ['text' => 'Surveillance from corporate office team', 'group' => '2'],
            ['text' => 'Well-equipped training centre with expert trainers', 'group' => '2'],
        ];
    }

    $whyColumns = [
        '1' => array_values(array_filter($whyPoints, fn (array $point): bool => ($point['group'] ?? '1') === '1')),
        '2' => array_values(array_filter($whyPoints, fn (array $point): bool => ($point['group'] ?? '1') === '2')),
    ];
@endphp
<section id="why_choos_us" class="home-band"
         style="background-image: url('{{ asset('images/home/bg/why.jpg') }}');"
         aria-labelledby="why-heading">
    <div class="home-band__shade" aria-hidden="true"></div>
    <div class="home-band__inner">
        <h2 id="why-heading" class="home-heading home-heading--light">Why To Choose HANSA</h2>
        <div class="mt-8 grid gap-x-10 gap-y-3 text-hansa-gold md:grid-cols-2">
            @foreach ($whyColumns as $column)
                <ul class="why-list">
                    @foreach ($column as $point)
                        <li>{{ $point['text'] }}</li>
                    @endforeach
                </ul>
            @endforeach
        </div>
    </div>
</section>
