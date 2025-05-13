<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PublicSkripsiResource\Pages;
use App\Filament\Resources\PublicSkripsiResource\RelationManagers;
use App\Models\PublicSkripsi;
use App\Models\TMahasiswaTab;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PublicSkripsiResource extends Resource
{
    protected static ?string $model = TMahasiswaTab::class;
    protected static ?string $navigationGroup = 'Mahasiswa';
    protected static ?string $navigationLabel = 'Skripsi';
    protected static ?string $breadcrumb = "Skripsi";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPublicSkripsis::route('/'),
            'create' => Pages\CreatePublicSkripsi::route('/create'),
            'edit' => Pages\EditPublicSkripsi::route('/{record}/edit'),
        ];
    }
}
