<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HonorResource\Pages;
use App\Filament\Resources\HonorResource\RelationManagers;
use App\Models\Honor;
use App\Models\THonorTab;
use Filament\Actions\StaticAction;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HonorResource extends Resource
{
    protected static ?string $model = THonorTab::class;
    protected static ?string $navigationLabel = 'Honor Dosen';
    protected static ?string $breadcrumb = "Honor Dosen";
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
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table->query(
            THonorTab::where('honor', 0)
        )
            ->columns([
            TextColumn::make('m_dosen_tabs_id')->label('Nama Dosen')->getStateUsing(fn($record) => $record->dosen ? $record->dosen->name : '-'),
            TextColumn::make('mahasiswa')->label('Nama Mahasiswa')
                ->getStateUsing(fn($record) => $record->mahasiswa ? $record->mahasiswa->name : '-')
                ->description(fn($record): string => 'NPM ' . $record->mahasiswa->nim),
            TextColumn::make('type_request')->label('Keterangan')
                ->getStateUsing(function ($record) {
                    if ($record->type_request_detail->id == 3)
                        return $record->type_request ? 'Pembimbing ' . $record->sequent . ' ' . $record->type_request->title : '-';
                    if ($record->type_request_detail->id == 4)
                        return $record->type_request ? 'Penguji ' . $record->sequent . ' ' . $record->type_request_detail->title . ' ' . $record->type_request->title : '-';
                }),
            TextColumn::make('mahasiswa_status')->label('Progress Mahasiswa')->badge()
                ->getStateUsing(fn($record) => $record->mahasiswa ? $record->mahasiswa->status->title : '-')
                ->color(fn(string $state): string => match ($state) {
                    'Bimbingan Proposal' => 'info',
                    'Bimbingan Skripsi' => 'info',
                    'Sidang Proposal' => 'success',
                    'Sidang Skripsi' => 'success',
                    'Disetujui' => 'success',
                    'Pengajuan' => 'success',
                    'Batal Pengajuan' => 'danger',
                    'Draft' => 'gray',
                })
        ])
            ->filters([
                //
            ])
            ->actions([
            Action::make('honor')->label('Atur Honor')->action(function (array $data, THonorTab $record) {
                $record->update([
                    'honor' => $data['honor'],
                ]);
            })
                ->form([
                    TextInput::make('honor')->numeric()->label('Honor Dosen')->placeholder('Masukan Honor Dosen')->required(),
                ])
                ->icon('heroicon-o-check')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Atur Honor Dosen')
                ->modalDescription('Masukan honor yang ingin diberikan untuk Dosen')
                ->modalSubmitActionLabel('Simpan')
                ->modalCancelAction(fn(StaticAction $action) => $action->label('Batal')),
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
            'index' => Pages\ListHonors::route('/'),
            'create' => Pages\CreateHonor::route('/create'),
            'edit' => Pages\EditHonor::route('/{record}/edit'),
        ];
    }
}
