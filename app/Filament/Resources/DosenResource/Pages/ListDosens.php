<?php

namespace App\Filament\Resources\DosenResource\Pages;

use App\Filament\Resources\DosenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDosens extends ListRecords
{
    protected static string $resource = DosenResource::class;
    protected static ?string $title = 'Dosen';
    protected ?string $heading = 'Data Dosen';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Dosen')->visible(auth()->user()->m_user_roles_id === 4),
        ];
    }
}
