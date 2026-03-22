<x-layouts.admin title="CRM Messages">
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-bold text-zinc-900 dark:text-white mb-2">Messages Inbox</h1>
            <p class="text-zinc-600 dark:text-zinc-400">Contact form submissions</p>
        </div>
    </div>

    {{-- Status Tabs --}}
    <div class="flex gap-2 flex-wrap">
        <a href="{{ route('admin.crm.messages.index') }}" class="px-3 py-1.5 rounded-md font-medium {{ !request('status') ? 'bg-[var(--color-accent)] text-white' : 'bg-zinc-100 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300' }}">All</a>
        @foreach(['new' => 'New', 'replied' => 'Replied', 'resolved' => 'Resolved'] as $k => $v)
            <a href="{{ route('admin.crm.messages.index', ['status' => $k]) }}" class="px-3 py-1.5 rounded-md font-medium {{ request('status') === $k ? 'bg-[var(--color-accent)] text-white' : 'bg-zinc-100 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300' }}">{{ $v }}</a>
        @endforeach
    </div>

    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 overflow-hidden">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-700/50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 dark:text-zinc-400">From</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 dark:text-zinc-400">Email</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 dark:text-zinc-400">Subject</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 dark:text-zinc-400">Status</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 dark:text-zinc-400">Date</th>
                    <th class="px-4 py-3 text-right font-medium text-zinc-500 dark:text-zinc-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse($messages as $msg)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/30 {{ $msg->status === 'new' ? 'font-semibold' : '' }}">
                        <td class="px-4 py-3 text-zinc-900 dark:text-white">{{ $msg->first_name }} {{ $msg->last_name }}</td>
                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">{{ $msg->email }}</td>
                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400 truncate max-w-xs">{{ $msg->reden ?? Str::limit($msg->bericht, 50) }}</td>
                        <td class="px-4 py-3"><span class="px-2 py-0.5 rounded {{ $msg->status === 'new' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-zinc-100 text-zinc-600 dark:bg-zinc-700 dark:text-zinc-400' }}">{{ ucfirst($msg->status) }}</span></td>
                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">{{ $msg->created_at->format('M j, H:i') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.crm.messages.show', $msg) }}" class="text-[var(--color-accent)] hover:underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-zinc-500 dark:text-zinc-400">No messages.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $messages->withQueryString()->links() }}</div>
</div>
</x-layouts.admin>
