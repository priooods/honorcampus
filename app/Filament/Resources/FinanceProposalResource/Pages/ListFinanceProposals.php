<?php

namespace App\Filament\Resources\FinanceProposalResource\Pages;

use App\Filament\Resources\FinanceProposalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFinanceProposals extends ListRecords
{
    protected static string $resource = FinanceProposalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
