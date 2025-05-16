<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FinanceSkripsiResource\Pages;
use App\Filament\Resources\FinanceSkripsiResource\RelationManagers;
use App\Models\FinanceSkripsi;
use App\Models\TMahasiswaTab;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FinanceSkripsiResource extends Resource
{
    protected static ?string $model = TMahasiswaTab::class;
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Skripsi';
    protected static ?string $breadcrumb = "Skripsi";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function shouldRegisterNavigation(): bool
    {
        if (auth()->user()->m_user_roles_id == 2) return true;
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
            ])
            ->filters([
                //
            ])
            ->actions([
            // Tables\Actions\EditAction::make(),
        ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListFinanceSkripsis::route('/'),
            'create' => Pages\CreateFinanceSkripsi::route('/create'),
            'edit' => Pages\EditFinanceSkripsi::route('/{record}/edit'),
        ];
    }
}
