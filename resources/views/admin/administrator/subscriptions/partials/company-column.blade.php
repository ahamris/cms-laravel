<div>
    <div class="text-sm text-gray-900 dark:text-white">{{ $item->company_name ?? 'N/A' }}</div>
    @if($item->job_title ?? null)
        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $item->job_title }}</div>
    @endif
</div>

