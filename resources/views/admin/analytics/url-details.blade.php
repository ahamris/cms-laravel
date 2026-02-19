<x-layouts.admin title="URL Analytics Details">
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">URL Analytics Details</h2>
                <p class="text-gray-600">Detailed analytics for: <code class="bg-gray-100 px-2 py-1 rounded text-sm">{{ $url }}</code></p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                    <input type="date" id="startDate" value="{{ $startDate->format('Y-m-d') }}" 
                           class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <span class="text-gray-500">to</span>
                    <input type="date" id="endDate" value="{{ $endDate->format('Y-m-d') }}" 
                           class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button onclick="updateDateRange()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Update
                    </button>
                </div>
                <a href="{{ route('admin.analytics.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                    <i class="fa-solid fa-arrow-left mr-2"></i>Back to Analytics
                </a>
            </div>
        </div>

        {{-- Summary Statistics --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-eye text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($urlAnalytics['summary']->total_views ?? 0) }}</div>
                <div class="text-sm text-gray-500">Total Views</div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-users text-green-600 text-xl"></i>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($urlAnalytics['summary']->total_unique_visitors ?? 0) }}</div>
                <div class="text-sm text-gray-500">Unique Visitors</div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-chart-line text-purple-600 text-xl"></i>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($urlAnalytics['summary']->avg_daily_views ?? 0, 1) }}</div>
                <div class="text-sm text-gray-500">Avg Daily Views</div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-calendar text-orange-600 text-xl"></i>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($urlAnalytics['summary']->active_days ?? 0) }}</div>
                <div class="text-sm text-gray-500">Active Days</div>
            </div>
        </div>

        {{-- Daily Trend Chart --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Daily Views Trend</h3>
            <div class="h-80">
                <canvas id="dailyTrendChart"></canvas>
            </div>
        </div>

        {{-- Daily Statistics Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Daily Statistics</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Page Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unique Visitors</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($urlAnalytics['daily_stats'] as $stat)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($stat['date'])->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $stat['page_title'] ?: 'Untitled' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="flex items-center">
                                        <span class="mr-2">{{ number_format($stat['views']) }}</span>
                                        <div class="w-16 bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" 
                                                 style="width: {{ $urlAnalytics['summary']->total_views > 0 ? ($stat['views'] / $urlAnalytics['summary']->total_views * 100) : 0 }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="flex items-center">
                                        <span class="mr-2">{{ number_format($stat['unique_visitors']) }}</span>
                                        <div class="w-16 bg-gray-200 rounded-full h-2">
                                            <div class="bg-green-600 h-2 rounded-full" 
                                                 style="width: {{ $urlAnalytics['summary']->total_unique_visitors > 0 ? ($stat['unique_visitors'] / $urlAnalytics['summary']->total_unique_visitors * 100) : 0 }}%"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                    No data available for this URL in the selected date range
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
// Daily Trend Chart
const dailyTrendCtx = document.getElementById('dailyTrendChart').getContext('2d');
const dailyTrendChart = new Chart(dailyTrendCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($urlAnalytics['daily_stats']->pluck('date_formatted')) !!},
        datasets: [{
            label: 'Views',
            data: {!! json_encode($urlAnalytics['daily_stats']->pluck('views')) !!},
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: true,
            pointBackgroundColor: 'rgb(59, 130, 246)',
            pointBorderColor: 'rgb(59, 130, 246)',
            pointRadius: 4,
            pointHoverRadius: 6
        }, {
            label: 'Unique Visitors',
            data: {!! json_encode($urlAnalytics['daily_stats']->pluck('unique_visitors')) !!},
            borderColor: 'rgb(16, 185, 129)',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            tension: 0.4,
            fill: false,
            pointBackgroundColor: 'rgb(16, 185, 129)',
            pointBorderColor: 'rgb(16, 185, 129)',
            pointRadius: 4,
            pointHoverRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        },
        plugins: {
            legend: {
                position: 'top',
            },
            tooltip: {
                mode: 'index',
                intersect: false,
            }
        },
        interaction: {
            mode: 'nearest',
            axis: 'x',
            intersect: false
        }
    }
});

// Date range update function
function updateDateRange() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    if (startDate && endDate) {
        const url = new URL(window.location);
        url.searchParams.set('start_date', startDate);
        url.searchParams.set('end_date', endDate);
        window.location.href = url.toString();
    }
}
</script>
    </script>
</x-layouts.admin>
