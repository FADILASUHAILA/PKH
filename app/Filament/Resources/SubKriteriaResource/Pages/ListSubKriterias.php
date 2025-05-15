<?php

namespace App\Filament\Resources\SubKriteriaResource\Pages;

use App\Models\Kriteria;
use App\Filament\Resources\SubKriteriaResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class ListSubKriterias extends Page
{
    protected static string $resource = SubKriteriaResource::class;

    protected static string $view = 'filament.resources.sub-kriteria-resource.pages.custom-sub-kriteria';

    public $kriterias;

    public function mount()
    {
        $this->kriterias = Kriteria::with('subKriterias')->get();
    }

    public function getTitle(): string
    {
        return 'Data Sub-Kriteria';
    }

    public function getKriterias()
    {
        return Kriteria::with('subKriterias')->get(); // Pastikan relasi sudah didefinisikan
    }
}
