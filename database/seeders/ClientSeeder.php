<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Seed valued clients with the logos currently hard-coded on the
     * public homepage (resources/views/pages/home/sections/_clients.blade.php).
     *
     * No content is invented. Idempotent and never touches is_active.
     */
    public function run(): void
    {
        $clients = [
            ['name' => 'The Westin Dhaka', 'image' => 'images/home/clients/westin-dhaka-logo.png', 'alt' => 'The Westin Dhaka'],
            ['name' => 'Sheraton Dhaka', 'image' => 'images/home/clients/sheraton-dhaka-logo.png', 'alt' => 'Sheraton Dhaka'],
            ['name' => 'HANSA – A Premium Residence', 'image' => 'images/home/clients/hansa-hotel.png', 'alt' => 'HANSA – A Premium Residence'],
            ['name' => 'Regus Dhaka', 'image' => 'images/home/clients/regus.png', 'alt' => 'Regus Dhaka'],
            ['name' => 'UAE Xchange', 'image' => 'images/home/clients/uae-xchange.png', 'alt' => 'UAE Xchange'],
            ['name' => 'Unique MeghnaGhat', 'image' => 'images/home/clients/unique-meghnaghat.png', 'alt' => 'Unique MeghnaGhat'],
            ['name' => 'Sure Cash', 'image' => 'images/home/clients/sure-cash.png', 'alt' => 'Sure Cash'],
            ['name' => 'NIPRO JMI PHARMA', 'image' => 'images/home/clients/nipro-jmi-pharma.png', 'alt' => 'NIPRO JMI PHARMA'],
            ['name' => 'Electricity Generation Company of Bangladesh', 'image' => 'images/home/clients/electricity-generation-company.png', 'alt' => 'Electricity Generation Company of Bangladesh'],
            ['name' => 'Lidl', 'image' => 'images/home/clients/lidl.jpeg', 'alt' => 'Lidl'],
            ['name' => 'Rupayan City', 'image' => 'images/home/clients/rupayancity.jpeg', 'alt' => 'Rupayan City'],
            ['name' => 'Table', 'image' => 'images/home/clients/table.jpeg', 'alt' => 'Table'],
            ['name' => 'UCC', 'image' => 'images/home/clients/ucc.jpeg', 'alt' => 'UCC'],
            ['name' => 'VF', 'image' => 'images/home/clients/vf.jpeg', 'alt' => 'VF'],
            ['name' => 'United Hospital', 'image' => 'images/home/clients/united-hostipital.jpg', 'alt' => 'United Hospital'],
            ['name' => 'Hyatt Place', 'image' => 'images/home/clients/hyatt-place.jpg', 'alt' => 'Hyatt Place'],
            ['name' => 'IN', 'image' => 'images/home/clients/in.jpg', 'alt' => 'IN'],
            ['name' => 'foodpanda', 'image' => 'images/home/clients/foodpanda.jpg', 'alt' => 'foodpanda'],
        ];

        foreach ($clients as $order => $client) {
            Client::query()->updateOrCreate(
                ['image' => $client['image']],
                [
                    'name' => $client['name'],
                    'alt' => $client['alt'],
                    'sort_order' => $order + 1,
                ]
            );
        }
    }
}
