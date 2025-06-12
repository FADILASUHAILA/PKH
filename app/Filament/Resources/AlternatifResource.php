<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AlternatifResource\Pages;
use App\Filament\Resources\AlternatifResource\RelationManagers;
use App\Models\Alternatif;
use App\Models\Desa;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AlternatifResource extends Resource
{
    protected static ?string $model = Alternatif::class;
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode')
                    ->label('Kode Alternatif')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan kode alternatif')
                    ->unique(ignoreRecord: true),

                TextInput::make('nama')
                    ->label('Nama Alternatif')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan nama alternatif'),

                Select::make('desa_id')
                    ->label('Desa')
                    ->relationship('desa', 'nama_desa')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->placeholder('Pilih desa')
                    ->createOptionForm([
                        TextInput::make('nama_desa')
                            ->required()
                            ->maxLength(255),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('nama')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('desa.nama_desa')
                    ->label('Desa')
                    ->sortable()
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
            'index' => Pages\ListAlternatifs::route('/'),
            'create' => Pages\CreateAlternatif::route('/create'),
            'edit' => Pages\EditAlternatif::route('/{record}/edit'),
        ];
    }
}
