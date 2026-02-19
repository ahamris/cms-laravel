<div class="flex items-center">
    @if($item->icon)
        <i class="{{ $item->icon }} text-lg mr-3 shrink-0" style="color: {{ $item->color }}"></i>
    @endif
    <div>
        <div class="text-sm font-medium text-zinc-900 dark:text-white flex items-center gap-2">
            {{ $item->title }}
            @if($item->is_featured)
                <x-ui.badge variant="warning" size="sm">Featured</x-ui.badge>
            @endif
        </div>
        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ $item->type_display }}</div>
    </div>
</div>
