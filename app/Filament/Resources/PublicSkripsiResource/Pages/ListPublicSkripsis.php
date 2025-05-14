<?php

namespace App\Filament\Resources\PublicSkripsiResource\Pages;

use App\Filament\Resources\PublicSkripsiResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ListPublicSkripsis extends ListRecords
{
    protected static string $resource = PublicSkripsiResource::class;
    protected static ?string $title = 'Skripsi';
    protected ?string $heading = 'Data Skripsi';

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        return [
            'bimbingan' => Tab::make('Bimbingan')
                ->modifyQueryUsing(fn(Builder $query) => $query->whereIn('m_status_tabs_id', [10])->where('status_bimbingan_skripsi', 1)),
            'sidang' => Tab::make('Sidang')
                ->modifyQueryUsing(fn(Builder $query) => $query->whereIn('m_status_tabs_id', [11])->where('status_sidang_skripsi', 1)),
        ];
    }
}
