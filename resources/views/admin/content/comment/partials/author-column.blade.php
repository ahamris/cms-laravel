<div>
    <div class="text-sm font-medium text-gray-900 dark:text-white">
        {{ $item->user->name ?? $item->guest_name ?? 'Anonymous' }}
    </div>
    <div class="text-xs text-gray-500 dark:text-gray-400">
        {{ $item->user->email ?? $item->guest_email ?? '' }}
    </div>
</div>