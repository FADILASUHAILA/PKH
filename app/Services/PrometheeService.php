<?php

namespace App\Services;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;

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
        $this->loadData();
        if ($this->alternatifs->count() < 2) {
            session()->flash('error', 'PROMETHEE membutuhkan minimal 2 alternatif yang dinilai!');
            return null; // Menghentikan 
        }
        $this->normalizeWeights();
        $this->buildDecisionMatrix();
        $this->calculatePreferenceMatrix();
        $this->calculateFlows();
        $this->calculateRanking();

        return $this->getAllResults();
    }

    private function loadData()
    {
        $this->alternatifs = Alternatif::has('penilaian')->with('penilaian')->get();
        $this->kriterias = Kriteria::all();
    }

    private function normalizeWeights()
    {
        $totalBobot = $this->kriterias->sum('bobot');
        $this->normalizedWeights = $this->kriterias->mapWithKeys(function ($kriteria) use ($totalBobot) {
            return [$kriteria->id => $kriteria->bobot / $totalBobot];
        });
    }

    private function buildDecisionMatrix()
    {
        $this->decisionMatrix = [];
        foreach ($this->alternatifs as $alt) {
            foreach ($this->kriterias as $kriteria) {
                $penilaian = $alt->penilaian->firstWhere('kriteria_id', $kriteria->id);
                $this->decisionMatrix[$alt->id][$kriteria->id] = $penilaian ? $penilaian->nilai : 0;
            }
        }
    }

    private function calculatePreferenceMatrix()
    {
        $this->preferenceMatrix = [];
        foreach ($this->alternatifs as $a) {
            foreach ($this->alternatifs as $b) {
                if ($a->id === $b->id) {
                    $this->preferenceMatrix[$a->id][$b->id] = 0;
                    continue;
                }

                //cari nilai preferensi nya

                $totalPreference = 0;
                foreach ($this->kriterias as $kriteria) { //Loop ganda untuk membandingkan alternatif (A vs B)
                    $nilaiA = $this->decisionMatrix[$a->id][$kriteria->id] ?? 0;
                    $nilaiB = $this->decisionMatrix[$b->id][$kriteria->id] ?? 0;

                    // Preferensi usual
                    if ($kriteria->jenis === 'benefit') {
                        $pref = ($nilaiA > $nilaiB) ? 1 : 0;
                    } else { // cost
                        $pref = ($nilaiA < $nilaiB) ? 1 : 0;
                    }

                    $totalPreference += $pref * $this->normalizedWeights[$kriteria->id];
                }

                $this->preferenceMatrix[$a->id][$b->id] = $totalPreference;
            }
        }
    }

    private function calculateFlows()
    {
        $n = count($this->alternatifs);

        // Leaving Flow (Φ+)
        $this->leavingFlow = [];
        foreach ($this->alternatifs as $a) {
            $sum = array_sum($this->preferenceMatrix[$a->id]);
            $this->leavingFlow[$a->id] = $sum / ($n - 1);
        }

        // Entering Flow (Φ-)
        $this->enteringFlow = [];
        foreach ($this->alternatifs as $a) {
            $sum = 0;
            foreach ($this->alternatifs as $b) {
                if ($a->id !== $b->id) {
                    $sum += $this->preferenceMatrix[$b->id][$a->id];
                }
            }
            $this->enteringFlow[$a->id] = $sum / ($n - 1);
        }

        // Net Flow (Φ)
        $this->netFlow = [];
        foreach ($this->alternatifs as $a) {
            $this->netFlow[$a->id] = $this->leavingFlow[$a->id] - $this->enteringFlow[$a->id];
        }
    }

    private function calculateRanking()
    {
        $validNetFlows = array_filter($this->netFlow, fn($value) => !is_null($value));
        arsort($validNetFlows);

        $this->ranking = [];
        $rank = 1;
        foreach ($validNetFlows as $altId => $value) {
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
