@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="bg-rose-600 px-6 py-8 text-center">
                <h1 class="text-3xl font-bold text-white">Data Diterima PKH</h1>
                <p class="mt-2 text-rose-100">Berikut data lengkap penerima bantuan</p>
            </div>
            
            <!-- Content -->
            <div class="px-6 py-8">
                <div class="flex flex-col md:flex-row gap-8">
                    <!-- Profile Card -->
                    <div class="w-full md:w-1/3">
                        <div class="bg-gradient-to-br from-rose-50 to-blue-50 rounded-xl p-6 shadow-sm border border-gray-100 text-center">
                            <div class="mx-auto h-24 w-24 rounded-full bg-rose-100 flex items-center justify-center mb-4">
                                <svg class="h-12 w-12 text-rose-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">{{ $alternatif->nama }}</h3>
                            <p class="text-sm text-gray-500 mb-4">Kode: {{ $alternatif->kode }}</p>
                            
                            <div class="mt-6 space-y-3">
                                <div class="flex items-center justify-center space-x-2">
                                    <span class="px-3 py-1 bg-rose-100 text-rose-800 text-xs font-medium rounded-full">
                                        Proses
                                    </span>
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                        PKH Tahap {{ rand(1, 4) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Data Detail -->
                    <div class="w-full md:w-2/3">
                        <div class="space-y-6">
                            <!-- Personal Info -->
                            <div class="bg-gray-50 rounded-lg p-5">
                                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 text-gray-500 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                    Informasi Pribadi
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-500">NIK</p>
                                        <p class="font-medium">{{ $alternatif->biodata->nik }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Desa</p>
                                        <p class="font-medium">{{ $alternatif->desa->nama_desa }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Alamat</p>
                                        <p class="font-medium">{{ $alternatif->biodata->alamat ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">No. HP</p>
                                        <p class="font-medium">{{ $alternatif->biodata->no_hp ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Program Info -->
                            <div class="bg-gray-50 rounded-lg p-5">
                                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 text-gray-500 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                    </svg>
                                    Informasi Bantuan
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="bg-white p-3 rounded-lg border border-gray-100 shadow-sm text-center">
                                        <p class="text-sm text-gray-500">Tanggal Daftar</p>
                                        <p class="font-medium text-indigo-600">{{ now()->subMonths(rand(1,12))->format('d M Y') }}</p>
                                    </div>
                                    <div class="bg-white p-3 rounded-lg border border-gray-100 shadow-sm text-center">
                                        <p class="text-sm text-gray-500">Jenis Bantuan</p>
                                        <p class="font-medium text-indigo-600">PKH Reguler</p>
                                    </div>
                                    <div class="bg-white p-3 rounded-lg border border-gray-100 shadow-sm text-center">
                                        <p class="text-sm text-gray-500">Penerimaan Terakhir</p>
                                        <p class="font-medium text-indigo-600">{{ now()->subMonths(1)->format('d M Y') }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Action Button -->
                            <div class="pt-4">
                                <a href="{{ route('pencarian.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                    Cari Data Lain
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4 text-center border-t border-gray-200">
                <p class="text-xs text-gray-500">Data diperbarui: {{ now()->format('d M Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection