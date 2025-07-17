# Fitur Import Excel untuk Penilaian

## Instalasi Package yang Diperlukan

Sebelum menggunakan fitur import Excel, pastikan untuk menginstall package PhpSpreadsheet:

```bash
composer require phpoffice/phpspreadsheet
```

## Fitur yang Tersedia

### 1. Download Template Excel
- Tombol "Download Template Excel" akan mengunduh file template dengan format yang sesuai
- Template sudah berisi header kolom dan contoh data
- Header kriteria akan disesuaikan dengan data kriteria yang ada di database

### 2. Import Data dari Excel
- Tombol "Import Excel" untuk mengupload dan memproses file Excel
- Mendukung format .xlsx dan .xls
- Maksimal ukuran file 5MB
- Validasi data otomatis dengan pesan error yang jelas

## Format File Excel

### Struktur Kolom:
| Kolom | Nama Field | Wajib | Keterangan |
|-------|------------|-------|------------|
| A | Kode Alternatif | Ya | Harus unik, contoh: ALT001 |
| B | Nama Alternatif | Ya | Nama lengkap alternatif |
| C | Nama Desa | Ya | Nama desa (akan dibuat otomatis jika belum ada) |
| D | NIK | Tidak | 16 digit NIK |
| E | Alamat | Tidak | Alamat lengkap |
| F | No HP | Tidak | Nomor telepon |
| G dst | Nilai Kriteria | Tidak | Nilai untuk setiap kriteria (berdasarkan urutan di database) |

### Contoh Data:
```
Kode Alternatif | Nama Alternatif | Nama Desa    | NIK              | Alamat           | No HP        | Kriteria 1 | Kriteria 2
ALT001         | John Doe        | Desa Contoh  | 1234567890123456 | Jl. Contoh No.123| 081234567890 | 1          | 2
ALT002         | Jane Smith      | Desa Lain    | 6543210987654321 | Jl. Lain No.456  | 087654321098 | 3          | 1
```

## Proses Import

### Yang Dilakukan Sistem:
1. **Validasi File**: Memeriksa format dan ukuran file
2. **Validasi Data**: 
   - Memastikan field wajib terisi
   - Memvalidasi kode alternatif unik
   - Memvalidasi nilai kriteria berupa angka
3. **Pembuatan Data**:
   - Membuat desa baru jika belum ada
   - Membuat alternatif baru
   - Membuat biodata jika ada data NIK/alamat/HP
   - Membuat penilaian untuk setiap kriteria
4. **Mapping Subkriteria**: Sistem akan mencari subkriteria yang sesuai berdasarkan nilai

### Error Handling:
- Kode alternatif duplikat
- Data wajib kosong
- Nilai kriteria bukan angka
- Subkriteria tidak ditemukan
- Format file tidak didukung

## Cara Penggunaan

### Langkah 1: Download Template
1. Buka halaman Penilaian
2. Klik tombol "Download Template Excel"
3. File template akan terdownload dengan nama `template_import_penilaian_[timestamp].xlsx`

### Langkah 2: Isi Data
1. Buka file template yang sudah didownload
2. Isi data sesuai format yang sudah disediakan
3. Pastikan tidak mengubah header kolom
4. Simpan file dalam format .xlsx atau .xls

### Langkah 3: Import Data
1. Kembali ke halaman Penilaian
2. Klik tombol "Import Excel"
3. Upload file yang sudah diisi
4. Klik "Import Data"
5. Sistem akan memproses dan menampilkan hasil import

### Langkah 4: Verifikasi
1. Periksa notifikasi hasil import
2. Jika ada error, perbaiki data dan ulangi proses
3. Data yang berhasil diimpor akan langsung muncul di halaman

## Tips dan Best Practices

1. **Backup Data**: Selalu backup database sebelum import data besar
2. **Test dengan Data Kecil**: Coba import dengan beberapa baris data terlebih dahulu
3. **Periksa Kriteria**: Pastikan kriteria sudah ada di database sebelum import
4. **Format Konsisten**: Gunakan format yang konsisten untuk semua data
5. **Validasi Manual**: Periksa hasil import secara manual untuk memastikan akurasi

## Troubleshooting

### Error "Package not found"
```bash
composer require phpoffice/phpspreadsheet
```

### Error "File too large"
- Pastikan file tidak lebih dari 5MB
- Kurangi jumlah data atau kompres file

### Error "Invalid format"
- Pastikan file dalam format .xlsx atau .xls
- Jangan mengubah struktur template

### Error "Duplicate code"
- Periksa kode alternatif yang duplikat
- Gunakan kode yang unik untuk setiap alternatif

### Error "Subkriteria not found"
- Pastikan subkriteria sudah dibuat untuk setiap kriteria
- Periksa nilai yang diinput sesuai dengan subkriteria yang ada

## Keamanan

- File upload disimpan sementara dan dihapus setelah proses
- Validasi tipe file untuk mencegah upload file berbahaya
- Transaction database untuk memastikan konsistensi data
- Log error untuk debugging dan monitoring