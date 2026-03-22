<x-layouts.admin title="CRM Deals">
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-bold text-zinc-900 dark:text-white mb-2">Deals Pipeline</h1>
            <p class="text-zinc-600 dark:text-zinc-400">{{ $deals->total() }} total deals</p>
        </div>
        <a href="{{ route('admin.crm.deals.create') }}" class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-4 py-2 font-semibold text-white shadow-xs hover:opacity-90 transition-opacity">
            <i class="fa-solid fa-plus"></i> New Deal
        </a>
    </div>

    {{-- Kanban Board --}}
    <div class="grid grid-cols-5 gap-4 overflow-x-auto">
        @foreach(['lead' => 'Lead', 'qualified' => 'Qualified', 'proposal' => 'Proposal', 'negotiation' => 'Negotiation', 'won' => 'Won'] as $stage => $label)
            <div class="min-w-[220px]">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-semibold text-zinc-700 dark:text-zinc-300">{{ $label }}</h3>
                    <span class="px-2 py-0.5 rounded-full bg-zinc-100 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-400">{{ $kanban[$stage]->count() }}</span>
                </div>
                <div class="space-y-3">
                    @foreach($kanban[$stage] as $deal)
                        <a href="{{ route('admin.crm.deals.show', $deal) }}" class="block p-4 rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 hover:shadow-md transition-shadow">
                            <div class="font-medium text-zinc-900 dark:text-white">{{ $deal->title }}</div>
                            <div class="text-zinc-500 dark:text-zinc-400 mt-1">{{ $deal->contact?->organization_name }}</div>
                            <div class="flex items-center justify-between mt-2">
                                <span class="font-bold text-zinc-900 dark:text-white">{{ $deal->formatted_value }}</span>
                                <span class="text-zinc-400">{{ $deal->probability }}%</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    {{-- Table View --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 overflow-hidden">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-700/50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 dark:text-zinc-400">Deal</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 dark:text-zinc-400">Contact</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 dark:text-zinc-400">Stage</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 dark:text-zinc-400">Value</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-500 dark:text-zinc-400">Probability</th>
                    <th class="px-4 py-3 text-right font-medium text-zinc-500 dark:text-zinc-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse($deals as $deal)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/30">
                        <td class="px-4 py-3"><a href="{{ route('admin.crm.deals.show', $deal) }}" class="font-medium text-zinc-900 dark:text-white hover:text-[var(--color-accent)]">{{ $deal->title }}</a></td>
                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">{{ $deal->contact?->organization_name }}</td>
                        <td class="px-4 py-3"><span class="px-2 py-0.5 rounded bg-zinc-100 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-400">{{ ucfirst($deal->stage) }}</span></td>
                        <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">{{ $deal->formatted_value }}</td>
                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">{{ $deal->probability }}%</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.crm.deals.show', $deal) }}" class="text-[var(--color-accent)] hover:underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-zinc-500 dark:text-zinc-400">No deals yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $deals->links() }}</div>
</div>
</x-layouts.admin>
