<?php

namespace App\Filament\Resources\KriteriaResource\Pages;

use App\Filament\Resources\KriteriaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateKriteria extends CreateRecord
{
    protected static string $resource = KriteriaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
