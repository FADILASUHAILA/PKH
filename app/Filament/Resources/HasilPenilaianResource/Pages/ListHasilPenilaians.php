<?php

namespace App\Filament\Resources\HasilPenilaianResource\Pages;

use App\Filament\Resources\HasilPenilaianResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHasilPenilaians extends ListRecords
{
    protected static string $resource = HasilPenilaianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
