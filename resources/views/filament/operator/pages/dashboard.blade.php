<x-filament-panels::page>
    <div class="container mx-auto px-4 py-8 space-y-8">
        <!-- Stats Cards with improved design -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Alternatif Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border-l-4 border-blue-500 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:border-blue-600 group overflow-hidden relative">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-white opacity-0 group-hover:opacity-100 transition-opacity duration-300 dark:from-blue-900/30 dark:to-gray-800/50"></div>
                <div class="relative z-10 flex items-center">
                    <div class="p-3 rounded-xl bg-blue-50 text-blue-600 mr-4 shadow-inner dark:bg-blue-900/30 dark:text-blue-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-300 text-sm font-medium">TOTAL CALON PENERIMA</p>
                        <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-1">{{ $totalAlternatif }}</h3>
                        <div class="flex items-center mt-2">
                            <span class="text-xs font-medium px-2 py-1 rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200">+5.2%</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">vs bulan lalu</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Desa Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border-l-4 border-emerald-500 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:border-emerald-600 group overflow-hidden relative">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 to-white opacity-0 group-hover:opacity-100 transition-opacity duration-300 dark:from-emerald-900/30 dark:to-gray-800/50"></div>
                <div class="relative z-10 flex items-center">
                    <div class="p-3 rounded-xl bg-emerald-50 text-emerald-600 mr-4 shadow-inner dark:bg-emerald-900/30 dark:text-emerald-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-300 text-sm font-medium">TOTAL DESA</p>
                        <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-1">{{ $totalDesa }}</h3>
                        <div class="flex items-center mt-2">
                            <span class="text-xs font-medium px-2 py-1 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-200">+2.1%</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">vs bulan lalu</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section with tabs -->
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
</x-filament-panels::page>