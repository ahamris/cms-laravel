@if($item->blog_category)
    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
          style="background-color: {{ $item->blog_category->color }}20; color: {{ $item->blog_category->color }};">
        {{ $item->blog_category->name }}
    </span>
@else
    <span class="text-xs text-zinc-500 dark:text-zinc-400">No category</span>
@endif

