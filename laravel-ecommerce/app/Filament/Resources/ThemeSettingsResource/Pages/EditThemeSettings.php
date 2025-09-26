<?php

namespace App\Filament\Resources\ThemeSettingsResource\Pages;

use App\Filament\Resources\ThemeSettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditThemeSettings extends EditRecord
{
    protected static string $resource = ThemeSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
