<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget as BaseTableWidget;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Session;
use Exception;

class GA4PageReport extends BaseTableWidget
{
    // Make the widget take up full width of the dashboard grid
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return \App\Models\ContactSubmission::query()->whereRaw('1 = 0');
            })
            ->columns([
                TextColumn::make('title')->label('Page Title'),
                TextColumn::make('path')->label('Page Path'),
                TextColumn::make('views')->label('Views'),
                TextColumn::make('users')->label('Active Users'),
                TextColumn::make('sessions')->label('Sessions'),
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
                    $data = $service->getPageReport($startDate, 'today');

                    // Implement manual pagination by slicing the collection
                    // Since we are using a custom records() provider, we handle slicing here
                    // In a real production scenario with thousands of rows, 
                    // we would pass the page number to the API.
                    return collect($data)->map(fn($item) => [
                        'title' => $item['dim_0'] ?? 'N/A',
                        'path' => $item['dim_1'] ?? 'N/A',
                        'views' => $item['met_0'] ?? 0,
                        'users' => $item['met_1'] ?? 0,
                        'sessions' => $item['met_2'] ?? 0,
                    ])->take(10); // Limit to top 10 for better UX
                } catch (Exception $e) {
                    return collect();
                }
            })
            ->emptyStateHeading('No Page Data')
            ->emptyStateDescription('No analytics data found for the selected period.');
    }

    protected function getHeading(): string
    {
        return 'Top Pages Performance';
    }
}
