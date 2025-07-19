<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DosenSkripsiResource\Pages;
use App\Filament\Resources\DosenSkripsiResource\RelationManagers;
use App\Models\DosenSkripsi;
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

class DosenSkripsiResource extends Resource
{
    protected static ?string $model = THonorTab::class;
    protected static ?string $navigationGroup = 'Dosen';
    protected static ?string $navigationLabel = 'Skripsi';
    protected static ?string $breadcrumb = "Skripsi";
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
            TextColumn::make('type_request')->label('Pembimbing/Penguji')
                ->getStateUsing(function ($record) {
                    if ($record->type_request_detail->id == 3)
                        return $record->type_request ? 'Pembimbing ' . $record->sequent . ' ' . $record->type_request->title : '-';
                    if ($record->type_request_detail->id == 4)
                        return $record->type_request ? 'Penguji ' . $record->sequent . ' ' . $record->type_request_detail->title . ' ' . $record->type_request->title : '-';
                }),
            TextColumn::make('honor')->label('Honor')
            ])
            ->filters([
                //
            ])
            ->actions([
            Action::make('pdf')->visible(fn($record) => $record->honor !== 0)->label('Print')->icon('heroicon-o-check')->color('danger')->url(fn(THonorTab $record): string => route('pdf.report', ['id' => $record]), shouldOpenInNewTab: true)
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
            'index' => Pages\ListDosenSkripsis::route('/'),
            'create' => Pages\CreateDosenSkripsi::route('/create'),
            'edit' => Pages\EditDosenSkripsi::route('/{record}/edit'),
        ];
    }
}
