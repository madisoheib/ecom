<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Services\GoogleAnalyticsService;

class PagePerformanceWidget extends ChartWidget
{
    protected static ?string $heading = 'Page Views Trend (No tracking data)';

    protected int | string | array $columnSpan = 2;

    protected function getData(): array
    {
        $analyticsService = app(GoogleAnalyticsService::class);
        $trendData = $analyticsService->getPageViewsTrend(7);
        
        if (empty($trendData)) {
            // No real tracking data available
            $dates = [];
            $pageViews = [];
            
            for ($i = 6; $i >= 0; $i--) {
                $dates[] = now()->subDays($i)->format('M j');
                $pageViews[] = 0;
            }
        } else {
            $dates = array_column($trendData, 'date');
            $pageViews = array_column($trendData, 'views');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Page Views',
                    'data' => $pageViews,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $dates,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'color' => 'rgba(0, 0, 0, 0.1)',
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
        ];
    }
}