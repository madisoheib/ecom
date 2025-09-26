<?php

namespace App\Filament\Resources\AnalyticsSettingsResource\Pages;

use App\Filament\Resources\AnalyticsSettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAnalyticsSettings extends ListRecords
{
    protected static string $resource = AnalyticsSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
