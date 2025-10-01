<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GoogleAnalyticsService;

class TestAnalyticsCommand extends Command
{
    protected $signature = 'analytics:test';
    
    protected $description = 'Test Google Analytics integration';

    public function handle()
    {
        $analyticsService = app(GoogleAnalyticsService::class);
        
        $this->info('Testing Google Analytics Service...');
        
        $this->line('Total Users: ' . $analyticsService->getTotalUsers());
        $this->line('Page Views: ' . $analyticsService->getPageViews());
        $this->line('Bounce Rate: ' . $analyticsService->getBounceRate() . '%');
        $this->line('Avg Session Duration: ' . $analyticsService->getAvgSessionDuration());
        
        $topPages = $analyticsService->getTopPages();
        $this->line('Top Pages Count: ' . count($topPages));
        
        if (!empty($topPages)) {
            $this->newLine();
            $this->info('Top Pages Details:');
            foreach ($topPages as $index => $page) {
                $this->line('  ' . ($index + 1) . '. ' . $page['title'] . ' (' . $page['page'] . ') - Views: ' . $page['views']);
            }
        } else {
            $this->newLine();
            $this->warn('No page tracking data available - Real Google Analytics API integration required');
        }
        
        $trafficSources = $analyticsService->getTrafficSources();
        $this->line('Traffic Sources Count: ' . count($trafficSources));
        
        $details = $analyticsService->getDetailedPageAnalytics();
        $this->newLine();
        $this->info('Page Analytics Summary:');
        $this->line('  Total Pages Tracked: ' . $details['total_pages']);
        $this->line('  Total Page Views: ' . $details['total_page_views']);
        $this->line('  Avg Time on Site: ' . $details['avg_time_on_site']);
        $this->line('  Pages per Session: ' . $details['pages_per_session']);
        
        $this->info('Analytics test completed!');
    }
}