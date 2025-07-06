<?php

namespace Tests\Unit\Services;

use App\Models\Kriteria;
use App\Services\PrometheeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NormalizeWeightsTest extends TestCase
{
    use RefreshDatabase;

    private PrometheeService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PrometheeService();
    }

    /** @test */
    public function it_normalizes_weights_correctly()
    {
        // Setup kriteria dengan bobot total 100%
        Kriteria::factory()->create(['bobot' => 60]); 
        Kriteria::factory()->create(['bobot' => 40]);
        
        // Load data ke service
        $reflector = new \ReflectionClass($this->service);
        $method = $reflector->getMethod('loadData');
        $method->setAccessible(true);
        $method->invoke($this->service);
        
        // Panggil fungsi normalizeWeights
        $normalizeMethod = $reflector->getMethod('normalizeWeights');
        $normalizeMethod->setAccessible(true);
        $normalizeMethod->invoke($this->service);
        
        // Ambil normalizedWeights property
        $property = $reflector->getProperty('normalizedWeights');
        $property->setAccessible(true);
        $normalizedWeights = $property->getValue($this->service);
        
        // Verifikasi hasil normalisasi
        $this->assertEquals(0.6, $normalizedWeights->first());
        $this->assertEquals(0.4, $normalizedWeights->last());
        $this->assertEquals(1.0, array_sum($normalizedWeights->toArray()));
    }

    /** @test */
    public function it_throws_exception_when_total_weight_not_100()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Total bobot kriteria harus 100%");
        
        // Setup kriteria dengan bobot total 80%
        Kriteria::factory()->create(['bobot' => 50]);
        Kriteria::factory()->create(['bobot' => 30]);
        
        // Load data ke service
        $reflector = new \ReflectionClass($this->service);
        $method = $reflector->getMethod('loadData');
        $method->setAccessible(true);
        $method->invoke($this->service);
        
        // Panggil fungsi normalizeWeights yang seharusnya throw exception
        $normalizeMethod = $reflector->getMethod('normalizeWeights');
        $normalizeMethod->setAccessible(true);
        $normalizeMethod->invoke($this->service);
    }
}