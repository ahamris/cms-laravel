<x-layouts.admin title="Activity Logs">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-[var(--color-accent)] rounded-md flex items-center justify-center">
                <i class="fa-solid fa-list-ul text-white text-xl"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white">Activity Logs</h1>
                <p class="text-zinc-600 dark:text-gray-400">Track all admin actions and system activities</p>
            </div>
        </div>
        <div x-data="{ showCleanModal: false }">
            <x-button variant="warning" @click="showCleanModal = true" icon="broom">
                Clean Old Logs
            </x-button>

            {{-- Clean Logs Modal --}}
            <x-ui.modal alpineShow="showCleanModal" title="Clean Old Activity Logs" modalId="cleanLogsModal">
                <form method="POST" action="{{ route('admin.activity-log.clean') }}">
                    @csrf
                    <div class="mb-4">
                        <x-ui.input
                            id="days"
                            name="days"
                            type="number"
                            label="Delete logs older than (days)"
                            :value="180"
                            min="1"
                            required
                        />
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            Recommended: 180 days (6 months). This action cannot be undone.
                        </p>
                    </div>
                    
                    <div class="bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-700 text-yellow-800 dark:text-yellow-300 px-4 py-3 rounded-md mb-4">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle mt-0.5 mr-2 text-sm"></i>
                            <div>
                                <strong class="font-semibold text-sm">Warning:</strong>
                                <p class="text-xs">This will permanently delete old activity logs. This action cannot be undone.</p>
                            </div>
                        </div>
                    </div>
                    
                    <x-slot:footer>
                        <x-button variant="outline-secondary" type="button" @click="showCleanModal = false">
                            Cancel
                        </x-button>
                        <x-button variant="warning" type="submit" icon="broom">
                            Clean Logs
                        </x-button>
                    </x-slot:footer>
                </form>
            </x-ui.modal>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-lg bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fa-solid fa-check-circle text-green-500 text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-300">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
        <x-ui.card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-[var(--color-accent)] uppercase mb-1">Total Activities (30 days)</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="text-gray-300 dark:text-gray-600">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
            </div>
        </x-ui.card>

        <x-ui.card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-green-600 dark:text-green-400 uppercase mb-1">Admin Actions</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['by_user_type']['admin'] ?? 0 }}</p>
                </div>
                <div class="text-gray-300 dark:text-gray-600">
                    <i class="fas fa-user-shield text-xl"></i>
                </div>
            </div>
        </x-ui.card>

        <x-ui.card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 uppercase mb-1">System Actions</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['by_user_type']['system'] ?? 0 }}</p>
                </div>
                <div class="text-gray-300 dark:text-gray-600">
                    <i class="fas fa-cogs text-xl"></i>
                </div>
            </div>
        </x-ui.card>

        <x-ui.card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-yellow-600 dark:text-yellow-400 uppercase mb-1">Today's Activities</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['by_date'][now()->format('Y-m-d')] ?? 0 }}</p>
                </div>
                <div class="text-gray-300 dark:text-gray-600">
                    <i class="fas fa-calendar-day text-xl"></i>
                </div>
            </div>
        </x-ui.card>
    </div>

    {{-- Filters --}}
    <x-ui.card class="mb-8">
        <div class="mb-6">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Filters</h3>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Filter activity logs by various criteria</p>
        </div>
        <form method="GET" action="{{ route('admin.activity-log.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <x-ui.input
                        id="search"
                        name="search"
                        :value="request('search')"
                        label="Search"
                        placeholder="Search in description..."
                    />
                </div>
                <div>
                    <x-ui.select
                        id="user_type"
                        name="user_type"
                        label="User Type"
                        :value="request('user_type', '')"
                        :options="[
                            '' => 'All Types',
                            'admin' => 'Admin',
                            'user' => 'User',
                            'system' => 'System',
                            'customer' => 'Customer'
                        ]"
                    />
                </div>
                <div>
                    <x-ui.date-picker
                        icon="calendar-days"
                        id="date_from"
                        name="date_from"
                        label="Date From"
                        :value="request('date_from') ?? ''"
                        format="Y-m-d"
                    />
                </div>
                <div>
                    <x-ui.date-picker
                        icon="calendar-days"
                        id="date_to"
                        name="date_to"
                        label="Date To"
                        :value="request('date_to') ?? ''"
                        format="Y-m-d"
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">&nbsp;</label>
                    <div class="flex gap-2">
                        <x-button variant="primary" type="submit" icon="filter" class="py-3">
                            Filter
                        </x-button>
                        <x-button variant="outline-secondary" :href="route('admin.activity-log.index')" icon="redo" class="py-3">
                            Reset
                        </x-button>
                    </div>
                </div>
            </div>
        </form>
    </x-ui.card>

    {{-- Activity Logs Table --}}
    <x-ui.card>
        @if($activities->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-white/5 border-b border-gray-200 dark:border-white/10">
                        <tr>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-16">#</th>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date & Time</th>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</th>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</th>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Subject</th>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-white/5 divide-y divide-gray-100 dark:divide-white/10">
                        @foreach($activities as $activity)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors duration-200">
                                <td class="py-3 px-4 text-xs text-gray-900 dark:text-gray-100">{{ $activity->id }}</td>
                                <td class="py-3 px-4">
                                    <div class="text-xs text-gray-900 dark:text-gray-100">{{ $activity->performed_at->format('d-m-Y') }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $activity->performed_at->format('H:i:s') }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $activity->user_name }}</div>
                                    @if($activity->user_id)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $activity->user_id }}</div>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    @if($activity->user_type == 'admin')
                                        <span class="bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 px-2 py-0.5 rounded-full text-xs font-semibold">Admin</span>
                                    @elseif($activity->user_type == 'system')
                                        <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 px-2 py-0.5 rounded-full text-xs font-semibold">System</span>
                                    @elseif($activity->user_type == 'user')
                                        <span class="bg-[var(--color-accent)]/10 text-[var(--color-accent)] px-2 py-0.5 rounded-full text-xs font-semibold">User</span>
                                    @else
                                        <span class="bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-300 px-2 py-0.5 rounded-full text-xs font-semibold">{{ ucfirst($activity->user_type) }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-900 dark:text-gray-100">{{ $activity->description }}</td>
                                <td class="py-3 px-4">
                                    @if($activity->subject_type)
                                        <div class="text-xs text-gray-600 dark:text-gray-400">{{ class_basename($activity->subject_type) }}</div>
                                        @if($activity->subject_id)
                                            <div class="text-xs text-gray-500 dark:text-gray-500">ID: {{ $activity->subject_id }}</div>
                                        @endif
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <a href="{{ route('admin.activity-log.show', $activity) }}" 
                                       class="text-[var(--color-accent)] hover:text-[var(--color-accent)]/80 text-xs inline-flex items-center gap-1"
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                        <span>View</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="p-4 border-t border-gray-200 dark:border-white/10">
                {{ $activities->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-inbox text-3xl text-gray-300 dark:text-gray-600 mb-3"></i>
                <p class="text-gray-500 dark:text-gray-400 text-sm">No activity logs found.</p>
            </div>
        @endif
    </x-ui.card>

</x-layouts.admin>
