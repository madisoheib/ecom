<?php

namespace App\Filament\Resources\GuestOrderResource\Pages;

use App\Filament\Resources\GuestOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGuestOrders extends ListRecords
{
    protected static string $resource = GuestOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
