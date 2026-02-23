<div class="text-zinc-600 dark:text-zinc-400 max-w-xs truncate text-sm">
    {{ Str::limit(strip_tags($item->short_body ?? ''), 60) }}
</div>
