<?php

namespace App\Filament\Resources\HonorResource\Pages;

use App\Filament\Resources\HonorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHonors extends ListRecords
{
    protected static string $resource = HonorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
