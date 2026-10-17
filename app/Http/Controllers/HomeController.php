<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Concern;
use App\Models\HeroSlide;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        // Active hero slides in display order. Any failure (table not yet
        // migrated, DB unreachable) falls back to the built-in slides in
        // the Blade partial so the homepage never breaks.
        try {
            $heroSlides = HeroSlide::query()
                ->active()
                ->ordered()
                ->get()
                ->map(fn (HeroSlide $slide) => [
                    'src' => $slide->imageUrl(),
                    'alt' => $slide->alt,
                ])
                ->values()
                ->all();
        } catch (\Throwable) {
            $heroSlides = [];
        }

        try {
            $clients = Client::query()
                ->active()
                ->ordered()
                ->get()
                ->map(fn (Client $client) => [
                    'src' => $client->imageUrl(),
                    'alt' => $client->alt,
                ])
                ->values()
                ->all();
        } catch (\Throwable) {
            $clients = [];
        }

        try {
            $concerns = Concern::query()
                ->active()
                ->ordered()
                ->get()
                ->map(fn (Concern $concern) => [
                    'src' => $concern->imageUrl(),
                    'alt' => $concern->alt,
                ])
                ->values()
                ->all();
        } catch (\Throwable) {
            $concerns = [];
        }

        return view('pages.home', [
            'seo' => [
                'title' => 'HANSA Management Ltd. | Hospitality and Facility Management Services in Dhaka',
                'description' => 'HANSA Management (Unique Group) provides facility management, 5-star hotel staffing, security, cleaning and MEP services in Dhaka. Get a quote.',
                'canonical' => 'https://hansamgt.com/',
            ],
            'hasHero' => true,
            'heroSlides' => $heroSlides,
            'clients' => $clients,
            'concerns' => $concerns,
        ]);
    }

    public function history(): View
    {
        return view('pages.company-profile', [
            'seo' => [
                'title' => 'Company Profile | HANSA Management Ltd.',
                'description' => 'Company Profile: HANSA Management Ltd. founded by Unique Group, incorporated in 2015. Security, cleaning, housekeeping, MEP and facade services.',
                'canonical' => 'https://hansamgt.com/company-profile',
            ],
        ]);
    }

    public function vision(): View
    {
        return view('pages.mission-vision', [
            'seo' => [
                'title' => 'Mission & Vision | HANSA Management Ltd.',
                'description' => 'Mission and Vision statements as published on this page covering service techniques, resource use and customer satisfaction.',
                'canonical' => 'https://hansamgt.com/mission-vision',
            ],
        ]);
    }

    public function charter(): View
    {
        return view('pages.charter-of-unique-group', [
            'seo' => [
                'title' => 'Charter of Unique Group | HANSA Management Ltd.',
                'description' => 'Charter of Unique Group: operation since 1982, diversified business areas, customer and service statements as published on this page.',
                'canonical' => 'https://hansamgt.com/charter-of-unique-group',
            ],
        ]);
    }

    public function management(): View
    {
        return view('pages.management', [
            'seo' => [
                'title' => 'Management | HANSA Management Ltd.',
                'description' => 'Management page listing Chairperson, Managing Director and management team members of HANSA Management Ltd.',
                'canonical' => 'https://hansamgt.com/management',
            ],
        ]);
    }

    public function csr(): View
    {
        return view('pages.corporate-social-responsibility', [
            'seo' => [
                'title' => 'CSR | HANSA Management Ltd.',
                'description' => 'CSR page stating HANSA published commitments regarding green environment and helping others.',
                'canonical' => 'https://hansamgt.com/corporate-social-responsibility',
            ],
        ]);
    }

    public function contact(): View
    {
        return view('pages.contact', [
            'seo' => [
                'title' => 'Contact | HANSA Management Ltd.',
                'description' => 'Corporate Office: Borak Mehnur, 51/B Kemal Ataturk Avenue, Banani, Dhaka-1213. Information Desk: 01708800870.',
                'canonical' => 'https://hansamgt.com/contact',
            ],
        ]);
    }

    public function hardService(): View
    {
        return view('pages.hospitality-management', [
            'seo' => [
                'title' => 'Hospitality Management | HANSA Management Ltd.',
                'description' => 'Hospitality Management streams listed on this page: front office, food & beverage service, culinary, cleaning, laundry, landscaping and related operations.',
                'canonical' => 'https://hansamgt.com/hospitality-management',
            ],
        ]);
    }

    public function softService(): View
    {
        return view('pages.security-fire-life-safety', [
            'seo' => [
                'title' => 'Security Surveillance, Fire Life Safety & Risk Management | HANSA',
                'description' => 'Services listed on this page: fire alarm monitoring, evacuation management, loss prevention procedures, investigations and operations.',
                'canonical' => 'https://hansamgt.com/security-fire-life-safety',
            ],
        ]);
    }

    public function payperUse(): View
    {
        return view('pages.cleaning-white-glove-housekeeping', [
            'seo' => [
                'title' => 'Cleaning & White-Glove Housekeeping | HANSA Management Ltd.',
                'description' => 'Services listed on this page: floor care, marble crystallization, carpet shampooing, facade cleaning, pest control and public area cleaning.',
                'canonical' => 'https://hansamgt.com/cleaning-white-glove-housekeeping',
            ],
        ]);
    }

    public function riskManagement(): View
    {
        return view('pages.mep-mechanical-electrical-plumbing', [
            'seo' => [
                'title' => 'MEP (Mechanical, Electrical & Plumbing) | HANSA Management Ltd.',
                'description' => 'MEP services listed on this page: air conditioning, water supply and drainage, firefighting, elevator, CCTV, BMS and water treatment.',
                'canonical' => 'https://hansamgt.com/mep-mechanical-electrical-plumbing',
            ],
        ]);
    }

    public function project(): View
    {
        return view('pages.projects', [
            'seo' => [
                'title' => 'Projects | HANSA Management Ltd.',
                'description' => 'Projects listed on this page include Westin Dhaka, Unique Heights, Red Crescent Borak Tower, Unique Oval and Unique Trade Center.',
                'canonical' => 'https://hansamgt.com/projects',
            ],
        ]);
    }

    public function siteManagement(): View
    {
        return view('pages.gallery', [
            'seo' => [
                'title' => 'Gallery | HANSA Management Ltd.',
                'description' => 'Gallery images labelled on this page include Top Management, Training Session and Team Work.',
                'canonical' => 'https://hansamgt.com/gallery',
            ],
        ]);
    }
}
