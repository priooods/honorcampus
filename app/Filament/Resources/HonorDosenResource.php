<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HonorDosenResource\Pages;
use App\Filament\Resources\HonorDosenResource\RelationManagers;
use App\Models\HonorDosen;
use App\Models\MHonorDosenTabs;
use App\Models\TPeriodeTab;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HonorDosenResource extends Resource
{
    protected static ?string $model = MHonorDosenTabs::class;
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Honor';
    protected static ?string $breadcrumb = "Honor";
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
                Select::make('t_periode_tabs')
                    ->label('Pilih Periode')
                    ->relationship('periode', 'title')
                    ->placeholder('Cari nama Periode')
                    ->options(TPeriodeTab::where('m_status_tabs_id', 1)->pluck('title', 'id'))
                    ->searchable()
                    ->required()
                    ->getSearchResultsUsing(fn(string $search): array => TPeriodeTab::where('m_status_tabs_id', 1)->where('title', 'like', "%{$search}%")->limit(5)->pluck('title', 'id')->toArray())
                    ->getOptionLabelUsing(fn($value): ?string => TPeriodeTab::where('m_status_tabs_id', 1)->find($value)?->title),
                Select::make('type')
                    ->label('Pilih Type Honor')
                    ->placeholder('Pilih Type')
                    ->options([
                        '1' => 'Pembimbing Proposal',
                        '2' => 'Penguji Proposal 1',
                        '3' => 'Penguji Proposal 2',
                        '4' => 'Penguji Proposal 3',
                        '5' => 'Pembimbing Skripsi 1',
                        '6' => 'Pembimbing Skripsi 2',
                        '7' => 'Penguji Skripsi 1',
                        '8' => 'Penguji Skripsi 2',
                        '9' => 'Penguji Skripsi 3',
                    ])
                    ->required(),
                TextInput::make('price')->label('Jumlah Honor')->numeric()->placeholder('Masukan Jumlah Honor')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->groups([
                Group::make('periode.title')
                    ->label('Periode'),
            ])
            ->columns([
                TextColumn::make('t_periode_tabs')->label('Periode Honor')
                    ->getStateUsing(fn($record) => $record->periode ? $record->periode->title : '-'),
                TextColumn::make('type')->label('Type')->getStateUsing(function ($record) {
                    if ($record->type == 1)
                        return 'Pembimbing Proposal';
                    elseif($record->type == 2)
                        return 'Penguji Proposal 1';
                    elseif ($record->type == 3)
                        return 'Penguji Proposal 2';
                    elseif ($record->type == 4)
                        return 'Penguji Proposal 3';
                    elseif ($record->type == 5)
                        return 'Pembimbing Skripsi 1';
                    elseif ($record->type == 6)
                        return 'Pembimbing Skripsi 2';
                    elseif ($record->type == 7)
                        return 'Penguji Skripsi 1';
                    elseif ($record->type == 8)
                        return 'Penguji Skripsi 2';
                    else
                        return 'Penguji Skripsi 3';
                }),
                TextColumn::make('price')->label('Honor')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                
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
            'index' => Pages\ListHonorDosens::route('/'),
            'create' => Pages\CreateHonorDosen::route('/create'),
            'edit' => Pages\EditHonorDosen::route('/{record}/edit'),
        ];
    }
}
