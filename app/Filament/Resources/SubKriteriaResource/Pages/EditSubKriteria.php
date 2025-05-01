<?php

namespace App\Filament\Resources\SubKriteriaResource\Pages;

use App\Filament\Resources\SubKriteriaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubKriteria extends EditRecord
{
    protected static string $resource = SubKriteriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
