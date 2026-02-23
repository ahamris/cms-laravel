<div class="flex items-center">
    <div class="flex-shrink-0 h-10 w-10">
        <img class="h-10 w-10 rounded-full object-cover ring-2 ring-white dark:ring-zinc-800" src="{{ $item->avatar_url }}" alt="{{ $item->name }}">
    </div>
    <div class="ml-4">
        <div class="text-sm font-medium text-zinc-900 dark:text-white">{{ $item->name }}</div>
        <div class="text-sm text-zinc-500 dark:text-zinc-400">Sort: {{ $item->sort_order }}</div>
    </div>
</div>
