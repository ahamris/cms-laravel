<x-layouts.admin title="Detailed Analytics">
    <div>
        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-[var(--color-accent)] rounded-md flex items-center justify-center">
                    <i class="fa-solid fa-chart-line text-white text-xl"></i>
                </div>
                <div class="flex flex-col gap-1">
                    <h1 class="text-3xl font-bold text-zinc-900 dark:text-white">Detailed Analytics</h1>
                    <p class="text-zinc-600 dark:text-zinc-400">Comprehensive page views and visitor analytics</p>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                <div class="flex items-center gap-2">
                    <x-ui.date-picker
                        id="startDate"
                        name="start_date"
                        :value="$startDate->format('Y-m-d')"
                        format="Y-m-d"
                        size="sm"
                    />
                    <span class="text-gray-500 dark:text-gray-400 text-sm">to</span>
                    <x-ui.date-picker
                        id="endDate"
                        name="end_date"
                        :value="$endDate->format('Y-m-d')"
                        format="Y-m-d"
                        size="sm"
                    />
                    <x-button variant="primary" onclick="updateDateRange()">
                        Update
                    </x-button>
                </div>
                <x-button variant="outline-secondary" :href="route('admin.index')" icon="arrow-left">
                    Back to Dashboard
                </x-button>
            </div>
        </div>

        {{-- Overview Statistics --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <x-ui.card
                icon="eye"
                icon-color="sky"
                title="Total Page Views"
                :value="number_format($analytics['overview']->total_page_views ?? 0)"
                variant="default"
                :hover="false"
            />

            <x-ui.card
                icon="users"
                icon-color="success"
                title="Unique Visitors"
                :value="number_format($analytics['overview']->total_unique_visitors ?? 0)"
                variant="default"
                :hover="false"
            />

            <x-ui.card
                icon="file-lines"
                icon-color="primary"
                title="Unique Pages"
                :value="number_format($analytics['overview']->total_unique_pages ?? 0)"
                variant="default"
                :hover="false"
            />

            <x-ui.card
                icon="chart-simple"
                icon-color="warning"
                title="Avg Daily Views"
                :value="number_format($analytics['overview']->avg_daily_views ?? 0)"
                variant="default"
                :hover="false"
            />
        </div>

        {{-- Charts Row --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            {{-- Daily Trend Chart --}}
            <x-ui.card variant="default" :hover="false">
                <x-slot:header>
                    <h3 class="text-base/7 font-semibold text-gray-900 dark:text-white">Daily Page Views Trend</h3>
                </x-slot:header>
                <x-slot:body>
                    <div class="h-64">
                        <canvas id="dailyTrendChart"></canvas>
                    </div>
                </x-slot:body>
            </x-ui.card>

            {{-- Hourly Distribution Chart --}}
            <x-ui.card variant="default" :hover="false">
                <x-slot:header>
                    <h3 class="text-base/7 font-semibold text-gray-900 dark:text-white">Hourly Distribution ({{ $endDate->format('M d') }})</h3>
                </x-slot:header>
                <x-slot:body>
                    <div class="h-64">
                        <canvas id="hourlyChart"></canvas>
                    </div>
                </x-slot:body>
            </x-ui.card>
        </div>

        {{-- Data Tables Row --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            {{-- Popular Pages --}}
            <x-ui.card variant="default" :hover="false">
                <x-slot:header>
                    <h3 class="text-base/7 font-semibold text-gray-900 dark:text-white">Popular Pages</h3>
                </x-slot:header>
                <x-slot:body>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-white/10">
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Page</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Views</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Visitors</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                                @forelse($analytics['popular_pages'] as $page)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                        <td class="px-4 py-3">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $page->page_title ?: 'Untitled' }}</span>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $page->url }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ number_format($page->total_views) }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ number_format($page->total_unique_visitors) }}</td>
                                        <td class="px-4 py-3">
                                            <a href="{{ route('admin.analytics.url-details', base64_encode($page->url)) }}" 
                                               class="text-[var(--color-accent)] hover:text-[var(--color-accent)]/80 dark:text-[var(--color-accent)] dark:hover:text-[var(--color-accent)]/80 text-sm font-medium">
                                                View Details
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400 text-sm">
                                            No page data available for this period
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-slot:body>
            </x-ui.card>

            {{-- Browser & Device Stats --}}
            <div class="space-y-6">
                {{-- Browser Statistics --}}
                <x-ui.card variant="default" :hover="false">
                    <x-slot:header>
                        <h3 class="text-base/7 font-semibold text-gray-900 dark:text-white">Browser Statistics</h3>
                    </x-slot:header>
                    <x-slot:body>
                        @forelse($analytics['browser_stats'] as $browser)
                            <div class="mb-4 last:mb-0">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-500/20 rounded-md flex items-center justify-center">
                                            <i class="fa-solid fa-globe text-blue-600 dark:text-blue-400 text-xs"></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $browser['browser'] }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm text-gray-600 dark:text-gray-300">{{ number_format($browser['count']) }}</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">({{ $browser['percentage'] }}%)</span>
                                    </div>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-white/10 rounded-full h-2">
                                    <div class="bg-blue-600 dark:bg-blue-500 h-2 rounded-full transition-all" style="width: {{ $browser['percentage'] }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 dark:text-gray-400 text-center py-4 text-sm">No browser data available</p>
                        @endforelse
                    </x-slot:body>
                </x-ui.card>

                {{-- Device Statistics --}}
                <x-ui.card variant="default" :hover="false">
                    <x-slot:header>
                        <h3 class="text-base/7 font-semibold text-gray-900 dark:text-white">Device Statistics</h3>
                    </x-slot:header>
                    <x-slot:body>
                        @forelse($analytics['device_stats'] as $device)
                            <div class="mb-4 last:mb-0">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-green-100 dark:bg-green-500/20 rounded-md flex items-center justify-center">
                                            <i class="fa-solid fa-{{ $device['device'] === 'Mobile' ? 'mobile-alt' : 'desktop' }} text-green-600 dark:text-green-400 text-xs"></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $device['device'] }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm text-gray-600 dark:text-gray-300">{{ number_format($device['count']) }}</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">({{ $device['percentage'] }}%)</span>
                                    </div>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-white/10 rounded-full h-2">
                                    <div class="bg-green-600 dark:bg-green-500 h-2 rounded-full transition-all" style="width: {{ $device['percentage'] }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 dark:text-gray-400 text-center py-4 text-sm">No device data available</p>
                        @endforelse
                    </x-slot:body>
                </x-ui.card>
            </div>
        </div>

        {{-- Top Referrers --}}
        @if($analytics['top_referrers']->count() > 0)
            <x-ui.card variant="default" :hover="false">
                <x-slot:header>
                    <h3 class="text-base/7 font-semibold text-gray-900 dark:text-white">Top Referrers</h3>
                </x-slot:header>
                <x-slot:body>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-white/10">
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Referrer</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Visits</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                                @foreach($analytics['top_referrers'] as $referrer)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $referrer['referrer'] }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ number_format($referrer['count']) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </x-slot:body>
            </x-ui.card>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    // Detect dark mode
    const isDarkMode = document.documentElement.classList.contains('dark');
    const textColor = isDarkMode ? 'rgb(161, 161, 170)' : 'rgb(107, 114, 128)';
    const gridColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
    const borderColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';

    // Chart.js default configuration
    Chart.defaults.color = textColor;
    Chart.defaults.borderColor = borderColor;

    // Daily Trend Chart
    const dailyTrendCtx = document.getElementById('dailyTrendChart').getContext('2d');
    const dailyTrendChart = new Chart(dailyTrendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($analytics['daily_trend']->pluck('date_formatted')) !!},
            datasets: [{
                label: 'Page Views',
                data: {!! json_encode($analytics['daily_trend']->pluck('page_views')) !!},
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: isDarkMode ? 'rgba(59, 130, 246, 0.2)' : 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Unique Visitors',
                data: {!! json_encode($analytics['daily_trend']->pluck('unique_visitors')) !!},
                borderColor: 'rgb(16, 185, 129)',
                backgroundColor: isDarkMode ? 'rgba(16, 185, 129, 0.2)' : 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    grid: {
                        color: gridColor
                    },
                    ticks: {
                        color: textColor
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: gridColor
                    },
                    ticks: {
                        color: textColor
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        color: textColor
                    }
                }
            }
        }
    });

    // Hourly Distribution Chart
    const hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
    const hourlyChart = new Chart(hourlyCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(collect($analytics['hourly_distribution'])->pluck('hour_formatted')) !!},
            datasets: [{
                label: 'Page Views',
                data: {!! json_encode(collect($analytics['hourly_distribution'])->pluck('views')) !!},
                backgroundColor: isDarkMode ? 'rgba(59, 130, 246, 0.8)' : 'rgba(59, 130, 246, 0.8)',
                borderColor: 'rgb(59, 130, 246)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    grid: {
                        color: gridColor
                    },
                    ticks: {
                        color: textColor
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: gridColor
                    },
                    ticks: {
                        color: textColor
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Date range update function
    function updateDateRange() {
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');
        
        // Get value from Flatpickr instance if available, otherwise from input
        let startDate = startDateInput.value;
        let endDate = endDateInput.value;
        
        // If Flatpickr is initialized, get the formatted date from instance
        if (startDateInput._flatpickrInstance && startDateInput._flatpickrInstance.selectedDates.length > 0) {
            startDate = startDateInput._flatpickrInstance.formatDate(startDateInput._flatpickrInstance.selectedDates[0], 'Y-m-d');
        } else if (startDateInput._flatpickr && startDateInput._flatpickr.selectedDates.length > 0) {
            startDate = startDateInput._flatpickr.formatDate(startDateInput._flatpickr.selectedDates[0], 'Y-m-d');
        }
        
        if (endDateInput._flatpickrInstance && endDateInput._flatpickrInstance.selectedDates.length > 0) {
            endDate = endDateInput._flatpickrInstance.formatDate(endDateInput._flatpickrInstance.selectedDates[0], 'Y-m-d');
        } else if (endDateInput._flatpickr && endDateInput._flatpickr.selectedDates.length > 0) {
            endDate = endDateInput._flatpickr.formatDate(endDateInput._flatpickr.selectedDates[0], 'Y-m-d');
        }
        
        if (startDate && endDate) {
            const url = new URL(window.location);
            url.searchParams.set('start_date', startDate);
            url.searchParams.set('end_date', endDate);
            window.location.href = url.toString();
        }
    }

    // Update charts when dark mode changes
    document.addEventListener('darkModeToggle', function() {
        const isDark = document.documentElement.classList.contains('dark');
        const newTextColor = isDark ? 'rgb(161, 161, 170)' : 'rgb(107, 114, 128)';
        const newGridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
        
        Chart.defaults.color = newTextColor;
        Chart.defaults.borderColor = newGridColor;
        
        dailyTrendChart.options.scales.x.grid.color = newGridColor;
        dailyTrendChart.options.scales.x.ticks.color = newTextColor;
        dailyTrendChart.options.scales.y.grid.color = newGridColor;
        dailyTrendChart.options.scales.y.ticks.color = newTextColor;
        dailyTrendChart.options.plugins.legend.labels.color = newTextColor;
        dailyTrendChart.update();
        
        hourlyChart.options.scales.x.grid.color = newGridColor;
        hourlyChart.options.scales.x.ticks.color = newTextColor;
        hourlyChart.options.scales.y.grid.color = newGridColor;
        hourlyChart.options.scales.y.ticks.color = newTextColor;
        hourlyChart.update();
    });
    </script>
</x-layouts.admin>
