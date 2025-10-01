<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\AnalyticsOverview;
use App\Filament\Widgets\TrafficSourcesChart;
use App\Filament\Widgets\TopPagesWidget;
use App\Filament\Widgets\PagePerformanceWidget;
use App\Filament\Widgets\PageAnalyticsSummary;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    protected static ?string $navigationLabel = 'Dashboard';
    
    protected static ?int $navigationSort = 1;

    public function getWidgets(): array
    {
        return [
            AnalyticsOverview::class,
            PageAnalyticsSummary::class,
            TrafficSourcesChart::class,
            PagePerformanceWidget::class,
            TopPagesWidget::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return [
            'md' => 2,
            'xl' => 4,
        ];
    }
}
