<?php

namespace App\Filament\Resources\ThemeSettingsResource\Pages;

use App\Filament\Resources\ThemeSettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListThemeSettings extends ListRecords
{
    protected static string $resource = ThemeSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
