<?php

namespace App\Filament\Widgets;

use App\Services\GoogleAnalyticsService;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Session;
use Exception;

class GA4TrendChart extends ChartWidget
{
    public function getData(): array
    {
        // FIXED: Removed the typo 'ga4_date_//_range'
        $currentRange = Session::get('ga4_date_range', '30d');
        
        $presets = [
            'today' => 'today',
            'yesterday' => 'yesterday',
            '7d' => '7daysAgo',
            '30d' => '30daysAgo',
            'this_month' => 'thisMonth',
            'last_month' => 'lastMonth',
        ];
        $startDate = $presets[$currentRange] ?? '30daysAgo';
        $endDate = 'today';

        try {
            $service = app(GoogleAnalyticsService::class);
            $data = $service->getTrendReport($startDate, $endDate);

            $labels = [];
            $users = [];
            $sessions = [];
            $views = [];

            foreach ($data as $row) {
                $labels[] = $row['dim_0'];
                $users[] = (int)($row['met_0'] ?? 0);
                $sessions[] = (int)($row['met_1'] ?? 0);
                $views[] = (int)($row['met_2'] ?? 0);
            }

            return [
                'datasets' => [
                    [
                        'label' => 'Active Users',
                        'data' => $users,
                        'borderColor' => '#3b82f6',
                    ],
                    [
                        'label' => 'Sessions',
                        'data' => $sessions,
                        'borderColor' => '#10b981',
                    ],
                    [
                        'label' => 'Page Views',
                        'data' => $views,
                        'borderColor' => '#f59e0b',
                    ],
                ],
                'labels' => $labels,
            ];
        } catch (Exception $e) {
            return ['datasets' => [], 'labels' => []];
        }
    }

    protected function getType(): string
    {
        return 'line';
    }

    public function getHeading(): string
    {
        return 'Traffic Trend';
    }
}
