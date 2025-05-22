<?php

namespace App\Filament\Resources\HasilPenilaianResource\Pages;

use App\Filament\Resources\HasilPenilaianResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHasilPenilaian extends EditRecord
{
    protected static string $resource = HasilPenilaianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
