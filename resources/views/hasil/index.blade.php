@extends('layouts.app')

@section('title', 'Hasil Penilaian dan Status Kelulusan PKH')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                        <div class="mb-4 lg:mb-0">
                            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                                <i class="fas fa-trophy text-yellow-500 mr-3"></i>
                                Hasil Penilaian dan Status Kelulusan PKH
                            </h1>
                            <p class="text-gray-600 mt-2">
                                Sistem penetapan status berdasarkan <strong>peringkat nilai tertinggi</strong> dengan kuota maksimal <strong>8 orang per desa</strong>
                            </p>
                            <p class="text-sm text-gray-500 mt-1">
                                Seleksi berdasarkan Net Flow tertinggi di masing-masing desa • Pencarian berdasarkan NIK tersedia
                            </p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <form action="{{ route('hasil.penetapan-status') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center shadow-sm"
                                        onclick="return confirm('⚠️ KONFIRMASI PENETAPAN STATUS\n\nProses ini akan:\n• Reset semua status yang sudah ada\n• Menetapkan ulang berdasarkan Net Flow tertinggi per desa\n• Maksimal 8 orang lolos per desa\n\nYakin ingin melanjutkan?')">
                                    <i class="fas fa-cogs mr-2"></i>
                                    Tetapkan Status Kelulusan
                                </button>
                            </form>
                            <div class="relative">
                                <select onchange="if(this.value) window.location.href=this.value" 
                                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 appearance-none pr-10 shadow-sm">
                                    <option value="">📊 Export Data</option>
                                    <option value="{{ route('hasil.export', ['format' => 'csv']) }}">📄 Export CSV Lengkap</option>
                                    <option value="{{ route('hasil.statistik') }}">📈 Lihat Statistik Detail</option>
                                </select>
                                <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-white pointer-events-none"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pencarian NIK -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-search text-blue-600 text-xl mr-3"></i>
                        <h2 class="text-xl font-bold text-gray-900">Pencarian Status Kelulusan Berdasarkan NIK</h2>
                    </div>
                    <p class="text-gray-600 mb-6">Masukkan NIK (16 digit) untuk mengetahui status kelulusan calon penerima bantuan</p>
                    
                    <form action="{{ route('hasil.cari-nik') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">NIK (Nomor Induk Kependudukan)</label>
                            <input type="text" 
                                   id="nik" 
                                   name="nik" 
                                   value="{{ request('nik') }}"
                                   placeholder="Masukkan 16 digit NIK" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   maxlength="16"
                                   pattern="[0-9]{16}"
                                   required>
                            <p class="text-xs text-gray-500 mt-1">Contoh: 1234567890123456</p>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center shadow-sm">
                                <i class="fas fa-search mr-2"></i>
                                Cari Status
                            </button>
                        </div>
                    </form>

                    <!-- Quick Search dengan AJAX -->
                    <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-bolt text-blue-600 mr-2"></i>
                            <span class="text-sm font-medium text-blue-900">Pencarian Cepat</span>
                        </div>
                        <div class="flex gap-2">
                            <input type="text" 
                                   id="quick-search-nik" 
                                   placeholder="NIK untuk pencarian cepat" 
                                   class="flex-1 px-3 py-2 text-sm border border-blue-200 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                   maxlength="16">
                            <button onclick="quickSearchNik()" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-medium transition-colors">
                                <i class="fas fa-bolt mr-1"></i>Cari
                            </button>
                        </div>
                        <div id="quick-search-result" class="mt-3 hidden"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Utama -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center hover:shadow-md transition-shadow">
                <div class="text-blue-600 mb-3">
                    <i class="fas fa-users text-4xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-900">{{ number_format($totalCalonPenerima) }}</h3>
                <p class="text-gray-600 font-medium">Total Calon Penerima</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center hover:shadow-md transition-shadow">
                <div class="text-indigo-600 mb-3">
                    <i class="fas fa-map-marker-alt text-4xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-900">{{ number_format($totalDesa) }}</h3>
                <p class="text-gray-600 font-medium">Total Desa</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center hover:shadow-md transition-shadow">
                <div class="text-purple-600 mb-3">
                    <i class="fas fa-trophy text-4xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-900">{{ number_format($kuotaMaksimal) }}</h3>
                <p class="text-gray-600 font-medium">Kuota Maksimal</p>
                <p class="text-xs text-gray-500 mt-1">({{ $totalDesa }} desa × 8 orang)</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center hover:shadow-md transition-shadow">
                <div class="text-green-600 mb-3">
                    <i class="fas fa-check-circle text-4xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-green-700">{{ number_format($totalLolos) }}</h3>
                <p class="text-gray-600 font-medium">Lolos Rekomendasi</p>
                @if($kuotaMaksimal > 0)
                    <p class="text-xs text-green-600 mt-1">{{ number_format(($totalLolos / $kuotaMaksimal) * 100, 1) }}% dari kuota</p>
                @endif
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center hover:shadow-md transition-shadow">
                <div class="text-red-600 mb-3">
                    <i class="fas fa-times-circle text-4xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-red-700">{{ number_format($totalTidakLolos) }}</h3>
                <p class="text-gray-600 font-medium">Tidak Lolos</p>
                @if($totalCalonPenerima > 0)
                    <p class="text-xs text-red-600 mt-1">{{ number_format(($totalTidakLolos / $totalCalonPenerima) * 100, 1) }}% dari total</p>
                @endif
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">Penetapan Status Berhasil!</h3>
                        <div class="mt-2 text-sm text-green-700 whitespace-pre-line">{{ session('success') }}</div>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terjadi Kesalahan!</h3>
                        <div class="mt-2 text-sm text-red-700">{{ session('error') }}</div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Informasi Sistem -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-blue-900">Cara Kerja Sistem Penetapan Status</h3>
                    <div class="mt-2 text-sm text-blue-800">
                        <ul class="list-disc list-inside space-y-1">
                            <li><strong>Kuota per Desa:</strong> Maksimal 8 orang yang dapat lolos rekomendasi</li>
                            <li><strong>Basis Penilaian:</strong> Peringkat nilai Net Flow tertinggi dalam masing-masing desa</li>
                            <li><strong>Contoh:</strong> Jika ada {{ $totalDesa }} desa, maka maksimal {{ $kuotaMaksimal }} orang yang lolos dari {{ number_format($totalCalonPenerima) }} total calon</li>
                            <li><strong>Status:</strong> Posisi 1-8 di desa = "Lolos Rekomendasi", Posisi 9+ = "Tidak Lolos Rekomendasi"</li>
                            <li><strong>Pencarian NIK:</strong> Gunakan form di atas untuk mencari status kelulusan berdasarkan NIK</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hasil Per Desa -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-list-alt text-blue-600 mr-3"></i>
                Hasil Seleksi Per Desa
            </h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @forelse($hasilPerDesa as $namaDesa => $hasilDesa)
                @php
                    $totalCalonDesa = $hasilDesa->count();
                    $lolosDesa = $hasilDesa->where('status_rekomendasi', 'Lolos Rekomendasi')->count();
                    $tidakLolosDesa = $hasilDesa->where('status_rekomendasi', 'Tidak Lolos Rekomendasi')->count();
                    $persentaseLolos = $totalCalonDesa > 0 ? ($lolosDesa / $totalCalonDesa) * 100 : 0;
                @endphp
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-map-marker-alt text-indigo-600 mr-2"></i>
                                {{ $namaDesa }}
                            </h3>
                            <div class="flex gap-2">
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                                    {{ $totalCalonDesa }} calon
                                </span>
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                    {{ $lolosDesa }} lolos
                                </span>
                                @if($tidakLolosDesa > 0)
                                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">
                                        {{ $tidakLolosDesa }} tidak lolos
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="mt-2">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ min($persentaseLolos, 100) }}%"></div>
                            </div>
                            <p class="text-xs text-gray-600 mt-1">{{ number_format($persentaseLolos, 1) }}% tingkat kelulusan</p>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posisi</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Net Flow</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($hasilDesa->take(10) as $index => $hasil)
                                    @php
                                        $posisiDesa = $index + 1;
                                        $masukKuota = $posisiDesa <= 8;
                                    @endphp
                                    <tr class="hover:bg-gray-50 {{ $masukKuota ? 'bg-green-50' : 'bg-red-50' }}">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if($posisiDesa <= 3)
                                                    <i class="fas fa-medal text-yellow-500 mr-2"></i>
                                                @elseif($posisiDesa <= 8)
                                                    <i class="fas fa-star text-green-500 mr-2"></i>
                                                @else
                                                    <i class="fas fa-circle text-gray-400 mr-2 text-xs"></i>
                                                @endif
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $masukKuota ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    #{{ $posisiDesa }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $hasil->alternatif->biodata->nama ?? $hasil->alternatif->nama }}
                                                </div>
                                                @if($hasil->alternatif->biodata->nik)
                                                    <div class="text-xs text-gray-500">NIK: {{ $hasil->alternatif->biodata->nik }}</div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="text-sm font-mono font-bold text-gray-900">{{ number_format($hasil->net_flow, 4) }}</span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @if($hasil->status_rekomendasi === 'Lolos Rekomendasi')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check mr-1"></i>Lolos
                                                </span>
                                            @elseif($hasil->status_rekomendasi === 'Tidak Lolos Rekomendasi')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-times mr-1"></i>Tidak Lolos
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    <i class="fas fa-clock mr-1"></i>Belum Ditetapkan
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($hasilDesa->count() > 10)
                        <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 text-center">
                            <a href="{{ route('hasil.detail-desa', $hasilDesa->first()->alternatif->desa_id) }}" 
                               class="text-blue-600 hover:text-blue-800 font-medium text-sm inline-flex items-center">
                                <i class="fas fa-eye mr-1"></i>
                                Lihat Semua {{ $hasilDesa->count() }} Calon Penerima
                            </a>
                        </div>
                    @endif
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                        <div class="text-gray-400 mb-4">
                            <i class="fas fa-inbox text-6xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Data Hasil Penilaian</h3>
                        <p class="text-gray-600 mb-6">Silakan lakukan penilaian terlebih dahulu untuk melihat hasil seleksi.</p>
                        <a href="/admin" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 inline-flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Mulai Penilaian
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
// Format input NIK hanya angka
document.getElementById('nik').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '');
});

