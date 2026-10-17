<?php

namespace Database\Seeders;

use App\Models\HeroSlide;
use Illuminate\Database\Seeder;

class HeroSlideSeeder extends Seeder
{
    /**
     * Seed the hero slider with the slides currently hard-coded on the
     * public homepage (resources/views/pages/home/sections/_hero.blade.php).
     *
     * No content is invented: images, alt texts and order mirror the live
     * site. Idempotent: safe to re-run without duplicating rows, and it
     * never touches the is_active flag so admin choices are preserved.
     */
    public function run(): void
    {
        $slides = [
            [
                'label' => 'Housekeeping',
                'image' => 'images/home/slider/hotel-housekeeping-bed-making-hansa-management.jpg',
                'alt' => 'HANSA housekeeping staff making a bed in a hotel guest room',
                'sort_order' => 1,
            ],
            [
                'label' => 'Team photo',
                'image' => 'images/home/slider/hansa-management-team-group-photo.jpg',
                'alt' => 'HANSA Management team group photo in a hotel restaurant',
                'sort_order' => 2,
            ],
            [
                'label' => 'Security inspection',
                'image' => 'images/home/slider/security-guards-uniform-inspection-hansa-management.jpg',
                'alt' => 'HANSA security guards in uniform lined up for inspection by two managers',
                'sort_order' => 3,
            ],
            [
                'label' => 'Cleaning training',
                'image' => 'images/home/slider/cleaning-building-hansa-management.jpg',
                'alt' => 'HANSA staff in hard hats during cleaning building hose training',
                'sort_order' => 4,
            ],
            [
                'label' => 'Floor scrubbing',
                'image' => 'images/home/slider/floor-cleaning-machine-terrace-hansa-management.jpg',
                'alt' => 'Two HANSA cleaning staff polishing a stone terrace floor with a scrubbing machine and wet vacuum',
                'sort_order' => 5,
            ],
            [
                'label' => 'Employee training',
                'image' => 'images/home/slider/hansa-employee-training.jpg',
                'alt' => 'HANSA Management Ltd. hospitality and facility management services',
                'sort_order' => 6,
            ],
            [
                'label' => 'Front office',
                'image' => 'images/home/slider/front-office-reception-desk-hansa-management.jpg',
                'alt' => 'HANSA front office executive at a reception desk with telephone and microphone',
                'sort_order' => 7,
            ],
        ];

        foreach ($slides as $slide) {
            HeroSlide::query()->updateOrCreate(
                ['image' => $slide['image']],
                [
                    'label' => $slide['label'],
                    'alt' => $slide['alt'],
                    'sort_order' => $slide['sort_order'],
                ]
            );
        }
    }
}
