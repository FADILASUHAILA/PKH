<?php

namespace App\Services;

use App\Models\Alternatif;
use App\Models\BioData;
use App\Models\Desa;
use App\Models\Penilaian;
use App\Models\Kriteria;
use App\Models\SubKriteria;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Exception;
use Illuminate\Support\Str;

class ExcelImportService
{
    /**
     * Import data from Excel file
     * 
     * Expected Excel format:
     * Column A: Kode Alternatif
     * Column B: Nama Alternatif  
     * Column C: Nama Desa
     * Column D: NIK
     * Column E: Alamat
     * Column F: No HP
     * Column G: Penghasilan
     * Column H: Pekerjaan
     * Column I: Jumlah Tanggungan
     * Column J: Jumlah Anak Sekolah
     * Column K: Ibu Hamil
     * Column L: Balita
     * Column M: Anggota Disabilitas
     * Column N: Lansia
     * Column O: Luas Lantai
     * Column P: Jenis Lantai
     * Column Q: Jenis Dinding
     */
    public function importFromExcel($filePath)
    {
        try {
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Skip header row
            $dataRows = array_slice($rows, 1);
            
            $importedCount = 0;
            $errors = [];

            foreach ($dataRows as $index => $row) {
                $rowNumber = $index + 2; // +2 because we skipped header and array is 0-indexed
                
                // Use individual transaction for each row to prevent rollback cascade
                DB::beginTransaction();
                
                try {
                    // Skip empty rows
                    if (empty(array_filter($row))) {
                        DB::rollBack();
                        continue;
                    }

                    // Validate required fields
                    if (empty($row[0]) || empty($row[1]) || empty($row[2])) {
                        $errors[] = "Baris {$rowNumber}: Kode, Nama, dan Desa harus diisi";
                        DB::rollBack();
                        continue;
                    }

                    // $kode = trim($row[0]);
                    // $nama = trim($row[1]);
                    // $namaDesa = trim($row[2]);
                    // $nik = isset($row[3]) ? $this->formatNik(trim($row[3])) : null;
                    // $alamat = isset($row[4]) ? trim($row[4]) : null;
                    // $noHp = isset($row[5]) ? $this->formatPhoneNumber(trim($row[5])) : null;

                    $kode = 'ALT-' . Str::random(8);
                    $alamat = null;
                    $nik = isset($row[0]) ? $this->formatNik(trim($row[0])) : null;
                    $nama = trim($row[1]);
                    $noHp = isset($row[2]) ? $this->formatPhoneNumber(trim($row[2])) : null;
                    $namaDesa = trim($row[3]);

                    // Find or create Desa
                    $desa = Desa::firstOrCreate(
                        ['nama_desa' => $namaDesa],
                        ['nama_desa' => $namaDesa]
                    );

                    // Check if alternatif already exists
                    $existingAlternatif = Alternatif::where('kode', $kode)->first();
                    if ($existingAlternatif) {
                        $errors[] = "Baris {$rowNumber}: Alternatif dengan kode '{$kode}' sudah ada";
                        DB::rollBack();
                        continue;
                    }

                    // Check if NIK already exists (if NIK is provided)
                    if ($nik) {
                        $existingBioData = BioData::where('nik', $nik)->first();
                        if ($existingBioData) {
                            $errors[] = "Baris {$rowNumber}: NIK '{$nik}' sudah digunakan";
                            DB::rollBack();
                            continue;
                        }
                    }

                    // Create Alternatif
                    $alternatif = Alternatif::create([
                        'kode' => $kode,
                        'nama' => $nama,
                        'desa_id' => $desa->id,
                    ]);

                    // Create BioData if provided
                    if ($nik || $alamat || $noHp) {
                        BioData::create([
                            'nik' => $nik,
                            'alamat' => $alamat,
                            'no_hp' => $noHp,
                            'alternatif_id' => $alternatif->id,
                        ]);
                    }

                    // Process Penilaian data using Indikasi model structure
                    $this->processPenilaianDataFromIndikasi($alternatif, $row, $rowNumber, $errors);

                    DB::commit();
                    $importedCount++;

                } catch (Exception $e) {
                    DB::rollBack();
                    $errors[] = "Baris {$rowNumber}: " . $e->getMessage();
                    Log::error("Error importing row {$rowNumber}: " . $e->getMessage());
                }
            }

            return [
                'success' => true,
                'imported_count' => $importedCount,
                'errors' => $errors,
                'message' => "Berhasil mengimpor {$importedCount} data" . 
                           (count($errors) > 0 ? " dengan " . count($errors) . " error" : "")
            ];

        } catch (Exception $e) {
            Log::error("Excel import failed: " . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Gagal mengimpor data: ' . $e->getMessage(),
                'errors' => [$e->getMessage()]
            ];
        }
    }

