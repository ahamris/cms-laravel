@if($item->author)
    <div class="text-xs">
        <div class="font-medium text-zinc-900 dark:text-zinc-100">{{ $item->author->name }}</div>
        <div class="text-zinc-500 dark:text-zinc-400 text-xs">{{ $item->author->email }}</div>
    </div>
@else
    <span class="text-xs text-zinc-500 dark:text-zinc-400">Unknown</span>
@endif

