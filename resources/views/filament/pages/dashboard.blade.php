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

            <!-- Sub Kriteria Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-amber-500 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="p-3 rounded-xl bg-amber-50 text-amber-600 mr-4 shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm font-medium">TOTAL SUB KRITERIA</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $totalSubKriteria }}</h3>
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
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:shadow-md">
            <!-- Chart Header with tabs -->
            <div class="border-b border-gray-100 dark:border-gray-700">
                <div class="px-6 py-4 flex items-center justify-between bg-gray-50/50 dark:bg-gray-700/30">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Visualisasi Net Flow Perangkingan</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Grafik menampilkan nilai Net Flow untuk setiap alternatif</p>
                    </div>
                </div>
            </div>

            <!-- Chart Container -->
            <div class="p-6">
                <div class="relative" style="height: 400px;">
                    <canvas id="rankingChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Info Panel with improved gradient and animation -->
        <!-- <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white overflow-hidden relative transition-all duration-500 hover:shadow-xl hover:-translate-y-1">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-y-0 left-0 w-1/3 bg-gradient-to-r from-white to-transparent"></div>
                <div class="absolute top-0 right-0 w-1/4 h-1/4 bg-white rounded-full filter blur-3xl opacity-20"></div>
                <div class="absolute bottom-0 right-0 w-1/3 h-1/3 bg-indigo-300 rounded-full filter blur-3xl opacity-20"></div>
            </div>
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between">
                <div class="max-w-lg mb-4 md:mb-0">
                    <h2 class="text-xl font-semibold mb-2">Sistem Pendukung Keputusan</h2>
                    <p class="opacity-90">
                        Dashboard ini menampilkan ringkasan data untuk sistem penentuan penerima bantuan.
                        Gunakan menu navigasi untuk mengelola data lebih lanjut.
                    </p>
                </div>
                <button class="bg-white text-indigo-600 px-5 py-2.5 rounded-lg font-medium hover:bg-opacity-90 transition-all flex items-center shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Panduan Penggunaan
                </button>
            </div>
        </div> -->

        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('rankingChart').getContext('2d');

                const chartData = @json($chartData);

                // Generate hover colors (darker versions)
                const hoverColors = chartData.colors.map(color => {
                    return Color(color).darken(0.2).hex();
                });

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            label: 'Net Flow',
                            data: chartData.data,
                            backgroundColor: chartData.colors,
                            borderColor: chartData.colors.map(color => Color(color).alpha(0.8).hexa()),
                            borderWidth: 2,
                            borderRadius: 6,
                            borderSkipped: false,
                            hoverBackgroundColor: hoverColors,
                            hoverBorderColor: chartData.colors,
                            hoverBorderWidth: 3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Net Flow Perangkingan PROMETHEE',
                                font: {
                                    size: 16,
                                    weight: 'bold',
                                    family: "'Inter', sans-serif"
                                },
                                color: '#111827',
                                padding: {
                                    top: 10,
                                    bottom: 30
                                }
                            },
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.85)',
                                titleColor: 'white',
                                bodyColor: 'white',
                                borderColor: 'rgba(255, 255, 255, 0.1)',
                                borderWidth: 1,
                                cornerRadius: 8,
                                displayColors: false,
                                padding: 12,
                                bodyFont: {
                                    size: 14,
                                    weight: '500'
                                },
                                titleFont: {
                                    size: 12,
                                    weight: 'normal'
                                },
                                callbacks: {
                                    title: function(context) {
                                        return context[0].label;
                                    },
                                    label: function(context) {
                                        return 'Net Flow: ' + context.parsed.y.toFixed(4);
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)',
                                    drawBorder: false
                                },
                                ticks: {
                                    font: {
                                        size: 12,
                                        family: "'Inter', sans-serif"
                                    },
                                    color: '#6B7280',
                                    callback: function(value) {
                                        return value.toFixed(4);
                                    }
                                },
                                title: {
                                    display: true,
                                    text: 'Net Flow Value',
                                    font: {
                                        size: 14,
                                        weight: 'bold',
                                        family: "'Inter', sans-serif"
                                    },
                                    color: '#374151',
                                    padding: {
                                        top: 10,
                                        bottom: 10
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        size: 12,
                                        family: "'Inter', sans-serif"
                                    },
                                    color: '#6B7280',
                                    maxRotation: 45,
                                    minRotation: 0
                                },
                                title: {
                                    display: true,
                                    text: 'Nama Penerima',
                                    font: {
                                        size: 14,
                                        weight: 'bold',
                                        family: "'Inter', sans-serif"
                                    },
                                    color: '#374151',
                                    padding: {
                                        top: 10,
                                        bottom: 10
                                    }
                                }
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        },
                        animation: {
                            duration: 1000,
                            easing: 'easeOutQuart'
                        },
                        elements: {
                            bar: {
                                backgroundColor: function(context) {
                                    const index = context.dataIndex;
                                    const value = context.dataset.data[index];
                                    return value >= 0 ? chartData.colors[index] : '#EF4444';
                                }
                            }
                        }
                    }
                });

                // Simple color manipulation helper
                function Color(hex) {
                    let r, g, b;

                    // Parse hex color
                    if (hex.length === 4) {
                        r = parseInt(hex[1] + hex[1], 16);
                        g = parseInt(hex[2] + hex[2], 16);
                        b = parseInt(hex[3] + hex[3], 16);
                    } else {
                        r = parseInt(hex.substring(1, 3), 16);
                        g = parseInt(hex.substring(3, 5), 16);
                        b = parseInt(hex.substring(5, 7), 16);
                    }

                    return {
                        darken: function(amount) {
                            r = Math.max(0, Math.floor(r * (1 - amount)));
                            g = Math.max(0, Math.floor(g * (1 - amount)));
                            b = Math.max(0, Math.floor(b * (1 - amount)));
                            return this;
                        },
                        alpha: function(a) {
                            this.a = a;
                            return this;
                        },
                        hex: function() {
                            return '#' + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
                        },
                        hexa: function() {
                            const alpha = Math.round(this.a * 255);
                            return '#' + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1) +
                                (alpha < 16 ? '0' : '') + alpha.toString(16);
                        }
                    };
                }
            });
        </script>
        @endpush
    </div>
</x-filament-panels::page>