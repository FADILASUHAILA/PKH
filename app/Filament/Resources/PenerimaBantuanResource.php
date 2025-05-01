<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenerimaBantuanResource\Pages;
use App\Filament\Resources\PenerimaBantuanResource\RelationManagers;
use App\Models\PenerimaBantuan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PenerimaBantuanResource extends Resource
{
    protected static ?string $model = PenerimaBantuan::class;

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-user';

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
            'index' => Pages\ListPenerimaBantuans::route('/'),
            'create' => Pages\CreatePenerimaBantuan::route('/create'),
            'edit' => Pages\EditPenerimaBantuan::route('/{record}/edit'),
        ];
    }
}
