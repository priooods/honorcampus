<?php

namespace App\Filament\Resources\FinanceSkripsiResource\Pages;

use App\Filament\Resources\FinanceSkripsiResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ListFinanceSkripsis extends ListRecords
{
    protected static string $resource = FinanceSkripsiResource::class;
    protected static ?string $title = 'Skripsi';
    protected ?string $heading = 'Data Skripsi';
    protected ?string $description = 'List mahasiswa yang telah membayar';

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        return [
            'bimbingan' => Tab::make('Bimbingan')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status_bimbingan_skripsi', 1)),
            'sidang' => Tab::make('Sidang')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status_sidang_skripsi', 1)),
        ];
    }
}
