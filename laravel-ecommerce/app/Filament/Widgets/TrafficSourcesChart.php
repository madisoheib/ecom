<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Services\GoogleAnalyticsService;

class TrafficSourcesChart extends ChartWidget
{
    protected static ?string $heading = 'Traffic Sources';

    protected function getData(): array
    {
        $analyticsService = app(GoogleAnalyticsService::class);
        $trafficSources = $analyticsService->getTrafficSources();

        if (empty($trafficSources)) {
            return [
                'datasets' => [
                    [
                        'label' => 'Traffic Sources',
                        'data' => [0],
                        'backgroundColor' => ['#e5e7eb'],
                    ],
                ],
                'labels' => ['No Data'],
            ];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Traffic Sources',
                    'data' => array_values(array_column($trafficSources, 'sessions')),
                    'backgroundColor' => [
                        '#10b981',
                        '#3b82f6', 
                        '#f59e0b',
                        '#ef4444',
                        '#8b5cf6',
                    ],
                ],
            ],
            'labels' => array_values(array_column($trafficSources, 'source')),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}