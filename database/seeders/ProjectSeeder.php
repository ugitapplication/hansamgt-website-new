<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

/**
 * The eleven projects, exactly as they are published today.
 *
 * Sort order matches the Projects page. Four of them are also shown in the
 * homepage "Service Projects" band: they carry `is_featured`, a separate
 * homepage order, and the longer card wording that band has always used.
 *
 * Image paths follow the legacy folder split, so the pages render byte for
 * byte what they rendered before: the four featured photos live under
 * images/home/projects/ and the rest under images/pages/projects/.
 *
 * Every image also exists under images/pages/projects/; the paths are kept as
 * published rather than normalised, because the two copies may not be the same
 * crop.
 */
class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'title' => 'WESTIN DHAKA',
                'location' => 'Gulshan-2, Dhaka',
                'category' => 'ongoing',
                'image' => 'images/pages/projects/westin.jpg',
                'image_alt' => 'Westin Dhaka, Gulshan-2, Dhaka',
                'is_featured' => false,
                'homepage_title' => null,
                'homepage_location' => null,
                'homepage_alt' => null,
                'homepage_sort' => 0,
                'sort' => 1,
            ],
            [
                'title' => 'UNIQUE HEIGHTS',
                'location' => 'Kazi Nazrul Islam Avenue, Eskaton, Dhaka',
                'category' => 'ongoing',
                'image' => 'images/home/projects/Heights.jpg',
                'image_alt' => 'Unique Heights, Eskaton, Dhaka',
                'is_featured' => true,
                'homepage_title' => 'Multi-Storied Commercial Cum Residential Complex Unique Heights',
                'homepage_location' => 'Paribagh, Dhaka',
                'homepage_alt' => 'Unique Heights complex, Paribagh',
                'homepage_sort' => 4,
                'sort' => 2,
            ],
            [
                'title' => 'RED CRESCENT BORAK TOWER',
                'location' => 'Old Elephant Road, Dhaka',
                'category' => 'ongoing',
                'image' => 'images/pages/projects/RCBT.jpg',
                'image_alt' => 'Red Crescent Borak Tower, Old Elephant Road, Dhaka',
                'is_featured' => false,
                'homepage_title' => null,
                'homepage_location' => null,
                'homepage_alt' => null,
                'homepage_sort' => 0,
                'sort' => 3,
            ],
            [
                'title' => 'UNIQUE OVAL',
                'location' => 'Kemal Ataturk Avenue, Banani, Dhaka',
                'category' => 'ongoing',
                'image' => 'images/pages/projects/DSC_8353.jpg',
                'image_alt' => 'Unique Oval, Kemal Ataturk Avenue, Banani, Dhaka',
                'is_featured' => false,
                'homepage_title' => null,
                'homepage_location' => null,
                'homepage_alt' => null,
                'homepage_sort' => 0,
                'sort' => 4,
            ],
            [
                'title' => 'UNIQUE TRADE CENTER',
                'location' => 'Panthapath, Kawran Bazar, Dhaka',
                'category' => 'ongoing',
                'image' => 'images/pages/projects/utc.jpg',
                'image_alt' => 'Unique Trade Center, Panthapath, Kawran Bazar, Dhaka',
                'is_featured' => false,
                'homepage_title' => null,
                'homepage_location' => null,
                'homepage_alt' => null,
                'homepage_sort' => 0,
                'sort' => 5,
            ],
            [
                'title' => 'SHERATON DHAKA',
                'location' => 'Kemal Ataturk Avenue, Banani, Dhaka',
                'category' => 'upcoming',
                'image' => 'images/home/projects/150304_DCC_B.RGB_color.jpg',
                'image_alt' => 'Sheraton Dhaka, Kemal Ataturk Avenue, Banani, Dhaka',
                'is_featured' => true,
                'homepage_title' => '5-Star International Chain Hotel Sheraton Dhaka',
                'homepage_location' => 'Banani, Dhaka',
                'homepage_alt' => 'Sheraton Dhaka hotel project, Banani',
                'homepage_sort' => 1,
                'sort' => 6,
            ],
            [
                'title' => 'BORAK BIZ CENTER',
                'location' => 'Dilkusha, Motijheel, Dhaka',
                'category' => 'upcoming',
                'image' => 'images/pages/projects/FINAL70DilkushaPic.jpg',
                'image_alt' => 'Borak Biz Center, Dilkusha, Motijheel, Dhaka',
                'is_featured' => false,
                'homepage_title' => null,
                'homepage_location' => null,
                'homepage_alt' => null,
                'homepage_sort' => 0,
                'sort' => 7,
            ],
            [
                'title' => 'BORAK PARK VALLEY',
                'location' => 'Parkroad, Baridhara, Dhaka',
                'category' => 'upcoming',
                'image' => 'images/home/projects/park_valy.jpg',
                'image_alt' => 'Borak Park Valley, Parkroad, Baridhara, Dhaka',
                'is_featured' => true,
                'homepage_title' => 'High-Rise Modern Residential Complex Borak Park Valley',
                'homepage_location' => 'Baridhara Diplomatic Zone, Dhaka',
                'homepage_alt' => 'Borak Park Valley residential complex, Baridhara',
                'homepage_sort' => 3,
                'sort' => 8,
            ],
            [
                'title' => 'BORAK MEHNUR',
                'location' => 'Banani, Dhaka',
                'category' => 'upcoming',
                'image' => 'images/home/projects/BORAK_mehnur.jpg',
                'image_alt' => 'Borak Mehnur, Banani, Dhaka',
                'is_featured' => true,
                'homepage_title' => 'Multi-Storied Commercial Complex Borak Mehnur',
                'homepage_location' => 'Banani, Dhaka',
                'homepage_alt' => 'Borak Mehnur commercial complex, Banani',
                'homepage_sort' => 2,
                'sort' => 9,
            ],
            [
                'title' => 'BORAK JOHIR TOWER',
                'location' => 'Panthapath, Kawran Bazar, Dhaka',
                'category' => 'upcoming',
                'image' => 'images/pages/projects/ZOHIR TOWER_02b.jpg',
                'image_alt' => 'Borak Johir Tower, Panthapath, Kawran Bazar, Dhaka',
                'is_featured' => false,
                'homepage_title' => null,
                'homepage_location' => null,
                'homepage_alt' => null,
                'homepage_sort' => 0,
                'sort' => 10,
            ],
            [
                'title' => 'BORAK ACROPOLIS',
                'location' => 'Gulshan-2, Dhaka',
                'category' => 'upcoming',
                'image' => 'images/pages/projects/rendering.jpg',
                'image_alt' => 'Borak Acropolis, Gulshan-2, Dhaka',
                'is_featured' => false,
                'homepage_title' => null,
                'homepage_location' => null,
                'homepage_alt' => null,
                'homepage_sort' => 0,
                'sort' => 11,
            ],
        ];

        foreach ($projects as $project) {
            Project::query()->updateOrCreate(
                ['title' => $project['title']],
                [...$project, 'is_active' => true],
            );
        }
    }
}
