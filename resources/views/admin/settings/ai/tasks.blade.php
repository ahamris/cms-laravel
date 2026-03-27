<x-layouts.admin title="AI background tasks">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">AI background tasks</h1>
            <p class="text-zinc-600 dark:text-zinc-400">
                Queued jobs from <code class="text-sm bg-zinc-100 dark:bg-white/10 px-1 rounded">POST {{ url('/admin/ai/tasks') }}</code>.
                Rows in <span class="font-medium">running</span> or <span class="font-medium">pending</span> refresh every 4s.
            </p>
        </div>
        <a href="{{ route('admin.settings.ai.index') }}"
           class="inline-flex items-center justify-center px-4 py-2 rounded-lg border border-gray-200 dark:border-white/10 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-white/5 text-sm font-medium">
            <i class="fa-solid fa-arrow-left mr-2"></i> AI providers
        </a>
    </div>

    <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-white/10 text-sm">
                <thead class="bg-gray-50 dark:bg-white/5">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-900 dark:text-white">ID</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-900 dark:text-white">Type</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-900 dark:text-white">Status</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-900 dark:text-white">Provider</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-900 dark:text-white">Duration</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-900 dark:text-white">Created</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-900 dark:text-white">Error</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-white/10" id="ai-tasks-tbody">
                    @forelse ($tasks as $task)
                        <tr data-task-id="{{ $task->id }}" data-status="{{ $task->status }}"
                            class="hover:bg-gray-50/80 dark:hover:bg-white/[0.03]">
                            <td class="px-4 py-3 font-mono text-gray-600 dark:text-gray-300">{{ $task->id }}</td>
                            <td class="px-4 py-3 text-gray-900 dark:text-white">{{ $task->task_type }}</td>
                            <td class="px-4 py-3">
                                <span data-field="status" class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium
                                    @if($task->status === 'completed') bg-green-100 text-green-800 dark:bg-green-500/20 dark:text-green-300
                                    @elseif($task->status === 'failed') bg-red-100 text-red-800 dark:bg-red-500/20 dark:text-red-300
                                    @elseif($task->status === 'running') bg-amber-100 text-amber-900 dark:bg-amber-500/20 dark:text-amber-200
                                    @else bg-gray-100 text-gray-800 dark:bg-white/10 dark:text-gray-300 @endif">
                                    {{ $task->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300" data-field="provider">
                                {{ $task->provider ?: '—' }}
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300 font-mono text-xs" data-field="duration">
                                @if($task->duration_ms !== null)
                                    {{ $task->duration_ms }} ms
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                {{ $task->created_at?->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-4 py-3 text-red-600 dark:text-red-400 max-w-xs truncate" data-field="error" title="{{ $task->error_message }}">
                                {{ Str::limit($task->error_message, 48) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                No tasks yet. Queue work via the AI API async endpoint.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($tasks->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 dark:border-white/10">
                {{ $tasks->links() }}
            </div>
        @endif
    </div>

    <script>
        (function () {
            const statusUrl = (id) => '{{ url('/admin/ai/tasks') }}/' + id;
            const rows = document.querySelectorAll('#ai-tasks-tbody tr[data-task-id]');
            const poll = () => {
                rows.forEach((row) => {
                    const st = row.getAttribute('data-status');
                    if (st !== 'pending' && st !== 'running') return;
                    const id = row.getAttribute('data-task-id');
                    fetch(statusUrl(id), { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                        .then((r) => r.json())
                        .then((body) => {
                            const payload = body.data && body.data.task ? body.data.task : null;
                            if (!payload) return;
                            row.setAttribute('data-status', payload.status);
                            const badge = row.querySelector('[data-field="status"]');
                            if (badge) badge.textContent = payload.status;
                            const prov = row.querySelector('[data-field="provider"]');
                            if (prov) prov.textContent = payload.provider || '—';
                            const dur = row.querySelector('[data-field="duration"]');
                            if (dur && payload.duration_ms != null) dur.textContent = payload.duration_ms + ' ms';
                            const err = row.querySelector('[data-field="error"]');
                            if (err && payload.error_message) {
                                err.textContent = (payload.error_message.length > 48)
                                    ? payload.error_message.slice(0, 45) + '…'
                                    : payload.error_message;
                                err.setAttribute('title', payload.error_message);
                            }
                        })
                        .catch(() => {});
                });
            };
            setInterval(poll, 4000);
            poll();
        })();
    </script>
</x-layouts.admin>