document.getElementById('quick-search-nik').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '');
});

// Quick search function
async function quickSearchNik() {
    const nik = document.getElementById('quick-search-nik').value;
    const resultDiv = document.getElementById('quick-search-result');
    
    if (nik.length !== 16) {
        resultDiv.innerHTML = '<div class="text-red-600 text-sm">NIK harus 16 digit</div>';
        resultDiv.classList.remove('hidden');
        return;
    }
    
    try {
        const response = await fetch(`{{ route('hasil.api-cari-nik') }}?nik=${nik}`);
        const data = await response.json();
        
        if (data.success) {
            const result = data.data;
            const statusClass = result.masuk_8_besar ? 'text-green-600' : 'text-red-600';
            const statusIcon = result.masuk_8_besar ? 'fa-check-circle' : 'fa-times-circle';
            const statusText = result.status_rekomendasi || (result.masuk_8_besar ? 'Lolos Rekomendasi' : 'Tidak Lolos Rekomendasi');
            
            resultDiv.innerHTML = `
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-medium text-gray-900">${result.nama}</h4>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${result.masuk_8_besar ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                            <i class="fas ${statusIcon} mr-1"></i>${statusText}
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">NIK:</span> <span class="font-mono">${result.nik}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Desa:</span> ${result.desa}
                        </div>
                        <div>
                            <span class="text-gray-500">Net Flow:</span> <span class="font-mono">${parseFloat(result.net_flow).toFixed(4)}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Posisi di Desa:</span> #${result.posisi_desa}
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('hasil.cari-nik') }}?nik=${nik}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Lihat Detail Lengkap →
                        </a>
                    </div>
                </div>
            `;
        } else {
            resultDiv.innerHTML = '<div class="text-red-600 text-sm">Data tidak ditemukan</div>';
        }
        
        resultDiv.classList.remove('hidden');
    } catch (error) {
        resultDiv.innerHTML = '<div class="text-red-600 text-sm">Terjadi kesalahan saat mencari data</div>';
        resultDiv.classList.remove('hidden');
    }
}

// Enter key untuk quick search
document.getElementById('quick-search-nik').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        quickSearchNik();
    }
});
</script>
@endsection