    /**
     * Format NIK from scientific notation to proper number
     */
    private function formatNik($nik)
    {
        if (empty($nik)) {
            return null;
        }

        // Handle scientific notation (e.g., 3.20123E+15)
        if (strpos($nik, 'E') !== false || strpos($nik, 'e') !== false) {
            $nik = sprintf('%.0f', floatval($nik));
        }

        // Remove any non-numeric characters except for the decimal point
        $nik = preg_replace('/[^0-9]/', '', $nik);

        // Ensure NIK is 16 digits
        if (strlen($nik) < 16) {
            $nik = str_pad($nik, 16, '0', STR_PAD_LEFT);
        } elseif (strlen($nik) > 16) {
            $nik = substr($nik, 0, 16);
        }

        return $nik;
    }

    /**
     * Format phone number
     */
    private function formatPhoneNumber($phone)
    {
        if (empty($phone)) {
            return null;
        }

        // Handle scientific notation
        if (strpos($phone, 'E') !== false || strpos($phone, 'e') !== false) {
            $phone = sprintf('%.0f', floatval($phone));
        }

        // Remove any non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        return $phone;
    }

    /**
     * Process penilaian data from Excel row using Indikasi model structure
     */
    private function processPenilaianDataFromIndikasi($alternatif, $row, $rowNumber, &$errors)
    {
        try {
            // Extract data from row based on Indikasi model structure
            $penghasilan = isset($row[4]) ? floatval($row[4]) : 0;
            $pekerjaan = isset($row[5]) ? trim($row[5]) : '';
            $jumlahTanggungan = isset($row[6]) ? intval($row[6]) : 0;
            $jumlahAnakSekolah = isset($row[7]) ? intval($row[7]) : 0;
            $ibuHamil = isset($row[8]) ? trim($row[8]) : 'Tidak ada';
            $balita = isset($row[9]) ? trim($row[9]) : 'Tidak ada';
            $anggotaDisabilitas = isset($row[10]) ? trim($row[10]) : 'Tidak ada';
            $lansia = isset($row[11]) ? trim($row[11]) : 'Tidak ada';
            $luasLantai = isset($row[12]) ? trim($row[12]) : '';
            $jenisLantai = isset($row[13]) ? trim($row[13]) : '';
            $jenisDinding = isset($row[14]) ? trim($row[14]) : '';

            // Process each kriteria based on Indikasi model logic
            $this->prosesPenghasilan($alternatif, $penghasilan, $rowNumber, $errors);
            $this->prosesPekerjaan($alternatif, $pekerjaan, $rowNumber, $errors);
            $this->prosesJumlahTanggungan($alternatif, $jumlahTanggungan, $rowNumber, $errors);
            $this->prosesJumlahAnakSekolah($alternatif, $jumlahAnakSekolah, $rowNumber, $errors);
            $this->prosesIbuHamil($alternatif, $ibuHamil, $rowNumber, $errors);
            $this->prosesBalita($alternatif, $balita, $rowNumber, $errors);
            $this->prosesAnggotaDisabilitas($alternatif, $anggotaDisabilitas, $rowNumber, $errors);
            $this->prosesLansia($alternatif, $lansia, $rowNumber, $errors);
            $this->prosesLuasLantai($alternatif, $luasLantai, $rowNumber, $errors);
            $this->prosesJenisLantai($alternatif, $jenisLantai, $rowNumber, $errors);
            $this->prosesJenisDinding($alternatif, $jenisDinding, $rowNumber, $errors);

        } catch (Exception $e) {
            $errors[] = "Baris {$rowNumber}: Error memproses data indikasi: " . $e->getMessage();
            Log::error("Error processing indikasi data for row {$rowNumber}: " . $e->getMessage());
        }
    }

    // Methods based on Indikasi model logic
    protected function prosesPenghasilan($alternatif, $penghasilan, $rowNumber, &$errors)
    {
        $subkriteria = match (true) {
            $penghasilan <= 600000 => '≤ Rp 600.000/bulan',
            $penghasilan <= 1000000 => 'Rp 600.000 – Rp 1.000.000/bulan',
            default => '> Rp 1.000.000/bulan'
        };

        $this->simpanPenilaian($alternatif, 1, $subkriteria, $rowNumber, $errors);
    }

