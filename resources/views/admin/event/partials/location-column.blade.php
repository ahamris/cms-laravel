<div class="max-w-xs">
    <div class="font-medium text-zinc-900 dark:text-white">{{ $item->location ?? '—' }}</div>
    @if($item->address)
        <div class="text-xs text-zinc-500 dark:text-zinc-400 truncate">{{ $item->address }}</div>
    @endif
</div>
