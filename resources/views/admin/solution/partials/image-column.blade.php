@if($item->image)
    <div class="flex items-center gap-2">
        <img src="{{ Storage::url($item->image) }}" 
             alt="{{ $item->title }}" 
             class="w-12 h-12 object-cover rounded-md border border-gray-200 dark:border-white/10">
        <i class="fa-solid fa-check-circle text-green-600 dark:text-green-400 text-xs"></i>
    </div>
@else
    <div class="flex items-center gap-2">
        <div class="w-12 h-12 bg-gray-100 dark:bg-white/5 rounded-md border border-gray-200 dark:border-white/10 flex items-center justify-center">
            <i class="fa-solid fa-image text-gray-400 dark:text-gray-500 text-xs"></i>
        </div>
        <span class="text-xs text-gray-500 dark:text-gray-400">No image</span>
    </div>
@endif

