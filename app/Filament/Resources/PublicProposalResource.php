<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PublicProposalResource\Pages;
use App\Models\MDosenTabs;
use App\Models\THonorTab;
use App\Models\TMahasiswaTab;
use App\Models\TPeriodeTab;
use App\Models\User;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\Repeater;
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

class PublicProposalResource extends Resource
{
    protected static ?string $model = TMahasiswaTab::class;
    protected static ?string $navigationGroup = 'Mahasiswa';
    protected static ?string $navigationLabel = 'Proposal';
    protected static ?string $breadcrumb = "Proposal";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
            Select::make('status_proposal')
                ->placeholder('Pilih Status')
                ->label('Status Pembayaran Proposal')
                ->options([
                    0 => 'Belum Lunas',
                    1 => 'Lunas',
                ])
            ]);
    }

    public function getDefaultActiveTab(): string | int | null
    {
        return 'bimbingan';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                if (auth()->user()->m_user_roles_id === 3) { // Admin
                    return $query->whereIn('m_status_tabs_id', [5, 6, 7]);
                } else if (auth()->user()->m_user_roles_id === 2) { // Kaprodi
                    return $query->whereIn('m_status_tabs_id', [3, 4, 5]);
                }
            })
            ->columns([
            TextColumn::make('nim')->label('NPM'),
            TextColumn::make('name')->label('Nama Mahasiswa'),
            TextColumn::make('prodi')->label('Prodi'),
            TextColumn::make('status_proposal')->label('Status Bayar')->badge()->color(fn(string $state): string => match ($state) {
                'Belum Lunas' => 'danger',
                'Lunas' => 'success',
            })->getStateUsing(fn($record) => $record->status_proposal ? 'Lunas' : 'Belum Lunas'),
            TextColumn::make('m_status_tabs_id')->label('Status Pengajuan')->badge()->color(fn(string $state): string => match ($state) {
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
                            'm_status_tabs_id' => 3,
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
                            ->label('Pilih Dosen yang akan Menguji')
                            ->relationship()
                            ->id('t_honor_tabs_id')
                            ->schema([
                                Select::make('m_dosen_tabs_id')
                                    ->label('Pilih Dosen Penguji')
                                    ->placeholder('Cari nama Dosen')
                                    ->options(MDosenTabs::where('m_status_tabs_id', 1)->pluck('name', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->getSearchResultsUsing(fn(string $search): array => MDosenTabs::where('name', 'like', "%{$search}%")->limit(5)->pluck('name', 'id')->toArray())
                                    ->getOptionLabelUsing(fn($value): ?string => MDosenTabs::find($value)?->name),
                            ])
                            ->defaultItems(1)
                            ->reorderable(true)
                            ->dehydrated(true)
                            ->addActionLabel('Tambah Dosen Penguji')
                            ->itemLabel(fn(array $state): ?string => $state['name'] ?? null)
                    ])
                    ->action(function ($record) {
                        $record->update([
                            'm_status_tabs_id' => 5,
                        ]);
                    })
                    ->visible(fn($record) =>  $record->m_status_tabs_id === 3)
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Akan/Sudah Sidang Proposal')
                    ->modalDescription('Mahasiswa yang bersangkutan akan/sudah sidang proposal ?')
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
