<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PublicProposalResource\Pages;
use App\Models\MDosenTabs;
use App\Models\MHonorDosenTabs;
use App\Models\THonorTab;
use App\Models\TMahasiswaTab;
use App\Models\TPeriodeTab;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Arr;

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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema(
            [
                Section::make('Informasi Mahasiswa')
                    ->description('Informasi Detail Mahasiswa')
                    ->schema([
                        TextEntry::make('t_periode_tabs')->label('Periode')
                            ->getStateUsing(fn($record) => $record->periode?->title ?? ''),
                        TextEntry::make('name')->label('Nama'),
                        TextEntry::make('status_bimbingan_proposal')->label('Status Bimbingan')
                            ->getStateUsing(fn($record) => $record->status_bimbingan_proposal == 0 ? 'Belum Lunas' : 'Lunas'),
                        TextEntry::make('nim')->label('NIM'),
                        TextEntry::make('prodi')->label('Prodi'),
                    ])->columns(2),
                Section::make('Informasi Dosen')
                    ->description('Informasi Detail Dosen')
                    ->schema([
                        RepeatableEntry::make('dosen_list')->label('Informasi Dosen')
                            ->schema([
                                TextEntry::make('dosen')->label('Nama Dosen')
                                    ->getStateUsing(fn($record) => $record->dosen?->name ?? ''),
                                TextEntry::make('dosen_nidn')->label('NIDN Dosen')
                                    ->getStateUsing(fn($record) => $record->dosen?->nidn ?? ''),
                                TextEntry::make('type_request')->label('Tugas')
                                    ->getStateUsing(function ($record) {
                                        if ($record->type_request_detail->id == 3)
                                            return $record->type_request ? 'Pembimbing ' . $record->sequent . ' ' . $record->type_request->title : '-';
                                        if ($record->type_request_detail->id == 4)
                                            return $record->type_request ? 'Penguji ' . $record->sequent . ' ' . $record->type_request_detail->title . ' ' . $record->type_request->title : '-';
                                    }),
                            ])->columns(3)
                    ]),
            ]
        );
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
                Select::make('prodi')
                    ->placeholder('Pilih Prodi Mahasiswa')
                    ->label('Prodi Mahasiswa')
                    ->options([
                        'Teknik Informatika' => 'Teknik Informatika',
                    ]),
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
                Action::make('lunas')
                    ->label('Lunas Pembayaran')
                    ->action(function ($record) {
                        $record->update([
                            'status_bimbingan_proposal' => 1,
                        ]);
                    })
                    ->visible(fn($record) =>  $record->m_status_tabs_id === 7 && $record->status_bimbingan_proposal === 0)
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Lunas Pembayaran Proposal')
                    ->modalDescription('Apakah Mahasiswa telah lunas melakukan pembayaran ?')
                    ->modalSubmitActionLabel('Simpan')
                    ->modalCancelAction(fn(StaticAction $action) => $action->label('Batal')),
                Action::make('pengajuan')
                    ->label('Ajukan Mahasiswa')
                    ->action(function ($record) {
                        $record->update([
                            'm_status_tabs_id' => 5,
                        ]);
                    })
                    ->visible(fn($record) =>  $record->m_status_tabs_id === 7 && $record->status_bimbingan_proposal === 1)
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
                        ->disableOptionWhen(fn(string $value): bool => $value === 'published')
                            ->getOptionLabelUsing(fn($value): ?string => MDosenTabs::find($value)?->name),
                    ])
                    ->action(function (array $data, TMahasiswaTab $record): void {
                    $findHonor = MHonorDosenTabs::where('t_periode_tabs', $record->t_periode_tabs)
                        ->where('type', 1)->first();
                    if (!isset($findHonor)) {
                        Notification::make()
                            ->title('Saved failure')
                            ->color('danger')
                            ->send();
                        $this->halt();
                    }
                        THonorTab::create([
                            't_mahasiswa_tabs' => $record->id,
                            'm_dosen_tabs_id' => $data['m_dosen_tabs_id'],
                            'm_type_request_id' => 1,
                            'm_type_request_id_detail' => 3,
                            'sequent' => 1,
                        'honor' => $findHonor->price,
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
                Action::make('edit_accept')
                    ->label('Edit Dospem')
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
                        THonorTab::where('t_mahasiswa_tabs', $record->id)->where('m_type_request_id', 1)->where('m_type_request_id_detail', 3)->delete();
                        $findHonor = MHonorDosenTabs::where('t_periode_tabs', $record->t_periode_tabs)
                            ->where('type', 1)->first();
                        if (!isset($findHonor)) {
                            Notification::make()
                                ->title('Saved failure')
                                ->color('danger')
                                ->send();
                            $this->halt();
                        }
                        THonorTab::create([
                            't_mahasiswa_tabs' => $record->id,
                            'm_dosen_tabs_id' => $data['m_dosen_tabs_id'],
                            'm_type_request_id' => 1,
                            'm_type_request_id_detail' => 3,
                            'sequent' => 1,
                            'honor' => $findHonor->price,
                        ]);
                    })
                    ->visible(fn($record) => $record->m_status_tabs_id === 8 && auth()->user()->m_user_roles_id === 2)
                    ->icon('heroicon-o-check')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Pengajuan Mahasiswa')
                    ->modalDescription('Apakah anda yakin ingin Menyetujui Pengajuan Mahasiswa yang bersangkutan ?')
                    ->modalSubmitActionLabel('Setujui Pengajuan')
                    ->modalCancelAction(fn(StaticAction $action) => $action->label('Batal')),
                Action::make('lanjutsidang')
                    ->label('Lanjut Sidang')
                    ->form([
                    Repeater::make('honor')
                        ->maxItems(3)
                        ->label('Pilih Dosen yang Menguji')
                        ->simple(
                            Select::make('m_dosen_tabs_id')
                                ->label('Pilih Dosen Penguji')
                                ->placeholder('Cari nama Dosen')
                                ->options(MDosenTabs::where('m_status_tabs_id', 1)->pluck('name', 'id'))
                                ->searchable()
                                ->required()
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
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
                    ->action(function (array $data, TMahasiswaTab $record) {
                        foreach ($data as $value) {
                            foreach ($value as $i => $item) {
                            $findHonor = MHonorDosenTabs::where('t_periode_tabs', $record->t_periode_tabs)
                                ->where('type', ($i + 2))
                                ->first();
                            if (!isset($findHonor)) {
                                Notification::make()
                                    ->title('Saved failure')
                                    ->color('danger')
                                    ->body('Honor dosen belum anda lengkapi untuk periode ini')
                                    ->send();
                                return false;
                            }
                                THonorTab::create([
                                't_mahasiswa_tabs' => $record->id,
                                'm_dosen_tabs_id' => $item,
                                'm_type_request_id' => 1,
                                'm_type_request_id_detail' => 4,
                                'sequent' => (int)$i + 1,
                                'honor' => $findHonor->price,
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
                Action::make('edit_sidang')
                    ->label('Edit Dosen')
                    ->form([
                        Repeater::make('honor')
                            ->maxItems(3)
                            ->label('Pilih Dosen yang Menguji')
                            ->simple(
                                Select::make('m_dosen_tabs_id')
                                    ->label('Pilih Dosen Penguji')
                                    ->placeholder('Cari nama Dosen')
                                    ->options(MDosenTabs::where('m_status_tabs_id', 1)->pluck('name', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
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
                    ->action(function (array $data, TMahasiswaTab $record) {
                        THonorTab::where('t_mahasiswa_tabs', $record->id)->where('m_type_request_id', 1)->where('m_type_request_id_detail', 4)->delete();
                        foreach ($data as $value) {
                            foreach ($value as $i => $item) {
                                $findHonor = MHonorDosenTabs::where('t_periode_tabs', $record->t_periode_tabs)
                                    ->where('type', ($i + 2))
                                    ->first();
                                if (!isset($findHonor)) {
                                    Notification::make()
                                        ->title('Saved failure')
                                        ->color('danger')
                                        ->body('Honor dosen belum anda lengkapi untuk periode ini')
                                        ->send();
                                    return false;
                                }
                                THonorTab::create([
                                    't_mahasiswa_tabs' => $record->id,
                                    'm_dosen_tabs_id' => $item,
                                    'm_type_request_id' => 1,
                                    'm_type_request_id_detail' => 4,
                                    'sequent' => (int)$i + 1,
                                    'honor' => $findHonor->price,
                                ]);
                            }
                        }
                    })
                    ->visible(fn($record) =>  $record->m_status_tabs_id === 9  && auth()->user()->m_user_roles_id === 2)
                    ->icon('heroicon-o-check')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->modalHeading('Edit Dosen Penguji Sidang Proposal')
                    ->modalDescription('Apakah kamu yakin ingin mengedit Dosen Penguji Sidang Proposal ?')
                    ->modalSubmitActionLabel('Simpan Perubahan')
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
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->getSearchResultsUsing(fn(string $search): array => MDosenTabs::where('name', 'like', "%{$search}%")->limit(5)->pluck('name', 'id')->toArray())
                                    ->getOptionLabelUsing(fn($value): ?string => MDosenTabs::find($value)?->name),
                            )
                        ->maxItems(2)
                        ->defaultItems(1)
                        ->reorderable(true)
                        ->dehydrated(true)
                        ->reorderableWithButtons()
                        ->addActionLabel('Tambah Dosen Pembimbing')
                        ->itemLabel(fn(array $state): ?string => $state['name'] ?? null)
                    ])
                    ->action(function (array $data, TMahasiswaTab $record) {
                        foreach ($data as $value) {
                            foreach ($value as $i => $item) {
                            $findHonor = MHonorDosenTabs::where('t_periode_tabs', $record->t_periode_tabs)
                                ->where('type', ($i + 5))->first();
                            if (!isset($findHonor)) {
                                Notification::make()
                                    ->title('Saved failure')
                                    ->color('danger')
                                    ->body('Honor Pembimbing Skripsi belum anda lengkapi untuk periode ini')
                                    ->send();
                                return false;
                            }
                            THonorTab::create([
                                't_mahasiswa_tabs' => $record->id,
                                'm_dosen_tabs_id' => $item,
                                'm_type_request_id' => 2,
                                'm_type_request_id_detail' => 3,
                                'sequent' => (int)$i + 1,
                                'honor' => $findHonor->price,
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
