<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Services\GoogleAnalyticsService;

class PageAnalyticsSummary extends BaseWidget
{
    protected function getStats(): array
    {
        $analyticsService = app(GoogleAnalyticsService::class);
        $details = $analyticsService->getDetailedPageAnalytics();

        return [
            Stat::make('Total Pages Tracked', $details['total_pages'])
                ->description('Pages with analytics data')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info'),

            Stat::make('Total Page Views', number_format($details['total_page_views']))
                ->description('All pages combined')
                ->descriptionIcon('heroicon-m-eye')
                ->color('success'),

            Stat::make('Avg. Time on Site', $details['avg_time_on_site'])
                ->description('User engagement time')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Pages per Session', number_format($details['pages_per_session'], 1))
                ->description('User navigation depth')
                ->descriptionIcon('heroicon-m-arrows-right-left')
                ->color('primary'),
        ];
    }
}