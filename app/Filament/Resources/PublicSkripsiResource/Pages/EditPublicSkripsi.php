<?php

namespace App\Filament\Resources\PublicSkripsiResource\Pages;

use App\Filament\Resources\PublicSkripsiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPublicSkripsi extends EditRecord
{
    protected static string $resource = PublicSkripsiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
