<x-filament-panels::page>
    <div class="container mx-auto px-4 py-8">

        <!-- Stats Cards dengan hover effect -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <!-- Alternatif Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="p-3 rounded-xl bg-blue-50 text-blue-600 mr-4 shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm font-medium">TOTAL CALON ALTERNATIF</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $totalAlternatif }}</h3>
                        <div class="flex items-center mt-2">
                            <span class="text-xs font-medium px-2 py-1 rounded-full bg-blue-100 text-blue-800">+5.2%</span>
                            <span class="text-xs text-gray-500 ml-2">vs bulan lalu</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Desa Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-emerald-500 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="p-3 rounded-xl bg-emerald-50 text-emerald-600 mr-4 shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm font-medium">TOTAL DESA</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $totalDesa }}</h3>
                        <div class="flex items-center mt-2">
                            <span class="text-xs font-medium px-2 py-1 rounded-full bg-emerald-100 text-emerald-800">+2.1%</span>
                            <span class="text-xs text-gray-500 ml-2">vs bulan lalu</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kriteria Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-amber-500 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="p-3 rounded-xl bg-amber-50 text-amber-600 mr-4 shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm font-medium">TOTAL KRITERIA</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $totalKriteria }}</h3>
                        <div class="flex items-center mt-2">
                            <span class="text-xs font-medium px-2 py-1 rounded-full bg-amber-100 text-amber-800">+0.8%</span>
                            <span class="text-xs text-gray-500 ml-2">vs bulan lalu</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest Alternatif Table dengan design modern -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-10">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Calon Alternatif Terbaru</h2>
                        <p class="text-sm text-gray-500">5 calon alternatif yang terakhir ditambahkan</p>
                    </div>
                    <button class="text-sm font-medium text-indigo-600 hover:text-indigo-500 transition-colors">
                        Lihat Semua →
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Desa</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ditambahkan</th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Aksi</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($latestAlternatifs as $alternatif)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <span class="text-blue-800 font-medium">{{ substr($alternatif->kode, 0, 3) }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $alternatif->kode }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 font-medium">{{ $alternatif->nama }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $alternatif->desa->nama_desa }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $alternatif->created_at->format('d M Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $alternatif->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">Detail</a>
                                <a href="#" class="text-gray-600 hover:text-gray-900">Edit</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                <div class="flex flex-col items-center justify-center py-8">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="mt-2 text-gray-500">Tidak ada data alternatif</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
            <!-- Alternatif per Desa Chart -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Alternatif per Desa</h2>
                        <p class="text-sm text-gray-500">Top 5 desa dengan alternatif terbanyak</p>
                    </div>
                </div>
                <div class="h-80">
                    <canvas id="alternatifPerDesaChart"></canvas>
                </div>
            </div>

            <!-- Pertumbuhan Bulanan Chart -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Pertumbuhan Alternatif</h2>
                        <p class="text-sm text-gray-500">Penambahan alternatif 6 bulan terakhir</p>
                    </div>
                </div>
                <div class="h-80">
                    <canvas id="monthlyGrowthChart"></canvas>
                </div>
            </div>
        </div>

        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Alternatif per Desa Chart (Bar Chart)
                const desaCtx = document.getElementById('alternatifPerDesaChart').getContext('2d');
                new Chart(desaCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($this -> alternatifPerDesa -> pluck('desa')),
                        datasets: [{
                            label: 'Jumlah Alternatif',
                            data: @json($this -> alternatifPerDesa -> pluck('total')),
                            backgroundColor: [
                                'rgba(79, 70, 229, 0.7)',
                                'rgba(99, 102, 241, 0.7)',
                                'rgba(129, 140, 248, 0.7)',
                                'rgba(165, 180, 252, 0.7)',
                                'rgba(199, 210, 254, 0.7)'
                            ],
                            borderColor: [
                                'rgba(79, 70, 229, 1)',
                                'rgba(99, 102, 241, 1)',
                                'rgba(129, 140, 248, 1)',
                                'rgba(165, 180, 252, 1)',
                                'rgba(199, 210, 254, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `Alternatif: ${context.raw}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });

                // Monthly Growth Chart (Line Chart)
                const growthCtx = document.getElementById('monthlyGrowthChart').getContext('2d');
                new Chart(growthCtx, {
                    type: 'line',
                    data: {
                        labels: @json($this -> monthlyGrowth -> pluck('month')),
                        datasets: [{
                            label: 'Penambahan Alternatif',
                            data: @json($this -> monthlyGrowth -> pluck('total')),
                            fill: true,
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderColor: 'rgba(16, 185, 129, 1)',
                            tension: 0.3,
                            pointBackgroundColor: 'rgba(16, 185, 129, 1)',
                            pointBorderColor: '#fff',
                            pointHoverRadius: 6,
                            pointHoverBorderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `Alternatif baru: ${context.raw}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            });
        </script>
        @endpush

        <!-- Info Panel dengan gradient -->
        <div class="bg-gradient-to-r from-indigo-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div class="max-w-lg">
                    <h2 class="text-xl font-semibold mb-2">Sistem Pendukung Keputusan</h2>
                    <p class="opacity-90">
                        Dashboard ini menampilkan ringkasan data untuk sistem penentuan penerima bantuan.
                        Gunakan menu navigasi untuk mengelola data lebih lanjut.
                    </p>
                </div>
                <button class="bg-white text-indigo-600 px-4 py-2 rounded-lg font-medium hover:bg-opacity-90 transition-all">
                    Panduan Penggunaan
                </button>
            </div>
        </div>
    </div>
</x-filament-panels::page>