<div>
    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->full_name }}</div>
    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $item->email }}</div>
    @if($item->phone)
        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $item->phone }}</div>
    @endif
</div>

