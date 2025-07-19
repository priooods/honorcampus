<?php

namespace App\Filament\Resources\KeahlianResource\Pages;

use App\Filament\Resources\KeahlianResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKeahlians extends ListRecords
{
    protected static string $resource = KeahlianResource::class;
    protected static ?string $title = 'Keahlian';
    protected ?string $heading = 'Data Keahlian';
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data'),
        ];
    }
}
