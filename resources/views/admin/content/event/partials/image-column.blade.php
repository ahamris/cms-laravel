@if($item->cover_image)
    <img src="{{ Storage::url($item->cover_image) }}"
         alt="{{ $item->title }}"
         class="w-12 h-12 object-cover rounded-md">
@else
    <div class="w-12 h-12 bg-zinc-200 dark:bg-zinc-700 rounded-md flex items-center justify-center">
        <i class="fa-solid fa-calendar text-zinc-400 dark:text-zinc-500 text-sm"></i>
    </div>
@endif
