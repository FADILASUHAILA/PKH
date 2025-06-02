<?php

namespace App\Filament\Pages\Penilaian;

use App\Models\Alternatif;
use Filament\Pages\Page;
use App\Models\Penilaian;
use App\Models\Kriteria;
use App\Services\PrometheeService;
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
    public $kriteria = [];
    public $existingPenilaian = [];
    public $allKriterias;

    public function mount()
    {
        $this->alternatif_id = Alternatif::findOrFail(request('alternatif_id'));
        $this->allKriterias = Kriteria::with('subkriterias')->get();

        // Load existing penilaian if any
        $existingPenilaian = Penilaian::where('alternatif_id', $this->alternatif_id->id)->get();

        foreach ($existingPenilaian as $penilaian) {
            $this->kriteria[$penilaian->kriteria_id] = $penilaian->subkriteria_id;
            $this->existingPenilaian[$penilaian->kriteria_id] = $penilaian->id;
        }

        // Split kriterias into two columns
        $this->leftColumnKriterias = $this->allKriterias->filter(fn($item, $key) => $key % 2 == 0);
        $this->rightColumnKriterias = $this->allKriterias->filter(fn($item, $key) => $key % 2 != 0);
    }

    protected function getFormSchema(): array
    {
        return [];
    }

    public function submit()
    {
        // Validasi semua kriteria harus diisi
        $validationRules = [];
        foreach ($this->allKriterias as $kriteria) {
            $validationRules['kriteria.' . $kriteria->id] = 'required|exists:sub_kriterias,id';
        }

        $this->validate($validationRules, [
            'kriteria.*.required' => 'Semua kriteria harus diisi',
            'kriteria.*.exists' => 'Sub kriteria yang dipilih tidak valid'
        ]);

        // Proses penyimpanan data
        foreach ($this->allKriterias as $kriteria) {
            $subkriteriaId = $this->kriteria[$kriteria->id];
            $subkriteria = \App\Models\SubKriteria::findOrFail($subkriteriaId);

            if (isset($this->existingPenilaian[$kriteria->id])) {
                // Update existing penilaian
                Penilaian::where('id', $this->existingPenilaian[$kriteria->id])
                    ->update([
                        'subkriteria_id' => $subkriteriaId,
                        'nilai' => $subkriteria->nilai
                    ]);
            } else {
                // Create new penilaian
                Penilaian::create([
                    'alternatif_id' => $this->alternatif_id->id,
                    'kriteria_id' => $kriteria->id,
                    'subkriteria_id' => $subkriteriaId,
                    'nilai' => $subkriteria->nilai
                ]);

                // Panggil PrometheeService setelah semua data disimpan
                $prometheeService = new \App\Services\PrometheeService();
                $results = $prometheeService->calculate();

                // Simpan hasil perhitungan PROMETHEE
                foreach ($results['alternatif_ids'] as $alternatifId) {
                    \App\Models\HasilPenilaian::where('alternatif_id', $alternatifId)
                        ->create([
                            'alternatif_id' => $alternatifId,
                            'leaving_flow' => $results['leavingFlow'][$alternatifId],
                            'entering_flow' => $results['enteringFlow'][$alternatifId],
                            'net_flow' => $results['netFlow'][$alternatifId],
                            'ranking' => $results['ranking'][$alternatifId]
                        ]);
                }
            }
        }

        return redirect()->route('filament.admin.pages.penilaian')
            ->with('success', 'Penilaian berhasil disimpan!');
    }

    public function isComplete()
    {
        foreach ($this->allKriterias as $kriteria) {
            if (!isset($this->kriteria[$kriteria->id])) {
                return false;
            }
        }
        return true;
    }
}
