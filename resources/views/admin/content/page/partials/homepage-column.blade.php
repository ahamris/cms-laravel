@if($item->home_page ?? false)
    <x-ui.badge variant="warning" icon="star" size="sm">Homepage</x-ui.badge>
@else
    <span class="text-zinc-400 dark:text-zinc-500 text-xs">—</span>
@endif
