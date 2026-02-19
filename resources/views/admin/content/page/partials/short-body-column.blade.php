@if(($item->page_type ?? 'static') === 'showcase')
    <span class="text-zinc-400 dark:text-zinc-500 italic text-sm">N/A (Showcase page)</span>
@else
    <div class="text-zinc-600 dark:text-zinc-400 max-w-xs truncate text-sm">
        {{ Str::limit(strip_tags($item->short_body ?? ''), 60) }}
    </div>
@endif
