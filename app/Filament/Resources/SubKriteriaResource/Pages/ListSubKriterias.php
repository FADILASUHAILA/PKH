<?php

namespace App\Filament\Resources\SubKriteriaResource\Pages;

use App\Filament\Resources\SubKriteriaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubKriterias extends ListRecords
{
    protected static string $resource = SubKriteriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
