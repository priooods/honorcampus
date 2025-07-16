<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PublicProposalResource\Pages;
use App\Models\MDosenTabs;
use App\Models\THonorTab;
use App\Models\TMahasiswaTab;
use App\Models\TPeriodeTab;
use App\Models\User;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class PublicProposalResource extends Resource
{
    protected static ?string $model = TMahasiswaTab::class;
    protected static ?string $navigationGroup = 'Mahasiswa';
    protected static ?string $navigationLabel = 'Proposal';
    protected static ?string $breadcrumb = "Proposal";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function shouldRegisterNavigation(): bool
    {
        if (auth()->user()->m_user_roles_id != 3) return true;
        else return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            Group::make([
                Select::make('t_periode_tabs')
                    ->label('Pilih Periode')
                    ->relationship('periode', 'title')
                    ->placeholder('Cari Periode')
                    ->options(TPeriodeTab::where('m_status_tabs_id', 1)->pluck('title', 'id'))
                    ->searchable()
                    ->required()
                    ->getSearchResultsUsing(fn(string $search): array => TPeriodeTab::where('title', 'like', "%{$search}%")->limit(5)->pluck('title', 'id')->toArray())
                    ->getOptionLabelUsing(fn($value): ?string => TPeriodeTab::find($value)?->title),
                TextInput::make('name')->label('Nama Mahasiswa')->placeholder('Masukan Nama Mahasiswa')->required(),
                TextInput::make('nim')->label('NPM')->numeric()->placeholder('Masukan NPM')->required(),
                TextInput::make('prodi')->label('Prodi Mahasiswa')->placeholder('Masukan Prodi Mahasiswa')->required(),
                Select::make('status_bimbingan_proposal')
                    ->placeholder('Pilih Status Pembayaran')
                    ->label('Status Pembayaran Proposal')
                    ->options([
                        0 => 'Belum Lunas',
                        1 => 'Lunas',
                    ])
            ])->columns(1)
            ]);
    }

    public function getDefaultActiveTab(): string | int | null
    {
        return 'bimbingan';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('t_periode_tabs')->label('Periode')->getStateUsing(fn($record) => $record->periode ? $record->periode->title : '-'),
            TextColumn::make('nim')->label('NPM'),
            TextColumn::make('name')->label('Nama Mahasiswa'),
            TextColumn::make('prodi')->label('Prodi'),
            TextColumn::make('status_bimbingan_proposal')->label('Status Bayar')->badge()->color(fn(string $state): string => match ($state) {
                'Belum Lunas' => 'danger',
                'Lunas' => 'success',
            })->getStateUsing(fn($record) => $record->status_bimbingan_proposal ? 'Lunas' : 'Belum Lunas'),
            TextColumn::make('m_status_tabs_id')->label('Status Pengajuan')->badge()->color(fn(string $state): string => match ($state) {
                'Bimbingan Proposal' => 'success',
                'Sidang Proposal' => 'info',
                'Disetujui' => 'success',
                'Pengajuan' => 'success',
                'Batal Pengajuan' => 'danger',
                'Draft' => 'gray',
            })->getStateUsing(fn($record) => $record->status ? $record->status->title : 'Tidak Ada')
            ])
            ->filters([
                //
            ])
            ->actions([
            ActionGroup::make([
                Tables\Actions\EditAction::make()->visible(fn($record) =>  $record->m_status_tabs_id === 7),
                Action::make('pengajuan')
                    ->label('Ajukan Mahasiswa')
                    ->action(function ($record) {
                        $record->update([
                            'm_status_tabs_id' => 5,
                        ]);
                    })
                    ->visible(fn($record) =>  $record->m_status_tabs_id === 7)
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Ajukan Mahasiswa')
                    ->modalDescription('Apakah anda yakin ingin Mengajukan Data Mahasiswa ?')
                    ->modalSubmitActionLabel('Ajukan Sekarang')
                    ->modalCancelAction(fn(StaticAction $action) => $action->label('Batal')),
                Action::make('unpengajuan') // for admin
                    ->label('Batalkan Pengajuan')
                    ->action(function ($record) {
                        $record->update([
                            'm_status_tabs_id' => 6,
                        ]);
                    })
                    ->visible(fn($record) => $record->m_status_tabs_id === 5  && auth()->user()->m_user_roles_id === 3)
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Batalkan Pengajuan Mahasiswa')
                    ->modalDescription('Apakah anda yakin ingin Batalkan Pengajuan Mahasiswa ?')
                    ->modalSubmitActionLabel('Batalkan Pengajuan')
                    ->modalCancelAction(fn(StaticAction $action) => $action->label('Batal')),
                Action::make('draft') // for admin
                    ->label('Draftkan Mahasiswa')
                    ->action(function ($record) {
                        $record->update([
                            'm_status_tabs_id' => 7,
                        ]);
                    })
                    ->visible(fn($record) =>  $record->m_status_tabs_id === 6)
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Draftkan Mahasiswa')
                    ->modalDescription('Apakah anda yakin ingin Draftkan Data Mahasiswa ?')
                    ->modalSubmitActionLabel('Draftkan Sekarang')
                    ->modalCancelAction(fn(StaticAction $action) => $action->label('Batal')),
                Action::make('accept')
                    ->label('Setujui Pengajuan')
                    ->form([
                        Select::make('m_dosen_tabs_id')
                            ->label('Pilih Dosen Pembimbing')
                            ->placeholder('Cari nama Dosen')
                            ->options(MDosenTabs::where('m_status_tabs_id', 1)->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->getSearchResultsUsing(fn(string $search): array => MDosenTabs::where('name', 'like', "%{$search}%")->limit(5)->pluck('name', 'id')->toArray())
                            ->getOptionLabelUsing(fn($value): ?string => MDosenTabs::find($value)?->name),
                    ])
                    ->action(function (array $data, TMahasiswaTab $record): void {
                        THonorTab::create([
                            't_mahasiswa_tabs' => $record->id,
                            'm_dosen_tabs_id' => $data['m_dosen_tabs_id'],
                            'm_type_request_id' => 1,
                            'm_type_request_id_detail' => 3,
                            'sequent' => 1,
                        ]);
                        $record->update([
                        'm_status_tabs_id' => 8,
                        ]);
                    })
                    ->visible(fn($record) => $record->m_status_tabs_id === 5 && auth()->user()->m_user_roles_id === 2)
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Pengajuan Mahasiswa')
                    ->modalDescription('Apakah anda yakin ingin Menyetujui Pengajuan Mahasiswa yang bersangkutan ?')
                    ->modalSubmitActionLabel('Setujui Pengajuan')
                    ->modalCancelAction(fn(StaticAction $action) => $action->label('Batal')),
                Action::make('lanjutsidang')
                    ->label('Lanjut Sidang')
                    ->form([
                    Repeater::make('honor')
                        ->label('Pilih Dosen yang Menguji')
                        ->simple(
                            Select::make('m_dosen_tabs_id')
                                ->label('Pilih Dosen Penguji')
                                ->placeholder('Cari nama Dosen')
                                ->options(MDosenTabs::where('m_status_tabs_id', 1)->pluck('name', 'id'))
                                ->searchable()
                                ->required()
                                ->getSearchResultsUsing(fn(string $search): array => MDosenTabs::where('name', 'like', "%{$search}%")->limit(5)->pluck('name', 'id')->toArray())
                                ->getOptionLabelUsing(fn($value): ?string => MDosenTabs::find($value)?->name),
                        )
                        ->defaultItems(1)
                        ->reorderable(true)
                        ->dehydrated(true)
                        ->reorderableWithButtons()
                        ->addActionLabel('Tambah Dosen Penguji')
                        ->itemLabel(fn(array $state): ?string => $state['name'] ?? null)
                ])
                    ->action(function (array $data, TMahasiswaTab $record): void {
                        foreach ($data as $value) {
                            foreach ($value as $i => $item) {
                                THonorTab::create([
                                    't_mahasiswa_tabs' => $record->id,
                                    'm_dosen_tabs_id' => $item,
                                    'm_type_request_id' => 1,
                                    'm_type_request_id_detail' => 4,
                                'sequent' => (int)$i + 1,
                                ]);
                            }
                        }
                        $record->update([
                            'm_status_tabs_id' => 9,
                            'status_sidang_proposal' => 1,
                        'progres_bimbingan_proposal' => 1,
                        ]);
                    })
                    ->visible(fn($record) =>  $record->m_status_tabs_id === 8  && auth()->user()->m_user_roles_id === 2)
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Akan Sidang Proposal')
                    ->modalDescription('Mahasiswa yang bersangkutan akan sidang proposal ?')
                    ->modalSubmitActionLabel('Setujui Sekarang')
                    ->modalCancelAction(fn(StaticAction $action) => $action->label('Batal')),
                Action::make('lanjutbimskripsi')
                    ->label('Lanjut Bimbingan Skripsi')
                    ->form([
                        Repeater::make('skripsi')
                            ->label('Pilih Dosen Pembimbing')
                            ->simple(
                                Select::make('m_dosen_tabs_id')
                                    ->label('Pilih Dosen Pembimbing')
                                    ->placeholder('Cari nama Dosen')
                                    ->options(MDosenTabs::where('m_status_tabs_id', 1)->pluck('name', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->getSearchResultsUsing(fn(string $search): array => MDosenTabs::where('name', 'like', "%{$search}%")->limit(5)->pluck('name', 'id')->toArray())
                                    ->getOptionLabelUsing(fn($value): ?string => MDosenTabs::find($value)?->name),
                            )
                        ->defaultItems(1)
                        ->reorderable(true)
                        ->dehydrated(true)
                        ->reorderableWithButtons()
                        ->addActionLabel('Tambah Dosen Pembimbing')
                        ->itemLabel(fn(array $state): ?string => $state['name'] ?? null)
                    ])
                    ->action(function (array $data, TMahasiswaTab $record): void {
                        foreach ($data as $value) {
                            foreach ($value as $i => $item) {
                                THonorTab::create([
                                    't_mahasiswa_tabs' => $record->id,
                                    'm_dosen_tabs_id' => $item,
                                    'm_type_request_id' => 2,
                                    'm_type_request_id_detail' => 3,
                                    'sequent' => (int)$i + 1,
                                ]);
                            }
                        }
                        $record->update([
                        'm_status_tabs_id' => 10,
                        'status_bimbingan_skripsi' => 1,
                        'progres_bimbingan_skripsi' => 0,
                        ]);
                    })
                    ->visible(fn($record) =>  $record->m_status_tabs_id === 9  && (auth()->user()->m_user_roles_id === 4 || auth()->user()->m_user_roles_id === 2))
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Lulus Sidang Proposal')
                    ->modalDescription('Mahasiswa yang bersangkutan dinyatakan lulus proposal dan melanjutkan skripsi ?')
                    ->modalSubmitActionLabel('Setujui Sekarang')
                    ->modalCancelAction(fn(StaticAction $action) => $action->label('Batal')),
                Tables\Actions\ViewAction::make()
            ])->label('Action')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPublicProposals::route('/'),
            'create' => Pages\CreatePublicProposal::route('/create'),
            'edit' => Pages\EditPublicProposal::route('/{record}/edit'),
        ];
    }
}
