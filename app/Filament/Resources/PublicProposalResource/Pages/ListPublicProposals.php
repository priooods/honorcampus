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
                    Column::make('t_periode_tabs')->formatStateUsing(
                        fn($record) => $record->periode->title
                    ),
                    Column::make('name'),
                    Column::make('nim'),
                    Column::make('prodi'),
                    Column::make('pem_proposal')->heading('Pembimbing Proposal')->formatStateUsing(
                        fn($record) => $record->pem_proposal->dosen->name
                    ),
                    Column::make('sid_proposal')->heading('Sidang Proposal')->formatStateUsing(
                        function ($record) {
                            $up = array();
                            foreach ($record->sid_proposal as $value) {
                                array_push($up, $value->dosen->name);
                            }
                            return implode(',', $up);
                        }
                    ),
                    Column::make('m_status_tabs_id')->formatStateUsing(
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
