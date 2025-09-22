<?php

namespace App\Filament\Resources\GuestOrderResource\Pages;

use App\Filament\Resources\GuestOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGuestOrder extends CreateRecord
{
    protected static string $resource = GuestOrderResource::class;
}
