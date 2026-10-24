<?php

namespace App\Console\Commands;

use App\Services\GoogleAnalyticsService;
use Illuminate\Console\Command;
use Exception;

class AnalyticsTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analytics:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test GA4 API connectivity and configuration';

    /**
     * Execute the console command.
     */
    public function handle(GoogleAnalyticsService $service)
    {
        $this->info('Validating GA4 configuration...');

        $propertyId = config('google-analytics.property_id');
        $credentialsPath = config('google-analytics.service_account_json');

        if (!$propertyId) {
            $this->error('Property ID is missing in config/google-analytics.php or .env');
            return 1;
        }

        if (!$credentialsPath || !file_exists($credentialsPath)) {
            $this->error('Service account JSON file is missing or unreadable at the configured path');
            return 1;
        }

        $this->info('Configuration valid. Attempting API request...');

        try {
            // Request a small, bounded report to verify connectivity
            $metrics = $service->getCoreMetrics('7daysAgo', 'today');
            
            if (empty($metrics)) {
                $this->warn('Connected, but no data was returned for the selected period.');
            } else {
                $this->info('Connected successfully!');
            }
            
            return 0;
        } catch (Exception $e) {
            $this->error('Connectivity failed: ' . $e->getMessage());
            return 1;
        }
    }
}
