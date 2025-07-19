<?php

namespace App\Filament\Resources\KeahlianResource\Pages;

use App\Filament\Resources\KeahlianResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateKeahlian extends CreateRecord
{
    protected static string $resource = KeahlianResource::class;
    protected ?string $heading = 'Tambah Data Keahlian';
    protected static ?string $title = 'Tambah Keahlian';
    protected static bool $canCreateAnother = false;
}
