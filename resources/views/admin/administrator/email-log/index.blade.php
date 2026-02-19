<x-layouts.admin title="Email Logs">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-[var(--color-accent)] rounded-md flex items-center justify-center">
                <i class="fa-solid fa-envelope text-white text-xl"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white">Email Logs</h1>
                <p class="text-zinc-600 dark:text-gray-400">View and manage all sent emails</p>
            </div>
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
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4 mb-8">
        <x-ui.card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-[var(--color-accent)] uppercase mb-1">Total Emails</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="text-gray-300 dark:text-gray-600">
                    <i class="fas fa-envelope text-xl"></i>
                </div>
            </div>
        </x-ui.card>

        <x-ui.card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-green-600 dark:text-green-400 uppercase mb-1">Sent</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['sent'] ?? 0 }}</p>
                </div>
                <div class="text-gray-300 dark:text-gray-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
            </div>
        </x-ui.card>

        <x-ui.card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-red-600 dark:text-red-400 uppercase mb-1">Failed</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['failed'] ?? 0 }}</p>
                </div>
                <div class="text-gray-300 dark:text-gray-600">
                    <i class="fas fa-times-circle text-xl"></i>
                </div>
            </div>
        </x-ui.card>

        <x-ui.card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-yellow-600 dark:text-yellow-400 uppercase mb-1">Pending</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['pending'] ?? 0 }}</p>
                </div>
                <div class="text-gray-300 dark:text-gray-600">
                    <i class="fas fa-clock text-xl"></i>
                </div>
            </div>
        </x-ui.card>

        <x-ui.card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 uppercase mb-1">Today</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['today'] ?? 0 }}</p>
                </div>
                <div class="text-gray-300 dark:text-gray-600">
                    <i class="fas fa-calendar-day text-xl"></i>
                </div>
            </div>
        </x-ui.card>
    </div>

    {{-- Filters --}}
    <x-ui.card class="mb-8 overflow-visible">
        <div class="mb-6">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Filters</h3>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Filter email logs by various criteria</p>
        </div>
        <form method="GET" action="{{ route('admin.administrator.email-logs.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <div>
                    <x-ui.select
                        id="status"
                        name="status"
                        label="Status"
                        :value="request('status', '')"
                        :options="[
                            '' => 'All Statuses',
                            'sent' => '✓ Sent',
                            'failed' => '✗ Failed',
                            'pending' => '⏱ Pending'
                        ]"
                    />
                </div>
                <div class="md:col-span-2">
                    <x-ui.input
                        id="email"
                        name="email"
                        :value="request('email')"
                        label="Search Email"
                        placeholder="Search recipient email..."
                    />
                </div>
                <div>
                    <x-ui.select
                        id="mail_class"
                        name="mail_class"
                        label="Mail Type"
                        :value="request('mail_class', '')"
                        :options="$mailClasses"
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
            </div>
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mt-4">
                <div class="md:col-span-6">
                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">&nbsp;</label>
                    <div class="flex gap-2">
                        <x-button variant="primary" type="submit" icon="filter" class="py-3">
                            Filter
                        </x-button>
                        <x-button variant="outline-secondary" :href="route('admin.administrator.email-logs.index')" icon="redo" class="py-3">
                            Reset
                        </x-button>
                    </div>
                </div>
            </div>
        </form>
    </x-ui.card>

    {{-- Email Logs Table --}}
    <x-ui.card>
        @if($emailLogs->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-white/5 border-b border-gray-200 dark:border-white/10">
                        <tr>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Subject</th>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Recipient</th>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                            <th class="text-left py-3 px-4 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                            <th class="text-right py-3 px-4 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-white/5 divide-y divide-gray-100 dark:divide-white/10">
                        @foreach($emailLogs as $emailLog)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors duration-200">
                                <td class="py-3 px-4 whitespace-nowrap">
                                    @if($emailLog->status === 'sent')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                            <i class="fas fa-check-circle"></i>
                                            Sent
                                        </span>
                                    @elseif($emailLog->status === 'failed')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                                            <i class="fas fa-times-circle"></i>
                                            Failed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300">
                                            <i class="fas fa-clock"></i>
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $emailLog->subject }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="text-sm text-gray-900 dark:text-white">{{ $emailLog->to_email }}</div>
                                    @if($emailLog->to_name)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $emailLog->to_name }}</div>
                                    @endif
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <div class="text-xs text-gray-600 dark:text-gray-400">
                                        {{ $emailLog->mail_class ? class_basename($emailLog->mail_class) : '-' }}
                                    </div>
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <div class="text-xs text-gray-900 dark:text-white">{{ $emailLog->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $emailLog->created_at->format('H:i A') }}</div>
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('admin.administrator.email-logs.show', $emailLog) }}" 
                                           class="text-[var(--color-accent)] hover:text-[var(--color-accent)]/80 text-sm inline-flex items-center gap-1"
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                            <span>View</span>
                                        </a>
                                        <form action="{{ route('admin.administrator.email-logs.destroy', $emailLog) }}" 
                                              method="POST" 
                                              class="inline"
                                              x-data="{ confirmDelete() { return confirm('Are you sure you want to delete this email log?'); } }"
                                              @submit="if(!confirmDelete()) { $event.preventDefault(); }">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-sm inline-flex items-center gap-1"
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                                <span>Delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="p-4 border-t border-gray-200 dark:border-white/10">
                {{ $emailLogs->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-inbox text-3xl text-gray-300 dark:text-gray-600 mb-3"></i>
                <p class="text-gray-500 dark:text-gray-400 text-sm">No email logs found.</p>
            </div>
        @endif
    </x-ui.card>

</x-layouts.admin>
