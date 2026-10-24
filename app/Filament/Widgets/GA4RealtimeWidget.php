<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Services\GoogleAnalyticsService;
use Exception;

class GA4RealtimeWidget extends Widget
{
    protected string $view = 'filament.widgets.ga4-realtime';

    public function getRealtimeData()
    {
        try {
            $service = app(GoogleAnalyticsService::class);
            return $service->getRealtimeMetrics();
        } catch (Exception $e) {
            return null;
        }
    }
}
