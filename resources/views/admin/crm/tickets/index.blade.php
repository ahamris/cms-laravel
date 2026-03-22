<x-layouts.admin title="CRM Tickets">
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-bold text-zinc-900 dark:text-white mb-2">Support Tickets</h1>
            <p class="text-zinc-600 dark:text-zinc-400">{{ $tickets->total() }} tickets</p>
        </div>
        <a href="{{ route('admin.crm.tickets.create') }}" class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-4 py-2 font-semibold text-white shadow-xs hover:opacity-90 transition-opacity">
            <i class="fa-solid fa-plus"></i> New Ticket
        </a>
    </div>

    {{-- Status Tabs --}}
    <div class="flex gap-2 flex-wrap">
        <a href="{{ route('admin.crm.tickets.index') }}" class="px-3 py-1.5 rounded-md font-medium {{ !request('status') ? 'bg-[var(--color-accent)] text-white' : 'bg-zinc-100 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300' }}">All</a>
        @foreach(['open' => 'Open', 'in_progress' => 'In Progress', 'waiting' => 'Waiting', 'resolved' => 'Resolved'] as $k => $v)
            <a href="{{ route('admin.crm.tickets.index', ['status' => $k]) }}" class="px-3 py-1.5 rounded-md font-medium {{ request('status') === $k ? 'bg-[var(--color-accent)] text-white' : 'bg-zinc-100 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300' }}">{{ $v }}</a>
        @endforeach
    </div>

    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 overflow-hidden">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-700/50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 dark:text-zinc-400">Subject</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 dark:text-zinc-400">Contact</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 dark:text-zinc-400">Priority</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 dark:text-zinc-400">Status</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 dark:text-zinc-400">Assigned</th>
                    <th class="px-4 py-3 text-right font-medium text-zinc-500 dark:text-zinc-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse($tickets as $ticket)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/30">
                        <td class="px-4 py-3"><a href="{{ route('admin.crm.tickets.show', $ticket) }}" class="font-medium text-zinc-900 dark:text-white hover:text-[var(--color-accent)]">{{ $ticket->subject }}</a></td>
                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">{{ $ticket->contact?->organization_name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded
                                @if($ticket->priority === 'urgent') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                                @elseif($ticket->priority === 'high') bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400
                                @else bg-zinc-100 text-zinc-600 dark:bg-zinc-700 dark:text-zinc-400 @endif">{{ ucfirst($ticket->priority) }}</span>
                        </td>
                        <td class="px-4 py-3"><span class="px-2 py-0.5 rounded bg-zinc-100 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-400">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span></td>
                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">{{ $ticket->assignedTo?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-right"><a href="{{ route('admin.crm.tickets.show', $ticket) }}" class="text-[var(--color-accent)] hover:underline">View</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-zinc-500 dark:text-zinc-400">No tickets.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $tickets->withQueryString()->links() }}</div>
</div>
</x-layouts.admin>
