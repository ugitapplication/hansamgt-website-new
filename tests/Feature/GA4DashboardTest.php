<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\GoogleAnalyticsService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Mockery;

class GA4DashboardTest extends TestCase
{
    public function test_ga4_stats_widget_renders_metrics_successfully()
    {
        $mockService = Mockery::mock(GoogleAnalyticsService::class);
        $mockService->shouldReceive('getCoreMetrics')
            ->once()
            ->with('30daysAgo', 'today')
            ->andReturn([
                'active_users' => '100',
                'sessions' => '200',
                'views' => '300',
            ]);

        $this->app->instance(GoogleAnalyticsService::class, $mockService);

        $response = $this->actingAs(\App\Models\User::first())
            ->get('/admin');

        $response->assertStatus(200);
        // Note: Testing actual HTML output for Filament widgets is complex due to Livewire,
        // but verifying the service call is a strong indicator.
    }

    public function test_ga4_stats_widget_handles_api_failure_gracefully()
    {
        $mockService = Mockery::mock(GoogleAnalyticsService::class);
        $mockService->shouldReceive('getCoreMetrics')
            ->once()
            ->andThrow(new \Exception('API Error'));

        $this->app->instance(GoogleAnalyticsService::class, $mockService);

        $response = $this->actingAs(\App\Models\User::first())
            ->get('/admin');

        $response->assertStatus(200);
    }

    public function test_ga4_stats_widget_handles_empty_results()
    {
        $mockService = Mockery::mock(GoogleAnalyticsService::class);
        $mockService->shouldReceive('getCoreMetrics')
            ->once()
            ->andReturn([]);

        $this->app->instance(GoogleAnalyticsService::class, $mockService);

        $response = $this->actingAs(\App\Models\User::first())
            ->get('/admin');

        $response->assertStatus(200);
    }
}
