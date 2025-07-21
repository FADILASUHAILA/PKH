@extends('layouts.app')

@section('title', 'Hasil Pencarian NIK - ' . $dataCalonPenerima['biodata']->nik)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <!-- <nav class="flex mb-4" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href="{{ route('hasil.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                    <i class="fas fa-home mr-1"></i>
                                    Hasil Penilaian
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                    <span class="text-gray-500 font-medium">Pencarian NIK</span>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                    <span class="text-gray-500 font-medium">{{ $dataCalonPenerima['biodata']->nik }}</span>
                                </div>
                            </li>
                        </ol>
                    </nav> -->

                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                        <div class="mb-4 lg:mb-0">
                            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                                <i class="fas fa-id-card text-blue-600 mr-3"></i>
                                Hasil Pencarian NIK
                            </h1>
                            <p class="text-gray-600 mt-2">
                                Status kelulusan untuk NIK: <strong class="font-mono">{{ $dataCalonPenerima['biodata']->nik }}</strong>
                            </p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('pencarian.index') }}"
                                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali
                            </a>
                            <!-- <a href="{{ route('hasil.detail-desa', $dataCalonPenerima['alternatif']->desa_id) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                Lihat Desa {{ $dataCalonPenerima['alternatif']->desa->nama_desa }}
                            </a> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Kelulusan -->
        <div class="mb-8">
            @if($dataCalonPenerima['masuk_8_besar'])
            <div class="bg-green-50 border-l-4 border-green-400 p-6 rounded-r-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400 text-3xl"></i>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold text-green-800">LOLOS REKOMENDASI</h2>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-red-50 border-l-4 border-red-400 p-6 rounded-r-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-times-circle text-red-400 text-3xl"></i>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold text-red-800">TIDAK LOLOS REKOMENDASI</h2>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Detail Calon Penerima -->
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-8 mb-8">
            <!-- Informasi Personal -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-user text-blue-600 mr-2"></i>
                        Informasi Personal
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">NIK</span>
                        <span class="font-mono text-gray-900 font-bold">{{ $dataCalonPenerima['biodata']->nik }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">Nama Lengkap</span>
                        <span class="text-gray-900 font-bold">{{ $dataCalonPenerima['alternatif']->nama }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600 font-medium">Desa</span>
                        <span class="text-gray-900 font-bold">{{ $dataCalonPenerima['alternatif']->desa->nama_desa }}</span>
                    </div>
                </div>
            </div>

            <!-- Informasi Penilaian -->
            <!-- <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-chart-line text-green-600 mr-2"></i>
                        Hasil Penilaian PROMETHEE
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">Leaving Flow (Phi+)</span>
                        <span class="font-mono text-green-600 font-bold">{{ number_format($dataCalonPenerima['hasil_penilaian']->leaving_flow, 6) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">Entering Flow (Phi-)</span>
                        <span class="font-mono text-red-600 font-bold">{{ number_format($dataCalonPenerima['hasil_penilaian']->entering_flow, 6) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">Net Flow (Phi)</span>
                        <span class="font-mono text-gray-900 font-bold text-lg bg-yellow-100 px-2 py-1 rounded">
                            {{ number_format($dataCalonPenerima['hasil_penilaian']->net_flow, 6) }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600 font-medium">Ranking Global</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            #{{ $dataCalonPenerima['hasil_penilaian']->ranking }}
                        </span>
                    </div>
                </div>
            </div> -->
        </div>

        <!-- Posisi dan Status -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-trophy text-purple-600 mr-2"></i>
                    Posisi dan Status Kelulusan
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Status Kelulusan -->
                    <div class="text-center">
                        <div class="flex items-center justify-center mb-3">
                            @if($dataCalonPenerima['masuk_8_besar'])
                            <i class="fas fa-check-circle text-green-500 text-4xl"></i>
                            @else
                            <i class="fas fa-times-circle text-red-500 text-4xl"></i>
                            @endif
                        </div>
                        <h4 class="text-lg font-bold {{ $dataCalonPenerima['masuk_8_besar'] ? 'text-green-700' : 'text-red-700' }}">
                            {{ $dataCalonPenerima['status_prediksi'] }}
                        </h4>
                        <p class="text-gray-600 font-medium">Status Prediksi</p>
                        @if($dataCalonPenerima['hasil_penilaian']->status_rekomendasi)
                        <p class="text-sm text-gray-500 mt-1">Status Resmi: {{ $dataCalonPenerima['hasil_penilaian']->status_rekomendasi }}</p>
                        @else
                        <p class="text-sm text-gray-500 mt-1">Belum ditetapkan resmi</p>
                        @endif
                    </div>

                    <!-- Kuota Desa -->
                    <div class="text-center">
                        <div class="flex items-center justify-center mb-3">
                            <i class="fas fa-users text-blue-500 text-4xl"></i>
                        </div>
                        <h4 class="text-2xl font-bold text-blue-700">8</h4>
                        <p class="text-gray-600 font-medium">Kuota Desa</p>
                        <p class="text-sm text-gray-500 mt-1">maksimal per desa</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Tambahan -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-yellow-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-yellow-900">Informasi Penting</h3>
                    <div class="mt-2 text-sm text-yellow-800">
                        <ul class="list-disc list-inside space-y-1">
                            <li><strong>Basis Seleksi:</strong> Peringkat berdasarkan nilai Net Flow tertinggi di masing-masing desa</li>
                            <li><strong>Kuota per Desa:</strong> Maksimal 8 orang yang dapat lolos rekomendasi</li>
                            <li><strong>Status Saat Ini:</strong>
                                @if($dataCalonPenerima['hasil_penilaian']->status_rekomendasi)
                                Sudah ditetapkan resmi
                                @else
                                Masih prediksi, belum ditetapkan resmi
                                @endif
                            </li>
                            <li><strong>Desa {{ $dataCalonPenerima['alternatif']->desa->nama_desa }}:</strong>
                                Total {{ $dataCalonPenerima['total_calon_desa'] }} calon penerima terdaftar
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pencarian Lain -->
        <!-- <div class="mt-8 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-search text-blue-600 mr-2"></i>
                Cari NIK Lain
            </h3>
            <form action="{{ route('hasil.cari-nik') }}" method="GET" class="flex gap-4">
                <div class="flex-1">
                    <input type="text" 
                           name="nik" 
                           placeholder="Masukkan NIK lain (16 digit)" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           maxlength="16"
                           pattern="[0-9]{16}"
                           required>
                </div>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center">
                    <i class="fas fa-search mr-2"></i>
                    Cari
                </button>
            </form>
        </div> -->
    </div>
</div>

<script>
    // Format input NIK hanya angka
    document.querySelector('input[name="nik"]').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Auto focus pada input NIK
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('input[name="nik"]').focus();
    });
</script>
@endsection