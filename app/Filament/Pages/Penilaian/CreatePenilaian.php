<?php

namespace App\Filament\Pages\Penilaian;

use App\Models\Alternatif;
use Filament\Pages\Page;
use App\Models\Penilaian;
use App\Models\Kriteria;
use App\Services\PrometheeService;
use Illuminate\Support\Facades\Route;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class CreatePenilaian extends Page
{
    use HasPageShield;
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
            Route::get('/{alternatif_id}/{penilaian_id?}', [static::class, 'mount'])
                ->name('create'),
            Route::post('/store', [static::class, 'store'])
                ->name('penilaian.store'),
        ];
    }

    public $alternatif_id;
    public $leftColumnKriterias;
    public $rightColumnKriterias;
    public $kriteria = [];
    public $existingPenilaian = [];
    public $allKriterias;
    public $penilaian_id;

    public function mount()
    {
        $this->alternatif_id = Alternatif::findOrFail(request('alternatif_id'));
        $this->penilaian_id = request('penilaian_id');
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

        // Buat header penilaian
        $header = \App\Models\PenilaianHeader::updateOrCreate(
            [
                'alternatif_id' => $this->alternatif_id->id,
                // Hanya gunakan alternatif_id sebagai kondisi unik
                // Jika ingin satu alternatif hanya punya satu header
            ],
            [
                'tanggal_penilaian' => now(),
                'catatan' => null,
                'updated_at' => now()
            ]
        );

        // Simpan detail penilaian
        foreach ($this->allKriterias as $kriteria) {
            $subkriteriaId = $this->kriteria[$kriteria->id];
            $subkriteria = \App\Models\SubKriteria::findOrFail($subkriteriaId);

            \App\Models\Penilaian::updateOrCreate(
                [
                    'alternatif_id' => $this->alternatif_id->id,
                    // 'header_id' => $header->id,
                    'kriteria_id' => $kriteria->id
                ],
                [
                    'subkriteria_id' => $subkriteriaId,
                    'nilai' => $subkriteria->nilai,
                    'deleted_at' => null,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }

        // Panggil PrometheeService setelah semua data disimpan
        $prometheeService = new \App\Services\PrometheeService();
        $results = $prometheeService->calculate();

        if (is_null($results)) {
            // Tangani kasus di mana hasilnya null
            return redirect()->route('filament.admin.pages.penilaian')
                ->with('error', 'PROMETHEE membutuhkan minimal 2 Calon Penerima yang dinilai!');
        }

        // Simpan hasil perhitungan PROMETHEE hanya untuk alternatif yang sudah dinilai
        foreach ($results['alternatif_ids'] as $index => $alternatifId) {
            // Cek apakah alternatif sudah memiliki penilaian lengkap
            $jumlahKriteria = \App\Models\Kriteria::count();
            $jumlahPenilaian = \App\Models\Penilaian::where('alternatif_id', $alternatifId)
                // ->where('header_id', $header->id)
                ->count();

            if ($jumlahPenilaian === $jumlahKriteria) {
                \App\Models\HasilPenilaian::updateOrCreate(
                    [
                        'alternatif_id' => $alternatifId
                    ],
                    [
                        'header_id' => $header->id,
                        'decision_matrix' => $results['decisionMatrix'][$alternatifId] ?? null,
                        'preference_matrix' => $this->transformPreferenceMatrix($results['preferenceMatrix'], $alternatifId),
                        'leaving_flow' => $results['leavingFlow'][$alternatifId] ?? 0,
                        'entering_flow' => $results['enteringFlow'][$alternatifId] ?? 0,
                        'net_flow' => $results['netFlow'][$alternatifId] ?? 0,
                        'ranking' => $results['ranking'][$alternatifId] ?? 0,
                        'updated_at' => now()
                    ]
                );
            }
        }

        return redirect()->route('filament.admin.pages.penilaian')
            ->with('success', 'Penilaian berhasil disimpan!');
    }

    /**
     * Helper method to transform preference matrix for specific alternatif
     */
    private function transformPreferenceMatrix(array $preferenceMatrix, int $alternatifId): array
    {
        $result = [];
        foreach ($preferenceMatrix as $rowKey => $row) {
            if ($rowKey == $alternatifId) {
                $result = $row;
                break;
            }
        }
        return $result;
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
