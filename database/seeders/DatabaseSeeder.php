<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call(HeroSlideSeeder::class);
        $this->call(ClientSeeder::class);
        $this->call(ConcernSeeder::class);
        $this->call(OfficeSeeder::class);
        $this->call(InsightsSeeder::class);
        $this->call(ProjectSeeder::class);
        $this->call(GallerySeeder::class);
        $this->call(TeamSeeder::class);
        $this->call(HomepageSectionSeeder::class);
    }
}
