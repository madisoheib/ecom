<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Services\GoogleAnalyticsService;

class AnalyticsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $analyticsService = app(GoogleAnalyticsService::class);

        return [
            Stat::make('Total Users', $analyticsService->getTotalUsers())
                ->description('Last 30 days')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),

            Stat::make('Page Views', $analyticsService->getPageViews())
                ->description('Last 30 days')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([15, 4, 10, 2, 12, 4, 12])
                ->color('info'),

            Stat::make('Bounce Rate', $analyticsService->getBounceRate() . '%')
                ->description('Last 30 days')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('warning'),

            Stat::make('Avg. Session Duration', $analyticsService->getAvgSessionDuration())
                ->description('Last 30 days')
                ->descriptionIcon('heroicon-m-clock')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('primary'),
        ];
    }
}