<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FinanceSkripsiResource\Pages;
use App\Filament\Resources\FinanceSkripsiResource\RelationManagers;
use App\Models\FinanceSkripsi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FinanceSkripsiResource extends Resource
{
    protected static ?string $model = FinanceSkripsi::class;

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
            'index' => Pages\ListFinanceSkripsis::route('/'),
            'create' => Pages\CreateFinanceSkripsi::route('/create'),
            'edit' => Pages\EditFinanceSkripsi::route('/{record}/edit'),
        ];
    }
}
