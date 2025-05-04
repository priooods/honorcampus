<?php

namespace App\Filament\Resources\DosenProposalResource\Pages;

use App\Filament\Resources\DosenProposalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDosenProposals extends ListRecords
{
    protected static string $resource = DosenProposalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
