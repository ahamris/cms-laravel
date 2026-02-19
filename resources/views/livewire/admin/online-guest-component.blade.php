<div class="flex items-start gap-4" wire:poll.15s>
    <div class="w-12 h-12 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center flex-shrink-0">
        <i class="fas fa-users text-green-600 dark:text-green-400 text-xl"></i>
    </div>
    <div class="flex-1">
        <p class="font-medium text-sm text-zinc-600 dark:text-zinc-400 mb-1">Realtime Visitors</p>
        <div class="flex items-center gap-2">
            <p class="text-2xl font-semibold text-zinc-900 dark:text-white">{{ number_format($guests) }}</p>
            <div class="flex items-center space-x-1">
                <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                <span class="text-xs text-zinc-500 dark:text-zinc-400">Live</span>
            </div>
        </div>
    </div>
</div>