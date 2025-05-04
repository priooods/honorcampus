<?php

namespace App\Filament\Resources\FinanceProposalResource\Pages;

use App\Filament\Resources\FinanceProposalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFinanceProposal extends EditRecord
{
    protected static string $resource = FinanceProposalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
