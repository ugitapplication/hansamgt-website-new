<?php

namespace App\Filament\Widgets;

use App\Services\GoogleAnalyticsService;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Session;
use Exception;
use Livewire\Attributes\On;

class GA4StatsOverview extends StatsOverviewWidget
{
    #[On('refresh-ga4-stats')]
    public function refreshGA4Stats(): void
    {
        $this->dispatch('refresh');
    }

    protected function getStats(): array
    {
        // Use the correct session key consistently
        $range = Session::get('ga4_date_range', '30d');
        
        $presets = [
            'today' => 'today',
            'yesterday' => 'yesterday',
            '7d' => '7daysAgo',
            '30d' => '30daysAgo',
            'this_month' => 'thisMonth',
            'last_month' => 'lastMonth',
        ];

        $startDate = $presets[$range] ?? '30daysAgo';
        $endDate = 'today';

        try {
            $service = app(GoogleAnalyticsService::class);
            $metrics = $service->getCoreMetrics($startDate, $endDate);

            if (empty($metrics)) {
                return [
                    Stat::make('GA4 Analytics', 'No data')
                        ->description('No data for this period')
                        ->color('gray'),
                ];
            }

            return [
                Stat::make('Active Users', $metrics['active_users'])
                    ->description('Users active in the selected period')
                    ->descriptionIcon('heroicon-m-users')
                    ->color('info'),
                Stat::make('Sessions', $metrics['sessions'])
                    ->description('Total sessions in the selected period')
                    ->descriptionIcon('heroicon-m-cursor-arrow-rays')
                    ->color('primary'),
                Stat::make('Page Views', $metrics['views'])
                    ->description('Total page views in the selected period')
                    ->descriptionIcon('heroicon-m-eye')
                    ->color('success'),
            ];

        } catch (Exception $e) {
            return [
                Stat::make('GA4 Analytics', 'API Error')
                    ->description($e->getMessage())
                    ->color('danger'),
            ];
        }
    }
}
