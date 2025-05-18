<?php

namespace App\Filament\Pages;

use App\Models\Alternatif;
use App\Models\Penilaian as ModelsPenilaian;
use Filament\Pages\Page;
use Illuminate\Contracts\Database\Eloquent\Builder;

class Penilaian extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static string $view = 'filament.pages.penilaian';

    protected static ?string $navigationGroup = 'Perhitungan';

    public $alternatifs;
    public $penilaians;

    public function mount()
    {
        $this->alternatifs = Alternatif::all();
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->with('penilaian');
    }
}
