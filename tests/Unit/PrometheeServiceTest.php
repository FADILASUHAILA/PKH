<?php

namespace Tests\Unit\Services;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Services\PrometheeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrometheeServiceTest extends TestCase
{
    use RefreshDatabase;

    private PrometheeService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PrometheeService();
    }

    // [1] TEST LOAD DATA
    public function test_load_data_successfully()
    {
        // Siapkan data test
        $alt1 = Alternatif::factory()->create(['nama' => 'Alternatif A']);
        $kriteria = Kriteria::factory()->create(['nama' => 'Kriteria 1', 'bobot' => 100]);
        Penilaian::factory()->create([
            'alternatif_id' => $alt1->id,
            'kriteria_id' => $kriteria->id,
            'nilai' => 75
        ]);

        // Eksekusi
        $this->invokePrivateMethod($this->service, 'loadData');

        // Verifikasi
        $this->assertCount(1, $this->getPrivateProperty($this->service, 'alternatifs'));
        $this->assertCount(1, $this->getPrivateProperty($this->service, 'kriterias'));
    }

    public function test_load_data_throws_exception_when_no_criteria()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Tidak ada kriteria yang terdefinisi");

        Alternatif::factory()->create();
        $this->invokePrivateMethod($this->service, 'loadData');
    }

    // [2] TEST NORMALIZE WEIGHTS
    public function test_normalize_weights_successfully()
    {
        $kriteria1 = Kriteria::factory()->create(['bobot' => 60]);
        $kriteria2 = Kriteria::factory()->create(['bobot' => 40]);

        $this->setPrivateProperty($this->service, 'kriterias', Kriteria::all());
        $this->invokePrivateMethod($this->service, 'normalizeWeights');

        $weights = $this->getPrivateProperty($this->service, 'normalizedWeights');
        $this->assertEquals(0.6, $weights[$kriteria1->id]);
        $this->assertEquals(0.4, $weights[$kriteria2->id]);
    }

    public function test_normalize_weights_throws_exception_when_total_not_100()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Total bobot kriteria harus 100%");

        Kriteria::factory()->create(['bobot' => 80]);
        $this->setPrivateProperty($this->service, 'kriterias', Kriteria::all());
        $this->invokePrivateMethod($this->service, 'normalizeWeights');
    }

    // [3] TEST BUILD DECISION MATRIX
    public function test_build_decision_matrix_correctly()
    {
        $alt = Alternatif::factory()->create();
        $kriteria = Kriteria::factory()->create();
        Penilaian::factory()->create([
            'alternatif_id' => $alt->id,
            'kriteria_id' => $kriteria->id,
            'nilai' => 85
        ]);

        $this->setPrivateProperty($this->service, 'alternatifs', Alternatif::all());
        $this->setPrivateProperty($this->service, 'kriterias', Kriteria::all());
        $this->invokePrivateMethod($this->service, 'buildDecisionMatrix');

        $matrix = $this->getPrivateProperty($this->service, 'decisionMatrix');
        $this->assertEquals(85, $matrix[$alt->id][$kriteria->id]);
    }

    // [4] TEST VALIDATE DECISION MATRIX
    public function test_validate_decision_matrix_successfully()
    {
        $alt = Alternatif::factory()->create();
        $kriteria = Kriteria::factory()->create();
        Penilaian::factory()->create([
            'alternatif_id' => $alt->id,
            'kriteria_id' => $kriteria->id,
            'nilai' => 75
        ]);

        $this->setPrivateProperty($this->service, 'alternatifs', Alternatif::all());
        $this->setPrivateProperty($this->service, 'kriterias', Kriteria::all());
        $this->invokePrivateMethod($this->service, 'buildDecisionMatrix');

        // Should not throw exception
        $this->invokePrivateMethod($this->service, 'validateDecisionMatrix');
        $this->assertTrue(true);
    }

    public function test_validate_decision_matrix_throws_exception_for_invalid_data()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Nilai tidak valid");

        $alt = Alternatif::factory()->create();
        $kriteria = Kriteria::factory()->create();
        
        // Create invalid decision matrix directly
        $this->setPrivateProperty($this->service, 'alternatifs', Alternatif::all());
        $this->setPrivateProperty($this->service, 'kriterias', Kriteria::all());
        $this->setPrivateProperty($this->service, 'decisionMatrix', [
            $alt->id => [$kriteria->id => 'invalid_value']
        ]);

        $this->invokePrivateMethod($this->service, 'validateDecisionMatrix');
    }

    // [5] TEST PREFERENCE MATRIX
    public function test_calculate_preference_matrix_for_maximization_criteria()
    {
        $alt1 = Alternatif::factory()->create();
        $alt2 = Alternatif::factory()->create();
        $kriteria = Kriteria::factory()->create(['bobot' => 100, 'kode' => 'C2']); // Maximization

        // Setup decision matrix directly
        $this->setPrivateProperty($this->service, 'alternatifs', Alternatif::all());
        $this->setPrivateProperty($this->service, 'kriterias', Kriteria::all());
        $this->setPrivateProperty($this->service, 'decisionMatrix', [
            $alt1->id => [$kriteria->id => 80],
            $alt2->id => [$kriteria->id => 70]
        ]);
        $this->setPrivateProperty($this->service, 'normalizedWeights', [$kriteria->id => 1.0]);

        $this->invokePrivateMethod($this->service, 'calculatePreferenceMatrix');

        $prefMatrix = $this->getPrivateProperty($this->service, 'preferenceMatrix');
        $this->assertEquals(0.0, $prefMatrix[$alt1->id][$alt1->id]);
        $this->assertEquals(1.0, $prefMatrix[$alt1->id][$alt2->id]);
        $this->assertEquals(0.0, $prefMatrix[$alt2->id][$alt1->id]);
    }

    public function test_calculate_preference_matrix_for_minimization_criteria()
    {
        $alt1 = Alternatif::factory()->create();
        $alt2 = Alternatif::factory()->create();
        $kriteria = Kriteria::factory()->create(['bobot' => 100, 'kode' => 'C1']); // Minimization

        // Setup decision matrix directly
        $this->setPrivateProperty($this->service, 'alternatifs', Alternatif::all());
        $this->setPrivateProperty($this->service, 'kriterias', Kriteria::all());
        $this->setPrivateProperty($this->service, 'decisionMatrix', [
            $alt1->id => [$kriteria->id => 50],
            $alt2->id => [$kriteria->id => 60]
        ]);
        $this->setPrivateProperty($this->service, 'normalizedWeights', [$kriteria->id => 1.0]);

        $this->invokePrivateMethod($this->service, 'calculatePreferenceMatrix');

        $prefMatrix = $this->getPrivateProperty($this->service, 'preferenceMatrix');
        $this->assertEquals(0.0, $prefMatrix[$alt1->id][$alt1->id]);
        $this->assertEquals(1.0, $prefMatrix[$alt1->id][$alt2->id]);
        $this->assertEquals(0.0, $prefMatrix[$alt2->id][$alt1->id]);
    }

    // [6] TEST CALCULATE FLOWS
    public function test_calculate_flows_correctly()
    {
        $alt1 = Alternatif::factory()->create();
        $alt2 = Alternatif::factory()->create();
        
        // Mock preference matrix
        $this->setPrivateProperty($this->service, 'alternatifs', Alternatif::all());
        $this->setPrivateProperty($this->service, 'preferenceMatrix', [
            $alt1->id => [$alt1->id => 0.0, $alt2->id => 0.6],
            $alt2->id => [$alt1->id => 0.4, $alt2->id => 0.0]
        ]);

        $this->invokePrivateMethod($this->service, 'calculateFlows');
        
        $leavingFlow = $this->getPrivateProperty($this->service, 'leavingFlow');
        $enteringFlow = $this->getPrivateProperty($this->service, 'enteringFlow');
        $netFlow = $this->getPrivateProperty($this->service, 'netFlow');
        
        $this->assertEquals(0.6, $leavingFlow[$alt1->id]);
        $this->assertEquals(0.4, $enteringFlow[$alt1->id]);
        $this->assertEquals(0.2, $netFlow[$alt1->id]);
    }

    // [7] TEST CALCULATE RANKING
    public function test_calculate_ranking_correctly()
    {
        $alt1 = Alternatif::factory()->create();
        $alt2 = Alternatif::factory()->create();
        
        // Mock net flow
        $this->setPrivateProperty($this->service, 'alternatifs', Alternatif::all());
        $this->setPrivateProperty($this->service, 'netFlow', [
            $alt1->id => 0.5,
            $alt2->id => -0.5
        ]);

        $this->invokePrivateMethod($this->service, 'calculateRanking');
        
        $ranking = $this->getPrivateProperty($this->service, 'ranking');
        $this->assertEquals(1, $ranking[$alt1->id]);
        $this->assertEquals(2, $ranking[$alt2->id]);
    }

    // [8] TEST INTEGRATION - FULL CALCULATION
    public function test_full_calculation_with_valid_data()
    {
        // Setup test data
        $alt1 = Alternatif::factory()->create(['nama' => 'A1']);
        $alt2 = Alternatif::factory()->create(['nama' => 'A2']);
        
        $kriteria1 = Kriteria::factory()->create(['kode' => 'C1', 'bobot' => 60]); // Minimization
        $kriteria2 = Kriteria::factory()->create(['kode' => 'C2', 'bobot' => 40]); // Maximization
        
        Penilaian::factory()->create([
            'alternatif_id' => $alt1->id,
            'kriteria_id' => $kriteria1->id,
            'nilai' => 50
        ]);
        Penilaian::factory()->create([
            'alternatif_id' => $alt1->id,
            'kriteria_id' => $kriteria2->id,
            'nilai' => 70
        ]);
        Penilaian::factory()->create([
            'alternatif_id' => $alt2->id,
            'kriteria_id' => $kriteria1->id,
            'nilai' => 60
        ]);
        Penilaian::factory()->create([
            'alternatif_id' => $alt2->id,
            'kriteria_id' => $kriteria2->id,
            'nilai' => 80
        ]);

        // Execute
        $results = $this->service->calculate();

        // Verify
        $this->assertArrayHasKey('netFlow', $results);
        $this->assertArrayHasKey('ranking', $results);
        $this->assertCount(2, $results['ranking']);
    }

    // Helper Methods
    private function invokePrivateMethod(object $object, string $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $parameters);
    }

    private function getPrivateProperty(object $object, string $propertyName)
    {
        $reflection = new \ReflectionClass(get_class($object));
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);
        return $property->getValue($object);
    }

    private function setPrivateProperty(object $object, string $propertyName, $value): void
    {
        $reflection = new \ReflectionClass(get_class($object));
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }
}