<div class="flex items-center space-x-2">
    @if($item->icon)
        <i class="{{ $item->icon }} text-[var(--color-accent)] flex-shrink-0"></i>
    @endif
    <span class="font-medium text-zinc-900 dark:text-white">{{ $item->title }}</span>
</div>
