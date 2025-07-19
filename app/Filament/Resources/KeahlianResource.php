<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KeahlianResource\Pages;
use App\Filament\Resources\KeahlianResource\RelationManagers;
use App\Models\Keahlian;
use App\Models\MKeahlianDosenTabs;
use App\Models\TPeriodeTab;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KeahlianResource extends Resource
{
    protected static ?string $model = MKeahlianDosenTabs::class;
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Keahlian';
    protected static ?string $breadcrumb = "Keahlian";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function shouldRegisterNavigation(): bool
    {
        if (auth()->user()->m_user_roles_id == 2 || auth()->user()->m_user_roles_id == 4) return true;
        else return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Masukan Keahlian')->placeholder('Masukan Keahlian')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama Keahlian'),
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
            'index' => Pages\ListKeahlians::route('/'),
            'create' => Pages\CreateKeahlian::route('/create'),
            'edit' => Pages\EditKeahlian::route('/{record}/edit'),
        ];
    }
}
