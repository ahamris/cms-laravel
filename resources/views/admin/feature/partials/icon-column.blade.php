@if($item->icon)
    <div class="flex items-center gap-2">
        <i class="{{ $item->icon }} text-lg text-[var(--color-accent)] dark:text-[var(--color-accent)]"></i>
        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $item->icon }}</span>
    </div>
@else
    <span class="text-sm text-gray-400 dark:text-gray-500">No icon</span>
@endif

