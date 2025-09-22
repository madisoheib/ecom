<?php

namespace App\Filament\Resources\GuestOrderResource\Pages;

use App\Filament\Resources\GuestOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGuestOrder extends EditRecord
{
    protected static string $resource = GuestOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
