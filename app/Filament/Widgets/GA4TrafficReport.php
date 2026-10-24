<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget as BaseTableWidget;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Session;
use Exception;

class GA4TrafficReport extends BaseTableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return \App\Models\ContactSubmission::query()->whereRaw('1 = 0');
            })
            ->columns([
                TextColumn::make('source')->label('Source / Medium'),
                TextColumn::make('sessions')->label('Sessions'),
                TextColumn::make('users')->label('Active Users'),
                TextColumn::make('views')->label('Views'),
            ])
            ->records(function() {
                try {
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
                    
                    $service = app(\App\Services\GoogleAnalyticsService::class);
                    $data = $service->getTrafficReport($startDate, 'today');

                    return collect($data)->map(fn($item) => [
                        'source' => $item['dim_0'] ?? 'N/A',
                        'sessions' => $item['met_0'] ?? 0,
                        'users' => $item['met_1'] ?? 0,
                        'views' => $item['met_2'] ?? 0,
                    ]);
                } catch (Exception $e) {
                    return collect();
                }
            })
            ->emptyStateHeading('No Traffic Data')
            ->emptyStateDescription('No traffic source data found for the selected period.');
    }

    protected function getHeading(): string
    {
        return 'Traffic Acquisition';
    }
}
