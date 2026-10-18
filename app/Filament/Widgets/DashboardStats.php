<?php

namespace App\Filament\Widgets;

use App\Models\ContactSubmission;
use App\Models\HeroSlide;
use App\Models\Post;
use App\Models\Project;
use App\Models\TeamMember;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(
                'Active Projects',
                Project::query()->where('is_active', true)->count()
            )
                ->description('Projects visible on the website')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('success'),

            Stat::make(
                'Active Team Members',
                TeamMember::query()->where('is_active', true)->count()
            )
                ->description('Currently active team members')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

            Stat::make(
                'Active Hero Slides',
                HeroSlide::query()->where('is_active', true)->count()
            )
                ->description('Enabled homepage slides')
                ->descriptionIcon('heroicon-m-photo')
                ->color('primary'),

            Stat::make(
                'Published Posts',
                Post::published()->count()
            )
                ->description('Posts currently eligible for publication')
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('success'),

            Stat::make(
                'Unread Messages',
                ContactSubmission::query()->whereNull('read_at')->count()
            )
                ->description('Contact submissions awaiting review')
                ->descriptionIcon('heroicon-m-envelope')
                ->color('warning'),
        ];
    }
}
