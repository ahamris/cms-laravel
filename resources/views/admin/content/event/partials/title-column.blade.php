<div class="max-w-xs">
    <div class="font-medium text-zinc-900 dark:text-white truncate">{{ $item->title }}</div>
    @if($item->slug)
        <code class="text-xs text-zinc-500 dark:text-zinc-400">{{ $item->slug }}</code>
    @endif
</div>
