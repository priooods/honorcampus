<?php

namespace App\Filament\Resources\HonorDosenResource\Pages;

use App\Filament\Resources\HonorDosenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHonorDosen extends EditRecord
{
    protected static string $resource = HonorDosenResource::class;
    protected static ?string $title = 'Honor';
    protected ?string $heading = 'Edit Honor';
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
