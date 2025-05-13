<?php

namespace App\Filament\Resources\PublicProposalResource\Pages;

use App\Filament\Resources\PublicProposalResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ListPublicProposals extends ListRecords
{
    protected static string $resource = PublicProposalResource::class;
    protected static ?string $title = 'Proposal';
    protected ?string $heading = 'Data Proposal';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Proposal')->visible(auth()->user()->m_user_roles_id === 4),
        ];
    }

    public function getTabs(): array
    {
        return [
            'bimbingan' => Tab::make('Bimbingan')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status_proposal', 1)),
            'sidang' => Tab::make('Sidang')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status_proposal', 0)),
        ];
    }
}
