<?php

namespace App\Filament\Resources\DosenSkripsiResource\Pages;

use App\Filament\Resources\DosenSkripsiResource;
use App\Models\MDosenTabs;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ListDosenSkripsis extends ListRecords
{
    protected static string $resource = DosenSkripsiResource::class;
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
                ->modifyQueryUsing(fn(Builder $query) => $query->where('m_type_request_id', 2)
                    ->where('m_dosen_tabs_id', MDosenTabs::where('users_id', auth()->user()->id)->value('id'))
                    ->where('m_type_request_id_detail', 3)),
            'sidang' => Tab::make('Sidang')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('m_type_request_id', 2)
                    ->where('m_dosen_tabs_id', MDosenTabs::where('users_id', auth()->user()->id)->value('id'))
                    ->where('m_type_request_id_detail', 4)),
        ];
    }
}
