<?php

namespace App\Support;

class Breadcrumbs
{
    /**
     * Trail label keyed by route name. "Home" is prepended automatically and
     * the current page is always the final, unlinked item.
     *
     * @var array<string, string>
     */
    protected const TRAILS = [
        'company-profile' => 'Company Profile',
        'mission-vision' => 'Mission & Vision',
        'charter-of-unique-group' => 'Charter of Unique Group',
        'management' => 'Management',
        'corporate-social-responsibility' => 'Corporate Social Responsibility',
        'contact' => 'Contact',
        'hospitality-management' => 'Hospitality Management',
        'security-fire-life-safety' => 'Security, Fire & Life Safety',
        'cleaning-white-glove-housekeeping' => 'Cleaning & White-Glove Housekeeping',
        'mep-mechanical-electrical-plumbing' => 'MEP (Mechanical, Electrical & Plumbing)',
        'projects' => 'Projects',
        'gallery' => 'Gallery',
        'careers' => 'Careers',
        'privacy-policy' => 'Privacy Policy',
        'insights' => 'Insights',
    ];

    /**
     * Build the breadcrumb trail for a route name.
     *
     * @return array<int, array{label: string, url: ?string}>
     */
    public static function forRoute(?string $name): array
    {
        if ($name === null || ! isset(self::TRAILS[$name])) {
            return [];
        }

        return [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => self::TRAILS[$name], 'url' => null],
        ];
    }
}
