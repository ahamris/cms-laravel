<div class="flex -space-x-1 overflow-hidden">
    @foreach($item->presenters->take(3) as $presenter)
        <img class="inline-block h-6 w-6 rounded-full ring-2 ring-white dark:ring-zinc-800"
             src="{{ $presenter->avatar_url }}"
             alt="{{ $presenter->name }}"
             title="{{ $presenter->name }}">
    @endforeach
    @if($item->presenters->count() > 3)
        <span class="flex items-center justify-center h-6 w-6 rounded-full bg-zinc-100 dark:bg-zinc-700 ring-2 ring-white dark:ring-zinc-800 text-xs font-medium text-zinc-500 dark:text-zinc-400">+{{ $item->presenters->count() - 3 }}</span>
    @endif
</div>
