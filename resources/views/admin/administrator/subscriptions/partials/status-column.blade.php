<div class="flex flex-wrap gap-1.5">
    @php
        $statusColors = [
            'new' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
            'contacted' => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900/30 dark:text-cyan-400',
            'demo_scheduled' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
            'demo_completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
            'converted' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400',
            'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
        ];
        $statusColor = $statusColors[$item->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400';
    @endphp
    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
        {{ $item->formatted_status }}
    </span>
    @if(!$item->is_active)
        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400">
            Inactive
        </span>
    @endif
</div>

