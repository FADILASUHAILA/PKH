<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubKriteriaResource\Pages;
use App\Models\Kriteria;
use App\Models\SubKriteria;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SubKriteriaResource extends Resource
{
    protected static ?string $model = SubKriteria::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('kriteria_id')
                    ->relationship('kriteria', 'nama_kriteria')
                    ->required(),
                Forms\Components\TextInput::make('nama_sub_kriteria')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nilai')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kriteria.nama_kriteria')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_sub_kriteria')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nilai')
                    ->label('Skor')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kriteria')
                    ->relationship('kriteria', 'nama_kriteria')
                    ->multiple()
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
            // Tambahkan relation manager jika diperlukan
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubKriterias::route('/'),
            'create' => Pages\CreateSubKriteria::route('/create'),
            'edit' => Pages\EditSubKriteria::route('/{record}/edit')
            
        ];
    }
}
