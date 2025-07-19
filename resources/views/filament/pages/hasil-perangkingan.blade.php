<x-filament::page>
    @if(!$rankingData)
    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
        <span class="font-medium">Belum ada data perangkingan. Silahkan lakukan perhitungan PROMETHEE terlebih dahulu.</span>
    </div>
    @else
    <!-- Tab Navigation -->
    <div class="mb-4 flex justify-between border-b border-gray-200">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="rankingTabs" role="tablist">
            <li class="me-2" role="presentation">
                <button class="inline-block p-4 border-b-2 rounded-t-lg" id="all-tab" data-tabs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">Semua Penerima</button>
            </li>
            <li class="me-2" role="presentation">
                <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="per-desa-tab" data-tabs-target="#per-desa" type="button" role="tab" aria-controls="per-desa" aria-selected="false">Per Desa</button>
            </li>
        </ul>
    </div>

    <!-- Tab Content -->
    <div id="rankingTabsContent">
        <!-- All Ranking Tab -->
        <div class="hidden p-4 rounded-lg bg-gray-50" id="all" role="tabpanel" aria-labelledby="all-tab">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                <!-- Card Header -->
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Semua Hasil Perangkingan</h3>
                        <p class="text-sm text-gray-600 mt-1">Ranking global semua calon penerima bantuan PKH</p>
                    </div>
                </div>

                <!-- Table Container -->
                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ranking</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Penerima</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Desa</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIK</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No HP</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Net Flow</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($rankingData as $item)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center justify-center w-8 h-8 text-sm font-bold text-white 
                                            @if($item['ranking'] <= 3) bg-yellow-500 
                                            @elseif($item['ranking'] <= 10) bg-blue-500 
                                            @else bg-gray-500 @endif rounded-full">
                                            {{ $item['ranking'] }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $item['nama'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $item['desa'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $item['nik'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $item['no_hp'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="text-sm font-medium text-gray-900">{{ number_format($item['net_flow'], 4) }}</div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Chart Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mt-8">
                <!-- Chart Header -->
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-medium text-gray-900">Visualisasi Net Flow Perangkingan</h3>
                </div>

                <!-- Chart Container -->
                <div class="p-6">
                    <div class="relative" style="height: 400px;">
                        <canvas id="rankingChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Per Desa Ranking Tab -->
        <div class="hidden p-4 rounded-lg bg-gray-50" id="per-desa" role="tabpanel" aria-labelledby="per-desa-tab">
            <!-- Download Button for Per Desa Tab -->
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Hasil Perangkingan Per Desa</h2>
                    <p class="text-sm text-gray-600 mt-1">Menampilkan maksimal 8 penerima terbaik per desa berdasarkan ranking global</p>
                </div>
                <div class="flex gap-2">
                    <button wire:click="downloadPerDesaPdf" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-900 focus:ring focus:ring-green-300 disabled:opacity-25 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download PDF Per Desa
                    </button>
                </div>
            </div>

            @foreach($rankingPerDesa as $desaId => $items)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                <!-- Card Header -->
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Desa {{ $items->first()['desa'] ?? 'Tidak Diketahui' }}</h3>
                        <p class="text-sm text-gray-600">{{ $items->count() }} penerima terpilih</p>
                    </div>
                    <div class="flex gap-2">
                        <button wire:click="downloadDesaPdf({{ $desaId }})" class="inline-flex items-center px-3 py-1.5 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            PDF
                        </button>
                    </div>
                </div>

                <!-- Table Container -->
                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ranking Desa</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Penerima</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIK</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No HP</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Net Flow</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ranking Global</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($items as $item)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center justify-center w-8 h-8 text-sm font-bold text-white 
                                            @if($item['local_ranking'] <= 3) bg-yellow-500 
                                            @else bg-blue-600 @endif rounded-full">
                                            {{ $item['local_ranking'] }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $item['nama'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $item['nik'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $item['no_hp'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="text-sm font-medium text-gray-900">{{ number_format($item['net_flow'], 4) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($item['global_ranking'] <= 10) bg-green-100 text-green-800 
                                        @elseif($item['global_ranking'] <= 50) bg-yellow-100 text-yellow-800 
                                        @else bg-gray-100 text-gray-800 @endif">
                                        #{{ $item['global_ranking'] }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Tab Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab functionality
            const tabs = document.querySelectorAll('[data-tabs-target]');
            const tabContents = document.querySelectorAll('[role="tabpanel"]');

            // Show first tab by default
            tabs[0].classList.add('border-primary-500', 'text-primary-600');
            tabContents[0].classList.remove('hidden');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const target = document.querySelector(tab.getAttribute('data-tabs-target'));

                    // Hide all tab contents
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });

                    // Remove active styles from all tabs
                    tabs.forEach(t => {
                        t.classList.remove('border-primary-500', 'text-primary-600');
                        t.classList.add('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');
                    });

                    // Show selected tab content
                    target.classList.remove('hidden');

                    // Add active styles to selected tab
                    tab.classList.add('border-primary-500', 'text-primary-600');
                    tab.classList.remove('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');
                });
            });

            // Chart.js initialization
            const ctx = document.getElementById('rankingChart').getContext('2d');
            const chartData = @json($chartData);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Net Flow',
                        data: chartData.data,
                        backgroundColor: chartData.colors,
                        borderColor: chartData.colors.map(color => color + '80'),
                        borderWidth: 3,
                        borderRadius: 4,
                        borderSkipped: false,
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
                                weight: 'bold'
                            },
                            padding: {
                                top: 10,
                                bottom: 30
                            }
                        },
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: 'white',
                            bodyColor: 'white',
                            borderColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 1,
                            cornerRadius: 6,
                            displayColors: false,
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
                                color: 'rgba(0, 0, 0, 0.1)',
                                drawBorder: false
                            },
                            ticks: {
                                font: {
                                    size: 12
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
                                    weight: 'bold'
                                },
                                color: '#374151'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 12
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
                                    weight: 'bold'
                                },
                                color: '#374151'
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutQuart'
                    }
                }
            });
        });
    </script>
    @endif
</x-filament::page>