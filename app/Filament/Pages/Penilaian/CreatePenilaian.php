<?php

namespace App\Filament\Pages\Penilaian;

use App\Models\Alternatif;
use Filament\Pages\Page;
use App\Models\Penilaian;
use Illuminate\Support\Facades\Route;

class CreatePenilaian extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.penilaian.create-penilaian';

    protected static ?string $title = '';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
    public static function getRoutes(): array
    {
        return [
            Route::post('/store', [static::class, 'store'])->name('penilaian.store'),
        ];
    }

    public $alternatif_id;
    public $leftColumnKriterias;
    public $rightColumnKriterias;

    public $kriteria = []; // Initialize kriteria as an array

    public function mount()
    {
        $this->alternatif_id = Alternatif::findOrFail(request('alternatif_id'));
        $kriterias = \App\Models\Kriteria::with('subkriterias')->get();

        $this->leftColumnKriterias = $kriterias->filter(fn($item, $key) => $key % 2 == 0);
        $this->rightColumnKriterias = $kriterias->filter(fn($item, $key) => $key % 2 != 0);
    }

    protected function getFormSchema(): array
    {
        return [];
    }

    public function submit()
    {
        $this->validate([
            'kriteria' => 'required|array',
            'kriteria.*' => 'required|exists:sub_kriterias,id'
        ]);

        // Delete existing penilaian for this alternatif if any
        // Penilaian::where('id_alternatif', $this->alternatif_id->id)->delete();

        // Create new penilaian records
        foreach ($this->kriteria as $kriteriaId => $subkriteriaId) {
            $subkriteria = \App\Models\SubKriteria::findOrFail($subkriteriaId);

            Penilaian::create([
                'alternatif_id' => $this->alternatif_id->id,
                'kriteria_id' => $kriteriaId,
                'nilai' => $subkriteria->nilai
            ]);
        }

        return redirect()->route('filament.admin.pages.penilaian')
            ->with('success', 'Penilaian berhasil disimpan!');
    }
}
