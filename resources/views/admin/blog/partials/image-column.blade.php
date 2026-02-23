@if($item->image)
    <img src="{{ Storage::url($item->image) }}" 
         alt="{{ $item->title }}" 
         class="w-12 h-12 object-cover rounded-md">
@else
    <div class="w-12 h-12 bg-gray-200 dark:bg-gray-700 rounded-md flex items-center justify-center">
        <i class="fa-solid fa-image text-gray-400 dark:text-gray-500 text-xs"></i>
    </div>
@endif

