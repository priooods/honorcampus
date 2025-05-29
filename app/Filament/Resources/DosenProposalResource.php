<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DosenProposalResource\Pages;
use App\Filament\Resources\DosenProposalResource\RelationManagers;
use App\Models\DosenProposal;
use App\Models\THonorTab;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DosenProposalResource extends Resource
{
    protected static ?string $model = THonorTab::class;
    protected static ?string $navigationGroup = 'Dosen';
    protected static ?string $navigationLabel = 'Proposal';
    protected static ?string $breadcrumb = "Proposal";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function shouldRegisterNavigation(): bool
    {
        if (auth()->user()->m_user_roles_id == 3) return true;
        else return false;
    }

    public function getDefaultActiveTab(): string | int | null
    {
        return 'bimbingan';
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
            TextColumn::make('t_mahasiswa_tabs')->label('Nama Mahasiswa')
                ->getStateUsing(fn($record) => $record->mahasiswa ? $record->mahasiswa->name : '-'),
            TextColumn::make('nim')->label('NPM Mahasiswa')
                ->getStateUsing(fn($record) => $record->mahasiswa ? $record->mahasiswa->nim : '-'),
            TextColumn::make('sequent')->label('Pembimbing/Penguji'),
            TextColumn::make('honor')->label('Honor')
            ])
            ->filters([
                //
            ])
            ->actions([
            Action::make('honor')->label('Cetak')->action(function (array $data, THonorTab $record) {
                //
            })
                ->icon('heroicon-o-check')
                ->color('success'),
            ])
            ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
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
            'index' => Pages\ListDosenProposals::route('/'),
            'create' => Pages\CreateDosenProposal::route('/create'),
            'edit' => Pages\EditDosenProposal::route('/{record}/edit'),
        ];
    }
}
