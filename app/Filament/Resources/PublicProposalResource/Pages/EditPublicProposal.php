<?php

namespace App\Filament\Resources\PublicProposalResource\Pages;

use App\Filament\Resources\PublicProposalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPublicProposal extends EditRecord
{
    protected static string $resource = PublicProposalResource::class;
    protected static ?string $title = 'Edit Proposal';
    protected ?string $heading = 'Edit Proposal';
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
