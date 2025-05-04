<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DosenResource\Pages;
use App\Filament\Resources\DosenResource\RelationManagers;
use App\Models\MDosenTabs;
use Filament\Actions\StaticAction;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DosenResource extends Resource
{
    protected static ?string $model = MDosenTabs::class;
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Dosen';
    protected static ?string $breadcrumb = "Dosen";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Nama Dosen')->placeholder('Masukan Nama Dosen')->required(),
                TextInput::make('nidn')->label('NIDN Dosen')->placeholder('Masukan NIDN Dosen')->required(),
                TextInput::make('scope')->label('Bidang Keahlian Dosen')->placeholder('Masukan Keahlian Dosen')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama Dosen'),
                TextColumn::make('nidn')->label('NIDN Dosen'),
                TextColumn::make('scope')->label('Keahlian Dosen'),
                TextColumn::make('m_status_tabs_id')->label('Status Dosen')->badge()->color(fn(string $state): string => match ($state) {
                    'Aktif' => 'success',
                    'Tidak Aktif' => 'danger',
                    'Disetujui' => 'success',
                    'Tidak Disetujui' => 'danger'
                })->getStateUsing(fn($record) => $record->status ? $record->status->title : 'Tidak Ada')
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Action::make('aktivated')
                        ->label('Aktifkan')
                        ->action(function ($record) {
                            $record->update([
                                'm_status_tabs_id' => 1,
                            ]);
                        })
                        ->visible(fn($record) =>  $record->m_status_tabs_id === 2)
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Aktifkan Status Dosen')
                        ->modalDescription('Apakah anda yakin ingin mengaktifkan Status Dosen ?')
                        ->modalSubmitActionLabel('Publish Sekarang')
                        ->modalCancelAction(fn(StaticAction $action) => $action->label('Batal')),
                    Action::make('unaktivated')
                        ->label('Non Aktifkan')
                        ->action(function ($record) {
                            $record->update([
                                'm_status_tabs_id' => 2,
                            ]);
                        })
                        ->visible(fn($record) => $record->m_status_tabs_id === 1)
                        ->icon('heroicon-o-no-symbol')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Non Aktifkan Dosen')
                        ->modalDescription('Apakah anda yakin ingin mengnonaktifkan Dosen ?')
                        ->modalSubmitActionLabel('Non Aktif Sekarang')
                        ->modalCancelAction(fn(StaticAction $action) => $action->label('Batal')),
                    Tables\Actions\ViewAction::make()
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make()->modalHeading('Menghapus Informasi Dosen'),
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
            'index' => Pages\ListDosens::route('/'),
            'create' => Pages\CreateDosen::route('/create'),
            'edit' => Pages\EditDosen::route('/{record}/edit'),
        ];
    }
}
