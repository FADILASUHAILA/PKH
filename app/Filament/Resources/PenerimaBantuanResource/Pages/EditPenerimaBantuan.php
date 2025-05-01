<?php

namespace App\Filament\Resources\PenerimaBantuanResource\Pages;

use App\Filament\Resources\PenerimaBantuanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPenerimaBantuan extends EditRecord
{
    protected static string $resource = PenerimaBantuanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
