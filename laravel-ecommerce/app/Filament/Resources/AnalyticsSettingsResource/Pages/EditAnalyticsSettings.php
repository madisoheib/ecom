<?php

namespace App\Filament\Resources\AnalyticsSettingsResource\Pages;

use App\Filament\Resources\AnalyticsSettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAnalyticsSettings extends EditRecord
{
    protected static string $resource = AnalyticsSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
