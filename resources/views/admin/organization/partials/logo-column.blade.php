@if($item->logo_url)
    <img src="{{ $item->logo_url }}" alt="" class="h-8 w-8 rounded object-cover" />
@else
    <span class="text-zinc-500 dark:text-zinc-400">—</span>
@endif
