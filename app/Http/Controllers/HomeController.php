<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Concern;
use App\Models\GalleryImage;
use App\Models\HeroSlide;
use App\Models\HomepageSectionItem;
use App\Models\Office;
use App\Models\Post;
use App\Models\Project;
use App\Models\TeamMember;
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

        // Latest published articles for the homepage "Latest News" section.
        try {
            $latestPosts = Post::query()
                ->published()
                ->ordered()
                ->limit(2)
                ->get();
        } catch (\Throwable) {
            $latestPosts = collect();
        }

        $homepageSections = $this->homepageSections();

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
            'latestPosts' => $latestPosts,
            'offices' => $this->offices(),
            'commitments' => $homepageSections['commitments'],
            'statistics' => $homepageSections['statistics'],
            'glanceCards' => $homepageSections['glance'],
            'whyPoints' => $homepageSections['why'],
            'projects' => $this->featuredProjects(),
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
            'members' => $this->teamMembers(),
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
            'offices' => $this->offices(),
        ]);
    }

    public function hardService(): View
    {
        return view('pages.hospitality-management', [
            'seo' => [
                'title' => 'Hospitality Management | HANSA Management Ltd.',
                'description' => 'Hospitality Management streams listed on this page: front office, food & beverage service, culinary, cleaning, laundry, landscaping and related operations.',
                'canonical' => 'https://hansamgt.com/hospitality-management',
                'service' => [
                    'name' => 'Hospitality Management',
                    'type' => 'Hospitality management services',
                    'description' => 'Front office, food & beverage service, culinary, cleaning, laundry, landscaping and related hospitality operations staffing.',
                ],
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
                'service' => [
                    'name' => 'Security Surveillance, Fire Life Safety & Risk Management',
                    'type' => 'Security, fire and life safety services',
                    'description' => 'Fire alarm monitoring, evacuation management, loss prevention procedures, investigations and security operations.',
                ],
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
                'service' => [
                    'name' => 'Cleaning & White-Glove Housekeeping',
                    'type' => 'Cleaning and housekeeping services',
                    'description' => 'Floor care, marble crystallization, carpet shampooing, facade cleaning, pest control and public area cleaning.',
                ],
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
                'service' => [
                    'name' => 'MEP (Mechanical, Electrical & Plumbing)',
                    'type' => 'Mechanical, electrical and plumbing services',
                    'description' => 'Air conditioning, water supply and drainage, firefighting, elevator, CCTV, BMS and water treatment services.',
                ],
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
            'projects' => $this->projects(),
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
            'photos' => $this->galleryPhotos(),
        ]);
    }

    public function privacy(): View
    {
        return view('pages.privacy', [
            'seo' => [
                'title' => 'Privacy Policy | HANSA Management Ltd.',
                'description' => 'How HANSA Management Ltd. collects, uses and protects personal data on this website — including the cookies we set and how to change your consent choice.',
                'canonical' => 'https://hansamgt.com/privacy-policy',
            ],
        ]);
    }

    public function careers(): View
    {
        return view('pages.careers', [
            'seo' => [
                'title' => 'Careers | HANSA Management Ltd.',
                'description' => 'Build your career with HANSA Management Ltd. Explore roles across hospitality, security, cleaning and housekeeping, and MEP services in Dhaka.',
                'canonical' => 'https://hansamgt.com/careers',
            ],
        ]);
    }

    /**
     * Projects for the Projects page, in the order set in the admin panel.
     *
     * @return array<int, array{src: string, alt: string, title: string, location: string, cat: string}>
     */
    private function projects(): array
    {
        try {
            return Project::query()->active()->ordered()->get()
                ->map(fn (Project $project): array => [
                    'src' => $project->imageUrl(),
                    'alt' => (string) $project->image_alt,
                    'title' => $project->title,
                    'location' => $project->location,
                    'cat' => $project->category === 'upcoming' ? 'upcoming' : 'ongoing',
                ])
                ->values()
                ->all();
        } catch (\Throwable) {
            return [];
        }
    }

    /**
     * The featured projects that make up the homepage "Service Projects" band.
     *
     * Their wording and order are their own: the band has always used longer
     * titles than the Projects page and lists the same four projects in a
     * different sequence.
     *
     * @return array<int, array{src: string, alt: string, title: string, location: string}>
     */
    private function featuredProjects(): array
    {
        try {
            return Project::query()->active()->featured()->homepageOrdered()->get()
                ->map(fn (Project $project): array => [
                    'src' => $project->imageUrl(),
                    'alt' => $project->homepageAlt(),
                    'title' => $project->homepageTitle(),
                    'location' => $project->homepageLocation(),
                ])
                ->values()
                ->all();
        } catch (\Throwable) {
            return [];
        }
    }

    /**
     * Gallery photos for the Site Management gallery.
     *
     * @return array<int, array{src: string, caption: string}>
     */
    private function galleryPhotos(): array
    {
        try {
            return GalleryImage::query()->active()->ordered()->get()
                ->map(fn (GalleryImage $photo): array => [
                    'src' => $photo->imageUrl(),
                    'caption' => (string) $photo->caption,
                ])
                ->values()
                ->all();
        } catch (\Throwable) {
            return [];
        }
    }

    /**
     * The executives shown on the Management page.
     *
     * @return array<int, array{id: string, src: string, alt: string, role: string, name: string, modalName: string, teaser: string, bio: string}>
     */
    private function teamMembers(): array
    {
        try {
            return TeamMember::query()->active()->ordered()->get()
                ->map(fn (TeamMember $member): array => [
                    'id' => $member->slug,
                    'src' => $member->imageUrl(),
                    'alt' => $member->imageAlt(),
                    'role' => $member->role,
                    'name' => $member->name,
                    'modalName' => $member->displayName(),
                    'teaser' => (string) $member->teaser,
                    'bio' => (string) $member->bio,
                ])
                ->values()
                ->all();
        } catch (\Throwable) {
            return [];
        }
    }

    /**
     * The four homepage sections driven by HomepageSectionItem.
     *
     * One query collects them all; each view keeps its own built-in fallback so
     * a section that is empty (before the table is seeded, for instance) still
     * renders as published.
     *
     * @return array<string, array<int, array<string, string|null>>>
     */
    private function homepageSections(): array
    {
        try {
            $items = HomepageSectionItem::query()->active()->ordered()->get()->groupBy('section');
        } catch (\Throwable) {
            $items = collect();
        }

        $map = fn (string $section, callable $mapper): array => ($items[$section] ?? collect())
            ->map($mapper)
            ->values()
            ->all();

        return [
            'commitments' => $map('commitments', fn (HomepageSectionItem $item): array => [
                'src' => $item->imageUrl(),
                'alt' => (string) $item->image_alt,
                'icon' => (string) $item->icon,
                'title' => (string) $item->title,
                'body' => (string) $item->body,
            ]),
            'statistics' => $map('numbers', fn (HomepageSectionItem $item): array => [
                'value' => $item->display_value,
                'label' => (string) $item->title,
            ]),
            'glance' => $map('glance', fn (HomepageSectionItem $item): array => [
                'src' => $item->imageUrl(),
                'title' => (string) $item->title,
                'text' => (string) $item->body,
            ]),
            'why' => $map('why', fn (HomepageSectionItem $item): array => [
                'text' => (string) $item->body,
                'group' => (string) ($item->group ?: '1'),
            ]),
        ];
    }

    /**
     * Offices for the contact page and the homepage "Our Offices" section.
     *
     * Both views used to carry their own copy of the same four offices, so the
     * two lists could — and did — drift apart. There is now one source.
     *
     * @return array<int, array{title: string, address: string, phone: ?string, landline: ?string, email: ?string}>
     */
    private function offices(): array
    {
        try {
            $offices = Office::query()->active()->ordered()->get();

            if ($offices->isNotEmpty()) {
                return $offices
                    ->map(fn (Office $office): array => [
                        'title' => $office->title,
                        'address' => $office->address,
                        'phone' => $office->phone,
                        'landline' => $office->landline,
                        'email' => $office->email,
                    ])
                    ->values()
                    ->all();
            }
        } catch (\Throwable) {
            // Table not migrated yet, or the database is unreachable.
        }

        return self::DEFAULT_OFFICES;
    }

    /**
     * The offices as they were published before the `offices` table existed.
     *
     * Kept so the contact page and the homepage section still render before
     * `php artisan migrate` and the OfficeSeeder have run.
     *
     * @var array<int, array{title: string, address: string, phone: ?string, landline: ?string, email: ?string}>
     */
    private const DEFAULT_OFFICES = [
        [
            'title' => 'Corporate Office',
            'address' => 'Borak Mehnur (Level-18), 51/B Kemal Ataturk Avenue, Banani, Dhaka-1213, Bangladesh',
            'phone' => '+8801708800870',
            'landline' => '88 02 222285116-123',
            'email' => 'info@hansamgt.com',
        ],
        [
            'title' => 'Service Booth (Midtown)',
            'address' => 'Unique Heights, 117 Kazi Nazrul Islam Avenue, Eskaton, Dhaka, Bangladesh',
            'phone' => null,
            'landline' => '88 02 48322812',
            'email' => null,
        ],
        [
            'title' => 'Service Booth (Uttara)',
            'address' => 'HANSA- A Premium Residence, House # 3 & 5, Road # 10/A , Sector # 09, Uttara, Dhaka - 1230, Bangladesh',
            'phone' => null,
            'landline' => null,
            'email' => null,
        ],
        [
            'title' => 'Service Booth (Dhanmondi)',
            'address' => 'Unique Trade Centre , 8 Panthapath, Kawranbazar, Dhaka-1215, Bangladesh',
            'phone' => null,
            'landline' => null,
            'email' => null,
        ],
    ];
}
