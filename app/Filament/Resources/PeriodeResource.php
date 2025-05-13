<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PeriodeResource\Pages;
use App\Filament\Resources\PeriodeResource\RelationManagers;
use App\Models\Periode;
use App\Models\TPeriodeTab;
use Filament\Actions\StaticAction;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
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

class PeriodeResource extends Resource
{
    protected static ?string $model = TPeriodeTab::class;
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Periode';
    protected static ?string $breadcrumb = "Periode";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')->label('Nama Periode')->placeholder('Masukan Nama Periode')->required(),
                DatePicker::make('start_date')->label('Waktu Mulai')->placeholder('Masukan Waktu')->native(false)->required(),
                DatePicker::make('end_date')->label('Waktu Selesai')->placeholder('Masukan Waktu')->native(false)->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Nama Periode'),
                TextColumn::make('start_date')->label('Waktu Mulai')->date(),
                TextColumn::make('end_date')->label('Waktu Selesai')->date(),
                TextColumn::make('m_status_tabs_id')->label('Status Periode')->badge()->color(fn(string $state): string => match ($state) {
                    'Aktif' => 'success',
                    'Tidak Aktif' => 'danger',
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
                        ->modalHeading('Aktifkan Status Periode')
                        ->modalDescription('Apakah anda yakin ingin mengaktifkan Status Periode ?')
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
                        ->modalHeading('Non Aktifkan Periode')
                        ->modalDescription('Apakah anda yakin ingin mengnonaktifkan Periode ?')
                        ->modalSubmitActionLabel('Non Aktif Sekarang')
                        ->modalCancelAction(fn(StaticAction $action) => $action->label('Batal')),
            ])->visible(auth()->user()->m_user_roles_id === 4)
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
            'index' => Pages\ListPeriodes::route('/'),
            'create' => Pages\CreatePeriode::route('/create'),
            'edit' => Pages\EditPeriode::route('/{record}/edit'),
        ];
    }
}
