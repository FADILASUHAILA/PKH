<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HasilPenilaianResource\Pages;
use App\Filament\Resources\HasilPenilaianResource\RelationManagers;
use App\Models\HasilPenilaian;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HasilPenilaianResource extends Resource
{
    protected static ?string $model = HasilPenilaian::class;

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
                Tables\Columns\TextColumn::make('kriteria.nama_kriteria'),
                Tables\Columns\TextColumn::make('alternatif.nama_alternatif'),


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
            'index' => Pages\ListHasilPenilaians::route('/'),
            'create' => Pages\CreateHasilPenilaian::route('/create'),
            // 'edit' => Pages\EditHasilPenilaian::route('/{record}/edit'),
        ];
    }
}
