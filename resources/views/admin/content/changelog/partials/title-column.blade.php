<div>
    <div class="font-medium text-zinc-900 dark:text-white">{{ $item->title }}</div>
    @if($item->description)
        <div class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">{{ Str::limit($item->description, 60) }}</div>
    @endif
</div>
