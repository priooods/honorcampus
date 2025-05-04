<?php

namespace App\Filament\Resources\FinanceSkripsiResource\Pages;

use App\Filament\Resources\FinanceSkripsiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFinanceSkripsi extends EditRecord
{
    protected static string $resource = FinanceSkripsiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
