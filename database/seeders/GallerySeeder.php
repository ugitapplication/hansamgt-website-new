<?php

namespace Database\Seeders;

use App\Models\GalleryImage;
use Illuminate\Database\Seeder;

/**
 * The twelve Site Management gallery photos, in their published order.
 *
 * The caption doubles as the image alt text, exactly as the Blade partial did.
 */
class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $photos = [
            ['image' => 'images/pages/gallery/team.jpg', 'caption' => 'Top Management'],
            ['image' => 'images/pages/gallery/IMG_9572.JPG', 'caption' => 'Training Session'],
            ['image' => 'images/pages/gallery/IMG_3555.JPG', 'caption' => 'Training Session'],
            ['image' => 'images/pages/gallery/IMG_9849.JPG', 'caption' => 'Team Work'],
            ['image' => 'images/pages/gallery/IMG_9320.JPG', 'caption' => 'Hospitality'],
            ['image' => 'images/pages/gallery/IMG_9712.JPG', 'caption' => 'Meetup With Management'],
            ['image' => 'images/pages/gallery/IMG_9402.JPG', 'caption' => 'Temperature Measurement'],
            ['image' => 'images/pages/gallery/IMG_9440.JPG', 'caption' => 'Security First'],
            ['image' => 'images/pages/gallery/IMG_9446.JPG', 'caption' => 'Hospitality'],
            ['image' => 'images/pages/gallery/IMG_9455.JPG', 'caption' => 'Customer Service'],
            ['image' => 'images/pages/gallery/IMG_9477.JPG', 'caption' => 'Customer Satisfaction'],
            ['image' => 'images/pages/gallery/IMG_9498.JPG', 'caption' => 'Customer Reception'],
        ];

        foreach ($photos as $index => $photo) {
            GalleryImage::query()->updateOrCreate(
                ['image' => $photo['image']],
                [...$photo, 'sort' => $index + 1, 'is_active' => true],
            );
        }
    }
}
