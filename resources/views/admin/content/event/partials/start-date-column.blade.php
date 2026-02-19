<div>
    <div class="font-medium text-zinc-900 dark:text-white">
        {{ $item->start_date ? $item->start_date->format('M d, Y') : '-' }}
    </div>
    @if($item->start_time)
        <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $item->start_time->format('H:i') }}</div>
    @endif
</div>
