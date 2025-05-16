<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenilaianResource\Pages;
use App\Filament\Resources\PenilaianResource\RelationManagers;
use App\Models\Alternatif;
use App\Models\Penilaian;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SelectTable;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PenilaianResource extends Resource
{
    protected static ?string $model = Penilaian::class;

    protected static ?int $navigationSort = 4;
    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?string $navigationGroup = 'Perhitungan';
    protected static ?string $modelLabel = 'Penilaian';
    protected static ?string $pluralModelLabel = 'Data Penilaian';

    // public static function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             Forms\Components\Section::make('Informasi Penilaian')
    //                 ->schema([
    //                     TextInput::make('kode')
    //                         ->label('Kode Penilaian')
    //                         ->required()
    //                         ->unique(ignoreRecord: true)
    //                         ->maxLength(50),

    //                     Select::make('alternatif_id')
    //                         ->relationship('alternatif', 'nama')
    //                         ->label('Alternatif')
    //                         ->required()
    //                         ->searchable()
    //                         ->preload()
    //                         ->native(false),

    //                     Select::make('desa_id')
    //                         ->relationship('desa', 'nama_desa')
    //                         ->label('Desa')
    //                         ->searchable()
    //                         ->preload()
    //                         ->native(false),
    //                 ])->columns(2),

    //             Forms\Components\Section::make('Detail Penilaian')
    //                 ->schema([
    //                     // Tambahkan field penilaian untuk setiap kriteria
    //                     // Contoh:
    //                     TextInput::make('nilai_kriteria1')
    //                         ->label('Nilai Kriteria 1')
    //                         ->numeric()
    //                         ->required()
    //                         ->minValue(1)
    //                         ->maxValue(5),

    //                     TextInput::make('nilai_kriteria2')
    //                         ->label('Nilai Kriteria 2')
    //                         ->numeric()
    //                         ->required()
    //                         ->minValue(1)
    //                         ->maxValue(5),

    //                     // Tambahkan lebih banyak kriteria sesuai kebutuhan
    //                 ])->columns(2)
    //         ]);
    // }


    // app/Filament/Resources/PenilaianResource.php


public static function form(Form $form): Form
{
    return $form
        ->schema([
            SelectTable::make('alternatif_id')
                ->label('Pilih Alternatif')
                ->relationship('alternatif', 'nama')
                ->searchable()
                ->columns([
                    TextColumn::make('kode'),
                    TextColumn::make('nama'),
                    TextColumn::make('desa.nama_desa'),
                ])
                ->required(),
        ]);
}









    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode')
                    ->label('Kode')
                    ->searchable(),


                TextColumn::make('alternatif.nama')
                    ->label('Alternatif')
                    ->searchable(),


                TextColumn::make('desa.nama_desa')
                    ->label('Desa')
                    ->searchable(),

            ])->filters([
                Tables\Filters\SelectFilter::make('desa_id')
                    ->relationship('desa', 'nama_desa')
                    ->label('Filter Desa'),

                Tables\Filters\SelectFilter::make('alternatif_id')
                    ->relationship('alternatif', 'nama')
                    ->label('Filter Alternatif'),
            ])
            ->actions([
                Action::make('penilaian')
                    // ->url(fn(Post $record): string => route('posts.edit', $record))
                    ->url(route('filament.admin.pages.penilaian')),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            // Jika perlu menambahkan relation manager
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenilaians::route('/'),
            'create' => Pages\CreatePenilaian::route('/create'),
            // 'view' => Pages\ViewPenilaian::route('/{record}'),
            'edit' => Pages\EditPenilaian::route('/{record}/edit'),
        ];
    }
}
