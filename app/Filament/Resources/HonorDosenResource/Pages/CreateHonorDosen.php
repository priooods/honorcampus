<?php

namespace App\Filament\Resources\HonorDosenResource\Pages;

use App\Filament\Resources\HonorDosenResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateHonorDosen extends CreateRecord
{
    protected static string $resource = HonorDosenResource::class;
    protected ?string $heading = 'Tambah Data Honor';
    protected static ?string $title = 'Tambah Honor';
    protected static bool $canCreateAnother = false;


    protected function getCreateFormAction(): \Filament\Actions\Action
    {
        return parent::getCreateFormAction()
            ->label('Simpan Data');
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('create');
    }
}
