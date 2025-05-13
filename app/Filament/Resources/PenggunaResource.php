<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenggunaResource\Pages;
use App\Models\MUserRole;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class PenggunaResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Pengguna';
    protected static ?string $breadcrumb = "Pengguna";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            TextInput::make('name')->label('Nama Pengguna')->placeholder('Masukan Nama Pengguna')->required(),
            TextInput::make('email')->email()->label('Email Pengguna')->placeholder('Masukan Email Pengguna')->required(),
            Select::make('m_user_roles_id')
                ->label('Pilih Role')
                ->relationship('Roles', 'title')
                ->placeholder('Cari nama Role')
                ->options(MUserRole::all()->pluck('title', 'id'))
                ->searchable()
                ->required()
                ->getSearchResultsUsing(fn(string $search): array => MUserRole::where('title', 'like', "%{$search}%")->limit(5)->pluck('title', 'id')->toArray())
                ->getOptionLabelUsing(fn($value): ?string => MUserRole::find($value)?->title),
            TextInput::make('password')->label('Password Akun')
                ->password()->revealable()
                ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                ->same('passwordConfirmation')
                ->placeholder('Masukan Password')
                ->dehydrated(fn(?string $state): bool => filled($state))
                ->required()
                ->afterStateHydrated(function (TextInput $component, $state) {
                    $component->state('');
                    }),
                TextInput::make('passwordConfirmation')->label('Confirmasi Password Akun')->password()->revealable()->placeholder('Masukan Password')->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                User::whereNot('id', auth()->user()->id)
            )
            ->columns([
            TextColumn::make('name')->label('Nama Pengguna'),
            TextColumn::make('email')->label('Email Pengguna'),
            TextColumn::make('m_user_roles_id')->label('Role')->badge()->getStateUsing(fn($record) => $record->roles ? $record->roles->title : 'Tidak Ada'),
            ])
            ->filters([
                //
            ])
            ->actions([
            Tables\Actions\EditAction::make()->visible(auth()->user()->m_user_roles_id === 4),
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
            'index' => Pages\ListPenggunas::route('/'),
            'create' => Pages\CreatePengguna::route('/create'),
            'edit' => Pages\EditPengguna::route('/{record}/edit'),
        ];
    }
}
