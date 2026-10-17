<?php

namespace Database\Seeders;

use App\Models\Concern;
use Illuminate\Database\Seeder;

class ConcernSeeder extends Seeder
{
    /**
     * Seed business concerns & associates with the logos currently
     * hard-coded on the public homepage
     * (resources/views/pages/home/sections/_concerns.blade.php).
     *
     * No content is invented. Idempotent and never touches is_active.
     */
    public function run(): void
    {
        $concerns = [
            ['name' => 'Unique Eastern', 'image' => 'images/home/concerns/unique-eastern.jpg', 'alt' => 'Unique Eastern'],
            ['name' => 'Gulshan Clinic', 'image' => 'images/home/concerns/gulshan-clinic.jpg', 'alt' => 'Gulshan Clinic'],
            ['name' => 'Canadian University of Bangladesh', 'image' => 'images/home/concerns/canadian-university.jpg', 'alt' => 'Canadian University of Bangladesh'],
            ['name' => 'Update College', 'image' => 'images/home/concerns/update-college.jpg', 'alt' => 'Update College'],
            ['name' => 'Update Institute', 'image' => 'images/home/concerns/update-institute.jpg', 'alt' => 'Update Institute'],
            ['name' => 'Arial Dairy and Agro', 'image' => 'images/home/concerns/arial-dairy.jpg', 'alt' => 'Arial Dairy and Agro'],
            ['name' => 'Unique Share Management', 'image' => 'images/home/concerns/unique-share-mgt.jpg', 'alt' => 'Unique Share Management'],
            ['name' => 'Borak Ready Mix Concrete', 'image' => 'images/home/concerns/borak-readymix.jpg', 'alt' => 'Borak Ready Mix Concrete'],
            ['name' => 'Amader Somoy', 'image' => 'images/home/concerns/amader-somoy.jpg', 'alt' => 'Amader Somoy'],
            ['name' => 'The Business Post', 'image' => 'images/home/concerns/business-post.jpg', 'alt' => 'The Business Post'],
            ['name' => 'Unique Vocational Training Center', 'image' => 'images/home/concerns/unique-vocational-training-center-logo.jpg', 'alt' => 'Unique Vocational Training Center'],
            ['name' => 'Borak Polytechnic Institute', 'image' => 'images/home/concerns/borak-polytechnic-logo.jpg', 'alt' => 'Borak Polytechnic Institute'],
            ['name' => 'My Sys Technology', 'image' => 'images/home/concerns/my-sis-tech-logo.jpg', 'alt' => 'My Sys Technology'],
        ];

        foreach ($concerns as $order => $concern) {
            Concern::query()->updateOrCreate(
                ['image' => $concern['image']],
                [
                    'name' => $concern['name'],
                    'alt' => $concern['alt'],
                    'sort_order' => $order + 1,
                ]
            );
        }
    }
}
