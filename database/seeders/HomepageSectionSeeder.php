<?php

namespace Database\Seeders;

use App\Models\HomepageSectionItem;
use Illuminate\Database\Seeder;

/**
 * The repeating items inside four of the homepage's own sections.
 *
 * The wording is transcribed exactly from the Blade partials that used to hold
 * it. Two homepage bands are deliberately NOT here:
 *
 *   - "Our Services" cards — they belong to the Services content type;
 *   - "Service Projects" — that band reads the featured Projects rows.
 *
 * Sort numbers are unique across the whole table so the admin list reads in
 * section order without grouping: commitments 10+, numbers 110+, glance 210+,
 * why 310+ (left column) and 410+ (right column).
 */
class HomepageSectionSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            // ------------------------------------------------------ Our commitments
            [
                'section' => 'commitments',
                'sort' => 10,
                'image' => 'images/home/policy/p-1.jpg',
                'image_alt' => 'OperationalExcellence',
                'icon' => 'polygon',
                'title' => 'Covid-19 Precautionary Measures',
                'body' => 'Our operational delivery and practices adhere to worldwide standards and policies, awarded and accredited by international bodies.',
            ],
            [
                'section' => 'commitments',
                'sort' => 20,
                'image' => 'images/home/policy/safety.jpg',
                'image_alt' => 'SafetyFirst',
                'icon' => 'safety',
                'title' => 'Safety First',
                'body' => 'Flawless safety record with daily objectives to achieve zero accident, HANSA applies an absolute safety policy to all employees in activities and projects.',
            ],
            [
                'section' => 'commitments',
                'sort' => 30,
                'image' => 'images/home/policy/never_sleep.jpg',
                'image_alt' => 'FutureReady (6)',
                'icon' => 'bulb',
                'title' => 'HANSA Never Sleeps',
                'body' => 'From the security and surveillance team, reception, housekeeping, elevator operator, electrical, mechanical and plumbing team, landscaping, pest management; Our dedicated team is always there for you regardless of the day or night, 24/7 or 365 days a day.',
            ],

            // ------------------------------------------------------ HANSA in Numbers
            [
                'section' => 'numbers',
                'sort' => 110,
                'value' => 'No 1',
                'title' => 'Integrated Hospitality & Facility Management Company',
            ],
            [
                'section' => 'numbers',
                'sort' => 120,
                'value' => '1000+',
                'title' => 'Employees',
            ],
            [
                // Counted from the founding year so the figure never goes stale.
                'section' => 'numbers',
                'sort' => 130,
                'value_from_year' => 2015,
                'title' => 'Years in operation',
            ],
            [
                'section' => 'numbers',
                'sort' => 140,
                'value' => '120+',
                'title' => 'Happy Customers',
            ],
            [
                'section' => 'numbers',
                'sort' => 150,
                'value' => '24/7',
                'title' => 'One-Stop Service',
            ],

            // ------------------------------------------------------ Services at-a-glance
            [
                'section' => 'glance',
                'sort' => 210,
                'image' => 'images/home/glance/p-13.jpg',
                'title' => 'Hospitality',
                'body' => 'We are the only integrated hotel & facility management company in the country that provides experienced associates in 5-STAR international luxury hotels.',
            ],
            [
                'section' => 'glance',
                'sort' => 220,
                'image' => 'images/home/glance/p-5.jpg',
                'title' => 'MEP',
                'body' => 'Our engineering teams create safe, productive environments and ensure your assets function optimally throughout their lifecycle from building services to energy management.',
            ],
            [
                'section' => 'glance',
                'sort' => 230,
                'image' => 'images/home/glance/p-6.jpg',
                'title' => 'Cleaning',
                'body' => 'Everyday our custom cleaning services keep people healthy and safe, nurture wellbeing and shape strong and reputable workplaces and expertly trained people to grant you peace of mind.',
            ],
            [
                'section' => 'glance',
                'sort' => 240,
                'image' => 'images/home/glance/p-29.jpg',
                'title' => 'Façade Cleaning',
                'body' => 'We have highly trained and experienced workforce with all necessary machine tools and safety equipment’s for façade cleaning.',
            ],
            [
                'section' => 'glance',
                'sort' => 250,
                'image' => 'images/home/glance/p-10.jpg',
                'title' => 'Security',
                'body' => 'Our security services are key to creating a welcoming and productive environment with intelligent solutions that meet today’s high standards, we ensure that people and places are safe.',
            ],
            [
                'section' => 'glance',
                'sort' => 260,
                'image' => 'images/home/glance/p-25.jpg',
                'title' => 'Pest Control',
                'body' => 'Our pest control service includes bed bugs, moths, crickets, fleas, hornets, scorpions, silverfish, carpenter ants, roaches, mice, spiders, centipedes, earwigs, ticks, etc.',
            ],
            [
                'section' => 'glance',
                'sort' => 270,
                'image' => 'images/home/glance/p-2.jpg',
                'title' => 'Parking Management',
                'body' => 'Parking management includes self-ticketing, enforcement, parking planning, maintenance, parking equipment, supplies, line painting services, letting spaces and garage maintenance.',
            ],
            [
                'section' => 'glance',
                'sort' => 280,
                'image' => 'images/home/glance/p-16.jpg',
                'title' => 'Front Office',
                'body' => 'HANSA provides experienced male/female executives to manage front office operations by welcoming the guests, providing information, operating PABX & maintaining log books.',
            ],

            // ---------------------------------------- Why to choose HANSA — left column
            ['section' => 'why', 'sort' => 310, 'group' => '1', 'body' => 'Experienced in maintaining multistoried complexes'],
            ['section' => 'why', 'sort' => 320, 'group' => '1', 'body' => 'Assure value addition to the property'],
            ['section' => 'why', 'sort' => 330, 'group' => '1', 'body' => 'One-stop service'],
            ['section' => 'why', 'sort' => 340, 'group' => '1', 'body' => 'Cost effective facility management services with no hidden cost'],
            ['section' => 'why', 'sort' => 350, 'group' => '1', 'body' => '100% transparency'],
            ['section' => 'why', 'sort' => 360, 'group' => '1', 'body' => '24/7 or 365 days round the clock'],

            // --------------------------------------- Why to choose HANSA — right column
            ['section' => 'why', 'sort' => 410, 'group' => '2', 'body' => 'A complete hospitality & facility management service provider'],
            ['section' => 'why', 'sort' => 420, 'group' => '2', 'body' => 'Experienced in maintenance of the world class amenities'],
            ['section' => 'why', 'sort' => 430, 'group' => '2', 'body' => 'Experienced civil and EME engineers services'],
            ['section' => 'why', 'sort' => 440, 'group' => '2', 'body' => 'Instant support in case of emergency'],
            ['section' => 'why', 'sort' => 450, 'group' => '2', 'body' => 'Surveillance from corporate office team'],
            ['section' => 'why', 'sort' => 460, 'group' => '2', 'body' => 'Well-equipped training centre with expert trainers'],
        ];

        foreach ($items as $item) {
            HomepageSectionItem::query()->updateOrCreate(
                ['section' => $item['section'], 'sort' => $item['sort']],
                [...$item, 'is_active' => true],
            );
        }
    }
}
