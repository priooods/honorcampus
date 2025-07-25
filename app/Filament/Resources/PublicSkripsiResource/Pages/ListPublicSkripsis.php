<?php

namespace App\Filament\Resources\PublicSkripsiResource\Pages;

use App\Filament\Resources\PublicSkripsiResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction as PagesExportAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
class ListPublicSkripsis extends ListRecords
{
    protected static string $resource = PublicSkripsiResource::class;
    protected static ?string $title = 'Skripsi';
    protected ?string $heading = 'Data Skripsi';

    protected function getHeaderActions(): array
    {
        return [
            PagesExportAction::make()->color('success')->exports([
                ExcelExport::make()->withColumns([
                    Column::make('t_periode_tabs')->heading('Periode')->formatStateUsing(
                        fn($record) => $record->periode->title
                    ),
                    Column::make('name')->heading('Nama Mahasiswa/i'),
                    Column::make('nim')->heading('NIM'),
                    Column::make('prodi')->heading('Prodi'),
                    Column::make('pem_skripsi')->heading('Pembimbing Skripsi')->formatStateUsing(
                        fn($record) => $record->pem_skripsi->dosen->name
                    ),
                    Column::make('sid_skripsi')->heading('Penguji 1')->formatStateUsing(
                        function ($record) {
                            foreach ($record->sid_skripsi as $key => $value) {
                                if ($key === 0)
                                    return $value->dosen->name;
                            }
                        }
                    ),
                    Column::make('sid_skripsi_2')->heading('Penguji 2')->formatStateUsing(
                        function ($record) {
                            foreach ($record->sid_skripsi_2 as $key => $value) {
                                if ($key === 0)
                                    return $value->dosen->name;
                            }
                        }
                    ),
                    Column::make('sid_skripsi_3')->heading('Penguji 3')->formatStateUsing(
                        function ($record) {
                            foreach ($record->sid_skripsi_3 as $key => $value) {
                                if ($key === 0)
                                    return $value->dosen->name;
                            }
                        }
                    ),
                    Column::make('m_status_tabs_id')->heading('Status')->formatStateUsing(
                        fn($record) => $record->status->title
                    ),
                ])->withFilename('Progress Skripsi'),
            ]),
        ];
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
