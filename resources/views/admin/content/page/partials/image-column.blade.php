@if($item->image)
    <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700 flex-shrink-0">
        <img src="{{ Storage::url($item->image) }}"
             alt="{{ $item->title }}"
             class="w-full h-full object-cover">
    </div>
@else
    <div class="w-12 h-12 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
        <i class="fa-solid fa-image text-gray-400 dark:text-gray-500 text-xs"></i>
    </div>
@endif
