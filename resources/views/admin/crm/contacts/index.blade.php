<x-layouts.admin title="CRM Contacts">
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-bold text-zinc-900 dark:text-white mb-2">Contacts</h1>
            <p class="text-zinc-600 dark:text-zinc-400">{{ $contacts->total() }} contacts</p>
        </div>
        <a href="{{ route('admin.crm.contacts.create') }}" class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-4 py-2 font-semibold text-white shadow-xs hover:opacity-90 transition-opacity">
            <i class="fa-solid fa-plus"></i> Add Contact
        </a>
    </div>

    {{-- Funnel Tabs --}}
    <div class="flex gap-2 flex-wrap">
        <a href="{{ route('admin.crm.contacts.index') }}" class="px-3 py-1.5 rounded-md font-medium {{ !request('funnel_fase') ? 'bg-[var(--color-accent)] text-white' : 'bg-zinc-100 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300' }}">All</a>
        @foreach(['interesseer' => 'Attract', 'overtuig' => 'Convert', 'activeer' => 'Close', 'inspireer' => 'Delight'] as $key => $label)
            <a href="{{ route('admin.crm.contacts.index', ['funnel_fase' => $key]) }}" class="px-3 py-1.5 rounded-md font-medium {{ request('funnel_fase') === $key ? 'bg-[var(--color-accent)] text-white' : 'bg-zinc-100 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300' }}">{{ $label }}</a>
        @endforeach
    </div>

    {{-- Search --}}
    <form method="GET" class="flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search contacts..." class="flex-1 rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
        <button type="submit" class="px-4 py-2 rounded-md bg-zinc-100 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300 font-medium">Search</button>
    </form>

    {{-- Table --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 overflow-hidden">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-700/50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 dark:text-zinc-400">Name</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 dark:text-zinc-400">Email</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 dark:text-zinc-400">Funnel</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 dark:text-zinc-400">Score</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 dark:text-zinc-400">Deals</th>
                    <th class="px-4 py-3 text-right font-medium text-zinc-500 dark:text-zinc-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse($contacts as $contact)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/30">
                        <td class="px-4 py-3"><a href="{{ route('admin.crm.contacts.show', $contact) }}" class="font-medium text-zinc-900 dark:text-white hover:text-[var(--color-accent)]">{{ $contact->organization_name }}</a></td>
                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">{{ $contact->email ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @if($contact->funnel_fase)
                                <span class="px-2 py-0.5 rounded bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">{{ ucfirst($contact->funnel_fase) }}</span>
                            @else
                                <span class="text-zinc-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">{{ $contact->lead_score ?? 0 }}</td>
                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">{{ $contact->deals_count }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.crm.contacts.show', $contact) }}" class="text-[var(--color-accent)] hover:underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-zinc-500 dark:text-zinc-400">No contacts found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $contacts->withQueryString()->links() }}</div>
</div>
</x-layouts.admin>
