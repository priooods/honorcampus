<?php

namespace App\Filament\Resources\HonorResource\Pages;

use App\Filament\Resources\HonorResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ListHonors extends ListRecords
{
    protected static string $resource = HonorResource::class;
    protected static ?string $title = 'Keuangan';
    protected ?string $heading = 'Data Keuangan';
    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        return [
            'before' => Tab::make('before')->label('Beri Honor')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('honor', 0)),
            'after' => Tab::make('after')->label('History')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('honor', '!=', 0)),
        ];
    }
}