    protected function prosesPekerjaan($alternatif, $pekerjaan, $rowNumber, &$errors)
    {
        $pekerjaan = trim($pekerjaan);

        $subkriteria = match ($pekerjaan) {
            'Tidak bekerja' => 'Tidak bekerja',
            'Petani', 'Kuli', 'Pedagang' => 'Pekerja harian lepas',
            'Karyawan' => 'Pekerja tetap',
            default => null
        };

        if ($subkriteria) {
            $this->simpanPenilaian($alternatif, 2, $subkriteria, $rowNumber, $errors);
        } else {
            $errors[] = "Baris {$rowNumber}: Pekerjaan '{$pekerjaan}' tidak valid";
        }
    }

    protected function prosesJumlahTanggungan($alternatif, $jumlahTanggungan, $rowNumber, &$errors)
    {
        $subkriteria = match (true) {
            $jumlahTanggungan >= 5 => '≥5 orang',
            $jumlahTanggungan >= 3 => '3-4 orang',
            default => '≤2 orang'
        };

        $this->simpanPenilaian($alternatif, 3, $subkriteria, $rowNumber, $errors);
    }

    protected function prosesJumlahAnakSekolah($alternatif, $jumlahAnakSekolah, $rowNumber, &$errors)
    {
        $subkriteria = match (true) {
            $jumlahAnakSekolah >= 3 => '≥3 anak',
            $jumlahAnakSekolah == 2 => '2 anak',
            $jumlahAnakSekolah == 1 => '1 anak',
            default => 'Tidak ada anak sekolah'
        };

        $this->simpanPenilaian($alternatif, 4, $subkriteria, $rowNumber, $errors);
    }

    protected function prosesIbuHamil($alternatif, $ibuHamil, $rowNumber, &$errors)
    {
        $subkriteria = match ($ibuHamil) {
            'Ada' => 'Ada',
            default => 'Tidak ada'
        };

        $this->simpanPenilaian($alternatif, 5, $subkriteria, $rowNumber, $errors);
    }

    protected function prosesBalita($alternatif, $balita, $rowNumber, &$errors)
    {
        $subkriteria = match ($balita) {
            'Ada' => 'Ada',
            default => 'Tidak ada'
        };

        $this->simpanPenilaian($alternatif, 6, $subkriteria, $rowNumber, $errors);
    }

    protected function prosesAnggotaDisabilitas($alternatif, $anggotaDisabilitas, $rowNumber, &$errors)
    {
        $subkriteria = match ($anggotaDisabilitas) {
            'Ada' => 'Ada',
            default => 'Tidak ada'
        };

        $this->simpanPenilaian($alternatif, 7, $subkriteria, $rowNumber, $errors);
    }

    protected function prosesLansia($alternatif, $lansia, $rowNumber, &$errors)
    {
        $subkriteria = match ($lansia) {
            'Ada' => 'Ada',
            default => 'Tidak ada'
        };

        $this->simpanPenilaian($alternatif, 8, $subkriteria, $rowNumber, $errors);
    }

    protected function prosesLuasLantai($alternatif, $luasLantai, $rowNumber, &$errors)
    {
        $subkriteria = match ($luasLantai) {
            '<8 m² per orang' => '<8 m² per orang',
            '8-15 m² per orang' => '8-15 m² per orang',
            '>15 m² per orang' => '>15 m² per orang',
            default => null
        };

        if ($subkriteria) {
            $this->simpanPenilaian($alternatif, 9, $subkriteria, $rowNumber, $errors);
        } else {
            $errors[] = "Baris {$rowNumber}: Luas Lantai '{$luasLantai}' tidak valid";
        }
    }

    protected function prosesJenisLantai($alternatif, $jenisLantai, $rowNumber, &$errors)
    {
        $jenisLantai = strtolower($jenisLantai);

        $subkriteria = match ($jenisLantai) {
            'tanah' => 'Tanah',
            'bambu' => 'Bambu',
            'semen' => 'Semen',
            'keramik' => 'Keramik',
            default => null
        };

        if ($subkriteria) {
            $this->simpanPenilaian($alternatif, 10, $subkriteria, $rowNumber, $errors);
        } else {
            $errors[] = "Baris {$rowNumber}: Jenis lantai '{$jenisLantai}' tidak valid";
        }
    }

    protected function prosesJenisDinding($alternatif, $jenisDinding, $rowNumber, &$errors)
    {
        $jenis = strtolower($jenisDinding);

        if (str_contains($jenis, 'bambu') || str_contains($jenis, 'rumbia') || str_contains($jenis, 'kayu rendah')) {
            $this->simpanPenilaian($alternatif, 11, 'Bambu/rumbia/kayu rendah', $rowNumber, $errors);
        } elseif (str_contains($jenis, 'tembok') || str_contains($jenis, 'semen')) {
            $this->simpanPenilaian($alternatif, 11, 'Tembok/Semen', $rowNumber, $errors);
        } else {
            $errors[] = "Baris {$rowNumber}: Jenis dinding '{$jenisDinding}' tidak valid";
        }
    }

