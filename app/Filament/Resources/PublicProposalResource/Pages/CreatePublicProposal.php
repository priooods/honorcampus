<?php

namespace App\Filament\Resources\PublicProposalResource\Pages;

use App\Filament\Resources\PublicProposalResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePublicProposal extends CreateRecord
{
    protected static string $resource = PublicProposalResource::class;
    protected ?string $heading = 'Tambah Data Proposal';
    protected static ?string $title = 'Tambah Proposal';
    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['m_status_tabs_id'] = 7;
        $data['progres_bimbingan_proposal'] = 0;
        return $data;
    }

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
