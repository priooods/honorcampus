<?php

namespace App\Filament\Resources\FinanceProposalResource\Pages;

use App\Filament\Resources\FinanceProposalResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ListFinanceProposals extends ListRecords
{
    protected static string $resource = FinanceProposalResource::class;
    protected static ?string $title = 'Proposal';
    protected ?string $heading = 'Data Proposal';
    protected ?string $description = 'List mahasiswa yang telah membayar';

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        return [
            'bimbingan' => Tab::make('Bimbingan')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status_bimbingan_proposal', 1)),
            'sidang' => Tab::make('Sidang')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status_sidang_proposal', 1)),
        ];
    }
}
