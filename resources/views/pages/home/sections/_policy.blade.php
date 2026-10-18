{{-- Info boxes with expanding hover overlay (legacy: WorkPolicy.php info_box style_3) --}}
{{-- Managed under Homepage Management → Homepage Sections. The built-in cards
     below are the fallback so the band still renders before the table is
     seeded. Icons stay here: they were traced from the legacy stm font
     (stm-polygon \e929, stm-user7 \e935, stm-stm14_bulb \e949) and the item
     only stores which one to draw. --}}
@php
    $commitments = ($commitments ?? []);

    if (empty($commitments)) {
        $commitments = [
            ['src' => asset('images/home/policy/p-1.jpg'), 'alt' => 'OperationalExcellence', 'icon' => 'polygon', 'title' => 'Covid-19 Precautionary Measures', 'body' => 'Our operational delivery and practices adhere to worldwide standards and policies, awarded and accredited by international bodies.'],
            ['src' => asset('images/home/policy/safety.jpg'), 'alt' => 'SafetyFirst', 'icon' => 'safety', 'title' => 'Safety First', 'body' => 'Flawless safety record with daily objectives to achieve zero accident, HANSA applies an absolute safety policy to all employees in activities and projects.'],
            ['src' => asset('images/home/policy/never_sleep.jpg'), 'alt' => 'FutureReady (6)', 'icon' => 'bulb', 'title' => 'HANSA Never Sleeps', 'body' => 'From the security and surveillance team, reception, housekeeping, elevator operator, electrical, mechanical and plumbing team, landscaping, pest management; Our dedicated team is always there for you regardless of the day or night, 24/7 or 365 days a day.'],
        ];
    }

    $commitmentIcons = [
        'polygon' => '<svg viewBox="0 0 100 100" fill="currentColor" fill-rule="evenodd" aria-hidden="true"><path d="M54 26L42 46.8 18 46.8 6 26 18 5.2 42 5.2Z M39.6 26L34.8 34.3 25.2 34.3 20.4 26 25.2 17.7 34.8 17.7Z M54 72L42 92.8 18 92.8 6 72 18 51.2 42 51.2Z M39.6 72L34.8 80.3 25.2 80.3 20.4 72 25.2 63.7 34.8 63.7Z M92 49L80 69.8 56 69.8 44 49 56 28.2 80 28.2Z M77.6 49L72.8 57.3 63.2 57.3 58.4 49 63.2 40.7 72.8 40.7Z"/></svg>',
        'safety' => '<svg viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M26 36C24 20 32 6 50 6 68 6 76 20 74 36 73 46 61 52 50 52 39 52 27 46 26 36Z"/><path d="M29 24C37 15 63 15 71 24"/><path d="M26 33c-4 0-4 8 0 9M74 33c4 0 4 8 0 9"/><path d="M41 52L41 58M59 52L59 58"/><path d="M6 91L6 79C6 68 20 60 41 58L50 65 59 58C80 60 94 68 94 79L94 91Z"/><path d="M46 65h8v6h-8zM47.5 71h5L50 82z"/><path d="M30 70v9M70 70v9"/></svg>',
        'bulb' => '<svg viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M50 12C36 12 27 23 27 36 27 44 31 49 35 53L35 60 65 60 65 53C69 49 73 44 73 36 73 23 64 12 50 12Z"/><path d="M35 60V76Q35 86 45 86L55 86Q65 86 65 76V60"/><path d="M35 66H65M35 72H65"/><path d="M57 27L40 52 49 52 44 68 61 41 51 41Z" fill="currentColor" stroke="none"/><path d="M50 1v7M29 7l-5-5M71 7l5-5M19 33h-8M89 33h-8M30 55l-6 6M70 55l6 6"/></svg>',
    ];
@endphp
<section class="home-section" aria-label="Our commitments">
    <div class="mx-auto grid max-w-7xl gap-6 px-4 sm:px-6 md:grid-cols-3">
        @foreach ($commitments as $commitment)
            <article class="policy-card" tabindex="0" data-reveal>
                <img src="{{ $commitment['src'] }}" alt="{{ $commitment['alt'] }}"
                     width="600" height="400" loading="lazy" decoding="async" class="policy-card__img">
                <div class="policy-card__overlay">
                    <div class="policy-card__head">
                        {!! $commitmentIcons[$commitment['icon']] ?? '' !!}
                        <h2 class="policy-card__title">{{ $commitment['title'] }}</h2>
                    </div>
                    <p class="policy-card__desc">{{ $commitment['body'] }}</p>
                </div>
            </article>
        @endforeach
    </div>
</section>
