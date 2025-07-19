<?php

namespace App\Filament\Resources\KeahlianResource\Pages;

use App\Filament\Resources\KeahlianResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKeahlian extends EditRecord
{
    protected static string $resource = KeahlianResource::class;
    protected static ?string $title = 'Keahlian';
    protected ?string $heading = 'Edit Keahlian';
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Hapus Data'),
        ];
    }
}
