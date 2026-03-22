<x-layouts.admin title="CRM Appointments">
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-bold text-zinc-900 dark:text-white mb-2">Appointments</h1>
            <p class="text-zinc-600 dark:text-zinc-400">{{ $appointments->total() }} appointments</p>
        </div>
        <a href="{{ route('admin.crm.appointments.create') }}" class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-4 py-2 font-semibold text-white shadow-xs hover:opacity-90 transition-opacity">
            <i class="fa-solid fa-plus"></i> New Appointment
        </a>
    </div>

    {{-- Calendar Events Data (for JS calendar integration) --}}
    <div id="calendar-data" data-events="{{ $calendarEvents->toJson() }}" class="hidden"></div>

    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 overflow-hidden">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-700/50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 dark:text-zinc-400">Title</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 dark:text-zinc-400">Contact</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 dark:text-zinc-400">Type</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 dark:text-zinc-400">Date</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 dark:text-zinc-400">Status</th>
                    <th class="px-4 py-3 text-right font-medium text-zinc-500 dark:text-zinc-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse($appointments as $apt)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/30">
                        <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">{{ $apt->title }}</td>
                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">{{ $apt->contact?->organization_name ?? '—' }}</td>
                        <td class="px-4 py-3"><span class="px-2 py-0.5 rounded bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">{{ ucfirst(str_replace('_', ' ', $apt->type)) }}</span></td>
                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">{{ $apt->starts_at->format('M j, Y H:i') }}</td>
                        <td class="px-4 py-3"><span class="px-2 py-0.5 rounded bg-zinc-100 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-400">{{ ucfirst($apt->status) }}</span></td>
                        <td class="px-4 py-3 text-right">
                            @if($apt->status === 'scheduled')
                                <form action="{{ route('admin.crm.appointments.complete', $apt) }}" method="POST" class="inline">@csrf<button type="submit" class="text-green-600 hover:underline mr-2">Complete</button></form>
                            @endif
                            <a href="{{ route('admin.crm.appointments.edit', $apt) }}" class="text-[var(--color-accent)] hover:underline">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-zinc-500 dark:text-zinc-400">No appointments.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $appointments->withQueryString()->links() }}</div>
</div>
</x-layouts.admin>