    protected function simpanPenilaian($alternatif, $kriteriaId, $namaSubkriteria, $rowNumber, &$errors)
    {
        $subkriteria = SubKriteria::where('kriteria_id', $kriteriaId)
            ->whereRaw('LOWER(nama_sub_kriteria) = ?', [strtolower($namaSubkriteria)])
            ->first();

        if (!$subkriteria) {
            $errors[] = "Baris {$rowNumber}: Subkriteria '{$namaSubkriteria}' tidak ditemukan untuk kriteria ID {$kriteriaId}";
            Log::error('Subkriteria tidak ditemukan', [
                'kriteria_id' => $kriteriaId,
                'nama_sub_kriteria' => $namaSubkriteria,
                'alternatif_id' => $alternatif->id
            ]);
            return;
        }

        try {
            // Update atau create dengan kondisi yang lebih spesifik
            $penilaian = Penilaian::updateOrCreate(
                [
                    'alternatif_id' => $alternatif->id,
                    'kriteria_id' => $kriteriaId
                ],
                [
                    'subkriteria_id' => $subkriteria->id,
                    'nilai' => $subkriteria->nilai,
                ]
            );

            Log::info('Penilaian berhasil diproses', $penilaian->toArray());
        } catch (\Exception $e) {
            $errors[] = "Baris {$rowNumber}: Gagal menyimpan penilaian untuk kriteria ID {$kriteriaId}: " . $e->getMessage();
            Log::error('Gagal menyimpan penilaian', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Generate Excel template for import
     */
    // public function generateTemplate()
    // {
    //     $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    //     $sheet = $spreadsheet->getActiveSheet();

    //     // Set headers based on new structure
    //     $headers = [
    //         'A1' => 'Kode Alternatif',
    //         'B1' => 'Nama Alternatif',
    //         'C1' => 'Nama Desa',
    //         'D1' => 'NIK',
    //         'E1' => 'Alamat',
    //         'F1' => 'No HP',
    //         'G1' => 'Penghasilan',
    //         'H1' => 'Pekerjaan',
    //         'I1' => 'Jumlah Tanggungan',
    //         'J1' => 'Jumlah Anak Sekolah',
    //         'K1' => 'Ibu Hamil',
    //         'L1' => 'Balita',
    //         'M1' => 'Anggota Disabilitas',
    //         'N1' => 'Lansia',
    //         'O1' => 'Luas Lantai',
    //         'P1' => 'Jenis Lantai',
    //         'Q1' => 'Jenis Dinding'
    //     ];

    //     // Set header values
    //     foreach ($headers as $cell => $value) {
    //         $sheet->setCellValue($cell, $value);
    //     }

    //     // Style headers
    //     $headerRange = 'A1:Q1';
    //     $sheet->getStyle($headerRange)->getFont()->setBold(true);
    //     $sheet->getStyle($headerRange)->getFill()
    //           ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    //           ->getStartColor()->setRGB('E2E8F0');

    //     // Auto-size columns
    //     foreach (range('A', 'Q') as $col) {
    //         $sheet->getColumnDimension($col)->setAutoSize(true);
    //     }

    //     // Add sample data
    //     $sheet->setCellValue('A2', 'ALT001');
    //     $sheet->setCellValue('B2', 'Fitriani Zahra');
    //     $sheet->setCellValue('C2', 'Alue Gunto');
    //     $sheet->setCellValue('D2', '1111010101010000');
    //     $sheet->setCellValue('E2', 'Jl. Contoh No. 1');
    //     $sheet->setCellValue('F2', '6286234567895');
    //     $sheet->setCellValue('G2', '347000');
    //     $sheet->setCellValue('H2', 'Pedagang');
    //     $sheet->setCellValue('I2', '6');
    //     $sheet->setCellValue('J2', '2');
    //     $sheet->setCellValue('K2', 'Ada');
    //     $sheet->setCellValue('L2', 'Tidak ada');
    //     $sheet->setCellValue('M2', 'Tidak ada');
    //     $sheet->setCellValue('N2', 'Ada');
    //     $sheet->setCellValue('O2', '>15 m² per orang');
    //     $sheet->setCellValue('P2', 'Keramik');
    //     $sheet->setCellValue('Q2', 'rumbia');

    //     return $spreadsheet;
    // }
}