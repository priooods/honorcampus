<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PublicProposalResource\Pages;
use App\Filament\Resources\PublicProposalResource\RelationManagers;
use App\Models\MDosenTabs;
use App\Models\PublicProposal;
use App\Models\TMahasiswaTab;
use App\Models\TPeriodeTab;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
            Select::make('status_proposal')
                ->placeholder('Pilih Status')
                ->label('Status Pembayaran Proposal')
                ->options([
                    0 => 'Belum Lunas',
                    1 => 'Lunas',
                ])
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
            'index' => Pages\ListPublicProposals::route('/'),
            'create' => Pages\CreatePublicProposal::route('/create'),
            'edit' => Pages\EditPublicProposal::route('/{record}/edit'),
        ];
    }
}
