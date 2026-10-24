<?php

namespace App\Services;

use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\RunReportRequest;
use Google\Analytics\Data\V1beta\RunRealtimeReportRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;
use Google\Analytics\Data\V1beta\Client\BetaAnalyticsDataClient;

class GoogleAnalyticsService
{
    protected ?BetaAnalyticsDataClient $client = null;

    public function getClient(): BetaAnalyticsDataClient
    {
        if ($this->client) {
            return $this->client;
        }

        $propertyId = config('google-analytics.property_id');
        $credentialsPath = config('google-analytics.service_account_json');

        if (!$propertyId || !$credentialsPath) {
            throw new Exception('GA4 configuration is missing (Property ID or Credentials Path).');
        }

        if (!file_exists($credentialsPath)) {
            throw new Exception("GA4 service account file not found at: {$credentialsPath}");
        }

        try {
            $this->client = new BetaAnalyticsDataClient([
                'credentials' => $credentialsPath,
            ]);
            return $this->client;
        } catch (Exception $e) {
            Log::error('GA4 Client Authentication Failed: ' . $e->getMessage());
            throw new Exception('Failed to authenticate with Google Analytics API.');
        }
    }

    protected function getTtlForRange(string $startDate): int
    {
        if ($startDate === 'today') {
            return 900; // 15 minutes
        }
        if ($startDate === 'yesterday') {
            return 3600; // 1 hour
        }
        return config('google-analytics.cache_ttl', 43200); // 12 hours
    }

    protected function generateCacheKey(string $type, string $startDate, string $endDate, array $dimensions = [], array $metrics = []): string
    {
        $propertyId = config('google-analytics.property_id');
        return 'ga4_' . $type . '_' . md5(
            $propertyId . $startDate . $endDate . json_encode($dimensions) . json_encode($metrics)
        );
    }

    public function getCoreMetrics(string $startDate = '30daysAgo', string $endDate = 'today'): array
    {
        $metricsList = ['activeUsers', 'sessions', 'screenPageViews'];
        return $this->runReport('summary', $startDate, $endDate, [], $metricsList);
    }

    public function getPageReport(string $startDate = '30daysAgo', string $endDate = 'today'): array
    {
        $dimensions = ['pageTitle', 'pagePath'];
        $metrics = ['screenPageViews', 'activeUsers', 'sessions'];
        return $this->runReport('pages', $startDate, $endDate, $dimensions, $metrics);
    }

    public function getTrafficReport(string $startDate = '30daysAgo', string $endDate = 'today'): array
    {
        $dimensions = ['sessionSourceMedium'];
        $metrics = ['sessions', 'activeUsers', 'screenPageViews'];
        return $this->runReport('traffic', $startDate, $endDate, $dimensions, $metrics);
    }

    public function getTrendReport(string $startDate = '30daysAgo', string $endDate = 'today'): array
    {
        $dimensions = ['date'];
        $metrics = ['activeUsers', 'sessions', 'screenPageViews'];
        return $this->runReport('trend', $startDate, $endDate, $dimensions, $metrics);
    }

    public function getRealtimeMetrics(): array
    {
        $cacheKey = 'ga4_realtime_' . config('google-analytics.property_id');

        return Cache::remember($cacheKey, 60, function () {
            try {
                $client = $this->getClient();
                $propertyId = config('google-analytics.property_id');

                $request = (new RunRealtimeReportRequest())
                    ->setProperty('properties/' . $propertyId)
                    ->setMetrics([
                        new Metric(['name' => 'activeUsers']),
                    ]);

                $response = $client->runRealtimeReport($request);
                
                $activeUsers = 0;
                if (!empty($response->getRows())) {
                    $activeUsers = $response->getRows()[0]->getMetricValues()[0]->getValue();
                }

                return ['active_users' => $activeUsers];
            } catch (Exception $e) {
                Log::error('GA4 Realtime Error: ' . $e->getMessage());
                throw new Exception('Realtime data unavailable.');
            }
        });
    }

    protected function runReport(string $type, string $startDate, string $endDate, array $dimensions, array $metrics): array
    {
        $propertyId = config('google-analytics.property_id');
        $cacheKey = $this->generateCacheKey($type, $startDate, $endDate, $dimensions, $metrics);
        $ttl = $this->getTtlForRange($startDate);

        return Cache::remember($cacheKey, $ttl, function () use ($type, $startDate, $endDate, $dimensions, $metrics) {
            try {
                $client = $this->getClient();
                $propertyId = config('google-analytics.property_id');

                $request = (new RunReportRequest())
                    ->setProperty('properties/' . $propertyId)
                    ->setDateRanges([new DateRange(['start_date' => $startDate, 'end_date' => $endDate])]);

                if (!empty($dimensions)) {
                    $request->setDimensions(array_map(fn($dim) => new Dimension(['name' => $dim]), $dimensions));
                }

                if (!empty($metrics)) {
                    $request->setMetrics(array_map(fn($met) => new Metric(['name' => $met]), $metrics));
                }

                $response = $client->runReport($request);
                
                $results = [];
                foreach ($response->getRows() as $row) {
                    $data = [];
                    foreach ($row->getDimensionValues() as $i => $dimValue) {
                        $data['dim_' . $i] = $dimValue->getValue();
                    }
                    foreach ($row->getMetricValues() as $i => $metValue) {
                        $data['met_' . $i] = $metValue->getValue();
                    }
                    $results[] = $data;
                }

                if ($type === 'summary' && !empty($results)) {
                    $row = $results[0];
                    return [
                        'active_users' => $row['met_0'] ?? 0,
                        'sessions' => $row['met_1'] ?? 0,
                        'views' => $row['met_2'] ?? 0,
                    ];
                }

                return $results;
            } catch (Exception $e) {
                Log::error("GA4 $type Report Error: " . $e->getMessage());
                throw new Exception("GA4 $type Report Error: " . $e->getMessage());
            }
        });
    }
}
