<?php

namespace App\Filament\Resources\PublicProposalResource\Pages;

use App\Filament\Resources\PublicProposalResource;
use App\Models\THonorTab;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction as PagesExportAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListPublicProposals extends ListRecords
{
    protected static string $resource = PublicProposalResource::class;
    protected static ?string $title = 'Proposal';
    protected ?string $heading = 'Data Proposal';

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
                    Column::make('pem_proposal')->heading('Pembimbing Proposal')->formatStateUsing(
                        fn($record) => $record->pem_proposal->dosen->name
                    ),
                    Column::make('sid_proposal')->heading('Penguji 1')->formatStateUsing(
                        function ($record) {
                            if (isset($record->sid_proposal[0]))
                                return $record->sid_proposal[0]->dosen->name;
                            else
                                return '-';
                        }
                    ),
                    Column::make('sid_proposal')->heading('Penguji 2')->formatStateUsing(
                        function ($record) {
                            if (isset($record->sid_proposal[1]))
                                return $record->sid_proposal[1]->dosen->name;
                            else
                                return '-';
                        }
                    ),
                    Column::make('sid_proposal')->heading('Penguji 3')->formatStateUsing(
                        function ($record) {
                            if (isset($record->sid_proposal[2]))
                                return $record->sid_proposal[2]->dosen->name;
                            else
                                return '-';
                        }
                    ),
                    Column::make('m_status_tabs_id')->heading('Status')->formatStateUsing(
                        fn($record) => $record->status->title
                    ),
                ])->withFilename('Progress Proposal'),
            ]),
            Actions\CreateAction::make()->label('Tambah Proposal')->visible(auth()->user()->m_user_roles_id === 4),
        ];
    }

    public function getTabs(): array
    {
        return [
            'bimbingan' => Tab::make('Bimbingan')
                ->modifyQueryUsing(fn(Builder $query) => $query->whereIn('m_status_tabs_id', [3, 4, 5, 6, 7, 8])->where('status_bimbingan_proposal', 1)),
            'sidang' => Tab::make('Sidang')
                ->modifyQueryUsing(fn(Builder $query) => $query->whereIn('m_status_tabs_id', [9])->where('status_sidang_proposal', 1)),
        ];
    }
}
