<x-layouts.admin title="Submissions: {{ $form->name }}">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-bold text-zinc-900 dark:text-white mb-2">Submissions: {{ $form->name }}</h1>
                <p class="text-zinc-600 dark:text-zinc-400">{{ $submissions->total() }} total submissions</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.form.submissions.export', $form) }}"
                    class="inline-flex items-center gap-2 rounded-md bg-zinc-100 dark:bg-zinc-700 px-4 py-2 font-semibold text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-600 transition-colors">
                    <i class="fa-solid fa-download"></i>
                    Export CSV
                </a>
                <a href="{{ route('admin.form.show', $form) }}"
                    class="inline-flex items-center gap-2 rounded-md border border-zinc-300 dark:border-zinc-600 px-4 py-2 font-semibold text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                    Back to Form
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 overflow-hidden">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-700/50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-zinc-500 dark:text-zinc-400">ID</th>
                        <th class="px-4 py-3 text-left font-medium text-zinc-500 dark:text-zinc-400">Date</th>
                        <th class="px-4 py-3 text-left font-medium text-zinc-500 dark:text-zinc-400">Status</th>
                        <th class="px-4 py-3 text-left font-medium text-zinc-500 dark:text-zinc-400">Lead Score</th>
                        <th class="px-4 py-3 text-left font-medium text-zinc-500 dark:text-zinc-400">Preview</th>
                        <th class="px-4 py-3 text-right font-medium text-zinc-500 dark:text-zinc-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($submissions as $sub)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/30">
                            <td class="px-4 py-3 text-zinc-900 dark:text-white">{{ $sub->id }}</td>
                            <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">{{ $sub->created_at->format('M j, Y H:i') }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 rounded {{ $sub->status === 'new' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-zinc-100 text-zinc-600 dark:bg-zinc-700 dark:text-zinc-400' }}">{{ ucfirst($sub->status) }}</span>
                            </td>
                            <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">{{ $sub->lead_score }}</td>
                            <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400 truncate max-w-xs">{{ collect($sub->data)->take(3)->implode(', ') }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.form.submissions.show', [$form, $sub]) }}" class="text-[var(--color-accent)] hover:underline">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-zinc-500 dark:text-zinc-400">No submissions yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $submissions->links() }}</div>
    </div>
</x-layouts.admin>
