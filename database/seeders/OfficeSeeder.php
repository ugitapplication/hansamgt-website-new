<?php

namespace Database\Seeders;

use App\Models\Office;
use Illuminate\Database\Seeder;

/**
 * The four offices, taken from the addresses already published on the site.
 *
 * The mobile number and the email address are recorded for the corporate office
 * only. Previously every card repeated the corporate mobile and the corporate
 * email because the Blade loop read neither field per office, which made the
 * three booths look as if they had their own mobile line. They are left empty
 * here rather than guessed, and can be filled in from Settings → Offices.
 */
class OfficeSeeder extends Seeder
{
    public function run(): void
    {
        $offices = [
            [
                'title' => 'Corporate Office',
                'address' => 'Borak Mehnur (Level-18), 51/B Kemal Ataturk Avenue, Banani, Dhaka-1213, Bangladesh',
                'phone' => '+8801708800870',
                'landline' => '88 02 222285116-123',
                'email' => 'info@hansamgt.com',
                'sort' => 1,
            ],
            [
                'title' => 'Service Booth (Midtown)',
                'address' => 'Unique Heights, 117 Kazi Nazrul Islam Avenue, Eskaton, Dhaka, Bangladesh',
                'phone' => null,
                'landline' => '88 02 48322812',
                'email' => null,
                'sort' => 2,
            ],
            [
                'title' => 'Service Booth (Uttara)',
                'address' => 'HANSA- A Premium Residence, House # 3 & 5, Road # 10/A , Sector # 09, Uttara, Dhaka - 1230, Bangladesh',
                'phone' => null,
                'landline' => null,
                'email' => null,
                'sort' => 3,
            ],
            [
                'title' => 'Service Booth (Dhanmondi)',
                'address' => 'Unique Trade Centre , 8 Panthapath, Kawranbazar, Dhaka-1215, Bangladesh',
                'phone' => null,
                'landline' => null,
                'email' => null,
                'sort' => 4,
            ],
        ];

        foreach ($offices as $office) {
            Office::query()->updateOrCreate(
                ['title' => $office['title']],
                [...$office, 'is_active' => true],
            );
        }
    }
}
