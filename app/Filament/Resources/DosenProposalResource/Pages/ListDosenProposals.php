<?php

namespace App\Filament\Resources\DosenProposalResource\Pages;

use App\Filament\Resources\DosenProposalResource;
use App\Models\MDosenTabs;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ListDosenProposals extends ListRecords
{
    protected static string $resource = DosenProposalResource::class;
    protected static ?string $title = 'Proposal';
    protected ?string $heading = 'Data Proposal';

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        return [
            'bimbingan' => Tab::make('Bimbingan')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('m_type_request_id', 1)
                ->where('m_dosen_tabs_id', auth()->user()->dosen->id)
                    ->where('m_type_request_id_detail', 3)),
            'sidang' => Tab::make('Sidang')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('m_type_request_id', 1)
                ->where('m_dosen_tabs_id', auth()->user()->dosen->id)
                    ->where('m_type_request_id_detail', 4)),
        ];
    }
}
