<?php

namespace App\Filament\Resources\PenerimaBantuanResource\Pages;

use App\Filament\Resources\PenerimaBantuanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPenerimaBantuans extends ListRecords
{
    protected static string $resource = PenerimaBantuanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
