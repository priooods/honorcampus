<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FinanceProposalResource\Pages;
use App\Filament\Resources\FinanceProposalResource\RelationManagers;
use App\Models\FinanceProposal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FinanceProposalResource extends Resource
{
    protected static ?string $model = FinanceProposal::class;
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Proposal';
    protected static ?string $breadcrumb = "Proposal";
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
            'index' => Pages\ListFinanceProposals::route('/'),
            'create' => Pages\CreateFinanceProposal::route('/create'),
            'edit' => Pages\EditFinanceProposal::route('/{record}/edit'),
        ];
    }
}
