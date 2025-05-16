<?php

namespace App\Filament\Pages;

use App\Models\Alternatif;
use App\Models\Penilaian as ModelsPenilaian;
use Filament\Pages\Page;

class Penilaian extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.penilaian';

    public $alternatifs;
    public $penilaians;

    public function mount()
    {
        $this->alternatifs = Alternatif::all();
    }
}
