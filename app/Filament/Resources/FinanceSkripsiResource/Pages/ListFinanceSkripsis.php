<?php

namespace App\Filament\Resources\FinanceSkripsiResource\Pages;

use App\Filament\Resources\FinanceSkripsiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFinanceSkripsis extends ListRecords
{
    protected static string $resource = FinanceSkripsiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
