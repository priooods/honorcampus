<?php

namespace App\Filament\Resources\HonorDosenResource\Pages;

use App\Filament\Resources\HonorDosenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHonorDosens extends ListRecords
{
    protected static string $resource = HonorDosenResource::class;
    protected ?string $heading = 'Master Honor';
    protected static ?string $title = 'Honor';
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Master'),
        ];
    }
}
