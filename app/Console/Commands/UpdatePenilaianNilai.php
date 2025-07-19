<?php

namespace App\Console\Commands;

use App\Models\Penilaian;
use App\Models\SubKriteria;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdatePenilaianNilai extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'penilaian:update-nilai 
                            {--force : Force update even if nilai already exists}
                            {--alternatif= : Update specific alternatif ID only}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update nilai penilaian berdasarkan subkriteria yang dipilih';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai update nilai penilaian...');

        $query = Penilaian::with(['subkriteria']);

        // Filter berdasarkan alternatif jika ditentukan
        if ($this->option('alternatif')) {
            $query->where('alternatif_id', $this->option('alternatif'));
        }

        // Filter hanya yang belum memiliki nilai, kecuali jika force
        if (!$this->option('force')) {
            $query->whereNull('nilai');
        }

        $penilaians = $query->get();

        if ($penilaians->isEmpty()) {
            $this->warn('Tidak ada data penilaian yang perlu diupdate.');
            return 0;
        }

        $this->info("Ditemukan {$penilaians->count()} data penilaian yang akan diupdate.");

        $bar = $this->output->createProgressBar($penilaians->count());
        $bar->start();

        $updated = 0;
        $errors = 0;

        foreach ($penilaians as $penilaian) {
            try {
                DB::beginTransaction();

                if ($penilaian->subkriteria) {
                    $penilaian->update([
                        'nilai' => $penilaian->subkriteria->nilai
                    ]);

                    $updated++;
                    
                    Log::info('Nilai penilaian berhasil diupdate', [
                        'penilaian_id' => $penilaian->id,
                        'alternatif_id' => $penilaian->alternatif_id,
                        'kriteria_id' => $penilaian->kriteria_id,
                        'subkriteria_id' => $penilaian->subkriteria_id,
                        'nilai_baru' => $penilaian->subkriteria->nilai
                    ]);
                } else {
                    $this->warn("Penilaian ID {$penilaian->id} tidak memiliki subkriteria yang valid");
                    $errors++;
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("Error updating penilaian ID {$penilaian->id}: " . $e->getMessage());
                $errors++;
                
                Log::error('Gagal update nilai penilaian', [
                    'penilaian_id' => $penilaian->id,
                    'error' => $e->getMessage()
                ]);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        $this->info("Update selesai!");
        $this->info("Berhasil diupdate: {$updated}");
        
        if ($errors > 0) {
            $this->warn("Error: {$errors}");
        }

        return 0;
    }
}