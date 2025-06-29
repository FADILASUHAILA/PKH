<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KriteriaResource\Pages;
use App\Filament\Resources\KriteriaResource\RelationManagers;
use App\Models\Kriteria;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KriteriaResource extends Resource
{
    protected static ?string $model = Kriteria::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 2;
    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode')
                 ->searchable()
                ->required(),
                TextInput::make('nama_kriteria')
                 ->searchable()
                ->required(),
                TextInput::make('bobot')
                 ->searchable()
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode')
                 ->searchable(),
                TextColumn::make('nama_kriteria')
                 ->searchable(),
                TextColumn::make('bobot')
                 ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                
                
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
            'index' => Pages\ListKriterias::route('/'),
            'create' => Pages\CreateKriteria::route('/create'),
            'edit' => Pages\EditKriteria::route('/{record}/edit'),
        ];
    }
}
