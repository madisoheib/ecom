<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Services\GoogleAnalyticsService;

class TopPagesWidget extends Widget
{
    protected static string $view = 'filament.widgets.top-pages';

    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        $analyticsService = app(GoogleAnalyticsService::class);
        $topPages = $analyticsService->getTopPages();

        return [
            'topPages' => $topPages,
        ];
    }
}