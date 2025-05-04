<?php

namespace App\Filament\Resources\DosenSkripsiResource\Pages;

use App\Filament\Resources\DosenSkripsiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDosenSkripsis extends ListRecords
{
    protected static string $resource = DosenSkripsiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
