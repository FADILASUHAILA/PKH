<?php

namespace App\Services;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use Illuminate\Support\Facades\Log;
class PrometheeService
{
    private $alternatifs;
    private $kriterias;
    private $normalizedWeights;
    private $decisionMatrix;
    private $preferenceMatrix;
    private $leavingFlow;
    private $enteringFlow;
    private $netFlow;
    private $ranking;

    public function calculate()
    {
        try {
            $this->loadData();
            
            if ($this->alternatifs->count() < 2) {
                throw new \Exception("PROMETHEE membutuhkan minimal 2 alternatif");
            }

            $this->normalizeWeights();
            $this->buildDecisionMatrix();
            $this->validateDecisionMatrix();
            $this->calculatePreferenceMatrix();
            $this->calculateFlows();
            $this->calculateRanking();

            return $this->getAllResults();

        } catch (\Exception $e) {
            Log::error('Error dalam PROMETHEE: ' . $e->getMessage());
            throw $e;
        }
    }

    private function loadData()
    {
        $this->alternatifs = Alternatif::has('penilaian')
            ->with(['penilaian' => function($q) {
                $q->orderBy('kriteria_id');
            }])
            ->orderBy('id')
            ->get();

        $this->kriterias = Kriteria::orderBy('id')->get();

        if ($this->kriterias->isEmpty()) {
            throw new \Exception("Tidak ada kriteria yang terdefinisi");
        }
    }

    private function normalizeWeights()
    {
        $totalBobot = $this->kriterias->sum('bobot');
        
        if (abs($totalBobot - 100) > 0.0001) {
            throw new \Exception("Total bobot kriteria harus 100% (Saat ini: {$totalBobot})");
        }

        $this->normalizedWeights = $this->kriterias->mapWithKeys(function ($kriteria) {
            return [$kriteria->id => $kriteria->bobot / 100];
        });
    }

    private function buildDecisionMatrix()
    {
        $this->decisionMatrix = [];
        
        foreach ($this->alternatifs as $alt) {
            foreach ($this->kriterias as $kriteria) {
                $penilaian = $alt->penilaian->firstWhere('kriteria_id', $kriteria->id);
                $this->decisionMatrix[$alt->id][$kriteria->id] = $penilaian ? (float)$penilaian->nilai : 0.0;
            }
        }
    }

    private function validateDecisionMatrix()
    {
        foreach ($this->alternatifs as $alt) {
            foreach ($this->kriterias as $kriteria) {
                $nilai = $this->decisionMatrix[$alt->id][$kriteria->id];
                
                if (!is_numeric($nilai)) {
                    throw new \Exception("Nilai tidak valid untuk {$alt->nama} pada kriteria {$kriteria->nama}");
                }
            }
        }
    }

    private function calculatePreferenceMatrix()
    {
        $this->preferenceMatrix = [];
        
        // Define which criteria are minimization (C1, C9, C10, C11)
        $minCriteria = ['C1', 'C9', 'C10', 'C11'];
        
        foreach ($this->alternatifs as $a) {
            foreach ($this->alternatifs as $b) {
                if ($a->id === $b->id) {
                    $this->preferenceMatrix[$a->id][$b->id] = 0.0;
                    continue;
                }

                $totalPreference = 0.0;
                
                foreach ($this->kriterias as $kriteria) {
                    $nilaiA = $this->decisionMatrix[$a->id][$kriteria->id] ?? 0.0;
                    $nilaiB = $this->decisionMatrix[$b->id][$kriteria->id] ?? 0.0;

                    // Determine preference based on criteria type
                    if (in_array($kriteria->kode, $minCriteria)) {
                        // For minimization criteria, lower is better
                        $pref = ($nilaiA < $nilaiB) ? 1.0 : 0.0;
                    } else {
                        // For maximization criteria, higher is better
                        $pref = ($nilaiA > $nilaiB) ? 1.0 : 0.0;
                    }

                    $totalPreference += $pref * $this->normalizedWeights[$kriteria->id];
                }

                $this->preferenceMatrix[$a->id][$b->id] = round($totalPreference, 4);
            }
        }
    }

    private function calculateFlows()
    {
        $n = count($this->alternatifs);
        $this->leavingFlow = [];
        $this->enteringFlow = [];
        $this->netFlow = [];

        foreach ($this->alternatifs as $a) {
            $sumLeaving = 0.0;
            $sumEntering = 0.0;

            foreach ($this->alternatifs as $b) {
                if ($a->id !== $b->id) {
                    $sumLeaving += $this->preferenceMatrix[$a->id][$b->id];
                    $sumEntering += $this->preferenceMatrix[$b->id][$a->id];
                }
            }

            $this->leavingFlow[$a->id] = round($sumLeaving / ($n - 1), 4);
            $this->enteringFlow[$a->id] = round($sumEntering / ($n - 1), 4);
            $this->netFlow[$a->id] = round($this->leavingFlow[$a->id] - $this->enteringFlow[$a->id], 4);
        }
    }

    private function calculateRanking()
    {
        $this->ranking = [];
        $sortedFlows = $this->netFlow;
        arsort($sortedFlows);
        
        $rank = 1;
        foreach ($sortedFlows as $altId => $value) {
            $this->ranking[$altId] = $rank++;
        }
    }

    public function getAllResults()
    {
        return [
            'decisionMatrix' => $this->decisionMatrix,
            'preferenceMatrix' => $this->preferenceMatrix,
            'leavingFlow' => $this->leavingFlow,
            'enteringFlow' => $this->enteringFlow,
            'netFlow' => $this->netFlow,
            'ranking' => $this->ranking,
            'alternatif_ids' => $this->alternatifs->pluck('id')->toArray(),
            'kriteria_ids' => $this->kriterias->pluck('id')->toArray()
        ];
    }
}